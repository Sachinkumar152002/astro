<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC"
      crossorigin="anonymous"
    />
      <link rel="stylesheet" href="{{asset('landing/vastu/asset/css/style.css')}}">
    <link href="https://fonts.googleapis.com/css2?family=Marcellus&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.5.9/slick-theme.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
      integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
      crossorigin="anonymous"
      referrerpolicy="no-referrer"
    />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
    <title>vastu Consultation</title>
  </head>
  <body>
    <div class="modal v-modal" tabindex="-1" id="modal-preview-1">
      <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
          <div class="modal-header course-de">
              <button type="button" class="btn-close position-absolute v_btn" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
              <iframe src="https://www.youtube.com/embed/aNEqToSC88Y?si=Qv1e9zQESeuuJnoR" width="100%" height="400"></iframe>
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
               <div class="vastu_banner">
                 <h1 class="">
                  Transform Your Space, Transform Your Life with Expert Vastu Guidance!
                 </h1>
                 <p class="mt-2">
                   Achieve balance, prosperity, and peace with expert Vastu consultancy tailored to your unique needs.

                 </p>


               </div>
             </div>

              <div class="col-lg-6 banner-video order-md-last order-first">


                  <div class="plyr__video-embed" id="player"  style="border-radius: 10px;">
                        <iframe
                          src="https://www.youtube.com/embed/aNEqToSC88Y?si=Qv1e9zQESeuuJnoR?origin=https://plyr.io&amp;iv_load_policy=3&amp;modestbranding=1&amp;playsinline=1&amp;showinfo=0&amp;rel=0&amp;enablejsapi=1"
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
       <section class="life_channning">
         <div class="container py-md-5 py-0">
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
                   <h2>How Vastu Can Transform Your Life</h2>
                 </div>
               </div>
             </div>
           </div>
           <div class="row">
             <div class="col-md-4 carrer_box">
               <div class="career_section text-center">
                 <div class="best_icon text-center d-flex">
                   <img src="{{('landing/vastu/asset/images/sun-energy.png')}}" alt="Enhances Positive Energy">

                 </div>
                 <div class="mt-2">
                 <h3>Enhances Positive Energy</h3>
                 <p>
                   Vastu aligns your living or working space with natural energies, creating a harmonious environment for growth and happiness.

                 </p>
                </div>
               </div>
             </div>
             <div class="col-md-4">
               <div class="career_section">
                 <div class="best_icon text-center">
                   <img src="{{('landing/vastu/asset/images/healthy.png')}}" alt="Improves Health & Well-being">
                 </div>
                 <div class="mt-2">
                   <h3> Improves Health & Well-being</h3>
                 <p>
                   Proper placement of rooms and objects can promote physical and mental health while reducing stress and anxiety.

                 </p>
                 </div>
               </div>
             </div>
             <div class="col-md-4">
               <div class="career_section">
                 <div class="best_icon text-center">
                   <img src="{{('landing/vastu/asset/images/money.png')}}" alt="Boosts Financial Prosperity">
                 </div>
                 <div class="mt-2">
                   <h3> Boosts Financial Prosperity</h3>
                 <p>
                Correct Vastu principles attract wealth and financial stability by balancing energy flow in key areas of your home or office.
                 </p>
                 </div>
               </div>
             </div>
           </div>
           <div class="row">
             <div class="col-md-4">
               <div class="career_section">
                <div class="best_icon text-center">
                  <img src="{{('landing/vastu/asset/images/strengthen.png')}}" alt="Strengthens Relationships">
               </div>
                 <div class="mt-2">
                   <h3>Strengthens Relationships</h3>
                 <p>
                   Balanced spaces encourage better communication, love, and understanding among family members and partners.
                 </p>
                 </div>
               </div>
             </div>
             <div class="col-md-4">
               <div class="career_section">
                 <div class="best_icon text-center">
                   <img src="{{('landing/vastu/asset/images/concentrate.png')}}" alt="Enhances Focus & Productivity">
                 </div>
                 <div class="mt-2">
                   <h3>Enhances Focus & Productivity</h3>
                 <p>
                   A Vastu-compliant workspace promotes clarity, focus, and increased efficiency in work or studies.
                 </p>
                 </div>
               </div>
             </div>
             <div class="col-md-4">
               <div class="career_section">
                 <div class="best_icon text-center">
                   <img src="{{('landing/vastu/asset/images/yoga.png')}}" alt="Supports Spiritual Growth">
               </div>
               <div class="mt-2">
                 <h3> Supports Spiritual Growth</h3>
                 <p>
                    Aligning spaces with cosmic energy fosters inner peace, mindfulness, and spiritual well-being.

                 </p>
                </div>
               </div>
             </div>
           </div>
         </div>
       </section>
       <!-- end section -->
       <!-- section start book consult -->
      <section class="book_consult">
       <div class="container">
       <h1 class="text-dark">Our Services</h1>
         <div class="row pb-2 pb-md-5 pt-2">

             <div class="col-md-3">
               <div class="personalized_main">
                 <div class="icon-img-b">
                  <img src="{{('landing/vastu/asset/images/home.jpg')}}" alt="Residential Vastu" class="img-fluid w-100">
                 </div>
                 <div  class="mt-2 p-2 p-md-3">
                   <h4>Residential Vastu</h4>
                 </div>
             </div>
             </div>
             <div class="col-md-3">
               <div class="personalized_main">
                 <div class="icon-img-b">
                  <img src="{{('landing/vastu/asset/images/office.webp')}}" alt="Office and Business Vastu" class="img-fluid w-100">
                 </div>
               <div class="mt-2 p-2 p-md-3">
                 <h4>Office and Business Vastu</h4>
               </div>
             </div>
             </div>
             <div class="col-md-3">
               <div class="personalized_main">
                 <div class="icon-img-b">
                  <img src="{{('landing/vastu/asset/images/land.webp')}}" alt="Land Selection and Layout Design" class="img-fluid w-100">
                 </div>
                 <div  class="mt-2 p-2 p-md-3">
                   <h4> Land Selection and Layout Design</h4>
                 </div>
             </div>
             </div>
             <div class="col-md-3">
               <div class="personalized_main">
                 <div class="icon-img-b">
                  <img src="{{('landing/vastu/asset/images/remi.webp')}}" alt="Remedies for Vastu Dosh" class="img-fluid w-100">
                 </div>
                 <div  class="mt-2 p-2 p-md-3">
                   <h4> Remedies for Vastu Dosh</h4>
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
                   <div class="col-md-6 order-2 order-md-1">
                     <div class="form-box" id="enquiry-form">
                       <form>
                         <div class="row mb-2">
                             <!-- Name Field -->
                             <div class="col-md-6 mb-2 labelright">
                                 <label for="name" class="form-label">Name <span class="text-danger">*</span> </label>
                                 <input type="text" id="name" class="form-control form-input" placeholder="Enter Your Name*">
                             </div>

                             <!-- Email Field -->
                             <div class="col-md-6 mb-2 labelright">
                                 <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                 <input type="email" id="email" class="form-control form-input" placeholder="Enter Your Email*">
                             </div>
                         </div>

                         <div class="row mb-2">
                             <!-- Phone Number Field -->
                             <div class="col-md-6 mb-2 labelright">
                                 <label for="phone" class="form-label">Phone Number <span class="text-danger">*</span></label>
                                 <input type="tel" id="phone" class="form-control form-input" placeholder="Enter Your Phone Number*">
                             </div>

                             <!-- Select a Service Field -->
                             <div class="col-md-6 mb-2 labelright">
                                 <label for="service" class="form-label">Select a Service</label>
                                 <select id="service" class="form-control form-input ">
                                     <option value="" class="option-flied" disabled="" selected="">Select a Service</option>
                                     <option value="service1" class="option-flied-open">Vastu For Shop</option>
                                     <option value="service2">Vastu For Office</option>
                                     <option value="service3">Vastu For Factory</option>
                                     <option value="service4">Vastu For Malls</option>
                                     <option value="service4">Vastu For Hotels</option>
                                     <option value="service4">Vastu For Restaurant</option>
                                     <option value="service4">Vastu For Home</option>
                                 </select>
                             </div>

                         </div>

                         <div class="row mb-2">
                             <!-- Length Field -->
                             <div class="col-md-6 mb-2 labelright">
                                 <label for="length" class="form-label">Length (in meters)</label>
                                 <input type="text" id="length" class="form-control form-input" placeholder="Enter Length (in meters)">
                             </div>

                             <!-- Breadth Field -->
                             <div class="col-md-6 mb-2 labelright">
                                 <label for="breadth" class="form-label">Breadth (in meters)</label>
                                 <input type="text" id="breadth" class="form-control form-input" placeholder="Enter Breadth (in meters)">
                             </div>
                         </div>

                         <!-- Description Field -->
                         <div class="mb-2 labelright">
                             <label for="description" class="form-label">Description</label>
                             <textarea id="description" class="form-control form-input" rows="2" placeholder="Optional"></textarea>
                         </div>

                         <!-- Submit Button -->
                         <div class="text-center mt-4">
                             <button type="button" class="btn btn-outline-gold">
                                 Enquiry Now
                             </button>
                         </div>
                     </form>
                     </div>
                   </div>
                   <div class="col-md-6 order-1 order-md-2">
                      <div class="who_this ms-2">
                       <h2>Why Choose Vastu Consultancy?</h2>
                       <!-- <h5>This consultation is ideal for</h5> -->
                       <ul>
                           <li><img src="{{('landing/vastu/asset/images/Exclamation.png')}}" class="me-2"> Create a positive energy flow</li>
                           <li><img src="{{('landing/vastu/asset/images/Exclamation.png')}}" class="me-2"> Enhance your relationships and health</li>
                           <li><img src="{{('landing/vastu/asset/images/Exclamation.png')}}" class="me-2"> Boost financial stability and career growth</li>
                       </ul>
                      </div>
                   </div>
           </div>
           </div>
       </section>
       <!-- end section -->
        <!-- <section class="carrer_session">
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
                         <a href="https://rzp.io/rzp/basic-vastu-consultancy" target="_blank" class="btn btn-outline-gold" id="book-consultation-btn">
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
                         <a href="https://rzp.io/rzp/pdAzKx8p" target="_blank" class="btn btn-outline-gold" id="book-consultation-btn">
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
                         <a href="https://pages.razorpay.com/pl_PZKl2qtIbhNHFG/view" target="_blank" class="btn btn-outline-gold" id="book-consultation-btn">
                           Book a Consultation
                       </a>
                       </div>
                   </div>
               </div>
             </div>
           </div>
        </section> -->
        <!-- section end -->
         <section class="happy_client_section" >
          <div class="container">
           <div class="row journey_row">
               <div class="col-md-4">
                   <div class="happy_client">
                     <div class="boot_icon">
                       <i class="bi bi-calendar-date-fill"></i>
                     </div>
                     <!-- <img src="images/Calendar.png">    -->

                     <h2>15+</h2>
                     <p>Years of Experience</p>
               </div>
               </div>
               <div class="col-md-4">
                   <div class="happy_client">
                     <!-- <img src="images/Emoj.png">    -->
                     <i class="bi bi-briefcase-fill"></i>
                     <h2>3000+</h2>
                     <p>Vastu Projects Done</p>
               </div>
               </div>

               <div class="col-md-4">
                   <div class="happy_client">
                     <!-- <img src="images/Emoj.png">    -->
                     <i class="bi bi bi-graph-up"></i>
                     <h2>91%</h2>
                     <p>Effective Ratio</p>
               </div>
               </div>
           </div>
          </div>
         </section>
     <!-- end section -->
      <section></section>
      <!-- end section -->
       <section class="testmobils_section">
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
                 <img src="{{('landing/vastu/asset/images/t1.jpg')}}" alt="Review Image" class="t-img">
               </div>
               <div class="content">
                 <p>"Dr. Sharma's Vastu consultancy completely transformed our home. After implementing his recommendations, we experienced a noticeable shift in the energy of our space. Our relationships have improved, and our financial situation has also seen positive changes. I highly recommend his services!.</p>
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
                   <img src="{{('landing/vastu/asset/images/t2.webp')}}" alt="Review Image" class="t-img">
                 </div>
                 <div class="content">
                   <p>As a business owner, I was struggling with stagnant growth and low employee morale. After consulting with Dr. Sharma, we made a few Vastu adjustments in our office. The results were remarkable – not only did our sales increase, but the work environment became much more positive and motivating.</p>
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
                 <img src="{{('landing/vastu/asset/images/t3.webp')}}" alt="Review Image" class="t-img">
               </div>
               <div class="content">
                 <p>I approached Dr. Sharma when we were planning to build our new house. His expert guidance on the layout and design of our home based on Vastu principles has truly made a difference. The energy in the house feels balanced and peaceful. We are all so happy in our new space!"</p>
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
                 <img src="{{('landing/vastu/asset/images/t4.webp')}}" alt="Review Image" class="t-img">
               </div>
               <div class="content">
                 <p>After struggling with health issues for years, I turned to Dr. Sharma for Vastu remedies. He suggested specific changes to the placement of furniture and the use of healing crystals. Within weeks, I started noticing improvements in my health and overall well-being. I am forever grateful for his guidance."</p>
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
             <div class=" main-ac">
               <div class="accordion" id="accordionPanelsStayOpenExample">
                 <div class="accordion-item">
                   <h2 class="accordion-header" id="panelsStayOpen-headingOne">
                     <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseOne" aria-expanded="true" aria-controls="panelsStayOpen-collapseOne">
                      What is Vastu Shastra?
                     </button>
                   </h2>
                   <div id="panelsStayOpen-collapseOne" data-bs-parent="#accordionPanelsStayOpenExample" class="accordion-collapse collapse show" aria-labelledby="panelsStayOpen-headingOne">
                     <div class="accordion-body">
                       Vastu Shastra is an ancient Indian architectural science that deals with the layout and design of living spaces to ensure they align with the natural elements and energies of the universe. Vastu helps create a balanced environment that promotes health, prosperity, and happiness.

                     </div>
                   </div>
                 </div>
                 <div class="accordion-item">
                   <h2 class="accordion-header" id="panelsStayOpen-headingTwo">
                     <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseTwo" aria-expanded="false" aria-controls="panelsStayOpen-collapseTwo">
                       Do I need to make major changes to my property for Vastu remedies?
                     </button>
                   </h2>
                   <div id="panelsStayOpen-collapseTwo" data-bs-parent="#accordionPanelsStayOpenExample" class="accordion-collapse collapse" aria-labelledby="panelsStayOpen-headingTwo">
                     <div class="accordion-body">
                       Not necessarily! In many cases, small adjustments such as rearranging furniture, changing the color scheme, or adding specific elements like mirrors or plants can bring about significant changes. For more complex issues, we may recommend layout adjustments, but we focus on practical and cost-effective solutions.

                     </div>
                   </div>
                 </div>
                 <div class="accordion-item">
                   <h2 class="accordion-header" id="panelsStayOpen-headingThree">
                     <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseThree" aria-expanded="false" aria-controls="panelsStayOpen-collapseThree">
                       How do I know if my property has Vastu dosh (flaws)?
                     </button>
                   </h2>
                   <div id="panelsStayOpen-collapseThree" data-bs-parent="#accordionPanelsStayOpenExample" class="accordion-collapse collapse" aria-labelledby="panelsStayOpen-headingThree">
                     <div class="accordion-body">
                       Vastu dosh can manifest as feelings of discomfort, frequent disagreements, health issues, or a lack of success and prosperity in the space. A consultation with our expert Vastu consultant can identify specific dosh in your space and suggest remedies for correction.

                     </div>
                   </div>
                 </div>
                 <div class="accordion-item">
                   <h2 class="accordion-header" id="panelsStayOpen-headingFour">
                     <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseFour" aria-expanded="false" aria-controls="panelsStayOpen-collapseFour">
                       Can Vastu be applied to both homes and businesses?
                     </button>
                   </h2>
                   <div id="panelsStayOpen-collapseFour" data-bs-parent="#accordionPanelsStayOpenExample" class="accordion-collapse collapse" aria-labelledby="panelsStayOpen-headingFour">
                     <div class="accordion-body">
                       Absolutely! Vastu principles are effective for all kinds of spaces, whether residential, commercial, or industrial. The benefits of Vastu apply to both home environments and business spaces, improving harmony, productivity, and financial growth.
                     </div>
                   </div>
                 </div>
                 <div class="accordion-item">
                   <h2 class="accordion-header" id="panelsStayOpen-headingFive">
                     <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseFive" aria-expanded="false" aria-controls="panelsStayOpen-collapseFive">
                       How long does it take to see results after applying Vastu remedies?
                     </button>
                   </h2>
                   <div id="panelsStayOpen-collapseFive" data-bs-parent="#accordionPanelsStayOpenExample" class="accordion-collapse collapse" aria-labelledby="panelsStayOpen-headingFive">
                     <div class="accordion-body">
                       The time it takes to see results can vary depending on the remedies applied and the individual situation. Many clients notice improvements in their environment and personal life within a few weeks of making changes. Some may experience more immediate effects, especially with simple fixes.


                     </div>
                   </div>
                 </div>
                 <div class="accordion-item">
                   <h2 class="accordion-header" id="panelsStayOpen-headingSix">
                     <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseSix" aria-expanded="false" aria-controls="panelsStayOpen-collapseFive">
                       Do Vastu remedies involve expensive renovations or alterations?
                     </button>
                   </h2>
                   <div id="panelsStayOpen-collapseSix" data-bs-parent="#accordionPanelsStayOpenExample" class="accordion-collapse collapse" aria-labelledby="panelsStayOpen-headingSix">
                     <div class="accordion-body">
                       Not always. Many Vastu remedies can be implemented through minor adjustments, such as changing the positioning of furniture, using specific colors, or placing items in certain directions. Only in rare cases, when the building layout has significant flaws, would we recommend major alterations.


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
         <a href="#enquiry-form" class="btn btn-outline-gold  animated-shimmer-button new-btn">
           Book a Consultation
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
      <script
         src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
         integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
         crossorigin="anonymous"
       ></script>

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
           autoplaySpeed: 3000,
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
