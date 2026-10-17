<div class="panel panel-default checkout-panel" id="panel-billing">
    <div class="panel-heading bg-blue checkout-heading" data-toggle="collapse" data-parent="#checkout-accordion" href="#collapse-billing">
        <h4 class="panel-title checkout-acc-title"> <a class="accordion-checkout" data-toggle="collapse" data-parent="#checkout-accordion" href="#collapse-billing">My Details</a> </h4>
    </div>
    <div id="collapse-billing" class="panel-collapse details collapse in">
        <div class="panel-body checkout-inside">
            <div class="col-md-12">
               <table class="table">
                   <tr>
                       <td>Name</td><td>:</td><td>{{auth::guard('employer')->user()->name}}</td>
                   </tr>
                   <tr>
                       <td>Email</td><td>:</td><td>{{auth::guard('employer')->user()->email}}</td>
                   </tr>
                   <tr>
                       <td>Mobile</td><td>:</td><td>{{auth::guard('employer')->user()->mobile}}</td>
                   </tr>
               </table>
            </div>
        </div>
    </div>
</div>