<!DOCTYPE html>
<html>
   <head>
      <meta charset="UTF-8" />
      <meta name="viewport" content="width=device-width, initial-scale=1.0" />
      <title>{{$page->title}}</title>
      <meta name="description" content="{{$page->meta_description}}"/>
      <meta name="keywords" content="{{$page->meta_keywords}}"/>
      <link rel="stylesheet" href="{{ asset('landing/landing.css') }}">
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous"/>
      {!! $page->header_script !!}
      {!! $page->style !!}
      <style>
         {!! $page->css !!}

         #citySuggestions {
              max-height: 200px; /* adjust height as needed */
              overflow-y: auto;
              border: 1px solid #ddd; /* optional for better visibility */
              border-top: none; /* optional: if placed under input */
          }

          /* Style the "fake" placeholder */
          input[data-empty="true"]::before {
            content: attr(data-placeholder);
            color: #888;
            position: absolute;
          }

          /* Fallback for older mobile browsers: just apply muted color */
          input[data-empty="true"] {
            color: #aaa;
          }
      </style>
   </head>
   <body>
      {!! $page->html !!}
      {!! $page->content !!}
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous" ></script>
      <script src="{{ asset('landing/landing.js') }}"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
      {!! $page->footer_script !!}


      <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
         <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
               <div class="modal-header">
                  <h1 class="modal-title fs-5" id="exampleModalLabel">Enter Your Details</h1>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
               </div>
               <div class="modal-body enquiry_form">
                  <form method="POST" action="{{ route('consultation.submit') }}">
                     @csrf
                     <div class="mb-3">
                        <label for="fullName" class="form-label">Full Name<span style="color: red;">*</span></label>
                        <input type="text" class="form-control" name="fullName" id="fullName" placeholder="Enter your full name" required>
                     </div>
                     <div class="mb-3">
                        <label for="email" class="form-label">Email<span style="color: red;">*</span></label>
                        <input type="email" class="form-control" name="email" id="email" placeholder="Enter your email address" required>
                     </div>
                     <div class="mb-3">
                        <label for="mobile" class="form-label">Mobile<span style="color: red;">*</span></label>
                        <input type="tel" class="form-control" name="mobile" id="mobile" placeholder="Enter your mobile number" pattern="[0-9]{10}" maxlength="10" minlength="10" inputmode="numeric" required>
                     </div>
                     <div class="mb-3">
                        <label for="dob" class="form-label">Date of Birth</label>
                        <input type="date" class="form-control" name="dob" id="dob" max="">
                     </div>
                     <div class="mb-3">
                        <label for="birthTime" class="form-label">Birth Time</label>
                        <input type="time" class="form-control" name="birthTime" id="birthTime">
                     </div>
                     <div class="mb-3">
                        <label for="birthPlace" class="form-label">Birth Place</label>
                        <!-- <input type="text" class="form-control" name="birthPlace" id="birthPlace" placeholder="Enter your place of birth">
                        <div id="citySuggestions" class="list-group mt-1"></div> -->
                        <input type="text" class="form-control" name="birthPlace" id="birthPlace" placeholder="Enter your place of birth" onkeyup="birthplaces('#birthPlace', '#birth_place_suggestion_video')">
                        <div class="suggestion_birth" style="display: none;">
                           <ul id="birth_place_suggestion_video"></ul>
                       </div>
                     </div>
                     <div class="mb-3">
                        <label for="message" class="form-label">Message</label>
                        <textarea class="form-control" name="message" id="message" rows="4" placeholder="Enter your message"></textarea>
                     </div>
                     <input type="hidden" name="amount" id="amount" value="{{@$amount}}">
                     <input type="hidden" name="payment_status" id="payment_status" value="0">
                     <input type="hidden" name="module" id="module" value="{{@$module}}">
                     <button type="submit" class="btn btn-primary">Submit</button>
                  </form>
               </div>
            </div>
         </div>
      </div>

      <script>
         /*$(document).ready(function () {
          $('#birthPlace').on('keyup', function () {
              let query = $(this).val().trim();

              if (query.length > 2) {
                  $('#citySuggestions').html('<div class="list-group-item disabled">Searching...</div>');

                  $.ajax({
                      url: "{{ route('city.search') }}",
                      type: "GET",
                      data: { query: query },
                      success: function (data) {
                          let suggestions = $('#citySuggestions');
                          suggestions.empty();

                          if (data.length > 0) {
                              data.forEach(function (city) {
                                  let fullName = `${city.city_name}, ${city.state_name}, ${city.country_name}`;
                                  suggestions.append(`
                                      <a href="#" class="list-group-item list-group-item-action city-item">
                                          ${fullName}
                                      </a>
                                  `);
                              });
                          } else {
                              suggestions.append('<div class="list-group-item disabled">No matches found</div>');
                          }
                      },
                      error: function () {
                          $('#citySuggestions').html('<div class="list-group-item disabled">Error loading results</div>');
                      }
                  });
              } else {
                  $('#citySuggestions').empty();
              }
           });

          // Handle city selection
             $(document).on('click', '.city-item', function (e) {
                 e.preventDefault();
                 $('#birthPlace').val($(this).text());
                 $('#citySuggestions').empty();
             });
         });*/


         document.getElementById('mobile').addEventListener('input', function (e) {
            // Remove non-digits
            let cleaned = this.value.replace(/[^0-9]/g, '');

            // Enforce first digit 6–9
            if (cleaned.length > 0 && !/^[6-9]/.test(cleaned)) {
                cleaned = cleaned.slice(1); // remove invalid first digit
            }

            // Limit to 10 digits
            this.value = cleaned.slice(0, 10);
        });

         document.addEventListener('DOMContentLoaded', function () {
             const today = new Date().toISOString().split('T')[0];
             document.getElementById('dob').setAttribute('max', today);
         });



         var typingTimer;
         function birthplaces(inputId, suggestionId) {
            clearTimeout(typingTimer);
            typingTimer = setTimeout(function () {
              var inputVal = $(inputId).val();

              if (inputVal.length > 2) {
                $(suggestionId).html('<li>Searching...</li>');
                $('.suggestion_birth').show();

                $.ajax({
                  url: '/search-location/' + encodeURIComponent(inputVal),
                  method: 'GET',
                  success: function (response) {
                    $(suggestionId).empty();

                    if (response.length > 0) {
                      response.forEach(function (location) {
                        var li = $('<li></li>');
                        li.text(location.place + ', ' + location.state + ', ' + location.country);
                        li.on('click', function () {
                          $(inputId).val($(this).text());
                          $(suggestionId).empty();
                          $('.suggestion_birth').hide();
                        });
                        $(suggestionId).append(li);
                      });
                      $('.suggestion_birth').show();
                    } else {
                      $('.suggestion_birth').hide();
                    }
                  },
                  error: function () {
                    $(suggestionId).html('');
                    $('.suggestion_birth').hide();
                  }
                });
              } else {
                $(suggestionId).empty();
                $('.suggestion_birth').hide();
              }
            }, 1000);
          }

          function isMobileDevice() {
    return /iPhone|iPad|iPod|Android/i.test(navigator.userAgent);
  }

  function applyFakePlaceholder(inputId, fakeText) {
    const input = document.getElementById(inputId);

    // For type="date" and type="time", value will be empty unless user selects
    if (isMobileDevice()) {
      input.dataset.placeholder = fakeText;

      // Set fake placeholder if input is empty
      if (!input.value) {
        input.classList.add('text-muted');
        input.setAttribute('data-empty', 'true');
      }

      // Style using CSS
      input.addEventListener('focus', function () {
        input.classList.remove('text-muted');
        input.removeAttribute('data-empty');
      });

      input.addEventListener('blur', function () {
        if (!input.value) {
          input.classList.add('text-muted');
          input.setAttribute('data-empty', 'true');
        }
      });
    }
  }

  applyFakePlaceholder("dob", "dd/mm/yyyy");
  applyFakePlaceholder("birthTime", "hh:mm");


      </script>



   </body>
</html>
