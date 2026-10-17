@extends(themeView().'layout')

@section('title', 'Order Detail | '.config("app.name"))

@section('content')

<section class="dashboad_section">
  <div class="container">
    <div class="row">
      @include(themeview().'account._sidebar')
      <div class="col-lg-9 col-12 order_detail_page">
        <div class=" my-3 recent order_details mt-md-0">
          <div class="col-lg-12">
            <h4 style="padding-left:10px;">Order detail</h4>
              @php
                $order_status = 1;
                $order_status_name = '';
                if($order->order_status == 'delivered'){
                  $order_status = 5;
                  $order_status_name = 'Delivered';
                }elseif($order->order_status == 'dispatched'){
                  $order_status = 4;
                  $order_status_name = 'Dispatched';
                }elseif($order->order_status == 'in transit'){
                  $order_status = 3;
                  $order_status_name = 'In Transit';
                }elseif($order->order_status == 'shipped'){
                  $order_status = 2;
                  $order_status_name = 'Shipped';
                }elseif($order->order_status == 'ordered'){
                  $order_status = 1;
                  $order_status_name = 'Ordered';
                }elseif($order->order_status == 'cancel'){
                  $order_status = 6;
                  $order_status_name = 'Cancelled';
                }
                $orderItems = $order->orderItems;
                $fristOrderItem = $orderItems->first();
                $showTrackingStatus=false;
              @endphp
            <div class="d-md-flex justify-content-between align-items-center">
              <div class="d-md-flex align-items-center k_order">
                <h6 class="m-0 mb-2" style="padding-left: 10px">Ordered on {{date('d-m-Y', strtotime($order->created_at))}}</h6>
                <h6 class="m-0 ms-0 mb-2 ms-md-2" style="padding-left: 10px">#{{$order->order_number}}</h6>
              </div>
              <div class="mt-2 mt-md-0">
                @if($pdf_url)
                   <a href="{{ $pdf_url }}" target="_blank" class="main_button mb-3 px-3 py-3 k_pdf text-black">PDF Download</a>

                @endif
                  @if($order->invoice)
                    <a href="{{route('user.invoice', ['order', $order->id])}}" target="_blank" class="main_button mb-3 px-5 py-3"> Invoice </a>
                  @endif
                  @if($order->item_type==0)
                    @php
                      $orderRequests = $order->requests;
                    @endphp
                    @if($orderRequests->where('request_type', 'cancel')->where('status', 0)->count())
                      <button type="button" class="main_button mb-3 px-3 py-3 ms-2">Cancel request pending</button>
                    @elseif($orderRequests->where('request_type', 'return')->where('status', 0)->count())
                      <button type="button" class="main_button mb-3 px-2 py-3 mx-2">Return request pending</button>
                    @elseif($order_status==1)
                      @php
                        $showTrackingStatus=true;
                      @endphp
                      {{--<button type="button" class="main_button mb-3 px-4" data-bs-toggle="modal" id="check-status-btn" data-bs-target="#trackOrderStatusModal">Check Status</button>--}}
                      @if($order_status==1 && !orderRequest($order->user_id, $order->id, 'cancel', [1,2]))
                        <button type="button" class="main_button mb-3 px-5" onclick="openModel('{{$order->id}}', 'cancel')">Cancel</button>
                      @endif
                    @elseif($order_status==5)
                      @php
                        $delivery_date = time()+(7*24*60*60);
                        if($order->delivery_date){
                          $delivery_date = strtotime($order->delivery_date)+(7*24*60*60);
                        }
                        $showTrackingStatus=true;
                      @endphp
                      {{--<button type="button" class="main_button mb-3 px-4" data-bs-toggle="modal" id="check-status-btn" data-bs-target="#trackOrderStatusModal">Check Status</button>--}}
                      @if(!orderRequest($order->user_id, $order->id, 'return', [1,2]) && time() < $delivery_date)
                        <button type="button" class="main_button mb-3 px-2 py-3 mx-2" onclick="openModel('{{$order->id}}', 'return')">Return</button>
                      @endif
                    @else
                      @if($order_status!=6)
                        @php
                          $showTrackingStatus=true;
                        @endphp
                        {{--<button type="button" class="main_button mb-3 px-4" data-bs-toggle="modal" id="check-status-btn" data-bs-target="#trackOrderStatusModal">Check Status</button>--}}
                      @else
                        <button type="button" class="main_button mb-3 px-4">{{$order_status_name}}</button>
                      @endif
                    @endif
                  @endif
              </div>
            </div>
            <div class="order-details card border-n border-0 my-3 mt-4">
            <div class="card-body px-4 py-4 mt-3">
              <div class="row g-4">
                @if($showTrackingStatus)
                  <div class="col-12">
                    <div class="row">
                      <div class="col-12 col-md-12 hh-grayBox border-n radius">
                        <div class="order-track-container">
                        <div class="row justify-content-between track-r">
                          <div class="order-tracking {{$order_status>=1?'completed':''}}">
                            <span class="is-complete"></span>
                            <p>Ordered <br>
                            <span>{{getOrderStatus($order->id, 'ordered')}}</span>
                            </p>
                          </div>
                          <div class="order-tracking {{$order_status>=2?'completed':''}}">
                            <span class="is-complete"></span>
                            <p>Shipped <br>
                            <span>{{getOrderStatus($order->id, 'shipped')}}</span>
                            </p>
                          </div>
                          <div class="order-tracking {{$order_status>=3?'completed':''}}">
                            <span class="is-complete"></span>
                            <p>In-Transit <br>
                            <span>{{getOrderStatus($order->id, 'in transit')}}</span>
                            </p>
                          </div>
                          <div class="order-tracking {{$order_status>=4?'completed':''}}">
                            <span class="is-complete"></span>
                            <p>Dispatched <br>
                            <span>{{getOrderStatus($order->id, 'dispatched')}}</span>
                            </p>
                          </div>
                          <div class="order-tracking {{$order_status>=5?'completed':''}}">
                            <span class="is-complete"></span>
                            <p>Delivered <br>
                            <span>{{getOrderStatus($order->id, 'delivered')}}</span>
                            </p>
                          </div>
                        </div>
                      </div>
                      </div>
                    </div>
                  </div>
                @endif
                <div class="col-md-4">
                  <h6 class="section-title"><i class="bi bi-receipt me-2"></i> Billing Address</h6>
                  <p class="mb-1 fw-semibold">{{ $order->billing_full_name }}</p>
                  <p class="mb-1"><i class="bi bi-envelope me-2 text-muted"></i>{{ $order->billing_email }}</p>
                  <p class="mb-1"><i class="bi bi-telephone me-2 text-muted"></i>{{ $order->billing_phone_number }}</p>
                  <p class="mb-0"><i class="bi bi-geo-alt me-2 text-muted"></i>{{ $order->billing_address }} - {{ $order->billing_city }}, {{ $order->billing_state }} - {{ $order->billing_country }} ({{ $order->billing_zipcode }})</p>
                </div>
                @if($order->item_type == 0)
                  <div class="col-md-4">
                    <h6 class="section-title"><i class="bi bi-receipt me-2"></i> Shipping Address</h6>
                    <p class="mb-1 fw-semibold">{{ $order->full_name }}</p>
                    <p class="mb-1"><i class="bi bi-envelope me-2 text-muted"></i>{{ $order->email }}</p>
                    <p class="mb-1"><i class="bi bi-telephone me-2 text-muted"></i>{{ $order->phone_number }}</p>
                    <p class="mb-0"><i class="bi bi-geo-alt me-2 text-muted"></i>{{ $order->address }} - {{ $order->city }}, {{ $order->state }} - {{ $order->country }} ({{ $order->zipcode }})</p>
                  </div>
                @endif

                @php
                  $optinsArray = json_decode($fristOrderItem->options, true);
                  // Check if this is a one-to-one puja order
                  $isOneToOnePuja = false;
                  if($fristOrderItem && $fristOrderItem->product) {
                    $isOneToOnePuja = ($fristOrderItem->product->is_one_to_one_puja == 1);
                  }
                @endphp
                @if(count($optinsArray) > 0)
                  <div class="col-md-4">
                    <h6 class="section-title"><i class="bi bi-receipt me-2"></i> Detail</h6>
                    @if($order->item_type == 1)
                      @php
                        // Get ashirwad_box value - check both order table and formdata
                        $ashirwadBoxValue = 0;
                        if(isset($order->ashirwad_box) && $order->ashirwad_box !== null) {
                          $ashirwadBoxValue = (int)$order->ashirwad_box;
                        } elseif(isset($optinsArray["formdata"])) {
                          // Fallback: check formdata if order table doesn't have it
                          $formDataArray = $optinsArray["formdata"];
                          foreach($formDataArray as $formField) {
                            if(isset($formField['name']) && $formField['name'] === 'ashirwad_box') {
                              $ashirwadBoxValue = isset($formField['value']) ? (int)$formField['value'] : 0;
                              break;
                            }
                          }
                        }
                        $ashirwadBoxText = $ashirwadBoxValue == 1 ? 'True' : 'False';
                      @endphp
                      <p class="mb-0"><strong>Ashirwad box:</strong> {{ $ashirwadBoxText }}</p>
                    @endif
                    @if( isset($optinsArray["formdata"]) )
                      @php
                        $formDataArray = $optinsArray["formdata"];
                        $gotraValue = "";
                        $gotraLabel = "Gotra";
                        $gotraFound = false;
                      @endphp
                      @foreach($formDataArray as $index => $item)
                        @php
                          // Check if this is gotra field - handle multiple possible formats
                          $itemName = isset($item["name"]) ? strtolower(trim($item["name"])) : "";
                          $isGotraField = false;
                          if($itemName == "gotra" || $itemName == "one_gotra" || (isset($item["label"]) && stripos(strtolower(trim($item["label"])), "gotra") !== false)) {
                            $isGotraField = true;
                          }
                        @endphp
                        @if($isGotraField && isset($item["value"]))
                          @php
                            $gotraFound = true;
                            $gotraValue = trim($item["value"]);
                            $gotraLabel = isset($item["label"]) && !empty(trim($item["label"])) ? $item["label"] : "Gotra";
                            // If gotra is "I do not know my gotra" or empty, display it
                            if(empty($gotraValue) || $gotraValue == "I do not know my gotra"){
                              $gotraValue = "I do not know my gotra";
                            }
                          @endphp
                        @endif
                      @endforeach
                      @foreach($formDataArray as $index => $item)
                        @php
                          $itemName = isset($item["name"]) ? strtolower(trim($item["name"])) : "";
                          $isGotraField = false;
                          if($itemName == "gotra" || $itemName == "one_gotra" || (isset($item["label"]) && stripos(strtolower(trim($item["label"])), "gotra") !== false)) {
                            $isGotraField = true;
                          }
                        @endphp
                        @if(isset($item["value"]) && isset($item["name"]) && $item["name"] !== 'ashirwad_box' && !$isGotraField)
                          <p class="mb-0">{{ str_replace(["P1", "P2"], ["Boy", "Girl"], $item["label"]) }}: {{ $item["value"] }}</p>
                        @endif
                      @endforeach
                      @if($gotraFound && !empty($gotraValue))
                        <p class="mb-0"><strong>{{ $gotraLabel }}:</strong> {{ $gotraValue }}</p>
                      @endif
                    @endif
                    @if( isset($optinsArray["place"]) )
                      <p class="mb-0">Place: {{ $optinsArray["place"] }}</p>
                    @endif
                    @php
                      $startAtSeconds = strtotime("now");
                    @endphp
                    @if( isset($optinsArray["start_at"]) && !$isOneToOnePuja )
                      @php
                        $startAtSeconds = strtotime($optinsArray["start_at"]);
                        $isPujaComplete = $startAtSeconds < strtotime("now");
                      @endphp
                      <p class="mb-0">Start at: {{ date("D, d M Y h:i a", $startAtSeconds) }}</p>
                    @elseif( isset($optinsArray["start_at"]) && $isOneToOnePuja )
                      @php
                        $startAtSeconds = strtotime($optinsArray["start_at"]);
                        if (isset($pujaScheduleDatetime) && $pujaScheduleDatetime) {
                          $startAtSeconds = $pujaScheduleDatetime->timestamp;
                        }
                        $isPujaComplete = isset($pujaStatusCompleted) && $pujaStatusCompleted;
                      @endphp
                      @if(isset($pujaScheduleDatetime) && $pujaScheduleDatetime)
                        <p class="mb-0">Start at: {{ $pujaScheduleDatetime->format('D, d M Y h:i a') }}</p>
                      @endif
                    @else
                      @php
                        $isPujaComplete = isset($pujaStatusCompleted) && $pujaStatusCompleted;
                        $startAtSeconds = isset($pujaScheduleDatetime) && $pujaScheduleDatetime ? $pujaScheduleDatetime->timestamp : strtotime("now");
                        if (!$startAtSeconds || $startAtSeconds === strtotime("now")) {
                          $startAtSeconds = isset($optinsArray["start_at"]) ? strtotime($optinsArray["start_at"]) : strtotime("now");
                        }
                      @endphp
                    @endif
                    @if($isOneToOnePuja && isset($pujaStatusCompleted) && $pujaStatusCompleted)
                      <p class="mb-0"><strong>Status:</strong> <span class="text-success">Success</span></p>
                    @elseif(!$isPujaComplete && isset($optinsArray["video_call_url"]) && $isOneToOnePuja)
                      @php
                        $scheduleSeconds = isset($pujaScheduleDatetime) && $pujaScheduleDatetime ? $pujaScheduleDatetime->timestamp : (isset($optinsArray["start_at"]) ? strtotime($optinsArray["start_at"]) : 0);
                        $nowSeconds = strtotime("now");
                        $schedulePast = $scheduleSeconds > 0 && date("Y-m-d", $scheduleSeconds) < date("Y-m-d");
                        $within15Min = $scheduleSeconds > 0 && ($scheduleSeconds - (15 * 60)) <= $nowSeconds;
                      @endphp
                      <p class="mb-0 k_pdf d-flex align-items-center flex-wrap gap-2">
                        <span><strong>Video Url :</strong></span>
                        @if($scheduleSeconds <= 0)
                          <span class="text-muted">Link will be available before 15 mins from start time.</span>
                        @elseif($schedulePast)
                          <span class="text-muted">not available</span>
                        @elseif($within15Min)
                          <a href="{{ $optinsArray['video_call_url'] }}" target="_blank" rel="noopener noreferrer" class="btn text-white text-center d-inline-flex align-items-center justify-content-center" style="background-color: #215082; border-radius: 6px; padding: 6px 16px; text-decoration: none; border: none;">Join</a>
                        @else
                          <span class="text-muted">Link will be available before 15 mins from start time.</span>
                        @endif
                      </p>
                    @elseif($isPujaComplete)
                      <p class="mb-0"><strong>Status:</strong> <span class="text-success">Success</span></p>
                    @endif

                  </div>
                @endif
                <div class="col-md-4">
                  <h6 class="section-title"><i class="bi bi-bag-check me-2"></i> Order Summary</h6>
                  <table class="table table-sm align-middle mb-0">
                    <tr>
                      <td>Item(s) subtotal</td>
                      <td class="text-end">₹ {{ $order->sub_total }}</td>
                    </tr>
                    <tr>
                      <td>Discount</td>
                      <td class="text-end text-success">- ₹ {{ sprintf('%.2f', $order->discount_amount+$order->coupon_amount) }}</td>
                    </tr>
                    <tr>
                      <td>GST & Taxes</td>
                      <td class="text-end">₹ {{ sprintf('%.2f', $order->igst+$order->cgst+$order->sgst) }}</td>
                    </tr>
                    @if($order->shipping_price > 0)
                    <tr>
                      <td>Shipping</td>
                      <td class="text-end">₹ {{ $order->shipping_price }}</td>
                    </tr>
                    @endif
                    <tr class="fw-bold border-top">
                      <td>Total</td>
                      <td class="text-end">₹ {{ $order->total_price }}</td>
                    </tr>
                  </table>
                </div>
                <div class="col-md-6">
                  @if($order->payment_status)
                    <h6 class="section-title"><i class="bi bi-credit-card me-2"></i> Payment Status</h6>
                    <p class="mb-0 text-capitalize">{{ $order->payment_status == "success" ? "Paid" : "Pending" }}</p>
                  @endif
                </div>



              </div>
            </div>
          </div>

          </div>
          <div class="row">
            <!-- <div class="col-md-7"> -->
              <div class="col-12 more-items">
                <h4 class="mt-4">Order Items</h4>
              </div>
              <div class="card-box">
                <div class="row">
                  @php
                    $productTypeNames = ["", "Product", "Pooja", "Service"];
                  @endphp
                 @foreach($orderItems as $key => $item)
                   @php
                     $product = null;
                     $course = null;
                   @endphp
                  @if($item->type=='shop')
                   @php
                    $product = $item->product;
                    $productCategory = $product && $product->status == 1 ? $product->category : null;
                   @endphp
                   @if($productCategory)
                     @php
                      $productId = $product->id;
                      $productTypeName = $productTypeNames[$product->type];
                      $productTypeValue = $product->type;
                      $productSlug = $product->slug;
                      $productFormFields = $product->fields;
                      $productCategoryImage = $productCategory->image;
                      $productCategorySlug = $productCategory->slug;
                      $productWishlist = $product->wishlist;
                      $productReviewsAll = $productReviews;
                      $productUrl = route('product.detail', [$productSlug, 'id'=>$product->id]);
                      if($product->type==2){
                        $productUrl = route('product.detailpuja', [$productSlug, 'id'=>$product->id]);
                      }elseif($product->type==3){
                        $productUrl = route('product.detailservice', [$productSlug, 'id'=>$product->id]);
                      }
                     @endphp
                     <div class="col-lg-12 col-6 m-0 p-0">
                       <div class="item m-2  d-md-flex">
                         <a href="{{ $productUrl }}" class="ghg">
                         <div class="p-box service-card2 p-de-card">
                             <img class="rounded" src="{{ $item->image }}">
                           <div class="effect"></div>
                           <div class="btn-effect">
                           </div>
                         </div>
                       </a>
                         <div class="p-2 pt-0">
                           <a href="{{ $productUrl }}" class="prod-name fs-12 text m-0 mt-0 pointer text-truncate" style="max-width:300px;">
                             {{ $item->name }}
                           </a>
                           <div class="">
                             <span class="text-danger fw-semibold">₹{{ $item->price }} x {{ $item->quantity }}</span>
                           </div>
                           <div class="d-md-flex  align-items-center mt-1">
                             @php
                               $review = $productReviewsAll->where("product_id", $productId)->first();
                               $comment = "";
                               $rating = "";
                               $status = 0;
                               if($review){
                                 $rating = $review->rating;
                                 $comment = $review->review;
                                 $status = $review->status;
                               }
                               $now = now();

                               // Check if puja is complete (for puja type products - both group and one-to-one)
                               // Use the same logic as in the detail section above to ensure consistency
                               $isPujaCompleteForReview = false;
                               if($productTypeValue == 2){
                                 // Check if this is a one-to-one puja
                                 $isOneToOnePujaItem = ($product->is_one_to_one_puja == 1);

                                 $itemOptions = json_decode($item->options, true);
                                 $startAtSecondsForReview = strtotime("now");

                                 // For one-to-one puja, use the same optinsArray as detail section (which shows Success)
                                 if($isOneToOnePujaItem && isset($optinsArray["start_at"]) && !empty($optinsArray["start_at"])){
                                   $startAtSecondsForReview = strtotime($optinsArray["start_at"]);
                                   $isPujaCompleteForReview = $startAtSecondsForReview < strtotime("now");
                                 }
                                 // For group puja or if optinsArray doesn't have start_at, check item's own options
                                 elseif(isset($itemOptions["start_at"]) && !empty($itemOptions["start_at"])){
                                   $startAtSecondsForReview = strtotime($itemOptions["start_at"]);
                                   $isPujaCompleteForReview = $startAtSecondsForReview < strtotime("now");
                                 }
                                 // Fallback: check product expire_at if start_at is not in options
                                 elseif($product && $product->expire_at){
                                   $startAtSecondsForReview = strtotime($product->expire_at);
                                   $isPujaCompleteForReview = $startAtSecondsForReview < strtotime("now");
                                 }
                               }
                             @endphp
                             @if($productTypeValue == 2 && $isPujaCompleteForReview)
                               {{-- For puja type products (both group and one-to-one), show review button only if puja is complete --}}
                               @if($status == 0)
                                 <a href="javascript:void(0)" class="p-add phone_btn me-0 me-md-3" onclick="handleReviewModal('open', '{{ $productId }}', '{{ $rating }}', '{{ $comment }}')">
                                   Review
                                 </a>
                               @endif
                             @elseif($order->order_status == "delivered" && $status == 0 && $productTypeValue != 2)
                               {{-- For non-puja products, show review button if order is delivered --}}
                               <a href="javascript:void(0)" class="p-add phone_btn me-0 me-md-3" onclick="handleReviewModal('open', '{{ $productId }}', '{{ $rating }}', '{{ $comment }}')">
                                 Review
                               </a>
                             @elseif($productTypeValue != 2)
                               {{-- For non-puja products, show other buttons if order is not delivered or review already submitted --}}
                               @if($productTypeValue == 1)
                                 @if($product->avaibility > 0)
                                   <button type="button" onclick="addToCart('{{ route('addtocart') }}', '{{ $productId }}')" class="p-add res_btn k_add_cart">Add To Cart</button>
                                 @else
                                   <button type="button" disabled class="p-add res_btn stock_btn">Out of Stock</button>
                                 @endif
                               @elseif($productFormFields->count() == 0)
                                 <a href="{{ route('checkout', [strtolower($productTypeName),'item_slug'=>$productSlug]) }}" class="p-add phone_btn">
                                   Buy Now
                                 </a>
                               @endif
                             @endif
                           </div>
                         </div>
                       </div>
                     </div>
                   @else
                     <div class="col-lg-12 col-6 m-0 p-0">
                       <div class="item m-2  d-md-flex">
                         <div class="p-box service-card2 p-de-card">
                           <a href="javascript:void(0)" class="ghg">
                             <img class="rounded" src="{{ $item->image }}">
                           </a>
                           <div class="effect"></div>
                           <div class="btn-effect">
                           </div>
                         </div>
                         <div class="p-2 pt-0">
                           <a href="javascript:void(0)" class="prod-name fs-12 text m-0 mt-0 pointer text-truncate" style="max-width:300px;">
                             {{ $item->name }}
                           </a>
                           <div class="">
                             <span class="text-danger fw-semibold">₹{{ $item->price }} x {{ $item->quantity }}</span>
                           </div>
                         </div>
                       </div>
                     </div>
                   @endif
                  @elseif($item->type=='course' && $course = $item->course)
                    <div class="col-md-4 col-12 primium-n primium-n1 p-0">
                      <div class="card border-0 p-card " style="margin-right: 20px !important;">
                        <img src="{{$course->image ? $course->image->image(200, 200) : no_image()}}" class="card-img-top" alt="Basic Astrology Course">
                        <div class="card-body">
                          <div class="name">
                            <img src="{{$course->image ? $course->image->image(50, 50) : no_image()}}" class="ins-img" alt="Instructor Image">
                            <h6 class="fw-bold c-h text-truncate">{{$item->name}}</h6>
                          </div>
                          <div class="footer">
                             <div class="d-flex align-items-center justify-content-between w-100 mt-2" style="gap: 0.4em;">
                               <a href="{{route('course.handle', [$course->slug])}}#review-form" tabindex="0" class="live-courses-active w-50 text-center mb-3 mt-3">Review</a>
                               @if($course->purchased)
                               <a onclick="alreadyPurchased()" class="live-courses w-50 text-center pointer">Reorder</a>
                              @else
                                @if($course->publish)
                                 <a href="{{ route('checkout', ['buynow', 'course', $course->slug]) }}" class="live-courses w-50 text-center">Reorder</a>
                                @endif
                              @endif
                             </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  @endif
                @endforeach
              </div>
            </div>
          </div>

          <!-- review modal start -->
          <!-- Review Modal -->
        <div class="modal fade" id="reviewModal" tabindex="-1" aria-labelledby="reviewModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-lg  review_phone">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="reviewModalLabel">Write Review</h5>
                <button type="button" class="btn-close" onclick="handleReviewModal('close', 0, 0, '')"></button>
              </div>
              <div class="modal-body">
                <form id="review_form">
                  <input type="hidden" name="order_id" value="{{$order->id}}">
                  <input type="hidden" name="product_id" value="0" id="product_id">
                  <input type="hidden" name="rating" id="rating" value="">
                  <div class="col-md-12 stars rati mb-3">
                    <i class="bi bi-star" data-value="1" id="star-1"></i>
                    <i class="bi bi-star" data-value="2" id="star-2"></i>
                    <i class="bi bi-star" data-value="3" id="star-3"></i>
                    <i class="bi bi-star" data-value="4" id="star-4"></i>
                    <i class="bi bi-star" data-value="5" id="star-5"></i>
                  </div>
                  <div class="mb-3">
                    <textarea name="comments" id="comments" rows="5" class="form-control" placeholder="Write your review..."></textarea>
                  </div>
                  <div class="text-end">
                    <button type="submit" class=" p-add">Submit</button>
                  </div>
                </form>
              </div>

            </div>
          </div>
        </div>

        <!-- review modal end -->

          <!-- tracking status modal start -->
          <div class="modal fade modal-lg order-modal" id="trackOrderStatusModal" tabindex="-1" aria-labelledby="trackOrderStatusModalLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <div class="container">
                    <div class="row">
                      <div class="d-flex align-items-center justify-content-between w-100">
                        <div class="">
                          <h2 class="fs-6 m-0 p-0">Order Tracking</h2>
                        </div>
                      </div>
                      <div class="col-12 col-md-12 hh-grayBox pt-2 pt-md-3">
                        <div class="row justify-content-between">
                          <div class="order-tracking {{$order_status>=1?'completed':''}}">
                            <span class="is-complete"></span>
                            <p>Ordered <br>
                            <span>{{getOrderStatus($order->id, 'ordered')}}</span>
                            </p>
                          </div>
                          <div class="order-tracking {{$order_status>=2?'completed':''}}">
                            <span class="is-complete"></span>
                            <p>Shipped <br>
                            <span>{{getOrderStatus($order->id, 'shipped')}}</span>
                            </p>
                          </div>
                          <div class="order-tracking {{$order_status>=3?'completed':''}}">
                            <span class="is-complete"></span>
                            <p>In-Transit <br>
                            <span>{{getOrderStatus($order->id, 'in transit')}}</span>
                            </p>
                          </div>
                          <div class="order-tracking {{$order_status>=4?'completed':''}}">
                            <span class="is-complete"></span>
                            <p>Dispatched <br>
                            <span>{{getOrderStatus($order->id, 'dispatched')}}</span>
                            </p>
                          </div>
                          <div class="order-tracking {{$order_status>=5?'completed':''}}">
                            <span class="is-complete"></span>
                            <p>Delivered <br>
                            <span>{{getOrderStatus($order->id, 'delivered')}}</span>
                            </p>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- tracking status modal end -->
        </div>
      </div>
    </div>
  </div>
