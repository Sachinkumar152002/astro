
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
        @endphp
        <tr>
        <td>{{$plsub}}</td>
        <td>{{$antardasha_order[$n][$i]}}</td>
        </tr>
     @endforeach
    </tbody>
    </table>
    </div>
 @endforeach
</div>
