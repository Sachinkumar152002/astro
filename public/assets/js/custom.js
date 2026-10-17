$(document).ready(function () {

  $('.feature_slide').slick({
    dots:false,
    infinite: true,
    speed: 300,
    slidesToShow: 4,
    slidesToScroll: 1,
    autoplay: true,
    autoplaySpeed: 2000,
    prevArrow: false,
      nextArrow: false,
    responsive: [
      {
        breakpoint: 1299,
        settings: {
          slidesToShow: 3,
          slidesToScroll: 1,
          infinite: true,
          dots:false,
        }
      },
      {
        breakpoint: 1024,
        settings: {
          slidesToShow: 3,
          slidesToScroll: 1,
          infinite: true,
        dots:false,
        }
      },
      {
        breakpoint: 600,
        settings: {
          slidesToShow: 3,
          slidesToScroll: 1,
            dots:false,
        }
      },
      {
        breakpoint: 576,
        settings: {
          slidesToShow: 3,
          slidesToScroll: 1,
            dots:false,
        }
      },
      {
        breakpoint: 480,
        settings: {
          slidesToShow: 3,
          slidesToScroll: 1,
            dots:false,
        }
      }
      // You can unslick at a given breakpoint now by adding:
      // settings: "unslick"
      // instead of a settings object
    ]
  });

  $('.primium').slick({
  dots: false,
  infinite: true,
  speed: 300,
  slidesToShow: 3,
  slidesToScroll: 1,
  autoplay: true,
  autoplaySpeed: 2000,
  prevArrow: '<button type="button" class="slick-prev"><i class="bi bi-chevron-left"></i></button>',
  nextArrow: '<button type="button" class="slick-next"><i class="bi bi-chevron-right"></i></button>',
  responsive: [
    {
      breakpoint: 1299,
      settings: {
        slidesToShow: 3,
        slidesToScroll: 1,
        infinite: true,
        dots: false,
      }
    },
    {
      breakpoint: 1024,
      settings: {
        slidesToShow: 1,
        slidesToScroll: 1,
        infinite: true,
        dots: true,
      }
    },
    {
      breakpoint: 600,
      settings: {
        slidesToShow: 1,
        slidesToScroll: 1,
        dots: true,
        prevArrow: '<button type="button" class="slick-prev"><i class="bi-chevron-left"></i></button>',
        nextArrow: '<button type="button" class="slick-next"><i class="bi-chevron-right"></i></button>',
      }
    },
    {
      breakpoint: 576,
      settings: {
        slidesToShow: 1,
        slidesToScroll: 1,
        dots: true,
        prevArrow: '<button type="button" class="slick-prev"><i class="bi-chevron-left"></i></button>',
        nextArrow: '<button type="button" class="slick-next"><i class="bi-chevron-right" ></i></button>',
      }
    },
    {
      breakpoint: 480,
      settings: {
        slidesToShow: 1,
        slidesToScroll: 1,
        dots: true,
        prevArrow: '<button type="button" class="slick-prev"><i class="bi-chevron-left"></i></button>',
        nextArrow: '<button type="button" class="slick-next"><i class="bi-chevron-right"></i></button>',
      }
    }
  ]
});


$('.coursesinner').slick({
  dots: false,
  infinite: true,
  speed: 300,
  slidesToShow: 3,
  slidesToScroll: 1,
  autoplay: true,
  autoplaySpeed: 8000,
  prevArrow: '<button type="button" class="slick-prev"><i class="bi bi-chevron-left"></i></button>',
  nextArrow: '<button type="button" class="slick-next"><i class="bi bi-chevron-right"></i></button>',
  responsive: [
    {
      breakpoint: 1299,
      settings: {
        slidesToShow: 3,
        slidesToScroll: 1,
        infinite: true,
        dots: false,
      }
    },
    {
      breakpoint: 1024,
      settings: {
        slidesToShow: 3,
        slidesToScroll: 1,
        infinite: true,
        dots: true,
      }
    },
    {
      breakpoint: 600,
      settings: {
        slidesToShow: 3,
        slidesToScroll: 1,
        dots: true,
        prevArrow: '<button type="button" class="slick-prev"><i class="bi-chevron-left"></i></button>',
        nextArrow: '<button type="button" class="slick-next"><i class="bi-chevron-right"></i></button>',
      }
    },
    {
      breakpoint: 576,
      settings: {
        slidesToShow: 3,
        slidesToScroll: 1,
        dots: true,
        prevArrow: '<button type="button" class="slick-prev"><i class="bi-chevron-left"></i></button>',
        nextArrow: '<button type="button" class="slick-next"><i class="bi-chevron-right" ></i></button>',
      }
    },
    {
      breakpoint: 480,
      settings: {
        slidesToShow: 3,
        slidesToScroll: 1,
        dots: true,
        prevArrow: '<button type="button" class="slick-prev"><i class="bi-chevron-left"></i></button>',
        nextArrow: '<button type="button" class="slick-next"><i class="bi-chevron-right"></i></button>',
      }
    }
  ]
});


  $('.primium-c').slick({
  dots:false,
    infinite: true,
    speed: 300,
    slidesToShow: 4,
    slidesToScroll: 1,
    autoplay:false,
    autoplaySpeed: 2000,
    responsive: [
      {
        breakpoint: 1299,
        settings: {
          slidesToShow: 3,
          slidesToScroll: 1,
          infinite: true,
        dots:false,
        }
      },
      {
        breakpoint: 1024,
        settings: {
          slidesToShow: 1,
          slidesToScroll: 1,
          infinite: true,
        dots:true,
        }
      },
      {
        breakpoint: 600,
        settings: {
          slidesToShow: 2,
          slidesToScroll: 1,
          dots:true
        }
      },
      {
        breakpoint: 576,
        settings: {
          slidesToShow: 2,
          slidesToScroll: 1,
          dots:true
        }
      },
      {
        breakpoint: 480,
        settings: {
          slidesToShow: 2,
          slidesToScroll: 1,
          dots:true
        }
      }

    ]
  });
  $('.prod_category').slick({
    dots:false,
    infinite: true,
    speed: 300,
    slidesToShow: 4,
    slidesToScroll: 1,
    autoplay: false,
    autoplaySpeed: 2000,
    responsive: [
      {
        breakpoint: 1299,
        settings: {
          slidesToShow: 3,
          slidesToScroll: 1,
          infinite: true,
        dots:false,
        }
      },
      {
        breakpoint: 1024,
        settings: {
          slidesToShow: 2,
          slidesToScroll: 1,
          infinite: true,
          dots:false,
        }
      },
      {
        breakpoint: 600,
        settings: {
          slidesToShow: 2,
          slidesToScroll: 1
        }
      },
      {
        breakpoint: 576,
        settings: {
          slidesToShow: 2,
          slidesToScroll: 1
        }
      },
      {
        breakpoint: 480,
        settings: {
          slidesToShow: 2,
          slidesToScroll: 1
        }
      }
      // You can unslick at a given breakpoint now by adding:
      // settings: "unslick"
      // instead of a settings object
    ]
  });
  $('.all-category').slick({
  dots:false,
     infinite: true,
     speed: 300,
     slidesToShow: 4,
     slidesToScroll: 1,
     autoplay:false,
     autoplaySpeed: 2000,
     responsive: [
       {
         breakpoint: 1024,
         settings: {
           slidesToShow: 3,
           slidesToScroll: 1,
           infinite: true,
          dots:false,
         }
       },
       {
         breakpoint: 600,
         settings: {
           slidesToShow: 2,
           slidesToScroll: 2
         }
       },
       {
         breakpoint: 480,
         settings: {
           slidesToShow: 2,
           slidesToScroll: 1
         }
       }
       // You can unslick at a given breakpoint now by adding:
       // settings: "unslick"
       // instead of a settings object
     ]
   });


  $('.live_courses').slick({
  dots: false,
  infinite: true,
  speed: 300,
  slidesToShow: 3,
  slidesToScroll: 1,
  autoplay: true,
  autoplaySpeed: 2000,
  prevArrow: '<button type="button" class="slick-prev"><i class="bi bi-chevron-left"></i></button>',
  nextArrow: '<button type="button" class="slick-next"><i class="bi bi-chevron-right"></i></button>',
  responsive: [
    {
      breakpoint: 1299,
      settings: {
        slidesToShow: 3,
        slidesToScroll: 1,
        infinite: true,
        dots: false,
      }
    },
    {
      breakpoint: 1024,
      settings: {
        slidesToShow: 1,
        slidesToScroll: 1,
        infinite: true,
        dots: true,
      }
    },
    {
      breakpoint: 600,
      settings: {
        slidesToShow: 1,
        slidesToScroll: 1,
        dots: true,
        prevArrow: '<button type="button" class="slick-prev"><i class="bi-chevron-left"></i></button>',
        nextArrow: '<button type="button" class="slick-next"><i class="bi-chevron-right"></i></button>',
      }
    },
    {
      breakpoint: 576,
      settings: {
        slidesToShow: 1,
        slidesToScroll: 1,
        infinite: true,
        dots: true,
        prevArrow: '<button type="button" class="slick-prev"><i class="bi-chevron-left"></i></button>',
        nextArrow: '<button type="button" class="slick-next"><i class="bi-chevron-right" ></i></button>',
      }
    },
    {
      breakpoint: 480,
      settings: {
        slidesToShow: 1,
        slidesToScroll: 1,
        infinite: true,
        dots: true,
        prevArrow: '<button type="button" class="slick-prev"><i class="bi-chevron-left"></i></button>',
        nextArrow: '<button type="button" class="slick-next"><i class="bi-chevron-right"></i></button>',
      }
    }
  ]
});
  $('.specifications').slick({
  dots:false,
    infinite: true,
    speed: 300,
    slidesToShow: 1,
    slidesToScroll: 1,
    autoplay: true,
    autoplaySpeed: 2000,
    responsive: [
      {
        breakpoint: 1299,
        settings: {
          slidesToShow: 1,
          slidesToScroll: 1,
          infinite: true,
        dots:false,
        }
      },
      {
        breakpoint: 1024,
        settings: {
          slidesToShow: 1,
          slidesToScroll: 1,
          infinite: true,
        dots:false,
        }
      },
      {
        breakpoint: 600,
        settings: {
          slidesToShow: 1,
          slidesToScroll: 1
        }
      },
      {
        breakpoint: 576,
        settings: {
          slidesToShow: 1,
          slidesToScroll: 1
        }
      },
      {
        breakpoint: 480,
        settings: {
          slidesToShow: 1,
          slidesToScroll: 1
        }
      }
      // You can unslick at a given breakpoint now by adding:
      // settings: "unslick"
      // instead of a settings object
    ]
  });
  $('.live_courses-2').slick({
  dots:false,
    infinite: true,
    speed: 300,
    slidesToShow: 3,
    slidesToScroll: 1,
    autoplay: true,
    autoplaySpeed: 2000,
    responsive: [
      {
        breakpoint: 1299,
        settings: {
          slidesToShow: 3,
          slidesToScroll: 1,
          infinite: true,
        dots:false,
        }
      },
      {
        breakpoint: 1024,
        settings: {
          slidesToShow: 1,
          slidesToScroll: 1,
          infinite: true,
        dots:false,
        }
      },
      {
        breakpoint: 600,
        settings: {
          slidesToShow: 1,
          slidesToScroll: 1
        }
      },
      {
        breakpoint: 576,
        settings: {
          slidesToShow: 1,
          slidesToScroll: 1
        }
      },
      {
        breakpoint: 480,
        settings: {
          slidesToShow: 1,
          slidesToScroll: 1
        }
      }
      // You can unslick at a given breakpoint now by adding:
      // settings: "unslick"
      // instead of a settings object
    ]
  });
  $('.talk_guru').filter(function () {
    return this.children.length > 0;
  }).slick({
    dots:false,
    infinite: true,
    speed: 300,
    slidesToShow: 3,
    slidesToScroll: 1,
    autoplay:true,
    autoplaySpeed: 3000,
    responsive: [
      {
        breakpoint: 1299,
        settings: {
          slidesToShow: 3,
          slidesToScroll: 1,
          infinite: true,
        dots:false,
        }
      },
      {
        breakpoint: 1024,
        settings: {
          slidesToShow: 3,
          slidesToScroll: 1,
          infinite: true,
        dots:false,
        }
      },
      {
        breakpoint: 992,
        settings: {
          slidesToShow: 2,
          slidesToScroll: 1,
          infinite: true,
        dots:false,
        }
      },
      {
        breakpoint: 600,
        settings: {
          slidesToShow: 2,
          slidesToScroll: 1
        }
      },
      {
        breakpoint: 576,
        settings: {
          slidesToShow: 1,
          slidesToScroll: 1
        }
      },
      {
        breakpoint: 480,
        settings: {
          slidesToShow: 1,
          slidesToScroll: 1
        }
      }
      // You can unslick at a given breakpoint now by adding:
      // settings: "unslick"
      // instead of a settings object
    ]
  });

  $('.instructor').slick({
  dots:false,
    infinite: true,
    speed: 300,
    slidesToShow: 5,
    slidesToScroll: 1,
    autoplay:false,
    autoplaySpeed: 2000,
    responsive: [
      {
        breakpoint: 1299,
        settings: {
          slidesToShow: 3,
          slidesToScroll: 1,
          infinite: true,
        dots:false,
        }
      },
      {
        breakpoint: 1024,
        settings: {
          slidesToShow: 3,
          slidesToScroll: 1,
          infinite: true,
        dots:false,
        }
      },
      {
        breakpoint: 600,
        settings: {
          slidesToShow: 2,
          slidesToScroll: 2
        }
      },
      {
        breakpoint: 576,
        settings: {
          slidesToShow: 2,
          slidesToScroll: 2
        }
      },
      {
        breakpoint: 480,
        settings: {
          slidesToShow: 2,
          slidesToScroll: 2
        }
      }
      // You can unslick at a given breakpoint now by adding:
      // settings: "unslick"
      // instead of a settings object
    ]
  });

  $('.vastu').slick({
  dots:false,
    infinite: true,
    speed: 300,
    slidesToShow: 4,
    slidesToScroll: 1,
    autoplay: true,
    autoplaySpeed: 2000,
    responsive: [
      {
        breakpoint: 1299,
        settings: {
          slidesToShow: 3,
          slidesToScroll: 1,
          infinite: true,
        dots:false,
        }
      },
      {
        breakpoint: 1024,
        settings: {
          slidesToShow: 3,
          slidesToScroll: 1,
          infinite: true,
        dots:false,
        }
      },
      {
        breakpoint: 600,
        settings: {
          slidesToShow: 1,
          slidesToScroll: 1
        }
      },
      {
        breakpoint: 576,
        settings: {
          slidesToShow: 1,
          slidesToScroll: 1
        }
      },
      {
        breakpoint: 480,
        settings: {
          slidesToShow: 1,
          slidesToScroll: 1
        }
      }
      // You can unslick at a given breakpoint now by adding:
      // settings: "unslick"
      // instead of a settings object
    ]
  });







  $('.testimonials').slick({
  dots:false,
    infinite: true,
    speed: 300,
    slidesToShow: 3,
    slidesToScroll: 1,
    autoplay:true,
    autoplaySpeed: 2000,
    responsive: [
      {
        breakpoint: 1299,
        settings: {
          slidesToShow: 3,
          slidesToScroll: 1,
          infinite: true,
        dots:false,
        }
      },
      {
        breakpoint: 1024,
        settings: {
          slidesToShow: 3,
          slidesToScroll: 1,
          infinite: true,
        dots:false,
          autoplay:true
        }
      },
      {
        breakpoint: 600,
        settings: {
          slidesToShow: 2,
          slidesToScroll: 1,
            autoplay:true,
        }
      },
      {
        breakpoint: 576,
        settings: {
          slidesToShow: 1,
          slidesToScroll: 1
        }
      },
      {
        breakpoint: 480,
        settings: {
          slidesToShow: 1,
          slidesToScroll: 1,
            autoplay:true,
        }
      }
      // You can unslick at a given breakpoint now by adding:
      // settings: "unslick"
      // instead of a settings object
    ]
  });

  $('.vtestimonials').slick({
    dots:true,
    infinite: true,
    speed: 300,
    slidesToShow: 3,
    slidesToScroll: 1,
    autoplay:true,
    autoplaySpeed: 2000,
    responsive: [
      {
        breakpoint: 1024,
        settings: {
          slidesToShow: 3,
          slidesToScroll: 1,
          infinite: true,
        dots:false,
        }
      },
      {
        breakpoint: 600,
        settings: {
          slidesToShow: 2,
          slidesToScroll: 1,
            autoplay:true,
        }
      },
      {
        breakpoint: 576,
        settings: {
          slidesToShow: 1,
          slidesToScroll: 1,
            autoplay:true,
        }
      },
      {
        breakpoint: 480,
        settings: {
          slidesToShow: 1,
          slidesToScroll: 1,
            autoplay:true,
        }
      }
      // You can unslick at a given breakpoint now by adding:
      // settings: "unslick"
      // instead of a settings object
    ]
  });

  $('.vtestimonials-2 ').slick({
    dots:false,
    infinite: true,
    speed: 300,
    slidesToShow: 3,
    slidesToScroll: 1,
    autoplay: true,
    autoplaySpeed: 2000,
    responsive: [
      {
        breakpoint: 1024,
        settings: {
          slidesToShow: 3,
          slidesToScroll: 1,
          infinite: true,
        dots:false,
        }
      },
      {
        breakpoint: 600,
        settings: {
          slidesToShow: 1,
          slidesToScroll: 1,
            autoplay:true,
        }
      },
      {
        breakpoint: 576,
        settings: {
          slidesToShow: 1,
          slidesToScroll: 1,
            autoplay:true,
        }
      },
      {
        breakpoint: 480,
        settings: {
          slidesToShow: 1,
          slidesToScroll: 1,
            autoplay:true,
        }
      }
      // You can unslick at a given breakpoint now by adding:
      // settings: "unslick"
      // instead of a settings object
    ]
  });

  $('.demov').slick({
    dots:false,
    infinite: true,
    speed: 300,
    slidesToShow: 1,
    slidesToScroll: 1,
    autoplay: true,
    autoplaySpeed: 2000,
    responsive: [
      {
        breakpoint: 1024,
        settings: {
          slidesToShow: 1,
          slidesToScroll: 1,
          infinite: true,
        dots:false,
        }
      },
      {
        breakpoint: 600,
        settings: {
          slidesToShow: 1,
          slidesToScroll: 1
        }
      },
      {
        breakpoint: 576,
        settings: {
          slidesToShow: 1,
          slidesToScroll: 1
        }
      },
      {
        breakpoint: 480,
        settings: {
          slidesToShow: 1,
          slidesToScroll: 1
        }
      }
      // You can unslick at a given breakpoint now by adding:
      // settings: "unslick"
      // instead of a settings object
    ]
  });

  // $('.blogs').slick({
  // dots:false,
  //   infinite: true,
  //   speed: 300,
  //   slidesToShow: 3,
  //   slidesToScroll: 1,
  //   autoplay: true,
  //   autoplaySpeed: 2000,
  //   responsive: [
  //     {
  //       breakpoint: 1299,
  //       settings: {
  //         slidesToShow: 3,
  //         slidesToScroll: 1,
  //         infinite: true,
  //         dots:false,
  //       }
  //     },
  //     {
  //       breakpoint: 1024,
  //       settings: {
  //         slidesToShow: 3,
  //         slidesToScroll: 1,
  //         infinite: true,
  //       dots:false,
  //         autoplay:true
  //       }
  //     },
  //     {
  //       breakpoint: 600,
  //       settings: {
  //         slidesToShow: 2,
  //         slidesToScroll: 1,
  //           autoplay:true,
  //       }
  //     },
  //     {
  //       breakpoint: 576,
  //       settings: {
  //         slidesToShow: 1,
  //         slidesToScroll: 1,
  //           autoplay:true,
  //       }
  //     },
  //     {
  //       breakpoint: 480,
  //       settings: {
  //         slidesToShow: 1,
  //         slidesToScroll: 1,
  //           autoplay:true,
  //       }
  //     }
  //     // You can unslick at a given breakpoint now by adding:
  //     // settings: "unslick"
  //     // instead of a settings object
  //   ]
  // });
  $('.blogs-2').slick({
  dots:false,
    infinite: true,
    speed: 300,
    slidesToShow: 3,
    slidesToScroll: 1,
    autoplay: true,
    autoplaySpeed: 2000,
    responsive: [
      {
        breakpoint: 1299,
        settings: {
          slidesToShow: 3,
          slidesToScroll: 1,
          infinite: true,
          dots:false,
        }
      },
      {
        breakpoint: 1024,
        settings: {
          slidesToShow: 2,
          slidesToScroll: 1,
          infinite: true,
        dots:false,
          autoplay:true,
        }
      },
      {
        breakpoint: 600,
        settings: {
          slidesToShow: 1,
          slidesToScroll: 1,
            autoplay:true,
        }
      },
      {
        breakpoint: 576,
        settings: {
          slidesToShow: 1,
          slidesToScroll: 1,
            autoplay:true,
        }
      },
      {
        breakpoint: 480,
        settings: {
          slidesToShow: 1,
          slidesToScroll: 1,
            autoplay:true,
        }
      }
      // You can unslick at a given breakpoint now by adding:
      // settings: "unslick"
      // instead of a settings object
    ]
  });


  $('.astrologerReview').slick({
    dots:false,
    infinite: true,
    speed: 300,
    slidesToShow: 2,
    slidesToScroll: 2,
    autoplay: false,
    autoplaySpeed: 2000,
    responsive: [
      {
        breakpoint: 1024,
        settings: {
          slidesToShow: 2,
          slidesToScroll: 2,
          infinite: true,
        dots:false,
        }
      },
      {
        breakpoint: 600,
        settings: {
          slidesToShow: 1,
          slidesToScroll: 2,
          dots:false,
        }
      },
      {
        breakpoint: 576,
        settings: {
          slidesToShow: 1,
          slidesToScroll: 1,
          dots:false,
        }
      },
      {
        breakpoint: 480,
        settings: {
          slidesToShow: 1,
          slidesToScroll: 1,
          dots:false,
        }
      }
      // You can unslick at a given breakpoint now by adding:
      // settings: "unslick"
      // instead of a settings object
    ]
  });


  // $('.my-acount').click(function () {
  //   $('.submenu').toggle("show");

  //   $(document).on('click', function(event) {
  //     if (!$(event.target).closest('.submenu, .menu-button').length) {
  //       $('.submenu').hide(); // Hide the submenu if click is outside
  //     }
  //   });
  // });
  $('.my-account').on('click', function(event) {
    event.stopPropagation(); // Prevent the click from propagating to the document
    $('.submenu').toggle(); // Show/hide the submenu
  });

  // Close submenu when clicking outside of it
  $(document).on('click', function(event) {
    if (!$(event.target).closest('.submenu, .menu-button').length) {
      $('.submenu').hide(); // Hide the submenu if click is outside
    }
  });
  $('#user').click(function () {
    $('.popup').toggle("show");
  });
  $('.btn-close').click(function() {
    // Hide the parent element of the close button
    $(this).closest('.popup').fadeOut();
});
$('#resister').click(function(){
  $('#registration').toggle("show");
  $(this).closest('.popup').fadeOut();
});
$('#login').click(function(){
  $('.popup').toggle("show");
  $(this).closest('#registration').fadeOut();
});
$('.btn-close').click(function() {
  // Hide the parent element of the close button
  $(this).closest('.reg-popup').fadeOut();
});

// review bar
$('.bar span').hide();
  $('#bar-five').animate({
     width: '75%'}, 1000);
  $('#bar-four').animate({
     width: '35%'}, 1000);
  $('#bar-three').animate({
     width: '20%'}, 1000);
  $('#bar-two').animate({
     width: '15%'}, 1000);
  $('#bar-one').animate({
     width: '30%'}, 1000);

  setTimeout(function() {
    $('.bar span').fadeIn('slow');
  }, 1000);

  // shop listing filter code

    var $lowerSlider = $('#lower');
    var $upperSlider = $('#upper');

    $('#two').val($upperSlider.val());
    $('#one').val($lowerSlider.val());

    var lowerVal = parseInt($lowerSlider.val());
    var upperVal = parseInt($upperSlider.val());

    $upperSlider.on('input', function () {
      lowerVal = parseInt($lowerSlider.val());
      upperVal = parseInt($upperSlider.val());

      if (upperVal < lowerVal + 4) {
        $lowerSlider.val(upperVal - 4);
        if (lowerVal == $lowerSlider.attr('min')) {
          $upperSlider.val(4);
        }
      }
      $('#two').val($(this).val());
    });

    $lowerSlider.on('input', function () {
      lowerVal = parseInt($lowerSlider.val());
      upperVal = parseInt($upperSlider.val());

      if (lowerVal > upperVal - 4) {
        $upperSlider.val(lowerVal + 4);
        if (upperVal == $upperSlider.attr('max')) {
          $lowerSlider.val(parseInt($upperSlider.attr('max')) - 4);
        }
      }
      $('#one').val($(this).val());
    });

});


