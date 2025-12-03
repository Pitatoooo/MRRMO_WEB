<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pairing Log</title>
    <link rel="stylesheet" href="{{ asset('css/stylish.css') }}">
    <link rel="stylesheet" href="{{ asset('css/pairing.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
      /* Status Badge Styles */
      .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
      }
      
      .status-badge.completed {
        background-color: #10b981;
        color: #ffffff;
        border: 1px solid #059669;
      }
      
      .status-badge.cancelled {
        background-color: #dc2626;
        color: #ffffff;
        border: 1px solid #b91c1c;
      }
      
      .status-badge.active {
        background-color: #3b82f6;
        color: #ffffff;
        border: 1px solid #1d4ed8;
      }
    </style>
    <style>
      .team-card {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        border-left: 4px solid #3b82f6;
      }
      .team-card:hover {
        background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      }
      .status-indicator {
        position: relative;
        overflow: hidden;
      }
      .status-indicator::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
        transition: left 0.5s;
      }
      .status-indicator:hover::before {
        left: 100%;
      }
    </style>
</head>
<body>
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
      <span class="nav-link-locked" style="display: block; padding: 12px 16px; color: #9ca3af; cursor: not-allowed; opacity: 0.6; position: relative;"><i class="fas fa-pen"></i> Posting <i class="fas fa-lock" style="font-size: 10px; margin-left: 8px; opacity: 0.7;"></i></span>
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

