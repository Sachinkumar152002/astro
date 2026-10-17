<style media="screen">
.coming {
position: relative;
width: 100%;
height: 100%;
background: linear-gradient(90deg, #FFFACD 2.5%, #FFF174 79.28%);
z-index: -999;
}

#beerCanvas {
position: absolute;
top: 0;
left: 0;
width: 100%;
height: 100%;
z-index: 100;
}

.coming_content {
position: absolute;
top: 50%;
left: 50%;
transform: translate(-50%, -50%);
width: 70%;
margin: 0 auto;
color: white;
text-align: center;
z-index: 101;
}

.coming_content h1 {
font-size: 90px;
margin: 0;
letter-spacing: 2px;
text-align: center;
color: black;
}

.coming_content .separator_container {
width: 100%;
display: block;
text-align: center;
position: relative;
margin: 12px 0;
}

.coming_content .separator {
color: white;
margin: 0 auto 1em;
width: 11em;
}

.coming_content .line_separator {
display: inline-block;
}

.coming_content .line_separator svg {
width: 30px;
height: 20px;
}

.coming_content .line_separator:before,
.coming_content .line_separator:after {
display: block;
width: 40%;
content: " ";
margin-top: 14px;
border: 1px solid white;
}

.coming_content .line_separator:before {
float: left;
}

.coming_content .line_separator:after {
float: right;
}

.coming_content h3 {
font-family: sans-serif;
letter-spacing: 2px;
line-height: 2;
font-size: 16px;
font-weight: 400;
text-align: center;
margin: 0;
}

.coming_content h3 a {
text-decoration: underline;
}
.coming{
  color: red !important;
}
</style>
@extends(themeView() . 'layout')
@section('content')

<section class="coming">
  <canvas id='beerCanvas'></canvas>
  <div class="coming_content">
    <img src="/assets/images/hourglass-unscreen.gif" alt="comingsoon" height="200px;">
    <h1 class="coming">Coming Soon</h1>

  </div>
</section>
@endsection


@push('scripts')
<script type="text/javascript">
$(document).ready(function() {
  var canvas = $('#beerCanvas')[0];
  var ctx = canvas.getContext('2d');
  var particles = [];
  var particleCount = 280;

  for (var i = 0; i < particleCount; i++) {
    particles.push(new particle());
  }

  function particle() {
    this.x = Math.random() * canvas.width;
    this.y = canvas.height + Math.random() * 300;
    this.speed = 1 + Math.random();
    this.radius = Math.random() * 3;
    this.opacity = (Math.random() * 100) / 1000;
  }

  function loop() {
    requestAnimationFrame(loop);
    draw();
  }

  function draw() {
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    ctx.globalCompositeOperation = 'lighter';
    for (var i = 0; i < particles.length; i++) {
      var p = particles[i];
      ctx.beginPath();
      ctx.fillStyle = 'rgba(255,255,255,' + p.opacity + ')';
      ctx.arc(p.x, p.y, p.radius, 0, Math.PI * 2, false);
      ctx.fill();
      p.y -= p.speed;
      if (p.y <= -10)
        particles[i] = new particle();
    }
  }

  loop();
});

</script>
@endpush
