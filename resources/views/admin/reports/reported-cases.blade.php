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
                        <th>CONTACT #</th> <th>TYPE</th>
                        <th>LOCATION</th>
                        <th>STATUS</th> <th>DATE</th>
                        <th>ACTIONS</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($cases as $case)
                    <tr data-id="{{ $case->id }}">
                        @php
                            $images = $case->uploaded_media;
                            if (is_string($images)) {
                                $images = str_replace(['{', '}', '"'], '', $images);
                                $images = explode(',', $images);
                            }
                            $firstImage = is_array($images) && count($images) > 0 ? $images[0] : '';
                            
                            // Determine status color
                            $statusColor = match($case->patient_status ?? 'Pending') {
                                'Accepted' => '#16a34a', // Green
                                'Completed' => '#2563eb', // Blue
                                default => '#d97706', // Orange/Pending
                            };
                        @endphp

                        <td style="font-weight:bold; color:#0b2a55;">{{ $case->reporter_name ?? 'Guest/Unknown' }}</td>
                        
                        <td>{{ $case->contact_number ?? '—' }}</td>

                        <td><span style="background:#f3f4f6; padding:4px 8px; border-radius:6px; font-size:12px;">{{ $case->incident_type ?? '—' }}</span></td>
                        
                        <td class="address-cell" data-lat="{{ $case->latitude }}" data-lng="{{ $case->longitude }}" title="{{ $case->location }}">
                            {{ Str::limit($case->location, 30) }}
                        </td>

                        <td>
                            <span class="status-badge" style="background:{{ $statusColor }}; color:white; padding:4px 8px; border-radius:6px; font-size:12px;">
                                {{ $case->patient_status ?? 'Pending' }}
                            </span>
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

                            <button class="btn-details"><a href="{{ url('/admin/gps') }}?lat={{ $case->latitude }}&lng={{ $case->longitude }}&location={{ urlencode($case->location) }}&name={{ urlencode($case->reporter_name) }}&contact={{ urlencode($case->contact_number ?? '') }}" 
                                class="btn-icon" 
                                style="color:black; text-decoration:none;"
                                title="Pin on Map"> PIN ON MAP 
                            </a>

                            @if(($case->patient_status ?? 'Pending') !== 'Accepted')
                            <button class="btn-accept" 
                                style="background:#22c55e; color:white;" 
                                title="Accept & Notify"
                                onclick="acceptReport('{{ $case->id }}', '{{ $case->user_id }}')">
                                ACCEPT
                            </button>
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
            <div class="detail-row"><span class="detail-label">Incident Type</span><span class="detail-value" id="m_type"></span></div>
            <div class="detail-row"><span class="detail-label">Status</span><span class="detail-value" id="m_patient_status"></span></div>
            <div class="detail-row"><span class="detail-label">Location</span><span class="detail-value" id="m_location"></span></div>
            <div class="detail-row"><span class="detail-label">Description</span><span class="detail-value" id="m_description" style="font-style:italic;"></span></div>
            <div class="detail-row" style="border:none;"><span class="detail-label">Time Reported</span><span class="detail-value" id="m_time"></span></div>

            <div id="m_image_container" class="modal-image-container" style="display:none;">
                <div class="detail-label" style="width:100%; text-align:left; margin-bottom:5px;">Evidence Photo</div>
                <a id="m_image_link" href="#" target="_blank">
                    <img id="m_image" class="modal-image" src="" alt="Evidence Photo">
                </a>
            </div>
        </div>
        <div class="modal-actions">
            <button onclick="closeModal()" style="padding:10px 18px; border:1px solid #cbd5e1; border-radius:8px; background:red; font-weight:600; cursor:pointer;">Close</button>
            <a id="m_map_link" href="#" style="padding:10px 18px; border-radius:8px; background:#2563eb; color:white; font-weight:600; text-decoration:none; display:inline-block;">Pin on Map</a>
        </div>
    </div>
</div>

<div id="liveStatus">CONNECTING...</div>

