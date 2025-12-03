<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- ✅ Your Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/stylish.css') }}">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- Vite for Tailwind/JS if needed -->

</head>
<body class="posting-no-scroll">
<!-- Toggle Button for Mobile -->
<button class="toggle-btn" onclick="toggleSidebar()">
    <i class="fas fa-bars"></i>
</button>

    <!-- Sidenav -->
    <aside class="sidenav" id="sidenav">
    <div class="logo-container" style="display: flex; flex-direction: column; align-items: center;">
        <img src="{{ asset('image/mdrrmologo.jpg') }}" alt="Logo" class="logo-img" style="display: block; margin: 0 auto;">
        <div style="margin-top: 8px; display: block; width: 100%; text-align: center; font-weight: 800; color: #ffffff; letter-spacing: .5px;">SILANG MDRRMO</div>
    </div>
    <nav class="nav-links">
    <a href="{{ url('/') }}" class="{{ request()->is('/') ? 'active' : '' }}"><i class="fas fa-chart-pie"></i> Dashboard</a>
      <a href="{{ url('/admin/posting') }}" class="{{ request()->is('admin/posting*') ? 'active' : '' }}"><i class="fas fa-pen"></i> Posting</a>
      <a href="{{ url('/admin/pairing') }}" class="{{ request()->is('admin/pairing') ? 'active' : '' }}"><i class="fas fa-link"></i> Pairing</a>
      <a href="{{ url('/admin/drivers') }}" class="{{ request()->is('admin/drivers*') ? 'active' : '' }}"><i class="fas fa-car"></i> Drivers</a>
      <a href="{{ url('/admin/medics') }}" class="{{ request()->is('admin/medics*') ? 'active' : '' }}"><i class="fas fa-plus"></i> Create</a>
      <a href="{{ url('/admin/gps') }}" class="{{ request()->is('admin/gps') ? 'active' : '' }}"><i class="fas fa-map-marker-alt mr-1"></i> GPS Tracker</a>
        <div class="logout-form">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit">
                    <i class="fas fa-sign-out-alt mr-2"></i> Logout
                </button>
            </form>
        </div>
    </nav>
</aside>


    <!-- Fixed Top Header -->
   <!-- ✅ Fixed Top Header -->
<div class="mdrrmo-header" style="background:#F7F7F7; box-shadow: 0 2px 8px rgba(0,0,0,0.12); border: none; min-height: var(--header-height); padding: 1rem 2rem; display: flex; align-items: center; justify-content: center;">
    <h2 class="header-title" style="display:none;"></h2>
    @php $firstName = explode(' ', auth()->user()->name ?? 'Admin')[0]; @endphp
    <div id="userMenu" style="position: fixed; right: 16px; top: 16px; display: inline-flex; align-items: center; gap: 10px; cursor: pointer; color: #e5e7eb; z-index: 1000; background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.15); padding: 6px 10px; border-radius: 9999px; box-shadow: 0 6px 18px rgba(0,0,0,0.18); backdrop-filter: saturate(140%);">
        <div style="width: 28px; height: 28px; border-radius: 9999px; background: linear-gradient(135deg,#4CC9F0,#031273); display: inline-flex; align-items: center; justify-content: center; position: relative;">
            <i class="fas fa-user-shield" style="font-size: 14px; color: #ffffff;"></i>
            <span style="position: absolute; right: -1px; bottom: -1px; width: 8px; height: 8px; border-radius: 9999px; background: #22c55e; box-shadow: 0 0 0 2px #0c2d5a;"></span>
        </div>
        <span style="font-weight: 800; color: #000000; letter-spacing: .2px;">{{ $firstName }}</span>
        <i class="fas fa-chevron-down" style="font-size: 10px; color: rgba(255,255,255,0.85);"></i>
        <div id="userDropdown" style="display: none; position: absolute; right: 0; top: calc(100% + 12px); background: #ffffff; color: #0f172a; border-radius: 10px; box-shadow: 0 10px 24px rgba(0,0,0,0.2); padding: 8px; min-width: 160px; z-index: 1001;">
            <div style="position: absolute; right: 12px; top: -8px; width: 0; height: 0; border-left: 8px solid transparent; border-right: 8px solid transparent; border-bottom: 8px solid #ffffff;"></div>
            <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                @csrf
                <button id="changeAccountBtn" type="submit" style="width: 100%; background: linear-gradient(135deg,#ef4444,#b91c1c); color: #ffffff; border: none; border-radius: 8px; padding: 6px 8px; font-weight: 700; font-size: 12px; display: inline-flex; align-items: center; justify-content: center; gap: 6px; cursor: pointer; box-shadow: 0 4px 12px rgba(239,68,68,0.28);">
                    <i class="fas fa-right-left" style="font-size: 12px;"></i>
                    <span>Change account</span>
                </button>
            </form>
        </div>
    </div>
