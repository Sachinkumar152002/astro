<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    @if(config('app.domain')=='iiag.co.in')
    <meta name="robots" content="index, follow">
    @else
    <meta name="robots" content="noindex, nofollow">
    @endif
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>@yield('title', optionByKey('website_title'))</title>
    <meta name="description" content="@yield('description', optionByKey('description'))" />
    <meta name="keywords" content="@yield('keywords', optionByKey('keywords'))">
    <meta property="og:title" content="@yield('title', optionByKey('website_title'))">
    <meta property="og:type" content="page" />
    <meta name="og:locale" content="en_US">
    <meta property="og:site_name" content="{{optionByKey('website_title')}}">
    <meta property="og:description" content="@yield('description', optionByKey('description'))">
    <meta property="og:image" content="@yield('image', asset('400x400.jpeg'))">
    <meta property="og:url" content="{{url()->current()}}">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:site" content="@IIAG">
    <meta name="twitter:title" content="@yield('title', optionByKey('website_title'))">
    <meta name="twitter:description" content="@yield('description', optionByKey('description'))">
    <meta name="twitter:image" content="@yield('image', logo())">
    <meta name="twitter:image:alt" content="@yield('title', optionByKey('website_title'))">

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{asset('images/favicon.png')}}">
    <script src="https://kit.fontawesome.com/d6d05d5c61.js" crossorigin="anonymous"></script>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css"
            integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400&family=Poppins:wght@300;400;600;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('css-old/toastr.min.css')}}">
    <link rel="stylesheet" href="{{asset('css-old/custom.css')}}">
    @stack('styles')
    <script>
        window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
        ]) !!};
    </script>
    @if(config('app.domain')=='iiag.co.in')
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-8PKZL0VJE1"></script>
    <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'G-8PKZL0VJE1');
    </script>
    <!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-W9JK45P');</script>
    <!-- End Google Tag Manager -->

    @endif
</head>

<body>
    @if(config('app.domain')=='iiag.co.in')
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-W9JK45P"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
    @endif
    @include(themeView().'common.header')
    @yield('content')
    @include(themeView().'common.footer')
    @if(auth()->guest())
        @include(themeView().'account.login')
    @else
        @include(themeView().'account.insufficient-balance')
    @endif
    <!-- Option 1: jQuery and Bootstrap Bundle (includes Popper) -->
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.3/themes/base/jquery-ui.css">
    <script src="{{asset('theme-admin/vendor/jquery/jquery.min.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous">
    </script>
    <script src="https://code.jquery.com/ui/1.13.3/jquery-ui.js"></script>
    <script src="{{asset('js-old/vue.min.js')}}"></script>
    <script src="{{asset('js-old/axios.min.js')}}"></script>
    <script src="{{asset('js-old/toastr.min.js')}}"></script>
    @stack('scripts')
    <script>
        $(".search-astro").on('click', function(){
            $(".search").toggle(100);
        });
        $('#search-box').on('keyup', function(event){
            if (event.keyCode === 13) {
                event.preventDefault();
                window.location.href='{{route('astrologers.index')}}/'+this.value;
            }
        });
        @if(config('app.domain')=='iiag.co.in')
        var $zoho=$zoho || {};$zoho.salesiq = $zoho.salesiq ||
        {widgetcode:"af656e46714abcb7ea468602db282df23d1280e3036c42af1d672e3fe1c0ee97a16d5559689a38a4c87a440dacea8a96", values:{},ready:function(){}};
        var d=document;s=d.createElement("script");s.type="text/javascript";s.id="zsiqscript";s.defer=true;
        s.src="https://salesiq.zoho.in/widget";t=d.getElementsByTagName("script")[0];t.parentNode.insertBefore(s,t);d.write("<div id='zsiqwidget'></div>");
        @endif
    </script>
    @if(auth()->guard('astrologer')->check())
    <script>
    setInterval(() => {
      checkChat();
    },1000);
    checkChat();

    function checkChat() {
      var astrologer_id = "{{auth()->guard('astrologer')->user()->id}}";
      $.ajax({
        url: '{{route('astrologer.chat.check')}}',
        type: 'GET',
        success: function(data) {
          if(data.length) {
            var response = JSON.parse(data);
            $('#chat_id').val(response.id);
            $('#chatAcceptModal').modal('show');
          } else {
            $('#chat_id').val('');
            $('#chatAcceptModal').modal('hide');
          }
        }
      });
    }

    $('.accept').click(function() {
      var chat_id = $('#chat_id').val();
      $.ajax({
        url: '{{route('astrologer.chat.update')}}',
        type: 'POST',
        data: {
          "_token": "{{ csrf_token() }}",
          "type": 'accept',
          "chat_id": chat_id
        },
        Accept: 'application/json',
        success: function(data) {
          console.log(data);
          $('#chat_id').val('');
          $('#chatAcceptModal').modal('hide');
          if(data.status) {
            window.location.href = data.message;
          }
        }
      });
    });

    $('.reject').click(function() {
      var chat_id = $('#chat_id').val();
      $.ajax({
        url: '{{route('astrologer.chat.update')}}',
        type: 'POST',
        data: {
          "_token": "{{ csrf_token() }}",
          "type": 'reject',
          "chat_id": chat_id
        },
        Accept: 'application/json',
        success: function(data) {
          $('#chat_id').val('');
          $('#chatAcceptModal').modal('hide');
        }
      });
    });
    </script>
    @endif
</body>

</html>
