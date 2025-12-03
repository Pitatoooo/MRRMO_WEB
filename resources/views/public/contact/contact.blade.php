<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Emergency Contact Hotlines | MDRRMO-Silang</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">


    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/stylie.css') }}?v={{ filemtime(public_path('css/style.css')) }}">


  <style>
    
    :root {
      --blue-dark: #0d47a1;
      --blue-main: #1976d2;
      --blue-light: #bbdefb;
      --white: #fff;
    }

header {
  background: linear-gradient(to right, #031273, #2196F3);
  color: var(--white);
  text-align: center;
  padding: 20px 10px;
  margin: 4px 0;
  line-height: 1.4;
}

header h1 {
  margin: 0;
  font-size: 2rem;
}

header p {
  margin-top: 8px;
  font-size: 1rem;
  opacity: 0.9;
}

main {
  max-width: 1100px;
  margin: 30px auto;
  padding: 0 20px;
  flex: 1;
}

/* Base Grid Layout */
.grid {
  display: grid;
  grid-template-columns: 1fr; /* default = 1 column for mobile */
  gap: 20px;
}

/* Tablet (≥577px) */
@media (min-width: 577px) and (max-width: 991px) {
  .grid {
    grid-template-columns: repeat(2, 1fr); /* 2 columns on medium screens */
  }
}

/* Desktop (≥992px) */
@media (min-width: 992px) {
  .grid {
    grid-template-columns: repeat(3, 1fr); /* 3 columns on larger screens */
  }
}

.hotline-card {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  border-radius: 10px;
  padding: 15px 20px;
  background: linear-gradient(
    to right,
    rgba(3, 18, 115, 0.85),  
    rgba(3, 18, 115, 0.577)
  );
  color: #ffffff;
  transition: background 0.3s, box-shadow 0.3s, transform 0.3s;
}

.hotline-card h1 {
  margin: 0;
  font-size: 1.1rem;
  font-weight: 700;
}

.hotline-card .numbers {
  text-align: right;
}

.hotline-card .numbers p {
  margin: 0;
  font-size: 0.95rem;
}

/* Extra tweak for very small devices */
@media (max-width: 400px) {
  header h1 {
    font-size: 1.5rem;
  }
  header p {
    font-size: 0.9rem;
  }
  .hotline-card h1 {
    font-size: 1rem;
  }
  .hotline-card .numbers p {
    font-size: 0.85rem;
  }
}



   /* ----------------------------------------------------------FOOTER ------------------------------------------------------*/
footer {
  background: linear-gradient(to right, #031273, #2196F3);
  color: #fff;
  text-align: center;
  padding: 20px;
  margin: 4px 0;
  line-height: 1.4; /* ✅ normal readable spacing */
  font-size: 14px; /* ✅ default font size */
  margin-top: 10%;
}
  .footer-logos {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 15px; /* space between logos */
    margin-bottom: 10px;
    margin-top: 10px;
  }

  .footer-logo {
    width: 50px;
    height: 50px;
    object-fit: cover;
    border-radius: 50%;
    background: var(--white);
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
  }

/* 📱 Mobile optimization */
@media (max-width: 768px) {
  footer {
    line-height: 1.6; /* more breathing room */
    font-size: 12px;  /* smaller text to avoid overlap */
    padding: 15px;   /* slightly tighter padding */
  }

  .footer-logos {
    flex-wrap: wrap; /* ✅ makes logos go to next line instead of squishing */
    gap: 10px;
  }
}

  </style>
</head>
<body>

 <section class="showcase fade-in">
    <div class="logo">
      <img src="{{ asset('image/mdrrmologo2.png') }}" alt="MDRRMO Logo">
    </div>
    <div class="text-content">
      <p>THE OFFICIAL WEBSITE OF THE</p>
      <h1>SILANG DISASTER RISK REDUCTION MANAGEMENT OFFICE</h1>
    </div>
  </section>

  <div class="emptybox"></div>


<!-- ✅ Placeholder for navbar height when fixed -->
<!-- <div class="nav-placeholder"></div> -->

<!-- ✅ Navbar -->
<!-- <nav class="custom-navbar d-flex justify-content-between align-items-center flex-wrap fade-in-up"> -->
  
  <!-- ✅ Burger Button (only visible on mobile) -->
  <!-- <div class="burger" id="burger">
    <span></span>
    <span></span>
    <span></span>
  </div>

  <ul class="d-flex list-unstyled m-0 flex-wrap nav-links">
    <li>
      <a href="{{ url('/') }}" class="{{ request()->is('/') ? 'active' : '' }}">
        Home
      </a>
    </li>
    <li>
      <a href="{{ url('/services') }}" class="{{ request()->is('services') ? 'active' : '' }}">
        Services
      </a>
    </li>
  </ul>

  <ul class="d-flex list-unstyled m-0 nav-links">
    <li>
        <p>August 12, 2025 | 16:56</p>
    </li>
    <li>
      <a href="{{ url('/contact') }}" class="contact-link {{ request()->is('contact') ? 'active' : '' }}">
        Contact
      </a>
    </li>
  </ul>
</nav> -->

<div class="nav-placeholder"></div>

<nav class="navbar navbar-expand-lg navbar-dark py-3" style="background-color:#031273;">
  <div class="container-fluid">

    <!-- Brand on the left -->
 <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
  <img src="{{ asset('image/mdrrmologo2.png') }}" alt="MDRRMO Logo" class="navbar-logo">
</a>


    <!-- Centered nav links (inside collapse) -->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent"
      aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse justify-content-center" id="navbarContent">
     <ul class="navbar-nav mx-auto " style="width: 300px; display: flex; justify-content: space-between;">

    <li class="nav-item">
        <a class="nav-link" href="{{ url('/') }}">Home</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ url('/services') }}">Services</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ url('/contact') }}">Contact</a>
    </li>
    </ul>

    </div>

    <!-- Date/time always visible on right (outside collapse) -->
    <span class="navbar-text text-white ms-auto" id="datetime"></span>

  </div>
