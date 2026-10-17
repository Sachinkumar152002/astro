<nav class="navbar page-header admin-page-header">
  <a href="#" class="btn btn-link sidebar-mobile-toggle d-md-none mr-md-auto">
    <i class="fa fa-bars"></i>
  </a>
  <a class="navbar-brand admin-header-brand" href="{{ route('admin.dashboard') }}">
    <img src="{{ logo() }}" alt="{{ config('app.name') }}" class="img-fluid admin-header-logo">
    <span class="admin-header-project-name">{{ config('app.name') }}</span>
  </a>
  <a href="#" class="btn btn-link sidebar-toggle d-md-down-none">
    <img src="{{asset('assets/images/menu-black.png')}}" alt="{{config('app.name')}}" class="menu-icon-admin" style="width:20px;">
  </a>
  <!-- breadcrumb-start -->
  <div class="">
    <!-- <p class="p-0 mb-0 ml-5 bread-heading">Course</p> -->
    <div class="d-none d-md-block">


    <ul class="new-bread pb-0 m-0 pl-m">
      <li>
        <a href="{{route('admin.dashboard')}}"><i class="bi bi-house-door text-dark"></i>&nbsp/&nbsp Dashboard &nbsp</a>
      </li>
      @if(isset($breads))
        @foreach($breads as $bread)
          <li>
            <span>/&nbsp</span>
          </li>
          <li class="{{!isset($bread['url']) ? 'active': '' }}">
            @if(isset($bread['url']))
              <a href="{{$bread['url']}}">&nbsp{{$bread['name']}}&nbsp</a>
            @else
              {{ $bread['name'] }}
            @endif
          </li>
        @endforeach
      @endif
    </ul>
</div>
  </div>
  <div class="m-auto nav-s d-none d-md-block">
    <div class="row" id="search_div_temp">
      <div class="col-10">
        <div class="form-group my-auto">
          <input type="text" name="search_string_temp" id="search_string_temp" class="form-control" placeholder="🔍 Search" onkeyup="openSearchListModal()">
        </div>
      </div>
      <div class="col-2">
        <button type="button" class="btn btn-success" id="search-btn-temp" onclick="openSearchListModal()">
          <i class="fas fa-search"></i>
        </button>
      </div>
    </div>
  </div>
  <!-- breadcrumb-end -->
  <button type="button" class="btn btn-success d-md-none d-block" id="search-btn-temp" onclick="openSearchListModal()">
    <i class="fas fa-search"></i>
  </button>
  <ul class="navbar-nav ml-md-auto">
    <div class="">
      <a href="{{ route('admin.notification.index', [0, 0]) }}" class="notif-btn ">
        <span class="icon">
          <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M18 8a6 6 0 10-12 0c0 7-3 7-3 7h18s-3 0-3-7"></path>
            <path d="M13.73 21a2 2 0 01-3.46 0"></path>
          </svg>
        </span>

        <span class="badge">3</span>
      </a>
    </div>
    <li class="nav-item dropdown">
      <a class="nav-link dropdown-toggle" href="javascript:void(0)" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <img src="{{asset('theme-admin/imgs/avatar-1.png')}}" class="avatar avatar-sm" alt="logo">
        <span class="small ml-1 d-md-down-none text-black">@if (Auth::guard('admin')->check()) {{Auth::guard('admin')->user()->name}} @endif</span>
      </a>
      <div class="dropdown-menu dropdown-menu-right">
        <a href="{{route('admin.settings')}}" class="dropdown-item">
          <i class="fa fa-list"></i> Settings
        </a>
        <a href="{{route('admin.logout')}}" class="dropdown-item">
          <i class="fa fa-lock"></i> Logout
        </a>
      </div>
    </li>
  </ul>


</nav>

<!-- search list modal start -->
<div class="modal fade" id="searchListModal" tabindex="-1" role="dialog" aria-labelledby="searchListModalLabelled" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content modal-s" style="max-width:500px;max-height:70vh;">
      <div class="modal-header">
        <h5 class="modal-title" id="searchListModalLabelled">Search List</h5>
        <button type="button" class="close" onclick="closeSearchListModal(this)">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row" id="search_div">
          <div class="col-10">
            <div class="form-group">
              <input type="text" name="search_string" id="search_string" class="form-control" placeholder="🔍 Search" onkeyup="handleSearchList()">
            </div>
          </div>
          <div class="col-2 search-bb">
            <button type="button" class="btn btn-success" id="search-btn" onclick="handleSearchList()">
              <i class="fas fa-search"></i>
            </button>
          </div>
        </div>
        <div class="table-scroll" style="display:block;max-height:50vh;overflow-y:auto;">
          <table class="table" id="search-list-table" data-url="{{ route('admin.search.list') }}">
            {{--<thead>
              <tr class="bg-primary text-white">
                <th>Image</th>
                <th>Name</th>
              </tr>
            </thead>--}}
            <tbody>
            </tbody>
            <tfoot class="d-none">
              <tr>
                <td colspan="5" class="pagination-second"></td>
              </tr>
            </tfoot>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- search list modal end -->
