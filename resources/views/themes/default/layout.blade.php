<!DOCTYPE html>
<html lang="en">

<head>

    <meta name="facebook-domain-verification" content="{{ config('vrdcms.facebook_domain_verification') }}" />
    <meta charset="utf-8">
    <meta name="robots"
        content="{{ fromSetting('follow_tag') ? 'follow' : 'no follow' }}, {{ fromSetting('index_tag') ? 'index' : 'no index' }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', fromSetting('website_title'))</title>
    <meta name="description" content="@yield('description', fromSetting('description'))" />
    <meta name="keywords" content="@yield('keywords', fromSetting('keywords'))" />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">
    <meta property="og:site_name" content="{{ config('app.name') }}" />
    <meta property="og:locale" content="en_IN" />
    <meta name="twitter:card" content="{{ logo() }}" />
    @if (url()->current() == route('home'))
        <meta property="og:type" content="website" />
        <meta property="og:title"
            content="{{ config('app.name') }} — Guiding your destiny with wisdom — and empowering it with divine products." />
        <meta property="og:description"
            content="{{ config('app.name') }} offers expert astrology consultations and authentic spiritual products to bring peace, balance, and positivity to your life." />
        <meta property="og:url" content="{{ url()->current() }}/" />
        <meta property="og:image" content="{{ logo() }}" />
        <meta name="twitter:title"
            content="{{ config('app.name') }} — Guiding your destiny with wisdom — and empowering it with divine products." />
        <meta name="twitter:description"
            content="{{ config('app.name') }} offers expert astrology consultations and authentic spiritual products to bring peace, balance, and positivity to your life." />
        <meta name="twitter:image" content="{{ logo() }}" />
    @endif
    @stack('og_tags')
    <link rel="shortcut icon" href="{{ asset('assets/img/logo.svg') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/responsive.css') }}">
    <link rel="stylesheet"
        href="{{ asset('assets/css/astrovyr-header.css') }}?v={{ filemtime(public_path('assets/css/astrovyr-header.css')) }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">


    <!-- slick slider css -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/slick.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/slick-theme.css') }}" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/toastr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/jquery.desoslide.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="canonical" href="{{ url()->current() }}" />

    <!-- LightSlider CSS & JS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/lightslider@1.1.6/dist/css/lightslider.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/lightgallery@2.7.1/css/lightgallery-bundle.min.css" />
    <!-- silder-link -->
    <script>
        var sessionUserId = 0;
    </script>
    @stack('style')
    @if (request()->is('account/*'))
        <link rel="stylesheet" href="{{ asset('assets/css/account-pages-ui.css') }}?v={{ filemtime(public_path('assets/css/account-pages-ui.css')) }}">
    @endif
    @if (request()->routeIs('astrologer.dashboard', 'astrologer.profile', 'astrologer.consultations', 'astrologer.earnings', 'astrologer.puja', 'astrologer.booked.puja', 'astrologer.puja.earnings', 'astrologer.notification', 'astrologer.newsletter', 'astrologer.support', 'astrologer.referral.listing', 'astrologer.withdrawal'))
        <link rel="stylesheet" href="{{ asset('assets/css/astrologer-account.css') }}?v={{ filemtime(public_path('assets/css/astrologer-account.css')) }}">
    @endif
    @stack('header_script')


    <style media="screen">
        .notification-dot {
            position: absolute;
            top: 2px;
            /* bell ke upar adjust karne ke liye */
            right: 4px;
            /* thoda right side */
            width: 10px;
            height: 10px;
            background: red;
            border-radius: 50%;
            display: none;
            /* default hidden */
            box-shadow: 0 0 4px rgba(0, 0, 0, 0.3);
        }

        .notification-dot.active {
            display: block;
            /* agar unseen true hai to show ho */
        }

        /* body {
    padding-top: 58px;
} */


        .fs-19 {
            font-size: 1.5rem;
        }

        .fs-17 {
            font-size: 17px;
        }

        .fs-19 {
            font-size: 19px;
        }

        .category_box {
            padding-top: 30px;
        }

        .box::-webkit-scrollbar {
            width: 6px !important;
        }

        .box::-webkit-scrollbar-track {
            background: #f1f1f1 !important;
        }

        .box::-webkit-scrollbar-thumb {
            background: #ccc !important;
            border-radius: 5px !important;
        }

        .box::-webkit-scrollbar-thumb:hover {
            background: #aaa !important;
        }






        .icons {
            display: inline;
            float: right
        }

        .notification {
            position: relative;
            display: inline-block;
        }

        .number {
            height: 18px;
            width: 18px;
            background-color: #d63031;
            border-radius: 20px;
            color: white;
            text-align: center;
            position: absolute;
            top: -8px;
            left: 45%;
            font-size: 13px;
        }

        .number:empty {
            display: none;
        }

        .notBtn {
            transition: 0.5s;
            cursor: pointer
        }

        .fas {
            font-size: 25pt;
            padding-bottom: 10px;
            color: black;
            margin-right: 40px;
            margin-left: 40px;
        }

        .box {
            width: 400px;
            height: 0px;
            border-radius: 10px;
            transition: 0.5s;
            position: absolute;
            overflow-y: scroll;
            padding: 0px;
            left: -300px;
            margin-top: 5px;
            background-color: #F4F4F4;
            -webkit-box-shadow: 10px 10px 23px 0px rgba(0, 0, 0, 0.2);
            -moz-box-shadow: 10px 10px 23px 0px rgba(0, 0, 0, 0.1);
            box-shadow: 10px 10px 23px 0px rgba(0, 0, 0, 0.1);
            cursor: context-menu;
        }

        .fas:hover {
            color: #d63031;
        }

        .notBtn:hover>.box {
            height: 40vh;
            z-index: 9999;
        }

        .content {
            padding: 20px;
            color: black;
            vertical-align: middle;
            text-align: left;
        }

        .gry {
            background-color: #F4F4F4;
        }

        .top {
            color: black;
            padding: 10px
        }

        .display {
            position: relative;
        }

        .cont {
            position: absolute;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: #F4F4F4;
        }

        .cont:empty {
            display: none;
        }

        /* .header.desktop {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    transition: transform 0.5s ease-in-out;
    z-index: 100;
} */

        .header-hidden {
            transform: translateY(-100%);
        }

        .position-sticky {
            top: 54px;
            /* Adjust this value based on your header height */
            transition: top 0.5s ease-in-out;
        }

        .stick {
            text-align: center;
            display: block;
            font-size: 50pt;
            padding-top: 70px;
            padding-left: 80px
        }

        .stick:hover {
            color: black;
        }



        .sec {
            padding: 10px 10px;
            background-color: #F4F4F4;
            transition: 0.5s;
        }



        .profile {
            -webkit-clip-path: circle(50% at 50% 50%);
            clip-path: circle(50% at 50% 50%);
            width: 50px;
            float: left;
        }

        .txt {
            vertical-align: top;
            font-size: 14px;

        }

        .sub {
            font-size: 14px;
            color: grey;
        }

        .new {
            border-bottom: 1px solid lightgrey;
            border-color: lightgrey;
        }

        .sec:hover {
            background-color: #BFBFBF;
        }

        .unseen {
            width: 0px;
            height: 7px;
            font-size: 10px;
            background: #d63031;
            display: none;
            color: white;
            justify-content: center;
            align-items: center;
            border-radius: 50%;
            position: absolute;
            right: 13px;
            top: -6px;
            border: 0px solid #F7471B;

        }

        .unseen::after {
            content: '';
            position: absolute;
            top: 2px;
            right: 2px;
            width: 10px;
            height: 10px;
            background-color: #d63031;
            border-radius: 50%;
            border: 0px solid white;
        }
    </style>

    <style>
        .serch-box {
            display: none;
            /* Hide by default */
            position: absolute;
            background-color: #fff;
            border: 1px solid #ccc;
            width: 100%;
            z-index: 1000;
            height: 400px;
            overflow: auto;
        }

        .serch-box ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .serch-box ul li {
            padding-left: 35px !important;
            background-position: 5px 5px;
            cursor: pointer;
            color: #333;
        }

        .serch-box ul li:hover {}

        .other-accounts {
            position: absolute;
            top: 55px;
            background-color: #fff;
            padding: 0px;
            width: 200px;
            right: 0;
            z-index: 99999;
            border-radius: 5px;
        }

        .other-accounts li {
            height: 45px !important;
            line-height: 45px !important;
        }

        @media(max-width:768px) {
            body {
                padding-top: 60px !important;
            }

            .category_box {
                padding-top: 60px;
            }
        }
    </style>
    <style>
        .white {
            filter: brightness(0) invert(1);
        }

        .social-fixed-icons {
            position: fixed;
            top: 50%;
            right: 30px;
            transform: translateY(-50%);
            display: flex;
            flex-direction: column;
            gap: 12px;
            z-index: 9999;
        }

        .social-fixed-icons a img {
            width: 20px;
            height: 20px;
            object-fit: contain;
            transition: transform 0.3s ease;
        }

        /* Hover effect */
        .social-fixed-icons a img:hover {
            transform: scale(1.1);
        }

        /* Circle background for Facebook & Call */
        .circle-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 50%;
        }

        /* Facebook background */
        .fb-bg {
            background-color: #1877f2;
        }

        /* Call background */
        .call-bg2 {
            background-color: #800080 !important;
        }

        .m-nav {
            position: fixed;
            bottom: 0;
            width: 100%;
            max-width: 100%;
            margin: 0 auto;
            left: 0;
            right: 0;
        }

        .nav-box {
            display: flex;
            padding: 6px 0px 0px 0px;
            background-color: #fff;
            box-shadow: 0px 0px 16px 0px #4444441f;
            border-radius: 8px;
        }

        .nav-container {
            display: flex;
            width: 100%;
            list-style: none;
            justify-content: space-around;
        }

        .nav__item {
            display: flex;
            position: relative;
            padding: 2px;
        }

        .nav__item.active .nav__item-icon {

            color: #F7471B;
        }

        .nav__item.active .nav__item-text {
            color: #EF233C;
        }

        .nav__item-text {
            transform: none;
            font-size: 11px;
        }


        .nav__item-link {
            display: flex;
            flex-direction: column;
            align-items: center;
            color: #2f3046;
            text-decoration: none;
        }

        .nav__item-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.6em;
            background-color: #fff;
            border-radius: 50%;
            height: 30px;
            width: 30px;
            transition: margin-top 250ms ease-in-out, box-shadow 250ms ease-in-out;
        }

        .m-nav-box ul li a {
            gap: 0px !important;
        }


        @-webkit-keyframes border-transform {

            0%,
            100% {
                border-radius: 63% 37% 54% 46% / 55% 48% 52% 45%;
            }

            14% {
                border-radius: 40% 60% 54% 46% / 49% 60% 40% 51%;
            }

            28% {
                border-radius: 54% 46% 38% 62% / 49% 70% 30% 51%;
            }

            42% {
                border-radius: 61% 39% 55% 45% / 61% 38% 62% 39%;
            }

            56% {
                border-radius: 61% 39% 67% 33% / 70% 50% 50% 30%;
            }

            70% {
                border-radius: 50% 50% 34% 66% / 56% 68% 32% 44%;
            }

            84% {
                border-radius: 46% 54% 50% 50% / 35% 61% 39% 65%;
            }
        }

        .paginacontainer {
            height: 3000px;
        }

        /* #Progress
================================================== */

        .progress-wrap {
            position: fixed;
            right: 30px;
            bottom: 30px;
            height: 50px;
            width: 50px;
            cursor: pointer;
            display: flex;
            justify-content: center;
            align-items: center;
            border-radius: 50%;
            box-shadow: inset 0 0 0 2px rgba(0, 0, 0, 0.1);
            z-index: 999;
            opacity: 0;
            visibility: hidden;
            transform: translateY(15px);
            -webkit-transition: all 200ms linear;
            transition: all 200ms linear;
        }


        .progress-path {
            fill: none;
            stroke: orange;
            stroke-width: 3;
            stroke-linecap: round;
            stroke-linejoin: round;
            transition: stroke-dashoffset 0.3s ease;
        }

        .progress-icon {
            position: absolute;
            font-size: 24px;
            color: orange;
            z-index: 1;
        }

        .progress-wrap.active-progress {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .progress-wrap.active-progress {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .progress-wrap::after {
            position: absolute;
            font-family: 'unicons';
            content: '';
            text-align: center;
            line-height: 46px;
            font-size: 24px;
            color: rgba(0, 0, 0, 0.3);
            left: 0;
            top: 0;
            height: 46px;
            width: 46px;
            cursor: pointer;
            display: block;
            z-index: 1;
            -webkit-transition: all 200ms linear;
            transition: all 200ms linear;
        }

        .progress-wrap:hover::after {
            opacity: 0;
        }

        .progress-wrap::before {
            position: absolute;
            font-family: 'unicons';
            content: '\e84b';
            text-align: center;
            line-height: 46px;
            font-size: 24px;
            opacity: 0;
            background: black;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            left: 0;
            top: 0;
            height: 46px;
            width: 46px;
            cursor: pointer;
            display: block;
            z-index: 2;
            -webkit-transition: all 200ms linear;
            transition: all 200ms linear;
        }

        .progress-wrap:hover::before {
            opacity: 1;
        }

        .progress-wrap svg path {
            fill: none;
        }

        .progress-wrap svg.progress-circle path {
            stroke: orange;
            stroke-width: 3;
            box-sizing: border-box;
            -webkit-transition: all 200ms linear;
            transition: all 200ms linear;
        }

        @media(max-width:768px) {
            .progress-wrap {
                position: fixed;
                right: 19px;
                bottom: 37%;
            }

        }
    </style>
    <style>
        .VIpgJd-ZVi9od-ORHb-OEVmcd {
            display: none !important;
        }

        body {
            top: 0px !important;
        }
    </style>

    @if (url()->current() == route('home'))
        <script type="application/ld+json">
  {
    "@context":"https://schema.org",
    "@type":"Organization",
    "@id":"{{ url('/') }}/#org",
    "name":"{{ config('app.name') }}",
    "url":"{{ url('/') }}/",
    "logo":"{{ logo() }}",
    "sameAs":[
      "{{ fromSetting('fb_link') }}",
      "{{ fromSetting('tw_link') }}",
      "{{ fromSetting('instagram_link') }}",
      "{{ fromSetting('linkedin_link') }}",
      "{{ fromSetting('youtube_link') }}",
    ],
    "contactPoint":[{
      "@type":"ContactPoint",
      "telephone":"{{ fromSetting('contact') }}",
      "contactType":"customer service",
      "areaServed":"IN",
      "availableLanguage":["en"]
    }]
  }
  </script>
    @endif

    @if (isset($faqs) && count($faqs))
        @php
            $faqsCount = count($faqs);
        @endphp
        <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "FAQPage",
      "mainEntity": [
        @foreach($faqs as $key => $faq)
          {
            "@type": "Question",
            "name": "{{$faq->question}}",
            "acceptedAnswer": {
              "@type": "Answer",
              "text": "{{ strip_tags($faq->answer) }}"
            }
          }
          @if($key!=($faqsCount-1))
          ,
          @endif
        @endforeach
      ]
    }
</script>
    @endif

    @if (isset($breads))
        @php
            $breadsCount = count($breads);
        @endphp
        <script type="application/ld+json">
    {
      "@context": "https://schema.org/",
      "@type": "BreadcrumbList",
      "itemListElement": [
        {
          "@type": "ListItem",
          "position": 1,
          "name": "Home",
          "item": "{{ url('/') }}"
        }
        @if($breadsCount>0)
        ,
        @endif
        @foreach($breads as $key => $bread)
          {
            "@type": "ListItem",
            "position": "{{$key+2}}",
            "name": "{{ isset($bread['name'])?$bread['name']:'' }}",
            "item": "{{ isset($bread['url'])?$bread['url']:url()->current() }}"
          }
          @if($key!=($breadsCount-1))
          ,
          @endif
        @endforeach
      ]
    }
  </script>
    @endif

    @stack('schema')

    {!! fromSetting('header_script') !!}

    <style>
        @media (min-width: 992px) {

            body header.header.desktop,
            body section.header-top.desktop,
            body section.header-top.desktop #header-nav {
                background-color: #fffaf4 !important;
                color: #24143a !important;
            }

            body header.header.desktop {
                height: 84px !important;
                border-bottom-color: #eee4d8 !important;
                box-shadow: 0 1px 8px rgba(43, 27, 67, .08);
            }

            body header.header.desktop .navbar-brand a {
                gap: 9px;
            }

            body header.header.desktop .navbar-brand img {
                width: 62px !important;
                height: 62px !important;
                max-width: 62px;
                border: 0 !important;
            }

            body header.header.desktop .av-desktop-brand strong {
                color: #21183e !important;
                font-size: 27px;
            }

            body header.header.desktop .av-desktop-brand small {
                color: #514867 !important;
                font-size: 8px;
                letter-spacing: .2px;
                margin-top: 3px;
            }

            body header.header.desktop .h-search {
                width: min(36vw, 460px) !important;
            }

            body header.header.desktop .icon_list {
                display: flex !important;
                flex: 0 0 auto;
                flex-wrap: nowrap;
                align-items: center;
                justify-content: flex-end;
                gap: 8px;
                margin: 0;
                padding: 0;
                list-style: none;
                white-space: nowrap;
            }

            body header.header.desktop .icon_list>.notification {
                position: relative;
                display: flex;
                flex: 0 0 38px;
                width: 38px;
                height: 38px;
                margin: 0;
                padding: 0;
                align-items: center;
                justify-content: center;
            }

            body header.header.desktop .icon_list>.notification .notBtn {
                position: relative;
                width: 38px;
                height: 38px;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            body header.header.desktop .icon_list>.notification .notBtn>ul {
                display: flex;
                margin: 0;
                padding: 0;
                list-style: none;
            }

            body header.header.desktop .icon_list>.notification .notBtn>ul>li {
                display: flex;
                align-items: center;
                justify-content: center;
                width: 38px;
                min-width: 38px !important;
                height: 38px !important;
                margin: 0;
                padding: 0 !important;
            }

            body header.header.desktop .icon_list>li {
                display: flex;
                align-items: center;
                justify-content: center;
                min-width: 0 !important;
                height: 34px !important;
                padding: 0 5px !important;
                color: #392653 !important;
                flex: 0 0 auto;
                width: auto;
            }

            body header.header.desktop .icon_list>li i {
                color: #51366d !important;
            }

            body header.header.desktop .icon_list>.notification .bell-2.bell-desk {
                display: flex;
                align-items: center;
                justify-content: center;
                width: 38px;
                min-width: 38px !important;
                height: 38px !important;
                padding: 0 !important;
                border: 1px solid #e7ddec !important;
                border-radius: 50%;
                background: #fff !important;
            }

            body header.header.desktop .icon_list>.notification .bell-2.bell-desk i {
                color: #4c286e !important;
                font-size: 17px !important;
            }

            body header.header.desktop .icon_list>li.pointer.wallet.custom-ci {
                width: auto;
                min-width: 0 !important;
                height: 36px !important;
                padding: 0 9px !important;
                border: 1px solid #e7ddec !important;
                border-radius: 20px;
                background: #fff !important;
            }

            body header.header.desktop .icon_list>li.pointer.wallet.custom-ci>a {
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 3px;
                white-space: nowrap;
                text-decoration: none;
            }

            body header.header.desktop .icon_list>li.pointer.wallet.custom-ci>a>i:first-child,
            body header.header.desktop .icon_list>li.pointer.wallet.custom-ci .circle2 {
                display: none;
            }

            body header.header.desktop .icon_list>li.pointer.wallet.custom-ci .text-green {
                color: #51366d !important;
                font-size: 12px;
                font-weight: 700;
            }

            body header.header.desktop .icon_list>li.pointer.wallet:not(.custom-ci) {
                width: 36px;
                min-width: 36px !important;
                height: 36px !important;
                padding: 0 !important;
                border: 1px solid #e7ddec !important;
                border-radius: 50%;
                background: #fff !important;
            }

            body header.header.desktop .icon_list>li.pointer.wallet:not(.custom-ci) .k_cart i {
                color: #4c286e !important;
                font-size: 15px !important;
            }

            body header.header.desktop .icon_list>li.nav-item.log {
                width: 42px;
                min-width: 42px !important;
                height: 42px !important;
                padding: 0 !important;
            }

            body header.header.desktop .icon_list>li.nav-item.log>img {
                display: block;
                width: 40px !important;
                height: 40px !important;
                object-fit: cover;
                border: 1px solid #e7ddec;
                border-radius: 50%;
            }

            body header.header.desktop .icon_list>li.header-language-control {
                position: relative;
                z-index: 1;
                display: flex;
                flex: 0 0 56px !important;
                width: 56px !important;
                min-width: 56px !important;
                height: 32px !important;
                padding: 0 !important;
                overflow: hidden;
            }

            body header.header.desktop #google_translate_element {
                display: flex;
                align-items: center;
                width: 56px !important;
                min-width: 56px !important;
                max-width: 56px !important;
                height: 30px;
                overflow: hidden;
                border: 1px solid #eadff0;
                border-radius: 18px;
                background: #fff;
            }

            body header.header.desktop #google_translate_element .goog-te-gadget-simple {
                display: flex;
                align-items: center;
                width: 56px !important;
                min-width: 56px !important;
                max-width: 56px !important;
                padding: 4px;
                border: 0;
                background: transparent;
                font-size: 10px;
                white-space: nowrap;
                overflow: hidden;
            }

            body header.header.desktop #google_translate_element .goog-te-gadget-simple img {
                display: none;
            }

            body header.header.desktop #google_translate_element .goog-te-gadget-simple span {
                color: #392653;
                font-size: 10px;
            }

            body header.header.desktop .h-search .form-control {
                height: 42px;
                background: #fff !important;
                color: #29223a !important;
                border-color: #e8dfeb !important;
                border-radius: 24px 0 0 24px;
                padding-left: 18px;
            }

            body header.header.desktop .h-search .form-control::placeholder {
                color: #898394 !important;
            }

            body header.header.desktop .h-search .input-group-text {
                width: 44px;
                height: 42px;
                background: #3f1264 !important;
                border-radius: 0 24px 24px 0;
            }

            body header.header.desktop .h-search .input-group-text i,
            body header.header.desktop .h-search .input-group-text .bi {
                color: #fff !important;
                font-size: 14px !important;
            }

            body header.header.desktop .icon_list>li.header-auth-controls {
                position: relative;
                z-index: 2;
                display: flex;
                flex: 0 0 155px !important;
                width: 155px !important;
                min-width: 155px !important;
                height: 40px !important;
                gap: 7px;
                margin-left: 8px;
                padding: 0 !important;
                white-space: nowrap;
            }

            body header.header.desktop .header-auth-controls .av-header-login,
            body header.header.desktop .header-auth-controls .av-header-signup {
                display: inline-flex;
                flex: 0 0 74px;
                align-items: center;
                justify-content: center;
                width: 74px;
                min-width: 74px;
                height: 37px;
                padding: 0 8px;
                white-space: nowrap;
                line-height: 1;
            }

            body header.header.desktop .av-header-login {
                border-color: #bcb1cc !important;
                color: #291743 !important;
            }

            body header.header.desktop .av-header-signup {
                background: linear-gradient(135deg, #ffb20c, #ef8700) !important;
            }

            body section.header-top.desktop {
                height: 48px !important;
                padding: 0 !important;
                background: #fffaf4 !important;
                border-top: 1px solid #f0e8de !important;
                border-bottom: 1px solid #eee4d9 !important;
            }

            body section.header-top.desktop>.container {
                height: 100%;
                padding: 0 12px;
            }

            body section.header-top.desktop .row,
            body section.header-top.desktop .row>div {
                height: 100%;
            }

            body #header-nav {
                min-height: 47px;
                height: 47px;
            }

            body #header-nav .container-fluid,
            body #header-nav .navbar-collapse,
            body #header-nav .navbar-nav {
                height: 100%;
            }

            body #header-nav .navbar-nav {
                max-width: 1280px;
                margin: auto;
            }

            body #header-nav .nav-link {
                padding: 14px 10px !important;
                color: #34244c !important;
                font-size: 13px !important;
                font-weight: 600;
            }

            body #header-nav .nav-link:hover,
            body #header-nav .nav-link.active {
                color: #dc8500 !important;
            }

            body #header-nav .nav-link.active:after {
                content: "";
                position: absolute;
                left: 7px;
                right: 7px;
                bottom: 0;
                height: 2px;
                background: #f3a008;
            }

            body #header-nav .menu-new-badge {
                top: 0;
                right: -3px;
                padding: 1px 4px;
                border-radius: 8px;
                background: #df2464;
                color: #fff;
                font-size: 7px;
                line-height: 1.2;
            }

            body header.header.desktop .navbar-brand a,
            body #header-nav .nav-link {
                color: #36234a !important;
            }

            body #header-nav .nav-link:hover,
            body #header-nav .nav-link.active {
                color: #e89008 !important;
            }

            body header.header.desktop .navbar-brand a {
                color: #26163d !important;
            }

            body header.header.desktop .av-desktop-brand strong {
                color: #26163d !important;
            }

            body header.header.desktop .av-desktop-brand small {
                color: #6b6076 !important;
            }

            body header.header.desktop .h-search .form-control {
                background: #fff !important;
                color: #31213e !important;
                border-color: #e9deef !important;
            }

            body header.header.desktop .h-search .form-control::placeholder {
                color: #777080 !important;
                opacity: 1 !important;
            }

            body header.header.desktop .h-search .input-group-text {
                background: #4c176f !important;
                color: #fff !important;
            }

            body header.header.desktop .icon_list .wallet {
                background: #fff !important;
                border-color: #d9cee4 !important;
            }

            body header.header.desktop .icon_list .wallet .k_cart i {
                color: #4c176f !important;
            }

            body header.header.desktop .av-header-login {
                background: #fff !important;
                color: #4c176f !important;
                border-color: #cbb8d9 !important;
            }
        }
    </style>

