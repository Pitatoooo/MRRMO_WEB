<!DOCTYPE html>
<html>
<head>
    <title>Driver GPS Tracker</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="theme-color" content="#1e3c72">

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.css" />

      <!-- ✅ External BOOTSTRAP -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Custom styles for Leaflet routing control */
.leaflet-routing-container {
    position: static !important;
    width: 100% !important;
    max-width: 100% !important;
    margin: 0.5rem 0 0 0 !important;
    background: rgba(255, 255, 255, 0.36) !important;
    border-radius: 2rem !important;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15) !important;
    border: 1px solid rgba(255, 255, 255, 0.2) !important;
    min-width: 280px !important;
    cursor: move !important;
    user-select: none !important;
}
.leaflet-routing-container h2 {
    background: rgba(10, 135, 174, 0.62) !important;
    color: #333 !important;
    font-weight: 600 !important;
    padding: 8px 12px !important;
    margin: 0 !important;
    border-radius: 8px 8px 0 0 !important;
    font-size: 14px !important;
}
.leaflet-routing-container .leaflet-routing-alt {
    background: transparent !important;
    max-height: 300px !important;
    overflow-y: auto !important;
}
.leaflet-routing-container .leaflet-routing-alt h3 {
    background: rgba(10, 135, 174, 0.62) !important;
    color: #555 !important;
    font-size: 13px !important;
    padding: 6px 12px !important;
    margin: 0 !important;
    border-bottom: 1px solid rgba(0, 195, 255, 0.1) !important;
}
.leaflet-routing-container .leaflet-routing-alt:first-child {
    border-radius: 0 !important;
}
.leaflet-routing-container .leaflet-routing-alt:last-child {
    border-radius: 0 0 8px 8px !important;
}
.leaflet-routing-container .leaflet-routing-alt:hover {
    background: rgba(0, 195, 255, 0.1) !important;
}

html, body {
    margin: 0;
    padding: 0;
    font-family: 'Segoe UI', Arial, sans-serif;
    background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
    overflow-x: hidden;
}

body {
    min-height: 100vh;
}

.container {
    width: 100%;
    max-width: 100vw;
    margin: 0 auto;
    padding: 0;
    overflow-x: hidden;
    border-radius: 2rem;
}
/* Header bar */
.top-header {
  width: 96%;
  max-width: 540px;
  margin: 12px auto 0 auto;
  background: transparent;
  border-radius: 0;
  color: #fff;
  position: relative;
  box-shadow: none;
  padding: 6px 16px;
}
.top-header .title {
  text-align: center;
  font-weight: 900;
  letter-spacing: .06em;
  text-transform: uppercase;
}
/* Burger button styling inside header */
.burger-btn {
  position: absolute;
  top: 1px;
  left: 1px;
  z-index: 10;
  background: #f59e0b;
  border: none;
  border-radius: 8px;
  padding: 6px 15px;
  font-size: 16px;
  color: #fff;
  cursor: pointer;
}


.glass-card {
    width: 96%;
    max-width: 540px;
    background: rgba(31, 33, 162, 0.23);
    border-radius: 2rem;
    box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0);
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
    border: 1.5px solid rgba(255,255,255,0.18);
    padding: 1.2rem 1rem 1rem 1rem;
    text-align: center;
    z-index: 3;
    animation: floatIn 1.2s cubic-bezier(.23,1.01,.32,1) 0s 1;
    margin: 1.2rem auto 0 auto;
}

