<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous"/>
    <link rel="stylesheet" href="{{asset('landing/astro/css/custom.css')}}">

    <link href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.5.9/slick-theme.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Marcellus&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
      integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
      crossorigin="anonymous"
      referrerpolicy="no-referrer"
    />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>

    <title>Astro Advice</title>
  </head>
  <body>
    <div class="modal v-modal" tabindex="-1" id="modal-preview-1">
      <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
          <div class="modal-header course-de">
              <button type="button" class="btn-close position-absolute v_btn" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
              <iframe src="https://www.youtube.com/embed/tatbZhfwU7o?si=48PaXj12xX-NJrnZ" width="100%" height="400"></iframe>
          </div>
        </div>
      </div>
    </div>
    <section class="banner_vastu">
            <!-- header section start -->
        <header class="logo_main">
           <div class="container">
          <nav class="navbar navbar-expand-lg navbar-light">
              <div class="container-fluid">
                <a class="navbar-brand" href="/"><img src="/assets/img/logo.png" alt="Logo" class="img-fluid" style="width: 180px;"></a>
              </nav>
          </div>
      </header>
          <!-- header section end -->
         <div class="container">
           <div class="row">
             <div class="col-md-6">
               <div class="vastu_banner pt-5">
                 <h1 class="">
                   Get the Secrets of Your Destiny with Personalized Astrology
                   Consultation
                 </h1>
                 <p>
                   Get Answers to All Your Life's Questions and Find Solutions in a
                   Single Consultation
                 </p>


               </div>
             </div>
             <div class="col-lg-6 banner-video order-md-last order-first">
                <div class="plyr__video-embed" id="player" style="border-radius: 10px;">
                    <iframe
                      src="https://www.youtube.com/embed/tatbZhfwU7o?si=48PaXj12xX-NJrnZ?origin=https://plyr.io&amp;iv_load_policy=3&amp;modestbranding=1&amp;playsinline=1&amp;showinfo=0&amp;rel=0&amp;enablejsapi=1"
                      allowfullscreen
                      allowtransparency
                      allow="autoplay" class="iframe_slider"
                    ></iframe>
                </div>

              </div>
           </div>
         </div>
       </section>
       <!-- banner section end -->
       <section class="life_channning pt-5 pb-4">
         <div class="container pt-5 pb-5">
          <div class="row align-items-center gx-0 mx-2">
      <div class="col-lg-2 col-sm-3">
      </div>
      <div class="col-lg-8 col-sm-6">
        <div class="heading_main">
        <img src="assets/img/icons/heading_icon.svg">

      </div>
      </div>
      <div class="col-lg-2 col-sm-3 mb-2 mb-md-0">

      </div>
    </div>
           <div class="row">
             <div class="col-md-12">
               <div class="best_life">
                 <div class="mt-2">
                   <h2>Best Life-Changing Solutions in Just One Consultation</h2>
                 <p>
                   Who would have thought that the solutions to your life’s
                   challenges could be just one consultation away with the right
                   expert? Among all the decisions you make, a few stand out as
                   life-changing. With Dr. Yagya Dutt Sharma’s profound expertise,
                   your problems can be addressed and resolved in a single,
                   personalized session.
                 </p>
                 </div>
               </div>
             </div>
           </div>
           <div class="row">
             <div class="col-md-4 carrer_box">
               <div class="career_section text-center">
                 <div class="best_icon text-center d-flex">
                   <i class="fa fa-bookmark" aria-hidden="true"></i>
                 </div>
                 <div class="mt-2">
                 <h3>Career Guidance</h3>
                 <p>
                   Career Guidance is a professional service that helps individuals
                   choose, plan, and develop their careers based on their skills,
                   interests, and goals.
                 </p>
                </div>
               </div>
             </div>
             <div class="col-md-4">
               <div class="career_section">
                 <div class="best_icon text-center">
                   <i class="fa fa-heart" aria-hidden="true"></i>
                 </div>
                 <div class="mt-2">
                   <h3>Love & Relationships</h3>
                 <p>
                   Love and Relationships encompass the emotional, and physical
                   connections between individuals, built on trust, communication,
                   and mutual understanding.
                 </p>
                 </div>
               </div>
             </div>
             <div class="col-md-4">
               <div class="career_section">
                 <div class="best_icon text-center">
                   <i class="fa fa-medkit" aria-hidden="true"></i>
                 </div>
                 <div class="mt-2">
                   <h3>Health Guidance</h3>
                 <p>
                   Health Guidance provides expert advice on physical, mental, and
                   emotional well-being. It includes nutrition, fitness, to promote
                   a healthier and more balanced life.
                 </p>
                 </div>
               </div>
             </div>
           </div>
           <div class="row">
             <div class="col-md-4">
               <div class="career_section">
                <div class="best_icon text-center">
                 <i class="fa fa-briefcase" aria-hidden="true"></i>
               </div>
                 <div class="mt-2">
                   <h3>Wealth Guidance</h3>
                 <p>
                   Struggling to achieve financial stability or wondering how to grow
                   your wealth? IIAG can uncover the planetary influences that impact
                   your financial journey.
                 </p>
                 </div>
               </div>
             </div>
             <div class="col-md-4">
               <div class="career_section">
                 <div class="best_icon text-center">
                   <i class="fa fa-venus-mars" aria-hidden="true"></i>
                 </div>
                 <div class="mt-2">
                   <h3>Marriage</h3>
                 <p>
                   IIAG holds profound insights related to childbirth and parenting.
                   Dr. Yagya Dutt Sharma examines of both the partners provide
                   guidance on having children.
                 </p>
                 </div>
               </div>
             </div>
             <div class="col-md-4">
               <div class="career_section">
                 <div class="best_icon text-center">
                 <i class="fa fa-child" aria-hidden="true"></i>
               </div>
               <div class="mt-2">
                 <h3>Child</h3>
                 <p>
                   Whether you’re a student uncertain about what to do after exams or a
                   professional seeking clarity about job opportunities and future growth,
                   astrology can provide the answers.
                 </p>
                </div>
               </div>
             </div>
           </div>
           </div>
       </section>
       <!-- end section -->
       <!-- section start book consult -->


      <section class="book_consult ">
       <div class="container">
        <div class="row">
         <div class="col-md-6 order-2">
           <div class="now_book text-center as-img">
               <img src="{{('landing/astro/images/aestro_consultation_1.png')}}">
               <!-- <button type="button" class="btn btn-outline-gold" id="book-consultation-btn">
                 Book a Consultation
             </button> -->
           </div>
         </div>
         <div class="col-md-6 order-1">

          <div class="mt-3 who_this">
                       <h2 class="pb-4">Why Choose Our Premium Astrology Consultation?</h2>






                      </div>
           <div class="row mb-4">
             <div class="col-md-6">
               <div class="personalized_main">
                 <div class="best_icon text-center">
                   <i class="fa fa-meetup" aria-hidden="true"></i>
                 </div>
                 <div class="mt-2">
                   <h4>Personalized Insights</h4>
                 <p>Receive detailed guidance tailored to your unique birth chart.</p>
                 </div>
             </div>
             </div>
             <div class="col-md-6">
               <div class="personalized_main">
                 <div class="best_icon text-center">
                   <i class="fa fa-american-sign-language-interpreting" aria-hidden="true"></i>
                 </div>
               <div class="mt-2">
                 <h4>Vedic Expertise</h4>
                 <p>Backed by years of research in ancient texts like Shrimad Bhagavat Purana and Valmiki Ramayana</p>
               </div>
             </div>
             </div>
           </div>
           <div class="row">
             <div class="col-md-6">
               <div class="personalized_main">
                 <div class="best_icon text-center">
                   <i class="fa fa-check-circle" aria-hidden="true"></i>
                 </div>
                 <div class="">
                   <h4>Holistic Approach</h4>
                 <p>Integrating astrology, Vastu, chakra healing, and spiritual rituals.</p>
                 </div>
             </div>
             </div>
             <div class="col-md-6">
               <div class="personalized_main">
                 <div class="best_icon text-center">
                   <i class="fa fa-shield" aria-hidden="true"></i>
                 </div>
                 <div class="mt-2">
                   <h4>Confidential & Exclusive</h4>
                 <p>Premium one-on-one sessions for your privacy and satisfaction.</p>
                 </div>
             </div>
             </div>
           </div>
         </div>
         </div>
         </div>
      </section>
      <!-- end section -->
       <section class="this_for">
           <div class="container">
               <div class="row">
                   <div class="col-md-6">
                       <div class="images_idea">
                       <img src="{{('landing/astro/images/all_define1.png')}}">
                       </div>
                   </div>
                   <div class="col-md-6">
                      <div class="who_this">
                       <h2>Ideal consultation for</h2>
                       <ul>
                           <li><img src="{{('landing/astro/images/Exclamation.png')}}"> High-achieving individuals seeking life alignment</li>
                           <li><img src="{{('landing/astro/images/Exclamation.png')}}"> Business professionals requiring strategic guidance</li>
                           <li><img src="{{('landing/astro/images/Exclamation.png')}}"> Those facing complex life challenges needing clarity</li>
                           <li><img src="{{('landing/astro/images/Exclamation.png')}}"> Anyone striving for success, health, and peace</li>
                       </ul>

                       <div class="dropdown">
                      <button class="btn btn-outline-gold animated-shimmer-button  dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                        Book a Consultation
                      </button>
                      <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                        <li><a class="dropdown-item" href="https://rzp.io/rzp/basic-astro-consultancy" target="_blank">Basic Consultation</a></li>
                        <li><a class="dropdown-item" href="https://rzp.io/rzp/pdAzKx8p" target="_blank">Advance Consultation</a></li>
                        <li><a class="dropdown-item" href="https://rzp.io/rzp/Dmvx7CcF" target="_blank">Premium Consultation</a></li>
                      </ul>
                    </div>



                      </div>
                   </div>
           </div>
           </div>
       </section>
       <!-- end section -->
        <section class="carrer_session">
           <div class="container">
             <div class="row">
               <div class="col-md-12">
                   <div class="consultation_book">
                       <h2>Book Your Consultation Now</h2>
                       <p>Who would have imagined that a single call to the right expert could hold the key to solving all your life problems? Among the countless calls you make in your lifetime, only a few truly matter. With Dr. Yagya Dutt Sharma, one call can change everything. He listens to your concerns with empathy, analyzes your birth chart, and provides precise, effective solutions to transform your life for the better.</p>
                   </div>
               </div>
               <div class="col-md-4">
                   <div class="basic_package">
                       <h2>Basic Package</h2>
                       <p>₹ 2,100</p>
                       <ul class="individuals">
                           <li><i class="fa-solid fa-check"></i> Beginners seeking general guidance</li>
                           <li> <i class="fa-solid fa-check"></i> Holistic Guideline</li>
                           <li> <i class="fa-solid fa-check"></i> Personalized birth chart analysis</li>
                           <li> <i class="fa-solid fa-check"></i> Only 2 Question</li>
                           <li><i class="fa-solid fa-check"></i> One 10-minute consultation session</li>
                       </ul>
                       <div class="text-center">
                         <a href="https://rzp.io/rzp/basic-astro-consultancy" target="_blank" class="btn btn-outline-gold animated-shimmer-button new-btn" id="book-consultation-btn">
                           Book Consultation
                       </a>
                       </div>
                   </div>
               </div>
               <div class="col-md-4">
                   <div class="basic_package">
                       <h2>Advanced Package</h2>
                       <p>₹ 3,100</p>
                       <ul class="individuals">
                           <li> <i class="fa-solid fa-check"></i> Individuals looking for deeper insights and life planning</li>
                           <li><i class="fa-solid fa-check"></i> Detailed birth chart analysis</li>
                           <li><i class="fa-solid fa-check"></i> In-depth interpretation of all planetary positions and houses</li>
                           <li><i class="fa-solid fa-check"></i> Remedies for challenges</li>
                           <li><i class="fa-solid fa-check"></i> Career, relationship, and health analysisn</li>
                           <li><i class="fa-solid fa-check"></i> One 15-minute consultation sessions</li>
                       </ul>
                       <div class="text-center">
                         <a href="https://rzp.io/rzp/pdAzKx8p" target="_blank" class="btn btn-outline-gold animated-shimmer-button new-btn" id="book-consultation-btn">
                           Book a Consultation
                       </a>
                       </div>
                   </div>
               </div>
               <div class="col-md-4">
                   <div class="basic_package">
                       <h2>Premium Package</h2>
                       <p>₹ 5,100</p>
                       <ul>
                           <li><i class="fa-solid fa-check"></i> Those seeking comprehensive guidance and remedies</li>
                           <li><i class="fa-solid fa-check"></i> Complete birth chart analysis with Dasha and transits</li>
                           <li><i class="fa-solid fa-check"></i> Dasha predictions with specific event timing</li>
                           <li><i class="fa-solid fa-check"></i> Vastu suggestions for personal and professional growth</li>
                           <li><i class="fa-solid fa-check"></i> Personalized remedies (gemstones, mantras, or rituals)</li>
                           <li><i class="fa-solid fa-check"></i> 30-minute consultation sessions</li>
                       </ul>
                       <div class="text-center">
                         <a href="https://pages.razorpay.com/pl_PZKl2qtIbhNHFG/view" target="_blank" class="btn btn-outline-gold animated-shimmer-button new-btn" id="book-consultation-btn">
                           Book a Consultation
                       </a>
                       </div>
                   </div>
               </div>
             </div>
           </div>
        </section>
        <!-- section end -->
         <section class="happy_client_section" >
          <div class="container">
           <div class="row journey_row">
               <div class="col-md-3">
                   <div class="happy_client">
                     <div class="boot_icon">
                       <i class="bi bi-calendar-date-fill"></i>
                     </div>
                     <!-- <img src="images/Calendar.png">    -->

                     <h2>15+</h2>
                     <p>Years of Experience</p>
               </div>
               </div>
               <div class="col-md-3">
                   <div class="happy_client">
                     <!-- <img src="images/Emoj.png">    -->
                     <i class="bi bi-emoji-smile-fill"></i>
                     <h2>50000+</h2>
                     <p>Happy Clients</p>
               </div>
               </div>

               <div class="col-md-3">
                   <div class="happy_client">
                     <!-- <img src="images/Emoj.png">    -->
                     <i class="bi bi-people-fill"></i>
                     <h2>85%</h2>
                     <p>Client Retention</p>
               </div>
               </div>
               <div class="col-md-3">
                   <div class="happy_client">
                     <!-- <img src="images/AwardFill.png">    -->
                     <i class="bi bi-diagram-2-fill"></i>
                     <h2>10+</h2>
                     <p class="jyotish">Served Branches</p>
               </div>
               </div>
           </div>
          </div>
         </section>
     <!-- end section -->
      <section></section>
      <!-- end section -->
       <section class="testmobils_section video-testimonials1">
       <div class="container">
        <div class="row">
         <h2 class="mb-3">Video Testimonials</h2>
         <div class="wrapper">
         <div class="my-testmonils">
           <div>
             <iframe width="100%" height="208" src="https://www.youtube.com/embed/VdW6R0f8ZZI?si=ohG8lZQMDujiuJIn" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
           </div>
           <div>
             <iframe width="100%" height="208" src="https://www.youtube.com/embed/93L1SzzA_y0?si=ZEDhisXAsFMHfxS_" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
           </div>
            <div>
             <iframe width="100%" height="208" src="https://www.youtube.com/embed/As0IKzwW_V4?si=MN19T6fLkpqB5kAe" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe></div>
            <div>
             <iframe width="100%" height="208" src="https://www.youtube.com/embed/pionjdi-pyo?si=cac0P7Qlyn23JxK4" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
            </div>
            <div>
             <iframe width="100%" height="208" src="https://www.youtube.com/embed/pionjdi-pyo?si=cac0P7Qlyn23JxK4" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
            </div>
         </div>
       </div>
   </div>
     </div>
   </section>
       <!-- end section -->

   <section class="slider_section">
     <div class="container">
       <div class="row">
         <h2> Testimonials </h2>
         <div class="wrapper">
           <div class="my-slider">
             <!-- Slide 1 -->
             <div class="textmonils_slider">
               <div class="imagess">
                 <img src="{{('landing/astro/images/t1.jpg')}}" alt="Review Image" class="t-img">
               </div>
               <div class="content">
                 <p>Dr. Sharma’s insights completely changed my perspective. I found solutions to my career stagnation and improved my relationships.</p>
                 <ul>
                   <li>Pratema Raahuel</li>
                   <li>
                     <i class="fa-solid fa-star"></i>
                     <i class="fa-solid fa-star"></i>
                     <i class="fa-solid fa-star"></i>
                     <i class="fa-solid fa-star"></i>
                     <i class="fa-solid fa-star"></i>
                   </li>
                 </ul>
               </div>
             </div>
               <!-- Slide  -->
               <div class="textmonils_slider">
                 <div class="imagess">
                   <img src="{{('landing/astro/images/t2.webp')}}" alt="Review Image" class="t-img">
                 </div>
                 <div class="content">
                   <p>"I was struggling with relationship issues for years, but after my consultation with Dr. Sharma, I gained a deeper understanding of myself and my partner. The guidance he provided was enlightening, and our bond has never been stronger.</p>
                   <ul>
                     <li>Pt. Pankaj Nirmal</li>
                     <li>
                       <i class="fa-solid fa-star"></i>
                       <i class="fa-solid fa-star"></i>
                       <i class="fa-solid fa-star"></i>
                       <i class="fa-solid fa-star"></i>
                       <i class="fa-solid fa-star"></i>
                     </li>
                   </ul>
                 </div>
               </div>
                 <!-- Slide 3 -->
             <div class="textmonils_slider">
               <div class="imagess">
                 <img src="{{('landing/astro/images/t3.webp')}}" alt="Review Image" class="t-img">
               </div>
               <div class="content">
                 <p>Dr. Sharma’s insights completely changed my perspective. I found solutions to my career stagnation and improved my relationships.</p>
                 <ul>
                   <li>Prappan Buddhi</li>
                   <li>
                     <i class="fa-solid fa-star"></i>
                     <i class="fa-solid fa-star"></i>
                     <i class="fa-solid fa-star"></i>
                     <i class="fa-solid fa-star"></i>
                     <i class="fa-solid fa-star"></i>
                   </li>
                 </ul>
               </div>
             </div>
             <div class="textmonils_slider">
               <div class="imagess">
                 <img src="{{('landing/astro/images/t4.webp')}}" alt="Review Image" class="t-img">
               </div>
               <div class="content">
                 <p>I was uncertain about my career direction, but after consulting with Dr. Sharma, I gained the clarity I needed. His analysis of my birth chart helped me understand my strengths and potential career paths. I’m now on the right track.</p>
                 <ul>
                   <li>Ankita</li>
                   <li>
                     <i class="fa-solid fa-star"></i>
                     <i class="fa-solid fa-star"></i>
                     <i class="fa-solid fa-star"></i>
                     <i class="fa-solid fa-star"></i>
                     <i class="fa-solid fa-star"></i>
                   </li>
                 </ul>
               </div>
             </div>


             <!-- Add more slides as needed -->
           </div>
         </div>
       </div>
     </div>
   </section>
     <!-- end section -->
     <!-- accordion section start -->
      <section class="accordion_main">
       <div class="container faq-b">
         <div class="row">
           <h2>Frequently Asked Questions</h2>
           <div class="col-md-12">
             <div class="main-ac">
               <div class="accordion" id="accordionPanelsStayOpenExample">
                 <div class="accordion-item">
                   <h2 class="accordion-header" id="panelsStayOpen-headingOne">
                     <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseOne" aria-expanded="true" aria-controls="panelsStayOpen-collapseOne">
                       How can I book a consultation?
                     </button>
                   </h2>
                   <div id="panelsStayOpen-collapseOne" data-bs-parent="#accordionPanelsStayOpenExample" class="accordion-collapse collapse show" aria-labelledby="panelsStayOpen-headingOne">
                     <div class="accordion-body">
                       Booking a consultation is easy! Simply click on the <b> "Book Now" </b>button on our website, select a convenient time, and provide your birth details. You will receive a confirmation along with further instructions.
                     </div>
                   </div>
                 </div>
                 <div class="accordion-item">
                   <h2 class="accordion-header" id="panelsStayOpen-headingTwo">
                     <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseTwo" aria-expanded="false" aria-controls="panelsStayOpen-collapseTwo">
                       Can I have a consultation for someone else (e.g., partner, child)?
                     </button>
                   </h2>
                   <div id="panelsStayOpen-collapseTwo" data-bs-parent="#accordionPanelsStayOpenExample" class="accordion-collapse collapse" aria-labelledby="panelsStayOpen-headingTwo">
                     <div class="accordion-body">
                       Yes, you can book a consultation for someone else. For accurate readings, we will need the birth details of the individual you are inquiring about.

                     </div>
                   </div>
                 </div>
                 <div class="accordion-item">
                   <h2 class="accordion-header" id="panelsStayOpen-headingThree">
                     <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseThree" aria-expanded="false" aria-controls="panelsStayOpen-collapseThree">
                       What if I have more questions after the consultation?
                     </button>
                   </h2>
                   <div id="panelsStayOpen-collapseThree" data-bs-parent="#accordionPanelsStayOpenExample" class="accordion-collapse collapse" aria-labelledby="panelsStayOpen-headingThree">
                     <div class="accordion-body">
                       We encourage clients to follow up with any questions after the consultation. You can contact us for further guidance or schedule additional sessions to dive deeper into specific areas of your life.

                     </div>
                   </div>
                 </div>
                 <div class="accordion-item">
                   <h2 class="accordion-header" id="panelsStayOpen-headingFour">
                     <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseFour" aria-expanded="false" aria-controls="panelsStayOpen-collapseFour">
                       Is astrology consultation confidential?
                     </button>
                   </h2>
                   <div id="panelsStayOpen-collapseFour" data-bs-parent="#accordionPanelsStayOpenExample" class="accordion-collapse collapse" aria-labelledby="panelsStayOpen-headingFour">
                     <div class="accordion-body">
                       Yes, all consultations are strictly confidential. Your personal information, birth details, and any insights shared during the session will remain private
                     </div>
                   </div>
                 </div>
                 <div class="accordion-item">
                   <h2 class="accordion-header" id="panelsStayOpen-headingFive">
                     <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseFive" aria-expanded="false" aria-controls="panelsStayOpen-collapseFive">
                       What should I provide for the consultation?
                     </button>
                   </h2>
                   <div id="panelsStayOpen-collapseFive" data-bs-parent="#accordionPanelsStayOpenExample" class="accordion-collapse collapse" aria-labelledby="panelsStayOpen-headingFive">
                     <div class="accordion-body">
                       To offer an accurate and detailed consultation, we require your full birth details:
                       <ol>
                         <li>Date of birth</li>
                         <li>Time of birth</li>
                         <li>Place of birth</li>
                         </ol>
                         These details allow us to create your personalized birth chart for analysis.

                     </div>
                   </div>
                 </div>
               </div>
             </div>
           </div>
         </div>
       </div>
      </section>
      <!-- end accordion section -->
      <!-- footer section start  -->

   <footer class="backfoot">
   <div class="container">
     <div class="row">
       <div class="text-center">
         <!-- <a href="https://pages.razorpay.com/pl_PZKl2qtIbhNHFG/view" target="_blank" class="btn btn-outline-gold  animated-shimmer-button new-btn" id="book-consultation-btn">
           Book a Consultation
       </a> -->
           <div class="dropdown">
          <button class="btn btn-outline-gold animated-shimmer-button new-btn dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
            Book a Consultation
          </button>
          <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
            <li><a class="dropdown-item" href="https://rzp.io/rzp/basic-astro-consultancy" target="_blank">Basic Consultation</a></li>
            <li><a class="dropdown-item" href="https://rzp.io/rzp/pdAzKx8p" target="_blank">Advance Consultation</a></li>
            <li><a class="dropdown-item" href="https://rzp.io/rzp/Dmvx7CcF" target="_blank">Premium Consultation</a></li>
          </ul>
        </div>
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
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous" ></script>
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
         $(document).ready(function(){
          $('.my-testmonils').slick({
            slidesToShow: 3,
            slidesToScroll: 1,
            arrows: false,
            dots: true,
            speed: 300,
            infinite: true,
            autoplaySpeed: 3000,
            autoplay: true,
            responsive: [
          {
            breakpoint: 991,
            settings: {
              slidesToShow: 2,
              autoplay: true,
            }
          },
          {
            breakpoint: 767,
            settings: {
              slidesToShow: 1,
              autoplay: true,
            }
          }
        ]
          });
        });
        </script>


       <script>
        $(document).ready(function(){
         $('.my-slider').slick({
           slidesToShow: 2,
           slidesToScroll: 1,
           arrows: true,
           dots: false,
           speed: 300,
           infinite: true,
           autoplaySpeed: 5000,
           autoplay: true,
           responsive: [
         {
           breakpoint: 991,
           settings: {
             slidesToShow: 2,
           }
         },
         {
           breakpoint: 767,
           settings: {
             slidesToShow: 1,
           }
         },
         {
           breakpoint: 567,
           settings: {
             slidesToShow: 1,
           }
         }
       ]
         });
       });
       </script>
  </body>
</html>
