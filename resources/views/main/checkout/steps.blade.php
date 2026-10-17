<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', config('sst.site_name')) }}</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{asset('css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/bootstrap-select.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/toastr.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/font-awesome.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/stepcheckout.css')}}">

    <!-- Scripts -->
    <script>
    window.Laravel = {!! json_encode([
    'csrfToken' => csrf_token(),
    ]) !!};
    </script>
     <script async src="https://www.googletagmanager.com/gtag/js?id=UA-111991155-1"></script>
        <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', 'UA-111991155-1');
        </script>
    <style type="text/css">
      .summary_heading a { color: #000; display: block; }
      .active_heading.summary_heading a { display: none; }
        .btn-cancel{
            background: transparent;
            border: none;
            color: #353535;
            margin-top: 10px;
            text-decoration: underline;
            cursor: pointer;
        }
    </style>
  </head>
  <body class="bg-grey">
    <header class="checkout_header">
        <div class="text-center"><a href="/"><img src="{{asset('images/logo.png')}}"></a></div>
    </header>
    <div class="wrapper inner-wrapper ">
      @if($cartItems && is_array($cartItems) && count($cartItems)>0)
          @if(Auth::guard('employer')->check())
            <!-- Step 1 -->
              @include('main.checkout.steps.employer.items')
              <!-- Step 2 -->
              @include('main.checkout.steps.employer.details')
              <!-- Step 3 -->
              @include('main..checkout.steps.employer.payment')
          @else
              <!-- Step 1 -->
              @include('main.checkout.steps.student.items')
              <!-- Step 2 -->
              @include('main.checkout.steps.student.details')
              <!-- Step 3 -->
              @include('main..checkout.steps.student.payment')
          @endif
      @else
         @if(Auth::guard('employer')->check())
              <p class="box-card">No item in cart. Click <a href="{{route('employer.plan')}}">here</a> to continue shopping</p>
          @else
            <p class="box-card">No item in cart. Click <a href="{{route('courses')}}">here</a> to continue shopping</p>
          @endif
      @endif
    </div>
    <script src="{{asset('js/jquery.3.1.1.min.js')}}"></script>
    <script src="{{asset('js/bootstrap.min.js')}}"></script>
    <script src="{{asset('js/bootstrap-select.min.js')}}"></script>
    <script src="{{asset('js/axios.min.js')}}"></script>
    <script src="{{asset('js/toastr.min.js')}}"></script>
    <script src="{{asset('js/jquery.validate.min.js')}}"></script>
    <script src="{{asset('js/csc-locations.js')}}"></script>
    @if(!Auth::guard('employer')->check())
    <script type="text/javascript">
        $('#coupon-form').validate({
        submitHandler : function(form){
                  var form = $(form);
                      axios.post(form.attr('action'), form.serialize())
                      .then(function (response) {
                          console.log(response);
                          toastr.success(response.data.message);
                          setTimeout(function(){
                              window.location.href = '{{route('cart.view')}}';
                          }, 2000);
                      })
                      .catch(function (error) {
                           var errors = error.response.data;
                          for(i in errors){
                              toastr.error(errors[i]);
                          }
                      });
                       return false;
                  }
          });
    </script>
    @endif

    <script type="text/javascript">
        var moveToStep = function(step) {
            var step1 = $('#step-1-summary');
            var step2 = $('#step-2-details');
            var step3 = $('#step-3-payment');

            step1.find('.summary_heading').removeClass('active_heading');
            step2.find('.summary_heading').removeClass('active_heading');
            step3.find('.summary_heading').removeClass('active_heading');

            step1.find('.course-summary-section').slideUp();
            step2.find('.course-summary-section').slideUp();
            step3.find('.course-summary-section').slideUp();

            step2.find('.summary_heading a').hide();

            switch(step){
              case 1:
                step1.find('.summary_heading').addClass('active_heading');
                step1.find('.course-summary-section').slideDown();
              break;
              case 2:
                step2.find('.summary_heading').addClass('active_heading');
                step2.find('.course-summary-section').slideDown();
              break;
              case 3:
                step3.find('.summary_heading').addClass('active_heading');
                step3.find('.course-summary-section').slideDown();
                step2.find('.summary_heading a').show();
              break;
            }
            window.localStorage.setItem("moveStep", 0)
        }

      csc.country_elm = $('#bill_country');
      csc.state_elm   = $('#bill_state');
      csc.city_elm    = $('#bill_city');

        @if(Auth::check())
          csc.country_id = '{{auth()->user()->details->country}}';
          csc.state_id   = '{{auth()->user()->details->state}}';
          csc.city_id    = '{{auth()->user()->details->city_id}}';
        $('#details-form').validate({
           rules: {},
          submitHandler : function(form){
              var form = $(form);
              $('#details-submit').addClass('disable').attr('disabled', 'disabled').text('Submitting..');
              axios.post(form.attr('action'), form.serialize())
              .then(function (response) {
                  toastr.success('Submitted Values are saved');
                  $('#details-submit').text('Success');
                  window.localStorage.setItem("moveStep", 3);
                  window.location.reload();
              })
              .catch(function (error) {
                  $('#details-submit').removeClass('disable').removeAttr('disabled').text('Submit');
              });
              return false;
          }
          });
        @elseif(Auth::guard('employer')->check())
          csc.country_id = '{{auth()->guard('employer')->user()->country}}';
          csc.state_id   = '{{auth()->guard('employer')->user()->state}}';
          csc.city_id    = '{{auth()->guard('employer')->user()->city_id}}';
        $('#details-form').validate({
           rules: {},
          submitHandler : function(form){
              var form = $(form);
              $('#details-submit').addClass('disable').attr('disabled', 'disabled').text('Submitting..');
              axios.post(form.attr('action'), form.serialize())
              .then(function (response) {
                  toastr.success('Submitted Values are saved');
                  $('#details-submit').text('Success');
                  window.localStorage.setItem("moveStep", 3);
                  window.location.reload();
              })
              .catch(function (error) {
                  $('#details-submit').removeClass('disable').removeAttr('disabled').text('Submit');
              });
              return false;
          }
          });
        @else
        $("#login-form").validate({
          submitHandler : function(form){
             $('#login-submit').addClass('disabled').attr('disabled', 'disabled').text('Validating..');
              var form = $(form);
              axios.post(form.attr('action'), form.serialize())
              .then(function (response) {
                  $('#login-submit').text('Success');
                  window.localStorage.setItem("moveStep", 2);
                  window.location.reload();
              })
              .catch(function (error) {
                   var errors = error.response.data;
                  for(i in errors){
                      toastr.error(errors[i]);
                  }
                   $('#login-submit').removeClass('disabled').removeAttr('disabled').text('Sing In');
              });
             return false;
        }
      });
        $('#registration-form').validate({
           rules: {
                  password_confirmation: {
                      equalTo: "#register_password"
                  }
           },
          submitHandler : function(form){
              var form = $(form);
              $('#register-submit').addClass('disable').attr('disabled', 'disabled').text('Submitting..');
              axios.post(form.attr('action'), form.serialize())
              .then(function (response) {
                  toastr.success('Thankyou for registeration');
                  $('#login-submit').text('Success');
                  window.localStorage.setItem("moveStep", 3);
                  window.location.reload();
              })
              .catch(function (error) {
                  var errors = error.response.data;
                  for(i in errors){
                      toastr.error(errors[i]);
                  }
                  $('#register-submit').removeClass('disable').removeAttr('disabled').text('Register');
              });
              return false;
          }
          });
          @endif
          var toMovestep = parseInt(window.localStorage.getItem("moveStep"));
          if(toMovestep!=0) {
              moveToStep(toMovestep);
          }
    </script>
  <script type="text/javascript">
      function confirmation(){
          if(confirm('Are you sure want to cancel this transaction?')){
              history.go(-1);
          } else {
              return false;
          }
      }
  </script>
  </body>
</html>