/* Drawer look when opened from burger */
.glass-card.open {
    display: block !important;
    position: fixed;
    top: 1%;
    left: 50%;
    transform: translate(-50%, 0);
    width: 96%;
    max-width: 540px;
    margin: 0;
    padding: 38px 12px 14px 12px;
    border-radius: 12px;
    background: linear-gradient(180deg, rgba(8, 24, 114, 0.56) 0%, rgba(34, 58, 194, 0.28) 100%);
    backdrop-filter: blur(14px) saturate(140%);
    -webkit-backdrop-filter: blur(14px) saturate(140%);
    box-shadow: 0 10px 30px rgba(0,0,0,0.22);
    border: 1px solid rgba(255,255,255,0.28);
    color: #ffffff;
    z-index: 4000;
    animation: slideFromTop .25s ease-out;
}
.glass-card.open h2, .glass-card.open p { color: #ffffff; }
.glass-card.open .button-row { margin: 8px 0 2px 0; }
.glass-card.open .btn {
    background: linear-gradient(180deg, #f6ad2e 0%, #d97706 100%) !important;
    color:#fff !important;
    padding: 8px 14px;
    border-radius: 10px;
    font-size: 12px;
    font-weight: 800;
    border: 2px solid #ffffff;
    box-shadow: inset 0 -2px 0 rgba(0,0,0,.18), 0 3px 10px rgba(0,0,0,0.2);
}
.glass-card.open .btn:hover { filter: brightness(1.06); }

@keyframes slideFromTop { from { transform: translate(-50%, -18px); opacity: 0; } to { transform: translate(-50%, 0); opacity: 1; } }



.glass-card h2 {
    font-size: clamp(1.1rem, 4vw, 1.7rem);
    font-weight: 800;
    letter-spacing: -1px;
    color: #fff;
    text-shadow: 0 2px 16px #00c3ff44;
    margin-bottom: 0.5rem;
}
.glass-card p {
    color: #e0e0e0;
    margin: 0.5rem 0 1rem 0;
    font-size: 1.05rem;
}

.status {
    margin: 0.5rem 0 1.2rem 0;
    font-size: 1.08rem;
    font-weight: 600;
    color: #fff;
    min-height: 1.5em;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5em;
}
.status-glow {
    display: inline-block;
    width: 0.9em;
    height: 0.9em;
    border-radius: 50%;
    background: #00ffe7;
    box-shadow: 0 0 12px 3px #00ffe7cc, 0 0 32px 8px #00ffe733;
    animation: pulseGlow 1.2s infinite alternate;
}

.btn-container {
    display: flex;
    flex-direction: row; /* <-- Horizontal layout */
    justify-content: space-between;
    gap: 0.8rem;
    width: 100%;
    margin-top: 1rem;
    flex-wrap: nowrap; /* Prevent buttons from wrapping unless screen is very small */
}

.btn-container .btn {
    flex: 1 1 50%; /* Share space equally */
    min-width: 0; /* Prevent overflow on smaller screens */
    width: 100%;
}

.btn {
    background: linear-gradient(90deg, #00c3ff 0%, #3a7bd5 100%);
    color: #fff;
    border: none;
    padding: 1rem 0;
    border-radius: 1rem;
    font-size: 1.15rem;
    font-weight: 700;
    cursor: pointer;
    box-shadow: 0 2px 12px #00c3ff33;
    position: relative;
    overflow: hidden;
    transition: background 0.2s, transform 0.15s, filter 0.18s, box-shadow 0.18s;
    min-width: 0; /* Prevent overflow on smaller screens */
}


.button-row {
    display: flex;
    flex-direction: row;
    flex-wrap: wrap;
    gap: 0.6rem;
    margin-bottom: 0.8rem;
    justify-content: center;
}

.btn:hover, .btn:focus {
    filter: brightness(1.13);
    box-shadow: 0 4px 24px #00c3ff55;
    z-index: 2;
}
.btn-success {
    background: linear-gradient(90deg, #00ffb8 0%, #00c3ff 100%);
    color: #222;
}
.btn-success:active {
    background: linear-gradient(90deg, #00c3ff 0%, #00ffb8 100%);
}
.btn-success::after {
    background: linear-gradient(120deg, #fff8 0%, #fff0 100%);
}
.btn-success:hover, .btn-success:focus {
    filter: brightness(1.13);
    box-shadow: 0 4px 24px #00ffb855;
    z-index: 2;
}

/* Primary pill buttons inside emergency cases */
.case-primary-btn {
    background: linear-gradient(180deg, rgba(8, 24, 114, 0.92) 0%, rgb(34, 58, 194) 100%) !important;
    color: #ffffff;
    border: none;
    padding: 8px 12px;
    border-radius: 6px;
    font-weight: 700;
    font-size: 12px;
    letter-spacing: .01em;
    box-shadow: none;
    transition: all 0.2s ease;
}
.case-primary-btn:hover { 
    transform: translateY(-1px);
}

/* Emergency Cases modal header styling (title + icon buttons) */
.modal-header-emergency {
    background: #0b2a5a;
    color: #ffffff;
    padding: 10px 16px;
    border-radius: 14px 14px 0 0;
    border-bottom: 3px solid rgba(255,255,255,0.85);
}
.modal-header-emergency h3 {
    margin: 0;
    font-weight: 900;
    letter-spacing: .03em;
    text-transform: uppercase;
}
.dismiss-new-case-btn {
    width: 26px;
    height: 26px;
    border-radius: 10px;
    border: 2px solid #0b0b0b;
    background: linear-gradient(180deg, #7de3ff 0%, #1eb5de 100%);
    color: #ffffff;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-weight: 900;
    box-shadow: 0 2px 6px rgba(0,0,0,.25);
}
.action-icon-btn {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    border: 2px solid #0b0b0b;
    background: linear-gradient(180deg, #7de3ff 0%, #1eb5de 100%);
    color: #ffffff;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-weight: 900;
    box-shadow: 0 2px 6px rgba(0,0,0,.25);
}
.marker-label.leaflet-tooltip {
    background: transparent;
    color: #111827;
    border-radius: 0;
    border: none;
    font-weight: 800;
    padding: 0;
    box-shadow: none;
    text-transform: none;
    font-size: 11px;
    text-shadow: 0 1px 0 rgba(255,255,255,0.85);
}
.dest-popup .leaflet-popup-content-wrapper { background: transparent; box-shadow: none; padding: 0; border-radius: 0; }
.dest-popup .leaflet-popup-content { margin: 0; }
.dest-popup .leaflet-popup-tip { background: transparent; box-shadow: none; }
.dest-popup .leaflet-popup-close-button { display: none; }
.popup-close-internal { position: absolute; top: 8px; right: 10px; width: 24px; height: 24px; border-radius: 8px; border: 2px solid #ffffff; background: rgba(255,255,255,0.18); color: #ffffff; font-weight: 900; cursor: pointer; line-height: 18px; }
.pickup-popup .leaflet-popup-content-wrapper { background: transparent; box-shadow: none; padding: 0; border-radius: 0; }
.pickup-popup .leaflet-popup-content { margin: 0; }
.pickup-popup .leaflet-popup-tip { background: transparent; box-shadow: none; }
.pickup-popup .leaflet-popup-close-button { display: none; }
.action-icon-btn:hover { filter: brightness(1.05); }

/* Bottom action bar button styling to match design */
#bottom-action-bar .btn {
    background: linear-gradient(120deg, #f6ad2e 0%, #d97706 100%) !important;
    color: #fff !important;
    border: 2px solid #ffffff;
    border-radius: 10px;
    padding: 4px 7px;
    font-size: 12px;
    font-weight: 800;
    box-shadow: inset 0 -2px 0 rgba(0,0,0,0.18), 0 3px 10px rgba(0,0,0,0.2);
}
#bottom-action-bar .btn-view-cases {
    background: linear-gradient(120deg,rgba(98, 132, 211, 0.44) 0%,rgb(3, 40, 103) 100%) !important;
    color: #fff !important;
    border: 2px solid #ffffff;
    border-radius: 10px;
    padding: 4px 7px;
    font-size: 12px;
    font-weight: 800;
    box-shadow: inset 0 -2px 0 rgba(0,0,0,0.18), 0 3px 10px rgba(0,0,0,0.2);
}
#bottom-action-bar .btn:hover {
    filter: brightness(1.06);
}
#bottom-action-bar .btn-view-cases:hover {
    filter: brightness(3);
}

/* Stop Navigation button styling to match the appealing design */
.btn-stop-nav {
    background: linear-gradient(180deg, #ef4444 0%, #dc2626 100%) !important;
    color: #fff !important;
    border: 2px solid #ffffff;
    border-radius: 10px;
    padding: 4px 7px;
    font-size: 12px;
    font-weight: 800;
    box-shadow: inset 0 -2px 0 rgba(0,0,0,0.18), 0 3px 10px rgba(0,0,0,0.2);
    transition: all 0.2s ease;
    letter-spacing: 0.2px;
}

.btn-stop-nav:hover {
    filter: brightness(1.06);
    transform: translateY(-1px);
    box-shadow: inset 0 -2px 0 rgba(0,0,0,0.18), 0 4px 12px rgba(0,0,0,0.25);
}

.map-wrap {
    width: 100%;
    margin: 0;
    padding: 8px 30px 0px 30px;
    box-sizing: border-box;
    overflow: hidden;
    border-radius: 2rem;
}

#map {
    width: 100%;
    height: 100%;
    min-height: 220px;
    max-height: 60vh;
    border: none;
    border-radius: 2rem;
    box-shadow: 0 4px 24px rgba(0, 195, 255, 0.3);
    margin: 1rem 0;
    touch-action: auto; /* important for mobile dragging */

}
@keyframes floatIn {
    0% { opacity: 0; transform: translateY(-40px) scale(0.95); }
    100% { opacity: 1; transform: translateY(0) scale(1); }
}

@keyframes pulseGlow {
    0% { box-shadow: 0 0 12px 3px #00ffe7cc, 0 0 32px 8px #00ffe733; }
    100% { box-shadow: 0 0 24px 8px #00ffe7cc, 0 0 48px 16px #00ffe733; }
}


@keyframes mapSlideUp {
    0% { transform: translateY(60px) scale(0.98); opacity: 0; }
    100% { transform: translateY(0) scale(1); opacity: 1; }
}

/* Hidden state */
.hidden-card {
    display: none; !important;
}
.toggle-card-btn {
    display: none;
    background: linear-gradient(90deg, #00c3ff 0%, #3a7bd5 100%);
    color: white;
    font-weight: 700;
    border: none;
    padding: 0.75rem 1.2rem;
    margin: 1rem auto 0 auto;
    border-radius: 1rem;
    cursor: pointer;
    font-size: 1rem;
    box-shadow: 0 2px 12px #00c3ff33;
    transition: background 0.2s, transform 0.15s;
}

/* MEDIA QUERIES */

@media (max-width: 1200px) {
    .button-row {
        flex-direction: column;
        align-items: stretch;
        min-width: 0; /* Prevent overflow on smaller screens */
    }
    .btn {
        width: 100%;
        font-size: 0.95rem;
        min-width: 0; /* Prevent overflow on smaller screens */
    }
}

@media (max-width: 900px) {
    .container {
        max-width: 100vw;
        padding: 0 1rem;
    }
    .glass-card {
        max-width: 100%;
        width: 100%;
        margin: 1rem 0 0 0;
        display: none;
    }
    .map-wrap {
        width: 100%;
        margin: 0;
        padding: 0;
        max-width: 100%;
    }
    #map {
        border-radius: 1.5rem;
        margin: 1rem 0 0 0;
    }
}

@media (min-width: 900px) {
    #map {
        height: 65vh;
        min-height: 400px;
        max-height: 75vh;
        border-radius: 2.8rem;
    }
}

/* Glass card hidden by default on mobile */
@media (max-width: 768px) {
  .glass-card { display: none; }
}

@media (max-width: 600px) {
    html, body {
        height: 100%;
        margin: 0;
        padding: 0;
    }
    body {
        display: flex;
        flex-direction: column;
    }
    .container {
        flex: 1;
        display: flex;
        flex-direction: column;
    }
    .map-wrap {
        flex-grow: 1;
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
        padding: 0 !important;
        margin-top: 18px !important;
    }
    #map {
        flex: 1;
        height: 100% !important;
        max-height: none !important;
        min-height: 40vh !important;
        border-radius: 0 !important;
        margin: 0 !important;
    }
    .glass-card {
        padding: 1.2rem;
        border-radius: 1rem;
        display: flex;
        flex-direction: column;
        gap: 1rem;
        width: 100%;
        box-sizing: border-box;
        margin-bottom: 0;
        display: none;

    }
    .glass-card h2 {
        font-size: 1.2rem;
        margin: 0;
    }
    .glass-card p {
        margin: 0.5rem 0;
        font-size: 1rem;
    }
    .status {
        font-size: 0.95rem;
        margin-bottom: 1rem;
        flex-wrap: wrap;
    }
    .button-row {
        flex-direction: row;
        justify-content: space-between;
        gap: 0.5rem;
    }
    .btn-container {
        margin: 0;
        margin-bottom: 4px;
    }
    .btn {
        margin-top: 4px;
    }
    .toggle-card-btn {
        display: block;
    }
}

@media (max-width: 500px) {
    .button-row {
        flex-direction: column;
        align-items: stretch;
        min-width: 0; /* Prevent overflow on smaller screens */
    }
    .btn {
        width: 100%;
        font-size: 0.95rem;
        min-width: 0; /* Prevent overflow on smaller screens */
    }
}

@media (max-width: 400px) {
    .container {
        padding: 0 0.3rem 0.8rem 0.3rem;
    }
    .glass-card {
        padding: 0.7rem 0.2rem 0.5rem 0.2rem;
        border-radius: 0.8rem;
        display: none;
    }
    .glass-card h2 {
        font-size: clamp(1.1rem, 4vw, 1.7rem);
    }
    .glass-card p {
        font-size: 0.85rem;
    }
    .btn {
        font-size: 0.85rem;
        padding: 0.6rem 0;
        min-width: 0; /* Prevent overflow on smaller screens */
    }
    .btn-container {
        flex-direction: column;
        min-width: 0; /* Prevent overflow on smaller screens */
    }
    #map {
        height: 30vh;
        min-height: 180px;
        border-radius: 0.8rem;
    }
}


.settings-btn {
    position: absolute;
    top: 1px;
    right: 1px;
    z-index: 10;
    background: #f59e0b;
    border: none;
    border-radius: 8px;
    padding: 6px 12px;
    font-size: 16px;
    color: #fff;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    min-width: 40px;
    height: 32px;
}

.settings-btn:hover {
    filter: brightness(1.1);
}

/* Settings dropdown menu */
.settings-dropdown {
    display: none;
    position: fixed;
    top: 1%;
    left: 50%;
    transform: translate(-50%, 0);
    width: 96%;
    max-width: 540px;
    margin: 0;
    padding: 38px 12px 14px 12px;
    border-radius: 12px;
    background: linear-gradient(180deg, rgba(8, 24, 114, 0.56) 0%, rgba(34, 58, 194, 0.28) 100%);
    backdrop-filter: blur(14px) saturate(140%);
    -webkit-backdrop-filter: blur(14px) saturate(140%);
    box-shadow: 0 10px 30px rgba(0,0,0,0.22);
    border: 1px solid rgba(255,255,255,0.28);
    color: #ffffff;
    z-index: 4001;
}

.settings-dropdown.open {
    display: block !important;
    animation: slideFromTop .25s ease-out;
}

.settings-dropdown .btn {
    background: linear-gradient(180deg, #f6ad2e 0%, #d97706 100%) !important;
    color:#fff !important;
    padding: 8px 14px;
    border-radius: 10px;
    font-size: 12px;
    font-weight: 800;
    border: 2px solid #ffffff;
    box-shadow: inset 0 -2px 0 rgba(0,0,0,.18), 0 3px 10px rgba(0,0,0,0.2);
    cursor: pointer;
    width: 100%;
    margin-bottom: 10px;
}

.settings-dropdown .btn:hover { 
    filter: brightness(1.06); 
}

.settings-dropdown .btn:last-child {
    margin-bottom: 0;
}

@keyframes slideFromTop { 
    from { 
        transform: translate(-50%, -18px); 
        opacity: 0; 
    } 
    to { 
        transform: translate(-50%, 0); 
        opacity: 1; 
    } 
}


    </style>
</head>
<body>
    <!-- Super Admin Presence Banner -->
    <div id="superadmin-banner" style="display: none; position: fixed; top: 0; left: 0; right: 0; background: linear-gradient(135deg, #1e40af, #1e3a8a); color: white; padding: 10px 20px; text-align: center; font-weight: 600; font-size: 14px; z-index: 10000; box-shadow: 0 2px 8px rgba(0,0,0,0.2);">
        <i class="fas fa-user-shield" style="margin-right: 8px;"></i>
        <span>Super Admin Active</span>
    </div>
<div class="container">
    <!-- Top Header: Burger + Title -->
    <div class="top-header">
        <button class="burger-btn" onclick="toggleGlassCard()">☰</button>
        <div class="title">Ambulance  GPS Tracker</div>
        <p id="ambulance-name-display" style="text-transform: uppercase; font-weight: 800; color:#fff; font-size: 0.85rem; margin: 4px 0 0 0; text-align: center; letter-spacing: 0.05em;">Loading ambulance name...</p>

        <!-- Settings Button (place in your header) -->
<button class="settings-btn" onclick="toggleSettingsDropdown()" title="Settings" aria-label="Settings">
    <span style="font-size: 18px; line-height: 1; display: inline-flex; align-items: center; justify-content: center;">⚙️</span>
</button>

<!-- Settings Dropdown -->
<div id="settings-dropdown" class="settings-dropdown">
    <p style="text-transform: uppercase; font-weight: 800; color:#fff; text-align: center; margin: 0 0 20px 0; font-size: 1.2rem;">Settings</p>
    
    <!-- Keep the screen button (top) -->
    <button id="wake-lock-btn" class="btn" onclick="toggleWakeLock()">Keep the screen: OFF</button>
    
    <!-- Install App Button (middle, functional when PWA prompt available) -->
    <div id="install-app-container" style="margin-bottom: 10px; display: none;">
        <button id="a2hs-btn" class="btn">Install app (Add to Home Screen)</button>
    </div>
    
    <!-- Logout removed: admin controls logout -->

    <!-- Tracking status indicator (read-only) -->
    <div id="tracking-settings-indicator" style="margin-top: 10px; padding:10px 12px; border-radius:10px; font-weight:800; text-transform:uppercase; font-size:.85rem; background:#111827; color:#9ca3af; text-align:center;">
        Location sharing: OFF
    </div>
</div>
    </div>
    

    <div id="glass-card" class="glass-card" style="display:none;">
    <!-- Title moved to top header per design -->
    <div id="status" class="status" style="text-transform: uppercase; font-weight: 800;"><span class="status-glow"></span> Tracking not started yet.</div>
    
    <!-- Removed glass-card notifications list per design -->
    
    <!-- Auto-start tracking: manual button removed -->
    <div class="button-row" style="justify-content: center; gap: 12px;"></div>
</div>

    <div class="map-wrap">
        <div id="map">
        </div>
        
    </div>
</div>

<!-- Fixed Bottom Action Bar (Always visible per design) -->
<div id="bottom-action-bar" style="position: fixed; left: 0; right: 0; bottom: 0; z-index: 3000; padding: 10px 12px calc(10px + env(safe-area-inset-bottom));     background: linear-gradient(180deg, rgba(8, 24, 114, 0.81) 0%, rgba(34, 58, 194, 0.41) 100%);
 backdrop-filter: blur(10px); display: block;">
    <div style="display: flex; gap: 10px; align-items: stretch;">
        <button id="open-cases-modal-mobile" class="btn-view-cases" style="flex: 1; margin: 0;">View Cases</button>
        <button id="open-notifications-modal-mobile" class="btn" style="flex: 1; margin: 0; background:#f59e0b;">Notifications</button>
        <button id="stop-navigation-mobile" class="btn-stop-nav" style="flex: 1; margin: 0; display:none;" title="Clear current route"> Stop Navigate</button>
    </div>
    <div style="height: env(safe-area-inset-bottom);"></div>
    </div>

<!-- New Case Alert (Top Center) -->
<div id="new-case-alert" style="display:none; position: fixed; top: 10px; left: 50%; transform: translateX(-50%); z-index: 11000; width: min(92vw, 520px);">
    <div style="background:#0b2a5a; border-radius: 14px; box-shadow: 0 10px 24px rgba(0,0,0,0.15); overflow:hidden;">
        <div style="display:flex; justify-content: space-between; align-items:center; padding:10px 12px; background:#0b2a5a; border-bottom:3px solid rgba(255,255,255,0.85);">
            <div style="font-weight:800; color:#ffffff;">New Emergency Case</div>
            <button id="dismiss-new-case-alert" aria-label="Close" class="dismiss-new-case-btn">✕</button>
        </div>
        <div id="new-case-alert-body" style="padding:12px 12px 10px 12px; color:#0f172a;"></div>
    </div>
    <div style="height:8px"></div>
  </div>

<!-- Cases Modal -->
<div id="cases-modal" style="display:none; position:fixed; inset:0; z-index:2000;">
  <div style="position:absolute; inset:0; background:rgba(0,0,0,0.45)"></div>
  <div id="cases-modal-panel" style="position:relative; max-width:900px; width:92%; margin:3rem auto; background:#0b2a5a; border-radius:16px; box-shadow:0 10px 30px rgba(0,0,0,0.18); overflow:hidden;">
    <div class="modal-header-emergency" style="display:flex; justify-content:space-between; align-items:center; position: sticky; top: 0; z-index: 1;">
        <h3>Emergency Cases</h3>
        <div style="display:flex; gap:8px;">
            <button id="refresh-cases-btn" class="action-icon-btn" title="Refresh">↻</button>
            <button id="close-cases-modal" class="action-icon-btn" title="Close">✕</button>
        </div>
    </div>
    <div id="cases-container-wrap" style="padding:14px 16px; max-height:70vh; overflow:auto;">
        <div id="cases-container" class="text-start" style="background: transparent !important; backdrop-filter: none !important; -webkit-backdrop-filter: none;">
        
        </div>
    </div>
    </div>
  </div>

<!-- Notifications Modal -->
<div id="notifications-modal" style="display:none; position:fixed; inset:0; z-index:2000;">
  <div style="position:absolute; inset:0; background:rgba(0,0,0,0.45)"></div>
  <div id="notifications-modal-panel" style="position:relative; max-width:900px; width:92%; margin:3rem auto; background:#0b2a5a; border-radius:16px; box-shadow:0 10px 30px rgba(255, 247, 247, 0.18); overflow:hidden;">
    <div style="display:flex; justify-content:space-between; align-items:center; padding:12px 16px; border-bottom:3px solid rgba(255,255,255,0.85); position: sticky; top: 0; background:#0b2a5a; z-index: 1;">
        <h3 style="font-weight:800; font-size:18px; color:#ffffff; margin:0; text-transform:uppercase;">Pending Notifications</h3>
        <div style="display:flex; gap:8px;">
            <button id="refresh-notifications-modal-btn" class="action-icon-btn" title="Refresh">↻</button>
            <button id="close-notifications-modal" class="action-icon-btn" title="Close">✕</button>
        </div>
    </div>
    <div id="notifications-container-wrap" style="padding:14px 16px; max-height:70vh; overflow:auto;">
        <div id="notifications-container" class="text-start" style="background: transparent !important; backdrop-filter: none !important; -webkit-backdrop-filter: none;"></div>
    </div>
    </div>
  </div>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.min.js"></script>
<script>
    let ambulanceId = @json($ambulanceId);
    let map = L.map('map', {
        dragging: true,
        touchZoom: true,
        scrollWheelZoom: true,
        doubleClickZoom: true,
        boxZoom: true,
        keyboard: true,
        tap: true
    }).setView([14.215, 120.975], 13); // Default to Silang, Cavite
    let routeControl = null;
    let currentMarker = null;
    let navigationActive = false;
    let currentNavigationType = null; // 'pickup' or 'destination'
    let currentNavigationCase = null; // Current case number being navigated to

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);
    
    // Load cases immediately when map is ready (don't wait for GPS or user interaction)
    map.whenReady(() => {
        // Small delay to ensure everything is fully initialized
        setTimeout(() => {
            // Load all cases first (this adds markers to map)
            loadAllCases();
            // Then load notifications (this only handles notifications, not map markers)
            // Add a small delay to ensure loadAllCases completes first
            setTimeout(() => {
                loadCaseNotifications();
            }, 200);
        }, 300);
    });

    let tracking = false;
    let statusEl = document.getElementById('status');
    let statusGlow = statusEl.querySelector('.status-glow');
    let pinsInitialized = false; // ensure pins show after first GPS lock
    let lastCoords = null; // { lat, lng }
    let sendIntervalId = null;
    let lastSuccessfulSendAt = 0;
    let manualSyncBtn = null; // deprecated inline button
    let retryModal = null; // modal element for manual sync
    let consecutiveSendFailures = 0;
    let trackingStartedAt = 0;
    let wakeLock = null;
    let wakeEnabled = false;
    let wakeLockDesired = false; // Track if user wants wake lock ON (even after auto-release)
    let isRefreshingToken = false; // Prevent multiple simultaneous token refreshes
    let sessionExpired = false; // Track if session has expired

    // ===== PWA bootstrap (register SW and ensure manifest) =====
    (function initPWA(){
        const SW_URL = @json(asset('sw.js'));
        const MANIFEST_URL = @json(asset('manifest.webmanifest'));
        try {
            // Ensure manifest and theme-color exist as early as possible (mobile requires it at load time)
            if (!document.querySelector('link[rel="manifest"]')){
                const link = document.createElement('link');
                link.rel = 'manifest';
                link.href = MANIFEST_URL;
                document.head.appendChild(link);
            }
            if (!document.querySelector('meta[name="theme-color"]')){
                const meta = document.createElement('meta');
                meta.name = 'theme-color';
                meta.content = '#0b2a5a';
                document.head.appendChild(meta);
            }
            if ('serviceWorker' in navigator) { navigator.serviceWorker.register(SW_URL); }
        } catch(_) {}
    })();

    // Android A2HS prompt (iOS Safari doesn't support beforeinstallprompt)
    let deferredPrompt = null;
    const container = document.getElementById('install-app-container');
    const btn = document.getElementById('a2hs-btn');
    
    // Function to handle install button click
    function handleInstallClick() {
        if (!btn) return;
        
        // For iOS, show manual instructions
        const isIOS = /iPad|iPhone|iPod/.test(navigator.userAgent) && !window.MSStream;
        if (isIOS) {
            alert('To install:\n1. Tap the Share button (square with arrow)\n2. Select "Add to Home Screen"\n3. Tap "Add"');
            return;
        }
        
        // For Android/Chrome with native prompt available
        if (deferredPrompt) {
            btn.setAttribute('disabled','true');
            btn.textContent = 'Installing...';
            try {
                deferredPrompt.prompt();
                deferredPrompt.userChoice.then((choiceResult) => {
                    const { outcome } = choiceResult;
                    deferredPrompt = null; // Clear prompt after use
                    // Button stays visible even after installation
                }).catch((err) => {
                    console.error('Install prompt userChoice error:', err);
                }).finally(() => {
                    setTimeout(() => {
                        btn.removeAttribute('disabled');
                        btn.textContent = 'Install app (Add to Home Screen)';
                    }, 1500);
                });
            } catch (err) {
                console.error('Install prompt error:', err);
                btn.removeAttribute('disabled');
                btn.textContent = 'Install app (Add to Home Screen)';
            }
        } else {
            alert('Installation prompt not available. Please use your browser\'s menu to add to home screen.');
        }
    }
    
    // Always show install button
    if (container) {
        container.style.display = 'block';
        if (btn) {
            btn.textContent = 'Install app (Add to Home Screen)';
            btn.onclick = handleInstallClick;
        }
    }

    // Listen for native install prompt
    window.addEventListener('beforeinstallprompt', (e) => {
        e.preventDefault();
        deferredPrompt = e;
        // Button is already visible and handler is set, no need to do anything else
    });

    // ===== Retry Modal (instead of inline button) =====
    function ensureRetryModal(){
        if (retryModal) return retryModal;
        retryModal = document.createElement('div');
        retryModal.id = 'retry-modal';
        retryModal.style.cssText = 'display:none; position:fixed; inset:0; z-index:3000;';
        retryModal.innerHTML = `
            <div style="position:absolute; inset:0; background:rgba(0,0,0,0.55);"></div>
            <div style="position:relative; max-width:500px; width:92%; margin:10vh auto; background:#0b2a5a; border-radius:16px; box-shadow:0 10px 30px rgba(0,0,0,0.35); padding:18px; border:1px solid #1f3b77;">
                <h3 style="margin:0 0 8px; color:#fff; font-weight:800;">Connection issue</h3>
                <p style="margin:0 0 14px; color:#d1d5db;">We couldn’t send your location. Please retry or wait while we reconnect.</p>
                <div style="display:flex; gap:10px; justify-content:flex-end;">
                    <button id="retry-send" style="background:#f59e0b; color:#111827; border:none; padding:8px 12px; border-radius:10px; font-weight:800;">Retry now</button>
                </div>
            </div>
        `;
        document.body.appendChild(retryModal);
        retryModal.querySelector('#retry-send').onclick = async () => {
            const btn = retryModal.querySelector('#retry-send');
            btn.setAttribute('disabled','true');
            btn.textContent = 'Syncing...';
            try {
                if (lastCoords) {
                    await postLocation(lastCoords.lat, lastCoords.lng, 1);
                    setStatus(`✅ Manual sync at ${new Date().toLocaleTimeString()}`, '#00ffe7');
                    showRetryModal(false);
                } else {
                    navigator.geolocation.getCurrentPosition(pos => {
                        postLocation(pos.coords.latitude, pos.coords.longitude, 1).then(() => {
                            setStatus(`✅ Manual sync at ${new Date().toLocaleTimeString()}`, '#00ffe7');
                            showRetryModal(false);
                        });
                    });
                }
            } finally {
                btn.removeAttribute('disabled');
                btn.textContent = 'Retry now';
            }
        };
        return retryModal;
    }

    function showRetryModal(show){
        ensureRetryModal();
        retryModal.style.display = show ? 'block' : 'none';
    }

    // ===== Screen Wake Lock (keep screen on to avoid OS suspending sends) =====
    function toggleWakeLock(){
        const btn = document.getElementById('wake-lock-btn');
        if (!btn) return;
        
        if (!('wakeLock' in navigator)) {
            alert('Keep screen awake is not supported on this device/browser.');
            return;
        }
        
        if (wakeLockDesired) {
            // User manually turning OFF
            releaseWakeLock().then(() => {
                wakeLockDesired = false;
                wakeEnabled = false;
                updateWakeBtn();
            });
        } else {
            // User manually turning ON
            requestWakeLock().then(ok => {
                wakeLockDesired = true;
                wakeEnabled = !!ok;
                updateWakeBtn();
            });
        }
    }

    function updateWakeBtn(){
        const btn = document.getElementById('wake-lock-btn');
        if (!btn) return;
        // Show desired state, not actual state (since it may be auto-released but we want it ON)
        btn.textContent = wakeLockDesired ? 'Keep the screen: ON' : 'Keep the screen: OFF';
        btn.style.background = wakeLockDesired ? '#10b981' : '#111827';
    }

    async function requestWakeLock(){
        try {
            if (!('wakeLock' in navigator)) return false;
            wakeLock = await navigator.wakeLock.request('screen');
            wakeLockDesired = true; // User/system wants it ON
            wakeLock.addEventListener('release', () => {
                // Browser auto-released (e.g., tab switch) - keep desired state, just mark as not active
                wakeEnabled = false;
                wakeLock = null; // Clear reference
                // Don't set wakeLockDesired = false here - we want to re-acquire when visible again
                updateWakeBtn();
                // Re-acquire when page becomes visible again (handled by visibilitychange listener)
            });
            wakeEnabled = true;
            updateWakeBtn();
            return true;
        } catch (e) {
            return false;
        }
    }

    async function releaseWakeLock(){
        try { 
            if (wakeLock) {
                await wakeLock.release();
            }
        } catch(_) {}
        wakeLock = null;
        // Note: wakeLockDesired is set to false by the caller (toggleWakeLock) when manually turning OFF
    }

    // ===== CSRF Token Refresh and Session Management =====
    async function refreshCSRFToken() {
        if (isRefreshingToken) {
            // Wait for ongoing refresh to complete
            return new Promise((resolve) => {
                const checkInterval = setInterval(() => {
                    if (!isRefreshingToken) {
                        clearInterval(checkInterval);
                        resolve();
                    }
                }, 100);
            });
        }
        
        isRefreshingToken = true;
        try {
            // Fetch the current page to get a fresh CSRF token
            // Use a longer timeout since this might take time if session is expired
            const controller = new AbortController();
            const timeoutId = setTimeout(() => controller.abort(), 10000); // 10s timeout for token refresh
            
            try {
                const response = await fetch(window.location.href, {
                    method: 'GET',
                    headers: {
                        'Accept': 'text/html',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    credentials: 'same-origin',
                    signal: controller.signal
                });
                
                clearTimeout(timeoutId);
                
                // Check if we got redirected to login (session expired)
                if (response.redirected && response.url.includes('/login')) {
                    throw new Error('Session completely expired');
                }
                
                if (response.ok) {
                    const html = await response.text();
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const newToken = doc.querySelector('meta[name="csrf-token"]');
                    
                    if (newToken) {
                        const tokenValue = newToken.getAttribute('content');
                        updateCSRFToken(tokenValue);
                        return tokenValue;
                    }
                } else if (response.status === 401 || response.status === 302) {
                    // Session completely expired, redirect to login
                    throw new Error('Session completely expired');
                }
            } catch (fetchError) {
                clearTimeout(timeoutId);
                if (fetchError.name === 'AbortError') {
                    console.warn('Token refresh timed out, but continuing...');
                    // Don't throw - allow retry to continue
                } else {
                    throw fetchError;
                }
            }
        } catch (error) {
            if (error.message === 'Session completely expired') {
                throw error; // Re-throw to trigger login redirect
            }
            console.error('Error refreshing CSRF token:', error);
        } finally {
            isRefreshingToken = false;
        }
        return null;
    }
    
    // Helper function to update CSRF token in the page
    function updateCSRFToken(tokenValue) {
        const currentToken = document.querySelector('meta[name="csrf-token"]');
        if (currentToken) {
            currentToken.setAttribute('content', tokenValue);
        }
        sessionExpired = false;
        console.log('✅ CSRF token refreshed successfully');
    }
    
    async function handleAuthError(response, retryFunction) {
        // Check if it's a 401 or 419 error
        if (response.status === 401 || response.status === 419) {
            console.log(`⚠️ Session expired (${response.status}), attempting to refresh...`);
            sessionExpired = true;
            
            // Try to refresh CSRF token
            const newToken = await refreshCSRFToken();
            
            if (newToken) {
                // Retry the original request with new token
                if (retryFunction) {
                    return retryFunction();
                }
            } else {
                // If token refresh fails, redirect to login
                console.error('❌ Failed to refresh session, redirecting to login...');
                showToast('Session expired. Please login again.', 'error', null, 3000);
                setTimeout(() => {
                    window.location.href = '/driver/login';
                }, 2000);
                throw new Error('Session expired');
            }
        }
        
        // If response is not JSON (likely HTML error page), try to refresh token
        const contentType = response.headers.get('content-type');
        if (!contentType || !contentType.includes('application/json')) {
            console.log('⚠️ Received non-JSON response, refreshing token...');
            await refreshCSRFToken();
            if (retryFunction) {
                return retryFunction();
            }
        }
        
        return response;
    }
    
    // Enhanced fetch wrapper that handles auth errors
    async function fetchWithAuth(url, options = {}) {
        const defaultOptions = {
            ...options,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                'Accept': 'application/json',
                ...options.headers
            },
            credentials: 'same-origin'
        };
        
        try {
            let response = await fetch(url, defaultOptions);
            
            // Handle auth errors
            if (response.status === 401 || response.status === 419) {
                // Remove abort signal for retry (token refresh might take time)
                const retryOptions = { ...defaultOptions };
                if (retryOptions.signal) {
                    delete retryOptions.signal;
                }
                
                response = await handleAuthError(response, () => {
                    // Retry with fresh token (without abort signal)
                    retryOptions.headers['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
                    return fetch(url, retryOptions);
                });
            }
            
            // Check if response is JSON
            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
                // Likely got HTML error page, try to refresh and retry once
                if (!sessionExpired) {
                    await refreshCSRFToken();
                    // Remove abort signal for retry
                    const retryOptions = { ...defaultOptions };
                    if (retryOptions.signal) {
                        delete retryOptions.signal;
                    }
                    retryOptions.headers['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
                    response = await fetch(url, retryOptions);
                }
            }
            
            return response;
        } catch (error) {
            // Don't log AbortError as it's expected for timeouts
            if (error.name !== 'AbortError') {
                console.error('Fetch error:', error);
            }
            throw error;
        }
    }

    // Expose postLocation globally so manual button can call it
    function postLocation(lat, lng, attempt = 1){
        // Only use abort controller for first attempt, not retries (retries might need more time for token refresh)
        const controller = attempt === 1 ? new AbortController() : null;
        const timeoutId = controller ? setTimeout(() => controller.abort(), 8000) : null; // 8s timeout (increased for slow connections)
        
        const fetchOptions = {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ id: ambulanceId, latitude: lat, longitude: lng }),
            keepalive: true
        };
        
        // Only add signal for first attempt
        if (controller) {
            fetchOptions.signal = controller.signal;
        }
        
        return fetchWithAuth("{{ route('update.location') }}", fetchOptions)
        .then(res => {
            if (!res.ok && (res.status === 401 || res.status === 419)) {
                throw new Error('Session expired');
            }
            return res.json();
        })
        .then(async (data) => {
            lastSuccessfulSendAt = Date.now();
            consecutiveSendFailures = 0;
            setStatus(`✅ GPS sent at ${new Date().toLocaleTimeString()}`, '#00ffe7');
            showRetryModal(false);
            
            // Store current location for geofence checking
            currentLocation = { lat, lng };
            
            // Check geofence proximity and update circles
            updateDriverGeofenceCircles(lat, lng);
            
            // Check if geofence alert was triggered
            if (data && data.geofence_alert) {
                const locationType = data.geofence_alert.location_type || 'destination';
                const locationLabel = locationType === 'pickup' ? 'pickup location' : 'destination';
                showToast(`You have reached the ${locationLabel} for Case #${data.geofence_alert.case_num}! Admin will be notified.`, 'geofence', null, 5000);
            }
            
            // Admin-forced logout signal
            if (data && data.must_logout) {
                try { clearInterval(sendIntervalId); } catch(e) {}
                try { showToast('You have been logged out by the admin.', 'error'); } catch(e) {}
                setTimeout(() => { window.location.href = '/driver/login'; }, 1500);
                return;
            }
            
            // Check if there's a pending resend request and mark it as completed
            try {
                const resendCheck = await fetchWithAuth('/driver/gps/resend-check');
                if (resendCheck.ok) {
                    const resendData = await resendCheck.json();
                    if (resendData.exists && resendData.data && resendData.data.status === 'acknowledged') {
                        // Mark request as completed
                        await fetchWithAuth('/driver/gps/resend-complete', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            }
                        });
                    }
                }
            } catch (e) {
                // Ignore errors - not critical
            }
        })
        .catch(async (err) => {
            // Clear timeout if it exists
            if (timeoutId) clearTimeout(timeoutId);
            
            // Handle AbortError (timeout) - allow retry
            if (err.name === 'AbortError') {
                console.log(`⏱️ Request timeout (attempt ${attempt}), retrying...`);
                consecutiveSendFailures += 1;
                if (attempt < 3) {
                    // Wait a bit longer for retries
                    return new Promise((resolve) => setTimeout(resolve, attempt * 1500))
                        .then(() => postLocation(lat, lng, attempt + 1));
                }
                setStatus('⚠️ Request timeout. Please check your connection.', '#ff7e5f');
                if (consecutiveSendFailures >= 2) showRetryModal(true);
                return;
            }
            
            // If session expired, try to refresh token and retry
            if (err.message === 'Session expired' || (err.name === 'TypeError' && err.message.includes('JSON'))) {
                if (attempt <= 2) {
                    console.log('🔄 Session expired, refreshing token and retrying...');
                    try {
                        await refreshCSRFToken();
                        // Retry with fresh token (no abort signal for retries)
                        return postLocation(lat, lng, attempt + 1);
                    } catch (refreshError) {
                        console.error('Failed to refresh token:', refreshError);
                        // If refresh fails, redirect to login
                        showToast('Session expired. Please login again.', 'error', null, 3000);
                        setTimeout(() => {
                            window.location.href = '/driver/login';
                        }, 2000);
                        return;
                    }
                }
            }
            
            consecutiveSendFailures += 1;
            if (!navigator.onLine) {
                setStatus('⚠️ Offline. Trying to reconnect...', '#ff7e5f');
                showRetryModal(true);
            } else if (sessionExpired) {
                setStatus('⚠️ Session expired. Refreshing...', '#ffb020');
                // Token refresh will be handled by the retry above
            } else {
                setStatus(`⚠️ Send failed${attempt ? ` (attempt ${attempt})` : ''}. Retrying...`, '#ffb020');
                if (consecutiveSendFailures >= 2) showRetryModal(true);
            }
            
            if (attempt < 3) {
                return new Promise((resolve) => setTimeout(resolve, attempt * 1500))
                    .then(() => postLocation(lat, lng, attempt + 1));
            } else {
                // After 3 attempts, show retry modal
                showRetryModal(true);
            }
        });
    }

    // Visibility banner helpers
    let visibilityBanner = null;
    function ensureVisibilityBanner(){
        if (visibilityBanner) return visibilityBanner;
        visibilityBanner = document.createElement('div');
        visibilityBanner.style.cssText = 'display:none; position:fixed; top:0; left:0; right:0; z-index:3000; background:#f59e0b; color:#111827; font-weight:800; padding:10px 14px; text-align:center; box-shadow:0 2px 8px rgba(0,0,0,0.2)';
        visibilityBanner.textContent = 'For reliable tracking, keep this page in the foreground or screen awake.';
        document.body.appendChild(visibilityBanner);
        return visibilityBanner;
    }
    function showVisibilityBanner(show){ ensureVisibilityBanner(); visibilityBanner.style.display = show ? 'block' : 'none'; }

    function setStatus(text, color = '#00ffe7') {
        statusEl.innerHTML = `<span class='status-glow' style='background:${color};box-shadow:0 0 12px 3px ${color}cc,0 0 32px 8px ${color}33;'></span> ${text}`;
    }

    function startTracking() {
        if (tracking) return;
        tracking = true;
        trackingStartedAt = Date.now();
        setStatus("📡 Sending GPS every 5 seconds...", '#00ffe7');
        try { showToast('Location sharing started', 'success', null, 3000, '📍 Location Sharing'); } catch(e) {}
        try { updateTrackingIndicator(true); } catch(e) {}
        // Try to enable wake lock by default (best-effort)
        (async () => { 
            if ('wakeLock' in navigator) { 
                wakeLockDesired = true; // User/system wants it ON
                const ok = await requestWakeLock(); 
                wakeEnabled = !!ok; 
                updateWakeBtn(); 
            } 
        })();

        // Periodic sender
        sendIntervalId = setInterval(() => {
            navigator.geolocation.getCurrentPosition(position => {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;
                lastCoords = { lat, lng };
                // Update backend with current position
                postLocation(lat, lng)
                .then(() => {
                    // Show current position on map
                    if (!currentMarker) {
                        // Create ambulance icon to distinguish from case markers
                        const ambulanceIcon = L.icon({
                            iconUrl: 'https://cdn-icons-png.flaticon.com/512/3448/3448339.png', // Ambulance icon
                            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
                            iconSize: [30, 30],
                            iconAnchor: [15, 15],
                            popupAnchor: [0, -15],
                            shadowSize: [41, 41]
                        });
                        currentMarker = L.marker([lat, lng], { icon: ambulanceIcon }).addTo(map).bindPopup("🚑 You are here").openPopup();
                        map.setView([lat, lng], 15);
                    } else {
                        currentMarker.setLatLng([lat, lng]);
                    }
                    // On first fix, load cases to ensure pins appear
                    if (!pinsInitialized) {
                        pinsInitialized = true;
                        loadAllCases();
                        loadCaseNotifications();
                    }
                    // Note: Case notifications are now refreshed manually via buttons
                    // If routing is active, update current location waypoint
                    if (routeControl) {
                        try {
                            const wps = routeControl.getWaypoints();
                            wps[0] = L.Routing.waypoint(L.latLng(lat, lng));
                            routeControl.setWaypoints(wps);
                        } catch (e) {}
                    }
                });
            }, error => {
                setStatus("❌ GPS Error: " + error.message, '#ff7e5f');
            });
        }, 5000);

        // React to connectivity changes
        window.addEventListener('offline', () => {
                setStatus('⚠️ Offline. Trying to reconnect...', '#ff7e5f');
                showRetryModal(true);
        });
        window.addEventListener('online', () => {
            setStatus('✅ Back online. Syncing...', '#00ffe7');
            showRetryModal(false);
            if (lastCoords) {
                postLocation(lastCoords.lat, lastCoords.lng, 1);
            }
        });


        // Wake lock: re-acquire on visibility change when returning to the page (if user wants it ON)
        document.addEventListener('visibilitychange', async () => {
            if (document.visibilityState === 'visible' && wakeLockDesired) {
                // User wants wake lock ON, re-acquire if it was auto-released
                if (!wakeLock || !wakeEnabled) {
                    const ok = await requestWakeLock();
                    wakeEnabled = !!ok;
                    updateWakeBtn();
                }
            }
            if (document.visibilityState === 'hidden') {
                setTimeout(() => { if (document.visibilityState === 'hidden') showVisibilityBanner(true); }, 1500);
            } else {
                showVisibilityBanner(false);
            }
        });
        // Don't release on pagehide - we want to keep trying to re-acquire if user wants it ON

        // Watchdog: if no successful send for >15s, show reconnecting; >60s, warn offline
        setInterval(() => {
            const now = Date.now();
            const delta = now - (lastSuccessfulSendAt || 0);
            const sinceStart = now - (trackingStartedAt || now);
            if (lastSuccessfulSendAt && delta > 60000) {
                setStatus('🚫 No GPS sync for 1+ minute. Reconnecting...', '#ff7e5f');
                showRetryModal(true);
            } else if (lastSuccessfulSendAt && delta > 15000) {
                setStatus('⏳ Sync delayed... attempting to reconnect', '#ffb020');
                showRetryModal(true);
            } else if (!lastSuccessfulSendAt && sinceStart > 30000) {
                setStatus('⏳ Still connecting to GPS service...', '#ffb020');
            }
        }, 5000);
        
        // Check for GPS resend requests from admin
        checkResendRequest();
        setInterval(checkResendRequest, 5000); // Check every 5 seconds
    }

    // Auto-start location sharing on page load
    document.addEventListener('DOMContentLoaded', () => {
        try { startTracking(); } catch (e) {}
    // remove unused nav/burger button if present
    try { const b = document.querySelector('.burger-btn'); if (b) b.style.display = 'none'; } catch(e) {}
    // create toast container once
    if (!document.getElementById('toastContainer')) {
        const tc = document.createElement('div');
        tc.id = 'toastContainer';
        tc.style.cssText = 'position:fixed;left:50%;bottom:24px;transform:translateX(-50%);z-index:3000;display:flex;flex-direction:column;gap:10px;align-items:center;';
        document.body.appendChild(tc);
    }
    
    // Load ambulance name on page load
    loadAmbulanceName();
    // Cases are loaded when map is ready (see map.whenReady above)
    });

// Function to load and display ambulance name
async function loadAmbulanceName() {
    if (!ambulanceId) {
        const nameDisplay = document.getElementById('ambulance-name-display');
        if (nameDisplay) {
            nameDisplay.textContent = 'AMBULANCE: Not Assigned';
        }
        return;
    }
    
    try {
        // Fetch ambulance data from GPS endpoint
        const response = await fetchWithAuth("{{ route('admin.gps.data') }}");
        if (response.ok) {
            const ambulances = await response.json();
            const ambulance = ambulances.find(a => a.id === ambulanceId);
            const nameDisplay = document.getElementById('ambulance-name-display');
            if (nameDisplay) {
                if (ambulance && ambulance.name) {
                    nameDisplay.textContent = `AMBULANCE: ${ambulance.name.toUpperCase()}`;
                } else {
                    nameDisplay.textContent = `AMBULANCE ID: ${ambulanceId}`;
                }
            }
        } else {
            // Fallback: show ID if fetch fails
            const nameDisplay = document.getElementById('ambulance-name-display');
            if (nameDisplay) {
                nameDisplay.textContent = `AMBULANCE ID: ${ambulanceId}`;
            }
        }
    } catch (error) {
        console.error('Error loading ambulance name:', error);
        // Fallback: show ID on error
        const nameDisplay = document.getElementById('ambulance-name-display');
        if (nameDisplay) {
            nameDisplay.textContent = `AMBULANCE ID: ${ambulanceId}`;
        }
    }
}

function updateTrackingIndicator(isOn){
    const el = document.getElementById('tracking-settings-indicator');
    if (!el) return;
    if (isOn){
        el.textContent = 'Location sharing: ON';
        el.style.background = '#065f46';
        el.style.color = '#d1fae5';
    } else {
        el.textContent = 'Location sharing: OFF';
        el.style.background = '#111827';
        el.style.color = '#9ca3af';
    }
}
    
    // ===== SOUND NOTIFICATION SYSTEM =====
    let lastResendRequestTime = 0; // Track when we last played sound for resend request
    let lastCaseSoundTime = 0; // Track when we last played sound for new case
    
    function playNotificationSound(type = 'default') {
        try {
            const audioContext = new (window.AudioContext || window.webkitAudioContext)();
            
            let frequency, duration, pattern, volume;
            
            if (type === 'case') {
                // Urgent notification sound for new case (3-5 seconds, loud repeating pattern)
                frequency = [440, 554]; // A4 and C#5
                duration = [800, 800]; // Longer duration - 800ms each tone
                pattern = 'ascending-repeat'; // Repeat pattern for 3-5 seconds
                volume = 0.7; // Louder - 70% volume
            } else if (type === 'gps-resend') {
                // Attention sound for GPS resend request (3-5 seconds, repeating beeps)
                frequency = [523]; // C5
                duration = [600]; // Longer - 600ms each beep
                pattern = 'repeating'; // Repeat multiple times
                volume = 0.65; // Louder - 65% volume
            } else {
                // Default single beep
                frequency = [440];
                duration = [1000];
                pattern = 'single';
                volume = 0.6;
            }
            
            if (pattern === 'ascending-repeat' && frequency.length === 2) {
                // Play repeating ascending pattern for 4-5 seconds
                const totalDuration = 4500; // 4.5 seconds total
                const cycleDuration = duration[0] + duration[1] + 400; // Time for one complete cycle (tone1 + tone2 + gap)
                const numCycles = Math.floor(totalDuration / cycleDuration); // How many full cycles
                
                for (let cycle = 0; cycle < numCycles; cycle++) {
                    const cycleStart = cycle * cycleDuration;
                    [0, 1].forEach((index, i) => {
                        const toneStart = cycleStart + (index * (duration[index] + 200));
                        setTimeout(() => {
                            const oscillator = audioContext.createOscillator();
                            const gainNode = audioContext.createGain();
                            
                            oscillator.connect(gainNode);
                            gainNode.connect(audioContext.destination);
                            
                            oscillator.frequency.value = frequency[index];
                            oscillator.type = 'sine';
                            
                            gainNode.gain.setValueAtTime(0, audioContext.currentTime);
                            gainNode.gain.linearRampToValueAtTime(volume, audioContext.currentTime + 0.05);
                            gainNode.gain.linearRampToValueAtTime(volume * 0.8, audioContext.currentTime + duration[index] / 1000 * 0.7);
                            gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + duration[index] / 1000);
                            
                            oscillator.start(audioContext.currentTime);
                            oscillator.stop(audioContext.currentTime + duration[index] / 1000);
                        }, toneStart);
                    });
                }
            } else if (pattern === 'repeating') {
                // Play repeating beeps for 3-4 seconds
                const totalDuration = 3500; // 3.5 seconds total
                const beepInterval = duration[0] + 200; // Beep + gap
                const numBeeps = Math.floor(totalDuration / beepInterval); // How many beeps
                
                for (let i = 0; i < numBeeps; i++) {
                    setTimeout(() => {
                        const oscillator = audioContext.createOscillator();
                        const gainNode = audioContext.createGain();
                        
                        oscillator.connect(gainNode);
                        gainNode.connect(audioContext.destination);
                        
                        oscillator.frequency.value = frequency[0];
                        oscillator.type = 'sine';
                        
                        gainNode.gain.setValueAtTime(0, audioContext.currentTime);
                        gainNode.gain.linearRampToValueAtTime(volume, audioContext.currentTime + 0.05);
                        gainNode.gain.linearRampToValueAtTime(volume * 0.85, audioContext.currentTime + duration[0] / 1000 * 0.7);
                        gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + duration[0] / 1000);
                        
                        oscillator.start(audioContext.currentTime);
                        oscillator.stop(audioContext.currentTime + duration[0] / 1000);
                    }, i * beepInterval);
                }
            } else if (pattern === 'ascending' && frequency.length === 2) {
                // Play two ascending tones (longer and louder) - fallback pattern
                [0, 1].forEach((index, i) => {
                    setTimeout(() => {
                        const oscillator = audioContext.createOscillator();
                        const gainNode = audioContext.createGain();
                        
                        oscillator.connect(gainNode);
                        gainNode.connect(audioContext.destination);
                        
                        oscillator.frequency.value = frequency[index];
                        oscillator.type = 'sine';
                        
                        gainNode.gain.setValueAtTime(0, audioContext.currentTime);
                        gainNode.gain.linearRampToValueAtTime(volume, audioContext.currentTime + 0.05);
                        gainNode.gain.linearRampToValueAtTime(volume * 0.8, audioContext.currentTime + duration[index] / 1000 * 0.7);
                        gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + duration[index] / 1000);
                        
                        oscillator.start(audioContext.currentTime);
                        oscillator.stop(audioContext.currentTime + duration[index] / 1000);
                    }, i * 300);
                });
            } else if (pattern === 'triple') {
                // Play three longer, louder beeps - fallback pattern
                [0, 1, 2].forEach((i) => {
                    setTimeout(() => {
                        const oscillator = audioContext.createOscillator();
                        const gainNode = audioContext.createGain();
                        
                        oscillator.connect(gainNode);
                        gainNode.connect(audioContext.destination);
                        
                        oscillator.frequency.value = frequency[0];
                        oscillator.type = 'sine';
                        
                        gainNode.gain.setValueAtTime(0, audioContext.currentTime);
                        gainNode.gain.linearRampToValueAtTime(volume, audioContext.currentTime + 0.05);
                        gainNode.gain.linearRampToValueAtTime(volume * 0.85, audioContext.currentTime + duration[0] / 1000 * 0.7);
                        gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + duration[0] / 1000);
                        
                        oscillator.start(audioContext.currentTime);
                        oscillator.stop(audioContext.currentTime + duration[0] / 1000);
                    }, i * 250);
                });
            } else {
                // Single longer, louder beep
                const oscillator = audioContext.createOscillator();
                const gainNode = audioContext.createGain();
                
                oscillator.connect(gainNode);
                gainNode.connect(audioContext.destination);
                
                oscillator.frequency.value = frequency[0];
                oscillator.type = 'sine';
                
                gainNode.gain.setValueAtTime(0, audioContext.currentTime);
                gainNode.gain.linearRampToValueAtTime(volume, audioContext.currentTime + 0.05);
                gainNode.gain.linearRampToValueAtTime(volume * 0.8, audioContext.currentTime + duration[0] / 1000 * 0.7);
                gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + duration[0] / 1000);
                
                oscillator.start(audioContext.currentTime);
                oscillator.stop(audioContext.currentTime + duration[0] / 1000);
            }
        } catch (e) {
            console.warn('Could not play notification sound:', e);
            // Fallback: try HTML5 Audio API with data URI (silent if fails)
        }
    }
    
    // ===== GPS RESEND REQUEST SYSTEM =====
    let resendRequestModal = null;
    let pendingResendRequest = null;
    let resendAutoRetryInterval = null;
    
    function ensureResendRequestModal() {
        if (resendRequestModal) return resendRequestModal;
        resendRequestModal = document.createElement('div');
        resendRequestModal.id = 'resend-request-modal';
        resendRequestModal.style.cssText = 'display:none; position:fixed; inset:0; z-index:4000;';
        resendRequestModal.innerHTML = `
            <div style="position:absolute; inset:0; background:rgba(0,0,0,0.65);"></div>
            <div style="position:relative; max-width:480px; width:92%; margin:15vh auto; background:#0b2a5a; border-radius:16px; box-shadow:0 10px 40px rgba(0,0,0,0.4); padding:24px; border:2px solid #3b82f6;">
                <div style="text-align:center; margin-bottom:16px;">
                    <div style="font-size:48px; margin-bottom:12px;">📡</div>
                    <h3 style="margin:0 0 8px; color:#fff; font-weight:800; font-size:20px;">Admin Request</h3>
                    <p style="margin:0 0 20px; color:#d1d5db; font-size:14px;">Admin is requesting you to resend your GPS location.</p>
                </div>
                <div style="display:flex; flex-direction:column; gap:12px;">
                    <button id="resend-location-btn" style="background:#3b82f6; color:#fff; border:none; padding:12px 20px; border-radius:10px; font-weight:800; font-size:15px; cursor:pointer; transition:background 0.2s;">
                        Resend Location Now
                    </button>
                    <p style="margin:0; color:#9ca3af; font-size:12px; text-align:center;">Auto-retrying in background...</p>
                </div>
            </div>
        `;
        document.body.appendChild(resendRequestModal);
        
        // Resend button handler
        resendRequestModal.querySelector('#resend-location-btn').onclick = async () => {
            const btn = resendRequestModal.querySelector('#resend-location-btn');
            btn.setAttribute('disabled', 'true');
            btn.textContent = 'Sending...';
            
            try {
                if (lastCoords) {
                    await postLocation(lastCoords.lat, lastCoords.lng, 1);
                    setStatus(`✅ Location resent at ${new Date().toLocaleTimeString()}`, '#00ffe7');
                    showResendRequestModal(false);
                } else {
                    navigator.geolocation.getCurrentPosition(pos => {
                        postLocation(pos.coords.latitude, pos.coords.longitude, 1).then(() => {
                            setStatus(`✅ Location resent at ${new Date().toLocaleTimeString()}`, '#00ffe7');
                            showResendRequestModal(false);
                        });
                    }, err => {
                        console.error('GPS error:', err);
                        setStatus('❌ Failed to get GPS location', '#ff7e5f');
                        btn.removeAttribute('disabled');
                        btn.textContent = 'Resend Location Now';
                    });
                }
            } catch (e) {
                console.error('Failed to resend location:', e);
                btn.removeAttribute('disabled');
                btn.textContent = 'Resend Location Now';
            }
        };
        
        return resendRequestModal;
    }
    
    function showResendRequestModal(show) {
        ensureResendRequestModal();
        resendRequestModal.style.display = show ? 'block' : 'none';
        
        if (show && pendingResendRequest) {
            // Start auto-retry when modal is shown
            startResendAutoRetry();
        } else {
            // Stop auto-retry when modal is hidden
            stopResendAutoRetry();
        }
    }
    
    // Auto-retry sending location every 5 seconds when resend request is active
    function startResendAutoRetry() {
        if (resendAutoRetryInterval) return; // Already running
        
        resendAutoRetryInterval = setInterval(async () => {
            if (!pendingResendRequest) {
                stopResendAutoRetry();
                return;
            }
            
            try {
                if (lastCoords) {
                    await postLocation(lastCoords.lat, lastCoords.lng, 1);
                    console.log('✅ Auto-retry: Location sent successfully');
                } else {
                    navigator.geolocation.getCurrentPosition(pos => {
                        postLocation(pos.coords.latitude, pos.coords.longitude, 1).catch(err => {
                            console.warn('Auto-retry failed:', err);
                        });
                    }, err => {
                        console.warn('Auto-retry GPS error:', err);
                    });
                }
            } catch (e) {
                console.warn('Auto-retry error:', e);
            }
        }, 5000); // Retry every 5 seconds
    }
    
    function stopResendAutoRetry() {
        if (resendAutoRetryInterval) {
            clearInterval(resendAutoRetryInterval);
            resendAutoRetryInterval = null;
        }
    }
    
    // Check for GPS resend request from admin
    async function checkResendRequest() {
        try {
            const response = await fetchWithAuth('/driver/gps/resend-check');
            if (!response.ok) {
                if (response.status === 401 || response.status === 419) {
                    return; // Session expired, will be handled by token refresh
                }
                return;
            }
            const result = await response.json();
            
            if (result.success && result.exists && result.data) {
                const requestData = result.data;
                
                // Only show modal if status is 'pending' (first time we see it)
                if (requestData.status === 'pending') {
                    pendingResendRequest = requestData;
                    
                    // Acknowledge the request
                    try {
                        await fetchWithAuth('/driver/gps/resend-acknowledge', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            }
                        });
                        
                        // Show modal and start auto-retry
                        showResendRequestModal(true);
                        // Play sound notification (only once per request, avoid duplicates)
                        const now = Date.now();
                        if (now - lastResendRequestTime > 2000) { // Prevent duplicate sounds within 2 seconds
                            try {
                                playNotificationSound('gps-resend');
                                lastResendRequestTime = now;
                            } catch (e) {
                                console.warn('Could not play resend request sound:', e);
                            }
                        }
                        console.log('📡 GPS resend request received from admin');
                    } catch (e) {
                        console.error('Failed to acknowledge resend request:', e);
                    }
                } else if (requestData.status === 'acknowledged') {
                    // Request acknowledged but modal not shown yet - show it
                    if (!resendRequestModal || resendRequestModal.style.display === 'none') {
                        pendingResendRequest = requestData;
                        showResendRequestModal(true);
                        // Play sound if not already played recently
                        const now = Date.now();
                        if (now - lastResendRequestTime > 2000) {
                            try {
                                playNotificationSound('gps-resend');
                                lastResendRequestTime = now;
                            } catch (e) {
                                console.warn('Could not play resend request sound:', e);
                            }
                        }
                    }
                } else if (requestData.status === 'completed') {
                    // Request completed - hide modal
                    pendingResendRequest = null;
                    showResendRequestModal(false);
                }
            } else {
                // No pending request - hide modal if open
                if (pendingResendRequest) {
                    pendingResendRequest = null;
                    showResendRequestModal(false);
                }
            }
        } catch (e) {
            console.error('Error checking resend request:', e);
        }
    }

    // Removed old assignment refresh function - replaced with case notifications

    // Removed old renderStops function - replaced with case notification system

    function ensureRoutingControl() {
        if (!routeControl) {
                        routeControl = L.Routing.control({
                waypoints: [],
                routeWhileDragging: false,
                show: true,
                        }).addTo(map);
            setTimeout(() => { makeRoutingPanelDraggable(); }, 400);
        }
    }

    function navigateToStop(lat, lng) {
        ensureRoutingControl();
        navigationActive = true;
        document.getElementById('stop-navigation')?.style && (document.getElementById('stop-navigation').style.display = 'inline-block');
        let cur = null;
        if (currentMarker) {
            const p = currentMarker.getLatLng();
            cur = [p.lat, p.lng];
        } else {
            cur = map.getCenter();
            cur = [cur.lat, cur.lng];
        }
        try {
            routeControl.setWaypoints([
                L.latLng(cur[0], cur[1]),
                L.latLng(lat, lng)
            ]);
        } catch (e) {}
    }

    function stopNavigation() {
        navigationActive = false;
        currentNavigationType = null;
        currentNavigationCase = null;
        if (routeControl) {
            try { routeControl.setWaypoints([]); } catch (e) {}
        }
        
        // Clear destination from database so dashboard stops showing trail
        if (ambulanceId) {
            fetchWithAuth(`/admin/ambulances/${ambulanceId}/clear-destination`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                }
            }).catch(err => {
                console.error('Error clearing destination:', err);
            });
        }
        
        const btn = document.getElementById('stop-navigation');
        if (btn) btn.style.display = 'none';
        const btnMobile = document.getElementById('stop-navigation-mobile');
        if (btnMobile) btnMobile.style.display = 'none';
    }

    // Removed old assignment functions - replaced with case notification system
    
    function makeRoutingPanelDraggable() {
        const routingContainer = document.querySelector('.leaflet-routing-container');
        if (!routingContainer) return;
        
        // Check if we're on mobile (screen width <= 600px)
        const isMobile = window.innerWidth <= 600;
        
        // Don't make draggable on mobile - it should stay below the map
        if (isMobile) {
            routingContainer.style.cursor = 'default';
            return;
        }
        
        let isDragging = false;
        let startX, startY, startLeft, startTop;
        
        routingContainer.addEventListener('mousedown', function(e) {
            if (e.target.tagName === 'H3' || e.target.closest('h3')) return; // Don't drag when clicking on route items
            isDragging = true;
            startX = e.clientX;
            startY = e.clientY;
            startLeft = parseInt(routingContainer.style.left) || 0;
            startTop = parseInt(routingContainer.style.top) || 0;
            routingContainer.style.cursor = 'grabbing';
            e.preventDefault();
        });
        
        document.addEventListener('mousemove', function(e) {
            if (!isDragging) return;
            
            const deltaX = e.clientX - startX;
            const deltaY = e.clientY - startY;
            
            routingContainer.style.left = (startLeft + deltaX) + 'px';
            routingContainer.style.top = (startTop + deltaY) + 'px';
            routingContainer.style.position = 'absolute';
            routingContainer.style.zIndex = '1000';
        });
        
        document.addEventListener('mouseup', function() {
            if (isDragging) {
                isDragging = false;
                routingContainer.style.cursor = 'move';
            }
        });
        
        // Touch events for mobile (only for desktop touch devices)
        routingContainer.addEventListener('touchstart', function(e) {
            if (isMobile) return; // Don't handle touch on mobile
            if (e.target.tagName === 'H3' || e.target.closest('h3')) return;
            isDragging = true;
            startX = e.touches[0].clientX;
            startY = e.touches[0].clientY;
            startLeft = parseInt(routingContainer.style.left) || 0;
            startTop = parseInt(routingContainer.style.top) || 0;
            e.preventDefault();
        });
        
        document.addEventListener('touchmove', function(e) {
            if (isMobile || !isDragging) return;
            
            const deltaX = e.touches[0].clientX - startX;
            const deltaY = e.touches[0].clientY - startY;
            
            routingContainer.style.left = (startLeft + deltaX) + 'px';
            routingContainer.style.top = (startTop + deltaY) + 'px';
            routingContainer.style.position = 'absolute';
            routingContainer.style.zIndex = '1000';
            e.preventDefault();
        });
        
        document.addEventListener('touchend', function() {
            if (isDragging) {
                isDragging = false;
            }
        });
    }

    function markArrived() {
        fetchWithAuth(`/driver/ambulance/${ambulanceId}/arrived`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            alert("🎉 Marked as arrived!");
            setStatus("Status: ✅ Available", '#00ffb8');
            if (routeControl) {
                routeControl.setWaypoints([]); // clear route
                map.removeControl(routeControl);
                routeControl = null;
            }
        })
        .catch(err => {
            setStatus("❌ Failed to mark as arrived", '#ff7e5f');
        });
    }

    window.addEventListener('resize', () => {
    map.invalidateSize();
});

