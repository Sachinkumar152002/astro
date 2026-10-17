<div class="howitworks d-none d-sm-block">
    <div class="container">
        <h2 class="text-center heading">Consult with <strong class="text-warning">{{isset($astrologer) && $astrologer?$astrologer->display_name:'top astrologers'}}</strong> using 3 easy steps</h2>
        <div class="row mt-4">
            @if(auth()->guard('web')->check())
            <div class="col-sm-4 mb-2">
                <div class="howit-steps pt-3">
                    <div class="media pl-3">
                        <svg width="2em" height="2em" viewBox="0 0 16 16" class="bi bi-person text-orange"
                            fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M10 5a2 2 0 1 1-4 0 2 2 0 0 1 4 0zM8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm6 5c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10z" />
                        </svg>
                        <div class="media-body ml-4">
                            <p><a href="javascript:void(0)" class="text-orange">Hi {{auth()->user()->name}},</a> </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-4  mb-2">
                <div class="howit-steps pt-3">
                    <div class="media pl-3">
                        <svg width="2em" height="2em" viewBox="0 0 16 16" class="bi bi-person text-orange"
                            fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M0 3a2 2 0 0 1 2-2h13.5a.5.5 0 0 1 0 1H15v2a1 1 0 0 1 1 1v8.5a1.5 1.5 0 0 1-1.5 1.5h-12A2.5 2.5 0 0 1 0 12.5V3zm1 1.732V12.5A1.5 1.5 0 0 0 2.5 14h12a.5.5 0 0 0 .5-.5V5H2a1.99 1.99 0 0 1-1-.268zM1 3a1 1 0 0 0 1 1h12V2H2a1 1 0 0 0-1 1z" />
                        </svg>
                        <div class="media-body ml-4">
                            <p><a href="{{route('user.recharge')}}" class="text-orange">Add Money to wallet</a></p>
                        </div>
                    </div>
                </div>
            </div>
            @else
            <div class="col-sm-4 mb-2">
                <div class="howit-steps pt-3">
                    <div class="media pl-3">
                        <svg width="2em" height="2em" viewBox="0 0 16 16" class="bi bi-person text-orange"
                            fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M10 5a2 2 0 1 1-4 0 2 2 0 0 1 4 0zM8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm6 5c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10z" />
                        </svg>
                        <div class="media-body ml-4">
                            <p><a href="javascript:void(0)" onclick="openLoginModal()" class="text-orange">Sign Up with Planet Vedic</a> </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-4  mb-2">
                <div class="howit-steps pt-3">
                    <div class="media pl-3">
                        <svg width="2em" height="2em" viewBox="0 0 16 16" class="bi bi-person text-orange"
                            fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M0 3a2 2 0 0 1 2-2h13.5a.5.5 0 0 1 0 1H15v2a1 1 0 0 1 1 1v8.5a1.5 1.5 0 0 1-1.5 1.5h-12A2.5 2.5 0 0 1 0 12.5V3zm1 1.732V12.5A1.5 1.5 0 0 0 2.5 14h12a.5.5 0 0 0 .5-.5V5H2a1.99 1.99 0 0 1-1-.268zM1 3a1 1 0 0 0 1 1h12V2H2a1 1 0 0 0-1 1z" />
                        </svg>
                        <div class="media-body ml-4">
                            <p><a href="javascript:void(0)" onclick="openLoginModal()" class="text-orange">Add money to wallet</a></p>
                        </div>
                    </div>
                </div>
            </div>
            @endif
            <div class="col-sm-4  mb-2">
                <div class="howit-steps pt-3">
                    <div class="media pl-3">
                        <svg width="2em" height="2em" viewBox="0 0 16 16"
                            class="bi bi-person-bounding-box text-orange" fill="currentColor"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M1.5 1a.5.5 0 0 0-.5.5v3a.5.5 0 0 1-1 0v-3A1.5 1.5 0 0 1 1.5 0h3a.5.5 0 0 1 0 1h-3zM11 .5a.5.5 0 0 1 .5-.5h3A1.5 1.5 0 0 1 16 1.5v3a.5.5 0 0 1-1 0v-3a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 1-.5-.5zM.5 11a.5.5 0 0 1 .5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 1 0 1h-3A1.5 1.5 0 0 1 0 14.5v-3a.5.5 0 0 1 .5-.5zm15 0a.5.5 0 0 1 .5.5v3a1.5 1.5 0 0 1-1.5 1.5h-3a.5.5 0 0 1 0-1h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 1 .5-.5z" />
                            <path fill-rule="evenodd"
                                d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z" />
                        </svg>
                        <div class="media-body ml-4">
                            <p><a href="{{route('astrologers.index')}}" class="text-orange">Select Astrolger and click</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>