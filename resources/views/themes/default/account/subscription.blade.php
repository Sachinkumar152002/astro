@extends(themeView().'layout')
@section('title', 'Dashboard')
@section('keywords', '')
@section('description','')
@section('content')
<section class="mt-3 mb-5">
  <div class="container">
   <div class="row justify-content-between">
    @include(themeview().'account._sidebar')
    <div class="col-lg-9 col-12">
       <div class="row my-2 dashbord">
        <div class="col-lg-3 mb-2 mb-md-0">
          <a href="/account/consultation"><div class="card bg-success p-4 d-flex justify-content-center align-items-center">
          <i class="bi fs-2 text bi-headphones text-white"></i> <p class="text-white fw-bold">Subscriptions  Status</p>
          </div></a>
        </div>
        <div class="col-lg-3 mb-2 mb-md-0">
          <a href="/account/courses"><div class="card bg-success p-4 d-flex justify-content-center align-items-center">
          <i class="bi fs-2 text bi-book text-white"></i> <p class="text-white fw-bold">My Learning</p>
          </div></a>
        </div>
        <div class="col-lg-3 mb-2 mb-md-0">
          <a href="/account/orders"><div class="card bg-success p-4 d-flex justify-content-center align-items-center">
          <i class="bi fs-2 text bi-cart text-white"></i> <p class="text-white fw-bold">My Orders</p>
          </div></a>
        </div>
        <div class="col-lg-3 mb-2 mb-md-0">
        <a href="/account/courses">  <div class="card bg-success p-4 d-flex justify-content-center align-items-center">
          <i class="bi fs-2 text bi-bookmark-check text-white"></i> <p class="text-white fw-bold">My Subscriptions</p>
          </div></a>
        </div>
      </div>


      <div class="row my-3 recent">
        <div class="col-lg-12">
          <h4>
          Recent Subscription
          </h4>
        </div>
      </div>

    <div class="card mb-3 bg-light">
      <div class="card-body">
        <h4 class="mb-4">Subscription Info</h4>
        <div class="row mb-3">
           <div class="col-sm-3 col-12">
            <div class="form-group">
              <label>Subscription ID <em class="text-danger">*</em>
              </label>
              <input type="number" placeholder="Full Name" name="name" value="SS Attri" required="required" class="form-control" disabled="disabled">
            </div>
           </div>
          <div class="col-sm-3 col-12">
            <div class="form-group">
              <label> Plan Name <em class="text-danger">*</em>
              </label>
              <input type="text" placeholder="Full Name" name="name" value="SS Attri" required="required" class="form-control" disabled="disabled">
            </div>
          </div>
        <div class="col-sm-3 col-12">
          <div class="form-group">
            <label>Plan Duration<em class="text-danger">*</em>
            </label>
            <select class="form-select" aria-label="Default select example"  disabled="disabled">
            <option selected>Select Plan</option>
            <option value="1">Monthly</option>
           <option value="2">Quarterly</option>
           <option value="3">Half Yearly</option>
         <option value="3">Annually</option>
          </select>
          </div>
        </div>
        <div class="col-sm-3 col-12">
          <div class="form-group">
            <label>Subscription Price <em class="text-danger">*</em>
            </label>
            <input type="number" placeholder="" disabled="disabled" name="phone" value="" required="required" class="form-control">
          </div>
        </div>


        </div>

        <div class="row">
          <div class="col-sm-3 col-12">
            <div class="form-group">
              <label>Start Date <em class="text-danger">*</em>
              </label>
              <input type="date" placeholder="Full Name" name="name" value="SS Attri" required="required" class="form-control" disabled="disabled">
            </div>
          </div>
          <div class="col-sm-3 col-12">
            <div class="form-group">
              <label>End Date <em class="text-danger">*</em>
              </label>
              <input type="date" placeholder="Full Name" name="name" value="SS Attri" required="required" class="form-control" disabled="disabled">
            </div>
          </div>
          <div class="col-sm-3 col-12">
            <div class="form-group">
              <label>Payments Mode <em class="text-danger">*</em>
              </label>
              <select name="gender" required="required" class="form-select">
                <option value="">Select</option>
                <option value="0" selected="selected">select gender</option>
                <option value="1">UPI</option>
                <option value="2">Credit Card</option>
                <option value="3">Debit Card</option>
              </select>
            </div>
          </div>
          <div class="col-sm-3 col-12">
            <div class="form-group">
              <label>Payments Status <em class="text-danger">*</em>
              </label>
              <input type="text" placeholder="" disabled="disabled" name="phone" value="" required="required" class="form-control">
            </div>
          </div>
          </div>

      <div class="col-12 mt-3 pb-3 ms-3">
            <button type="submit" class="btn btn-secondary px-4 py-2 fw-bold" style="height: 100%;" id="renew_btn">
           Renew Plan
            </button>
            <div class="renew-box" style="display:none;">
              <div class="row mt-3">
              <div class="col-sm-4 col-12">
                <div class="form-group">
                  <label>Start Date <em class="text-danger">*</em>
                  </label>
                  <input type="date" placeholder="Full Name" name="name" value="SS Attri" required="required" class="form-control" disabled="disabled">
                </div>
              </div>
              <div class="col-sm-4 col-12">
                <div class="form-group">
                  <label> Plan Name <em class="text-danger">*</em>
                  </label>
                  <input type="text" placeholder="Full Name" name="name" value="SS Attri" required="required" class="form-control" disabled="disabled">
                </div>
              </div>
            <div class="col-sm-4 col-12">
              <div class="form-group">
                <label>Plan Duration<em class="text-danger">*</em>
                </label>
                <select class="form-select" aria-label="Default select example"  disabled="disabled">
                <option selected>Select Plan</option>
                <option value="1">Monthly</option>
               <option value="2">Quarterly</option>
               <option value="3">Half Yearly</option>
             <option value="3">Annually</option>
           </select>
              </div>
            </div>
          </div>




        <div class="col-12 mt-3 pb-3 ms-3">
          <button type="submit" class="btn btn-secondary px-4 py-2 fw-bold" style="height: 100%;">
          Save
          </button>
        </div>




  </div>
  </div>
</section>
@endsection
