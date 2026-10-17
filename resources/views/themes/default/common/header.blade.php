<style>
/* ======================================
   ASTROVYRA - ULTRA MODERN HEADER v3
   Glassmorphism Dark Navy Theme
====================================== */
.av3-header * { box-sizing: border-box; }

.av3-header {
    background: linear-gradient(135deg, #0a0f2c 0%, #111936 60%, #0d1530 100%);
    position: sticky;
    top: 0;
    z-index: 9999;
    box-shadow: 0 4px 30px rgba(0, 0, 0, 0.4);
    border-bottom: 1px solid rgba(255, 255, 255, 0.06);
}

/* === TOP ROW === */
.av3-top-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 12px 40px;
    gap: 20px;
}

/* Logo */
.av3-logo {
    display: flex;
    align-items: center;
    gap: 12px;
    text-decoration: none;
    flex-shrink: 0;
}
.av3-logo img { height: 52px; filter: drop-shadow(0 0 8px rgba(245, 158, 11, 0.5)); }
.av3-logo-text { display: flex; flex-direction: column; line-height: 1; }
.av3-logo-text .name { font-size: 22px; font-weight: 800; color: #ffffff; letter-spacing: 1px; font-family: 'Georgia', serif; }
.av3-logo-text .name span { color: #f59e0b; }
.av3-logo-text .tagline { font-size: 11px; color: #f59e0b; letter-spacing: 2px; margin-top: 3px; font-style: italic; opacity: 0.85; }

/* Search */
.av3-search { flex: 1; max-width: 520px; position: relative; }
.av3-search input {
    width: 100%;
    background: rgba(255, 255, 255, 0.07);
    border: 1px solid rgba(255, 255, 255, 0.15);
    backdrop-filter: blur(10px);
    border-radius: 50px;
    padding: 10px 50px 10px 20px;
    color: #ffffff;
    font-size: 14px;
    outline: none;
    transition: all 0.3s;
}
.av3-search input::placeholder { color: rgba(255,255,255,0.45); }
.av3-search input:focus { border-color: rgba(245,158,11,0.6); background: rgba(255,255,255,0.11); }
.av3-search-btn {
    position: absolute; right: 5px; top: 50%; transform: translateY(-50%);
    background: linear-gradient(135deg, #f59e0b, #d97706);
    border: none; border-radius: 50%; width: 35px; height: 35px;
    display: flex; align-items: center; justify-content: center;
    color: white; font-size: 14px; cursor: pointer;
}

/* Right Actions */
.av3-actions { display: flex; align-items: center; gap: 10px; flex-shrink: 0; }

/* Wallet Chip */
.av3-wallet {
    display: flex; align-items: center; gap: 7px;
    background: rgba(16,185,129,0.15); border: 1px solid rgba(16,185,129,0.35);
    border-radius: 50px; padding: 7px 12px; text-decoration: none;
}
.av3-wallet:hover { background: rgba(16,185,129,0.25); }
.av3-wallet span.amount { font-size: 13px; font-weight: 700; color: #10b981; }

/* Cart */
.av3-cart {
    position: relative; width: 40px; height: 40px;
    background: rgba(255,255,255,0.07); border: 1px solid rgba(255,255,255,0.12);
    border-radius: 10px; display: flex; align-items: center; justify-content: center;
    color: #e2e8f0; font-size: 17px; text-decoration: none; cursor: pointer;
}
.av3-cart:hover { background: rgba(255,255,255,0.15); color: white; }
.av3-cart-badge {
    position: absolute; top: -6px; right: -6px;
    background: #ef4444; color: white; font-size: 10px; font-weight: 700;
    width: 18px; height: 18px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    border: 2px solid #0a0f2c;
}

/* Language Toggle Button */
.av3-lang-btn {
    display: flex; align-items: center; gap: 5px;
    border: 1px solid rgba(255,255,255,0.2);
    border-radius: 50px;
    background: rgba(255,255,255,0.07);
    padding: 7px 13px;
    color: #e2e8f0; font-size: 12px; font-weight: 600;
    cursor: pointer; transition: all 0.3s;
    white-space: nowrap;
}
.av3-lang-btn:hover { background: rgba(245,158,11,0.2); border-color: rgba(245,158,11,0.5); color: #f59e0b; }

/* Profile */
.av3-profile {
    width: 40px; height: 40px; border-radius: 50%; overflow: hidden;
    border: 2px solid rgba(245,158,11,0.5); cursor: pointer; text-decoration: none; display: block;
}
.av3-profile img { width: 100%; height: 100%; object-fit: cover; }

/* Auth Buttons */
.av3-login-btn {
    padding: 7px 16px; background: transparent;
    border: 1.5px solid rgba(255,255,255,0.3);
    border-radius: 50px; color: #e2e8f0; font-size: 13px; font-weight: 600;
    text-decoration: none; transition: all 0.3s;
}
.av3-login-btn:hover { border-color: #f59e0b; color: #f59e0b; }
.av3-signup-btn {
    padding: 7px 16px;
    background: linear-gradient(135deg, #f59e0b, #d97706);
    border: none; border-radius: 50px; color: white !important;
    font-size: 13px; font-weight: 700; text-decoration: none;
    box-shadow: 0 4px 12px rgba(245,158,11,0.3);
}
.av3-signup-btn:hover { color: white !important; }

/* Mobile Toggle */
.av3-mobile-toggle {
    width: 40px; height: 40px;
    background: rgba(255,255,255,0.07); border: 1px solid rgba(255,255,255,0.12);
    border-radius: 10px; display: none; align-items: center; justify-content: center;
    color: white; font-size: 18px; cursor: pointer;
}

/* === NAV ROW === */
.av3-nav-row {
    border-top: 1px solid rgba(255,255,255,0.06);
    background: rgba(255,255,255,0.03);
    overflow-x: auto; scrollbar-width: none;
}
.av3-nav-row::-webkit-scrollbar { display: none; }
.av3-nav-list {
    display: flex; align-items: center;
    padding: 0 30px; gap: 0; list-style: none; margin: 0;
    white-space: nowrap;
}
.av3-nav-list li { position: relative; }
.av3-nav-link {
    display: flex; align-items: center; gap: 5px;
    padding: 11px 15px;
    color: rgba(255,255,255,0.7);
    text-decoration: none; font-size: 13px; font-weight: 500;
    transition: all 0.3s;
    border-bottom: 2px solid transparent;
}
.av3-nav-link:hover { color: #fff; border-bottom-color: rgba(245,158,11,0.4); }
.av3-nav-link.active { color: #f59e0b !important; border-bottom-color: #f59e0b; }
.av3-nav-badge {
    position: absolute; top: 4px; right: 0px;
    font-size: 8px; font-weight: 800; padding: 1px 4px;
    border-radius: 3px; line-height: 1.3;
}
.av3-nav-badge.popular { background: #ef4444; color: white; }
.av3-nav-badge.trending { background: #8b5cf6; color: white; }
.av3-nav-badge.new-badge { background: #10b981; color: white; }

/* Mobile Nav */
.av3-mobile-nav {
    background: rgba(10,15,44,0.98);
    border-top: 1px solid rgba(255,255,255,0.08);
    display: none; padding: 16px 20px;
    flex-direction: column; gap: 4px;
}
.av3-mobile-nav.open { display: flex; }
.av3-mobile-nav a {
    padding: 11px 14px; color: rgba(255,255,255,0.75);
    text-decoration: none; border-radius: 10px;
    font-size: 14px; font-weight: 500; transition: all 0.2s;
    display: flex; align-items: center; gap: 10px;
}
.av3-mobile-nav a:hover { background: rgba(255,255,255,0.07); color: white; }
.av3-mobile-nav a.active { color: #f59e0b; background: rgba(245,158,11,0.1); }

/* Responsive */
@media (max-width: 992px) {
    .av3-top-row { padding: 10px 16px; }
    .av3-search { display: none; }
    .av3-wallet { display: none; }
    .av3-mobile-toggle { display: flex !important; }
    .av3-nav-row { display: none; }
    .av3-logo-text .tagline { display: none; }
}
</style>

@php
    $currentRoute = Route::currentRouteName();
@endphp

<header class="av3-header">

    <!-- TOP ROW -->
    <div class="av3-top-row">

        <!-- Logo -->
        <a href="{{ route('home') }}" class="av3-logo">
            <img src="{{ logo() }}" alt="AstroVyra">
            <div class="av3-logo-text">
                <span class="name">Astro<span>Vyra</span></span>
                <span class="tagline">✦ Your Cosmic Guide ✦</span>
            </div>
        </a>

        <!-- Search Bar -->
        <div class="av3-search">
            <input type="text" id="av3SearchInput" placeholder="Search astrologers, pujas, kundli..." onkeyup="searchByName()">
            <button class="av3-search-btn" onclick="searchByName()"><i class="fa fa-search"></i></button>
            <div id="suggestionsList" class="serch-box" style="position:absolute; top:100%; left:0; right:0; z-index:9999;"></div>
        </div>

        <!-- Right Actions -->
        <div class="av3-actions">

            @if(auth()->guard('web')->check())
            @php $authUser = auth()->guard('web')->user(); @endphp
            <!-- Wallet -->
            <a href="{{ route('user.recharge') }}" class="av3-wallet">
                <i class="fa fa-inr" style="color:#10b981; font-size:13px;"></i>
                <span class="amount">{{ $authUser->wallet ?? 0 }}</span>
            </a>
            @elseif(auth()->guard('astrologer')->check())
            @php $authAstro = auth()->guard('astrologer')->user(); @endphp
            <a href="{{ route('astrologer.earnings') }}" class="av3-wallet">
                <i class="fa fa-inr" style="color:#10b981; font-size:13px;"></i>
                <span class="amount">{{ $authAstro->wallet ?? 0 }}</span>
            </a>
            @endif

            <!-- Cart -->
            <a href="javascript:void(0)" onclick="showCart()" class="av3-cart">
                <i class="fa fa-shopping-cart"></i>
                <span class="av3-cart-badge" id="totalcartproduct">{{ cartitemcount() }}</span>
            </a>

            <!-- Language Toggle -->
            <button class="av3-lang-btn" id="av3LangToggle" onclick="av3ToggleLang()" title="Switch Language">
                <i class="fa fa-globe" style="font-size:14px;"></i>
                <span id="av3LangLabel">EN / हि</span>
            </button>

            <!-- Profile / Auth -->
            @if(auth()->guard('astrologer')->check())
                @php $authAstro = auth()->guard('astrologer')->user(); $astroImg = $authAstro->image ?? null; @endphp
                <a href="{{ route('astrologer.dashboard') }}" class="av3-profile">
                    <img src="{{ $astroImg ? $astroImg->image(80,80) : asset('/assets/img/avtar-p.avif') }}" alt="Profile">
                </a>
            @elseif(auth()->guard('web')->check())
                @php $authUser = auth()->guard('web')->user(); $userImg = $authUser->image ?? null; @endphp
                <a href="{{ route('user.dashboard') }}" class="av3-profile">
                    <img src="{{ $userImg ? $userImg->image(80,80) : asset('/assets/img/avtar-p.avif') }}" alt="Profile">
                </a>
            @else
                <a href="#" data-toggle="modal" data-target="#loginmodal" class="av3-login-btn">Login</a>
                <a href="#" data-toggle="modal" data-target="#loginmodal" class="av3-signup-btn">Sign Up</a>
            @endif

            <!-- Mobile Toggle -->
            <button class="av3-mobile-toggle" onclick="document.querySelector('.av3-mobile-nav').classList.toggle('open')">
                <i class="fa fa-bars"></i>
            </button>

        </div>
    </div>

    <!-- NAV ROW -->
    <nav class="av3-nav-row">
        <ul class="av3-nav-list">
            <li>
                <a href="{{ route('home') }}" class="av3-nav-link {{ $currentRoute == 'home' ? 'active' : '' }}">
                    🏠 Home
                </a>
            </li>
            <li>
                <a href="{{ route('panchang') }}" class="av3-nav-link {{ $currentRoute == 'panchang' ? 'active' : '' }}">
                    📅 Panchang
                </a>
            </li>
            <li>
                <a href="{{ route('horoscopes.index') }}" class="av3-nav-link {{ str_starts_with($currentRoute, 'horoscopes') ? 'active' : '' }}">
                    ⭐ Horoscope
                </a>
            </li>
            <li style="padding-right: 26px;">
                <a href="{{ route('astrologers.chatwithastrologers') }}" class="av3-nav-link {{ $currentRoute == 'astrologers.chatwithastrologers' ? 'active' : '' }}">
                    💬 Chat With Astrologers
                </a>
                <span class="av3-nav-badge popular">POPULAR</span>
            </li>
            <li style="padding-right: 26px;">
                <a href="{{ route('astrologers.talktoastrologers') }}" class="av3-nav-link {{ $currentRoute == 'astrologers.talktoastrologers' ? 'active' : '' }}">
                    📞 Talk To Astrologers
                </a>
                <span class="av3-nav-badge trending">TRENDING</span>
            </li>
            <li style="padding-right: 26px;">
                <a href="{{ route('astrologers.index', ['ai']) }}" class="av3-nav-link {{ $currentRoute == 'astrologers.index' && request('type') == 'ai' ? 'active' : '' }}">
                    🤖 AI Astrologers
                </a>
                <span class="av3-nav-badge new-badge">NEW</span>
            </li>
            <li>
                <a href="{{ route('product.handle') }}" class="av3-nav-link {{ str_starts_with($currentRoute, 'product') ? 'active' : '' }}">
                    🛍️ Astro Mall
                </a>
            </li>
            <li>
                <a href="{{ route('service.handle') }}" class="av3-nav-link {{ str_starts_with($currentRoute, 'service') ? 'active' : '' }}">
                    📄 Reports
                </a>
            </li>
            <li>
                <a href="{{ route('puja.handle') }}" class="av3-nav-link {{ str_starts_with($currentRoute, 'puja') || str_starts_with($currentRoute, 'one.to.one') ? 'active' : '' }}">
                    🪔 Puja Remedies
                </a>
            </li>
            <li>
                <a href="{{ route('kundali.index') }}" class="av3-nav-link {{ str_starts_with($currentRoute, 'kundali') ? 'active' : '' }}">
                    🔮 Kundli
                </a>
            </li>
        </ul>
    </nav>

    <!-- MOBILE NAV -->
    <div class="av3-mobile-nav">
        <div style="position:relative; margin-bottom:12px;">
            <input type="text" placeholder="Search..." style="width:100%; padding:10px 44px 10px 16px; border-radius:50px; border:1px solid rgba(255,255,255,0.2); background:rgba(255,255,255,0.07); color:#fff; outline:none; font-size:14px;">
            <button style="position:absolute;right:5px;top:50%;transform:translateY(-50%);background:#f59e0b;border:none;border-radius:50%;width:34px;height:34px;color:white;cursor:pointer;"><i class="fa fa-search"></i></button>
        </div>
        <a href="{{ route('home') }}" class="{{ $currentRoute == 'home' ? 'active' : '' }}">🏠 Home</a>
        <a href="{{ route('panchang') }}" class="{{ $currentRoute == 'panchang' ? 'active' : '' }}">📅 Panchang</a>
        <a href="{{ route('horoscopes.index') }}" class="{{ str_starts_with($currentRoute, 'horoscopes') ? 'active' : '' }}">⭐ Horoscope</a>
        <a href="{{ route('astrologers.chatwithastrologers') }}" class="{{ $currentRoute == 'astrologers.chatwithastrologers' ? 'active' : '' }}">💬 Chat With Astrologers</a>
        <a href="{{ route('astrologers.talktoastrologers') }}" class="{{ $currentRoute == 'astrologers.talktoastrologers' ? 'active' : '' }}">📞 Talk To Astrologers</a>
        <a href="{{ route('astrologers.index', ['ai']) }}" class="">🤖 AI Astrologers</a>
        <a href="{{ route('product.handle') }}" class="{{ str_starts_with($currentRoute, 'product') ? 'active' : '' }}">🛍️ Astro Mall</a>
        <a href="{{ route('service.handle') }}" class="{{ str_starts_with($currentRoute, 'service') ? 'active' : '' }}">📄 Reports</a>
        <a href="{{ route('puja.handle') }}" class="{{ str_starts_with($currentRoute, 'puja') ? 'active' : '' }}">🪔 Puja Remedies</a>
        <a href="{{ route('kundali.index') }}" class="{{ str_starts_with($currentRoute, 'kundali') ? 'active' : '' }}">🔮 Kundli</a>
    </div>

</header>

<script>
// ===== AstroVyra Language Toggle =====
var av3CurrentLang = 'en';

function av3ToggleLang() {
    if (av3CurrentLang === 'en') {
        av3SetLang('hi');
    } else {
        av3SetLang('en');
    }
}

function av3SetLang(lang) {
    av3CurrentLang = lang;
    var label = document.getElementById('av3LangLabel');
    if (lang === 'hi') {
        label.textContent = 'हिन्दी / EN';
        // Use Google Translate cookie method
        document.cookie = "googtrans=/en/hi; path=/";
        document.cookie = "googtrans=/en/hi; domain=" + window.location.hostname + "; path=/";
    } else {
        label.textContent = 'EN / हि';
        document.cookie = "googtrans=; path=/; expires=Thu, 01 Jan 1970 00:00:01 GMT";
        document.cookie = "googtrans=; domain=" + window.location.hostname + "; path=/; expires=Thu, 01 Jan 1970 00:00:01 GMT";
    }
    // Reload to apply translation
    window.location.reload();
}

// Check current lang state on load
(function() {
    var match = document.cookie.match(/googtrans=\/en\/(hi)/);
    if (match) {
        av3CurrentLang = 'hi';
        var label = document.getElementById('av3LangLabel');
        if (label) label.textContent = 'हिन्दी / EN';
    }
})();
</script>


