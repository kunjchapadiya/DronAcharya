const menuBtn = document.querySelector(".header .menu-btn");
const menu = document.querySelector(".header .menu");

function toggleMenu()
{
    menuBtn.classList.toggle("active");
    menu.classList.toggle("open");
}
menuBtn.addEventListener("click", toggleMenu);

/* image Slider */
const images = [
    'Image/is1.jpg',
    'Image/is2.jpg',
    'Image/is3.jpg'
];
let currentIndex = 0;

function showSlide(index) {
    const slide = document.getElementById('slide');
    slide.src = images[index];
}

function nextSlide() {
    currentIndex = (currentIndex + 1) % images.length;
    showSlide(currentIndex);
}

function prevSlide() {
    currentIndex = (currentIndex - 1 + images.length) % images.length;
    showSlide(currentIndex);
}

// Initial display
showSlide(currentIndex);

var modal = document.getElementById("myModal");
var img = document.getElementById("img01");
var modalImg = document.getElementById("img01");
var captionText = document.getElementById("caption");

var imagess = document.querySelectorAll('.gallery img');

imagess.forEach(image => {
    image.addEventListener('click', () => {
        modal.style.display = "block";
        modalImg.src = image.src;
    });
});

var span = document.getElementsByClassName("close")[0];

span.onclick = function() {
    modal.style.display = "none";
}

/* Counting Animation */
document.addEventListener("DOMContentLoaded", () => {
    const counters = document.querySelectorAll(".count");
    const statsSection = document.querySelector(".stats-section");
  
    const observer = new IntersectionObserver((entries, observer) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          counters.forEach((counter) => {
            const target = +counter.getAttribute("data-target");
            const increment = target / 200; // Adjust for animation speed
            let count = 0;
  
            const updateCounter = () => {
              count += increment;
              if (count < target) {
                counter.textContent = Math.ceil(count);
                requestAnimationFrame(updateCounter);
              } else {
                counter.textContent = target;
              }
            };
  
            updateCounter();
          });
  
          observer.unobserve(statsSection); // Stop observing after animation
        }
      });
    }, { threshold: 0.3 }); // Trigger when 30% of the section is visible
  
    observer.observe(statsSection);
  });
  
  /* wipe-in wipe-out animations */
  const phrases = ["Modern Agriculture", "Modern Technology"];
let currentPhraseIndex = 0;

function updateText() {
    const animatedText = document.getElementById("animated-text");
    animatedText.textContent = phrases[currentPhraseIndex];
    animatedText.style.animation = "none"; // Reset animation
    void animatedText.offsetWidth; // Trigger reflow
    animatedText.style.animation = "typing 4s steps(30, end), blink 0.5s step-end infinite alternate";

    currentPhraseIndex = (currentPhraseIndex + 1) % phrases.length;
}

// Update text every 5 seconds
setInterval(updateText, 5000);

// toast message 

const toastTrigger = document.getElementById('liveToastBtn')
const toastLiveExample = document.getElementById('liveToast')

if (toastTrigger) {
  const toastBootstrap = bootstrap.Toast.getOrCreateInstance(toastLiveExample)
  toastTrigger.addEventListener('click', () => {
    toastBootstrap.show()
  })
}