// Mobile UI setup
function configureMobileUI() {
    const bottomBar = document.getElementById('bottom-action-bar');
    if (bottomBar) bottomBar.style.display = 'block';
    // Ensure content is never hidden behind the action bar
    document.body.style.paddingBottom = '76px';
}
configureMobileUI();
window.addEventListener('resize', configureMobileUI);

function toggleGlassCard() {
    const card = document.querySelector('.glass-card');
    const burger = document.querySelector('.burger-btn');
    const isHidden = card.style.display === 'none' || card.style.display === '';
    card.style.display = isHidden ? 'block' : 'none';
    card.classList.toggle('open', isHidden);
    // Move burger inside the glass when opened, back to header when closed
    if (isHidden) {
        // place inside card
        card.appendChild(burger);
        burger.style.top = '10px';
        burger.style.left = '12px';
        burger.style.position = 'absolute';
    } else {
        // return to header
        document.querySelector('.top-header').appendChild(burger);
        burger.style.top = '1px';
        burger.style.left = '1px';
        burger.style.position = 'absolute';
    }
}

// Modal controls
document.getElementById('open-cases-modal-mobile')?.addEventListener('click', () => {
    // Close notifications if open, then open cases
    const notif = document.getElementById('notifications-modal');
    if (notif) notif.style.display = 'none';
    document.getElementById('cases-modal').style.display = 'block';
    // Cases are already loaded on page load, just show the modal
});
document.getElementById('close-cases-modal')?.addEventListener('click', () => {
    document.getElementById('cases-modal').style.display = 'none';
});
document.getElementById('refresh-cases-btn')?.addEventListener('click', () => {
    loadAllCases(); // Refresh all cases
    showNotification('Cases refreshed!', 'info');
});

