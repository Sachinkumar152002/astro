<style media="screen">
  .sub {
    display: none;
    padding-left: 15px;
  }
  .nav-item.open > .sub {
    display: block;
  }
</style>
<div class="sidebar">
  <nav class="sidebar-nav">
    <h5 class="text-center" style="margin-top: 24px; font-size: 24px; color: white; font-weight: 600;">Navigation</h5>
    <ul class="nav">
      <li class="nav-item">
        <a href="{{route('admin.dashboard')}}" class="nav-link {{$nav && $nav=='dashboard'?'active':''}}">
          <i class="bi bi-speedometer2 text-white fs-18"></i> Dashboard
        </a>
      </li>
      <!-- astrologer managements start -->
      @php
        $canastrologer = adminCan('astrologer','any');
        $canastrologeradd = adminCan('astrologer','add');
        $cantemporary_astrologer_data = adminCan('temporary-astrologer-data','any');
        $canastrologer_chat_consultation = adminCan('astrologer-chat-consultation','any');
        $canastrologer_call_consultation = adminCan('astrologer-call-consultation','any');
        $canastrologer_video_consultation = adminCan('astrologer-video-consultation','any');
        $canwithdraw = adminCan('withdraw','any');
        $canastrologer_review = adminCan('astrologer-review','any');
        $canastrologer_reviewadd = adminCan('astrologer-review','add');
        $canai = adminCan('ai','any');
        $canaiadd = adminCan('ai','add');
        $canai_chat_consultation = adminCan('ai-chat-consultation','any');
        $canai_review = adminCan('ai-review','any');
        $canai_reviewadd = adminCan('ai-review','add');
        $canexpertise = adminCan('expertise','any');
        $canexpertiseadd = adminCan('expertise','add');
        $canlanguage = adminCan('language','any');
        $canrecharge_plan = adminCan('recharge-plan','any');
        $canrecharge_planadd = adminCan('recharge-plan','add');
        $canpayout_package = adminCan('payout-package','any');
        $canpayout_packageadd = adminCan('payout-package','add');
      @endphp
      @php
        $catalogueNav = ["Astrologer", "addAstrologer", "temp_astrologer", "chat_consultation", "call_consultation", "video_consultation", "withdraw", "review", "addreview", "pendingreview", "AI", "addAI", "ai_chat_consultation", "aireview", "aiaddreview", "aipendingReview",
        "language", "service", "addservice", "rechargeplans", "addrechargeplans", "payoutpackages", "addpayoutpackages"];
      @endphp
      @if($canastrologer || $canastrologeradd || $cantemporary_astrologer_data || $canastrologer_chat_consultation || $canastrologer_call_consultation || $canastrologer_video_consultation || $canwithdraw || $canastrologer_review || $canastrologer_reviewadd || $canai || $canaiadd || $canai_chat_consultation || $canai_review || $canai_reviewadd || $canlanguage || $canexpertise || $canexpertiseadd || $canrecharge_plan || $canrecharge_planadd || $canpayout_package || $canpayout_packageadd)
        <li class="nav-item nav-item nav-dropdown nav-main-li {{$nav && in_array($nav, $catalogueNav) ? 'open' : ''}}">
          <a href="javascript:void(0)" class="nav-link nav-dropdown-toggle {{$nav && in_array($nav, $catalogueNav) ? 'active' : ''}}">
            <i class="bi bi-people fs-18 text-white"></i>Astrologers Management<i class="bi bi-chevron-double-left menu-arrow"></i>
          </a>
          @php
            $catalogueAstrologerNav = ["Astrologer", "addAstrologer", "temp_astrologer", "chat_consultation", "call_consultation", "video_consultation", "withdraw", "review", "addreview", "pendingreview"];
            $catalogueAINav = ["AI", "addAI", "ai_chat_consultation", "aireview", "aiaddreview", "aipendingReview"];
          @endphp
          <ul class="nav nav-dropdown-items">
            @if($canastrologer || $canastrologeradd || $cantemporary_astrologer_data || $canastrologer_chat_consultation || $canastrologer_call_consultation || $canastrologer_video_consultation || $canwithdraw || $canastrologer_review || $canastrologer_reviewadd)
              <li class="nav-item nav-dropdown sub-menu-nav">
                <a href="javascript:void(0)" class="nav-link nav-dropdown-toggle {{$nav && in_array($nav, $catalogueAstrologerNav) && str_contains(url()->current(), 'Astrologer') ? 'active' : ''}}">
                  Humen Astrologers<i class="bi bi-chevron-double-left menu-arrow text-white"></i>
                </a>
                <ul class="sub">
                  @if($canastrologer)
                    <li class="nav-item">
                      <a href="{{route('admin.Astrologer.index')}}" class="nav-link {{$nav && $nav=='Astrologer'?'active':''}}">
                        List
                      </a>
                    </li>
                  @endif
                  @if($canastrologeradd)
                    <li class="nav-item">
                      <a href="{{route('admin.Astrologer.add')}}" class="nav-link {{$nav && $nav=='addAstrologer'?'active':''}}">Add New</a>
                    </li>
                  @endif
                  @if($cantemporary_astrologer_data)
                    <li class="nav-item">
                      <a href="{{route('admin.temp_astrologer.index')}}" class="nav-link {{$nav && $nav=='temp_astrologer'?'active':''}}">
                        Approval List
                      </a>
                    </li>
                  @endif

                  @if($canastrologer_chat_consultation || $canastrologer_call_consultation || $canastrologer_video_consultation)
                    @php
                      $navArray = ["chat_consultation", "call_consultation", "video_consultation"];
                    @endphp
                    <li class="nav-item nav-dropdown sub-menu-nav">
                      <a href="javascript:void(0)" class="nav-link nav-dropdown-toggle {{$nav && in_array($nav, $navArray) ? 'active' : ''}}">
                        Consultations <i class="bi bi-chevron-double-left menu-arrow text-white"></i>
                      </a>
                      <ul class="sub">
                        @if($canastrologer_chat_consultation)
                          <li class="nav-item">
                            <a href="{{route('admin.consultation.chat.index', ['Astrologer'])}}" class="nav-link {{isset($nav) && isset($userType) && $userType == 'Astrologer' && $nav=='chat_consultation'?'active':''}}">
                              Chat
                            </a>
                          </li>
                        @endif
                        @if($canastrologer_call_consultation)
                          <li class="nav-item">
                            <a href="{{route('admin.consultation.call.index', ['Astrologer'])}}" class="nav-link {{isset($nav) && isset($userType) && $userType == 'Astrologer' && $nav=='call_consultation'?'active':''}}">
                              Call
                            </a>
                          </li>
                        @endif
                        @if($canastrologer_video_consultation)
                          <li class="nav-item">
                            <a href="{{route('admin.consultation.video.index', ['Astrologer'])}}" class="nav-link {{isset($nav) && isset($userType) && $userType == 'Astrologer' && $nav=='video_consultation'?'active':''}}">
                              Video
                            </a>
                          </li>
                        @endif
                      </ul>
                    </li>
                  @endif

                  @if($canwithdraw)
                    <li class="nav-item nav-dropdown sub-menu-nav">
                      <a href="{{route('admin.withdraw.index')}}" class="nav-link {{$nav && $nav=='withdraw'?'active':''}}">
                        Withdraw Requests
                      </a>
                    </li>
                  @endif

                  @if($canastrologer_review || $canastrologer_reviewadd)
                    @php
                      $navArray = ["review", "addreview", "pendingreview"];
                    @endphp
                    <li class="nav-item nav-dropdown sub-menu-nav">
                      <a href="javascript:void(0)" class="nav-link nav-dropdown-toggle {{$nav && in_array($nav, $navArray) ? 'active' : ''}}">
                        Reviews <i class="bi bi-chevron-double-left menu-arrow text-white"></i>
                      </a>
                      <ul class="sub">
                        @if($canastrologer_review)
                          <li class="nav-item">
                            <a href="{{route('admin.astrologer.review', ['Astrologer'])}}" class="nav-link {{ isset($nav) && $nav == 'review' ? 'active' : '' }}">
                              Lists
                            </a>
                          </li>
                        @endif
                        @if($canastrologer_reviewadd)
                          <li class="nav-item">
                            <a href="{{route('admin.astrologer.review', ['Astrologer'])}}" class="nav-link {{ isset($nav) && $nav == 'addreview' ? 'active' : '' }}">
                              Add New
                            </a>
                          </li>
                        @endif
                        @if($canastrologer_review)
                          <li class="nav-item">
                            <a href="{{route('admin.astrologer.review', ['Astrologer', 'status' => 'inactive'])}}" class="nav-link {{ isset($nav) && $nav == 'pendingreview' ? 'active' : '' }}">
                              Pending
                            </a>
                          </li>
                        @endif
                      </ul>
                    </li>
                  @endif

                </ul>
              </li>
            @endif

            @if($canai || $canaiadd || $canai_chat_consultation || $canai_review || $canai_reviewadd)
              <li class="nav-item nav-dropdown sub-menu-nav">
                <a href="javascript:void(0)" class="nav-link nav-dropdown-toggle {{$nav && in_array($nav, $catalogueAINav) && str_contains(url()->current(), 'AI') ? 'active' : ''}}">AI Astrologers<i class="bi bi-chevron-double-left menu-arrow text-white"></i>
                </a>
                <ul class="sub">
                  @if($canai)
                    <li class="nav-item">
                      <a href="{{route('admin.AI.index')}}" class="nav-link {{$nav && $nav=='AI'?'active':''}}">
                        List
                      </a>
                    </li>
                  @endif
                  @if($canaiadd)
                    <li class="nav-item">
                      <a href="{{route('admin.AI.add')}}" class="nav-link {{$nav && $nav=='addAI'?'active':''}}">
                        Add New
                      </a>
                    </li>
                  @endif
                  @if($canai_chat_consultation)
                    @php
                      $navArray = ["ai_chat_consultation"];
                    @endphp
                    <li class="nav-item nav-dropdown sub-menu-nav">
                      <a href="javascript:void(0)" class="nav-link nav-dropdown-toggle {{$nav && in_array($nav, $navArray) ? 'active' : ''}}">
                        Consultations <i class="bi bi-chevron-double-left menu-arrow text-white"></i>
                      </a>
                      <ul class="sub">
                        @if($canai_chat_consultation)
                          <li class="nav-item">
                            <a href="{{route('admin.aiconsultation.chat.index', ['AI'])}}" class="nav-link {{isset($nav) && isset($userType) && $userType == 'AI' && $nav=='ai_chat_consultation'?'active':''}}">
                              Chat
                            </a>
                          </li>
                        @endif
                      </ul>
                    </li>
                  @endif
                  @if($canai_review || $canai_reviewadd)
                    @php
                      $navArray = ["aireview", "aiaddreview", "aipendingReview"];
                    @endphp
                    <li class="nav-item nav-dropdown sub-menu-nav">
                      <a href="javascript:void(0)" class="nav-link nav-dropdown-toggle {{$nav && in_array($nav, $navArray) ? 'active' : ''}}">
                        Reviews <i class="bi bi-chevron-double-left menu-arrow text-white"></i>
                      </a>
                      <ul class="sub">
                        @if($canai_review)
                          <li class="nav-item">
                            <a href="{{route('admin.astrologer.review', ['AI'])}}" class="nav-link {{ isset($nav) && $nav == 'aireview' ? 'active' : '' }}">
                              List
                            </a>
                          </li>
                        @endif
                        @if($canai_reviewadd)
                          <li class="nav-item">
                            <a href="{{route('admin.astrologer.review', ['AI'])}}" class="nav-link {{ isset($nav) && $nav == 'aiaddreview' ? 'active' : '' }}">
                              Add New
                            </a>
                          </li>
                        @endif
                        @if($canai_review)
                          <li class="nav-item">
                            <a href="{{route('admin.astrologer.review', ['AI', 'status' => 'inactive'])}}" class="nav-link {{ isset($nav) && $nav == 'aipendingReview' ? 'active' : '' }}">
                              Pending
                            </a>
                          </li>
                        @endif
                      </ul>
                    </li>
                  @endif
                </ul>
              </li>
            @endif

            @if($canexpertise || $canexpertiseadd)
              @php
                $navArray = ["service", "addservice"];
              @endphp
              <li class="nav-item nav-dropdown sub-menu-nav">
                <a href="javascript:void(0)" class="nav-link nav-dropdown-toggle {{$nav && in_array($nav, $navArray) ? 'active' : ''}}">
                  Categories<i class="bi bi-chevron-double-left menu-arrow text-white"></i>
                </a>
                <ul class="sub">
                  @if($canexpertise)
                    <li class="nav-item">
                      <a href="{{route('admin.service.index')}}" class="nav-link {{isset($nav) && $nav=='service'?'active':''}}">
                        List
                      </a>
                    </li>
                  @endif
                  {{--@if($canexpertiseadd)
                    <li class="nav-item">
                      <a href="{{route('admin.service.add')}}" class="nav-link {{isset($nav) && $nav=='addservice'?'active':''}}">
                        Add New
                      </a>
                    </li>
                  @endif--}}
                </ul>
              </li>
            @endif

            @if($canlanguage)
              <li class="nav-item nav-dropdown sub-menu-nav">
                <a href="{{ route('admin.language.index') }}" class="nav-link {{isset($nav) && $nav=='language' ? 'active':''}}">
                  Languages
                </a>
              </li>
            @endif

            @if($canrecharge_plan || $canrecharge_planadd)
              @php
                $navArray = ["rechargeplans", "addrechargeplans"];
              @endphp
              <li class="nav-item nav-dropdown sub-menu-nav">
                <a href="javascript:void(0)" class="nav-link nav-dropdown-toggle {{$nav && in_array($nav, $navArray) ? 'active' : ''}}">
                  Recharge Plan<i class="bi bi-chevron-double-left menu-arrow text-white"></i>
                </a>
                <ul class="sub">
                  @if($canrecharge_plan)
                    <li class="nav-item">
                      <a href="{{route('admin.rechargeplans.index')}}" class="nav-link {{isset($nav) && $nav=='rechargeplans'?'active':''}}">
                        List
                      </a>
                    </li>
                  @endif
                  {{--@if($canrecharge_planadd)
                    <li class="nav-item">
                      <a href="{{route('admin.rechargeplans.add')}}" class="nav-link {{isset($nav) && $nav=='addrechargeplans'?'active':''}}">
                        Add New
                      </a>
                    </li>
                  @endif--}}
                </ul>
              </li>
            @endif

            @if($canpayout_package || $canpayout_packageadd)
              @php
                $navArray = ["payoutpackages", "addpayoutpackages"];
              @endphp
              <li class="nav-item nav-dropdown sub-menu-nav">
                <a href="javascript:void(0)" class="nav-link nav-dropdown-toggle {{$nav && in_array($nav, $navArray) ? 'active' : ''}}">
                  Payout Packages<i class="bi bi-chevron-double-left menu-arrow text-white"></i>
                </a>
                <ul class="sub">
                  @if($canpayout_package)
                    <li class="nav-item">
                      <a href="{{route('admin.payoutpackages.index')}}" class="nav-link {{isset($nav) && $nav=='payoutpackages'?'active':''}}">
                        List
                      </a>
                    </li>
                  @endif
                  {{--@if($canpayout_packageadd)
                    <li class="nav-item">
                      <a href="{{route('admin.payoutpackages.add')}}" class="nav-link {{isset($nav) && $nav=='addpayoutpackages'?'active':''}}">
                        Add New
                      </a>
                    </li>
                  @endif--}}
                </ul>
              </li>
            @endif

          </ul>
        </li>
      @endif
      <!-- astrologer managements end -->

      <!-- pandits management start -->
      @php
        $canPanditCategory = adminCan('PanditCategory', 'any');
        $canPanditCategoryAdd = adminCan('PanditCategory', 'add');
        $canPandit = adminCan('Pandit', 'any');
        $canPanditAdd = adminCan('Pandit', 'add');
      @endphp
      @php
        $navArray = ['PanditCategory', 'PanditCategoryAdd', "Pandit", "PanditAdd"];
      @endphp
      @if($canPanditCategory || $canPanditCategoryAdd || $canPandit || $canPanditAdd)
        <li class="nav-item nav-item nav-dropdown nav-main-li {{isset($nav) && in_array($nav, $navArray) ? 'open' : ''}}">
          <a href="javascript:void(0)" class="nav-link nav-dropdown-toggle {{isset($nav) && in_array($nav, $navArray) ? 'active' : ''}}">
            <i class="bi bi-people fs-18 text-white"></i>Pandits Managements<i class="bi bi-chevron-double-left menu-arrow"></i>
          </a>
          <ul class="nav nav-dropdown-items">
            <!-- pandits start -->
            @if($canPanditCategory || $canPanditCategoryAdd || $canPandit || $canPanditAdd)
              @php
                $navArray = ['PanditCategory', 'PanditCategoryAdd', "Pandit", "PanditAdd"];
              @endphp
              <li class="nav-item nav-dropdown sub-menu-nav">
                <a href="javascript:void(0)" class="nav-link nav-dropdown-toggle {{$nav && in_array($nav, $navArray) ? 'active' : ''}}">
                  Pandits<i class="bi bi-chevron-double-left menu-arrow text-white"></i>
                </a>
                <ul class="sub">
                  @if($canPandit)
                    <li class="nav-item nav-dropdown sub-menu-nav">
                      <a href="{{ route('admin.Pandit.index') }}" class="nav-link {{isset($nav) && $nav=='Pandit' ? 'active':''}}">
                        List
                      </a>
                    </li>
                  @endif
                  @if($canPanditAdd)
                    <li class="nav-item nav-dropdown sub-menu-nav">
                      <a href="{{ route('admin.Pandit.add') }}" class="nav-link {{isset($nav) && $nav=='PanditAdd' ? 'active':''}}">
                        Add New
                      </a>
                    </li>
                  @endif

                  @if($canPanditCategory || $canPanditCategoryAdd)
                    @php
                      $navArray = ['PanditCategory', 'PanditCategoryAdd'];
                    @endphp
                    <li class="nav-item nav-dropdown sub-menu-nav">
                      <a href="javascript:void(0)" class="nav-link nav-dropdown-toggle {{$nav && in_array($nav, $navArray) ? 'active' : ''}}">
                        Categories <i class="bi bi-chevron-double-left menu-arrow text-white"></i>
                      </a>
                      <ul class="sub">
                        @if($canPanditCategory)
                          <li class="nav-item">
                            <a href="{{ route('admin.PanditCategory.index') }}" class="nav-link {{isset($nav) && $nav=='PanditCategory' ? 'active':''}}">
                              List
                            </a>
                          </li>
                        @endif
                        @if($canPanditCategoryAdd)
                          <li class="nav-item">
                            <a href="{{ route('admin.PanditCategory.add') }}" class="nav-link {{isset($nav) && $nav=='PanditCategoryAdd' ? 'active':''}}">
                              Add New
                            </a>
                          </li>
                        @endif
                      </ul>
                    </li>
                  @endif
                </ul>
              </li>
            @endif
            <!-- pandits end -->
          </ul>
        </li>
      @endif
      <!-- pandits management end -->

      <!-- product managements start -->
      @php
        $canproduct_category = adminCan('product-category','any');
        $canproduct_categoryadd = adminCan('product-category','add');
        $canproduct = adminCan('product','any');
        $canproductadd = adminCan('product','add');
        $canproduct_review = adminCan('product-review','any');
        $canproduct_reviewadd = adminCan('product-review','add');
        $canservice_product_category = adminCan('service-product-category','any');
        $canservice_product_categoryadd = adminCan('service-product-category','add');
        $canservice_product = adminCan('service-product','any');
        $canservice_productadd = adminCan('service-product','add');
        $canservice_product_review = adminCan('service-product-review','any');
        $canservice_product_reviewadd = adminCan('service-product-review','add');
        $canpooja_product_category = adminCan('pooja-product-category','any');
        $canpooja_product_categoryadd = adminCan('pooja-product-category','add');
        $canpooja_product = adminCan('pooja-product','any');
        $canpooja_productadd = adminCan('pooja-product','add');
        $canpooja_product_review = adminCan('pooja-product-review','any');
        $canpooja_product_reviewadd = adminCan('pooja-product-review','add');
        $canhsn = adminCan('hsn','any');
        $canhsnadd = adminCan('hsn','add');
        $canform_field = adminCan('form-field','any');
      @endphp
      @php
        $navArray = ['productCategory', 'productCategoryadd', 'product', 'productadd', 'productReview', 'productReviewadd', 'productReviewpending', 'serviceProductCategory', 'serviceProductCategoryadd', 'serviceProduct', 'serviceProductadd', 'serviceProductReview', 'serviceProductReviewadd',  'serviceProductReviewpending', 'poojaProductCategory', 'poojaProductCategoryadd', 'poojaProduct', 'poojaProductadd', 'poojaProductReview', 'poojaProductReviewadd', 'poojaProductReviewpending', 'hsn', 'hsnadd', "form"];
      @endphp
      @if($canproduct_category || $canproduct_categoryadd || $canproduct || $canproductadd || $canproduct_review || $canproduct_reviewadd || $canservice_product_category || $canservice_product_categoryadd || $canservice_product || $canservice_productadd || $canservice_product_review || $canservice_product_reviewadd || $canpooja_product_category || $canpooja_product_categoryadd || $canpooja_product || $canpooja_productadd || $canpooja_product_review || $canpooja_product_reviewadd || $canhsn || $canhsnadd || $canform_field)
        <li class="nav-item nav-item nav-dropdown nav-main-li {{isset($nav) && in_array($nav, $navArray) ? 'open' : ''}}">
          <a href="javascript:void(0)" class="nav-link nav-dropdown-toggle {{isset($nav) && in_array($nav, $navArray) ? 'active' : ''}}">
            <i class="bi bi-files text-white fs-18"></i>Products Management<i class="bi bi-chevron-double-left menu-arrow"></i>
          </a>
          <ul class="nav nav-dropdown-items">
            @php
              $navArray = ['productCategory', 'productCategoryadd', 'product', 'productadd', 'productReview', 'productReviewadd', 'productReviewpending'];
            @endphp
            @if($canproduct_category || $canproduct_categoryadd || $canproduct || $canproductadd || $canproduct_review || $canproduct_reviewadd)
              <li class="nav-item nav-dropdown sub-menu-nav">
                <a href="javascript:void(0)" class="nav-link nav-dropdown-toggle {{$nav && in_array($nav, $navArray) ? 'active' : ''}}">
                  Products<i class="bi bi-chevron-double-left menu-arrow text-white"></i>
                </a>
                <ul class="sub">
                  @if($canproduct)
                    <li class="nav-item nav-dropdown sub-menu-nav">
                      <a href="{{ route('admin.product.index', ['Product']) }}" class="nav-link {{isset($nav) && $nav=='product' ? 'active':''}}">
                        List
                      </a>
                    </li>
                  @endif
                  @if($canproductadd)
                    <li class="nav-item nav-dropdown sub-menu-nav">
                      <a href="{{ route('admin.product.add', ['Product']) }}" class="nav-link {{isset($nav) && $nav=='productadd' ? 'active':''}}">
                        Add New
                      </a>
                    </li>
                  @endif

                  @if($canproduct_category || $canproduct_categoryadd)
                    @php
                      $navArray = ['productCategory', 'productCategoryadd'];
                    @endphp
                    <li class="nav-item nav-dropdown sub-menu-nav">
                      <a href="javascript:void(0)" class="nav-link nav-dropdown-toggle {{$nav && in_array($nav, $navArray) ? 'active' : ''}}">
                        Categories <i class="bi bi-chevron-double-left menu-arrow text-white"></i>
                      </a>
                      <ul class="sub">
                        @if($canproduct_category)
                          <li class="nav-item">
                            <a href="{{ route('admin.productcategory.index', ['en', 'Product']) }}" class="nav-link {{isset($nav) && $nav=='productCategory' ? 'active':''}}">
                              List
                            </a>
                          </li>
                        @endif
                        @if($canproduct_categoryadd)
                          <li class="nav-item">
                            <a href="{{ route('admin.productcategory.add', ['en', 'Product']) }}" class="nav-link {{isset($nav) && $nav=='productCategoryadd' ? 'active':''}}">
                              Add New
                            </a>
                          </li>
                        @endif
                      </ul>
                    </li>
                  @endif

                  @if($canproduct_review || $canproduct_reviewadd)
                    @php
                      $navArray = ['productReview', 'productReviewadd', 'productReviewpending'];
                    @endphp
                    <li class="nav-item nav-dropdown sub-menu-nav">
                      <a href="javascript:void(0)" class="nav-link nav-dropdown-toggle {{$nav && in_array($nav, $navArray) ? 'active' : ''}}">
                        Reviews <i class="bi bi-chevron-double-left menu-arrow text-white"></i>
                      </a>
                      <ul class="sub">
                        @if($canproduct_review)
                          <li class="nav-item">
                            <a href="{{ route('admin.product.review', ['productTypeName' => 'Product']) }}" class="nav-link {{isset($nav) && $nav=='productReview' ? 'active':''}}">
                              List
                            </a>
                          </li>
                        @endif
                        @if($canproduct_reviewadd)
                          <li class="nav-item">
                            <a href="{{ route('admin.product.review', ['productTypeName' => 'Product']) }}" class="nav-link {{isset($nav) && $nav=='productReviewadd' ? 'active':''}}">
                              Add New
                            </a>
                          </li>
                        @endif
                        @if($canproduct_review)
                          <li class="nav-item">
                            <a href="{{ route('admin.product.review', ['productTypeName' => 'Product', 'status' => 'inactive']) }}" class="nav-link {{isset($nav) && $nav=='productReviewpending' ? 'active':''}}">
                              Pending
                            </a>
                          </li>
                        @endif
                      </ul>
                    </li>
                  @endif
                </ul>
              </li>
            @endif

            @php
              $navArray = ['serviceProductCategory', 'serviceProductCategoryadd', 'serviceProduct', 'serviceProductadd', 'serviceProductReview', 'serviceProductReviewadd', 'serviceProductReviewpending'];
            @endphp
            @if($canservice_product_category || $canservice_product_categoryadd || $canservice_product || $canservice_productadd || $canservice_product_review || $canservice_product_reviewadd)
              <li class="nav-item nav-dropdown sub-menu-nav">
                <a href="javascript:void(0)" class="nav-link nav-dropdown-toggle {{$nav && in_array($nav, $navArray) ? 'active' : ''}}">
                  Service Products<i class="bi bi-chevron-double-left menu-arrow text-white"></i>
                </a>
                <ul class="sub">
                  @if($canservice_product)
                    <li class="nav-item nav-dropdown sub-menu-nav">
                      <a href="{{ route('admin.product.index', ['Service']) }}" class="nav-link {{isset($nav) && $nav=='serviceProduct' ? 'active':''}}">
                        List
                      </a>
                    </li>
                  @endif
                  @if($canservice_productadd)
                    <li class="nav-item nav-dropdown sub-menu-nav">
                      <a href="{{ route('admin.product.add', ['Service']) }}" class="nav-link {{isset($nav) && $nav=='serviceProductadd' ? 'active':''}}">
                        Add New
                      </a>
                    </li>
                  @endif

                  @if($canservice_product_category || $canservice_product_categoryadd)
                    @php
                      $navArray = ['serviceProductCategory', 'serviceProductCategoryadd'];
                    @endphp
                    <li class="nav-item nav-dropdown sub-menu-nav">
                      <a href="javascript:void(0)" class="nav-link nav-dropdown-toggle {{$nav && in_array($nav, $navArray) ? 'active' : ''}}">
                        Categories <i class="bi bi-chevron-double-left menu-arrow text-white"></i>
                      </a>
                      <ul class="sub">
                        @if($canservice_product_category)
                          <li class="nav-item">
                            <a href="{{ route('admin.productcategory.index', ['en', 'Service']) }}" class="nav-link {{isset($nav) && $nav=='serviceProductCategory' ? 'active':''}}">
                              List
                            </a>
                          </li>
                        @endif
                        @if($canservice_product_categoryadd)
                          <li class="nav-item">
                            <a href="{{ route('admin.productcategory.add', ['en', 'Service']) }}" class="nav-link {{isset($nav) && $nav=='serviceProductCategoryadd' ? 'active':''}}">
                              Add New
                            </a>
                          </li>
                        @endif
                      </ul>
                    </li>
                  @endif

                  @if($canservice_product_review || $canservice_product_reviewadd)
                    @php
                      $navArray = ['serviceProductReview', 'serviceProductReviewadd', 'serviceProductReviewpending'];
                    @endphp
                    <li class="nav-item nav-dropdown sub-menu-nav">
                      <a href="javascript:void(0)" class="nav-link nav-dropdown-toggle {{$nav && in_array($nav, $navArray) ? 'active' : ''}}">
                        Reviews <i class="bi bi-chevron-double-left menu-arrow text-white"></i>
                      </a>
                      <ul class="sub">
                        @if($canservice_product_review)
                          <li class="nav-item">
                            <a href="{{ route('admin.product.review', ['productTypeName' => 'Service']) }}" class="nav-link {{isset($nav) && $nav=='serviceProductReview' ? 'active':''}}">
                              List
                            </a>
                          </li>
                        @endif
                        @if($canservice_product_reviewadd)
                          <li class="nav-item">
                            <a href="{{ route('admin.product.review', ['productTypeName' => 'Service']) }}" class="nav-link {{isset($nav) && $nav=='serviceProductReviewadd' ? 'active':''}}">
                              Add New
                            </a>
                          </li>
                        @endif
                        @if($canservice_product_review)
                          <li class="nav-item">
                            <a href="{{ route('admin.product.review', ['productTypeName' => 'Service', 'status' => 'inactive']) }}" class="nav-link {{isset($nav) && $nav=='serviceProductReviewpending' ? 'Service':''}}">
                              Pending
                            </a>
                          </li>
                        @endif
                      </ul>
                    </li>
                  @endif
                </ul>
              </li>
            @endif

            @php
              $navArray = ['poojaProductCategory', 'poojaProductCategoryadd', 'poojaProduct', 'poojaProductadd', 'poojaProductReview', 'poojaProductReviewadd', 'poojaProductReviewpending'];
            @endphp
            @if($canpooja_product_category || $canpooja_product_categoryadd || $canpooja_product || $canpooja_productadd || $canpooja_product_review || $canpooja_product_reviewadd)
              <li class="nav-item nav-dropdown sub-menu-nav">
                <a href="javascript:void(0)" class="nav-link nav-dropdown-toggle {{$nav && in_array($nav, $navArray) ? 'active' : ''}}">
                  Pooja Products<i class="bi bi-chevron-double-left menu-arrow text-white"></i>
                </a>
                <ul class="sub">
                  @if($canpooja_product)
                    <li class="nav-item nav-dropdown sub-menu-nav">
                      <a href="{{ route('admin.product.index', ['Pooja']) }}" class="nav-link {{isset($nav) && $nav=='poojaProduct' ? 'active':''}}">
                        List
                      </a>
                    </li>
                  @endif
                  @if($canpooja_productadd)
                    <li class="nav-item nav-dropdown sub-menu-nav">
                      <a href="{{ route('admin.product.add', ['Pooja']) }}" class="nav-link {{isset($nav) && $nav=='poojaProductadd' ? 'active':''}}">
                        Add New
                      </a>
                    </li>
                  @endif

                  @if($canpooja_product_category || $canpooja_product_categoryadd)
                    @php
                      $navArray = ['poojaProductCategory', 'poojaProductCategoryadd'];
                    @endphp
                    <li class="nav-item nav-dropdown sub-menu-nav">
                      <a href="javascript:void(0)" class="nav-link nav-dropdown-toggle {{$nav && in_array($nav, $navArray) ? 'active' : ''}}">
                        Categories <i class="bi bi-chevron-double-left menu-arrow text-white"></i>
                      </a>
                      <ul class="sub">
                        @if($canpooja_product_category)
                          <li class="nav-item">
                            <a href="{{ route('admin.productcategory.index', ['en', 'Pooja']) }}" class="nav-link {{isset($nav) && $nav=='poojaProductCategory' ? 'active':''}}">
                              List
                            </a>
                          </li>
                        @endif
                        @if($canpooja_product_categoryadd)
                          <li class="nav-item">
                            <a href="{{ route('admin.productcategory.add', ['en', 'Pooja']) }}" class="nav-link {{isset($nav) && $nav=='poojaProductCategoryadd' ? 'active':''}}">
                              Add New
                            </a>
                          </li>
                        @endif
                      </ul>
                    </li>
                  @endif

                  @if($canpooja_product_review || $canpooja_product_reviewadd)
                    @php
                      $navArray = ['poojaProductReview', 'poojaProductReviewadd', 'poojaProductReviewpending'];
                    @endphp
                    <li class="nav-item nav-dropdown sub-menu-nav">
                      <a href="javascript:void(0)" class="nav-link nav-dropdown-toggle {{$nav && in_array($nav, $navArray) ? 'active' : ''}}">
                        Reviews <i class="bi bi-chevron-double-left menu-arrow text-white"></i>
                      </a>
                      <ul class="sub">
                        @if($canpooja_product_review)
                          <li class="nav-item">
                            <a href="{{ route('admin.product.review', ['productTypeName' => 'Pooja']) }}" class="nav-link {{isset($nav) && $nav=='poojaProductReview' ? 'active':''}}">
                              List
                            </a>
                          </li>
                        @endif
                        @if($canpooja_product_reviewadd)
                          <li class="nav-item">
                            <a href="{{ route('admin.product.review', ['productTypeName' => 'Pooja']) }}" class="nav-link {{isset($nav) && $nav=='poojaProductReviewadd' ? 'active':''}}">
                              Add New
                            </a>
                          </li>
                        @endif
                        @if($canpooja_product_review)
                          <li class="nav-item">
                            <a href="{{ route('admin.product.review', ['productTypeName' => 'Pooja', 'status' => 'inactive']) }}" class="nav-link {{isset($nav) && $nav=='poojaProductReviewpending' ? 'active':''}}">
                              Pending
                            </a>
                          </li>
                        @endif
                      </ul>
                    </li>
                  @endif
                </ul>
              </li>
            @endif

            @php
              $navArray = ['hsn', 'hsnadd'];
            @endphp
            @if($canhsn || $canhsnadd)
              <li class="nav-item nav-dropdown sub-menu-nav">
                <a href="javascript:void(0)" class="nav-link nav-dropdown-toggle {{$nav && in_array($nav, $navArray) ? 'active' : ''}}">
                  Hsn<i class="bi bi-chevron-double-left menu-arrow text-white"></i>
                </a>
                <ul class="sub">
                  @if($canhsn)
                    <li class="nav-item nav-dropdown sub-menu-nav">
                      <a href="{{ route('admin.hsn.index') }}" class="nav-link {{isset($nav) && $nav=='hsn' ? 'active':''}}">
                        List
                      </a>
                    </li>
                  @endif
                  @if($canhsnadd)
                    <li class="nav-item nav-dropdown sub-menu-nav">
                      <a href="{{ route('admin.hsn.add') }}" class="nav-link {{isset($nav) && $nav=='hsnadd' ? 'active':''}}">
                        Add New
                      </a>
                    </li>
                  @endif
                </ul>
              </li>
            @endif

            <!-- form fields starts -->
            @php
              $navArray = ['form'];
            @endphp
            @if($canform_field)
              <li class="nav-item nav-dropdown sub-menu-nav">
                <a href="javascript:void(0)" class="nav-link nav-dropdown-toggle {{$nav && in_array($nav, $navArray) ? 'active' : ''}}">
                  Form Fields<i class="bi bi-chevron-double-left menu-arrow text-white"></i>
                </a>
                <ul class="sub">
                  @if($canform_field)
                    <li class="nav-item nav-dropdown sub-menu-nav">
                      <a href="{{ route('admin.form.index') }}" class="nav-link {{isset($nav) && $nav=='form' ? 'active':''}}">
                        List
                      </a>
                    </li>
                  @endif
                </ul>
              </li>
            @endif
            <!-- form fields end -->
          </ul>
        </li>
      @endif
      <!-- product managements end -->

      <!-- sales start -->
      @php
        $canorder = adminCan('order','any');
        $canuser_recharge = adminCan('user-recharge','any');
        $caninvoice = adminCan('invoice','any');
        $cancartitem = adminCan('cartitem','any');
      @endphp
      @php
        $navArray = ["order", "shipped", "delivered", "cancel", "user_recharge", "invoice", "cartitems"];
      @endphp
      @if($canorder || $canuser_recharge || $caninvoice || $cancartitem)
        <li class="nav-item nav-item nav-dropdown nav-main-li {{isset($nav) && in_array($nav, $navArray) ? 'open' : ''}}">
          <a href="javascript:void(0)" class="nav-link nav-dropdown-toggle {{isset($nav) && in_array($nav, $navArray) ? 'active' : ''}}">
            <i class="bi bi-currency-rupee text-white fs-18"></i>Sales<i class="bi bi-chevron-double-left menu-arrow"></i>
          </a>
          <ul class="nav nav-dropdown-items">
            <!-- orders start -->
            @if($canorder)
              @php
                $navArray = ["order", "shipped", "delivered", "cancel"];
              @endphp
              <li class="nav-item nav-dropdown sub-menu-nav">
                <a href="javascript:void(0)" class="nav-link nav-dropdown-toggle {{$nav && in_array($nav, $navArray) ? 'active' : ''}}">
                  Orders<i class="bi bi-chevron-double-left menu-arrow text-white"></i>
                </a>
                <ul class="sub">
                  @if($canorder)
                    <li class="nav-item nav-dropdown sub-menu-nav">
                      <a href="{{ route('admin.order') }}" class="nav-link {{$nav=='order' && '' == request()->status ? 'active' : ''}}">
                        All
                      </a>
                    </li>
                  @endif
                  @if($canorder)
                    <li class="nav-item nav-dropdown sub-menu-nav">
                      <a href="{{ route('admin.order', ['status'=>'shipped']) }}" class="nav-link {{$nav=='order' && 'shipped' == request()->status ? 'active' : ''}}">
                        Shipped
                      </a>
                    </li>
                  @endif
                  @if($canorder)
                    <li class="nav-item nav-dropdown sub-menu-nav">
                      <a href="{{ route('admin.order', ['status'=>'delivered']) }}" class="nav-link {{$nav=='order' && 'delivered' == request()->status ? 'active' : ''}}">
                        Delivered
                      </a>
                    </li>
                  @endif
                  @if($canorder)
                    <li class="nav-item nav-dropdown sub-menu-nav">
                      <a href="{{ route('admin.order', ['status'=>'cancel']) }}" class="nav-link {{$nav=='order' && 'cancel' == request()->status ? 'active' : ''}}">
                        Cancelled
                      </a>
                    </li>
                  @endif
                </ul>
              </li>
            @endif
            <!-- orders end -->

            <!-- cartitems start -->
            @if($cancartitem)
              @php
                $navArray = ["cartitems"];
              @endphp
              <li class="nav-item nav-dropdown sub-menu-nav">
                <a href="javascript:void(0)" class="nav-link nav-dropdown-toggle {{$nav && in_array($nav, $navArray) ? 'active' : ''}}">
                  CartItems<i class="bi bi-chevron-double-left menu-arrow text-white"></i>
                </a>
                <ul class="sub">
                  @if($cancartitem)
                    <li class="nav-item nav-dropdown sub-menu-nav">
                      <a href="{{ route('admin.customer.cartitem', [0]) }}" class="nav-link {{$nav=='cartitems' ? 'active' : ''}}">
                        List
                      </a>
                    </li>
                  @endif
                </ul>
              </li>
            @endif
            <!-- cartitems end -->

            <!-- user recharges start -->
            @if($canuser_recharge)
              @php
                $navArray = ["user_recharge"];
              @endphp
              <li class="nav-item nav-dropdown sub-menu-nav">
                <a href="javascript:void(0)" class="nav-link nav-dropdown-toggle {{$nav && in_array($nav, $navArray) ? 'active' : ''}}">
                  Customer Recharges<i class="bi bi-chevron-double-left menu-arrow text-white"></i>
                </a>
                <ul class="sub">
                  @if($caninvoice)
                    <li class="nav-item nav-dropdown sub-menu-nav">
                      <a href="{{ route('admin.user_recharge') }}" class="nav-link {{$nav=='user_recharge' ? 'active' : ''}}">
                        List
                      </a>
                    </li>
                  @endif
                </ul>
              </li>
            @endif
            <!-- user recharges end -->

            <!-- invoices start -->
            @if($caninvoice)
              @php
                $navArray = ["invoice"];
              @endphp
              <li class="nav-item nav-dropdown sub-menu-nav">
                <a href="javascript:void(0)" class="nav-link nav-dropdown-toggle {{$nav && in_array($nav, $navArray) ? 'active' : ''}}">
                  Invoice<i class="bi bi-chevron-double-left menu-arrow text-white"></i>
                </a>
                <ul class="sub">
                  @if($caninvoice)
                    <li class="nav-item nav-dropdown sub-menu-nav">
                      <a href="{{ route('admin.invoice') }}" class="nav-link {{$nav=='invoice' ? 'active' : ''}}">
                        List
                      </a>
                    </li>
                  @endif
                </ul>
              </li>
            @endif
            <!-- invoices end -->
          </ul>
        </li>
      @endif
      <!-- sales end -->

      <!-- customer managements start -->
      @php
        $cancustomer = adminCan('customer','any');
        $cancustomeradd = adminCan('customer','add');
      @endphp
      @php
        $navArray = ['customer', 'customeradd'];
      @endphp
      @if($cancustomer || $cancustomeradd)
        <li class="nav-item nav-item nav-dropdown nav-main-li {{isset($nav) && in_array($nav, $navArray) ? 'open' : ''}}">
          <a href="javascript:void(0)" class="nav-link nav-dropdown-toggle {{isset($nav) && in_array($nav, $navArray) ? 'active' : ''}}">
            <i class="bi bi-people fs-18 text-white"></i>Customers Management<i class="bi bi-chevron-double-left menu-arrow"></i>
          </a>
          <ul class="nav nav-dropdown-items">
            @php
              $navArray = ['customer', 'customeradd'];
            @endphp
            @if($cancustomer || $cancustomeradd)
              <li class="nav-item nav-dropdown sub-menu-nav">
                <a href="javascript:void(0)" class="nav-link nav-dropdown-toggle {{$nav && in_array($nav, $navArray) ? 'active' : ''}}">
                  Customer<i class="bi bi-chevron-double-left menu-arrow text-white"></i>
                </a>
                <ul class="sub">
                  @if($cancustomer)
                    <li class="nav-item nav-dropdown sub-menu-nav">
                      <a href="{{ route('admin.customer.index') }}" class="nav-link {{isset($nav) && $nav=='customer' ? 'active':''}}">
                        List
                      </a>
                    </li>
                  @endif
                  @if($cancustomeradd)
                    <li class="nav-item nav-dropdown sub-menu-nav">
                      <a href="{{ route('admin.customer.add') }}" class="nav-link {{isset($nav) && $nav=='customeradd' ? 'active':''}}">
                        Add New
                      </a>
                    </li>
                  @endif
                </ul>
              </li>
            @endif
          </ul>
        </li>
      @endif
      <!-- customer managements end -->

      <!-- marketing and promotions start -->
      @php
        $cancoupon = adminCan('coupon','any');
        $cancouponadd = adminCan('coupon','add');
        $cangift = adminCan('gift','any');
        $cangiftadd = adminCan('gift','add');
      @endphp
      @php
        $navArray = ['coupons', 'couponsadd', "gift", "giftadd"];
      @endphp
      @if($cancoupon || $cancouponadd || $cangift || $cangiftadd)
        <li class="nav-item nav-item nav-dropdown nav-main-li {{isset($nav) && in_array($nav, $navArray) ? 'open' : ''}}">
          <a href="javascript:void(0)" class="nav-link nav-dropdown-toggle {{isset($nav) && in_array($nav, $navArray) ? 'active' : ''}}">
            <i class="bi bi-bag-plus text-white fs-18"></i>Marketing and Promotions<i class="bi bi-chevron-double-left menu-arrow"></i>
          </a>
          <ul class="nav nav-dropdown-items">
            <!-- coupons start -->
            @if($cancoupon || $cancouponadd)
              @php
                $navArray = ['coupons', 'couponsadd'];
              @endphp
              <li class="nav-item nav-dropdown sub-menu-nav">
                <a href="javascript:void(0)" class="nav-link nav-dropdown-toggle {{$nav && in_array($nav, $navArray) ? 'active' : ''}}">
                  Coupons<i class="bi bi-chevron-double-left menu-arrow text-white"></i>
                </a>
                <ul class="sub">
                  @if($cancoupon)
                    <li class="nav-item nav-dropdown sub-menu-nav">
                      <a href="{{ route('admin.coupons.index') }}" class="nav-link {{isset($nav) && $nav=='coupons' ? 'active':''}}">
                        List
                      </a>
                    </li>
                  @endif
                  @if($cancouponadd)
                    <li class="nav-item nav-dropdown sub-menu-nav">
                      <a href="{{ route('admin.coupons.add') }}" class="nav-link {{isset($nav) && $nav=='couponsadd' ? 'active':''}}">
                        Add New
                      </a>
                    </li>
                  @endif
                </ul>
              </li>
            @endif
            <!-- coupons end -->

            <!-- gifts start -->
            @if($cangift || $cangiftadd)
              @php
                $navArray = ["gift", "giftadd"];
              @endphp
              <li class="nav-item nav-dropdown sub-menu-nav">
                <a href="javascript:void(0)" class="nav-link nav-dropdown-toggle {{$nav && in_array($nav, $navArray) ? 'active' : ''}}">
                  Gifts<i class="bi bi-chevron-double-left menu-arrow text-white"></i>
                </a>
                <ul class="sub">
                  @if($cangift)
                    <li class="nav-item nav-dropdown sub-menu-nav">
                      <a href="{{ route('admin.gift.index') }}" class="nav-link {{isset($nav) && $nav=='gift' ? 'active':''}}">
                        List
                      </a>
                    </li>
                  @endif
                  @if($cangiftadd)
                    <li class="nav-item nav-dropdown sub-menu-nav">
                      <a href="{{ route('admin.gift.add') }}" class="nav-link {{isset($nav) && $nav=='giftadd' ? 'active':''}}">
                        Add New
                      </a>
                    </li>
                  @endif
                </ul>
              </li>
            @endif
            <!-- gifts end -->
          </ul>
        </li>
      @endif
      <!-- marketing and promotions end -->

      <!-- cms management  start -->
      @php
        $canbanner = adminCan('banner','any');
        $cancms = adminCan('cms','any');
        $canblog = adminCan('blog','any');
        $canblog_category = adminCan('blog-category','any');
        $canfaq = adminCan('faq','any');
        $cantext_testimonial = adminCan('text-testimonial','any');
        $canvideo_testimonial = adminCan('video-testimonial','any');
      @endphp
      @php
        $navArray = ['banner', 'cms', "article", "Category", "faq", "testimonial", "video_testimonial"];
      @endphp
      @if($canbanner || $cancms || $canblog || $canblog_category || $canfaq || $cantext_testimonial || $canvideo_testimonial)
        <li class="nav-item nav-item nav-dropdown nav-main-li {{isset($nav) && in_array($nav, $navArray) ? 'open' : ''}}">
          <a href="javascript:void(0)" class="nav-link nav-dropdown-toggle {{isset($nav) && in_array($nav, $navArray) ? 'active' : ''}}">
            <i class="bi bi-info-circle text-white fs-18"></i>CMS Management <i class="bi bi-chevron-double-left menu-arrow"></i>
          </a>
          <ul class="nav nav-dropdown-items">
            <!-- banners start -->
            @if($canbanner)
              @php
                $navArray = ["banner"];
              @endphp
              <li class="nav-item nav-dropdown sub-menu-nav">
                <a href="javascript:void(0)" class="nav-link nav-dropdown-toggle {{$nav && in_array($nav, $navArray) ? 'active' : ''}}">
                  Banners<i class="bi bi-chevron-double-left menu-arrow text-white"></i>
                </a>
                <ul class="sub">
                  @if($canbanner)
                    <li class="nav-item nav-dropdown sub-menu-nav">
                      <a href="{{ route('admin.banner.index') }}" class="nav-link {{isset($nav) && $nav=='banner' ? 'active':''}}">
                        List
                      </a>
                    </li>
                  @endif
                </ul>
              </li>
            @endif
            <!-- banners end -->
            <!-- pages start -->
            @if($cancms)
              @php
                $navArray = ["cms"];
              @endphp
              <li class="nav-item nav-dropdown sub-menu-nav">
                <a href="javascript:void(0)" class="nav-link nav-dropdown-toggle {{$nav && in_array($nav, $navArray) ? 'active' : ''}}">
                  Pages<i class="bi bi-chevron-double-left menu-arrow text-white"></i>
                </a>
                <ul class="sub">
                  @if($canbanner)
                    <li class="nav-item nav-dropdown sub-menu-nav">
                      <a href="{{ route('admin.cms.index') }}" class="nav-link {{isset($nav) && $nav=='cms' ? 'active':''}}">
                        List
                      </a>
                    </li>
                  @endif
                </ul>
              </li>
            @endif
            <!-- pages end -->
            <!-- blogs start -->
            @if($canblog || $canblog_category)
              @php
                $navArray = ["article", "Category"];
              @endphp
              <li class="nav-item nav-dropdown sub-menu-nav">
                <a href="javascript:void(0)" class="nav-link nav-dropdown-toggle {{$nav && in_array($nav, $navArray) ? 'active' : ''}}">
                  Blogs<i class="bi bi-chevron-double-left menu-arrow text-white"></i>
                </a>
                <ul class="sub">
                  @if($canblog)
                    <li class="nav-item nav-dropdown sub-menu-nav">
                      <a href="{{ route('admin.article.index', 'en') }}" class="nav-link {{isset($nav) && $nav=='article' ? 'active':''}}">
                        List
                      </a>
                    </li>
                  @endif

                  @if($canblog_category)
                    @php
                      $navArray = ["Category"];
                    @endphp
                    <li class="nav-item nav-dropdown sub-menu-nav">
                      <a href="javascript:void(0)" class="nav-link nav-dropdown-toggle {{$nav && in_array($nav, $navArray) ? 'active' : ''}}">
                        Categories <i class="bi bi-chevron-double-left menu-arrow text-white"></i>
                      </a>
                      <ul class="sub">
                        @if($canblog_category)
                          <li class="nav-item">
                            <a href="{{ route('admin.category.index', 'en') }}" class="nav-link {{isset($nav) && $nav=='Category' ? 'active':''}}">
                              List
                            </a>
                          </li>
                        @endif
                      </ul>
                    </li>
                  @endif
                </ul>
              </li>
            @endif
            <!-- blogs end -->
            <!-- faqs start -->
            @if($canfaq)
              @php
                $navArray = ["faq"];
              @endphp
              <li class="nav-item nav-dropdown sub-menu-nav">
                <a href="javascript:void(0)" class="nav-link nav-dropdown-toggle {{$nav && in_array($nav, $navArray) ? 'active' : ''}}">
                  FAQ's<i class="bi bi-chevron-double-left menu-arrow text-white"></i>
                </a>
                <ul class="sub">
                  @if($canfaq)
                    <li class="nav-item nav-dropdown sub-menu-nav">
                      <a href="{{ route('admin.faq.index') }}" class="nav-link {{isset($nav) && $nav=='faq' ? 'active':''}}">
                        List
                      </a>
                    </li>
                  @endif
                </ul>
              </li>
            @endif
            <!-- faqs end -->
            <!-- testimonials start -->
            @if($cantext_testimonial || $canvideo_testimonial)
              @php
                $navArray = ["testimonial", "video_testimonial"];
              @endphp
              <li class="nav-item nav-dropdown sub-menu-nav">
                <a href="javascript:void(0)" class="nav-link nav-dropdown-toggle {{$nav && in_array($nav, $navArray) ? 'active' : ''}}">
                  Testimonials<i class="bi bi-chevron-double-left menu-arrow text-white"></i>
                </a>
                <ul class="sub">
                  @if($cantext_testimonial)
                    @php
                      $navArray = ["testimonial"];
                    @endphp
                    <li class="nav-item nav-dropdown sub-menu-nav">
                      <a href="javascript:void(0)" class="nav-link nav-dropdown-toggle {{$nav && in_array($nav, $navArray) ? 'active' : ''}}">
                        Text <i class="bi bi-chevron-double-left menu-arrow text-white"></i>
                      </a>
                      <ul class="sub">
                        @if($cantext_testimonial)
                          <li class="nav-item">
                            <a href="{{ route('admin.testimonial.list') }}" class="nav-link {{isset($nav) && $nav=='testimonial' ? 'active':''}}">
                              List
                            </a>
                          </li>
                        @endif
                      </ul>
                    </li>
                  @endif
                  @if($canvideo_testimonial)
                    @php
                      $navArray = ["video_testimonial"];
                    @endphp
                    <li class="nav-item nav-dropdown sub-menu-nav">
                      <a href="javascript:void(0)" class="nav-link nav-dropdown-toggle {{$nav && in_array($nav, $navArray) ? 'active' : ''}}">
                        Video <i class="bi bi-chevron-double-left menu-arrow text-white"></i>
                      </a>
                      <ul class="sub">
                        @if($cantext_testimonial)
                          <li class="nav-item">
                            <a href="{{ route('admin.videotestimonial.list') }}" class="nav-link {{isset($nav) && $nav=='video_testimonial' ? 'active':''}}">
                              List
                            </a>
                          </li>
                        @endif
                      </ul>
                    </li>
                  @endif
                </ul>
              </li>
            @endif
            <!-- testimonials end -->
          </ul>
        </li>
      @endif
      <!-- cms management end -->

      <!-- supports start -->
      @php
        $cansupport = adminCan('support', 'any');
      @endphp
      @php
        $navArray = ['support'];
      @endphp
      @if($cansupport)
        <li class="nav-item nav-item nav-dropdown nav-main-li {{isset($nav) && in_array($nav, $navArray) ? 'open' : ''}}">
          <a href="javascript:void(0)" class="nav-link nav-dropdown-toggle {{isset($nav) && in_array($nav, $navArray) ? 'active' : ''}}">
            <i class="bi bi-envelope-fill text-white fs-18"></i>Supports<i class="bi bi-chevron-double-left menu-arrow"></i>
          </a>
          <ul class="nav nav-dropdown-items">
            @if($cansupport)
              <li class="nav-item nav-dropdown sub-menu-nav">
                <a href="{{ route('admin.support') }}" class="nav-link {{isset($nav) && $nav=='support' ? 'active':''}}">
                  List
                </a>
              </li>
            @endif
          </ul>
        </li>
      @endif
      <!-- supports end -->

      <!-- shippings start -->
      @php
        $canshippingweightprice = adminCan('shippingweightprice', 'any');
        $canshippingvolumeprice = adminCan('shippingvolumeprice', 'any');
        $canlocalpincode = adminCan('localpincode', 'any');
        $candomesticpincode = adminCan('domesticpincode', 'any');
      @endphp
      @php
        $navArray = ['shippingweightprice', "shippingvolumeprice", "localpincode", "domesticpincode"];
      @endphp
      @if($canshippingweightprice || $canshippingvolumeprice || $canlocalpincode || $candomesticpincode)
        <li class="nav-item nav-item nav-dropdown nav-main-li {{isset($nav) && in_array($nav, $navArray) ? 'open' : ''}}">
          <a href="javascript:void(0)" class="nav-link nav-dropdown-toggle {{isset($nav) && in_array($nav, $navArray) ? 'active' : ''}}">
            <i class="bi bi-truck text-white fs-18"></i>Shippings<i class="bi bi-chevron-double-left menu-arrow"></i>
          </a>
          <ul class="nav nav-dropdown-items">
            @if($canshippingweightprice)
              <li class="nav-item nav-dropdown sub-menu-nav">
                <a href="{{ route('admin.shippingweightprice.index') }}" class="nav-link {{isset($nav) && $nav=='shippingweightprice' ? 'active':''}}">
                  Price (Weigth)
                </a>
              </li>
            @endif
            @if($canshippingvolumeprice)
              <li class="nav-item nav-dropdown sub-menu-nav">
                <a href="{{ route('admin.shippingvolumeprice.index') }}" class="nav-link {{isset($nav) && $nav=='shippingvolumeprice' ? 'active':''}}">
                  Price (Volume)
                </a>
              </li>
            @endif
            @if($canlocalpincode)
              <li class="nav-item nav-dropdown sub-menu-nav">
                <a href="{{ route('admin.localpincode.index') }}" class="nav-link {{isset($nav) && $nav=='domesticpincode' ? 'active':''}}">
                  Pincode Local
                </a>
              </li>
            @endif
            @if($candomesticpincode)
              <li class="nav-item nav-dropdown sub-menu-nav">
                <a href="{{ route('admin.domesticpincode.index') }}" class="nav-link {{isset($nav) && $nav=='permission' ? 'active':''}}">
                  Pincode Domestic
                </a>
              </li>
            @endif
          </ul>
        </li>
      @endif
      <!-- shippings end -->

      <!-- contact enquiries start -->
      @php
        $cancontact_enquiry = adminCan('contact-enquiry', 'any');
      @endphp
      @php
        $navArray = ['enquiry'];
      @endphp
      @if($cancontact_enquiry)
        <li class="nav-item nav-item nav-dropdown nav-main-li {{isset($nav) && in_array($nav, $navArray) ? 'open' : ''}}">
          <a href="javascript:void(0)" class="nav-link nav-dropdown-toggle {{isset($nav) && in_array($nav, $navArray) ? 'active' : ''}}">
            <i class="bi bi-telephone-inbound text-white fs-18"></i>Contact Enquiries<i class="bi bi-chevron-double-left menu-arrow"></i>
          </a>
          <ul class="nav nav-dropdown-items">
            @if($cancontact_enquiry)
              <li class="nav-item nav-dropdown sub-menu-nav">
                <a href="{{ route('admin.enquiry.index') }}" class="nav-link {{isset($nav) && $nav=='enquiry' ? 'active':''}}">
                  List
                </a>
              </li>
            @endif
          </ul>
        </li>
      @endif
      <!-- contact enquiries end -->

      <!-- newsletter subscribers start -->
      @php
        $cannewsletter = adminCan('newsletter', 'any');
      @endphp
      @php
        $navArray = ['subscribers'];
      @endphp
      @if($cannewsletter)
        <li class="nav-item nav-item nav-dropdown nav-main-li {{isset($nav) && in_array($nav, $navArray) ? 'open' : ''}}">
          <a href="javascript:void(0)" class="nav-link nav-dropdown-toggle {{isset($nav) && in_array($nav, $navArray) ? 'active' : ''}}">
            <i class="bi bi-newspaper fs-18 text-white"></i>Newsletter Subscribers<i class="bi bi-chevron-double-left menu-arrow"></i>
          </a>
          <ul class="nav nav-dropdown-items">
            @if($cannewsletter)
              <li class="nav-item nav-dropdown sub-menu-nav">
                <a href="{{ route('admin.subscribers.index') }}" class="nav-link {{isset($nav) && $nav=='subscribers' ? 'active':''}}">
                  List
                </a>
              </li>
            @endif
          </ul>
        </li>
      @endif
      <!-- newsletter subscribers end -->


      <!-- notification start -->
      @php
        $cannotification = adminCan('notification', 'any');
      @endphp
      @php
        $navArray = ['notification'];
      @endphp
      @if($cannotification)
        <li class="nav-item nav-item nav-dropdown nav-main-li {{isset($nav) && in_array($nav, $navArray) ? 'open' : ''}}">
          <a href="javascript:void(0)" class="nav-link nav-dropdown-toggle {{isset($nav) && in_array($nav, $navArray) ? 'active' : ''}}">
            <i class="bi bi-bell text-white fs-18"></i>Notification<i class="bi bi-chevron-double-left menu-arrow"></i>
          </a>
          <ul class="nav nav-dropdown-items">
            @if($cannotification)
              <li class="nav-item nav-dropdown sub-menu-nav">
                <a href="{{ route('admin.notification.index', [0, 0]) }}" class="nav-link {{isset($nav) && $nav=='notification' ? 'active':''}}">
                  List
                </a>
              </li>
            @endif
          </ul>
        </li>
      @endif
      <!-- notification end -->

      <!-- role and permissions start -->
      @php
        $canadmin = adminCan('admin', 'any');
        $canrole = adminCan('role', 'any');
        $canpermission = adminCan('permission', 'any');
      @endphp
      @php
        $navArray = ['user', "role", "permission"];
      @endphp
      @if($canadmin || $canrole || $canpermission)
        <li class="nav-item nav-item nav-dropdown nav-main-li {{isset($nav) && in_array($nav, $navArray) ? 'open' : ''}}">
          <a href="javascript:void(0)" class="nav-link nav-dropdown-toggle {{isset($nav) && in_array($nav, $navArray) ? 'active' : ''}}">
            <i class="bi bi-bag-plus text-white fs-18"></i>Role and Permissions<i class="bi bi-chevron-double-left menu-arrow"></i>
          </a>
          <ul class="nav nav-dropdown-items">
            @if($canadmin)
              <li class="nav-item nav-dropdown sub-menu-nav">
                <a href="{{ route('admin.user.index') }}" class="nav-link {{isset($nav) && $nav=='user' ? 'active':''}}">
                  Users
                </a>
              </li>
            @endif
            @if($canrole)
              <li class="nav-item nav-dropdown sub-menu-nav">
                <a href="{{ route('admin.role.index') }}" class="nav-link {{isset($nav) && $nav=='role' ? 'active':''}}">
                  Roles
                </a>
              </li>
            @endif
            @if($canpermission)
              <li class="nav-item nav-dropdown sub-menu-nav">
                <a href="{{ route('admin.permission.index') }}" class="nav-link {{isset($nav) && $nav=='permission' ? 'active':''}}">
                  Permissions
                </a>
              </li>
            @endif
          </ul>
        </li>
      @endif
      <!-- role and permissions end -->

      <!-- settings start -->
      @php
        $cansettings = adminCan('settings', 'any');
      @endphp
      @php
        $navArray = ["settings"];
      @endphp
      @if($cansettings)
        <li class="nav-item nav-item nav-dropdown nav-main-li {{isset($nav) && in_array($nav, $navArray) ? 'open' : ''}}">
          <a href="javascript:void(0)" class="nav-link nav-dropdown-toggle {{isset($nav) && in_array($nav, $navArray) ? 'active' : ''}}">
            <i class="bi bi-gear-wide-connected text-white fs-18"></i>Settings<i class="bi bi-chevron-double-left menu-arrow"></i>
          </a>
          <ul class="nav nav-dropdown-items">
            @if($cansettings)
              <li class="nav-item nav-dropdown sub-menu-nav">
                <a href="{{ route('admin.settings', ['tab' => 'My Profile']) }}" class="nav-link {{isset($nav) && $nav=='My Profile' ? 'active':''}}">
                  My Profile
                </a>
              </li>
            @endif
            @if($cansettings)
              <li class="nav-item nav-dropdown sub-menu-nav">
                <a href="{{ route('admin.settings', ['tab' => 'Social']) }}" class="nav-link {{isset($nav) && $nav=='Social' ? 'active':''}}">
                  Social Links
                </a>
              </li>
            @endif
            @if($cansettings)
              <li class="nav-item nav-dropdown sub-menu-nav">
                <a href="{{ route('admin.settings', ['tab' => 'SEO']) }}" class="nav-link {{isset($nav) && $nav=='SEO' ? 'active':''}}">
                  SEO
                </a>
              </li>
            @endif
            @if($cansettings)
              <li class="nav-item nav-dropdown sub-menu-nav">
                <a href="{{ route('admin.settings', ['tab' => 'Withdraw']) }}" class="nav-link {{isset($nav) && $nav=='Withdraw' ? 'active':''}}">
                  Withdraw
                </a>
              </li>
            @endif
            @if($cansettings)
              <li class="nav-item nav-dropdown sub-menu-nav">
                <a href="{{ route('admin.settings', ['tab' => 'Api']) }}" class="nav-link {{isset($nav) && $nav=='Api' ? 'active':''}}">
                  Api
                </a>
              </li>
            @endif
            @if($cansettings)
              <li class="nav-item nav-dropdown sub-menu-nav">
                <a href="{{ route('admin.settings', ['tab' => 'Default']) }}" class="nav-link {{isset($nav) && $nav=='Default' ? 'active':''}}">
                  Default
                </a>
              </li>
            @endif
            {{--@if($cansettings)
              <li class="nav-item nav-dropdown sub-menu-nav">
                <a href="{{ route('admin.settings', ['tab' => 'Platform Charges']) }}" class="nav-link {{isset($nav) && $nav=='Platform Charges' ? 'active':''}}">
                  Platform Charges
                </a>
              </li>
            @endif--}}
            {{--@if($cansettings)
              <li class="nav-item nav-dropdown sub-menu-nav">
                <a href="{{ route('admin.settings', ['tab' => 'Third Party/Tools Charges']) }}" class="nav-link {{isset($nav) && $nav=='Third Party/Tools Charges' ? 'active':''}}">
                  Third Party/Tools Charges
                </a>
              </li>
            @endif--}}
            {{--@if($cansettings)
              <li class="nav-item nav-dropdown sub-menu-nav">
                <a href="{{ route('admin.settings', ['tab' => 'Other']) }}" class="nav-link {{isset($nav) && $nav=='Other' ? 'active':''}}">
                  Other
                </a>
              </li>
            @endif--}}
            @if($cansettings)
              <li class="nav-item nav-dropdown sub-menu-nav">
                <a href="{{ route('admin.settings', ['tab' => 'Estimated Delivery']) }}" class="nav-link {{isset($nav) && $nav=='Estimated Delivery' ? 'active':''}}">
                  Estimated Delivery
                </a>
              </li>
            @endif
          </ul>
        </li>
      @endif
      <!-- settings end -->

     </ul>
  </nav>
</div>
