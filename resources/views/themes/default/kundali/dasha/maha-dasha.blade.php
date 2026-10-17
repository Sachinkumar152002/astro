<h5 class="mt-4 card-header-main">Vimshottari Dasha</h5>
<div>
<div class="table-responsive">
<div><h6>Balance Of Dasha :</h6>
<p> {{$dasha_remaining_at_birth}}</p></div>
<table class="table table-bordered table-sm">
 <tbody>
   @foreach($mahadasha as $i=>$md)
    <tr>
       <td>{{$md}}</td>
       <td>{{$mahadasha_order[$i]}}</td>
     </tr>
   @endforeach
 </tbody>
</table>
<h6><a href="javascript:void(0)" onclick="handleTab(document.getElementById('dasha-tab'),'dasha')">See full Vimshottari Dasha</a></h6>
</div>
</div>
