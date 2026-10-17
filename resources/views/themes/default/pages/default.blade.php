@extends(themeView().(request()->webview?'webview':'layout'))
@section('title', $page->meta_title)
@section('keywords', $page->meta_keywords)
@section('description',$page->meta_description)

@if($page->bannerImage && $page->bannerImage->image())
  @section('image')
  {{$page->bannerImage->image()}}
  @endsection
@endif

@if($page->slug === 'about-us')
@push('style')
<style>
  .about-page-skeleton { position:fixed; inset:0; z-index:20000; overflow:auto; padding:10px max(4vw,20px) 36px; background:#fffaf5; transition:opacity .3s ease; }
  .about-page-skeleton.is-dismissing { opacity:0; pointer-events:none; }
  .about-page-skeleton__top,.about-page-skeleton__nav,.about-page-skeleton__hero,.about-page-skeleton__body { max-width:1280px; margin-left:auto; margin-right:auto; }
  .about-page-skeleton__top { height:78px; display:flex; align-items:center; gap:22px; }
  .about-page-skeleton :is(.about-page-skeleton__top i,.about-page-skeleton__top b,.about-page-skeleton__top span,.about-page-skeleton__top em,.about-page-skeleton__nav i,.about-page-skeleton__hero b,.about-page-skeleton__hero span,.about-page-skeleton__body i) { display:block; border-radius:9px; background:linear-gradient(100deg,#eee8e2 20%,#faf5ef 40%,#eee8e2 60%); background-size:220% 100%; animation:about-page-shimmer 1.35s linear infinite; }
  .about-page-skeleton__top i { width:150px;height:42px; }.about-page-skeleton__top b { width:34%;height:38px;border-radius:24px;margin-left:8%; }.about-page-skeleton__top span { width:62px;height:14px; }.about-page-skeleton__top em { width:82px;height:34px;margin-left:auto; }
  .about-page-skeleton__nav { height:46px; display:flex; align-items:center; justify-content:space-between; gap:12px; border-top:1px solid #f0e8df; border-bottom:1px solid #f0e8df; }.about-page-skeleton__nav i { width:8%;height:13px; }
  .about-page-skeleton__hero { height:190px;margin-top:22px;border-radius:18px;background:linear-gradient(120deg,#fff3e4,#f1e8f8); }.about-page-skeleton__hero b { width:38%;height:24px;margin:38px 0 14px 6%; }.about-page-skeleton__hero span { width:65%;height:13px;margin:10px 0 0 6%; }
  .about-page-skeleton__body { display:grid;grid-template-columns:.8fr 1.2fr;gap:22px;margin-top:24px; }.about-page-skeleton__body i { height:260px;border-radius:16px;background-color:#fff;box-shadow:0 5px 16px rgba(48,25,70,.06); }.about-page-skeleton__body i:last-child { height:260px; }
  @keyframes about-page-shimmer { to { background-position-x:-220%; } }
  @media(max-width:767px) { .about-page-skeleton { padding:8px 14px 28px; }.about-page-skeleton__top { height:60px;gap:9px; }.about-page-skeleton__top i { width:112px;height:34px; }.about-page-skeleton__top b { width:32%;height:30px;margin-left:auto; }.about-page-skeleton__top span { display:none; }.about-page-skeleton__top em { width:58px;height:30px;margin-left:0; }.about-page-skeleton__nav { height:38px;gap:8px;overflow:hidden; }.about-page-skeleton__nav i { flex:0 0 72px; }.about-page-skeleton__hero { height:155px;margin-top:16px; }.about-page-skeleton__hero b { width:70%;margin:25px 0 12px 6%; }.about-page-skeleton__hero span { width:83%; }.about-page-skeleton__body { grid-template-columns:1fr;gap:12px;margin-top:16px; }.about-page-skeleton__body i { height:175px; }.about-page-skeleton__body i:last-child { height:215px; } }
</style>
@endpush
@endif

@push('schema')
  <script type="application/ld+json">
  {
    "@context":"https://schema.org",
    "@type":"WebPage",
    "@id":"{{ url()->current() }}#webpage",
    "url":"{{ url()->current() }}",
    "name":"{{ $page->name }}",
    "description":"{{ strip_tags($page->short_description) }}",
    "inLanguage":"en-IN",
    "isPartOf":{
      "@id":"{{ url('/') }}/#website"
    },
    "about":{
      "@id":"{{ url('/') }}/#org"
    },
    "datePublished":"{{ date('Y-m-d') }}",
    "dateModified":"{{ date('Y-m-d') }}"
  }
  </script>
@endpush

@section('content')
@if($page->slug === 'about-us')
<style>
  .av-about-cms { --about-ink:#211832; --about-purple:#592584; --about-gold:#f5a313; color:var(--about-ink); background:#f7f8fc; }
  .av-about-cms .about-hero { position:relative; overflow:hidden; padding:58px 0 64px; background:linear-gradient(120deg,#fff7e7 0%,#fffaf4 52%,#f0e8fb 100%); }
  .av-about-cms .about-hero:after { display:none; }
  .av-about-cms .about-hero .container { position:relative; z-index:1; }
  .av-about-cms .eyebrow { display:inline-flex; align-items:center; gap:8px; margin-bottom:14px; padding:7px 13px; border-radius:999px; color:var(--about-purple); background:#fff; box-shadow:0 4px 15px rgba(51,28,70,.08); font-size:12px; font-weight:700; }
  .av-about-cms .eyebrow i { color:var(--about-gold); }
  .av-about-cms h1 { margin:0 0 14px; color:var(--about-ink); font-size:clamp(32px,4vw,48px); font-weight:800; line-height:1.12; }
  .av-about-cms h1 span { color:#e99008; }
  .av-about-cms .hero-copy { max-width:720px; margin:0; color:#686574; font-size:16px; line-height:1.75; }
  .av-about-cms .about-content { padding:48px 0 60px; }
  .av-about-cms .about-intro,.av-about-cms .about-details { height:100%; padding:28px; border:1px solid #eee8f2; border-radius:18px; background:#fff; box-shadow:0 8px 24px rgba(38,27,69,.07); }
  .av-about-cms .about-visual { display:flex; min-height:300px; align-items:center; justify-content:center; overflow:hidden; border-radius:14px; background:radial-gradient(circle at 50% 40%,#fff 0 18%,#f8eddb 19% 47%,#f3eafb 48% 100%); }
  .av-about-cms .about-visual img { width:100%; max-height:390px; object-fit:cover; }
  .av-about-cms .about-visual i { color:#682c8e; font-size:82px; }
  .av-about-cms .about-intro h2,.av-about-cms .about-details h2 { margin:0 0 15px; color:var(--about-ink); font-size:25px; font-weight:800; }
  .av-about-cms :is(.about-intro,.about-details) :is(p,li) { color:#686574; font-size:15px; line-height:1.8; }
  .av-about-cms .about-details { height:auto; margin-top:26px; }
  .av-about-cms .about-details ul { padding-left:22px; }
  .av-about-cms .about-details li { margin-bottom:8px; }
  .av-about-cms .about-details :is(img,iframe,video) { max-width:100%; }
  @media(max-width:767px) {
    .av-about-cms .about-hero { padding:38px 0 42px; }
    .av-about-cms .about-hero:after { right:-10px; top:15px; font-size:120px; }
    .av-about-cms .hero-copy { font-size:14px; }
    .av-about-cms .about-content { padding:25px 0 36px; }
    .av-about-cms .about-intro,.av-about-cms .about-details { padding:20px; }
    .av-about-cms .about-visual { min-height:210px; }
    .av-about-cms .about-intro h2,.av-about-cms .about-details h2 { font-size:21px; }
    .av-about-cms :is(.about-intro,.about-details) :is(p,li) { font-size:14px; }
  }
</style>
<main class="av-about-cms">
  <section class="about-hero">
    <div class="container">
      <span class="eyebrow"><i class="fa fa-moon-o"></i> About {{ config('app.name') }}</span>
      <h1>Guidance for your journey,<br><span>written in the stars.</span></h1>
      @if($page->short_description)
        <div class="hero-copy">{!! $page->short_description !!}</div>
      @else
        <p class="hero-copy">Discover our story and learn how AstroVyra brings astrology services and astrologer guidance together.</p>
      @endif
    </div>
  </section>
  <section class="about-content">
    <div class="container">
      <div class="row g-4 align-items-stretch">
        <div class="col-lg-5">
          <div class="about-intro about-visual">
            @if($page->image && $page->image->image())
              <img src="{{ $page->image->image(700) }}" alt="{{ $page->name }}">
            @else
              <i class="bi bi-stars" aria-hidden="true"></i>
            @endif
          </div>
        </div>
        <div class="col-lg-7">
          <div class="about-intro">
            <h2>{{ $page->name }}</h2>
            <p>AstroVyra brings astrology services together in one place, making it easier to explore your horoscope, prepare your Kundli, and connect with astrologers.</p>
            <a class="btn btn-warning rounded-pill px-4 mt-2 fw-bold" href="{{ route('astrologers.index') }}">Explore Astrologers <i class="fa fa-arrow-right ms-1"></i></a>
          </div>
        </div>
      </div>
      @if($page->content)
        <article class="about-details">
          <h2>Discover AstroVyra</h2>
          {!! $page->content !!}
        </article>
      @endif
    </div>
  </section>
</main>
@else
<section class="page-header about-page" style="background: <?php echo $page->bannerImage ? 'url(\'' . $page->bannerImage->image() . '\')' : 'linear-gradient(135deg, #1e1b4b 0%, #4c1d95 100%)'; ?>; background-repeat: no-repeat; width: 100%; background-size: cover; padding: 60px 0; text-align: center;">
    <div class="container">
        <h1 class="mt-0 text-white">{{$page->name}}</h1>
    </div>
        @include(themeView().'common.breadcrumb')
</section>
<div class="container mt-5 common_pages px-4 px-md-0">
    <div class="row">
    @if ($page->image)
    <div class="col l4 m12 s12">
      <div class="box">
          <figure>
              <img itemprop="image" src="{{$page->image ? $page->image->image(200) : 'N/A'}}"
                  alt="{{$page->name}}">
          </figure>
      </div>
    </div>
    @endif
        <div class="col l8 m12 s12 short_desc">
          {!! $page->short_description !!}
          <div class="sharethis-inline-share-buttons"></div>
        </div>
    </div>
    <div class="row mt-10 about-list common_pages">
      {!! $page->content !!}
    </div>

</div>
@endif
@endsection
