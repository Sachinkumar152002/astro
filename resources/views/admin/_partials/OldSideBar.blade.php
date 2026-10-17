<div class="sidebar">
  <nav class="sidebar-nav">
      <h5 class="text-center" style="margin-top: 24px; font-size: 24px; color: white; font-weight: 600;">Navigation</h5>
<ul class="nav">
      <li class="nav-item">
        <a href="{{route('admin.dashboard')}}" class="nav-link {{$nav && $nav=='dashboard'?'active':''}}">
        <i class="bi bi-speedometer2 text-white fs-18"></i> Dashboard </a>
      </li>
        @php
          $catalogueNav = ['Astrologer', 'AI', 'review', 'video_consultation', 'chat_consultation', 'call_consultation', 'withdraw', 'ai_chat_consultation'];
        @endphp
      @if(adminCan('astrologer','any') || adminCan('astrologer-review','any') || adminCan('withdraw','any') || adminCan('ai','any') || adminCan('ai-review','any') || adminCan('astrologer-video-consultation','any') || adminCan('astrologer-chat-consultation','any') || adminCan('astrologer-call-consultation','any') || adminCan('ai-video-consultation','any') || adminCan('ai-chat-consultation','any') || adminCan('ai-call-consultation','any') || adminCan('temporary-astrologer-data','any') || adminCan('temporary-ai-data','any') || adminCan('course-category','any') || adminCan('course','any') || adminCan('course-review','any') || adminCan('product-category','any') || adminCan('product','any') || adminCan('product-review','any'))
        <li class="nav-item nav-item nav-dropdown nav-main-li {{$nav && in_array($nav, $catalogueNav) ? 'open' : ''}}">
          <a href="javascript:void(0)" class="nav-link nav-dropdown-toggle {{$nav && in_array($nav, $catalogueNav) ? 'active' : ''}}">
            <i class="bi bi-bag-plus text-white fs-18"></i> Catalogue<i class="bi bi-chevron-double-left menu-arrow"></i>
          </a>
          @php
            $catalogueAstrologerNav = ['Astrologer', 'review'];
            $catalogueAINav = ['AI', 'review'];
          @endphp
          <ul class="nav nav-dropdown-items">
          @if(adminCan('astrologer','any') || adminCan('astrologer-review','any'))
            <li class="nav-item nav-dropdown sub-menu-nav">
              <a href="javascript:void(0)" class="nav-link nav-dropdown-toggle {{$nav && in_array($nav, $catalogueAstrologerNav) && str_contains(url()->current(), 'astrologer') ? 'active' : ''}}"> Astrologer <i class="bi bi-chevron-double-left menu-arrow text-white"></i>
              </a>
              <ul class="sub">
                @if(adminCan('astrologer','any'))
                  <li class="nav-item">
                    <a href="{{route('admin.Astrologer.index')}}" class="nav-link {{$nav && $nav=='Astrologer'?'active':''}}"> List </a>
                  </li>
                @endif
                @if(adminCan('astrologer-review','any'))
                <li class="nav-item">
                  <a href="{{route('admin.astrologer.review', ['astrologer'])}}" class="nav-link {{isset(request()->type) && request()->type=='astrologer' && $nav=='review'?'active':''}}"> Reviews </a>
                </li>
                @endif
                @if(adminCan('withdraw','any'))
                <li class="nav-item">
                  <a href="{{route('admin.withdraw.index')}}" class="nav-link {{$nav && $nav=='withdraw'?'active':''}}"> Withdraw </a>
                </li>
                @endif
              </ul>
            </li>
            @endif
            @if(adminCan('ai','any') || adminCan('ai-review','any'))
            <li class="nav-item nav-dropdown sub-menu-nav">
              <a href="javascript:void(0)" class="nav-link nav-dropdown-toggle {{$nav && in_array($nav, $catalogueAINav) && str_contains(url()->current(), 'ai') ? 'active' : ''}}"> AI Astrologer<i class="bi bi-chevron-double-left menu-arrow text-white"></i>
              </a>
              <ul class="sub">
                @if(adminCan('ai','any'))
                <li class="nav-item">
                  <a href="{{route('admin.AI.index')}}" class="nav-link {{$nav=='AI'?'active':''}}"> List </a>
                </li>
                @endif
                @if(adminCan('ai-review','any'))
                <li class="nav-item">
                  <a href="{{route('admin.astrologer.review', ['ai'])}}" class="nav-link {{isset(request()->type) && request()->type=='ai' && $nav=='review'?'active':''}}"> Reviews </a>
                </li>
                @endif
              </ul>
            </li>
            @endif
          </ul>
          @php
            $catalogueConstultationNav = ['video_consultation', 'chat_consultation', 'call_consultation', 'ai_chat_consultation'];
          @endphp
          @if(adminCan('astrologer-video-consultation','any') || adminCan('astrologer-chat-consultation','any') || adminCan('astrologer-call-consultation','any'))
          <ul class="nav nav-dropdown-items">
          @if(adminCan('astrologer-video-consultation','any') || adminCan('astrologer-chat-consultation','any') || adminCan('astrologer-call-consultation','any'))
            <li class="nav-item nav-dropdown sub-menu-nav">
              <a href="javascript:void(0)" class="nav-link nav-dropdown-toggle {{$nav && in_array($nav, $catalogueConstultationNav) && isset($userType) && $userType == 'Astrologer' ? 'active' : ''}}"> Astrologer Consultations <i class="bi bi-chevron-double-left menu-arrow"></i>
              </a>
              <ul class="sub">
              @if(adminCan('astrologer-video-consultation','any'))
                <li class="nav-item">
                  <a href="{{route('admin.consultation.video.index', ['Astrologer'])}}" class="nav-link {{isset($nav) && isset($userType) &&
                    $userType == 'Astrologer' && $nav=='video_consultation'?'active':''}}"> Video </a>
                </li>
                @endif
                @if(adminCan('astrologer-chat-consultation','any'))
                <li class="nav-item">
                  <a href="{{route('admin.consultation.chat.index', ['Astrologer'])}}" class="nav-link {{isset($nav) && isset($userType) &&
                    $userType == 'Astrologer' && $nav=='chat_consultation'?'active':''}}"> Chat </a>
                </li>
                @endif
                @if(adminCan('astrologer-call-consultation','any'))
                <li class="nav-item">
                  <a href="{{route('admin.consultation.call.index', ['Astrologer'])}}" class="nav-link {{isset($nav) && isset($userType) &&
                    $userType == 'Astrologer' && $nav=='call_consultation'?'active':''}}"> Call </a>
                </li>
                @endif
              </ul>
            </li>
            @endif
            @if(adminCan('ai-video-consultation','any') || adminCan('ai-chat-consultation','any') || adminCan('ai-call-consultation','any'))
            <li class="nav-item nav-dropdown sub-menu-nav">
              <a href="javascript:void(0)" class="nav-link nav-dropdown-toggle {{isset($nav) && in_array($nav, $catalogueConstultationNav) && isset($userType) && $userType == 'AI' ? 'active' : ''}}"> AI Consultations <i class="bi bi-chevron-double-left menu-arrow"></i>
              </a>
              <ul class="sub">
                {{--
                @if(adminCan('ai-video-consultation','any'))
                <li class="nav-item">
                  <a href="{{route('admin.consultation.video.index', ['AI'])}}" class="nav-link {{isset($nav) && isset($userType) &&
                    $userType == 'AI' && $nav=='ai_video_consultation'?'active':''}}"> Video </a>
                </li>
                @endif
                --}}
                @if(adminCan('ai-chat-consultation','any'))
                <li class="nav-item">
                  <a href="{{route('admin.aiconsultation.chat.index', ['AI'])}}" class="nav-link {{isset($nav) && isset($userType) &&
                    $userType == 'AI' && $nav=='ai_chat_consultation'?'active':''}}"> Chat </a>
                </li>
                @endif
                {{--
                @if(adminCan('ai-call-consultation','any'))
                <li class="nav-item">
                  <a href="{{route('admin.consultation.call.index', ['AI'])}}" class="nav-link {{isset($nav) && isset($userType) &&
                    $userType == 'AI' && $nav=='ai_call_consultation'?'active':''}}"> Call </a>
                </li>
                @endif
                --}}
              </ul>
            </li>
            @endif
          </ul>
          @endif
          @php
            $catalogueApprovalRequestNav = ['temp_astrologer', 'temp_ai'];
          @endphp
          @if(adminCan('temporary-astrologer-data','any') || adminCan('temporary-ai-data','any'))
          <ul class="nav nav-dropdown-items">
            <li class="nav-item nav-dropdown sub-menu-nav">
              <a href="javascript:void(0)" class="nav-link nav-dropdown-toggle {{$nav && in_array($nav, $catalogueApprovalRequestNav) ? 'active' : ''}}"> Approval Request <i class="bi bi-chevron-double-left menu-arrow"></i>
              </a>
              <ul class="sub">
                @if(adminCan('temporary-astrologer-data','any'))
                <li class="nav-item">
                  <a href="{{route('admin.temp_astrologer.index')}}" class="nav-link {{isset($nav) && $nav=='temp_astrologer'?'active':''}}"> Astrologer </a>
                </li>
                @endif
                {{--
                @if(adminCan('temporary-ai-data','any'))
                <li class="nav-item">
                  <a href="{{route('admin.temp_ai.index')}}" class="nav-link {{isset($nav) && $nav=='temp_ai'?'active':''}}"> AI Astrologer </a>
                </li>
                @endif
                --}}
              </ul>
            </li>
          </ul>
          @endif
          @php
            $catalogueELearningNav = ['course', 'courseCategory', 'courseReview'];
          @endphp
          {{--
          @if(adminCan('course-category','any') || adminCan('course','any') || adminCan('course-review','any'))
          <ul class="nav nav-dropdown-items">
            <li class="nav-item nav-dropdown sub-menu-nav">
              <a href="javascript:void(0)" class="nav-link nav-dropdown-toggle {{$nav && in_array($nav, $catalogueELearningNav) ? 'active' : ''}}"> E-Learning <i class="bi bi-chevron-double-left menu-arrow"></i>
              </a>
              <ul class="sub">
              @if(adminCan('course','any'))
                <li class="nav-item">
                  <a href="{{route('admin.course')}}" class="nav-link {{$nav=='course'?'active':''}}"> Listing </a>
                </li>
              @endif
              @if(adminCan('course-category','any'))
                <li class="nav-item">
                  <a href="{{route('admin.coursecategory.index', 'en')}}" class="nav-link {{$nav=='courseCategory'?'active':''}}"> Categories </a>
                </li>
              @endif
              @if(adminCan('course-review','any'))
                <li class="nav-item">
                  <a href="{{route('admin.course.review')}}" class="nav-link {{$nav=='courseReview'?'active':''}}"> Reviews </a>
                </li>
              @endif
              </ul>
            </li>
          </ul>
          @endif
          --}}
          @php
            $catalogueProductNav = ['product', 'productCategory', 'productReview'];
            $catalogueServiceProductNav = ['serviceProduct', 'serviceProductCategory', 'serviceProductReview'];
            $cataloguePoojaProductNav = ['poojaProduct', 'poojaProductCategory', 'poojaProductReview'];
          @endphp
          @if(adminCan('product-category','any') || adminCan('product','any') || adminCan('product-review','any'))
          <ul class="nav nav-dropdown-items">
            <li class="nav-item nav-dropdown sub-menu-nav">
              <a href="javascript:void(0)" class="nav-link nav-dropdown-toggle {{$nav && in_array($nav, $catalogueProductNav) ? 'active' : ''}}"> Product <i class="bi bi-chevron-double-left menu-arrow"></i>
              </a>
              <ul class="sub">
              @if(adminCan('product','any'))
                <li class="nav-item">
                  <a href="{{route('admin.product.index', ['Product'])}}" class="nav-link {{$nav=='product'?'active':''}}"> Listing </a>
                </li>
                @endif
                @if(adminCan('product-category','any'))
                <li class="nav-item">
                  <a href="{{route('admin.productcategory.index', ['en', 'Product'])}}" class="nav-link {{$nav=='productCategory'?'active':''}}"> Categories </a>
                </li>
                @endif
                @if(adminCan('product-review','any'))
                <li class="nav-item">
                  <a href="{{route('admin.product.review')}}" class="nav-link {{$nav=='productReview'?'active':''}}"> Reviews </a>
                </li>
                @endif
              </ul>
            </li>
            <li class="nav-item nav-dropdown sub-menu-nav">
              <a href="javascript:void(0)" class="nav-link nav-dropdown-toggle {{$nav && in_array($nav, $catalogueServiceProductNav) ? 'active' : ''}}"> Service Product <i class="bi bi-chevron-double-left menu-arrow"></i>
              </a>
              <ul class="sub">
              @if(adminCan('product','any'))
                <li class="nav-item">
                  <a href="{{route('admin.product.index', ['Service'])}}" class="nav-link {{$nav=='serviceProduct'?'active':''}}"> Listing </a>
                </li>
                @endif
                @if(adminCan('product-category','any'))
                <li class="nav-item">
                  <a href="{{route('admin.productcategory.index', ['en', 'Service'])}}" class="nav-link {{$nav=='serviceProductCategory'?'active':''}}"> Categories </a>
                </li>
                @endif
                @if(adminCan('product-review','any'))
                <li class="nav-item">
                  <a href="{{route('admin.product.review')}}" class="nav-link {{$nav=='serviceProductReview'?'active':''}}"> Reviews </a>
                </li>
                @endif
              </ul>
            </li>
            <li class="nav-item nav-dropdown sub-menu-nav">
              <a href="javascript:void(0)" class="nav-link nav-dropdown-toggle {{$nav && in_array($nav, $cataloguePoojaProductNav) ? 'active' : ''}}"> Pooja Product <i class="bi bi-chevron-double-left menu-arrow"></i>
              </a>
              <ul class="sub">
              @if(adminCan('product','any'))
                <li class="nav-item">
                  <a href="{{route('admin.product.index', ['Pooja'])}}" class="nav-link {{$nav=='poojaProduct' ? 'active' : ''}}"> Listing </a>
                </li>
                @endif
                @if(adminCan('product-category','any'))
                <li class="nav-item">
                  <a href="{{route('admin.productcategory.index', ['en', 'Pooja'])}}" class="nav-link {{$nav=='poojaProductCategory'?'active':''}}"> Categories </a>
                </li>
                @endif
                @if(adminCan('product-review','any'))
                <li class="nav-item">
                  <a href="{{route('admin.product.review')}}" class="nav-link {{$nav=='poojaProductReview'?'active':''}}"> Reviews </a>
                </li>
                @endif
              </ul>
            </li>
          </ul>
          @endif
          @php
            $catalogueHSNNav = ['hsn'];
          @endphp
          @if(adminCan('hsn','any'))
            <ul class="nav nav-dropdown-items">
              <li class="nav-item nav-dropdown sub-menu-nav">
                <a href="javascript:void(0)" class="nav-link nav-dropdown-toggle {{$nav && in_array($nav, $catalogueHSNNav) ? 'active' : ''}}"> HSN <i class="bi bi-chevron-double-left menu-arrow"></i>
                </a>
                <ul class="sub">
                  <li class="nav-item">
                    <a href="{{route('admin.hsn.index')}}" class="nav-link {{$nav=='hsn'?'active':''}}"> List </a>
                  </li>
                </ul>
              </li>
            </ul>
          @endif
        </li>
      @endif
      @if(adminCan('banner','any') || adminCan('advertisement','any') || adminCan('astrologer-advertisement','any') || adminCan('text-testimonial','any') || adminCan('video-testimonial','any') || adminCan('blog-category','any') || adminCan('blog','any') || adminCan('panchange', 'any') || adminCan('importfile', 'any'))
      <li class="nav-item nav-item nav-dropdown nav-main-li">
        <a href="javascript:void(0)" class="nav-link nav-dropdown-toggle {{$nav && $nav=='dashboard'?'active':''}}">
        <i class="bi bi-files text-white fs-18"></i>Design<i class="bi bi-chevron-double-left menu-arrow text-white"></i>
        </a>
        <ul class="nav nav-dropdown-items">
          @if(adminCan('banner','any'))
          <li class="nav-item">
            <a href="{{route('admin.banner.index')}}" class="nav-link {{$nav=='banner'?'active':''}}"> Banners </a>
          </li>
          @endif
          @if(adminCan('advertisement','any') && false)
          <li class="nav-item">
            <a href="{{route('admin.advertisement.index')}}" class="nav-link {{$nav=='advertisement'?'active':''}}"> Advertisements </a>
          </li>
          @endif
          @if(adminCan('astrologer-advertisement','any') && false)
          <li class="nav-item">
            <a href="{{route('admin.paid_advertisement.index')}}" class="nav-link {{$nav=='paid_advertisement'?'active':''}}"> Astrologer Advertisements </a>
          </li>
          @endif
          @if(adminCan('text-testimonial','any'))
          <li class="nav-item">
            <a href="{{route('admin.testimonial.list')}}" class="nav-link {{$nav=='testimonial'?'active':''}}"> Testimonial </a>
          </li>
          @endif
          @if(adminCan('video-testimonial','any'))
          <li class="nav-item">
            <a href="{{route('admin.videotestimonial.list')}}" class="nav-link {{$nav=='video_testimonial'?'active':''}}"> Video Testimonial </a>
          </li>
          @endif
          @if(adminCan('blog-category','any'))
          <li class="nav-item">
            <a href="{{route('admin.category.index', 'en')}}" class="nav-link {{strtolower($nav)=='category'?'active':''}}">Blog Categories </a>
          </li>
          @endif
          @if(adminCan('blog','any'))
          <li class="nav-item">
            <a href="{{ route('admin.article.index', 'en') }}" class="nav-link {{$nav=='article'?'active':''}}"> Blog Posts </a>
          </li>
          @endif
          {{--
          @if(adminCan('panchange', 'any'))
          <li class="nav-item">
            <a href="{{route('admin.panchange.index')}}" class="nav-link {{$nav=='panchange' ? 'active' : ''}}">Thought of the Day</a>
          </li>
          @endif
          @if(adminCan('importfile', 'any'))
          <li class="nav-item">
            <a href="{{route('admin.importfile.index')}}" class="nav-link {{$nav=='importfile' ? 'active' : ''}}">Import Files</a>
          </li>
          @endif
          --}}
        </ul>
      </li>
      @endif

      @php
        $extentionNav = ['form', 'coupons', 'gift', 'rechargeplans', 'payoutpackages', 'service', 'subscribers', 'cms', 'contact'];
      @endphp

      @if(adminCan('form-field','any') || adminCan('coupon','any') || adminCan('gift','any') || adminCan('recharge-plan','any') || adminCan('payout-package','any') || adminCan('expertise','any') || adminCan('newsletter','any') || adminCan('cms','any') || adminCan('contact-us','any'))
      <li class="nav-item nav-item nav-dropdown nav-main-li {{$nav && in_array($nav, $extentionNav) ? 'open' : ''}}">
        <a href="javascript:void(0)" class="nav-link nav-dropdown-toggle {{$nav && $nav=='dashboard'?'active':''}}">
          <i class="bi bi-diagram-2 text-white fs-18"></i>Extensions <i class="bi bi-chevron-double-left menu-arrow text-white"></i>
        </a>
        <ul class="nav nav-dropdown-items">
          @if(adminCan('form-field','any'))
          <li class="nav-item">
            <a href="{{route('admin.form.index')}}" class="nav-link {{$nav=='form'?'active':''}}"> Form Fields </a>
          </li>
          @endif
          @if(adminCan('coupon','any'))
          <li class="nav-item">
            <a href="{{route('admin.coupons.index')}}" class="nav-link {{$nav=='coupons'?'active':''}}"> Coupons </a>
          </li>
          @endif
          @if(adminCan('gift','any'))
          <li class="nav-item">
            <a href="{{route('admin.gift.index')}}" class="nav-link {{$nav=='gift'?'active':''}}"> Gifts </a>
          </li>
          @endif
          @if(adminCan('recharge-plan','any'))
          <li class="nav-item">
            <a href="{{route('admin.rechargeplans.index')}}" class="nav-link {{$nav=='rechargeplans'?'active':''}}"> Recharge Plans </a>
          </li>
          @endif
          @if(adminCan('payout-package','any'))
          <li class="nav-item">
            <a href="{{route('admin.payoutpackages.index')}}" class="nav-link {{$nav=='payoutpackages'?'active':''}}"> Payout Package </a>
          </li>
          @endif
          @if(adminCan('expertise','any'))
           <li class="nav-item">
             <a href="{{route('admin.service.index')}}" class="nav-link {{$nav=='service'?'active':''}}">
              Astrologer Categories </a>
           </li>
          @endif

          @if(adminCan('cms','any') || adminCan('contact-us','any'))
           <li class="nav-item nav-dropdown sub-menu-nav">
             <a href="javascript:void(0)" class="nav-link nav-dropdown-toggle ">Web Pages <i class="bi bi-chevron-double-left menu-arrow"></i>
             </a>
             <ul class="sub">
               @if(adminCan('cms','any'))
               <li class="nav-item">
                 <a href="{{route('admin.cms.index')}}" class="nav-link {{$nav=='cms'?'active':''}}"> All Pages </a>
               </li>
               @endif
               @php
                $cms = App\Model\Cms::where('slug', 'contact')->first();
               @endphp
              @if(adminCan('cms','any') && $cms)
               <li class="nav-item">
                 <a href="{{route('admin.cms.edit', [$cms->id])}}" class="nav-link {{$nav=='contact'?'active':''}}"> Contact Us </a>
               </li>
              @endif
             </ul>
           </li>
         @endif
        </ul>
      </li>
      @endif


      @php
        $salesNav = ['order', 'user_recharge', 'invoice', 'payment'];
      @endphp

      @if(adminCan('order','any') || adminCan('user-recharge','any') || adminCan('invoice','any') || adminCan('payment','any'))
      <li class="nav-item nav-item nav-dropdown nav-main-li {{$nav && in_array($nav, $salesNav) ? 'open' : ''}}">
        <a href="javascript:void(0)" class="nav-link nav-dropdown-toggle ">
          <i class="bi bi-currency-rupee text-white fs-18"></i></i>Sales <i class="bi bi-chevron-double-left menu-arrow"></i>
        </a>
        <ul class="nav nav-dropdown-items">
          @if(adminCan('order','any'))
           <li class="nav-item nav-dropdown sub-menu-nav">
            <a href="javascript:void(0)" class="nav-link nav-dropdown-toggle {{$nav=='order'?'active':''}}"> Orders <i class="bi bi-chevron-double-left menu-arrow"></i></a>
            {{-- href="{{route('admin.order')}}" --}}
            <ul class="sub">
              <li class="nav-item">
                <a href="{{route('admin.order', ['status'=>'pending'])}}" class="nav-link {{$nav=='order' && 'pending' == request()->status ?'active':''}}"> Pendings </a>
              </li>
              <li class="nav-item">
                <a href="{{route('admin.order', ['status'=>'shipped'])}}" class="nav-link {{$nav=='order' && 'shipped' == request()->status ?'active':''}}"> Shipped </a>
              </li>
              <li class="nav-item">
                <a href="{{route('admin.order', ['status'=>'in transit'])}}" class="nav-link {{$nav=='order' && 'in transit' == request()->status ?'active':''}}"> In Transit </a>
              </li>
              <li class="nav-item">
                <a href="{{route('admin.order', ['status'=>'dispatched'])}}" class="nav-link {{$nav=='order' && 'dispatched' == request()->status ?'active':''}}"> Dispatched </a>
              </li>
              <li class="nav-item">
                <a href="{{route('admin.order', ['status'=>'delivered'])}}" class="nav-link {{$nav=='order' && 'delivered' == request()->status ?'active':''}}"> Delivered </a>
              </li>
              <li class="nav-item">
                <a href="{{route('admin.order', ['status'=>'ordered'])}}" class="nav-link {{$nav=='order' && 'ordered' == request()->status ?'active':''}}"> Ordered </a>
              </li>
              <li class="nav-item">
                <a href="{{route('admin.order', ['status'=>'cancel'])}}" class="nav-link {{$nav=='order' && 'cancel' == request()->status ?'active':''}}"> Cancelled </a>
              </li>
            </ul>
          </li>

          @endif
          @if(adminCan('user-recharge','any'))
          <li class="nav-item">
            <a href="{{route('admin.user_recharge')}}" class="nav-link {{$nav=='user_recharge'?'active':''}}"> Recharge </a>
          </li>
          @endif
          @if(adminCan('invoice','any'))
          <li class="nav-item">
            <a href="{{route('admin.invoice')}}" class="nav-link {{$nav=='invoice'?'active':''}}"> Invoice </a>
          </li>
          @endif
          {{--
          @if(adminCan('payment','any'))
          <li class="nav-item">
            <a href="{{route('admin.payment.list.index', ['rudraksha-consultation'])}}" class="nav-link {{$nav=='rudraksha-consultation'?'active':''}}"> Payment </a>
          </li>
          @endif
          --}}
        </ul>
      </li>
      @endif

      {{--
      <li class="nav-item nav-item nav-dropdown nav-main-li">
        <a href="javascript:void(0)" class="nav-link nav-dropdown-toggle {{$nav && $nav=='dashboard'?'active':''}}">
          <i class="icon icon-speedometer"></i>Astro Page <i class="fa fa-caret-left menu-arrow"></i>
        </a>
        <ul class="nav nav-dropdown-items">
          <li class="nav-item nav-dropdown sub-menu-nav">
            <a href="javascript:void(0)" class="nav-link nav-dropdown-toggle "> Horoscope <i class="bi bi-chevron-double-left menu-arrow"></i>
            </a>
            <ul class="sub">
              <li class="nav-item">
                <a href="{{route('admin.cms.edit', [App\Model\Cms::where('slug', 'horoscopes')->first()->id])}}" class="nav-link {{$nav=='horoscopes'?'active':''}}"> Horoscopes Main Page </a>
              </li> @if(adminCan('horoscope-daily','any')) <li class="nav-item">
                <a href="{{route('admin.horoscope-daily.index')}}" class="nav-link {{$nav=='horoscope-daily'?'active':''}}"> Daily </a>
              </li> @endif @if(adminCan('horoscope-weekly','any')) <li class="nav-item">
                <a href="{{route('admin.horoscope-weekly.index')}}" class="nav-link {{$nav=='horoscope-weekly'?'active':''}}"> Weekly </a>
              </li> @endif @if(adminCan('horoscope-monthly','any')) <li class="nav-item">
                <a href="{{route('admin.horoscope-monthly.index')}}" class="nav-link {{$nav=='horoscope-monthly'?'active':''}}"> Monthly </a>
              </li> @endif @if(adminCan('horoscope-yearly','any')) <li class="nav-item">
                <a href="{{route('admin.horoscope-yearly.index')}}" class="nav-link {{$nav=='horoscope-yearly'?'active':''}}"> Yearly </a>
              </li> @endif
            </ul>
          </li>
        </ul> @if(adminCan('punchang','any')) <ul class="nav nav-dropdown-items">
          <li class="nav-item nav-dropdown sub-menu-nav">
            <a href="javascript:void(0)" class="nav-link nav-dropdown-toggle "> Punchang <i class="bi bi-chevron-double-left menu-arrow"></i>
            </a>
            <ul class="sub"> @if(adminCan('punchang','any')) <li class="nav-item">
                <a href="{{route('admin.punchang.edit', [App\Model\Punchang::where('slug', 'panchang')->where('type', 'page')->first()->id])}}" class="nav-link {{$nav=='punchang_page'?'active':''}}"> Main Page </a>
              </li>
              <li class="nav-item">
                <a href="{{route('admin.punchang.index')}}" class="nav-link {{$nav=='panchang'?'active':''}}"> Pages </a>
              </li> @endif
            </ul>
          </li>
        </ul> @endif @if(adminCan('mantra','any')) <ul class="nav nav-dropdown-items">
          <li class="nav-item nav-dropdown sub-menu-nav">
            <a href="javascript:void(0)" class="nav-link nav-dropdown-toggle "> Mantra <i class="fa fa-caret-left menu-arrow"></i>
            </a>
            <ul class="sub"> @if(adminCan('mantra','any')) <li class="nav-item">
                <a href="{{route('admin.mantra.edit', [App\Model\Mantra::where('slug', 'mantra')->where('type', 'page')->first()->id])}}" class="nav-link {{$nav=='mantra_page'?'active':''}}"> Main Page </a>
              </li>
              <li class="nav-item">
                <a href="{{route('admin.mantra.index')}}" class="nav-link {{$nav=='mantra'?'active':''}}"> Mantra Pages </a>
              </li> @endif
            </ul>
          </li>
        </ul> @endif @if(adminCan('festival','any')) <ul class="nav nav-dropdown-items">
          <li class="nav-item nav-dropdown sub-menu-nav">
            <a href="javascript:void(0)" class="nav-link nav-dropdown-toggle "> Festival <i class="fa fa-caret-left menu-arrow"></i>
            </a>
            <ul class="sub"> @if(adminCan('festival','any')) <li class="nav-item">
                <a href="{{route('admin.festival.edit', [App\Model\Festival::where('slug', 'festival')->where('type', 'page')->first()->id])}}" class="nav-link {{$nav=='festival_page'?'active':''}}"> Main Page </a>
              </li>
              <li class="nav-item">
                <a href="{{route('admin.festival.index')}}" class="nav-link {{$nav=='festival'?'active':''}}"> Pages </a>
              </li> @endif
            </ul>
          </li>
        </ul> @endif @if(adminCan('planets','any')) <ul class="nav nav-dropdown-items">
          <li class="nav-item nav-dropdown sub-menu-nav">
            <a href="javascript:void(0)" class="nav-link nav-dropdown-toggle "> Planet <i class="fa fa-caret-left menu-arrow"></i>
            </a>
            <ul class="sub"> @if(adminCan('planets','any')) <li class="nav-item">
                <a href="{{route('admin.planets.index')}}" class="nav-link {{$nav=='planets'?'active':''}}"> All Planets </a>
              </li>
              <li class="nav-item">
                <a href="{{route('admin.planets.edit', [App\Model\Planet::where('page_type', 'planet')->first()->id])}}" class="nav-link {{$nav=='planets_planets'?'active':''}}"> Planet Page </a>
              </li>
              <li class="nav-item">
                <a href="{{route('admin.planets.edit', [App\Model\Planet::where('page_type', 'transit')->first()->id])}}" class="nav-link {{$nav=='planets_transit'?'active':''}}"> Transit Page </a>
              </li>
              <li class="nav-item">
                <a href="{{route('admin.planets.edit', [App\Model\Planet::where('page_type', 'retrograde')->first()->id])}}" class="nav-link {{$nav=='planets_retrograde'?'active':''}}"> Retrograde Page </a>
              </li> @endif
            </ul>
          </li>
        </ul> @endif
      </li>
      --}}

      @if(adminCan('customer','any'))
      <li class="nav-item">
        <a href="{{route('admin.customer.index')}}" class="nav-link {{$nav=='customer'?'active':''}}">
          <i class="bi bi-people fs-18 text-white"></i> Customers </a>
        </li>
        @endif
        {{--
          @if(adminCan('zodiacsigns','any'))
          <li class="nav-item nav-dropdown">
            <a href="javascript:void(0)" class="nav-link nav-dropdown-toggle ">
              <i class="icon icon-target"></i> Zodiac Signs
              <i class="fa fa-caret-left"></i>
            </a>
       <ul class="nav-dropdown-items">
                 @if(adminCan('zodiacsigns','any'))

         <li class="nav-item">
           <a href="{{route('admin.zodiacsigns.index')}}" class="nav-link {{$nav=='zodiacsigns'?'active':''}}"> <i class="icon icon-list"></i> All Zodiac Signs </a>
      </li>
      <li class="nav-item">
        <a href="{{route('admin.cms.edit', [App\Model\Cms::where('slug', 'zodiac-signs')->first()->id])}}" class="nav-link {{$nav=='zodiac-signs'?'active':''}}">
          <i class="icon icon-list"></i> Zodiac Page </a>
      </li>
      <li class="nav-item">
        <a href="{{route('admin.cms.edit', [App\Model\Cms::where('slug', 'sunsigns')->first()->id])}}" class="nav-link {{$nav=='sunsigns'?'active':''}}">
          <i class="icon icon-list"></i> Know Your Sun Sign </a>
      </li>
      <li class="nav-item">
        <a href="{{route('admin.cms.edit', [App\Model\Cms::where('slug', 'moonsigns')->first()->id])}}" class="nav-link {{$nav=='moonsigns'?'active':''}}">
          <i class="icon icon-list"></i> Know Your Moon Sign </a>
      </li>
      <li class="nav-item">
        <a href="{{route('admin.cms.edit', [App\Model\Cms::where('slug', 'compatibility')->first()->id])}}" class="nav-link {{$nav=='compatibility'?'active':''}}">
          <i class="icon icon-list"></i> Zodiac Compatibility </a>
      </li> @endif
    </ul>
    </li>
    @endif
    --}}
    {{--
      @if(adminCan('palmreading','any'))
     <li class="nav-item nav-dropdown">
       <a href="javascript:void(0)" class="nav-link nav-dropdown-toggle ">
         <i class="icon icon-target"></i> Palm Reading
         <i class="fa fa-caret-left"></i>
       </a>
       <ul class="nav-dropdown-items">
                 @if(adminCan('palmreading','any'))

         <li class="nav-item">
           <a href="{{route('admin.palmreading.edit', [App\Model\PalmReading::where('slug', 'palmreading')->where('type', 'page')->first()->id])}}" class="nav-link {{$nav=='palmreading_page'?'active':''}}"> <i class="icon icon-list"></i> Main Page </a>
    </li>
    <li class="nav-item">
      <a href="{{route('admin.palmreading.index')}}" class="nav-link {{$nav=='palmreading'?'active':''}}">
        <i class="icon icon-list"></i> Palmistry Page </a>
    </li> @endif </ul>
    </li> @endif
    --}}
    {{-- @if(adminCan('remedies','any'))
     <li class="nav-item nav-dropdown">
       <a href="javascript:void(0)" class="nav-link nav-dropdown-toggle ">
         <i class="icon icon-target"></i> Remedies
         <i class="fa fa-caret-left"></i>
       </a>
       <ul class="nav-dropdown-items">
                 @if(adminCan('remedies','any'))

         <li class="nav-item">
           <a href="{{route('admin.remedies.edit', [App\Model\Remedie::where('slug', 'remedies')->where('type', 'page')->first()->id])}}" class="nav-link {{$nav=='remedies_page'?'active':''}}"> <i class="icon icon-list"></i> Main Page </a>
    </li>
    <li class="nav-item">
      <a href="{{route('admin.remedies.index')}}" class="nav-link {{$nav=='remedies'?'active':''}}">
        <i class="icon icon-list"></i> Remedies Page </a>
    </li> @endif </ul>
    </li> @endif
    --}}
    {{--
      @if(adminCan('vastu','any'))
     <li class="nav-item nav-dropdown">
       <a href="javascript:void(0)" class="nav-link nav-dropdown-toggle ">
         <i class="icon icon-target"></i> Procedures
         <i class="fa fa-caret-left"></i>
       </a>
       <ul class="nav-dropdown-items">
         <li class="nav-item">
           <a href="{{route('admin.vastu.procedure.index')}}" class="nav-link {{$nav=='vastu_procedure'?'active':''}}"> List </a>
         </li>
       </ul>
    </li>
    @endif
    --}}


    @if(adminCan('cms-two','any') || adminCan('vastu-page','any') || adminCan('vastu-service','any') || adminCan('vastu-procedure','any') || adminCan('faq','any'))
    <li class="nav-item nav-item nav-dropdown nav-main-li">
      <a href="javascript:void(0)" class="nav-link nav-dropdown-toggle">
        <i class="bi bi-info-circle text-white fs-18"></i>Information <i class="bi bi-chevron-double-left menu-arrow text-white"></i>
      </a>
      <ul class="nav nav-dropdown-items">
        {{--
        @if(adminCan('cms-two','any'))
        <li class="nav-item">
          <a href="{{route('admin.software.index')}}" class="nav-link {{$nav=='software'?'active':''}}"> Listing </a>
        </li>
         @endif
        @if(adminCan('vastu-page','any'))
        <li class="nav-item">
          <a href="{{route('admin.vastu.main')}}" class="nav-link {{$nav=='vastu'?'active':''}}"> Vastu Page </a>
        </li>
         @endif
        @if(adminCan('vastu-page','any'))
        <li class="nav-item">
          <a href="{{route('admin.vastu.index')}}" class="nav-link {{$nav=='vastu'?'active':''}}"> Vastu Services </a>
        </li>
         @endif
        @if(adminCan('vastu-procedure','any'))
        <li class="nav-item">
          <a href="{{route('admin.vastu.procedure.index')}}" class="nav-link {{$nav=='vastu_procedure'?'active':''}}"> Vastu Procedures </a>
        </li>
         @endif
         --}}
         @if(adminCan('faq','any'))
         <li class="nav-item">
           <a href="{{route('admin.faq.index')}}" class="nav-link {{$nav=='faq'?'active':''}}">
             FAQ
           </a>
         </li>
          @endif
      </ul>
    </li>
    @endif

    @php
      $landingNav = ['landing','landing-page'];
    @endphp
    {{--
    @if(adminCan('landing','any') || adminCan('landing','any') || adminCan('landing-page','any'))
    <li class="nav-item nav-item nav-dropdown nav-main-li {{in_array($nav, $landingNav)?'open':''}}">
      <a href="javascript:void(0)" class="nav-link nav-dropdown-toggle">
        <i class="bi bi-award text-white fs-18"></i>Landing Pages <i class="bi bi-chevron-double-left menu-arrow text-white"></i>
      </a>
      <ul class="nav nav-dropdown-items">
        @if(adminCan('landing','any'))
        <li class="nav-item">
          <a href="{{route('admin.landing.index')}}" class="nav-link {{$nav=='landing'?'active':''}}">
            List
          </a>
        </li>
         @endif
         @if(adminCan('landing-page','any'))
         <li class="nav-item">
            <a href="{{route('admin.landingpage.index')}}" class="nav-link {{$nav=='landingpage'?'active':''}}">
              Landing Page
            </a>
          </li>
          @endif
      </ul>
    </li>
    @endif

    @php
      $enquiryNav = ['enquiryastrology', 'enquirycmstwo', 'enquiryvastu', 'landing-data-courses', 'landing-data-vastu-consultation-landing-page', 'landing-data-ng-software-landing-page', 'landing-data-rudraksh-landing-page', 'landing-data-gemstone-landing-page'];
    @endphp

    @if(adminCan('enquiryastrology','any') || adminCan('enquirycmstwo','any') || adminCan('enquiryvastu','any') || adminCan('landing-data-courses','any') || adminCan('landing-data-vastu-consultation-landing-page','any') || adminCan('landing-data-ng-software-landing-page','any') || adminCan('landing-data-rudraksh-landing-page','any') || adminCan('landing-data-gemstone-landing-page','any'))
    <li class="nav-item nav-item nav-dropdown nav-main-li {{in_array($nav, $enquiryNav)?'open':''}}">
      <a href="javascript:void(0)" class="nav-link nav-dropdown-toggle">
        <i class="bi bi-envelope-paper text-white fs-18"></i>Enquiry <i class="bi bi-chevron-double-left menu-arrow text-white"></i>
      </a>
      <ul class="nav nav-dropdown-items">
        @if(adminCan('enquiryastrology','any'))
          <li class="nav-item">
            <a href="{{route('admin.enquiryastrology.index')}}" class="nav-link {{$nav=='enquiryastrology'?'active':''}}"> Astrology </a>
          </li>
         @endif
         @if(adminCan('enquirycmstwo','any'))
          <li class="nav-item">
            <a href="{{route('admin.enquirycmstwo.index')}}" class="nav-link {{$nav=='enquirycmstwo'?'active':''}}"> NG Software </a>
          </li>
        @endif
        @if(adminCan('enquiryvastu','any'))
          <li class="nav-item">
            <a href="{{route('admin.enquiryvastu.index')}}" class="nav-link {{$nav=='enquiryvastu'?'active':''}}"> Vastu Shastra </a>
          </li>
        @endif
        @if(adminCan('landing-data-courses','any'))
          <li class="nav-item">
            <a href="{{route('admin.landing.page.index', ['courses'])}}" class="nav-link {{$nav=='landing-data-courses'?'active':''}}"> Courses </a>
          </li>
        @endif
        @if(adminCan('landing-data-vastu-consultation-landing-page','any'))
          <li class="nav-item">
            <a href="{{route('admin.landing.page.index', ['vastu-consultation-landing-page'])}}" class="nav-link {{$nav=='landing-data-vastu-consultation-landing-page'?'active':''}}"> Vastu Guidence (LP) </a>
          </li>
        @endif
        @if(adminCan('landing-data-ng-software-landing-page','any'))
          <li class="nav-item">
            <a href="{{route('admin.landing.page.index', ['ng-software-landing-page'])}}" class="nav-link {{$nav=='landing-data-ng-software-landing-page'?'active':''}}"> NG Software (LP) </a>
          </li>
        @endif
        @if(adminCan('landing-data-rudraksh-landing-page','any'))
          <li class="nav-item">
            <a href="{{route('admin.landing.page.index', ['rudraksh-landing-page'])}}" class="nav-link {{$nav=='landing-data-rudraksh-landing-page'?'active':''}}"> Rudraksh (LP) </a>
          </li>
        @endif
        @if(adminCan('landing-data-gemstone-landing-page','any'))
          <li class="nav-item">
            <a href="{{route('admin.landing.page.index', ['gemstone-landing-page'])}}" class="nav-link {{$nav=='landing-data-gemstone-landing-page'?'active':''}}"> Gemstone (LP) </a>
          </li>
        @endif
      </ul>
    </li>
    @endif
    --}}


     {{--
       @if(adminCan('weddings','any'))
     <li class="nav-item nav-dropdown">
       <a href="javascript:void(0)" class="nav-link nav-dropdown-toggle ">
         <i class="icon icon-target"></i> Weddings
         <i class="fa fa-caret-left"></i>
       </a>
       <ul class="nav-dropdown-items">
                 @if(adminCan('weddings','any'))

         <li class="nav-item">
           <a href="{{route('admin.weddings.edit', [App\Model\Wedding::where('slug', 'wedding')->where('type', 'page')->first()->id])}}" class="nav-link {{$nav=='weddings_page'?'active':''}}"> <i class="icon icon-list"></i> Main Page </a>
    </li>
    <li class="nav-item">
      <a href="{{route('admin.weddings.index')}}" class="nav-link {{$nav=='weddings'?'active':''}}">
        <i class="icon icon-list"></i> Weddings Pages </a>
    </li> @endif </ul>
    </li>
    @endif
    --}}
    {{--
      @if(adminCan('chakra','any'))
     <li class="nav-item nav-dropdown">
       <a href="javascript:void(0)" class="nav-link nav-dropdown-toggle ">
         <i class="icon icon-target"></i> Chakra
         <i class="fa fa-caret-left"></i>
       </a>
       <ul class="nav-dropdown-items">
                 @if(adminCan('chakra','any'))

         <li class="nav-item">
           <a href="{{route('admin.chakra.edit', [App\Model\Chakra::where('slug', 'chakra')->where('type', 'page')->first()->id])}}" class="nav-link {{$nav=='chakra_page'?'active':''}}"> <i class="icon icon-list"></i> Main Page </a>
    </li>
    <li class="nav-item">
      <a href="{{route('admin.chakra.index')}}" class="nav-link {{$nav=='chakra'?'active':''}}">
        <i class="icon icon-list"></i> Chakra Pages </a>
    </li> @endif </ul>
    </li> @endif
    --}}
    {{--
      @if(adminCan('color','any'))
     <li class="nav-item nav-dropdown">
       <a href="javascript:void(0)" class="nav-link nav-dropdown-toggle ">
         <i class="icon icon-target"></i> Color Therapy
         <i class="fa fa-caret-left"></i>
       </a>
       <ul class="nav-dropdown-items">
                 @if(adminCan('color','any'))

         <li class="nav-item">
           <a href="{{route('admin.color.edit', [App\Model\ColorTherapy::where('slug', 'color-therapy')->where('type', 'page')->first()->id])}}" class="nav-link {{$nav=='color_page'?'active':''}}"> <i class="icon icon-list"></i> Main Page </a>
    </li>
    <li class="nav-item">
      <a href="{{route('admin.color.index')}}" class="nav-link {{$nav=='color'?'active':''}}">
        <i class="icon icon-list"></i> Pages </a>
    </li> @endif </ul>
    </li> @endif
    --}}
    {{--
      @if(adminCan('meditation','any'))
     <li class="nav-item nav-dropdown">
       <a href="javascript:void(0)" class="nav-link nav-dropdown-toggle ">
         <i class="icon icon-target"></i> Meditation
         <i class="fa fa-caret-left"></i>
       </a>
       <ul class="nav-dropdown-items">
         @if(adminCan('meditation','any'))
         <li class="nav-item">
           <a href="{{route('admin.meditation.edit', [App\Model\Meditation::where('slug', 'meditation')->where('type', 'page')->first()->id])}}" class="nav-link {{$nav=='meditation_page'?'active':''}}"> <i class="icon icon-list"></i> Main Page </a>
         </li>
    <li class="nav-item">
      <a href="{{route('admin.meditation.index')}}" class="nav-link {{$nav=='meditation'?'active':''}}">
        <i class="icon icon-list"></i> Pages </a>
    </li> @endif </ul>
    </li> @endif
    --}}
    {{--
      @if(adminCan('yoga','any'))
     <li class="nav-item nav-dropdown">
       <a href="javascript:void(0)" class="nav-link nav-dropdown-toggle ">
         <i class="icon icon-target"></i> Yoga
         <i class="fa fa-caret-left"></i>
       </a>
       <ul class="nav-dropdown-items">
         @if(adminCan('yoga','any'))
         <li class="nav-item">
           <a href="{{route('admin.yoga.edit', [App\Model\Yoga::where('slug', 'yoga')->where('type', 'page')->first()->id])}}" class="nav-link {{$nav=='yoga_page'?'active':''}}"> <i class="icon icon-list"></i> Main Page </a>
         </li>
      <li class="nav-item">
        <a href="{{route('admin.yoga.index')}}" class="nav-link {{$nav=='yoga'?'active':''}}">
          <i class="icon icon-list"></i> Pages </a>
      </li>
      @endif
    </ul>
    </li>
    @endif
    --}}
  {{--
    @if(adminCan('videos','any'))
    <li class="nav-item">
      <a href="{{route('admin.videos.index')}}" class="nav-link {{$nav=='videos'?'active':''}}">
      <i class="icon icon-list"></i> Videos </a>
    </li>
    @endif
     --}}
     @if(adminCan('shippingweightprice','any') || adminCan('shippingvolumeprice','any') || adminCan('localpincode','any') || adminCan('domesticpincode','any'))
     <li class="nav-item nav-item nav-dropdown nav-main-li">
       <a href="javascript:void(0)" class="nav-link nav-dropdown-toggle">
         <i class="bi bi-truck text-white fs-18"></i>Shipping <i class="bi bi-chevron-double-left menu-arrow text-white"></i>
       </a>
       <ul class="nav nav-dropdown-items">
       @if(adminCan('shippingweightprice','any'))
         <li class="nav-item">
           <a href="{{route('admin.shippingweightprice.index')}}" class="nav-link {{$nav=='shippingweightprice'?'active':''}}"> Price (Weigth) </a>
         </li>
          @endif
         @if(adminCan('shippingvolumeprice','any'))
         <li class="nav-item">
           <a href="{{route('admin.shippingvolumeprice.index')}}" class="nav-link {{$nav=='shippingvolumeprice'?'active':''}}"> Price (Volume) </a>
         </li>
          @endif
         @if(adminCan('localpincode','any'))
         <li class="nav-item">
           <a href="{{route('admin.localpincode.index')}}" class="nav-link {{$nav=='localpincode'?'active':''}}"> Pincode Local </a>
         </li>
          @endif
         @if(adminCan('domesticpincode','any'))
         <li class="nav-item">
           <a href="{{route('admin.domesticpincode.index')}}" class="nav-link {{$nav=='domesticpincode'?'active':''}}"> Pincode Domestic </a>
         </li>
          @endif
       </ul>
     </li>
     @endif
    @if(adminCan('contact-enquiry','any'))
    <li class="nav-item">
      <a href="{{route('admin.enquiry.index')}}" class="nav-link {{$nav=='enquiry'?'active':''}}">
      <i class="bi bi-telephone-inbound text-white fs-18"></i> Contact Enquiries </a>
    </li>
    @endif
    @if(adminCan('support','any'))
    <li class="nav-item">
      <a href="{{route('admin.support')}}" class="nav-link {{$nav=='support'?'active':''}}">
      <i class="bi bi-envelope-fill text-white fs-18"></i> Support Tickets </a>
    </li>
    @endif
    @if(adminCan('newsletter','any'))
    <li class="nav-item">
      <a href="{{route('admin.subscribers.index')}}" class="nav-link {{$nav=='subscribers'?'active':''}}">
        <i class="bi bi-newspaper fs-18 text-white"></i> Newsletter Subscribers </a>
    </li>
    @endif
    @if(adminCan('notification','any'))
    <li class="nav-item">
      <a href="{{route('admin.notification.index', [0, 0])}}" class="nav-link {{$nav=='notification'?'active':''}}">
      <i class="bi bi-gear-wide-connected text-white fs-18"></i> Notification</a>
    </li>
    @endif

    @php
        $systemNav = ['language', 'user', 'role', 'module', 'permission'];
      @endphp

      @if(adminCan('language','any') || adminCan('admin','any') || adminCan('role','any') || adminCan('module','any') || adminCan('permission','any'))
      <li class="nav-item nav-item nav-dropdown nav-main-li pb-5 {{isset($nav) && in_array($nav, $systemNav)?'open':''}}">
        <a href="javascript:void(0)" class="nav-link nav-dropdown-toggle">
        <i class="bi bi-gear text-white fs-18"></i>System<i class="bi bi-chevron-double-left menu-arrow text-white"></i>
        </a>
        <ul class="nav nav-dropdown-items">
          @if(adminCan('language','any'))
           <li class="nav-item">
            <a href="{{route('admin.language.index')}}" class="nav-link {{$nav=='language'?'active':''}}"> Languages </a>
          </li>
          @endif
         </ul>
         @if(adminCan('admin','any') || adminCan('role','any') || adminCan('module','any') || adminCan('permission','any'))
         <ul class="nav nav-dropdown-items">
           <li class="nav-item nav-dropdown sub-menu-nav">
             <a href="javascript:void(0)" class="nav-link nav-dropdown-toggle ">
               Admin <i class="bi bi-chevron-double-left menu-arrow text-white"></i>
            </a>
            <ul class="sub">

              @if(adminCan('admin','any'))
              <li class="nav-item">
                <a href="{{route('admin.user.index')}}" class="nav-link {{$nav=='user'?'active':''}}"> Users </a>
              </li>
              @endif
              @if(adminCan('role','any'))
              <li class="nav-item">
                <a href="{{route('admin.role.index')}}" class="nav-link {{$nav=='role'?'active':''}}"> Roles </a>
              </li>
              @endif
              @if(adminCan('permission','any'))
              <li class="nav-item">
                <a href="{{route('admin.permission.index')}}" class="nav-link {{$nav=='permission'?'active':''}}">Permissions</a>
              </li>
              @endif
              @if(adminCan('module','any'))
              <li class="nav-item d-none">
                <a href="{{route('admin.module.index')}}" class="nav-link {{$nav=='module'?'active':''}}"> Modules </a>
              </li>
              @endif
            </ul>
          </li>
          <!-- pendings start -->
          <!-- pandit, pandit categories, cartitems -->
          <!-- pendings end -->
        </ul>
        @endif
      </li>
       @endif
  </nav>
</div>
