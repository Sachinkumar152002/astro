<tr>
  <td>#</td>
  <td><a href="javascript:void(0)" onclick="javascript:fetchSupportReplies('{{$support->id}}'); document.getElementById('summary-tab').click();">{{$support->subject}}</a></td>
  <td>{{getSupportTypeName($support->type)}}</td>
  <td>{{date('d-m-Y', strtotime($support->created_at))}}</td>
  <td>{{date('h:i A', strtotime($support->created_at))}}</td>
  <td>{{$support->status}}</td>
</tr>
