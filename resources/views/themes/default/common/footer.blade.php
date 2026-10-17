<style>
    /* Suggestion Box */
    .custom-suggestion-box {
        position: absolute;
        bottom: 512%;
        left: 0px;
        width: 88%;
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        max-height: 180px;
        overflow-y: auto;
        margin: 20px;
        z-index: 9999;
        padding: 6px 0;
        display: none;
        transition: all 0.3s ease;
    }

    .custom-suggestion-box h5 {
        margin: 13px;
    }

    .custom-suggestion-box.show {
        display: block;
        opacity: 1;
        transform: translateY(0);
    }

    .custom-suggestion-box div {
        padding: 10px 15px;
        cursor: pointer;
        font-size: 14px;
        color: #333;
        transition: all 0.2s ease;
    }

    .custom-suggestion-box div:hover {
        background: #f7f7f7;
        color: #d50000;
    }

    /* Scrollbar */
    .custom-suggestion-box::-webkit-scrollbar {
        width: 5px;
    }

    .custom-suggestion-box::-webkit-scrollbar-thumb {
        background: #ff3b3b;
        border-radius: 5px;
    }

    .custom-suggestion-box::-webkit-scrollbar-track {
        background: #f0f0f0;
    }

    /* Input */
    .search-bar {
        border: 1px solid #ddd;
        border-radius: 6px;
        padding: 7px 10px;
        width: 180px;
        outline: none;
        transition: all 0.2s;
    }

    .search-bar:focus {
        border-color: #ff3b3b;
        box-shadow: 0 0 4px rgba(255, 59, 59, 0.3);
    }
