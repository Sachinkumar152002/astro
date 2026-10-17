@if($courses)

    <section id="Courses">
        <div class="container">
            <div class="row">
                <div class="col-sm-3 prod-cont">
                    <h5>Current Courses</h5>
                    <p>Choose from over 200 online video courses with new additions published every month</p>
                    <a href="{{route('courses')}}">View All</a>
                </div>
                <div class="col-sm-9">
                    <div class="row blog">
                        <div class="col-md-12">
                            <div id="blogCarousel" class="carousel slide container-blog" data-ride="carousel">

                                <!-- Carousel items -->
                                <div class="carousel-inner">
                                    <?php $count = 1?>
                                    <div class="carousel-item active">
                                        <div class="row">
                                            @foreach($courses as $course)
                                                <div class="col-md-4">
                                                    <a href="{{$course->status ? route('course.handle', [$course->slug]) : 'javascript:void(0)'}}"
                                                       tabindex="0">
                                                        <div class="item-box-blog">
                                                            <div class="item-box-blog-image">
                                                                <figure><img alt="{{$course->slug}}"
                                                                             src="{{$course->image ? $course->image->image(360,209) : ''}}">
                                                                </figure>
                                                            </div>
                                                            <div class="item-box-blog-body">
                                                                <!--Heading-->
                                                                <div class="item-box-blog-heading">
                                                                    <h5 style="height: 35px;">{{$course->name}}</h5>
                                                                </div>
                                                                <?php if($course->slug === "smart-employment-enhancement-program" || $course->slug === "japanese-work-culture-program" ){ ?>
                                                                <span class="brand">TOP RECOMMENDED</span>
                                                                <?php } else if($course->slug === "process-coordinator-program" || $course->slug === "data-entry-operator-program" || $course->slug === "software-developer-program"){ ?>
                                                                <span class="brand">BEST SELLER</span>
                                                                <?php } else {?>
                                                                <span class="brand">NEW ARRIVAL</span>
                                                                <?php } ?>
                                                                <!--Data-->
                                                                <div class="item-box-blog-data"
                                                                     style="padding: 0px 15px;">
                                                                    <div class="car1">
                                                                        <div class="car-left-pro">
                                                                            <ul>
                                                                                <li style="margin-left:2px;">
                                                                                    <i class="fa fa-clock-o"></i> {{$course->access}}
                                                                                    Days
                                                                                </li>
                                                                            </ul>
                                                                        </div>
                                                                        <div class="car-left-pro">
                                                                            <ul>
                                                                                <li>
                                                                                    <img src="{{asset('rewamp/images/cart.jpg')}}"
                                                                                         alt="cart" title=""></li>
                                                                                <?php if($course->slug == 'japanese-work-culture-program'){?>
                                                                                <li class="sst"><img width="144"
                                                                                                     src="{{asset('rewamp/images/adts.jpg')}}"
                                                                                                     alt="adts" title="">
                                                                                </li>
                                                                                <?php } else if(($course->slug == 'process-coordinator-program') || $course->slug == 'smart-employment-enhancement-program' || $course->slug == 'data-entry-operator-program'){?>
                                                                                <li class="sst"><img width="144"
                                                                                                     src="{{asset('rewamp/images/smart.jpg')}}"
                                                                                                     alt="smart" title="">
                                                                                </li>
                                                                                <?php } else { ?>
                                                                                <li style="margin-left:0px;"><img
                                                                                            src="{{asset('rewamp/images/nass.jpg')}}"
                                                                                            alt="nass" title=""></li>
                                                                                <?php } ?>
                                                                            </ul>
                                                                        </div>
                                                                    </div>

                                                                </div>
                                                                <!--Text-->
                                                                <div class="item-box-blog-text">
                                                                    <?php if(!empty($course->job_roles)){
                                                                    $roles = str_replace('<ul>', '', $course->job_roles);
                                                                    $roles = str_replace('</ul>', '', $roles);
                                                                    $roles = str_replace('<li>', '', $roles);
                                                                    $roles = str_replace('</li>', ' | ', $roles);
                                                                    $jobRole = trim(strip_tags($roles));
                                                                    ?>
                                                                    <p>Perfect For <br/>
                                                                        <b>
                                                                            {{ strlen($jobRole) > 60 ? substr($jobRole,0,60)."..." : $jobRole }}
                                                                        </b>
                                                                    </p>
                                                                    <div class="car1">
                                                                        <div class="car-left-ratting">
                                                                            <ul>
                                                                                <li>
                                                                                    @for($i=1; $i<=5; $i++)
                                                                                        <i class="fa  {{$course->rating>=$i ? 'fa-star yellow-star' : 'fa-star-o'}}">&nbsp;</i>
                                                                                    @endfor
                                                                                    (<?= $course->rating; ?>)
                                                                                    <?php if($course->slug == 'japanese-work-culture-program'){?>
                                                                                    (857)
                                                                                    <?php } else if($course->slug == 'process-coordinator-program'){?>
                                                                                    (2,487)
                                                                                    <?php } else if($course->slug == 'smart-employment-enhancement-program'){?>
                                                                                    (231)
                                                                                    <?php } else if($course->slug == 'data-entry-operator-program'){?>
                                                                                    (1,765)
                                                                                    <?php } else if($course->slug == 'software-developer-program'){?>
                                                                                    (4,530)
                                                                                    <?php }?>
                                                                                </li>
                                                                            </ul>
                                                                        </div>
                                                                        <div class="car-left-price">
                                                                            <ul>
                                                                                <li>
                                                                                    <i class="fa fa-inr">&nbsp;</i> {{number_format($course->price)}}
                                                                                    /-
                                                                                </li>
                                                                            </ul>

                                                                        </div>

                                                                    </div>
                                                                    <?php } else { ?>
                                                                    <p style="font-size:25px; color:#ff412d; text-align:center;">
                                                                        Coming Soon
                                                                    </p>
                                                                    <div class="car1">
                                                                        <div class="car-left-price">
                                                                            <ul>
                                                                                <li>
                                                                                    <i class="fa fa-inr">&nbsp;</i> {{number_format($course->price)}}
                                                                                    /-
                                                                                </li>
                                                                            </ul>
                                                                        </div>
                                                                    </div>
                                                                    <?php } ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </a>

                                                    <?php if($course->slug != 'video-resume-training-program'){?>
                                                    <div class="courses-hover {{($count%3 == 0) ? 'right': '' }}">
                                                        <h5>{{$course->name}}</h5>

                                                        <p>Perfect For
                                                            {{-- <br/><b>{{ strlen($jobRole) > 100 ? substr($jobRole,0,100)."..." : $jobRole }}</b> --}}
                                                            <a href="{{route('course.handle', [$course->slug])."#id-fore" }}">
                                                                <small>see more</small>
                                                            </a>
                                                        </p>
                                                        <div class="listing-coures">
                                                            <h6>Includes</h6>
                                                            <ul>
                                                                <li><i class="fa fa-video-camera"
                                                                       aria-hidden="true"></i> {{$course->duration}}
                                                                    hours on-demand
                                                                    video
                                                                </li>
                                                                <li><i class="fa fa-graduation-cap"></i> Type of
                                                                    Certification :
                                                                    <?php if (($course->slug == 'process-coordinator-program') || $course->slug == 'smart-employment-enhancement-program' || $course->slug == 'data-entry-operator-program') { ?>
                                                                    <b style="font-size: 13px;color: #ff412d;">Smart Skill Tech</b>
                                                                    <?php } else if ($course->slug == 'japanese-work-culture-program') { ?>
                                                                    AOTS
                                                                    <?php } else { ?>
                                                                    NASSCOM
                                                                    <?php } ?>
                                                                </li>
                                                                <li><i class="fa fa-bullseye"></i> Full lifetime access
                                                                </li>
                                                                <li><i class="fa fa-laptop"></i> Access on mobile and
                                                                    laptop
                                                                </li>
                                                                <li><i class="fa fa-diamond" aria-hidden="true"></i>
                                                                    Certificate of Completion
                                                                </li>
                                                            </ul>
                                                        </div>

                                                        <ul class="cour-inn-aaa">
                                                            <li>
                                                                @if(Auth::guard('web')->check())
                                                                    <button onclick="$('#mini-cart-form').submit();" class="add_kart">Add to cart
                                                                    </button>
                                                                    <form class="form-horizontal" role="form" id="mini-cart-form" method="POST"
                                                                          action="{{route('mini.cart.item.add', [$course->slug])}}">
                                                                        {{ csrf_field() }}</form>
                                                                @else
                                                                    <button class="add_kart">
                                                                        <a href="{{route('cart.item.add', [$course->slug])}}">Add to cart</a>
                                                                    </button>
                                                                @endif
                                                            </li>
                                                            @if(Auth::guard('web')->check() )
                                                                @if($course->hasWishlist)
                                                                    <li class="heart"
                                                                        onclick="$('#wishlist-form-remove').submit()">
                                                                        <i class="fa fa-heart"
                                                                           style="color: #e24747;"></i>
                                                                        <span>Wishlist</Span>
                                                                    </li>
                                                                    <form id="wishlist-form-remove"
                                                                          action="{{ route('deletewishlist') }}"
                                                                          method="POST"
                                                                          style="display:none;">
                                                                        {{ csrf_field() }}
                                                                        <input type="hidden" name="user_id"
                                                                               value="{{ base64_encode(Auth::user()->id)}}">
                                                                        <input type="hidden" name="course_id"
                                                                               value="{{base64_encode($course->id)}}">
                                                                    </form>
                                                                @else
                                                                    <li class="heart"
                                                                        onclick="$('#wishlist-form').submit()"><i
                                                                                class="fa fa-heart-o"></i>
                                                                        <span>Wishlist</Span>
                                                                    </li>
                                                                    <form id="wishlist-form"
                                                                          action="{{ route('wishlist') }}" method="POST"
                                                                          style="display:none;">
                                                                        {{ csrf_field() }}
                                                                        <input type="hidden" name="user_id"
                                                                               value="{{ base64_encode(Auth::user()->id)}}">
                                                                        <input type="hidden" name="course_id"
                                                                               value="{{base64_encode($course->id)}}">
                                                                    </form>
                                                                @endif
                                                            @else
                                                                <li class="heart" onclick="openModal('login')">
                                                                    <i class="fa fa-heart-o"></i>
                                                                    <span>Wishlist</Span>
                                                                </li>
                                                            @endif
                                                        </ul>
                                                    </div>
                                                    <?php } ?>
                                                </div>
                                                <?php if ($count == 3) {?>
                                        </div>
                                    </div>
                                    <div class="carousel-item <?= ($count > 3) ? 'active' : ''; ?>">
                                        <div class="row">
                                            <?php } ?>
                                            <?php $count++;?>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>

                                <ol class="carousel-indicators">
                                    <li data-target="#blogCarousel" data-slide-to="0" class="active">&nbsp;</li>
                                    <li data-target="#blogCarousel" data-slide-to="1">&nbsp;</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endif

