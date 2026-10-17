@extends('layouts.app')
@push('styles')
<link href="{{asset('css/pricetable.css')}}" rel="stylesheet">
@endpush


@section('content')
<section class="slider-section">
  <div id="carousel-fade" class="carousel fade">
    <div class="carousel-inner">
      <div class="item active"> <img src="{{asset('images/smart-hiring.jpg')}}" alt="test" title="ytesy"  width="100%">
        <div class="carousel-caption banner_text"> </div>
      </div>
      <div class="item"> <img src="{{asset('images/skilled-candidates.jpg')}}" alt="test" title="ytesy"  width="100%">
        <div class="carousel-caption banner_text"> </div>
      </div>
    </div>
    <a class="left carousel-control" href="#carousel-fade" data-slide="prev">&lsaquo;</a> <a class="right carousel-control" href="#carousel-fade" data-slide="next">&rsaquo;</a> </div>
</section>
<section class="bg-grey plan-sec">
<div class="container">
<div class="col-md-12">
  <div class="row">
      @if($plans)
      @foreach($plans as $k=>$plan)
    <div class="col-xs-12 col-md-3">
      <div class="panel panel-plan">
        <div class="panel-heading  panel-green" style="background-color: {{$plan->color_name}}">
          <h3 class="panel-title1" > {{$plan->name}}</h3>
        </div>
        <div class="panel-body">
          <div class="the-price panel-green-dark" style="background-color: {{$plan->color_price}}">
            <h1> <sup><i class="fa fa-inr"></i></sup>{{number_format($plan->price)}}</h1>
          </div>
          <table class="table">
            <tr>
              <td> {{$plan->posts}} Job Posting </td>
            </tr>
            <tr>
              <td> {{$plan->downloads}} Resume Downloads </td>
            </tr>
            <tr>
              <td> {{$plan->duration}} day{{$plan->duration>1?'s':''}} Validity </td>
            </tr>
            <tr>
              <td>
                @if(Auth::guard('employer')->check())
                  @if(Auth::guard('employer')->user()->checkAlreadyFree($plan->id, Auth::guard('employer')->user()->id))
                      <p class="btn btn-silver" style="background-color: {{$plan->color_name}}">Already Subscribed</p>
                  @else
                  <a href="{{route('cart.item.addplan', [$plan->id])}}"  class="btn btn-silver" style="background-color: {{$plan->color_name}}">Subscribe</a>
                  @endif
                @else
                <a href="javascript:void(0)" onclick="openModal('registration')" class="btn btn-silver" style="background-color: {{$plan->color_name}}">Subscribe</a>
                @endif
              </td>
            </tr>
          </table>
        </div>
      </div>
    </div>
    @endforeach
    @endif
    <div class="col-xs-12 col-md-3">
      <div class="form-box">
        <div class="form-head">Ask for Demo</div>
        <div class="form-body">
            <form role="form" action="{{route('contact.submit')}}" method="post" id="demo-form" novalidate="novalidate">
                {{csrf_field()}}
            <div class="form-group">
              <input type="text" class="form-control"   placeholder="Name" name="name" required>
            </div>
            <div class="form-group">
              <input type="email" class="form-control"   placeholder="Email" name="email" required>
            </div>
            <div class="form-group">
              <input type="text" class="form-control"   placeholder="Contact Number" name="phone" required>
            </div>
            <div class="form-group">
              <input type="text" class="form-control"   placeholder="Company Name" name="message" required>
            </div>
            <div class="form-group">
                <input type="hidden" class="form-control"  value="Ask For Demo - Employer Portal" name="for">
              <input type="submit" class="btn btn-primary form-control" value="Submit">
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="col-md-12">
  <div class="row">
    <div class="col-md-9"> <img src="{{asset('images/offer-banner-emp.png')}}" alt="offer" title="offer"  width="100%">
      <h3>Create your <strong>own package</strong></h3>
      <div class="card-box">
        <div class="left" id="my-btns">
          <form class="create-package">
            <table class="table">
              <tbody>
                <tr>
                  <td>
                  <span class="radio rd">
                  <input id="radio-1" name="radio" type="radio" value="50  - 100" checked data-value="50" data-min="50" data-max="100" data-price="15">
                       <label for="radio-1" class="radio-label">50&nbsp;&nbsp;&nbsp;- 100</label>
                       </span>
                       </td>
                  <td align="right"><small>Rs. 15/- per C.V.</small></td>
                </tr>
                <tr>
                  <td>
                  <span class="radio rd">
                  <input id="radio-2" name="radio" type="radio" value="101 - 300" data-value="101" data-min="101" data-max="300" data-price="12">
                       <label for="radio-2" class="radio-label">101 - 300</label>
                       </span>
                       </td>
                  <td align="right"><small>Rs. 12/- per C.V.</small></td>
                </tr>
                <tr>
                  <td>
                  <span class="radio rd">
                  <input id="radio-3" name="radio" type="radio" value="301 - 500" data-value="301" data-min="301" data-max="500" data-price="8">
                       <label for="radio-3" class="radio-label">301 - 500</label>
                       </span>
                       </td>
                  <td align="right"><small>Rs. 08/- per C.V.</small></td>
                </tr>
                <tr>
                  <td>
                  <span class="radio rd">
                  <input id="radio-4" name="radio" type="radio" value="500 > " data-value="501" data-min="501" data-max="999999" data-price="7">
                       <label for="radio-4" class="radio-label">500 > </label>
                       </span>
                       </td>
                  <td align="right"><small>Rs. 07/- per C.V.</small></td>
                </tr>
              </tbody>
            </table>
          </form>
        </div>
        <div class="right">
          <div class="col-md-6 bd-right">
          <div class="selected-package"><h5>No Job Post</h5><h4>Downloads</h4><div id="output">50-100</div></div>
          <div class="quantity">
            <form>
              <div class="form-group">
                <input type="number" class="form-control" id="quantity" placeholder="Quantity" min="50" max="100" data-price="15" value="50"> <small>X<span id="per_price">15</span> Per C.V.</small>
                 </div>
            </form>
          </div>
          </div>
          <div class="col-md-6">
          <div class="actual-price"><h4>Final Price</h4>Rs. <span id="final_price">750</span>/-</div>
          <div class="button-section text-center"> <a href="javascript:void(0)" onclick="@if(Auth::guard('employer')->check()) buyCustomPlan() @else openModal('login') @endif" class="btn btn-info w100" role="button">BUY NOW</a> </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-xs-12 col-md-3">
      <div class="form-box">
        <div class="form-head">Enquire Now</div>
        <div class="form-body">
          <form role="form" action="{{route('contact.submit')}}" method="post" id="contact-form" novalidate="novalidate">
            {{csrf_field()}}
            <div class="form-group">
              <input type="text" class="form-control" placeholder="Name" name="name" required  >
            </div>
            <div class="form-group">
              <input type="email" class="form-control" placeholder="Email" name="email" required  >
            </div>
            <div class="form-group">
              <input type="text" class="form-control" placeholder="Contact Number" name="phone" required >
            </div>
            <div class="form-group">
              <input type="text" class="form-control" placeholder="Message" name="message" required >
            </div>
            <div class="form-group">
                <input type="hidden" class="form-control"  value="Enquiry - Employer Portal" name="for">
                <input type="submit" class="btn btn-primary form-control" value="Submit">
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
<section class="skilled-section">
  <div class="col-md-12">
    <div class="row">
      <div class="col-md-9">
        <h3 class="cstm_heading">How we provide <strong>Skilled Students</strong></h3>
        <ul class="list-unstyled multi-steps1">
          <li><a href="#" data-toggle="tooltip" title="" data-original-title="This is where the learning begins">Students Comes</a></li>
          <li><a href="#" data-toggle="tooltip" title="" data-original-title="The best is waiting for you">Get Enrolled</a></li>
          <li><a href="#" data-toggle="tooltip" title="" data-original-title="Value added,the cost-benifit analysis">Complete Course</a></li>
          <li><a href="#" data-toggle="tooltip" title="" data-original-title="Be the one to start a queue">Get Certified</a></li>
          <li><a href="#" data-toggle="tooltip" title="" data-original-title="Come with an open mind">Get listed on Employer Portal</a></li>
        </ul>
      </div>
      <div class="col-xs-12 col-md-3">
        <div class="form-box">
          <div class="form-body pd-20">
            <h3>Our <strong>Usp’s</strong></h3>
            <table class="table listtable">
              <tbody>
                <tr>
                  <td> We Provide only Skilled Candidates </td>
                </tr>
                <tr>
                  <td> We have NSDC and AOTS certified Students </td>
                </tr>
                <tr>
                  <td> Easy to judge candidate by video resume </td>
                </tr>
                <tr>
                  <td> Low cost subscription</td>
                </tr>
                <tr>
                  <td>Only registered Candidate</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<section class="testimonials-plan">
  <div class="col-md-12">
    <div class="row">

      <div class="testimonials-coontainer">
      <h3 class="text-center">What our <strong>Customer Says</strong></h3>
        <div id="Carousel" class="carousel slide">
          <div class="carousel-inner">
            <div class="item active">
              <div class="row">
                <div class="col-md-4">
                  <div class="card-shadow">
                  <div class="user-image"> <img src="{{asset('uploads_emp/tstmnls_img/maharani-paints.jpg')}}"> </div>
                  <div class="user-review"><p> I will definitely use Professional employment's recruiting services again and have already recommended them to others. They... </p></div>
                  <div class="username"><strong>R.K. Bhatia</strong></div>
                  <div class="company-name">Maharani Innovative Private Limited</div>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="card-shadow">
                  <div class="user-image"> <img src="{{asset('uploads_emp/tstmnls_img/cdass.jpg')}}"> </div>
                  <div class="user-review"><p>The placement services are very impressive.They are offering great services. </p></div>
                  <div class="username"><strong>M.M. Singh</strong></div>
                  <div class="company-name">Cdass Group</div>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="card-shadow">
                  <div class="user-image"> <img src="{{asset('uploads_emp/tstmnls_img/vamani.png')}}"> </div>
                  <div class="user-review"><p> I will definitely use Professional employment's recruiting services again and have already recommended them to others. They...</p></div>
                  <div class="username"><strong>N. R. Bhat</strong></div>
                  <div class="company-name">Vamani Overseas</div>
                  </div>
                </div>
              </div>
              <!--.row-->
            </div>

          </div>
           <!-- <a data-slide="prev" href="#Carousel" class="left carousel-control">‹</a> <a data-slide="next" href="#Carousel" class="right carousel-control">›</a> --> </div>
      </div>
    </div>
  </div>