</nav>
  <!-- <header>
    <h1>Emergency Contact Hotlines</h1>
    <p>Stay safe everyone — save these numbers for urgent situations</p>
  </header> -->

  <main>
    <div class="grid">
  <div class="hotline-card">
    <h1>MDRRMO-Silang</h1>
    <div class="numbers">
      <p>Globe: 0935-601-6738</p>
      <p>Smart: 0922-384-6130</p>
    </div>
  </div>

  <div class="hotline-card">
    <h1>MDRRMO Command Center</h1>
    <div class="numbers">
      <p>Globe: 0917-169-5681</p>
      <p>LandLine: (046) 414-3776</p>
    </div>
  </div>

  <div class="hotline-card">
    <h1>Silang PNP</h1>
    <div class="numbers">
      <p>(046) 345-6789</p>
    </div>
  </div>

  <div class="hotline-card">
    <h1>Silang BFP</h1>
    <div class="numbers">
      <p>(046) 456-7890</p>
    </div>
  </div>
</div>

  </main>

<footer>
    <p>This is the official Website of Silang Disaster Risk Reduction Management Office</p>
    <p>&copy; 2025 MDRRMO-Silang | All Rights Reserved</p>
    <div class="footer-logos">
        <img src="{{ asset('image/mdrrmologo.png') }}" alt="MDRRMO Logo" class="footer-logo">
        <img src="{{ asset('image/mdrrmologo2.png') }}" alt="PNP Logo" class="footer-logo">
        <img src="{{ asset('image/mdrrmologo2.png') }}" alt="BFP Logo" class="footer-logo">
    </div>
</footer>



 <!-- ✅ Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
  <!-- ✅ AOS Script -->
  <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
  <script src="{{ asset('js/script.js') }}"></script>

<script>
  AOS.init({
    duration: 800,    // animation duration
    once: false,      // allow it to animate every time in/out
    mirror: true      // ✅ animate out when scrolling up
  });



// when refreshing it will go to the top 
    document.addEventListener("DOMContentLoaded", function () {
    window.scrollTo(0, 0);
  });





// document.addEventListener('DOMContentLoaded', function () {
//   const navbar = document.querySelector('.custom-navbar');
//   const placeholder = document.querySelector('.nav-placeholder');

//   if (!navbar || !placeholder) return;

//   // Measurement
//   let originalTop = 0;
//   function recalc() {
//     // temporarily remove fixed to measure correct position
//     navbar.classList.remove('is-fixed', 'hidden');
//     placeholder.style.display = 'none';
//     placeholder.style.height = navbar.offsetHeight + 'px';
//     // distance from doc top to navbar top
//     originalTop = navbar.getBoundingClientRect().top + window.pageYOffset;
//   }

