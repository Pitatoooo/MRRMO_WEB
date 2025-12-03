@extends('layouts.app')
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Driver Management - MDRRMO</title>
    <link rel="stylesheet" href="{{ asset('css/stylish.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        .card { background:#fff; border-radius:16px; box-shadow:0 10px 30px rgba(3,18,115,.08); border:1px solid #eef2f7; overflow:hidden; }
        .card-header { display:flex; align-items:center; justify-content:space-between; padding:16px 20px; background:linear-gradient(180deg,#f9fafb 0%, #f3f4f6 100%); border-bottom:1px solid #eef2f7; }
        .table-elegant { width:100%; border-collapse:separate; border-spacing:0; }
        /* .table-elegant thead th { position:sticky; top:0; z-index:5; background:#f9fafb; font-size:12px; letter-spacing:.06em; text-transform:uppercase; color:#6b7280; padding:14px 20px; border-bottom:1px solid #eef2f7; } */
        .table-elegant tbody tr { transition: background .15s ease; }
        .table-elegant tbody tr:hover { background:#f9fafb; }
        .table-elegant tbody tr:nth-child(even) { background:#fcfdff; }
        .row-online { box-shadow: inset 4px 0 0 0 #10b981; }
        .table-elegant td { padding:14px 20px; border-bottom:1px solid #e6ecf5; vertical-align:middle; }
        .table-elegant tbody tr:last-child td { border-bottom: 0; }
        .table-elegant tbody tr:hover td { border-bottom-color:#dfe7f3; }
        .chip { display:inline-flex; align-items:center; gap:6px; padding:6px 10px; font-size:12px; font-weight:600; border-radius:999px; border:1px solid transparent; }
        .chip-blue { background:#eff6ff; color:#1d4ed8; border-color:#bfdbfe; }
        .chip-green { background:#ecfdf5; color:#047857; border-color:#a7f3d0; }
        .chip-gray { background:#f3f4f6; color:#374151; border-color:#e5e7eb; }
        .chip-yellow { background:#fffbeb; color:#92400e; border-color:#fde68a; }
        .chip-red { background:#fef2f2; color:#991b1b; border-color:#fecaca; }
        .avatar { width:44px; height:44px; border-radius:999px; object-fit:cover; box-shadow:0 2px 8px rgba(0,0,0,.08); }
        .avatar-fallback { width:44px; height:44px; border-radius:999px; display:grid; place-items:center; background:linear-gradient(135deg,#3b82f6 0%, #6366f1 100%); color:#fff; font-weight:800; box-shadow:0 2px 8px rgba(0,0,0,.08); }
        .table-wrap { max-height:70vh; overflow:auto; }
        .sticky-shadow { box-shadow: 0 2px 0 rgba(0,0,0,0.03); }

        /* Action buttons visible by default */
        .actions .btn-icon { display:inline-flex; align-items:center; justify-content:center; width:34px; height:34px; border-radius:10px; border:1px solid #e5e7eb; background:#f8fafc; transition: all .15s ease; }
        .actions .btn-icon i { font-size:14px; }
        .actions .btn-icon:hover { background:#eef2f7; box-shadow:0 2px 8px rgba(0,0,0,.06); transform: translateY(-1px); }
        .actions .btn-blue { color:#1d4ed8; border-color:#c7d2fe; background:#eef2ff; }
        .actions .btn-indigo { color:#4f46e5; border-color:#c7d2fe; background:#eef2ff; }
        .actions .btn-green { color:#047857; border-color:#a7f3d0; background:#ecfdf5; }
        .actions .btn-red { color:#b91c1c; border-color:#fecaca; background:#fef2f2; }
        .driver-card {
            transition: all 0.3s ease;
            border-left: 4px solid #3b82f6;
        }
        .driver-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        }
        .status-indicator {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 8px;
        }
        .status-online { background-color: #10b981; }
        .status-offline { background-color: #6b7280; }
        .status-busy { background-color: #ef4444; }
        .status-on-break { background-color: #f59e0b; }
        .photo-placeholder {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 18px;
        }
        .license-expired {
            background-color: #fef2f2;
            border-color: #fecaca;
            color: #dc2626;
        }
        .license-expiring {
            background-color: #fffbeb;
            border-color: #fed7aa;
            color: #d97706;
        }

        /* New Analytics Design */
        .analytics-containers {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 24px;
            padding: 40px;
            margin-bottom: 30px;
            position: relative;
            min-height: 300px;
            max-width: 1200px;
            width: 93%;
            margin-left: auto;
            margin-right: auto;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1), 0 2px 4px -1px rgba(0,0,0,0.06);
        }
        .analytics-containers::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, #f59e0b 0%, #f97316 100%);
            border-radius: 24px 24px 0 0;
        }

        .analytics-header {
            background: linear-gradient(135deg, #f59e0b 0%, #f97316 100%);
            border: none;
            border-radius: 16px;
            padding: 16px 24px;
            margin-bottom: 24px;
            text-align: center;
            box-shadow: 0 4px 6px -1px rgba(245, 158, 11, 0.3);
        }

        .analytics-header h3 {
            color: #fff;
            font-size: 18px;
            font-weight: 700;
            letter-spacing: 0.3px;
            margin: 0;
        }

        .analytics-controls {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 20px;
        }

        .analytics-filter-controls {
            display: flex;
            justify-content: flex-end;
        }

        .analytics-buttons {
            display: flex;
            gap: 15px;
            align-items: center;
        }

        .analytics-search {
            position: relative;
        }

        .analytics-search input {
            background: #fff;
            border: 2px solid #000;
            border-radius: 25px;
            padding: 12px 20px;
            width: 500px;
            font-size: 14px;
            color: #333;
            outline: none;
            margin-top: -3px;
        }

        .analytics-search input::placeholder {
            color: #666;
            font-weight: 500;
        }

        .btn-analytics {
            background: linear-gradient(135deg, #f59e0b 0%, #f97316 100%);
            border: none;
            color: #fff;
            padding: 12px 20px;
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

        .btn-analytics:hover {
            filter: brightness(1.02);
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(245, 158, 11, 0.4);
        }
        /* Make select (ALL) same orange style with custom arrow */
        select.btn-analytics {
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            background: linear-gradient(120deg, #f6ad2e 0%, #d97706 100%) !important;
            color: #ffffff !important;
            padding-right: 38px; /* room for arrow */
            position: relative;
            box-shadow: inset 0 -2px 0 rgba(0,0,0,0.18);
        }
        /* white arrow on the right */
        select.btn-analytics:not(.oval) {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3E%3Cpath fill='%23ffffff' d='M1.41.59L6 5.17 10.59.59 12 2 6 8 0 2z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
            background-size: 12px 8px;
        }
        select.btn-analytics.oval {
            background-image: none;
        }
        .btn-analytics option { background:#f59e0b; color:#000; }

        .btn-analytics.oval {
            border-radius: 20px;
            padding: 10px 45px 10px 25px;
            margin-bottom: 20px;
            line-height: 1.5;
            vertical-align: middle;
        }

        .analytics-stats {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 18px;
            margin-top: 16px;
        }

        .analytics-stat-card {
            position: relative;
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 16px;
            padding: 18px;
            text-align: center;
            color: #1f2937;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08);
            min-height: 110px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            transition: box-shadow .2s ease, transform .2s ease;
        }
        .analytics-stat-card::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 3px;
            background: linear-gradient(90deg, #f59e0b 0%, #f97316 100%);
            border-radius: 16px 16px 0 0;
        }
        .analytics-stat-card:hover {
            box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1), 0 4px 6px -2px rgba(0,0,0,0.05);
            transform: translateY(-2px);
        }

        .analytics-stat-title {
            font-size: 12px;
            font-weight: 600;
            letter-spacing: .03em;
            margin-bottom: 6px;
            color: #6b7280;
            text-transform: uppercase;
        }

        .analytics-stat-value {
            font-size: 28px;
            font-weight: 700;
            line-height: 1;
            color: #111827;
        }

        @media (max-width: 768px) {
            .analytics-controls {
                flex-direction: column;
                gap: 20px;
            }
            
            .analytics-search input {
                width: 100%;
            }

            .analytics-filter-controls {
                justify-content: center;
            }
            
            .analytics-stats {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 480px) {
            .analytics-stats {
                grid-template-columns: 1fr;
            }
        }

        /* Driver Directory Design */
        .directory-container {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 24px;
            padding: 40px;
            margin-bottom: 30px;
            position: relative;
            max-width: 1200px;
            width: 93%;
            margin-left: auto;
            margin-right: auto;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1), 0 2px 4px -1px rgba(0,0,0,0.06);
        }
        .directory-container::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, #f59e0b 0%, #f97316 100%);
            border-radius: 24px 24px 0 0;
        }

        .directory-header {
            font-size: 20px;
            font-weight: 900;
            color: #000;
            text-transform: uppercase;
            margin-bottom: 20px;
            letter-spacing: 1px;
        }

        .directory-table-container {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 16px;
            overflow: hidden;
            /* Let tbody handle scrolling so scrollbar starts below header */
            max-height: none;
            padding-right: 0;
            scrollbar-gutter: stable;
            box-shadow: 0 6px 18px rgba(0,0,0,0.08);
        }

        .directory-table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            table-layout: fixed; /* fixed columns for block header/body */
        }

        /* Keep columns aligned when body scrolls */
        .directory-table thead, .directory-table tbody { display: block; }
        .directory-table thead {
            padding-right: 0; /* match container width; no extra header overhang */
            border: none; /* remove orange border */
            border-bottom: 3px solid #f59e0b; /* keep only bottom line */
            background: #ffffff;
        }
        .directory-table tbody {
            max-height: 62vh; /* scrolling area height */
            overflow-y: auto;
            scrollbar-gutter: stable; /* keep header and body columns aligned without header padding */
            border-top: none; /* remove extra top orange line over data */
        }
        .directory-table tbody tr:first-child { border-top: none; }
        /* Column widths for clean alignment */
        .directory-table thead th:nth-child(1), .directory-table tbody td:nth-child(1) { width: 18%; }
        .directory-table thead th:nth-child(2), .directory-table tbody td:nth-child(2) { width: 24%; }
        .directory-table thead th:nth-child(3), .directory-table tbody td:nth-child(3) { width: 18%; }
        .directory-table thead th:nth-child(4), .directory-table tbody td:nth-child(4) { width: 16%; }
        .directory-table thead th:nth-child(5), .directory-table tbody td:nth-child(5) { width: 16%; }
        .directory-table thead th:nth-child(6), .directory-table tbody td:nth-child(6) { width: 8%; }

        .directory-table thead th {
            background: #ffffff;
            border-bottom: 2px solid #f3f4f6;
            padding: 15px 12px;
            text-align: center;
            font-weight: 700;
            font-size: 13px;
            color: #1f2937;
            letter-spacing: 0.3px;
            position: relative;
            z-index: 5;
        }

        .directory-table tbody tr {
            border-bottom: 1px solid #f3f4f6;
        }

        .directory-table tbody tr:last-child {
            border-bottom: none;
        }

        .directory-table td {
            padding: 15px 12px;
            vertical-align: middle;
            text-align: center;
        }

        .driver-profile {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
        }

        .driver-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: #fff;
            border: 2px solid #f59e0b;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #000;
        }

        .driver-avatar i {
            font-size: 26px;
            color: #1f2937;
        }

        .driver-name {
            font-weight: 600;
            color: #000;
            font-size: 14px;
        }

        .driver-badge {
            background: linear-gradient(135deg, #2563eb 0%, #1e3a8a 100%);
            color: #ffffff;
            border: none;
            border-radius: 10px;
            padding: 6px 12px;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 0.3px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.08);
        }

        .contact-info {
            color: #000;
            font-weight: 500;
            font-size: 14px;
        }

        .license-info {
            color: #000;
            font-weight: 500;
            font-size: 14px;
        }

        .status-badge {
            border: none;
            border-radius: 10px;
            padding: 8px 16px;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 0.3px;
            color: #fff;
            box-shadow: 0 2px 4px rgba(0,0,0,0.08);
        }

        .status-active {
            background: #1e3a8a;
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

        .action-buttons {
            display: flex;
            flex-direction: column;
            gap: 6px;
            align-items: center;
        }

        .action-btn {
            width: 35px;
            height: 25px;
            border: 1px solid #000;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .action-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }

        .action-btn-blue {
            background: #1e3a8a;
            color: #fff;
        }

        .action-btn-green {
            background: #10b981;
            color: #fff;
        }

        .action-btn-red {
            background: #ef4444;
            color: #fff;
        }

        /* Scrollbar styling */
        .directory-table-container::-webkit-scrollbar {
            width: 8px;
        }

        .directory-table-container::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }

        .directory-table-container::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 4px;
        }

        .directory-table-container::-webkit-scrollbar-thumb:hover {
            background: #555;
        }

        /* Modal Button Hover Effects */
        .modal-cancel-btn:hover {
            transform: scale(1.05);
            border-color: #d1d5db;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            color: #ffffff !important;
        }

        .modal-logout-btn:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 12px rgba(239,68,68,0.4);
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

<main class="main-content pt-8" style="display: flex; flex-direction: column; align-items: center;">
<!-- <section class="containers-grid" style="max-width:1400px;"> -->
@if(session('success'))
      <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
        {{ session('success') }}
      </div>
    @endif

    <!-- Header (Redesigned with search pill) -->
    <style>
        .drivers-topbar { max-width: 1200px; width: 93%; margin: 0 auto 18px auto; background:#263f7a; border:2px solid #0b1e4d; border-radius:12px; padding:10px; display:flex; align-items:center; gap:14px; }
        .drivers-topbar .title { color:#ffffff; font-weight:900; font-size:22px; letter-spacing:.5px; margin-left:10px; }
        .drivers-topbar .search-area { margin-left:auto; display:flex; align-items:center; }
        .drivers-topbar .search-btn { background:linear-gradient(90deg,#7de3ff 0%, #1e3a8a 100%); color:#0b1e4d; font-weight:900; border:2px solid #000; border-radius:22px; padding:8px 18px; cursor:pointer; box-shadow: inset 0 -2px 0 rgba(0,0,0,.18); }
        .drivers-topbar .search-form { display:none; }
        .drivers-topbar.search-open .search-btn { display:none; }
        .drivers-topbar.search-open .search-form { display:block; }
        .drivers-topbar .search-input { width:min(680px, 64vw); background: linear-gradient(90deg,#74d9de 0%, #0b58c9 100%); color:#0b1e4d; font-weight:800; border:2px solid #000; border-radius:22px; padding:10px 18px; outline:none; }
        .drivers-topbar .search-input::placeholder { color:#0b1e4daa; font-weight:800; }
    </style>
    <div class="drivers-topbar" id="driversTopbar">
        <div class="title">Driver Management</div>
        <div class="search-area">
            <button type="button" id="toggleSearchBtn" class="search-btn">SEARCH</button>
            <form method="GET" action="{{ route('admin.drivers.index') }}" class="search-form" id="driversSearchForm">
                <input class="search-input" type="text" name="q" value="{{ request('q') }}" placeholder="SEARCH: Name, Email, ID">
            </form>
        </div>
    </div>

    <!-- Driver Analytics Dashboard -->
    <div class="analytics-containers">

      <!-- Controls -->
      <div class="analytics-controls">
        <!-- <div class="analytics-buttons">
          <a href="{{ route('admin.drivers.create') }}" class="btn-analytics">
            <i class="fas fa-user-plus"></i>
            ADD NEW DRIVER
          </a>
        </div> -->

        <div class="analytics-search" style="display:none;">
          <form method="GET" action="{{ route('admin.drivers.index') }}">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="SEARCH: Name, email, ID">
          </form>
        </div>
      </div>

      <!-- Filter Controls -->
      <div class="analytics-filter-controls">
        <form id="availabilityFilterForm" method="GET" action="{{ route('admin.drivers.index') }}" style="display: flex; align-items: center; gap: 12px; margin: 0;">
          <label style="font-weight: 900; font-size: 16px; color: #000; text-transform: uppercase; letter-spacing: 0.5px; margin: 0; line-height: 1; display: flex; align-items: center;">FILTER:</label>
          <div style="position: relative; display: inline-block; vertical-align: middle;">
            <select id="availabilitySelect" name="availability" class="btn-analytics oval" style="vertical-align: middle; margin: 0;">
              <option value="">ALL</option>
              <option value="online" {{ request('availability')==='online' ? 'selected' : '' }}>ONLINE</option>
              <option value="offline" {{ request('availability')==='offline' ? 'selected' : '' }}>OFFLINE</option>
              <option value="busy" {{ request('availability')==='busy' ? 'selected' : '' }}>BUSY</option>
              <option value="on_break" {{ request('availability')==='on_break' ? 'selected' : '' }}>ON BREAK</option>
            </select>
            <i class="fas fa-chevron-down" style="position: absolute; right: 18px; top: 50%; transform: translateY(-50%); pointer-events: none; color: #fff; font-size: 14px; font-weight: 900; line-height: 1;"></i>
          </div>
        </form>
      </div>

      <!-- Statistics Cards -->
      <div class="analytics-stats">
        <div class="analytics-stat-card">
          <div class="analytics-stat-title">Total Drivers</div>
          <div class="analytics-stat-value">{{ $drivers->count() }}</div>
        </div>
        <div class="analytics-stat-card">
          <div class="analytics-stat-title">Online</div>
          <div class="analytics-stat-value">{{ $drivers->where('availability_status', 'online')->count() }}</div>
        </div>
        <div class="analytics-stat-card">
          <div class="analytics-stat-title">License Expiring</div>
          <div class="analytics-stat-value">{{ $drivers->filter(function($driver) { return $driver->isLicenseExpiringSoon(); })->count() }}</div>
        </div>
        <div class="analytics-stat-card">
          <div class="analytics-stat-title">License Expired</div>
          <div class="analytics-stat-value">{{ $drivers->filter(function($driver) { return $driver->isLicenseExpired(); })->count() }}</div>
        </div>
      </div>
    </div>

    <!-- Driver Directory -->
    <div class="directory-container">
      <div class="directory-header">DRIVER DIRECTORY:</div>
      
      <div class="directory-table-container">
        <table class="directory-table">
          <thead>
            <tr>
              <th>Driver</th>
              <th>Contact</th>
              <th>License</th>
              <th>Status</th>
              <th>Availability</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            @forelse($drivers as $driver)
              <tr>
                <td>
                  <div class="driver-profile">
                    <div class="driver-avatar">
                      <i class="fas fa-user"></i>
                    </div>
                    <div class="driver-name">{{ $driver->name }}</div>
                    @if($driver->employee_id)
                      <div class="driver-badge">ID: {{ $driver->employee_id }}</div>
                    @endif
                    @php
                      $pairedAmbulanceName = $driver->ambulance->name ?? null;
                      if (!$pairedAmbulanceName) {
                        $activePair = \App\Models\DriverAmbulancePairing::where('driver_id', $driver->id)
                          ->where('status','active')->orderByDesc('pairing_date')->first();
                        if ($activePair) {
                          $pairedAmbulanceName = optional($activePair->ambulance)->name;
                        }
                      }
                    @endphp
                    @if($pairedAmbulanceName)
                      <div class="driver-badge">{{ $pairedAmbulanceName }}</div>
                    @else
                      <div class="driver-badge">Unassigned</div>
                    @endif
                  </div>
                </td>
                <td>
                  <div class="contact-info">{{ $driver->email }}</div>
                  @if($driver->phone)
                    <div class="contact-info">{{ $driver->phone }}</div>
                  @endif
                </td>
                <td>
                  @if($driver->license_number)
                    <div class="license-info">{{ $driver->license_number }}</div>
                    @if($driver->license_expiry)
                      <div class="license-info">{{ $driver->license_expiry->format('M d, Y') }}</div>
                    @endif
                  @else
                    <div class="license-info">No license</div>
                  @endif
                </td>
                <td>
                  <div class="status-badge status-{{ $driver->status === 'active' ? 'active' : ($driver->status === 'inactive' ? 'offline' : ($driver->status === 'suspended' ? 'busy' : 'offline')) }}">
                    {{ ucfirst($driver->status) }}
                  </div>
                </td>
                <td>
                  <div class="status-badge status-{{ str_replace('_', '-', $driver->availability_status) }}">
                    {{ ucfirst(str_replace('_', ' ', $driver->availability_status)) }}
                  </div>
                </td>
                <td>
                  <div class="action-buttons">
                    <a href="{{ route('admin.drivers.show', $driver) }}" class="action-btn action-btn-blue" title="View Details">
                      <i class="fas fa-eye"></i>
                    </a>
                    <a href="{{ route('admin.drivers.edit', $driver) }}" class="action-btn action-btn-blue" title="Edit">
                      <i class="fas fa-edit"></i>
                    </a>
                    <button type="button" class="action-btn action-btn-green" title="Log out driver" onclick="openForceLogoutModal({{ $driver->id }}, '{{ addslashes($driver->name) }}')">
                      <i class="fas fa-power-off"></i>
                    </button>
                    <form method="POST" action="{{ route('admin.drivers.destroy', $driver) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this driver?')">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="action-btn action-btn-red" title="Delete">
                        <i class="fas fa-trash"></i>
                      </button>
                    </form>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="6" style="padding: 40px; text-align: center; color: #000;">
                  <div style="display: flex; flex-direction: column; align-items: center; gap: 15px;">
                    <i class="fas fa-users" style="font-size: 48px; color: #666;"></i>
                    <div style="font-size: 18px; font-weight: 600;">No drivers found</div>
                    <div style="color: #666;">Get started by adding your first driver.</div>
                    <a href="{{ route('admin.drivers.create') }}" class="btn-analytics" style="margin-top: 10px;">
                      <i class="fas fa-plus"></i>
                      Add Driver
                    </a>
                  </div>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  <!-- </section> -->
</main>

<script>
// Force Logout Modal
const modalHtml = `
  <div id="forceLogoutModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,.4); backdrop-filter:blur(4px); -webkit-backdrop-filter:blur(4px); z-index:2000; align-items:center; justify-content:center;">
    <div style="background:#fff; border-radius:24px; border:1px solid #e5e7eb; box-shadow:0 10px 25px rgba(0,0,0,0.12); width:min(440px, 92vw); position:relative; overflow:hidden;">
      <div style="position:absolute; top:0; left:0; width:100%; height:4px; background:linear-gradient(90deg, #f59e0b 0%, #f97316 100%); border-radius:24px 24px 0 0;"></div>
      <div style="padding:18px 20px; border-bottom:1px solid #f3f4f6; display:flex; align-items:center; justify-content:space-between; margin-top:4px;">
        <div style="font-weight:700; color:#111827;">Log out driver</div>
        <button onclick="closeForceLogoutModal()" style="border:none; background:transparent; font-size:18px; cursor:pointer; color:#6b7280; transition:color 0.2s ease;">✕</button>
      </div>
      <div style="padding:18px 20px; color:#374151;">
        <p id="forceLogoutText" style="margin:0;">Are you sure you want to log out this driver?</p>
      </div>
      <div style="padding:16px 20px; border-top:1px solid #f3f4f6; display:flex; gap:10px; justify-content:flex-end;">
        <button onclick="closeForceLogoutModal()" class="modal-cancel-btn" style="padding:10px 16px; border-radius:10px; border:1px solid #e5e7eb; background:#000000; color:#ffffff; font-weight:600; cursor:pointer; transition:all 0.2s ease;">Cancel</button>
        <button id="forceLogoutConfirm" class="modal-logout-btn" style="padding:10px 16px; border-radius:10px; border:none; background:linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color:#fff; font-weight:600; cursor:pointer; transition:all 0.2s ease;">Log out</button>
      </div>
    </div>
  </div>`;

document.addEventListener('DOMContentLoaded', function(){
  if (!document.getElementById('forceLogoutModal')) {
    const wrap = document.createElement('div');
    wrap.innerHTML = modalHtml;
    document.body.appendChild(wrap.firstElementChild);
  }
});

let selectedDriverId = null;
function openForceLogoutModal(driverId, name){
  selectedDriverId = driverId;
  const modal = document.getElementById('forceLogoutModal');
  const text = document.getElementById('forceLogoutText');
  if (text) text.textContent = `Are you sure you want to log out ${name}?`;
  modal.style.display = 'flex';
  const btn = document.getElementById('forceLogoutConfirm');
  if (btn) {
    btn.onclick = submitForceLogout;
  }
}
function closeForceLogoutModal(){
  const modal = document.getElementById('forceLogoutModal');
  if (modal) modal.style.display = 'none';
}
async function submitForceLogout(){
  if (!selectedDriverId) return;
  try {
    const res = await fetch(`/admin/drivers/${selectedDriverId}/force-logout`, {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        'Content-Type': 'application/json'
      }
    });
    const data = await res.json();
    if (data && data.success){
      closeForceLogoutModal();
      alert('Driver has been logged out successfully.');
      // Optionally refresh to reflect immediate state
      location.reload();
    } else {
      alert('Failed to log out driver.');
    }
  } catch (e) {
    alert('Failed to log out driver.');
  }
}
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

// Search toggle for the drivers topbar
(function(){
  const topbar = document.getElementById('driversTopbar');
  const btn = document.getElementById('toggleSearchBtn');
  const form = document.getElementById('driversSearchForm');
  if (btn && topbar && form) {
    btn.addEventListener('click', function(){
      topbar.classList.add('search-open');
      const input = form.querySelector('input[name="q"]');
      if (input) setTimeout(()=>input.focus(), 20);
    });

    // Collapse when clicking anywhere outside the topbar
    document.addEventListener('click', function(ev){
      if (!topbar.contains(ev.target)) {
        topbar.classList.remove('search-open');
      }
    });

    // Optional: collapse with Escape key
    document.addEventListener('keydown', function(ev){
      if (ev.key === 'Escape') {
        topbar.classList.remove('search-open');
      }
    });
  }
})();

// Client-side combined filters: availability + live text search
(function(){
  const formAvail = document.getElementById('availabilityFilterForm');
  const selectEl = document.getElementById('availabilitySelect');
  const searchForm = document.getElementById('driversSearchForm');
  const searchInput = searchForm ? searchForm.querySelector('input[name="q"]') : null;

  if (!selectEl && !searchInput) return;

  function normalize(text){
    return String(text || '').toLowerCase();
  }

  function filterRows(){
    const availability = normalize(selectEl ? selectEl.value : '');
    const query = normalize(searchInput ? searchInput.value : '');
    const body = document.querySelector('.directory-table tbody');
    if (!body) return;
    const rows = Array.from(body.querySelectorAll('tr'));
    rows.forEach(row => {
      let visible = true;
      // availability match
      if (availability) {
        const availText = normalize((row.querySelector('td:nth-child(5)')?.textContent) || '');
        visible = availText.includes(availability);
      }
      // text search across main columns
      if (visible && query) {
        const cellsText = [1,2,3].map(i => normalize(row.querySelector(`td:nth-child(${i})`)?.textContent || '')).join(' ');
        visible = cellsText.includes(query);
      }
      row.style.display = visible ? '' : 'none';
    });
  }

  // Availability changes
  if (formAvail) {
    formAvail.addEventListener('submit', e => e.preventDefault());
  }
  if (selectEl) selectEl.addEventListener('change', filterRows);

  // Live search with small debounce
  if (searchInput) {
    let t;
    searchInput.addEventListener('input', () => {
      clearTimeout(t);
      t = setTimeout(filterRows, 120);
    });
  }
})();

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

// Auto-refresh the page every 10 seconds to reflect live statuses
setInterval(function(){
  try { 
    // Avoid interrupting form interactions
    const anyModalOpen = document.getElementById('forceLogoutModal') && document.getElementById('forceLogoutModal').style.display === 'flex';
    if (!anyModalOpen) {
      location.reload();
    }
  } catch(e) { location.reload(); }
}, 10000);
</script>
</body>
</html>