<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>MDRRMO-Silang Information System</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- ✅ AOS CSS -->
  <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">

  <!-- Your custom CSS -->
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

   <!-- ✅ External BOOTSTRAP -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- AOS Animation CSS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" rel="stylesheet" />


  <!-- ✅ Your Custom CSS -->
<link rel="stylesheet" href="{{ asset('css/style.css') }}?v={{ filemtime(public_path('css/style.css')) }}">
<!-- GOOGLE FONTS -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

  <!-- Ambulance Widget Styles -->
  <style>
    /* Ensure ambulance widget is clickable */
    .ambulance-widget {
      z-index: 10 !important;
    }
    
    .ambulance-widget button {
      z-index: 11 !important;
    }
    
    .ambulance-toggle {
      z-index: 10 !important;
    }
  </style>

</head>

<body>
  <div class="showcase-carousel">
  <!-- ✅ Showcase Section (on top of carousel) -->
    <section class="showcase showcase-banner" style="border-radius: 0 !important;">
      <div class="logo logo-left">
        <img src="{{ asset('image/mdrrmologo2.png') }}" alt="Left Logo">
      </div>
      <div class="text-content">
        <h1 class="desktop-text"><b>THE OFFICIAL WEBSITE OF THE SILANG DISASTER RISK REDUCTION MANAGEMENT OFFICE</b></h1>
        <h1 class="desktop-text" style="  font-size: 1vw; line-height: 0.8 !important;"><b> LandLine : (046) 414-3776   |   </b> <a href="https://www.facebook.com/silang.drrmo.7"> <b> FB : SILANG DRRMO</b></a></h1>
        <p class="mobile-text"><b>THE OFFICIAL WEBSITE OF THE SDRRMO </b></p>

      </div>
      <div class="logo logo-right">
        <img src="{{ asset('image/mdrrmologo.png') }}" alt="Right Logo">
      </div>
    </section>
    <div class="nav-placeholder"></div>

<nav class="navbar navbar-expand-lg navbar-dark py-3" style="background: linear-gradient( to right,rgb(13, 16, 64),rgba(7, 27, 51, 0.851)) !important; border-top-style: solid; border-top-width: 4px; border-top-color: #000000;">
  <div class="container-fluid">

    <!-- Brand on the left
 <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
  <img src="{{ asset('image/mdrrmologo2.png') }}" alt="MDRRMO Logo" class="navbar-logo">
</a> -->


    <!-- Centered nav links (inside collapse) -->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent"
      aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse justify-content-center" id="navbarContent">
     <ul class="navbar-nav mx-auto " style="width: 400px; display: flex; justify-content: space-between;">

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
  <!-- ✅ Carousel -->
