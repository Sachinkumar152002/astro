<h5 class="mt-4 card-header-main">Planet Positions</h5>
<div class="table-responsive">
<table class="table table-bordered table-sm">
<thead class="table-light">
<tr>
<th>Planet</th>
<th>Zodiac</th>
<th>Zodiac Lord</th>
<th>Nakshatra</th>
<th>Nakshatra Lord</th>
<th>Degree</th>
<th>Retro(R)</th>
<th>Combust</th>
<th>Avastha</th>
<th>House</th>
<th>Status</th>
</tr>
</thead>
<tbody>
@foreach($planets as $planet)
<tr>
  <td>{{$planet['full_name']}}</td>
  <td>{{$planet['zodiac']}}</td>
  <td>{{$planet['zodiac_lord']}}</td>
  <td>{{$planet['nakshatra']}}</td>
  <td>{{$planet['nakshatra_lord']}}</td>
  <td>{{$planet['local_degree']}}</td>
  <td>{{isset($planet['retro']) && $planet['retro']?'Yes':'No'}}</td>
  <td>{{isset($planet['is_combust']) && $planet['is_combust']?'Yes':'No'}}</td>
  <td>{{$planet['basic_avastha']}}</td>
  <td>{{$planet['house']}}</td>
  <td>{{$planet['lord_status']}}</td>
</tr>
@endforeach
</tbody>
</table>
</div>
