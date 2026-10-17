@if($results->count())
  @foreach($results as $index => $row)
    @php
      $item = null;
      if($row->type == "shop" && $row->product){
        $item = $row->product;
      }elseif($row->type== "course" && $row->course){
        $item = $row->course;
      }
      $image = $item ? $item->image : null;
      $user = $row->user;
    @endphp

    @if($item)
      <tr>
        <td>{{$index+1+(($results->currentPage()-1)*$results->perPage())}}</td>
        <td>
          @if($user)
            <a href="{{route('admin.customer.edit', [$user->id])}}" class="text-primary" target="_blank">
              {{ $user->name }}
            </a>
          @else
            {{ "Guest" }}
          @endif
        </td>
        <td><img src="{{ $image ? $image->image(50) : no_image() }}" alt="" width="50" height="50" style="object-fit:cover;"></td>
        <td>
          @if($row->type == "shop")
            @php
              $productTypeName = "Product";
              if($item->type == 2){
                $productTypeName = "Pooja";
              }elseif($item->type == 3){
                $productTypeName = "Service";
              }
            @endphp
            <a href="{{route('admin.product.edit', [$item->id, $productTypeName])}}" class="text-primary" target="_blank">
              {{$item->title}}
            </a>
          @elseif($row->type == "course")
            <a href="{{ route('admin.customer.course.edit', [$row->user_id, $item->id]) }}" class="text-primary" target="_blank">
              {{ $item->name }}
            </a>
          @endif
        </td>
        <td>{{ $row->quantity }}</td>
        <td>{{ ISTDatetimeFormat($row->created_at) }}</td>
        <td class="text-right">
            <a href="javascript:void(0)" class="text-danger" title="Delete" onclick="deleteRecord({{$row->id}})">
              <i class="fa fa-trash"></i>
            </a>
        </td>
      </tr>
    @else
      @php
        $row->delete();
      @endphp
    @endif
  @endforeach
@else
<tr>
  <td class="text-center" colspan="7"><p>No Records</p></td>
</tr>
@endif
