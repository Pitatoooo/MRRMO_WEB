<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $service->title }} | MDRRMO</title>
      <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/stylie.css') }}?v={{ filemtime(public_path('css/style.css')) }}">
</head>
<body class="bg-gray-100 text-gray-800">

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
    {{-- 🔹 Service Info --}}
    <div class="container mx-auto px-4 py-8 max-w-4xl">
        <div class="bg-white p-6 rounded shadow-sm border border-gray-300">
            <h2 class="text-3xl font-bold mb-4 text-blue-900">{{ $service->title }}</h2>

            @if($service->image)
                <img src="{{ asset('image/' . $service->image) }}"
                     style="display: block; max-width: 100%; max-height: 250px; margin: 0 auto 1rem; border-radius: 8px; object-fit: cover; border: 1px solid #ccc;"
                     alt="{{ $service->title }}">
            @endif

            @if($service->status)
                <span class="inline-block bg-blue-100 text-blue-800 text-xs px-3 py-1 rounded mb-4">
                    {{ $service->status }}
                </span>
            @endif

            <p class="text-gray-700 leading-relaxed whitespace-pre-line">
                {!! nl2br(e($service->description)) !!}
            </p>
        </div>
    </div>

    {{-- 🔹 Interactions --}}
    <div class="container mx-auto px-4 py-12 max-w-4xl space-y-12">

        {{-- ⭐ Leave a Review --}}
        <div style="background: white; padding: 1.5rem; border-radius: 10px; border: 1px solid #ddd; box-shadow: 0 2px 6px rgba(0,0,0,0.05);">
            <h3 style="font-size: 1.25rem; font-weight: bold; margin-bottom: 1rem; color: #eab308;">⭐ Leave a Review</h3>
            <form method="POST" action="{{ route('reviews.store') }}">
                @csrf
                <input type="hidden" name="service_id" value="{{ $service->id }}">

                <div style="margin-bottom: 1rem;">
                    <label style="display:block; font-weight:500; margin-bottom: 0.25rem;">Your Name</label>
                    <input type="text" name="name" style="width:100%; padding:0.5rem; border:1px solid #ccc; border-radius:5px;" required>
                </div>

                <div style="margin-bottom: 1rem;">
                    <label style="display:block; font-weight:500; margin-bottom: 0.25rem;">Rating</label>
                    <div style="display: flex; gap: 0.5rem;">
                        @for ($i = 1; $i <= 5; $i++)
                            <label style="cursor:pointer;">
                                <input type="radio" name="rating" value="{{ $i }}" style="display:none;">
                                <span style="font-size: 1.5rem; color: #facc15;">&#9733;</span>
                            </label>
                        @endfor
                    </div>
                </div>

                <div style="margin-bottom: 1rem;">
                    <label style="display:block; font-weight:500; margin-bottom: 0.25rem;">Comment</label>
                    <textarea name="comment" rows="4" style="width:100%; padding:0.5rem; border:1px solid #ccc; border-radius:5px;" required></textarea>
                </div>

                <button type="submit" style="background:#facc15; color:#000; font-weight:500; padding:0.5rem 1rem; border-radius:6px;">
                    Submit Review
                </button>
            </form>
        </div>

        {{-- 📅 Request This Service --}}
        <div style="background: white; padding: 1.5rem; border-radius: 10px; border: 1px solid #ddd; box-shadow: 0 2px 6px rgba(0,0,0,0.05);">
            <h3 style="font-size: 1.25rem; font-weight: bold; margin-bottom: 1rem; color: #16a34a;">📅 Request This Service</h3>
            <form method="POST" action="{{ route('bookings.store') }}">
                @csrf
                <input type="hidden" name="service_id" value="{{ $service->id }}">

                <div style="margin-bottom: 1rem;">
                    <label style="display:block; font-weight:500; margin-bottom: 0.25rem;">Full Name</label>
                    <input type="text" name="name" style="width:100%; padding:0.5rem; border:1px solid #ccc; border-radius:5px;" required>
                </div>

                <div style="margin-bottom: 1rem;">
                    <label style="display:block; font-weight:500; margin-bottom: 0.25rem;">Contact Info (Phone/Email)</label>
                    <input type="text" name="contact_info" style="width:100%; padding:0.5rem; border:1px solid #ccc; border-radius:5px;" required>
                </div>

                <div style="display: flex; gap: 1rem; margin-bottom: 1rem;">
                    <div style="flex:1;">
                        <label style="display:block; font-weight:500; margin-bottom: 0.25rem;">Preferred Date</label>
                        <input type="date" name="preferred_date" style="width:100%; padding:0.5rem; border:1px solid #ccc; border-radius:5px;" required>
                    </div>
                    <div style="flex:1;">
                        <label style="display:block; font-weight:500; margin-bottom: 0.25rem;">Preferred Time</label>
                        <input type="time" name="preferred_time" style="width:100%; padding:0.5rem; border:1px solid #ccc; border-radius:5px;" required>
                    </div>
                </div>

                <button type="submit" style="background:#16a34a; color:white; font-weight:500; padding:0.5rem 1rem; border-radius:6px;">
                    Send Booking Request
                </button>
            </form>
        </div>
    </div>

    {{-- 🔹 Footer --}}
    <footer class="bg-blue-600 text-white text-center py-4 mt-12">
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
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
