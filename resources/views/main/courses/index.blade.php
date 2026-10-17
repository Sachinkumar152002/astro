@extends(themeView().'layout')
@section('title', 'IIAG - Courses')

  @push('schema')
  <script type="application/ld+json">
    @foreach($onlineCourses as $key => $c)
      {
        "@context": "https://schema.org",
        "@type": "Course",
        "name": "{{$c->name}}",
        "description": "{{strip_tags($c->short_description)}}",
        "provider": {
          "@type": "Organization",
          "name": "IIAG Jyotish Sansthan",
          "sameAs": "iiag.co.in"
        }
      },
    @endforeach
    @foreach($liveCourses as $key => $c)
      {
        "@context": "https://schema.org",
        "@type": "Course",
        "name": "{{$c->name}}",
        "description": "{{strip_tags($c->short_description)}}",
        "provider": {
          "@type": "Organization",
          "name": "IIAG Jyotish Sansthan",
          "sameAs": "iiag.co.in"
        }
      }
      @if($key+1!=count($liveCourses))
      ,
      @endif
    @endforeach
  </script>
  @endpush

@section('content')<!--banner start-->
@include('main.courses.home')
@include('main.courses.form')
@endsection

@push('scripts')
<script type="text/javascript">
$(document).ready(function() {

  setTimeout(function(){
    document.getElementById('allcourselandingpageopenbtn').click();
  }, 15000);
    $('.cartfom').on('submit', function(event) {
        event.preventDefault();
        var formData = $(this).serialize();
        $.ajax({
            url: "{{ route('addtocartcourse') }}",
            method: "POST",
            data: formData,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            success: function(response) {
              sessionUserId = response.user_id;
              showCart()
              // $('#subTotalPrice').html(response.totalPrice);
              // $('.minicartdata').html(response.html); // If you have an element to show cart items
              // var offcanvasElement = document.getElementById('offcanvasWithBothOptions');
              // var offcanvas = new bootstrap.Offcanvas(offcanvasElement);
              // offcanvas.show();
            },
            error: function(xhr) {
                // Handle errors, show an error message
                alert('Error adding item to cart. Please try again.');
                console.log(xhr.responseText); // Optional: log error for debugging
            }
        });
    });
});

  function alreadyPurchased(){
    toastr.error('Course already purchased.');
  }
</script>
@endpush
