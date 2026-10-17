<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NG Software | IIAG</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <!-- AOS Library CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" />
    <!-- SLick Slider cdn -->
    <link rel="stylesheet" href="{{asset('landing/ng_software/asset/css/slick.css')}}">
    <link rel="stylesheet" href="{{asset('landing/ng_software/asset/css/slick-theme.css')}}">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{asset('landing/ng_software/asset/css/style.css')}}">


</head>

<body>
    <!-- Header -->
    <header class="headerbackimg pb-5">
        <div class="container">
            <!-- Logo and Contact Us Button -->
            <div class="row align-items-center py-2">
                <div class="col-6 col-md-4">
                    <a href="/" target="_blank">
                        <img src="{{('landing/ng_software/asset/image/IIAG_New_Logo-For_Web 2.png')}}" alt="Logo" class="img-fluid">
                    </a>
                </div>


                <!-- <div class="col-6 col-md-8 text-end">
                    <button type="button" class="btn btn-outline-gold">
                        Contact Us
                    </button>
                </div> -->
            </div>
            <!-- Content and Form -->
            <div class="row align-items-center mt-4">
                <!-- Left Content -->
                <div class="col-md-6 mb-4 mb-md-0">
                    <h1>Nakshatra Gold Software: Your Simple Solution for Easy Astrology and Accurate Predictions</h1>
                    <p>Simplify your astrology practice with NG software—it’s easy to use and helps you save time while
                        providing better readings for your clients!</p>
                    <img src="{{('landing/ng_software/asset/image/iiagbook.png')}}" alt="software" class="img-fluid w-75" data-aos="fade-right"
                        data-aos-duration="1000" data-aos-delay="200">
                </div>
                <!-- Right Form -->
                <div class="col-md-6" id="demoForm">
                    <div class="demo-form-container p-4 rounded" >
                        <h3 class="mb-3 text-center">Get a Free Demo</h3>
                        <form>
                            <div class="mb-2">
                                <input type="text" class="form-control" placeholder="Enter Your Name *" required>
                            </div>
                            <div class="mb-2">
                                <input type="email" class="form-control" placeholder="Enter Your Email *" required>
                            </div>
                            <div class="mb-2">
                                <div class="input-group">
                                    <span class="input-group-text bg-white">
                                        <img src="https://upload.wikimedia.org/wikipedia/en/4/41/Flag_of_India.svg"
                                            alt="India Flag" style="width: 20px; height: auto;">
                                        <span class="ms-2">+91</span>
                                    </span>
                                    <input type="tel" class="form-control" placeholder="Phone Number">
                                </div>
                            </div>
                            <div class="mb-2">
                                <textarea class="form-control" rows="4" placeholder="Description"></textarea>
                            </div>
                            <div class="text-center pt-3">
                                <button type="submit" class="btn form_btn-outline-gold px-4">
                                    Book a Demo
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <!-- End Header -->

    <!-- Trusted Section -->
    <section class="trusted-section">
        <div class="container">
            <h2>Trusted by 1000+ Astrologers across the country</h2>
            <div class="row justify-content-center">
                <!-- Card 1 -->
                <div class="col-6 col-md-3">
                    <div class="trusted-card">
                        <img src="{{('landing/ng_software/asset/image/counter1.png')}}" alt="Total Predictions">
                      <div class="d-flex justify-content-center">
                          <h5 class="counter text-center" data-target="7"></h5><span class="ms-2">Lakh+</span>
                      </div>
                        <p>Total Predictions</p>
                    </div>
                </div>
                <!-- Card 2 -->
                <div class="col-6 col-md-3">
                    <div class="trusted-card">
                        <img src="{{('landing/ng_software/asset/image/counter2.png')}}" alt="Accuracy Rate">
                          <div class="d-flex justify-content-center">
                        <h5 class="counter" data-target="99"></h5><span>%</span>
                      </div>
                        <p>Accuracy Rate</p>
                    </div>
                </div>
                <!-- Card 3 -->
                <div class="col-6 col-md-3">
                    <div class="trusted-card">
                        <img src="{{('landing/ng_software/asset/image/counter3.png')}}" alt="Features">
                        <div class="d-flex justify-content-center">
                        <h5 class="counter" data-target="20"></h5><span>+</span></div>
                        <p>Features</p>
                    </div>
                </div>
                <!-- Card 4 -->
                <div class="col-6 col-md-3">
                    <div class="trusted-card">
                        <img src="{{('landing/ng_software/asset/image/counter4.png')}}" alt="Happy Clients">
                          <div class="d-flex justify-content-center">
                        <h5 class="counter" data-target="1200"></h5><span>+</span>
                      </div>
                        <p>Happy Clients</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End Trusted Section -->
    <!-- Artificial-section -->
    <section class="Artificial-section">
        <div class="container">
            <h2>Artificial Intelligence based Software</h2>
            <div class="row justify-content-center">
                <!-- Card 1 -->
                <div class="col-12 col-sm-6 col-md-4">
                    <div class="Artificial-card">
                        <img src="{{('landing/ng_software/asset/image/timsave.png')}}" alt="Total Predictions">
                        <h5>Time Saving</h5>
                        <p>Save valuable time on your routine tasks, giving you the freedom to focus on your clients and provide them with the insights they seek.</p>
                    </div>
                </div>
                <!-- Card 2 -->
                <div class="col-12 col-sm-6 col-md-4">
                    <div class="Artificial-card">
                        <img src="{{('landing/ng_software/asset/image/easyuse.png')}}" alt="Accuracy Rate">
                        <h5>Easy To Use</h5>
                        <p>Our software is designed to be user-friendly, making it easy for you to navigate through features and access the tools you need without any hassle.</p>
                    </div>
                </div>
                <!-- Card 3 -->
                <div class="col-12 col-sm-6 col-md-4">
                    <div class="Artificial-card boxborder">
                        <img src="{{('landing/ng_software/asset/image/suppty.png')}}" alt="Features">
                        <h5>Fast Support</h5>
                        <p>Boost your productivity with tools that help you work faster and more efficiently, helping you to get more done in less time.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

