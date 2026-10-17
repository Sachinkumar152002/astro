<div class="card-header-main">Yogini Dasha </div>
<div class="table-responsive">
    <table class="table table-bordered table-sm">
        <thead class="bg-light">
            <tr>
                <th>Planet</th>
                <th>Lord</th>
                <th>Start Date</th>
                <th>End Date</th>
            </tr>
        </thead>
        <tbody>
          @foreach($dasha_list as $i=>$dl)
            <tr>
                <td>{{$dl}}</td>
                <td>{{$dasha_lord_list[$i]}}</td>
                @if($i==0)
                  <td>Birth</td>
                @else
                  <td>{{str_replace('00:00:00 GMT+0000 (Coordinated Universal Time)', '',$dasha_end_dates[$i-1])}}</td>
                @endif
                <td>{{str_replace('00:00:00 GMT+0000 (Coordinated Universal Time)', '',$dasha_end_dates[$i])}}</td>
            </tr>
          @endforeach
        </tbody>
    </table>
</div>
</div>
