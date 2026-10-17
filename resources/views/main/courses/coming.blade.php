<div class="row upcoming-highlight">
    <div class="col-md-12">
        <h3 class="title-3 text-uppercase">Upcoming <span>Exciting Courses</span></h3>
    </div>
    <div class="col-md-4">
        <div class="full-section">
            <div class="image-section">
                <img class="img-responsive" src="{{asset('images/java-developer-program.jpg')}}" alt="Java Developer Program">
                <div class="certified-section">
                    <div class="certified-text"><i class="fa fa-calendar"></i> Live on : Oct 15, 2018</div>
                    <div class="border-shape2"></div>
                </div>
            </div>
            <div class="bg-white course-list-content">
                <h3>Java Developer Program</h3>
                <div class="btn-sections cart-login">
                    <div class="form-group newsletterbox notify">
    <form role="form" action="{{route('contact.submit')}}" method="post" class="coming-form" novalidate="novalidate">
    {{csrf_field()}}
                            {!! Form::hidden('for', 'Java Developer Program') !!}
                            {!! Form::hidden('message', 'Java Developer Program') !!}
                            {!! Form::hidden('name', '-') !!}
                            {!! Form::hidden('phone', '-') !!}
                <input class="form-control" placeholder="enter your email address" name="email" required="" type="email">
                <button class="btn btn-success" type="submit" >Notify Me</button>
                </form>
                </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="full-section">
            <div class="image-section">
                <img class="img-responsive" src="{{asset('images/process-cordinator.jpg')}}" alt="Process Coordinator Program">
                <div class="certified-section">
                    <div class="certified-text"><i class="fa fa-calendar"></i> Live on : Sep 30, 2018</div>
                    <div class="border-shape2"></div>
                </div>
            </div>
            <div class="bg-white course-list-content">
                <h3>Process Coordinator Program</h3>
                <div class="btn-sections cart-login">
                <div class="form-group newsletterbox notify">
    <form role="form" action="{{route('contact.submit')}}" method="post" class="coming-form" novalidate="novalidate">
                    {{csrf_field()}}
                    {!! Form::hidden('for', 'Process Coordinator Program') !!}
                    {!! Form::hidden('message', 'Process Coordinator Program') !!}
                     {!! Form::hidden('name', '-') !!}
                            {!! Form::hidden('phone', '-') !!}
                <input class="form-control" placeholder="enter your email address" name="email" required="" type="email">
                <button class="btn btn-success" type="submit" >Notify Me</button>
                </form>
                </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="full-section">
            <div class="image-section">
                <img class="img-responsive" src="{{asset('images/video-resume-training.jpg')}}" alt="Video Resume Training">
                <div class="certified-section">
                    <div class="certified-text"><i class="fa fa-calendar"></i> Coming Soon</div>
                    <div class="border-shape2"></div>
                </div>
            </div>
            <div class="bg-white course-list-content">
                <h3>Video Resume Training</h3>


                <div class="btn-sections cart-login">
                     <div class="form-group newsletterbox notify">
    <form role="form" action="{{route('contact.submit')}}" method="post" class="coming-form" novalidate="novalidate">
    {{csrf_field()}}
                            {!! Form::hidden('for', 'Video Resume Training') !!}
                            {!! Form::hidden('message', 'Video Resume Training') !!}
                            {!! Form::hidden('name', '-') !!}
                            {!! Form::hidden('phone', '-') !!}
                <input class="form-control" placeholder="enter your email address" name="email" required="" type="email">
                <button class="btn btn-success" type="submit" >Notify Me</button>
                </form>
                </div>
                </div>
            </div>
        </div>
    </div>
</div>
@push('scripts')

<script type="text/javascript">
$(".coming-form").validate({
    submitHandler : function(form){
        var f = $(form);
        f.find('button').addClass('disable').attr('disabled', 'disabled');
            axios.post(f.attr('action'), f.serialize())
            .then(function (response) {
               toastr.success('Thankyou for contacting us');
               f.find('button').removeClass('disable').removeAttr('disabled');
       sendDataToGoogleForm( '',f.find('[name="email"]').val(),'',f.find('[name="for"]').val(),f.find('[name="for"]').val());
               setTimeout(function(){
                    window.location.reload();
               }, 5000);
            })
            .catch(function (error) {
                var errors = error.response.data;
                for(i in errors){
                    toastr.error(errors[i]);
                }
                f.find('button').removeClass('disable').removeAttr('disabled');
            });
             return false;
        }
});

var   url = 'https://script.google.com/macros/s/AKfycbxNZQrFHgFz2hlpH-pcnHhgyuH5zFDLedSsKRpNg42UZlFmkQwm/exec';
var sendDataToGoogleForm = function(name, email, phone, _for, message){

var dataSend = {'Name': name, 'E-mail ID': email, 'Contact No.': phone, 'Certificate & Course':message, 'Qualification':'','Experience':'','Remark':'Source - Website Contact Page'+_for, 'Form Edit URL':'Web Admin','Template 1 - Send Status':'', 'Location':'Website Contact page', 'Timestamp':'<?php echo date('d/m/Y H:i:s');?>', 'page':'LP','Education':''};
var jqxhr = $.ajax({
url: url,
method: "GET",
dataType: "jsonP",
crossDomain: true,
data: dataSend
}).done(function(res){
// console.log(res);
});
}
</script>
@endpush