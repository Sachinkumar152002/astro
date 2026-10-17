
@extends(themeView().'layout')

@section('title', "Contact Us | ".config("app.name"))
@section('keywords', "Contact Us | ".config("app.name"))
@section('description', "Contact Us | ".config("app.name"))

@push('style')
<style media="screen">
.contact-page-skeleton { position:fixed; inset:0; z-index:20000; overflow:auto; padding:10px max(4vw,20px) 36px; background:#fffaf5; transition:opacity .3s ease; }
.contact-page-skeleton.is-dismissing { opacity:0; pointer-events:none; }
.contact-page-skeleton__top,.contact-page-skeleton__nav,.contact-page-skeleton__hero,.contact-page-skeleton__cards,.contact-page-skeleton__map { max-width:1280px; margin-left:auto; margin-right:auto; }
.contact-page-skeleton__top { height:78px; display:flex; align-items:center; gap:22px; }
.contact-page-skeleton :is(.contact-page-skeleton__top i,.contact-page-skeleton__top b,.contact-page-skeleton__top span,.contact-page-skeleton__top em,.contact-page-skeleton__nav i,.contact-page-skeleton__hero b,.contact-page-skeleton__hero span,.contact-page-skeleton__cards i,.contact-page-skeleton__map) { display:block; border-radius:9px; background:linear-gradient(100deg,#eee8e2 20%,#faf5ef 40%,#eee8e2 60%); background-size:220% 100%; animation:contact-page-shimmer 1.35s linear infinite; }
.contact-page-skeleton__top i { width:150px;height:42px; }.contact-page-skeleton__top b { width:34%;height:38px;border-radius:24px;margin-left:8%; }.contact-page-skeleton__top span { width:62px;height:14px; }.contact-page-skeleton__top em { width:82px;height:34px;margin-left:auto; }
.contact-page-skeleton__nav { height:46px;display:flex;align-items:center;justify-content:space-between;gap:12px;border-top:1px solid #f0e8df;border-bottom:1px solid #f0e8df; }.contact-page-skeleton__nav i { width:8%;height:13px; }
.contact-page-skeleton__hero { height:150px;margin-top:22px;border-radius:18px;background:linear-gradient(120deg,#fff3e4,#f1e8f8); }.contact-page-skeleton__hero b { width:28%;height:26px;margin:60px auto 0; }.contact-page-skeleton__hero span { display:none; }
.contact-page-skeleton__cards { display:grid;grid-template-columns:repeat(3,1fr);gap:20px;margin-top:26px; }.contact-page-skeleton__cards i { height:155px;border-radius:15px;background-color:#fff;box-shadow:0 5px 16px rgba(48,25,70,.06); }
.contact-page-skeleton__map { height:300px;margin-top:24px;border-radius:14px; }
@keyframes contact-page-shimmer { to { background-position-x:-220%; } }
@media(max-width:767px) { .contact-page-skeleton { padding:8px 14px 28px; }.contact-page-skeleton__top { height:60px;gap:9px; }.contact-page-skeleton__top i { width:112px;height:34px; }.contact-page-skeleton__top b { width:32%;height:30px;margin-left:auto; }.contact-page-skeleton__top span { display:none; }.contact-page-skeleton__top em { width:58px;height:30px;margin-left:0; }.contact-page-skeleton__nav { height:38px;gap:8px;overflow:hidden; }.contact-page-skeleton__nav i { flex:0 0 72px; }.contact-page-skeleton__hero { height:120px;margin-top:16px; }.contact-page-skeleton__hero b { width:50%;margin-top:47px; }.contact-page-skeleton__cards { grid-template-columns:1fr;gap:10px;margin-top:16px; }.contact-page-skeleton__cards i { height:100px; }.contact-page-skeleton__map { height:180px;margin-top:16px; } }
.contact-box-container {
display:grid;
grid-template-columns:repeat(3,minmax(0,1fr));
gap:20px;
padding:0;
}

.contact-box {
border-radius:16px;
padding:26px 24px;
width:auto;
min-height:172px;
text-align: center;
transition:transform .2s ease,box-shadow .2s ease;
border:1px solid #eee5f2;
background:#fff;
box-shadow:0 8px 24px rgba(50,25,72,.06);
}
.contact-box:hover { transform:translateY(-3px);box-shadow:0 12px 28px rgba(50,25,72,.1); }

.contact-box i {
display:grid;
place-items:center;
width:52px;
height:52px;
margin:0 auto 13px;
border-radius:50%;
background:#f5ecfb;
font-size:22px;
color:#57247b;
}

.contact-box h6 {
font-size:15px;
font-weight:600;
letter-spacing:.04em;
margin:0 0 8px;
color:#24143a;
}

.contact-box a,
.contact-box p {
display:block;
font-size:14px;
font-weight:500;
line-height:1.6;
color:#665d72;
text-decoration: none;
margin:0;
overflow-wrap:anywhere;
}

.contact-box a:hover { color:#e28a00; }
.contact-us-page { background:#fffdfa; }
.contact-us-hero { position:relative;overflow:hidden;padding:58px 0 62px;background:radial-gradient(ellipse at 85% 18%,rgba(130,73,171,.18),transparent 34%),linear-gradient(115deg,#fff5e5 0%,#fffaf5 58%,#f4eafb 100%); }
.contact-us-hero:after { content:'\\2726';position:absolute;right:9%;top:5px;color:rgba(90,38,124,.09);font-size:190px;line-height:1;pointer-events:none; }
.contact-us-hero .container { position:relative;z-index:1; }
.contact-us-eyebrow { display:inline-flex;align-items:center;gap:8px;margin-bottom:12px;padding:7px 13px;border:1px solid #eee0d1;border-radius:999px;background:rgba(255,255,255,.82);color:#604077;font-size:12px;font-weight:700; }
.contact-us-hero h1 { margin:0;color:#20153b;font-family:Georgia,serif;font-size:clamp(34px,4vw,48px);font-weight:700; }
.contact-us-hero h1 span { color:#ec9200; }
.contact-us-hero p { max-width:650px;margin:12px 0 0;color:#655c70;font-size:15px; }
.contact-us-content { padding:38px 0 52px; }
.contact-us-section-title { margin:0 0 8px;color:#24143a;font-size:24px;font-weight:700; }
.contact-us-section-copy { margin:0 0 24px;color:#746c7e;font-size:14px; }
.contact-us-map { overflow:hidden;border:1px solid #eee5f2;border-radius:16px;background:#fff;box-shadow:0 8px 24px rgba(50,25,72,.06); }
.contact-us-map iframe { display:block;width:100%;height:360px;border:0; }
@media(max-width:767px) {
  .contact-us-hero { padding:42px 0 44px; }
  .contact-us-hero:after { right:-20px;top:12px;font-size:130px; }
  .contact-us-content { padding:28px 0 36px; }
  .contact-box-container { grid-template-columns:1fr;gap:12px; }
  .contact-box { display:grid;grid-template-columns:52px minmax(0,1fr);column-gap:14px;align-items:center;min-height:0;padding:17px;text-align:left; }
  .contact-box i { grid-row:span 2;margin:0; }
  .contact-box h6 { align-self:end;margin-bottom:2px; }
  .contact-box a,.contact-box p { align-self:start; }
  .contact-us-map iframe { height:260px; }
}

</style>
@endpush

@push('schema')
  <script type="application/ld+json">
  {
    "@context":"https://schema.org",
    "@type":"ContactPage",
    "@id":"{{ url()->current() }}#webpage",
    "url":"{{ url()->current() }}",
    "name":"Contact {{ config('app.name') }}",
    "description":"Reach us for orders, support, or partnerships.",
    "isPartOf":{
      "@id":"{{ url('/') }}/#website"
    },
    "about":{
      "@id":"{{ url('/') }}/#org"
    },
    "breadcrumb":{
      "@id":"{{ url()->current() }}#breadcrumb"
    }
  }
  </script>
@endpush

@section('content')
<main class="contact-us-page">
<section class="contact-us-hero">
  <div class="container">
    <div class="contact-us-eyebrow"><i class="bi bi-moon-stars-fill"></i> We're here to help</div>
    <h1>Contact <span>Us</span></h1>
    <p>Have a question or need assistance? Reach our team by phone, email, or visit us at our office.</p>
  </div>
</section>

<section class="contact-us-content">
  <div class="container">
    <h2 class="contact-us-section-title">Get in touch</h2>
    <p class="contact-us-section-copy">Choose the contact option that works best for you.</p>
    <div class="contact-box-container">
    <!-- Phone Box -->
    <div class="contact-box">
      <i class="bi bi-telephone-fill"></i>
      <h6>CALL US</h6>
      <a href="tel:{{ fromSetting('contact') }}">{{ fromSetting('contact') }}</a>

    </div>

    <!-- Email Box -->
    <div class="contact-box">
      <i class="bi bi-envelope-fill"></i>
      <h6>EMAIL US</h6>
      <a href="mailto:{{ fromSetting('contact_email') }}">{{ fromSetting('contact_email') }}</a>
    </div>

    <!-- Address Box -->
    <div class="contact-box">
      <i class="bi bi-geo-alt-fill"></i>
      <h6>OUR LOCATION</h6>
      <p>{{ fromSetting("address") }}</p>
    </div>
  </div>
  </div>
</section>

<section class="contact-us-content pt-0">
  <div class="container">
    <h2 class="contact-us-section-title">Find us</h2>
    <p class="contact-us-section-copy">Our team is ready to assist you.</p>
    <div class="contact-us-map">
  <iframe
    src="https://www.google.com/maps?q=Rajasthan&output=embed"
    title="AstroVyra location map"
    allowfullscreen=""
    loading="lazy"
    referrerpolicy="no-referrer-when-downgrade">
  </iframe>
    </div>
  </div>
</section>
</main>

@endsection
