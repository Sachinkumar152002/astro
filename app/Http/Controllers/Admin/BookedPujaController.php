<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\{PujaAstrologerAssignment, AstrologerPujaMedia, Media, Astrologer, WalletTransition};
use App\{Product, Order, OrderItem};
use DB;
use File;
use PDF;

class BookedPujaController extends Controller
{
    /**
     * For group puja with multiple valid dates: which valid date does this order belong to?
     * - Date 17, time 5 PM: only families who booked on/before 17th 5 PM appear in "17" listing.
     * - Date 27, time 2 PM: families who booked after 17th 5 PM and on/before 27th 2 PM appear in "27" listing.
     * Order belongs to the first valid date D whose expiry (date+time) is >= order created_at.
     * @param \App\Order $order
     * @param \App\Product $parentProduct parent product (has valid_till_dates, expire_at)
     * @return string|null Y-m-d or null
     */
    protected function getValidDateForGroupOrder($order, $parentProduct)
    {
        if (!$order || !$parentProduct) {
            return null;
        }
        $tz = config('app.timezone', 'Asia/Kolkata');
        $orderDt = $order->created_at ? \Carbon\Carbon::parse($order->created_at)->setTimezone($tz) : null;
        $orderTs = $orderDt ? $orderDt->timestamp : 0;

        $dates = $parentProduct->valid_till_dates;
        $timePart = $parentProduct->expire_at ? \Carbon\Carbon::parse($parentProduct->expire_at)->format('H:i:s') : '23:59:59';
        if (!empty($dates) && is_array($dates)) {
            $dates = array_values(array_filter(array_map('trim', $dates), function ($d) {
                return preg_match('/^\d{4}-\d{2}-\d{2}$/', $d);
            }));
            sort($dates);
            foreach ($dates as $d) {
                $expiryTs = \Carbon\Carbon::parse($d . ' ' . $timePart, $tz)->timestamp;
                if ($orderTs <= $expiryTs) {
                    return $d;
                }
            }
            return count($dates) > 0 ? end($dates) : null;
        }
        if ($parentProduct->expire_at) {
            return \Carbon\Carbon::parse($parentProduct->expire_at)->format('Y-m-d');
        }
        return null;
    }

    /**
     * AJAX: Get media (images/videos) for a specific group puja family reference.
     */
    public function getGroupFamilyMedia(Request $request)
    {
        if(!adminCan('product', 'view')) {
            return response()->json(['success' => false, 'message' => 'No Permission'], 403);
        }

        $request->validate([
            'product_id' => 'required|integer',
            'astrologer_id' => 'nullable|integer',
            'group_puja_reference' => 'required|string'
        ]);

        $productId = (int)$request->product_id;
        $astrologerId = $request->has('astrologer_id') && $request->astrologer_id !== '' && $request->astrologer_id !== null
            ? (int)$request->astrologer_id
            : null;
        $ref = trim((string)$request->group_puja_reference);
        if ($ref === '') {
            return response()->json(['success' => true, 'images' => [], 'videos' => []], 200);
        }

        // Resolve parent product and all related IDs (parent + variations) so we fetch media
        // whether it was stored with parent_id or variation product_id
        $product = Product::find($productId);
        $parentProduct = $product && $product->parent_id && $product->parent_id > 0
            ? Product::find($product->parent_id)
            : $product;
        $allProductIds = [$productId];
        if ($parentProduct) {
            $variationIds = Product::where('parent_id', $parentProduct->id)->where('status', 1)->pluck('id')->toArray();
            $allProductIds = array_values(array_unique(array_merge([$parentProduct->id], $variationIds)));
        }

        $imagesQuery = AstrologerPujaMedia::whereIn('product_id', $allProductIds)
            ->where('type', 'image')
            ->where(function ($q) use ($ref) {
                $trimmed = trim($ref);
                $q->where('group_puja_reference', $ref)
                  ->orWhere('group_puja_reference', $trimmed)
                  ->orWhereRaw('TRIM(COALESCE(group_puja_reference,\'\')) = ?', [$trimmed]);
            });
        if ($astrologerId !== null) {
            $imagesQuery->where('astrologer_id', $astrologerId);
        }
        $images = $imagesQuery->with('media')
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function($m){
                return [
                    'id' => $m->id,
                    'is_approved' => (int)$m->is_approved,
                    'thumb' => $m->media ? $m->media->image(120,120) : null,
                    'original' => $m->media ? $m->media->originalPath : null,
                ];
            })
            ->values();

        $videosQuery = AstrologerPujaMedia::whereIn('product_id', $allProductIds)
            ->where('type', 'video')
            ->where(function ($q) use ($ref) {
                $trimmed = trim($ref);
                $q->where('group_puja_reference', $ref)
                  ->orWhere('group_puja_reference', $trimmed)
                  ->orWhereRaw('TRIM(COALESCE(group_puja_reference,\'\')) = ?', [$trimmed]);
            });
        if ($astrologerId !== null) {
            $videosQuery->where('astrologer_id', $astrologerId);
        }
        $videos = $videosQuery->with('video')
            ->orderBy('created_at', 'desc')
            ->get();

        $videoArr = [];
        foreach ($videos as $v) {
            $url = $v->video ? $v->video->originalPath : null;
            $fileOrUrl = $v->video ? $v->video->file : '';
            $isLink = $fileOrUrl && (filter_var($fileOrUrl, FILTER_VALIDATE_URL) || strpos($fileOrUrl, 'http://') === 0 || strpos($fileOrUrl, 'https://') === 0);
            $videoArr[] = [
                'id' => $v->id,
                'is_approved' => (int)$v->is_approved,
                'url' => $url,
                'is_link' => (bool)$isLink,
            ];
        }

