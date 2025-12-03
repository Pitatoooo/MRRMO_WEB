@extends('layouts.app')

@section('content')
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pairing Management</title>
    <link rel="stylesheet" href="{{ asset('css/stylish.css') }}">
    <link rel="stylesheet" href="{{ asset('css/pairing.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
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

      /* Inline Modal Styles */
      .inline-modal {
        position: fixed;
        inset: 0;
        display: none;
        align-items: center;
        justify-content: center;
        background: rgba(15, 23, 42, 0.45);
        z-index: 11000;
        padding: 1rem;
      }

      .inline-modal.show {
        display: flex;
        animation: fadeIn 0.2s ease;
      }

      .inline-modal-content {
        width: min(420px, 90vw);
        border-radius: 20px;
        box-shadow: 0 20px 40px rgba(15, 23, 42, 0.25);
        overflow: hidden;
        background: #ffffff;
        transform: translateY(12px);
        animation: slideUp 0.25s cubic-bezier(0.25, 1, 0.5, 1);
      }

      .inline-modal-header {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 18px 22px;
        color: #ffffff;
      }

      .inline-modal-header i {
        font-size: 1.3rem;
      }

      .inline-modal-body {
        padding: 20px 24px;
        color: #1f2937;
        font-size: 0.95rem;
        line-height: 1.5;
      }

      .inline-modal-footer {
        padding: 16px 24px 24px 24px;
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        justify-content: flex-end;
      }

      .inline-modal-btn {
        border: none;
        border-radius: 10px;
        padding: 10px 20px;
        font-weight: 700;
        cursor: pointer;
        font-size: 0.9rem;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
      }

      .inline-modal-btn:focus {
        outline: none;
      }

      .inline-modal-btn.primary {
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
        color: #ffffff;
        box-shadow: 0 10px 20px rgba(37, 99, 235, 0.3);
      }

      .inline-modal-btn.secondary {
        background: #f1f5f9;
        color: #0f172a;
      }

      @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
      }

      @keyframes slideUp {
        from { transform: translateY(24px); }
        to { transform: translateY(0); }
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
      <a href="{{ url('/admin/reported-cases') }}" class="{{ request()->is('admin/reported-cases') ? 'active' : '' }}"><i class="fas fa-file-alt"></i> Reported Cases</a>
      <!-- placeholder ng register -->
     
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
  <!-- Pairing Navigation Bar removed -->

  <!-- Header bar: Dashboard-style layout -->
  <div style="width: 95%; max-width: 1200px; margin: 4px auto 8px auto; display:flex; align-items:center; justify-content: space-between; position: relative; z-index: 3001;">
    <div style="text-align: left; font-weight: 900; color: #031273; letter-spacing: .2px;">
      Pairing
    </div>
    <div style="display:flex; align-items:center; gap:0.4rem;">
      <a href="{{ route('admin.pairing.log') }}" style="background:#031273; color:#ffffff; border:0; border-radius:8px; padding:6px 12px; font-weight:800; letter-spacing:.2px; display:inline-flex; align-items:center; gap:8px; cursor:pointer; text-decoration:none;">
        <i class="fas fa-clock"></i>
        View Log
      </a>
    </div>
  </div>

  <section class="containers-grid" style="max-width:1400px;">
    <!-- Search and Filter Section (below headerbar) -->
    <div class="search-section">
      <form method="GET" action="{{ route('admin.pairing.index') }}" class="search-form">
        <div class="search-row">
          <div class="search-field">
          <input type="text" name="search" value="{{ request('search') }}" 
                   placeholder="Search"
                   class="search-input" id="searchInput">
            <div class="search-indicator" id="searchIndicator" style="display: none;">
              <i class="fas fa-spinner fa-spin"></i>
            </div>
        </div>
          <div class="driver-dropdown">
            <select name="driver_id" class="driver-select">
            <option value="">All Drivers</option>
            @foreach($drivers as $driver)
              <option value="{{ $driver->id }}" {{ request('driver_id') == $driver->id ? 'selected' : '' }}>
                {{ $driver->name }}
              </option>
            @endforeach
          </select>
        </div>
          <button type="button" onclick="openPairingPanel()" class="pairing-main-btn" style="display:inline-flex; align-items:center; gap:0.5rem; background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color:#fff; border:2px solid transparent; border-radius:10px; font-weight:800;">
            <i class="fas fa-link"></i> Pairing
          </button>
          <button type="button" onclick="toggleTable()" id="toggleTableBtn" class="pairing-main-btn" style="display:inline-flex; align-items:center; gap:0.5rem; background: linear-gradient(135deg, #10b981 0%, #059669 100%); color:#fff; border:2px solid transparent; border-radius:10px; font-weight:800;">
            <i class="fas fa-user-md"></i> Driver‑Medic
          </button>
        </div>
      </form>
    </div>

    <!-- Bulk Pairing Panel -->
    <div id="bulkPanel" class="pairing-panel" style="display: none; position: fixed; top: 0; right: -600px; width: 600px; height: 100vh; background: #ffffff; box-shadow: -5px 0 15px rgba(0, 0, 0, 0.1); z-index: 1000; transition: right 0.3s ease; overflow-y: auto;">
      <div class="panel-content enhanced-panel" style="background: #ffffff; border: 3px solid #8b5cf6; border-radius: 0; box-shadow: none; width: 100%; height: 100%; position: relative; overflow: hidden; animation: panelSlideIn 0.4s ease-out;">
      <div class="panel-accent-bar" style="position: absolute; top: 0; left: 0; width: 100%; height: 8px; background: linear-gradient(90deg, #8b5cf6, #7c3aed, #a855f7);"></div>
      
        <div class="panel-header enhanced-header" style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 50%, #a855f7 100%); color: white; padding: 1.25rem 1rem; position: relative; border-bottom: 1px solid rgba(255,255,255,0.1); display:flex; align-items:center; justify-content: space-between;">
        <div class="header-content" style="display: flex; align-items: center; justify-content: flex-start; gap: 0.75rem;">
          <div class="header-icon" style="width: 60px; height: 60px; background: rgba(255,255,255,0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.8rem; backdrop-filter: blur(10px);">
            <i class="fas fa-layer-group"></i>
          </div>
          <div class="header-text">
            <h3 style="margin: 0; font-size: 1.8rem; font-weight: 800; text-transform: uppercase; letter-spacing: 1px;">
              Bulk Pairing
            </h3>
            <p style="margin: 0.5rem 0 0 0; font-size: 1rem; opacity: 0.9; font-weight: 500;">
              Create multiple pairings efficiently
            </p>
          </div>
        </div>
        <button type="button" onclick="closeBulkPanel()" class="panel-close-btn" style="position: absolute; top: 50%; transform: translateY(-50%); right: 12px; background: rgba(239, 68, 68, 0.95); color: white; border: none; width: 36px; height: 36px; border-radius: 50%; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 0.95rem; transition: all 0.3s ease;">
          <i class="fas fa-times"></i>
        </button>
      </div>
      
      <div class="panel-body modern-panel-body" style="padding: 2rem; background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%); min-height: 500px;">
        <div id="bulkPanelContent" class="panel-content-wrapper">
          <!-- Form Header -->
          <div class="modern-form-header" style="text-align: center; margin-bottom: 2rem; padding: 2rem; background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%); border-radius: 20px; border: 2px solid #e5e7eb; box-shadow: 0 8px 25px rgba(0,0,0,0.08);">
            <div class="header-icon-container" style="width: 70px; height: 70px; background: linear-gradient(135deg, #8b5cf6, #7c3aed); border-radius: 20px; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem; box-shadow: 0 8px 25px rgba(139, 92, 246, 0.4);">
              <i class="fas fa-layer-group" style="color: white; font-size: 1.8rem;"></i>
            </div>
            <h4 style="color: #1f2937; font-weight: 800; font-size: 1.8rem; margin: 0 0 0.75rem 0;">Bulk Pairing Configuration</h4>
            <p style="color: #6b7280; margin: 0; font-size: 1.1rem; font-weight: 500;">Select multiple drivers, medics, and ambulances for efficient pairing</p>
          </div>
            
          <!-- Form Inputs -->
          <div class="modern-form-inputs" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 2rem; margin-bottom: 2rem;">
            <!-- Date Selection -->
            <div class="modern-input-card" style="background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%); border-radius: 20px; padding: 2rem; border: 2px solid #e5e7eb; box-shadow: 0 8px 25px rgba(0,0,0,0.08); position: relative; overflow: hidden; transition: all 0.3s ease;">
              <div class="input-header" style="display: flex; align-items: center; margin-bottom: 1.5rem; padding-bottom: 1rem; border-bottom: 2px solid #f1f5f9;">
                <div class="input-icon-bg" style="width: 50px; height: 50px; background: linear-gradient(135deg, #8b5cf6, #7c3aed); border-radius: 15px; display: flex; align-items: center; justify-content: center; margin-right: 1rem; box-shadow: 0 6px 20px rgba(139, 92, 246, 0.4);">
                  <i class="fas fa-calendar-alt" style="color: white; font-size: 1.2rem;"></i>
                </div>
                <div>
                  <h5 style="margin: 0; color: #1f2937; font-weight: 800; font-size: 1.2rem;">Pairing Date</h5>
                  <p style="margin: 0.25rem 0 0 0; color: #6b7280; font-size: 0.9rem; font-weight: 500;">Select the date for pairing</p>
                </div>
              </div>
              <div class="input-field-wrapper" style="position: relative;">
                <input type="date" class="modern-date-input" style="width: 100%; padding: 1.25rem 1.25rem 1.25rem 3.5rem; border: 2px solid #e5e7eb; border-radius: 15px; font-size: 1.1rem; font-weight: 600; color: #374151; background: #ffffff; transition: all 0.3s ease; box-shadow: inset 0 3px 6px rgba(0,0,0,0.05);" onchange="updateBulkPreview()">
                <div class="field-icon" style="position: absolute; left: 1.25rem; top: 50%; transform: translateY(-50%); color: #8b5cf6; font-size: 1.2rem; pointer-events: none;">
                  <i class="fas fa-calendar-check"></i>
                </div>
              </div>
            </div>
            
            <!-- Pairing Type -->
            <div class="modern-input-card" style="background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%); border-radius: 20px; padding: 2rem; border: 2px solid #e5e7eb; box-shadow: 0 8px 25px rgba(0,0,0,0.08); position: relative; overflow: hidden; transition: all 0.3s ease;">
              <div class="input-header" style="display: flex; align-items: center; margin-bottom: 1.5rem; padding-bottom: 1rem; border-bottom: 2px solid #f1f5f9;">
                <div class="input-icon-bg" style="width: 50px; height: 50px; background: linear-gradient(135deg, #8b5cf6, #7c3aed); border-radius: 15px; display: flex; align-items: center; justify-content: center; margin-right: 1rem; box-shadow: 0 6px 20px rgba(139, 92, 246, 0.4);">
                  <i class="fas fa-layer-group" style="color: white; font-size: 1.2rem;"></i>
                </div>
                <div>
                  <h5 style="margin: 0; color: #1f2937; font-weight: 800; font-size: 1.2rem;">Pairing Type</h5>
                  <p style="margin: 0.25rem 0 0 0; color: #6b7280; font-size: 0.9rem; font-weight: 500;">Choose the type of pairing</p>
                </div>
              </div>
              <div class="select-field-wrapper" style="position: relative;">
                <select class="modern-type-select" style="width: 100%; padding: 1.25rem 1.25rem 1.25rem 3.5rem; border: 2px solid #e5e7eb; border-radius: 15px; font-size: 1.1rem; font-weight: 600; color: #374151; background: #ffffff; transition: all 0.3s ease; box-shadow: inset 0 3px 6px rgba(0,0,0,0.05); appearance: none; cursor: pointer;" onchange="updateBulkPreview()">
                  <option value="driver_medic">Driver-Medic Pairing</option>
                  <option value="driver_ambulance">Driver-Ambulance Pairing</option>
                </select>
                <div class="field-icon" style="position: absolute; left: 1.25rem; top: 50%; transform: translateY(-50%); color: #8b5cf6; font-size: 1.2rem; pointer-events: none;">
                  <i class="fas fa-cogs"></i>
                </div>
                <div class="select-arrow" style="position: absolute; right: 1.25rem; top: 50%; transform: translateY(-50%); color: #6b7280; font-size: 1rem; pointer-events: none;">
                  <i class="fas fa-chevron-down"></i>
                </div>
              </div>
            </div>
          </div>
            
          <!-- Selection Cards -->
          <div class="modern-selection-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 2rem; margin-bottom: 2rem;">
            <!-- Drivers Selection -->
            <div class="modern-selection-card" style="background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%); border: 2px solid #e5e7eb; border-radius: 20px; padding: 2rem; transition: all 0.3s ease; box-shadow: 0 8px 25px rgba(0,0,0,0.08); position: relative; overflow: hidden;">
              <div class="selection-header" style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.5rem; padding-bottom: 1.25rem; border-bottom: 2px solid #f1f5f9;">
                <div class="selection-title-area" style="display: flex; align-items: center;">
                  <div class="selection-icon" style="width: 55px; height: 55px; background: linear-gradient(135deg, #3b82f6, #1d4ed8); border-radius: 15px; display: flex; align-items: center; justify-content: center; margin-right: 1rem; box-shadow: 0 6px 20px rgba(59, 130, 246, 0.4);">
                    <i class="fas fa-user-tie" style="color: white; font-size: 1.3rem;"></i>
                  </div>
                  <div>
                    <h5 style="margin: 0; color: #1f2937; font-weight: 800; font-size: 1.3rem;">Drivers</h5>
                    <p style="margin: 0.25rem 0 0 0; color: #6b7280; font-size: 0.95rem; font-weight: 500;">Select available drivers</p>
                  </div>
                </div>
                <button type="button" class="modern-select-btn" style="background: linear-gradient(135deg, #3b82f6, #1d4ed8); color: white; border: none; padding: 0.75rem 1.25rem; border-radius: 12px; font-size: 0.9rem; font-weight: 700; cursor: pointer; transition: all 0.3s ease; box-shadow: 0 6px 20px rgba(59, 130, 246, 0.4); display: flex; align-items: center; gap: 0.5rem;">
                  <i class="fas fa-check-double"></i>
                  Select All
                </button>
              </div>
              <div class="selection-content" style="max-height: 250px; overflow-y: auto; border: 2px solid #e5e7eb; border-radius: 15px; padding: 1rem; background: #ffffff; box-shadow: inset 0 3px 6px rgba(0,0,0,0.05);">
                <!-- Driver checkboxes will be loaded here -->
                <div class="modern-loading-state" style="text-align: center; padding: 2.5rem; color: #6b7280; background: linear-gradient(135deg, #f8fafc, #f1f5f9); border-radius: 12px; border: 2px dashed #d1d5db;">
                  <div style="width: 50px; height: 50px; background: linear-gradient(135deg, #3b82f6, #1d4ed8); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem; animation: pulse 2s infinite;">
                    <i class="fas fa-spinner fa-spin" style="color: white; font-size: 1.2rem;"></i>
                  </div>
                  <p style="margin: 0; font-weight: 700; font-size: 1rem;">Loading drivers...</p>
                </div>
              </div>
            </div>
              
            <!-- Medics Selection -->
            <div class="modern-selection-card" style="background: linear-gradient(135deg, #ffffff 0%, #f0fdf4 100%); border: 2px solid #e5e7eb; border-radius: 20px; padding: 2rem; transition: all 0.3s ease; box-shadow: 0 8px 25px rgba(0,0,0,0.08); position: relative; overflow: hidden;">
              <div class="selection-header" style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.5rem; padding-bottom: 1.25rem; border-bottom: 2px solid #f1f5f9;">
                <div class="selection-title-area" style="display: flex; align-items: center;">
                  <div class="selection-icon" style="width: 55px; height: 55px; background: linear-gradient(135deg, #10b981, #059669); border-radius: 15px; display: flex; align-items: center; justify-content: center; margin-right: 1rem; box-shadow: 0 6px 20px rgba(16, 185, 129, 0.4);">
                    <i class="fas fa-user-md" style="color: white; font-size: 1.3rem;"></i>
                  </div>
                  <div>
                    <h5 style="margin: 0; color: #1f2937; font-weight: 800; font-size: 1.3rem;">Medics</h5>
                    <p style="margin: 0.25rem 0 0 0; color: #6b7280; font-size: 0.95rem; font-weight: 500;">Select medical personnel</p>
                  </div>
                </div>
                <button type="button" class="modern-select-btn" style="background: linear-gradient(135deg, #10b981, #059669); color: white; border: none; padding: 0.75rem 1.25rem; border-radius: 12px; font-size: 0.9rem; font-weight: 700; cursor: pointer; transition: all 0.3s ease; box-shadow: 0 6px 20px rgba(16, 185, 129, 0.4); display: flex; align-items: center; gap: 0.5rem;">
                  <i class="fas fa-check-double"></i>
                  Select All
                </button>
              </div>
              <div class="selection-content" style="max-height: 250px; overflow-y: auto; border: 2px solid #e5e7eb; border-radius: 15px; padding: 1rem; background: #ffffff; box-shadow: inset 0 3px 6px rgba(0,0,0,0.05);">
                <!-- Medic checkboxes will be loaded here -->
                <div class="modern-loading-state" style="text-align: center; padding: 2.5rem; color: #6b7280; background: linear-gradient(135deg, #f0fdf4, #ecfdf5); border-radius: 12px; border: 2px dashed #d1d5db;">
                  <div style="width: 50px; height: 50px; background: linear-gradient(135deg, #10b981, #059669); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem; animation: pulse 2s infinite;">
                    <i class="fas fa-spinner fa-spin" style="color: white; font-size: 1.2rem;"></i>
                  </div>
                  <p style="margin: 0; font-weight: 700; font-size: 1rem;">Loading medics...</p>
                </div>
              </div>
            </div>
            
            <!-- Ambulances Selection -->
            <div class="modern-selection-card" style="background: linear-gradient(135deg, #ffffff 0%, #fef2f2 100%); border: 2px solid #e5e7eb; border-radius: 20px; padding: 2rem; transition: all 0.3s ease; box-shadow: 0 8px 25px rgba(0,0,0,0.08); position: relative; overflow: hidden;">
              <div class="selection-header" style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.5rem; padding-bottom: 1.25rem; border-bottom: 2px solid #f1f5f9;">
                <div class="selection-title-area" style="display: flex; align-items: center;">
                  <div class="selection-icon" style="width: 55px; height: 55px; background: linear-gradient(135deg, #ef4444, #dc2626); border-radius: 15px; display: flex; align-items: center; justify-content: center; margin-right: 1rem; box-shadow: 0 6px 20px rgba(239, 68, 68, 0.4);">
                    <i class="fas fa-ambulance" style="color: white; font-size: 1.3rem;"></i>
                  </div>
                  <div>
                    <h5 style="margin: 0; color: #1f2937; font-weight: 800; font-size: 1.3rem;">Ambulances</h5>
                    <p style="margin: 0.25rem 0 0 0; color: #6b7280; font-size: 0.95rem; font-weight: 500;">Select available vehicles</p>
                  </div>
                </div>
                <button type="button" class="modern-select-btn" style="background: linear-gradient(135deg, #ef4444, #dc2626); color: white; border: none; padding: 0.75rem 1.25rem; border-radius: 12px; font-size: 0.9rem; font-weight: 700; cursor: pointer; transition: all 0.3s ease; box-shadow: 0 6px 20px rgba(239, 68, 68, 0.4); display: flex; align-items: center; gap: 0.5rem;">
                  <i class="fas fa-check-double"></i>
                  Select All
                </button>
              </div>
              <div class="selection-content" style="max-height: 250px; overflow-y: auto; border: 2px solid #e5e7eb; border-radius: 15px; padding: 1rem; background: #ffffff; box-shadow: inset 0 3px 6px rgba(0,0,0,0.05);">
                <!-- Ambulance checkboxes will be loaded here -->
                <div class="modern-loading-state" style="text-align: center; padding: 2.5rem; color: #6b7280; background: linear-gradient(135deg, #fef2f2, #fee2e2); border-radius: 12px; border: 2px dashed #d1d5db;">
                  <div style="width: 50px; height: 50px; background: linear-gradient(135deg, #ef4444, #dc2626); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem; animation: pulse 2s infinite;">
                    <i class="fas fa-spinner fa-spin" style="color: white; font-size: 1.2rem;"></i>
                  </div>
                  <p style="margin: 0; font-weight: 700; font-size: 1rem;">Loading ambulances...</p>
                </div>
              </div>
            </div>
          </div>
            
          <!-- Preview Section -->
          <div class="modern-preview-section" style="background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%); border-radius: 20px; padding: 2.5rem; margin-bottom: 2rem; border: 2px solid #bae6fd; box-shadow: 0 8px 25px rgba(0,0,0,0.08); position: relative; overflow: hidden;">
            <div class="preview-header" style="display: flex; align-items: center; margin-bottom: 2rem; padding-bottom: 1.5rem; border-bottom: 2px solid #e0f2fe;">
              <div class="preview-icon" style="width: 60px; height: 60px; background: linear-gradient(135deg, #0369a1, #0284c7); border-radius: 15px; display: flex; align-items: center; justify-content: center; margin-right: 1.25rem; box-shadow: 0 6px 20px rgba(3, 105, 161, 0.4);">
                <i class="fas fa-eye" style="color: white; font-size: 1.4rem;"></i>
              </div>
              <div>
                <h5 style="margin: 0; color: #0369a1; font-weight: 800; font-size: 1.4rem;">Pairing Preview</h5>
                <p style="margin: 0.25rem 0 0 0; color: #6b7280; font-size: 1rem; font-weight: 500;">Review your selections before creating</p>
              </div>
            </div>
            <div id="bulkPreview" class="preview-content-area" style="background: #ffffff; border-radius: 15px; padding: 2rem; border: 2px solid #e0f2fe; color: #6b7280; font-style: italic; font-weight: 500; text-align: center; min-height: 80px; display: flex; align-items: center; justify-content: center; box-shadow: inset 0 3px 6px rgba(0,0,0,0.05);">
              <div style="display: flex; align-items: center; gap: 0.75rem; font-size: 1.1rem;">
                <i class="fas fa-info-circle" style="color: #0369a1; font-size: 1.2rem;"></i>
                Select drivers, medics, and ambulances to see pairing preview
              </div>
            </div>
          </div>
          
          <!-- Action Buttons -->
          <div class="modern-action-buttons" style="display: flex; gap: 2rem; justify-content: center; flex-wrap: wrap; margin-top: 2rem;">
            <button type="button" class="modern-primary-btn" style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); color: white; border: none; padding: 1.25rem 3rem; border-radius: 15px; font-weight: 800; font-size: 1.1rem; cursor: pointer; transition: all 0.3s ease; display: flex; align-items: center; gap: 1rem; box-shadow: 0 8px 25px rgba(139, 92, 246, 0.4); position: relative; overflow: hidden;">
              <div class="btn-icon-container" style="width: 30px; height: 30px; background: rgba(255,255,255,0.2); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                <i class="fas fa-save" style="font-size: 1rem;"></i>
              </div>
              <span>Create Bulk Pairings</span>
            </button>
            <button type="button" onclick="closeBulkPanel()" class="modern-secondary-btn" style="background: linear-gradient(135deg, #6b7280, #4b5563); color: white; border: none; padding: 1.25rem 3rem; border-radius: 15px; font-weight: 800; font-size: 1.1rem; cursor: pointer; transition: all 0.3s ease; display: flex; align-items: center; gap: 1rem; box-shadow: 0 8px 25px rgba(107, 114, 128, 0.4); position: relative; overflow: hidden;">
              <div class="btn-icon-container" style="width: 30px; height: 30px; background: rgba(255,255,255,0.2); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                <i class="fas fa-times" style="font-size: 1rem;"></i>
              </div>
              <span>Cancel</span>
            </button>
          </div>
          </div>
        </div>
      </div>
      </div>
    </div>

    <!-- Driver-Medic Panel -->
    <div id="driverMedicPanel" class="pairing-panel" style="display: none; position: fixed; top: 0; right: -600px; width: 600px; height: 100vh; background: #ffffff; box-shadow: -5px 0 15px rgba(0, 0, 0, 0.1); z-index: 1000; transition: right 0.3s ease; overflow-y: auto;">
      <div class="panel-content enhanced-panel" style="background: #ffffff; border: 3px solid #10b981; border-radius: 0; box-shadow: none; width: 100%; height: 100%; position: relative; overflow: hidden; animation: panelSlideIn 0.4s ease-out;">
      <div class="panel-accent-bar" style="position: absolute; top: 0; left: 0; width: 100%; height: 8px; background: linear-gradient(90deg, #10b981, #059669, #047857);"></div>
      
        <div class="panel-header enhanced-header" style="background: linear-gradient(135deg, #10b981 0%, #059669 50%, #047857 100%); color: white; padding: 1.25rem 1rem; position: relative; border-bottom: 1px solid rgba(255,255,255,0.1); display:flex; align-items:center; justify-content: space-between;">
        <div class="header-content" style="display: flex; align-items: center; justify-content: flex-start; gap: 0.75rem;">
          <div class="header-icon" style="width: 60px; height: 60px; background: rgba(255,255,255,0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.8rem; backdrop-filter: blur(10px);">
            <i class="fas fa-user-md"></i>
          </div>
          <div class="header-text">
            <h3 style="margin: 0; font-size: 1.8rem; font-weight: 800; text-transform: uppercase; letter-spacing: 1px;">
              Driver-Medic Pairing
            </h3>
            <p style="margin: 0.5rem 0 0 0; font-size: 1rem; opacity: 0.9; font-weight: 500;">
              Pair drivers with medical personnel
            </p>
          </div>
        </div>
        <button type="button" onclick="closeDriverMedicPanel()" class="panel-close-btn" style="position: absolute; top: 50%; transform: translateY(-50%); right: 12px; background: rgba(239, 68, 68, 0.95); color: white; border: none; width: 36px; height: 36px; border-radius: 50%; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 0.95rem; transition: all 0.3s ease;">
          <i class="fas fa-times"></i>
        </button>
      </div>
      
      <div class="panel-body" style="padding: 2.5rem; background: linear-gradient(135deg, #f0fdf4 0%, #ecfdf5 100%); min-height: 400px;">
        <div id="driverMedicPanelContent" class="panel-content-wrapper enhanced-form-layout">
          <!-- Driver-Medic content will be loaded here -->
          <div class="form-container" style="background: #ffffff; border-radius: 15px; padding: 2rem; box-shadow: 0 8px 25px rgba(0,0,0,0.08); border: 1px solid #e5e7eb;">
            <div class="form-header" style="text-align: center; margin-bottom: 2rem; padding-bottom: 1rem; border-bottom: 2px solid #f1f5f9;">
              <h4 style="color: #10b981; font-size: 1.3rem; font-weight: 700; margin: 0; display: flex; align-items: center; justify-content: center; gap: 0.5rem;">
                <i class="fas fa-user-md"></i>
                Driver-Medic Pairing Form
              </h4>
              <p style="color: #6b7280; margin: 0.5rem 0 0 0; font-size: 0.95rem;">Create a new pairing between a driver and medical personnel</p>
            </div>
            
            <div class="form-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem; margin-bottom: 2rem;">
              <!-- Driver Selection -->
              <div class="form-section" style="background: #f0fdf4; border-radius: 12px; padding: 1.5rem; border: 1px solid #bbf7d0;">
                <label style="display: block; font-weight: 700; color: #374151; margin-bottom: 0.75rem; font-size: 0.95rem;">
                  <i class="fas fa-user-tie" style="color: #10b981; margin-right: 0.5rem;"></i>
                  Select Driver
                </label>
                <select class="form-select" style="width: 100%; padding: 0.75rem; border: 2px solid #d1d5db; border-radius: 8px; font-size: 0.95rem; transition: all 0.3s ease;">
                  <option value="">Choose a driver...</option>
                  <!-- Driver options will be loaded here -->
                </select>
              </div>
              
              <!-- Medic Selection -->
              <div class="form-section" style="background: #f0fdf4; border-radius: 12px; padding: 1.5rem; border: 1px solid #bbf7d0;">
                <label style="display: block; font-weight: 700; color: #374151; margin-bottom: 0.75rem; font-size: 0.95rem;">
                  <i class="fas fa-user-md" style="color: #10b981; margin-right: 0.5rem;"></i>
                  Select Medic
                </label>
                <select class="form-select" style="width: 100%; padding: 0.75rem; border: 2px solid #d1d5db; border-radius: 8px; font-size: 0.95rem; transition: all 0.3s ease;">
                  <option value="">Choose a medic...</option>
                  <!-- Medic options will be loaded here -->
                </select>
              </div>
            </div>
            
            <div class="form-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem; margin-bottom: 2rem;">
              <!-- Schedule Date -->
              <div class="form-section" style="background: #f0fdf4; border-radius: 12px; padding: 1.5rem; border: 1px solid #bbf7d0;">
                <label style="display: block; font-weight: 700; color: #374151; margin-bottom: 0.75rem; font-size: 0.95rem;">
                  <i class="fas fa-calendar-alt" style="color: #10b981; margin-right: 0.5rem;"></i>
                  Schedule Date
                </label>
                <input type="date" class="form-input" style="width: 100%; padding: 0.75rem; border: 2px solid #d1d5db; border-radius: 8px; font-size: 0.95rem; transition: all 0.3s ease;">
              </div>
              
              <!-- Schedule Time -->
              <div class="form-section" style="background: #f0fdf4; border-radius: 12px; padding: 1.5rem; border: 1px solid #bbf7d0;">
                <label style="display: block; font-weight: 700; color: #374151; margin-bottom: 0.75rem; font-size: 0.95rem;">
                  <i class="fas fa-clock" style="color: #10b981; margin-right: 0.5rem;"></i>
                  Schedule Time
                </label>
                <input type="time" class="form-input" style="width: 100%; padding: 0.75rem; border: 2px solid #d1d5db; border-radius: 8px; font-size: 0.95rem; transition: all 0.3s ease;">
              </div>
            </div>
            
            <!-- Additional Information -->
            <div class="form-section" style="background: #f0fdf4; border-radius: 12px; padding: 1.5rem; margin-bottom: 2rem; border: 1px solid #bbf7d0;">
              <label style="display: block; font-weight: 700; color: #374151; margin-bottom: 0.75rem; font-size: 0.95rem;">
                <i class="fas fa-comment-alt" style="color: #10b981; margin-right: 0.5rem;"></i>
                Additional Notes
              </label>
              <textarea class="form-textarea" rows="3" style="width: 100%; padding: 0.75rem; border: 2px solid #d1d5db; border-radius: 8px; font-size: 0.95rem; transition: all 0.3s ease; resize: vertical;" placeholder="Enter any additional information about this pairing..."></textarea>
            </div>
            
            <!-- Action Buttons -->
            <div class="form-actions" style="display: flex; gap: 1rem; justify-content: center; flex-wrap;">
              <button type="button" class="action-btn primary-btn" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; border: none; padding: 0.75rem 2rem; border-radius: 10px; font-weight: 700; cursor: pointer; transition: all 0.3s ease; display: flex; align-items: center; gap: 0.5rem;">
                <i class="fas fa-save"></i>
                Create Pairing
              </button>
              <button type="button" onclick="closeDriverMedicPanel()" class="action-btn secondary-btn" style="background: #6b7280; color: white; border: none; padding: 0.75rem 2rem; border-radius: 10px; font-weight: 700; cursor: pointer; transition: all 0.3s ease; display: flex; align-items: center; gap: 0.5rem;">
                <i class="fas fa-times"></i>
                Cancel
              </button>
            </div>
          </div>
        </div>
      </div>
      </div>
    </div>

    <!-- Driver-Ambulance Panel -->
    <div id="driverAmbulancePanel" class="pairing-panel" style="display: none; position: fixed; top: 0; right: -600px; width: 600px; height: 100vh; background: #ffffff; box-shadow: -5px 0 15px rgba(0, 0, 0, 0.1); z-index: 1000; transition: right 0.3s ease; overflow-y: auto;">
      <div class="panel-content enhanced-panel" style="background: #ffffff; border: 3px solid #3b82f6; border-radius: 0; box-shadow: none; width: 100%; height: 100%; position: relative; overflow: hidden; animation: panelSlideIn 0.4s ease-out;">
      <div class="panel-accent-bar" style="position: absolute; top: 0; left: 0; width: 100%; height: 8px; background: linear-gradient(90deg, #3b82f6, #1d4ed8, #1e40af);"></div>
      
        <div class="panel-header enhanced-header" style="background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 50%, #1e40af 100%); color: white; padding: 1.25rem 1rem; position: relative; border-bottom: 1px solid rgba(255,255,255,0.1); display:flex; align-items:center; justify-content: space-between;">
        <div class="header-content" style="display: flex; align-items: center; justify-content: flex-start; gap: 0.75rem;">
          <div class="header-icon" style="width: 60px; height: 60px; background: rgba(255,255,255,0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.8rem; backdrop-filter: blur(10px);">
            <i class="fas fa-ambulance"></i>
          </div>
          <div class="header-text">
            <h3 style="margin: 0; font-size: 1.8rem; font-weight: 800; text-transform: uppercase; letter-spacing: 1px;">
              Driver-Ambulance Pairing
            </h3>
            <p style="margin: 0.5rem 0 0 0; font-size: 1rem; opacity: 0.9; font-weight: 500;">
              Assign drivers to ambulance vehicles
            </p>
          </div>
        </div>
        <button type="button" onclick="closeDriverAmbulancePanel()" class="panel-close-btn" style="position: absolute; top: 50%; transform: translateY(-50%); right: 12px; background: rgba(239, 68, 68, 0.95); color: white; border: none; width: 36px; height: 36px; border-radius: 50%; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 0.95rem; transition: all 0.3s ease;">
          <i class="fas fa-times"></i>
        </button>
      </div>
      
      <div class="panel-body" style="padding: 2.5rem; background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%); min-height: 400px;">
        <div id="driverAmbulancePanelContent" class="panel-content-wrapper enhanced-form-layout">
          <!-- Driver-Ambulance content will be loaded here -->
          <div class="form-container" style="background: #ffffff; border-radius: 15px; padding: 2rem; box-shadow: 0 8px 25px rgba(0,0,0,0.08); border: 1px solid #e5e7eb;">
            <div class="form-header" style="text-align: center; margin-bottom: 2rem; padding-bottom: 1rem; border-bottom: 2px solid #f1f5f9;">
              <h4 style="color: #3b82f6; font-size: 1.3rem; font-weight: 700; margin: 0; display: flex; align-items: center; justify-content: center; gap: 0.5rem;">
                <i class="fas fa-ambulance"></i>
                Driver-Ambulance Pairing Form
              </h4>
              <p style="color: #6b7280; margin: 0.5rem 0 0 0; font-size: 0.95rem;">Assign a driver to an ambulance vehicle for emergency response</p>
            </div>
            
            <div class="form-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem; margin-bottom: 2rem;">
              <!-- Driver Selection -->
              <div class="form-section" style="background: #eff6ff; border-radius: 12px; padding: 1.5rem; border: 1px solid #bfdbfe;">
                <label style="display: block; font-weight: 700; color: #374151; margin-bottom: 0.75rem; font-size: 0.95rem;">
                  <i class="fas fa-user-tie" style="color: #3b82f6; margin-right: 0.5rem;"></i>
                  Select Driver
                </label>
                <select class="form-select" style="width: 100%; padding: 0.75rem; border: 2px solid #d1d5db; border-radius: 8px; font-size: 0.95rem; transition: all 0.3s ease;">
                  <option value="">Choose a driver...</option>
                  <!-- Driver options will be loaded here -->
                </select>
              </div>
              
              <!-- Ambulance Selection -->
              <div class="form-section" style="background: #eff6ff; border-radius: 12px; padding: 1.5rem; border: 1px solid #bfdbfe;">
                <label style="display: block; font-weight: 700; color: #374151; margin-bottom: 0.75rem; font-size: 0.95rem;">
                  <i class="fas fa-ambulance" style="color: #3b82f6; margin-right: 0.5rem;"></i>
                  Select Ambulance
                </label>
                <select class="form-select" style="width: 100%; padding: 0.75rem; border: 2px solid #d1d5db; border-radius: 8px; font-size: 0.95rem; transition: all 0.3s ease;">
                  <option value="">Choose an ambulance...</option>
                  <!-- Ambulance options will be loaded here -->
                </select>
              </div>
            </div>
            
            <div class="form-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem; margin-bottom: 2rem;">
              <!-- Assignment Date -->
              <div class="form-section" style="background: #eff6ff; border-radius: 12px; padding: 1.5rem; border: 1px solid #bfdbfe;">
                <label style="display: block; font-weight: 700; color: #374151; margin-bottom: 0.75rem; font-size: 0.95rem;">
                  <i class="fas fa-calendar-alt" style="color: #3b82f6; margin-right: 0.5rem;"></i>
                  Assignment Date
                </label>
                <input type="date" class="form-input" style="width: 100%; padding: 0.75rem; border: 2px solid #d1d5db; border-radius: 8px; font-size: 0.95rem; transition: all 0.3s ease;">
              </div>
              
              <!-- Shift Time -->
              <div class="form-section" style="background: #eff6ff; border-radius: 12px; padding: 1.5rem; border: 1px solid #bfdbfe;">
                <label style="display: block; font-weight: 700; color: #374151; margin-bottom: 0.75rem; font-size: 0.95rem;">
                  <i class="fas fa-clock" style="color: #3b82f6; margin-right: 0.5rem;"></i>
                  Shift Time
                </label>
                <select class="form-select" style="width: 100%; padding: 0.75rem; border: 2px solid #d1d5db; border-radius: 8px; font-size: 0.95rem; transition: all 0.3s ease;">
                  <option value="">Select shift...</option>
                  <option value="morning">Morning Shift (6:00 AM - 2:00 PM)</option>
                  <option value="afternoon">Afternoon Shift (2:00 PM - 10:00 PM)</option>
                  <option value="night">Night Shift (10:00 PM - 6:00 AM)</option>
                </select>
              </div>
            </div>
            
            <!-- Vehicle Information -->
            <div class="form-section" style="background: #eff6ff; border-radius: 12px; padding: 1.5rem; margin-bottom: 2rem; border: 1px solid #bfdbfe;">
              <h5 style="margin: 0 0 1rem 0; color: #1e40af; font-weight: 700; display: flex; align-items: center; gap: 0.5rem;">
                <i class="fas fa-info-circle"></i>
                Vehicle Information
              </h5>
              <div class="info-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                <div class="info-item" style="background: #ffffff; padding: 1rem; border-radius: 8px; border: 1px solid #dbeafe;">
                  <div style="font-size: 0.8rem; color: #6b7280; font-weight: 600; margin-bottom: 0.25rem;">VEHICLE ID</div>
                  <div style="font-weight: 700; color: #1e40af;">AMB-001</div>
                </div>
                <div class="info-item" style="background: #ffffff; padding: 1rem; border-radius: 8px; border: 1px solid #dbeafe;">
                  <div style="font-size: 0.8rem; color: #6b7280; font-weight: 600; margin-bottom: 0.25rem;">STATUS</div>
                  <div style="font-weight: 700; color: #10b981;">Available</div>
                </div>
                <div class="info-item" style="background: #ffffff; padding: 1rem; border-radius: 8px; border: 1px solid #dbeafe;">
                  <div style="font-size: 0.8rem; color: #6b7280; font-weight: 600; margin-bottom: 0.25rem;">CAPACITY</div>
                  <div style="font-weight: 700; color: #1e40af;">4 Patients</div>
                </div>
              </div>
            </div>
            
            <!-- Additional Information -->
            <div class="form-section" style="background: #eff6ff; border-radius: 12px; padding: 1.5rem; margin-bottom: 2rem; border: 1px solid #bfdbfe;">
              <label style="display: block; font-weight: 700; color: #374151; margin-bottom: 0.75rem; font-size: 0.95rem;">
                <i class="fas fa-comment-alt" style="color: #3b82f6; margin-right: 0.5rem;"></i>
                Assignment Notes
              </label>
              <textarea class="form-textarea" rows="3" style="width: 100%; padding: 0.75rem; border: 2px solid #d1d5db; border-radius: 8px; font-size: 0.95rem; transition: all 0.3s ease; resize: vertical;" placeholder="Enter any special instructions or notes for this assignment..."></textarea>
            </div>
            
            <!-- Action Buttons -->
            <div class="form-actions" style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
              <button type="button" class="action-btn primary-btn" style="background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); color: white; border: none; padding: 0.75rem 2rem; border-radius: 10px; font-weight: 700; cursor: pointer; transition: all 0.3s ease; display: flex; align-items: center; gap: 0.5rem;">
                <i class="fas fa-save"></i>
                Create Assignment
              </button>
              <button type="button" onclick="closeDriverAmbulancePanel()" class="action-btn secondary-btn" style="background: #6b7280; color: white; border: none; padding: 0.75rem 2rem; border-radius: 10px; font-weight: 700; cursor: pointer; transition: all 0.3s ease; display: flex; align-items: center; gap: 0.5rem;">
                <i class="fas fa-times"></i>
                Cancel
              </button>
            </div>
          </div>
        </div>
      </div>
      </div>
    </div>

    <!-- Pairing Side Panel -->
    <div id="pairingSidePanel" class="pairing-side-panel" style="display: none; position: fixed; top: 50%; right: -500px; width: 500px; height: 60vh; background: #ffffff; box-shadow: -5px 0 15px rgba(0, 0, 0, 0.1); z-index: 1000; transition: right 0.3s ease; overflow-y: auto; transform: translateY(-50%); border-radius: 15px 0 0 15px;">
      <div class="panel-content enhanced-panel" style="background: #ffffff; border: 3px solid #f59e0b; border-radius: 15px 0 0 15px; box-shadow: none; width: 100%; height: 100%; position: relative; overflow: hidden; animation: panelSlideIn 0.4s ease-out;">
        <div class="panel-accent-bar" style="position: absolute; top: 0; left: 0; width: 100%; height: 8px; background: linear-gradient(90deg, #f59e0b, #d97706, #fbbf24);"></div>
        
        <div class="panel-header enhanced-header" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 50%, #fbbf24 100%); color: white; padding: 1.5rem; text-align: center; position: relative; border-bottom: 1px solid rgba(255,255,255,0.1);">
          <div class="header-content" style="display: flex; align-items: center; justify-content: center; gap: 1rem;">
            <div class="header-icon" style="width: 50px; height: 50px; background: rgba(255,255,255,0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; backdrop-filter: blur(10px);">
              <i class="fas fa-link"></i>
            </div>
            <div class="header-text">
              <h3 style="margin: 0; font-size: 1.5rem; font-weight: 800; text-transform: uppercase; letter-spacing: 1px;">
                Quick Pairing
              </h3>
              <p style="margin: 0.5rem 0 0 0; font-size: 0.9rem; opacity: 0.9; font-weight: 500;">
                Create pairings efficiently
              </p>
            </div>
          </div>
          <button type="button" onclick="closePairingPanel()" class="panel-close-btn" style="position: absolute; top: 15px; right: 15px; background: rgba(239, 68, 68, 0.9); color: white; border: none; width: 35px; height: 35px; border-radius: 50%; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 0.9rem; transition: all 0.3s ease; backdrop-filter: blur(10px);">
            <i class="fas fa-times"></i>
          </button>
        </div>
        
        <div class="panel-body" style="padding: 1rem; background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%); min-height: calc(60vh - 120px); overflow-y: auto;">
          <div id="pairingPanelContent" class="panel-content-wrapper">
            
            <!-- Tab Navigation -->
            <div class="tab-navigation" style="display: flex; gap: 0.25rem; margin-bottom: 1rem; background: #ffffff; border-radius: 10px; padding: 0.25rem; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
              <button class="tab-btn active" onclick="switchTab('bulk')" style="flex: 1; padding: 0.5rem 0.75rem; border: none; background: linear-gradient(135deg, #8b5cf6, #7c3aed); color: white; border-radius: 8px; font-size: 0.8rem; font-weight: 700; cursor: pointer; transition: all 0.3s ease;">
                <i class="fas fa-layer-group" style="margin-right: 0.25rem;"></i>Bulk
              </button>
              <button class="tab-btn" onclick="switchTab('driver-medic')" style="flex: 1; padding: 0.5rem 0.75rem; border: none; background: transparent; color: #6b7280; border-radius: 8px; font-size: 0.8rem; font-weight: 700; cursor: pointer; transition: all 0.3s ease;">
                <i class="fas fa-user-md" style="margin-right: 0.25rem;"></i>Driver-Medic
              </button>
              <button class="tab-btn" onclick="switchTab('driver-ambulance')" style="flex: 1; padding: 0.5rem 0.75rem; border: none; background: transparent; color: #6b7280; border-radius: 8px; font-size: 0.8rem; font-weight: 700; cursor: pointer; transition: all 0.3s ease;">
                <i class="fas fa-ambulance" style="margin-right: 0.25rem;"></i>Driver-Amb
              </button>
            </div>

            <!-- Tab Content -->
            <div class="tab-content">
              
              <!-- Bulk Pairing Tab -->
              <div id="bulk-tab" class="tab-panel active" style="display: block;">
                <form action="{{ route('admin.pairing.bulk.store') }}" method="POST" class="compact-form" style="background: #ffffff; border-radius: 12px; padding: 1rem; border: 1px solid #e5e7eb; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                  @csrf
                  
                  <div style="display: grid; gap: 0.75rem;">
                    <!-- Pairing Type -->
                    <div>
                      <label style="display: block; font-size: 0.8rem; font-weight: 700; color: #1f2937; margin-bottom: 0.25rem;">Pairing Type *</label>
                      <select name="pairing_type" id="pairing_type" required onchange="togglePairingType()" style="width: 100%; padding: 0.5rem; border-radius: 8px; border: 1.5px solid #e5e7eb; font-size: 0.85rem; background: #fff;">
                        <option value="">Select Type</option>
                        <option value="driver_medic">Driver-Medic</option>
                        <option value="driver_ambulance">Driver-Ambulance</option>
                      </select>
                    </div>
                    
                    <!-- Date -->
                    <div>
                      <label style="display: block; font-size: 0.8rem; font-weight: 700; color: #1f2937; margin-bottom: 0.25rem;">Date *</label>
                      <input type="date" name="pairing_date" id="pairing_date" value="{{ old('pairing_date', date('Y-m-d')) }}" required style="width: 100%; padding: 0.5rem; border-radius: 8px; border: 1.5px solid #e5e7eb; font-size: 0.85rem;">
                    </div>
                    
                    <!-- Time Fields (for driver-medic) -->
                    <div id="time_fields" style="display: none;">
                      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.5rem;">
                        <div>
                          <label style="display: block; font-size: 0.75rem; font-weight: 600; color: #6b7280; margin-bottom: 0.25rem;">Start Time</label>
                          <input type="time" name="start_time" value="{{ old('start_time') }}" required style="width: 100%; padding: 0.4rem; border-radius: 6px; border: 1px solid #d1d5db; font-size: 0.8rem;">
                        </div>
                        <div>
                          <label style="display: block; font-size: 0.75rem; font-weight: 600; color: #6b7280; margin-bottom: 0.25rem;">End Time</label>
                          <input type="time" name="end_time" value="{{ old('end_time') }}" required style="width: 100%; padding: 0.4rem; border-radius: 6px; border: 1px solid #d1d5db; font-size: 0.8rem;">
                        </div>
                      </div>
                    </div>
                    
                    <!-- Selection Areas -->
                    <div style="display: grid; gap: 0.75rem;">
                      <!-- Drivers -->
                      <div>
                        <label style="display: block; font-size: 0.8rem; font-weight: 700; color: #1f2937; margin-bottom: 0.25rem;">Drivers *</label>
                        <div class="chip-list" style="background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 8px; padding: 0.5rem; max-height: 100px; overflow-y: auto;">
                          @foreach($drivers as $driver)
                            @php 
                              $driverPairedAmbu = isset($pairedDriversAmbulance) && in_array($driver->id, $pairedDriversAmbulance);
                              $driverPairedMedic = isset($driversPairedWithMedics) && in_array($driver->id, $driversPairedWithMedics);
                              $driverBlocked = $driverPairedAmbu || $driverPairedMedic;
                            @endphp
                            <label style="display: flex; align-items: center; gap: 0.5rem; padding: 0.25rem; background: #fff; border-radius: 6px; margin-bottom: 0.25rem; font-size: 0.75rem; cursor: pointer; {{ $driverBlocked ? 'opacity:0.6;' : '' }}">
                              <input type="checkbox" name="drivers[]" value="{{ $driver->id }}" class="driver-checkbox" style="width: 12px; height: 12px;" {{ $driverBlocked ? 'disabled' : '' }}>
                              <span>{{ $driver->name }} {{ $driverBlocked ? '- Already paired' : '' }}</span>
                            </label>
                          @endforeach
                        </div>
                        <button type="button" onclick="selectAllDrivers()" style="font-size: 0.7rem; color: #3b82f6; background: none; border: none; cursor: pointer; margin-top: 0.25rem;">Select All (Max 2)</button>
                      </div>
                      
                      <!-- Medics -->
                      <div id="medic_selection" style="display: none;">
                        <label style="display: block; font-size: 0.8rem; font-weight: 700; color: #1f2937; margin-bottom: 0.25rem;">Medics *</label>
                        <div class="chip-list" style="background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 8px; padding: 0.5rem; max-height: 100px; overflow-y: auto;">
                          @foreach($medics as $medic)
                            <label style="display: flex; align-items: center; gap: 0.5rem; padding: 0.25rem; background: #fff; border-radius: 6px; margin-bottom: 0.25rem; font-size: 0.75rem; cursor: pointer;">
                              <input type="checkbox" name="medics[]" value="{{ $medic->id }}" class="medic-checkbox" style="width: 12px; height: 12px;">
                              <span>{{ $medic->name }} ({{ $medic->specialization ?? 'N/A' }})</span>
                            </label>
                          @endforeach
                        </div>
                        <button type="button" onclick="selectAllMedics()" style="font-size: 0.7rem; color: #3b82f6; background: none; border: none; cursor: pointer; margin-top: 0.25rem;">Select All Medics</button>
                      </div>
                      
                      <!-- Ambulances -->
                      <div id="ambulance_selection" style="display: none;">
                        <label style="display: block; font-size: 0.8rem; font-weight: 700; color: #1f2937; margin-bottom: 0.25rem;">Ambulances *</label>
                        <div class="chip-list" style="background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 8px; padding: 0.5rem; max-height: 100px; overflow-y: auto;">
                          @foreach($ambulances as $ambulance)
                            @php $isPaired = isset($pairedAmbulances) && in_array($ambulance->id, $pairedAmbulances); @endphp
                            <label style="display: flex; align-items: center; gap: 0.5rem; padding: 0.25rem; background: #fff; border-radius: 6px; margin-bottom: 0.25rem; font-size: 0.75rem; {{ $isPaired ? 'opacity:0.6;' : '' }}">
                              <input type="checkbox" name="ambulances[]" value="{{ $ambulance->id }}" class="ambulance-checkbox" style="width: 12px; height: 12px;" {{ $isPaired ? 'disabled' : '' }} onchange="enforceSingleAmbulance(this)">
                              <span>{{ $ambulance->name }} {{ $isPaired ? '- Already paired' : '' }}</span>
                            </label>
                          @endforeach
                        </div>
                        <div style="font-size: 0.72rem; color:#6b7280; margin-top:0.25rem;">Only one ambulance can be selected.</div>
                      </div>
                    </div>
                    
                    <!-- Notes -->
                    <div>
                      <label style="display: block; font-size: 0.8rem; font-weight: 700; color: #1f2937; margin-bottom: 0.25rem;">Notes</label>
                      <textarea name="notes" rows="2" style="width: 100%; padding: 0.5rem; border-radius: 8px; border: 1.5px solid #e5e7eb; font-size: 0.85rem; resize: vertical;" placeholder="Additional notes..."></textarea>
                    </div>
                    
                    <!-- Actions -->
                    <div style="display: flex; gap: 0.5rem; margin-top: 0.5rem;">
                      <button type="submit" style="flex: 1; background: linear-gradient(135deg, #8b5cf6, #7c3aed); color: white; border: none; border-radius: 8px; padding: 0.6rem; font-weight: 700; font-size: 0.8rem; cursor: pointer;">
                        <i class="fas fa-check" style="margin-right: 0.25rem;"></i>Create
                      </button>
                      <button type="button" onclick="closePairingPanel()" style="background: #6b7280; color: white; border: none; border-radius: 8px; padding: 0.6rem; font-weight: 700; font-size: 0.8rem; cursor: pointer;">Cancel</button>
                    </div>
                  </div>
                </form>
              </div>
              
              <!-- Driver-Medic Tab -->
              <div id="driver-medic-tab" class="tab-panel" style="display: none;">
                <form action="{{ route('admin.pairing.driver-medic.store') }}" method="POST" class="compact-form" style="background: #ffffff; border-radius: 12px; padding: 1rem; border: 1px solid #e5e7eb; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                  @csrf
                  
                  <div style="display: grid; gap: 0.75rem;">
                    <!-- Driver Selection -->
                    <div>
                      <label style="display: block; font-size: 0.8rem; font-weight: 700; color: #1f2937; margin-bottom: 0.25rem;">Driver *</label>
                      <select name="driver_id" required style="width: 100%; padding: 0.5rem; border-radius: 8px; border: 1.5px solid #e5e7eb; font-size: 0.85rem;">
                        <option value="">Select Driver</option>
                        @foreach($drivers as $driver)
                          @php $driverPaired = isset($pairedDriversAmbulance) && in_array($driver->id, $pairedDriversAmbulance); @endphp
                          @php $label = $driver->name . ($driverPaired ? ' - Already paired' : ''); @endphp
                          <option value="{{ $driver->id }}" {{ $driverPaired ? 'disabled' : '' }} data-base="{{ $driver->name }}">{{ $label }}</option>
                        @endforeach
                      </select>
                    </div>
                    
                    <!-- Medic Selection -->
                    <div>
                      <label style="display: block; font-size: 0.8rem; font-weight: 700; color: #1f2937; margin-bottom: 0.25rem;">Medic *</label>
                      <select name="medic_id" required style="width: 100%; padding: 0.5rem; border-radius: 8px; border: 1.5px solid #e5e7eb; font-size: 0.85rem;">
                        <option value="">Select Medic</option>
                        @foreach($medics as $medic)
                          <option value="{{ $medic->id }}">{{ $medic->name }} ({{ $medic->specialization ?? 'No specialization' }})</option>
                        @endforeach
                      </select>
                    </div>
                    
                    <!-- Date -->
                    <div>
                      <label style="display: block; font-size: 0.8rem; font-weight: 700; color: #1f2937; margin-bottom: 0.25rem;">Date *</label>
                      <input type="date" name="pairing_date" value="{{ old('pairing_date', date('Y-m-d')) }}" required style="width: 100%; padding: 0.5rem; border-radius: 8px; border: 1.5px solid #e5e7eb; font-size: 0.85rem;">
                    </div>
                    
                    <!-- Time -->
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.5rem;">
                      <div>
                        <label style="display: block; font-size: 0.75rem; font-weight: 600; color: #6b7280; margin-bottom: 0.25rem;">Start Time</label>
                        <input type="time" name="start_time" required style="width: 100%; padding: 0.4rem; border-radius: 6px; border: 1px solid #d1d5db; font-size: 0.8rem;">
                      </div>
                      <div>
                        <label style="display: block; font-size: 0.75rem; font-weight: 600; color: #6b7280; margin-bottom: 0.25rem;">End Time</label>
                        <input type="time" name="end_time" required style="width: 100%; padding: 0.4rem; border-radius: 6px; border: 1px solid #d1d5db; font-size: 0.8rem;">
                      </div>
                    </div>
                    
                    <!-- Notes -->
                    <div>
                      <label style="display: block; font-size: 0.8rem; font-weight: 700; color: #1f2937; margin-bottom: 0.25rem;">Notes</label>
                      <textarea name="notes" rows="2" style="width: 100%; padding: 0.5rem; border-radius: 8px; border: 1.5px solid #e5e7eb; font-size: 0.85rem; resize: vertical;" placeholder="Additional notes..."></textarea>
                    </div>
                    
                    <!-- Actions -->
                    <div style="display: flex; gap: 0.5rem; margin-top: 0.5rem;">
                      <button type="submit" style="flex: 1; background: linear-gradient(135deg, #10b981, #059669); color: white; border: none; border-radius: 8px; padding: 0.6rem; font-weight: 700; font-size: 0.8rem; cursor: pointer;">
                        <i class="fas fa-check" style="margin-right: 0.25rem;"></i>Create
                      </button>
                      <button type="button" onclick="closePairingPanel()" style="background: #6b7280; color: white; border: none; border-radius: 8px; padding: 0.6rem; font-weight: 700; font-size: 0.8rem; cursor: pointer;">Cancel</button>
                    </div>
                  </div>
                </form>
              </div>
              
              <!-- Driver-Ambulance Tab -->
              <div id="driver-ambulance-tab" class="tab-panel" style="display: none;">
                <form action="{{ route('admin.pairing.driver-ambulance.store') }}" method="POST" class="compact-form" style="background: #ffffff; border-radius: 12px; padding: 1rem; border: 1px solid #e5e7eb; box-shadow: 0 2px 8px rgba(0,0,0,0.05);" onsubmit="handleInlineSubmit(event)">
                  @csrf
                  
                  <div style="display: grid; gap: 0.75rem;">
                    <!-- Driver Selection -->
                    <div>
                      <label style="display: block; font-size: 0.8rem; font-weight: 700; color: #1f2937; margin-bottom: 0.25rem;">Driver *</label>
                      <select name="driver_id" required style="width: 100%; padding: 0.5rem; border-radius: 8px; border: 1.5px solid #e5e7eb; font-size: 0.85rem;">
                        <option value="">Select Driver</option>
                        @foreach($drivers as $driver)
                          @php 
                            $driverPairedAmbu = isset($pairedDriversAmbulance) && in_array($driver->id, $pairedDriversAmbulance);
                            $driverPairedMedic = isset($driversPairedWithMedics) && in_array($driver->id, $driversPairedWithMedics);
                            $driverBlocked = $driverPairedAmbu || $driverPairedMedic;
                            $dlabel = $driver->name . ($driverBlocked ? ' - Already paired' : '');
                          @endphp
                          <option value="{{ $driver->id }}" {{ $driverBlocked ? 'disabled' : '' }} data-base="{{ $driver->name }}">{{ $dlabel }}</option>
                        @endforeach
                      </select>
                    </div>
                    
                    <!-- Ambulance Selection -->
                    <div>
                      <label style="display: block; font-size: 0.8rem; font-weight: 700; color: #1f2937; margin-bottom: 0.25rem;">Ambulance *</label>
                      <select name="ambulance_id" required style="width: 100%; padding: 0.5rem; border-radius: 8px; border: 1.5px solid #e5e7eb; font-size: 0.85rem;">
                        <option value="">Select Ambulance</option>
                        @foreach($ambulances as $ambulance)
                          @php $isPaired = isset($pairedAmbulances) && in_array($ambulance->id, $pairedAmbulances); @endphp
                          @php $alabel = $ambulance->name . ($isPaired ? ' - Already paired' : ''); @endphp
                          <option value="{{ $ambulance->id }}" {{ $isPaired ? 'disabled' : '' }} data-base="{{ $ambulance->name }}">{{ $alabel }}</option>
                        @endforeach
                      </select>
                    </div>
                    
                    <!-- Date -->
                    <div>
                      <label style="display: block; font-size: 0.8rem; font-weight: 700; color: #1f2937; margin-bottom: 0.25rem;">Date *</label>
                      <input type="date" name="pairing_date" value="{{ old('pairing_date', $selectedDate ?? date('Y-m-d')) }}" required style="width: 100%; padding: 0.5rem; border-radius: 8px; border: 1.5px solid #e5e7eb; font-size: 0.85rem;">
                    </div>
                    
                    <!-- Notes -->
                    <div>
                      <label style="display: block; font-size: 0.8rem; font-weight: 700; color: #1f2937; margin-bottom: 0.25rem;">Notes</label>
                      <textarea name="notes" rows="2" style="width: 100%; padding: 0.5rem; border-radius: 8px; border: 1.5px solid #e5e7eb; font-size: 0.85rem; resize: vertical;" placeholder="Additional notes..."></textarea>
                    </div>
                    
                    <!-- Actions -->
                    <div style="display: flex; gap: 0.5rem; margin-top: 0.5rem;">
                      <button type="submit" style="flex: 1; background: linear-gradient(135deg, #3b82f6, #1d4ed8); color: white; border: none; border-radius: 8px; padding: 0.6rem; font-weight: 700; font-size: 0.8rem; cursor: pointer;">
                        <i class="fas fa-check" style="margin-right: 0.25rem;"></i>Create
                      </button>
                      <button type="button" onclick="closePairingPanel()" style="background: #6b7280; color: white; border: none; border-radius: 8px; padding: 0.6rem; font-weight: 700; font-size: 0.8rem; cursor: pointer;">Cancel</button>
                    </div>
                  </div>
                </form>
              </div>
              
          </div>
        </div>
      </div>
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
          <p id="errorMessage">{{ session('error') }}</p>
        </div>
        <div class="error-modal-footer">
          <button onclick="closeErrorModal()" class="error-modal-btn">OK</button>
        </div>
      </div>
    </div>

    <!-- Inline Notification Modal -->
    <div id="inlineModal" class="inline-modal" role="dialog" aria-modal="true" aria-labelledby="inlineModalTitle">
      <div class="inline-modal-content">
        <div id="inlineModalHeader" class="inline-modal-header" style="background:#2563eb;">
          <i id="inlineModalIcon" class="fas fa-info-circle"></i>
          <h3 id="inlineModalTitle" style="margin:0; font-size:1.05rem;">Notice</h3>
        </div>
        <div class="inline-modal-body">
          <p id="inlineModalMessage" style="margin:0;">This is an inline message.</p>
        </div>
        <div class="inline-modal-footer" id="inlineModalActions">
          <button type="button" class="inline-modal-btn primary" onclick="closeInlineModal()">OK</button>
        </div>
      </div>
    </div>

    <!-- Driver-Medic Pairings -->
    <div id="driverMedicSection" class="driver-medic-section" style="display:none;">
      <div class="section-header-main" style="display:flex; align-items:center; justify-content:space-between; gap:0.5rem; padding:0.65rem 0.9rem; border-radius:10px;">
        <div style="font-weight:800; font-size:1rem; color:#ffffff; letter-spacing:0.3px;">Driver‑Medic Pairings</div>
        <div class="bulk-nav">
          <span class="nav-item" onclick="bulkActionDriverMedic('complete')"><i class="fas fa-check"></i>Complete</span>
          <span class="nav-sep"></span>
          <span class="nav-item" onclick="bulkActionDriverMedic('cancel')"><i class="fas fa-times"></i>Cancel</span>
        </div>
      </div>
        
      <div class="pairing-table-container">
        <div class="overflow-x-auto">
          <table class="pairing-table" data-paginate="true" data-page-size="10">
            <thead>
              <tr>
                <th>
                  <input type="checkbox" id="selectAllDriverMedic" onchange="toggleAllDriverMedic()" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                </th>
                <th>
                  <i class="fas fa-user mr-2"></i>Operator
                </th>
                <th>
                  <i class="fas fa-user-md mr-2"></i>Assigned Medics
                </th>
                <th>
                  <i class="fas fa-calendar mr-2"></i>Date
                </th>
                <th>
                  <i class="fas fa-info-circle mr-2"></i>Status
                </th>
                <th>
                  <i class="fas fa-cogs mr-2"></i>Actions
                </th>
              </tr>
            </thead>
            <tbody>
              @forelse($groupedDriverMedicPairings as $groupKey => $pairings)
                @php
                  $firstPairing = $pairings->first();
                  $driver = $firstPairing->driver;
                  $medics = $pairings->pluck('medic');
                  $allStatuses = $pairings->pluck('status')->unique();
                  $isActive = $allStatuses->contains('active');
                @endphp
                <tr>
                  <td>
                    <input type="checkbox" name="driver_medic_group_ids[]" value="{{ $groupKey }}" class="driver-medic-group-checkbox rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                  </td>
                  <td>
                    <div class="team-leader-cell" style="flex-direction:column; align-items:flex-start; gap:8px;">
                      <div class="operator-row" style="display:flex; align-items:center; gap:10px;">
                        <div class="team-leader-avatar">{{ $driver ? strtolower(substr($driver->name, 0, 2)) : '??' }}</div>
                        <div class="team-leader-info" style="display:flex; flex-direction:column; gap:2px;">
                          <h4 style="margin:0; font-size:0.95rem; font-weight:700; color:#1f2937;">{{ $driver ? $driver->name : 'Deleted Driver' }}</h4>
                          <p style="margin:0; color:#6b7280; font-size:0.8rem; display:flex; align-items:center; gap:6px;">
                            <i class="fas fa-car"></i>
                            Vehicle Operator
                          </p>
                        </div>
                      </div>
                    </div>
                  </td>
                  <td>
                    <div class="team-members-cell">
                      <div class="team-members-tags">
                        @foreach($medics as $medic)
                          <div class="team-member-tag" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                            <i class="fas fa-user-md"></i>
                            <span>{{ $medic ? $medic->name : 'Deleted Medic' }}</span>
                            @if($medic && $medic->specialization)
                              <span>({{ $medic->specialization }})</span>
                            @endif
                          </div>
                        @endforeach
                      </div>
                    </div>
                  </td>
                  <td class="date-cell">
                    <div class="date-primary">{{ $firstPairing->pairing_date->format('M d, Y') }}</div>
                    <div class="date-secondary">{{ $firstPairing->pairing_date->format('l') }}</div>
                  </td>
                  <td class="status-cell">
                    @if($allStatuses->count() == 1)
                      <span class="status-badge {{ $allStatuses->first() }}">
                        {{ ucfirst($allStatuses->first()) }}
                      </span>
                    @else
                      <div class="flex flex-wrap gap-1 justify-center">
                        @foreach($allStatuses as $status)
                          <span class="status-badge {{ $status }}">
                            {{ ucfirst($status) }}
                          </span>
                        @endforeach
                      </div>
                    @endif
                  </td>
                  <td class="actions-cell">
                      @if($isActive)
                      <div class="action-buttons">
                        <button onclick="editGroupPairings('{{ $groupKey }}')" class="action-btn edit" title="Edit Team">
                          <i class="fas fa-edit"></i>
                        </button>
                        <button onclick="bulkActionGroup('driver_medic', '{{ $groupKey }}', 'complete')" class="action-btn complete" title="Complete Team">
                          <i class="fas fa-check"></i>
                        </button>
                        <button onclick="bulkActionGroup('driver_medic', '{{ $groupKey }}', 'cancel')" class="action-btn cancel" title="Cancel Team">
                          <i class="fas fa-times"></i>
                        </button>
                      </div>
                      @else
                        <span class="text-sm text-gray-400 italic">No actions</span>
                      @endif
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="6" class="empty-state">No driver-medic pairings found.</td>
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

    <!-- Driver-Ambulance Pairings -->
    <div id="driverAmbulanceSection" class="driver-ambulance-section" style="display:block;">
      <div class="section-header-main" style="display:flex; align-items:center; justify-content:space-between; gap:0.5rem; padding:0.65rem 0.9rem; border-radius:10px;">
        <div style="font-weight:800; font-size:1rem; color:#ffffff; letter-spacing:0.3px;">Driver‑Ambulance Pairings</div>
        <div class="bulk-nav">
          <span class="nav-item" onclick="bulkActionDriverAmbulance('complete')"><i class="fas fa-check"></i>Complete</span>
          <span class="nav-sep"></span>
          <span class="nav-item" onclick="bulkActionDriverAmbulance('cancel')"><i class="fas fa-times"></i>Cancel</span>
      </div>
        </div>
      <div class="pairing-table-container">
        <div class="overflow-x-auto">
          <table class="pairing-table" data-paginate="true" data-page-size="10">
            <thead>
              <tr>
                <th>
                  <input type="checkbox" id="selectAllDriverAmbulance" onchange="toggleAllDriverAmbulance()" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                </th>
                <th>
                  <i class="fas fa-user mr-2"></i>Operator
                </th>
                <th>
                  <i class="fas fa-ambulance mr-2"></i>Assigned Vehicles
                </th>
                <th>
                  <i class="fas fa-calendar mr-2"></i>Date
                </th>
                <th>
                  <i class="fas fa-info-circle mr-2"></i>Status
                </th>
                <th>
                  <i class="fas fa-cogs mr-2"></i>Actions
                </th>
              </tr>
            </thead>
            <tbody>
              @forelse($groupedDriverAmbulancePairings as $groupKey => $pairings)
                @php
                  $firstPairing = $pairings->first();
                  $groupDrivers = isset($groupOperators[$groupKey]) ? $groupOperators[$groupKey] : $pairings->pluck('driver');
                  $ambulance = $firstPairing->ambulance;
                  $allStatuses = $pairings->pluck('status')->unique();
                  $isActive = $allStatuses->contains('active');
                @endphp
                <tr>
                  <td>
                    <input type="checkbox" name="driver_ambulance_group_ids[]" value="{{ $groupKey }}" class="driver-ambulance-group-checkbox rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                  </td>
                  <td>
                    <div class="team-leader-cell" style="flex-direction:column; align-items:flex-start; gap:8px;">
                      @foreach($groupDrivers as $op)
                        <div class="operator-row" style="display:flex; align-items:center; gap:10px;">
                          <div class="team-leader-avatar">{{ $op ? strtolower(substr($op->name, 0, 2)) : '??' }}</div>
                          <div class="team-leader-info" style="display:flex; flex-direction:column; gap:2px;">
                            <h4 style="margin:0; font-size:0.95rem; font-weight:700; color:#1f2937;">{{ $op ? $op->name : 'Deleted Driver' }}</h4>
                            <p style="margin:0; color:#6b7280; font-size:0.8rem; display:flex; align-items:center; gap:6px;">
                          <i class="fas fa-car"></i>
                          Vehicle Operator
                        </p>
                      </div>
                        </div>
                      @endforeach
                    </div>
                  </td>
                  <td>
                    <div class="team-members-cell">
                      <div class="team-members-tags">
                          <div class="team-member-tag" style="background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);">
                            <i class="fas fa-ambulance"></i>
                            <span>{{ $ambulance ? $ambulance->name : 'Deleted Ambulance' }}</span>
                            @if($ambulance && $ambulance->plate_number)
                              <span>({{ $ambulance->plate_number }})</span>
                            @endif
                          </div>
                      </div>
                    </div>
                  </td>
                  <td class="date-cell">
                    <div class="date-primary">{{ $firstPairing->pairing_date->format('M d, Y') }}</div>
                    <div class="date-secondary">{{ $firstPairing->pairing_date->format('l') }}</div>
                  </td>
                  <td class="status-cell">
                    @if($allStatuses->count() == 1)
                      <span class="status-badge {{ $allStatuses->first() }}">
                        {{ ucfirst($allStatuses->first()) }}
                      </span>
                    @else
                      <div class="flex flex-wrap gap-1 justify-center">
                        @foreach($allStatuses as $status)
                          <span class="status-badge {{ $status }}">
                            {{ ucfirst($status) }}
                          </span>
                        @endforeach
                      </div>
                    @endif
                  </td>
                  <td class="actions-cell">
                      @if($isActive)
                      <div class="action-buttons">
                        <button onclick="editGroupPairings('{{ $groupKey }}')" class="action-btn edit" title="Edit Assignment">
                          <i class="fas fa-edit"></i>
                        </button>
                        <button onclick="bulkActionGroup('driver_ambulance', '{{ $groupKey }}', 'complete')" class="action-btn complete" title="Complete Assignment">
                          <i class="fas fa-check"></i>
                        </button>
                        <button onclick="bulkActionGroup('driver_ambulance', '{{ $groupKey }}', 'cancel')" class="action-btn cancel" title="Cancel Assignment">
                          <i class="fas fa-times"></i>
                        </button>
                      </div>
                      @else
                        <span class="text-sm text-gray-400 italic">No actions</span>
                      @endif
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="6" class="empty-state">No driver-ambulance pairings found.</td>
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
    const searchForm = document.querySelector('.search-form');
    const searchIndicator = document.getElementById('searchIndicator');
    let searchTimeout;
    let isSubmitting = false;
    let panelJustOpened = false;
    
    // Show success modal if there's a success message
    const successMessage = document.getElementById('successMessage') ? document.getElementById('successMessage').textContent.trim() : '';
    if (successMessage) {
      showSuccessModal(successMessage);
    }
    
    // Show error modal if there's an error message
    const errorMessage = document.getElementById('errorMessage') ? document.getElementById('errorMessage').textContent.trim() : '';
    if (errorMessage) {
      showErrorModal(errorMessage);
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

  // Error Modal Functions
  function showErrorModal(message) {
    const modal = document.getElementById('errorModal');
    const messageElement = document.getElementById('errorMessage');
    
    if (modal && messageElement) {
      messageElement.textContent = message;
      modal.style.display = 'flex';
      
      // Auto-close after 8 seconds (longer for errors)
      setTimeout(() => {
        closeErrorModal();
      }, 8000);
    }
  }

  function closeErrorModal() {
    const modal = document.getElementById('errorModal');
    if (modal) {
      modal.style.display = 'none';
    }
  }

  // Inline modal helpers
  const inlineModalThemes = {
    info:   { bg: 'linear-gradient(135deg,#2563eb,#1d4ed8)', icon: 'fas fa-info-circle' },
    success:{ bg: 'linear-gradient(135deg,#16a34a,#15803d)', icon: 'fas fa-check-circle' },
    warning:{ bg: 'linear-gradient(135deg,#f59e0b,#d97706)', icon: 'fas fa-triangle-exclamation' },
    danger: { bg: 'linear-gradient(135deg,#ef4444,#dc2626)', icon: 'fas fa-exclamation-circle' }
  };

  function openInlineModal({ title = 'Notice', message = '', type = 'info', actions = [] } = {}) {
    const modal = document.getElementById('inlineModal');
    if (!modal) return;

    const header = document.getElementById('inlineModalHeader');
    const icon = document.getElementById('inlineModalIcon');
    const titleEl = document.getElementById('inlineModalTitle');
    const messageEl = document.getElementById('inlineModalMessage');
    const actionsWrap = document.getElementById('inlineModalActions');

    const theme = inlineModalThemes[type] || inlineModalThemes.info;
    if (header) header.style.background = theme.bg;
    if (icon) icon.className = theme.icon;
    if (titleEl) titleEl.textContent = title;
    if (messageEl) messageEl.textContent = message;

    if (actionsWrap) {
      actionsWrap.innerHTML = '';
      const fallbackActions = actions.length ? actions : [{
        label: 'OK',
        variant: 'primary',
        handler: closeInlineModal
      }];

      fallbackActions.forEach(({ label, variant = 'primary', handler }) => {
        const btn = document.createElement('button');
        btn.type = 'button';
        btn.className = `inline-modal-btn ${variant}`;
        btn.textContent = label;
        btn.addEventListener('click', () => {
          if (typeof handler === 'function') handler();
        });
        actionsWrap.appendChild(btn);
      });
    }

    modal.classList.add('show');
  }

  function closeInlineModal() {
    const modal = document.getElementById('inlineModal');
    if (!modal) return;
    modal.classList.remove('show');
  }

  function showInlineNotice(message, options = {}) {
    openInlineModal({
      title: options.title || 'Notice',
      message,
      type: options.type || 'info',
      actions: options.actions
    });
  }

  function showConfirmModal({ message, title = 'Confirm Action', confirmLabel = 'Yes', cancelLabel = 'Cancel', onConfirm, type = 'warning' }) {
    openInlineModal({
      title,
      message,
      type,
      actions: [
        { label: cancelLabel, variant: 'secondary', handler: closeInlineModal },
        { label: confirmLabel, variant: 'primary', handler: () => { closeInlineModal(); if (typeof onConfirm === 'function') onConfirm(); } }
      ]
    });
  }

  // Close modal when clicking outside
  document.addEventListener('click', function(event) {
    const successModal = document.getElementById('successModal');
    const errorModal = document.getElementById('errorModal');
    const inlineModal = document.getElementById('inlineModal');
    
    if (successModal && event.target === successModal) {
      closeSuccessModal();
    }
    if (errorModal && event.target === errorModal) {
      closeErrorModal();
    }
    if (inlineModal && event.target === inlineModal) {
      closeInlineModal();
    }
  });

  // Close modal with Escape key
  document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
      closeSuccessModal();
      closeErrorModal();
      closeInlineModal();
    }
  });

  // Driver-Medic bulk actions
  function toggleAllDriverMedic() {
    const selectAll = document.getElementById('selectAllDriverMedic');
    const checkboxes = document.querySelectorAll('.driver-medic-group-checkbox');
    checkboxes.forEach(checkbox => checkbox.checked = selectAll && selectAll.checked);
  }

  function bulkActionDriverMedic(action) {
    const checkboxes = document.querySelectorAll('.driver-medic-group-checkbox:checked');
    if (checkboxes.length === 0) {
      showInlineNotice('Please select at least one pairing group.', { title: 'Selection Required' });
      return;
    }

    const proceed = () => {
      const form = document.createElement('form');
      form.method = 'POST';
      form.action = '{{ route("admin.pairing.bulk.action") }}';
      
      const csrfToken = document.createElement('input');
      csrfToken.type = 'hidden';
      csrfToken.name = '_token';
      csrfToken.value = '{{ csrf_token() }}';
      form.appendChild(csrfToken);

      const actionInput = document.createElement('input');
      actionInput.type = 'hidden';
      actionInput.name = 'action';
      actionInput.value = action;
      form.appendChild(actionInput);

      const typeInput = document.createElement('input');
      typeInput.type = 'hidden';
      typeInput.name = 'pairing_type';
      typeInput.value = 'driver_medic';
      form.appendChild(typeInput);

      const logInput = document.createElement('input');
      logInput.type = 'hidden';
      logInput.name = 'create_log';
      logInput.value = '1';
      form.appendChild(logInput);

      checkboxes.forEach(checkbox => {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'pairing_ids[]';
        input.value = checkbox.value;
        form.appendChild(input);
      });

      document.body.appendChild(form);
      form.submit();
      
      // Force page refresh after form submission to ensure table updates
      setTimeout(() => {
        window.location.reload();
      }, 1000);
    };

    showConfirmModal({
      title: 'Confirm Bulk Action',
      message: `Are you sure you want to ${action} ${checkboxes.length} selected pairing groups?`,
      confirmLabel: 'Yes, proceed',
      onConfirm: proceed,
      type: action === 'cancel' ? 'danger' : 'warning'
    });
  }


  function bulkActionGroup(pairingType, groupKey, action) {
    const proceed = () => {
      const form = document.createElement('form');
      form.method = 'POST';
      form.action = '{{ route("admin.pairing.group.action") }}';
      
      const csrfToken = document.createElement('input');
      csrfToken.type = 'hidden';
      csrfToken.name = '_token';
      csrfToken.value = '{{ csrf_token() }}';
      form.appendChild(csrfToken);

      const actionInput = document.createElement('input');
      actionInput.type = 'hidden';
      actionInput.name = 'action';
      actionInput.value = action;
      form.appendChild(actionInput);

      const typeInput = document.createElement('input');
      typeInput.type = 'hidden';
      typeInput.name = 'pairing_type';
      typeInput.value = pairingType;
      form.appendChild(typeInput);

      const groupInput = document.createElement('input');
      groupInput.type = 'hidden';
      groupInput.name = 'group_key';
      groupInput.value = groupKey;
      form.appendChild(groupInput);

      document.body.appendChild(form);
      form.submit();
      
      // Force page refresh after form submission to ensure table updates
      setTimeout(() => {
        window.location.reload();
      }, 1000);
    };

    showConfirmModal({
      title: 'Confirm Group Action',
      message: `Are you sure you want to ${action} this pairing group?`,
      confirmLabel: 'Yes, proceed',
      onConfirm: proceed,
      type: action === 'cancel' ? 'danger' : 'warning'
    });
  }

  function editGroupPairings(groupKey) {
    showInlineNotice('Group editing feature coming soon! For now, please edit individual pairings.', {
      title: 'Feature Coming Soon'
    });
  }

  // Driver-Ambulance bulk actions
  function toggleAllDriverAmbulance() {
    const selectAll = document.getElementById('selectAllDriverAmbulance');
    const checkboxes = document.querySelectorAll('.driver-ambulance-group-checkbox');
    checkboxes.forEach(checkbox => checkbox.checked = selectAll && selectAll.checked);
  }

  function selectAllDriverAmbulance() {
    const checkboxes = document.querySelectorAll('.driver-ambulance-group-checkbox');
    checkboxes.forEach(checkbox => checkbox.checked = true);
    const all = document.getElementById('selectAllDriverAmbulance');
    if (all) all.checked = true;
  }

  function deselectAllDriverAmbulance() {
    const checkboxes = document.querySelectorAll('.driver-ambulance-group-checkbox');
    checkboxes.forEach(checkbox => checkbox.checked = false);
    const all = document.getElementById('selectAllDriverAmbulance');
    if (all) all.checked = false;
  }

  function bulkActionDriverAmbulance(action) {
    const checkboxes = document.querySelectorAll('.driver-ambulance-group-checkbox:checked');
    if (checkboxes.length === 0) {
      showInlineNotice('Please select at least one pairing group.', { title: 'Selection Required' });
      return;
    }

    const proceed = () => {
      const form = document.createElement('form');
      form.method = 'POST';
      form.action = '{{ route("admin.pairing.bulk.action") }}';
      
      const csrfToken = document.createElement('input');
      csrfToken.type = 'hidden';
      csrfToken.name = '_token';
      csrfToken.value = '{{ csrf_token() }}';
      form.appendChild(csrfToken);

      const actionInput = document.createElement('input');
      actionInput.type = 'hidden';
      actionInput.name = 'action';
      actionInput.value = action;
      form.appendChild(actionInput);

      const typeInput = document.createElement('input');
      typeInput.type = 'hidden';
      typeInput.name = 'pairing_type';
      typeInput.value = 'driver_ambulance';
      form.appendChild(typeInput);

      const logInput = document.createElement('input');
      logInput.type = 'hidden';
      logInput.name = 'create_log';
      logInput.value = '1';
      form.appendChild(logInput);

      checkboxes.forEach(checkbox => {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'pairing_ids[]';
        input.value = checkbox.value;
        form.appendChild(input);
      });

      document.body.appendChild(form);
      form.submit();
      
      // Force page refresh after form submission to ensure table updates
      setTimeout(() => {
        window.location.reload();
      }, 1000);
    };

    showConfirmModal({
      title: 'Confirm Bulk Action',
      message: `Are you sure you want to ${action} ${checkboxes.length} selected pairing groups?`,
      confirmLabel: 'Yes, proceed',
      onConfirm: proceed,
      type: action === 'cancel' ? 'danger' : 'warning'
    });
  }

  // Pairing Side Panel Functions
  function openPairingPanel() {
    const panel = document.getElementById('pairingSidePanel');
    if (panel) {
      panel.style.display = 'block';
      // Trigger the slide-in animation
      setTimeout(() => {
        panel.style.right = '0';
      }, 10);
    }
  }

  function closePairingPanel() {
    const panel = document.getElementById('pairingSidePanel');
    if (panel) {
      panel.style.right = '-500px';
      // Hide the panel after animation completes
      setTimeout(() => {
        panel.style.display = 'none';
      }, 300);
    }
  }

  // Quick Action Functions
  function quickPairDriverMedic() {
    // Close the side panel first
    closePairingPanel();
    // Open the driver-medic panel
    setTimeout(() => {
      openDriverMedicPanel();
    }, 350);
  }

  function quickPairDriverAmbulance() {
    // Close the side panel first
    closePairingPanel();
    // Open the driver-ambulance panel
    setTimeout(() => {
      openDriverAmbulancePanel();
    }, 350);
  }

  function quickBulkPairing() {
    // Close the side panel first
    closePairingPanel();
    // Open the bulk panel
    setTimeout(() => {
      openBulkPanel();
    }, 350);
  }

  // Existing panel functions (if they don't exist, we'll add them)
  function openDriverMedicPanel() {
    const panel = document.getElementById('driverMedicPanel');
    if (panel) {
      panel.style.display = 'block';
      setTimeout(() => {
        panel.style.right = '0';
      }, 10);
    }
  }

  function closeDriverMedicPanel() {
    const panel = document.getElementById('driverMedicPanel');
    if (panel) {
      panel.style.right = '-600px';
      setTimeout(() => {
        panel.style.display = 'none';
      }, 300);
    }
  }

  function openDriverAmbulancePanel() {
    const panel = document.getElementById('driverAmbulancePanel');
    if (panel) {
      panel.style.display = 'block';
      setTimeout(() => {
        panel.style.right = '0';
      }, 10);
    }
  }

  function closeDriverAmbulancePanel() {
    const panel = document.getElementById('driverAmbulancePanel');
    if (panel) {
      panel.style.right = '-600px';
      setTimeout(() => {
        panel.style.display = 'none';
      }, 300);
    }
  }

  // Toggle landing sections
  function showDriverAmbulance() {
    const amb = document.getElementById('driverAmbulanceSection');
    const med = document.getElementById('driverMedicSection');
    if (amb && med) {
      amb.style.display = 'block';
      med.style.display = 'none';
      window.scrollTo({ top: amb.offsetTop - 80, behavior: 'smooth' });
      const btn = document.getElementById('toggleTableBtn');
      if (btn) {
        btn.innerHTML = '<i class="fas fa-user-md"></i> Driver‑Medic';
        btn.style.background = 'linear-gradient(135deg, #10b981 0%, #059669 100%)';
      }
    }
  }

  function showDriverMedic() {
    const amb = document.getElementById('driverAmbulanceSection');
    const med = document.getElementById('driverMedicSection');
    if (amb && med) {
      med.style.display = 'block';
      amb.style.display = 'none';
      window.scrollTo({ top: med.offsetTop - 80, behavior: 'smooth' });
      const btn = document.getElementById('toggleTableBtn');
      if (btn) {
        btn.innerHTML = '<i class="fas fa-ambulance"></i> Driver‑Ambu';
        btn.style.background = 'linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%)';
      }
    }
  }

  function toggleTable() {
    const amb = document.getElementById('driverAmbulanceSection');
    const med = document.getElementById('driverMedicSection');
    if (amb && med) {
      if (amb.style.display !== 'none') {
        showDriverMedic();
      } else {
        showDriverAmbulance();
      }
    }
  }

  function openBulkPanel() {
    const panel = document.getElementById('bulkPanel');
    if (panel) {
      panel.style.display = 'block';
      setTimeout(() => {
        panel.style.right = '0';
      }, 10);
    }
  }

  function closeBulkPanel() {
    const panel = document.getElementById('bulkPanel');
    if (panel) {
      panel.style.right = '-600px';
      setTimeout(() => {
        panel.style.display = 'none';
      }, 300);
    }
  }

  // Close panels when clicking outside
  document.addEventListener('click', function(event) {
    const pairingPanel = document.getElementById('pairingSidePanel');
    const driverMedicPanel = document.getElementById('driverMedicPanel');
    const driverAmbulancePanel = document.getElementById('driverAmbulancePanel');
    const bulkPanel = document.getElementById('bulkPanel');
    
    // Close pairing side panel if clicking outside
    if (pairingPanel && !pairingPanel.contains(event.target) && !event.target.closest('.pairing-main-btn')) {
      if (pairingPanel.style.right === '0px') {
        closePairingPanel();
      }
    }
    
    // Close other panels if clicking outside
    if (driverMedicPanel && !driverMedicPanel.contains(event.target)) {
      if (driverMedicPanel.style.right === '0px') {
        closeDriverMedicPanel();
      }
    }
    
    if (driverAmbulancePanel && !driverAmbulancePanel.contains(event.target)) {
      if (driverAmbulancePanel.style.right === '0px') {
        closeDriverAmbulancePanel();
      }
    }
    
    if (bulkPanel && !bulkPanel.contains(event.target)) {
      if (bulkPanel.style.right === '0px') {
        closeBulkPanel();
      }
    }
  });

  // Close panels with Escape key
  document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
      closePairingPanel();
      closeDriverMedicPanel();
      closeDriverAmbulancePanel();
      closeBulkPanel();
    }
  });

  // Tab switching functionality
  function switchTab(tabName) {
    // Hide all tab panels
    const panels = document.querySelectorAll('.tab-panel');
    panels.forEach(panel => panel.style.display = 'none');
    
    // Remove active class from all tab buttons
    const buttons = document.querySelectorAll('.tab-btn');
    buttons.forEach(button => {
      button.style.background = 'transparent';
      button.style.color = '#6b7280';
    });
    
    // Show selected tab panel
    const selectedPanel = document.getElementById(tabName + '-tab');
    if (selectedPanel) {
      selectedPanel.style.display = 'block';
    }
    
    // Activate selected tab button
    const selectedButton = event.target.closest('.tab-btn');
    if (selectedButton) {
      if (tabName === 'bulk') {
        selectedButton.style.background = 'linear-gradient(135deg, #8b5cf6, #7c3aed)';
      } else if (tabName === 'driver-medic') {
        selectedButton.style.background = 'linear-gradient(135deg, #10b981, #059669)';
      } else if (tabName === 'driver-ambulance') {
        selectedButton.style.background = 'linear-gradient(135deg, #3b82f6, #1d4ed8)';
      }
      selectedButton.style.color = 'white';
    }
  }

  // Bulk pairing functionality (from bulk.blade.php)
  function togglePairingType() {
    const pairingType = document.getElementById('pairing_type').value;
    const timeFields = document.getElementById('time_fields');
    const medicSelection = document.getElementById('medic_selection');
    const ambulanceSelection = document.getElementById('ambulance_selection');
    const startTimeInput = document.querySelector('input[name="start_time"]');
    const endTimeInput = document.querySelector('input[name="end_time"]');
    
    if (pairingType === 'driver_medic') {
      timeFields.style.display = 'block';
      medicSelection.style.display = 'block';
      ambulanceSelection.style.display = 'none';
      // Make time fields required for driver-medic
      if (startTimeInput) startTimeInput.required = true;
      if (endTimeInput) endTimeInput.required = true;
    } else if (pairingType === 'driver_ambulance') {
      timeFields.style.display = 'none';
      medicSelection.style.display = 'none';
      ambulanceSelection.style.display = 'block';
      // Remove required attribute for driver-ambulance
      if (startTimeInput) startTimeInput.required = false;
      if (endTimeInput) endTimeInput.required = false;
    } else {
      timeFields.style.display = 'none';
      medicSelection.style.display = 'none';
      ambulanceSelection.style.display = 'none';
      // Remove required attribute when no type selected
      if (startTimeInput) startTimeInput.required = false;
      if (endTimeInput) endTimeInput.required = false;
    }
  }

  function selectAllDrivers() {
    const checkboxes = document.querySelectorAll('.driver-checkbox:not(:disabled)');
    // Limit to maximum 2 drivers
    const maxDrivers = Math.min(2, checkboxes.length);
    checkboxes.forEach((checkbox, index) => {
      checkbox.checked = index < maxDrivers;
    });
  }

  function selectAllMedics() {
    const checkboxes = document.querySelectorAll('.medic-checkbox:not(:disabled)');
    checkboxes.forEach(checkbox => checkbox.checked = true);
  }

  function selectAllAmbulances() {
    const checkboxes = document.querySelectorAll('.ambulance-checkbox:not(:disabled)');
    // Enforce single selection in bulk mode; pick the first available only
    let selected = false;
    checkboxes.forEach(checkbox => {
      if (!selected) {
        checkbox.checked = true;
        selected = true;
      } else {
        checkbox.checked = false;
      }
    });
  }

  function limitDriverSelection() {
    const driverCheckboxes = document.querySelectorAll('input[name="drivers[]"]:not(:disabled)');
    const checkedDrivers = document.querySelectorAll('input[name="drivers[]"]:checked:not(:disabled)');
    
    if (checkedDrivers.length > 2) {
      // Uncheck the last checked driver
      checkedDrivers[checkedDrivers.length - 1].checked = false;
      showInlineNotice('Maximum 2 drivers can be selected for bulk pairing.', {
        title: 'Limit Reached'
      });
    }
  }

  // Initialize all event listeners after DOM is loaded
  document.addEventListener('DOMContentLoaded', function() {
    // Add event listeners for checkboxes in bulk pairing
    const checkboxes = document.querySelectorAll('input[type="checkbox"]');
    checkboxes.forEach(checkbox => {
      checkbox.addEventListener('change', function() {
        if (this.name === 'drivers[]') {
          limitDriverSelection();
        } else if (this.name === 'ambulances[]') {
          enforceSingleAmbulance(this);
        }
      });
    });
  });

  function enforceSingleAmbulance(changed) {
    const all = document.querySelectorAll('.ambulance-checkbox');
    if (changed && changed.checked) {
      all.forEach(cb => { if (cb !== changed) cb.checked = false; });
    }
  }

  // ===== Dynamic constraints for Driver-Ambulance based on selected date =====
  const pairedDriversByDate = Object.create(null);
  const pairedAmbulancesByDate = Object.create(null);
  @foreach($driverAmbulancePairsAll as $p)
    (function(){
      const d = '{{ $p->pairing_date->format('Y-m-d') }}';
      if (!pairedDriversByDate[d]) pairedDriversByDate[d] = {};
      if (!pairedAmbulancesByDate[d]) pairedAmbulancesByDate[d] = {};
      pairedDriversByDate[d][{{ $p->driver_id }}] = true;
      pairedAmbulancesByDate[d][{{ $p->ambulance_id }}] = true;
    })();
  @endforeach

  function applyDriverAmbConstraintsForDate(dateStr) {
    // Driver select (Driver-Ambulance tab)
    const driverSelect = document.querySelector('#driverAmbulancePanel select[name="driver_id"]');
    if (driverSelect) {
      const set = pairedDriversByDate[dateStr] || {};
      Array.prototype.forEach.call(driverSelect.options, function(opt){
        if (!opt.value) return;
        const isPaired = !!set[parseInt(opt.value, 10)];
        opt.disabled = isPaired;
        if (isPaired) {
          const baseText = opt.getAttribute('data-base') || opt.text;
          opt.setAttribute('data-base', baseText);
          opt.text = baseText + ' - Already paired';
        } else if (opt.getAttribute('data-base')) {
          opt.text = opt.getAttribute('data-base');
        }
      });
      // If current selection has become disabled, clear it
      if (driverSelect.value && set.has(parseInt(driverSelect.value, 10))) {
        driverSelect.value = '';
      }
    }
    // Ambulance select (Driver-Ambulance tab)
    const ambSelect = document.querySelector('#driverAmbulancePanel select[name="ambulance_id"]');
    if (ambSelect) {
      const setA = pairedAmbulancesByDate[dateStr] || {};
      Array.prototype.forEach.call(ambSelect.options, function(opt){
        if (!opt.value) return;
        const isPaired = !!setA[parseInt(opt.value, 10)];
        opt.disabled = isPaired;
        if (isPaired) {
          const baseText = opt.getAttribute('data-base') || opt.text;
          opt.setAttribute('data-base', baseText);
          opt.text = baseText + ' - Already paired';
        } else if (opt.getAttribute('data-base')) {
          opt.text = opt.getAttribute('data-base');
        }
      });
      if (ambSelect.value && setA.has(parseInt(ambSelect.value, 10))) {
        ambSelect.value = '';
      }
    }
    // Bulk checklists
    const driverChecks = document.querySelectorAll('#bulkPanel .driver-checkbox');
    if (driverChecks.length) {
      const setD = pairedDriversByDate[dateStr] || new Set();
      driverChecks.forEach(cb => {
        const paired = setD.has(Number(cb.value));
        cb.disabled = paired;
        const label = cb.closest('label');
        if (label) label.style.opacity = paired ? '0.6' : '';
      });
    }
    const ambChecks = document.querySelectorAll('#bulkPanel .ambulance-checkbox');
    if (ambChecks.length) {
      const setA2 = pairedAmbulancesByDate[dateStr] || new Set();
      ambChecks.forEach(cb => {
        const paired = setA2.has(Number(cb.value));
        cb.disabled = paired;
        const label = cb.closest('label');
        if (label) label.style.opacity = paired ? '0.6' : '';
      });
    }
  }

  // Hook to date changes
  document.addEventListener('change', function(e){
    if (e.target && e.target.name === 'pairing_date' && (e.target.closest('#driverAmbulancePanel') || e.target.closest('#bulkPanel'))) {
      const dateVal = e.target.value;
      applyDriverAmbConstraintsForDate(dateVal);
    }
  });

  // Apply initial constraints on load for default date
  (function(){
    const defaultDateInput = document.querySelector('#driverAmbulancePanel input[name="pairing_date"], #bulkPanel input[name="pairing_date"]');
    if (defaultDateInput && defaultDateInput.value) {
      applyDriverAmbConstraintsForDate(defaultDateInput.value);
    }
  })();

  // Inline submit handler to show success modal and refresh page
  async function handleInlineSubmit(e) {
    // Allow normal post; UI feedback will be handled by backend redirect with session('success')
    // This function exists to keep a single place if we later add AJAX.
  }

  // Simple client-side pagination for tables marked with data-paginate
  document.addEventListener('DOMContentLoaded', function(){
    document.querySelectorAll('table.pairing-table[data-paginate="true"]').forEach(function(tbl){
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
</body>
</html>