</div>




    


    <!-- Main content (with padding top to not hide under fixed header) -->
     
    <main class="main-content pt-8" style="padding-top: calc(var(--header-height) + 24px);">
      <!-- Header bar below MDRRMO: Dashboard-style layout -->
      <div style="width: 95%; max-width: 1200px; margin: 4px auto 8px auto; display:flex; align-items:center; justify-content: space-between; position: relative; z-index: 3001;">
        <div style="text-align: left; font-weight: 900; color: #031273; letter-spacing: .2px;">
          Posting
        </div>
        <div style="display:flex; align-items:center; gap:0.4rem; position:relative;">
          <button type="button" class="panel-btn" id="togglePostingButtons" aria-expanded="false" aria-controls="postingButtons" style="width:auto; padding:8px 12px; display:flex; flex-direction:row; gap:8px; align-items:center; background:#031273; color:#ffffff; border:0; border-radius:8px; font-weight:800; letter-spacing:.2px; display:inline-flex; align-items:center; gap:8px; cursor:pointer;">
            <i class="fas fa-bars"></i>
            Menu
          </button>
          <div class="button-container posting-menu" id="postingButtons" style="display:none;" >
            <button type="button" class="menu-btn panel-btn active" data-target="carousel" id="carousel-btn">
              <i class="fas fa-images"></i>
              <span>Carousel</span>
            </button>
            <button type="button" class="menu-btn panel-btn" data-target="mission-vision" id="mission-vision-btn">
              <i class="fas fa-bullseye"></i>
              <span>Mission & Vision</span>
            </button>
            <button type="button" class="menu-btn panel-btn" data-target="about" id="about-btn">
              <i class="fas fa-book"></i>
              <span>About</span>
            </button>
            <button type="button" class="menu-btn panel-btn" data-target="officials" id="officials-btn">
              <i class="fas fa-user-tie"></i>
              <span>Officials</span>
            </button>
            <button type="button" class="menu-btn panel-btn" data-target="training" id="training-btn">
              <i class="fas fa-chalkboard-teacher"></i>
              <span>Training</span>
            </button>
            <button type="button" class="menu-btn panel-btn" data-target="services" id="services-btn">
              <i class="fas fa-concierge-bell"></i>
              <span>Services</span>
            </button>
            <button type="button" class="menu-btn panel-btn" id="openBookingsModal" data-target="bookings" style="background:linear-gradient(135deg,#6b7280 0%, #4b5563 100%); color:#fff;">
              <i class="fas fa-calendar-alt"></i>
              <span>Bookings</span>
            </button>
          </div>
        </div>
      </div>

      

        <!-- Panels (medics-style) -->
        <div id="carousel-panel" class="panel-content" style="display:none;">
            <div class="border p-4 rounded panel-card">
              <div class="section-wrapper section-carousel">
                <h3 class="section-title"><i class="fas fa-images"></i> Carousel</h3>
                <form action="{{ url('/admin/posting/carousel') }}" method="POST" enctype="multipart/form-data">
                  @csrf
                  <div class="form-grid">
                    <div class="input-group">
                      <label class="block">Caption (optional)</label>
                      <input type="text" name="caption" class="border p-2 w-full">
                    </div>
                    <div class="input-group">
                      <label class="block">Image</label>
                      <input type="file" name="image" class="border p-2 w-full" required>
                    </div>
                  </div>
                  <div class="text-center">
                    <button type="submit" class="text-white px-4 py-2 rounded" style="width: 170px; background-color: var(--brand-navy);">
                      <i class="fas fa-upload mr-2"></i> Upload
                    </button>
                  </div>
                </form>
              </div>
            </div>
        </div>

        <div id="mission-vision-panel" class="panel-content" style="display:none;">
            <div class="border p-4 rounded panel-card">
              <div class="section-wrapper section-mission">
                <h3 class="section-title"><i class="fas fa-bullseye"></i> Mission & Vision</h3>
                <form action="{{ url('/admin/posting/mission-vision') }}" method="POST">
                  @csrf
                  <div class="form-grid">
                    <div class="input-group">
                      <label class="block">Mission</label>
                      <textarea name="mission" class="border p-2 w-full" rows="2" required></textarea>
                    </div>
                    <div class="input-group">
                      <label class="block">Vision</label>
                      <textarea name="vision" class="border p-2 w-full" rows="2" required></textarea>
                    </div>
                  </div>
                  <div class="text-center">
                    <button type="submit" class="text-white px-4 py-2 rounded" style="width: 170px; background-color: var(--brand-navy);">
                      <i class="fas fa-save mr-2"></i> Save
                    </button>
                  </div>
                </form>
              </div>
            </div>
        </div>

        <div id="about-panel" class="panel-content" style="display:none;">
            <div class="border p-4 rounded panel-card">
              <div class="section-wrapper section-about">
                <h3 class="section-title"><i class="fas fa-book"></i> About MDRRMO</h3>
                <form action="{{ url('/admin/posting/about') }}" method="POST" enctype="multipart/form-data">
                  @csrf
                  <div class="form-grid">
                    <div class="input-group">
                      <label class="block mb-1">Description</label>
                      <textarea name="text" class="border p-2 w-full" rows="3" required></textarea>
                    </div>
                    <div class="input-group">
                      <label class="block">Image</label>
                      <input type="file" name="image" class="border p-2 w-full" required>
                    </div>
                  </div>
                  <div class="text-center">
                    <button type="submit" class="text-white px-4 py-2 rounded" style="width: 170px; background-color: var(--brand-navy);">
                      <i class="fas fa-paper-plane mr-2"></i> Post
                    </button>
                  </div>
                </form>
              </div>
            </div>
        </div>

        <div id="officials-panel" class="panel-content" style="display:none;">
            <div class="border p-4 rounded panel-card">
              <div class="section-wrapper section-officials">
                <h3 class="section-title"><i class="fas fa-user-tie"></i> Officials</h3>
                <form action="{{ url('/admin/posting/officials') }}" method="POST" enctype="multipart/form-data">
                  @csrf
                  <div class="form-grid">
                    <div class="input-group">
                      <label class="block">Name</label>
                      <input type="text" name="name" class="border p-2 w-full" required>
                    </div>
                    <div class="input-group">
                      <label class="block">Position</label>
                      <input type="text" name="position" class="border p-2 w-full" required>
                    </div>
                    <div class="input-group" style="grid-column: 1 / -1;">
                      <label class="block">Image (optional)</label>
                      <input type="file" name="image" class="border p-2 w-full" required>
                    </div>
                  </div>
                  <div class="text-center">
                    <button type="submit" class="text-white px-4 py-2 rounded" style="width: 170px; background-color: var(--brand-navy);">
                      <i class="fas fa-user-plus mr-2"></i> Add Official
                    </button>
                  </div>
                </form>
              </div>
            </div>
        </div>

        <div id="training-panel" class="panel-content" style="display:none;">
            <div class="border p-4 rounded panel-card">
              <div class="section-wrapper section-training">
                <h3 class="section-title"><i class="fas fa-chalkboard-teacher"></i> Post New Training</h3>
                <form action="/admin/posting/trainings" method="POST" enctype="multipart/form-data">
                  @csrf
                  <div class="form-grid">
                    <div class="input-group">
                      <label class="block">Title</label>
                      <input type="text" name="title" class="border p-2 w-full" required>
                    </div>
                    <div class="input-group">
                      <label class="block">Upload Image</label>
                      <input type="file" name="image" class="border p-2 w-full" required>
                    </div>
                    <div class="input-group" style="grid-column: 1 / -1;">
                      <label class="block">Description</label>
                      <textarea name="description" class="border p-2 w-full" rows="3" required></textarea>
                    </div>
                  </div>
                  <div class="text-center">
                    <button type="submit" class="text-white px-4 py-2 rounded" style="width: 170px; background-color: var(--brand-navy);">
                      <i class="fas fa-dumbbell mr-2"></i> Post Training
                    </button>
                  </div>
                </form>
              </div>
            </div>
        </div>

        <div id="services-panel" class="panel-content" style="display:none;">
            <div class="border p-4 rounded panel-card">
              <div class="section-wrapper section-services">
                <div class="section-header" style="display:flex; align-items:center; justify-content:flex-start; gap:0.75rem;">
                  <h3 class="section-title" style="margin:0;"><i class="fas fa-plus-circle"></i> Add New Service</h3>
                </div>
                
                <form action="{{ route('admin.services.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                  @csrf
                  <div class="form-grid">
                    <div class="input-group">
                      <label class="block font-semibold">Title:</label>
                      <input type="text" name="title" class="border p-2 w-full" required>
                    </div>
                    <div class="input-group">
                      <label class="block font-semibold">Category:</label>
                      <input type="text" name="category" class="border p-2 w-full" placeholder="e.g. First Aid, Training, etc." required>
                    </div>
                    <div class="input-group">
                      <label class="block font-semibold">Status:</label>
                      <input type="text" name="status" class="border p-2 w-full" placeholder="Available, Coming Soon, etc." required>
                    </div>
                    <div class="input-group">
                      <label class="block font-semibold">Image:</label>
                      <input type="file" name="image" class="border p-2 w-full" required>
                    </div>
                    <div class="input-group" style="grid-column: 1 / -1;">
                      <label class="block font-semibold">Description:</label>
                      <textarea name="description" class="border p-2 w-full" required></textarea>
                    </div>
                  </div>
                  <div class="text-center">
                    <button type="submit" class="text-white px-4 py-2 rounded" style="width: 170px; background-color: var(--brand-navy);">
                      <i class="fas fa-save mr-2"></i> Save Service
                    </button>
                  </div>
                </form>
              </div>
            </div>
        </div>

      
    </main>
