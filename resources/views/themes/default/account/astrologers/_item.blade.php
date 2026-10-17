
<div class="col-lg-4 col-sm-6 col-12">
    <a class="rounded" href="{{route('astrologers.publicview',$astrologer->slug)}}" tabindex="0">
        <div class="card rounded">
          <div class="tag tag-ab mb-2">
            @if(isset($astrologer->call_price) && isset($astrologer->call_special_price) && $astrologer->call_price > 0 && $astrologer->call_special_price > 0)
          @php
              $discountPercentage = (($astrologer->call_price - $astrologer->call_special_price) / $astrologer->call_price) * 100;
              $discountPercentage = round($discountPercentage);
          @endphp

          <span>{{ $discountPercentage }}% Off</span>
      @else
          <span>No special pricing available.</span>
      @endif

    </div>

            <div class="row card-header gx-0 border-0">
                <div class="col-5 card_image d-flex flex-column justify-content-center gap-2">
                    <div class="live position-absolute top-10 end-10">
                        @if($astrologer->call)
                            <span class="{{onlineStatus($astrologer->call)}}"></span>
                        @elseif($astrologer->chat)
                            <span class="{{onlineStatus($astrologer->chat)}}"></span>
                        @endif
                    </div>
                    <a href="{{route('astrologers.publicview',$astrologer->slug)}}">
                        <img src="{{$astrologer->image?$astrologer->image->image(200):no_image()}}" class=" astro-img rounded-circle" alt="{{$astrologer->display_name}}">
                    </a>
                    <div class="rating">{{$astrologer->rating}}<i class="bi bi-star-fill"></i></div>
                </div>
                <div class="col-7 card_detail pt-2">

                    <a href="{{route('astrologers.publicview',$astrologer->slug)}}">
                        <h6 class="">{{mySubStr($astrologer->display_name,20)}}</h6>
                    </a>
                    <ul class="list-group list-group-flush p-0">
                        <li class="list-group-item d-flex gap-2"><i class="bi bi-translate"></i> <span class="text-break fs-8 text fs-md-6">{{mySubStr($astrologer->my_language, 20)}}</span></li>
                          <li class="list-group-item d-flex gap-2 "><i class="bi bi-award"></i><span class="text-break fs-8 text fs-md-6">{{mySubStr($astrologer->my_expertise)}}</span></li>
                          <li class="list-group-item d-flex gap-2"><i class="bi bi-mortarboard"></i><span class="text-break"> {{$astrologer->year_exp}} years</span></li>
                          <li class="list-group-item d-flex gap-2">
                              @if($astrologer->offer_price)
                                  <i class="bi bi-telephone"></i><span class="text-break"> ₹ <s>{{$astrologer->call_price}}/Min</s><br> ₹ {{$astrologer->call_special_price}}/Min</span>
                              @else
                                    <i class="bi bi-telephone"></i>  ₹ {{ $astrologer->call_price }}/min.
                              @endif
                            </span>
                          </li>
                    </ul>
                </div>
            </div>
            <!-- <div class="row">
              <div class="col-lg-12">
                <ul class="list-group list-group-flush pb-0">
                    <li class="list-group-item d-flex gap-2"><i class="bi bi-mortarboard"></i><span class="text-break"> {{$astrologer->year_exp}} years</span></li>
                    <li class="list-group-item d-flex gap-2"><span class="text-break">
                        @if($astrologer->offer_price)
                            <small><i class="bi bi-telephone"></i>  ₹ <s>{{$astrologer->call_price}}/Min</s></small>&nbsp;<span> ₹{{$astrologer->call_special_price}}/Min</span>
                        @else
                              <i class="bi bi-telephone"></i>  ₹{{ $astrologer->price }}/min.
                        @endif
                      </span>
                    </li>
                    <!-- Assuming this is for a call/chat count or another relevant metric -->
                    <!-- <li class="list-group-item"><i class="bi bi-person-check-fill"></i> 3124</li> -->
                <!-- </ul>
              </div> -->
              <!-- <div class="col-lg-4">
                <div class="tags">
                    <div class="tag-first">Best Deals</div>
                    <div class="tag-second">Offers</div>
                </div>
              </div> -->
            <!-- </div> -->

            <div class="card-body pt-0">
                <div class="footer card_ft">
                    <a class="btn btn-primary btn-sm float-shadow " href="{{route('astrologers.publicview',$astrologer->slug)}}">
                        Book Now
                    </a>
                    <ul class="icon_list">
                        @if($astrologer->call)

                            <li>
                                <a class="btn btn-circle p-1 bordedr-{{$astrologer->call==1?'success':($astrologer->call==3?'danger':'light')}}"
                                    @if($astrologer->call!=2)
                                    @if(auth()->guard('web')->check())
                                        @if(auth()->guard('web')->user()->wallet < $astrologer->per_minute_rate)
                                            href="javascript:void(0)" onclick="insufficientbalance()"
                                        @else
                                            href="{{route('astrologers.publicview',$astrologer->slug)}}?type=call "
                                        @endif
                                    @else
                                        href="javascript:void(0)" onclick="openLoginModal()"
                                    @endif
                                    @endif
                                    ><span class="rounded-circle  bg-light float-shadow cl-border"><i class="bi bi-telephone-fill text-{{$astrologer->call==1?'call':($astrologer->call==3?'busy':'light')}}"></i></span></a>
                            </li>
                        @endif
                        @if($astrologer->video)
                            <li>
                                <a class="btn btn-circle p-1  bordser-{{$astrologer->video==1?'success':($astrologer->video==3?'chat':'light')}}"
                                    @if($astrologer->video!=2)
                                    @if(auth()->guard('web')->check())
                                        @if(auth()->guard('web')->user()->wallet < $astrologer->per_minute_rate)
                                            href="javascript:void(0)" onclick="insufficientbalance() "
                                        @else
                                            href="{{route('astrologers.publicview',$astrologer->slug)}}?type=video"
                                        @endif
                                    @else
                                        href="javascript:void(0)" onclick="openLoginModal()"
                                    @endif
                                    @endif
                                    ><span class="rounded-circle bg-light float-shadow vi-border"><i class="bi text-video_icon bi-camera-video-fill text-{{$astrologer->chat==1?'success':($astrologer->chat==3?'danger':'light')}}"></i></span></a>
                            </li>
                        @endif
                          @if($astrologer->chat)
                        <li>
                            <a @if($astrologer->chat!=2)
                            @if(auth()->guard('web')->check())
                                @if(auth()->guard('web')->user()->wallet < $astrologer->per_minute_rate)
                                    href="javascript:void(0)" onclick="insufficientbalance()"
                                @else
                                    href="{{route('astrologers.publicview',$astrologer->slug)}}?type=chat"
                                @endif
                            @else
                                href="javascript:void(0)" onclick="openLoginModal()"
                            @endif
                            @endif class="btn btn-circle p-1 bordser-light">
                            <span class="rounded-circle bg-light float-shadow ch-border"><i class="bi bi-chat-fill text-chat_icon"></i></span>
                            </a>
                        </li>
                          @endif
                    </ul>
                </div>
            </div>
        </div>
    </a>
</div>