$('#toggleButton').click(function() {
       $('.testimonials-p').fadeToggle(300, function() {
           if ($(this).is(':visible')) {
               $('#toggleButton').text('View Less');
           } else {
               $('#toggleButton').text('View All');
           }
       });
   });




    // function logScrollHeight() {
    //     const scrollHeight = window.scrollY;
    //     const fixedColumn = document.getElementById('fixed-column');
    // $("#fixed-column").removeAttr("style");
    //     if (scrollHeight >= 540 && scrollHeight <= 1800) {
    //         fixedColumn.style.position = 'fixed';
    //         fixedColumn.style.top = '110px';
    //         fixedColumn.style.width = '29%';
    //         fixedColumn.style.zIndex = '99999';
    //     } else if(scrollHeight > 1800){
    //         fixedColumn.style.position = 'absolute';
    //         fixedColumn.style.bottom = '0';
    //         fixedColumn.style.width = '100%';
    //         fixedColumn.style.zIndex = '0';
    //     }else {
    //           fixedColumn.style.position = 'static';
    //           fixedColumn.style.width = '100%';
    //     }
    //
    //
    // }
    //
    // window.addEventListener('scroll', logScrollHeight);
    // function logScrollHeight() {
    //     const scrollHeight = window.scrollY;
    //     const fixedColumn = document.getElementById('fixed-column');
    //     $("#fixed-column").removeAttr("style"); // Reset styles

    //     if (scrollHeight >= 640 && scrollHeight <= 1800) {
    //         fixedColumn.style.position = 'fixed';
    //         fixedColumn.style.top = '100px';
    //         fixedColumn.style.width = '392px'; // Fixed position width
    //         fixedColumn.style.zIndex = '99';
    //     } else if (scrollHeight > 1800) {
    //         fixedColumn.style.position = 'absolute';
    //         fixedColumn.style.bottom = '0';
    //         fixedColumn.style.width = '392px'; // Absolute position width
    //         fixedColumn.style.zIndex = '0';
    //     } else {
    //         fixedColumn.style.position = 'static';
    //         fixedColumn.style.width = '392px'; // Static position width
    //     }
    // }

    // window.addEventListener('scroll', logScrollHeight);




    $('#toggleButtonn').click(function() {
           $('.testimonials-p').fadeToggle(300, function() {
               if ($(this).is(':visible')) {
                   $('#toggleButtonn').text('View Less');
               } else {
                   $('#toggleButtonn').text('View All');
               }
           });
       });

      $('#ref_btn').click(function() {
      $(".ref").show();
          });

          $('#renew_btn').click(function() {
          $(".renew-box").toggle();
              });

  $('#task_btn').click(function(){
    $('.task_box').toggle();
  })


  // step-form-function-user

    $("#accordian h3").click(function(){
    //slide up all the link lists
    $("#accordian ul ul").slideUp();
    //slide down the link list below the h3 clicked - only if its closed
    if(!$(this).next().is(":visible"))
    {
    $(this).next().slideDown();
    }
    });



        function showTime(){
          var date = new Date();
          var h = date.getHours();
          var m = date.getMinutes();
          var s = date.getSeconds();
          var session = "AM";
          if ( h == 0 ) {
            h = 12;
          }
          if( h >= 12 ){
             session = "PM";
             }

          if ( h > 12 ){
            h = h - 12;
          }
          m = ( m < 10 ) ? m = "0" + m : m;
          s = ( s < 10 ) ? s = "0" + s : s;

          var time = h + ":" + m + ":" + s + " " + session;
          $('#clock').html(time);
          setTimeout( showTime, 1000 );
        }
        showTime();