<script>
    // --- 1. ACCEPT REPORT FUNCTION (NEW) ---
    async function acceptReport(reportId, userId) {
    if (!confirm("Are you sure you want to ACCEPT this report?")) return;

    // Update report status in Supabase
    const { error: updateError } = await supabaseClient
        .from('reports')
        .update({ patient_status: 'Accepted' })
        .eq('id', reportId);

    if (updateError) {
        alert("Error updating status: " + updateError.message);
        return;
    }

    // Send push via Laravel
    try {
        const res = await fetch("/api/send-push", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ user_id: userId, report_id: reportId })
        });

        let data;
        try {
            data = await res.json();
        } catch (err) {
            console.error("Invalid JSON response", await res.text());
            alert("Server returned an error. Check console for details.");
            return;
        }

        console.log("Push response:", data);
        if (data.success) alert("✅ Report accepted and push sent!");
        else alert("⚠️ Report accepted but push failed: " + data.error);
    } catch (err) {
        console.error(err);
        alert("⚠️ Failed to send push. Check console.");
    }

    // Update UI badge
    const row = document.querySelector(`tr[data-id="${reportId}"]`);
    if (row) {
        const badge = row.querySelector('.status-badge');
        if (badge) {
            badge.innerText = "Accepted";
            badge.style.background = "#16a34a";
        }
        const btn = row.querySelector('.btn-accept');
        if (btn) btn.remove();
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
            const isCoordinates = /^-?\d+(\.\d+)?,\s*-?\d+(\.\d+)?$/.test(currentText);

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

        document.getElementById('m_reporter').innerText = data.reporter_name || 'Guest';
        document.getElementById('m_type').innerText = data.incident_type || 'N/A';
        document.getElementById('m_patient_status').innerText = data.patient_status || 'Pending';
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

        // Map Link
        const mapUrl = `/admin/gps?lat=${data.latitude}&lng=${data.longitude}&name=${encodeURIComponent(data.reporter_name || '')}&contact=${encodeURIComponent(data.contact_number || '')}`;
        document.getElementById('m_map_link').href = mapUrl;

        // Image Logic
        const imgContainer = document.getElementById('m_image_container');
        const img = document.getElementById('m_image');
        const link = document.getElementById('m_image_link');
        
        // Handle image passed from blade or extracted from JSON
        let mediaUrl = firstImage; 
        
        // If passed as empty string, try parsing from JSON
        if (!mediaUrl && data.uploaded_media) {
             let mediaArray = data.uploaded_media;
             if (typeof mediaArray === 'string') {
                 try { mediaArray = JSON.parse(mediaArray); } catch(e){}
             }
             if (Array.isArray(mediaArray) && mediaArray.length > 0) mediaUrl = mediaArray[0];
             else if (typeof mediaArray === 'string') mediaUrl = mediaArray;
        }

        if (mediaUrl) {
            mediaUrl = mediaUrl.replace(/^{|}$/g, '').replace(/"/g, '').trim();
            img.src = mediaUrl;
            link.href = mediaUrl;
            imgContainer.style.display = 'block';
        } else {
            imgContainer.style.display = 'none';
        }

        document.getElementById('caseModal').style.display = 'flex';
    }

    function closeModal() {
        document.getElementById('caseModal').style.display = 'none';
    }
    
    window.onclick = function(event) {
        if (event.target == document.getElementById('caseModal')) closeModal();
    };

    document.addEventListener("DOMContentLoaded", () => {
    const tableBody = document.querySelector("#reports-table tbody");
    const notifySound = new Audio("https://assets.mixkit.co/active_storage/sfx/2869/2869-preview.mp3");
    notifySound.loop = true;

    let audioUnlocked = true; // Already working

    // --- Realtime listener ---
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
                } else {
                    report.reporter_name = "Guest";
                    report.contact_number = "—";
                }

                // Update the existing row with reporter info
                if (row) {
                    row.querySelector("td:nth-child(1)").innerText = report.reporter_name;
                    row.querySelector("td:nth-child(2)").innerText = report.contact_number;
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

        // --- Add new row function ---
        function addNewRow(report) {
        // Prevent duplicate row
        if (document.querySelector(`tr[data-id="${report.id}"]`)) return null;

        const row = document.createElement("tr");
        row.setAttribute("data-id", report.id);
        row.style.animation = "highlightNew 2s ease-out";

        const dateStr = new Date(report.created_at).toLocaleString();

        let btnHtml = "";
        if (report.patient_status !== "Accepted") {
            btnHtml = `<button class="btn-accept" style="background:#22c55e; color:white;" title="Accept" onclick="acceptReport('${report.id}', '${report.user_id}')">ACCEPT</button>`;
        }

        const reportJson = encodeURIComponent(JSON.stringify(report));

        row.innerHTML = `
            <td style="font-weight:bold; color:#0b2a55;">${report.reporter_name || "Loading..."}</td>
            <td>${report.contact_number || "Loading..."}</td>
            <td><span style="background:#f3f4f6; padding:4px 8px; border-radius:6px; font-size:12px;">${report.incident_type || "—"}</span></td>
            <td class="address-cell" data-lat="${report.latitude}" data-lng="${report.longitude}">${report.location || "Locating..."}</td>
            <td><span class="status-badge" style="background:#d97706; color:white; padding:4px 8px; border-radius:6px; font-size:12px;">${report.patient_status || "Pending"}</span></td>
            <td style="font-size:12px; color:#64748b;">${dateStr}</td>
            <td class="actions">
                <button class="btn-details" style="background:#64748b; color:white;" onclick="openModal(decodeURIComponent('${reportJson}'), '')">DETAILS</button>
                <button class="btn-details"><a href="/admin/gps?lat=${report.latitude}&lng=${report.longitude}" class="btn-icon" style="color:black; text-decoration:none;">PIN ON MAP</a></button>
                ${btnHtml}
            </td>
        `;

        tableBody.prepend(row);
        processAddressCells([row.querySelector(".address-cell")]);

        return row; // Return the row so we can update it later
    }
});


</script>
@endsection