@push('scripts')
    <script type="text/javascript">
        var delay = 1000;
        $("#mini-cart-form").validate({
            submitHandler: function (form) {
                $('.add_kart.cart').addClass('disabled').attr('disabled', 'disabled');
                var form = $(form);
                axios.post(form.attr('action'), form.serialize())
                    .then(function (response) {
                        toastr.success(response.data.message);
                        setTimeout(function () {
                            window.location.reload();
                        }, delay);
                    })
                    .catch(function (error) {
                        $('.add_kart.cart').removeClass('disabled').removeAttr('disabled').val('Add to cart');
                        toastr.error('Ooops!Something went wrong...');
                    });
                return false;
            }
        });
    </script>
    <script type="text/javascript">
        var delay = 1000;
        $("#wishlist-form").validate({
            submitHandler: function (form) {
                $('.wislist-button').addClass('disabled').attr('disabled', 'disabled');
                var form = $(form);
                axios.post(form.attr('action'), form.serialize())
                    .then(function (response) {
                        $('.fa-heart').css("color", "#e24747");
                        //toastr.success(response.data.message);
                        toastr.success("Marked as wishlist");
                        setTimeout(function () {
                            window.location = '/account/wishlist';
                        }, delay);
                    })
                    .catch(function (error) {
                        $('.wishlist-button .fa').css("color", "#ffffff");
                        $('.wislist-button').removeClass('disabled').removeAttr('disabled');
                        //$('.wislist-button').removeClass('disabled').removeAttr('disabled').val('Add to cart');
                        toastr.error('Ooops!Something went wrong...');
                    });
                return false;
            }
        });

        $("#wishlist-form-remove").validate({
            submitHandler: function (form) {
                $('.remove-wislist-button').addClass('disabled').attr('disabled', 'disabled');
                var form = $(form);
                axios.post(form.attr('action'), form.serialize())
                    .then(function (response) {
                        $('.fa-heart').css("color", "#ffffff");
                        //toastr.success(response.data.message);
                        toastr.success("Removed from wishlist");
                        setTimeout(function () {
                            window.location = '/account/wishlist';
                        }, delay);
                    })
                    .catch(function (error) {
                        $('.remove-wislist-button .fa').css("color", "#e24747");
                        $('.remove-wislist-button').removeClass('disabled').removeAttr('disabled');
                        //$('.wislist-button').removeClass('disabled').removeAttr('disabled').val('Add to cart');
                        toastr.error('Ooops!Something went wrong...');
                    });
                return false;
            }
        });

    </script>
@endpush