// Notifications modal controls
document.getElementById('open-notifications-modal-mobile')?.addEventListener('click', () => {
    // Close cases if open, then open notifications
    const cases = document.getElementById('cases-modal');
    if (cases) cases.style.display = 'none';
    document.getElementById('notifications-modal').style.display = 'block';
    loadPendingNotifications();
});
document.getElementById('close-notifications-modal')?.addEventListener('click', () => {
    document.getElementById('notifications-modal').style.display = 'none';
});
document.getElementById('refresh-notifications-modal-btn')?.addEventListener('click', () => {
    loadPendingNotifications(); // Refresh notifications
    showNotification('Notifications refreshed!', 'info');
});
document.getElementById('refresh-notifications-btn')?.addEventListener('click', () => {
    loadCaseNotifications(); // Refresh notifications
    showNotification('Notifications refreshed!', 'info');
});
document.getElementById('stop-navigation-mobile')?.addEventListener('click', stopNavigation);

// Case notification functions
let caseMarkers = {};
let geofenceCircles = {}; // Store geofence circles for case destinations
const GEOFENCE_RADIUS = 100; // 100 meters radius
let currentLocation = null; // Store driver's current location
let acceptedCases = new Set();
let knownCaseNums = new Set();
let alertQueue = [];
let alertVisible = false;
const PAGE_REFRESH_INTERVAL_MS = 60000; // Auto refresh every 60 seconds

