@extends('layouts.app')


<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Medics Management</title>
    <link rel="stylesheet" href="{{ asset('css/stylish.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
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
      <a href="{{ url('/admin/reported-cases') }}" class="{{ request()->is('admin/reported-cases') ? 'active' : '' }}"><i class="fas fa-file-alt"></i> Reported Cases</a>
    
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
  
    <!-- Header bar below MDRRMO: Dashboard-style layout -->
    <div style="width: 95%; max-width: 1200px; margin: 4px auto 8px auto; display:flex; align-items:center; justify-content: space-between; position: relative; z-index: 3001;">
      <div style="text-align: left; font-weight: 900; color: #031273; letter-spacing: .2px;">
        Create Page
      </div>
      <div style="display:flex; align-items:center; gap:0.4rem; position:relative;">
        <button type="button" class="panel-btn" id="toggleMedicsButtons" aria-expanded="false" aria-controls="medicsMenuDropdown" style="width:auto; padding:8px 12px; display:flex; flex-direction:row; gap:8px; align-items:center; background:#031273; color:#ffffff; border:0; border-radius:8px; font-weight:800; letter-spacing:.2px; display:inline-flex; align-items:center; gap:8px; cursor:pointer;">
          <i class="fas fa-bars"></i>
          Menu
        </button>
        <div class="button-container posting-menu" id="medicsMenuDropdown" style="display:none;">
          <button type="button" class="menu-btn panel-btn" id="menu-register">
            <i class="fas fa-user-plus"></i>
            <span>Register</span>
          </button>
          <button type="button" class="menu-btn panel-btn" id="menu-medic">
            <i class="fas fa-user-md"></i>
            <span>+Medic</span>
          </button>
          <button type="button" class="menu-btn panel-btn" id="menu-ambulance">
            <i class="fas fa-ambulance"></i>
            <span>+Ambulance</span>
          </button>
          <button type="button" class="menu-btn panel-btn" id="menu-driver">
            <i class="fas fa-car"></i>
            <span>+Driver</span>
          </button>
        </div>
      </div>
    </div>

    <!-- Panel Navigation Buttons -->
    <div class="button-container" id="medicsButtons" style="display:none; position:absolute; right:0; top:100%; margin-top:6px; width: 240px; background:#ffffff; border:1px solid #e5e7eb; border-radius:12px; box-shadow:0 12px 28px rgba(0,0,0,0.18); padding: 10px; z-index: 9999; grid-template-columns: 1fr; gap: 8px;">
      <button onclick="showPanel('register')" class="panel-btn active" id="register-btn">
        <i class="fas fa-user-plus"></i>
        <span>Register</span>
      </button>
      <button onclick="showPanel('medic')" class="panel-btn" id="medic-btn">
        <i class="fas fa-user-md"></i>
        <span>+Medic</span>
      </button>
      <button onclick="showPanel('ambulance')" class="panel-btn" id="ambulance-btn">
        <i class="fas fa-ambulance"></i>
        <span>+Ambulance</span>
      </button>
      <button onclick="showPanel('driver')" class="panel-btn" id="driver-btn">
        <i class="fas fa-car"></i>
        <span>+Driver</span>
      </button>
    </div>

    <!-- Register Panel (Default) -->
    <div id="register-panel" class="panel-content">
      <div class="register-container">
        <div class="register-card">
          <div class="register-header">
            <h3><i class="fas fa-user-plus"></i> Register New User</h3>
          </div>
          
          <!-- Error messages will be handled by modal -->
          
          <form method="POST" action="{{ route('register') }}" class="register-form">
            @csrf
            <div class="form-layout">
              <div class="form-fields">
                <div class="input-group">
                  <label for="name"><i class="fas fa-user"></i> Name</label>
                  <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus placeholder="Enter your name">
                </div>
                <div class="input-group">
                  <label for="email"><i class="fas fa-envelope"></i> Email</label>
                  <input id="email" type="email" name="email" value="{{ old('email') }}" required placeholder="Enter your email address">
                </div>
                <div class="input-group">
                  <label for="password"><i class="fas fa-lock"></i> Password</label>
                  <input id="password" type="password" name="password" required autocomplete="new-password" placeholder="Enter your password">
                </div>
                <div class="input-group">
                  <label for="password_confirmation"><i class="fas fa-lock"></i> Confirm Password</label>
                  <input id="password_confirmation" type="password" name="password_confirmation" required placeholder="Confirm your password">
                </div>
              </div>
              <div class="logo-section">
                <img src="{{ asset('image/mdrrmologo.jpg') }}" alt="Municipal Seal" class="municipal-logo">
              </div>
            </div>
            <div class="form-footer">
              <a href="{{ route('login') }}" class="login-link">
                <i class="fas fa-sign-in-alt"></i> Already registered?
              </a>
              <button type="submit" class="register-btn">
                <i class="fas fa-user-plus"></i> Register
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Medic Panel -->
    <div id="medic-panel" class="panel-content" style="display: none;">
      <div class="medic-container">
        <div class="medic-header" style="background-color:#f28c28;">
          <div class="medic-header-left">
            <h3 style="color:white"><i class="fas fa-user-md"></i> Medics Management</h3>
            <div class="medic-search-container">
              <div class="search-input-group">
                <input type="text" id="medicSearchInput" class="medic-search-input" placeholder="Search by name, email, or phone...">
                <button type="button" id="clearMedicSearch" class="clear-search-btn" style="display: none;">
                  <i class="fas fa-times"></i>
                </button>
              </div>
            </div>
          </div>
          <button onclick="openMedicModal()" class="add-btn">
            <i class="fas fa-plus"></i> Add New Medic
          </button>
    </div>

    <!-- Success and Error messages will be handled by modals -->

        <div class="medic-table-container">
          <div class="overflow-x-auto">
            <table class="medic-table" data-paginate="true" data-page-size="10">
              <thead>
                <tr>
                  <th>Name</th>
                  <th>Phone</th>
                  <th>Specialization</th>
                  <th>Status</th>
                  <th>Actions</th>
              </tr>
            </thead>
              <tbody>
              @forelse($medics as $medic)
                <tr>
                    <td class="font-medium">{{ $medic->name }}</td>
                    <td>{{ $medic->phone ?? 'N/A' }}</td>
                    <td>{{ $medic->specialization ?? 'N/A' }}</td>
                    <td>
                      <span class="status-badge {{ $medic->status === 'active' ? 'active' : 'inactive' }}">
                      {{ ucfirst($medic->status) }}
                    </span>
                  </td>
                    <td class="actions">
                      <button onclick="openEditModal({{ $medic->id }}, '{{ $medic->name }}', '{{ $medic->phone }}', '{{ $medic->specialization }}', '{{ $medic->status }}')" class="action-btn edit">
                        <i class="fas fa-edit"></i>
                      </button>
                      <form action="{{ route('admin.medics.destroy', $medic->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this medic?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="action-btn delete">
                          <i class="fas fa-trash"></i>
                        </button>
                      </form>
                    </td>
                </tr>
              @empty
                <tr>
                    <td colspan="5" class="no-data">No medics found.</td>
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
    </div>

    <!-- Ambulance Panel -->
    <div id="ambulance-panel" class="panel-content" style="display: none;">
      <div class="ambulance-container">
        <div class="ambulance-header" style="background-color:#f28c28;">
          <h3 style="color:white"><i class="fas fa-ambulance"></i> Ambulance Management</h3>
        </div>

        <!-- Add New Ambulance Form -->
        <div class="ambulance-form-card">
          <h4><i class="fas fa-plus-circle"></i> Add New Ambulance</h4>
          <form action="{{ route('admin.ambulances.store') }}" method="POST" class="ambulance-form">
            @csrf
            <div class="form-row">
              <div class="input-group">
                <label>Name</label>
                <input type="text" name="name" required placeholder="Enter ambulance name">
              </div>
              <div class="input-group">
                <label>Status</label>
                <select name="status" required>
                  <option value="Available">Available</option>
                  <option value="Out">Out</option>
                  <option value="Unavailable">Unavailable</option>
                </select>
              </div>
            </div>
            <button type="submit" class="submit-btn">
              <i class="fas fa-plus"></i> Add Ambulance
            </button>
          </form>
        </div>

        <!-- Existing Ambulances -->
        <div class="ambulance-list-card">
          <div class="ambulance-list-header">
            <h4><i class="fas fa-list"></i> Existing Ambulances</h4>
            <div class="ambulance-search-container">
              <div class="search-input-group">
                <input type="text" id="ambulanceSearchInput" class="ambulance-search-input" placeholder="Search ambulances by name...">
                <button type="button" id="clearAmbulanceSearch" class="clear-search-btn" style="display: none;">
                  <i class="fas fa-times"></i>
                </button>
              </div>
            </div>
          </div>
          <div class="ambulance-grid">
            @forelse ($ambulances as $amb)
              <div class="ambulance-item">
                <div class="ambulance-info">
                  <h5>{{ $amb->name }}</h5>
                  <span class="ambulance-status {{ strtolower($amb->status) }}">
                    {{ $amb->status }}
                  </span>
                </div>
              </div>
            @empty
              <div class="no-ambulances">
                <i class="fas fa-ambulance"></i>
                <p>No ambulances yet.</p>
              </div>
            @endforelse
          </div>
        </div>
      </div>
    </div>

    <!-- Driver Panel (Create Driver Form) -->
    <div id="driver-panel" class="panel-content" style="display: none;">
      <div class="driver-container">
        <div class="driver-header" style="background-color:#f28c28;">
          <h3 style="color:white"><i class="fas fa-car"></i> Driver Management</h3>
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

        <form action="{{ route('admin.drivers.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
          @csrf
          
          <!-- Basic Information -->
          <div id="driver-section-1" class="driver-form-card">
            <h4><i class="fas fa-user"></i> Basic Information</h4>
            <div class="form-row">
              <div class="input-group">
                <label>Full Name</label>
                <input type="text" name="name" value="{{ old('name') }}" required placeholder="Enter driver's full name">
              </div>
              <div class="input-group">
                <label>Phone Number</label>
                <input type="text" name="phone" value="{{ old('phone') }}" placeholder="Enter phone number">
              </div>
            </div>
            <div class="form-row">
              <div class="input-group">
                <label>Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required placeholder="Enter email address">
              </div>
              <div class="input-group">
                <label>Date of Birth</label>
                <input type="date" name="date_of_birth" value="{{ old('date_of_birth') }}">
              </div>
            </div>
            <div class="form-row">
              <div class="input-group">
                <label>Gender</label>
                <select name="gender">
                  <option value="">Select Gender</option>
                  <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                  <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                  <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
                </select>
              </div>
            </div>
            <div class="form-row">
              <div class="input-group">
                <label>Password</label>
                <input type="password" name="password" required placeholder="Enter password">
              </div>
              <div class="input-group">
                <label>Confirm Password</label>
                <input type="password" name="password_confirmation" required placeholder="Confirm password">
              </div>
            </div>
            <div class="form-row">
              <div class="input-group" style="grid-column: 1 / -1;">
                <label>Address</label>
                <textarea name="address" rows="2" placeholder="Enter address">{{ old('address') }}</textarea>
              </div>
            </div>
            <div class="form-actions">
              <button type="button" class="btn-next" onclick="nextDriverStep()">Next</button>
            </div>
          </div>

          <!-- Professional + Emergency Section -->
          <div id="driver-section-2" class="driver-form-card" style="display: none;">
            <h4><i class="fas fa-briefcase"></i> Professional Information</h4>
            <div class="form-row">
              <div class="input-group">
                <label>Employee ID</label>
                <input type="text" name="employee_id" value="{{ old('employee_id') }}" placeholder="Enter employee ID">
              </div>
              <div class="input-group">
                <label>Status</label>
                <select name="status" required>
                  <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                  <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                  <option value="suspended" {{ old('status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                  <option value="on_leave" {{ old('status') == 'on_leave' ? 'selected' : '' }}>On Leave</option>
                </select>
              </div>
            </div>
            
            <h4><i class="fas fa-phone"></i> Emergency Contact</h4>
            <div class="form-row">
              <div class="input-group">
                <label>Emergency Contact Name</label>
                <input type="text" name="emergency_contact_name" value="{{ old('emergency_contact_name') }}" placeholder="Enter emergency contact name">
              </div>
              <div class="input-group">
                <label>Emergency Contact Phone</label>
                <input type="text" name="emergency_contact_phone" value="{{ old('emergency_contact_phone') }}" placeholder="Enter emergency contact phone">
              </div>
            </div>
            <div class="form-actions">
              <button type="button" class="btn-prev" onclick="prevDriverStep()">Prev</button>
              <button type="button" class="btn-next" onclick="nextDriverStep()">Next</button>
            </div>
          </div>

          <!-- Skills and Certifications -->
          <div id="driver-section-3" class="driver-form-card" style="display: none;">
            <h4><i class="fas fa-certificate"></i> Skills and Certifications</h4>
            <div class="form-row">
              <div class="input-group">
                <label>Certifications (one per line)</label>
                <textarea name="certifications_text" rows="4" placeholder="e.g., First Aid Certification&#10;CPR Certification&#10;Emergency Response Training">{{ old('certifications_text') }}</textarea>
              </div>
              <div class="input-group">
                <label>Skills (one per line)</label>
                <textarea name="skills_text" rows="4" placeholder="e.g., Defensive Driving&#10;Emergency Response&#10;Vehicle Maintenance">{{ old('skills_text') }}</textarea>
              </div>
            </div>
            <div class="form-row">
              <div class="input-group" style="grid-column: 1 / -1;">
                <label>Notes</label>
                <textarea name="notes" rows="3" placeholder="Additional notes about the driver...">{{ old('notes') }}</textarea>
              </div>
            </div>
            <div class="form-actions">
              <button type="button" class="btn-prev" onclick="prevDriverStep()">Prev</button>
              <button type="button" onclick="showPanel('medic')" class="btn-danger" style="text-decoration:none;">Cancel</button>
              <button type="submit" class="btn-primary">Create Driver</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </section>
</main>

<!-- Add New Medic Modal -->
<div id="medicModal" class="modal">
  <div class="modal-content" style="height: 43%;">
    <div class="modal-header">
      <h3><i class="fas fa-user-md"></i> Add New Medic ubfubfeufbeu</h3>
      <button class="close-btn" onclick="closeMedicModal()">
        <i class="fas fa-times"></i>
      </button>
    </div>
    <form action="{{ route('admin.medics.store') }}" method="POST" class="modal-form">
      @csrf
      
      <!-- Display validation errors in modal -->
      @if($errors->any())
        <div class="modal-error-alert">
          <div class="modal-error-title">Validation Errors:</div>
          <ul class="modal-error-list">
            @foreach($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif
      
      <div class="form-grid">
        <div class="input-group">
          <label for="modal_name"><i class="fas fa-user"></i> Name</label>
          <input id="modal_name" type="text" name="name" value="{{ old('name') }}" required placeholder="Enter medic name">
        </div>
        <div class="input-group">
          <label for="modal_phone"><i class="fas fa-phone"></i> Phone</label>
          <input id="modal_phone" type="text" name="phone" value="{{ old('phone') }}" placeholder="Enter phone number">
        </div>
        <div class="input-group">
          <label for="modal_specialization"><i class="fas fa-stethoscope"></i> Specialization</label>
          <input id="modal_specialization" type="text" name="specialization" value="{{ old('specialization') }}" placeholder="Enter specialization">
        </div>
        <div class="input-group">
          <label for="modal_status"><i class="fas fa-toggle-on"></i> Status</label>
          <select id="modal_status" name="status" required>
            <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
          </select>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" onclick="closeMedicModal()" class="cancel-btn">
          <i class="fas fa-times"></i> Cancel
        </button>
        <button type="submit" class="submit-btn">
          <i class="fas fa-save"></i> Add Medic
        </button>
      </div>
      <div class="modal-footer-bar"></div>
    </form>
  </div>
</div>

<!-- Edit Medic Modal -->
<div id="editMedicModal" class="modal">
  <div class="modal-content" style="height: 43%;">
    <div class="modal-header">
      <h3><i class="fas fa-edit"></i> Edit Medic</h3>
      <button class="close-btn" onclick="closeEditModal()">
        <i class="fas fa-times"></i>
      </button>
    </div>
    <form id="editMedicForm" method="POST" class="modal-form">
      @csrf
      @method('PUT')
      <div class="form-grid">
        <div class="input-group">
          <label for="edit_name"><i class="fas fa-user"></i> Name</label>
          <input id="edit_name" type="text" name="name" required placeholder="Enter medic name">
        </div>
        <div class="input-group">
          <label for="edit_phone"><i class="fas fa-phone"></i> Phone</label>
          <input id="edit_phone" type="text" name="phone" placeholder="Enter phone number">
        </div>
        <div class="input-group">
          <label for="edit_specialization"><i class="fas fa-stethoscope"></i> Specialization</label>
          <input id="edit_specialization" type="text" name="specialization" placeholder="Enter specialization">
        </div>
        <div class="input-group">
          <label for="edit_status"><i class="fas fa-toggle-on"></i> Status</label>
          <select id="edit_status" name="status" required>
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
          </select>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" onclick="closeEditModal()" class="cancel-btn">
          <i class="fas fa-times"></i> Cancel
        </button>
        <button type="submit" class="submit-btn">
          <i class="fas fa-save"></i> Update Medic
        </button>
      </div>
      <div class="modal-footer-bar edit"></div>
    </form>
  </div>
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

<!-- Error Modal -->
<div id="errorModal" class="error-modal" style="display: none;">
  <div class="error-modal-content">
    <div class="error-modal-header">
      <i class="fas fa-exclamation-triangle"></i>
      <h3>Error!</h3>
    </div>
    <div class="error-modal-body">
      <p id="errorMessage"></p>
    </div>
    <div class="error-modal-footer">
      <button onclick="closeErrorModal()" class="error-modal-btn">OK</button>
    </div>
  </div>
</div>

<script>
  function toggleSidebar() {
    const sidenav = document.getElementById('sidenav');
    if (!sidenav) return;
    sidenav.classList.toggle('active');
  }

  // Toggle medics menu like posting page
  (function(){
    const toggleBtn = document.getElementById('toggleMedicsButtons');
    const dropdown = document.getElementById('medicsMenuDropdown');
    if (toggleBtn && dropdown){
      toggleBtn.addEventListener('click', function(){
        const isOpen = dropdown.style.display === 'block';
        dropdown.style.display = isOpen ? 'none' : 'grid';
        toggleBtn.setAttribute('aria-expanded', String(!isOpen));
      });
    }

    // Wire dropdown buttons to existing panels
    function clickById(id){ const el = document.getElementById(id); if (el) el.click(); }
    const map = {
      'menu-register': 'register-btn',
      'menu-medic': 'medic-btn',
      'menu-ambulance': 'ambulance-btn',
      'menu-driver': 'driver-btn'
    };
    Object.keys(map).forEach(menuId => {
      const menuBtn = document.getElementById(menuId);
      if (!menuBtn) return;
      menuBtn.addEventListener('click', function(){
        const targetBtnId = map[menuId];
        if (dropdown) dropdown.style.display = 'none';
        clickById(targetBtnId);
      });
    });
    document.addEventListener('click', function(e){
      if (!dropdown) return;
      const within = e.target === dropdown || dropdown.contains(e.target) || e.target === toggleBtn;
      if (!within) dropdown.style.display = 'none';
    });
  })();

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

  function showPanel(panelName) {
    // Hide all panels
    const panels = document.querySelectorAll('.panel-content');
    panels.forEach(panel => {
      panel.style.display = 'none';
    });

    // Remove active class from all buttons
    const buttons = document.querySelectorAll('.panel-btn');
    buttons.forEach(btn => {
      btn.classList.remove('active');
    });

    // Show selected panel
    const selectedPanel = document.getElementById(panelName + '-panel');
    if (selectedPanel) {
      selectedPanel.style.display = 'block';
    }

    // Add active class to selected button
    const selectedButton = document.getElementById(panelName + '-btn');
    if (selectedButton) {
      selectedButton.classList.add('active');
    }

    // Reset driver form if switching to driver panel
    if (panelName === 'driver') {
      showDriverSection(1);
    }
  }

  function openMedicModal() {
    document.getElementById('medicModal').style.display = 'flex';
    document.body.style.overflow = 'hidden';
  }

  function closeMedicModal() {
    document.getElementById('medicModal').style.display = 'none';
    document.body.style.overflow = 'auto';
  }

  function openEditModal(id, name, phone, specialization, status) {
    // Set form action
    document.getElementById('editMedicForm').action = `/admin/medics/${id}`;
    
    // Populate form fields
    document.getElementById('edit_name').value = name;
    document.getElementById('edit_phone').value = phone || '';
    document.getElementById('edit_specialization').value = specialization || '';
    document.getElementById('edit_status').value = status;
    
    // Show modal
    document.getElementById('editMedicModal').style.display = 'flex';
    document.body.style.overflow = 'hidden';
  }

  function closeEditModal() {
    document.getElementById('editMedicModal').style.display = 'none';
    document.body.style.overflow = 'auto';
  }

  // Success Modal Functions
  function showSuccessModal(message) {
    document.getElementById('successMessage').textContent = message;
    document.getElementById('successModal').style.display = 'flex';
    document.body.style.overflow = 'hidden';
  }

  function closeSuccessModal() {
    document.getElementById('successModal').style.display = 'none';
    document.body.style.overflow = 'auto';
  }

  // Error Modal Functions
  function showErrorModal(message) {
    document.getElementById('errorMessage').textContent = message;
    document.getElementById('errorModal').style.display = 'flex';
    document.body.style.overflow = 'hidden';
  }

  function closeErrorModal() {
    document.getElementById('errorModal').style.display = 'none';
    document.body.style.overflow = 'auto';
  }

  // Close modal when clicking outside
  window.onclick = function(event) {
    const medicModal = document.getElementById('medicModal');
    const editModal = document.getElementById('editMedicModal');
    const successModal = document.getElementById('successModal');
    const errorModal = document.getElementById('errorModal');
    
    if (event.target === medicModal) {
      closeMedicModal();
    }
    
    if (event.target === editModal) {
      closeEditModal();
    }
    
    if (event.target === successModal) {
      closeSuccessModal();
    }
    
    if (event.target === errorModal) {
      closeErrorModal();
    }
  }

  // Search functionality for medic table
  function filterMedicTable() {
    const searchInput = document.getElementById('medicSearchInput');
    const clearBtn = document.getElementById('clearMedicSearch');
    const table = document.querySelector('.medic-table tbody');
    
    if (!searchInput || !table) return;
    
    const searchTerm = searchInput.value.toLowerCase().trim();
    const rows = table.querySelectorAll('tr');
    
    let visibleCount = 0;
    
    rows.forEach(row => {
      const nameCell = row.cells[0]?.textContent.toLowerCase() || '';
      const emailCell = row.cells[1]?.textContent.toLowerCase() || '';
      const phoneCell = row.cells[2]?.textContent.toLowerCase() || '';
      
      const matches = nameCell.includes(searchTerm) || 
                     emailCell.includes(searchTerm) || 
                     phoneCell.includes(searchTerm);
      
      if (matches || searchTerm === '') {
        row.style.display = '';
        visibleCount++;
      } else {
        row.style.display = 'none';
      }
    });
    
    // Show/hide clear button
    if (searchTerm !== '') {
      clearBtn.style.display = 'block';
    } else {
      clearBtn.style.display = 'none';
    }
    
    // Show "No results" message if needed
    let noResultsRow = table.querySelector('.no-results-row');
    if (visibleCount === 0 && searchTerm !== '') {
      if (!noResultsRow) {
        noResultsRow = document.createElement('tr');
        noResultsRow.className = 'no-results-row';
        noResultsRow.innerHTML = '<td colspan="6" class="no-data">No medics found matching your search.</td>';
        table.appendChild(noResultsRow);
      }
      noResultsRow.style.display = '';
    } else if (noResultsRow) {
      noResultsRow.style.display = 'none';
    }
  }

  // Search functionality for ambulance table
  function filterAmbulanceTable() {
    const searchInput = document.getElementById('ambulanceSearchInput');
    const clearBtn = document.getElementById('clearAmbulanceSearch');
    const ambulanceGrid = document.querySelector('.ambulance-grid');
    
    if (!searchInput || !ambulanceGrid) return;
    
    const searchTerm = searchInput.value.toLowerCase().trim();
    const ambulanceItems = ambulanceGrid.querySelectorAll('.ambulance-item');
    
    let visibleCount = 0;
    
    ambulanceItems.forEach(item => {
      const nameElement = item.querySelector('h5');
      const name = nameElement ? nameElement.textContent.toLowerCase() : '';
      
      const matches = name.includes(searchTerm);
      
      if (matches || searchTerm === '') {
        item.style.display = '';
        visibleCount++;
      } else {
        item.style.display = 'none';
      }
    });
    
    // Show/hide clear button
    if (searchTerm !== '') {
      clearBtn.style.display = 'block';
    } else {
      clearBtn.style.display = 'none';
    }
    
    // Show "No results" message if needed
    let noResultsDiv = ambulanceGrid.querySelector('.no-results-div');
    if (visibleCount === 0 && searchTerm !== '') {
      if (!noResultsDiv) {
        noResultsDiv = document.createElement('div');
        noResultsDiv.className = 'no-results-div';
        noResultsDiv.innerHTML = '<div class="no-ambulances"><p>No ambulances found matching your search.</p></div>';
        ambulanceGrid.appendChild(noResultsDiv);
      }
      noResultsDiv.style.display = '';
    } else if (noResultsDiv) {
      noResultsDiv.style.display = 'none';
    }
  }

  // Clear search functions
  function clearMedicSearch() {
    const searchInput = document.getElementById('medicSearchInput');
    if (searchInput) {
      searchInput.value = '';
      filterMedicTable();
    }
  }

  function clearAmbulanceSearch() {
    const searchInput = document.getElementById('ambulanceSearchInput');
    if (searchInput) {
      searchInput.value = '';
      filterAmbulanceTable();
    }
  }

  // Driver form multi-step functionality
  let currentDriverSection = 1;

  function showDriverSection(index) {
    for (let i = 1; i <= 3; i++) {
      const el = document.getElementById(`driver-section-${i}`);
      if (!el) continue;
      el.style.display = i === index ? 'block' : 'none';
    }
    currentDriverSection = index;
  }

  function nextDriverStep() {
    if (currentDriverSection < 3) {
      showDriverSection(currentDriverSection + 1);
      window.scrollTo({ top: 0, behavior: 'smooth' });
    }
  }

  function prevDriverStep() {
    if (currentDriverSection > 1) {
      showDriverSection(currentDriverSection - 1);
      window.scrollTo({ top: 0, behavior: 'smooth' });
    }
  }

  // Convert textarea inputs to arrays for form submission
  function setupDriverFormSubmission() {
    const form = document.querySelector('form[action*="drivers.store"]');
    if (!form) return;
    
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
  }

  // Simple client-side pagination for medic table
  document.addEventListener('DOMContentLoaded', function(){
    // Initialize driver form
    showDriverSection(1);
    setupDriverFormSubmission();

    // Show success modal if there's a success message
    @if(session('success'))
      showSuccessModal('{{ session('success') }}');
    @endif

    // Show medic modal if there are validation errors (so user can see and fix them)
    @if($errors->any())
      openMedicModal();
    @endif

    // Add event listeners for search functionality
    const medicSearchInput = document.getElementById('medicSearchInput');
    const ambulanceSearchInput = document.getElementById('ambulanceSearchInput');
    const clearMedicBtn = document.getElementById('clearMedicSearch');
    const clearAmbulanceBtn = document.getElementById('clearAmbulanceSearch');

    if (medicSearchInput) {
      medicSearchInput.addEventListener('input', filterMedicTable);
    }

    if (ambulanceSearchInput) {
      ambulanceSearchInput.addEventListener('input', filterAmbulanceTable);
    }

    if (clearMedicBtn) {
      clearMedicBtn.addEventListener('click', clearMedicSearch);
    }

    if (clearAmbulanceBtn) {
      clearAmbulanceBtn.addEventListener('click', clearAmbulanceSearch);
    }
    document.querySelectorAll('table.medic-table[data-paginate="true"]').forEach(function(tbl){
      const pageSize = parseInt(tbl.getAttribute('data-page-size') || '10', 10);
      const tbody = tbl.querySelector('tbody');
      if (!tbody) return;
      const rows = Array.from(tbody.children);
      if (rows.length <= pageSize) return; // nothing to paginate

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
</script>

<style>
  /* Button Container */
  .button-container {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 16px;
    margin-bottom: 24px;
    max-width: 800px;
    margin-left: auto;
    margin-right: auto;
  }
  
  /* Posting dropdown menu: one button per row */
  .posting-toolbar { position: relative; }
  .posting-menu { position:absolute; right:0; top:100%; margin-top:6px; width: 240px; display: grid; grid-template-columns: 1fr !important; gap: 8px; padding: 10px; background:#fff; border:1px solid #e5e7eb; border-radius:12px; box-shadow:0 12px 28px rgba(0,0,0,0.18); z-index: 50000; }
  .posting-menu .menu-btn { justify-content:flex-start; flex-direction:row; gap:10px; padding:10px 12px; }
  .posting-menu .menu-btn i { font-size:14px; }
  .posting-menu .menu-btn span { font-size:12px; }

  /* Panel Navigation Buttons */
  .panel-btn {
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
    border: 2px solid #cbd5e1;
    color: #475569;
    padding: 12px 8px;
    border-radius: 10px;
    cursor: pointer;
    transition: all 0.3s ease;
    font-weight: 600;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 6px;
    justify-content: center;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    width: 100%;
  }

  .panel-btn:hover {
    background: linear-gradient(135deg, #e2e8f0 0%, #cbd5e1 100%);
    border-color: #94a3b8;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
  }

  .panel-btn.active {
    background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
    border-color: #3b82f6;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
  }

  .panel-btn i {
    font-size: 16px;
  }

  .panel-btn span {
    font-size: 11px;
    text-align: center;
  }

  /* Panel Content Animation */
  .panel-content {
    animation: fadeIn 0.4s ease-in;
    width: 100%;
  }

  @keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
  }

  /* Register Panel Styles */
  #register-panel {
    width: 100%;
    max-width: none;
  }

  #register-panel .register-container {
    max-width: 800px;
    margin: 0 auto;
    padding: 20px;
    width: 100%;
  }

  #register-panel .register-card {
    background: white;
    border-radius: 16px;
    box-shadow: 0 8px 32px rgba(0,0,0,0.1);
    overflow: hidden;
  }

  #register-panel .register-header {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    color: white;
    padding: 24px;
    text-align: center;
  }

  #register-panel .register-header h3 {
    margin: 0;
    font-size: 24px;
    font-weight: 600;
  }

  #register-panel .register-form {
    padding: 32px;
  }

  #register-panel .form-layout {
    display: grid;
    grid-template-columns: 1fr auto;
    gap: 40px;
    align-items: start;
  }

  #register-panel .form-fields {
    display: grid;
    gap: 20px;
  }

  #register-panel .input-group {
    display: flex;
    flex-direction: column;
    gap: 8px;
  }

  #register-panel .input-group label {
    font-weight: 600;
    color: #374151;
    display: flex;
    align-items: center;
    gap: 8px;
  }

  #register-panel .input-group input {
    padding: 12px 16px;
    border: 2px solid #e5e7eb;
    border-radius: 8px;
    font-size: 16px;
    transition: all 0.3s ease;
  }

  #register-panel .input-group input:focus {
    outline: none;
    border-color: #4338ca;
    box-shadow: 0 0 0 3px rgba(67, 56, 202, 0.1);
  }

  #register-panel .logo-section {
    display: flex;
    justify-content: center;
    align-items: center;
  }

  #register-panel .municipal-logo {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    object-fit: cover;
    box-shadow: 0 4px 16px rgba(0,0,0,0.1);
  }

  #register-panel .form-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 32px;
    padding-top: 24px;
    border-top: 1px solid #e5e7eb;
  }

  #register-panel .login-link {
    color: #6b7280;
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: color 0.3s ease;
  }

  #register-panel .login-link:hover {
    color: #4338ca;
  }

  #register-panel .register-btn {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    color: white;
    border: none;
    padding: 12px 24px;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 8px;
  }

  #register-panel .register-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
  }

  #register-panel .error-alert {
    background: #fef2f2;
    border: 1px solid #fecaca;
    color: #dc2626;
    padding: 16px;
    border-radius: 8px;
    margin-bottom: 24px;
  }

  #register-panel .error-title {
    font-weight: 600;
    margin-bottom: 8px;
  }

  #register-panel .error-list {
    margin: 0;
    padding-left: 20px;
  }

  /* Medic Panel Styles */
  .medic-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
  }

  .medic-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 24px;
    padding: 20px;
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 16px rgba(0,0,0,0.1);
  }

  .medic-header-left {
    display: flex;
    align-items: center;
    gap: 20px;
    flex: 1;
  }

  .medic-search-container {
    flex: 1;
    max-width: 400px;
  }

  .search-input-group {
    position: relative;
    display: flex;
    align-items: center;
  }

  .search-icon {
    position: absolute;
    left: 12px;
    color: #6b7280;
    z-index: 1;
  }

  .medic-search-input {
    width: 100%;
    padding: 10px 12px;
    border: 2px solid #e5e7eb;
    border-radius: 8px;
    font-size: 14px;
    transition: all 0.3s ease;
    background: white;
  }

  .medic-search-input:focus {
    outline: none;
    border-color: #059669;
    box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.1);
  }

  .clear-search-btn {
    position: absolute;
    right: 8px;
    background: none;
    border: none;
    color: #6b7280;
    cursor: pointer;
    padding: 4px;
    border-radius: 4px;
    transition: all 0.2s ease;
  }

  .clear-search-btn:hover {
    background: #f3f4f6;
    color: #374151;
  }

  .medic-header h3 {
    margin: 0;
    color: #1f2937;
    font-size: 24px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 12px;
  }

  .add-btn {
    background: linear-gradient(135deg, #059669 0%, #047857 100%);
    color: white;
    padding: 12px 20px;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s ease;
  }

  .add-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(5, 150, 105, 0.3);
  }

  .success-alert {
    background: #f0fdf4;
    border: 1px solid #bbf7d0;
    color: #166534;
    padding: 16px;
    border-radius: 8px;
    margin-bottom: 24px;
    font-weight: 500;
  }

  .medic-table-container {
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 16px rgba(0,0,0,0.1);
    overflow: hidden;
  }

  .medic-table {
    width: 100%;
    border-collapse: collapse;
  }

  .medic-table thead {
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
  }

  .medic-table th {
    padding: 16px;
    text-align: left;
    font-weight: 600;
    color: #374151;
    border-bottom: 2px solid #e5e7eb;
  }

  .medic-table td {
    padding: 16px;
    border-bottom: 1px solid #f3f4f6;
  }

  .medic-table tbody tr:hover {
    background: #f9fafb;
  }

  .status-badge {
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
  }

  .status-badge.active {
    background: #dcfce7;
    color: #166534;
  }

  .status-badge.inactive {
    background: #fee2e2;
    color: #dc2626;
  }

  .actions {
    display: flex;
    gap: 8px;
  }

  .action-btn {
    padding: 8px;
    border-radius: 6px;
    text-decoration: none;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
  }

  .action-btn.edit {
    background: #fed7aa;
    color: #d97706;
  }

  .action-btn.edit:hover {
    background: #fdba74;
  }

  .action-btn.delete {
    background: #fee2e2;
    color: #dc2626;
  }

  .action-btn.delete:hover {
    background: #fecaca;
  }

  .no-data {
    text-align: center;
    color: #6b7280;
    font-style: italic;
    padding: 40px;
  }

  /* Table Pager Styles */
  .table-pager {
    display: flex;
    justify-content: flex-end;
    gap: 0.5rem;
    padding: 0.5rem 0.25rem;
  }

  .pager-btn {
    background: #f3f4f6;
    color: #374151;
    border: 1px solid #d1d5db;
    padding: 0.5rem 1rem;
    border-radius: 6px;
    cursor: pointer;
    font-size: 0.875rem;
    font-weight: 500;
    transition: all 0.2s ease;
  }

  .pager-btn:hover {
    background: #e5e7eb;
    border-color: #9ca3af;
  }

  .pager-btn[disabled] {
    opacity: 0.5;
    cursor: default;
  }

  /* Ambulance Panel Styles */
  .ambulance-container {
    max-width: 1000px;
    margin: 0 auto;
    padding: 20px;
    display: grid;
    gap: 24px;
  }

  .ambulance-header {
    background: white;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 4px 16px rgba(0,0,0,0.1);
    display: flex;
    justify-content: space-between;
    align-items: center;
  }

  .ambulance-header-left {
    display: flex;
    align-items: center;
    gap: 20px;
    flex: 1;
  }

  .ambulance-search-container {
    flex: 1;
    max-width: 400px;
  }

  .ambulance-search-input {
    width: 100%;
    padding: 10px 12px;
    border: 2px solid #e5e7eb;
    border-radius: 8px;
    font-size: 14px;
    transition: all 0.3s ease;
    background: white;
  }

  .ambulance-search-input:focus {
    outline: none;
    border-color: #10b981;
    box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
  }

  .ambulance-header h3 {
    margin: 0;
    color: #1f2937;
    font-size: 24px;
    font-weight: 600;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 12px;
  }

  .ambulance-form-card, .ambulance-list-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 16px rgba(0,0,0,0.1);
    padding: 24px;
  }

  .ambulance-form-card h4, .ambulance-list-card h4 {
    margin: 0 0 20px 0;
    color: #374151;
    font-size: 18px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 8px;
  }

  .ambulance-list-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
  }

  .ambulance-list-header h4 {
    margin: 0;
    color: #374151;
    font-size: 18px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 8px;
  }

  .form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
    margin-bottom: 20px;
  }

  .ambulance-form .input-group {
    display: flex;
    flex-direction: column;
    gap: 8px;
  }

  .ambulance-form .input-group label {
    font-weight: 600;
    color: #374151;
  }

  .ambulance-form .input-group input,
  .ambulance-form .input-group select {
    padding: 12px;
    border: 2px solid #e5e7eb;
    border-radius: 8px;
    font-size: 16px;
    transition: all 0.3s ease;
  }

  .ambulance-form .input-group input:focus,
  .ambulance-form .input-group select:focus {
    outline: none;
    border-color: #4338ca;
    box-shadow: 0 0 0 3px rgba(67, 56, 202, 0.1);
  }

  .submit-btn {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: white;
    border: none;
    padding: 12px 24px;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 8px;
    margin: 0 auto;
  }

  .submit-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
  }

  .ambulance-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 16px;
  }

  .ambulance-item {
    background: #f8fafc;
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    padding: 20px;
    transition: all 0.3s ease;
  }

  .ambulance-item:hover {
    border-color: #4338ca;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
  }

  .ambulance-info h5 {
    margin: 0 0 8px 0;
    color: #1f2937;
    font-size: 18px;
    font-weight: 600;
  }

  .ambulance-status {
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
  }

  .ambulance-status.available {
    background: #dcfce7;
    color: #166534;
  }

  .ambulance-status.out {
    background: #fef3c7;
    color: #d97706;
  }

  .ambulance-status.unavailable {
    background: #fee2e2;
    color: #dc2626;
  }

  .no-ambulances {
    grid-column: 1 / -1;
    text-align: center;
    padding: 40px;
    color: #6b7280;
  }

  .no-ambulances i {
    font-size: 48px;
    margin-bottom: 16px;
    opacity: 0.5;
  }

  .no-results-div {
    grid-column: 1 / -1;
    display: flex;
    justify-content: center;
    align-items: center;
    width: 100%;
  }

  .no-results-div .no-ambulances {
    text-align: center;
    padding: 40px;
    color: #6b7280;
  }

  /* Driver Panel Styles */
  .driver-container {
    max-width: 1000px;
    margin: 0 auto;
    padding: 20px;
    display: grid;
    gap: 24px;
  }

  .driver-header {
    background: white;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 4px 16px rgba(0,0,0,0.1);
    display: flex;
    justify-content: space-between;
    align-items: center;
  }

  .driver-header h3 {
    margin: 0;
    color: #1f2937;
    font-size: 24px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 12px;
  }

  .driver-form-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 16px rgba(0,0,0,0.1);
    padding: 24px;
    margin-top: -23px;
  }

  .driver-form-card h4 {
    margin: 0 0 20px 0;
    color: #374151;
    font-size: 18px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 8px;
  }

  .form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
    margin-bottom: 20px;
  }

  .driver-form-card .input-group {
    display: flex;
    flex-direction: column;
    gap: 8px;
  }

  .driver-form-card .input-group label {
    font-weight: 600;
    color: #374151;
  }

  .driver-form-card .input-group input,
  .driver-form-card .input-group select,
  .driver-form-card .input-group textarea {
    padding: 12px;
    border: 2px solid #e5e7eb;
    border-radius: 8px;
    font-size: 16px;
    transition: all 0.3s ease;
  }

  .driver-form-card .input-group input:focus,
  .driver-form-card .input-group select:focus,
  .driver-form-card .input-group textarea:focus {
    outline: none;
    border-color: #4338ca;
    box-shadow: 0 0 0 3px rgba(67, 56, 202, 0.1);
  }

  .form-actions {
    display: flex;
    justify-content: flex-end;
    gap: 12px;
    margin-top: 24px;
    padding-top: 20px;
    border-top: 1px solid #e5e7eb;
  }

  .btn-next, .btn-prev {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: white;
    border: none;
    padding: 12px 24px;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 8px;
  }

  .btn-next:hover, .btn-prev:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
  }

  .btn-primary {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: white;
    border: none;
    padding: 12px 24px;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 8px;
  }

  .btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
  }

  .btn-danger {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    color: white;
    border: none;
    padding: 12px 24px;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 8px;
  }

  .btn-danger:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
  }

  /* Modal Styles */
  .modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    backdrop-filter: blur(4px);
    align-items: center;
    justify-content: center;
  }

  .modal-content {
    background: white;
    border-radius: 12px;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    max-width: 480px;
    width: 90%;
    max-height: 70vh;
    min-height: auto;
    overflow: hidden;
    animation: modalSlideIn 0.3s ease-out;
    display: flex;
    flex-direction: column;
  }

  @keyframes modalSlideIn {
    from {
      opacity: 0;
      transform: translateY(-50px) scale(0.9);
    }
    to {
      opacity: 1;
      transform: translateY(0) scale(1);
    }
  }

  .modal-header {
    background: linear-gradient(135deg, #059669 0%, #047857 100%);
    color: white;
    padding: 12px 16px;
    border-radius: 12px 12px 0 0;
    display: flex;
    justify-content: space-between;
    align-items: center;
    width: 100%;
    flex-shrink: 0;
  }

  .modal-header h3 {
    margin: 0;
    font-size: 16px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 6px;
  }

  .close-btn {
    background: none;
    border: none;
    color: white;
    font-size: 16px;
    cursor: pointer;
    padding: 4px;
    border-radius: 50%;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 28px;
    height: 28px;
  }

  .close-btn:hover {
    background: rgba(255, 255, 255, 0.2);
  }

  .modal-form {
    padding: 12px 16px;
    flex: 0 1 auto;
    overflow-y: auto;
    display: flex;
    flex-direction: column;
  }

  .form-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 10px;
    margin-bottom: 0;
  }

  .modal-form .input-group {
    display: flex;
    flex-direction: column;
    gap: 4px;
  }

  .modal-form .input-group label {
    font-weight: 600;
    color: #374151;
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 13px;
  }

  .modal-form .input-group input,
  .modal-form .input-group select {
    padding: 8px 12px;
    border: 2px solid #e5e7eb;
    border-radius: 6px;
    font-size: 14px;
    transition: all 0.3s ease;
    background: white;
  }

  .modal-form .input-group input:focus,
  .modal-form .input-group select:focus {
    outline: none;
    border-color: #059669;
    box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.1);
  }

  .modal-footer {
    display: flex;
    justify-content: flex-end;
    gap: 8px;
    padding: 8px 16px;
    border-top: 1px solid #e5e7eb;
    background: #f9fafb;
    flex-shrink: 0;
  }

  .modal-footer-bar {
    height: 4px;
    background: linear-gradient(135deg, #059669 0%, #047857 100%);
    border-radius: 0 0 12px 12px;
    flex-shrink: 0;
  }

  .modal-footer-bar.edit {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
  }

  .cancel-btn {
    background: #f3f4f6;
    color: #374151;
    border: 2px solid #d1d5db;
    padding: 8px 14px;
    border-radius: 6px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 4px;
    font-size: 13px;
  }

  .cancel-btn:hover {
    background: #e5e7eb;
    border-color: #9ca3af;
  }

  .modal .submit-btn {
    background: linear-gradient(135deg, #059669 0%, #047857 100%);
    color: white;
    border: none;
    padding: 8px 14px;
    border-radius: 6px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 4px;
    font-size: 13px;
  }

  .modal .submit-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(5, 150, 105, 0.3);
  }

  /* Edit Modal Specific Styles */
  #editMedicModal .modal-header {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    padding: 12px 16px;
    flex-shrink: 0;
  }

  #editMedicModal .modal-header h3 {
    font-size: 16px;
    gap: 6px;
  }

  #editMedicModal .modal-form .input-group input:focus,
  #editMedicModal .modal-form .input-group select:focus {
    border-color: #f59e0b;
    box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.1);
  }

  #editMedicModal .submit-btn {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    padding: 8px 14px;
    font-size: 13px;
  }

  #editMedicModal .submit-btn:hover {
    box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
  }

  /* Success Modal Styles */
  .success-modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 10000;
  }

  .success-modal-content {
    background: #ffffff;
    border-radius: 15px;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
    max-width: 400px;
    width: 90%;
    position: relative;
    overflow: hidden;
    animation: modalSlideIn 0.2s ease-out;
  }

  .success-modal-header {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: #ffffff;
    padding: 1.5rem;
    text-align: center;
    position: relative;
  }

  .success-modal-header::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 4px;
    background: #ffffff;
  }

  .success-modal-header i {
    font-size: 3rem;
    margin-bottom: 0.5rem;
    display: block;
    filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.2));
  }

  .success-modal-header h3 {
    margin: 0;
    font-size: 1.5rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.5px;
  }

  .success-modal-body {
    padding: 2rem 1.5rem;
    text-align: center;
  }

  .success-modal-body p {
    margin: 0;
    font-size: 1.1rem;
    color: #374151;
    line-height: 1.6;
  }

  .success-modal-footer {
    padding: 1rem 1.5rem 1.5rem;
    text-align: center;
  }

  .success-modal-btn {
    background: linear-gradient(135deg, #0c2d5a 0%, #1e3a8a 100%);
    color: #ffffff;
    border: none;
    border-radius: 10px;
    padding: 0.75rem 2rem;
    font-size: 1rem;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.3s ease;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    box-shadow: 0 4px 12px rgba(3, 18, 115, 0.2);
  }

  .success-modal-btn:hover {
    background: linear-gradient(135deg, #f59e0b 0%, #ff8c42 100%);
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(242, 140, 40, 0.3);
  }

  .success-modal-btn:active {
    transform: translateY(0);
  }

  /* Error Modal Styles */
  .error-modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 10000;
  }

  .error-modal-content {
    background: white;
    border-radius: 12px;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
    max-width: 500px;
    width: 90%;
    max-height: 80vh;
    overflow-y: auto;
  }

  .error-modal-header {
    background: #dc2626;
    color: white;
    padding: 20px;
    border-radius: 12px 12px 0 0;
    text-align: center;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
  }

  .error-modal-header i {
    font-size: 24px;
  }

  .error-modal-header h3 {
    margin: 0;
    font-size: 20px;
    font-weight: 700;
  }

  .error-modal-body {
    padding: 20px;
    text-align: center;
  }

  .error-modal-body p {
    margin: 0;
    color: #374151;
    font-size: 16px;
    line-height: 1.5;
  }

  .error-modal-footer {
    padding: 20px;
    text-align: center;
    border-top: 1px solid #e5e7eb;
  }

  .error-modal-btn {
    background: #dc2626;
    color: white;
    border: none;
    padding: 12px 24px;
    border-radius: 8px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: background-color 0.2s;
  }

  .error-modal-btn:hover {
    background: #b91c1c;
  }

  /* Modal Error Alert Styles */
  .modal-error-alert {
    background: #fef2f2;
    border: 1px solid #fecaca;
    color: #dc2626;
    padding: 12px 16px;
    border-radius: 8px;
    margin-bottom: 16px;
    font-size: 14px;
  }

  .modal-error-title {
    font-weight: 600;
    margin-bottom: 8px;
    color: #dc2626;
  }

  .modal-error-list {
    margin: 0;
    padding-left: 20px;
    color: #dc2626;
  }

  .modal-error-list li {
    margin-bottom: 4px;
  }

  /* Responsive Design */
  @media (max-width: 768px) {
    #register-panel .form-layout {
      grid-template-columns: 1fr;
      gap: 24px;
    }

    #register-panel .form-footer {
      flex-direction: column;
      gap: 16px;
      align-items: center;
    }

    .medic-header {
      flex-direction: column;
      gap: 16px;
      text-align: center;
    }

    .medic-header-left {
      flex-direction: column;
      gap: 16px;
      width: 100%;
    }

    .medic-search-container {
      max-width: 100%;
      width: 100%;
    }

    .ambulance-list-header {
      flex-direction: column;
      gap: 16px;
      align-items: flex-start;
    }

    .ambulance-search-container {
      max-width: 100%;
      width: 100%;
    }

    .form-row {
      grid-template-columns: 1fr;
    }

    .ambulance-grid {
      grid-template-columns: 1fr;
    }

    .button-container {
      grid-template-columns: repeat(2, 1fr);
      gap: 12px;
    }

    .panel-btn {
      padding: 10px 6px;
    }

    .panel-btn i {
      font-size: 14px;
    }

    .panel-btn span {
      font-size: 10px;
    }

    .form-grid {
      grid-template-columns: 1fr;
      gap: 10px;
    }

    .modal-content {
      width: 95%;
      margin: 10px;
      max-width: 400px;
      min-height: auto;
      max-height: 75vh;
    }

    .modal-footer {
      flex-direction: column;
      gap: 8px;
    }

    .modal-form {
      padding: 8px 12px;
    }

    .modal-header {
      padding: 10px 12px;
    }

    .modal-header h3 {
      font-size: 15px;
    }
  }

  @media (max-width: 480px) {
    .button-container {
      grid-template-columns: 1fr;
      gap: 8px;
    }

    .panel-btn {
      flex-direction: row;
      padding: 10px 12px;
    }

    .panel-btn i {
      font-size: 14px;
    }

    .panel-btn span {
      font-size: 12px;
    }
  }
</style>
</body>
</html>