<main class="main-content pt-8" style="padding-top: calc(var(--header-height) + 24px);">
  <!-- Header bar: Dashboard-style layout -->
  <div style="width: 95%; max-width: 1200px; margin: 4px auto 8px auto; display:flex; align-items:center; justify-content: space-between; position: relative; z-index: 3001;">
    <div style="text-align: left; font-weight: 900; color: #031273; letter-spacing: .2px;">
      Logs
    </div>
    <div style="display:flex; align-items:center; gap:0.4rem;">
      <a href="{{ route('admin.pairing.index') }}" style="background:#031273; color:#ffffff; border:0; border-radius:8px; padding:6px 12px; font-weight:800; letter-spacing:.2px; display:inline-flex; align-items:center; gap:8px; cursor:pointer; text-decoration:none;">
        <i class="fas fa-arrow-left"></i>
        Back to Pairing
      </a>
    </div>
  </div>

  <section class="containers-grid" style="max-width:1400px;">
    <!-- Search and Filter Section -->
    <div class="search-section">
      <form method="GET" action="{{ route('admin.pairing.log') }}" class="search-form">
        <div class="search-row">
          <div class="search-field">
            <input type="text" name="search" value="{{ request('search') }}" 
                   placeholder="Search"
                   class="search-input" id="searchInput">
            <div class="search-indicator" id="searchIndicator" style="display: none;">
              <i class="fas fa-spinner fa-spin"></i>
            </div>
          </div>
          
          <div class="log-driver-dropdown">
            <select name="driver_id" class="driver-select">
              <option value="">All Drivers</option>
              @foreach($drivers as $driver)
                <option value="{{ $driver->id }}" {{ request('driver_id') == $driver->id ? 'selected' : '' }}>
                  {{ $driver->name }}
                </option>
              @endforeach
            </select>
          </div>
          
          <div class="log-status-dropdown">
            <select name="status" class="driver-select">
              <option value="">All Status</option>
              <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
              <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
          </div>
          <button type="button" onclick="toggleLogTable()" id="toggleLogTableBtn" class="pairing-main-btn" style="display:inline-flex; align-items:center; gap:0.5rem; background: linear-gradient(135deg, #10b981 0%, #059669 100%); color:#fff; border:2px solid transparent; border-radius:10px; font-weight:800;">
            <i class="fas fa-user-md"></i> Driver‑Medic
          </button>
        </div>
      </form>
    </div>

    <!-- Success Modal -->
    <div id="successModal" class="success-modal" style="display: none;">
      <div class="success-modal-content">
        <div class="success-modal-header">
          <i class="fas fa-check-circle"></i>
          <h3>Success!</h3>
        </div>
        <div class="success-modal-body">
          <p id="successMessage">{{ session('success') }}</p>
        </div>
        <div class="success-modal-footer">
          <button onclick="closeSuccessModal()" class="success-modal-btn">OK</button>
        </div>
      </div>
    </div>

    <!-- Driver-Medic Pairings Log -->
    <div id="driverMedicLogSection" class="mb-8" style="display:none;">
      <div class="section-header-main" style="display:flex; align-items:center; justify-content:space-between; gap:0.5rem; padding:0.65rem 0.9rem; border-radius:10px;">
        <div style="font-weight:800; font-size:1rem; color:#ffffff; letter-spacing:0.3px;">Driver‑Medic Pairings Log</div>
      </div>
      
      <div class="pairing-table-container">
        <div class="overflow-x-auto">
          <table class="pairing-table" data-paginate="true" data-page-size="10">
            <thead>
              <tr>
                <th>
                  <i class="fas fa-user mr-2"></i>Driver
                </th>
                <th>
                  <i class="fas fa-user-md mr-2"></i>Medics
                </th>
                <th>
                  <i class="fas fa-calendar mr-2"></i>Date
                </th>
                <th>
                  <i class="fas fa-info-circle mr-2"></i>Status
                </th>
                <th>
                  <i class="fas fa-history mr-2"></i>Completed/Cancelled At
                </th>
              </tr>
            </thead>
            <tbody>
              @forelse($driverMedicPairings as $pairing)
                @php
                  $driver = $pairing->driver;
                  $medic = $pairing->medic;
                  $updatedAt = \Carbon\Carbon::parse($pairing->updated_at)->setTimezone('Asia/Manila');
                @endphp
                <tr>
                  <td>
                    <div class="team-leader-cell">
                      <div class="team-leader-avatar">
                        {{ strtolower(substr($driver->name, 0, 2)) }}
                      </div>
                      <div class="team-leader-info">
                        <h4>{{ $driver->name }}</h4>
                        <p>
                          <i class="fas fa-users"></i>
                          Team Leader
                        </p>
                      </div>
                    </div>
                  </td>
                  <td>
                    <div class="team-members-cell">
                      <div class="team-members-count">
                        <i class="fas fa-user-md"></i>
                        <span>1 Medic</span>
                      </div>
                      <div class="team-members-tags">
                        <div class="team-member-tag">
                          <span>{{ $medic->name }}</span>
                          @if($medic->specialization)
                            <span>({{ $medic->specialization }})</span>
                          @endif
                        </div>
                      </div>
                    </div>
                  </td>
                  <td class="date-cell">
                    <div class="date-primary">{{ $pairing->pairing_date->format('M d, Y') }}</div>
                    <div class="date-secondary">{{ $pairing->pairing_date->format('l') }}</div>
                  </td>
                  <td class="status-cell">
                    <span class="status-badge {{ $pairing->status }}">{{ ucfirst($pairing->status) }}</span>
                  </td>
                  <td class="date-cell">
                    <div class="date-primary">{{ $updatedAt->format('M d, Y') }}</div>
                    <div class="date-secondary">{{ $updatedAt->format('H:i') }}</div>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="5" class="empty-state">No completed or cancelled driver-medic pairings found.</td>
                </tr>
              @endforelse
            </tbody>
          </table>
          <div class="table-pager">
            <button class="pager-btn" data-prev>Prev</button>
            <button class="pager-btn" data-next>Next</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Driver-Ambulance Pairings Log -->
    <div id="driverAmbulanceLogSection" class="driver-ambulance-section" style="display:block;">
      <div class="section-header-main" style="display:flex; align-items:center; justify-content:space-between; gap:0.5rem; padding:0.65rem 0.9rem; border-radius:10px;">
        <div style="font-weight:800; font-size:1rem; color:#ffffff; letter-spacing:0.3px;">Driver‑Ambulance Pairings Log</div>
      </div>
      
      <div class="pairing-table-container">
        <div class="overflow-x-auto">
          <table class="pairing-table" data-paginate="true" data-page-size="10">
            <thead>
              <tr>
                <th>
                  <i class="fas fa-user mr-2"></i>Driver
                </th>
                <th>
                  <i class="fas fa-ambulance mr-2"></i>Ambulances
                </th>
                <th>
                  <i class="fas fa-calendar mr-2"></i>Date
                </th>
                <th>
                  <i class="fas fa-info-circle mr-2"></i>Status
                </th>
                <th>
                  <i class="fas fa-history mr-2"></i>Completed/Cancelled At
                </th>
              </tr>
            </thead>
            <tbody>
              @forelse($driverAmbulancePairings as $pairing)
                @php
                  $driver = $pairing->driver;
                  $ambulance = $pairing->ambulance;
                  $updatedAt = \Carbon\Carbon::parse($pairing->updated_at)->setTimezone('Asia/Manila');
                @endphp
                <tr>
                  <td>
                    <div class="team-leader-cell">
                      <div class="team-leader-avatar">
                        {{ strtolower(substr($driver->name, 0, 2)) }}
                      </div>
                      <div class="team-leader-info">
                        <h4>{{ $driver->name }}</h4>
                        <p>
                          <i class="fas fa-car"></i>
                          Vehicle Operator
                        </p>
                      </div>
                    </div>
                  </td>
                  <td>
                    <div class="team-members-cell">
                      <div class="team-members-count">
                        <i class="fas fa-ambulance"></i>
                        <span>1 Ambulance</span>
                      </div>
                      <div class="team-members-tags">
                        <div class="team-member-tag">
                          <span>{{ $ambulance->name }}</span>
                          @if($ambulance->plate_number)
                            <span>({{ $ambulance->plate_number }})</span>
                          @endif
                        </div>
                      </div>
                    </div>
                  </td>
                  <td class="date-cell">
                    <div class="date-primary">{{ $pairing->pairing_date->format('M d, Y') }}</div>
                    <div class="date-secondary">{{ $pairing->pairing_date->format('l') }}</div>
                  </td>
                  <td class="status-cell">
                    <span class="status-badge {{ $pairing->status }}">{{ ucfirst($pairing->status) }}</span>
                  </td>
                  <td class="date-cell">
                    <div class="date-primary">{{ $updatedAt->format('M d, Y') }}</div>
                    <div class="date-secondary">{{ $updatedAt->format('H:i') }}</div>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="5" class="empty-state">No completed or cancelled driver-ambulance pairings found.</td>
                </tr>
              @endforelse
            </tbody>
          </table>
          <div class="table-pager">
            <button class="pager-btn" data-prev>Prev</button>
            <button class="pager-btn" data-next>Next</button>
          </div>
        </div>
      </div>
    </div>
  </section>
