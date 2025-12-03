const slides = document.querySelectorAll('.carousel-slide');
    let index = 0;

    function showSlide(i) {
      slides.forEach((slide, idx) => {
        slide.classList.toggle('active', idx === i);
      });
    }

    setInterval(() => {
      index = (index + 1) % slides.length;
      showSlide(index);
    }, 3000); // Change slide every 3 seconds 