<script>
    function toggleSidebar() {
        const sidenav = document.querySelector('.sidenav');
        sidenav.classList.toggle('active');
    }

    // User menu toggle and AJAX logout redirect to login
    (function(){
      const userMenu = document.getElementById('userMenu');
      const userDropdown = document.getElementById('userDropdown');
      const changeBtn = document.getElementById('changeAccountBtn');
      if (changeBtn) {
        changeBtn.addEventListener('click', function(ev){
          ev.preventDefault();
          const form = changeBtn.closest('form');
          if (!form) return;
          const action = form.getAttribute('action');
          const tokenInput = form.querySelector('input[name="_token"]');
          const token = tokenInput ? tokenInput.value : '';
          fetch(action, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': token, 'X-Requested-With': 'XMLHttpRequest' },
            body: JSON.stringify({})
          }).finally(() => { window.location.href = '{{ route('login') }}'; });
        });
      }
      if (userMenu && userDropdown) {
        userMenu.addEventListener('click', function(e){
          e.stopPropagation();
          const isOpen = userDropdown.style.display === 'block';
          userDropdown.style.display = isOpen ? 'none' : 'block';
        });
        document.addEventListener('click', function(){
          if (userDropdown.style.display === 'block') userDropdown.style.display = 'none';
        });
      }
    })();

    // Medics-style panel toggling (no posting frame)
    (function() {
      const buttons = document.querySelectorAll('.button-container .panel-btn');
      const sectionIds = ['carousel', 'mission-vision', 'about', 'officials', 'training', 'services'];
      const bookingsBtn = document.getElementById('openBookingsModal');
      const toggleBtn = document.getElementById('togglePostingButtons');
      const buttonGrid = document.getElementById('postingButtons');

      function showPanel(name){
        document.querySelectorAll('.panel-content').forEach(p=> p.style.display='none');
        const selected = document.getElementById(name+'-panel');
        if (selected) selected.style.display = 'block';
        buttons.forEach(btn=> btn.classList.remove('active'));
        const activeBtn = document.getElementById(name+'-btn');
        if (activeBtn) activeBtn.classList.add('active');
        window.location.hash = name;
      }

      if (toggleBtn && buttonGrid){
        toggleBtn.addEventListener('click', function(){
          const isOpen = buttonGrid.style.display !== 'none';
          buttonGrid.style.display = isOpen ? 'none' : 'grid';
          toggleBtn.setAttribute('aria-expanded', String(!isOpen));
        });
      }

      buttons.forEach(btn=>{
        btn.addEventListener('click', function(e){
          const target = this.getAttribute('data-target');
          if (target === 'bookings') {
            e.preventDefault();
            if (bookingsBtn) bookingsBtn.click();
            return;
          }
          // Open certain panels in modal instead of inline
          const modalTargets = ['carousel','mission-vision','about','officials','training'];
          if (modalTargets.includes(target)) {
            e.preventDefault();
            const openEvent = new CustomEvent('openPanelModal', { detail: { panel: target } });
            window.dispatchEvent(openEvent);
          } else {
            showPanel(target);
          }
          // collapse menu after selection (mobile feel)
          if (buttonGrid) buttonGrid.style.display = 'none';
          if (toggleBtn) toggleBtn.setAttribute('aria-expanded', 'false');
        });
      });

      const hash = window.location.hash.replace('#','');
      if (sectionIds.includes(hash)) { showPanel(hash); } else { showPanel('services'); }
    })();
