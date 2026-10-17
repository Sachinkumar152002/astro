@extends(themeView().'layout')

@push('schema')

@endpush

@section('content')
  <section class=" category_box pad-top">
      <div class="container">
        <h2 class="fs-2 text fw-bold mb-3 text-t">Related {{$tag->name}}</h2>
          <div class="row">
            @foreach($tag->products as $key => $value)
              @if($product=$value->product)
                <div class="mb-2 col-lg-3 col-sm-6 col-6">
                  <a href="{{route('product.handle', [$product->slug])}}">
                    <div class="card border">
                        <img class="rounded p-categary-img" src="{{$product->image?$product->image->image(400, 400):no_image()}}" alt="{{$product->title}}">
                        <h5 class="card-title text-center fw-semibold fs-6 mb-0 product-card-h-c text-truncate py-3">{{$product->title}}</h5>
                    </div>
                  </a>
                </div>
              @endif
             @endforeach
          </div>
      </div>
  </section>
@endsection

@push('scripts')
  <script>
    let scrollTimeout;

    window.addEventListener('scroll', () => {
      let button = document.getElementById('scrollButton');

      // Show the button when scrolling
      button.style.display = 'block';

      // Clear the timeout if it's already set
      if (scrollTimeout) clearTimeout(scrollTimeout);

      // Set a timeout to hide the button after a delay
      scrollTimeout = setTimeout(() => {
        button.style.display = 'none';
      }, 2000); // Adjust time (ms) to suit your preference
    });
  </script>
@endpush