        return response()->json([
            'success' => true,
            'images' => $images,
            'videos' => $videoArr,
            'video_links' => array_values(array_filter($videoArr, function($x) { return !empty($x['is_link']); })),
            'video_uploads' => array_values(array_filter($videoArr, function($x) { return empty($x['is_link']); })),
        ], 200);
    }
    public function index(Request $request)
    {
        if(!adminCan('product', 'view')) return dd('No Permission');

        $type = $request->type ?? 'one-to-one'; // Default to one-to-one
        // For one-to-one puja, familyRows / familyStats are not used. Ensure they are defined.
        if($type !== 'group') {
            $familyRows = collect();
            $familyStats = [
                'total' => 0,
                'completed' => 0,
                'pending' => 0,
            ];
        }

        $nav = $type == 'group' ? 'bookedPujaGroup' : 'bookedPujaOneToOne';

        $breads = [
            ['name' => 'Booked Puja'],
            ['name' => $type == 'group' ? 'Group Puja' : 'One to One Puja']
        ];

        return view('admin.booked-puja.list', compact('nav', 'breads', 'type'));
    }

    public function all(Request $request)
    {
        if(!adminCan('product', 'view')) return dd('No Permission');

        $type = $request->type ?? 'one-to-one';

        if($type == 'group') {
            // Handle Group Puja - show bookings as soon as orders are placed (independent of media uploads)
            $orderItemsQuery = OrderItem::with(['order', 'product'])
                ->where('type', 'shop')
                ->whereHas('order', function($q) {
                    $q->whereNotNull('group_puja_reference')
                      ->where('payment_status', 'Success')
                      ->where('status', 'Success');
                })
                ->whereHas('product', function($query) {
                    $query->where('type', 2)
                          ->where(function($q) {
                              $q->where('is_one_to_one_puja', '!=', 1)
                                ->orWhereNull('is_one_to_one_puja');
                })
                          ->where('status', 1);
                });

            // Filters for group puja
            if($order_number = $request->order_number) {
                $orderItemsQuery->whereHas('order', function($query) use ($order_number) {
                    $query->where('order_number', 'like', "%$order_number%");
                });
            }

            if($product_name = $request->product_name) {
                $orderItemsQuery->whereHas('product', function($query) use ($product_name) {
                    $query->where('title', 'like', "%$product_name%");
                });
            }

            if($astrologer_name = $request->astrologer_name) {
                // Filter using product->pandit_name (stored when assigning pandit)
                $orderItemsQuery->whereHas('product', function($query) use ($astrologer_name) {
                    $query->where('pandit_name', 'like', "%$astrologer_name%");
                });
            }

            if($family_reference = $request->family_reference) {
                $orderItemsQuery->whereHas('order', function($query) use ($family_reference) {
                    $query->where('group_puja_reference', 'like', "%$family_reference%");
                });
            }

            if($updated_at_from = $request->updated_at_from) {
                $orderItemsQuery->whereHas('order', function($query) use ($updated_at_from) {
                    $query->whereRaw("DATE_FORMAT(updated_at, '%Y-%m-%d') >= ?", $updated_at_from);
                });
            }

            if($updated_at_to = $request->updated_at_to) {
                $orderItemsQuery->whereHas('order', function($query) use ($updated_at_to) {
                    $query->whereRaw("DATE_FORMAT(updated_at, '%Y-%m-%d') <= ?", $updated_at_to);
                });
            }

            // Fetch all relevant order items
            $orderItems = $orderItemsQuery
                ->orderBy('created_at', $request->sort_dir ?: 'desc')
                ->get();

            // Group by (parent_product_id, valid_date) so same puja with multiple valid dates shows separate rows per date (all variations together per date)
            $grouped = $orderItems->groupBy(function ($item) {
                $order = $item->order;
                $product = $item->product;
                if (!$order || !$product) {
                    return '0|single';
                }
                $parentProduct = $product->parent_id && $product->parent_id > 0
                    ? Product::find($product->parent_id)
                    : $product;
                $parentId = $parentProduct ? $parentProduct->id : $product->id;
                $validDate = $parentProduct ? $this->getValidDateForGroupOrder($order, $parentProduct) : null;
                return $parentId . '|' . ($validDate ?? 'single');
            });

            // Build assignment-like objects: one row per (puja, valid date)
            $groupPujas = $grouped->map(function ($items) {
                /** @var \App\OrderItem $firstItem */
                $firstItem = $items->first();
                if (!$firstItem || !$firstItem->order || !$firstItem->product) {
                    return null;
                }

                $product = $firstItem->product;
                $parentProduct = $product->parent_id && $product->parent_id > 0
                    ? Product::find($product->parent_id)
                    : $product;
                if (!$parentProduct) {
                    return null;
                }

                // Valid date for this row (from group key: product_id|Y-m-d or product_id|single)
                $firstOrder = $firstItem->order;
                $validDate = $this->getValidDateForGroupOrder($firstOrder, $parentProduct);

                // Only include family refs whose order belongs to this valid date
                $familyRefs = $items->filter(function ($item) use ($parentProduct, $validDate) {
                    if (!$item->order) {
                        return false;
                    }
                    $orderDate = $this->getValidDateForGroupOrder($item->order, $parentProduct);
                    return $orderDate === $validDate;
                })
                    ->pluck('order.group_puja_reference')
                    ->filter()
                    ->unique()
                    ->values()
                    ->toArray();
                $totalBookings = count($familyRefs);

                $astrologerObj = null;
                if ($parentProduct->pandit_name) {
                    $astrologerObj = new \stdClass();
                    $astrologerObj->name = $parentProduct->pandit_name;
                    $astrologerObj->display_name = $parentProduct->pandit_name;
                }

                $createdAt = $items->min(function ($item) {
                    return $item->order ? $item->order->created_at : null;
                });
                $updatedAt = $items->max(function ($item) {
                    return $item->order ? $item->order->updated_at : null;
                });

                $mediaItem = AstrologerPujaMedia::where('product_id', $parentProduct->id)
                    ->whereNotNull('group_puja_reference')
                    ->first();

                $mockAssignment = new \stdClass();
                $mockAssignment->id = $mediaItem ? $mediaItem->id : $parentProduct->id;
                $mockAssignment->media_item = $mediaItem;
                $mockAssignment->product = $parentProduct; // row shows puja (parent) name; edit link uses parent id
                $mockAssignment->parent_product_id = $parentProduct->id;
                $mockAssignment->astrologer = $astrologerObj;
                $mockAssignment->order = null;
                $mockAssignment->group_puja_reference = implode(', ', $familyRefs);
                $mockAssignment->group_puja_references = $familyRefs;
                $mockAssignment->media_count = 0;
                $mockAssignment->total_bookings = $totalBookings;
                $mockAssignment->created_at = $createdAt;
                $mockAssignment->updated_at = $updatedAt;
                $mockAssignment->status = null;
                $mockAssignment->valid_date = $validDate; // Y-m-d for this row (multi-date puja)

                return $mockAssignment;
            })->filter()->values();

            // Manual pagination
            $page = $request->page ?? 1;
            $perPage = 10;
            $total = $groupPujas->count();
            $items = $groupPujas->slice(($page - 1) * $perPage, $perPage)->values();

            $assignments = new \Illuminate\Pagination\LengthAwarePaginator(
                $items,
                $total,
                $perPage,
                $page,
                ['path' => $request->url(), 'query' => $request->query()]
            );

            $data = [
                'rows' => view('admin.booked-puja.listrows', ['assignments' => $assignments, 'type' => $type])->render(),
                'pagination' => view('admin._partials.pagination', ['results' => $assignments])->render(),
            ];
            return response()->json($data, 200);
        } else {
            // Handle One to One Puja - existing logic
            $assignments = PujaAstrologerAssignment::with(['product', 'astrologer', 'order', 'orderItem'])
                ->withCount([
                    'media as total_images_count' => function($query) {
                        $query->where('type', 'image');
                    },
                    'media as total_videos_count' => function($query) {
                        $query->where('type', 'video');
                    }
                ])
                ->whereHas('product', function($query) {
                    $query->where('type', 2)
                          ->where('is_one_to_one_puja', 1);
                })
                ->orderBy($request->sort_by ?: 'created_at', $request->sort_dir ?: 'desc');

            // Filters
            if($order_number = $request->order_number) {
                $assignments->whereHas('order', function($query) use ($order_number) {
                    $query->where('order_number', 'like', "%$order_number%");
                });
            }

            if($product_name = $request->product_name) {
                $assignments->whereHas('product', function($query) use ($product_name) {
                    $query->where('title', 'like', "%$product_name%");
                });
            }

            if($astrologer_name = $request->astrologer_name) {
                $assignments->whereHas('astrologer', function($query) use ($astrologer_name) {
                    $query->where('name', 'like', "%$astrologer_name%")
                          ->orWhere('display_name', 'like', "%$astrologer_name%");
                });
            }

            if($status = $request->status) {
                $assignments->where('status', $status);
            }

            if($updated_at_from = $request->updated_at_from) {
                $assignments->whereRaw("DATE_FORMAT(updated_at, '%Y-%m-%d') >= ?", $updated_at_from);
            }

            if($updated_at_to = $request->updated_at_to) {
                $assignments->whereRaw("DATE_FORMAT(updated_at, '%Y-%m-%d') <= ?", $updated_at_to);
            }

            $assignments = $assignments->paginate(10);

            $data = [
                'rows' => view('admin.booked-puja.listrows', ['assignments' => $assignments, 'type' => $type])->render(),
                'pagination' => view('admin._partials.pagination', ['results' => $assignments])->render(),
            ];
            return response()->json($data, 200);
        }
    }

    public function edit(Request $request)
    {
        if(!adminCan('product', 'update')) return dd('No Permission');

        $id = $request->id;
        $type = $request->type ?? 'one-to-one';

        // Ensure familyRows and familyStats are always defined (used only for group puja UI)
        $familyRows = collect();
        $familyStats = [
            'total' => 0,
            'completed' => 0,
            'pending' => 0,
        ];

        if($type == 'group') {
            // Handle Group Puja
            // First, try to treat ID as an existing media item (old behaviour)
            $mediaItem = AstrologerPujaMedia::with(['product', 'astrologer', 'order'])
                ->whereNotNull('group_puja_reference')
                ->find($id);

            if($mediaItem) {
                // Get all successful orders for this group puja (parent + all variations)
                // IMPORTANT: Always get ALL families from orders, not just those with media
                $parentProduct = $mediaItem->product;
                $variationIds = Product::where('parent_id', $parentProduct->id)
                    ->where('status', 1)
                    ->pluck('id')
                    ->toArray();
                $allProductIds = array_merge([$parentProduct->id], $variationIds);

                $orders = Order::whereHas('orderItems', function($query) use ($allProductIds) {
                        $query->whereIn('product_id', $allProductIds)
                              ->where('type', 'shop');
                    })
                    ->where('payment_status', 'Success')
                    ->where('status', 'Success')
                    ->whereNotNull('group_puja_reference')
                    ->get();

                // Restrict to orders that belong to this valid date (multi-date puja)
                if ($request->filled('valid_date')) {
                    $validDate = trim((string) $request->valid_date);
                    if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $validDate)) {
                        $orders = $orders->filter(function ($o) use ($parentProduct, $validDate) {
                            return $this->getValidDateForGroupOrder($o, $parentProduct) === $validDate;
                        })->values();
                    }
                }

                $allFamilies = $orders->pluck('group_puja_reference')
                    ->filter()
                    ->unique()
                    ->sort()
                    ->values()
                    ->toArray();

                // If a specific product/variation was clicked in the list
                // (1 booking vs 29 bookings), restrict families to only those
                // that actually have an order item for that product_id.
                if($request->filled('product_id')) {
                    $productFilterId = (int) $request->get('product_id');
                    $ordersForProduct = Order::whereHas('orderItems', function($query) use ($productFilterId) {
                            $query->where('product_id', $productFilterId)
                                  ->where('type', 'shop');
                        })
                        ->where('payment_status', 'Success')
                        ->where('status', 'Success')
                        ->whereNotNull('group_puja_reference')
                        ->get();

                    $familyRefsForProduct = $ordersForProduct->pluck('group_puja_reference')
                        ->filter()
                        ->unique()
                        ->toArray();

                    if(!empty($familyRefsForProduct)) {
                        $allFamilies = array_values(array_intersect($allFamilies, $familyRefsForProduct));
                    }
                }

                // Get all media for this product and astrologer (for families that have media)
                $allMediaForProduct = AstrologerPujaMedia::where('product_id', $mediaItem->product_id)
                    ->where('astrologer_id', $mediaItem->astrologer_id)
                    ->whereNotNull('group_puja_reference')
                    ->get();

                // Families that already have at least one media entry (for pre-selection)
                $familiesWithMedia = $allMediaForProduct->pluck('group_puja_reference')
                    ->filter()
                    ->unique()
                    ->sort()
                    ->values()
                    ->toArray();

                // Load all media (images/videos) for ALL families (not just those with media)
                // This ensures we show media for families that have it, but still show all families
                $images = AstrologerPujaMedia::whereIn('group_puja_reference', $allFamilies)
                    ->where('product_id', $mediaItem->product_id)
                    ->where('astrologer_id', $mediaItem->astrologer_id)
                    ->where('type', 'image')
                    ->with('media')
                    ->orderBy('created_at', 'desc')
                    ->get();

                $videos = AstrologerPujaMedia::whereIn('group_puja_reference', $allFamilies)
                    ->where('product_id', $mediaItem->product_id)
                    ->where('astrologer_id', $mediaItem->astrologer_id)
                    ->where('type', 'video')
                    ->with('video')
                    ->orderBy('created_at', 'desc')
                    ->get();

                // Build family rows for UI (reference + contact/email + media status)
                $ordersByRef = $orders->filter(function($o){
                        return !empty($o->group_puja_reference);
                    })
                    ->unique('group_puja_reference')
                    ->keyBy('group_puja_reference');

                // Count all media per family (any astrologer) so refresh shows correct counts
                $productForMedia = Product::find($mediaItem->product_id);
                $parentForMedia = $productForMedia && $productForMedia->parent_id && $productForMedia->parent_id > 0
                    ? Product::find($productForMedia->parent_id)
                    : $productForMedia;
                $allProductIdsForCount = $parentForMedia
                    ? array_merge([$parentForMedia->id], Product::where('parent_id', $parentForMedia->id)->where('status', 1)->pluck('id')->toArray())
                    : [$mediaItem->product_id];
                $mediaBaseQuery = AstrologerPujaMedia::whereIn('product_id', $allProductIdsForCount)
                    ->whereNotNull('group_puja_reference');

                $imgCounts = (clone $mediaBaseQuery)
                    ->where('type', 'image')
                    ->select('group_puja_reference', DB::raw('COUNT(*) as cnt'))
                    ->groupBy('group_puja_reference')
                    ->pluck('cnt', 'group_puja_reference')
                    ->toArray();

                $videoCounts = (clone $mediaBaseQuery)
                    ->where('type', 'video')
                    ->select('group_puja_reference', DB::raw('COUNT(*) as cnt'))
                    ->groupBy('group_puja_reference')
                    ->pluck('cnt', 'group_puja_reference')
                    ->toArray();

                $lastUpdated = (clone $mediaBaseQuery)
                    ->select('group_puja_reference', DB::raw('MAX(updated_at) as last_updated'))
                    ->groupBy('group_puja_reference')
                    ->pluck('last_updated', 'group_puja_reference')
                    ->toArray();

                $photosRequired = 5;
                $familyRows = [];
                foreach($allFamilies as $ref) {
                    $orderForRef = $ordersByRef[$ref] ?? null;
                    $contactPerson = $orderForRef->billing_full_name ?? $orderForRef->full_name ?? $orderForRef->shipping_full_name ?? 'N/A';
                    $email = $orderForRef->billing_email ?? $orderForRef->email ?? $orderForRef->shipping_email ?? 'N/A';

                    // Extract gotra for this family (similar to group bookings)
                    $gotra = null;
                    if($orderForRef) {
                        $orderItemsForRef = OrderItem::where('order_id', $orderForRef->id)
                            ->whereIn('product_id', $allProductIds)
                            ->where('type', 'shop')
                            ->get();

                        foreach($orderItemsForRef as $orderItem) {
                            if($orderItem->options) {
                                $options = json_decode($orderItem->options, true);
                                if(isset($options['formdata']) && is_array($options['formdata'])) {
                                    foreach($options['formdata'] as $formField) {
                                        if(!is_array($formField)) {
                                            continue;
                                        }
                                        $itemName = isset($formField['name']) ? strtolower(trim($formField['name'])) : '';
                                        $itemLabel = isset($formField['label']) ? strtolower(trim($formField['label'])) : '';
                                        $isGotraField = false;
                                        if(!empty($itemName)) {
                                            if($itemName == 'gotra' || $itemName == 'one_gotra' || $itemName == 'gotra_name' ||
                                               strpos($itemName, 'gotra') !== false) {
                                                $isGotraField = true;
                                            }
                                        }
                                        if(!$isGotraField && !empty($itemLabel)) {
                                            if(strpos($itemLabel, 'gotra') !== false) {
                                                $isGotraField = true;
                                            }
                                        }
                                        if($isGotraField && empty($gotra)) {
                                            $gotraValue = isset($formField['value']) ? trim($formField['value']) : '';
                                            if(!empty($gotraValue)) {
                                                $gotra = $gotraValue;
                                                break 2;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }

                    $imagesCnt = (int)($imgCounts[$ref] ?? 0);
                    $videosCnt = (int)($videoCounts[$ref] ?? 0);

                    $status = ($imagesCnt >= $photosRequired && $videosCnt > 0) ? 'Completed' : 'Pending';
                    $last = $lastUpdated[$ref] ?? null;

                    $familyRows[] = [
                        'reference' => $ref,
                        'contact_person' => $contactPerson,
                        'email' => $email,
                        'gotra' => $gotra,
                        'images_count' => $imagesCnt,
                        'videos_count' => $videosCnt,
                        'photos_required' => $photosRequired,
                        'status' => $status,
                        'last_updated' => $last,
                    ];
                }

                $familyStats = [
                    'total' => count($familyRows),
                    'completed' => count(array_filter($familyRows, fn($r) => ($r['status'] ?? '') === 'Completed')),
                    'pending' => count(array_filter($familyRows, fn($r) => ($r['status'] ?? '') === 'Pending')),
                ];

                // Paginate family rows (10 per page)
                $page = (int) request()->get('page', 1);
                $perPage = 10;
                $total = count($familyRows);
                $familyRowsForPage = array_slice($familyRows, ($page - 1) * $perPage, $perPage);

                $familyRows = new \Illuminate\Pagination\LengthAwarePaginator(
                    $familyRowsForPage,
                    $total,
                    $perPage,
                    $page,
                    ['path' => request()->url(), 'query' => request()->query()]
                );

            // Create a mock assignment object for compatibility
            $assignment = new \stdClass();
            $assignment->id = $id;
                $assignment->product = $parentProduct;
            $assignment->astrologer = $mediaItem->astrologer;
            $assignment->order = $mediaItem->order;
                // Families that already have media (these should come pre-selected)
                $assignment->group_puja_reference = implode(', ', $familiesWithMedia); // Comma separated
                $assignment->group_puja_references = $familiesWithMedia; // Array for JavaScript (selected)
                // All available families for this puja (for listing in UI)
                $assignment->all_family_references = $allFamilies;
            $assignment->is_group_puja = true;
            } else {
                // Fallback: treat ID as product ID when no media exists yet
                // But if product_id parameter is provided (from eye button), use that instead
                // This ensures we get the correct product even if $id is a media item ID
                $productIdFromRequest = $request->get('product_id');
                if($productIdFromRequest) {
                    $product = Product::find($productIdFromRequest);
                } else {
                    $product = Product::find($id);
                }

                if(!$product) {
                    return redirect()->route('admin.booked-puja.index', ['type' => 'group'])
                        ->with('error', 'Group puja not found');
                }

                // Determine parent product and all related product IDs (parent + variations)
                $parentProduct = $product->parent_id && $product->parent_id > 0
                    ? Product::find($product->parent_id)
                    : $product;

                if(!$parentProduct) {
                    return redirect()->route('admin.booked-puja.index', ['type' => 'group'])
                        ->with('error', 'Group puja not found');
                }

                // Get variation IDs for this parent product
                $variationIds = Product::where('parent_id', $parentProduct->id)
                    ->where('status', 1)
                    ->pluck('id')
                    ->toArray();
                $allProductIds = array_merge([$parentProduct->id], $variationIds);

                // Use the same logic as groupBookings method (counter)
                // Always fetch orders for parent + all variations (same as counter)
                // This ensures eye button shows same data as counter
                // Using whereHas with orderItems relation to filter orders by product_id
                $orders = Order::with(['orderItems' => function($query) use ($allProductIds) {
                        $query->whereIn('product_id', $allProductIds)
                              ->where('type', 'shop');
                    }])
                    ->whereHas('orderItems', function($query) use ($allProductIds) {
                        $query->whereIn('product_id', $allProductIds)
                              ->where('type', 'shop');
                    })
                    ->where('payment_status', 'Success')
                    ->where('status', 'Success')
                    ->whereNotNull('group_puja_reference')
                    ->orderBy('group_puja_reference', 'asc')
                    ->get();

                if ($request->filled('valid_date')) {
                    $validDate = trim((string) $request->valid_date);
                    if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $validDate)) {
                        $orders = $orders->filter(function ($o) use ($parentProduct, $validDate) {
                            return $this->getValidDateForGroupOrder($o, $parentProduct) === $validDate;
                        })->values();
                    }
                }

                // Process orders exactly like groupBookings method does
                // This ensures eye button shows same data as counter
                $images = collect();
                $videos = collect();

                // Try to resolve astrologer/pandit from product (needed for uploads)
                $resolvedAstrologer = null;
                if(!empty($parentProduct->pandit_id)) {
                    $resolvedAstrologer = Astrologer::find($parentProduct->pandit_id);
                }

                $photosRequired = 5;
                $familyRows = [];

                // Process each order exactly like groupBookings does
                foreach($orders as $order) {
                    $familyRef = $order->group_puja_reference;
                    if(!$familyRef) {
                        continue;
                    }

                    // Get order items for this puja in the order (same as groupBookings)
                    $orderItems = OrderItem::where('order_id', $order->id)
                        ->whereIn('product_id', $allProductIds)
                        ->where('type', 'shop')
                        ->get();

                    $gotra = null;

                    // Extract gotra from all order items - exactly like groupBookings
                    foreach($orderItems as $orderItem) {
                        if($orderItem->options) {
                            $options = json_decode($orderItem->options, true);
                            if(isset($options['formdata']) && is_array($options['formdata'])) {
                                foreach($options['formdata'] as $formField) {
                                    if(!is_array($formField)) {
                                        continue;
                                    }

                                    // Check field name
                                    $itemName = isset($formField['name']) ? strtolower(trim($formField['name'])) : '';
                                    // Check field label
                                    $itemLabel = isset($formField['label']) ? strtolower(trim($formField['label'])) : '';

                                    // Check for gotra field - check both name and label comprehensively
                                    $isGotraField = false;
                                    if(!empty($itemName)) {
                                        if($itemName == 'gotra' || $itemName == 'one_gotra' || $itemName == 'gotra_name' ||
                                           strpos($itemName, 'gotra') !== false) {
                                            $isGotraField = true;
                                        }
                                    }
                                    if(!$isGotraField && !empty($itemLabel)) {
                                        if(strpos($itemLabel, 'gotra') !== false) {
                                            $isGotraField = true;
                                        }
                                    }

                                    // Extract gotra value - take first non-empty value found
                                    if($isGotraField && empty($gotra)) {
                                        $gotraValue = isset($formField['value']) ? trim((string)$formField['value']) : '';
                                        if(!empty($gotraValue)) {
                                            $gotra = $gotraValue;
                                            break 2; // Found gotra, break both loops
                                        }
                                    }
                                }
                            }
                        }
                    }

                    // Check if this family reference already exists in familyRows
                    $existingIndex = null;
                    foreach($familyRows as $index => $row) {
                        if($row['reference'] == $familyRef) {
                            $existingIndex = $index;
                            break;
                        }
                    }

                    $contactPerson = $order->billing_full_name ?? $order->full_name ?? $order->shipping_full_name ?? 'N/A';
                    $email = $order->billing_email ?? $order->email ?? $order->shipping_email ?? 'N/A';

                    // If family already exists, update gotra if not set
                    if($existingIndex !== null) {
                        if(empty($familyRows[$existingIndex]['gotra']) && !empty($gotra)) {
                            $familyRows[$existingIndex]['gotra'] = $gotra;
                        }
                    } else {
                        // Add new family row
                        $familyRows[] = [
                            'reference' => $familyRef,
                            'contact_person' => $contactPerson,
                            'email' => $email,
                            'gotra' => $gotra,
                            'images_count' => 0,
                            'videos_count' => 0,
                            'photos_required' => $photosRequired,
                            'status' => 'Pending',
                            'last_updated' => null,
                        ];
                    }
                }

                // Get media counts for each family reference (all astrologers so refresh shows correct counts)
                $variationIdsForCount = Product::where('parent_id', $parentProduct->id)->where('status', 1)->pluck('id')->toArray();
                $allProductIdsForCount = array_merge([$parentProduct->id], $variationIdsForCount);
                $mediaBaseQuery = AstrologerPujaMedia::whereIn('product_id', $allProductIdsForCount)
                    ->whereNotNull('group_puja_reference');

                $imgCounts = (clone $mediaBaseQuery)
                    ->where('type', 'image')
                    ->select('group_puja_reference', DB::raw('COUNT(*) as cnt'))
                    ->groupBy('group_puja_reference')
                    ->pluck('cnt', 'group_puja_reference')
                    ->toArray();

                $videoCounts = (clone $mediaBaseQuery)
                    ->where('type', 'video')
                    ->select('group_puja_reference', DB::raw('COUNT(*) as cnt'))
                    ->groupBy('group_puja_reference')
                    ->pluck('cnt', 'group_puja_reference')
                    ->toArray();

                $lastUpdated = (clone $mediaBaseQuery)
                    ->select('group_puja_reference', DB::raw('MAX(updated_at) as last_updated'))
                    ->groupBy('group_puja_reference')
                    ->pluck('last_updated', 'group_puja_reference')
                    ->toArray();

                // Update family rows with media counts
                foreach($familyRows as $index => $row) {
                    $ref = $row['reference'];
                    $imagesCnt = (int)($imgCounts[$ref] ?? 0);
                    $videosCnt = (int)($videoCounts[$ref] ?? 0);
                    $status = ($imagesCnt >= $photosRequired && $videosCnt > 0) ? 'Completed' : 'Pending';
                    $last = $lastUpdated[$ref] ?? null;

                    $familyRows[$index]['images_count'] = $imagesCnt;
                    $familyRows[$index]['videos_count'] = $videosCnt;
                    $familyRows[$index]['status'] = $status;
                    $familyRows[$index]['last_updated'] = $last;
                }

                // Sort family rows by reference (same as groupBookings)
                usort($familyRows, function($a, $b) {
                    return strcmp($a['reference'], $b['reference']);
                });

                // Extract family references from familyRows for assignment object
                $relatedFamilies = array_column($familyRows, 'reference');

                $familyStats = [
                    'total' => count($familyRows),
                    'completed' => count(array_filter($familyRows, fn($r) => ($r['status'] ?? '') === 'Completed')),
                    'pending' => count(array_filter($familyRows, fn($r) => ($r['status'] ?? '') === 'Pending')),
                ];

                // Paginate family rows (10 per page)
                $page = (int) request()->get('page', 1);
                $perPage = 10;
                $total = count($familyRows);
                $familyRowsForPage = array_slice($familyRows, ($page - 1) * $perPage, $perPage);

                $familyRows = new \Illuminate\Pagination\LengthAwarePaginator(
                    $familyRowsForPage,
                    $total,
                    $perPage,
                    $page,
                    ['path' => request()->url(), 'query' => request()->query()]
                );

                // Create a mock assignment object with basic info (no astrologer/order/media yet)
                // Here admin is uploading media for the first time, so no families are pre-selected.
                $assignment = new \stdClass();
                $assignment->id = $id;
                $assignment->product = $parentProduct;
                $assignment->astrologer = $resolvedAstrologer;
                $assignment->order = null;
                $assignment->group_puja_reference = implode(', ', $relatedFamilies);
                $assignment->group_puja_references = []; // nothing selected yet
                $assignment->all_family_references = $relatedFamilies; // all available families for listing
                $assignment->is_group_puja = true;
            }

        } else {
            // Handle One to One Puja
            $assignment = PujaAstrologerAssignment::with(['product', 'astrologer', 'order', 'orderItem', 'mahurat'])
                ->find($id);

            if(!$assignment) {
                return redirect()->route('admin.booked-puja.index', ['type' => 'one-to-one'])->with('error', 'Assignment not found');
            }

            // Get all media for this assignment
            $images = AstrologerPujaMedia::where('assignment_id', $id)
                ->where('type', 'image')
                ->with('media')
                ->orderBy('created_at', 'desc')
                ->get();

            $videos = AstrologerPujaMedia::where('assignment_id', $id)
                ->where('type', 'video')
                ->with('video')
                ->orderBy('created_at', 'desc')
                ->get();

            $assignment->is_group_puja = false;
        }

        // Get media for media manager
        $medias = Media::where('type', 'astrologer')
            ->whereNull('deleted_at')
            ->orderBy('id', 'desc')
            ->limit(50)
            ->get();

        $nav = $type == 'group' ? 'bookedPujaGroup' : 'bookedPujaOneToOne';
        $breads = [
            ['name' => 'Booked Puja', 'url' => route('admin.booked-puja.index', ['type' => $type])],
            ['name' => 'Edit']
        ];

        return view('admin.booked-puja.edit', compact('nav', 'breads', 'assignment', 'images', 'videos', 'medias', 'type', 'familyRows', 'familyStats'));
    }

    /**
     * Show all family references and member names for a given group puja (product).
     */
    public function groupBookings(Request $request, $product_id)
    {
        if(!adminCan('product', 'view')) {
            return redirect()->route('admin.booked-puja.index', ['type' => 'group'])
                ->with('error', 'No permission');
        }

        $product = Product::find($product_id);
        if(!$product) {
            return redirect()->route('admin.booked-puja.index', ['type' => 'group'])
                ->with('error', 'Puja not found');
        }

        // Determine parent product for variations
        $parentProduct = $product->parent_id && $product->parent_id > 0
            ? Product::find($product->parent_id)
            : $product;

        if(!$parentProduct) {
            return redirect()->route('admin.booked-puja.index', ['type' => 'group'])
                ->with('error', 'Puja not found');
        }

        // Collect all product IDs (parent + variations)
        $variationIds = Product::where('parent_id', $parentProduct->id)
            ->where('status', 1)
            ->pluck('id')
            ->toArray();
        $allProductIds = array_merge([$parentProduct->id], $variationIds);

        // Get all successful orders for this puja
        // Using whereHas with orderItems relation to filter orders by product_id
        // Relations: Order hasMany OrderItem (via order_id), OrderItem belongsTo Order (via order_id)
        $orders = Order::with(['orderItems' => function($query) use ($allProductIds) {
                $query->whereIn('product_id', $allProductIds)
                      ->where('type', 'shop');
            }])
            ->whereHas('orderItems', function($query) use ($allProductIds) {
                $query->whereIn('product_id', $allProductIds)
                      ->where('type', 'shop');
            })
            ->where('payment_status', 'Success')
            ->where('status', 'Success')
            ->whereNotNull('group_puja_reference')
            ->orderBy('group_puja_reference', 'asc')
            ->get();

        if ($request->filled('valid_date')) {
            $validDate = trim((string) $request->valid_date);
            if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $validDate)) {
                $orders = $orders->filter(function ($o) use ($parentProduct, $validDate) {
                    return $this->getValidDateForGroupOrder($o, $parentProduct) === $validDate;
                })->values();
            }
        }

        $families = [];
        foreach($orders as $order) {
            $familyRef = $order->group_puja_reference;
            if(!$familyRef) {
                continue;
            }

            // Get order items for this puja in the order
            $orderItems = OrderItem::where('order_id', $order->id)
                ->whereIn('product_id', $allProductIds)
                ->where('type', 'shop')
                ->get();

            $persons = [];
            $gotra = null;

            // Extract gotra from all order items - similar to how order edit page does it
            foreach($orderItems as $orderItem) {
                if($orderItem->options) {
                    $options = json_decode($orderItem->options, true);
                    if(isset($options['formdata']) && is_array($options['formdata'])) {
                        foreach($options['formdata'] as $formField) {
                            if(!is_array($formField)) {
                                continue;
                            }

                            // Check field name
                            $itemName = isset($formField['name']) ? strtolower(trim($formField['name'])) : '';
                            // Check field label
                            $itemLabel = isset($formField['label']) ? strtolower(trim($formField['label'])) : '';

                            // Check for gotra field - check both name and label comprehensively
                            $isGotraField = false;
                            if(!empty($itemName)) {
                                if($itemName == 'gotra' || $itemName == 'one_gotra' || $itemName == 'gotra_name' ||
                                   strpos($itemName, 'gotra') !== false) {
                                    $isGotraField = true;
                                }
                            }
                            if(!$isGotraField && !empty($itemLabel)) {
                                if(strpos($itemLabel, 'gotra') !== false) {
                                    $isGotraField = true;
                                }
                            }

                            // Extract gotra value - take first non-empty value found
                            if($isGotraField && empty($gotra)) {
                                $gotraValue = isset($formField['value']) ? trim($formField['value']) : '';
                                if(!empty($gotraValue)) {
                                    $gotra = $gotraValue;
                                    break 2; // Found gotra, break both loops
                                }
                            }
                        }
                    }
                }
            }

            // Second pass: Extract names and other fields
            foreach($orderItems as $orderItem) {
                if($orderItem->options) {
                    $options = json_decode($orderItem->options, true);
                    if(isset($options['formdata']) && is_array($options['formdata'])) {
                        foreach($options['formdata'] as $formField) {
                            if(!is_array($formField)) {
                                continue;
                            }

                            $itemName = isset($formField['name']) ? strtolower(trim($formField['name'])) : '';
                            $isAshirwadBox = ($itemName == 'ashirwad_box');

                            if($isAshirwadBox) {
                                continue;
                            }

                            $isNameField = false;
                            if(isset($formField['name'])) {
                                $fieldName = strtolower($formField['name']);
                                if(strpos($fieldName, 'name') !== false && strpos($fieldName, 'gotra') === false){
                                    $isNameField = true;
                                }
                            }
                            if(!$isNameField && isset($formField['label'])) {
                                $fieldLabel = strtolower($formField['label']);
                                if(strpos($fieldLabel, 'name') !== false && strpos($fieldLabel, 'gotra') === false){
                                    $isNameField = true;
                                }
                            }

                            if($isNameField && isset($formField['value']) && !empty(trim($formField['value']))) {
                                $nameValue = trim($formField['value']);
                                if(strpos(strtolower($nameValue), 'do not know') === false){
                                    $displayLabel = isset($formField['label'])
                                        ? str_replace(["P1", "P2"], ["Boy", "Girl"], $formField['label'])
                                        : 'Name';
                                    $persons[] = [
                                        'name' => $nameValue,
                                        'label' => $displayLabel,
                                    ];
                                }
                            }
                        }
                    }
                }
            }

            // Remove duplicate names
            $uniquePersons = [];
            $seenNames = [];
            foreach($persons as $person) {
                $personName = $person['name'];
                if(!in_array($personName, $seenNames)) {
                    $seenNames[] = $personName;
                    $uniquePersons[] = $person;
                }
            }
            $persons = $uniquePersons;

            if(empty($persons)) {
                $persons[] = [
                    'name' => $order->full_name ?: 'Family Member',
                    'label' => 'Name',
                ];
            }

            $families[] = [
                'reference' => $familyRef,
                'persons' => $persons,
                'gotra' => $gotra,
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'order_date' => $order->created_at ? $order->created_at->format('Y-m-d') : null,
            ];
        }

        // Manual pagination for families (10 per page)
        $page = (int) request()->get('page', 1);
        $perPage = 10;
        $total = count($families); // Total count of all families (before pagination)
        $familiesForPage = array_slice($families, ($page - 1) * $perPage, $perPage);

        $paginator = new \Illuminate\Pagination\LengthAwarePaginator(
            $familiesForPage,
            $total,
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        $nav = 'bookedPujaGroup';
        $breads = [
            ['name' => 'Booked Puja', 'url' => route('admin.booked-puja.index', ['type' => 'group'])],
            ['name' => 'Group Bookings'],
        ];

        return view('admin.booked-puja.group-bookings', [
            'nav' => $nav,
            'breads' => $breads,
            'product' => $parentProduct,
            'families' => $paginator,
            'totalBookings' => $total, // Pass total count separately
        ]);
    }

    /**
     * Download group puja bookings as PDF.
     */
    public function groupBookingsPdf(Request $request, $product_id)
    {
        if(!adminCan('product', 'view')) {
            abort(403, 'No permission');
        }

        // Reuse the same logic as groupBookings() to build $product and $families
        $product = Product::find($product_id);
        if(!$product) {
            abort(404, 'Puja not found');
        }

        $parentProduct = $product->parent_id && $product->parent_id > 0
            ? Product::find($product->parent_id)
            : $product;

        if(!$parentProduct) {
            abort(404, 'Puja not found');
        }

        $variationIds = Product::where('parent_id', $parentProduct->id)
            ->where('status', 1)
            ->pluck('id')
            ->toArray();
        $allProductIds = array_merge([$parentProduct->id], $variationIds);

        $orders = Order::whereHas('orderItems', function($query) use ($allProductIds) {
                $query->whereIn('product_id', $allProductIds)
                      ->where('type', 'shop');
            })
            ->where('payment_status', 'Success')
            ->where('status', 'Success')
            ->whereNotNull('group_puja_reference')
            ->orderBy('group_puja_reference', 'asc')
            ->get();

        $families = [];
        foreach($orders as $order) {
            $familyRef = $order->group_puja_reference;
            if(!$familyRef) {
                continue;
            }

            $orderItems = OrderItem::where('order_id', $order->id)
                ->whereIn('product_id', $allProductIds)
                ->where('type', 'shop')
                ->get();

            $persons = [];
            $gotra = null;

            // Extract gotra from all order items - similar to how order edit page does it
            foreach($orderItems as $orderItem) {
                if($orderItem->options) {
                    $options = json_decode($orderItem->options, true);
                    if(isset($options['formdata']) && is_array($options['formdata'])) {
                        foreach($options['formdata'] as $formField) {
                            if(!is_array($formField)) {
                                continue;
                            }

                            // Check field name
                            $itemName = isset($formField['name']) ? strtolower(trim($formField['name'])) : '';
                            // Check field label
                            $itemLabel = isset($formField['label']) ? strtolower(trim($formField['label'])) : '';

                            // Check for gotra field - check both name and label comprehensively
                            $isGotraField = false;
                            if(!empty($itemName)) {
                                if($itemName == 'gotra' || $itemName == 'one_gotra' || $itemName == 'gotra_name' ||
                                   strpos($itemName, 'gotra') !== false) {
                                    $isGotraField = true;
                                }
                            }
                            if(!$isGotraField && !empty($itemLabel)) {
                                if(strpos($itemLabel, 'gotra') !== false) {
                                    $isGotraField = true;
                                }
                            }

                            // Extract gotra value - take first non-empty value found
                            if($isGotraField && empty($gotra)) {
                                $gotraValue = isset($formField['value']) ? trim($formField['value']) : '';
                                if(!empty($gotraValue)) {
                                    $gotra = $gotraValue;
                                    break 2; // Found gotra, break both loops
                                }
                            }
                        }
                    }
                }
            }

            // Second pass: Extract names and other fields
            foreach($orderItems as $orderItem) {
                if($orderItem->options) {
                    $options = json_decode($orderItem->options, true);
                    if(isset($options['formdata']) && is_array($options['formdata'])) {
                        foreach($options['formdata'] as $formField) {
                            if(!is_array($formField)) {
                                continue;
                            }

                            $itemName = isset($formField['name']) ? strtolower(trim($formField['name'])) : '';
                            $isAshirwadBox = ($itemName == 'ashirwad_box');

                            if($isAshirwadBox) {
                                continue;
                            }

                            $isNameField = false;
                            if(isset($formField['name'])) {
                                $fieldName = strtolower($formField['name']);
                                if(strpos($fieldName, 'name') !== false && strpos($fieldName, 'gotra') === false){
                                    $isNameField = true;
                                }
                            }
                            if(!$isNameField && isset($formField['label'])) {
                                $fieldLabel = strtolower($formField['label']);
                                if(strpos($fieldLabel, 'name') !== false && strpos($fieldLabel, 'gotra') === false){
                                    $isNameField = true;
                                }
                            }

                            if($isNameField && isset($formField['value']) && !empty(trim($formField['value']))) {
                                $nameValue = trim($formField['value']);
                                if(strpos(strtolower($nameValue), 'do not know') === false){
                                    $displayLabel = isset($formField['label'])
                                        ? str_replace(["P1", "P2"], ["Boy", "Girl"], $formField['label'])
                                        : 'Name';
                                    $persons[] = [
                                        'name' => $nameValue,
                                        'label' => $displayLabel,
                                    ];
                                }
                            }
                        }
                    }
                }
            }

            $uniquePersons = [];
            $seenNames = [];
            foreach($persons as $person) {
                $personName = $person['name'];
                if(!in_array($personName, $seenNames)) {
                    $seenNames[] = $personName;
                    $uniquePersons[] = $person;
                }
            }
            $persons = $uniquePersons;

            if(empty($persons)) {
                $persons[] = [
                    'name' => $order->full_name ?: 'Family Member',
                    'label' => 'Name',
                ];
            }

            $families[] = [
                'reference' => $familyRef,
                'persons' => $persons,
                'gotra' => $gotra,
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'order_date' => $order->created_at ? $order->created_at->format('Y-m-d') : null,
            ];
        }

        // Build data for PDF (members like listing, not comma-separated)
        $rows = [];
        foreach($families as $family) {
            $rows[] = [
                'reference' => $family['reference'] ?? '',
                'gotra' => $family['gotra'] ?? 'N/A',
                'persons' => (!empty($family['persons']) && is_array($family['persons'])) ? $family['persons'] : [],
            ];
        }

        $astrologerName = $parentProduct->pandit_name ?? 'N/A';
        $pujaName = $parentProduct->title ?? ('Puja #'.$parentProduct->id);
        $scheduledAt = $parentProduct->expire_at ? \Carbon\Carbon::parse($parentProduct->expire_at)->format('Y-m-d H:i') : 'N/A';

        $pdf = PDF::loadView('admin.booked-puja.group-bookings-pdf', [
            'product' => $parentProduct,
            'pujaName' => $pujaName,
            'astrologerName' => $astrologerName,
            'scheduledAt' => $scheduledAt,
            'rows' => $rows,
        ])->setPaper('a4', 'portrait');

        $fileName = 'group-puja-bookings-'.$parentProduct->id.'.pdf';
        return $pdf->download($fileName);
    }

    public function bulkApproveMedia(Request $request)
    {
        if(!adminCan('product', 'update')) {
            return response()->json(['success' => false, 'message' => 'No Permission'], 403);
        }

        $request->validate([
            'is_approved' => 'required|in:0,1'
        ]);

        try {
            $query = AstrologerPujaMedia::query();

            // Check if it's group puja or one-to-one puja
            if($request->has('group_puja_references') && is_array($request->group_puja_references) && count($request->group_puja_references) > 0 &&
               $request->has('product_id') && $request->product_id) {
                // Group puja - multiple family references (astrologer_id optional: when not sent, approve all media for family)
                $productId = (int) $request->product_id;
                $product = Product::find($productId);
                $parentProduct = $product && $product->parent_id && $product->parent_id > 0 ? Product::find($product->parent_id) : $product;
                $allProductIds = $parentProduct ? array_merge([$parentProduct->id], Product::where('parent_id', $parentProduct->id)->where('status', 1)->pluck('id')->toArray()) : [$productId];
                $query->whereIn('group_puja_reference', $request->group_puja_references)
                      ->whereIn('product_id', $allProductIds);
                if ($request->filled('astrologer_id')) {
                    $query->where('astrologer_id', $request->astrologer_id);
                }
            } elseif($request->has('group_puja_reference') && $request->group_puja_reference &&
                     $request->has('product_id') && $request->product_id) {
                // Group puja - single family reference (astrologer_id optional)
                $productId = (int) $request->product_id;
                $product = Product::find($productId);
                $parentProduct = $product && $product->parent_id && $product->parent_id > 0 ? Product::find($product->parent_id) : $product;
                $allProductIds = $parentProduct ? array_merge([$parentProduct->id], Product::where('parent_id', $parentProduct->id)->where('status', 1)->pluck('id')->toArray()) : [$productId];
                $query->where('group_puja_reference', $request->group_puja_reference)
                      ->whereIn('product_id', $allProductIds);
                if ($request->filled('astrologer_id')) {
                    $query->where('astrologer_id', $request->astrologer_id);
                }
            } elseif($request->has('assignment_id') && $request->assignment_id) {
                // One to one puja
                $query->where('assignment_id', $request->assignment_id);
            } else {
                return response()->json(['success' => false, 'message' => 'Invalid parameters'], 422);
            }

            // Update all media (images and videos)
            $updated = $query->update(['is_approved' => $request->is_approved]);

            // When admin approves, notify the user(s) who booked the puja
            if ($request->is_approved == 1 && $updated > 0) {
                $usersToNotify = collect();
                if ($request->has('assignment_id') && $request->assignment_id) {
                    // One-to-one puja: get user from assignment
                    $assignment = PujaAstrologerAssignment::with(['order.user'])->find($request->assignment_id);
                    if ($assignment && $assignment->order && $assignment->order->user) {
                        $usersToNotify->push($assignment->order->user);
                    }
                } else {
                    // Group puja: get all orders (and their users) for the approved family reference(s)
                    $refs = [];
                    if ($request->has('group_puja_references') && is_array($request->group_puja_references) && count($request->group_puja_references) > 0) {
                        $refs = $request->group_puja_references;
                    } elseif ($request->has('group_puja_reference') && $request->group_puja_reference) {
                        $refs = [$request->group_puja_reference];
                    }
                    if (count($refs) > 0) {
                        $orders = Order::whereIn('group_puja_reference', $refs)
                            ->where('payment_status', 'Success')
                            ->with('user')
                            ->get();
                        foreach ($orders as $order) {
                            if ($order->user) {
                                $usersToNotify->push($order->user);
                            }
                        }
                        $usersToNotify = $usersToNotify->unique('id');
                    }
                }
                foreach ($usersToNotify as $user) {
                    try {
                        $userName = $user->name ?? 'User';
                        $user->notify(new \App\Notifications\PujaMediaApprovedNotification($userName));
                    } catch (\Exception $e) {
                        \Log::error('Error sending puja media approved notification: ' . $e->getMessage());
                    }
                }
            }

            $statusText = $request->is_approved == 1 ? 'Successfully approved' : 'Successfully not approved';
            return response()->json([
                'success' => true,
                'message' => $statusText
            ], 200);
        } catch (\Exception $e) {
            \Log::error('Error in bulk approval: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error applying bulk approval'], 500);
        }
    }

    public function deleteMedia(Request $request)
    {
        if(!adminCan('product', 'delete')) {
            return response()->json(['success' => false, 'message' => 'No Permission'], 403);
        }

        $request->validate([
            'media_id' => 'required|exists:astrologer_puja_media,id'
        ]);

        $media = AstrologerPujaMedia::find($request->media_id);
        if($media) {
            $media->delete();
            return response()->json(['success' => true, 'message' => 'Media deleted successfully'], 200);
        }

        return response()->json(['success' => false, 'message' => 'Media not found'], 404);
    }

    public function uploadMedia(Request $request)
    {
        if(!adminCan('product', 'update')) {
            return response()->json(['success' => false, 'message' => 'No Permission'], 403);
        }

        $request->validate([
            'media_type' => 'required|in:image,video',
            'media_id' => 'required|exists:media,id'
        ]);

        // Check if it's group puja or one-to-one puja
        $isGroupPuja = false;
        $assignment = null;
        $groupPujaReferences = [];

        // Check image limit for one-to-one puja
        if($request->has('assignment_id') && $request->assignment_id && $request->media_type == 'image') {
            $imageCount = AstrologerPujaMedia::where('assignment_id', $request->assignment_id)
                ->where('type', 'image')
                ->count();
            if($imageCount >= 5) {
                return response()->json(['success' => false, 'message' => 'Maximum 5 images allowed per puja'], 422);
            }
        }

        if($request->has('group_puja_references') && is_array($request->group_puja_references) && count($request->group_puja_references) > 0 &&
           $request->has('product_id') && $request->product_id &&
           $request->has('astrologer_id') && $request->astrologer_id) {
            // Group puja - multiple family references
            $isGroupPuja = true;
            $groupPujaReferences = $request->group_puja_references;
            $mediaItem = AstrologerPujaMedia::where('product_id', $request->product_id)
                ->where('astrologer_id', $request->astrologer_id)
                ->whereNotNull('group_puja_reference')
                ->first();
        } elseif($request->has('group_puja_reference') && $request->group_puja_reference &&
                 $request->has('product_id') && $request->product_id &&
                 $request->has('astrologer_id') && $request->astrologer_id) {
            // Group puja - single family reference (backward compatibility)
            $isGroupPuja = true;
            $groupPujaReferences = [$request->group_puja_reference];
            $mediaItem = AstrologerPujaMedia::where('product_id', $request->product_id)
                ->where('astrologer_id', $request->astrologer_id)
                ->whereNotNull('group_puja_reference')
                ->first();
        } elseif($request->has('assignment_id') && $request->assignment_id) {
            // One to one puja
            $assignment = PujaAstrologerAssignment::find($request->assignment_id);
            if(!$assignment) {
                return response()->json(['success' => false, 'message' => 'Assignment not found'], 404);
            }
        } else {
            return response()->json(['success' => false, 'message' => 'Invalid parameters'], 422);
        }

        try {
            $pujaMedia = new AstrologerPujaMedia();

            if($isGroupPuja) {
                // Build parent + variation product IDs (order items may use variation; media stored with parent or variation)
                $groupPujaProductId = (int) $request->product_id;
                $variationIdsForCount = Product::where('parent_id', $groupPujaProductId)->where('status', 1)->pluck('id')->toArray();
                $allProductIdsForOrder = array_merge([$groupPujaProductId], $variationIdsForCount);

                // Enforce max 5 images per family for group puja (count all media for this family, any astrologer)
                if($request->media_type == 'image') {
                    foreach($groupPujaReferences as $familyRef) {
                        $count = AstrologerPujaMedia::whereIn('product_id', $allProductIdsForOrder)
                            ->where('group_puja_reference', $familyRef)
                            ->where('type', 'image')
                            ->count();
                        if($count >= 5) {
                            return response()->json(['success' => false, 'message' => "Maximum 5 images allowed for family {$familyRef}"], 422);
                        }
                    }
                }

                // Reuse for order lookup below
                $groupPujaProductId = (int) $request->product_id;
                $variationIds = Product::where('parent_id', $groupPujaProductId)->where('status', 1)->pluck('id')->toArray();
                $allProductIdsForOrder = array_merge([$groupPujaProductId], $variationIds);

                // Group puja - save media for all family references (assignment_id & astrologer_id same as astrologer upload flow)
                foreach($groupPujaReferences as $familyRef) {
                    // If media type is video, ensure only one video per family (delete old for this family, any astrologer)
                    if($request->media_type == 'video') {
                        AstrologerPujaMedia::whereIn('product_id', $allProductIdsForOrder)
                            ->where('group_puja_reference', $familyRef)
                            ->where('type', 'video')
                            ->delete();
                    }
                    // Order for this family + product (order items may have parent or variation product_id)
                    $order = Order::where('group_puja_reference', $familyRef)
                        ->whereHas('orderItems', function ($q) use ($allProductIdsForOrder) {
                            $q->whereIn('product_id', $allProductIdsForOrder)->where('type', 'shop');
                        })
                        ->where('payment_status', 'Success')
                        ->where('status', 'Success')
                        ->first();
                    if (!$order && $mediaItem) {
                        $order = $mediaItem->order_id ? Order::find($mediaItem->order_id) : null;
                    }
                    $orderId = $order ? $order->id : null;

                    // Use same assignment & astrologer as when astrologer uploads: prefer existing assignment for this family's booking
                    $assignmentId = null;
                    $astrologerIdForFamily = $request->astrologer_id;
                    if ($order) {
                        $orderItem = OrderItem::where('order_id', $order->id)
                            ->whereIn('product_id', $allProductIdsForOrder)
                            ->where('type', 'shop')
                            ->first();
                        if ($orderItem) {
                            // Prefer existing assignment for this order (astrologer to whom this family's booking was assigned)
                            $assignment = PujaAstrologerAssignment::where('order_id', $order->id)
                                ->where('order_item_id', $orderItem->id)
                                ->where('product_id', $orderItem->product_id)
                                ->first();
                            if (!$assignment) {
                                $userId = null;
                                if (!empty($order->user_id) && \App\User::where('id', $order->user_id)->exists()) {
                                    $userId = $order->user_id;
                                }
                                $assignment = PujaAstrologerAssignment::firstOrCreate(
                                    [
                                        'order_id' => $order->id,
                                        'order_item_id' => $orderItem->id,
                                        'product_id' => $orderItem->product_id,
                                        'astrologer_id' => $astrologerIdForFamily,
                                    ],
                                    [
                                        'user_id' => $userId,
                                        'status' => 'assigned',
                                        'assigned_at' => now(),
                                    ]
                                );
                            }
                            $assignmentId = $assignment->id;
                            $astrologerIdForFamily = $assignment->astrologer_id;
                        }
                    }

                    $pujaMedia = new AstrologerPujaMedia();
                    $pujaMedia->assignment_id = $assignmentId;
                    $pujaMedia->astrologer_id = $astrologerIdForFamily;
                    $pujaMedia->product_id = $request->product_id;
                    $pujaMedia->order_id = $orderId;
                    $pujaMedia->group_puja_reference = $familyRef;
                    $pujaMedia->type = $request->media_type;

                    if($request->media_type == 'image') {
                        $pujaMedia->media_id = $request->media_id;
                    } else {
                        $pujaMedia->video_id = $request->media_id;
                        $pujaMedia->media_id = $request->media_id;
                    }

                    $pujaMedia->is_approved = 1; // Admin upload/update = auto approved
                    $pujaMedia->save();
                }

                return response()->json([
                    'success' => true,
                    'message' => 'Media uploaded successfully for all families',
                    'media' => [
                        'type' => $request->media_type,
                        'media_id' => $request->media_id,
                        'is_approved' => 1
                    ]
                ], 200);
            } else {
                // One to one puja
                $pujaMedia->assignment_id = $request->assignment_id;
                $pujaMedia->astrologer_id = $assignment->astrologer_id;
                $pujaMedia->product_id = $assignment->product_id;
                $pujaMedia->order_id = $assignment->order_id;
                $pujaMedia->group_puja_reference = null;
            }

            $pujaMedia->type = $request->media_type;

            if($request->media_type == 'image') {
                $pujaMedia->media_id = $request->media_id;
            } else {
                $pujaMedia->video_id = $request->media_id;
                $pujaMedia->media_id = $request->media_id;
            }

            $pujaMedia->is_approved = 1; // Auto-approve admin uploads
            $pujaMedia->save();

            return response()->json([
                'success' => true,
                'message' => 'Media uploaded successfully',
                'media' => [
                    'id' => $pujaMedia->id,
                    'type' => $pujaMedia->type,
                    'media_id' => $pujaMedia->media_id,
                    'is_approved' => $pujaMedia->is_approved
                ]
            ], 200);
        } catch (\Exception $e) {
            \Log::error('Error uploading media: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error uploading media'], 500);
        }
    }

    public function update(Request $request)
    {
        if(!adminCan('product', 'update')) {
            return response()->json(['success' => false, 'message' => 'No Permission'], 403);
        }

        $request->validate([
            'id' => 'required|exists:puja_astrologer_assignments,id',
            'status' => 'nullable|in:assigned,completed,cancelled',
            'notes' => 'nullable|string',
            'display_in_user_profile' => 'nullable|boolean',
            'display_in_astrologer_profile' => 'nullable|boolean',
        ]);

        try {
            // Load assignment with product relationship
            $assignment = PujaAstrologerAssignment::with('product')->find($request->id);

            if(!$assignment) {
                return response()->json(['success' => false, 'message' => 'Assignment not found'], 404);
            }

            if($request->has('status')) {
                $oldStatus = $assignment->status;
                $assignment->status = $request->status;

                // When status changes to 'completed', calculate and credit commission
                if($request->status == 'completed' && $oldStatus != 'completed' && !$assignment->commission_paid) {
                    $assignment->completed_at = now();

                    // Get order item to calculate commission
                    $orderItem = $assignment->orderItem;
                    $astrologer = $assignment->astrologer;

                    if($orderItem && $astrologer) {
                        $orderItemPrice = $orderItem->price ?? 0;

                        // Reload product to ensure we have latest data
                        $product = \App\Product::find($assignment->product_id);

                        // For one-to-one puja, use product's commission settings
                        // For group puja, use astrologer's commission settings
                        if($product && $product->is_one_to_one_puja == 1) {
                            // Use product's commission type and value
                            $commissionType = $product->commission_type ?? 'percentage';
                            $commissionValue = $product->astrologer_commission_percentage ?? 0;

                            \Log::info('One-to-One Puja Commission Calculation', [
                                'product_id' => $product->id,
                                'commission_type' => $commissionType,
                                'commission_value' => $commissionValue,
                                'order_item_price' => $orderItemPrice
                            ]);

                            if($commissionType == 'fixed') {
                                // Fixed commission - use value directly
                                $commissionAmount = (float)$commissionValue;
                            } else {
                                // Percentage commission - calculate from order item price
                                $commissionAmount = (($orderItemPrice * (float)$commissionValue) / 100);
                            }
                        } else {
                            // Group puja - use astrologer's commission settings
                            $commissionPercentage = $astrologer->commission ?? 0;
                            $commissionType = $astrologer->commission_type ?? 0; // 0 = percentage, 1 = fixed per unit

                            \Log::info('Group Puja Commission Calculation', [
                                'astrologer_id' => $astrologer->id,
                                'commission_type' => $commissionType,
                                'commission_percentage' => $commissionPercentage,
                                'order_item_price' => $orderItemPrice
                            ]);

                            if($commissionType == 1) {
                                // Fixed commission per unit
                                $commissionAmount = (float)$commissionPercentage;
                            } else {
                                // Percentage commission
                                $commissionAmount = (($orderItemPrice * (float)$commissionPercentage) / 100);
                            }
                        }

                        // Ensure commission doesn't exceed order item price
                        if($commissionAmount > $orderItemPrice) {
                            $commissionAmount = $orderItemPrice;
                        }

                        // Round commission amount to 2 decimal places
                        $commissionAmount = round($commissionAmount, 2);

                        // Astrologer gets the commission amount
                        $astrologerAmount = $commissionAmount;

                        // Save commission details
                        $assignment->commission_amount = $commissionAmount;
                        $assignment->commission_paid = 1;

                        // Credit commission amount to astrologer wallet
                        $astrologer->wallet += $astrologerAmount;
                        $astrologer->save();

                        // Create wallet transition record
                        $walletTransition = new WalletTransition();
                        $walletTransition->user_id = $astrologer->id;
                        $walletTransition->user_type = 'astrologer';
                        $walletTransition->table_id = $assignment->id;
                        $walletTransition->table_name = 'puja_astrologer_assignments';
                        $walletTransition->action = 1; // credit
                        $walletTransition->status = 1; // success
                        $walletTransition->remark = 'Puja Commission - ' . ($product ? $product->title : 'Puja');
                        $walletTransition->invoice = 0;
                        $walletTransition->amount = $astrologerAmount;
                        $walletTransition->save();

                        \Log::info('Puja commission credited', [
                            'assignment_id' => $assignment->id,
                            'astrologer_id' => $astrologer->id,
                            'product_id' => $product ? $product->id : null,
                            'is_one_to_one_puja' => $product ? $product->is_one_to_one_puja : 0,
                            'commission_type' => $product && $product->is_one_to_one_puja == 1 ? $product->commission_type : 'astrologer_settings',
                            'commission_value' => $product && $product->is_one_to_one_puja == 1 ? $product->astrologer_commission_percentage : ($astrologer->commission ?? 0),
                            'order_item_price' => $orderItemPrice,
                            'commission_amount' => $commissionAmount,
                            'astrologer_amount' => $astrologerAmount
                        ]);
                    }
                }
            }

            if($request->has('notes')) {
                // Store notes as JSON array
                $assignment->notes = $request->notes;
            }

            if($request->has('display_in_user_profile')) {
                $assignment->display_in_user_profile = $request->display_in_user_profile ? 1 : 0;
            }

            if($request->has('display_in_astrologer_profile')) {
                $assignment->display_in_astrologer_profile = $request->display_in_astrologer_profile ? 1 : 0;
            }

            $assignment->save();

            // When admin clicks Save Changes, approve all puja media (photos & videos) for this assignment
            $updatedMedia = AstrologerPujaMedia::where('assignment_id', $assignment->id)->update(['is_approved' => 1]);
            if ($updatedMedia > 0 && $assignment->order && $assignment->order->user) {
                try {
                    $assignment->order->user->notify(new \App\Notifications\PujaMediaApprovedNotification($assignment->order->user->name ?? 'User'));
                } catch (\Exception $e) {
                    \Log::error('Error sending puja media approved notification: ' . $e->getMessage());
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Puja assignment updated successfully'
            ], 200);
        } catch (\Exception $e) {
            \Log::error('Error updating puja assignment: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error updating assignment'], 500);
        }
    }

    public function uploadVideo(Request $request)
    {
        if(!adminCan('product', 'update')) {
            return response()->json(['success' => false, 'message' => 'No Permission'], 403);
        }

        // Check if this is a URL upload
        if($request->has('video_url') && $request->video_url) {
            return $this->uploadVideoFromUrl($request);
        }

        // Get file from any key (file, video_file, video, etc.) – PHP may not populate $_FILES if post_max_size/upload_max_filesize exceeded
        $file = null;
        $fieldName = null;
        $allFiles = $request->allFiles();
        foreach ($allFiles as $key => $uploadedFile) {
            if ($uploadedFile) {
                $file = is_object($uploadedFile) ? $uploadedFile : $request->file($key);
                $fieldName = $key;
                break;
            }
        }
        if (!$file && $request->hasFile('file')) {
            $file = $request->file('file');
            $fieldName = 'file';
        }
        if (!$file && $request->hasFile('video_file')) {
            $file = $request->file('video_file');
            $fieldName = 'video_file';
        }

        if (!$file) {
            $contentLength = (int) $request->header('Content-Length');
            if ($contentLength > 5 * 1024 * 1024) {
                return response()->json([
                    'success' => false,
                    'message' => 'File too large or PHP limit reached. Increase upload_max_filesize and post_max_size in php.ini (e.g. 20M) and try again.'
                ], 422);
            }
            return response()->json([
                'success' => false,
                'message' => 'No file uploaded. Please select a video file and ensure it is under 10MB.'
            ], 422);
        }

        // Check if file is valid (not rejected by PHP)
        if(!$file->isValid()) {
            $errorCode = $file->getError();
            $errorMessages = [
                UPLOAD_ERR_INI_SIZE => 'File exceeds PHP upload_max_filesize (currently 2MB). Please increase upload_max_filesize and post_max_size in php.ini to at least 12MB.',
                UPLOAD_ERR_FORM_SIZE => 'File exceeds form MAX_FILE_SIZE limit.',
                UPLOAD_ERR_PARTIAL => 'File was only partially uploaded.',
                UPLOAD_ERR_NO_FILE => 'No file was uploaded.',
                UPLOAD_ERR_NO_TMP_DIR => 'Missing temporary folder.',
                UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk.',
                UPLOAD_ERR_EXTENSION => 'A PHP extension stopped the file upload.'
            ];

            $errorMsg = $errorMessages[$errorCode] ?? 'File upload failed with error code: ' . $errorCode;

            \Log::error('Video upload - Invalid file', [
                'error_code' => $errorCode,
                'error_message' => $errorMsg,
                'file_name' => $file->getClientOriginalName(),
                'file_size' => $file->getSize()
            ]);

            return response()->json([
                'success' => false,
                'message' => $errorMsg
            ], 422);
        }

        // Validate file extension
        $allowedExtensions = ['mp4', 'mov', 'avi', 'wmv', 'flv', 'webm', 'mkv'];
        $fileExtension = strtolower($file->getClientOriginalExtension());

        if(!in_array($fileExtension, $allowedExtensions)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid file type. Allowed formats: ' . implode(', ', $allowedExtensions)
            ], 422);
        }

        // Check file size (10MB = 10240 KB)
        $maxSizeKB = 10240;
        $fileSizeKB = $file->getSize() / 1024;

        if($fileSizeKB > $maxSizeKB) {
            return response()->json([
                'success' => false,
                'message' => 'Upload 10 MB or less Video'
            ], 422);
        }

        try {
            // Validate the field that actually received the file (file or video_file)
            $request->validate([
                $fieldName => 'required|file|max:10240'
            ], [
                $fieldName . '.required' => 'Please select a video file',
                $fieldName . '.file' => 'The uploaded file is not valid',
                $fieldName . '.max' => 'Upload 10 MB or less Video'
            ]);

            // Create onetoonepuja directory in public if it doesn't exist
            $videoPath = public_path('onetoonepuja');
            if(!File::exists($videoPath)) {
                File::makeDirectory($videoPath, 0755, true);
            }

            // Generate unique filename
            $time = time();
            $originalName = $file->getClientOriginalName();
            $sanitizedName = preg_replace('/[^a-zA-Z0-9._-]/', '_', pathinfo($originalName, PATHINFO_FILENAME));
            $fileName = $time . '_' . $sanitizedName . '.' . $fileExtension;

            // Move uploaded file
            $file->move($videoPath, $fileName);

            // Create media record
            $attributes = [
                'H' => 0,
                'W' => 0,
                'file' => $fileName
            ];

            $media = new Media();
            $media->file = $fileName;
            $media->title = $file->getClientOriginalName();
            $media->caption = '';
            $media->type = 'astrologer';
            $media->folder_id = 0;
            $media->attributes = json_encode($attributes);
            $media->sizes = json_encode(['0-0']);
            $media->save();

            return response()->json([
                'success' => true,
                'media_id' => $media->id,
                'file_path' => asset('onetoonepuja/' . $fileName),
                'message' => 'Video uploaded successfully'
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Video upload validation failed', [
                'errors' => $e->errors(),
                'file_size' => $file->getSize(),
                'file_extension' => $fileExtension
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Error uploading video', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'File failed to upload. Please try again.'
            ], 500);
        }
    }

    private function uploadVideoFromUrl(Request $request)
    {
        try {
            $request->validate([
                'video_url' => 'required|url|max:500'
            ], [
                'video_url.required' => 'Please enter a video URL',
                'video_url.url' => 'Please enter a valid URL',
                'video_url.max' => 'URL is too long'
            ]);

            $videoUrl = $request->video_url;

            // Validate video file extension in URL
            $allowedExtensions = ['mp4', 'mov', 'avi', 'wmv', 'flv', 'webm', 'mkv'];
            $urlPath = parse_url($videoUrl, PHP_URL_PATH);
            $urlExtension = strtolower(pathinfo($urlPath, PATHINFO_EXTENSION));

            // Remove query parameters from extension
            $urlExtension = explode('?', $urlExtension)[0];

            if(!in_array($urlExtension, $allowedExtensions)) {
                // Warning but allow it (might be a streaming URL)
                \Log::warning('Video URL extension not in allowed list', [
                    'url' => $videoUrl,
                    'extension' => $urlExtension
                ]);
            }

            // Create media record with URL
            $attributes = [
                'H' => 0,
                'W' => 0,
                'file' => $videoUrl,
                'is_url' => true
            ];

            $media = new Media();
            $media->file = $videoUrl; // Store URL in file field
            $media->title = 'Video from URL';
            $media->caption = '';
            $media->type = 'astrologer';
            $media->folder_id = 0;
            $media->attributes = json_encode($attributes);
            $media->sizes = json_encode(['0-0']);
            $media->save();

            return response()->json([
                'success' => true,
                'media_id' => $media->id,
                'file_path' => $videoUrl,
                'message' => 'Video URL added successfully'
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Error adding video URL', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to add video URL. Please try again.'
            ], 500);
        }
    }
}
