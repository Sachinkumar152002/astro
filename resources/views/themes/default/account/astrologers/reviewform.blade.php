@extends(themeView().'layout')

@section('title', 'Astrologers')
@section('keywords', '')
@section('description','')

@section('content')
<style>
.rating {
      float:left;
    }

    /* :not(:checked) is a filter, so that browsers that don’t support :checked don’t
      follow these rules. Every browser that supports :checked also supports :not(), so
      it doesn’t make the test unnecessarily selective */
    .rating:not(:checked) > input {
        position:absolute;
        top:-9999px;
        clip:rect(0,0,0,0);
    }

    .rating:not(:checked) > label {
        float:right;
        width:1em;
        /* padding:0 .1em; */
        overflow:hidden;
        white-space:nowrap;
        cursor:pointer;
        font-size:200%;
        /* line-height:1.2; */
        color:#ddd;
    }

    .rating:not(:checked) > label:before {
        content: '★ ';
    }

    .rating > input:checked ~ label {
        color: #ffc107;

    }

    .rating:not(:checked) > label:hover,
    .rating:not(:checked) > label:hover ~ label {
        color: #ffc107;

    }

    .rating > input:checked + label:hover,
    .rating > input:checked + label:hover ~ label,
    .rating > input:checked ~ label:hover,
    .rating > input:checked ~ label:hover ~ label,
    .rating > label:hover ~ input:checked ~ label {
        color: #ffc107;

    }

    .rating > label:active {
        position:relative;
        top:2px;
        left:2px;
    }
</style>

<div class="container" >
  @if(session()->has('message'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      {{ session()->get('message') }}
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    @endif

  <form method="post" action="{{route('astrologers.review', $astrologer->slug)}}">
    @csrf
    <h5 class="mb-3 h5 border-bottom p-2 bg-light">Review</h5>
    <div class="row">
      <div class="col-12">

        <div class="form-group">
          <div class="rating">
            <input type="radio" id="star5" name="rating" value="5" /><label for="star5" title="Meh">5 stars</label>
            <input type="radio" id="star4" name="rating" value="4" /><label for="star4" title="Kinda bad">4 stars</label>
            <input type="radio" id="star3" name="rating" value="3" /><label for="star3" title="Kinda bad">3 stars</label>
            <input type="radio" id="star2" name="rating" value="2" /><label for="star2" title="Sucks big tim">2 stars</label>
            <input type="radio" id="star1" name="rating" value="1" /><label for="star1" title="Sucks big time">1 star</label>
          </div>

          <label>Comments</label>
          <textarea class="form-control" name="comments" rows="3"></textarea>
        </div>
        <button class="btn btn-warning btn-lg" type="submit">Submit</button>
      </div>
    </div>
  </form>
</div>
@endsection