</section>

</div>
<section class="wish">
<h3 class="text-center">We wish to serve you with best Hiring solution</h3>
</section>
<section class="recommended-industries">
<div class="container">
<h3 class="text-center">Best Recommended <strong>Industries</strong></h3>
<p class="text-center">We have the right Candidates for these industries</p>
<div class="row top">
<div class="col-md-2 col-xs-6">
<div class="deep-shadow-box">
<div class="image-section text-center"><img src="{{asset('images/indutries-icons/apparels.png')}}" alt="Apparels" title="Apparels"></div>
<div class="cnt-section text-center">Apparels</div>
</div>
</div>
<div class="col-md-2 col-xs-6">
<div class="deep-shadow-box">
<div class="image-section text-center"><img src="{{asset('images/indutries-icons/accounting.png')}}" alt="Apparels" title="Apparels"></div>
<div class="cnt-section text-center">Accounting</div>
</div>
</div>
<div class="col-md-2 col-xs-6">
<div class="deep-shadow-box">
<div class="image-section text-center"><img src="{{asset('images/indutries-icons/airlines.png')}}" alt="Apparels" title="Apparels"></div>
<div class="cnt-section text-center">Airlines</div>
</div>
</div>
<div class="col-md-2 col-xs-6">
<div class="deep-shadow-box">
<div class="image-section text-center"><img src="{{asset('images/indutries-icons/agriculture.png')}}" alt="Apparels" title="Apparels"></div>
<div class="cnt-section text-center">Agriculture</div>
</div>
</div>
<div class="col-md-2 col-xs-6">
<div class="deep-shadow-box">
<div class="image-section text-center"><img src="{{asset('images/indutries-icons/gaming.png')}}" alt="Apparels" title="Apparels"></div>
<div class="cnt-section text-center">Gaming</div>
</div>
</div>
<div class="col-md-2 col-xs-6">
<div class="deep-shadow-box">
<div class="image-section text-center"><img src="{{asset('images/indutries-icons/courier.png')}}" alt="Apparels" title="Apparels"></div>
<div class="cnt-section text-center">Courier</div>
</div>
</div>
</div>
<div class="row top">
<div class="col-md-2 col-xs-6">
<div class="deep-shadow-box">
<div class="image-section text-center"><img src="{{asset('images/indutries-icons/advertising.png')}}" alt="Apparels" title="Apparels"></div>
<div class="cnt-section text-center">Advertising</div>
</div>
</div>
<div class="col-md-2 col-xs-6">
<div class="deep-shadow-box">
<div class="image-section text-center"><img src="{{asset('images/indutries-icons/automobiles.png')}}" alt="Apparels" title="Apparels"></div>
<div class="cnt-section text-center">Automobiles</div>
</div>
</div>
<div class="col-md-2 col-xs-6">
<div class="deep-shadow-box">
<div class="image-section text-center"><img src="{{asset('images/indutries-icons/chemicals.png')}}" alt="Apparels" title="Apparels"></div>
<div class="cnt-section text-center">Chemicals</div>
</div>
</div>
<div class="col-md-2 col-xs-6">
<div class="deep-shadow-box">
<div class="image-section text-center"><img src="{{asset('images/indutries-icons/petrolium.png')}}" alt="Apparels" title="Apparels"></div>
<div class="cnt-section text-center">Petrolium</div>
</div>
</div>
<div class="col-md-2 col-xs-6">
<div class="deep-shadow-box">
<div class="image-section text-center"><img src="{{asset('images/indutries-icons/printing.png')}}" alt="Apparels" title="Apparels"></div>
<div class="cnt-section text-center">Printing</div>
</div>
</div>
<div class="col-md-2 col-xs-6">
<div class="deep-shadow-box">
<div class="image-section text-center"><img src="{{asset('images/indutries-icons/real-estate.png')}}" alt="Apparels" title="Apparels"></div>
<div class="cnt-section text-center">Real Estate</div>
</div>
</div>
</div>

