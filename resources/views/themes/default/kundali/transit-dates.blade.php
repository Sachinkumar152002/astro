<table class="table table-bordered table-sm">
   <thead>
       <th>Planets</th>
       <th>Rashi</th>
       <th>Start Date</th>
       <th>End Date</th>
       <th>R</th>
       <th>Strength</th>
   </thead>
          <tbody>
            @foreach($planets as $planet=>$gochars)
            @foreach($gochars as $gochar)
           <tr>
              <td>{{$planet}}</td>
              <td>{{$gochar['zodiac']}}</td>
              <td>{{$gochar['start_date']}}</td>
              <td>{{$gochar['end_date']}}</td>
              <td>{{$gochar['retro']?'R':''}}</td>
              <td>{{$gochar['strength']}}</td>
            </tr>
            @endforeach
            @endforeach

        </tbody>
      </table>
