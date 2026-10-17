@php
$adPopUpContents = ads($page, 'popup');
@endphp
@if($adPopUpContents && $adPopUpContents->count()>0)
@php
  $popUpAd = $adPopUpContents->first();
@endphp
<div class="modal fade" id="popupadmodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="  $('#popupadmodal').modal('hide')">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body text-center">
        @if($popUpAd->image)
          @if($popUpAd->button_text)
            <img src="{{$popUpAd->image->image(300)}}" alt="popUpAd-image" class="w-100 mt-3">
            <p><a class="btn btn-primary"  href="{{$popUpAd->link}}" target="_blank">{{$popUpAd->button_text}}</a></p>
          @else
            <a href="{{$popUpAd->link}}" target="_blank">
              <img src="{{$popUpAd->image->image(300)}}" alt="popUpAd-image" class="w-100 mt-3">
            </a>
          @endif
        @else
          {!! $popUpAd->content !!}
        @endif
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script>
$(function(){
  setTimeout(function(){
        $('#popupadmodal').modal('show');
  }, {{$popUpAd->duration*1000}})
});
</script>
@endpush
@endif
