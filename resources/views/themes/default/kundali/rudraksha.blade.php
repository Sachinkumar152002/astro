<div class="rudraksha-suggestion-container">
  <!-- Header Section -->
  <div class="rudraksha-header mb-4">
    <h2 class="rudraksha-title">Rudraksha Recommendation</h2>
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
        <p class="text-muted">{{$name}}</p>
      </div>
    </div>

    <!-- For Disease Cure Card -->
    <div class="rudraksha-card rudraksha-card-green">
      <div class="rudraksha-card-header">
        <i class="fa fa-plus-circle rudraksha-icon-green"></i>
        <span class="rudraksha-card-title">For Disease Cure</span>
      </div>
      <div class="rudraksha-card-content" id="rudraksha-disease">
        <p class="text-muted">{{implode(' Mukhi Rudraksha, ',$mukhi_for_disease_cure)}} Mukhi Rudraksha</p>
      </div>
    </div>

    <!-- For Money Card -->
    <div class="rudraksha-card rudraksha-card-yellow">
      <div class="rudraksha-card-header">
        <i class="fa fa-gem rudraksha-icon-yellow"></i>
        <span class="rudraksha-card-title">For Money</span>
      </div>
      <div class="rudraksha-card-content" id="rudraksha-money">
        <p class="text-muted">{{implode(' Mukhi Rudraksha , ',$mukhi_for_money)}} Mukhi Rudraksha</p>
      </div>
    </div>

    <!-- Mukhi Description Card (Full Width) -->
    <div class="rudraksha-card rudraksha-card-blue rudraksha-card-full">
      <div class="rudraksha-card-header">
        <i class="fa fa-file-alt rudraksha-icon-blue"></i>
        <span class="rudraksha-card-title">Mukhi Description</span>
      </div>
      <div class="rudraksha-card-content" id="rudraksha-description">
        <p class="text-muted">{{$mukhi_description}}</p>
      </div>
    </div>

    <!-- Bot Response Card (Full Width) -->
    <div class="rudraksha-card rudraksha-card-blue rudraksha-card-full">
      <div class="rudraksha-card-header">
        <i class="fa fa-bolt rudraksha-icon-blue"></i>
        <span class="rudraksha-card-title">Bot Response</span>
      </div>
      <div class="rudraksha-card-content" id="rudraksha-bot-response">
        <p class="text-muted">{{$bot_response}}</p>
      </div>
    </div>
  </div>
</div>