</main>

<script>
  function toggleSidebar() {
    const sidenav = document.getElementById('sidenav');
    if (!sidenav) return;
    sidenav.classList.toggle('active');
  }

  // User menu toggle + AJAX logout redirect to login
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

  // Real-time search functionality - Optimized
  document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.querySelector('input[name="search"]');
    const driverSelect = document.querySelector('select[name="driver_id"]');
    const statusSelect = document.querySelector('select[name="status"]');
    const searchForm = document.querySelector('.search-form');
    const searchIndicator = document.getElementById('searchIndicator');
    let searchTimeout;
    let isSubmitting = false;
    
    // Show success modal if there's a success message
    const successMessage = document.getElementById('successMessage').textContent.trim();
    if (successMessage) {
      showSuccessModal(successMessage);
    }
    
    if (searchInput && searchForm) {
      // Optimized real-time search with better debouncing
      searchInput.addEventListener('input', function() {
        if (isSubmitting) return; // Prevent multiple simultaneous submissions
        
        clearTimeout(searchTimeout);
        
        // Show loading indicator
        if (searchIndicator) {
          searchIndicator.style.display = 'block';
        }
        
        searchTimeout = setTimeout(() => {
          if (!isSubmitting) {
            isSubmitting = true;
            searchForm.submit();
          }
        }, 300); // Reduced delay for better responsiveness
      });
      
      // Auto-submit when driver select changes
      if (driverSelect) {
        driverSelect.addEventListener('change', function() {
          if (isSubmitting) return; // Prevent multiple simultaneous submissions
          
          clearTimeout(searchTimeout);
          
          // Show loading indicator
          if (searchIndicator) {
            searchIndicator.style.display = 'block';
          }
          
          searchTimeout = setTimeout(() => {
            if (!isSubmitting) {
              isSubmitting = true;
              searchForm.submit();
            }
          }, 50); // Very short delay for dropdown changes
        });
      }

      // Auto-submit when status select changes
      if (statusSelect) {
        statusSelect.addEventListener('change', function() {
          if (isSubmitting) return; // Prevent multiple simultaneous submissions
          
          clearTimeout(searchTimeout);
          
          // Show loading indicator
          if (searchIndicator) {
            searchIndicator.style.display = 'block';
          }
          
          searchTimeout = setTimeout(() => {
            if (!isSubmitting) {
              isSubmitting = true;
              searchForm.submit();
            }
          }, 50); // Very short delay for dropdown changes
        });
      }
    }
  });

  // Success Modal Functions
  function showSuccessModal(message) {
    const modal = document.getElementById('successModal');
    const messageElement = document.getElementById('successMessage');
    
    if (modal && messageElement) {
      messageElement.textContent = message;
      modal.style.display = 'flex';
      
      // Auto-close after 5 seconds
      setTimeout(() => {
        closeSuccessModal();
      }, 5000);
    }
  }

  function closeSuccessModal() {
    const modal = document.getElementById('successModal');
    if (modal) {
      modal.style.display = 'none';
    }
  }

  // Close modal when clicking outside
  document.addEventListener('click', function(event) {
    const modal = document.getElementById('successModal');
    if (event.target === modal) {
      closeSuccessModal();
    }
  });

  // Close modal with Escape key
  document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
      closeSuccessModal();
    }
  });

  // Simple client-side pagination for all pairing tables
  document.addEventListener('DOMContentLoaded', function(){
    document.querySelectorAll('table.pairing-table[data-paginate="true"]').forEach(function(tbl){
      const pageSize = parseInt(tbl.getAttribute('data-page-size') || '10', 10);
      const tbody = tbl.querySelector('tbody');
      if (!tbody) return;
      const rows = Array.from(tbody.children);
      if (rows.length <= pageSize) return;

      let page = 0;
      const container = tbl.parentElement;
      const prevBtn = container.querySelector('.table-pager [data-prev]');
      const nextBtn = container.querySelector('.table-pager [data-next]');

      function render(){
        rows.forEach((tr, i)=>{
          const inPage = i >= page*pageSize && i < (page+1)*pageSize;
          tr.style.display = inPage ? '' : 'none';
        });
        if (prevBtn) prevBtn.disabled = page === 0;
        if (nextBtn) nextBtn.disabled = (page+1)*pageSize >= rows.length;
      }
      if (prevBtn) prevBtn.addEventListener('click', function(){ if (page>0){ page--; render(); } });
      if (nextBtn) nextBtn.addEventListener('click', function(){ if ((page+1)*pageSize < rows.length){ page++; render(); } });
      render();
    });
  });

  // Toggle log sections similar to index page
  function showDriverAmbulanceLog() {
    const amb = document.getElementById('driverAmbulanceLogSection');
    const med = document.getElementById('driverMedicLogSection');
    if (amb && med) {
      amb.style.display = 'block';
      med.style.display = 'none';
      window.scrollTo({ top: amb.offsetTop - 80, behavior: 'smooth' });
      const btn = document.getElementById('toggleLogTableBtn');
      if (btn) {
        btn.innerHTML = '<i class="fas fa-user-md"></i> Driver‑Medic';
        btn.style.background = 'linear-gradient(135deg, #10b981 0%, #059669 100%)';
      }
    }
  }

  function showDriverMedicLog() {
    const amb = document.getElementById('driverAmbulanceLogSection');
    const med = document.getElementById('driverMedicLogSection');
    if (amb && med) {
      med.style.display = 'block';
      amb.style.display = 'none';
      window.scrollTo({ top: med.offsetTop - 80, behavior: 'smooth' });
      const btn = document.getElementById('toggleLogTableBtn');
      if (btn) {
        btn.innerHTML = '<i class="fas fa-ambulance"></i> Driver‑Ambu';
        btn.style.background = 'linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%)';
      }
    }
  }

  function toggleLogTable() {
    const amb = document.getElementById('driverAmbulanceLogSection');
    const med = document.getElementById('driverMedicLogSection');
    if (amb && med) {
      if (amb.style.display !== 'none') {
        showDriverMedicLog();
      } else {
        showDriverAmbulanceLog();
      }
    }
  }
</script>
</body>
</html>
