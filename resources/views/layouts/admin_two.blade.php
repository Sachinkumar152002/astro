<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Admin - {{config('app.name')}}</title>
    <link rel="icon" href="{{asset('favicon.ico')}}" type="image/png" sizes="16x16">
    <link rel="stylesheet" href="{{asset('theme-admin/vendor/simple-line-icons/css/simple-line-icons.css')}}">
    <link rel="stylesheet" href="{{asset('theme-admin/vendor/font-awesome/css/fontawesome-all.min.css')}}">
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100;200;300;400;500;600;700;800;900&amp;display=swap"  rel="stylesheet">
    <link rel="stylesheet" href="{{asset('theme-admin/vendor/bootstrap/js/bootstrap-tagsinput.css')}}">
    <link rel="stylesheet" href="{{asset('css/bootstrap-select.min.css')}}">
    <link rel="stylesheet" href="{{asset('theme-admin/css/styles.css')}}">
    <link rel="stylesheet" href="{{asset('css/toastr.min.css')}}">

    <meta name="csrf-token" content="{{ csrf_token() }}">
     <script>
        window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
        ]) !!};
    </script>
    <style>
        .tox-statusbar__branding{
            display: none;
        }
        /* .header-fixed{
          position:relative
        }
        .add-new-button{
          position:absolute;
          top:0%;
          z-index: 99;
        } */
    </style>
    @stack('styles')
</head>
<body class="sidebar-fixed header-fixed">
  <div class="section-header float-left">
    <ol class="breadcrumb" style="z-index:100">
      @if(isset($breads))
        @foreach($breads as $key => $bread)
          <li class="{{!isset($bread['url']) ? 'active': '' }}">
            @if(isset($bread['url']) && $bread['url'])
              <a href="{{$bread['url']}}">{{$bread['name']}}</a>
            @else
            <a href="javascript:void(0)">{{$bread['name']}}</a>
            @endif
          </li>
          @if(count($breads)-1 != $key)
            <li>
                <span>></span>
            </li>
          @endif
        @endforeach
      @endif
    </ol>
  </div>
  <div class="px-3">
    @yield('content')
  </div>
  {{--
<div class="page-wrapper">

    <div class="main-container">

        <div class="content">
            <div class="container-fluid">
                <section>


<div class="section-body contain-lg">
        <!-- BEGIN RESPONSIVE TABLE 1 -->
        <div class="row">
            <div class="col-lg-12">
            <div class="card">

                <div class="card-body">
                    @yield('content')

                    </div><!--end .card-body -->
            </div><!--end .card -->
            </div><!--end .col -->
        </div><!--end .row -->
        <!-- END RESPONSIVE TABLE 1 -->

<!-- END RESPONSIVE TABLE 1 -->

                </div>
                </section>
            </div>
        </div>
    </div>
</div>
--}}

@include('admin._partials.modal-delete')
@include('admin.media.view')

<script src="{{asset('theme-admin/vendor/jquery/jquery.min.js')}}"></script>
<script src="{{asset('theme-admin/vendor/popper.js/popper.min.js')}}"></script>
<script src="{{asset('theme-admin/vendor/bootstrap/js/bootstrap.min.js')}}"></script>
<script src="{{asset('js/bootstrap-select.min.js')}}"></script>
<script src="{{asset('js/toastr.min.js')}}"></script>
<script src="{{asset('theme-admin/vendor/chart.js/chart.min.js')}}"></script>
<script src="{{asset('theme-admin/js/carbon.js')}}"></script>
<script src="{{asset('theme-admin/vendor/bootstrap/js/bootstrap-tagsinput.min.js')}}"></script>

@stack('scripts')
@stack('scripts-child')

</body>
</html>