<div id="carouselExampleAutoplaying" class="carousel slide carousel-fade custom-carousel fade-in-up fade-delay-2" data-bs-ride="carousel" data-bs-interval="3000" data-bs-pause="hover" style="height:50vh; max-height:50vh; overflow:hidden; margin:1% 1% 0% 0%; padding-right: 1%; padding-left: 1%; border-radius:20px;">
  <div class="carousel-inner" style="height:50vh;">
    @foreach($carousels as $key => $item)
    <div class="carousel-item {{ $key == 0 ? 'active' : '' }} position-relative" style="height:50vh;">
      <img src="{{ asset('image/' . $item->image) }}" class="d-block w-100" alt="Carousel Image" style="height:50vh; object-fit:cover; border-radius:20px;">

      <!-- Overlay content: title/description left + ambulance widget right -->
      <div class="position-absolute top-0 start-0 w-100 h-100" style="pointer-events:none; border-radius:20px; overflow:hidden;">
        <!-- dim image so text is readable -->
        <div style="position:absolute; inset:0; background:linear-gradient(to right, rgba(3, 18, 115, 0.35), rgba(3, 18, 115, 0.19)); pointer-events:none; border-radius:inherit;"></div>

        <!-- Title and description (left) -->
        <div style="position:absolute; left:150px; top:30px; max-width:620px; text-align:left; pointer-events:auto; color:#fff; text-shadow:0 2px 8px rgba(0,0,0,.7);">
          <div style="font-size:40px; font-weight:800; margin-bottom:8px;">{{ $item->title ?? 'About MDRRMO' }}</div>
          <div style="font-size:20px; font-weight:600; opacity:.95;">{{ Str::limit($item->caption ?? 'No description provided.', 140) }}</div>
        </div>

        <!-- Ambulance widget (right) -->
        <div class="ambulance-widget" id="ambulanceWidget-{{ $item->id ?? $key }}" style="pointer-events:auto; position:absolute; right:20px; top:20px; background:linear-gradient(135deg,#ffcc99,#d87916); border-radius:18px; padding:14px 18px; box-shadow:0 6px 16px rgba(0,0,0,.35); border:6px solid #000; min-height:240px; min-width:240px; max-width:240px; z-index: 10;">
        <button type="button" aria-label="Close" class="btn p-0" style="font-weight:800; font-size:20px; line-height:1; color:#000; background:transparent; left:208px; top:2px; position:absolute; z-index: 11;" onclick="window.hideAmbulanceWidgets()">×</button>
        <div class="d-flex justify-content-between align-items-start" style="gap:10px; text-align: center; margin: 15px 15px 0px 10px;">
            <div style="font-weight:800; font-size:24px; line-height:1.2; color:#000; text-align: center; margin: 15px 15px 0px 20px;">Ambulance<br>Available:</div>
          </div>
          <div class="text-center" style="font-size:70px; font-weight:900; margin-top:6px; color:#000;">{{ \App\Models\Ambulance::count() }}</div>

        </div>
        <!-- Toggle to show ambulance widget when hidden (right) -->
        <button id="ambulanceToggle-{{ $item->id ?? $key }}" class="btn btn-sm ambulance-toggle" style="display:none; position:absolute; right:20px; top:20px; background:#000; color:#fff; border-radius:16px; padding:12px 20px; pointer-events:auto; z-index: 10;" onclick="window.showAmbulanceWidgets()">Show Ambulance</button>
      </div>


      
      
    </div>
    @endforeach
  </div>
</div>


 
 
 
 
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
</div>





<div class="parallax"></div>
<main>
<div class="parallax0">

<!-- ✅ Officials -->
<div id="officials" class="officials-wrapper position-relative" style="height:100px;">
<!-- <img src="{{ asset('image\mv2.jpg') }}" alt="Officials Background" class="officials-bg-img" /> -->
<div class="flex-container-headings" data-aos="fade-up">
<h2>Officials</h2>
</div>
<div class="card-container" data-aos="fade-up">
@foreach ($officials as $official)
<div class="card">
  <img src="{{ asset('image/' . $official->image) }}" alt="{{ $official->name }}">
  <h3>{{ $official->name }}</h3>
  <p>{{ $official->position }}</p>
</div>
@endforeach
</div>
</div>

</div>
</main>
<div class="parallax"></div>
<main>
<div class="parallax2">
<!-- class="mission-vision-wrapper -->
<div id="mission-vision" class="mv-wrapper position-relative">
  <!-- <img src="{{ asset('image\mv2.jpg') }}" alt="Mission Vision Background" class="mv-bg-img" /> -->
  <div class="container">
    <div class="row justify-content-center align-items-stretch g-4">
      <!-- Mission Card -->
       
      <div class="col-md-6  pad">
      <h2 class="mv-title mb-3 fw-bold text-uppercase" data-aos="fade-up">Mission</h2>

        <div class="mv-card h-100 shadow-lg p-4 bg-white rounded-4 d-flex flex-column align-items-center text-center position-relative"  data-aos="fade-up">
          <div class="mv-divider mb-3"></div>
          <p class="mv-text fs-5">{{ $missionVision->mission ?? 'No mission posted yet.' }}</p>
        </div>
      </div>
      <!-- Vision Card -->
      <div class="col-md-6 pad">
      <h2 class="mv-title mb-3 fw-bold text-uppercase" data-aos="fade-up">Vision</h2>

        <div class="mv-card h-100 shadow-lg p-4 bg-white rounded-4 d-flex flex-column align-items-center text-center position-relative" data-aos="fade-up">
          <div class="mv-divider mb-3"></div>
          <p class="mv-text fs-5">{{ $missionVision->vision ?? 'No vision posted yet.' }}</p>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- End Mission & Vision Section -->