</div>
</section>
<section class="contact-sec">
<div class="container">
<div class="row">
<div class="col-md-9"><h3>If you are looking for any information, please feel free to contact us. We will be glad to help.</h3>
<div class="contac_detail">
<a href="tel:+91 8929 501 502"><i class="fa fa-phone"></i> +91 8929 501 502     </a>
<a href="mailto:info@smartskilltech.com"><i class="fa fa-envelope"></i> info@smartskilltech.com</a></div>
</div>
<div class="col-md-3">
<div class="social_icons">
<h2 class="text-right">Follow us on</h2>
<div class="sc_icons pull-right"> <a href="https://www.facebook.com/smartskilltech" target="_blank"><i class="fa fa-facebook"></i></a> <a href="https://www.linkedin.com/company/smart-skill-tech-pvt.-ltd." target="_blank"><i class="fa fa-linkedin"></i></a> <a href="https://twitter.com/SmartSkillTech" target="_blank"><i class="fa fa-twitter"></i></a></div>
</div>
</div>
</div>
</div>
</section>
<section class="faq-sec">
<div class="container">
  <div class="panel-group faq-tabs" id="accordion">
  <div class="panel panel-default">
      <div class="panel-heading">
          <h4 class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
           What is smartskilltech.com?
           <i class="more-less glyphicon glyphicon-minus icon-mod"></i>
          </h4>
      </div>
      <div id="collapseOne" class="panel-collapse collapse in space">
        <div class="panel-body">
          <p>It is India's leading online education company that provides high quality Online Skill Development program. We intend to align ourselves with the Skill India Mission to empower youth by online facilitating Skill Development Programs certified through recognised organizations and there by providing placement assistance education company that provides high quality, industry relevant certifications and preparation for students, young graduates and working professionals. </p>
        </div>
      </div>
    </div>

     <div class="panel panel-default">
      <div class="panel-heading">
          <h4 class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse1">
           What is Unique about smartskilltech.com courses ?             <i class="more-less glyphicon glyphicon-plus icon-mod"></i>
          </h4>
      </div>
      <div id="collapse1" class="panel-collapse collapse space">
        <div class="panel-body">
          <p>These courses are designed and reviewed specially by  recognised body & Universities and industry experts providing participants with real life situations. Most of our courses include PDF and AV Lecturesclasseswith Time flexibility and Cost effectiveness with offline / online study material with Placement Assistance.Curriculums aligned with National Occupation Standards and Qualification Pack as per the Job Role and having Final exam at a secured testing facility.</p>
        </div>
      </div>
    </div>

     <div class="panel panel-default">
      <div class="panel-heading">
          <h4 class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse2">
         How we will get placement assistance?             <i class="more-less glyphicon glyphicon-plus icon-mod"></i>
          </h4>
      </div>
      <div id="collapse2" class="panel-collapse collapse space">
        <div class="panel-body">
          <p>Once you are done with course of skill development than you will be placed to the companies. Moreover our process will be define very soon and our team is working very dedicately. </p>
        </div>
      </div>
    </div>

     <div class="panel panel-default">
      <div class="panel-heading">
          <h4 class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse3">
          What Type are the courses being offered ?              <i class="more-less glyphicon glyphicon-plus icon-mod"></i>
          </h4>
      </div>
      <div id="collapse3" class="panel-collapse collapse space">
        <div class="panel-body">
          <p>Smart Skill Tech provide skill development course and certificate courses in different Sectors like  Information technology, BFSI, Hospitality and Tourism, Apparels.Smart Skill tech also offering soft skilled courses related to office productivity, IT, Business etc.Smart Skill Tech also offering some Autonoyms or Job oriented course which are very useful to enhance / upgrade candidate employability skills.</p>
        </div>
      </div>
    </div>

     <div class="panel panel-default">
      <div class="panel-heading">
          <h4 class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse4">
         How Smart Skill Tech going to help you ?             <i class="more-less glyphicon glyphicon-plus icon-mod"></i>
          </h4>
      </div>
      <div id="collapse4" class="panel-collapse collapse space">
        <div class="panel-body">
          <p>Smart Skill tech is a Skill India Mission to empower youth by online facilitating Skill Development Programs by providing PLACEMENT ASSISTANCE. As in India's growing Skill development platform, where people develop their skills , learn, improve and explore their talents to the world. Moreover we will braodly define our process very soon as we are still developing our process dedicately for the seekers</p>
        </div>
      </div>
    </div>

     <div class="panel panel-default">
      <div class="panel-heading">
          <h4 class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse5">
          Do I get classroom training or any other assistance if I go for an online course?             <i class="more-less glyphicon glyphicon-plus icon-mod"></i>
          </h4>
      </div>
      <div id="collapse5" class="panel-collapse collapse space">
        <div class="panel-body">
          <p>Classroom training is not fully available for the participants of online courses as the idea of creating a platform for online education is to remove the need of going to a classroom for studying. However, there are offline skilled training centers for different skill development course module with theory and practicals .</p>
        </div>
      </div>
    </div>

     <div class="panel panel-default">
      <div class="panel-heading">
          <h4 class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse6">
            How are the assessments done?             <i class="more-less glyphicon glyphicon-plus icon-mod"></i>
          </h4>
      </div>
      <div id="collapse6" class="panel-collapse collapse space">
        <div class="panel-body">
          <p>Continuous assessments are included in the course. There are online and offline testing for each and every module so that student may know their study progress, for Soft skill development exams there is online test for course module</p>
        </div>
      </div>
    </div>

     <div class="panel panel-default">
      <div class="panel-heading">
          <h4 class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse7">
          What is the maximum number of days for which my login is valid?             <i class="more-less glyphicon glyphicon-plus icon-mod"></i>
          </h4>
      </div>
      <div id="collapse7" class="panel-collapse collapse space">
        <div class="panel-body">
          <p>Participant's login ID is valid forever. However, may every course comes with certain validity</p>
        </div>
      </div>
    </div>

     <div class="panel panel-default">
      <div class="panel-heading">
          <h4 class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse8">
         How to consume the material on the website?             <i class="more-less glyphicon glyphicon-plus icon-mod"></i>
          </h4>
      </div>
      <div id="collapse8" class="panel-collapse collapse space">
        <div class="panel-body">
          <p>Visit to www. smartskilltech.com</p>
        </div>
      </div>
    </div>


    </div>
  </div>
