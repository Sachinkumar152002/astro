<div class="modal fade" id="insufficient-balance" data-backdrop="static" data-keyboard="true" tabindex="-1"
aria-labelledby="staticBackdropLabel" aria-hidden="true">
<div class="modal-dialog modal-lg" >

    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close float-right" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
        <div class="modal-body text-center p-5">

            <h3 class="h3">You have Insufficent balance</h3>
            <h4 class="h4">You can't connect the call to an Astrologer.</h4>
            <a href="{{route('astrologers.index')}}" class="btn btn-dark text-white">Explore more</a>
            <a href="{{route('user.recharge')}}" class="btn btn-warning">Recharge now</a>
        </div>
    </div>
</div>
</div>

@push('scripts')
<script>
    var insufficientbalance = function(){
            $('#insufficient-balance').modal('show');
    }
</script>
@endpush