</div>
</main>



<!-- <hr data-aos="fade-up"> -->

    <!-- ✅ About MDRRMO -->
<div id="about-mdrrmo" class="about-wrapper position-relative" style="border-bottom: 6px solid #000000;">
  <!-- <img src="{{ asset('image/mv2.jpg') }}" alt="About MDRRMO Background" class="about-bg-img" /> -->
  <div class="flex-container-headings about-trainings-headings" data-aos="fade-up">
    <h2>ABOUT MDRRMO</h2>
  </div>
  <div class="container" data-aos="fade-up">
    <div class="about-slider">
      <div class="about-track" id="aboutTrack">
        @foreach ($about as $entry)
          <div class="about-item">
              <div class="about-card-media">
                @if ($entry->image)
                  <img src="{{ asset('image/' . $entry->image) }}" alt="About Image"/>
                @else
                  <img src="{{ asset('image/test.png') }}" alt="About Image"/>
                @endif
              </div>
              <div class="about-card-title">{{ $entry->title ?? 'TITLE' }}</div>
            </a>
          </div>
        @endforeach
      </div>
    </div>
  </div>
</div>

    <!-- ✅ Trainings -->
<div id="trainings" class="trainings-wrapper position-relative">
  <!-- <img src="{{ asset('image\1754726422_example.jpg') }}" alt="Trainings Background" class="trainings-bg-img" /> -->
  <div class="flex-container-headings about-trainings-headings" data-aos="fade-up">
    <h2>Trainings We Offer</h2>
  </div>
  <div class="container" data-aos="fade-up">
    <div class="row justify-content-center g-4 about-grid">
      @foreach ($trainings as $item)
        <div class="col-12 col-md-4 d-flex justify-content-center">
          <div class="about-card">
            <div class="about-card-media">
              @if ($item->image)
                <img src="{{ asset('image/' . $item->image) }}" alt="Training Image"/>
              @else
                <img src="{{ asset('image/test.png') }}" alt="Training Image"/>
              @endif
            </div>
            <div class="about-card-title">{{ $item->title ?? 'TITLE' }}</div>
          </div>
        </div>
      @endforeach
    </div>
  </div>
</div>
  </main>
<!-- About cards now link to a dedicated page; modal removed -->
  <!-- ✅ Footer -->

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


document.addEventListener('DOMContentLoaded', function () {
  const navbar = document.querySelector('.custom-navbar');
  const placeholder = document.querySelector('.nav-placeholder');

  if (!navbar || !placeholder) return;

  // Measurement
  let originalTop = 0;
  function recalc() {
    // temporarily remove fixed to measure correct position
    navbar.classList.remove('is-fixed', 'hidden');
    placeholder.style.display = 'none';
    placeholder.style.height = navbar.offsetHeight + 'px';
    // distance from doc top to navbar top
    originalTop = navbar.getBoundingClientRect().top + window.pageYOffset;
  }

  recalc();

  // Scroll state
  let lastScroll = window.pageYOffset || document.documentElement.scrollTop;
  let ticking = false;

  function update() {
    const current = window.pageYOffset || document.documentElement.scrollTop;
    const isFixed = current >= originalTop;

    if (isFixed) {
      // Fixed mode — show and disable hide-on-scroll
      if (!navbar.classList.contains('is-fixed')) {
        navbar.classList.add('is-fixed');
        placeholder.style.display = 'block';
      }
      // ensure visible
      navbar.classList.remove('hidden');
    } else {
      // Not fixed — use hide-on-scroll logic
      if (navbar.classList.contains('is-fixed')) {
        navbar.classList.remove('is-fixed');
        placeholder.style.display = 'none';
      }

      // hide when scrolling down, show when scrolling up
      if (current > lastScroll && current > 50) {
        // scrolling down
        navbar.classList.add('hidden');
      } else {
        // scrolling up
        navbar.classList.remove('hidden');
      }
    }

    lastScroll = current <= 0 ? 0 : current;
    ticking = false;
  }

  window.addEventListener('scroll', function () {
    if (!ticking) {
      window.requestAnimationFrame(update);
      ticking = true;
    }
  }, { passive: true });

  window.addEventListener('resize', function () {
    recalc();
    // run an update after recalculating
    update();
  });

  // initial run
  update();
});