</head>

<body
    class="{{ request()->is('panchang') ? 'panchang-route' : '' }}{{ request()->is('horoscopes') ? ' horoscopes-route' : '' }}{{ request()->is('horoscope/*/*/detail') ? ' horoscope-detail-route' : '' }}{{ request()->is('astrologer/login') ? ' astrologer-login-route' : '' }}{{ request()->is('astrologer/register') ? ' astrologer-register-route' : '' }}{{ request()->is('chat-with-astrologers', 'talk-to-astrologers') ? ' chat-astrologers-route' : '' }}{{ request()->is('astrologers/ai') ? ' ai-astrologers-route' : '' }}{{ request()->is('astrologers/*/*') ? ' astrologer-detail-route' : '' }}{{ request()->is('articles', 'articles/*') ? ' articles-route' : '' }}{{ request()->is('collections', 'collections/*', 'reports-collections', 'reports-collections/*', 'pujas-collections', 'pujas-collections/*', 'one-to-one-puja', 'one-to-one-puja/*') ? ' collections-route' : '' }}{{ request()->is('kundali') ? ' kundali-route' : '' }}{{ request()->is('kundali-matching') ? ' kundali-matching-route' : '' }}{{ request()->is('user/register') ? ' user-register-route' : '' }}{{ request()->routeIs('astrologer.dashboard', 'astrologer.profile', 'astrologer.consultations', 'astrologer.earnings', 'astrologer.puja', 'astrologer.booked.puja', 'astrologer.puja.earnings', 'astrologer.notification', 'astrologer.newsletter', 'astrologer.support', 'astrologer.referral.listing', 'astrologer.withdrawal') ? ' astrologer-account-route' : '' }}">


    @if (Request::routeIs('home'))
        <section class="home-skeleton" id="loader-web" aria-label="Loading homepage" role="status">
            <div class="home-skeleton__header">
                <i></i><b></b><span></span><span></span><span></span><span></span><em></em></div>
            <div class="home-skeleton__hero">
                <div><i></i><b></b><b></b><span></span><span></span><em></em><em></em></div>
                <aside><i></i><b></b><span></span><span></span></aside>
            </div>
            <div class="home-skeleton__icons">
                @for ($i = 0; $i < 8; $i++)
                    <i></i>


                @endfor
            </div>
            <div class="home-skeleton__cards">
                @for ($i = 0; $i < 8; $i++)
                    <i></i>
                @endfor
            </div>
        </section>
    @elseif(request()->is('panchang'))
        <section class="panchang-skeleton" id="loader-web" aria-label="Loading Panchang" role="status">
            <div class="panchang-skeleton__top"><i></i><b></b><span></span><span></span><span></span><em></em></div>
            <div class="panchang-skeleton__nav">
                @for ($i = 0; $i < 9; $i++)
                    <i></i>









                @endfor
            </div>
            <div class="panchang-skeleton__banner"></div>
            <div class="panchang-skeleton__filters"><i></i><i></i><i></i><i></i></div>
            <div class="panchang-skeleton__cards">
                @for ($i = 0; $i < 4; $i++)
                    <i></i>


                @endfor
            </div>
        </section>
    @elseif(request()->is('horoscopes'))
        <section class="horoscopes-skeleton" id="loader-web" aria-label="Loading horoscopes" role="status">
            <div class="horoscopes-skeleton__top"><i></i><b></b><span></span><span></span><em></em></div>
            <div class="horoscopes-skeleton__nav">
                @for ($i = 0; $i < 9; $i++)
                    <i></i>
                @endfor
            </div>
            <div class="horoscopes-skeleton__banner"></div>
            <div class="horoscopes-skeleton__title"></div>
            <div class="horoscopes-skeleton__content"><i></i><i></i></div>
        </section>
    @elseif(request()->is('horoscope/*/*/detail'))
        <section class="horoscope-detail-skeleton" id="loader-web" aria-label="Loading horoscope" role="status">
            <div class="horoscope-detail-skeleton__signs">@for ($i = 0; $i < 12; $i++)<i></i>@endfor</div>
            <div class="horoscope-detail-skeleton__heading"></div>
            <div class="horoscope-detail-skeleton__tabs">@for ($i = 0; $i < 4; $i++)<i></i>@endfor</div>
            <div class="horoscope-detail-skeleton__cards"><i></i><i></i><i></i></div>
            <div class="horoscope-detail-skeleton__text"><i></i><i></i><i></i></div>
        </section>
    @elseif(request()->is('account/profile', 'account/addresses', 'account/orders', 'account/wishlists', 'account/recharge-history', 'account/wallet-histories', 'account/consultations', 'account/ai-consultations', 'account/suggested-remedies', 'account/booked-puja', 'account/notification', 'account/newsletter', 'account/support', 'account/refer-earn', 'account/referral-rules', 'account/commissions', 'account/app-referrals', 'account/earnings'))
        <section class="account-pages-skeleton" id="loader-web" aria-label="Loading account page" role="status">
            <div class="account-pages-skeleton__heading"><i></i><b></b><span></span></div>
            <div class="account-pages-skeleton__layout">
                <aside><i></i><b></b><span></span><span></span><span></span><span></span><span></span></aside>
                <main><b></b><div><i></i><i></i><i></i></div><section><span></span><span></span><span></span><span></span></section></main>
            </div>
        </section>
    @elseif(request()->is('astrologer/login'))
        <section class="astrologer-login-skeleton" id="loader-web" aria-label="Loading astrologer login" role="status">
            <div class="astrologer-login-skeleton__card"><aside><i></i><b></b><span></span><span></span></aside><div><b></b><i></i><i></i><em></em><span></span></div></div>
        </section>
    @elseif(request()->is('chat-with-astrologers') || request()->is('talk-to-astrologers') || request()->is('astrologers/ai'))
        <section class="chat-astrologers-skeleton" id="loader-web" aria-label="Loading astrologers" role="status">
            <div class="chat-astrologers-skeleton__top"><i></i><b></b><span></span><span></span><em></em></div>
            <div class="chat-astrologers-skeleton__nav">
                @for ($i = 0; $i < 9; $i++)
                    <i></i>
                @endfor
            </div>
            <div class="chat-astrologers-skeleton__intro"><b></b><span></span><span></span></div>
            <div class="chat-astrologers-skeleton__filters">
                @for ($i = 0; $i < 5; $i++)
                    <i></i>
                @endfor
            </div>
            <div class="chat-astrologers-skeleton__cards">
                @for ($i = 0; $i < 6; $i++)
                    <i></i>

                @endfor
            </div>
        </section>
    @elseif(request()->is(
            'collections',
            'collections/*',
            'reports-collections',
            'reports-collections/*',
            'pujas-collections',
            'pujas-collections/*',
            'one-to-one-puja',
            'one-to-one-puja/*'))
        <section class="collections-skeleton" id="loader-web" aria-label="Loading collection" role="status">
            <div class="collections-skeleton__top"><i></i><b></b><span></span><span></span><em></em></div>
            <div class="collections-skeleton__nav">
                @for ($i = 0; $i < 9; $i++)
                    <i></i>
                @endfor
            </div>
            <div class="collections-skeleton__banner"></div>
            <div class="collections-skeleton__heading"></div>
            <div class="collections-skeleton__cards">
                @for ($i = 0; $i < 8; $i++)
                    <i></i>
                @endfor
            </div>
        </section>
    @elseif(request()->is('astrologer/dashboard'))
        <section class="astrologer-dashboard-skeleton" id="loader-web" aria-label="Loading astrologer dashboard" role="status">
            <div class="astro-dashboard-skeleton__header">
                <img class="astro-dashboard-skeleton__logo" src="{{ logo(200) ?? asset('assets/img/logo.svg') }}" alt="{{ config('app.name') }}">
                <div><i></i><i></i><i></i><b></b></div>
            </div>
            <div class="astro-dashboard-skeleton__layout">
                <aside class="astro-dashboard-skeleton__sidebar">
                    @for ($i = 0; $i < 9; $i++)<i></i>@endfor
                </aside>
                <main class="astro-dashboard-skeleton__main">
                    <section class="astro-dashboard-skeleton__profile"><i></i><div><b></b><span></span><span></span></div></section>
                    <section class="astro-dashboard-skeleton__revenue"><i></i><i></i><i></i><i></i></section>
                    <section class="astro-dashboard-skeleton__times"><i></i><i></i><i></i></section>
                    <section class="astro-dashboard-skeleton__availability"><b></b><span></span><span></span><span></span></section>
                </main>
            </div>
        </section>
    @elseif(request()->routeIs('astrologer.profile', 'astrologer.consultations', 'astrologer.earnings', 'astrologer.puja', 'astrologer.booked.puja', 'astrologer.puja.earnings', 'astrologer.notification', 'astrologer.newsletter', 'astrologer.support', 'astrologer.referral.listing', 'astrologer.withdrawal'))
        <section class="astrologer-account-skeleton" id="loader-web" aria-label="Loading astrologer account page" role="status">
            <div class="astrologer-account-skeleton__header">
                <img src="{{ logo(200) ?? asset('assets/img/logo.svg') }}" alt="{{ config('app.name') }}">
                <span></span><span></span><span></span><b></b>
            </div>
            <div class="astrologer-account-skeleton__layout">
                <aside>@for ($i = 0; $i < 10; $i++)<i></i>@endfor</aside>
                <main>
                    <div class="astrologer-account-skeleton__heading"><i></i><b></b><span></span></div>
                    <div class="astrologer-account-skeleton__tabs">@for ($i = 0; $i < 6; $i++)<i></i>@endfor</div>
                    <section class="astrologer-account-skeleton__panel">
                        <b></b><div class="astrologer-account-skeleton__fields">@for ($i = 0; $i < 6; $i++)<i></i>@endfor</div>
                        <div class="astrologer-account-skeleton__rows">@for ($i = 0; $i < 4; $i++)<span></span>@endfor</div>
                    </section>
                </main>
            </div>
        </section>
    @elseif(request()->is('account/dashboard'))
        <section class="account-dashboard-skeleton" id="loader-web" aria-label="Loading your dashboard" role="status">
            <div class="account-dashboard-skeleton__top"><i></i><b></b><span></span><span></span><em></em></div>
            <div class="account-dashboard-skeleton__heading"><i></i><b></b><span></span></div>
            <div class="account-dashboard-skeleton__layout">
                <aside class="account-dashboard-skeleton__sidebar"><i></i><b></b><span></span><span></span><span></span><span></span><span></span></aside>
                <div class="account-dashboard-skeleton__main">
                    <div class="account-dashboard-skeleton__quick-links">
                        @for ($i = 0; $i < 4; $i++)
                            <i></i>
                        @endfor
                    </div>
                    @for ($i = 0; $i < 4; $i++)
                        <section class="account-dashboard-skeleton__panel">
                            <b></b>
                            <div><i></i><i></i><i></i></div>
                        </section>
                    @endfor
                </div>
            </div>
        </section>
    @elseif(request()->is('kundali/report'))
        <section class="kundali-report-skeleton" id="loader-web" aria-label="Loading Kundali report" role="status">
            <div class="kundali-report-skeleton__hero">
                <i></i><b></b><span></span>
            </div>
            <div class="kundali-report-skeleton__tabs">
                @for ($i = 0; $i < 9; $i++)
                    <i></i>
                @endfor
            </div>
            <div class="kundali-report-skeleton__title"></div>
            <div class="kundali-report-skeleton__content">
                <div class="kundali-report-skeleton__main">
                    <i></i><i></i><i></i><i></i>
                    <b></b>
                </div>
                <aside><i></i><i></i><i></i></aside>
            </div>
            <div class="kundali-report-skeleton__cards">
                @for ($i = 0; $i < 3; $i++)
                    <i></i>
                @endfor
            </div>
        </section>
    @elseif(request()->is('kundali-matching'))
        <section class="kundali-skeleton kundali-matching-skeleton" id="loader-web"
            aria-label="Loading Kundali matching" role="status">
            <div class="kundali-skeleton__top"><i></i><b></b><span></span><span></span><em></em></div>
            <div class="kundali-skeleton__nav">
                @for ($i = 0; $i < 9; $i++)
                    <i></i>
                @endfor
            </div>
            <div class="kundali-skeleton__banner"></div>
            <div class="kundali-skeleton__heading"></div>
            <div class="kundali-skeleton__body">
                <div class="kundali-skeleton__form kundali-skeleton__form--match">
                    <section><i></i><i></i><i></i><i></i></section>
                    <section><i></i><i></i><i></i><i></i><i></i></section><i></i><i></i><b></b>
                </div><i class="kundali-skeleton__side"></i>
            </div>
            <div class="kundali-skeleton__heading kundali-skeleton__heading--small"></div>
            <div class="kundali-skeleton__zodiac">
                @for ($i = 0; $i < 4; $i++)
                    <i></i>
                @endfor
            </div>
        </section>
    @elseif(request()->is('kundali'))
        <section class="kundali-skeleton" id="loader-web" aria-label="Loading Kundali" role="status">
            <div class="kundali-skeleton__top"><i></i><b></b><span></span><span></span><em></em></div>
            <div class="kundali-skeleton__nav">
                @for ($i = 0; $i < 9; $i++)
                    <i></i>
                @endfor
            </div>
            <div class="kundali-skeleton__banner"></div>
            <div class="kundali-skeleton__heading"></div>
            <div class="kundali-skeleton__body">
                <div class="kundali-skeleton__form">
                    @for ($i = 0; $i < 6; $i++)
                        <i></i>
                    @endfor
                    <b>
                    </b>
                </div><i class="kundali-skeleton__side"></i>
            </div>
            <div class="kundali-skeleton__heading kundali-skeleton__heading--small"></div>
            <div class="kundali-skeleton__zodiac">
                @for ($i = 0; $i < 12; $i++)
                    <i></i>
                @endfor
            </div>
        </section>
    @elseif(request()->is('user/register', 'astrologer/register'))
        <section class="auth-register-skeleton" id="loader-web" aria-label="Loading registration form"
            role="status">
            <div class="auth-register-skeleton__top"><i></i><b></b><span></span><span></span><em></em></div>
            <div class="auth-register-skeleton__nav">
                @for ($i = 0; $i < 9; $i++)
                    <i></i>
                @endfor
            </div>
            <div class="auth-register-skeleton__card">
                <aside><i></i><b></b><span></span><span></span></aside>
                <div><b></b><i></i><i></i><i></i><i></i><em></em><span></span></div>
            </div>
        </section>
    @elseif(request()->is('astrologers/*/*'))
        <section class="astrologer-detail-skeleton" id="loader-web" aria-label="Loading astrologer profile"
            role="status">
            <div class="astrologer-detail-skeleton__top"><i></i><b></b><span></span><span></span><em></em></div>
            <div class="astrologer-detail-skeleton__nav">
                @for ($i = 0; $i < 9; $i++)
                    <i></i>
                @endfor
            </div>
            <div class="astrologer-detail-skeleton__hero">
                <div><i></i>
                    <div><b></b><span></span><span></span><span></span><em></em></div>
                </div>
                <aside>
                    @for ($i = 0; $i < 4; $i++)
                        <i></i>
                    @endfor
                </aside>
            </div>
            <div class="astrologer-detail-skeleton__heading"></div>
            <div class="astrologer-detail-skeleton__content"><i></i><i></i><i></i></div>
        </section>
    @elseif(Request::routeIs('blog.index') || Request::routeIs('blog.category'))
        <section class="articles-skeleton" id="loader-web" aria-label="Loading articles" role="status">
            <div class="articles-skeleton__top"><i></i><b></b><span></span><span></span><em></em></div>
            <div class="articles-skeleton__nav">
                @for ($i = 0; $i < 9; $i++)
                    <i></i>
                @endfor
            </div>
            <div class="articles-skeleton__hero"></div>
            <div class="articles-skeleton__heading"></div>
            <div class="articles-skeleton__grid">
                @for ($i = 0; $i < 9; $i++)
                    <i></i>
                @endfor
            </div>
        </section>
    @elseif(request()->is('about-us'))
        <section class="about-page-skeleton" id="loader-web" aria-label="Loading About Us page" role="status">
            <div class="about-page-skeleton__top"><i></i><b></b><span></span><span></span><em></em></div>
            <div class="about-page-skeleton__nav">
                @for ($i = 0; $i < 10; $i++)
                    <i></i>
                @endfor
            </div>
            <div class="about-page-skeleton__hero"><b></b><span></span><span></span></div>
            <div class="about-page-skeleton__body"><i></i><i></i></div>
        </section>
    @elseif(request()->is('contact-us'))
        <section class="contact-page-skeleton" id="loader-web" aria-label="Loading Contact Us page" role="status">
            <div class="contact-page-skeleton__top"><i></i><b></b><span></span><span></span><em></em></div>
            <div class="contact-page-skeleton__nav">
                @for ($i = 0; $i < 11; $i++)
                    <i></i>
                @endfor
            </div>
            <div class="contact-page-skeleton__hero"><b></b><span></span></div>
            <div class="contact-page-skeleton__cards"><i></i><i></i><i></i></div>
            <div class="contact-page-skeleton__map"></div>
        </section>
    @else
        @unless(request()->is('astrologer/dashboard'))
            <section class="loader" id="loader-web">
                <div class="preloader_relative">
                    <img src="{{ asset('assets/loader/dot_circle.gif') }}" class="main_preloader">
                    <div id="loader"><img src="{{ asset('assets/loader/dot_circle.gif') }}"></div>
                </div>
            </section>
        @endunless
    @endif

    <div class="">
        <!-- Header Section Start -->
        @php
            $user = auth()->guard('web')->user();
            $astrologer = auth()->guard('astrologer')->user();
            $hasSwitchArrow = false;
            $mobileView = str_contains($_SERVER['HTTP_USER_AGENT'], 'Mobile') ? true : false;
        @endphp
        @if (!session('app_login'))
            @if (!$mobileView)
                @if (Request::routeIs('astrologer.register') || Request::routeIs('astrologer.login') || Request::routeIs('login'))
                    <header class="header desktop specal_head">
                        <div class="container">
                            <div class="row align-items-center justify-content-between">
                                <div class=" d-flex header-list-m align-items-center justify-content-between ms-3">
                                    <div class=" navbar-brand">
                                        <a href="{{ route('home') }}">
                                            <img src="{{ logo(200) ?? asset('assets/img/logo.svg') }}"
                                                alt="{{ config('app.name') }}">
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </header>
                @endif
                @if (!Request::routeIs('astrologer.register') && !Request::routeIs('astrologer.login') && !Request::routeIs('login'))
                    <header class="header desktop">
                        <div class="container">
                            <div class="row align-items-center justify-content-between">
                                <div class=" d-flex header-list-m align-items-center justify-content-between ms-3">
                                    <div class=" navbar-brand">
                                        <a href="{{ route('home') }}">
                                            <img src="{{ logo(200) ?? asset('assets/img/logo.svg') }}"
                                                alt="{{ config('app.name') }}">
                                            <span class="av-desktop-brand">
                                                <strong>Astro<span>Vyra</span></strong>
                                                <small>✦ Your Cosmic Guide ✦</small>
                                            </span>
                                        </a>
                                    </div>
                                    @if ($astrologer == null)
                                        <div class="position-relative" role="search">
                                            <div class="input-group h-search">
                                                <input type="search" id="searchInput" class="form-control"
                                                    placeholder="{{ Request::routeIs('home') ? 'Search astrologer, service, or topic...' : 'Search' }}"
                                                    aria-label="Search" onkeyup="searchByName()">
                                                <span class="input-group-text">
                                                    <i class="bi bi-search"></i>
                                                </span>
                                            </div>
                                            <div id="suggestionsList" class="serch-box">
                                                <!-- For displaying search suggestions -->
                                            </div>
                                        </div>
                                    @endif

                                    <div class="d-flex align-items-center justify-content-end">
                                        <ul class="icon_list">
                                            @php
                                                $user = $user;
                                                $for = [0, 1];
                                                $user_type = 'user';
                                                $has_notifications = false;
                                                if (!$user) {
                                                    $user = $astrologer;
                                                    $for = [0, 2];
                                                    $user_type = 'astrologer';
                                                }
                                            @endphp
                                            @if ($user)
                                                @php
                                                    $notifications = notifications($for, $user, $user_type, 8);
                                                    $url = route('user.notification');
                                                    $notificationsCount = $notifications->count();
                                                    $userIds = [0, $user->id];
                                                    $unseen = false;

                                                    if ($user_type == 'user') {
                                                        $unseen = $notifications->where('view', 0)->count();
                                                    } else {
                                                        $url = route('astrologer.notification');
                                                        $unseen = $notifications->where('view', 0)->count();
                                                    }
                                                @endphp
                                                <div class="notification mobile desktop">
                                                    <div class="notBtn">
                                                        <ul>
                                                            <li class="pointer bell-2 bell-desk"
                                                                style="border: 2px solid #828587;">
                                                                @if ($notificationsCount > 0)
                                                                    <i class="bi bi-bell fs-20 position-relative three"
                                                                        style="color:#122e47;"></i>
                                                                    <span
                                                                        class="{{ $unseen ? 'unseen' : '' }}"></span>
                                                                @else
                                                                    <a href="{{ $url }}" target="_blank">
                                                                        <i class="bi bi-bell fs-20 position-relative three"
                                                                            style="color:#122e47;"></i>
                                                                    </a>
                                                                @endif
                                                            </li>
                                                        </ul>
                                                        @if ($notificationsCount > 0)
                                                            <div class="box bell-d-b mo-box-n">
                                                                <ul class="no-li px-3">
                                                                    @foreach ($notifications as $index => $item)
                                                                        <li class=" pointer border-bottom notification_li k_notiii"
                                                                            style="background-color: #F4F4F4;  border-width:0 0 0 2px; height: fit-content !important;">
                                                                            @if ($item->link)
                                                                                <a href="{{ route('notification.view', [$item->id]) }}"
                                                                                    target="_blank">
                                                                                    <span
                                                                                        class="no-span">{{ $item->message }}
                                                                                        {{ $notifications->count() }}</span>
                                                                                    <span
                                                                                        class="no-span">{{ date('d/m/Y - h:i a', strtotime($item->created_at)) }}</span>
                                                                                </a>
                                                                            @else
                                                                                <a href="#">
                                                                                    <span
                                                                                        class="no-span">{{ $item->message }}
                                                                                        {{ $notifications->count() }}</span>
                                                                                    <span
                                                                                        class="no-span">{{ date('d/m/Y - h:i a', strtotime($item->created_at)) }}</span>
                                                                                </a>
                                                                            @endif
                                                                        </li>
                                                                    @endforeach
                                                                    @if ($notificationsCount > 0)
                                                                        <li class=" pointer border-bottom phone_vi"
                                                                            style="background-color: #F4F4F4;  border-width:0 0 0 2px; height: fit-content !important;">
                                                                            <a href="{{ $url }}"
                                                                                target="_blank" class="m-auto">
                                                                                <button
                                                                                    class="view_all px-2 ms-auto fs-14">View
                                                                                    all</button>
                                                                            </a>
                                                                        </li>
                                                                    @else
                                                                        <li class="pointer border-bottom"
                                                                            style="background-color: #F4F4F4;  border-width:0 0 0 2px; height: fit-content !important;">
                                                                            <button class="view_all px-2 fs-14">No
                                                                                Notifications</button>
                                                                        </li>
                                                                    @endif
                                                                </ul>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endif
                                            @php
                                                $user = auth()->guard('web')->user();
                                                $astrologer = auth()->guard('astrologer')->user();
                                            @endphp
                                            @if ($user)
                                                <li class="pointer wallet custom-ci"
                                                    style="background: #ffffff; border: 2px solid #828587">
                                                    <a href="{{ route('user.recharge') }}"
                                                        class="d-flex justify-content-between align-items-center">
                                                        <i class="bi bi-credit-card text-green fs-20"></i>
                                                        <i class="bi bi-currency-rupee text-green"></i> <span
                                                            class="text-green">{{ $user->wallet }}</span>
                                                        <div class="circle2 ms-1 p-2 bg-green">
                                                            <i class="fa-solid fa-plus"></i>
                                                        </div>
                                                    </a>
                                                </li>
                                            @elseif($astrologer)
                                                <li class="pointer wallet custom-ci"
                                                    style="background: #E8F6EF; border: 2px solid #e9eaec">
                                                    <a href="{{ route('astrologer.earnings') }}"
                                                        class="d-flex justify-content-between align-items-center">
                                                        <i class="bi bi-credit-card text-green fs-20"></i>
                                                        <i class="bi bi-currency-rupee text-green"></i> <span
                                                            class="text-green">{{ $astrologer->wallet }}</span>
                                                    </a>
                                                </li>
                                            @endif
                                            @if ($astrologer == null)
                                                <li onclick="showCart()" class="pointer wallet"
                                                    style="border: 2px solid #888a8d">
                                                    <a href="javascript:void(0)"
                                                        class=" d-flex flex-column justify-centent center align-items-center k_cart"
                                                        id="cart-icon">
                                                        <i class="bi bi-cart-plus fs-20 text-black"></i>
                                                        <span class="count"
                                                            id="totalcartproduct">{{ cartitemcount() }}</span>
                                                    </a>
                                                </li>
                                            @endif
                                            <li class="header-language-control" style="padding:0px !important;">
                                                <div id="google_translate_element">
                                                </div>
                                            </li>
                                            @if ($user)
                                                @php
                                                    $userImage = $user->image;
                                                @endphp
                                                <li class="nav-item dropdown log"
                                                    style="padding-left:0px !important;">
                                                    <img src="{{ $userImage ? $userImage->image(40, 40) : asset('/assets/img/avtar-p.avif') }}"
                                                        alt="{{ $user->name }}" class="avtar">
                                                    <ul class="dropdown-menu profile-drop py-0">
                                                        <li class="nav-item py-0">
                                                            <a class="nav-link" href="{{ route('user.dashboard') }}">
                                                                <div class=" d-flex gap-3 align-items-center fs-6"><i
                                                                        class="bi bi-person text-black"
                                                                        style="font-size: 18px;"></i>My Profile</div>
                                                            </a>
                                                        </li>
                                                        <li class="nav-item py-0">
                                                            <a class="nav-link" href="{{ route('logout') }}">
                                                                <div class=" d-flex gap-3 align-items-center fs-6"><i
                                                                        class="fa fa-lock text-black"
                                                                        style="font-size: 18px;"></i> Logout</div>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </li>
                                            @elseif($astrologer)
                                                @php
                                                    $astrologerImage = $astrologer->image;
                                                @endphp
                                                <li class="nav-item dropdown log"
                                                    style="padding-left:0px !important;">
                                                    <img src="{{ $astrologerImage ? $astrologerImage->image(40, 40) : asset('/assets/img/avtar-p.avif') }}"
                                                        alt="{{ $astrologer->display_name }}"
                                                        style="width:40px; height:40px">
                                                    <ul class="dropdown-menu">
                                                        <li class="nav-item">
                                                            <a class="nav-link"
                                                                href="{{ route('astrologer.dashboard') }}">
                                                                <div class=" d-flex gap-3 align-items-center fs-6"><i
                                                                        class="bi bi-person"
                                                                        style="font-size: 18px;"></i>My Profile </div>
                                                            </a>
                                                        </li>
                                                        <li class="nav-item">
                                                            <a class="nav-link"
                                                                href="{{ route('astrologer.logout') }}">
                                                                <div class=" d-flex gap-3 align-items-center fs-6"><i
                                                                        class="bi bi-lock"
                                                                        style="font-size: 18px;"></i> Logout</div>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </li>
                                            @else
                                                <li class="nav-item dropdown log header-auth-controls"
                                                    style="padding-left:0px !important;">
                                                    <button type="button" onclick="handleUserLoginModal('open')"
                                                        class="av-header-login">Login</button>
                                                    <button type="button" onclick="handleUserLoginModal('open')"
                                                        class="av-header-signup">Sign Up</button>
                                                </li>
                                            @endif

                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </header>
                @endif
            @endif
            @if (!$mobileView)
                @if (!Request::routeIs('astrologer.register') && !Request::routeIs('astrologer.login') && !Request::routeIs('login'))
                    <section class="start-0 desktop header-top pt-2 pt-md-0 pb-0" style="z-index:99;">
                        <div class="container">
                            <div class="row">
                                <div class=" col-lg-12 col-sm-2 d-flex justify-content-center py-1 pe-0">
                                    <nav class="navbar navbar-expand-lg bg-body-dark p-0" id="header-nav">
                                        <div class="container-fluid container-xl">
                                            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                                                data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown"
                                                aria-expanded="false" aria-label="Toggle navigation">
                                                <span class="navbar-toggler-icon"></span>
                                            </button>
                                            @if (!isset($hideHeaderNavigation) && $astrologer == null)
                                                <div class="collapse navbar-collapse" id="navbarNavDropdown">
                                                    <ul class="navbar-nav align-items-center desk-n">
                                                        <li class="nav-item">
                                                            <a class="nav-link {{ isset($nav) && $nav == 'home' ? 'active' : '' }}"
                                                                aria-current="page" href="{{ route('home') }}">
                                                                Home
                                                            </a>
                                                        </li>
                                                        <li
                                                            class="nav-item py-2 {{ request()->is('panchang') ? 'active' : '' }}">
                                                            <a class="nav-link {{ request()->is('panchang') ? 'active' : '' }}"
                                                                href="{{ route('panchang') }}">
                                                                Panchang
                                                            </a>
                                                        </li>
                                                        <li
                                                            class="nav-item py-2 {{ request()->is('horoscopes*') ? 'active' : '' }}">
                                                            <a class="nav-link {{ request()->is('horoscopes*') ? 'active' : '' }}"
                                                                href="{{ route('horoscopes.index') }}">
                                                                Horoscope
                                                            </a>
                                                        </li>
                                                        <li class="nav-item sp-btn position-relative">
                                                            <a class="nav-link {{ (isset($nav) && $nav == 'chatwithastrologers') || request()->is('astrologers/*/*') ? 'active' : '' }}"
                                                                href="{{ route('astrologers.chatwithastrologers') }}">
                                                                <i class="fa fa-commenting"></i>
                                                                Chat With Astrologers
                                                                <span class="menu-new-badge">Popular</span>
                                                            </a>
                                                        </li>

                                                        <li class="nav-item sp-btn position-relative">
                                                            <a class="nav-link {{ isset($nav) && $nav == 'talktoastrologers' ? 'active' : '' }}"
                                                                href="{{ route('astrologers.talktoastrologers') }}">
                                                                <i class="fa fa-volume-control-phone"
                                                                    aria-hidden="true"></i> Talk To Astrologers
                                                                <span class="menu-new-badge">Trending</span>

                                                            </a>
                                                        </li>
                                                        <li class="nav-item sp-btn  position-relative">
                                                            <a class="nav-link {{ isset($nav) && $nav == 'ai' ? 'active' : '' }}"
                                                                href="{{ route('astrologers.index', ['ai']) }}">
                                                                <i class="fa fa-robot" aria-hidden="true"></i> AI
                                                                Astrologers
                                                                <span class="menu-new-badge">New</span>

                                                            </a>
                                                        </li>
                                                        <li class="nav-item">
                                                            <a class="nav-link {{ (isset($nav) && $nav == 'Product') || request()->is('collections', 'collections/*') ? 'active' : '' }}"
                                                                href="{{ route('product.handle') }}">
                                                                Astro Mall
                                                            </a>
                                                        </li>
                                                        <li class="nav-item">
                                                            <a class="nav-link {{ (isset($nav) && $nav == 'Service') || request()->is('reports-collections', 'reports-collections/*') ? 'active' : '' }}"
                                                                href="{{ route('service.handle') }}">
                                                                Reports
                                                            </a>
                                                        </li>
                                                        <li class="nav-item dropdown">
                                                            <a class="nav-link dropdown-toggle {{ (isset($nav) && ($nav == 'Pooja' || $nav == 'OneToOnePuja')) || request()->is('pujas-collections', 'pujas-collections/*', 'one-to-one-puja', 'one-to-one-puja/*') ? 'active' : '' }}"
                                                                href="#" id="pujaDropdown" role="button"
                                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                                Puja Remedies
                                                            </a>
                                                            <ul class="dropdown-menu" aria-labelledby="pujaDropdown">
                                                                <li><a class="dropdown-item {{ isset($nav) && $nav == 'Pooja' ? 'active' : '' }}"
                                                                        href="{{ route('puja.handle') }}">Group
                                                                        Puja</a></li>
                                                                <li><a class="dropdown-item {{ isset($nav) && $nav == 'OneToOnePuja' ? 'active' : '' }}"
                                                                        href="{{ route('one.to.one.puja.handle') }}">VIP
                                                                        Puja</a></li>
                                                            </ul>
                                                        </li>

                                                        <li class="nav-item dropdown">
                                                            <a class="nav-link dropdown-toggle {{ (isset($nav) && ($nav == 'kundali' || $nav == 'kundalimatching')) || request()->is('kundali', 'kundali-matching') ? 'active' : '' }}"
                                                                href="#" id="kundaliDropdown" role="button"
                                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                                Kundli
                                                            </a>
                                                            <ul class="dropdown-menu"
                                                                aria-labelledby="kundaliDropdown">
                                                                <li>
                                                                    <a class="dropdown-item"
                                                                        href="{{ route('kundali.index') }}">Free
                                                                        Kundli</a>
                                                                </li>
                                                                <li>
                                                                    <a class="dropdown-item"
                                                                        href="{{ route('kundalimatching') }}"> Match
                                                                        Making</a>
                                                                </li>
                                                                <!-- <li>
                              <a class="dropdown-item" href="{{ route('panchang') }}"> Panchang</a>
                            </li>
                            <li>
                              <a class="dropdown-item" href="{{ route('horoscopes.index') }}"> Horoscope</a>
                            </li> -->
                                                            </ul>
                                                        </li>

                                                        <!-- <li class="nav-item">
                          <a class="nav-link {{ isset($nav) && $nav == 'ai' ? 'active' : '' }}" href="{{ route('contactForm') }}">
                            Contact Us
                          </a>
                        </li> -->
                                                    </ul>
                                            @endif
                                        </div>
                                </div>
                                </nav>
                            </div>
                        </div>
    </div>
    </section>
    @endif
    @endif
    <!-- Header Section End  -->
    <!-- mobile menu section -->

    <header class="container-fluid  header mobile  position-fixed fixed-top  bg-nav hide-in-webview"
        style="z-index:9999;">
        <div class="container py-2">
            <div class="row align-items-center justify-content-between">
                <div class="col-4 ps-0">
                    <a class="logo" href="{{ asset('/') }}"><img
                            src="{{ logo(110) ?? asset('/assets/img/logo.svg') }}" alt="Logo"></a>
                </div>
                <div class="col-8 position-relative pb-1 pb-lg-0 desktop" role="search">
                    @if ($astrologer == null)
                        <!-- <div class="input-group d-none d-lg-block">
                      <input type="search" id="searchInput" class="form-control" placeholder="Search" aria-label="Search" onkeyup="searchByName()">
                      <span class="input-group-text">
                        <i class="bi bi-search"></i>
                      </span>
                    </div> -->
                        <div id="suggestionsList" class="serch-box">
                            <!-- For displaying search suggestions -->
                        </div>
                    @endif
                </div>
                <div class="col-8 col-lg-4 d-flex align-items-center justify-content-end pe-2">
                    <ul class="icon_list gap-2 jjjj">

                        @php
                            $user = $user;
                            $for = [0, 1];
                            $user_type = 'user';
                            $has_notifications = false;
                            if (!$user) {
                                $user = $astrologer;
                                $for = [0, 2];
                                $user_type = 'astrologer';
                            }
                        @endphp
                        @if ($user)
                            <div class="notification mobile">
                                @php
                                    $notifications = notifications($for, $user, $user_type, 8);
                                    $url = route('user.notification');
                                    $notificationsCount = $notifications->count();
                                    $userIds = [0, $user->id];
                                    $unseen = false;

                                    if ($user_type == 'user') {
                                        $unseen = $notifications->where('view', 0)->count();
                                    } else {
                                        $url = route('astrologer.notification');
                                        $unseen = $notifications->where('view', 0)->count();
                                    }
                                @endphp
                                <div class="notBtn">
                                    <ul>

                                        <li class="pointer bell-2 bell-desk"
                                            style="border: 2px solid #f9a4a6 ;background:#f9a4a6;">
                                            @if ($notificationsCount > 0)
                                                <i class="bi bi-bell fs-20 position-relative three text-black"
                                                    onclick="handleNotifications(this);"></i>
                                                <span class="{{ $unseen ? 'unseen' : '' }}"></span>
                                            @else
                                                <a href="{{ $url }}" target="_blank">
                                                    <i class="bi bi-bell fs-20 position-relative three text-black"
                                                        style="background:#9975ee;"></i>
                                                </a>
                                            @endif
                                        </li>
                                    </ul>
                                    @if ($notificationsCount > 0)
                                        <div
                                            class="box {{ \Illuminate\Support\Str::startsWith(Route::currentRouteName(), 'astrologer.') ? 'k_dash_n' : '' }}">
                                            <ul class="no-li px-3">
                                                @foreach ($notifications as $index => $item)
                                                    <li class="py-2 pointer border-bottom notification_li"
                                                        id="k_notifi"
                                                        style="background-color: #F4F4F4;  border-width:0 0 0 2px">
                                                        @if ($item->link)
                                                            <a href="{{ route('notification.view', [$item->id]) }}"
                                                                target="_blank">
                                                                <span class="no-span">{{ $item->message }}</span>
                                                                <span
                                                                    class="no-span">{{ date('d/m/Y - h:i a', strtotime($item->created_at)) }}</span>
                                                            </a>
                                                        @else
                                                            <a href="#">
                                                                <span class="no-span">{{ $item->message }}</span>
                                                                <span
                                                                    class="no-span">{{ date('d/m/Y - h:i a', strtotime($item->created_at)) }}</span>
                                                            </a>
                                                        @endif
                                                    </li>
                                                @endforeach
                                                @if ($notificationsCount > 0)
                                                    <li class="py-2 pointer border-bottom phone_vi"
                                                        style="background-color: #F4F4F4;  border-width:0 0 0 2px">
                                                        <a href="{{ $url }}" target="_blank">
                                                            <button class="view_all px-2 ms-auto fs-14">View
                                                                all</button>
                                                        </a>
                                                    </li>
                                                @else
                                                    <li class="py-2 pointer border-bottom"
                                                        style="background-color: #F4F4F4;  border-width:0 0 0 2px">
                                                        <button class="view_all px-2 fs-14">No Notifications</button>
                                                    </li>
                                                @endif
                                            </ul>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif
                        @php
                            $user = auth()->guard('web')->user();
                            $astrologer = auth()->guard('astrologer')->user();
                        @endphp
                        @if ($user)
                            <li class="pointer bell-2"
                                id="{{ Request::routeIs('astrologer.support') ? 'k_ast_dash_wa' : '' }}"
                                style="background:#9ce895; border:1px solid #9ce895;">
                                <a href="{{ route('user.recharge') }}"
                                    class="d-flex justify-content-between align-items-center">
                                    <i class="bi bi-credit-card text-black"></i>
                                    {{-- <span class="text-green">{{ $user->wallet }}</span> --}}
                                </a>
                            </li>
                        @elseif($astrologer)
                            <li class="pointer bell-2"
                                id="{{ Request::routeIs('astrologer.support') ? 'k_ast_dash_wa' : '' }}"
                                style="width:40px;background:#5AB8E6; border:1px solid #5AB8E6;height:30px !important;">
                                <a href="{{ route('astrologer.earnings') }}"
                                    class="d-flex justify-content-between align-items-center">
                                    <i class="bi bi-credit-card text-black hh"></i>
                                    {{-- <span class="text-green">{{ $astrologer->wallet }}</span> --}}
                                </a>
                            </li>
                        @endif
                        @if ($astrologer == null)
                            <li class="pointer bell-2"
                                style="width:40px; background:#C5CBE1;border:1px solid #C5CBE1;">
                                <a href="javascript:void(0)" id="search">
                                    <i class="bi bi-search text-black fs-20"></i>
                                </a>
                                <div class="search-input d-none">
                                    <input type="search" class="search-bar" id="searchInputs"
                                        onkeyup="searchByNames()">
                                </div>
                            </li>
                        @endif
                        @if ($astrologer)
                            @php
                                $astrologerImage = $astrologer->image;
                            @endphp
                            <li class="pointer bell-2 ast_img">
                                <a data-bs-toggle="offcanvas" href="#offcanvasExample"
                                    aria-controls="offcanvasExample">
                                    <img src="{{ $astrologerImage ? $astrologerImage->image(30, 30) : asset('/assets/img/avtar-p.avif') }}"
                                        alt="{{ $astrologer->display_name }}" style="width:30px; height:30px">
                                </a>
                            </li>
                        @endif
                        @if (!isset($hideHeaderNavigation) && $astrologer == null)
                            <li data-bs-toggle="offcanvas" data-bs-target="#mobilemenu"
                                aria-controls="offcanvasWithBothOptions"
                                style="background:#5AB8E6;border:1px solid #5AB8E6;">
                                <a href="javascript:void(0)">
                                    <i class="bi bi-list text-black"></i>
                                </a>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </header>
    @endif
    @php
        $user = auth()->guard('web')->user();
        $astrologer = auth()->guard('astrologer')->user();
    @endphp

    <!-- mobile menu Section End  -->
    @yield('content')
    <div class="d-lg-none">
        @if ($user && !request()->is('account/*'))
            @include(themeView() . 'account._sidebar')
        @endif
    </div>

    <!-- mini cart -->
    <section class="mini_cart">
        <div class="offcanvas offcanvas-end mini_cart-off mt-0 mt-md-0" data-bs-scroll="true" tabindex="-1"
            data-empty-cart="{{ asset('assets/images/empty-cart.png') }}" id="offcanvasWithBothOptions"
            aria-labelledby="offcanvasWithBothOptionsLabel">
            <div class="container cart-boxx side-scrollbar card-side-new px-2">
                <div class="offcanvas-header ps-0">
                    <i class="fa fa-shopping-bag me-2"></i>
                    <h5 class="offcanvas-title mini-title" id="offcanvasWithBothOptionsLabel">YOUR CART</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"
                        id="close-mini-cart-btn"></button>
                </div>
                <div class="minicartdata side-scrollbar mt-2"></div>
                <div class="offcanvas-footer" id="subTotalCheckoutBtnDiv">
                    <div class="d-flex justify-content-between">
                        <h5 class="fw-bold f13">Subtotal</h5>
                        <span class="fw-bold f13"><i class="bi text-dark bi-currency-rupee"></i><span
                                id="subTotalPrice"></span></span>
                    </div>
                    <div class="text-end py-3">
                        <div class="text-end">
                            @if ($user)
                                <button id="checkout-btn" class="main_button w-100 text-white checkbtn"><a
                                        href="{{ route('checkout', ['product']) }}"
                                        class="text-white">CHECKOUT</a></button>
                            @else
                                <button class="btn btn-secondary fw-semibold" onclick="handleUserLoginModal('open')"
                                    id="checkout-btn">CHECKOUT</button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- mini cart end -->
    </div>
    <!-- mobile menu -->

    <div class="offcanvas offcanvas-start menu-offf mt-5" data-bs-scroll="true" tabindex="-1" id="mobilemenu"
        aria-labelledby="offcanvasWithBothOptionsLabel" style="z-index:9999; width:70%;">
        <div class="offcanvas-header p-0">
            <h5 class="offcanvas-title" id="offcanvasWithBothOptionsLabel"></h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"
                id="sidebar-close-btn"></button>
        </div>
        <div class="offcanvas-body" style="padding-bottom: 80px;">
            <ul class="navbar-nav mobile-menu">
                <li class="nav-item py-2 border-bottom">
                    <div id="google_translate_element">
                    </div>
                </li>
                <li class="nav-item py-2 border-bottom {{ isset($nav) && $nav == 'home' ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('home') }}">
                        <i class="b bi-house"></i> Home
                    </a>
                </li>
                <li class="nav-item py-2 border-bottom {{ request()->is('panchang') ? 'active' : '' }}">
                    <a class="nav-link {{ request()->is('panchang') ? 'active' : '' }}"
                        href="{{ route('panchang') }}">
                        <i class="bi bi-person"></i>Panchang
                    </a>
                </li>
                <li class="nav-item py-2 border-bottom {{ request()->is('horoscopes*') ? 'active' : '' }}">
                    <a class="nav-link {{ request()->is('horoscopes*') ? 'active' : '' }}"
                        href="{{ route('horoscopes.index') }}">
                        <i class="bi bi-person"></i>Horoscope
                    </a>
                </li>
                <li class="nav-item border-bottom py-2 mb-2 position-relative">
                    <a class="nav-link {{ (isset($nav) && $nav == 'chatwithastrologers') || request()->is('astrologers/*/*') ? 'active' : '' }}"
                        aria-current="page" href="{{ route('astrologers.chatwithastrologers') }}">
                        <i class="bi bi-chat"></i> Chat With Astrologers
                        <span class="menu-new-badge">Popular</span>

                    </a>
                </li>
                <li class="nav-item border-bottom py-2 position-relative">
                    <a class="nav-link {{ isset($nav) && $nav == 'talktoastrologers' ? 'active' : '' }}"
                        aria-current="page" href="{{ route('astrologers.talktoastrologers') }}">
                        <i class="bi bi-telephone"></i> Talk To Astrologers
                        <span class="menu-new-badge">Trending</span>

                    </a>
                </li>
                <li class="nav-item border-bottom py-2 position-relative">
                    <a class="nav-link  {{ isset($nav) && $nav == 'ai' ? 'active' : '' }}" aria-current="page"
                        href="{{ route('astrologers.index', ['ai']) }}">
                        <i class="bi bi-robot"></i> AI Astrologers
                        <span class="menu-new-badge new">New</span>

                    </a>
                </li>
                <li class="nav-item border-bottom py-2">
                    <a class="nav-link {{ (isset($nav) && $nav == 'Product') || request()->is('collections', 'collections/*') ? 'active' : '' }}"
                        aria-current="page" href="{{ route('product.handle') }}">
                        <i class="bi bi-collection"></i> Astro Mall
                    </a>
                </li>
                <li class="nav-item border-bottom py-2">
                    <a class="nav-link {{ (isset($nav) && $nav == 'Service') || request()->is('reports-collections', 'reports-collections/*') ? 'active' : '' }}"
                        aria-current="page" href="{{ route('service.handle') }}">
                        <i class="bi bi-flag"></i> Reports
                    </a>
                </li>
                <li class="nav-item dropdown border-bottom py-2">
                    <a class="nav-link dropdown-toggle {{ (isset($nav) && ($nav == 'Pooja' || $nav == 'OneToOnePuja')) || request()->is('pujas-collections', 'pujas-collections/*', 'one-to-one-puja', 'one-to-one-puja/*') ? 'active' : '' }}"
                        href="#" id="pujaDropdownMobile" role="button" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        <i class="bi bi-bell"></i> Puja Remedies
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="pujaDropdownMobile">
                        <li><a class="dropdown-item {{ isset($nav) && $nav == 'Pooja' ? 'active' : '' }}"
                                href="{{ route('puja.handle') }}">Group Puja</a></li>
                        <li><a class="dropdown-item {{ isset($nav) && $nav == 'OneToOnePuja' ? 'active' : '' }}"
                                href="{{ route('one.to.one.puja.handle') }}">VIP Puja</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown border-bottom py-2">
                    <a class="nav-link dropdown-toggle {{ (isset($nav) && ($nav == 'kundali' || $nav == 'kundalimatching')) || request()->is('kundali', 'kundali-matching') ? 'active' : '' }}"
                        href="#" id="kundaliDropdown" role="button" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        <i class="bi bi-card-text"></i> Kundli
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="kundaliDropdown">
                        <li class="nav-item border-bottom py-2">
                            <a class="dropdown-item" href="{{ route('kundali.index') }}"><i class="bi bi-link"></i>
                                Free Kundli</a>
                        </li>
                        <li class="nav-item border-bottom py-2">
                            <a class="dropdown-item" href="{{ route('kundalimatching') }}"><i
                                    class="bi bi-chevron-contract"></i> Match Making</a>
                        </li>
                        <!-- <li class="nav-item border-bottom py-2">
                <a class="dropdown-item" href="{{ route('panchang') }}"><i class="bi bi-calendar"></i> Panchang</a>
              </li>
              <li class="nav-item border-bottom py-2">
                <a class="dropdown-item" href="{{ route('horoscopes.index') }}"><i class="bi bi-bookmark"></i> Horoscope</a>
              </li> -->
                    </ul>
                </li>
                <!-- <li class="nav-item border-bottom py-2">
            <a class="nav-link {{ isset($nav) && $nav == 'ai' ? 'active' : '' }}" href="{{ route('contactForm') }}">
            <i class="bi bi-person-rolodex"></i> Contact Us
            </a>
          </li> -->
            </ul>
        </div>
    </div>

    <audio controls id="hiddenAudio" loop style="display:none">
        <source src="{{ asset('sounds/ring.mp3') }}" type="audio/mpeg">
    </audio>
    <button onClick="playAudio()" id="playMusic" style="display:none"></button>
    <!-- mobile menu end -->
    @if (!session('app_login'))
        <!-- popup section End -->
        @if (!isset($hideFooter) && $astrologer == null)
            @if (!Request::RouteIs('astrologer.register') && !Request::RouteIs('astrologer.login') && !Request::RouteIs('login'))
                @include(themeView() . 'common.footer', ['mobileView' => $mobileView, 'user' => $user])
            @endif
        @endif
        <!-- footer section End -->
        @if (!isset($hideUserLoginPopup) && $astrologer == null)
            @include(themeView() . 'common.loginpopup')
        @endif
    @endif

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script type="text/javascript">
        function googleTranslateElementInit() {
            new google.translate.TranslateElement({
                pageLanguage: 'en',
                includedLanguages: 'en,hi',
                layout: google.translate.TranslateElement.InlineLayout.SIMPLE
            }, 'google_translate_element');
            setTimeout(() => {
                const selectText = $('#google_translate_element span span').first();
                if (selectText) {
                    if (selectText.text().includes("Select Language")) {
                        selectText.text('Eng');
                    } else if (selectText.text().includes("Hindi")) {
                        selectText.text("हिन्दी");
                    }
                }
                $("iframe").last().click();
                const languagesOption = $("iframe").last().contents().find('.VIpgJd-ZVi9od-vH1Gmf td a').first()
                    .find('.text');
                languagesOption.text("Eng");
                const languagesOptionSecond = $("iframe").last().contents().find('.VIpgJd-ZVi9od-vH1Gmf td a').eq(1)
                    .find('.text');
                languagesOptionSecond.text("हिन्दी");
            }, 1000);
        }
    </script>
    <script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit">
    </script>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
        integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous">
    </script>
    <script type="text/javascript" src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
    <!-- jQuery (required for Slick) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Slick JS -->
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
    <script type="module" src="{{ asset('assets/js/custom.js') }}"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/vue@2/dist/vue.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script defer src="{{ asset('js/toastr.min.js') }}"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script defer src="https://static.addtoany.com/menu/page.js"></script>
    <script>
        @if (!isset($hideUserLoginPopup) && $astrologer == null)
            function handleUserLoginModal(action) {
                if (action == "open") {
                    $("#userLoginModal").modal('show');
                } else {
                    $("#userLoginModal").modal('hide');
                }
                $("#close-mini-cart-btn").click();
            }
        @endif
        document.addEventListener('DOMContentLoaded', function() {
            var offcanvasElement = document.getElementById('offcanvasWithBothOptions');
            offcanvasElement.addEventListener('shown.bs.offcanvas', function() {
                document.body.classList.add('overflow-hidden');
            });
            offcanvasElement.addEventListener('hidden.bs.offcanvas', function() {
                document.body.classList.remove('overflow-hidden');
            });
        });





        @if (!$astrologer)
            var typingTimer;
            const suggestionsList = document.getElementById('suggestionsList');

            function headerSearchEscAttr(s) {
                return String(s == null ? '' : s).replace(/&/g, '&amp;').replace(/"/g, '&quot;').replace(/</g, '&lt;');
            }

            function headerSearchEscHtml(s) {
                return String(s == null ? '' : s).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
            }

            function headerSearchSuggestRow(url, imageUrl, name) {
                var img = imageUrl || '';
                return '<a href="' + headerSearchEscAttr(url) + '" class="header-search-row">' +
                    '<img src="' + headerSearchEscAttr(img) + '" alt="" width="32" height="32" loading="lazy">' +
                    '<span>' + headerSearchEscHtml(name) + '</span>' +
                    '</a>';
            }

            function searchByName() {
                clearTimeout(typingTimer);
                typingTimer = setTimeout(function() {
                    let query = document.getElementById('searchInput').value.trim(); // Trim whitespace
                    if (query.length >= 2) { // Show suggestions from 2 characters
                        document.getElementById('suggestionsList').innerHTML =
                        ''; // Clear suggestions if query is too short
                        document.getElementById('suggestionsList').style.display = 'none'; // Hide suggestions box
                        let xhr = new XMLHttpRequest();
                        xhr.open('GET', '/search-data?title=' + encodeURIComponent(query), true);
                        xhr.onload = function() {
                            if (xhr.status === 200) {
                                try {
                                    // Parse the JSON response
                                    let response = JSON.parse(xhr.responseText);
                                    let suggestionsHtml = '';
                                    let astrologers = response.astrologers;
                                    let aiastrologers = response.aiastrologers;
                                    let products = response.products;
                                    let poojas = response.poojas;
                                    let services = response.services;

                                    if (astrologers && astrologers.length > 0) {
                                        suggestionsHtml +=
                                            '<h5>Astrologers</h5><ul class="header-search-suggest-ul">';
                                        astrologers.forEach(function(astrologer) {
                                            suggestionsHtml += '<li>' + headerSearchSuggestRow(
                                                    astrologer.url, astrologer.image, astrologer.name) +
                                                '</li>';
                                        });
                                        suggestionsHtml += '</ul>';
                                    }

                                    if (aiastrologers && aiastrologers.length > 0) {
                                        suggestionsHtml +=
                                            '<h5>Ai Astrologers</h5><ul class="header-search-suggest-ul">';
                                        aiastrologers.forEach(function(aiastrologer) {
                                            suggestionsHtml += '<li>' + headerSearchSuggestRow(
                                                aiastrologer.url, aiastrologer.image, aiastrologer
                                                .name) + '</li>';
                                        });
                                        suggestionsHtml += '</ul>';
                                    }

                                    // if (response.courses && response.courses.length > 0) {
                                    //     suggestionsHtml += '<h5>Courses</h5><ul class="sidebar_ul">';
                                    //     response.courses.forEach(function(course) {
                                    //         suggestionsHtml += `<a href="${course.url}"><li class="link link__0">${course.name}</li></a>`;
                                    //     });
                                    //     suggestionsHtml += '</ul>';
                                    // }

                                    if (products && products.length > 0) {
                                        suggestionsHtml +=
                                            '<h5>Astro Mall</h5><ul class="header-search-suggest-ul">';
                                        products.forEach(function(product) {
                                            if (!product) {
                                                return;
                                            }
                                            suggestionsHtml += '<li>' + headerSearchSuggestRow(product
                                                .url, product.image, product.name) + '</li>';
                                        });
                                        suggestionsHtml += '</ul>';
                                    }

                                    if (poojas && poojas.length > 0) {
                                        suggestionsHtml +=
                                            '<h5>E-Pooja</h5><ul class="header-search-suggest-ul">';
                                        poojas.forEach(function(pooja) {
                                            if (!pooja) {
                                                return;
                                            }
                                            suggestionsHtml += '<li>' + headerSearchSuggestRow(pooja
                                                .url, pooja.image, pooja.name) + '</li>';
                                        });
                                        suggestionsHtml += '</ul>';
                                    }

                                    if (services && services.length > 0) {
                                        suggestionsHtml +=
                                            '<h5>Reports</h5><ul class="header-search-suggest-ul">';
                                        services.forEach(function(service) {
                                            if (!service) {
                                                return;
                                            }
                                            suggestionsHtml += '<li>' + headerSearchSuggestRow(service
                                                .url, service.image, service.name) + '</li>';
                                        });
                                        suggestionsHtml += '</ul>';
                                    }

                                    // if (response.blogs && response.blogs.length > 0) {
                                    //     suggestionsHtml += '<h5>Blogs</h5><ul class="sidebar_ul">';
                                    //     response.blogs.forEach(function(blog) {
                                    //         suggestionsHtml += `<a href="${blog.url}"><li class="link link__0">${blog.name}</li></a>`;
                                    //     });
                                    //     suggestionsHtml += '</ul>';
                                    // }
                                    //
                                    // if (response.vastus && response.vastus.length > 0) {
                                    //     suggestionsHtml += '<h5>Vastus</h5><ul class="sidebar_ul">';
                                    //     response.vastus.forEach(function(vastu) {
                                    //         suggestionsHtml += `<a href="${vastu.url}"><li class="link link__0">${vastu.name}</li></a>`;
                                    //     });
                                    //     suggestionsHtml += '</ul>';
                                    // }
                                    //
                                    // if (response.cms_two && response.cms_two.length > 0) {
                                    //     suggestionsHtml += '<h5>Other</h5><ul class="sidebar_ul">';
                                    //     response.cms_two.forEach(function(cms2) {
                                    //         suggestionsHtml += `<a href="${cms2.url}"><li class="link link__0">${cms2.name}</li></a>`;
                                    //     });
                                    //     suggestionsHtml += '</ul>';
                                    // }

                                    // If there are no results at all, show a "no results" message
                                    if (suggestionsHtml === '') {
                                        suggestionsHtml = '<p>No results found</p>';
                                    }

                                    // Update suggestions list in the DOM
                                    suggestionsList.innerHTML = suggestionsHtml;
                                    suggestionsList.style.display = 'block'; // Show the suggestions
                                } catch (e) {
                                    console.error("Error parsing JSON response:", e);
                                    document.getElementById('suggestionsList').innerHTML =
                                        '<p>Error loading suggestions</p>';
                                }
                            } else {
                                console.error("Error: " + xhr.statusText);
                                document.getElementById('suggestionsList').innerHTML =
                                    '<p>Error loading suggestions</p>';
                            }
                        };

                        xhr.onerror = function() {
                            console.error("Request error...");
                            document.getElementById('suggestionsList').innerHTML =
                                '<p>Error loading suggestions</p>';
                        };

                        xhr.send();
                    } else {
                        document.getElementById('suggestionsList').innerHTML =
                        ''; // Clear suggestions if query is too short
                        document.getElementById('suggestionsList').style.display = 'none'; // Hide suggestions box
                    }
                }, 1000);
            }

            // Hide suggestions when clicking outside
            const searchInput = document.getElementById('searchInput');
            if (searchInput) {
                document.addEventListener('click', function(event) {
                    const suggestionsList = document.getElementById('suggestionsList');

                    // If the click was outside both the search input and suggestions list, hide the suggestions
                    if (!searchInput.contains(event.target) && !suggestionsList.contains(event.target)) {
                        suggestionsList.style.display = 'none'; // Hide suggestions when clicking outside
                    }
                });

                // Show suggestions when clicking inside the search box (if results exist)
                document.getElementById('searchInput').addEventListener('click', function() {
                    const suggestionsList = document.getElementById('suggestionsList');
                    if (this.value.length >= 2 && suggestionsList.innerHTML.trim() !== '') {
                        suggestionsList.style.display =
                        'block'; // Show suggestions if input has value and there are results
                    }
                });
            }
        @endif
        function showConsultation(type) {
            // Hide both sections initially
            $('#videocallsection').hide();
            $('#chatsection').hide();
            $('#voicesection').hide();

            // Show the selected section with a slide down and fade-in effect
            if (type === 'call') {
                $('#videocallsection').stop(true, true).hide().fadeIn(800).slideDown(800);
            } else if (type === 'chat') {
                $('#chatsection').stop(true, true).hide().fadeIn(800).slideDown(800);
            } else if (type === 'voice') {
                $('#voicesection').stop(true, true).hide().fadeIn(800).slideDown(800);
            }
        }

        $(document).ready(function() {
            if ($('#loader-web').hasClass('account-pages-skeleton') || $('#loader-web').hasClass('account-dashboard-skeleton') || $('#loader-web').hasClass('astrologer-dashboard-skeleton') || $('#loader-web').hasClass('astrologer-account-skeleton') || $('#loader-web').hasClass('kundali-report-skeleton') || $('#loader-web').hasClass('home-skeleton') || $('#loader-web').hasClass('panchang-skeleton') || $(
                    '#loader-web').hasClass('horoscopes-skeleton') || $('#loader-web').hasClass('horoscope-detail-skeleton') || $('#loader-web').hasClass(
                    'chat-astrologers-skeleton') || $('#loader-web').hasClass('collections-skeleton') || $(
                    '#loader-web').hasClass('kundali-skeleton') || $('#loader-web').hasClass(
                    'auth-register-skeleton') || $('#loader-web').hasClass('astrologer-detail-skeleton') || $(
                    '#loader-web').hasClass('articles-skeleton') || $('#loader-web').hasClass(
                    'about-page-skeleton') || $('#loader-web').hasClass('contact-page-skeleton') || $('#loader-web').hasClass('astrologer-login-skeleton')) {
                var skeletonShownAt = Date.now();
                var skeletonDismissed = false;
                var dismissHomeSkeleton = function() {
                    if (skeletonDismissed) return;
                    skeletonDismissed = true;
                    var visibleFor = Date.now() - skeletonShownAt;
                    var remainingVisibleTime = Math.max(0, 900 - visibleFor);
                    window.setTimeout(function() {
                        $('#loader-web').addClass('is-dismissing');
                        window.setTimeout(function() {
                            $('#loader-web').remove();
                        }, 350);
                    }, remainingVisibleTime);
                };
                if (document.readyState === 'complete') dismissHomeSkeleton();
                else $(window).one('load', dismissHomeSkeleton);
                window.setTimeout(dismissHomeSkeleton, 5000);
            } else {
                $('#loader-web').hide();
            }
            // Remove item from the cart
            $(document).on('click', '.remove-mini-item', function() {
                var itemId = $(this).data('id');
                $.ajax({
                    url: '/cart/remove/' + itemId, // Assuming this is your remove route
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.status === 'success') {
                            toastr.success('Item removed successfully');
                            $('#cart-item-' + itemId).remove();
                            showCart2();
                        }
                    },
                    error: function(xhr) {
                        toastr.error('Error removing item to cart. Please try again');
                    }
                });
            });

        });

        $('.toggle_wishlist').on('click', function(e) {
            let actionType = $(this).data('action-type');
            let url = "{{ route('wishlist') }}";
            if (actionType == 'remove') {
                url = "{{ route('wishlist.remove') }}";
            }
            var formData = new FormData();
            formData.append('actionType', $(this).data('action-type'))
            formData.append('item_id', $(this).data('item_id'))
            formData.append('type', $(this).data('type'))
            formData.append('wishlist_id', $(this).data('wishlist_id'))
            let that = this;
            $.ajax({
                url: url,
                enctype: 'application/json',
                method: "POST",
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (actionType == 'add') {
                        $(that).data('wishlist_id', response.wishlist_id);
                        $(that).find('i').removeClass('bi-heart').addClass('bi-heart-fill text-danger');
                        $(that).data('action-type', 'remove');
                        toastr.success('Item added in wishlist successfully.');
                    } else if (actionType == 'remove') {
                        $(that).data('action-type', 'add');
                        $(that).data('wishlist_id', '0');
                        $(that).find('i').removeClass('bi-heart-fill text-danger');
                        toastr.success('Item remove from wishlist successfully.');
                    }
                },
                error: function(xhr) {
                    console.log(xhr)
                }
            });
        });

        if ($('.remove_from_wishlist_btn')) {
            $('.remove_from_wishlist_btn').on('click', function(e) {
                var formData = new FormData();
                formData.append('wishlist_id', $(this).data('wishlist_id'))
                let that = this;
                $.ajax({
                    url: "{{ route('wishlist.remove') }}",
                    enctype: 'application/json',
                    method: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        toastr.success('Item remove from wishlist successfully.');
                        // $(that).parent().parent().parent().remove();
                        setTimeout(function() {
                            window.location.reload();
                        }, 1500);
                    },
                    error: function(xhr) {
                        // $(that).parent().parent().parent().remove();
                    }
                });
            });
        }

        function checkAuth(url, redirectTo = '') {
            sessionStorage.setItem('redirect_from_login', url);
            let data = {
                redirectUrl: url
            }
            $.ajax({
                url: @json(route('handle.redirect.url')),
                method: "POST",
                data: data,
                success: function(response) {
                    toastr.success('Please login');
                    setTimeout(function() {
                        if (redirectTo) {
                            window.location.href = redirectTo;
                        } else {
                            window.location.href = @json(route('login'));
                        }

                    }, 2000);
                },
                error: function(error) {
                    console.log(error)
                }
            });
        }

        function increaseQuantity(cartId = 0) {
            let url = @json(url('/cart/increase-quantity'));
            url = url + '/' + cartId;
            $.ajax({
                url: url,
                method: "POST",
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}' // Add CSRF token for security
                },
                success: function(response) {
                    toastr.success(response.message);
                    showCart2();
                },
                error: function(error) {
                    console.log(error)
                }
            });
        }

        function decreaseQuantity(cartId = 0) {
            let url = @json(url('/cart/decrease-quantity'));
            url = url + '/' + cartId;
            $.ajax({
                url: url,
                method: "POST",
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}' // Add CSRF token for security
                },
                success: function(response) {
                    toastr.success(response.message);
                    showCart2();
                },
                error: function(error) {
                    console.log(error)
                }
            });
        }

        function showCart2() {
            console.log('show cart 2')
            const userId = '{{ \Session::get('cart_user_id') }}';
            $.ajax({
                type: 'POST',
                url: '{{ route('mini.cart') }}', // URL to submit the data
                data: {
                    user_id: userId,
                    _token: '{{ csrf_token() }}' // Include CSRF token for security
                },
                success: function(response) {
                    sessionUserId = response.user_id;
                    console.log(response.totalCount);
                    if (response.html) {
                        $("#subTotalCheckoutBtnDiv").removeClass("d-none");
                        $('.minicartdata').html(response.html);
                        $('#subTotalPrice').html(response.totalPrice);
                        $('#totalcartproduct').text(response
                        .totalCount); // Update the total count based on response
                        $('#totalcartproductmob').text(response
                        .totalCount); // Update the total count based on response
                    } else {
                        $("#subTotalCheckoutBtnDiv").addClass("d-none");
                        let src = $('#offcanvasWithBothOptions').attr('data-empty-cart');
                        $('.minicartdata').html(
                            `<div class="cartb-img"><img src="${src}"><h5>No Items Found!</h5><p> Your Cart items will be displayed here.</p></div>`
                            );
                        $('#subTotalPrice').html(0);
                        $('#totalcartproduct').text(0); // Update the total count based on response
                        $('#totalcartproductmob').text(0); // Update the total count based on response
                    }
                    if (response.hasOutOfStockProduct) {
                        $("#subTotalCheckoutBtnDiv").addClass("d-none");
                        toastr.error('Please remove out of stock item from cart to checkout.');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching cart:', error);
                }
            });
        }

        function hideShow(cls) {
            $('.' + cls).toggleClass('d-none')
        }
    </script>

    <script>
        let lastScrollTop = 0;
        const header = document.querySelector('.header.desktop');
        const stickySection = null;
        if (header && stickySection) {
            window.addEventListener('scroll', function() {
                let scrollTop = window.scrollY || document.documentElement.scrollTop;

                if (scrollTop > lastScrollTop) {
                    // Scrolling down - hide header
                    header.classList.add('header-hidden');
                    stickySection.style.top = "0px"; // Move sticky section up
                } else {
                    // Scrolling up - show header
                    header.classList.remove('header-hidden');
                    stickySection.style.top = "54px"; // Make space for header
                }

                lastScrollTop = scrollTop;
            });
        }
    </script>
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {
            // Jab koi bhi offcanvas open ho to scroll disable kar do
            document.addEventListener('shown.bs.offcanvas', function() {
                document.body.style.overflow = 'hidden';
            });

            // Jab koi bhi offcanvas close ho to scroll enable kar do
            document.addEventListener('hidden.bs.offcanvas', function() {
                document.body.style.overflow = '';
            });
        });
    </script>

    <script type="text/javascript">
        var typingTimer;

        function handleLocation(inputId, suggestionId) {
            clearTimeout(typingTimer);
            typingTimer = setTimeout(function() {
                var inputVal = $(inputId).val();
                if (inputVal.length > 2) {
                    $(suggestionId).html('<li>Searching...</li>');
                    $.ajax({
                        url: '/search-location/' + encodeURIComponent(inputVal),
                        method: 'GET',
                        success: function(response) {
                            $(suggestionId).empty();
                            response.forEach(function(location) {
                                var li = $('<li class="pointer"></li>');
                                li.text(location.place + ', ' + location.state + ', ' + location
                                    .country);
                                li.on('click', function() {
                                    $(inputId).val($(this).text());
                                    $(suggestionId).empty();
                                });
                                $(suggestionId).append(li);
                            });
                        },
                        error: function(xhr, status, error) {
                            $(suggestionId).html('');
                        }
                    });
                } else {
                    $(suggestionId).empty();
                }
            }, 1000);
        }
    </script>

    <script type="text/javascript">
        function userNotification(user_id, astrologer_id, notify_to, type, description, message) {
            var formData = new FormData();
            formData.append('user_id', user_id);
            formData.append('astrologer_id', astrologer_id);
            formData.append('notify_to', notify_to);
            formData.append('type', type);
            formData.append('description', description);
            formData.append('message', message);
            $.ajax({
                url: "{{ route('usernotification') }}",
                method: "POST",
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function(response) {
                    toastr.success(response.message);
                    setTimeout(function() {
                        window.location.reload();
                    }, 2000);
                },
                error: function(xhr) {
                    let errors = xhr.responseJSON.errors;
                    if (errors) {
                        for (var key in errors) {
                            toastr.error(errors[key][0]);
                        }
                    } else {
                        let msg = xhr.responseJSON.message;
                        if (msg) {
                            toastr.error(error[key][0]);
                        }
                    }
                }
            });
            return false;
        }
    </script>

    @if (\Session::has('alert'))
        <script type="text/javascript">
            toastr.warning("{{ \Session::get('alert') }}");
        </script>
    @endif

    <script type="text/javascript">
        @if ($astrologer && !isset($stopCurrentAppointmentInterval))
            let currentAppointmentInterval = null;
            let navigateInterval = null;

            function currentAppointment() {
                let url = "{{ route('astrologer.currentappointment') }}";
                $.ajax({
                    url: url,
                    method: "GET",
                    data: {
                        _token: "{{ request()->has('_token') ? request()->get('_token') : '' }}"
                    },
                    success: function(response) {
                        clearInterval(currentAppointmentInterval);
                        playAudio();
                        Swal.fire({
                            text: response.message,
                            confirmButtonText: 'Accept',
                            allowOutsideClick: false,
                            allowEscapeKey: false
                        }).then((result) => {
                            if (result.isConfirmed) {
                                let redirectTo = response.redirectTo;
                                if (redirectTo) {
                                    window.location.href = redirectTo;
                                }
                            }
                        });
                        setTimeout(function() {
                            window.location.reload();
                        }, 180000);
                    },
                    error: function(error) {
                        console.log(error);
                    }
                });
            }
            currentAppointmentInterval = setInterval(currentAppointment, 15000);
            // currentAppointment();
        @endif
        // document.getElementById("hiddenAudio").style.display = "none";
        function playAudio() {
            var audio = document.getElementById("hiddenAudio");
            audio.play();
        }
        @if ($astrologer)
            let updateLoginTimeInterval = null;

            function updateLoginTime() {
                let url = "{{ route('astrologer.updatelogintime') }}";
                $.ajax({
                    url: url,
                    method: "GET",
                    success: function(response) {
                        console.log(response);
                        if (updateLoginTimeInterval == null) {
                            updateLoginTimeInterval = setInterval(updateLoginTime, 300000);
                        }
                    },
                    error: function(error) {
                        console.log(error);
                    }
                });
            }
            updateLoginTime();
        @endif
    </script>
    <script>
        $(document).ready(function() {
            $('.articles-slider').slick({
                slidesToShow: 4,
                slidesToScroll: 1,
                arrows: true,
                dots: true,
                infinite: true,
                autoplay: false,
                responsive: [{
                        breakpoint: 1024,
                        settings: {
                            slidesToShow: 2,

                        }
                    },
                    {
                        breakpoint: 768, // mobile
                        settings: {
                            slidesToShow: 1
                        }
                    }
                ]
            });
        });
        $(document).ready(function() {
            $('.slick-slider-class').slick({
                slidesToShow: 5,
                slidesToScroll: 1,
                autoplay: false,
                autoplaySpeed: 2000,
                arrows: true,
                dots: false,

                responsive: [{
                        breakpoint: 1024,
                        settings: {
                            slidesToShow: 4,

                        }
                    },
                    {
                        breakpoint: 768,
                        settings: {
                            slidesToShow: 3,

                        }
                    },
                    {
                        breakpoint: 480,
                        settings: {
                            slidesToShow: 2,

                        }
                    }
                ]
            });
        });
    </script>


    <script>
        $(document).ready(function() {
            $('.product-slider').slick({
                slidesToShow: 5, // Desktop pe 4
                slidesToScroll: 1,
                autoplay: false,
                autoplaySpeed: 2000,
                arrows: true,
                dots: false,
                responsive: [{
                        breakpoint: 992, // Tablet
                        settings: {
                            slidesToShow: 2
                        }
                    },
                    {
                        breakpoint: 576, // Mobile
                        settings: {
                            slidesToShow: 2
                        },
                    }
                ]
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('.kundli-slider').slick({
                slidesToShow: 4,
                slidesToScroll: 1,
                autoplay: false,
                autoplaySpeed: 3000,
                arrows: true,
                dots: false,
                responsive: [{
                        breakpoint: 1024,
                        settings: {
                            slidesToShow: 4,

                        }
                    },
                    {
                        breakpoint: 768,
                        settings: {
                            slidesToShow: 3,

                        }
                    },
                    {
                        breakpoint: 480,
                        settings: {
                            slidesToShow: 2,

                        }
                    }
                ]
            });
            $('.kundli-slider-new').slick({
                slidesToShow: 5,
                slidesToScroll: 1,
                autoplay: false,
                autoplaySpeed: 3000,
                arrows: true,
                dots: false,
                responsive: [{
                        breakpoint: 1024,
                        settings: {
                            slidesToShow: 5,

                        }
                    },
                    {
                        breakpoint: 768,
                        settings: {
                            slidesToShow: 3,

                        }
                    },
                    {
                        breakpoint: 480,
                        settings: {
                            slidesToShow: 2,

                        }
                    }
                ]
            });
        });
    </script>


    <script>
        $(document).ready(function() {
            $('.pooja-slider').slick({
                slidesToShow: 4, // Desktop pe 4 cards
                slidesToScroll: 1,
                autoplay: false,
                autoplaySpeed: 2000,
                arrows: true,
                dots: false,
                responsive: [{
                        breakpoint: 992, // Tablet
                        settings: {
                            slidesToShow: 2
                        }
                    },
                    {
                        breakpoint: 576, // Mobile
                        settings: {
                            slidesToShow: 2
                        }
                    }
                ]
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('.testimonial-slider').slick({
                slidesToShow: 2, // default desktop
                slidesToScroll: 1,
                infinite: true,
                arrows: true,
                dots: true,
                autoplay: false,
                autoplaySpeed: 3000,
                responsive: [{
                    breakpoint: 768, // phones
                    settings: {
                        slidesToShow: 1
                    }
                }]
            });
        });
    </script>


    <script>
        $(document).ready(function() {
            $('.vtestimonials').slick({
                slidesToShow: 3,
                slidesToScroll: 1,
                infinite: true,
                arrows: true,
                dots: true,
                autoplay: true,
                autoplaySpeed: 3000,
                responsive: [{
                        breakpoint: 992, // tablet & below
                        settings: {
                            slidesToShow: 2
                        }
                    },
                    {
                        breakpoint: 768, // phones
                        settings: {
                            slidesToShow: 1
                        }
                    }
                ]
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            // Jab kahi bhi click ho
            $(document).on('click', function(e) {
                // Agar click .search-input ke andar nahi hua
                if (!$(e.target).closest('.search-input').length) {
                    $('.search-input').addClass('d-none'); // box hide kar do
                    $('.custom-suggestion-box').addClass('d-none');
                }
            });

            // Jab search input par click ho to dikhado
            $('#search').on('click', function(e) {
                e.stopPropagation(); // prevent document click hide
                $('.search-input').removeClass('d-none');
            });

            // Jab search ke andar likha jaye (keyup)
            $('.search-input input').on('keyup', function() {
                $('.custom-suggestion-box').removeClass('d-none');
            });

            @if (isset($errorToastr) && $errorToastr)
                toastr.error("{{ $errorToastr }}");
            @endif
        });

        function handleNotifications(that) {
            $("#sidebar-close-btn").click();
        }
    </script>

    <script>
        let scrollTimeout;
        window.addEventListener('scroll', () => {
            const button = document.getElementById('scrollButton');
            if (!button) return;

            // Show the button when scrolling
            button.style.display = 'block';

            // Clear the timeout if it's already set
            if (scrollTimeout) clearTimeout(scrollTimeout);

            // Set a timeout to hide the button after a delay
            scrollTimeout = setTimeout(() => {
                button.style.display = 'none';
            }, 2000); // Adjust time (ms) to suit your preference
        });
    </script>
    <script type="text/javascript">
        function handleEnter(that, event, submitBtnId) {
            if (event.key === 'Enter') {
                $(`#${submitBtnId}`).click();
            }
        }
    </script>


    @stack('scripts')

    {!! fromSetting('footer_script') !!}

</body>

</html>
