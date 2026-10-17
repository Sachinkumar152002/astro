@if(Auth::check())
<div class="panel panel-default checkout-panel" id="panel-billing">
    <div class="panel-heading bg-blue checkout-heading" data-toggle="collapse" data-parent="#checkout-accordion" href="#collapse-billing">
        <h4 class="panel-title checkout-acc-title"> <a class="accordion-checkout" data-toggle="collapse" data-parent="#checkout-accordion" href="#collapse-billing">My Details</a> </h4>
    </div>
    <div id="collapse-billing" class="panel-collapse details collapse in">
        <div class="panel-body checkout-inside">
            <div class="col-md-12">
               <table class="table">
                   <tr>
                       <td>Name</td><td>:</td><td>{{auth::user()->name}}</td>
                   </tr>
                   <tr>
                       <td>Email</td><td>:</td><td>{{auth::user()->email}}</td>
                   </tr>
                   <tr>
                       <td>Phone</td><td>:</td><td>{{auth::user()->details->phone}}</td>
                   </tr>
                    <tr>
                       <td>Address</td><td>:</td><td>{{auth::user()->details->address}}, {{auth::user()->details->city}}, {{auth::user()->details->state_text}}, {{auth::user()->details->pincode}} </td>
                   </tr>
                   <tr>
                       <td colspan="3">
                            <a href="{{route('account.profile')}}" class="pull-right">Change Details</a>
                       </td>
                   </tr>
               </table>
            </div>
        </div>
    </div>
</div>
@else
<div class="panel panel-default checkout-panel" id="panel-billing">
    <div class="panel-heading bg-blue checkout-heading" data-toggle="collapse" data-parent="#checkout-accordion" href="#collapse-billing">
        <h4 class="panel-title checkout-acc-title"> <a class="accordion-checkout" data-toggle="collapse" data-parent="#checkout-accordion" href="#collapse-billing">Login/Signup</a> </h4>
    </div>
    <div id="collapse-billing" class="panel-collapse details collapse in">
        <div class="panel-body checkout-inside">
            <div class="col-md-12">
                <div class="col-md-6 border-right">
                    <h3 class="title-3 title-4 text-uppercase">
    <a href="javascript:void(0)" onclick="openModal('login')">Login</a>
                    </h3>
                </div>
                <div class="col-md-6">
                    <h3 class="title-3 title-4 text-uppercase">
                    <a href="javascript:void(0)" onclick="openModal('registration')">Signup</a></h3>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
