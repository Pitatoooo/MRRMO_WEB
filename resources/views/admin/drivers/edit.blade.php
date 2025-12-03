<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Driver - {{ $driver->name }} - MDRRMO</title>
    <link rel="stylesheet" href="{{ asset('css/stylish.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .driver-create-container {
            width: 100vw;
            max-width: 1100px;
            margin: 0 auto;
            padding: 12px;
            background: linear-gradient(180deg, #ff8c42 0%, #ff6b1a 100%);
            border-radius: 20px;
            border: 3px solid #000;
        }
        .driver-show-container {
            max-width: 1400px;
            margin-top: 20px;
            padding: 20px;
            background: #ffffff;
            border-radius: 20px;
            border: 3px solid #000;
            position: relative;
            overflow: hidden;
        }
        .driver-show-container::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 8px;
            background: #f59e0b;
            border-radius: 20px 20px 0 0;
        }
        .row { display: grid; gap: 20px; margin-bottom: 30px; }
        .row-1 { grid-template-columns: 1fr; }
        .row-2 { grid-template-columns: 1fr; }
        .row-3 { grid-template-columns: 1fr; }

        .tile {
            background: #ffffff;
            border: 3px solid #000;
            border-radius: 20px;
            padding: 25px;
            position: relative;
            overflow: hidden;
        }
        .tile::before { content: ""; position: absolute; top: 0; left: 0; width: 100%; height: 8px; background: #f59e0b; border-radius: 20px 20px 0 0; }
        .section-title {
            font-size: 24px;
            font-weight: 900;
            text-align: center;
            text-transform: uppercase;
            border: 3px solid #000;
            padding: 6px 12px;
            border-radius: 10px;
            display: inline-block;
            scroll-behavior: block;
            margin-top: 5px;
        }
        .panel-header {
            font-size: 22px;
            font-weight: 900;
            color: #000;
            text-transform: uppercase;
            letter-spacing: 1px;
            border: none;
            border-radius: 14px;
            padding: 10px 16px;
            display: table;
            margin: 0 auto;
        }
        .label-white {
            font-weight: 800;
            color: #fff;
            font-size: 14px;
            margin-bottom: 6px;
            display: inline-block;
        }
        .input-field {
            width: 100%;
            background: #fff;
            color: #000;
            border: 2px solid #000;
            border-radius: 8px;
            padding: 8px 10px;
            box-sizing: border-box;
            display: block;
        }
        .back-round-btn {
            background: #1e3a8a;
            border: 3px solid #000;
            color: #fff;
            height: 44px;
            padding: 0 18px;
            border-radius: 24px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        .btn-pill {
            border: 3px solid #000;
            border-radius: 14px;
            padding: 10px 22px;
            font-weight: 800;
            color: #fff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            box-shadow: inset 0 -3px 0 rgba(0,0,0,0.15);
        }
        .btn-primary {
            background: linear-gradient(135deg, #1e90ff 0%, #0072ff 100%);
        }
        .btn-danger {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        }
        .btn-large {
            border: 3px solid #000;
            color: #fff;
            height: 44px;
            padding: 0 28px;
            border-radius: 24px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            box-shadow: inset 0 -3px 0 rgba(0,0,0,0.15), 0 3px 0 rgba(0,0,0,0.2);
        }
        .btn-next {
            background: linear-gradient(135deg, #ff8c42 0%, #ff6b1a 100%);
            border: 3px solid #000;
            color: #fff;
            height: 44px;
            padding: 0 28px;
            border-radius: 24px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            box-shadow: inset 0 -3px 0 rgba(0,0,0,0.15), 0 3px 0 rgba(0,0,0,0.2);
        }
        .btn-next:hover {
            filter: brightness(1.03);
        }
        .btn-prev {
            background: linear-gradient(135deg, #ff8c42 0%, #ff6b1a 100%);
            border: 3px solid #000;
            color: #fff;
            height: 44px;
            padding: 0 28px;
            border-radius: 24px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            box-shadow: inset 0 -3px 0 rgba(0,0,0,0.15), 0 3px 0 rgba(0,0,0,0.2);
        }
        .btn-prev:hover {
            filter: brightness(1.03);
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
        <img src="{{ asset('image/mdrrmologo.jpg') }}" alt="Logo" class="logo-img" style="display:block; margin:0 auto;">
        <div style="margin-top: 8px; display:block; width:100%; text-align:center; font-weight:800; color:#ffffff; letter-spacing:.5px;">SILANG MDRRMO</div>
    </div>
    <nav class="nav-links">
      <a href="{{ url('/') }}" class="{{ request()->is('/') ? 'active' : '' }}"><i class="fas fa-chart-pie"></i> Dashboard</a>
      <span class="nav-link-locked" style="display: block; padding: 12px 16px; color: #9ca3af; cursor: not-allowed; opacity: 0.6; position: relative;"><i class="fas fa-pen"></i> Posting <i class="fas fa-lock" style="font-size: 10px; margin-left: 8px; opacity: 0.7;"></i></span>
      <a href="{{ url('/admin/pairing') }}" class="{{ request()->is('admin/pairing') ? 'active' : '' }}"><i class="fas fa-link"></i> Pairing</a>
      <a href="{{ url('/admin/drivers') }}" class="{{ request()->is('admin/drivers*') ? 'active' : '' }}"><i class="fas fa-car"></i> Drivers</a>
      <a href="{{ url('/admin/medics') }}" class="{{ request()->is('admin/medics*') ? 'active' : '' }}"><i class="fas fa-user-md"></i> Medics</a>
      <a href="{{ url('/admin/gps') }}" class="{{ request()->is('admin/gps') ? 'active' : '' }}"><i class="fas fa-map-marker-alt mr-1"></i> GPS Tracker</a>
      <a href="{{ url('/admin/ambulances') }}" class="{{ request()->is('admin/ambulances*') ? 'active' : '' }}"><i class="fas fa-ambulance mr-1"></i> Ambulances</a>

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

<!-- Fixed Top Header with user menu -->
<div class="mdrrmo-header" style="background:#F7F7F7; box-shadow: 0 2px 8px rgba(0,0,0,0.12); border: none; min-height: var(--header-height); padding: 1rem 2rem; display: flex; align-items: center; justify-content: center; position: fixed; top: 0; margin-left: 260px; width: calc(100% - 260px); z-index: 1200;">
    <h2 class="header-title" style="display:none;"></h2>
    @php $firstName = explode(' ', auth()->user()->name ?? 'Admin')[0]; @endphp
    <div id="userMenu" style="position: fixed; right: 16px; top: 16px; display: inline-flex; align-items: center; gap: 10px; cursor: pointer; color: #e5e7eb; z-index: 1300; background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.15); padding: 6px 10px; border-radius: 9999px; box-shadow: 0 6px 18px rgba(0,0,0,0.18); backdrop-filter: saturate(140%);">
        <div style="width: 28px; height: 28px; border-radius: 9999px; background: linear-gradient(135deg,#4CC9F0,#031273); display: inline-flex; align-items: center; justify-content: center; position: relative;">
            <i class="fas fa-user-shield" style="font-size: 14px; color: #ffffff;"></i>
            <span style="position: absolute; right: -1px; bottom: -1px; width: 8px; height: 8px; border-radius: 9999px; background: #22c55e; box-shadow: 0 0 0 2px #0c2d5a;"></span>
        </div>
        <span style="font-weight: 800; color: #000000; letter-spacing: .2px;">{{ $firstName }}</span>
        <i class="fas fa-chevron-down" style="font-size: 10px; color: rgba(255,255,255,0.85);"></i>
        <div id="userDropdown" style="display: none; position: absolute; right: 0; top: calc(100% + 12px); background: #ffffff; color: #0f172a; border-radius: 10px; box-shadow: 0 10px 24px rgba(0,0,0,0.2); padding: 8px; min-width: 160px; z-index: 1301;">
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

<main class="main-content pt-8" style="padding-top: calc(var(--header-height) + 8px);">
  <!-- <section class="containers-grid" style="max-width:1200px;"> -->
    <div style="text-align: center; margin-bottom: 20px;">
      <h1 class="section-title">EDIT DRIVER: {{ strtoupper($driver->name) }}</h1>
    </div>

    @if($errors->any())
      <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        <ul class="list-disc list-inside">
          @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form action="{{ route('admin.drivers.update', $driver) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
      @csrf
      @method('PUT')
      
      <div class="driver-show-container">
        <div style="display:flex; gap:10px; margin-bottom:30px;">
          <a href="{{ route('admin.drivers.show', $driver) }}" class="nav-btn" style="background:#f59e0b; border:2px solid #000; color:#fff; padding:10px 16px; border-radius:25px; font-weight:800;"><i class="fas fa-arrow-left"></i></a>
        </div>

        <div class="row row-1">
          <div class="tile">
            <div style="display: grid; grid-template-columns: 1fr auto 1fr; gap: 24px; align-items: start;">
              <div>
                <div class="driver-name-large" style="font-size: 28px; font-weight: 900; color: #000; margin-bottom: 25px; text-transform: uppercase; letter-spacing: 1px;">DRIVER {{ strtoupper($driver->name) }}</div>
                <div style="display:flex; flex-direction:column; gap:10px;">
                  <div class="info-item-white">
                    <label class="info-label-white">Full Name</label>
                    <input type="text" name="name" value="{{ old('name', $driver->name) }}" class="input-field" required>
                  </div>
                  <div class="info-item-white">
                    <label class="info-label-white">Email</label>
                    <input type="email" name="email" value="{{ old('email', $driver->email) }}" class="input-field" required>
                  </div>
                  <div class="info-item-white">
                    <label class="info-label-white">Employee ID</label>
                    <input type="text" name="employee_id" value="{{ old('employee_id', $driver->employee_id) }}" class="input-field">
                  </div>
                  <div class="info-item-white">
                    <label class="info-label-white">Status</label>
                    <select name="status" class="input-field" required>
                      <option value="active" {{ old('status', $driver->status) == 'active' ? 'selected' : '' }}>Active</option>
                      <option value="inactive" {{ old('status', $driver->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                      <option value="suspended" {{ old('status', $driver->status) == 'suspended' ? 'selected' : '' }}>Suspended</option>
                      <option value="on_leave" {{ old('status', $driver->status) == 'on_leave' ? 'selected' : '' }}>On Leave</option>
                    </select>
                  </div>
                </div>
              </div>

              <div style="width: 2px; background: #f59e0b; height: 100%; min-height: 200px;"></div>

              <div>
                <div class="section-header-white" style="font-size: 20px; font-weight: 900; color: #000; margin-bottom: 20px; text-transform: uppercase; letter-spacing: 1px;">Personal Information</div>
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                  <div class="info-item-white">
                    <label class="info-label-white">Phone</label>
                    <input type="text" name="phone" value="{{ old('phone', $driver->phone) }}" class="input-field">
                  </div>
                  <div class="info-item-white">
                    <label class="info-label-white">Gender</label>
                    <select name="gender" class="input-field">
                      <option value="">Select Gender</option>
                      <option value="male" {{ old('gender', $driver->gender) == 'male' ? 'selected' : '' }}>Male</option>
                      <option value="female" {{ old('gender', $driver->gender) == 'female' ? 'selected' : '' }}>Female</option>
                      <option value="other" {{ old('gender', $driver->gender) == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                  </div>
                  <div class="info-item-white">
                    <label class="info-label-white">Date of Birth</label>
                    <input type="date" name="date_of_birth" value="{{ old('date_of_birth', $driver->date_of_birth ? $driver->date_of_birth->format('Y-m-d') : '') }}" class="input-field">
                  </div>
                  <div class="info-item-white" style="grid-column:1 / -1;">
                    <label class="info-label-white">Address</label>
                    <textarea name="address" rows="2" class="input-field">{{ old('address', $driver->address) }}</textarea>
                  </div>
                  <div class="info-item-white" style="grid-column:1 / -1; display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                    <div>
                      <label class="info-label-white">New Password</label>
                      <input type="password" name="password" class="input-field" placeholder="Leave blank to keep current password">
                    </div>
                    <div>
                      <label class="info-label-white">Confirm Password</label>
                      <input type="password" name="password_confirmation" class="input-field" placeholder="Leave blank to keep current password">
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="row row-2">
          <div class="tile">
            <div style="display:grid; grid-template-columns:1fr auto 1fr; gap:40px; align-items:start;">
              <div>
                <div class="section-header-white">Professional Information</div>
                <div class="info-item-white">
                  <label class="info-label-white">Availability Status</label>
                  <select name="availability_status" class="input-field" required>
                    <option value="online" {{ old('availability_status', $driver->availability_status) == 'online' ? 'selected' : '' }}>Online</option>
                    <option value="offline" {{ old('availability_status', $driver->availability_status) == 'offline' ? 'selected' : '' }}>Offline</option>
                    <option value="busy" {{ old('availability_status', $driver->availability_status) == 'busy' ? 'selected' : '' }}>Busy</option>
                    <option value="on_break" {{ old('availability_status', $driver->availability_status) == 'on_break' ? 'selected' : '' }}>On Break</option>
                  </select>
                </div>
                <div class="info-item-white">
                  <label class="info-label-white">Assigned Ambulance</label>
                  <select name="ambulance_id" class="input-field">
                    <option value="">Not Assigned</option>
                    @foreach($ambulances as $ambulance)
                      <option value="{{ $ambulance->id }}" {{ old('ambulance_id', $driver->ambulance_id) == $ambulance->id ? 'selected' : '' }}>{{ $ambulance->name }}</option>
                    @endforeach
                  </select>
                </div>
              </div>

              <div style="width: 2px; background: #f59e0b; height: 100%; min-height: 200px;"></div>

              <div>
                <div class="section-header-white"><b>Emergency Contact</b></div>
                <div class="info-item-white">
                  <label class="info-label-white">Contact Name</label>
                  <input type="text" name="emergency_contact_name" value="{{ old('emergency_contact_name', $driver->emergency_contact_name) }}" class="input-field">
                </div>
                <div class="info-item-white">
                  <label class="info-label-white">Contact Phone</label>
                  <input type="text" name="emergency_contact_phone" value="{{ old('emergency_contact_phone', $driver->emergency_contact_phone) }}" class="input-field">
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="row row-3">
          <div class="tile">
            <div class="section-header-white" style="font-size:20px; font-weight:900; color:#000; margin-bottom:20px; text-transform:uppercase; letter-spacing:1px;">Skills and Certifications</div>
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:14px;">
              <div>
                <label class="label-white">Certifications (one per line)</label>
                <textarea name="certifications_text" rows="4" class="input-field" placeholder="e.g., First Aid Certification&#10;CPR Certification&#10;Emergency Response Training">{{ old('certifications_text', $driver->certifications && is_array($driver->certifications) ? implode("\n", $driver->certifications) : '') }}</textarea>
              </div>
              <div>
                <label class="label-white">Skills (one per line)</label>
                <textarea name="skills_text" rows="4" class="input-field" placeholder="e.g., Defensive Driving&#10;Emergency Response&#10;Vehicle Maintenance">{{ old('skills_text', $driver->skills && is_array($driver->skills) ? implode("\n", $driver->skills) : '') }}</textarea>
              </div>
              <div style="grid-column:1 / -1;">
                <label class="label-white">Notes</label>
                <textarea name="notes" rows="3" class="input-field" placeholder="Additional notes about the driver...">{{ old('notes', $driver->notes) }}</textarea>
              </div>
            </div>
            <div class="flex justify-center gap-6" style="margin: 12px 0 0; align-items: center;">
              <a href="{{ route('admin.drivers.show', $driver) }}" class="btn-danger btn-large" style="text-decoration:none;">Cancel</a>
              <button type="submit" class="btn-primary btn-large">Update Driver</button>
            </div>
          </div>
        </div>
      </div>
    </form>
  <!-- </section> -->
</main>

<script>
function toggleSidebar() {
  const sidenav = document.getElementById('sidenav');
  if (!sidenav) return;
  sidenav.classList.toggle('active');
}

// User menu + logout redirect (AJAX) like dashboard
document.addEventListener('DOMContentLoaded', function(){
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
});
let currentSection = 1;

function showSection(index) {
    for (let i = 1; i <= 3; i++) {
        const el = document.getElementById(`section-${i}`);
        if (!el) continue;
        el.style.display = i === index ? 'block' : 'none';
    }
    currentSection = index;
}

function nextStep() {
    if (currentSection < 3) {
        showSection(currentSection + 1);
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }
}

function prevStep() {
    if (currentSection > 1) {
        showSection(currentSection - 1);
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }
}

document.addEventListener('DOMContentLoaded', () => {
    showSection(1);
});
function toggleSidebar() {
    const sidenav = document.getElementById('sidenav');
    if (!sidenav) return;
    sidenav.classList.toggle('active');
}

// Convert textarea inputs to arrays for form submission
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        // Convert certifications text to array
        const certificationsText = document.querySelector('textarea[name="certifications_text"]');
        if (certificationsText) {
            const certifications = certificationsText.value.split('\n').filter(item => item.trim() !== '');
            
            // Create hidden input for certifications array
            const certificationsInput = document.createElement('input');
            certificationsInput.type = 'hidden';
            certificationsInput.name = 'certifications[]';
            certificationsInput.value = JSON.stringify(certifications);
            form.appendChild(certificationsInput);
        }
        
        // Convert skills text to array
        const skillsText = document.querySelector('textarea[name="skills_text"]');
        if (skillsText) {
            const skills = skillsText.value.split('\n').filter(item => item.trim() !== '');
            
            // Create hidden input for skills array
            const skillsInput = document.createElement('input');
            skillsInput.type = 'hidden';
            skillsInput.name = 'skills[]';
            skillsInput.value = JSON.stringify(skills);
            form.appendChild(skillsInput);
        }
    });
});
</script>
</body>
</html>

