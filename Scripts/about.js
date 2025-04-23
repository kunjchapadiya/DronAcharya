// script.js
document.addEventListener("DOMContentLoaded", function() {
    const logo = document.getElementById('brandLogo');

    // Animation function
    function animateLogo() {
        logo.style.opacity = 1;
        logo.style.transform = 'scale(1)';
    }

    // Trigger animation after a delay
    setTimeout(animateLogo, 500); // Adjust delay as needed
});

/* testimonials */
$(document).ready(function(){
    
    $('.items').slick({
  dots: true,
  infinite: true,
  speed: 800,
 autoplay: true,
 autoplaySpeed: 2000,
  slidesToShow: 4,
  slidesToScroll: 4,
  responsive: [
    {
      breakpoint: 1024,
      settings: {
        slidesToShow: 3,
        slidesToScroll: 3,
        infinite: true,
        dots: true
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
        slidesToShow: 1,
        slidesToScroll: 1
      }
    }

  ]
});
          });