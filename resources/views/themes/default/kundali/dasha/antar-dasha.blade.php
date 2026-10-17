
<!-- Vimshottari Maha Dasha Table -->
<h5 class="mb-4 card-header-main">Vimshottari Maha Dasha</h5>

  <div class="row">
  @foreach($antardashas as $n=>$_antardashas)
    @php
    list($planetName, $plsub) = explode('/', $_antardashas[0]);
    @endphp
    <div class="col-md-4">
    <table class="table table-bordered table-sm">
    <thead class="bg-light">
    <tr><th colspan="2">{{$planetName}}</td></tr>
    <tr>
    <th>Planet</th>
    <th>End Date</th>
    </tr>
    </thead>
    <tbody>
    @foreach($_antardashas as $i=>$antar)
        @php
          list($pm, $plsub) = explode('/', $antar);
          $dPlanet = trim($antardasha_order[$n][$i]);
          $endYr = substr($dPlanet, -4);
        @endphp
        @if($endYr>=$birth_year)
        <tr>
        <td>{{$plsub}}</td>
        <td>{{$dPlanet}}</td>
        </tr>
        @endif
     @endforeach
    </tbody>
    </table>
    </div>
 @endforeach
</div>