function isCaseCompleted(caseData) {
    if (!caseData) return false;
    const statusSources = [
        caseData.status,
        caseData.case_status,
        caseData.driver_status
    ];
    return statusSources.some(status => {
        if (!status) return false;
        const normalized = String(status).toLowerCase();
        return normalized.includes('completed') || normalized.includes('complete') || normalized.includes('done');
    });
}

async function loadCaseNotifications() {
    try {
        const response = await fetchWithAuth('/driver/cases/notifications', {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        
        if (response.ok) {
            const cases = await response.json();
            
            // Filter out completed cases
            const activeCases = cases.filter(c => !isCaseCompleted(c));

            // Trigger toast for new cases
            const incomingNums = new Set(activeCases.map(c => c.case_num));
            activeCases.forEach(c => {
                if (!knownCaseNums.has(c.case_num) && !c.driver_accepted && c.status !== 'Rejected') {
                    showToast(`New case #${c.case_num}: ${c.name}`, 'info', () => {
                        document.getElementById('cases-modal').style.display = 'block';
                        loadAllCases();
                    });
                    enqueueNewCaseAlert(c);
                }
            });
            knownCaseNums = incomingNums;

            // Maintain accepted tracking
            activeCases.forEach(caseData => {
                if (caseData.driver_accepted) {
                    acceptedCases.add(caseData.case_num);
                }
            });

            // Don't update map markers here - let loadAllCases() handle that
            // This prevents markers from being removed when notifications return different data
        }
    } catch (error) {
        console.error('Error loading case notifications:', error);
    }
}

// Function to load notifications only (for auto-refresh) - doesn't affect map or table
async function loadCaseNotificationsOnly() {
    try {
        const response = await fetchWithAuth('/driver/cases/notifications', {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        
        if (response.ok) {
            const cases = await response.json();
            
            // Filter out completed cases
            const activeCases = cases.filter(c => !isCaseCompleted(c));

            // Toast new cases (without touching map/table)
            const incomingNums = new Set(activeCases.map(c => c.case_num));
            activeCases.forEach(c => {
                if (!knownCaseNums.has(c.case_num) && !c.driver_accepted && c.status !== 'Rejected') {
                    showToast(`New case #${c.case_num}: ${c.name}`, 'info', () => {
                        document.getElementById('cases-modal').style.display = 'block';
                        loadAllCases();
                    });
                    enqueueNewCaseAlert(c);
                }
            });
            knownCaseNums = incomingNums;

            // Maintain accepted tracking
            activeCases.forEach(caseData => {
                if (caseData.driver_accepted) {
                    acceptedCases.add(caseData.case_num);
                }
            });
        }
    } catch (error) {
        console.error('Error loading case notifications:', error);
    }
}

// Function to update only the notification panel (for auto-refresh)
function updateNotificationPanelOnly(cases) {
    // Deprecated: we now use toast popups for notifications
    const notificationsDiv = document.getElementById('case-notifications');
    if (notificationsDiv) notificationsDiv.style.display = 'none';
}

async function loadAllCases() {
    try {
        const response = await fetchWithAuth('/driver/cases/all', {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        
        if (response.ok) {
            const cases = await response.json();
            console.log('All cases data:', cases); // Debug log
            
            // Filter out completed cases for UI lists only (keep full dataset for map cleanup)
            const activeCases = cases.filter(c => !isCaseCompleted(c));
            
            // Debug each case's destination data
            activeCases.forEach(caseData => {
                console.log(`All Cases - Case #${caseData.case_num}:`, {
                    pickup: caseData.address,
                    destination: caseData.to_go_to_address,
                    dest_lat: caseData.to_go_to_latitude,
                    dest_lng: caseData.to_go_to_longitude
                });
            });
            
            // Update accepted cases set based on active cases only
            activeCases.forEach(caseData => {
                if (caseData.driver_accepted) {
                    acceptedCases.add(caseData.case_num);
                }
            });
            
            displayCaseNotifications(activeCases);
            
            // Pass the full cases list so completed cases still trigger cleanup
            addCaseMarkersToMap(cases);
        }
    } catch (error) {
        console.error('Error loading all cases:', error);
    }
}

async function loadPendingNotifications() {
    try {
        const response = await fetchWithAuth('/driver/cases/notifications', {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        
        if (response.ok) {
            const cases = await response.json();
            console.log('Pending notifications data:', cases); // Debug log
            
            // Filter out completed cases
            const activeCases = cases.filter(c => !isCaseCompleted(c));
            
            // Debug each case's destination data
            activeCases.forEach(caseData => {
                console.log(`Case #${caseData.case_num}:`, {
                    pickup: caseData.address,
                    destination: caseData.to_go_to_address,
                    dest_lat: caseData.to_go_to_latitude,
                    dest_lng: caseData.to_go_to_longitude
                });
            });
            
            displayPendingNotifications(activeCases);
        }
    } catch (error) {
        console.error('Error loading pending notifications:', error);
    }
}

function displayPendingNotifications(cases) {
    const notificationsContainer = document.getElementById('notifications-container');
    
    if (!notificationsContainer) return;
    
    notificationsContainer.innerHTML = '';
    
    if (cases.length === 0) {
        notificationsContainer.innerHTML = '<div style="text-align: center; padding: 2rem; color: #6b7280;">No pending notifications.</div>';
        return;
    }
    
    cases.forEach(caseData => {
        // Only show pending (not accepted) cases
        if (acceptedCases.has(caseData.case_num)) return;
        
        const notificationItem = document.createElement('div');
        notificationItem.style.cssText = `
            background: linear-gradient(180deg, #e59a1b 0%, #dd7349 100%);
            border: 2px solid #1e3a8a;
            border-radius: 14px;
            padding: 12px;
            margin-bottom: 12px;
            box-shadow: 0 6px 14px rgba(0,0,0,0.18);
            color: #ffffff;
        `;
        
        notificationItem.innerHTML = `
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 8px;">
                <div style="font-weight: 900; font-size: 18px; letter-spacing: .02em; color: #ffffff;">CASE #${caseData.case_num}</div>
                ${caseData.type ? `<span style=\"background: #2b5db8; color: #fff; font-weight: 800; font-size: 10px; padding: 4px 10px; border-radius: 999px;\">${caseData.type}</span>` : ''}
            </div>
            <div style="margin-top: 8px; color:rgb(255, 255, 255); font-weight: 1500;">${caseData.name}</div>
            <div style="margin-top: 6px; color:rgb(255, 255, 255); font-size: 12px; display: flex; align-items: center; gap: 8px;"><strong>Contact info:</strong> ${caseData.contact} <a href="${telHref(caseData.contact)}" style="color: #dc2626; font-weight: 900; text-decoration: none;">CALL</a></div>
            <div style="margin-top: 6px; color:rgb(255, 255, 255); font-size: 12px;">
                <div><strong>Transport From:</strong> ${caseData.address}</div>
                ${caseData.to_go_to_address ? `<div><strong>Transport To:</strong> ${caseData.to_go_to_address}</div>` : `<div><strong>Transport To:</strong> —</div>`}
            </div>
            <div style="margin-top: 10px; display: flex; gap: 8px; flex-wrap: nowrap; align-items: center;">
                <button onclick="acceptCase(${caseData.case_num})" style="
                    background: #1e3a8a;
                    color: white;
                    border: none;
                    padding: 8px 12px;
                    border-radius: 6px;
                    font-size: 12px;
                    cursor: pointer;
                    font-weight: 700;
                    flex: 1;
                ">Accept</button>
                <button onclick="rejectCase(${caseData.case_num})" style="
                    background: #dc2626;
                    color: white;
                    border: none;
                    padding: 8px 12px;
                    border-radius: 6px;
                    font-size: 12px;
                    cursor: pointer;
                    font-weight: 700;
                    flex: 1;
                ">Reject</button>
            </div>
            <div style="margin-top: 8px; color: #ffffff; font-size: 10px; text-align: right;">
                Created: ${new Date(caseData.created_at).toLocaleString()}
            </div>
        `;
        
        notificationsContainer.appendChild(notificationItem);
    });
}

function displayCaseNotifications(cases) {
    const notificationsDiv = document.getElementById('case-notifications');
    const notificationsList = document.getElementById('notifications-list');
    const casesContainer = document.getElementById('cases-container');
    
    // Update the glass card notifications (only if they exist)
    if (notificationsDiv && notificationsList) {
        if (cases.length === 0) {
            notificationsDiv.style.display = 'none';
        } else {
            notificationsDiv.style.display = 'block';
        
        // Only update if there are changes
        const currentNotificationCount = notificationsList.children.length;
        const newNotificationCount = cases.filter(c => !acceptedCases.has(c.case_num)).length;
        
        if (currentNotificationCount !== newNotificationCount) {
            notificationsList.innerHTML = '';
            
            cases.forEach(caseData => {
                if (acceptedCases.has(caseData.case_num)) return; // Skip already accepted cases
                
                const notificationItem = document.createElement('div');
                notificationItem.style.cssText = `
                    background: rgba(255, 255, 255, 0.1);
                    border: 1px solid rgba(255, 255, 255, 0.2);
                    border-radius: 8px;
                    padding: 0.75rem;
                    margin-bottom: 0.5rem;
                    color: #fff;
                `;
                
                notificationItem.innerHTML = `
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 0.5rem;">
                        <div>
                            <h5 style="margin: 0 0 0.3rem 0; font-size: 0.9rem; font-weight: 600;">Case #${caseData.case_num}</h5>
                            <p style="margin: 0 0 0.3rem 0; font-size: 0.8rem; opacity: 0.9;">${caseData.name}</p>
                            <p style="margin: 0 0 0.3rem 0; color: #6b7280; font-size: 0.75rem; opacity: 0.8;">📍 Transport From: ${caseData.address}</p>
                            ${caseData.to_go_to_address ? `<p style="margin: 0 0 0.3rem 0; color: #6b7280; font-size: 0.75rem; opacity: 0.8;">🏁 Transport To: ${caseData.to_go_to_address}</p>` : ''}
                        </div>
                        <div style="display: flex; gap: 0.3rem;">
                            <button onclick="acceptCase(${caseData.case_num})" style="
                                background: #10b981; 
                                color: white; 
                                border: none; 
                                padding: 0.3rem 0.6rem; 
                                border-radius: 4px; 
                                font-size: 0.7rem; 
                                cursor: pointer;
                            ">Accept</button>
                            <button onclick="rejectCase(${caseData.case_num})" style="
                                background: #ef4444; 
                                color: white; 
                                border: none; 
                                padding: 0.3rem 0.6rem; 
                                border-radius: 4px; 
                                font-size: 0.7rem; 
                                cursor: pointer;
                            ">Reject</button>
                            <a href="${telHref(caseData.contact)}" style="
                                background: #0ea5e9;
                                color: white;
                                border: none;
                                padding: 0.3rem 0.6rem;
                                border-radius: 4px;
                                font-size: 0.7rem;
                                cursor: pointer;
                                text-decoration: none;
                            ">📞 Call</a>
                        </div>
                    </div>
                    <div style="display: flex; gap: 0.3rem; flex-wrap: wrap;">
                        <span style="background: ${getPriorityColor(caseData.priority || 'Medium')}; color: white; padding: 0.2rem 0.4rem; border-radius: 8px; font-size: 0.7rem;">
                            ${caseData.priority || 'Medium'}
                        </span>
                        ${caseData.type ? `<span style="background: #3b82f6; color: white; padding: 0.2rem 0.4rem; border-radius: 8px; font-size: 0.7rem;">${caseData.type}</span>` : ''}
                    </div>
                `;
                
                notificationsList.appendChild(notificationItem);
            });
        }
    }
    }
    
    // Update the cases modal only if it's open
    if (casesContainer && casesContainer.parentElement && casesContainer.parentElement.style.display !== 'none') {
        // Only update if there are changes
        const currentCaseCount = casesContainer.children.length;
        const newCaseCount = cases.filter(c => c.status !== 'Rejected' && acceptedCases.has(c.case_num)).length;
        
        if (currentCaseCount !== newCaseCount) {
            casesContainer.innerHTML = '';
            
            if (cases.length === 0) {
                casesContainer.innerHTML = '<div style="text-align: center; padding: 2rem; color: #6b7280;">No emergency cases assigned.</div>';
                return;
            }
            
            cases.forEach(caseData => {
                // Skip rejected cases - they should not appear in the list
                if (caseData.status === 'Rejected') return;
                
                // Emergency Cases modal should ONLY show accepted cases
                const isAccepted = acceptedCases.has(caseData.case_num);
                if (!isAccepted) return; // Skip pending cases - they belong in Notifications modal only
                
                const caseItem = document.createElement('div');
                caseItem.style.cssText = `
                    background: transparent;
                    border-radius: 0;
                    padding: 0;
                    margin-bottom: 12px;
                    box-shadow: none;
                    border: none;
                    color: #fff;
                `;
                
                caseItem.innerHTML = `
                    <div style="background: linear-gradient(180deg, #e59a1b 0%, #dd7349 100%); border-radius: 10px; padding: 12px; position: relative;">
                        <div style="display:flex; justify-content:space-between; align-items:flex-start;">
                            <div style="font-weight:900; font-size:18px; letter-spacing:.02em; color:#ffffff;">CASE #${caseData.case_num}</div>
                            ${caseData.type ? `<span style=\"background:#2b5db8; color:#fff; font-weight:800; font-size:10px; padding:4px 10px; border-radius:999px;\">${caseData.type}</span>` : ''}
                        </div>
                        <div style="margin-top:8px; color:#ffffff; font-weight:800;">${caseData.name}</div>
                        <div style="margin-top:6px; color:#ffffff; font-size:12px; display:flex; align-items:center; gap:8px;"><strong>Contact info:</strong> ${caseData.contact} <a href="${telHref(caseData.contact)}" style="color:#dc2626; font-weight:900; text-decoration:none;">CALL</a></div>
                        <div style="margin-top:6px; color:#ffffff; font-size:12px;">
                            <div><strong>Transport From:</strong> ${caseData.address}</div>
                            ${caseData.to_go_to_address ? `<div><strong>Transport To:</strong> ${caseData.to_go_to_address}</div>` : `<div><strong>Transport To:</strong> —</div>`}
                        </div>
                        <div style="margin-top: 10px; display: flex; gap: 8px; flex-wrap: nowrap; align-items: center;">
                            <button onclick="navigateToLocation(${caseData.case_num}, ${caseData.latitude}, ${caseData.longitude}, 'pickup')" class="case-primary-btn">From</button>
                        ${caseData.to_go_to_latitude && caseData.to_go_to_longitude ? `
                                <button onclick="navigateToLocation(${caseData.case_num}, ${caseData.to_go_to_latitude}, ${caseData.to_go_to_longitude}, 'destination')" class="case-primary-btn">To</button>
                        ` : ''}
                    </div>
                        <div style="margin-top:8px; color:#ffffff; font-size:10px; text-align:right;">Created: ${new Date(caseData.created_at).toLocaleString()}</div>
                    </div>
                `;
                
                casesContainer.appendChild(caseItem);
            });
        }
    }
}

// Helper function to remove all case-related elements from map
function removeCaseFromMap(caseNum) {
    // Safety check: ensure map is initialized
    if (!map || typeof map.hasLayer !== 'function') {
        console.warn('⚠️ Map not initialized, cannot remove case elements');
        return;
    }
    
    const caseStr = String(caseNum);
    
    // Remove pickup marker
    if (caseMarkers && caseMarkers[caseStr]) {
        try {
            if (map.hasLayer(caseMarkers[caseStr])) {
                map.removeLayer(caseMarkers[caseStr]);
            }
            delete caseMarkers[caseStr];
        } catch(e) {
            console.error('Error removing pickup marker:', e);
        }
    }
    
    // Remove destination marker
    if (caseMarkers && caseMarkers[`dest_${caseStr}`]) {
        try {
            if (map.hasLayer(caseMarkers[`dest_${caseStr}`])) {
                map.removeLayer(caseMarkers[`dest_${caseStr}`]);
            }
            delete caseMarkers[`dest_${caseStr}`];
        } catch(e) {
            console.error('Error removing destination marker:', e);
        }
    }
    
    // Remove connection line
    if (caseMarkers && caseMarkers[`line_${caseStr}`]) {
        try {
            if (map.hasLayer(caseMarkers[`line_${caseStr}`])) {
                map.removeLayer(caseMarkers[`line_${caseStr}`]);
            }
            delete caseMarkers[`line_${caseStr}`];
        } catch(e) {
            console.error('Error removing connection line:', e);
        }
    }
    
    // Remove pickup geofence circle
    if (geofenceCircles && geofenceCircles[`${caseStr}_pickup`]) {
        try {
            if (map.hasLayer(geofenceCircles[`${caseStr}_pickup`])) {
                map.removeLayer(geofenceCircles[`${caseStr}_pickup`]);
            }
            delete geofenceCircles[`${caseStr}_pickup`];
        } catch(e) {
            console.error('Error removing pickup geofence circle:', e);
        }
    }
    
    // Remove destination geofence circle
    if (geofenceCircles && geofenceCircles[`${caseStr}_destination`]) {
        try {
            if (map.hasLayer(geofenceCircles[`${caseStr}_destination`])) {
                map.removeLayer(geofenceCircles[`${caseStr}_destination`]);
            }
            delete geofenceCircles[`${caseStr}_destination`];
        } catch(e) {
            console.error('Error removing destination geofence circle:', e);
        }
    }

    // If driver was currently navigating to this case, stop navigation so the routing path disappears
    if (currentNavigationCase && String(currentNavigationCase) === caseStr) {
        stopNavigation();
    }
}

function addCaseMarkersToMap(cases) {
    console.log(`🗺️ addCaseMarkersToMap called with ${cases.length} cases`);
    
    // Safety check: ensure map and caseMarkers are initialized
    if (!map || typeof map.hasLayer !== 'function') {
        console.warn('⚠️ Map not initialized, cannot add case markers');
        return;
    }
    if (!caseMarkers || typeof caseMarkers !== 'object') {
        console.warn('⚠️ caseMarkers not initialized');
        return;
    }
    
    console.log('✅ Map and caseMarkers are initialized, proceeding to add markers');
    
    // Get current case numbers on map (including destination and line markers)
    const currentCaseNums = new Set();
    Object.keys(caseMarkers).forEach(key => {
        // Extract case number from keys like "123", "dest_123", "line_123"
        const match = key.match(/^dest_(\d+)$|^line_(\d+)$|^(\d+)$/);
        if (match) {
            const caseNum = match[1] || match[2] || match[3];
            if (caseNum) currentCaseNums.add(caseNum);
        }
    });
    
    const newCaseNums = new Set(cases.map(c => c.case_num.toString()));
    
    // Only remove markers for cases that are explicitly rejected or completed
    // Don't remove markers just because they're not in the current list (they might be in another API call)
    currentCaseNums.forEach(caseNum => {
        const caseData = cases.find(c => String(c.case_num) === caseNum);
        if (caseData) {
            // Only remove if case is explicitly rejected or completed
            if (caseData.status === 'Rejected' || isCaseCompleted(caseData)) {
                console.log(`🗑️ Removing Case #${caseNum} from map - status: ${caseData.status}`);
                removeCaseFromMap(caseNum);
            }
        }
        // Don't remove markers for cases not in current list - they might be in another API response
    });
    
    // Add or update markers for new cases
    cases.forEach(caseData => {
        // Skip rejected cases entirely
        if (caseData.status === 'Rejected') {
            console.log(`⏭️ Skipping Case #${caseData.case_num} - status: ${caseData.status}`);
            return;
        }

        // Force-remove completed cases to ensure no remnants
        if (isCaseCompleted(caseData)) {
            console.log(`🧹 Case #${caseData.case_num} marked completed; removing layers`);
            removeCaseFromMap(caseData.case_num);
            return;
        }
        
        console.log(`🔄 Processing Case #${caseData.case_num} - lat: ${caseData.latitude}, lng: ${caseData.longitude}`);
        
        // Check if marker already exists
        if (caseMarkers[caseData.case_num]) {
            // Update existing marker if needed
            const isAccepted = acceptedCases.has(caseData.case_num);
            const currentMarker = caseMarkers[caseData.case_num];
            
            // Update marker color if status changed
            let markerColor = 'grey'; // Pending cases (neutral)
            if (isAccepted) {
                markerColor = 'blue'; // Accepted cases
            }
            
            const caseIcon = L.icon({
                iconUrl: `https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-${markerColor}.png`,
                shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
                iconSize: [25, 41],
                iconAnchor: [12, 41],
                popupAnchor: [1, -34],
                shadowSize: [41, 41]
            });
            
            currentMarker.setIcon(caseIcon);
            
            // Update popup content (pickup - refined design like sample image)
            const popupContent = `
                <div style="position:relative; min-width: 260px; max-width: 360px; background: linear-gradient(90deg, #0b2a5a 0%, #1e3a8a 55%, #3b82f6 100%); color:#ffffff; border-radius:12px; box-shadow:0 12px 26px rgba(0,0,0,0.28); overflow:hidden; border:2px solid rgba(255,255,255,0.9);">
                    <button type="button" class="popup-close-internal" aria-label="Close" style="position:absolute; top:6px; right:6px; width:18px; height:18px; border-radius:4px; border:2px solid #ffffff; background:#1e3a8a; color:#ffffff; font-weight:900; line-height:12px; font-size:12px;">X</button>
                    <div style="padding:12px 28px 12px 14px;">
                        <div style="display:flex; justify-content:space-between; align-items:flex-start; gap:10px;">
                            <div style="font-weight:900; font-size:22px; line-height:1.1;">Case # ${caseData.case_num}</div>
                            ${caseData.type ? `<span style=\"background:#1d4ed8; color:#ffffff; font-weight:800; font-size:10px; padding:2px 8px; border-radius:4px; border:1px solid rgba(255,255,255,0.35);\">${caseData.type}</span>` : ''}
                        </div>
                        
                        <div style="margin-top:6px; font-size:12px;"><strong>NO# :</strong> ${caseData.contact || ''} <a href="${telHref(caseData.contact)}" style="color:#ef4444; font-weight:900; text-decoration:none;">CALL</a></div>
                        <div style="margin-top:8px; font-size:12px;">
                            <div><strong>Transport From :</strong> ${caseData.address || ''}</div>
                            ${caseData.to_go_to_address ? `<div style=\"margin-top:4px;\"><strong>Transport To :</strong> ${caseData.to_go_to_address}</div>` : ''}
                        </div>
                        <div style="margin-top:12px; display:flex; gap:12px;">
                            <button onclick="navigateToLocation(${caseData.case_num}, ${caseData.latitude}, ${caseData.longitude}, 'pickup')" style="background:#1e3a8a; color:#fff; border:none; padding:8px 12px; border-radius:10px; font-weight:900; cursor:pointer;">Navigate Here</button>
                            <a href="${telHref(caseData.contact)}" style="background:#f59e0b; color:#0b0b0b; border:none; padding:8px 12px; border-radius:10px; font-weight:900; text-decoration:none;">Call</a>
                        </div>
                    </div>
                </div>
            `;
            
            currentMarker.setPopupContent(popupContent);
            currentMarker.caseData = caseData;
            // Ensure close button works
            currentMarker.on('popupopen', (e) => {
                try {
                    const el = e.popup.getElement();
                    const btn = el && el.querySelector('.popup-close-internal');
                    if (btn) btn.addEventListener('click', () => currentMarker.closePopup(), { once: true });
                } catch (_) {}
            });
        } else {
            // Create new marker
            // Parse coordinates to ensure they are numbers (not strings)
            const pickupLat = parseFloat(caseData.latitude);
            const pickupLng = parseFloat(caseData.longitude);
            
            // Validate coordinates
            if (isNaN(pickupLat) || isNaN(pickupLng)) {
                console.warn(`⚠️ Invalid coordinates for Case #${caseData.case_num}:`, {
                    lat: caseData.latitude,
                    lng: caseData.longitude
                });
                return; // Skip this case if coordinates are invalid
            }
            
            const isAccepted = acceptedCases.has(caseData.case_num);
            let markerColor = 'grey'; // Pending cases (neutral)
            if (isAccepted) {
                markerColor = 'blue'; // Accepted cases
            }
            
            const caseIcon = L.icon({
                iconUrl: `https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-${markerColor}.png`,
                shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
                iconSize: [25, 41],
                iconAnchor: [12, 41],
                popupAnchor: [1, -34],
                shadowSize: [41, 41]
            });
            
            console.log(`📍 Adding marker for Case #${caseData.case_num} at [${pickupLat}, ${pickupLng}]`);
            try {
                const marker = L.marker([pickupLat, pickupLng], { icon: caseIcon })
                    .addTo(map);
                
                // Verify marker was added
                if (map.hasLayer(marker)) {
                    console.log(`✅ Marker for Case #${caseData.case_num} successfully added to map`);
                } else {
                    console.error(`❌ Marker for Case #${caseData.case_num} was not added to map`);
                }
                
                // Store case data in the marker for later use
                marker.caseData = caseData;
                
                // Add pickup label
                marker.bindTooltip(`Transport From`, {
                    permanent: true,
                    direction: 'top',
                    offset: [0, -28],
                    className: 'marker-label'
                }).openTooltip();
                
                // Add geofence circle for pickup location
                const pickupGeofenceCircle = L.circle([pickupLat, pickupLng], {
                    radius: GEOFENCE_RADIUS,
                    color: '#6b7280', // Gray when outside
                    fillColor: '#6b7280',
                    fillOpacity: 0.2,
                    weight: 2
                }).addTo(map);
                
                geofenceCircles[`${caseData.case_num}_pickup`] = pickupGeofenceCircle;
                
                // Store marker in caseMarkers object
                caseMarkers[caseData.case_num] = marker;
                
                // Add destination marker if it exists
                if (caseData.to_go_to_latitude && caseData.to_go_to_longitude) {
                // Convert string coordinates to numbers
                const destLat = parseFloat(caseData.to_go_to_latitude);
                const destLng = parseFloat(caseData.to_go_to_longitude);
                
                // Validate coordinates are valid numbers
                if (isNaN(destLat) || isNaN(destLng)) {
                    console.warn(`Invalid destination coordinates for Case #${caseData.case_num}:`, {
                        lat: caseData.to_go_to_latitude,
                        lng: caseData.to_go_to_longitude
                    });
                    return; // Skip this destination marker
                }
                
                const destIcon = L.icon({
                    iconUrl: `https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-orange.png`,
                    shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
                    iconSize: [25, 41],
                    iconAnchor: [12, 41],
                    popupAnchor: [1, -34],
                    shadowSize: [41, 41]
                });
                
                const destMarker = L.marker([destLat, destLng], { icon: destIcon })
                    .addTo(map);
                // Add destination label
                destMarker.bindTooltip(`Transport To`, {
                    permanent: true,
                    direction: 'top',
                    offset: [0, -28],
                    className: 'marker-label'
                }).openTooltip();
                
                // Add geofence circle for destination
                const destGeofenceCircle = L.circle([destLat, destLng], {
                    radius: GEOFENCE_RADIUS,
                    color: '#6b7280', // Gray when outside
                    fillColor: '#6b7280',
                    fillOpacity: 0.2,
                    weight: 2
                }).addTo(map);
                
                geofenceCircles[`${caseData.case_num}_destination`] = destGeofenceCircle;
                
                destMarker.bindPopup(`
                    <div style="position:relative; min-width: 260px; max-width: 320px; background: linear-gradient(135deg,#e59a1b 0%, #dd7349 100%); color:#ffffff; border-radius:14px; box-shadow:0 8px 20px rgba(0,0,0,0.22); overflow:hidden; border:2px solid rgba(255,255,255,0.85);">
                        <button type="button" class="popup-close-internal" aria-label="Close">✕</button>
                        <div style="padding:12px 14px;">
                            <div style="display:flex; justify-content:space-between; align-items:center;">
                                <div style="font-weight:900; font-size:22px; color:#ffffff;">Transport To - Case # ${caseData.case_num}</div>
                            </div>
                            <div style="margin-top:6px; color:#ffffff; font-size:12px;">
                                <div><strong>Jay :</strong> ${caseData.name}</div>
                                <div style="margin-top:6px;"><strong>Transport To:</strong> ${caseData.to_go_to_address}</div>
                            </div>
                            <div style="margin-top:12px;">
                                <button onclick="navigateToLocation(${caseData.case_num}, ${destLat}, ${destLng}, 'destination')" style="background:#0b2a5a; color:#fff; border:none; padding:8px 12px; border-radius:10px; font-size:12px; font-weight:800; cursor:pointer;">Navigate Here</button>
                            </div>
                        </div>
                    </div>
                `, { className: 'dest-popup', closeButton: false });
                // Wire the internal close button when popup opens
                destMarker.on('popupopen', (e) => {
                    try {
                        const el = e.popup.getElement();
                        const btn = el && el.querySelector('.popup-close-internal');
                        if (btn) btn.addEventListener('click', () => destMarker.closePopup(), { once: true });
                    } catch (_) {}
                });
                
                // Store destination marker with a different key
                caseMarkers[`dest_${caseData.case_num}`] = destMarker;
                
                // Add connection line between pickup and destination
                const pickupLat = parseFloat(caseData.latitude);
                const pickupLng = parseFloat(caseData.longitude);
                
                const connectionLine = L.polyline([
                    [pickupLat, pickupLng],
                    [destLat, destLng]
                ], {
                    color: isAccepted ? '#2563eb' : '#6b7280',
                    weight: 2,
                    opacity: 0.7,
                    dashArray: '5, 10'
                }).addTo(map);
                
                // Store connection line
                caseMarkers[`line_${caseData.case_num}`] = connectionLine;
                }
                
                // Create initial popup content (pickup - refined design)
                const popupContent = `
                    <div style="position:relative; min-width: 260px; max-width: 340px; background: linear-gradient(90deg, #0b2a5a 0%, #1e3a8a 55%, #3b82f6 100%); color:#ffffff; border-radius:12px; box-shadow:0 12px 26px rgba(0,0,0,0.28); overflow:hidden; border:2px solid rgba(255,255,255,0.9);">
                        <button type="button" class="popup-close-internal" aria-label="Close" style="position:absolute; top:6px; right:6px; width:18px; height:18px; border-radius:4px; border:2px solid #ffffff; background:#1e3a8a; color:#ffffff; font-weight:900; line-height:12px; font-size:12px;">X</button>
                        <div style="padding:12px 28px 12px 14px;">
                            <div style="display:flex; justify-content:space-between; align-items:flex-start; gap:8px;">
                                <div style="font-weight:900; font-size:20px;">Case #${caseData.case_num}</div>
                                ${caseData.type ? `<span style=\"background:#1d4ed8; color:#fff; padding:2px 8px; border-radius:4px; border:1px solid rgba(255,255,255,0.35); font-size:10px; font-weight:800;\">${caseData.type}</span>` : ''}
                            </div>
                            
                            <div style="margin-top:6px; font-size:12px;">
                                <strong>NO# :</strong> ${caseData.contact || ''} <a href="${telHref(caseData.contact)}" style="color:#ef4444; font-weight:900; text-decoration:none;">CALL</a>
                            </div>
                            <div style="margin-top:8px; font-size:12px;">
                                <div><strong>Transport From:</strong> ${caseData.address}</div>
                                ${caseData.to_go_to_address ? `<div style=\"margin-top:4px;\"><strong>Transport To:</strong> ${caseData.to_go_to_address}</div>` : ''}
                            </div>
                            <div style="margin-top:12px; display:flex; gap:10px; flex-wrap:wrap;">
                                ${!isAccepted ? `
                                <button onclick=\"acceptCase(${caseData.case_num})\" style=\"background:#1d4ed8; color:#fff; border:none; padding:6px 10px; border-radius:8px; font-size:12px; font-weight:700; cursor:pointer;\">Accept</button>
                                <button onclick=\"rejectCase(${caseData.case_num})\" style=\"background:#e5e7eb; color:#111827; border:none; padding:6px 10px; border-radius:8px; font-size:12px; font-weight:700; cursor:pointer;\">Reject</button>
                                ` : ''}
                                <button onclick="navigateToCase(${caseData.case_num}, ${pickupLat}, ${pickupLng})" style="background:#1e3a8a; color:#fff; border:none; padding:6px 10px; border-radius:8px; font-size:12px; font-weight:700; cursor:pointer;">🧭 Navigate</button>
                                <a href="${telHref(caseData.contact)}" style="background:#f59e0b; color:#0b0b0b; border:none; padding:6px 10px; border-radius:8px; font-size:12px; font-weight:700; text-decoration:none; display:inline-block;">📞 Call</a>
                            </div>
                        </div>
                    </div>
                `;
                
                marker.bindPopup(popupContent, { className: 'pickup-popup', closeButton: false });
                marker.on('popupopen', (e) => {
                    try {
                        const el = e.popup.getElement();
                        const btn = el && el.querySelector('.popup-close-internal');
                        if (btn) btn.addEventListener('click', () => marker.closePopup(), { once: true });
                    } catch (_) {}
                });
            } catch (error) {
                console.error(`❌ Error creating marker for Case #${caseData.case_num}:`, error);
            }
        }
    });
}

function getPriorityColor(priority) {
    const colors = {
        'Low': '#93c5fd',       // light blue
        'Medium': '#3b82f6',    // blue
        'High': '#1d4ed8',      // deep blue
        'Critical': '#1e3a8a',  // navy
        'Emergency': '#1e40af'  // royal blue
    };
    return colors[priority] || colors['Medium'];
}

function telHref(num) {
    if (!num) return '#';
    try {
        const cleaned = String(num).replace(/[^+\d]/g, '');
        return `tel:${cleaned}`;
    } catch (_) {
        return '#';
    }
}

async function acceptCase(caseNum) {
    try {
        const response = await fetchWithAuth(`/driver/cases/${caseNum}/accept`, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        
        if (response.ok) {
            acceptedCases.add(caseNum);
            showNotification(`Case #${caseNum} accepted!`, 'success');
            
            // Update marker color to blue and popup content
            updateCaseMarkerColor(caseNum, 'blue');
            
            // Optimistically refresh UI
            loadAllCases();
            loadCaseNotifications(); // keep markers in sync too
            
            // Dismiss alert if it was for this case
            hideNewCaseAlert(true);
        } else {
            alert('Error accepting case');
        }
    } catch (error) {
        console.error('Error accepting case:', error);
        alert('Error accepting case');
    }
}

async function rejectCase(caseNum) {
    try {
        const response = await fetchWithAuth(`/driver/cases/${caseNum}/reject`, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        
        if (response.ok) {
            acceptedCases.delete(caseNum);
            showNotification(`Case #${caseNum} rejected`, 'info');
            
            // Remove marker from map completely for rejected cases
            if (caseMarkers[caseNum]) {
                map.removeLayer(caseMarkers[caseNum]);
                delete caseMarkers[caseNum];
            }
            
            // Refresh UI immediately
            loadAllCases();
            loadCaseNotifications();
            hideNewCaseAlert(true);
        } else {
            alert('Error rejecting case');
        }
    } catch (error) {
        console.error('Error rejecting case:', error);
        alert('Error rejecting case');
    }
}

// Custom confirmation dialog function
function showConfirmDialog(message, title = 'Confirm Action') {
    return new Promise((resolve) => {
        // Create overlay
        const overlay = document.createElement('div');
        overlay.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 15000;
            display: flex;
            justify-content: center;
            align-items: center;
            animation: fadeIn 0.2s ease;
        `;
        
        // Create dialog box
        const dialog = document.createElement('div');
        dialog.style.cssText = `
            background: linear-gradient(180deg, #f59e0b 0%, #c2410c 100%);
            border-radius: 16px;
            padding: 24px;
            max-width: 400px;
            width: 90%;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            border: 2px solid rgba(255, 255, 255, 0.3);
            animation: slideUp 0.3s ease;
        `;
        
        // Title
        const titleEl = document.createElement('div');
        titleEl.style.cssText = `
            font-size: 20px;
            font-weight: 900;
            color: #ffffff;
            margin-bottom: 16px;
            text-align: center;
        `;
        titleEl.textContent = title;
        
        // Message
        const messageEl = document.createElement('div');
        messageEl.style.cssText = `
            font-size: 15px;
            color: #ffffff;
            margin-bottom: 24px;
            line-height: 1.6;
            text-align: center;
        `;
        messageEl.textContent = message;
        
        // Button container
        const buttonContainer = document.createElement('div');
        buttonContainer.style.cssText = `
            display: flex;
            gap: 12px;
            justify-content: center;
        `;
        
        // Yes button
        const yesButton = document.createElement('button');
        yesButton.textContent = 'Yes';
        yesButton.style.cssText = `
            background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 100%);
            color: white;
            border: none;
            padding: 12px 32px;
            border-radius: 10px;
            font-weight: 800;
            font-size: 15px;
            cursor: pointer;
            flex: 1;
            transition: transform 0.1s, box-shadow 0.1s;
            box-shadow: 0 4px 8px rgba(30, 58, 138, 0.3);
        `;
        yesButton.onmouseover = () => {
            yesButton.style.transform = 'scale(1.02)';
            yesButton.style.boxShadow = '0 6px 12px rgba(30, 58, 138, 0.4)';
        };
        yesButton.onmouseout = () => {
            yesButton.style.transform = 'scale(1)';
            yesButton.style.boxShadow = '0 4px 8px rgba(30, 58, 138, 0.3)';
        };
        yesButton.onclick = () => {
            document.body.removeChild(overlay);
            resolve(true);
        };
        
        // No button
        const noButton = document.createElement('button');
        noButton.textContent = 'No';
        noButton.style.cssText = `
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
            border: none;
            padding: 12px 32px;
            border-radius: 10px;
            font-weight: 800;
            font-size: 15px;
            cursor: pointer;
            flex: 1;
            transition: transform 0.1s, box-shadow 0.1s;
            box-shadow: 0 4px 8px rgba(239, 68, 68, 0.3);
        `;
        noButton.onmouseover = () => {
            noButton.style.transform = 'scale(1.02)';
            noButton.style.boxShadow = '0 6px 12px rgba(239, 68, 68, 0.4)';
        };
        noButton.onmouseout = () => {
            noButton.style.transform = 'scale(1)';
            noButton.style.boxShadow = '0 4px 8px rgba(239, 68, 68, 0.3)';
        };
        noButton.onclick = () => {
            document.body.removeChild(overlay);
            resolve(false);
        };
        
        // Add CSS animations if not already added
        if (!document.getElementById('confirm-dialog-styles')) {
            const style = document.createElement('style');
            style.id = 'confirm-dialog-styles';
            style.textContent = `
                @keyframes fadeIn {
                    from { opacity: 0; }
                    to { opacity: 1; }
                }
                @keyframes slideUp {
                    from { transform: translateY(20px); opacity: 0; }
                    to { transform: translateY(0); opacity: 1; }
                }
            `;
            document.head.appendChild(style);
        }
        
        // Assemble dialog
        buttonContainer.appendChild(noButton);
        buttonContainer.appendChild(yesButton);
        dialog.appendChild(titleEl);
        dialog.appendChild(messageEl);
        dialog.appendChild(buttonContainer);
        overlay.appendChild(dialog);
        
        // Click outside to close (resolve as false)
        overlay.onclick = (e) => {
            if (e.target === overlay) {
                document.body.removeChild(overlay);
                resolve(false);
            }
        };
        
        // Add to page
        document.body.appendChild(overlay);
    });
}

// Driver can no longer complete cases - only admin can complete when driver reaches destination
async function completeCase(caseNum) {
    showToast('Only admin can complete cases when you reach the destination. Please wait for admin notification.', 'info');
}

function updateCaseMarkerColor(caseNum, color) {
    const marker = caseMarkers[caseNum];
    if (marker) {
        const newIcon = L.icon({
            iconUrl: `https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-${color}.png`,
            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
        });
        marker.setIcon(newIcon);
        // Ensure destination pin remains orange regardless of case status
        const destMarker = caseMarkers[`dest_${caseNum}`];
        if (destMarker) {
            const orangeIcon = L.icon({
                iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-orange.png',
                shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
                iconSize: [25, 41],
                iconAnchor: [12, 41],
                popupAnchor: [1, -34],
                shadowSize: [41, 41]
            });
            destMarker.setIcon(orangeIcon);
        }
        
        // Update the popup content to remove Accept/Reject buttons
        updateMarkerPopup(marker, caseNum, color);
    }
}

function updateMarkerPopup(marker, caseNum, status) {
    // Get the case data from the marker's stored data or find it in the current cases
    const caseData = marker.caseData || {};
    
    // Determine if case is accepted or rejected
    const isAccepted = status === 'blue' || acceptedCases.has(caseNum);
    const isRejected = status === 'gray';
    
    const popupContent = `
        <div style="position:relative; min-width: 240px; max-width: 320px; background: linear-gradient(90deg, #0b2a5a 0%, #1e3a8a 55%, #3b82f6 100%); color:#ffffff; border-radius:12px; box-shadow:0 12px 26px rgba(0,0,0,0.28); overflow:hidden; border:2px solid rgba(255,255,255,0.9);">
            <button type=\"button\" class=\"popup-close-internal\" aria-label=\"Close\" style=\"position:absolute; top:6px; right:6px; width:18px; height:18px; border-radius:4px; border:2px solid #ffffff; background:#1e3a8a; color:#ffffff; font-weight:900; line-height:12px; font-size:12px;\">X</button>
            <div style="padding:10px 28px 10px 12px;">
                <div style="display:flex; justify-content:space-between; align-items:flex-start; gap:8px;">
                    <div>
                        <div style="margin:0 0 6px 0; color:#ffffff; font-weight:800;">Case #${caseNum}</div>
                        <div style="display:flex; align-items:center; gap:6px; color:#e5f0ff; font-weight:600;">${caseData.name || 'Unknown'}
                            ${caseData.type ? `<span style=\"background:#dbeafe; color:#1e40af; padding:2px 8px; border-radius:999px; font-size:10px; font-weight:700;\">${caseData.type}</span>` : ''}
                        </div>
                    </div>
                    <div style="display:flex; flex-wrap:wrap; gap:4px; justify-content:flex-end;">
                        ${caseData.type ? `<span style=\"background:#dbeafe; color:#1e40af; padding:2px 8px; border-radius:4px; border:1px solid rgba(30,64,175,0.35); font-size:10px; font-weight:700;\">${caseData.type}</span>` : ''}
                    </div>
                </div>
                <div style="margin-top:6px; display:flex; align-items:center; gap:8px; color:#cfe2ff; font-size:12px;">
                    <span>📞 ${caseData.contact || 'Unknown'}</span>
                    <a href="${telHref(caseData.contact || '')}" style="color:#67e8f9; text-decoration:none; font-weight:700;">Call</a>
                </div>
                <div style="margin-top:4px; color:#d0defc; font-size:12px;">📍 ${caseData.address || 'Unknown'}</div>
                <div style="margin-top:10px; display:flex; gap:6px; flex-wrap:wrap;">
                    <button onclick="navigateToCase(${caseNum}, ${caseData.latitude || 0}, ${caseData.longitude || 0})" style="background:#1e3a8a; color:#fff; border:none; padding:6px 10px; border-radius:8px; font-size:12px; font-weight:700; cursor:pointer;">🧭 Navigate</button>
                </div>
            </div>
        </div>
    `;
    
    marker.setPopupContent(popupContent);
    // ensure internal X works
    marker.on('popupopen', (e) => {
        try {
            const el = e.popup.getElement();
            const btn = el && el.querySelector('.popup-close-internal');
            if (btn) btn.addEventListener('click', () => marker.closePopup(), { once: true });
        } catch (_) {}
    });
}

function navigateToCase(caseNum, latitude, longitude) {
    // Check if navigation is already active
    if (navigationActive) {
        const confirmNavigation = confirm(`Navigation is already active.\n\nDo you want to cancel the current navigation and navigate to Case #${caseNum}?\n\nClick OK to proceed, or Cancel to keep current navigation.`);
        
        if (!confirmNavigation) {
            return; // User chose to keep current navigation
        }
        
        // Stop current navigation
        stopNavigation();
    }
    
    // Find the case data to check if it has destination
    const marker = caseMarkers[caseNum];
    const caseData = marker ? marker.caseData : null;
    
    // If case has destination, ask user where to navigate
    if (caseData && caseData.to_go_to_latitude && caseData.to_go_to_longitude) {
        const choice = confirm(`Case #${caseNum} has both transport from and transport to locations.\n\nClick OK to navigate to TRANSPORT FROM location\nClick Cancel to navigate to TRANSPORT TO location`);
        
        if (choice) {
            // Navigate to pickup (original coordinates)
            navigateToLocation(caseNum, latitude, longitude, 'pickup');
        } else {
            // Navigate to destination
            navigateToLocation(caseNum, caseData.to_go_to_latitude, caseData.to_go_to_longitude, 'destination');
        }
    } else {
        // Only pickup location available
        navigateToLocation(caseNum, latitude, longitude, 'pickup');
    }
}

function navigateToLocation(caseNum, latitude, longitude, locationType) {
    // Special check: If navigating to Transport To while Transport From is still active
    if (navigationActive && currentNavigationType === 'pickup' && locationType === 'destination' && currentNavigationCase === caseNum) {
        const confirmNavigation = confirm(`You are currently navigating to Transport From location for Case #${caseNum}.\n\nDo you want to navigate to Transport To location?\n\nClick OK to navigate to Transport To, or Cancel to continue to Transport From.`);
        
        if (!confirmNavigation) {
            return; // User chose to continue to Transport From
        }
        
        // User confirmed, proceed to navigate to Transport To
        // Stop current navigation first
        stopNavigation();
    }
    // General check: If navigation is already active for a different case or location
    else if (navigationActive) {
        const locationText = locationType === 'destination' ? 'transport to location' : 'transport from location';
        const confirmNavigation = confirm(`Navigation is already active.\n\nDo you want to cancel the current navigation and navigate to Case #${caseNum} ${locationText}?\n\nClick OK to proceed, or Cancel to keep current navigation.`);
        
        if (!confirmNavigation) {
            return; // User chose to keep current navigation
        }
        
        // Stop current navigation
        stopNavigation();
    }
    
    // Ensure routing control exists
    ensureRoutingControl();
    navigationActive = true;
    currentNavigationType = locationType;
    currentNavigationCase = caseNum;
    
    // Show stop navigation button
    const stopNavBtn = document.getElementById('stop-navigation');
    if (stopNavBtn) {
        stopNavBtn.style.display = 'inline-block';
    }
    const stopNavBtnMobile = document.getElementById('stop-navigation-mobile');
    if (stopNavBtnMobile) {
        stopNavBtnMobile.style.display = 'inline-block';
    }
    
    // Get current location
    let currentLat, currentLng;
    if (currentMarker) {
        const pos = currentMarker.getLatLng();
        currentLat = pos.lat;
        currentLng = pos.lng;
    } else {
        // Use map center if no current location
        const center = map.getCenter();
        currentLat = center.lat;
        currentLng = center.lng;
    }
    
    try {
        // Set waypoints for navigation
        routeControl.setWaypoints([
            L.latLng(currentLat, currentLng),
            L.latLng(latitude, longitude)
        ]);
        
        // Save destination to database so dashboard can show trail
        if (ambulanceId) {
            fetchWithAuth('/admin/gps/set-destination', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    ambulance_id: ambulanceId,
                    latitude: latitude,
                    longitude: longitude
                })
            }).catch(err => {
                console.error('Error setting destination:', err);
            });
        }
        
        const locationText = locationType === 'destination' ? 'transport to location' : 'transport from location';
        showNotification(`Navigating to Case #${caseNum} ${locationText}`, 'info');
    } catch (error) {
        console.error('Navigation error:', error);
        showNotification('Navigation failed. Please try again.', 'error');
    }
}

function showNotification(message, type = 'info') {
    // Create notification element (center-top, exit to top). For 'info', use orange gradient bar style
    const isInfo = type === 'info';
    const notification = document.createElement('div');
    notification.style.cssText = isInfo ? `
        position: fixed;
        top: 16px;
        left: 50%;
        transform: translateX(-50%) translateY(-12px);
        background: linear-gradient(180deg, #f59e0b 0%, #c2410c 100%);
        color: #ffffff;
        padding: 10px 18px;
        border-radius: 10px;
        border: 2px solid rgba(255,255,255,0.9);
        box-shadow: 0 8px 18px rgba(0,0,0,0.18);
        z-index: 12000;
        font-weight: 800;
        font-size: 16px;
        letter-spacing: .01em;
        max-width: 92vw;
        text-align: center;
        transition: transform .18s ease, opacity .18s ease;
        opacity: 0;
    ` : `
        position: fixed;
        top: 16px;
        right: 20px;
        background: ${type === 'success' ? '#10b981' : type === 'error' ? '#ef4444' : '#3b82f6'};
        color: white;
        padding: 1rem 1.5rem;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        z-index: 10000;
        font-weight: 600;
        max-width: 300px;
    `;
    notification.textContent = message;

    document.body.appendChild(notification);

    // Animate in/out
    if (isInfo) {
        requestAnimationFrame(() => {
            notification.style.opacity = '1';
            notification.style.transform = 'translateX(-50%) translateY(0)';
        });
        setTimeout(() => {
            notification.style.opacity = '0';
            notification.style.transform = 'translateX(-50%) translateY(-16px)';
            setTimeout(() => notification.remove(), 220);
        }, 2600);
    } else {
        setTimeout(() => notification.remove(), 3000);
    }
}

// Toast helper with click-to-open support
function showToast(message, type = 'info', onClick = null, duration = 3200, customTitle = null) {
    const notification = document.createElement('div');
    
    // Determine title and background based on type
    let title = 'New Case Issued';
    let bgGradient = 'linear-gradient(180deg, #f59e0b 0%, #c2410c 100%)';
    
    if (type === 'geofence' || type === 'vicinity') {
        title = customTitle || '📍 Location Reached';
        bgGradient = 'linear-gradient(180deg, #10b981 0%, #059669 100%)';
    } else if (type === 'success') {
        title = customTitle || '✅ Success';
        bgGradient = 'linear-gradient(180deg, #10b981 0%, #059669 100%)';
    } else if (type === 'error') {
        title = customTitle || '❌ Error';
        bgGradient = 'linear-gradient(180deg, #ef4444 0%, #dc2626 100%)';
    } else if (customTitle) {
        title = customTitle;
        // Keep default orange gradient for custom titles without specific type
    }
    
    notification.style.cssText = `
        position: fixed;
        top: 16px;
        left: 50%;
        transform: translateX(-50%) translateY(-12px);
        background: ${bgGradient};
        color: #ffffff;
        padding: 10px 14px;
        border-radius: 12px;
        border: 2px solid rgba(255,255,255,0.9);
        box-shadow: 0 8px 18px rgba(0,0,0,0.18);
        z-index: 12000;
        max-width: 320px;
        cursor: ${onClick ? 'pointer' : 'default'};
        transition: transform .18s ease, opacity .18s ease;
        opacity: 0;
    `;
    // Two-line design: title + subtitle
    notification.innerHTML = `
        <div style="font-weight: 900; font-size: 16px; line-height: 1.1;">${title}</div>
        <div style="font-weight: 700; font-size: 12px; opacity:.95;">${message || 'Admin issued you a new case'}</div>
    `;
    if (onClick) {
        notification.addEventListener('click', (e) => {
            // Ensure clicking the toast closes notifications and opens cases as needed
            const notif = document.getElementById('notifications-modal');
            if (notif) notif.style.display = 'none';
            onClick(e);
        });
    }
    document.body.appendChild(notification);
    // Enter animation from top
    requestAnimationFrame(() => {
        notification.style.opacity = '1';
        notification.style.transform = 'translateX(-50%) translateY(0)';
    });
    // Exit upwards
    setTimeout(() => {
        notification.style.opacity = '0';
        notification.style.transform = 'translateX(-50%) translateY(-16px)';
        setTimeout(() => notification.remove(), 220);
    }, duration);
}

// New Case Alert helpers
function enqueueNewCaseAlert(caseData) {
    alertQueue.push(caseData);
    if (!alertVisible) {
        showNextNewCaseAlert();
    }
}

function showNextNewCaseAlert() {
    if (alertQueue.length === 0) {
        hideNewCaseAlert();
        return;
    }
    const c = alertQueue.shift();
    renderNewCaseAlert(c);
}

function renderNewCaseAlert(c) {
    alertVisible = true;
    const alert = document.getElementById('new-case-alert');
    const body = document.getElementById('new-case-alert-body');
    if (!alert || !body) return;
    
    // Play sound notification for new case (only once per case, avoid duplicates)
    const now = Date.now();
    if (now - lastCaseSoundTime > 3000) { // Prevent duplicate sounds within 3 seconds
        try {
            playNotificationSound('case');
            lastCaseSoundTime = now;
        } catch (e) {
            console.warn('Could not play case notification sound:', e);
        }
    }
    const priorityBadge = '';
    body.innerHTML = `
        <div style="background: linear-gradient(180deg, #e59a1b 0%, #dd7349 100%); border: 2px solid #1e3a8a; border-radius: 14px; padding: 12px;">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 8px;">
                <div style="font-weight: 900; font-size: 18px; letter-spacing: .02em; color: #ffffff;">CASE #${c.case_num}</div>
                ${c.type ? `<span style=\"background: #2b5db8; color: #fff; font-weight: 800; font-size: 10px; padding: 4px 10px; border-radius: 999px;\">${c.type}</span>` : ''}
            </div>
            <div style="margin-top: 8px; color:rgb(255, 255, 255); font-weight: 1500;">${c.name || ''}</div>
            <div style="margin-top: 6px; color:rgb(255, 255, 255); font-size: 12px; display: flex; align-items: center; gap: 8px;"><strong>Contact info:</strong> ${c.contact || ''} <a href="${telHref(c.contact || '')}" style="color: #dc2626; font-weight: 900; text-decoration: none;">CALL</a></div>
            <div style="margin-top: 6px; color:rgb(255, 255, 255); font-size: 12px;">
                <div><strong>Transport From:</strong> ${c.address || ''}</div>
                ${c.to_go_to_address ? `<div><strong>Transport To:</strong> ${c.to_go_to_address}</div>` : `<div><strong>Transport To:</strong> —</div>`}
            </div>
            <div style="margin-top: 10px; display: flex; gap: 8px; flex-wrap: nowrap; align-items: center;">
                <button onclick="acceptCase(${c.case_num}); hideNewCaseAlert(true);" style="
                    background: #1e3a8a;
                    color: white;
                    border: none;
                    padding: 8px 12px;
                    border-radius: 6px;
                    font-size: 12px;
                    cursor: pointer;
                    font-weight: 700;
                    flex: 1;
                ">Accept</button>
                <button onclick="rejectCase(${c.case_num}); hideNewCaseAlert(true);" style="
                    background: #dc2626;
                    color: white;
                    border: none;
                    padding: 8px 12px;
                    border-radius: 6px;
                    font-size: 12px;
                    cursor: pointer;
                    font-weight: 700;
                    flex: 1;
                ">Reject</button>
            </div>
            <div style="margin-top: 8px; color: #ffffff; font-size: 10px; text-align: right;">Created: ${new Date(c.created_at).toLocaleString()}</div>
        </div>
    `;
    alert.style.display = 'block';
}

function hideNewCaseAlert(processNext) {
    const alert = document.getElementById('new-case-alert');
    if (alert) alert.style.display = 'none';
    alertVisible = false;
    if (processNext) {
        setTimeout(showNextNewCaseAlert, 150);
    }
}

document.getElementById('dismiss-new-case-alert')?.addEventListener('click', () => hideNewCaseAlert(true));
// Cases are now loaded when map is ready (see map.whenReady above)
// This ensures pins show immediately on page load

// Auto-refresh for GPS tracking and notifications only
setInterval(() => {
    // Only refresh notifications, not the map markers or table
    loadCaseNotificationsOnly();
}, 10000); // Check every 10 seconds

// Check for completed cases and remove markers
async function checkForCompletedCases() {
    try {
        if (!map || typeof map.hasLayer !== 'function') return;
        if (!caseMarkers || typeof caseMarkers !== 'object') return;
        
        // Get all visible case numbers from markers
        const visibleCaseNums = new Set();
        Object.keys(caseMarkers).forEach(key => {
            const match = key.match(/^dest_(\d+)$|^line_(\d+)$|^(\d+)$/);
            if (match) {
                const caseNum = match[1] || match[2] || match[3];
                if (caseNum) visibleCaseNums.add(caseNum);
            }
        });
        
        if (visibleCaseNums.size === 0) return;
        
        // Fetch all cases to check status
        const response = await fetchWithAuth('/driver/cases/all', {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        
        if (!response.ok) return;
        
        const allCases = await response.json();
        if (!Array.isArray(allCases)) return;
        
        // Check each visible case and remove if completed
        visibleCaseNums.forEach(caseNum => {
            const caseData = allCases.find(c => String(c.case_num) === String(caseNum));
            if (caseData && isCaseCompleted(caseData)) {
                console.log(`🗑️ Removing completed case #${caseNum} from map`);
                removeCaseFromMap(caseNum);
            }
        });
    } catch (error) {
        console.error('Error checking for completed cases:', error);
    }
}

