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
      <link rel="preconnect" href="https://fonts.googleapis.com">
      <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
      <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
      <link rel="stylesheet" href="{{asset('theme-admin/vendor/bootstrap/js/bootstrap-tagsinput.css')}}">
      <link rel="stylesheet" href="{{asset('css/bootstrap-select.min.css')}}">
      <link rel="stylesheet" href="{{asset('theme-admin/css/styles.css')}}">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
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
        .bg-p {
            background: #FCD7C5;
            border-radius: 8px 8px 0px 0px;
        }
        .admin-page-header { display:flex; align-items:center; gap:16px; padding:0 18px; background:#fff; box-shadow:0 3px 14px rgba(35,24,48,.08); }
        .admin-page-header .admin-header-brand { display:flex; flex:0 0 auto; align-items:center; gap:10px; margin:0!important; padding:0 8px; }
        .admin-page-header .admin-header-logo { display:block; width:auto; max-width:54px; height:auto; max-height:44px; object-fit:contain; }
        .admin-page-header .admin-header-project-name { color:#39234f; font-size:16px; font-weight:700; line-height:1.15; white-space:nowrap; }
        .admin-page-header .sidebar-mobile-toggle,.admin-page-header .sidebar-toggle { display:flex; align-items:center; justify-content:center; width:38px; height:38px; margin:0!important; padding:0; border-radius:10px; color:#553275; background:#f5f0f8; }
        .admin-page-header .sidebar-toggle img { width:18px!important; }
        .admin-page-header .nav-s { max-width:430px; width:100%; }
        .admin-page-header #search_div_temp { align-items:center; margin:0; }
        .admin-page-header #search_div_temp .form-control { height:38px; border:1px solid #e8e1ed; border-radius:9px 0 0 9px; box-shadow:none; }
        .admin-page-header #search_div_temp .btn,.admin-page-header>#search-btn-temp { height:38px; border:0; border-radius:0 9px 9px 0; background:#573476; }
        .admin-page-header .navbar-nav { gap:12px; margin-left:auto; }
        .admin-page-header .notif-btn { display:flex; position:relative; align-items:center; justify-content:center; width:38px; height:38px; border-radius:10px; color:#563476; background:#f6f2f8; }
        .admin-page-header .notif-btn .badge { position:absolute; top:-5px; right:-5px; min-width:17px; padding:3px 5px; border-radius:20px; color:#fff; background:#e05858; font-size:10px; }
        .admin-page-header .avatar { width:34px; height:34px; border-radius:50%; object-fit:cover; }
        .sidebar .admin-sidebar-heading { margin:20px 20px 8px; color:#8b8194; font-size:11px; font-weight:700; letter-spacing:1.3px; text-transform:uppercase; }
        .sidebar .nav-link { margin:2px 10px; width:calc(100% - 20px); border-radius:9px; color:#40364b!important; transition:background .18s ease,color .18s ease; }
        .sidebar .nav-link:hover { color:#573476!important; background:#f0eaf5; }
        .sidebar .nav-link.active { color:#563476!important; background:#eee5f5; font-weight:700!important; }
        .sidebar .nav-link.active i { color:#68438a!important; }
        @media(max-width:767px) {
          .admin-page-header { gap:7px; padding:0 10px; }
          .admin-page-header .admin-header-brand { padding:0 2px; }
          .admin-page-header .admin-header-logo { max-width:42px; max-height:36px; }
          .admin-page-header .admin-header-project-name { font-size:13px; }
          .admin-page-header .navbar-nav { gap:5px; }
          .admin-page-header .navbar-nav .nav-item .nav-link { padding:4px; }
        }
      </style>
      @stack('styles')
  </head>
  <body class="sidebar-fixed header-fixed">
    <div class="page-wrapper">
      @include('admin._partials.header')
      <div class="main-container">
          @include('admin._partials.sidebar')
          <div class="content">
            <div class="container-fluid">
              <section>
                @include('admin._partials.breadcrumbs')
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
      @include('admin._partials.modal-delete');
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
      <script type="text/javascript">
        var checkOnline = function(){
          $.get('{{route('admin.check.status')}}',function( data ) {
            if($.trim(data)!=''){
              window.location.href= data;
            }
          });
        }
        setInterval(checkOnline, 5000);
      </script>
      <script type="text/javascript">
          function openSearchListModal(){
            $("#search_string").val($("#search_string_temp").val());
            $("#search-btn").click();
            $("#searchListModal").modal("show");
            setTimeout(() => {
              $("#search_string").focus();
            }, 500);
          }
          function closeSearchListModal(that){
            $("#search_string_temp").val("");
            $("#searchListModal").modal("hide");
          }
          var searchTableObj=$("#search-list-table");
          function handleSearchList(){
            $.ajax({
               url: searchTableObj.attr("data-url"),
               type : 'GET',
               data : {
                 search_string:$("#search_string").val(),
               },
               success:function(response){
                 // console.log(response);
                 searchTableObj.find('tbody').html(response.data.rows);
               },
               error:function(error){
                 // console.log(error);
                 toastr.error('Error Occured');
               }
             });
          }
      </script>
  </body>
</html>
