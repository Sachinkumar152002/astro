<div class="kundli-dashboard-container">
  <!-- Header Section -->
  <header class="dashboard-header">
    <div class="dashboard-header-content">
      <h1 class="dashboard-main-title">
        <i class="fas fa-star"></i>
        Kundli Insights
      </h1>
      <p class="dashboard-subtitle">Unlock the mysteries of the cosmos through ancient Vedic wisdom</p>
    </div>
  </header>

  <!-- Services Grid -->
  <section class="dashboard-services-section">
    <div class="dashboard-services-grid">
      <!-- Basic Details -->
      <div class="dashboard-service-card dashboard-card-1" onclick="handleTab(document.getElementById('basic-tab'),'basic')">
        <div class="dashboard-card-icon">
          <i class="fas fa-user-circle"></i>
        </div>
        <h3 class="dashboard-card-title">Basic Astrological Details</h3>
        <p class="dashboard-card-description">Your personalized astrological profile with detailed planetary positions</p>
      </div>

      <!-- Kundli -->
      <div class="dashboard-service-card dashboard-card-2" onclick="handleTab(document.getElementById('kundali-tab'),'kundali')">
        <div class="dashboard-card-icon">
          <i class="fas fa-star"></i>
        </div>
        <h3 class="dashboard-card-title">Kundali</h3>
        <p class="dashboard-card-description">Complete birth chart analysis with all planetary details</p>
      </div>

      <!-- Divisional Charts -->
      <div class="dashboard-service-card dashboard-card-3" onclick="handleTab(document.getElementById('divisional-tab'),'divisional')">
        <div class="dashboard-card-icon">
          <i class="fas fa-chart-bar"></i>
        </div>
        <h3 class="dashboard-card-title">Divisional Charts</h3>
        <p class="dashboard-card-description">Visual representations of various astrological charts</p>
      </div>

      <!-- KP Planetary Details -->
      <div class="dashboard-service-card dashboard-card-4" onclick="handleTab(document.getElementById('transit-tab'),'transit')">
        <div class="dashboard-card-icon">
          <i class="fas fa-hashtag"></i>
        </div>
        <h3 class="dashboard-card-title">Transit (Gochar) Today</h3>
        <p class="dashboard-card-description">Transit (Gochar) Today</p>
      </div>

      <!-- Ashtakvarga -->
      <div class="dashboard-service-card dashboard-card-5" onclick="handleTab(document.getElementById('ashtakvarga-tab'),'ashtakvarga')">
        <div class="dashboard-card-icon">
          <i class="fas fa-th"></i>
        </div>
        <h3 class="dashboard-card-title">Ashtakvarga</h3>
        <p class="dashboard-card-description">Eight-fold division chart for life aspects analysis</p>
      </div>

      <!-- Dasha -->
      <div class="dashboard-service-card dashboard-card-6" onclick="handleTab(document.getElementById('dasha-tab'),'dasha')">
        <div class="dashboard-card-icon">
          <i class="fas fa-moon"></i>
        </div>
        <h3 class="dashboard-card-title">Dasha</h3>
        <p class="dashboard-card-description">Planetary periods and their influence on your life</p>
      </div>

      <!-- Dosha -->
      <div class="dashboard-service-card dashboard-card-7" onclick="handleTab(document.getElementById('dosha-tab'),'dosha')">
        <div class="dashboard-card-icon">
          <i class="fas fa-exclamation-triangle"></i>
        </div>
        <h3 class="dashboard-card-title">Dosha</h3>
        <p class="dashboard-card-description">Analysis of astrological doshas and their remedies</p>
      </div>

      <!-- Gem Suggestion -->
      <div class="dashboard-service-card dashboard-card-8" onclick="handleTab(document.getElementById('gem-tab'),'gem')">
        <div class="dashboard-card-icon">
          <i class="fas fa-gem"></i>
        </div>
        <h3 class="dashboard-card-title">Gem Suggestion</h3>
        <p class="dashboard-card-description">Personalized gemstone recommendations for your wellbeing</p>
      </div>

      <!-- Rudraksha Suggestion -->
      <div class="dashboard-service-card dashboard-card-9" onclick="handleTab(document.getElementById('rudraksha-tab'),'rudraksha')">
        <div class="dashboard-card-icon">
          <i class="fas fa-praying-hands"></i>
        </div>
        <h3 class="dashboard-card-title">Rudraksha Suggestion</h3>
        <p class="dashboard-card-description">Personalized Rudraksha bead recommendations for spiritual wellbeing</p>
      </div>


    </div>
  </section>

  <!-- Footer CTA Section -->
  <footer class="dashboard-footer-cta">
    <div class="dashboard-cta-content">
      <h2 class="dashboard-cta-title text-white">Start Your Astrological Journey</h2>
      <p class="dashboard-cta-subtitle text-white">Discover personalized insights and guidance from the ancient science of astrology</p>
      <button class="dashboard-cta-button" onclick="handleTab(document.getElementById('basic-tab'),'basic')">Get Started</button>
    </div>
  </footer>
</div>