</section>
</section>
@endsection

@push('scripts')
<script>
var qty = $('#quantity');
var per_item_price = 15;
var item_min = 50;
var item_max = 100;
var item_value = 50;
$('#my-btns .rd input').on('click', function(event) {
  var item = $('#my-btns .rd input:checked');

  item_value =item.data('value');
  item_min = item.data('min');
  item_max = item.data('max');
  per_item_price = item.data('price');

  $('#output').html(item.val());

  qty.val(item_value);
  qty.attr('min', item_min);
  qty.attr('max', item_max);

  calculateFinalPrice();
});

qty.on('keyup', function(){
  calculateFinalPrice();
});
qty.on('change', function(){
   if(item_min>qty.val()) {
    qty.val(item_min);
  }
  if(item_max < qty.val()) {
    qty.val(item_max);
  }
  calculateFinalPrice();
});
qty.on('blur', function(){
   if(item_min>qty.val()) {
    qty.val(item_min);
  }
  if(item_max < qty.val()) {
    qty.val(item_max);
  }
  calculateFinalPrice();
});

var calculateFinalPrice = function(){
  $('#final_price').text(qty.val()*per_item_price);
}

function toggleIcon(e) {
    $(e.target)
        .prev('.panel-heading')
        .find(".more-less")
        .toggleClass('glyphicon-plus glyphicon-minus');
}
$('.panel-group').on('hidden.bs.collapse', toggleIcon);
$('.panel-group').on('shown.bs.collapse', toggleIcon);

