<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $driver->name }} - Driver Details - MDRRMO</title>
    <link rel="stylesheet" href="{{ asset('css/stylish.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .driver-show-container {
            max-width: 1400px;
            margin: 30px auto;
            padding: 40px;
            background: #ffffff;
            border-radius: 24px;
            border: 1px solid #e5e7eb;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            position: relative;
            overflow: hidden;
        }
        .driver-show-container::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, #f59e0b 0%, #f97316 100%);
            border-radius: 24px 24px 0 0;
        }
        
        .row {
            display: grid;
            gap: 24px;
            margin-bottom: 32px;
        }
        
        .row-1 {
            grid-template-columns: 1fr;
            display: grid;
            align-items: stretch;
        }
        
        .row-2 {
            grid-template-columns: 1fr;
        }
        
        .row-3 {
            grid-template-columns: 1fr;
        }
        
        .tile {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 20px;
            padding: 32px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
            transition: box-shadow 0.3s ease;
        }
        .tile:hover {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
        .tile::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 3px;
            background: linear-gradient(90deg, #f59e0b 0%, #f97316 100%);
            border-radius: 20px 20px 0 0;
        }
        
        .tile-content {
            background: #fff;
            border: 2px solid #000;
            border-radius: 15px;
            padding: 20px;
            height: 100%;
        }
        
        .tile-header {
            font-size: 18px;
            font-weight: 900;
            color: #000;
            text-transform: uppercase;
            margin-bottom: 20px;
            letter-spacing: 1px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }
        
        .driver-photo {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            border: 4px solid #000;
            object-fit: cover;
            margin: 0 auto 20px;
            display: block;
        }
        
        .driver-photo-placeholder {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            border: 4px solid #000;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 36px;
            margin: 0 auto 20px;
        }
        
        .driver-photo-large {
          
            width: 230px;
            height: 230px;
            border-radius: 50%;
            border: 4px solid #fff;
            object-fit: cover;
            margin: 0 auto;
            display: block;
            background: #f59e0b;
            margin-left: 0;
        }
        
        .driver-photo-large-placeholder {
            width: 200px;
            height: 200px;
            border-radius: 50%;
            border: 4px solid #fff;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 60px;
            margin: 0 auto;
        }
        
        .nav-btn {
            background: linear-gradient(135deg, #f59e0b 0%, #f97316 100%);
            border: none;
            color: #fff;
            padding: 12px 24px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 4px 6px -1px rgba(245, 158, 11, 0.3);
        }
        
        .nav-btn:hover {
            background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(245, 158, 11, 0.4);
        }
        
        .driver-name-large {
            font-size: 28px;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 24px;
            letter-spacing: -0.5px;
            line-height: 1.2;
        }
        
        .section-header-white {
            font-size: 18px;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 20px;
            letter-spacing: -0.3px;
            padding-bottom: 12px;
            border-bottom: 2px solid #f3f4f6;
        }
        
        .info-item-white {
            display: flex;
            flex-direction: column;
            margin-bottom: 16px;
        }
        
        .info-label-white {
            font-weight: 600;
            color: #6b7280;
            font-size: 12px;
            margin-bottom: 6px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .info-value-white {
            font-weight: 500;
            color: #1f2937;
            font-size: 15px;
            line-height: 1.5;
        }
        
        .quick-action-btn {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            border: none;
            color: #fff;
            padding: 12px 20px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            box-shadow: 0 4px 6px -1px rgba(59, 130, 246, 0.3);
        }
        .quick-action-btn1 {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            border: none;
            color: #fff;
            padding: 12px 20px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            box-shadow: 0 4px 6px -1px rgba(59, 130, 246, 0.3);
        }
        
        .quick-action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(59, 130, 246, 0.4);
        }
        
        .activity-text {
            color: #4b5563;
            font-size: 14px;
            font-weight: 500;
            line-height: 1.6;
        }
        
        .status-label {
            color: #6b7280;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
        }
        
        .status-badge-small {
            border: none;
            border-radius: 8px;
            padding: 8px 16px;
            font-size: 12px;
            font-weight: 600;
            text-transform: capitalize;
            letter-spacing: 0.3px;
            color: #fff;
            display: inline-block;
            width: fit-content;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .status-badge-small-offline {
            background: #9ca3af;
            color: #fff;
        }
        
        .driver-name {
            font-size: 24px;
            font-weight: 900;
            color: #000;
            text-align: center;
            margin-bottom: 15px;
            text-transform: uppercase;
        }
        
        .info-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .info-item:last-child {
            border-bottom: none;
        }
        
        .info-label {
            font-weight: 600;
            color: #374151;
            font-size: 14px;
        }
        
        .info-value {
            font-weight: 500;
            color: #000;
            font-size: 14px;
        }
        
        .status-badge {
            border: 1px solid #000;
            border-radius: 15px;
            padding: 6px 15px;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #fff;
            display: inline-block;
        }
        
        .status-active {
            background: #10b981;
        }
        
        .status-inactive {
            background: #6b7280;
        }
        
        .status-suspended {
            background: #ef4444;
        }
        
        .status-online {
            background: #10b981;
        }
        
        .status-offline {
            background: #6b7280;
        }
        
        .status-busy {
            background: #ef4444;
        }
        
        .status-on-break {
            background: #f59e0b;
        }
        
        .action-btn {
            background: #1e3a8a;
            border: 2px solid #000;
            color: #fff;
            padding: 12px 20px;
            border-radius: 8px;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin: 5px;
        }
        
        .action-btn:hover {
            background: #1e40af;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.3);
        }
        
        .action-btn-danger {
            background: #ef4444;
        }
        
        .action-btn-danger:hover {
            background: #dc2626;
        }
        
        .activity-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 0;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .activity-item:last-child {
            border-bottom: none;
        }
        
        .activity-icon {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: #1e3a8a;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
        }
        
        .activity-content {
            flex: 1;
        }
        
        .activity-title {
            font-weight: 600;
            color: #000;
            font-size: 14px;
        }
        
        .activity-time {
            font-size: 12px;
            color: #6b7280;
        }
        
        @media (max-width: 1200px) {
            .row-1, .row-3 {
                grid-template-columns: 1fr;
            }
            .row-2 {
                grid-template-columns: 1fr;
            }
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
    <div class="logo-container">
        <img src="{{ asset('image/mdrrmologo.jpg') }}" alt="Logo" class="logo-img">
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

<!-- Fixed Top Header -->
<div class="mdrrmo-header" style="border: 2px solid #031273;">
    <h2 class="header-title">SILANG MDRRMO</h2>
</div>

<main class="main-content pt-8">
  <!-- Header with Title Outside Container -->
  <div style="text-align: center; margin-bottom: 20px;">
    <!-- <h1 style="            font-size: 24px;
            font-weight: 900;
            text-align: center;
            text-transform: uppercase;
            border: 3px solid #000;
            padding: 6px 12px;
            border-radius: 10px;
            display: inline-block;
            scroll-behavior: block;
            margin-top: 5px;">
      DRIVER PROFILE: {{ strtoupper($driver->name) }}
    </h1> -->
  </div>

  <div class="driver-show-container">
    <!-- Navigation Buttons Inside Container -->
    <div style="display: flex; gap: 12px; margin-bottom: 32px; align-items: center;">
      <a href="{{ route('admin.drivers.index') }}" class="nav-btn">
        <i class="fas fa-arrow-left"></i> Back
      </a>
      <a href="{{ route('admin.drivers.edit', $driver) }}" class="nav-btn">
        <i class="fas fa-edit"></i> Edit Driver
      </a>
    </div>

    <!-- First Row: Basic Info, Personal Info -->
    <div class="row row-1">
      <!-- Tile 2 & 3: Combined Dark Blue Information Section -->
      <div class="tile">
        <!-- <div class="tile-content" style="background: #1e3a8a; border: 2px solid #8b5cf6; padding: 30px; margin-left: 0;"> -->
          <div style="display: grid; grid-template-columns: 1fr auto 1fr; gap: 24px; align-items: start;">
            
            <!-- Left Column: Driver Information -->
            <div>
              <div class="driver-name-large">Driver {{ $driver->name }}</div>
              
              <div style="display: flex; flex-direction: column; gap: 16px;">
                <div class="info-item-white">
                  <span class="info-label-white">Email</span>
                  <span class="info-value-white">{{ $driver->email ?? 'Not provided' }}</span>
                </div>
                
                @if($driver->employee_id)
                <div class="info-item-white">
                  <span class="info-label-white">Employee ID</span>
                  <span class="info-value-white">{{ $driver->employee_id }}</span>
                </div>
                @endif
                
                <div class="info-item-white">
                  <span class="info-label-white">Status</span>
                  <span class="info-value-white">{{ ucfirst($driver->status) }} • {{ ucfirst(str_replace('_', ' ', $driver->availability_status)) }}</span>
                </div>
                
                @if($driver->last_seen_at)
                <div class="info-item-white">
                  <span class="info-label-white">Last Seen</span>
                  <span class="info-value-white">{{ $driver->last_seen_at->diffForHumans() }}</span>
                </div>
                @endif
              </div>
            </div>

            <!-- Vertical Divider -->
            <div style="width: 1px; background: #e5e7eb; height: 100%; min-height: 200px;"></div>

            <!-- Right Column: Personal Information -->
            <div>
              <div class="section-header-white">Personal Information</div>
              
              <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <!-- Left sub-column -->
                <div style="display: flex; flex-direction: column; gap: 16px;">
                  <div class="info-item-white">
                    <span class="info-label-white">Phone Number</span>
                    <span class="info-value-white">{{ $driver->phone ?? 'Not provided' }}</span>
                  </div>
                  <div class="info-item-white">
                    <span class="info-label-white">Gender</span>
                    <span class="info-value-white">{{ $driver->gender ? ucfirst($driver->gender) : 'Not specified' }}</span>
                  </div>
                  <div class="info-item-white">
                    <span class="info-label-white">Address</span>
                    <span class="info-value-white">{{ $driver->address ?? 'Not provided' }}</span>
                  </div>
                </div>
                
                <!-- Right sub-column -->
                <div style="display: flex; flex-direction: column; gap: 16px;">
                  <div class="info-item-white">
                    <span class="info-label-white">Date of Birth</span>
                    <span class="info-value-white">
                      @if($driver->date_of_birth)
                        {{ $driver->date_of_birth->format('M d, Y') }}
                        @if(method_exists($driver, 'getAttribute') && $driver->age)
                          <span style="color: #6b7280;">({{ $driver->age }} years old)</span>
                        @endif
                      @else
                        Not provided
                      @endif
                    </span>
                  </div>
                </div>
              </div>
            </div>
            
          </div>
        <!-- </div> -->
      </div>
    </div>

    <!-- Second Row: Professional Info, Emergency Contact -->
    <div class="row row-2">
      <div class="tile">
         <!-- <div class="tile-content" style="background: #1e3a8a; border: 2px solid #8b5cf6; padding: 30px;"> -->
           <div style="display: grid; grid-template-columns: 1fr auto 1fr; gap: 40px; align-items: start;">
             
             <!-- Left Section: Professional Information -->
             <div>
               <div class="section-header-white">Professional Information</div>
               
               <div class="info-item-white">
                 <span class="info-label-white">Assigned Ambulance</span>
                 <span class="info-value-white">
                   @if($driver->ambulance)
                     {{ $driver->ambulance->name }}
                   @else
                     Not assigned
                   @endif
                 </span>
               </div>
             </div>

             <!-- Vertical Divider -->
             <div style="width: 1px; background: #e5e7eb; height: 100%; min-height: 200px;"></div>

             <!-- Right Section: Emergency Contact -->
             <div>
               <div class="section-header-white">Emergency Contact</div>
               
               <div class="info-item-white">
                 <span class="info-label-white">Contact Name</span>
                 <span class="info-value-white">{{ $driver->emergency_contact_name ?? 'Not provided' }}</span>
               </div>
               
               <div class="info-item-white">
                 <span class="info-label-white">Contact Phone</span>
                 <span class="info-value-white">{{ $driver->emergency_contact_phone ?? 'Not provided' }}</span>
               </div>
             </div>
             
           </div>
        <!-- </div> -->
      </div>
    </div>

    <!-- Third Row: Quick Actions, Recent Activity, Status Management -->
    <div class="row row-3">
      <div class="tile">
        <!-- <div class="tile-content" style="background: #1e3a8a; border: 2px solid #8b5cf6; padding: 30px;"> -->
          <div style="display: grid; grid-template-columns: 1fr auto 1fr auto 1fr; gap: 40px; align-items: start;">
            
            <!-- Left Section: Quick Actions -->
            <div>
              <div class="section-header-white" style="text-align: center;">Quick Actions</div>
              
              <div style="display: flex; flex-direction: column; gap: 12px; align-items: center;">
                <button onclick="toggleAvailability({{ $driver->id }})" class="quick-action-btn" style="background: linear-gradient(135deg, #f59e0b 0%, #f97316 100%); box-shadow: 0 4px 6px -1px rgba(245, 158, 11, 0.3);">
                  <i class="fas fa-toggle-on"></i> Toggle Availability
                </button>
                
                <form method="POST" action="{{ route('admin.drivers.destroy', $driver) }}" onsubmit="return confirm('Are you sure you want to delete this driver?')" style="margin: 0; width: 100%;">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="quick-action-btn" style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); box-shadow: 0 4px 6px -1px rgba(239, 68, 68, 0.3);">
                    <i class="fas fa-trash"></i> Delete Driver
                  </button>
                </form>
                
                <a href="{{ route('admin.drivers.edit', $driver) }}" class="quick-action-btn1">
                  <i class="fas fa-edit"></i> Edit Profile
                </a>
              </div>
            </div>

            <!-- First Vertical Divider -->
            <div style="width: 1px; background: #e5e7eb; height: 100%; min-height: 200px;"></div>

            <!-- Middle Section: Recent Activity -->
            <div>
              <div class="section-header-white" style="text-align: center;">Recent Activity</div>
              
              <div style="display: flex; flex-direction: column; gap: 16px; margin-top: 12px;">
                <div class="activity-text">
                  <i class="fas fa-calendar-plus" style="color: #f59e0b; margin-right: 8px;"></i>
                  Profile created {{ $driver->created_at->diffForHumans() }}
                </div>
                <div class="activity-text">
                  <i class="fas fa-edit" style="color: #3b82f6; margin-right: 8px;"></i>
                  Last updated {{ $driver->updated_at->diffForHumans() }}
                </div>
                @if($driver->last_seen_at)
                  <div class="activity-text">
                    <i class="fas fa-eye" style="color: #10b981; margin-right: 8px;"></i>
                    Last seen {{ $driver->last_seen_at->diffForHumans() }}
                  </div>
                @else
                  <div class="activity-text">
                    <i class="fas fa-eye-slash" style="color: #9ca3af; margin-right: 8px;"></i>
                    Last seen Never
                  </div>
                @endif
              </div>
            </div>

            <!-- Second Vertical Divider -->
            <div style="width: 1px; background: #e5e7eb; height: 100%; min-height: 200px;"></div>

            <!-- Right Section: Status Management -->
            <div>
              <div class="section-header-white" style="text-align: center;">Status Management</div>
              
              <div style="display: flex; flex-direction: column; gap: 20px;">
                <!-- Status Indicators Row -->
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                  <!-- Driver Status -->
                  <div style="display: flex; flex-direction: column; gap: 8px;">
                    <div class="status-label">Driver Status</div>
                    <span class="status-badge-small status-{{ $driver->status }}" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                      {{ ucfirst($driver->status) }}
                    </span>
                  </div>
                  
                  <!-- Availability Status -->
                  <div style="display: flex; flex-direction: column; gap: 8px;">
                    <div class="status-label">Availability</div>
                    <span class="status-badge-small status-{{ str_replace('_', '-', $driver->availability_status) }}" style="background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);">
                      {{ ucfirst(str_replace('_', ' ', $driver->availability_status)) }}
                    </span>
                  </div>
                </div>
                
                <!-- Update Status Button
                <div style="display: flex; justify-content: center; margin-top: 10px;">
                  <button onclick="openStatusModal()" class="quick-action-btn" style="background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 100%); border: 2px solid #000; border-radius: 25px; padding: 12px 30px; font-weight: 700; text-transform: uppercase; font-size: 14px; color: #fff; cursor: pointer; transition: all 0.3s ease; width: fit-content;">
                    Update Status
                  </button>
                </div> -->
              </div>
            </div>
            
          </div>
        <!-- </div> -->
      </div>
    </div>
  </div>
</main>

<script>
function toggleSidebar() {
    const sidenav = document.getElementById('sidenav');
    if (!sidenav) return;
    sidenav.classList.toggle('active');
}

function toggleAvailability(driverId) {
    if (confirm('Are you sure you want to toggle this driver\'s availability?')) {
        fetch(`/admin/drivers/${driverId}/toggle-availability`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error updating availability');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error updating availability');
        });
    }
}

function changeStatus(status) {
    if (confirm(`Are you sure you want to set this driver's status to ${status}?`)) {
        fetch(`/admin/drivers/{{ $driver->id }}/change-status`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                availability_status: status
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error updating status');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error updating status');
        });
    }
}
</script>
</body>
</html>