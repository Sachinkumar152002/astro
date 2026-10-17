@foreach($products as $item)
    <div class="col-lg-4">
      <div class="item m-2">
        <a href="{{route('product.handle', ['slug'=>$item->slug])}}">
          <div class="p-box"><img class="rounded" src="{{$item->image ? $item->image->image(450, 450) : ''}}" alt="{{$item->title}}">
              <div class="effect"></div>
              @if(strtotime($item->sale_price_start_date) <= strtotime("now") && strtotime($item->sale_price_end_date) >= strtotime("now"))
                <div class="salebox">
                  <button class="sale-btna">Sale</button>
                  <button class="sale-btnb">{{ (($item->base_price-$item->sale_price)/$item->base_price)*100 }}%</button>
                </div>
              @endif
              <div class="btn-effect">
                  <div class="p-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16"
                          width="16" height="16" fill="black" class="bi bi-eye-slash pointer">
                          <path
                              d="M13.359 11.238C15.06 9.72 16 8 16 8s-3-5.5-8-5.5a7 7 0 0 0-2.79.588l.77.771A6 6 0 0 1 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13 13 0 0 1 14.828 8q-.086.13-.195.288c-.335.48-.83 1.12-1.465 1.755q-.247.248-.517.486z">
                          </path>
                          <path
                              d="M11.297 9.176a3.5 3.5 0 0 0-4.474-4.474l.823.823a2.5 2.5 0 0 1 2.829 2.829zm-2.943 1.299.822.822a3.5 3.5 0 0 1-4.474-4.474l.823.823a2.5 2.5 0 0 0 2.829 2.829">
                          </path>
                          <path
                              d="M3.35 5.47q-.27.24-.518.487A13 13 0 0 0 1.172 8l.195.288c.335.48.83 1.12 1.465 1.755C4.121 11.332 5.881 12.5 8 12.5c.716 0 1.39-.133 2.02-.36l.77.772A7 7 0 0 1 8 13.5C3 13.5 0 8 0 8s.939-1.721 2.641-3.238l.708.709zm10.296 8.884-12-12 .708-.708 12 12z">
                          </path>
                      </svg></div>
                      {{--
                  <div class="p-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16"
                          width="16" height="16" fill="black" class="bi bi-heart pointer">
                          <path
                              d="m8 2.748-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143q.09.083.176.171a3 3 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15">
                          </path>
                      </svg></div>
                      --}}
                  <div class="p-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16"
                          width="16" height="16" fill="black"
                          class="bi bi-arrow-clockwise pointer">
                          <path fill-rule="evenodd"
                              d="M8 3a5 5 0 1 0 4.546 2.914.5.5 0 0 1 .908-.417A6 6 0 1 1 8 2z">
                          </path>
                          <path
                              d="M8 4.466V.534a.25.25 0 0 1 .41-.192l2.36 1.966c.12.1.12.284 0 .384L8.41 4.658A.25.25 0 0 1 8 4.466">
                          </path>
                      </svg></div>
              </div>
          </div>
          @if($item->avaibility)
            @if($item->type == 3)
             <!-- <a href="{{route('product.handle', ['slug'=>$item->slug])}}" class="ad-cart-btn m-0 mt-2 pointer">Detail</a> -->
            @else
              <form class="cartfom" action="" method="post">
                   @csrf
                   <input type="hidden" name="item_id" value="{{ $item->id }}">
                   <button type="submit" data-bs-toggle="offcanvas" data-bs-target="#offcanvasWithBothOptions"
                   aria-controls="offcanvasWithBothOptions" class="ad-cart-btn m-0 mt-2 pointer">Add to cart</button>
               </form>
            @endif
             <div class="p-2 mt-2">
         @else
         <a class="ad-cart-btn m-0 mt-2 pointer">Out Of Stock</a>
          @endif
          <a "{{route('product.handle', [$item->slug])}}" class="prod-name fs-6 text m-0 mt-2 pointer fw-semibold">{{$item->title}}</a>
          @if(strtotime($item->sale_price_start_date) <= strtotime("now") && strtotime($item->sale_price_end_date) >= strtotime("now"))
            <p class="price m-0 mt-1 fs-7 text"><strike class="actule_price">₹ {{$item->base_price}}</strike> ₹ {{$item->sale_price}}</p>
          @else
            <p class="price m-0 mt-1 fs-7 text">₹ {{$item->base_price}}</p>
          @endif
        </div>
        </a>
      </div>
    </div>
  @endforeach
