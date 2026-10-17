<div class="col-12 mt-4">
  <div class="row">
    <div class="col-md-6">
  <div class="m-1 p-1 bg-repely text-black rounded d-flex">
    <div class="ch-imag-box">
      <img src="{{$image}}" class="img-fluid astro-img rounded-circle ch-imag" alt="User-A">
    </div>
   <div class="mx-2">
     <p class="mb-1"> <strong class="text-black">{{$ticket->name}}</strong></p>
     {!!nl2br($ticket->message)!!}
   </div>
  </div>
  <div class="">
     <small class="text-black">

        {{ date('d M Y, h:i A', strtotime($ticket->created_at)) }}
    </small>
  </div>
</div>
</div>
</div>
@foreach($ticket->replies as $key => $value)
  @if($value->reply_type=='user'||$value->reply_type=='astrologer')
    <div class="col-12 mt-4">
      <div class="row">
    <div class="col-md-6">
      <div class="m-1 p-1 bg-repely text-black rounded d-flex">
        <div class="">

          <div class="ch-imag-box i'mher">
          <img src="{{$image}}" class="img-fluid astro-img rounded-circle ch-imag" alt="User-A">
        </div>
      </div>
       <div class="mx-2">
          <p class="mb-1"> <strong class="text-black">{{$value->name}}</strong></p>
         {!!nl2br($value->reply)!!}

       </div>
       </div>
       <div class="">
          <small class="text-black">
             <em>{{ date('d M Y, h:i A', strtotime($value->created_at)) }}</em>
         </small>
       </div>
     </div>
   </div>
    </div>
    <div class="col-6"></div>
  @else
    <div class="col-12 mt-4">
      <div class="row">
        <div class="col-4">  </div>
        <div class="col-8">
          <div class="m-1 p-1 bg-send text-white rounded d-flex align-items-start position-relative">
            <div class="">
              <div class="ch-imag-box i'mhere">
                <img src="{{ asset('assets/images/profile.png') }}" class="img-fluid astro-img rounded-circle ch-imag" alt="User-A">
              </div>
            </div>
            <div class="mx-2 flex-grow-1 w-100">
              <div class="">
                <strong class="text-dark">{{$value->name}}</strong>
                @if(adminCan('support','delete'))
                <i class="bi bi-trash btn btn-danger btn-sm float-right" onclick="deleteReply(this, '{{$value->id}}')"></i>
                <i class="bi bi-pencil btn btn-success btn-sm mx-1 bg-white text-dark float-right" onclick="editReply('{{$value->id}}', '{{$value->reply}}')"></i>
                @endif
              </div>
              <p class="mb-0">{!! nl2br($value->reply) !!}</p>
            </div>
        </div>
        <div class="">
           <small class="text-black">
              <em>{{ date('d M Y, h:i A', strtotime($value->created_at)) }}</em>
          </small>
        </div>
      </div>



    </div>
</div>

  @endif
@endforeach