// burger style nav bar
  document.getElementById('burger').addEventListener('click', function() {
    document.querySelectorAll('.nav-links').forEach(ul => {
      ul.classList.toggle('active');
    });
  });
</script>




<script>
    
const navbar = document.querySelector('.navbar');
const navbarOffsetTop = navbar.offsetTop;
let lastScrollTop = 0;

window.addEventListener('scroll', () => {
  const scrollTop = window.pageYOffset || document.documentElement.scrollTop;

  if (scrollTop >= navbarOffsetTop) {
    // Once it reaches top → stick
    navbar.classList.add('fixed');

    if (scrollTop > lastScrollTop) {
      // scrolling down → hide
      navbar.classList.add('hidden');
    } else {
      // scrolling up → show
      navbar.classList.remove('hidden');
    }
  } else {
    // Back under carousel → reset
    navbar.classList.remove('fixed', 'hidden');
  }

  lastScrollTop = scrollTop <= 0 ? 0 : scrollTop;
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





  window.hideAmbulanceWidgets = function () {
    document.querySelectorAll('.ambulance-widget').forEach(widget => {
      widget.style.display = 'none';
    });
    document.querySelectorAll('.ambulance-toggle').forEach(toggle => {
      toggle.style.display = 'block';
    });
  };

  window.showAmbulanceWidgets = function () {
    document.querySelectorAll('.ambulance-widget').forEach(widget => {
      widget.style.display = 'block';
    });
    document.querySelectorAll('.ambulance-toggle').forEach(toggle => {
      toggle.style.display = 'none';
    });
  };
</script>

<script>
  // About slider autoplay and controls
  (function(){
    const track = document.getElementById('aboutTrack');
    if (!track) return;

    const getStep = () => {
      const item = track.querySelector('.about-item');
      if (!item) return Math.min(320, track.clientWidth * 0.4);
      const rect = item.getBoundingClientRect();
      return Math.max(160, Math.min(rect.width + 16, track.clientWidth * 0.5));
    };

    const goNext = () => {
      // Scroll by exactly one item width so arrows move box-by-box
      const firstVisible = track.querySelector('.about-item');
      const step = firstVisible ? (firstVisible.getBoundingClientRect().width + 16) : getStep();
      const maxLeft = Math.max(0, track.scrollWidth - track.clientWidth);
      if (track.scrollLeft + step >= maxLeft) {
        track.scrollLeft = maxLeft; // clamp to end
        stop();
      } else {
        track.scrollBy({ left: step, behavior: 'smooth' });
      }
    };
    const goPrev = () => {
      const firstVisible = track.querySelector('.about-item');
      const step = firstVisible ? (firstVisible.getBoundingClientRect().width + 16) : getStep();
      if (track.scrollLeft - step <= 0) {
        track.scrollLeft = 0; // clamp to start
      } else {
        track.scrollBy({ left: -step, behavior: 'smooth' });
      }
    };

    let timer = null;
    const start = () => { stop(); timer = setInterval(goNext, 2500); };
    const stop = () => { if (timer) clearInterval(timer); timer = null; };

    track.addEventListener('mouseenter', stop);
    track.addEventListener('mouseleave', () => {
      const maxLeft = Math.max(0, track.scrollWidth - track.clientWidth);
      if (track.scrollLeft < maxLeft - 1) start();
    });

    // start autoplay after initial paint
    requestAnimationFrame(() => { track.scrollLeft = 0; });
    setTimeout(start, 600);

    // Keep in bounds on resize
    let resizeTick = false;
    window.addEventListener('resize', () => {
      if (resizeTick) return;
      resizeTick = true;
      requestAnimationFrame(() => {
        const maxLeft = Math.max(0, track.scrollWidth - track.clientWidth);
        if (track.scrollLeft > maxLeft) track.scrollLeft = maxLeft;
        resizeTick = false;
      });
    }, { passive: true });
  })();
  </script>

<!-- Hover/highlight effect removed per request -->

</body>
</html>