</script>

<style>
  .button-container { display: grid; grid-template-columns: repeat(7, 1fr); gap: 12px; margin: 0 auto 16px; }
  /* Posting dropdown menu: one button per row */
  .posting-toolbar { position: relative; }
  .posting-menu { position:absolute; right:0; top:100%; margin-top:6px; width: 240px; display: grid; grid-template-columns: 1fr !important; gap: 8px; padding: 10px; background:#fff; border:1px solid #e5e7eb; border-radius:12px; box-shadow:0 12px 28px rgba(0,0,0,0.18); z-index: 50000; }
  .posting-menu .menu-btn { justify-content:flex-start; flex-direction:row; gap:10px; padding:10px 12px; }
  .posting-menu .menu-btn i { font-size:14px; }
  .posting-menu .menu-btn span { font-size:12px; }
  .panel-btn { background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%); border: 2px solid #cbd5e1; color: #475569; padding: 10px 6px; border-radius: 10px; cursor: pointer; transition: box-shadow 0.2s ease, background 0.2s ease, border-color 0.2s ease; font-weight: 600; display: flex; flex-direction: column; align-items: center; gap: 6px; justify-content: center; box-shadow: 0 2px 4px rgba(0,0,0,0.1); width: 100%; }
  .panel-btn:hover { background: linear-gradient(135deg, #e2e8f0 0%, #cbd5e1 100%); border-color: #94a3b8; box-shadow: 0 4px 8px rgba(0,0,0,0.15); }
  .panel-btn.active { background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); border-color: #3b82f6; color: white; box-shadow: 0 4px 12px rgba(59,130,246,0.3); }
  /* Prevent button row height shift */
  .panel-btn, .panel-btn:hover, .panel-btn.active { transform: none !important; }
  .panel-btn i { font-size: 16px; }
  .panel-btn span { font-size: 11px; text-align: center; }
  @media (max-width: 900px) { .button-container { grid-template-columns: repeat(3,1fr); } }
  @media (max-width: 640px) { .button-container { grid-template-columns: repeat(2,1fr); } .panel-btn { padding: 10px 8px; flex-direction: row; } .panel-btn span { font-size: 12px; } }
  /* Ensure the posting header bar stacks above section containers */
  .posting-headerbar { position: relative; z-index: 60000; }
  /* Compact card tweaks */
  .grid { max-width: 900px; }
  .border { background: #ffffff; border: 1px solid #e5e7eb; }
  /* Panel content compact styling (medics-like) */
  .panel-content .border { box-shadow: 0 4px 12px rgba(0,0,0,0.08); border-radius: 12px; max-width: 1100px; margin: 0 auto; }
  .panel-card { margin: 10px auto 20px; }
  /* Medics-like outer container */
  .containers-grid { background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%); border-radius: 15px; padding: 1.5rem; box-shadow: 0 4px 12px rgba(0,0,0,0.12); }
  /* Medics-like card */
  /* Uniform section container size across all panels (match Services) */
  .panel-content .border.panel-card { background: #ffffff; border: 1px solid #e5e7eb; border-radius: 14px; box-shadow: 0 10px 24px rgba(0,0,0,0.08); padding: 16px; max-width: 1100px; margin-left:auto; margin-right:auto; min-height: 460px; display: flex; align-items: stretch; }
  /* Section wrapper – elevate visuals */
  .panel-content .section-wrapper { position: relative; background: linear-gradient(180deg, #ffffff 0%, #fcfcfd 100%); padding: 16px 16px 14px; border-radius: 12px; display: grid; gap: 12px; border: 1px solid #eef2f7; box-shadow: 0 6px 18px rgba(3,18,115,0.06); flex: 1 1 auto; }
  @media (max-width: 640px){ .panel-content .border.panel-card { min-height: 380px; } }
  .panel-content .section-wrapper::before { content: ""; position: absolute; left: 0; top: 0; width: 100%; height: 4px; border-radius: 12px 12px 0 0; background: linear-gradient(135deg, #031273 0%, #4CC9F0 100%); }
  .panel-content .section-wrapper h3.section-title { margin: 2px 0 10px 0; font-size: 18px; font-weight: 800; display: flex; align-items: center; gap: 10px; color: #0f172a; }
  .panel-content .section-wrapper h3.section-title i { background:#eef2ff; color:#1d4ed8; border-radius:10px; padding:6px; box-shadow: inset 0 0 0 1px rgba(29,78,216,0.15); }
  .panel-content .section-wrapper .divider { height:1px; background: linear-gradient(90deg, rgba(3,18,115,0.15), rgba(76,201,240,0.15)); border:0; margin: 2px 0 8px; }
  /* Input group cards */
  .panel-content .section-wrapper .input-group { background:#fafbff; border:1px solid #e8edf5; padding:10px; border-radius:10px; transition: border-color .2s, box-shadow .2s, background .2s; }
  .panel-content .section-wrapper .input-group:hover { border-color:#c7d2fe; box-shadow: 0 2px 8px rgba(29,78,216,0.08); background:#ffffff; }
  .panel-content .section-wrapper .mb-2 { margin-bottom: 10px; }
  .panel-content .section-wrapper label.block { font-weight: 700; color: #374151; font-size: 0.92rem; margin-bottom: 6px; }
  .panel-content .section-wrapper form { max-width: 900px; margin: 0 auto; }
  .panel-content .section-wrapper .form-row,
  .panel-content .section-wrapper .form-layout,
  .panel-content .section-wrapper .form-grid { display: grid; grid-template-columns: 1fr; gap: 12px; }
  @media (min-width: 768px) {
    .panel-content .section-wrapper .form-row,
    .panel-content .section-wrapper .form-layout,
    .panel-content .section-wrapper .form-grid { grid-template-columns: 1fr 1fr; }
  }
  .panel-content .section-wrapper input[type="text"],
  .panel-content .section-wrapper input[type="file"],
  .panel-content .section-wrapper textarea,
  .panel-content .section-wrapper select {
    padding: 10px 12px;
    border: 2px solid #e5e7eb;
    border-radius: 8px;
    font-size: 14px;
    transition: border-color 0.2s, box-shadow 0.2s;
    background: #fff;
  }
  .panel-content .section-wrapper input:focus,
  .panel-content .section-wrapper textarea:focus,
  .panel-content .section-wrapper select:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59,130,246,0.12);
  }
  .panel-content .text-center { margin-top: 6px; }
  .panel-content .text-center button {
    padding: 10px 16px;
    border-radius: 8px;
    font-weight: 700;
    box-shadow: 0 4px 10px rgba(3,18,115,0.15);
    width: auto !important;
  }
  .panel-content .text-center button:hover { filter: brightness(1.02); box-shadow: 0 6px 14px rgba(3,18,115,0.18); }
  /* Stabilize container height similar to medics */
  .containers-grid { min-height: 60vh; }
  /* Reduce vertical whitespace between stacked grids */
  .panel-content .grid { gap: 12px; }

  /* Compact modal layout similar to Medics page */
  /* Unified modal compact styles target wrapper inside bookingsContainer */
  .unified-panel-wrapper .border.panel-card { min-height: auto; padding: 10px; box-shadow: none; border: 1px solid #e5e7eb; }
  .unified-panel-wrapper .panel-card { margin: 0; }
  .unified-panel-wrapper .border.panel-card { display: block; }
  .unified-panel-wrapper .section-wrapper { padding: 10px; gap: 8px; box-shadow: none; border: 1px solid #eef2f7; }
  .unified-panel-wrapper .section-wrapper { flex: none; }
  .unified-panel-wrapper .section-wrapper::before { height: 3px; }
  .unified-panel-wrapper h3.section-title { font-size: 14px; margin: 0 0 6px 0; font-weight: 800; color:#0f172a; background: none; -webkit-text-fill-color: currentColor; -webkit-background-clip: initial; background-clip: initial; }
  .unified-panel-wrapper h3.section-title i { padding: 3px; font-size: 12px; }
  .unified-panel-wrapper .section-wrapper form { max-width: none; margin: 0; }
  .unified-panel-wrapper .form-grid { display: grid; grid-template-columns: 1fr; gap: 8px; }
  @media (min-width: 768px){ .unified-panel-wrapper .form-grid { grid-template-columns: 1fr 1fr; } }
  .unified-panel-wrapper .input-group { padding: 6px; border-radius: 8px; }
  .unified-panel-wrapper .input-group label.block { font-size: 12.5px; margin-bottom: 4px; }
  .unified-panel-wrapper input[type="text"],
  .unified-panel-wrapper input[type="file"],
  .unified-panel-wrapper textarea,
  .unified-panel-wrapper select { padding: 7px 9px; border-width: 2px; border-radius: 7px; font-size: 13px; }
  .unified-panel-wrapper textarea { min-height: 68px; }
  .unified-panel-wrapper .text-center { margin-top: 4px; }
  .unified-panel-wrapper .text-center button { padding: 7px 12px; border-radius: 7px; font-weight: 700; font-size: 13px; box-shadow: 0 3px 8px rgba(3,18,115,0.12); }
  .unified-panel-wrapper .text-center button i { margin-right: 6px; }

  /* Compact unified modal container (reuse Bookings modal) */
  #bookingsModal .modal-content { position: relative; display: inline-block; max-width: 480px; width: 90%; height: auto; max-height: 70vh; border-radius: 12px; box-shadow: 0 20px 60px rgba(0,0,0,0.3); overflow: hidden; }
  #bookingsModal .modal-iframe { position: relative; z-index: 1; height: auto; max-height: calc(70vh - 60px); overflow: auto; padding: 10px 12px; }
  .unified-panel-wrapper { padding: 0; }
  #bookingsModal .modal-close { position: absolute; top: 10px; right: 10px; background: linear-gradient(135deg,#f59e0b 0%, #d97706 100%); color: #fff; border: 0; border-radius: 8px; padding: 6px 10px; cursor: pointer; z-index: 20; pointer-events: auto; }
  #bookingsModal .modal-close:hover { filter: brightness(0.98); }
  /* Orange palette tweaks inside unified modal */
  .unified-panel-wrapper .section-wrapper::before { background: linear-gradient(135deg,#f59e0b 0%, #ff8c42 100%); }
  .unified-panel-wrapper h3.section-title i { background:#fff7ed; color:#c2410c; box-shadow: inset 0 0 0 1px rgba(234,88,12,0.15); }
  .unified-panel-wrapper .input-group:hover { border-color:#fdba74; box-shadow: 0 2px 8px rgba(234,88,12,0.08); }
  .unified-panel-wrapper input:focus,
  .unified-panel-wrapper textarea:focus,
  .unified-panel-wrapper select:focus { border-color:#f59e0b; box-shadow: 0 0 0 3px rgba(245,158,11,0.12); }
  .unified-panel-wrapper .text-center button { background: linear-gradient(135deg,#f59e0b 0%, #d97706 100%) !important; color:#fff !important; border: none !important; }

  /* Services panel: orange header and button */
  #services-panel .section-title { color:#ffffff !important; background: linear-gradient(135deg,#f59e0b 0%, #d97706 100%); padding:8px 12px; border-radius:8px; display:inline-flex; align-items:center; gap:10px; -webkit-text-fill-color: #ffffff !important; -webkit-background-clip: initial !important; background-clip: initial !important; }
  #services-panel .section-title i { background: rgba(255,255,255,0.2); color:#ffffff; border-radius:10px; padding:6px; box-shadow: inset 0 0 0 1px rgba(255,255,255,0.25); }
  #services-panel .section-wrapper::before { background: linear-gradient(135deg,#f59e0b 0%, #ff8c42 100%); }
  /* Make the entire services section header bar orange with white text */
  #services-panel .section-header { background: linear-gradient(135deg,#f59e0b 0%, #d97706 100%); color:#ffffff; padding: 10px 14px; border-radius: 10px; width: 100%; }
  #services-panel .section-header .section-title { color:#ffffff !important; background: transparent !important; padding: 0 !important; margin: 0 !important; }
  #services-panel .section-header .section-title i { background: rgba(255,255,255,0.2) !important; color:#ffffff !important; }

  /* Ensure icons render visibly in modal and headers */
  .unified-panel-wrapper h3.section-title i,
  #services-panel .section-title i { display:inline-flex; align-items:center; justify-content:center; }
  #services-panel .text-center button { background: linear-gradient(135deg,#f59e0b 0%, #d97706 100%) !important; color:#fff !important; border:none !important; }
  #services-panel .text-center button:hover { filter: brightness(0.98); }

  /* Success/Error modals (match medics page) */
  .success-modal { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); display: none; justify-content: center; align-items: center; z-index: 10000; }
  .success-modal-content { background: #ffffff; border-radius: 15px; box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3); max-width: 400px; width: 90%; position: relative; overflow: hidden; }
  .success-modal-header { background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: #ffffff; padding: 1.5rem; text-align: center; position: relative; }
  .success-modal-header i { font-size: 3rem; margin-bottom: 0.5rem; display: block; filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.2)); }
  .success-modal-header h3 { margin: 0; font-size: 1.5rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px; }
  .success-modal-body { padding: 2rem 1.5rem; text-align: center; }
  .success-modal-footer { padding: 1rem 1.5rem 1.5rem; text-align: center; }
  .success-modal-btn { background: linear-gradient(135deg, #0c2d5a 0%, #1e3a8a 100%); color: #ffffff; border: none; border-radius: 10px; padding: 0.75rem 2rem; font-size: 1rem; font-weight: 700; cursor: pointer; transition: all 0.3s ease; text-transform: uppercase; letter-spacing: 0.5px; box-shadow: 0 4px 12px rgba(3, 18, 115, 0.2); }
  .success-modal-btn:hover { background: linear-gradient(135deg, #f59e0b 0%, #ff8c42 100%); transform: translateY(-2px); box-shadow: 0 6px 16px rgba(242, 140, 40, 0.3); }

  .error-modal { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); display: none; justify-content: center; align-items: center; z-index: 10000; }
  .error-modal-content { background: white; border-radius: 12px; box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3); max-width: 500px; width: 90%; max-height: 80vh; overflow-y: auto; }
  .error-modal-header { background: #dc2626; color: white; padding: 20px; border-radius: 12px 12px 0 0; text-align: center; display: flex; align-items: center; justify-content: center; gap: 10px; }
  .error-modal-body { padding: 20px; text-align: center; }
  .error-modal-footer { padding: 20px; text-align: center; border-top: 1px solid #e5e7eb; }
  .error-modal-btn { background: #dc2626; color: white; border: none; padding: 12px 24px; border-radius: 8px; font-size: 16px; font-weight: 600; cursor: pointer; transition: background-color 0.2s; }
  .error-modal-btn:hover { background: #b91c1c; }
</style>

<!-- Success Modal (Toast-like) -->
<div id="toastOverlay" style="display:none; position:fixed; inset:0; background: rgba(0,0,0,0.35); backdrop-filter: blur(4px); z-index:3000; align-items:center; justify-content:center;">
  <div id="toastBox" style="background:#16a34a; color:#fff; padding:22px 26px; border-radius:14px; box-shadow:0 16px 32px rgba(0,0,0,0.28); font-weight:800; min-width:360px; max-width:520px; text-align:center; font-size:1.05rem;">
    <div id="toastMessage">Added successfully</div>
    <div style="margin-top:8px; display:flex; justify-content:center;">
      <button type="button" id="toastClose" aria-label="Close" style="background:#0b7a33; border:0; color:#fff; font-weight:800; cursor:pointer; padding:6px 14px; border-radius:8px;">OK</button>
    </div>
  </div>
</div>

<!-- Service Bookings Modal -->
<div id="bookingsModal" class="modal-overlay" style="display:none;">
  <div class="modal-content">
    <button type="button" id="closeBookingsModal" class="modal-close" aria-label="Close">
      <i class="fas fa-times"></i>
    </button>
    <div id="bookingsContainer" class="modal-iframe" title="Service Bookings" style="background:#fff; overflow:auto;">
      <div style="padding:1rem; text-align:center; color:#031273; font-weight:700;">Loading bookings…</div>
    </div>
  </div>
</div>

<!-- Unified modal: reuse Bookings Modal for all content -->

<!-- Posting Success Modal (same layout as medics) -->
<div id="postingSuccessModal" class="success-modal" style="display:none;">
  <div class="success-modal-content">
    <div class="success-modal-header">
      <i class="fas fa-check-circle"></i>
      <h3>Success!</h3>
    </div>
    <div class="success-modal-body">
      <p id="postingSuccessMessage"></p>
    </div>
    <div class="success-modal-footer">
      <button id="postingSuccessOk" class="success-modal-btn">OK</button>
    </div>
  </div>
</div>

<!-- Posting Error Modal (same layout as medics) -->
<div id="postingErrorModal" class="error-modal" style="display:none;">
  <div class="error-modal-content">
    <div class="error-modal-header">
      <i class="fas fa-exclamation-triangle"></i>
      <h3>Error!</h3>
    </div>
    <div class="error-modal-body">
      <p id="postingErrorMessage"></p>
    </div>
    <div class="error-modal-footer">
      <button id="postingErrorOk" class="error-modal-btn">OK</button>
    </div>
  </div>
</div>
<script>
  (function() {
    const openBtn = document.getElementById('openBookingsModal');
    const modal = document.getElementById('bookingsModal');
    const closeBtn = document.getElementById('closeBookingsModal');
  const bookingsContainer = document.getElementById('bookingsContainer');
    // Unified modal uses bookings modal/container

    if (openBtn && modal && closeBtn && bookingsContainer) {
      openBtn.addEventListener('click', function() {
        modal.style.display = 'flex';
        document.body.classList.add('modal-open');

        // Fetch only bookings content from Services page
        bookingsContainer.innerHTML = '<div style="padding:1rem; text-align:center; color:#031273; font-weight:700;">Loading bookings…</div>';
        fetch("{{ url('/admin/services') }}", { credentials: 'include' })
          .then(r => r.text())
          .then(html => {
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            // Try to find the bookings block by its heading
            let block = null;
            const headings = doc.querySelectorAll('h2.section-title');
            headings.forEach(h => {
              if (h.textContent && h.textContent.toLowerCase().includes('service bookings')) {
                block = h.closest('.border');
              }
            });
            if (!block) {
              // Fallback: second .grid.mb-10 may be bookings
              const grids = doc.querySelectorAll('.grid.mb-10');
              if (grids.length > 1) {
                block = grids[1];
              }
            }
            if (block) {
              bookingsContainer.innerHTML = '';
              bookingsContainer.appendChild(block.cloneNode(true));
            } else {
              bookingsContainer.innerHTML = '<div style="padding:1rem; text-align:center; color:#b91c1c; font-weight:700;">No bookings content available.</div>';
            }
          })
          .catch(() => {
            bookingsContainer.innerHTML = '<div style="padding:1rem; text-align:center; color:#b91c1c; font-weight:700;">Failed to load bookings.</div>';
          });
      });

      closeBtn.addEventListener('click', function() {
        modal.style.display = 'none';
        document.body.classList.remove('modal-open');
        bookingsContainer.innerHTML = '';
      });

      modal.addEventListener('click', function(e) {
        if (e.target === modal) {
          modal.style.display = 'none';
          document.body.classList.remove('modal-open');
          bookingsContainer.innerHTML = '';
        }
      });
    }

    // Open a panel inside the unified bookings modal by cloning existing panel DOM
    window.addEventListener('openPanelModal', function(ev){
      const detail = ev.detail || {};
      const name = detail.panel;
      if (!name || !modal || !bookingsContainer) return;
      // Show unified modal
      modal.style.display = 'flex';
      document.body.classList.add('modal-open');
      // Prepare container
      bookingsContainer.innerHTML = '<div style="padding:1rem; text-align:center; color:#031273; font-weight:700;">Loading…</div>';
      // Find and clone panel
      const source = document.getElementById(name + '-panel');
      if (source) {
        const clone = source.cloneNode(true);
        clone.style.display = 'block';
        // Wrap for compact styling inside unified modal
        const wrapper = document.createElement('div');
        wrapper.className = 'unified-panel-wrapper';
        wrapper.appendChild(clone);
        bookingsContainer.innerHTML = '';
        bookingsContainer.appendChild(wrapper);
      } else {
        bookingsContainer.innerHTML = '<div style="padding:1rem; text-align:center; color:#b91c1c; font-weight:700;">Unable to load content.</div>';
      }
    });
  })();
</script>

<script>
  // Success/Error popups for Posting page (match medics layout)
  (function(){
    const successModal = document.getElementById('postingSuccessModal');
    const successOk = document.getElementById('postingSuccessOk');
    const errorModal = document.getElementById('postingErrorModal');
    const errorOk = document.getElementById('postingErrorOk');

    function openModal(modal){ if (modal){ modal.style.display = 'flex'; document.body.style.overflow = 'hidden'; } }
    function closeModal(modal){ if (modal){ modal.style.display = 'none'; document.body.style.overflow = 'auto'; } }

    window.showPostingSuccess = function(message){
      const msg = document.getElementById('postingSuccessMessage');
      if (msg) msg.textContent = message || 'Saved successfully';
      openModal(successModal);
    };
    window.showPostingError = function(message){
      const msg = document.getElementById('postingErrorMessage');
      if (msg) msg.textContent = message || 'Something went wrong';
      openModal(errorModal);
    };

    if (successOk) successOk.addEventListener('click', function(){ closeModal(successModal); });
    if (errorOk) errorOk.addEventListener('click', function(){ closeModal(errorModal); });
    window.addEventListener('click', function(e){
      if (e.target === successModal) closeModal(successModal);
      if (e.target === errorModal) closeModal(errorModal);
    });

    // Auto-open based on flash/validation
    @if(session('success'))
      showPostingSuccess(@json(session('success')));
    @endif
    @if($errors->any())
      showPostingError(@json($errors->first()));
    @endif
  })();
</script>

</body>
</html>