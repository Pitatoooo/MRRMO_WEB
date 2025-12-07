@extends('layouts.app')

@section('content')
@php
    $firstName = auth()->check() ? explode(' ', auth()->user()->name ?? 'Admin')[0] : 'Admin';
@endphp
<style>
  /* --- EXISTING STYLES --- */
  #liveStatus {
    position: fixed; bottom: 20px; right: 20px; padding: 8px 14px;
    border-radius: 20px; font-size: 13px; font-weight: bold;
    background: gray; color: white; z-index: 9999;
  }
  .highlight { animation: flash 1s ease-in-out 2; }
  @keyframes flash {
    0% { background: #fff3cd; } 50% { background: #ffeeba; } 100% { background: transparent; }
  }
  @keyframes highlightNew {
    from { background-color: #fee2e2; } to { background-color: white; }
  }

  body { background:#f5f7fb; }
  .sidebar { width:240px; background:#0b2a55; position:fixed; top:0; left:0; bottom:0; color:white; padding:20px; }
  .sidebar h2 { font-weight:800; margin:20px 0 40px; font-size:18px; }
  .sidebar a { display:flex; align-items:center; gap:10px; padding:12px 14px; border-radius:10px; color:white; font-size:14px; text-decoration:none; margin-bottom:8px; }
  .sidebar a.active, .sidebar a:hover { background:#f7941d; color:#fff; }
  .main { margin-left:240px; min-height:100vh; }
  .topbar { background:white; height:70px; display:flex; align-items:center; justify-content:flex-end; padding:0 30px; box-shadow:0 2px 10px rgba(0,0,0,.08); }
  .admin-pill { background:white; padding:8px 14px; border-radius:999px; display:flex; align-items:center; gap:8px; box-shadow:0 4px 10px rgba(0,0,0,.12); font-size:13px; font-weight:600; }
  .container-box { padding:30px; }
  .card { background:white; border-radius:18px; box-shadow:0 15px 35px rgba(15,23,42,.08); padding:24px; }
  .header-row { display:flex; justify-content:space-between; align-items:center; margin-bottom:20px; }
  .header-row h3 { font-size:18px; font-weight:800; color:#031273; }
  .search-row { display:flex; gap:12px; margin-bottom:18px; }
  .search-row input, .search-row select { padding:10px 14px; border-radius:10px; border:1px solid #dbe0ea; font-size:14px; width:100%; }
  .btn-green { background:#16a34a; color:white; padding:10px 18px; border-radius:10px; font-weight:700; border:none; cursor:pointer; text-decoration:none; }
  
  table { width:100%; border-collapse:collapse; }
  th { text-align:left; font-size:12px; color:#64748b; padding:10px; border-bottom:1px solid #e5e7eb; }
  td { padding:12px 10px; border-bottom:1px solid #f1f5f9; font-size:14px; vertical-align: middle; }
  .actions button, .actions a.action-btn { border:none; padding:6px 10px; border-radius:8px; margin-right:6px; cursor:pointer; text-decoration: none; display: inline-block; font-size: 14px; line-height: 1.5; }

  /* Modal Styles */
  .modal-overlay {
      position: fixed; top: 0; left: 0; right: 0; bottom: 0;
      background: rgba(0,0,0,0.6); backdrop-filter: blur(4px);
      z-index: 10000; display: none; align-items: center; justify-content: center;
  }
  .modal-box {
      background: white; width: 90%; max-width: 500px;
      border-radius: 16px; box-shadow: 0 25px 50px rgba(0,0,0,0.25);
      overflow: hidden; animation: popIn 0.3s ease-out;
      max-height: 90vh; overflow-y: auto;
  }
  .modal-header {
      background: linear-gradient(135deg, #0b2a55, #1e40af); padding: 20px 25px;
      color: white; display: flex; justify-content: space-between; align-items: center;
  }
  .modal-header h3 { margin: 0; font-size: 18px; display: flex; align-items: center; gap: 10px; }
  .modal-close { background: transparent; border: none; color: rgba(255,255,255,0.7); font-size: 24px; cursor: pointer; }
  .modal-body { padding: 25px; }
  .detail-row { display: flex; justify-content: space-between; margin-bottom: 12px; border-bottom: 1px solid #f1f5f9; padding-bottom: 12px; }
  .detail-label { font-weight: 700; color: #94a3b8; font-size: 12px; text-transform: uppercase; width: 120px; }
  .detail-value { font-weight: 600; color: #334155; font-size: 14px; flex: 1; }
  .modal-image-container { text-align: center; margin-top: 15px; border-top: 1px solid #f1f5f9; padding-top: 15px; }
  .modal-image { max-width: 100%; border-radius: 8px; border: 1px solid #e2e8f0; margin-top: 10px; cursor: pointer; }
  .image-gallery { display:flex; align-items:center; justify-content:center; gap:8px; margin-top:8px; }
  .gallery-main { flex:1; }
  .gallery-nav { background:#e2e8f0; border:none; border-radius:999px; width:28px; height:28px; display:flex; align-items:center; justify-content:center; cursor:pointer; font-weight:700; color:#334155; }
  .gallery-nav:disabled { opacity:0.4; cursor:default; }
  .thumbnail-row { display:flex; flex-wrap:wrap; justify-content:center; gap:6px; margin-top:10px; }
  .thumbnail-image { width:48px; height:48px; object-fit:cover; border-radius:6px; border:2px solid transparent; cursor:pointer; }
  .thumbnail-image.active { border-color:#0b2a55; }
  .modal-actions {
      background: #f8fafc; padding: 15px 25px; border-top: 1px solid #e2e8f0;
      display: flex; justify-content: flex-end; gap: 10px;
  }
  @keyframes popIn { from { transform: scale(0.9); opacity: 0; } to { transform: scale(1); opacity: 1; } }
  .loading-text { font-style: italic; color: #94a3b8; font-size: 12px; }
</style>

<div class="sidebar">
    <h2>SILANG MDRRMO</h2>
    <a href="{{ route('dashboard') }}">📊 Dashboard</a>
    <a href="#">📝 Posting</a>
    <a href="">🚑 Drivers</a>
    <a href="">➕ Create</a>
    <a href="{{ route('admin.gps') }}" class="{{ request()->routeIs('admin.gps') ? 'active' : '' }}">
        📍 GPS Tracker
    </a>
    <a class="active" href="{{ route('reported-cases') }}">📄 Reported Cases</a>
</div>

<div class="main">
    <div class="topbar">
        <div class="admin-pill">
            👤 {{ $firstName }}
        </div>
    </div>

    <div class="container-box">
        <div class="card">
            <div class="header-row">
                <h3>Reported Cases</h3>
            </div>

            @if(session('success'))
                <div style="background-color: #d4edda; color: #155724; padding: 10px; border-radius: 5px; margin-bottom: 15px;">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div style="background-color: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px; margin-bottom: 15px;">
                    {{ session('error') }}
                </div>
            @endif

            <div class="search-row">
                <form action="{{ route('reported-cases') }}" method="GET" style="display:flex; gap:10px; width:100%;">
                    <input type="text" name="search" placeholder="Search..." value="{{ request('search') }}">
                    <a href="{{ route('reported-cases') }}" class="btn-green" style="text-align:center; padding-top:10px;">🔄 Refresh</a>
                </form>
            </div>

            <div class="overflow-x-auto">
                <table id="reports-table">
                <thead>
                    <tr>
                        <th>REPORTER</th>
                        <th>CONTACT #</th> 
                        <th>TYPE</th>
                        <th>PATIENT STATUS</th> 
                        <th>LOCATION</th>
                        <th>STATUS</th> 
                        <th>DATE</th>
                        <th>ACTIONS</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($cases as $case)
                    <tr data-id="{{ $case->id }}" data-user-id="{{ $case->user_id }}">

                        @php
                            $images = $case->uploaded_media;
                            if (is_string($images)) {
                                $images = str_replace(['{', '}', '"'], '', $images);
                                $images = explode(',', $images);
                            }
                            $firstImage = is_array($images) && count($images) > 0 ? $images[0] : '';

                            // Determine status color
                            $statusColor = match($case->status ?? 'PENDING') {
                                'PENDING' => '#d97706',
                                'ACKNOWLEDGED' => '#2563eb',
                                'ON_GOING' => '#ca8a04',
                                'RESOLVED' => '#16a34a',
                                'DECLINED' => '#dc2626',
                                default => '#64748b',
                            };

                            // Incident Type Colors from Mobile App
                            $incidentColor = match($case->incident_type) {
                                'Fire' => '#FF6B35',
                                'Medical' => '#3B82F6',
                                'Vehicular Accident' => '#FF4444',
                                'Flood' => '#4A90E2',
                                'Earthquake' => '#8B4513',
                                'Electrical' => '#F59E0B',
                                default => '#64748b',
                            };

                            // Patient Status (AVPU) Colors from Mobile App
                            $avpuColor = match($case->patient_status) {
                                'Alert' => '#10B981',
                                'Voice' => '#F59E0B',
                                'Pain' => '#FF6B35',
                                'Unresponsive' => '#EF4444',
                                default => '#334155',
                            };
                        @endphp

                        <td style="font-weight:bold; color:#0b2a55;">{{ $case->reporter_name ?? 'Guest/Unknown' }}</td>

                        <td>{{ $case->contact_number ?? '—' }}</td>

                        <td><span style="background-color:{{ $incidentColor }}; color:white; padding:4px 8px; border-radius:6px; font-size:12px; font-weight:600;">{{ $case->incident_type ?? '—' }}</span></td>

                        <td>
                            <span style="background-color:{{ $avpuColor }}; color:white; padding:4px 8px; border-radius:6px; font-size:12px; font-weight:600;">
                                {{ $case->patient_status ?? 'N/A' }}
                            </span>
                        </td>

                        <td class="address-cell" data-lat="{{ $case->latitude }}" data-lng="{{ $case->longitude }}" title="{{ $case->location }}">
                            {{ Str::limit($case->location, 30) }}
                        </td>

                        <td>
                            <form action="{{ route('admin.reports.updateStatus', $case->id) }}" method="POST" class="status-form">
                                @csrf
                                <input type="hidden" name="status" value="{{ $case->status ?? 'PENDING' }}">
                                <span class="status-badge" style="background-color: {{ $statusColor }}; color: white; border-radius: 6px; font-size: 12px; padding: 4px 8px; display: inline-block;">
                                    {{ ucfirst(strtolower(str_replace('_', ' ', $case->status ?? 'PENDING'))) }}
                                </span>
                            </form>
                        </td>
                        <td style="font-size:12px; color:#64748b;">{{ \Carbon\Carbon::parse($case->incident_datetime)->setTimezone('Asia/Manila')->format('M d, Y h:i A') }}</td>

                        <td class="actions">
                            <button class="btn-details" 
                                style="background:#64748b; color:white;" 
                                title="See Details"
                                onclick="openModal('{{ addslashes(json_encode($case)) }}', '{{ $firstImage }}')"
                            >
                                DETAILS
                            </button>

                            <button class="btn-details">
                                <a href="{{ url('/admin/gps') }}?lat={{ $case->latitude }}&lng={{ $case->longitude }}&location={{ urlencode($case->location) }}&name={{ urlencode($case->reporter_name) }}&contact={{ urlencode($case->contact_number ?? '') }}" 
                                    class="btn-icon" 
                                    style="color:black; text-decoration:none;"
                                    title="Pin on Map">
                                    PIN ON MAP 
                                </a>
                            </button>

                            @php $workflowStatus = $case->status ?? 'PENDING'; @endphp
                            @if($workflowStatus === 'PENDING')
                                <button type="button"
                                    class="btn-accept" 
                                    style="background:#22c55e; color:white;" 
                                    title="Accept Report"
                                    onclick="handleStatusAction('{{ $case->id }}', 'ACKNOWLEDGED')">
                                    Accept Report
                                </button>
                                <button type="button"
                                    class="btn-dispatch" 
                                    style="background:#dc2626; color:white;" 
                                    title="Decline Report"
                                    onclick="handleStatusAction('{{ $case->id }}', 'DECLINED')">
                                    Decline Report
                                </button>
                                
                            @elseif($workflowStatus === 'ACKNOWLEDGED')
                                <button type="button"
                                    class="btn-dispatch" 
                                    style="background:#2563eb; color:white;" 
                                    title="Dispatch Team"
                                    onclick="handleStatusAction('{{ $case->id }}', 'ON_GOING')">
                                    Dispatch Team
                                </button>
                                <button type="button"
                                    class="btn-dispatch" 
                                    style="background:#dc2626; color:white;" 
                                    title="Decline Report"
                                    onclick="handleStatusAction('{{ $case->id }}', 'DECLINED')">
                                    Decline Report
                                </button>
                            @elseif($workflowStatus === 'ON_GOING')
                                <button type="button"
                                    class="btn-dispatch" 
                                    style="background:#16a34a; color:white;" 
                                    title="Mark as Resolved"
                                    onclick="handleStatusAction('{{ $case->id }}', 'RESOLVED')">
                                    Mark as Resolved
                                </button>
                            @elseif($workflowStatus === 'RESOLVED')
                                <span style="font-size:12px; color:#16a34a; font-weight:600;">
                                    Case Resolved!
                                </span>
                            @elseif($workflowStatus === 'DECLINED')
                                <span style="font-size:12px; color:#dc2626; font-weight:600;">
                                    Report Declined
                                </span>
                            @endif
                        </td>

                    </tr>
                    @empty
                    <tr><td colspan="7" style="text-align:center; padding:30px; color:#94a3b8;">No reported cases found.</td></tr>
                    @endforelse
                </tbody>
                </table>
                <div style="margin-top:20px;">{{ $cases->links() }}</div>
            </div>
        </div>
    </div>
</div>

<div id="caseModal" class="modal-overlay">
    <div class="modal-box">
        <div class="modal-header">
            <h3><i class="fas fa-file-medical"></i> Incident Details</h3>
            <button class="modal-close" onclick="closeModal()">&times;</button>
        </div>
        <div class="modal-body">
            <div class="detail-row"><span class="detail-label">Reporter</span><span class="detail-value" id="m_reporter"></span></div>
            <div class="detail-row"><span class="detail-label">Contact No.</span><span class="detail-value" id="m_contact_number"></span></div>
            <div class="detail-row"><span class="detail-label">Incident Type</span><span class="detail-value" id="m_type"></span></div>
            <div class="detail-row"><span class="detail-label">Patient Status</span><span class="detail-value" id="m_patient_status"></span></div>
            <div class="detail-row"><span class="detail-label">Status</span><span class="detail-value" id="m_status"></span></div>
            <div class="detail-row"><span class="detail-label">Location</span><span class="detail-value" id="m_location"></span></div>
            <div class="detail-row"><span class="detail-label">Description</span><span class="detail-value" id="m_description" style="font-style:italic;"></span></div>
            <div class="detail-row" style="border:none;"><span class="detail-label">Time Reported</span><span class="detail-value" id="m_time"></span></div>

            <div id="m_image_container" class="modal-image-container" style="display:none;">
                <div class="detail-label" style="width:100%; text-align:left; margin-bottom:5px;">Evidence Photo</div>
                <div id="m_image_gallery" class="image-gallery">
                    <button type="button" id="m_image_prev" class="gallery-nav" onclick="showPrevImage()">&#10094;</button>
                    <div class="gallery-main">
                        <a id="m_image_link" href="#" target="_blank">
                            <img id="m_image" class="modal-image" src="" alt="Evidence Photo">
                        </a>
                    </div>
                    <button type="button" id="m_image_next" class="gallery-nav" onclick="showNextImage()">&#10095;</button>
                </div>
                <div id="m_thumbnails" class="thumbnail-row"></div>
            </div>
        </div>
        <div class="modal-actions">
            <!-- ADDED: Print button -->
            <button onclick="printReportDetails()" style="padding:10px 18px; border-radius:8px; background:#0b2a55; color:white; font-weight:600; border:none; cursor:pointer; margin-right:auto;">
                🖨️ Print
            </button>
            
            <button onclick="closeModal()" style="padding:10px 18px; border:1px solid #cbd5e1; border-radius:8px; background:#64748b; color:white; font-weight:600; cursor:pointer;">Close</button>
        </div>
    </div>
</div>

<div id="liveStatus">CONNECTING...</div>

<div id="resolvedModal" class="modal-overlay">
    <div class="modal-box">
        <div class="modal-header">
            <h3>Report Resolved</h3>
            <button class="modal-close" onclick="closeResolvedModal()">&times;</button>
        </div>
        <div class="modal-body">
            <p id="resolvedModalMessage" style="margin: 0; color: #334155; font-weight: 500;"></p>
        </div>
        <div class="modal-actions">
            <button onclick="closeResolvedModal()" style="padding:10px 18px; border-radius:8px; background:#2563eb; color:white; font-weight:600; border:none; cursor:pointer;">
                Acknowledge &amp; Close
            </button>
        </div>
    </div>
</div>

<div id="statusConfirmModal" class="modal-overlay">
    <div class="modal-box">
        <div class="modal-header">
            <h3>Confirm Action</h3>
            <button class="modal-close" onclick="closeStatusConfirmModal()">&times;</button>
        </div>
        <div class="modal-body">
            <p id="statusConfirmMessage" style="margin: 0; color: #334155; font-weight: 500;"></p>
        </div>
        <div class="modal-actions">
            <button onclick="closeStatusConfirmModal()" style="padding:10px 18px; border-radius:8px; background:white; color:#0f172a; font-weight:600; border:1px solid #cbd5e1; cursor:pointer;">
                Cancel
            </button>
            <button onclick="performStatusUpdate()" style="padding:10px 18px; border-radius:8px; background:#0b2a55; color:white; font-weight:600; border:none; cursor:pointer;">
                Yes, Continue
            </button>
        </div>
    </div>
</div>

<div id="statusErrorModal" class="modal-overlay">
    <div class="modal-box">
        <div class="modal-header">
            <h3>Status Update Failed</h3>
            <button class="modal-close" onclick="closeStatusErrorModal()">&times;</button>
        </div>
        <div class="modal-body">
            <p id="statusErrorMessage" style="margin: 0; color: #b91c1c; font-weight: 500;"></p>
        </div>
        <div class="modal-actions">
            <button onclick="closeStatusErrorModal()" style="padding:10px 18px; border-radius:8px; background:#dc2626; color:white; font-weight:600; border:none; cursor:pointer;">
                Close
            </button>
        </div>
    </div>
</div>

<script>
    let currentReportId = null;
    let modalMediaList = [];
    let modalMediaIndex = 0;

    // --- 0. PRINT FUNCTION (ADDED) ---
    function printReportDetails() {
        const reporterName = document.getElementById('m_reporter').innerText;
        const contactNumber = document.getElementById('m_contact_number').innerText;
        const incidentType = document.getElementById('m_type').innerText;
        const patientStatus = document.getElementById('m_patient_status').innerText;
        const status = document.getElementById('m_status').innerText;
        const location = document.getElementById('m_location').innerText;
        const description = document.getElementById('m_description').innerText;
        const timeReported = document.getElementById('m_time').innerText;
        const imageElement = document.getElementById('m_image');

        let imagesHtml = '';

        if (modalMediaList && modalMediaList.length > 0) {
            imagesHtml += '<div class="print-section">';
            imagesHtml += '<div class="print-label" style="width:100%; text-align:left; margin-bottom:5px;">Evidence Photos</div>';
            for (let i = 0; i < modalMediaList.length; i++) {
                const rawUrl = modalMediaList[i];
                if (!rawUrl) continue;
                const mediaUrl = String(rawUrl).replace(/^{|}$/g, '').replace(/"/g, '').trim();
                if (!mediaUrl) continue;

                const isFirst = i === 0;
                const isLast = i === modalMediaList.length - 1;
                let wrapperStyle = 'text-align:center; margin-top:' + (isFirst ? '100px' : '100px') + '; page-break-inside: avoid;';
                if (!isLast) {
                    wrapperStyle += ' page-break-after: always; break-after: page;';
                }

                const imgStyleAttr = isFirst ? ' style="max-height:260px;"' : '';

                imagesHtml += '<div class="print-image-wrapper" style="' + wrapperStyle + '">';
                imagesHtml += '<div class="print-image-label" style="font-weight:600; margin-bottom:6px; color:#0b2a55;">Photo ' + (i + 1) + '</div>';
                imagesHtml += '<img src="' + mediaUrl + '" class="print-image" alt="Evidence Photo ' + (i + 1) + '"' + imgStyleAttr + ' />';
                imagesHtml += '</div>';
            }
            imagesHtml += '</div>';
        } else if (imageElement && imageElement.src) {
            imagesHtml += '<div class="print-section">';
            imagesHtml += '<div class="print-image-wrapper" style="text-align:center; margin-top:15px;">';
            imagesHtml += '<div class="print-image-label" style="font-weight:600; margin-bottom:8px; color: blue;">Evidence Photo</div>';
            imagesHtml += '<img src="' + imageElement.src + '" class="print-image" alt="Evidence Photo" style="display:inline-block;" />';
            imagesHtml += '</div>';
            imagesHtml += '</div>';
        }

        let printWindow = window.open('', '', 'height=600,width=800');
        printWindow.document.write(`
            <html>
            <head>
                <title style="text-align: right">Report Details</title>
                <style>
                    body { font-family: Arial, sans-serif; margin: 20px; background: white; }
                    .print-header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #0b2a55; padding-bottom: 15px; }
                    .print-header h1 { color: #0b2a55; margin: 0; }
                    .print-section { margin-bottom: 15px; }
                    .print-label { font-weight: bold; color: #0b2a55; }
                    .print-value { color: #334155; margin-top: 5px; }
                    .print-image { max-width: 400px; margin-top: 10px; border: 1px solid #ddd; }
                </style>
            </head>
            <body>
                <div class="print-header">
                    <h1>Incident Report Details</h1>
                </div>
                <div>
                <table class="print-table" style="width:100%; margin-bottom:20px; border-collapse:collapse;">
                    <tr>
                        <th style="color: blue;">Reporter: </th>
                        <td style="text-decoration: underline;"> ${reporterName}</td>

                        <th style="color: blue;">Contact No.: </th>
                        <td style="text-decoration: underline;"> ${contactNumber}</td>

                        <th style="color: blue;">Incident Type: </th>
                        <td style="text-decoration: underline;"> ${incidentType}</td>
                    </tr>
                </table>
                </div>
                <div>
                <table class="print-table" style="margin-bottom:20px;">
                    <tr>
                        <th style="color: blue;">Patient Status: </th>
                        <td>${patientStatus}</td>
                        <th style="color: blue;">Status: </th>
                        <td>${status}</td>
                    </tr>
                </table>
                </div>
                <div>
                <table class="print-table" style="margin-bottom:20px;">
                    <tr>
                        <th style="color: blue;">Description: </th>
                        <td>${description}</td>
                    </tr>
                </table>
                </div>
                <div>
                <table class="print-table" style="margin-bottom:20px;">
                    <tr>
                        <th style="color: blue;">Location: </th>
                        <td>${location}</td>
                    </tr>
                </table>
                </div>
                <table class="print-table" style="margin-bottom:20px;">
                    <tr>
                        <th style="color: blue;">Time Reported: </th>
                        <td>${timeReported}</td>
                    </tr>
                </table>
                </div>
                ${imagesHtml}
                <script>
                    window.print();
                    window.close();
                <\/script>
            </body>
            </html>
        `);
        printWindow.document.close();
    }

    // --- 1. STRICT STATUS ACTION HANDLER ---
    let pendingStatusReportId = null;
    let pendingStatusNewStatus = null;

    function openStatusConfirmModal(message, reportId, newStatus) {
        pendingStatusReportId = reportId;
        pendingStatusNewStatus = newStatus;

        const messageEl = document.getElementById('statusConfirmMessage');
        if (messageEl) {
            messageEl.textContent = message;
        }

        const modal = document.getElementById('statusConfirmModal');
        if (modal) {
            const header = modal.querySelector('.modal-header');
            if (header) {
                let bgColor = '#0b2a55';
                if (newStatus === 'ACKNOWLEDGED') {
                    // Match Accept button (green)
                    bgColor = '#22c55e';
                } else if (newStatus === 'ON_GOING') {
                    // Match Dispatch button (blue)
                    bgColor = '#2563eb';
                } else if (newStatus === 'DECLINED') {
                    // Match Decline button (red)
                    bgColor = '#dc2626';
                } else if (newStatus === 'RESOLVED') {
                    // Match Resolve button (green)
                    bgColor = '#16a34a';
                }
                header.style.background = bgColor;
            }
            modal.style.display = 'flex';
        }
    }

    function closeStatusConfirmModal() {
        const modal = document.getElementById('statusConfirmModal');
        if (modal) {
            modal.style.display = 'none';
        }
    }

    function showStatusError(message) {
        const messageEl = document.getElementById('statusErrorMessage');
        if (messageEl) {
            messageEl.textContent = message;
        }
        const modal = document.getElementById('statusErrorModal');
        if (modal) {
            const header = modal.querySelector('.modal-header');
            if (header) {
                header.style.background = '#dc2626';
            }
            modal.style.display = 'flex';
        }
    }

    function closeStatusErrorModal() {
        const modal = document.getElementById('statusErrorModal');
        if (modal) {
            modal.style.display = 'none';
        }
    }

    async function handleStatusAction(reportId, newStatus) {
        const row = document.querySelector(`tr[data-id="${reportId}"]`);
        if (!row) return;

        let confirmMessage;
        if (newStatus === 'ACKNOWLEDGED') {
            confirmMessage = 'Are you sure you want to ACCEPT this report?';
        } else if (newStatus === 'ON_GOING') {
            confirmMessage = 'Are you sure you want to DISPATCH a team for this report?';
        } else if (newStatus === 'DECLINED') {
            confirmMessage = 'Decline this report? This will mark it as acknowledged and it cannot be changed afterwards.';
        } else if (newStatus === 'RESOLVED') {
            confirmMessage = 'Mark this report as RESOLVED? This cannot be changed afterwards.';
        } else {
            confirmMessage = 'Apply this status change?';
        }

        openStatusConfirmModal(confirmMessage, reportId, newStatus);
    }

    async function performStatusUpdate() {
        const reportId = pendingStatusReportId;
        const newStatus = pendingStatusNewStatus;

        if (!reportId || !newStatus) {
            closeStatusConfirmModal();
            return;
        }

        const row = document.querySelector(`tr[data-id="${reportId}"]`);
        if (!row) {
            closeStatusConfirmModal();
            return;
        }

        closeStatusConfirmModal();

        try {
            const response = await fetch(`/admin/reports/${reportId}/status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
                body: JSON.stringify({ status: newStatus }),
            });

            if (!response.ok) {
                const text = await response.text();
                console.error('Status update failed:', text);
                showStatusError('Failed to update status. Please refresh and try again.');
                return;
            }

            // Optionally send push notification when report is accepted
            if (newStatus === 'ACKNOWLEDGED') {
                const userId = row.getAttribute('data-user-id');
                if (userId) {
                    try {
                        await fetch('/api/send-push', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            },
                            body: JSON.stringify({ user_id: userId, report_id: reportId }),
                        });
                    } catch (err) {
                        console.error('Push notification failed:', err);
                    }
                }
            }

            // Reload the page so badges and actions reflect the new status
            window.location.reload();
        } catch (error) {
            console.error('Error updating report status:', error);
            showStatusError('An unexpected error occurred while updating status.');
        }
    }

    // --- 2. ADDRESS PROCESSING ---
    document.addEventListener("DOMContentLoaded", function() {
        processAddressCells(document.querySelectorAll('.address-cell'));

        // Setup Live Status
        const statusBadge = document.getElementById("liveStatus");
        window.addEventListener("online", () => { statusBadge.innerText = "ONLINE"; statusBadge.style.background = "green"; });
        window.addEventListener("offline", () => { statusBadge.innerText = "OFFLINE"; statusBadge.style.background = "red"; });
    });

    async function processAddressCells(cells) {
        for (const cell of cells) {
            if (cell.dataset.processed) continue;

            const lat = cell.getAttribute('data-lat');
            const lng = cell.getAttribute('data-lng');
            const currentText = cell.innerText.trim();
            const isCoordinates = /^-?\d+(\.\d+)?(,\s*-?\d+(\.\d+)?)$/.test(currentText);

            if (lat && lng && isCoordinates) {
                try {
                    cell.innerHTML = '<span class="loading-text">Loading...</span>';
                    const url = `https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lng}`;
                    const response = await fetch(url);
                    const data = await response.json();
                    if (data && (data.display_name || data.address)) { cell.innerText = data.display_name; } 
                    else { cell.innerText = currentText; }
                } catch (error) {
                    cell.innerText = currentText;
                }
                cell.dataset.processed = "true";
                await new Promise(r => setTimeout(r, 800)); 
            }
        }
    }

    // --- 3. MODAL LOGIC ---
    async function openModal(caseJson, firstImage) {
        const data = typeof caseJson === 'string' ? JSON.parse(caseJson) : caseJson;

        currentReportId = data.id || null;

        document.getElementById('m_reporter').innerText = data.reporter_name || 'Guest';
        document.getElementById('m_contact_number').innerText = data.contact_number || 'N/A';

        // Incident Type with color
        const incidentTypeEl = document.getElementById('m_type');
        const incidentType = data.incident_type || 'N/A';
        const incidentColor = {
            'Fire': '#FF6B35',
            'Medical': '#3B82F6',
            'Vehicular Accident': '#FF4444',
            'Flood': '#4A90E2',
            'Earthquake': '#8B4513',
            'Electrical': '#F59E0B'
        }[incidentType] || '#64748b';
        incidentTypeEl.innerHTML = `<span style="background-color:${incidentColor}; color:white; padding:3px 7px; border-radius:6px; font-size:13px; font-weight:600;">${incidentType}</span>`;

        // AVPU Status with color
        const patientStatusEl = document.getElementById('m_patient_status');
        const patientStatus = data.patient_status || 'N/A';
        const avpuColor = {
            'Alert': '#10B981',
            'Voice': '#F59E0B',
            'Pain': '#FF6B35',
            'Unresponsive': '#EF4444'
        }[patientStatus] || '#64748b';
        patientStatusEl.innerHTML = `<span style="background-color:${avpuColor}; color:white; padding:3px 7px; border-radius:6px; font-size:13px; font-weight:600;">${patientStatus}</span>`;

        document.getElementById('m_status').innerText = data.status || 'Pending';
        const rawStatus = data.status || 'PENDING';
        const displayStatus = rawStatus === 'DECLINED' ? 'RECORDED' : rawStatus;
        document.getElementById('m_status').innerText = displayStatus;

        document.getElementById('m_description').innerText = data.description || 'No description.';

        const dateObj = new Date(data.incident_datetime || data.created_at);

        document.getElementById('m_time').innerText = dateObj.toLocaleString();

        // Location Logic
        const locationEl = document.getElementById('m_location');
        locationEl.innerText = data.location || `${data.latitude}, ${data.longitude}`;

        if (data.latitude && data.longitude) {
            try {
                const url = `https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${data.latitude}&lon=${data.longitude}`;
                const response = await fetch(url);
                const geoData = await response.json();
                if(geoData.display_name) locationEl.innerText = geoData.display_name;
            } catch (e) { console.warn("Modal Address Failed"); }
        }


        // Image Logic
        modalMediaList = [];
        if (data.uploaded_media) {
            modalMediaList = parseMediaValue(data.uploaded_media);
        }
        if ((!modalMediaList || modalMediaList.length === 0) && firstImage) {
            modalMediaList = parseMediaValue(firstImage);
        }
        modalMediaIndex = 0;
        updateModalImage();

        document.getElementById('caseModal').style.display = 'flex';
    }

    function parseMediaValue(value) {
        if (!value) return [];
        if (Array.isArray(value)) {
            return value.filter(function (item) { return !!item; });
        }
        let str = String(value).trim();
        if (!str) return [];
        try {
            const parsed = JSON.parse(str);
            if (Array.isArray(parsed)) {
                return parsed.filter(function (item) { return !!item; });
            }
            if (typeof parsed === 'string') {
                str = parsed.trim();
            }
        } catch (e) {}
        str = str.replace(/^[{\[]/, '').replace(/[}\]]$/, '');
        str = str.replace(/"/g, '');
        const parts = str.split(',').map(function (item) {
            return item.trim();
        }).filter(function (item) {
            return item.length > 0;
        });
        return parts;
    }

    function updateModalImage() {
        const imgContainer = document.getElementById('m_image_container');
        const img = document.getElementById('m_image');
        const link = document.getElementById('m_image_link');
        const thumbs = document.getElementById('m_thumbnails');
        const prevBtn = document.getElementById('m_image_prev');
        const nextBtn = document.getElementById('m_image_next');

        if (!imgContainer || !img || !link) {
            return;
        }

        if (!modalMediaList || modalMediaList.length === 0) {
            imgContainer.style.display = 'none';
            img.src = '';
            link.href = '#';
            if (thumbs) {
                thumbs.innerHTML = '';
            }
            if (prevBtn) prevBtn.disabled = true;
            if (nextBtn) nextBtn.disabled = true;
            return;
        }

        if (modalMediaIndex < 0) {
            modalMediaIndex = 0;
        }
        if (modalMediaIndex >= modalMediaList.length) {
            modalMediaIndex = modalMediaList.length - 1;
        }

        const rawUrl = modalMediaList[modalMediaIndex];
        if (!rawUrl) {
            imgContainer.style.display = 'none';
            return;
        }
        const mediaUrl = String(rawUrl).replace(/^{|}$/g, '').replace(/"/g, '').trim();

        img.src = mediaUrl;
        link.href = mediaUrl;
        imgContainer.style.display = 'block';

        if (thumbs) {
            thumbs.innerHTML = '';
            if (modalMediaList.length > 1) {
                for (let i = 0; i < modalMediaList.length; i++) {
                    const thumbRaw = modalMediaList[i];
                    const thumbUrl = String(thumbRaw).replace(/^{|}$/g, '').replace(/"/g, '').trim();
                    if (!thumbUrl) {
                        continue;
                    }
                    const thumb = document.createElement('img');
                    thumb.src = thumbUrl;
                    thumb.className = 'thumbnail-image' + (i === modalMediaIndex ? ' active' : '');
                    (function (index) {
                        thumb.addEventListener('click', function () {
                            modalMediaIndex = index;
                            updateModalImage();
                        });
                    })(i);
                    thumbs.appendChild(thumb);
                }
            }
        }

        if (prevBtn) {
            prevBtn.disabled = modalMediaList.length <= 1;
        }
        if (nextBtn) {
            nextBtn.disabled = modalMediaList.length <= 1;
        }
    }

    function showPrevImage() {
        if (!modalMediaList || modalMediaList.length === 0) return;
        modalMediaIndex = (modalMediaIndex - 1 + modalMediaList.length) % modalMediaList.length;
        updateModalImage();
    }

    function showNextImage() {
        if (!modalMediaList || modalMediaList.length === 0) return;
        modalMediaIndex = (modalMediaIndex + 1) % modalMediaList.length;
        updateModalImage();
    }

    function closeModal() {
        document.getElementById('caseModal').style.display = 'none';
        currentReportId = null;
    }

    function closeResolvedModal() {
        const el = document.getElementById('resolvedModal');
        if (el) el.style.display = 'none';
    }

    window.onclick = function(event) {
        if (event.target == document.getElementById('caseModal')) closeModal();
    };

    document.addEventListener("DOMContentLoaded", () => {

    const tableBody = document.querySelector("#reports-table tbody");

    const notifySound = new Audio("https://assets.mixkit.co/active_storage/sfx/2869/2869-preview.mp3");
    notifySound.loop = true;

    let audioUnlocked = true; // Already working

    const supabaseClient = window.supabaseClient || null;
    if (!supabaseClient) {
        const el = document.getElementById("liveStatus");
        if (el) {
            el.innerText = "REALTIME DISCONNECTED";
            el.style.background = "gray";
        }
        return;
    }

    // --- Realtime listener for new reports ---
    supabaseClient
        .channel("reports-live")
        .on(
            "postgres_changes",
            { event: "INSERT", schema: "public", table: "reports" },
            async (payload) => {
                const report = payload.new;

                // Add row with placeholders first
                const row = addNewRow(report);

                // Fetch reporter info via RPC if user_id exists
                if (report.user_id) {
                    try {
                        const { data: user, error } = await supabaseClient.rpc("get_reporter_info", { uid: report.user_id });

                        if (!error && user && user.length > 0) {
                            report.reporter_name = user[0].name || "Guest";
                            report.contact_number = user[0].contact_number || "—";
                        } else {
                            report.reporter_name = "Guest";
                            report.contact_number = "—";
                        }
                    } catch (err) {
                        report.reporter_name = "Guest";
                        report.contact_number = "—";
                        console.error("Error fetching user via RPC:", err);
                    }
                }

                // --- Update row cells ---
                if (row) {
                    row.querySelector("td:nth-child(1)").innerText = report.reporter_name;
                    row.querySelector("td:nth-child(2)").innerText = report.contact_number;

                    const displayStatusText = report.status === 'DECLINED'
                        ? 'RECORDED'
                        : (report.status || 'PENDING').replace('_', ' ');

                    // Update DETAILS button with correct report object
                    const reportJson = encodeURIComponent(JSON.stringify(report));
                    const detailsBtn = row.querySelector(".btn-details");
                    if (detailsBtn) {
                        detailsBtn.setAttribute("onclick", `openModal(decodeURIComponent('${reportJson}'), '')`);
                    }
                }

                // Play notification sound
                if (audioUnlocked) {
                    notifySound.play().catch(() => {});
                }
            }
        )
        .subscribe((status) => {
            const el = document.getElementById("liveStatus");
            if (status === "SUBSCRIBED") {
                el.innerText = "LIVE CONNECTED";
                el.style.background = "green";
            }
        });

        // --- Realtime listener for RESOLVED status updates (table + modal) ---
        supabaseClient
            .channel("reports-resolved")
            .on(
                "postgres_changes",
                { event: "UPDATE", schema: "public", table: "reports" },
                (payload) => {
                    if (!payload.new || !payload.old) return;

                    const report = payload.new;
                    const previous = payload.old;

                    // Only react when a report transitions into RESOLVED
                    if (previous.status === 'RESOLVED') return;
                    if (report.status !== 'RESOLVED') return;

                    // 1) Update the table row in place, if it exists on this page
                    const row = document.querySelector(`tr[data-id="${report.id}"]`);
                    if (row) {
                        // Update hidden status input, if present
                        const statusInput = row.querySelector('input[name="status"]');
                        if (statusInput) {
                            statusInput.value = 'RESOLVED';
                        }

                        // Update status badge to green "Resolved"
                        const statusBadge = row.querySelector('.status-badge');
                        if (statusBadge) {
                            statusBadge.style.backgroundColor = '#16a34a';
                            statusBadge.textContent = 'Resolved';
                        }

                        // Update actions cell to show resolved text
                        const actionsCell = row.querySelector('.actions');
                        if (actionsCell) {
                            actionsCell.innerHTML = '<span style="font-size:12px; color:#16a34a; font-weight:600;">Case Resolved!</span>';
                        }
                    }

                    // 2) If this report is currently open in the details modal, show the resolved modal
                    if (!currentReportId || String(report.id) !== String(currentReportId)) {
                        return;
                    }

                    const modal = document.getElementById('resolvedModal');
                    const msgEl = document.getElementById('resolvedModalMessage');
                    if (msgEl) {
                        msgEl.textContent = `Report #${report.id} has been marked as RESOLVED by the citizen.`;
                    }
                    if (modal) {
                        const header = modal.querySelector('.modal-header');
                        if (header) {
                            header.style.background = '#16a34a';
                        }
                        modal.style.display = 'flex';
                    }
                }
            )
            .subscribe();

        // --- Add new row function ---
        function addNewRow(report) {
            // Prevent duplicate row
            if (document.querySelector(`tr[data-id="${report.id}"]`)) return null;

            const row = document.createElement("tr");
            row.setAttribute("data-id", report.id);
            if (report.user_id) {
                row.setAttribute("data-user-id", report.user_id);
            }
            row.style.animation = "highlightNew 2s ease-out";

            const dateStr = new Date(report.incident_datetime || report.created_at).toLocaleString();
            const status = report.status || 'PENDING';

            let statusColor;
            switch (status) {
                case 'PENDING': statusColor = '#d97706'; break;
                case 'ACKNOWLEDGED': statusColor = '#2563eb'; break;
                case 'ON_GOING': statusColor = '#ca8a04'; break;
                case 'RESOLVED': statusColor = '#16a34a'; break;
                case 'DECLINED': statusColor = '#dc2626'; break;
                default: statusColor = '#64748b';
            }

            const incidentType = report.incident_type || 'N/A';
            const incidentColor = {
                'Fire': '#FF6B35',
                'Medical': '#3B82F6',
                'Vehicular Accident': '#FF4444',
                'Flood': '#4A90E2',
                'Earthquake': '#8B4513',
                'Electrical': '#F59E0B'
            }[incidentType] || '#64748b';

            const patientStatus = report.patient_status || 'N/A';
            const avpuColor = {
                'Alert': '#10B981',
                'Voice': '#F59E0B',
                'Pain': '#FF6B35',
                'Unresponsive': '#EF4444'
            }[patientStatus] || '#334155';

            const reportJson = encodeURIComponent(JSON.stringify(report));

            let actionsHtml = `
                    <button class="btn-details" style="background:#64748b; color:white;" onclick="openModal(decodeURIComponent('${reportJson}'), '')">DETAILS</button>
                    <button class="btn-details"><a href="/admin/gps?lat=${report.latitude}&lng=${report.longitude}" class="btn-icon" style="color:black; text-decoration:none;">PIN ON MAP</a></button>
            `;

            if (status === 'PENDING') {
                actionsHtml += `<button type="button" class="btn-accept" style="background:#22c55e; color:white;" title="Accept Report" onclick="handleStatusAction('${report.id}', 'ACKNOWLEDGED')">Accept Report</button>`;
                actionsHtml += `<button type="button" class="btn-dispatch" style="background:#dc2626; color:white;" title="Decline Report" onclick="handleStatusAction('${report.id}', 'DECLINED')">Decline Report</button>`;
            } else if (status === 'ACKNOWLEDGED') {
                actionsHtml += `<button type="button" class="btn-dispatch" style="background:#2563eb; color:white;" title="Dispatch Team" onclick="handleStatusAction('${report.id}', 'ON_GOING')">Dispatch Team</button>`;
                actionsHtml += `<button type="button" class="btn-dispatch" style="background:#dc2626; color:white;" title="Decline Report" onclick="handleStatusAction('${report.id}', 'DECLINED')">Decline Report</button>`;
            } else if (status === 'ON_GOING') {
                actionsHtml += `<button type="button" class="btn-dispatch" style="background:#16a34a; color:white;" title="Mark as Resolved" onclick="handleStatusAction('${report.id}', 'RESOLVED')">Mark as Resolved</button>`;
            } else if (status === 'RESOLVED') {
                actionsHtml += `<span style="font-size:12px; color:#16a34a; font-weight:600;">Case Resolved!</span>`;
            } else if (status === 'DECLINED') {
                actionsHtml += `<span style="font-size:12px; color:#0ea5e9; font-weight:600;">Report Recorded</span>`;
            }

            row.innerHTML = `
    <td style="font-weight:bold; color:#0b2a55;">${report.reporter_name || "Loading..."}</td>
    <td>${report.contact_number || "Loading..."}</td>
    <td><span style="background-color:${incidentColor}; color:white; padding:4px 8px; border-radius:6px; font-size:12px; font-weight:600;">${incidentType}</span></td>
    
    <td>
        <span style="background-color:${avpuColor}; color:white; padding:4px 8px; border-radius:6px; font-size:12px; font-weight:600;">
            ${patientStatus}
        </span>
    </td>

    <td class="address-cell" data-lat="${report.latitude}" data-lng="${report.longitude}">${report.location || "Locating..."}</td>
    
    <td>
        <form action="/admin/reports/${report.id}/status" method="POST" class="status-form">
            <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').getAttribute('content')}"> 
            <input type="hidden" name="status" value="${status}">
            <span class="status-badge" style="background-color: ${statusColor}; color: white; border-radius: 6px; font-size: 12px; padding: 4px 8px; display: inline-block;">
                ${status === 'DECLINED' ? 'RECORDED' : (status || 'PENDING').replace('_', ' ')}
            </span>
        </form>
    </td>
    <td style="font-size:12px; color:#64748b;">${dateStr}</td>
    <td class="actions">
        ${actionsHtml}
    </td>
`;

            tableBody.prepend(row);
            processAddressCells([row.querySelector(".address-cell")]);

            return row; // Return the row so we can update it later
        }
    });
</script>
@endsection