<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Services | MDRRMO</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/stylie.css') }}?v={{ filemtime(public_path('css/style.css')) }}">



</head>
<body class="bg-gray-100 text-gray-800">

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

  <nav class="navbar navbar-expand-lg navbar-dark py-3" style="background-color:#031273;">
  <div class="container-fluid">

    <!-- Brand on the left -->
 <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
  <img src="{{ asset('image/mdrrmologo.jpg') }}" alt="MDRRMO Logo" class="navbar-logo">
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



  <h2 class="text-3xl font-bold text-center mb-8 mt-4 fade-in">Our Services</h2>
  
<div class="container-fluid fade-in">

   @php
    $grouped = $services->groupBy('category');
    $colors = ['#EAF4FB', '#EAF4FB', '#EAF4FB', '#EAF4FB', '#EAF4FB'];
 // pastel background choices
    $i = 0;
@endphp

@foreach($grouped as $category => $items)
    <!-- Full width block with background -->
    <div class="w-100 py-6 fade-in" style="background-color: {{ $colors[$i % count($colors)] }};">
        
        <!-- Inner container to center content -->
        <div class="max-w-7xl mx-auto px-4">
    <h3 class="category-divider fade-in mb-4 text-xl font-bold text-center">
        {{ $category ?? 'Uncategorized' }}
    </h3>

    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 services-wrapper pb-12">
        @foreach($items as $service)
            <a href="{{ route('services.show', $service->id) }}"
               class="service-card bg-white rounded-lg shadow-sm hover:shadow-md transition-transform hover:-translate-y-1 duration-200 overflow-hidden border border-gray-200 fade-in">

                @if($service->image)
                    <img src="{{ asset('image/' . $service->image) }}"
                         class="w-full h-36 object-cover"
                         alt="{{ $service->title }}">
                @endif

                <div class="p-3 space-y-2">
                    <h3 class="text-lg font-semibold text-gray-800 leading-tight">
                        {{ $service->title }}
                    </h3>
                    <p class="text-sm text-gray-600 leading-snug">
                        {{ Str::limit($service->description, 60) }}
                    </p>
                </div>
            </a>
        @endforeach
    </div>
</div>

    </div>

    @php $i++; @endphp
@endforeach

</div>

  <footer class="bg-blue-600 text-white text-center py-4 mt-8">
      <p>&copy; {{ date('Y') }} MDRRMO. All rights reserved.</p>
  </footer>
<script>
  const navbar = document.querySelector('.navbar');
const navbarOffsetTop = navbar.offsetTop;
let lastScrollTop = 0;

window.addEventListener('scroll', () => {
  const scrollTop = window.pageYOffset || document.documentElement.scrollTop;

  if (scrollTop > lastScrollTop && scrollTop > navbarOffsetTop) {
    // Scrolling down → stick navbar
    navbar.classList.add('fixed');
  } else if (scrollTop < lastScrollTop) {
    // Scrolling up
    if (scrollTop > navbarOffsetTop) {
      navbar.classList.add('fixed');
    } else {
      // back to default position
      navbar.classList.remove('fixed');
    }
  }

  lastScrollTop = scrollTop <= 0 ? 0 : scrollTop;
});

  </script>

  {{-- Simple JS for search --}}
  <script>
      const searchInput = document.getElementById('searchInput');
      const cards = document.querySelectorAll('.service-card');

      searchInput.addEventListener('keyup', function () {
          let value = this.value.toLowerCase();
          cards.forEach(card => {
              const text = card.textContent.toLowerCase();
              card.style.display = text.includes(value) ? 'block' : 'none';
          });
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
