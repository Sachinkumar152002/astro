@extends(themeView() . 'layout')

@section('title', $page->meta_title ?: $page->name)
@section('keywords', $page->meta_keywords)
@section('description', $page->meta_description ?: strip_tags($page->short_description))

@push('style')
<style>
  .av-about-page { --about-ink:#211832; --about-purple:#592584; --about-gold:#f5a313; color:var(--about-ink); background:#f7f8fc; }
  .av-about-hero { position:relative; overflow:hidden; padding:62px 0 68px; background:linear-gradient(120deg,#fff7e7 0%,#fffaf4 52%,#f0e8fb 100%); }
  .av-about-hero:after { content:"✦"; position:absolute; right:8%; top:12px; color:rgba(89,37,132,.08); font-size:190px; line-height:1; pointer-events:none; }
  .av-about-hero .container { position:relative; z-index:1; }
  .av-about-eyebrow { display:inline-flex; align-items:center; gap:8px; margin-bottom:14px; padding:7px 13px; border-radius:999px; color:var(--about-purple); background:#fff; box-shadow:0 4px 15px rgba(51,28,70,.08); font-size:12px; font-weight:700; }
  .av-about-eyebrow i { color:var(--about-gold); }
  .av-about-hero h1 { margin:0 0 14px; color:var(--about-ink); font-size:clamp(32px,4vw,48px); font-weight:800; line-height:1.12; }
  .av-about-hero h1 span { color:#e99008; }
  .av-about-hero p { max-width:650px; margin:0; color:#686574; font-size:16px; line-height:1.75; }
  .av-about-content { padding:52px 0 64px; }
  .av-about-card { height:100%; padding:30px; border:1px solid #eee8f2; border-radius:18px; background:#fff; box-shadow:0 8px 24px rgba(38,27,69,.07); }
  .av-about-image { display:flex; min-height:300px; align-items:center; justify-content:center; overflow:hidden; border-radius:15px; background:radial-gradient(circle at 50% 40%,#fff 0 18%,#f8eddb 19% 47%,#f3eafb 48% 100%); }
  .av-about-image img { display:block; width:100%; max-height:390px; object-fit:cover; }
  .av-about-image .fallback { color:#682c8e; font-size:86px; }
  .av-about-copy h2,.av-about-details h2 { margin:0 0 16px; color:var(--about-ink); font-size:27px; font-weight:800; }
  .av-about-copy p { margin:0 0 18px; color:#686574; font-size:15px; line-height:1.8; }
  .av-about-copy a { display:inline-flex; align-items:center; gap:8px; padding:11px 20px; border-radius:999px; color:#fff; background:linear-gradient(110deg,#f6a90e,#ed8100); font-size:14px; font-weight:700; text-decoration:none; box-shadow:0 5px 14px rgba(237,129,0,.2); }
  .av-about-details { margin-top:28px; padding:30px; border:1px solid #eee8f2; border-radius:18px; background:#fff; box-shadow:0 8px 24px rgba(38,27,69,.06); }
  .av-about-details h2 { font-size:23px; }
  .av-about-details :is(p,li) { color:#686574; font-size:15px; line-height:1.8; }
  .av-about-details ul { padding-left:22px; }
  .av-about-details li { margin-bottom:8px; }
  @media(max-width:767px) {
    .av-about-hero { padding:38px 0 42px; }
    .av-about-hero:after { right:-10px; top:18px; font-size:125px; }
    .av-about-hero p { font-size:14px; }
    .av-about-content { padding:26px 0 38px; }
    .av-about-card,.av-about-details { padding:20px; }
    .av-about-image { min-height:210px; }
    .av-about-copy h2,.av-about-details h2 { font-size:21px; }
    .av-about-copy p,.av-about-details :is(p,li) { font-size:14px; }
  }
</style>
@endpush

@section('content')
<main class="av-about-page">
  <section class="av-about-hero">
    <div class="container">
      <span class="av-about-eyebrow"><i class="fa fa-moon-o"></i> About {{ config('app.name') }}</span>
      <h1>Guidance for your journey,<br><span>written in the stars.</span></h1>
      @if($page->short_description)
        <p>{{ strip_tags($page->short_description) }}</p>
      @else
        <p>Explore astrology services and connect with astrologers for guidance shaped around your questions and your journey.</p>
      @endif
    </div>
  </section>

  <section class="av-about-content">
    <div class="container">
      <div class="row g-4 align-items-stretch">
        <div class="col-lg-5">
          <div class="av-about-card av-about-image">
            @if($page->image && $page->image->image())
              <img src="{{ $page->image->image(700) }}" alt="{{ $page->name }}">
            @else
              <span class="fallback" aria-hidden="true"><i class="fa fa-sun-o"></i></span>
            @endif
          </div>
        </div>
        <div class="col-lg-7">
          <div class="av-about-card av-about-copy">
            <h2>{{ $page->name ?: 'About Us' }}</h2>
            @if($page->short_description)
              <p>{{ strip_tags($page->short_description) }}</p>
            @endif
            <p>AstroVyra brings astrology services together in one place, making it easier to explore your horoscope, prepare your Kundli, and connect with astrologers.</p>
            <a href="{{ route('astrologers.index') }}">Explore astrologers <i class="fa fa-arrow-right"></i></a>
          </div>
        </div>
      </div>

      @if($page->content)
        <article class="av-about-details">
          <h2>Discover AstroVyra</h2>
          {!! $page->content !!}
        </article>
      @endif
    </div>
  </section>
</main>
@endsection