</style>
<!-- AstroVyra newsletter and footer -->
<style>
    .d2-footer {
        color: #ddd9ec;
        font: 12px/1.45 Roboto, Arial, sans-serif;
    }

    .d2-newsletter-area {
        padding: 20px 0 14px;
        background: #fffaf3;
    }

    .d2-top-strip {
        min-height: 92px;
        padding: 25px 40px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 20px;
        border: 1px solid rgba(255, 255, 255, .2);
        border-radius: 16px;
        color: #fff;
        background: linear-gradient(105deg, #241044 0%, #68205f 34%, #f47713 65%, #ffad15 78%, #352052 100%);
        box-shadow: 0 10px 25px rgba(41, 20, 59, .2);
        position: relative;
        overflow: hidden;
    }

    .d2-top-strip:before {
        content: "";
        position: absolute;
        inset: 0;
        opacity: .16;
        pointer-events: none;
        background-image: radial-gradient(circle at 8% 45%, transparent 0 34px, rgba(255, 255, 255, .5) 35px, transparent 37px), radial-gradient(circle at 92% 50%, transparent 0 46px, rgba(255, 255, 255, .45) 47px, transparent 49px);
    }

    .d2-newsletter-copy {
        position: relative;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .d2-newsletter-icon {
        display: grid;
        width: 42px;
        height: 42px;
        flex: 0 0 42px;
        place-items: center;
        border-radius: 50%;
        color: #4b2065;
        background: linear-gradient(140deg, #fff0bb, #ff9700);
        font-size: 18px;
    }

    .d2-top-strip h3 {
        margin: 0;
        font-size: 20px;
        font-weight: 800;
    }

    .d2-top-strip p {
        margin: 4px 0 0;
        color: rgba(255, 255, 255, .8);
        font-size: 14px;
    }

    .d2-subscribe {
        position: relative;
        display: flex;
        width: 100%;
        max-width: 400px;
        min-width: 290px;
        padding: 3px;
        border-radius: 999px;
        background: #fff;
    }

    .d2-subscribe input {
        min-width: 0;
        flex: 1;
        padding: 12px 16px;
        border: 0;
        outline: 0;
        color: #322343;
        background: transparent;
        font-size: 14px;
    }

    .d2-subscribe button {
        padding: 0 20px;
        border: 0;
        border-radius: 999px;
        color: #fff;
        background: linear-gradient(110deg, #ffb20c, #ef7900);
        font-size: 14px;
        font-weight: 700;
        cursor: pointer;
    }

    .d2-main-wrap {
        background: linear-gradient(110deg, #11102f, #1b1237 55%, #17132f);
    }

    .d2-main {
        padding: 60px 0 30px;
    }

    .d2-brand {
        padding-right: 18px;
    }

    .d2-brand-logo {
        display: flex;
        align-items: center;
        gap: 7px;
        margin: 0 0 10px;
        color: #fff;
        text-decoration: none;
    }

    .d2-brand-logo img {
        width: 46px;
        height: 46px;
        object-fit: contain;
    }

    .d2-brand-logo strong {
        display: block;
        color: #fff;
        font: 700 23px Georgia, serif;
    }

    .d2-brand-logo strong span {
        color: #ffb20c;
    }

    .d2-brand-logo small {
        display: block;
        color: #c9c3d8;
        font-size: 8px;
    }

    .d2-heading {
        margin: 0 0 24px;
        color: #fff;
        font-size: 17px;
        font-weight: 800;
    }

    .d2-links a {
        display: block;
        margin: 0 0 14px;
        color: #c8c2d7;
        font-size: 14px;
        line-height: 1.5;
        text-decoration: none;
        transition: color .2s;
    }

    .d2-links a:hover {
        color: #ffbd32;
    }

    .d2-links .row {
        --bs-gutter-x: 4px;
    }

    .d2-social-icons {
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .d2-social-icons a {
        display: inline-flex;
        width: 36px;
        height: 36px;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        color: #fff;
        background: #312452;
        text-decoration: none;
        font-size: 16px;
    }

    .d2-brand .d2-social-icons {
        gap: 6px;
    }

    .d2-brand .d2-social-icons a {
        width: 28px;
        height: 28px;
        font-size: 14px;
    }

    .d2-brand .d2-social-icons a i {
        color: #fff !important;
        line-height: 1;
    }

    .d2-social-icons a:nth-child(1) {
        background: #3157a4;
    }

    .d2-social-icons a:nth-child(2) {
        background: #d63876;
    }

    .d2-social-icons a:nth-child(3) {
        background: #e63232;
    }

    .d2-social-icons a:nth-child(4) {
        background: #27669c;
    }

    .d2-social-icons a:nth-child(5) {
        background: #191821;
    }

    .d2-bottom {
        color: #c8c2d7;
        background: #0d0b22;
        border-top: 1px solid rgba(255, 255, 255, .08);
        padding: 20px 0;
        font-size: 13px;
    }

    .d2-bottom-inner {
        min-height: 28px;
    }

    .d2-payments {
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .d2-payments img {
        height: 26px;
        width: auto;
        border-radius: 4px;
        background: #fff;
        padding: 2px 4px;
    }

    @media(max-width:767px) {
        .d2-newsletter-area {
            padding: 10px 0 12px;
        }

        .d2-top-strip {
            margin: 0 10px;
            padding: 13px;
            border-radius: 13px;
        }

        .d2-subscribe {
            width: 100%;
            max-width: none;
            min-width: 0;
        }

        .d2-main {
            padding: 18px 10px 8px;
        }

        .d2-brand {
            margin-bottom: 12px;
        }

        .d2-main > .d2-brand {
            flex: 0 0 100%;
            max-width: 100%;
            padding-right: 12px;
        }

        .d2-main>div {
            margin-bottom: 10px !important;
        }

        .d2-heading {
            margin-bottom: 12px;
            font-size: 12px;
        }

        .d2-links a {
            margin-bottom: 8px;
            font-size: 11px;
            line-height: 1.4;
        }

        .d2-bottom-inner {
            flex-direction: column;
            text-align: center;
            gap: 8px;
        }
    }
</style>

<footer class="d2-footer mt-5 hide-in-webview" id="footer-nav">
    <div class="d2-newsletter-area">
        <div class="container">
            <div class="d2-top-strip">
                <div class="d2-newsletter-copy">
                    <span class="d2-newsletter-icon"><i class="bi bi-envelope-paper-fill"></i></span>
                    <div>
                        <h3>Get Daily Free Horoscope</h3>
                        <p>Subscribe for free to receive your daily horoscope updates.</p>
                    </div>
                </div>
                <form id="newsletter" class="d2-subscribe">
                    <input id="newsEmail" type="email" name="email" placeholder="Enter your email address" required>
                    <button type="submit">Subscribe</button>
                </form>
            </div>
        </div>
    </div>

    <div class="d2-main-wrap">
        <div class="container">
            <!-- Main Footer Links -->
            <div class="d2-main row">
                <div class="col-lg-2 col-md-4 col-6 mb-3 d2-brand">
                    <a class="d2-brand-logo" href="{{ route('home') }}">
                        <img src="{{ asset('assets/img/logo.png') }}" alt="{{ config('app.name') }}">
                        <span><strong>Astro<span>Vyra</span></strong><small>Find Your Cosmic Guide</small></span>
                    </a>
                    <div class="d2-social-icons">
                        <a href="{{ fromSetting('fb_link') ?: '#' }}" target="_blank" aria-label="Facebook"><i
                                class="bi bi-facebook"></i></a>
                        <a href="{{ fromSetting('instagram_link') ?: '#' }}" target="_blank" aria-label="Instagram"><i
                                class="bi bi-instagram"></i></a>
                        <a href="{{ fromSetting('youtube_link') ?: '#' }}" target="_blank" aria-label="YouTube"><i
                                class="bi bi-youtube"></i></a>
                        <a href="{{ fromSetting('linkedin_link') ?: '#' }}" target="_blank" aria-label="LinkedIn"><i
                                class="bi bi-linkedin"></i></a>
                        <a href="#" aria-label="X"><i class="bi bi-twitter-x"></i></a>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 col-6 mb-3 d2-links">
                    <h4 class="d2-heading">Quick Links</h4>
                    <a href="{{ route('home') }}">Home</a>
                    <a href="{{ route('pages', ['about-us']) }}">About Us</a>
                    <a href="{{ route('astrologers.index', ['ai']) }}">AI Astrologers</a>
                    <a href="{{ route('service.handle') }}">Reports</a>
                    <a href="{{ route('puja.handle') }}">E-Puja</a>
                    <a href="{{ route('kundali.index') }}">Kundli</a>
                    <a href="{{ route('panchang') }}">Panchang</a>
                    <a href="{{ route('blog.index') }}">Blogs</a>
                    <a href="{{ route('contactForm') }}">Contact Us</a>
                </div>

                <div class="col-lg-3 col-md-4 col-6 mb-3 d2-links">
                    <h4 class="d2-heading">Services & Astro Mall</h4>
                    @php
                        $astrologerCategories = astrologerCategories(4);
                        $productCategories = isset($productCategories) ? $productCategories : productCategory(4);
                    @endphp
                    @foreach ($astrologerCategories as $item)
                        <a href="{{ route('astrologers.talktoastrologers', ['category_id' => $item->id]) }}">{{ $item->name }}
                            Astrologers</a>
                    @endforeach
                    @foreach ($productCategories as $item)
                        <a href="{{ route('product.handle', [$item->slug]) }}">{{ $item->name }}</a>
                    @endforeach
                    <a href="{{ route('puja.handle') }}">Group Puja</a>
                    <a href="{{ route('one.to.one.puja.handle') }}">VIP Puja</a>
                </div>

                <div class="col-lg-2 col-md-4 col-6 mb-3 d2-links">
                    <h4 class="d2-heading">Horoscope</h4>
                    @php
                        $zodiacSigns = zodiacSignsModel();
                    @endphp
                    <div class="row">
                        @foreach ($zodiacSigns as $index => $item)
                            <div class="col-6">
                                <a
                                    href="{{ route('horoscopedetail', [$item->name, 'today']) }}">{{ $item->name }}</a>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="col-lg-3 col-md-4 col-6 mb-3 d2-links">
                    <h4 class="d2-heading">Policies & Trust</h4>
                    <a href="{{ route('astrologer.login') }}">Astrologer Login</a>
                    <a href="{{ route('astrologer.register') }}">Astrologer Registration</a>
                    <a href="{{ route('pages', ['privacy-policy']) }}">Privacy Policy</a>
                    <a href="{{ route('pages', ['terms-condition']) }}">Terms & Conditions</a>
                    <a href="{{ route('pages', ['refund-and-cancellation-policy']) }}">Refund & Cancellation</a>
                    <a href="{{ route('pages', ['disclaimer']) }}">Disclaimer</a>

                </div>
            </div>
        </div>
    </div>

    <!-- Bottom Copyright -->
    <div class="d2-bottom">
        <div class="container d-flex justify-content-between align-items-center flex-wrap gap-3 d2-bottom-inner">
            <div>
                Copyright &copy; <?php echo date('Y'); ?> ASTROVYRA | All Rights Reserved | Vyntrixa
            </div>
            <div class="d2-payments">
                <img src="{{ asset('assets/img/card-1.jpg') }}" alt="Visa">
                <img src="{{ asset('assets/img/card-2.jpg') }}" alt="Mastercard">
                <img src="{{ asset('assets/img/card-3.jpg') }}" alt="Amex">
                <img src="{{ asset('assets/img/card-4.jpg') }}" alt="Discover">
            </div>
        </div>
    </div>
</footer>
<div class="sticky-buttons">
    <a href="{{ route('astrologers.chatwithastrologers') }}" class="action-btn">
        <i class="fa fa-commenting"></i>
        Chat with Astrologer
    </a>
    <a href="{{ route('astrologers.talktoastrologers') }}" class="action-btn-2">
        <i class="fa fa-volume-control-phone"></i>
        Call with Astrologer
    </a>
</div>



<footer class="mobile position-fixed fixed-bottom py-2">
    <div class="container">

        <nav class="m-nav">
            <div class="nav-box m-nav-box position-relative">
                <div id="suggestionsLists" class="custom-suggestion-box">
                    <!-- For displaying search suggestions -->
                </div>
                <ul class="nav-container m-0">
                    <li class="nav__item {{ isset($nav) && $nav == 'home' ? 'active' : '' }}">
                        <a href="{{ route('home') }}" class="nav__item-link nav__item">
                            <div class="nav__item-icon">
                                <i class="bi bi-house-door"></i>
                            </div>
                            <span class="nav__item-text">Home</span>
                        </a>
                    </li>
                    <li class="nav__item {{ isset($nav) && $nav == 'ai' ? 'active' : '' }}">
                        <a href="{{ route('astrologers.index', ['ai']) }}" class="nav__item-link">
                            <div class="nav__item-icon">
                                <i class="bi bi-robot fs-2"></i>
                            </div>
                            <span class="nav__item-text">AI Chat</span>
                        </a>
                    </li>
                    <li class="nav__item {{ isset($nav) && $nav == 'chatwithastrologers' ? 'active' : '' }}">
                        <a href="{{ route('astrologers.chatwithastrologers') }}" class="nav__item-link">
                            <div class="nav__item-icon">
                                <i class="fa-regular fa-comment-dots fs-2"></i>
                            </div>
                            <span class="nav__item-text">Chat</span>
                        </a>
                    </li>
                    <li class="nav__item {{ isset($nav) && $nav == 'talktoastrologers' ? 'active' : '' }}">
                        <a href="{{ route('astrologers.talktoastrologers') }}" class="nav__item-link">
                            <div class="nav__item-icon">
                                <i class="bi bi-telephone-forward fs-2"></i>
                            </div>
                            <span class="nav__item-text">Call</span>
                        </a>
                    </li>
                    <li onclick="showCart()" class="nav__item wallet">
                        <a href="javascript:void(0)"
                            class=" d-flex flex-column justify-centent center align-items-center" id="cart-icon">
                            <div class="nav__item-icon">
                                <i class="bi bi-cart-plus"></i>
                                <span class="count" id="totalcartproduct">{{ cartitemcount() }}</span>
                            </div>
                            <span class="nav__item-text">Shop</span>
                        </a>
                    </li>
                    @if ($user)
                        <li class="nav__item">
                            <a data-bs-toggle="offcanvas" href="#offcanvasExample" aria-controls="offcanvasExample"
                                class="d-flex flex-column justify-centent center align-items-center"
                                style="color:#2f3046;">
                                <div class="nav__item-icon">
                                    <i class="bi bi-person fs-2"></i>
                                </div>
                                <span class="nav__item-text">Profile</span>
                            </a>
                        </li>
                    @else
                        <li class="nav__item">
                            <a href="javascript:void(0)"
                                class="d-flex flex-column justify-centent center align-items-center"
                                style="color:#2f3046;" onclick="handleUserLoginModal('open')">
                                <div class="nav__item-icon">
                                    <i class="bi bi-person fs-2"></i>
                                </div>
                                <span class="nav__item-text">Profile</span>
                            </a>
                        </li>
                    @endif
                </ul>
            </div>
        </nav>
    </div>
</footer>



@push('scripts')
    <script>
        function showCart() {
            $('.minicartdata').html('');
            if ($("#sidebar-close-btn")) {
                $("#sidebar-close-btn").click();
            }
            const userId = '{{ \Session::get('cart_user_id') }}';
            $.ajax({
                type: 'POST',
                url: '{{ route('mini.cart') }}',
                data: {
                    user_id: userId,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    sessionUserId = response.user_id;
                    if (response.html) {
                        $("#subTotalCheckoutBtnDiv").removeClass("d-none");
                        $('.minicartdata').html(response.html);
                        $('#subTotalPrice').html(response.totalPrice);
                        $('#totalcartproduct').text(response.totalCount);
                        $('#totalcartproductmob').text(response.totalCount);
                    } else {
                        $("#subTotalCheckoutBtnDiv").addClass("d-none");
                        let src = $('#offcanvasWithBothOptions').attr('data-empty-cart');
                        $('.minicartdata').html(
                            `<div class="cartb-img"><img src="${src}"><h5>No Items Found!</h5><p> Your Cart items will be displayed here.</p></div>`
                            );
                        $('#subTotalPrice').html(0);
                        $('#totalcartproduct').text(0);
                        $('#totalcartproductmob').text(0);
                    }
                    if (response.hasOutOfStockProduct) {
                        $("#subTotalCheckoutBtnDiv").addClass("d-none");
                        toastr.error('Please remove out of stock item from cart to checkout.');
                    }
                    var offcanvasElement = document.getElementById('offcanvasWithBothOptions');
                    var offcanvas = new bootstrap.Offcanvas(offcanvasElement);
                    offcanvas.show();
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching cart:', error);
                }
            });
        }
        @if (Request()->has('_token'))
            $('.hide-in-webview').hide();
        @endif
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $('#newsletter').on('submit', function(e) {
                e.preventDefault();
                var email = $('#newsEmail').val();

                $.ajax({
                    url: '{{ route('newsletter') }}',
                    type: 'POST',
                    data: {
                        email: email
                    },
                    success: function(response) {
                        if (response.message) {
                            toastr.success(response.message);
                            $('#newsEmail').val('');
                        }
                    },
                    error: function(xhr, status, error) {
                        toastr.error('Invalid email!');
                    }
                });
            });
        });

        $('.nav__item').on('click', function() {
            $(this).addClass('active');
        });
    </script>
    <script type="text/javascript">
        var typingTimer;
        const suggestions = document.getElementById('suggestionsLists');

        function headerSearchEscAttrM(s) {
            return String(s == null ? '' : s).replace(/&/g, '&amp;').replace(/"/g, '&quot;').replace(/</g, '&lt;');
        }

        function headerSearchEscHtmlM(s) {
            return String(s == null ? '' : s).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
        }

        function headerSearchSuggestRowM(url, imageUrl, name) {
            var img = imageUrl || '';
            return '<a href="' + headerSearchEscAttrM(url) + '" class="header-search-row">' +
                '<img src="' + headerSearchEscAttrM(img) + '" alt="" width="32" height="32" loading="lazy">' +
                '<span>' + headerSearchEscHtmlM(name) + '</span>' +
                '</a>';
        }

        function searchByNames() {
            clearTimeout(typingTimer);
            typingTimer = setTimeout(function() {
                let query = document.getElementById('searchInputs').value.trim(); // Trim whitespace
                if (query.length >= 2) { // Show suggestions from 2 characters
                    document.getElementById('suggestionsLists').innerHTML =
                    ''; // Clear suggestions if query is too short
                    document.getElementById('suggestionsLists').style.display = 'none'; // Hide suggestions box
                    let xhr = new XMLHttpRequest();
                    xhr.open('GET', '/search-data?title=' + encodeURIComponent(query), true);
                    xhr.onload = function() {
                        if (xhr.status === 200) {
                            try {
                                // Parse the JSON response
                                let response = JSON.parse(xhr.responseText);
                                let suggestionsHtml = '';
                                let astrologers = response.astrologers;
                                let aiastrologers = response.aiastrologers;
                                let products = response.products;
                                let poojas = response.poojas;
                                let services = response.services;

                                if (astrologers && astrologers.length > 0) {
                                    suggestionsHtml +=
                                        '<h5>Astrologers</h5><ul class="header-search-suggest-ul">';
                                    astrologers.forEach(function(astrologer) {
                                        suggestionsHtml += '<li>' + headerSearchSuggestRowM(astrologer
                                            .url, astrologer.image, astrologer.name) + '</li>';
                                    });
                                    suggestionsHtml += '</ul>';
                                }

                                if (aiastrologers && aiastrologers.length > 0) {
                                    suggestionsHtml +=
                                        '<h5>Ai Astrologers</h5><ul class="header-search-suggest-ul">';
                                    aiastrologers.forEach(function(aiastrologer) {
                                        suggestionsHtml += '<li>' + headerSearchSuggestRowM(aiastrologer
                                            .url, aiastrologer.image, aiastrologer.name) + '</li>';
                                    });
                                    suggestionsHtml += '</ul>';
                                }

                                if (products && products.length > 0) {
                                    suggestionsHtml +=
                                        '<h5>Astro Mall</h5><ul class="header-search-suggest-ul">';
                                    products.forEach(function(product) {
                                        if (!product) {
                                            return;
                                        }
                                        suggestionsHtml += '<li>' + headerSearchSuggestRowM(product.url,
                                            product.image, product.name) + '</li>';
                                    });
                                    suggestionsHtml += '</ul>';
                                }

                                if (poojas && poojas.length > 0) {
                                    suggestionsHtml += '<h5>E-Pooja</h5><ul class="header-search-suggest-ul">';
                                    poojas.forEach(function(pooja) {
                                        if (!pooja) {
                                            return;
                                        }
                                        suggestionsHtml += '<li>' + headerSearchSuggestRowM(pooja.url,
                                            pooja.image, pooja.name) + '</li>';
                                    });
                                    suggestionsHtml += '</ul>';
                                }

                                if (services && services.length > 0) {
                                    suggestionsHtml += '<h5>Reports</h5><ul class="header-search-suggest-ul">';
                                    services.forEach(function(service) {
                                        if (!service) {
                                            return;
                                        }
                                        suggestionsHtml += '<li>' + headerSearchSuggestRowM(service.url,
                                            service.image, service.name) + '</li>';
                                    });
                                    suggestionsHtml += '</ul>';
                                }


                                // If there are no results at all, show a "no results" message
                                if (suggestionsHtml === '') {
                                    suggestionsHtml = '<p>No results found</p>';
                                }

                                // Update suggestions list in the DOM
                                suggestions.innerHTML = suggestionsHtml;
                                suggestions.style.display = 'block'; // Show the suggestions
                            } catch (e) {
                                console.error("Error parsing JSON response:", e);
                                document.getElementById('suggestionsLists').innerHTML =
                                    '<p>Error loading suggestions</p>';
                            }
                        } else {
                            console.error("Error: " + xhr.statusText);
                            document.getElementById('suggestionsLists').innerHTML =
                                '<p>Error loading suggestions</p>';
                        }
                    };

                    xhr.onerror = function() {
                        console.error("Request error...");
                        document.getElementById('suggestionsLists').innerHTML =
                            '<p>Error loading suggestions</p>';
                    };

                    xhr.send();
                } else {
                    document.getElementById('suggestionsLists').innerHTML = '';
                    document.getElementById('suggestionsLists').style.display = 'none';
                }
            }, 1000);
        }

        // Hide suggestions when clicking outside
        const searchInputs = document.getElementById('searchInputs');
        if (searchInputs) {
            document.addEventListener('click', function(event) {
                const suggestionsListss = document.getElementById('suggestionsLists');
                if (!suggestionsListss) {
                    return;
                }
                if (!searchInputs.contains(event.target) && !suggestionsListss.contains(event.target)) {
                    suggestionsListss.style.display = 'none';
                }
            });

            // Show suggestions when clicking inside the search box (if results exist)
            document.getElementById('searchInputs').addEventListener('click', function() {
                const suggestionsLists = document.getElementById('suggestionsLists');
                if (suggestionsLists && this.value.length >= 2 && suggestionsLists.innerHTML.trim() !== '') {
                    suggestionsLists.style.display = 'block';
                }
            });
        }
    </script>
@endpush