// today-date

 $('#todayButton').click(function() {
    var today = new Date();
    var year = today.getFullYear();
    var month = String(today.getMonth() + 1).padStart(2, '0');
    var day = String(today.getDate()).padStart(2, '0');
    var formattedDate = year + '-' + month + '-' + day;
    $('#startDate').val(formattedDate);
  });



  $(document).ready(function() {
    // Configure/customize these variables.
    var showChar = 87;  // How many characters are shown by default
    var ellipsestext = "";
    var moretext = "Read More";
    var lesstext = "Read Less";


    $('.more').each(function() {
        var content = $(this).html();

        if(content.length > showChar) {

            var c = content.substr(0, showChar);
            var h = content.substr(showChar, content.length - showChar);

            var html = c + '<span class="moreellipses">' + ellipsestext+ '&nbsp;</span><span class="morecontent"><span>' + h + '</span>&nbsp;&nbsp;<a href="" class="morelink">' + moretext + '</a></span>';

            $(this).html(html);
        }

    });

    $(".morelink").click(function(){
        if($(this).hasClass("less")) {
            $(this).removeClass("less");
            $(this).html(moretext);
        } else {
            $(this).addClass("less");
            $(this).html(lesstext);
        }
        $(this).parent().prev().toggle();
        $(this).prev().toggle();
        return false;
    });
});
function setToday() {
  const today = new Date().toISOString().split('T')[0];
  document.getElementById('date').value = today;
}

function changeDate(offset) {
  const dateInput = document.getElementById('date');
  let currentDate = dateInput.value ? new Date(dateInput.value) : new Date();
  currentDate.setDate(currentDate.getDate() + offset);
  dateInput.value = currentDate.toISOString().split('T')[0];
}