//   recalc();

//   // Scroll state
//   let lastScroll = window.pageYOffset || document.documentElement.scrollTop;
//   let ticking = false;

//   function update() {
//     const current = window.pageYOffset || document.documentElement.scrollTop;
//     const isFixed = current >= originalTop;

//     if (isFixed) {
//       // Fixed mode — show and disable hide-on-scroll
//       if (!navbar.classList.contains('is-fixed')) {
//         navbar.classList.add('is-fixed');
//         placeholder.style.display = 'block';
//       }
//       // ensure visible
//       navbar.classList.remove('hidden');
//     } else {
//       // Not fixed — use hide-on-scroll logic
//       if (navbar.classList.contains('is-fixed')) {
//         navbar.classList.remove('is-fixed');
//         placeholder.style.display = 'none';
//       }

//       // hide when scrolling down, show when scrolling up
//       if (current > lastScroll && current > 50) {
//         // scrolling down
//         navbar.classList.add('hidden');
//       } else {
//         // scrolling up
//         navbar.classList.remove('hidden');
//       }
//     }

//     lastScroll = current <= 0 ? 0 : current;
//     ticking = false;
//   }

//   window.addEventListener('scroll', function () {
//     if (!ticking) {
//       window.requestAnimationFrame(update);
//       ticking = true;
//     }
//   }, { passive: true });

//   window.addEventListener('resize', function () {
//     recalc();
//     // run an update after recalculating
//     update();
//   });

//   // initial run
//   update();
// });


// MY NAVBAR CSS------------------------------===================================================================

/* base */
//   .custom-navbar {
//       background: linear-gradient(
//       to right,
//       rgba(3, 18, 115, 0.936),  
//       rgba(3, 18, 115, 0.342)
//     ) !important;
//     color: #ffffff;
//     backdrop-filter: none !important;
//     -webkit-backdrop-filter: none !important;
//     filter: none !important;
//     transition: background 0.3s, box-shadow 0.3s, transform 0.3s;
//     backdrop-filter: none !important;
//     -webkit-backdrop-filter: none !important;
//     filter: none !important;


//     padding: 16px 24px;
//     display: flex;
//     justify-content: space-between;
//     align-items: center;
//     flex-wrap: wrap;
//     position: relative;
//     transition: transform 240ms ease, opacity 180ms ease, box-shadow 180ms ease;
//     will-change: transform;
//     transition: top 0.4s ease, opacity 0.4s ease;

//       z-index: 9999 !important;
//     position: relative;
//   }

//   /* hidden (hide-on-scroll) — only used in non-fixed mode */
//   .custom-navbar.hidden {
//     transform: translateY(-100%);
//     opacity: 0;
//     pointer-events: none;

//     top: -100px;
//   }

//   /* fixed at top */
//   .custom-navbar.is-fixed {
//     position: fixed !important;
//     top: 0;
//     left: 0;
//     right: 0;
//     width: 100%;
//     z-index: 99999 !important; /* way above carousel */
//      box-shadow: 0 6px 18px rgba(0,0,0,0.08);
//     /* force visible when fixed (override other animations) */
//     transform: none !important;
//     opacity: 1 !important;
//     animation: none !important;
//     pointer-events: auto !important;
//   }

//   /* placeholder to avoid layout jump */
//   .nav-placeholder {
//     display: none;
//     width: 100%;
//     height: 0;
//   }
//   /* Ensure fixed nav stays visible */
//   .custom-navbar.always-visible {
//     transform: translateY(0) !important;
//     opacity: 1 !important;
//     visibility: visible !important;
//   }

//   .custom-navbar ul {
//     display: flex;
//     list-style: none;
//     margin: 0;
//     padding: 0;
//     gap: 16px;
//   }

//   .custom-navbar .brand {
//     font-size: 1.25rem;
//     font-weight: bold;
//   }

//   .custom-navbar a {
//     background-color: #fcfeff;
//     position: relative;
//     color: rgb(0, 0, 0);
//     text-decoration: none !important;  /* ✅ removes underline always */
//     margin-left: 16px;
//     padding: 8px 12px;
//     border: 3px solid #000000;
//     border-radius: 10px;
//     overflow: hidden;
//     z-index: 1;
//     transition: background-color 0.3s ease, color 0.3s ease;
//   }

