<div class="card-header-main"> Ashtakvarga -Sarvashtakvarga</div>
   <div class="row text-center gy-4 description ">
<div class="col-12">
<div>
<table class="table table-bordered table-sm">
    <thead>
            <th>Planets</th>
            <th>Aries</th>
            <th>Taurus</th>
            <th>Gemini</th>
            <th>Cancer</th>
            <th>Leo</th>
            <th>Virgo</th>
            <th>Libra</th>
            <th>Scorpio</th>
            <th>Sagittarius</th>
            <th>Capricorn</th>
            <th>Aquarius</th>
            <th>Pisces  </th>
    </thead>
           <tbody>
             @foreach($ashtakvarga_order as $i=>$planet)
             @if($i<7)
            <tr>
                <td>{{$planet}} </td>
                @foreach($ashtakvarga_points[$i] as $pt)
                <td>{{$pt}}</td>
                @endforeach
             </tr>
             @endif
             @endforeach

             <tfoot>
                <th>Total  </th>
                @foreach($ashtakvarga_total as $pt)
                <td>{{$pt}}</td>
                @endforeach
             </tfoot>
         </tbody>
       </table>
</div>
</div>
</div>