</section>

<!-- modal start -->
<div class="modal fade redeem-modal" id="order_request" tabindex="-1" aria-labelledby="order_request" aria-hidden="true">
  <div class="modal-dialog modal-lg pad-p2">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="order_request_type">Request</h5>
        <button type="button" class="btn-close" onclick="closeModel()"></button>
      </div>
      <div class="modal-body">
        <form class="" action="" method="post" onsubmit="return orderRequest()">
          <input type="hidden" name="order_id" id="order_id" value="">
          <input type="hidden" name="request_type" id="request_type" value="">
          <div class="row">
            <div class="col-12">
              <label for="item" id="items">Order Items</label>
              @foreach($orderItems as $key => $item)
              <div class="d-flex align-items-center justify-content-between p-2 border-top border-dark">
                <p class="m-0 p-0 fs-7 fw-bold">{{$item->name}}</p>
                  <label class="switch" for="checkbox{{$item->id}}">
                  <input type="checkbox" id="checkbox{{$item->id}}" value="{{$item->id}}" class="items-checkbox"/>
                  <div class="slider round" onclick="selectItem('{{$item->id}}')"></div>
              </div>
              @endforeach
              <div class="validation text-danger fs-8 d-none" id="items-error"></div>
            </div>
            <div class="col-12">
              <div class="form-group reasons" id="cancel-reasons">
                <label for="message" class="why_heading my-1" id="why_heading">Why?</label>
                <select class="form-select" name="reasons" id="reason" onchange="handleOrderRequestReason(this)">
                  @foreach(orderRequestReason() as $key => $value)
                    @if($key==count(orderRequestReason())-1)
                      <option value="{{$key}}" data-lastoption="yes">{{$value}}</option>
                    @else
                      <option value="{{$key}}" data-lastoption="no">{{$value}}</option>
                    @endif
                  @endforeach
                </select>
              </div>
              <div class="form-group reasons" id="return-reasons">
                <label for="message" class="why_heading my-1" id="why_heading">Why?</label>
                <select class="form-select" name="reasons" id="reason" onchange="handleOrderRequestReason(this)">
                  @foreach(orderRequestReturnReason() as $key => $value)
                    @if($key==count(orderRequestReturnReason())-1)
                      <option value="{{$key}}" data-lastoption="yes">{{$value}}</option>
                    @else
                      <option value="{{$key}}" data-lastoption="no">{{$value}}</option>
                    @endif
                  @endforeach
                </select>
              </div>

              <div class="validation text-danger fs-8 d-none" id="reason-error"></div>
            </div>
            <div class="col-12 d-none mt-2">
              <label for="message" class="my-1">Message</label>
              <div class="form-group">
                <textarea name="message" class="p-2" id="message" rows="3" cols="50"></textarea>
              </div>
              <div class="validation text-danger fs-8 d-none" id="message-error"></div>
            </div>
          </div>
          <div class="form-group mt-2">
            <button type="submit" class="btn btn-secondary px-4 py-2 submit_btn" style="height: 100%;">Send</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- modal end -->
 @endsection
 @push('scripts')
 <script type="text/javascript">
    var selectedOrderItem = [];
    function orderRequest(){
      $('.validation').addClass('d-none');
      let order_id = $('#order_id').val();
      let request_type = $('#request_type').val();
      let reason = $('#reason').val();
      let message = $('#message').val();
      $.ajax({
        url:@json(route('user.order.request')),
        method:'POST',
        data: {
          order_id:order_id,
          request_type:request_type,
          reason:reason,
          message:message,
          items:selectedOrderItem
        },
        headers:{
          "X-CSRF-TOKEN":@json(csrf_token())
        },
        success:function(response){
          toastr.success(response.message);
          closeModel();
          setTimeout(function(){
            window.location.reload();
          }, 2000)
        },
        error:function(error){
          if(error.responseJSON && error.responseJSON.errors){
            for (var key in error.responseJSON.errors) {
              toastr.error(error.responseJSON.errors[key][0]);
            }
          }else if(error.responseJSON.message){
            toastr.error(error.responseJSON.message);
            closeModel();
          }
        }
      });
      return false;
    }

    function openModel(order_id, request_type){
      $('#order_request_type').html(request_type.toUpperCase()+' REQUEST');
      $('#order_request').modal('show');
      $('#order_id').val(order_id);
      $('#request_type').val(request_type);
      $('#reason').val('')
      $('#message').parent().parent().addClass('d-none')
      $('#message').val('')
      $('#items').parent().addClass('d-none')
      $('.reasons').addClass('d-none').find('select').attr('id', '').attr('disabled', true);
      $('#'+request_type+'-reasons').removeClass('d-none').find('select').attr('id', 'reason').attr('disabled', false);
      $('.why_heading').text('Reason For Cancellation');
      if(request_type=='return'){
        $('.why_heading').text('Reason For Return');
        $('.items-checkbox').prop('checked', false);
        $('#items').parent().removeClass('d-none');
      }
      selectedOrderItem = [];
    }
    function closeModel(){
      $('#order_id').val('');
      $('#request_type').val('');
      $('#message').val('');
      $('#order_request').modal('hide');
    }

    function alreadyRequested(message){
    }

    function handleOrderRequestReason(that){
      $('#message').parent().parent().addClass('d-none')
      let value = $(that).find(':selected').val();
      let dataLastOption = $(that).find(':selected').attr('data-lastoption');
      if(dataLastOption=='yes'){
        $('#message').parent().parent().removeClass('d-none');
      }
    }
    function selectItem(item_id){
      if(!$('#checkbox'+item_id).prop('checked')){
        if(!selectedOrderItem.includes(item_id)){
          selectedOrderItem.push(item_id);
        }
      }else{
        if(selectedOrderItem.includes(item_id)){
          let i = selectedOrderItem.indexOf(item_id)
          selectedOrderItem.splice(i, 1);
        }
      }
    }
