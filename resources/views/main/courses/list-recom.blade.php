<?php $courseCount = 1;?>

<div class="carousel-item">
    <div class="row">
        @foreach($courses as $course)
            <div class="col-md-4">
                <a href="{{route('course.handle', [$course->slug])}}" tabindex="0">
                    <div class="item-box-blog">
                        <div class="item-box-blog-image">
                            <figure><img alt="{{$course->slug}}"
                                         src="{{$course->image ? $course->image->image(360,209) : ''}}">
                            </figure>
                        </div>
                        <div class="item-box-blog-body">
                            <!--Heading-->
                            <div class="item-box-blog-heading">
                                <h5>{{$course->name}}</h5>
                            </div>
                            <?php if($course->slug === "smart-employment-enhancement-program" || $course->slug === "japanese-work-culture-program" ){ ?>
                            <span class="brand" style="left:5px">TOP RECOMMENDED</span>
                            <?php } else if($course->slug === "process-coordinator-program" || $course->slug === "data-entry-operator-program" || $course->slug === "software-developer-program"){ ?>
                            <span class="brand" style="left:5px">BEST SELLER</span>
                            <?php } else {?>
                            <span class="brand" style="left:5px">NEW ARRIVAL</span>
                            <?php } ?>

                            <div class="item-box-blog-data" style="padding: 0px 15px;">
                                <div class="car1">
                                    <div class="car-left-pro">
                                        <ul>
                                            <li>
                                                <i class="fa fa-clock-o"></i>
                                                {{ $course->access }} Days
                                            </li>
                                        </ul>

                                    </div>
                                    <div class="car-left-pro">
                                        <ul>
                                            <li>
                                                <img src="{{asset('rewamp/images/cart.jpg')}}" alt="{{$course->slug}}" title="">
                                            </li>
                                            <li>
                                                <img src="{{asset('rewamp/images/smart.jpg')}}" alt="{{$course->slug}}" title="">
                                            </li>
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
                                <?php } else { ?>
                                <p style="font-size:25px; color:#ff412d; text-align:center;">Coming Soon<br/><b></b></p>
                                <?php } ?>
                                <div class="car1">
                                    <div class="car-left-ratting">
                                        <ul>
                                            <li>
                                                @for($i=1; $i<=5; $i++)
                                                    <i class="fa  {{$course->rating>=$i ? 'fa-star yellow-star' : 'fa-star-o'}}"></i>
                                                @endfor
                                                (<?= $course->rating; ?>)
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="car-left-price">
                                        <ul>
                                            <li>
                                                <i class="fa fa-inr"></i>
                                                {{$course->price}}/-
                                            </li>
                                        </ul>

                                    </div>

                                </div>

                            </div>

                        </div>
                    </div>
                </a>
            </div>
            <?php if ($courseCount == 3) { ?>
    </div>
</div>
Best Course Quality
<div class="carousel-item active">
    <div class="row sticky-stopper">
        <?php } ?>
        <?php $courseCount++ ?>
        @endforeach
    </div>
</div>


{{--
@foreach($courses as $course)
    <div class="col-md-12 recomnded-courses">
        <div class="full-section">
            <div class="col-md-4">
                <div class="image-section">
                    <img class="img-responsive" src="{{$course->image ? $course->image->image(360,209) : ''}}"
                         alt="{{$course->name}}">
                    <div class="certified-section">
                        <div class="certified-text"><i class="fa fa-newspaper-o"></i> Certified by
                            : {{$course->association->short_name}} </div>
                        <div class="border-shape2"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="bg-white course-list-content">
                    <h3>{{$course->name}}</h3>
                    <div class="row">
                        <div class="col-md-5">
                            <ul class="">
                                <li><i class="fa fa-clock-o"></i> <b>Duration</b> : {{$course->access}} days</li>
                                <li>
                                    <i class="fa fa-star-0"></i> <b>Rating</b> :
                                    @for($i=1; $i<=5; $i++)
                                        <i class="fa  {{$course->rating>=$i ? 'fa-star yellow-star' : 'fa-star-o'}}"></i>
                                    @endfor
                                </li>
                            </ul>
                            <div class="price-section-list">

                                @if($course->offer_price)
                                    Offer Price :
                                    <strike><i class="fa fa-inr"></i> {{number_format($course->price)}}/-</strike>
                                    <i class="fa fa-inr"></i> {{number_format($course->offer_price)}}/-
                                @else
                                    Price :
                                    <i class="fa fa-inr"></i> {{number_format($course->price)}}/-
                                @endif
                            </div>
                        </div>
                        <div class="col-md-7">
                            <div class="btn-sections cart-login">
                                <a href="{{route('cart.item.add', [$course->slug])}}">
                                    <button class="">Buy Now</button>
                                </a>
                                <a href="{{route('course.handle', [$course->slug])}}">
                                    <button class="forgot-password">Read More</button>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endforeach
--}}