<!-- END Artificial-section -->

    <!-- About NG Software -->
    <section class="About-NG-Software pb-5 pt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center">
                    <h2 class="text-white">About NG Software</h2>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row pt-3">
                <div class="col-md-6 videomain">
                    <div class="pt-3 videochild">
                        <iframe width="100%" height="315"
                            src="https://www.youtube.com/embed/KBrJtV9yoSU?si=_GkMcUMe8D7KIqEz"
                            title="YouTube video player" frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                            referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
                    </div>
                </div>
                <div class="col-md-6">
                    <div>
                        <p class="text-white text-center pt-3">Nakshatra Gold Software, developed by IIAG Jyotish
                            Sansthan under the guidance of Dr. Yagya Dutt Sharma Ji, is grounded in the principles of KP
                            (Krishnamurti Paddhati). This software has been tailored to meet the demands of today’s
                            fast-paced world. Unlike other astrology software on the market, Nakshatra Gold allows
                            individuals with little to no background in astrology to make meaningful predictions about
                            events in their lives.</p>
                        <div class="text-center pt-4 ngbtn">
                          <a href="#demoForm">  <button type="button" class="btn form_btn-outline-gold px-4">
                                Book a Demo
                            </button></a
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- END About NG Software -->


    <!-- Nakshatra Gold Software Feature -->
    <div class="container mt-4 mb-4">
        <div class="Nakshatra_Gold text-center ">
            <h1>Nakshatra Gold Software Features</h1>
        </div>
        <div class="row justify-content-center text-center">
            <!-- Card 1 -->
            <div class="col-6 col-md-4">
                <div class=" Nakshatra_Gold_card">
                    <img src="{{('landing/ng_software/asset/image/Horary Chart.png')}}" alt="Total Predictions">
                    <h5>Horary Chart</h5>
                    <p>The Horary Chart is a key highlight of this software, offering unique and precise insights for
                        users</p>
                </div>
            </div>
            <!-- Card 2 -->
            <div class="col-6 col-md-4">
                <div class="Nakshatra_Gold_card" >
                    <img src="{{('landing/ng_software/asset/image/Transit.png')}}" alt="Accuracy Rate">
                    <h5>Transit</h5>
                    <p>The best feature of this software is that you can run the transit seamlessly from 1 second to 1 year.</p>
                </div>
            </div>
            <!-- Card 3 -->
            <div class="col-6 col-md-4">
                <div class="Nakshatra_Gold_card leftbox">
                    <img src="{{('landing/ng_software/asset/image/Ruling Planet.png')}}" alt="Features">
                    <h5>Ruling Planet</h5>
                    <p>With the help of the Ruling Planet, you can accurately determine the timing and likelihood of
                        events.</p>
                </div>
            </div>


        </div>
        <div class="row justify-content-center text-center">
            <!-- Card 1 -->
            <div class="col-6 col-md-4">
                <div class="Nakshatra_Gold_card rightbox">
                    <img src="{{('landing/ng_software/asset/image/Winner Finder.png')}}" alt="Total Predictions">
                    <h5>Winner Finder</h5>
                    <p>Any kind of defeat win can be easily predicted through the software.</p>
                </div>
            </div>
            <!-- Card 2 -->
            <div class="col-6 col-md-4">
                <div class="Nakshatra_Gold_card">
                    <img src="{{('landing/ng_software/asset/image/Dasha Promise.png')}}" alt="Accuracy Rate">
                    <h5>Dasha Promise</h5>
                    <p>Through the software, you can determine in which case will an event occur.</p>
                </div>
            </div>
            <!-- Card 3 -->
            <div class="col-6 col-md-4">
                <div class="Nakshatra_Gold_card">
                    <img src="{{('landing/ng_software/asset/image/Bhav Promise.png')}}" alt="Features">
                    <h5>Bhav Promise</h5>
                    <p>Any person can check incidents and circumstances about to occur in their lives.</p>
                </div>
            </div>

        </div>
    </div>

    <!-- ENd Nakshatra Gold Software Feature -->


    <!-- youtube video section -->
    <section class="NGyoutubevideo pb-5 mb-5">
        <!-- <iframe src="https://www.youtube.com/embed/7eYA1MxNr1M" width="100%" height="400"></iframe> -->
        <div class="container pb-5">
            <div class="row">
                <div class="col-md-12 text-center pt-4 ">
                    <h2>NG Software Video</h2>
                    <p>Experience the power of our NG software with a personalized demo session! Discover how our
                        innovative features can streamline your workflow and enhance productivity.</p>
                </div>
            </div>

            <!-- Video Slick Slider -->
            <div class="youtubevideo text-center">
                <!-- Video 1 -->
                <div class="p-2">
                    <div class="embed-responsive embed-responsive-16by9">
                        <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/8fmACgxN4xE?si=RvNPJVPNh6r8J42N"
                            allowfullscreen></iframe>
                    </div>
                </div>

                <!-- Video 2 -->
                <div class="p-2">
                    <div class="embed-responsive embed-responsive-16by9">
                        <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/a0i8Shcj2JI?si=Uh38CzlaemmoqGbi"
                            allowfullscreen></iframe>
                    </div>
                </div>
                <!-- Video 3 -->
                <div class="p-2">
                    <div class="embed-responsive embed-responsive-16by9">
                        <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/SrFGunK9_z0?si=4p5roZkudH25--mA"
                            allowfullscreen></iframe>
                    </div>
                </div>
                <div class="p-2">
                    <div class="embed-responsive embed-responsive-16by9">
                        <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/qaesXEzWyOw?si=cedfsxCD8UPvTeQh"
                        allowfullscreen></iframe>
                    </div>
                </div>
                <!-- More videos can be added in the same way -->
            </div>
            <!-- video end -->
        </div>
    </section>
    <!-- End youtube video section -->





    <!-- CTA -->
    <section class="background mt-5 mb-5">
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center pt-4 pb-4">
                    <h2>Are you interested in a Free Demo Session of our NG software?</h2>
                    <p>Experience the power of our NG software with a personalized demo session! Discover how our
                        innovative features can streamline your workflow and enhance productivity.</p>
                    <div class="text-center pt-3">

                      <a href="#demoForm">  <button type="button" class="btn btn-outline-gold" id="scrollToForm">
                            Book a Demo
                        </button></a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!--END  CTA -->

    <!-- testimonials video section -->
    <section class="testimonials">
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center pt-3">
                    <h2>Testimonials</h2>
                </div>
            </div>

            <!-- Video Slick Slider -->
            <div class=" testimonialsslider">
                <!-- Video 1 -->
                <div class="p-2">
                    <div class="card shadow">

                        <img src="{{('landing/ng_software/asset/image/sof.png')}}" alt="Software for professionals" class="w-25 d-block m-auto">
                        <div class="card-body">
                            <h3 class="title text-center">
                                Software for professionals
                            </h3>
                            <p class="discription">
                              Great astrology software for professionals! Nakshatra Gold offers clear and precise predictions, it is a fantastic tool
                            </p>
                           <div class="text-center">
                            <h4 class="fw-bold title">
                              Darshan
                            </h4>
                            <span>Vedic Astrologer</span>
                           </div>
                        </div>

                    </div>
                </div>
                <!-- Video 2 -->
                <div class="p-2">
                    <div class="embed-responsive embed-responsive-16by9">
                        <div class="card shadow">

                            <img src="{{('landing/ng_software/asset/image/Dilip.png')}}" alt="Detailed Insights" class="w-25 d-block m-auto">
                            <div class="card-body">
                                <h3 class="title text-center">
                                    Detailed Insights
                                </h3>
                                <p class="discription">
                                    This software has been a game-changer for my astrology practice, offering detailed insights and accurate predictions
                                </p>
                                <div class="text-center">
                                    <h4 class="fw-bold title">
                                        Dilip Rawal
                                    </h4>
                                    <span>KP Astrologer</span>
                                   </div>

                            </div>

                        </div>
                    </div>
                </div>
                <!-- Video 3 -->
                <div class="p-2">
                    <div class="embed-responsive embed-responsive-16by9">
                        <div class="card shadow">

                            <img src="{{('landing/ng_software/asset/image/patil.png')}}" alt="Easy to Use" class="w-25 d-block m-auto">
                            <div class="card-body">
                                <h3 class="title text-center">
                                    Easy to Use
                                </h3>
                                <p class="discription">
                                    Nakshatra Gold is easy to use and incredibly accurate, making my astrology practice more efficient and reliable
                                </p>
                                <div class="text-center">
                                    <h4 class="fw-bold title">
                                        Vijyanand Patil
                                    </h4>
                                    <span>KP Astrologer</span>
                                   </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="p-2">
                    <div class="embed-responsive embed-responsive-16by9">
                        <div class="card shadow">

                            <img src="{{('landing/ng_software/asset/image/test-1.png')}}" alt="NG Software for Accurate Prediction" class="w-25 d-block m-auto">
                            <div class="card-body">
                                <h3 class="title text-center">
                                    NG Software for Accurate Prediction
                                </h3>
                                <p class="discription">
                                    Nakshatra Gold is an excellent astrology
                                    software for accurate predictions. It s a must
                                    have tool for any professional astrologer.
                                </p>
                                <div class="text-center">
                                    <h4 class="fw-bold title">
                                        PR Pattnaik
                                    </h4>
                                    <span>KP Astrologer</span>
                                   </div>
                            </div>

                        </div>
                    </div>
                </div>
                <!-- More videos can be added in the same way -->
            </div>
            <!-- video end -->
        </div>
    </section>
    <!-- End testimonials video section -->






    <!-- Try NG software -->
    <div class="container-fluid bg-purple text-white py-5 mb-5">
        <div class="container">
            <div class="row align-items-center">
                <!-- Left Text Section -->
                <div class="col-md-6">
                    <h2 class="fw-bold">
                        NG Software : Your All-in-One Tool for Astrology, Predictions, Matchmaking, and Kundli Creation.
                    </h2>
                    <p class="mt-3">
                        Experience the power of NG Software, designed specifically for astrologers! With this
                        user-friendly tool, you can effortlessly create detailed Kundlis, provide accurate predictions,
                        and enhance your matchmaking services.
                    </p>
                </div>

                <!-- Right Card Section -->
                <div class="col-md-6">
                    <div class="card p-4 rounded-3 formcardtry">
                        <h4 class="text-center fw-bold mb-4">Try NG Software Today!</h4>
                        <ul class="list-unstyled">
                            <li class="d-flex align-items-center mb-2">
                                <i class="bi bi-shield-fill-check me-3 icon-large"></i>
                                Initial Consultation
                            </li>
                            <li class="d-flex align-items-center mb-2">
                                <i class="bi bi-shield-fill-check me-3 icon-large"></i>
                                Demo Sessions
                            </li>
                            <li class="d-flex align-items-center mb-2">
                                <i class="bi bi-shield-fill-check me-3 icon-large"></i>
                              Life time access in just 26000/-
                            </li>
                            <li class="d-flex align-items-center mb-2">
                                <i class="bi bi-shield-fill-check me-3 icon-large"></i>
                                Unlimited Predictions
                            </li>
                            <li class="d-flex align-items-center mb-2">
                                <i class="bi bi-shield-fill-check me-3 icon-large"></i>
                                Advanced Technology
                            </li>
                            <li class="d-flex align-items-center mb-2">
                                <i class="bi bi-shield-fill-check me-3 icon-large"></i>
                                User Friendly
                            </li>

                            <li class="d-flex align-items-center mb-2">
                                <i class="bi bi-shield-fill-check me-3 icon-large"></i>
                                Unlimited Report Downloading
                            </li>
                            <li class="d-flex align-items-center mb-2">
                                <i class="bi bi-shield-fill-check me-3 icon-large"></i>
                                24*7 Customer Support
                            </li>
                        </ul>

                        <div class="text-center mt-4">
                          <a href="#demoForm">  <button type="button" class="btn btn-outline-gold">
                                Book a Demo Now
                            </button></a>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>
    <!--END Try NG software -->



    <!-- FAQ -->

    <div class="container FAQ">
        <div class="row">
            <div class="col-md-12">
                <h2 class="text-center text-gold mb-4">FAQ</h2>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="accordion" id="accordionPanelsStayOpenExample">
                    <!-- First Accordion Item -->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="panelsStayOpen-headingOne">
                            <button class="accordion-button accordionbtn" type="button" data-bs-toggle="collapse"
                                data-bs-target="#panelsStayOpen-collapseOne" aria-expanded="true"
                                aria-controls="panelsStayOpen-collapseOne">
                                What is the cost of Nakshatra Gold software?
                            </button>
                        </h2>
                        <div id="panelsStayOpen-collapseOne" data-bs-parent="#accordionPanelsStayOpenExample" class="accordion-collapse collapse show"
                            aria-labelledby="panelsStayOpen-headingOne">
                            <div class="accordion-body">
                                <strong>To know the cost of Nakshatra Gold software, please contact our team. Pricing varies
                                    based on different packages and features, so our team can provide you with the best
                                    option to suit your needs.
                                </strong>
                            </div>
                        </div>
                    </div>

                    <!-- Second Accordion Item -->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="panelsStayOpen-headingTwo">
                            <button class="accordion-button accordionbtn collapsed" type="button"
                                data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseTwo"
                                aria-expanded="false" aria-controls="panelsStayOpen-collapseTwo">
                                How do astrologers make predictions?
                            </button>
                        </h2>
                        <div id="panelsStayOpen-collapseTwo" data-bs-parent="#accordionPanelsStayOpenExample" class="accordion-collapse collapse"
                            aria-labelledby="panelsStayOpen-headingTwo">
                            <div class="accordion-body">
                                <strong>In this software, all the questions of astrology have been answered based on
                                    Indian astrology, Krishnamurthy system, and Nadi Astrology. The main purpose of
                                    Nakshatra Gold software is to reduce the misconceptions spread in society and to
                                    inform the common man about Astrology.</strong>
                            </div>
                        </div>
                    </div>

                    <!-- Third Accordion Item -->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="panelsStayOpen-headingThree">
                            <button class="accordion-button accordionbtn collapsed" type="button"
                                data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseThree"
                                aria-expanded="false" aria-controls="panelsStayOpen-collapseThree">
                                Can I get astrology software in different languages?
                            </button>
                        </h2>
                        <div id="panelsStayOpen-collapseThree" data-bs-parent="#accordionPanelsStayOpenExample" class="accordion-collapse collapse"
                            aria-labelledby="panelsStayOpen-headingThree">
                            <div class="accordion-body">
                                <strong>Yes, you can get astrology software in different languages. Our software is available
                                    in two languages: Hindi and English, allowing you to use it comfortably in your preferred
                                    language.</strong>
                            </div>
                        </div>
                    </div>

                    <!-- Fourth Accordion Item -->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="panelsStayOpen-headingFour">
                            <button class="accordion-button accordionbtn collapsed" type="button"
                                data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseFour"
                                aria-expanded="false" aria-controls="panelsStayOpen-collapseFour">
                                Can I get a Prediction without DOB information?
                            </button>
                        </h2>
                        <div id="panelsStayOpen-collapseFour" data-bs-parent="#accordionPanelsStayOpenExample" class="accordion-collapse collapse"
                            aria-labelledby="panelsStayOpen-headingFour">
                            <div class="accordion-body">
                                <strong>Yes, our software can provide predictions even without date of birth information. It
                                    offers tools that allow for accurate predictions using alternative methods, making it
                                    versatile for various astrological needs.</strong>
                            </div>
                        </div>
                    </div>

                    <!-- Fifth Accordion Item -->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="panelsStayOpen-headingFive">
                            <button class="accordion-button accordionbtn collapsed" type="button"
                                data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseFive"
                                aria-expanded="false" aria-controls="panelsStayOpen-collapseFive">
                                What are the best jyotish apps for PC?
                            </button>
                        </h2>
                        <div id="panelsStayOpen-collapseFive" data-bs-parent="#accordionPanelsStayOpenExample" class="accordion-collapse collapse"
                            aria-labelledby="panelsStayOpen-headingFive">
                            <div class="accordion-body">
                                <strong>Nakshatra Gold Software is one of the best astrology software options for PC, offering
                                    a comprehensive suite of tools for accurate predictions, Kundli creation, matchmaking,
                                    and detailed chart analysis.</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Footer Section -->
    <section class="footer-section mt-5">
        <div class="container pt-4">
            <div class="row align-items-center">
                <!-- Left Logo -->
                <div class="col-6 d-flex align-items-center">
                    <a href="/" target="_blank">
                        <img src="{{('landing/ng_software/asset/image/IIAG_New_Logo-For_Web 2.png')}}" alt="IIAG Logo" class="footer-logo">
                    </a>
                </div>

                <!-- Right Button & Links -->
                <div class="col-6 d-flex justify-content-end">
                  <a href="#demoForm"> <button type="button" class="btn form_btn-outline-gold_footer px-4">
                        Book a Demo
                    </button></a>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-6">
                    <span class="ms-2 footer-text">IIAG JYOTISH SANSTHAN &copy; 2024. All Rights Reserved.</span>
                </div>
                <div class="col-6">
                    <div class="footer-links mt-2 text-end">
                        <a href="/terms-conditions" class="footer-link me-3">Terms & Conditions</a>
                        <a href="/privacy-policy" class="footer-link">Privacy Policy</a>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- Call to action -->
    <ul class="menu bottomRight">
        <li class="share top ">
            <!-- Bootstrap share icon -->
            <i class="bi bi-chat-left-dots-fill mes"></i>
            <ul class="submenu">
                <li>
                    <a href="tel:+91 8800 850 853" class="call">
                        <!-- Bootstrap Telephone icon -->
                        <i class="bi bi-telephone"></i>
                    </a>
                </li>
                <li>
                    <a href="https://wa.link/ktpipd" target="_blank" class="whatsapp">
                        <!-- Bootstrap WhatsApp icon -->
                        <i class="bi bi-whatsapp"></i>
                    </a>
                </li>
            </ul>
        </li>
    </ul>