// Check for completed cases every 3 seconds
setInterval(checkForCompletedCases, 3000);

// Brute-force safety net: hard refresh the page periodically to ensure UI never goes stale
setInterval(() => {
    if (document.hidden) return; // Avoid refreshing while in background to prevent user disruption
    console.log('🔄 Auto refreshing driver page to keep map data in sync...');
    window.location.reload();
}, PAGE_REFRESH_INTERVAL_MS);




function toggleSettingsDropdown() {
    const dropdown = document.getElementById('settings-dropdown');
    const settingsBtn = document.querySelector('.settings-btn');
    const topHeader = document.querySelector('.top-header');
    const isHidden = !dropdown.classList.contains('open');
    
    if (dropdown && settingsBtn && topHeader) {
        dropdown.classList.toggle('open');
        
        // Move settings icon inside the dropdown when opened, back to header when closed
        if (isHidden) {
            // place inside dropdown (ensure it's removed from header first)
            if (topHeader.contains(settingsBtn)) {
                topHeader.removeChild(settingsBtn);
            }
            dropdown.appendChild(settingsBtn);
            settingsBtn.style.top = '10px';
            settingsBtn.style.right = '12px';
            settingsBtn.style.position = 'absolute';
            settingsBtn.style.display = 'flex';
        } else {
            // return to header (ensure it's removed from dropdown first)
            if (dropdown.contains(settingsBtn)) {
                dropdown.removeChild(settingsBtn);
            }
            topHeader.appendChild(settingsBtn);
            settingsBtn.style.top = '1px';
            settingsBtn.style.right = '1px';
            settingsBtn.style.position = 'absolute';
            settingsBtn.style.display = 'flex';
        }
    }
}

