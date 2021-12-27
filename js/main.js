$(document).ready(function(){
  $('.slick-slider').slick({
    autoplay: false,
    pauseOnHover: false,
    dots: true
  });

  $('a[href*="#"]')
  // Remove links that don't actually link to anything
  .not('[href="#"]')
  .not('[href="#0"]')
  .click(function(event) {
    // On-page links
    if (
      location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') 
      && 
      location.hostname == this.hostname
    ) {
      // Figure out element to scroll to
      var target = $(this.hash);
      target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
      // Does a scroll target exist?
      if (target.length) {
        // Only prevent default if animation is actually gonna happen
        event.preventDefault();
        $('html, body').animate({
          scrollTop: target.offset().top
        }, 1000, function() {
          // Callback after animation
          // Must change focus!
          var $target = $(target);
          $target.focus();
          if ($target.is(":focus")) { // Checking if the target was focused
            return false;
          } else {
            $target.attr('tabindex','-1'); // Adding tabindex for elements not focusable
            $target.focus(); // Set focus again
          };
        });
      }
    }
  });

  $(function () {
    $("#contactForm").on("submit", function () {
      var form = this;
      var button = $(this).find("button")
      button.prop('disabled', true);

      if ($(this).valid()) {
        $.ajax({
          url: form.action + "?form=contact",
          method: "POST",
          data: $(this).serialize(),
          success: function (r) {
            if (r === "ok") {
              $("#contactForm").prepend(
                '<div class="alert alert-success alert-dismissible fade show" role="alert">' +
                "<strong>Mensaje enviado</strong>." +
                '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
                '<span aria-hidden="true">&times;</span>' +
                "</button></div>");
              button.prop('disabled', false);
              form.reset();
              setTimeout(function() { $('.alert').alert('close'); }, 4000);
            } else {
              $("#contactForm").prepend(
                '<div class="alert alert-danger alert-dismissible fade show" role="alert">' +
                "<strong>Hubo un error al enviar el mensaje</strong>." +
                '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
                '<span aria-hidden="true">&times;</span>' +
                "</button></div>");
              button.prop('disabled', false);
            }
          }
        });
      } else {
        button.prop('disabled', false);
      }
      return false;
    })

    $("#contactForm").validate({
      rules: {
        name: "required",
        message: "required",
        email: {
          "required": true,
          "email": true
        }
      },
      messages: {
        name: "Por favor, ingresá tu nombre.",
        message: "Por favor, escribí un mensaje.",
        email: {
          "required": "Por favor, escribí un email.",
          "email": "Por favor, escribí un email válido."
        }
      }
    });
  })
});