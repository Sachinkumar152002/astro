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
    <link href="https://fonts.googleapis.com/css2?family=Marcellus&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
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
                        <img src="/assets/img/logo.png" alt="Logo" class="img-fluid" style="width: 180px;">
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
                <div class="col-md-6 mb-4 mb-md-0 text-center text-md-start">
                    <h1>Nakshtra Gold Software: Your Simple Solution for Easy Astrology and Accurate Predictions</h1>
                    <p class="">Simplify your astrology practice with NG software—it’s easy to use and helps you save time while
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
            <h2>Trusted by 2000+ Astrologers across the country</h2>
            <div class="row justify-content-center">
                <!-- Card 1 -->
                <div class="col-6 col-md-3">
                    <div class="trusted-card">
                        <img src="{{('landing/ng_software/asset/image/counter1.png')}}" alt="Total Predictions">
                      <div class="d-flex justify-content-center">
                          <h5 class="counter text-center" data-target="10"></h5><span class="ms-2">Lakh+</span>
                      </div>
                        <p class="h20">Total Predictions</p>
                    </div>
                </div>
                <!-- Card 2 -->
                <div class="col-6 col-md-3">
                    <div class="trusted-card">
                        <img src="{{('landing/ng_software/asset/image/counter2.png')}}" alt="Accuracy Rate">
                          <div class="d-flex justify-content-center">
                        <h5 class="counter" data-target="99"></h5><span>%</span>
                      </div>
                        <p class="h20">Accuracy Rate</p>
                    </div>
                </div>
                <!-- Card 3 -->
                <div class="col-6 col-md-3">
                    <div class="trusted-card">
                        <img src="{{('landing/ng_software/asset/image/counter3.png')}}" alt="Features">
                        <div class="d-flex justify-content-center">
                        <h5 class="counter" data-target="20"></h5><span>+</span></div>
                        <p class="h20">Features</p>
                    </div>
                </div>
                <!-- Card 4 -->
                <div class="col-6 col-md-3">
                    <div class="trusted-card">
                        <img src="{{('landing/ng_software/asset/image/counter4.png')}}" alt="Happy Clients">
                          <div class="d-flex justify-content-center">
                        <h5 class="counter" data-target="3200"></h5><span>+</span>
                      </div>
                        <p class="h20">Happy Clients</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End Trusted Section -->
    <!-- Artificial-section -->
    <section class="Artificial-section">
        <div class="container">
            <h2>Auto predictive & Calculation based Software</h2>
            <div class="row justify-content-center">
                <!-- Card 1 -->
                <div class="col-12 col-sm-6 col-md-4">
                    <div class="Artificial-card">
                        <!-- <img src="{{('landing/ng_software/asset/image/timsave.png')}}" alt="Total Predictions"> -->
                        <i class="bi bi-alarm icon-text"></i>
                        <h5>Time Saving</h5>
                        <p>Save valuable time on your routine tasks, giving you the freedom to focus on your clients and provide them with the insights they seek.</p>
                    </div>
                </div>
                <!-- Card 2 -->
                <div class="col-12 col-sm-6 col-md-4">
                    <div class="Artificial-card">
                        <!-- <img src="{{('landing/ng_software/asset/image/easyuse.png')}}" alt="Accuracy Rate"> -->
                        <i class="bi bi-emoji-sunglasses icon-text"></i>
                        <h5>Easy To Use</h5>
                        <p>Our software is designed to be user-friendly, making it easy for you to navigate through features and access the tools you need without any hassle.</p>
                    </div>
                </div>
                <!-- Card 3 -->
                <div class="col-12 col-sm-6 col-md-4">
                    <div class="Artificial-card boxborder">
                        <!-- <img src="{{('landing/ng_software/asset/image/suppty.png')}}" alt="Features"> -->
                        <i class="bi bi-clipboard2-data icon-text"></i>
                        <h5>Increasing Productivity</h5>
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

                            <div class="plyr__video-embed" id="player"  style="border-radius: 10px;">
                        <iframe
                          src="https://www.youtube.com/embed/lf53SIFOeks?si=vzfRolPa2-0vC8te?origin=https://plyr.io&amp;iv_load_policy=3&amp;modestbranding=1&amp;playsinline=1&amp;showinfo=0&amp;rel=0&amp;enablejsapi=1"
                          allowfullscreen
                          allowtransparency
                          allow="autoplay" class="iframe_slider"
                        ></iframe>
                      </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div>
                        <p class="text-white text-center pt-3">Nakshtra Gold Software, developed by IIAG Jyotish
                            Sansthan under the guidance of Dr. Yagya Dutt Sharma Ji, is grounded in the principles of KP
                            (Krishnamurti Paddhati). This software has been tailored to meet the demands of today’s
                            fast-paced world. Unlike other astrology software on the market, Nakshtra Gold allows
                            individuals with little to no background in astrology to make meaningful predictions about
                            events in their lives.</p>
                        <div class="text-center pt-4 ngbtn">
                          <a href="#demoForm">  <button type="button" class="btn form_btn-outline-gold px-4" style="    border-radius: 50px;">
                                Book a Demo
                            </button></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- END About NG Software -->


    <!-- Nakshtra Gold Software Feature -->
    <div class="container mt-4 mb-4">
        <div class="Nakshtra_Gold text-center ">
            <h1>Nakshtra Gold Software Features</h1>
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

    <!-- ENd Nakshtra Gold Software Feature -->


    <!-- youtube video section -->
    <section class="NGyoutubevideo">
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
                        <iframe class="embed-responsive-item" src="https://www.youtube-nocookie.com/embed/YcT7OQ2T7Qc?si=l5patnucf6vsmEEx&amp;controls=0"
                            allowfullscreen></iframe>
                    </div>
                </div>

                <!-- Video 2 -->
                <div class="p-2">
                    <div class="embed-responsive embed-responsive-16by9">
                        <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/VoDL_EWBcg8?si=zSO3jpVe84EZJF4S"
                            allowfullscreen></iframe>
                    </div>
                </div>
                <!-- Video 3 -->
                <div class="p-2">
                    <div class="embed-responsive embed-responsive-16by9">
                        <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/0rNKfyua0Ks?si=qbGgdKGJhWTisdlh"
                            allowfullscreen></iframe>
                    </div>
                </div>
                <div class="p-2">
                    <div class="embed-responsive embed-responsive-16by9">
                        <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/WjZ-cIRdumI?si=E3_wTrXPRl9cOQgL"
                        allowfullscreen></iframe>
                    </div>
                </div>
                <div class="p-2">
                    <div class="embed-responsive embed-responsive-16by9">
                        <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/Za455HOrJyI?si=8dJlOQziaS6NP9Pk"
                        allowfullscreen></iframe>
                    </div>
                </div>
                <div class="p-2">
                    <div class="embed-responsive embed-responsive-16by9">
                        <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/cB0uiW0dwsk?si=37XiB9my_OTEGBfz"
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
    <section class="testimonials pb-5">
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
                              Great astrology software for professionals! Nakshtra Gold offers clear and precise predictions, it is a fantastic tool
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
                                    Nakshtra Gold is easy to use and incredibly accurate, making my astrology practice more efficient and reliable
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
                                    Nakshtra Gold is an excellent astrology
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
                              Life time access
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
    <div class="container ac-box">
        <div class="row">
            <div class="col-md-12">
                <div class="accordion" id="accordionPanelsStayOpenExample">
                    <!-- First Accordion Item -->
                    <div class="accordion-item mb-3">
                        <h2 class="accordion-header" id="panelsStayOpen-headingOne">
                            <button class="accordion-button accordionbtn" type="button" data-bs-toggle="collapse"
                                data-bs-target="#panelsStayOpen-collapseOne" aria-expanded="true"
                                aria-controls="panelsStayOpen-collapseOne">
                                What is the cost of Nakshtra Gold software?
                            </button>
                        </h2>
                        <div id="panelsStayOpen-collapseOne" data-bs-parent="#accordionPanelsStayOpenExample" class="accordion-collapse collapse show"
                            aria-labelledby="panelsStayOpen-headingOne">
                            <div class="accordion-body">
                                <strong>To know the cost of Nakshtra Gold software, please contact our team. Pricing varies
                                    based on different packages and features, so our team can provide you with the best
                                    option to suit your needs.
                                </strong>
                            </div>
                        </div>
                    </div>

                    <!-- Second Accordion Item -->
                    <div class="accordion-item mb-3">
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
                                    Nakshtra Gold software is to reduce the misconceptions spread in society and to
                                    inform the common man about Astrology.</strong>
                            </div>
                        </div>
                    </div>

                    <!-- Third Accordion Item -->
                    <div class="accordion-item mb-3">
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
                    <div class="accordion-item mb-3">
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
                    <div class="accordion-item mb-3">
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
                                <strong>Nakshtra Gold Software is one of the best astrology software options for PC, offering
                                    a comprehensive suite of tools for accurate predictions, Kundli creation, matchmaking,
                                    and detailed chart analysis.</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <footer class="backfoot">
    <div class="container">
      <div class="row">
        <div class="text-center">
          <a href="#demoForm" class="btn btn-outline-gold2  animated-shimmer-button new-btn">
            Book a Demo
        </a>
        </div>
      </div>
    </div>
    </footer>

    <ul class="menu bottomRight">
        <li class="share top ">
            <!-- Bootstrap share icon -->
            <i class="bi bi-chat-left-dots-fill mes"></i>
            <ul class="submenu">
                <li>
                    <a href="tel:+1234567890" class="call">
                        <!-- Bootstrap Telephone icon -->
                        <i class="bi bi-telephone"></i>
                    </a>
                </li>
                <li>
                    <a href="https://wa.me/918800850853" target="_blank" class="whatsapp">
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
<link rel="stylesheet" href="https://cdn.plyr.io/3.6.2/plyr.css" />
 <script src="https://cdn.plyr.io/3.6.2/plyr.js"></script>
<script>
  const player = new Plyr('#player');
</script>
 <script>
    document.addEventListener('DOMContentLoaded', () => {
        const player = new Plyr('#player', {
            controls: ['play', 'progress', 'current-time', 'mute', 'volume', 'fullscreen'],
            youtube: {
                noCookie: true, // Uses privacy-enhanced mode
                rel: 0, // Limits related videos to the same channel
                modestbranding: 1 // Hides YouTube logo
            }
        });
    });
</script>
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