// Close dropdown when clicking outside
document.addEventListener('click', function(event) {
    const dropdown = document.getElementById('settings-dropdown');
    const settingsBtn = document.querySelector('.settings-btn');
    const topHeader = document.querySelector('.top-header');
    
    if (dropdown && settingsBtn && topHeader) {
        if (!dropdown.contains(event.target) && !settingsBtn.contains(event.target)) {
            if (dropdown.classList.contains('open')) {
                dropdown.classList.remove('open');
                // Always ensure settings button is in header (remove from dropdown if it's there)
                if (dropdown.contains(settingsBtn)) {
                    dropdown.removeChild(settingsBtn);
                }
                // Ensure button is in header
                if (!topHeader.contains(settingsBtn)) {
                    topHeader.appendChild(settingsBtn);
                }
                // Reset styles
                settingsBtn.style.top = '1px';
                settingsBtn.style.right = '1px';
                settingsBtn.style.position = 'absolute';
                settingsBtn.style.display = 'flex';
            }
        }
    }
});

// ===== Super Admin Presence System =====
(function() {
    const banner = document.getElementById('superadmin-banner');
    if (!banner) return;
    
    // Check super admin status (drivers don't send heartbeat, only view)
    function checkSuperAdminStatus() {
        fetchWithAuth('/presence/superadmin/status', {
            headers: {
                'Accept': 'application/json'
            }
        })
        .then(res => {
            if (!res.ok) return null;
            return res.json();
        })
        .then(data => {
            if (!data) return;
            if (data.active) {
                banner.style.display = 'block';
                // Adjust container padding
                const container = document.querySelector('.container');
                if (container) container.style.paddingTop = '56px';
            } else {
                banner.style.display = 'none';
                const container = document.querySelector('.container');
                if (container) container.style.paddingTop = '0';
            }
        })
        .catch(err => {
            // Silently handle errors - not critical for super admin status
            if (err.message !== 'Session expired') {
                console.error('Status check error:', err);
            }
        });
    }
    
    // Initial check
    checkSuperAdminStatus();
    
    // Poll every 5 seconds
    setInterval(checkSuperAdminStatus, 5000);
})();