//   /* Smooth background hover */
//   .custom-navbar a:hover,
//   .custom-navbar a.active {
//     background-color: #031273;
//     color: white;
//     text-decoration: none !important;  /* ✅ ensures no underline on hover */
//   }

//   /* Burger Icon Style */
// .burger {
//   display: none;
//   flex-direction: column;
//   cursor: pointer;
//   gap: 5px;
//   padding: 3px;
// }

// .burger span {
//   width: 25px;
//   height: 3px;
//   background: #333;
//   border-radius: 3px;
// }

// Mobile View Setup
// @media (max-width: 768px) {
//   /* ✅ Center burger icon */
//   .burger {
//     display: flex;
//     flex-direction: column;
//     align-items: center;
//     margin: 10px auto; /* centers horizontally */
//   }

//   /* ✅ Hide links by default, show on toggle */
//   .nav-links {
//     display: none !important;
//     flex-direction: row !important; /* horizontal layout */
//     justify-content: center;
//     align-items: center;
//     gap: 5px;
//     width: 100%;
//   }

//   .nav-links.active {
//     display: flex !important;
//   }

//   /* ✅ Make buttons smaller */
//   .nav-links li {
//     flex: 0 1 auto;
//   }

//   .nav-links li a {
//     padding: 6px 10px;
//     font-size: 14px;
//     border-radius: 6px;
//     white-space: nowrap;
//   }

//   /* ✅ Remove weird width restrictions from ul in mobile */
//   .custom-navbar ul {
//     flex-direction: row;
//     justify-content: center;
//     width: auto;
//     gap: 8px;
//     margin: 0;
//   }
// }


// let prevScrollPos = window.pageYOffset;
// const navbar = document.querySelector("nav");

// window.addEventListener("scroll", () => {
//   const currentScrollPos = window.pageYOffset;

//   // Run hide-on-scroll ONLY if navbar is fixed
//   if (navbar.classList.contains("is-fixed")) {
//     if (prevScrollPos > currentScrollPos) {
//       // scrolling up
//       navbar.style.top = "0";
//     } else {
//       // scrolling down
//       navbar.style.top = "-100px"; // hide navbar
//     }
//   } else {
//     // If not fixed, always show
//     navbar.style.top = "0";
//   }

//   prevScrollPos = currentScrollPos;
// });



// // burger style nav bar
//   document.getElementById('burger').addEventListener('click', function() {
//     document.querySelectorAll('.nav-links').forEach(ul => {
//       ul.classList.toggle('active');
//     });
//   });

</script>


<script>
    
const navbar = document.querySelector('.navbar');
const navbarOffsetTop = navbar.offsetTop;
let lastScrollTop = 0;

window.addEventListener('scroll', () => {
  const scrollTop = window.pageYOffset || document.documentElement.scrollTop;

if (scrollTop > lastScrollTop && scrollTop > navbarOffsetTop) {
  navbar.classList.add('fixed', 'hidden');
} else if (scrollTop < lastScrollTop) {
  if (scrollTop > navbarOffsetTop) {
    navbar.classList.add('fixed');
    navbar.classList.remove('hidden');
  } else {
    navbar.classList.remove('fixed', 'hidden');
  }
}

  lastScrollTop = scrollTop <= 0 ? 0 : scrollTop;
});
  </script>

  
<script>
  document.addEventListener("DOMContentLoaded", function () {
  const faders = document.querySelectorAll('.fade-in');

  const options = {
    root: null,           // viewport
    rootMargin: '0px 0px -100px 0px', // trigger slightly before element fully visible
    threshold: 0          // trigger even if tiny bit visible
  };

  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('visible');
      } else {
        entry.target.classList.remove('visible');
      }
    });
  }, options);

  faders.forEach(el => observer.observe(el));
});

</script>
<script>
  function updateDateTime() {
    const now = new Date();
    const formatted = now.toLocaleString('en-US', {
      weekday: 'short',
      year: 'numeric',
      month: 'short',
      day: '2-digit',
      hour: '2-digit',
      minute: '2-digit',
      second: '2-digit',
      hour12: true
    });
    document.getElementById('datetime').textContent = formatted;
  }

  setInterval(updateDateTime, 1000);
  updateDateTime(); // initial call
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>


</body>
</html>