<div class="card-header-main"> Gem Suggestions </div>
   <div class="  gy-4 description ">
 <div class="rudraksha-suggestion-container">
  <!-- Header Section -->
  <div class="rudraksha-header mb-4">
    <h2 class="rudraksha-title">Gem Recommendation</h2>
    <p class="rudraksha-subtitle">Personalized Rudraksha suggestions based on your astrological profile</p>
  </div>

  <!-- Cards Grid -->
  <div class="rudraksha-cards-grid">
    <!-- Name Card -->
    <div class="rudraksha-card rudraksha-card-purple">
      <div class="rudraksha-card-header">
        <i class="fa fa-bolt rudraksha-icon-purple"></i>
        <span class="rudraksha-card-title">Name</span>
      </div>
      <div class="rudraksha-card-content" id="rudraksha-name">
        <p class="">{{$name}}/{{$other_name}}</p>
      </div>
    </div>

    <!-- For Disease Cure Card -->
    <div class="rudraksha-card rudraksha-card-green">
      <div class="rudraksha-card-header">
        <i class="fa fa-plus-circle rudraksha-icon-green"></i>
        <span class="rudraksha-card-title">Gem</span>
      </div>
      <div class="rudraksha-card-content" id="rudraksha-disease">
        <p class="">{{$gem}}</p>
      </div>
    </div>

    <!-- For Money Card -->
    <div class="rudraksha-card rudraksha-card-yellow">
      <div class="rudraksha-card-header">
        <i class="fa fa-gem rudraksha-icon-yellow"></i>
        <span class="rudraksha-card-title">Planet</span>
      </div>
      <div class="rudraksha-card-content" id="rudraksha-money">
        <p class="">{{$planet}}</p>
      </div>
    </div>

    <!-- Mukhi Description Card (Full Width) -->
    <div class="rudraksha-card rudraksha-card-blue rudraksha-card-full">
      <div class="rudraksha-card-header">
        <i class="fa fa-file-alt rudraksha-icon-blue"></i>
        <span class="rudraksha-card-title">Emerald Description</span>
      </div>
      <div class="rudraksha-card-content" id="rudraksha-description">
        <p class="">{{$description}}</p>
      </div>
    </div>

    <!-- Bot Response Card (Full Width) -->
    <div class="rudraksha-card rudraksha-card-blue">
      <div class="rudraksha-card-header">
        <i class="fa fa-bolt rudraksha-icon-blue"></i>
        <span class="rudraksha-card-title">Good Results For:</span>
      </div>
      <div class="rudraksha-card-content" id="rudraksha-bot-response">
        <p class="">
          @foreach($good_results as $gr)
        <span class="badge badge-pill bg-primary">{{$gr}}</span>
        @endforeach
      </p>
      </div>
    </div>

     <div class="rudraksha-card rudraksha-card-blue">
      <div class="rudraksha-card-header">
        <i class="fa fa-bolt rudraksha-icon-blue"></i>
        <span class="rudraksha-card-title">Diseases Cure:</span>
      </div>
      <div class="rudraksha-card-content" id="rudraksha-bot-response">
        <p class="">
          @foreach($diseases_cure as $dc)
        <span class="badge badge-pill bg-dark">{{$dc}}</span>
        @endforeach
       </p>
      </div>
    </div>
  <div class="rudraksha-card rudraksha-card-blue">
      <div class="rudraksha-card-header">
        <i class="fa fa-bolt rudraksha-icon-blue"></i>
        <span class="rudraksha-card-title">Metal substitute:</span>
      </div>
      <div class="rudraksha-card-content" id="rudraksha-bot-response">
       <p class="">
        @foreach($substitute as $subs)
        <span class="badge badge-pill bg-secondary">{{$subs}}</span>
        @endforeach
       </p>
      </div>
    </div>
<div class="rudraksha-card rudraksha-card-blue">
      <div class="rudraksha-card-header">
        <i class="fa fa-bolt rudraksha-icon-blue"></i>
        <span class="rudraksha-card-title">Not wear with:</span>
      </div>
      <div class="rudraksha-card-content" id="rudraksha-bot-response">
       <p class="">
         @foreach($not_to_wear_with as $ntw)
        <span class="badge badge-pill bg-dark">{{$ntw}} </span>
        @endforeach
      </p>
      </div>
    </div>

    <div class="rudraksha-card rudraksha-card-blue">
      <div class="rudraksha-card-header">
        <i class="fa fa-bolt rudraksha-icon-blue"></i>
        <span class="rudraksha-card-title">Finger and Weight:</span>
      </div>
      <div class="rudraksha-card-content" id="rudraksha-bot-response">
        <table class="table table-bordered"><tbody>
          <tr><td><b>Finger</b></td><td>{{$finger}}</td> </tr>
          <tr><td><b>Weight</b></td><td>{{$weight}}</td></tr>
          <tr><td><b>Day</b></td><td>{{$day}}</td></tr>
          <tr><td><b>Metal</b></td><td>{{$metal}}</td></tr>
        </tbody></table>
      </div>
    </div>

      <div class="rudraksha-card rudraksha-card-blue">
      <div class="rudraksha-card-header">
        <i class="fa fa-bolt rudraksha-icon-blue"></i>
        <span class="rudraksha-card-title">Flaw Results:</span>
      </div>
      <div class="rudraksha-card-content" id="rudraksha-bot-response">
       <table class="table-responsive"><tbody>
         <tr><th>Type</th><th>Effect</th></tr>
         @foreach($flaw_results as $fr)
         <tr><td>{{$fr['flaw_type']}} </td><td>{{$fr['flaw_effects']}}</td>
         @endforeach
       </tbody></table>
      </div>
    </div>
<!-- Mukhi Description Card (Full Width) -->
    <div class="rudraksha-card rudraksha-card-blue rudraksha-card-full">
      <div class="rudraksha-card-header">
        <i class="fa fa-file-alt rudraksha-icon-blue"></i>
        <span class="rudraksha-card-title">Time to wear</span>
      </div>
      <div class="rudraksha-card-content" id="rudraksha-description">
        <p class=""><strong>Time to wear short:</strong> {{$time_to_wear_short}}</p>
        <p class=""><strong>Time to wear:</strong> {{$time_to_wear}}</p>
        <p class=""><strong>Methods:</strong> {{$methods}}
</p>
<p class=""> <blockquote><strong>Mantra:</strong> " {{$mantra}} "</blockquote></p>
      </div>
    </div>

  </div>
</div>

    </div>