// ===== GEOFENCING SYSTEM FOR DRIVER =====
// Function to calculate distance (Haversine formula)
function calculateDriverDistance(lat1, lon1, lat2, lon2) {
    const R = 6371000; // Earth's radius in meters
    const dLat = (lat2 - lat1) * Math.PI / 180;
    const dLon = (lon2 - lon1) * Math.PI / 180;
    const a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
              Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
              Math.sin(dLon / 2) * Math.sin(dLon / 2);
    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
    return R * c;
}

// Function to update geofence circle colors based on driver proximity
function updateDriverGeofenceCircles(driverLat, driverLng) {
    if (!driverLat || !driverLng) return;
    
    let nearestDistance = Infinity;
    let nearestLocation = null;
    
    // Get all cases and update both pickup and destination circles
    Object.keys(caseMarkers).forEach(caseNum => {
        const caseMarker = caseMarkers[caseNum];
        if (!caseMarker || !caseMarker.caseData) return;
        
        const caseData = caseMarker.caseData;
        
        // Update pickup circle
        if (caseData.latitude && caseData.longitude && geofenceCircles[`${caseNum}_pickup`]) {
            const pickupLat = parseFloat(caseData.latitude);
            const pickupLon = parseFloat(caseData.longitude);
            
            if (!isNaN(pickupLat) && !isNaN(pickupLon)) {
                const pickupDistance = calculateDriverDistance(driverLat, driverLng, pickupLat, pickupLon);
                
                if (pickupDistance <= GEOFENCE_RADIUS) {
                    geofenceCircles[`${caseNum}_pickup`].setStyle({
                        color: '#10b981',
                        fillColor: '#10b981',
                        fillOpacity: 0.3,
                        weight: 3
                    });
                    
                    if (pickupDistance < nearestDistance) {
                        nearestDistance = pickupDistance;
                        nearestLocation = 'pickup';
                    }
                } else {
                    geofenceCircles[`${caseNum}_pickup`].setStyle({
                        color: '#6b7280',
                        fillColor: '#6b7280',
                        fillOpacity: 0.2,
                        weight: 2
                    });
                }
            }
        }
        
        // Update destination circle
        if (caseData.to_go_to_latitude && caseData.to_go_to_longitude && geofenceCircles[`${caseNum}_destination`]) {
            const destLat = parseFloat(caseData.to_go_to_latitude);
            const destLon = parseFloat(caseData.to_go_to_longitude);
            
            if (!isNaN(destLat) && !isNaN(destLon)) {
                const destDistance = calculateDriverDistance(driverLat, driverLng, destLat, destLon);
                
                if (destDistance <= GEOFENCE_RADIUS) {
                    geofenceCircles[`${caseNum}_destination`].setStyle({
                        color: '#10b981',
                        fillColor: '#10b981',
                        fillOpacity: 0.3,
                        weight: 3
                    });
                    
                    if (destDistance < nearestDistance) {
                        nearestDistance = destDistance;
                        nearestLocation = 'destination';
                    }
                } else {
                    geofenceCircles[`${caseNum}_destination`].setStyle({
                        color: '#6b7280',
                        fillColor: '#6b7280',
                        fillOpacity: 0.2,
                        weight: 2
                    });
                }
            }
        }
    });
    
    // Show proximity indicator if near any location
    if (nearestLocation && nearestDistance <= GEOFENCE_RADIUS) {
        const locationLabel = nearestLocation === 'pickup' ? 'pickup location' : 'destination';
        const proximityText = `📍 Near ${locationLabel} (${Math.round(nearestDistance)}m away)`;
        
        if (!document.getElementById('geofence-proximity-indicator')) {
            const indicator = document.createElement('div');
            indicator.id = 'geofence-proximity-indicator';
            indicator.style.cssText = 'position: fixed; top: 80px; left: 50%; transform: translateX(-50%); background: linear-gradient(135deg, #10b981, #059669); color: white; padding: 12px 24px; border-radius: 12px; font-weight: 800; font-size: 14px; z-index: 2000; box-shadow: 0 8px 24px rgba(16, 185, 129, 0.4); display: flex; align-items: center; gap: 8px;';
            indicator.innerHTML = `<i class="fas fa-map-marker-alt"></i> <span>${proximityText}</span>`;
            document.body.appendChild(indicator);
        } else {
            const indicator = document.getElementById('geofence-proximity-indicator');
            indicator.innerHTML = `<i class="fas fa-map-marker-alt"></i> <span>${proximityText}</span>`;
            indicator.style.display = 'flex';
        }
    } else {
        // Hide proximity indicator if far away from all locations
        const indicator = document.getElementById('geofence-proximity-indicator');
        if (indicator) {
            indicator.style.display = 'none';
        }
    }
}

</script>
</body>
</html>
