<option value="0">Root</option>
@foreach ($dropDownValues as $foId=>$item)
    <option value="{{$foId}}">{{$item}}</option>
@endforeach