$("#demo-form").validate({
    submitHandler : function(form){
        $('#demo-btn').addClass('disable').attr('disabled', 'disabled').text('Please wait...');
        var form = $(form);
            axios.post(form.attr('action'), form.serialize())
            .then(function (response) {
               toastr.success('Thankyou for contacting us');
               $('#contact-btn').removeClass('disable').removeAttr('disabled').text('Submit');
               setTimeout(function(){
                    window.location.reload();
               }, 5000);
            })
            .catch(function (error) {
                var errors = error.response.data;
                for(i in errors){
                    toastr.error(errors[i]);
                }
                $('#demo-btn').removeClass('disable').removeAttr('disabled').text('Submit');
            });
             return false;
        }
});

$("#contact-form").validate({
    submitHandler : function(form){
        $('#contact-btn').addClass('disable').attr('disabled', 'disabled').text('Please wait...');
        var form = $(form);
            axios.post(form.attr('action'), form.serialize())
            .then(function (response) {
               toastr.success('Thankyou for contacting us');
               $('#contact-submit').removeClass('disable').removeAttr('disabled').text('Submit');
               setTimeout(function(){
                    window.location.reload();
               }, 5000);
            })
            .catch(function (error) {
                var errors = error.response.data;
                for(i in errors){
                    toastr.error(errors[i]);
                }
                $('#contact-submit').removeClass('disable').removeAttr('disabled').text('Submit');
            });
             return false;
        }
});

var buyCustomPlan = function() {

   axios.post('{{route('cart.item.custom')}}',
   {
     _token: window.Laravel.csrfToken,
     qty: qty.val(),
     price: per_item_price
   })
      .then(function (response) {
          toastr.success('Cart item added into the cart successfully');
          setTimeout(function(){
              window.location.href = '{{route('cart.view')}}';
          }, 3000);
      })
      .catch(function (error) {
          toastr.error("Error in adding into cart, please try again after some time.");
          setTimeout(function(){
              window.location.reload();
          }, 4000);
      });
}
</script>
@endpush