</body>

<script type="text/javascript" src="//code.jquery.com/jquery-1.11.0.min.js"></script>
<script type="text/javascript" src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
<script type="text/javascript" src="{{asset('landing/ng_software/asset/js/slick.min.js')}}"></script>
<script type="text/javascript">
    $('.youtubevideo').slick({
        dots: false,
        arrows: true, // Enable navigation arrows
        slidesToShow: 3, // Default: Show 3 slides
        slidesToScroll: 1,
        autoplay: true,
        autoplaySpeed: 2000,
        responsive: [
            {
                breakpoint: 768, // For devices smaller than 768px
                settings: {
                    slidesToShow: 1, // Show 1 slide on mobile
                    slidesToScroll: 1
                }
            },
            {
                breakpoint: 992, // For devices smaller than 992px (tablets)
                settings: {
                    slidesToShow: 2, // Show 2 slides on tablets
                    slidesToScroll: 1
                }
            }
        ]
    });
</script>
<script type="text/javascript">
    $('.testimonialsslider').slick({
        dots: true,               // Enable dots navigation
        arrows: false,            // Disable arrows
        slidesToShow: 3,          // Show 3 slides on larger screens
        slidesToScroll: 1,        // Scroll 1 slide at a time
        autoplay: true,
        autoplaySpeed: 2000,
        responsive: [
            {
                breakpoint: 768,  // For screens smaller than 768px
                settings: {
                    slidesToShow: 1,  // Show 1 slide on mobile
                    slidesToScroll: 1, // Scroll 1 slide at a time
                }
            }
        ]
    });
</script>
<!-- AOS Library JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
<script>
    AOS.init();
</script>
<!-- Include jQuery -->
<!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script src="{{asset('landing/ng_software/asset/js/Script.js')}}"></script>

<!-- FontAwesome (For Arrow Icons) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js"></script>
<script>
function animateCounters() {
    const counters =
        document
            .querySelectorAll(".counter");
    counters
        .forEach((counter, index) => {
            const target =
                parseInt(counter
                    .getAttribute(
                        "data-target")
                );
            const duration = 1000;
            const step =
                Math.ceil(
                    (target / duration) * 15
                );
            let current = 0;

            const updateCounter = () => {
                current += step;
                if (current <= target) {
                    counter
                        .innerText = current;
                    requestAnimationFrame(updateCounter);
                } else {
                    counter.innerText = target;
                }
            };

            setTimeout(() => {
                updateCounter();
            }, index * 1000);
            // Delay each counter by 1 second
        });
}

animateCounters();
</script>


</html>
