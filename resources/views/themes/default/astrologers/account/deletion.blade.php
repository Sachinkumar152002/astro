@extends(themeView().'layout')

@section('title', 'Profile')
@section('keywords', '')
@section('description','')

@section('content')
{{--
@include(themeView().'astrologers._partials.top')
--}}
<section class="dashboad_section" style="background: #f2f2f2;">
<div class="container">
    <div class="row">
        @include(themeView().'astrologers._partials.sidebar')
        <div class="col-sm-9 mt-5 mt-md-0">
           <div class="row">
               @if(Session::has('message'))
               <div class="col-md-12">
                   <div class="alert alert-success">
                       {{Session::get('message')}}
                   </div>
               </div>
               @endif
               <div class="col-sm-12">
                   <h3>Delete My Account</h3>
                   <p>Are you sure you want to delete your account? This action cannot be undone.</p>

                    <!-- Delete Account Button (Triggers Modal) -->
                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal">
                        Delete My Account
                    </button>

               </div>
           </div>
         </div>
    </div>
  </div>
</section>
<!-- Confirmation Modal -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel">Confirm Account Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you **absolutely sure** you want to delete your account? This action **cannot be undone**.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{route('astrologer.deletion.confirm')}}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-danger">Yes, Delete My Account</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