</script>

<script type="text/javascript">
  function addToCart(url, item_id){
    $.ajax({
      url:url,
      method:'POST',
      data: {
        item_id:item_id
      },
      headers:{
        "X-CSRF-TOKEN":@json(csrf_token())
      },
      success:function(response){
        toastr.success('Item added successfully');
        showCart2();
      },
      error:function(error){
        console.log(error)
      }
    });
  }

  function alreadyPurchased(){
    toastr.error('Course already purchased.');
  }
</script>
<script type="text/javascript">
  $(document).ready(function(){
    $('#review_form').on('submit', function(event){
        event.preventDefault(); // Prevent default form submission
        var formData = $(this).serialize(); // Serialize form data
        $.ajax({
        url: "{{ route('product.review') }}",
        method: "POST",
        data: formData,
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        success: function(response) {
          toastr.success(response.message);
          setTimeout(function() {
            window.location.reload();
          }, 1000);
        },
        error: function(error){
          let errors = error.responseJSON.errors;
            if(errors){
              for(let key in errors){
                toastr.error(errors[key][0]);
              }
            }else{
              let message = error.responseJSON.message;
              if(message){
                toastr.error(message);
              }
            }
        }
      });
    });
  });

  document.querySelectorAll(".rati i").forEach(star => {
    star.addEventListener("click", function() {
      let value = this.getAttribute("data-value");
      document.querySelectorAll(".rati i").forEach(s => {
        s.classList.remove("bi-star-fill");
        s.classList.add("bi-star");
      });
      for (let i = 0; i < value; i++) {
        let starEl = document.querySelectorAll(".rati i")[i];
        starEl.classList.remove("bi-star");
        starEl.classList.add("bi-star-fill");
      }
      document.getElementById("rating").value = value;
    });
  });

  function handleReviewModal(action, product_id, rating, comment){
    $("#product_id").val(product_id);
    $("#comments").val(comment);
    rating = Number(rating);
    $("#star-"+rating).click();
    if(action == "open"){
      $("#reviewModal").modal("show");
    }else{
      $("#reviewModal").modal("hide");
    }
  }
</script>
 @endpush
