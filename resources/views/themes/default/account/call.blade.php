@extends(themeView().'layout')

@section('title', 'Call')
@section('keywords', '')
@section('description','')

@section('content')
<div class="container" id="call-app">
<div class="card mt-5 mb-5">
    <div class="cord-body bg-light pt-5 pb-5">
    <div class="text-center mb-3">
        <img src="{{asset('images/call.svg')}}" width="100" class="border rounded-circle shadow-sm bg-primary p-3">
    </div>
    <p class="lead text-center">Thank you Your call will be connected shortly</p>
    <p class="lead text-center">Please keep your phone line free. You will receive a call from +91-11-xxxxxxxx</p>
    <p class="lead text-center">Please ensure you have full network coverage.</p>
    <p class="text-center">In case, you don’t receive a call, or the astrologer does not take the call, please select <a href="{{route('astrologers.index')}}">another astrologer</a>.</p>
    </div>
</div>
</div>
@endsection

@push('scripts')
<script>
    var callApp = new Vue({
        el : '#call-app',
        data: {
            message : 'Calling {{$callHistory->astrologer->display_name}}...',
            is_running_call : false,
        },
        mounted:function(){
            toastr.success(this.message);
        },
        methods: {
            makeAstrologerCall(){
                let that = this;
                axios.post("{{route('user.call', [encrypt($callHistory->id)])}}")
                .then(function(response){
                    if(response.data.status==1) toastr.success(response.data.message);
                    else{
                        toastr.error(response.data.message);
                        setTimeout(()=>{window.location.href="{{route('astrologers.index')}}";}, 3000);
                    }
                })
                .catch(function(errors){
                    toastr.error("Failed to connect with astrologer. Will try with another astrologer");
                    setTimeout(()=>{window.location.href="{{route('astrologers.index')}}";}, 3000);
                });
            }
        },
        created() {
           this.makeAstrologerCall();
        }
    })
</script>
@endpush
