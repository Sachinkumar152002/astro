@forelse($results as $index=>$row)
<tr>
  <td> <input type="checkbox" name="" value=""> </td>
  <td scope="row">{{$index+1+(($results->currentPage()-1)*$results->perPage())}}</td>
  <td>
    @if(adminCan('form-field', 'update'))
    <a href="javascript:void(0)" class="text-primary" onclick="editForm('{{$row->id}}', '{{$row->form_label}}', '{{$row->form_type}}', '{{$row->isrequired}}', '{{$row->filetype}}' )">
      {{$row->form_label}}
    </a>
    @else
    {{$row->form_label}}
    @endif
  </td>
  <td>{{$row->form_type}}</td>
  <td>{{$row->isrequired ? 'Yes' : 'No'}}</td>
    <td class="text-right">
      @if(adminCan('form-field', 'update'))
      <a href="javascript:void(0)" class="text-success" onclick="editForm('{{$row->id}}', '{{$row->form_label}}', '{{$row->form_type}}', '{{$row->isrequired}}', '{{$row->filetype}}' )">
        <i class="fa fa-edit"></i>
      </a>
      @endif
      @if(adminCan('form-field', 'delete'))
      |
      <a href="javascript:void(0)" class="text-danger" onclick="deleteItem({{$row->id}})">
        <i class="fa fa-trash"></i>
      </a>
      @endif
  </td>
</tr>
@empty


@endforelse
