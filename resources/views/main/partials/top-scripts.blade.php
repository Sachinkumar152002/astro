 <!-- Scripts -->
<script>
window.Laravel = {!! json_encode([
'csrfToken' => csrf_token(),
]) !!};
</script>
@if(config('app.env')=='production')
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-111991155-1"></script>
<script>
window.dataLayer = window.dataLayer || [];
function gtag(){dataLayer.push(arguments);}
gtag('js', new Date());
gtag('config', 'UA-111991155-1');
</script>
<noscript></noscript>
@endif
