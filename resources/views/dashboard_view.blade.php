
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>View - SILANG MDRRMO</title>
    <link rel="stylesheet" href="{{ asset('css/stylish.css') }}">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  </head>
  <style>
    /* Dashboard View Styles */
body { 
  margin:0; 
  font-family: 'Roboto', Arial, sans-serif; 
  background:#f5f5f5; 
  overflow: hidden; 
}

/* Left Dashboard Panel */
.dashboard-left-panel {
  position: fixed;
  left: 0;
  top: 0;
  width: 320px;
  height: 100vh;
  background: linear-gradient(180deg, #f8fafc 0%, #f1f5f9 100%);
  box-shadow: 4px 0 30px rgba(3,18,115,0.2), 2px 0 12px rgba(0,0,0,0.1);
  z-index: 1500;
  overflow: hidden;
  display: flex;
  flex-direction: column;
}

.dashboard-panel-header {
  padding: 24px 20px;
  background: #031273;
  color: #ffffff;
  display: flex;
  align-items: center;
  gap: 14px;
  box-shadow: 0 4px 20px rgba(3,18,115,0.4), inset 0 1px 0 rgba(255,255,255,0.1);
  flex-shrink: 0;
  position: relative;
  overflow: hidden;
}

.dashboard-panel-header::before {
  content: '';
  position: absolute;
  top: -50%;
  right: -20%;
  width: 200px;
  height: 200px;
  background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
  border-radius: 50%;
}

.dashboard-panel-logo {
  width: 48px;
  height: 48px;
  border-radius: 50%;
  object-fit: cover;
  border: 3px solid rgba(255,255,255,0.4);
  box-shadow: 0 4px 12px rgba(0,0,0,0.2), inset 0 1px 0 rgba(255,255,255,0.3);
  position: relative;
  z-index: 1;
}

.dashboard-panel-title {
  font-size: 19px;
  font-weight: 900;
  color: #ffffff;
  margin: 0;
  text-shadow: 0 2px 8px rgba(0,0,0,0.2);
  letter-spacing: 0.3px;
  position: relative;
  z-index: 1;
}

.dashboard-overview-text {
  padding: 18px 20px;
  text-align: center;
  background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
  border-bottom: 2px solid #e2e8f0;
  flex-shrink: 0;
  box-shadow: 0 2px 8px rgba(0,0,0,0.04);
  position: relative;
}

.dashboard-overview-text::after {
  content: '';
  position: absolute;
  bottom: 0;
  left: 50%;
  transform: translateX(-50%);
  width: 60px;
  height: 3px;
  background: linear-gradient(90deg, transparent, #031273, transparent);
  border-radius: 2px;
}

.dashboard-overview-text h3 {
  font-size: 14px;
  font-weight: 900;
  background: linear-gradient(135deg, #031273 0%, #2563eb 100%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
  letter-spacing: 0.5px;
  margin: 0;
  text-transform: uppercase;
}

.dashboard-hotline-card {
  background: linear-gradient(135deg, #ffffff 0%, #fff7ed 100%);
  border: 2px solid rgba(242,140,40,0.2) !important;
  border-radius: 16px;
  box-shadow: 0 6px 20px rgba(242,140,40,0.15), 0 2px 6px rgba(0,0,0,0.08);
  padding: 20px;
  display: flex;
  flex-direction: column;
  gap: 12px;
  margin-top: auto;
  position: relative;
  overflow: hidden;
}

.dashboard-hotline-card::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  height: 4px;
  background: linear-gradient(90deg, #f28c28, #ea580c, #c2410c);
}

.dashboard-hotline-header {
  display: flex;
  align-items: center;
  gap: 12px;
}

.dashboard-hotline-icon {
  width: 56px;
  height: 56px;
  border-radius: 14px;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
  font-size: 24px;
  background: linear-gradient(135deg, #ef4444 0%, #dc2626 50%, #b91c1c 100%);
  color: #ffffff;
  box-shadow: 0 6px 20px rgba(239,68,68,0.4), inset 0 1px 0 rgba(255,255,255,0.3);
  position: relative;
  overflow: hidden;
  animation: iconZoom 2s ease-in-out infinite;
  animation-delay: 1.6s;
}

.dashboard-hotline-content {
  flex: 1;
}

.dashboard-hotline-title {
  font-weight: 800;
  letter-spacing: 0.3px;
  color: #475569;
  font-size: 0.75rem;
  display: flex;
  align-items: center;
  gap: 8px;
  margin-bottom: 6px;
  text-transform: uppercase;
}

.dashboard-hotline-title i {
  color: #f28c28;
  font-size: 14px;
  filter: drop-shadow(0 1px 2px rgba(242,140,40,0.3));
}

.dashboard-hotline-number {
  font-weight: 900;
  background: linear-gradient(135deg, #f28c28 0%, #ea580c 100%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
  font-size: 1.5rem;
  line-height: 1.1;
  letter-spacing: 0.5px;
  margin: 4px 0;
}

.dashboard-hotline-subtitle {
  font-size: 0.75rem;
  color: #64748b;
  font-weight: 600;
  margin-top: 4px;
  letter-spacing: 0.2px;
}

.dashboard-panel-content {
  padding: 20px;
  flex: 1;
  display: flex;
  flex-direction: column;
  gap: 14px;
  background: linear-gradient(180deg, #f8fafc 0%, #f1f5f9 100%);
  overflow: hidden;
}

/* Dashboard metric card styling - modern design */
.dashboard-metric-card {
  background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
  border: 1px solid rgba(226,232,240,0.8) !important;
  border-radius: 16px;
  box-shadow: 0 4px 16px rgba(15,23,42,0.08), 0 1px 3px rgba(0,0,0,0.05);
  padding: 18px;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  display: flex;
  flex-direction: column;
  gap: 12px;
  position: relative;
  overflow: hidden;
}


.dashboard-metric-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 12px;
}

.dashboard-metric-left {
  display: flex;
  flex-direction: column;
  align-items: flex-start;
  gap: 4px;
  flex: 1;
}

.dashboard-metric-title {
  font-weight: 800;
  letter-spacing: 0.3px;
  color: #475569;
  font-size: 0.875rem;
  text-transform: none;
  display: flex;
  align-items: center;
  gap: 8px;
}

.dashboard-metric-title i {
  color: #f28c28;
  font-size: 15px;
  filter: drop-shadow(0 1px 2px rgba(242,140,40,0.3));
}

.dashboard-metric-value {
  font-weight: 900;
  background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
  font-size: 2rem;
  line-height: 1.1;
  letter-spacing: -0.5px;
}

.dashboard-metric-subtitle {
  font-size: 0.75rem;
  color: #64748b;
  font-weight: 600;
  margin-top: 4px;
  letter-spacing: 0.2px;
}

.dashboard-metric-icon {
  width: 56px;
  height: 56px;
  border-radius: 14px;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
  font-size: 22px;
  box-shadow: 0 4px 12px rgba(0,0,0,0.15), inset 0 1px 0 rgba(255,255,255,0.2);
  position: relative;
  overflow: hidden;
}

@keyframes iconZoom {
  0%, 100% {
    transform: scale(1);
  }
  50% {
    transform: scale(1.15);
  }
}

.dashboard-metric-icon.drivers {
  background: linear-gradient(135deg, #10b981 0%, #059669 50%, #047857 100%);
  color: #ffffff;
  box-shadow: 0 4px 16px rgba(16,185,129,0.4), inset 0 1px 0 rgba(255,255,255,0.3);
  animation: iconZoom 2s ease-in-out infinite;
  animation-delay: 0s;
}

.dashboard-metric-icon.ambulances {
  background: linear-gradient(135deg, #ef4444 0%, #dc2626 50%, #b91c1c 100%);
  color: #ffffff;
  box-shadow: 0 4px 16px rgba(239,68,68,0.4), inset 0 1px 0 rgba(255,255,255,0.3);
  animation: iconZoom 2s ease-in-out infinite;
  animation-delay: 0.4s;
}

.dashboard-metric-icon.cases {
  background: linear-gradient(135deg, #f28c28 0%, #ea580c 50%, #c2410c 100%);
  color: #ffffff;
  box-shadow: 0 4px 16px rgba(242,140,40,0.4), inset 0 1px 0 rgba(255,255,255,0.3);
  animation: iconZoom 2s ease-in-out infinite;
  animation-delay: 0.8s;
}

.dashboard-metric-icon.available {
  background: linear-gradient(135deg, #06b6d4 0%, #0891b2 50%, #0e7490 100%);
  color: #ffffff;
  box-shadow: 0 4px 16px rgba(6,182,212,0.4), inset 0 1px 0 rgba(255,255,255,0.3);
  animation: iconZoom 2s ease-in-out infinite;
  animation-delay: 1.2s;
}

/* Full screen map - adjusted for left panel */
.map-wrapper { 
  width: calc(100vw - 320px);
  margin-left: 320px;
  height: 100vh; 
  margin-top: 0;
  border: 0; 
  border-radius: 0; 
  position: relative; 
}

#readonly-map { 
  width: 100%; 
  height: 100%; 
  border-radius: 0; 
  margin: 0; 
  padding: 0; 
  display: block; 
}

.driver-marker { 
  display:flex; 
  flex-direction:column; 
  align-items:center; 
  gap:4px; 
}

.driver-badge { 
  background:#111827; 
  color:#ffffff; 
  font-weight:800; 
  font-size:11px; 
  padding:2px 6px; 
  border-radius:9999px; 
  white-space:nowrap; 
  box-shadow:0 2px 6px rgba(0,0,0,0.15); 
}

.case-badge { 
  background:#2563eb; 
  color:#ffffff; 
  font-weight:800; 
  font-size:11px; 
  padding:2px 6px; 
  border-radius:6px; 
  white-space:nowrap; 
  box-shadow:0 2px 6px rgba(0,0,0,0.15); 
}

/* Google Maps style controls */
.gm-controls { 
  position: absolute; 
  top: 70px; 
  left: 10px; 
  z-index: 2000; 
  display: flex; 
  flex-direction: column; 
  gap: 2px; 
  transition: left 0.3s ease; 
}

/* Adjust controls when left panel is present */
body.has-left-panel .gm-controls {
  left: 10px;
}

.gm-controls.panel-open { 
  left: 10px; 
}

.gm-control-group { 
  background: #ffffff; 
  border-radius: 2px; 
  box-shadow: 0 2px 6px rgba(0,0,0,0.3); 
  border: 1px solid rgba(0,0,0,0.2); 
  overflow: visible; 
  position: relative; 
}

.gm-control-btn { 
  background: #ffffff; 
  border: none; 
  padding: 8px 12px; 
  cursor: pointer; 
  font-size: 13px; 
  color: #333; 
  display: flex; 
  align-items: center; 
  gap: 6px; 
  transition: background-color 0.2s; 
  width: 100%; 
  text-align: left; 
  font-family: 'Roboto', Arial, sans-serif; 
  white-space: nowrap; 
}

.gm-control-btn:hover { 
  background: #f5f5f5; 
}

.gm-control-btn.active { 
  background: #e3f2fd; 
  color: #1976d2; 
}

.gm-control-btn i { 
  font-size: 18px; 
  color: #666; 
}

.gm-status-bar { 
  position: absolute; 
  top: 10px; 
  left: 10px; 
  right: 10px; 
  background: rgba(255,255,255,0.9); 
  border-radius: 2px; 
  box-shadow: 0 2px 6px rgba(0,0,0,0.3); 
  padding: 8px 12px; 
  display: flex; 
  justify-content: space-between; 
  align-items: center; 
  z-index: 1000; 
  font-family: 'Roboto', Arial, sans-serif; 
  transition: right 0.3s ease; 
}

.gm-status-bar.panel-open { 
  right: 420px; 
}

/* Leaflet zoom control positioning */
.leaflet-control-zoom { 
  transition: right 0.3s ease !important; 
  position: absolute !important;
  right: 10px !important;
  bottom: 10px !important;
}

/* Make Leaflet zoom buttons bigger */
.leaflet-control-zoom a {
  width: 36px !important;
  height: 36px !important;
  line-height: 36px !important;
  font-size: 20px !important;
}

.leaflet-control-zoom.panel-open { 
  right: 430px !important; 
}

/* UI Toggle Button - Hide/Show UI (Icon Only) */
.gm-ui-toggle {
  position: absolute;
  top: 70px;
  right: 10px;
  z-index: 2001;
  background: transparent;
  border: none;
  width: auto;
  height: auto;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: all 0.2s ease;
  padding: 0;
}

.gm-ui-toggle i {
  color: #374151;
  font-size: 26px;
  text-shadow: 0 1px 3px rgba(0,0,0,0.3);
  transition: all 0.2s ease;
}

.gm-ui-toggle:hover i {
  color: #031273;
  transform: scale(1.1);
  text-shadow: 0 2px 6px rgba(3,18,115,0.4);
}

/* UI Hidden State - Hide all UI except toggle button */
body.ui-hidden .gm-status-bar,
body.ui-hidden .gm-controls,
body.ui-hidden .gm-back-button,
body.ui-hidden .leaflet-control-zoom {
  display: none !important;
}

/* Toggle button always visible */
body.ui-hidden .gm-ui-toggle {
  display: flex !important;
}

/* Back button styling */
.gm-back-button {
  position: absolute;
  bottom: 10px;
  left: 10px;
  z-index: 2000;
  background: #ffffff;
  border: 2px solid #e5e7eb;
  border-radius: 50%;
  width: 48px;
  height: 48px;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  box-shadow: 0 2px 6px rgba(0,0,0,0.15);
  transition: all 0.2s ease;
  color: #374151;
  font-size: 20px;
}

.gm-back-button:hover {
  background: #f3f4f6;
  border-color: #d1d5db;
  transform: scale(1.05);
  box-shadow: 0 4px 8px rgba(0,0,0,0.2);
}

.gm-status-item { 
  display: flex; 
  align-items: center; 
  gap: 6px; 
  font-size: 12px; 
  color: #333; 
}

.gm-status-icon { 
  width: 16px; 
  height: 16px; 
  border-radius: 2px; 
  display: flex; 
  align-items: center; 
  justify-content: center; 
  color: #ffffff; 
  font-size: 10px; 
}

.gm-status-green { 
  background: #4caf50; 
}

.gm-status-red { 
  background: #f44336; 
}

.gm-status-orange { 
  background: #ff9800; 
}

.gm-status-blue {
  background: #031273; /* Dark blue for active cases */
}

.gm-status-light-blue {
  background: #4CC9F0; /* Light blue for total cases (inactive) */
}

.gm-status-case-active {
  background: transparent; /* Transparent for custom pin icon */
  display: flex;
  align-items: center;
  justify-content: center;
  width: 20px;
  height: 20px;
}

.gm-status-case-inactive {
  background: transparent; /* Transparent for custom pin icon */
  display: flex;
  align-items: center;
  justify-content: center;
  width: 20px;
  height: 20px;
}

.gm-filter-panel { 
  display: none; 
  position: absolute; 
  top: 100%; 
  left: 0; 
  background: linear-gradient(135deg, #fef3e7 0%, #ffffff 100%); 
  border: 1px solid #f28c28; 
  box-shadow: 0 2px 4px rgba(242,140,40,0.1); 
  padding: 16px; 
  border-radius: 12px; 
  min-width: 300px; 
  width: max-content; 
  z-index: 2001; 
  transition: all 0.2s ease;
}

.gm-filter-panel:hover {
  transform: translateY(-1px);
  box-shadow: 0 4px 8px rgba(242,140,40,0.2);
  border-color: #f28c28;
  background: linear-gradient(135deg, #ffffff 0%, #fef3e7 100%);
}

.gm-filter-option { 
  display: flex; 
  align-items: center; 
  gap: 8px; 
  padding: 8px 12px; 
  font-size: 13px; 
  color: #1e293b; 
  cursor: pointer; 
  font-family: 'Roboto', Arial, sans-serif; 
  white-space: nowrap; 
  border-radius: 6px;
  transition: background-color 0.2s ease;
}

.gm-filter-option:hover { 
  background: rgba(3,18,115,0.1); 
  color: #031273;
}

/* Container for filter options to display them in a row */
.gm-filter-options-row {
  display: flex !important;
  flex-direction: row !important;
  gap: 8px;
  margin-bottom: 12px;
  width: 100%;
  align-items: stretch;
}

/* Make options inside the row container take equal width */
.gm-filter-options-row > .gm-filter-option {
  flex: 1 1 0;
  min-width: 0;
}

.gm-filter-option input[type="radio"] { 
  margin: 0; 
}

.gm-filter-search { 
  width: 100%; 
  padding: 10px 12px; 
  border: 2px solid #e5e7eb; 
  border-radius: 8px; 
  font-size: 13px; 
  margin-bottom: 12px; 
  background: rgba(255,255,255,0.8);
  transition: border-color 0.2s ease;
}

.gm-filter-search:focus {
  outline: none;
  border-color: #031273;
  background: rgba(255,255,255,0.95);
}

.gm-filter-pagination { 
  display: flex; 
  align-items: center; 
  justify-content: space-between; 
  margin-top: 12px; 
  padding-top: 12px; 
  border-top: 1px solid rgba(3,18,115,0.1); 
}

.gm-pagination-btn { 
  background: rgba(3,18,115,0.1); 
  border: 1px solid rgba(3,18,115,0.2); 
  border-radius: 6px; 
  padding: 6px 10px; 
  cursor: pointer; 
  font-size: 12px; 
  color: #031273; 
  display: flex; 
  align-items: center; 
  gap: 4px; 
  transition: all 0.2s ease;
  font-weight: 600;
}

.gm-pagination-btn:hover { 
  background: rgba(3,18,115,0.2); 
  border-color: rgba(3,18,115,0.3);
}

.gm-pagination-btn:disabled { 
  opacity: 0.5; 
  cursor: not-allowed; 
  background: rgba(0,0,0,0.05);
  border-color: rgba(0,0,0,0.1);
  color: #6b7280;
}

.gm-pagination-info { 
  font-size: 12px; 
  color: #64748b; 
  font-weight: 600; 
}

.gm-filter-apply-container {
  display: flex;
  justify-content: flex-end;
  margin-top: 12px;
  padding-top: 8px;
  border-top: 1px solid rgba(3,18,115,0.1);
}

.gm-filter-apply-btn {
  background: linear-gradient(135deg, #031273 0%, #1e40af 100%);
  color: #ffffff;
  border: none;
  border-radius: 6px;
  padding: 8px 16px;
  font-size: 12px;
  font-weight: 700;
  cursor: pointer;
  transition: all 0.2s ease;
  box-shadow: 0 2px 4px rgba(3,18,115,0.2);
}

.gm-filter-apply-btn:hover {
  background: linear-gradient(135deg, #1e40af 0%, #031273 100%);
  transform: scale(1.05);
  box-shadow: 0 4px 12px rgba(3,18,115,0.4);
}

/* Side panel for pin information */
.gm-side-panel { 
  position: fixed; 
  top: 0; 
  right: -420px; 
  width: 420px; 
  height: 100vh; 
  background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%); 
  box-shadow: -4px 0 20px rgba(0,0,0,0.15), -2px 0 8px rgba(0,0,0,0.1); 
  z-index: 3000; 
  transition: right 0.3s cubic-bezier(0.4, 0, 0.2, 1); 
  overflow: hidden; 
  display: flex;
  flex-direction: column;
}

.gm-side-panel.open { 
  right: 0; 
}

.gm-panel-header { 
  padding: 16px 20px; 
  background: linear-gradient(135deg, #031273 0%, #1e40af 100%); 
  color: #ffffff; 
  display: flex; 
  justify-content: space-between; 
  align-items: center; 
  position: relative;
  box-shadow: 0 2px 8px rgba(3,18,115,0.3);
  flex-shrink: 0;
}

.gm-panel-title { 
  font-size: 20px; 
  font-weight: 800; 
  color: #ffffff; 
  margin: 0; 
  display: flex;
  align-items: center;
  gap: 8px;
}

.gm-panel-title i {
  font-size: 18px;
  color: rgba(255,255,255,0.9);
}

.gm-panel-close { 
  background: rgba(255,255,255,0.2); 
  border: 2px solid rgba(255,255,255,0.3); 
  font-size: 18px; 
  cursor: pointer; 
  color: #ffffff; 
  padding: 0; 
  width: 36px; 
  height: 36px; 
  display: flex; 
  align-items: center; 
  justify-content: center; 
  border-radius: 50%;
  transition: all 0.2s ease;
}

.gm-panel-close:hover { 
  background: rgba(255,255,255,0.3); 
  border-color: rgba(255,255,255,0.5);
  transform: scale(1.05);
}

.gm-panel-content { 
  padding: 16px 20px; 
  background: linear-gradient(135deg, #fef3e7 0%, #ffffff 100%);
  flex: 1;
  overflow: hidden;
  display: flex;
  flex-direction: column;
}

.gm-info-item { 
  margin-bottom: 20px; 
  padding: 16px; 
  background: linear-gradient(135deg, #fef3e7 0%, #ffffff 100%); 
  border: 1px solid #f28c28; 
  border-radius: 12px; 
  box-shadow: 0 2px 4px rgba(242,140,40,0.1);
  transition: all 0.2s ease;
}

.gm-info-item:hover {
  transform: translateY(-1px);
  box-shadow: 0 4px 8px rgba(242,140,40,0.2);
  border-color: #f28c28;
  background: linear-gradient(135deg, #ffffff 0%, #fef3e7 100%);
}

.gm-info-label { 
  font-size: 11px; 
  color: #64748b; 
  font-weight: 700; 
  margin-bottom: 6px; 
  text-transform: uppercase; 
  letter-spacing: 0.8px; 
  display: flex;
  align-items: center;
  gap: 6px;
}

.gm-info-label i {
  color: #f28c28;
  font-size: 12px;
}

.gm-info-value { 
  font-size: 15px; 
  color: #1e293b; 
  font-weight: 600; 
  line-height: 1.4;
}

.gm-status-badge { 
  display: inline-flex; 
  align-items: center;
  gap: 4px;
  padding: 6px 12px; 
  border-radius: 20px; 
  font-size: 11px; 
  font-weight: 700; 
  text-transform: uppercase; 
  letter-spacing: 0.5px;
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.gm-status-online { 
  background: linear-gradient(135deg, #10b981 0%, #059669 100%); 
  color: #ffffff; 
}

.gm-status-offline { 
  background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); 
  color: #ffffff; 
}

.gm-status-pending { 
  background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); 
  color: #ffffff; 
}

/* Minimap styles */
.minimap-container { 
  position: relative; 
  width: 100%; 
  height: 100%; 
  border: 2px solid #e5e7eb; 
  border-radius: 8px; 
  overflow: hidden; 
  background: #f8fafc;
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.minimap-container .leaflet-container {
  border-radius: 6px;
  height: 100% !important;
  width: 100% !important;
}

.minimap-case-marker,
.minimap-ambulance-marker {
  animation: pulse 2s infinite;
}

@keyframes pulse {
  0% { transform: scale(1); }
  50% { transform: scale(1.1); }
  100% { transform: scale(1); }
}

/* Hospital Marker Wrapper */
.hospital-marker-wrapper {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 4px;
  text-align: center;
}

/* Hospital Label Below Icon */
.hospital-label {
  background: #ffffff;
  color: #dc2626;
  font-size: 10px;
  font-weight: 800;
  padding: 2px 6px;
  border-radius: 4px;
  white-space: nowrap;
  box-shadow: 0 2px 4px rgba(0,0,0,0.2);
  border: 1px solid rgba(220,38,38,0.2);
  font-family: 'Roboto', Arial, sans-serif;
  letter-spacing: 0.5px;
  max-width: 60px;
  overflow: hidden;
  text-overflow: ellipsis;
}

/* Hospital Icon - Pure CSS Cross/Plus Shape */
.hospital-marker-icon {
  position: relative !important;
}

/* Hospital Hover Tooltip */
.hospital-hover-tooltip {
  position: absolute;
  bottom: 100%;
  left: 50%;
  transform: translateX(-50%) translateY(-8px);
  background: rgba(0, 0, 0, 0.85);
  color: #ffffff;
  padding: 6px 10px;
  border-radius: 6px;
  font-size: 11px;
  font-weight: 600;
  white-space: nowrap;
  opacity: 0;
  pointer-events: none;
  transition: opacity 0.2s ease, transform 0.2s ease;
  z-index: 1000;
  box-shadow: 0 2px 8px rgba(0,0,0,0.3);
  font-family: 'Roboto', Arial, sans-serif;
  max-width: 200px;
  overflow: hidden;
  text-overflow: ellipsis;
}

.hospital-hover-tooltip::after {
  content: '';
  position: absolute;
  top: 100%;
  left: 50%;
  transform: translateX(-50%);
  width: 0;
  height: 0;
  border-left: 6px solid transparent;
  border-right: 6px solid transparent;
  border-top: 6px solid rgba(0, 0, 0, 0.85);
}

.hospital-marker-icon:hover .hospital-hover-tooltip {
  opacity: 1;
  transform: translateX(-50%) translateY(-12px);
  pointer-events: auto;
}

.hospital-icon-css {
  width: 16px !important;
  height: 16px !important;
  position: relative !important;
  display: inline-block !important;
  flex-shrink: 0 !important;
  z-index: 10 !important;
}

/* Horizontal line of cross */
.hospital-icon-css::before {
  content: '' !important;
  position: absolute !important;
  top: 50% !important;
  left: 0 !important;
  transform: translateY(-50%) !important;
  width: 16px !important;
  height: 3px !important;
  background: #ffffff !important;
  display: block !important;
  z-index: 11 !important;
  border-radius: 1px !important;
  box-shadow: 0 1px 3px rgba(0,0,0,0.4) !important;
}

/* Vertical line of cross */
.hospital-icon-css::after {
  content: '' !important;
  position: absolute !important;
  top: 0 !important;
  left: 50% !important;
  transform: translateX(-50%) !important;
  width: 3px !important;
  height: 16px !important;
  background: #ffffff !important;
  display: block !important;
  z-index: 11 !important;
  border-radius: 1px !important;
  box-shadow: 0 1px 3px rgba(0,0,0,0.4) !important;
}

.hospital-popup-icon {
  background: #dc2626;
  position: relative;
  display: inline-block;
  width: 18px;
  height: 18px;
}

/* Horizontal line of cross for popup */
.hospital-popup-icon::before {
  content: '';
  position: absolute;
  top: 50%;
  left: 0;
  transform: translateY(-50%);
  width: 18px;
  height: 3px;
  background: #ffffff;
  display: block;
  z-index: 2;
  border-radius: 1px;
  box-shadow: 0 1px 3px rgba(0,0,0,0.4);
}

/* Vertical line of cross for popup */
.hospital-popup-icon::after {
  content: '';
  position: absolute;
  top: 0;
  left: 50%;
  transform: translateX(-50%);
  width: 3px;
  height: 18px;
  background: #ffffff;
  display: block;
  z-index: 2;
  border-radius: 1px;
  box-shadow: 0 1px 3px rgba(0,0,0,0.4);
}

/* Driver Icon Container - Circle with gradient background */
.driver-icon-container {
  position: relative;
}

/* Driver Icon - Simple User Icon inside circle */
.driver-icon-css {
  width: 160px;
  height: 160px;
  position: relative;
  background: transparent;
  display: flex;
  align-items: center;
  justify-content: center;
}

/* User Head */
.driver-icon-css::before {
  content: '';
  position: absolute;
  top: 25%;
  left: 50%;
  transform: translateX(-50%);
  width: 70px;
  height: 70px;
  background: #ffffff;
  border-radius: 50%;
  box-shadow: 0 2px 8px rgba(0,0,0,0.2);
  z-index: 2;
}

/* User Body */
.driver-icon-css::after {
  content: '';
  position: absolute;
  top: 55%;
  left: 50%;
  transform: translateX(-50%);
  width: 90px;
  height: 100px;
  background: #ffffff;
  border-radius: 45px 45px 0 0;
  box-shadow: 0 2px 8px rgba(0,0,0,0.2);
  z-index: 1;
}

/* MDRRMO Base Popup Styling */
.mdrrmo-base-popup {
  margin-bottom: 70px; /* Space above marker */
}

.mdrrmo-base-popup .leaflet-popup-tip {
  margin-top: 0;
  margin-bottom: -1px;
}

@media (max-width: 1024px) {
  .dashboard-left-panel {
    width: 280px;
  }
  .map-wrapper {
    width: calc(100vw - 280px);
    margin-left: 280px;
  }
}

@media (max-width: 640px) {
  .header-inner { flex-wrap:wrap; }
  .header-side { flex:1 1 40%; }
  .header-title { flex:1 0 100%; order:-1; margin-bottom:8px; }
  .dashboard-left-panel {
    width: 100vw;
    transform: translateX(-100%);
    transition: transform 0.3s ease;
  }
  .dashboard-left-panel.open {
    transform: translateX(0);
  }
  .map-wrapper {
    width: 100vw;
    margin-left: 0;
  }
  .gm-side-panel { width: 100vw; right: -100vw; }
  .gm-status-bar.panel-open { right: 0; }
  .gm-controls.panel-open { left: 10px; }
  .leaflet-control-zoom.panel-open { right: 10px !important; }
}
  </style>
  <body class="has-left-panel">
    <!-- Super Admin Presence Banner -->
    <div id="superadmin-banner" style="display: none; position: fixed; top: 0; left: 320px; right: 0; background: linear-gradient(135deg, #1e40af, #1e3a8a); color: white; padding: 10px 20px; text-align: center; font-weight: 600; font-size: 14px; z-index: 10000; box-shadow: 0 2px 8px rgba(0,0,0,0.2);">
        <i class="fas fa-user-shield" style="margin-right: 8px;"></i>
        <span>Super Admin Active</span>
    </div>
    
    <!-- Left Dashboard Panel -->
    <div class="dashboard-left-panel">
      <div class="dashboard-panel-header">
        <img src="{{ asset('image/mdrrmologo.jpg') }}" alt="MDRRMO Logo" class="dashboard-panel-logo">
        <h3 class="dashboard-panel-title">SILANG MDRRMO</h3>
      </div>
      <div class="dashboard-overview-text">
        <h3>Overview</h3>
      </div>
      <div class="dashboard-panel-content">
        <!-- Active Drivers Card -->
        <div class="dashboard-metric-card">
          <div class="dashboard-metric-header">
            <div class="dashboard-metric-left">
              <div class="dashboard-metric-title">
                <i class="fas fa-user-check"></i>
                Active Drivers
              </div>
              <div class="dashboard-metric-value" id="leftPanelActiveDrivers">0</div>
              <div class="dashboard-metric-subtitle">With active cases</div>
            </div>
            <div class="dashboard-metric-icon drivers">
              <i class="fas fa-user-check"></i>
            </div>
          </div>
        </div>
        
        <!-- Total Ambulances Card -->
        <div class="dashboard-metric-card">
          <div class="dashboard-metric-header">
            <div class="dashboard-metric-left">
              <div class="dashboard-metric-title">
                <i class="fas fa-ambulance"></i>
                Total Ambulances
              </div>
              <div class="dashboard-metric-value" id="leftPanelTotalAmbulances">0</div>
              <div class="dashboard-metric-subtitle">All registered units</div>
            </div>
            <div class="dashboard-metric-icon ambulances">
              <i class="fas fa-ambulance"></i>
            </div>
          </div>
        </div>
        
        <!-- Active Cases Card -->
        <div class="dashboard-metric-card">
          <div class="dashboard-metric-header">
            <div class="dashboard-metric-left">
              <div class="dashboard-metric-title">
                <i class="fas fa-map-marker-alt"></i>
                Active Cases
              </div>
              <div class="dashboard-metric-value" id="leftPanelActiveCases">0</div>
              <div class="dashboard-metric-subtitle">In progress</div>
            </div>
            <div class="dashboard-metric-icon cases">
              <i class="fas fa-map-marker-alt"></i>
            </div>
          </div>
        </div>
        
        <!-- Available Drivers Card -->
        <div class="dashboard-metric-card">
          <div class="dashboard-metric-header">
            <div class="dashboard-metric-left">
              <div class="dashboard-metric-title">
                <i class="fas fa-user-clock"></i>
                Available Drivers
              </div>
              <div class="dashboard-metric-value" id="leftPanelAvailableDrivers">0</div>
              <div class="dashboard-metric-subtitle">Online without cases</div>
            </div>
            <div class="dashboard-metric-icon available">
              <i class="fas fa-user-clock"></i>
            </div>
          </div>
        </div>
        
        <!-- Hotline Card -->
        <div class="dashboard-hotline-card">
          <div class="dashboard-hotline-header">
            <div class="dashboard-hotline-icon">
              <i class="fas fa-phone-alt"></i>
            </div>
            <div class="dashboard-hotline-content">
              <div class="dashboard-hotline-title">
                <i class="fas fa-phone"></i>
                MDRRMO-Silang
              </div>
              <div class="dashboard-hotline-number">Globe: 0935-601-6738</div>
              <div class="dashboard-hotline-subtitle">24/7 Emergency Response</div>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <div class="map-wrapper">
      <div class="gm-status-bar">
        <div class="gm-status-item">
          <div class="gm-status-icon gm-status-case-inactive">
            <i class="fas fa-map-marker-alt" style="font-size:16px; color:#9ca3af; text-shadow:0 1px 2px rgba(0,0,0,0.2);"></i>
          </div>
          <span>Total Cases: <span id="totalCases">0</span></span>
        </div>
        <div class="gm-status-item">
          <div class="gm-status-icon gm-status-red"><i class="fas fa-ambulance"></i></div>
          <span>Total Ambulances: <span id="totalAmbulances">0</span></span>
        </div>
        <div class="gm-status-item">
          <div class="gm-status-icon gm-status-case-active">
            <i class="fas fa-map-marker-alt" style="font-size:16px; color:#f28c28; text-shadow:0 1px 2px rgba(0,0,0,0.2);"></i>
          </div>
          <span>Active Cases: <span id="activeCases">0</span></span>
        </div>
      </div>
      
      <!-- UI Hide/Show Toggle Button -->
      <button id="uiToggle" class="gm-ui-toggle" title="Hide/Show UI">
        <i class="fas fa-eye" id="uiToggleIcon"></i>
      </button>
      
      <div class="gm-controls">
        <div class="gm-control-group">
          <button id="filterToggle" class="gm-control-btn">
            <i class="fas fa-filter"></i>
            <span>Filter</span>
          </button>
          <div id="filterPanel" class="gm-filter-panel">
            <form id="filterForm">
              <!-- Search Input -->
              <input type="text" id="filterSearch" class="gm-filter-search" placeholder="Search ambulances..." autocomplete="off">
              
              <div class="gm-filter-options-row">
                <label class="gm-filter-option">
                  <input type="radio" name="ambFilter" value="all" checked> All ambulances
                </label>
                <label class="gm-filter-option">
                  <input type="radio" name="ambFilter" value="active-cases"> Active Cases
                </label>
              </div>
              <div id="filterAmbList"></div>
              
              <!-- Pagination Controls -->
              <div class="gm-filter-pagination">
                <button type="button" id="filterPrev" class="gm-pagination-btn" disabled>
                  <i class="fas fa-chevron-left"></i> Back
                </button>
                <button type="button" id="filterNext" class="gm-pagination-btn" disabled>
                  Next <i class="fas fa-chevron-right"></i>
                </button>
              </div>
              
              <div class="gm-filter-apply-container">
                <button type="button" id="filterApply" class="gm-filter-apply-btn">Apply</button>
              </div>
            </form>
          </div>
        </div>
        </div>
        
        <!-- Side panel for pin information -->
        <div id="sidePanel" class="gm-side-panel">
          <div class="gm-panel-header">
            <h3 class="gm-panel-title" id="panelTitle">Pin Information</h3>
            <button class="gm-panel-close" id="panelClose">&times;</button>
          </div>
          <div class="gm-panel-content" id="panelContent">
            <!-- Content will be populated dynamically -->
          </div>
        </div>
        
        <div id="readonly-map" role="img" aria-label="Read-only map showing driver locations"></div>
        
        <!-- Back Button -->
        <button class="gm-back-button" id="backButton" title="Back to Dashboard">
          <i class="fas fa-arrow-left"></i>
        </button>
      </div>
    
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
      (function(){
        const center = [14.2154, 120.9714]; // Silang, Cavite
        const map = L.map('readonly-map', {
          zoomControl: false,
          dragging: true,
          scrollWheelZoom: true,
          doubleClickZoom: true,
          boxZoom: true,
          keyboard: true,
          tap: true,
        }).setView(center, 13);
        
        // Add custom zoom control in bottom-right
        const zoomControl = L.control.zoom({
          position: 'bottomright'
        }).addTo(map);
        
        // Store reference to zoom control for positioning
        window.zoomControl = zoomControl;
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
          attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        let driverMarkers = {};
        let caseMarkers = {};
        let connectionLines = {};
        let driverTraces = {};
        let driverToCaseTraces = {};
        let driverToBaseTraces = {}; // Store trails from driver to base for completed cases
        let destinationMarkers = {};
        let caseDestinationMarkers = {};
        let ambIdToCaseNum = {};
        let ambulancesCache = [];
        let ambulanceDataMap = {}; // Store ambulance data by ID for navigation checking
        let currentFilterAmbId = 'all';
        let hospitalMarkers = {}; // Store hospital markers
        let totalCasesCount = 0;
        let activeCasesCount = 0;
        const baseLat = 14.240252979236983;
        const baseLng = 120.9792923735777;
        let sidePanel = null;
        let panelTitle = null;
        let panelContent = null;
        let showActiveOnly = false; // when true, show only drivers with active cases and only those cases
        let activeAmbulanceIds = new Set(); // Set of ambulance IDs that currently have an active case
        let currentActiveCaseNums = new Set(); // Set of case numbers considered "current active" per ambulance
        let isActiveModeApplying = false; // guard to prevent race/double-apply for Active Cases
        let isUserInteracting = false; // Track if user is manually interacting with map
        let autoFitBoundsEnabled = true; // Enable/disable auto-fit bounds
        let lastAutoFitBounds = null; // Store last bounds to avoid unnecessary refits
        
        // Filter pagination variables
        let filterCurrentPage = 1;
        let filterItemsPerPage = 10;
        let filterTotalPages = 1;
        let filterSearchTerm = '';
        let filterAllAmbulances = [];
        let filterFilteredAmbulances = [];

        // Auto-fit map bounds to show all visible ambulances and cases
        function autoFitMapBounds() {
          if (!autoFitBoundsEnabled || isUserInteracting) return;
          
          try {
            const visibleMarkers = [];
            
            // Collect all visible ambulance markers
            Object.values(driverMarkers || {}).forEach(marker => {
              if (marker && map.hasLayer(marker)) {
                const latLng = marker.getLatLng();
                if (latLng && !Number.isNaN(latLng.lat) && !Number.isNaN(latLng.lng)) {
                  visibleMarkers.push(latLng);
                }
              }
            });
            
            // Collect all visible case markers
            Object.values(caseMarkers || {}).forEach(marker => {
              if (marker && map.hasLayer(marker)) {
                const latLng = marker.getLatLng();
                if (latLng && !Number.isNaN(latLng.lat) && !Number.isNaN(latLng.lng)) {
                  visibleMarkers.push(latLng);
                }
              }
            });
            
            // If we have markers, fit bounds with padding
            if (visibleMarkers.length > 0) {
              const group = new L.featureGroup(visibleMarkers.map(ll => L.marker(ll)));
              const bounds = group.getBounds();
              
              // Only auto-fit if we have at least 1 marker
              // If only 1 marker, zoom to a reasonable level (zoom 14)
              if (visibleMarkers.length === 1) {
                map.setView(visibleMarkers[0], 14, { animate: true, duration: 0.8 });
              } else {
                // Calculate distance between markers to determine if they're very far apart
                const northEast = bounds.getNorthEast();
                const southWest = bounds.getSouthWest();
                const latDiff = Math.abs(northEast.lat - southWest.lat);
                const lngDiff = Math.abs(northEast.lng - southWest.lng);
                
                // If markers are very far apart (e.g., one in Cavite, one in Manila)
                // Remove maxZoom restriction to allow full zoom out
                // For close markers, limit max zoom to 16 to prevent zooming in too much
                const fitOptions = {
                  animate: true,
                  duration: 0.8,
                  padding: [20, 20] // Add padding in pixels
                };
                
                // Only set maxZoom for close markers (within 0.3 degrees ≈ 33km)
                if (latDiff <= 0.3 && lngDiff <= 0.3) {
                  fitOptions.maxZoom = 16; // Limit zoom for close markers
                }
                // For distant markers, don't set maxZoom - allow full zoom out to show all
                
                map.fitBounds(bounds.pad(0.1), fitOptions);
              }
              
              lastAutoFitBounds = bounds;
            }
          } catch (e) {
            console.warn('Error auto-fitting bounds:', e);
          }
        }

        // ===== Visibility control for driver markers depending on selection =====
        function applyDriverVisibility(){
          try {
            const isAll = String(currentFilterAmbId || 'all') === 'all';
            Object.keys(driverMarkers || {}).forEach((ambIdStr) => {
              const ambId = parseInt(ambIdStr);
              const marker = driverMarkers[ambIdStr];
              if (!marker) return;
              let shouldShow = true;
              if (showActiveOnly){
                // In active-only mode, only show drivers who have an active case
                shouldShow = activeAmbulanceIds && activeAmbulanceIds.has(ambId);
              }
              if (!isAll && String(ambId) !== String(currentFilterAmbId)) {
                shouldShow = false;
              }
              // If in active-only as well, both conditions must pass
              if (showActiveOnly && isAll){ 
                shouldShow = shouldShow && activeAmbulanceIds && activeAmbulanceIds.has(ambId); 
              }
              const onMap = map.hasLayer(marker);
              if (shouldShow && !onMap) { try { marker.addTo(map); } catch(_){} }
              if (!shouldShow && onMap) { try { map.removeLayer(marker); } catch(_){} }
            });
            
            // Ensure hospitals and base marker are always visible (not affected by filter)
            ensureStaticMarkersVisible();
          } catch(_){}
        }
        
        // Ensure hospitals and base marker are always visible (regardless of filter)
        function ensureStaticMarkersVisible() {
          try {
            // Ensure all hospital markers are visible
            Object.values(hospitalMarkers || {}).forEach(marker => {
              if (marker && !map.hasLayer(marker)) {
                try { marker.addTo(map); } catch(_){}
              }
            });
            
            // Ensure base marker is visible
            if (baseMarker && !map.hasLayer(baseMarker)) {
              try { baseMarker.addTo(map); } catch(_){}
            }
          } catch(_){}
        }

        function getFirstName(fullName) {
          if (!fullName || typeof fullName !== 'string') return '';
          const parts = fullName.trim().split(/\s+/);
          return parts[0] || fullName;
        }

        function createDriverIcon(label) {
          // 3/4 perspective ambulance (matches your image reference)
          const svg = `
            <svg width="60" height="40" viewBox="0 0 320 200" xmlns="http://www.w3.org/2000/svg">
              <defs>
                <linearGradient id="gBody" x1="0" y1="0" x2="0" y2="1">
                  <stop offset="0%" stop-color="#ffffff"/>
                  <stop offset="100%" stop-color="#eaf2ff"/>
                </linearGradient>
                <filter id="drop" x="-20%" y="-20%" width="140%" height="140%">
                  <feDropShadow dx="0" dy="2" stdDeviation="3" flood-color="#000" flood-opacity="0.25"/>
                </filter>
              </defs>
              <g filter="url(#drop)">
                <!-- base -->
                <path d="M30 160 L260 160 285 150 295 110 260 90 250 60 110 60 90 40 45 45 30 100 Z" fill="url(#gBody)" stroke="#c7d2e5" stroke-width="6"/>
                <!-- roof -->
                <path d="M90 40 L110 60 250 60 255 50 220 50 220 40 Z" fill="#e74d46"/>
                <!-- red stripes -->
                <path d="M35 120 L280 120 288 115 286 105 40 105 Z" fill="#e74d46"/>
                <path d="M45 135 L270 135 278 130 277 125 50 125 Z" fill="#e74d46" opacity=".9"/>
                <!-- front windshield and window -->
                <path d="M205 60 L245 60 255 90 210 85 Z" fill="#1f2937"/>
                <rect x="145" y="65" width="48" height="26" fill="#1f2937" rx="4"/>
                <!-- bumper -->
                <rect x="52" y="150" width="46" height="10" rx="2" fill="#111827"/>
                <!-- AMBULANCE text top -->
                <text x="110" y="50" font-family="Arial,Helvetica,sans-serif" font-size="22" font-weight="900" fill="#e74d46">AMBULANCE</text>
                <!-- side label -->
                <text x="70" y="115" font-family="Arial,Helvetica,sans-serif" font-size="20" font-weight="900" fill="#e74d46">AMBULANCE</text>
                <!-- wheels -->
                <circle cx="95" cy="162" r="20" fill="#111827"/>
                <circle cx="95" cy="162" r="9" fill="#9ca3af"/>
                <circle cx="235" cy="162" r="20" fill="#111827"/>
                <circle cx="235" cy="162" r="9" fill="#9ca3af"/>
                <!-- light bar -->
                <rect x="170" y="36" width="36" height="8" rx="4" fill="#ef4444"/>
              </g>
            </svg>
          `;
          const html = `
            <div class="driver-marker">
              <div style="display:flex; align-items:center; justify-content:center; width:60px; height:40px;">${svg}</div>
              <div class="driver-badge">${label}</div>
            </div>
          `;
          return L.divIcon({ className: '', html, iconSize: [1,1], iconAnchor: [30, 46] });
        }

        function createDestinationIcon(caseNum) {
          const html = `
            <div class="driver-marker">
              <div style="width:20px; height:20px; background:#10b981; color:#fff; border-radius:6px; display:flex; align-items:center; justify-content:center; box-shadow:0 4px 10px rgba(16,185,129,0.35);">
                <i class="fas fa-flag-checkered" style="font-size:11px;"></i>
              </div>
              <div class="case-badge" style="background:#065f46">Destination<br></div>
            </div>
          `;
          return L.divIcon({ className: '', html, iconSize: [1,1], iconAnchor: [10, 26] });
        }

        // Case marker icon - pin icon only (no square background)
        // isActive: true = ambulance is OTW (orange icon), false = pending (gray icon)
        function createCaseIcon(caseNum, isActive = false) {
          const label = caseNum ? `Case #${caseNum}` : 'Case';
          // Different icon colors: orange for active, gray for inactive
          const iconColor = isActive ? '#f28c28' : '#9ca3af'; // Orange for active (MDRRMO brand orange), gray for inactive
          const html = `
            <div class="driver-marker">
              <i class="fas fa-map-marker-alt" style="font-size:24px; color:${iconColor}; text-shadow:0 2px 4px rgba(0,0,0,0.3);"></i>
              <div class="case-badge" style="background:#031273">${label}</div>
            </div>
          `;
          return L.divIcon({ className: '', html, iconSize: [1,1], iconAnchor: [12, 36] });
        }

        // Function to abbreviate hospital name
        function abbreviateHospitalName(fullName) {
          if (!fullName || typeof fullName !== 'string') return 'H';
          
          // Remove common words that shouldn't be abbreviated
          const commonWords = ['of', 'the', 'ng', 'and', 'at'];
          const words = fullName.trim().split(/\s+/).filter(w => w.length > 0);
          
          if (words.length === 1) {
            // Single word: take first 3-4 letters
            return fullName.substring(0, 4).toUpperCase();
          }
          
          // Multiple words: take first letter of each word, but skip common words
          const importantWords = words.filter(w => !commonWords.includes(w.toLowerCase()));
          
          if (importantWords.length <= 3) {
            // Take all first letters if 3 or fewer important words
            return importantWords.map(w => w[0].toUpperCase()).join('');
          } else {
            // Take first 3 words' first letters
            return importantWords.slice(0, 3).map(w => w[0].toUpperCase()).join('');
          }
        }

        // Hospital marker icon with label below - Pure CSS icon
        function createHospitalIcon(hospitalName) {
          const abbreviation = abbreviateHospitalName(hospitalName);
          const html = `
            <div class="hospital-marker-wrapper">
              <div class="hospital-marker-icon" data-full-name="${hospitalName || 'Hospital'}" style="width:32px; height:32px; background:#dc2626; border-radius:50%; display:flex; align-items:center; justify-content:center; box-shadow:0 4px 12px rgba(220,38,38,0.5); border:3px solid #fff; cursor:pointer; position:relative; margin: 0 auto;">
                <div class="hospital-icon-css"></div>
                <div class="hospital-hover-tooltip">${hospitalName || 'Hospital'}</div>
              </div>
              <div class="hospital-label">${abbreviation}</div>
            </div>
          `;
          // Adjust icon anchor: iconSize accounts for label below, anchor centers the icon circle on coordinates
          return L.divIcon({ className: 'hospital-div-icon', html, iconSize: [50, 50], iconAnchor: [25, 32] });
        }

        // Base marker icon (for SILANG MDRRMO base location)
        function createBaseIcon() {
          const html = `
            <div style="position: relative;">
              <div style="width:48px; height:48px; background: linear-gradient(135deg, #031273 0%, #0c2d5a 100%); color:#fff; border-radius:50%; display:flex; align-items:center; justify-content:center; box-shadow:0 4px 16px rgba(3,18,115,0.6), 0 0 0 4px rgba(255,255,255,0.95), 0 0 0 5px rgba(242,140,40,0.4); border:3px solid #fff; cursor:pointer; position: relative;">
                <i class="fas fa-landmark" style="font-size:22px; text-shadow: 0 2px 4px rgba(0,0,0,0.3);"></i>
              </div>
              <div style="position: absolute; bottom: -10px; left: 50%; transform: translateX(-50%); background: #f28c28; color: #fff; padding: 3px 8px; border-radius: 6px; font-size: 9px; font-weight: 800; white-space: nowrap; box-shadow: 0 2px 8px rgba(242,140,40,0.5); border: 2px solid #fff; letter-spacing: 0.5px;">
                MDRRMO
              </div>
            </div>
          `;
          return L.divIcon({ className: '', html, iconSize: [48, 58], iconAnchor: [24, 58] });
        }

        async function loadAmbulances() {
          try {
            const res = await fetch('{{ route('admin.gps.data') }}', { headers: { 'Accept': 'application/json' } });
            if (!res.ok) return;
            const ambulances = await res.json();
            ambulancesCache = Array.isArray(ambulances) ? ambulances : [];
            // Clear and re-render markers for simplicity
            Object.values(driverMarkers).forEach(m => map.removeLayer(m));
            driverMarkers = {};
            Object.values(driverTraces).forEach(line => map.removeLayer(line));
            driverTraces = {};
            Object.values(destinationMarkers).forEach(m => map.removeLayer(m));
            destinationMarkers = {};
            Object.values(caseDestinationMarkers).forEach(m => map.removeLayer(m));
            caseDestinationMarkers = {};
            ambulanceDataMap = {}; // Clear ambulance data map
            // Rebuild filter list
            renderFilterOptions(ambulancesCache);
            // Update status counts
            updateStatusCounts();
            for (const a of ambulances) {
              if (!a) continue;
              let lat = parseFloat(a.latitude), lng = parseFloat(a.longitude);
              const hasGps = !(Number.isNaN(lat) || Number.isNaN(lng));
              if (!hasGps) { const c = map.getCenter(); lat = c.lat; lng = c.lng; }
              const ambName = a.name || a.ambulance_name || '';
              const driverName = a.driver_name || a.driver_first_name || '';
              const computed = ambName ? ambName : (driverName ? `Driver ${getFirstName(driverName)}` : 'Ambulance');
              const label = computed;
              const show = currentFilterAmbId === 'all' || String(a.id) === String(currentFilterAmbId);
              if (!show) continue;
              const marker = L.marker([lat, lng], { icon: createDriverIcon(label), interactive: true });
              marker.addTo(map);
              marker.ambulanceData = a; // Store ambulance data for click events
              marker.on('click', function() { showAmbulanceInfo(a); });
              driverMarkers[a.id] = marker;
              // Store ambulance data for navigation checking
              ambulanceDataMap[a.id] = a;
              // Destination handling removed; trails handled in loadActiveCases()
            }
            // Apply visibility rules after markers are created
            applyDriverVisibility();
            
            // Ensure static markers (hospitals, base) remain visible
            ensureStaticMarkersVisible();
            
            // Auto-fit bounds to show all visible ambulances
            setTimeout(() => autoFitMapBounds(), 300);
          } catch (e) { /* no-op for view-only */ }
        }

        async function loadActiveCases() {
          try {
            const res = await fetch('/admin/cases', { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } });
            if (!res.ok) return;
            const allCases = await res.json();
            const cases = (Array.isArray(allCases) ? allCases : []);

            Object.values(caseMarkers).forEach(m => map.removeLayer(m));
            caseMarkers = {};
            Object.values(connectionLines).forEach(l => map.removeLayer(l));
            connectionLines = {};
            Object.values(driverToCaseTraces).forEach(l => map.removeLayer(l));
            driverToCaseTraces = {};
            Object.values(driverToBaseTraces).forEach(l => map.removeLayer(l));
            driverToBaseTraces = {};
            ambIdToCaseNum = {};

            // 1) Render case markers; if a specific ambulance is selected, only show cases for that ambulance
            totalCasesCount = cases.length;
            // Active cases = cases accepted by driver/ambulance (not just non-completed)
            activeCasesCount = cases.filter(c => c.driver_accepted === true || c.status === 'In Progress' || c.status === 'Accepted').length;
            
            // Rebuild activeAmbulanceIds and currentActiveCaseNums snapshots
            try {
              activeAmbulanceIds.clear();
              currentActiveCaseNums.clear();
            } catch(_){}
            
            for (const c of cases) {
              const lat = parseFloat(c.latitude), lng = parseFloat(c.longitude);
              if (Number.isNaN(lat) || Number.isNaN(lng)) continue;
              const ambIdForCase = c.ambulance_id || (c.ambulance && (c.ambulance.id || c.ambulance.ambulance_id));
              
              // Track active ambulances and cases
              const isActive = (c.driver_accepted === true || c.status === 'In Progress' || c.status === 'Accepted');
              if (isActive && ambIdForCase) {
                activeAmbulanceIds.add(parseInt(ambIdForCase));
              }
              
              // Determine if this is the "current" case for its ambulance (same logic as trail picking)
              const showThisCase = String(currentFilterAmbId) === 'all' || (ambIdForCase && String(ambIdForCase) === String(currentFilterAmbId));
              if (!showThisCase) continue;
              
              // Skip completed cases
              if (c.status === 'Completed') continue;
              
              // If showActiveOnly is true, we'll show cases after determining current cases per ambulance
              if (showActiveOnly) {
                // Will be handled later when we determine current active cases
                continue;
              }
              
              // Show all non-completed cases in normal mode
              // Check if case is active (ambulance is OTW)
              const isActiveCase = (c.driver_accepted === true || c.status === 'In Progress' || c.status === 'Accepted') && ambIdForCase;
              const m = L.marker([lat, lng], { icon: createCaseIcon(c.case_num, isActiveCase), interactive: true, zIndexOffset: 800 });
              m.addTo(map);
              m.caseData = c; // Store case data for click events
              m.on('click', function() { showCaseInfo(c); });
              caseMarkers[c.case_num] = m;
            }
            
            // Update status counts
            updateStatusCounts();

            // 2) Determine the current case per ambulance (only draw lines for that case)
            const casesByAmb = {};
            for (const c of cases) {
              const ambId = c.ambulance_id || (c.ambulance && (c.ambulance.id || c.ambulance.ambulance_id));
              if (!ambId) continue;
              if (!casesByAmb[ambId]) casesByAmb[ambId] = [];
              if (String(currentFilterAmbId) !== 'all' && String(ambId) !== String(currentFilterAmbId)) continue;
              casesByAmb[ambId].push(c);
            }

            const pickCurrentCase = (list) => {
              if (!Array.isArray(list) || list.length === 0) return null;
              // Prefer driver_accepted true and non-completed; fallback to newest non-completed; else null
              const open = list.filter(x => (x.status || 'Pending') !== 'Completed');
              if (open.length === 0) return null;
              const accepted = open.filter(x => x.driver_accepted === true || x.status === 'In Progress' || x.status === 'Accepted');
              const pool = accepted.length ? accepted : open;
              pool.sort((a,b) => new Date(b.updated_at || b.created_at || 0) - new Date(a.updated_at || a.created_at || 0));
              return pool[0];
            };

            // 3) Draw ONLY the orange driver → current case trail per ambulance (no blue destination lines)
            for (const ambId of Object.keys(casesByAmb)) {
              // Respect ambulance filter for drawing lines (but markers remain visible)
              if (String(currentFilterAmbId) !== 'all' && String(ambId) !== String(currentFilterAmbId)) continue;
              const cur = pickCurrentCase(casesByAmb[ambId]);
              if (!cur) continue;
              const lat = parseFloat(cur.latitude), lng = parseFloat(cur.longitude);
              
              // Track this as the current active case for its ambulance
              currentActiveCaseNums.add(parseInt(cur.case_num));
              
              // If showActiveOnly is true, show this case marker
              if (showActiveOnly && !caseMarkers[cur.case_num]) {
                // Current case is always active (ambulance is OTW)
                const m = L.marker([lat, lng], { icon: createCaseIcon(cur.case_num, true), interactive: true, zIndexOffset: 800 });
                m.addTo(map);
                m.caseData = cur;
                m.on('click', function() { showCaseInfo(cur); });
                caseMarkers[cur.case_num] = m;
              }
              
              // Driver -> destination trail if driver position exists AND driver is actively navigating
              if (driverMarkers[ambId]) {
                // Check if driver is actively navigating (has destination coordinates)
                const ambData = ambulanceDataMap[ambId];
                const isNavigating = ambData && ambData.destination_latitude && ambData.destination_longitude;
                
                // Only draw trail if driver is actively navigating to a pin
                if (isNavigating) {
                  const driverLatLng = driverMarkers[ambId].getLatLng();
                  const destLat = parseFloat(ambData.destination_latitude);
                  const destLng = parseFloat(ambData.destination_longitude);
                  
                  if (driverLatLng && !Number.isNaN(driverLatLng.lat) && !Number.isNaN(driverLatLng.lng) && !Number.isNaN(destLat) && !Number.isNaN(destLng)) {
                    const routedTrail = await drawRoutedLine([driverLatLng.lat, driverLatLng.lng], [destLat, destLng], { color: '#ff8c42', weight: 6, opacity: 0.95 });
                    driverToCaseTraces[cur.case_num] = routedTrail;
                  }
                }
              }

              ambIdToCaseNum[ambId] = cur.case_num;
            }
            
            // If showActiveOnly, hide case markers that aren't in currentActiveCaseNums
            if (showActiveOnly) {
              Object.keys(caseMarkers).forEach(caseNum => {
                const marker = caseMarkers[caseNum];
                const onMap = map.hasLayer(marker);
                const shouldShow = currentActiveCaseNums.has(parseInt(caseNum));
                if (shouldShow && !onMap) { try { marker.addTo(map); } catch(_){} }
                if (!shouldShow && onMap) { try { map.removeLayer(marker); } catch(_){} }
              });
            }

            // 4) Remove any leftover destination markers, we no longer use them
            Object.values(destinationMarkers).forEach(m => map.removeLayer(m));
            destinationMarkers = {};
            Object.values(caseDestinationMarkers).forEach(m => map.removeLayer(m));
            caseDestinationMarkers = {};
            
            // 5) Draw trails from drivers to base for completed cases
            // Check for drivers who have completed cases and draw trail to base
            for (const c of cases) {
              if (c.status !== 'Completed') continue;
              const ambIdForCase = c.ambulance_id || (c.ambulance && (c.ambulance.id || c.ambulance.ambulance_id));
              if (!ambIdForCase) continue;
              
              // Respect ambulance filter
              if (String(currentFilterAmbId) !== 'all' && String(ambIdForCase) !== String(currentFilterAmbId)) continue;
              
              // Check if driver marker exists and has GPS data
              if (driverMarkers[ambIdForCase]) {
                const driverLatLng = driverMarkers[ambIdForCase].getLatLng();
                if (driverLatLng && !Number.isNaN(driverLatLng.lat) && !Number.isNaN(driverLatLng.lng)) {
                  // Draw trail from driver to base (blue color to indicate return to base)
                  const baseTrail = await drawRoutedLine(
                    [driverLatLng.lat, driverLatLng.lng], 
                    [baseLat, baseLng], 
                    { color: '#2563eb', weight: 5, opacity: 0.8, dashArray: '10, 5' }
                  );
                  driverToBaseTraces[ambIdForCase] = baseTrail;
                }
              }
            }
            
            // Auto-fit bounds to show all visible cases and ambulances
            setTimeout(() => autoFitMapBounds(), 500);
          } catch (e) { /* ignore */ }
        }

        // Load hospitals from OpenStreetMap in Cavite, Philippines
        async function loadHospitals() {
          try {
            // Cavite province bounding box (South, West, North, East)
            // Approximate coordinates for Cavite province
            const bbox = '120.7,14.1,121.1,14.5'; // West, South, East, North
            
            // Overpass API query to get hospitals in Cavite
            const overpassQuery = `
              [out:json][timeout:25];
              (
                node["amenity"="hospital"]["addr:province"="Cavite"](${bbox});
                way["amenity"="hospital"]["addr:province"="Cavite"](${bbox});
                relation["amenity"="hospital"]["addr:province"="Cavite"](${bbox});
                node["amenity"="hospital"]["addr:city"~"Cavite",i](${bbox});
                way["amenity"="hospital"]["addr:city"~"Cavite",i](${bbox});
                relation["amenity"="hospital"]["addr:city"~"Cavite",i](${bbox});
              );
              out center;
            `;
            
            const encodedQuery = encodeURIComponent(overpassQuery.trim());
            const overpassUrl = `https://overpass-api.de/api/interpreter?data=${encodedQuery}`;
            
            const response = await fetch(overpassUrl, {
              headers: { 'Accept': 'application/json' }
            });
            
            if (!response.ok) {
              console.warn('Failed to fetch hospitals from Overpass API');
              // Fallback: Use known hospitals in Cavite
              loadKnownHospitals();
              return;
            }
            
            const data = await response.json();
            
            // Clear existing hospital markers
            Object.values(hospitalMarkers).forEach(m => {
              if (m && map.hasLayer(m)) {
                map.removeLayer(m);
              }
            });
            hospitalMarkers = {};
            
            // Process results
            if (data.elements && Array.isArray(data.elements)) {
              data.elements.forEach(element => {
                // Get coordinates
                let lat, lng;
                if (element.type === 'node') {
                  lat = element.lat;
                  lng = element.lon;
                } else if (element.type === 'way' || element.type === 'relation') {
                  if (element.center) {
                    lat = element.center.lat;
                    lng = element.center.lon;
                  }
                }
                
                if (!lat || !lng) return;
                
                // Get hospital name
                const name = element.tags?.name || 
                           element.tags?.['name:en'] || 
                           element.tags?.['name:tl'] || 
                           'Hospital';
                
                // Create hospital marker with name
                const marker = L.marker([lat, lng], {
                  icon: createHospitalIcon(name),
                  zIndexOffset: 500,
                  interactive: true
                }).addTo(map);
                
                // Store marker
                const markerId = `hospital_${element.id}`;
                hospitalMarkers[markerId] = marker;
              });
              
              console.log(`Loaded ${Object.keys(hospitalMarkers).length} hospitals from OpenStreetMap`);
              // Ensure hospitals are visible
              ensureStaticMarkersVisible();
            }
          } catch (error) {
            console.error('Error loading hospitals:', error);
            // Fallback to known hospitals
            loadKnownHospitals();
          }
        }
        
        // Fallback function with known major hospitals in Cavite
        function loadKnownHospitals() {
          const knownHospitals = [
            { name: 'De La Salle Medical', lat: 14.330776825232102, lng: 120.94515471902851, address: 'Dasmarinas, Cavite' },
            { name: 'Bacoor Doctors Medical Center', lat: 14.420609873205866, lng: 120.96811851244432, address: 'Bacoor, Cavite' },
            { name: 'Medical Center Imus', lat: 14.426500859008211, lng: 120.9461359243246, address: 'Imus, Cavite' },
            { name: 'Ospital ng Imus', lat: 14.394393009508827, lng: 120.91994440841304, address: 'Imus, Cavite' },
            { name: 'St. Dominic Medical Center', lat: 14.45967139874632, lng: 120.96092868911074, address: 'Bacoor, Cavite' },
            { name: 'Gen Emilio Aguinaldo Memorial Hospital', lat: 14.276100761348713, lng: 120.87030852376998, address: 'Cavite' },
            { name: 'Emilio Aguinaldo College Medical Center', lat: 14.348808786719188, lng: 120.93977642432321, address: 'Cavite' },
            { name: 'Pagamutan ng Dasmarinas', lat: 14.323283224537036, lng: 120.96182690258594, address: 'Dasmarinas, Cavite' },
            { name: 'Tagaytay Medical Center', lat: 14.11576594265624, lng: 120.9610150666475, address: 'Tagaytay City, Cavite' },
          ];
          
          // Clear existing markers
          Object.values(hospitalMarkers).forEach(m => {
            if (m && map.hasLayer(m)) {
              map.removeLayer(m);
            }
          });
          hospitalMarkers = {};
          
          knownHospitals.forEach((hospital, index) => {
            const marker = L.marker([hospital.lat, hospital.lng], {
              icon: createHospitalIcon(hospital.name),
              zIndexOffset: 500,
              interactive: true
            }).addTo(map);
            
            hospitalMarkers[`known_${index}`] = marker;
          });
          
          console.log(`Loaded ${knownHospitals.length} known hospitals in Cavite`);
          // Ensure hospitals are visible
          ensureStaticMarkersVisible();
        }

        // Base location marker
        let baseMarker = null;
        function addBaseMarker() {
          // Base coordinates are defined globally: baseLat, baseLng
          
          // Remove existing base marker if any
          if (baseMarker && map.hasLayer(baseMarker)) {
            map.removeLayer(baseMarker);
          }
          
          // Create and add base marker
          baseMarker = L.marker([baseLat, baseLng], {
            icon: createBaseIcon(),
            zIndexOffset: 1000,
            interactive: true
          }).addTo(map);
          
          // Add popup with base information - positioned above the icon
          baseMarker.bindPopup(`
            <div style="min-width: 260px; padding: 8px;">
              <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 12px; padding-bottom: 10px; border-bottom: 2px solid #e5e7eb;">
                <div style="width: 48px; height: 48px; background: linear-gradient(135deg, #031273 0%, #0c2d5a 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 8px rgba(3,18,115,0.4);">
                  <i class="fas fa-landmark" style="color: #fff; font-size: 24px;"></i>
                </div>
                <div>
                  <div style="font-size: 16px; font-weight: 800; color: #031273; margin-bottom: 2px;">SILANG MDRRMO</div>
                  <div style="font-size: 11px; color: #6b7280; font-weight: 600;">Municipal Office</div>
                </div>
              </div>
              <div style="display: flex; flex-direction: column; gap: 8px;">
                <div style="display: flex; align-items: start; gap: 8px;">
                  <i class="fas fa-map-marker-alt" style="color: #f28c28; font-size: 14px; margin-top: 2px;"></i>
                  <div style="flex: 1;">
                    <div style="font-size: 10px; font-weight: 700; color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 2px;">Location</div>
                    <div style="font-size: 12px; color: #1f2937; font-weight: 600;">Silang, Cavite, Philippines</div>
                  </div>
                </div>
                <div style="display: flex; align-items: start; gap: 8px;">
                  <i class="fas fa-globe" style="color: #f28c28; font-size: 14px; margin-top: 2px;"></i>
                  <div style="flex: 1;">
                    <div style="font-size: 10px; font-weight: 700; color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 2px;">Coordinates</div>
                    <div style="font-size: 11px; color: #1f2937; font-weight: 500; font-family: monospace;">${baseLat.toFixed(6)}, ${baseLng.toFixed(6)}</div>
                  </div>
                </div>
              </div>
            </div>
          `, { 
            maxWidth: 280,
            offset: L.point(0, -70), // Position popup above the marker (negative y = upward)
            className: 'mdrrmo-base-popup',
            autoPan: true,
            autoPanPadding: [50, 50]
          });
          
          // Ensure base marker is visible
          ensureStaticMarkersVisible();
        }

        async function refreshAll(){
          await loadAmbulances();
          await loadActiveCases();
          // Ensure auto-fit runs after all data is refreshed to catch distant ambulances
          setTimeout(() => autoFitMapBounds(), 600);
        }
        (async () => { 
          await refreshAll();
          await loadHospitals(); // Load hospitals after initial data
          addBaseMarker(); // Add base marker
        })();
        setInterval(() => { refreshAll(); }, 8000);

        // Track user interactions with the map to temporarily disable auto-fit
        let interactionTimeout = null;
        
        map.on('zoomstart', function() {
          isUserInteracting = true;
          clearTimeout(interactionTimeout);
        });
        
        map.on('dragstart', function() {
          isUserInteracting = true;
          clearTimeout(interactionTimeout);
        });
        
        map.on('zoomend', function() {
          clearTimeout(interactionTimeout);
          // Re-enable auto-fit after 3 seconds of no interaction
          interactionTimeout = setTimeout(() => {
            isUserInteracting = false;
            autoFitMapBounds();
          }, 3000);
        });
        
        map.on('dragend', function() {
          clearTimeout(interactionTimeout);
          // Re-enable auto-fit after 3 seconds of no interaction
          interactionTimeout = setTimeout(() => {
            isUserInteracting = false;
            autoFitMapBounds();
          }, 3000);
        });

        // Routing helper using OSRM public API to follow roads
        async function drawRoutedLine(start, end, style){
          try {
            const coords = `${start[1]},${start[0]};${end[1]},${end[0]}`;
            const url = `https://router.project-osrm.org/route/v1/driving/${coords}?overview=full&geometries=geojson`;
            const res = await fetch(url);
            if (!res.ok) throw new Error('routing_failed');
            const data = await res.json();
            const points = (data && data.routes && data.routes[0] && data.routes[0].geometry && data.routes[0].geometry.coordinates) || [];
            if (!points.length) return L.polyline([start, end], style).addTo(map);
            const latlngs = points.map(([lng, lat]) => [lat, lng]);
            return L.polyline(latlngs, Object.assign({ lineCap: 'round', lineJoin: 'round' }, style)).addTo(map);
          } catch (e) {
            return L.polyline([start, end], Object.assign({ lineCap: 'round', lineJoin: 'round' }, style)).addTo(map);
          }
        }

        // Side panel UI
        sidePanel = document.getElementById('sidePanel');
        panelTitle = document.getElementById('panelTitle');
        panelContent = document.getElementById('panelContent');
        const panelClose = document.getElementById('panelClose');
        
        // Side panel controls
        panelClose.addEventListener('click', function() {
          closeSidePanel();
        });
        
        // Close panel when clicking outside
        document.addEventListener('click', function(e) {
          if (!sidePanel.contains(e.target) && !e.target.closest('.driver-marker') && !e.target.closest('.case-badge')) {
            closeSidePanel();
          }
        });

        // Function to open side panel
        function openSidePanel() {
          sidePanel.classList.add('open');
          // Adjust status bar and controls
          document.querySelector('.gm-status-bar').classList.add('panel-open');
          document.querySelector('.gm-controls').classList.add('panel-open');
          // Adjust zoom control
          const zoomControl = document.querySelector('.leaflet-control-zoom');
          if (zoomControl) {
            zoomControl.classList.add('panel-open');
          }
        }

        // Function to close side panel
        function closeSidePanel() {
          sidePanel.classList.remove('open');
          // Reset status bar and controls
          document.querySelector('.gm-status-bar').classList.remove('panel-open');
          document.querySelector('.gm-controls').classList.remove('panel-open');
          // Reset zoom control
          const zoomControl = document.querySelector('.leaflet-control-zoom');
          if (zoomControl) {
            zoomControl.classList.remove('panel-open');
          }
        }
        
        // Filter UI
        const filterToggle = document.getElementById('filterToggle');
        const filterPanel = document.getElementById('filterPanel');
        const filterApply = document.getElementById('filterApply');
        const filterAmbList = document.getElementById('filterAmbList');
        // Filter toggle
        filterToggle.addEventListener('click', function(e){ 
          e.stopPropagation(); 
          console.log('Filter clicked, current display:', filterPanel.style.display);
          const isOpening = filterPanel.style.display !== 'block';
          filterPanel.style.display = filterPanel.style.display==='block'?'none':'block';
          console.log('Filter display after toggle:', filterPanel.style.display);
          
          // If opening and active-only mode is enabled, select the active-cases radio
          if (isOpening && showActiveOnly) {
            const activeCasesRadio = document.querySelector('input[name="ambFilter"][value="active-cases"]');
            if (activeCasesRadio) {
              activeCasesRadio.checked = true;
            }
          } else if (isOpening && !showActiveOnly && currentFilterAmbId === 'all') {
            // If opening and not in active-only mode and showing all, select "all" radio
            const allRadio = document.querySelector('input[name="ambFilter"][value="all"]');
            if (allRadio) {
              allRadio.checked = true;
            }
          }
        });
        // Keep the panel open when interacting inside
        filterPanel.addEventListener('click', function(e){ e.stopPropagation(); });
        // Close filter panel when clicking outside
        document.addEventListener('click', function(e){ 
          if (!filterPanel.contains(e.target) && !filterToggle.contains(e.target)) {
            filterPanel.style.display = 'none';
          }
        });
        function renderFilterOptions(list){
          if (!Array.isArray(list)) return;
          
          // Store all ambulances for pagination
          filterAllAmbulances = list;
          filterFilteredAmbulances = list;
          
          // Apply search filter
          applySearchFilter();
          
          // Update pagination
          updatePagination();
          
          // Render current page
          renderCurrentPage();
        }
        
        function applySearchFilter() {
          if (!filterSearchTerm.trim()) {
            filterFilteredAmbulances = filterAllAmbulances;
          } else {
            filterFilteredAmbulances = filterAllAmbulances.filter(a => {
              const ambName = (a.name || a.ambulance_name || `Ambulance ${a.id}`).toLowerCase();
              return ambName.includes(filterSearchTerm.toLowerCase());
            });
          }
        }
        
        function updatePagination() {
          filterTotalPages = Math.ceil(filterFilteredAmbulances.length / filterItemsPerPage);
          if (filterCurrentPage > filterTotalPages) {
            filterCurrentPage = Math.max(1, filterTotalPages);
          }
          
          // Update button states
          document.getElementById('filterPrev').disabled = filterCurrentPage <= 1;
          document.getElementById('filterNext').disabled = filterCurrentPage >= filterTotalPages;
        }
        
        function renderCurrentPage() {
          const selected = String(currentFilterAmbId);
          filterAmbList.innerHTML = '';
          
          const startIndex = (filterCurrentPage - 1) * filterItemsPerPage;
          const endIndex = startIndex + filterItemsPerPage;
          const pageAmbulances = filterFilteredAmbulances.slice(startIndex, endIndex);
          
          pageAmbulances.forEach(a => {
            const ambName = a.name || a.ambulance_name || `Ambulance ${a.id}`;
            const lbl = document.createElement('label');
            lbl.className = 'gm-filter-option';
            lbl.innerHTML = `<input type="radio" name="ambFilter" value="${a.id}" ${String(a.id)===selected?'checked':''}> ${ambName}`;
            filterAmbList.appendChild(lbl);
          });
        }
        filterApply.addEventListener('click', async function(e){
          e.preventDefault();
          const sel = document.querySelector('input[name="ambFilter"]:checked');
          const selectedValue = sel ? sel.value : 'all';
          
          // Handle active cases filter
          if (selectedValue === 'active-cases') {
            if (isActiveModeApplying) return;
            isActiveModeApplying = true;
            try {
              showActiveOnly = true;
              currentFilterAmbId = 'all';
              // Refresh cases to rebuild active sets
              await loadActiveCases();
              // Guarantee visibility after trails/sets are up to date
              applyDriverVisibility();
              // Ensure static markers (hospitals, base) remain visible
              ensureStaticMarkersVisible();
              // Auto-fit bounds after filter is applied
              setTimeout(() => autoFitMapBounds(), 400);
            } finally {
              isActiveModeApplying = false;
            }
          } else {
            // Regular ambulance filter
            showActiveOnly = false;
            currentFilterAmbId = selectedValue;
            await refreshAll();
            // Apply visibility
            try { applyDriverVisibility(); } catch(_){}
            // Ensure static markers (hospitals, base) remain visible
            ensureStaticMarkersVisible();
            // Auto-fit bounds after filter is applied
            setTimeout(() => autoFitMapBounds(), 400);
          }
          
          filterPanel.style.display = 'none';
        });
        
        // Search functionality
        const filterSearch = document.getElementById('filterSearch');
        filterSearch.addEventListener('input', function(e) {
          filterSearchTerm = e.target.value;
          filterCurrentPage = 1; // Reset to first page when searching
          applySearchFilter();
          updatePagination();
          renderCurrentPage();
        });
        
        // Pagination functionality
        const filterPrev = document.getElementById('filterPrev');
        const filterNext = document.getElementById('filterNext');
        
        filterPrev.addEventListener('click', function() {
          if (filterCurrentPage > 1) {
            filterCurrentPage--;
            updatePagination();
            renderCurrentPage();
          }
        });
        
        filterNext.addEventListener('click', function() {
          if (filterCurrentPage < filterTotalPages) {
            filterCurrentPage++;
            updatePagination();
            renderCurrentPage();
          }
        });

        // Show ambulance information in side panel
        function showAmbulanceInfo(ambulance) {
          panelTitle.innerHTML = '<img src="{{ asset("image/mdrrmologo.jpg") }}" alt="MDRRMO" style="width: 32px; height: 32px; border-radius: 50%; margin-right: 12px; object-fit: cover; border: 2px solid rgba(255,255,255,0.3);"> Ambulance Information';
          
          // Fixed driver icon that covers the entire circle container
          const driverIcon = `<div class="driver-icon-container" style="width: 200px; height: 200px; border-radius: 50%; background: linear-gradient(135deg, #031273 0%, #1e40af 100%); display: flex; align-items: center; justify-content: center; border: 3px solid #e5e7eb; box-shadow: 0 4px 12px rgba(0,0,0,0.1); position: relative; overflow: hidden;">
            <div class="driver-icon-css"></div>
          </div>`;
          
          panelContent.innerHTML = `
            <div style="display: flex; flex-direction: column; gap: 12px;">
              <!-- Profile Picture Container - 1 Circle Only (200px) -->
              <div style="height: 200px; display: flex; justify-content: center; align-items: center;">
                ${driverIcon}
              </div>
              
              <!-- All Info in ONE Container -->
              <div class="gm-info-item" style="padding: 12px; display: flex; flex-direction: column; gap: 10px;">
                <div style="display: flex; align-items: center; gap: 8px;">
                  <span style="font-size: 12px; font-weight: 600; color: #64748b;">AMBULANCE NAME:</span>
                  <span style="font-size: 14px; font-weight: 800; color: #031273;">${ambulance.name || ambulance.ambulance_name || 'Unknown'}</span>
                </div>
                
                <div style="display: flex; align-items: center; gap: 8px;">
                  <span style="font-size: 12px; font-weight: 600; color: #64748b;">DRIVER NAME:</span>
                  <span style="font-size: 13px; font-weight: 600; color: #031273;">${ambulance.driver_name || ambulance.driver_first_name || 'Not assigned'}</span>
                </div>
                
                <div style="display: flex; align-items: center; gap: 8px;">
                  <span style="font-size: 12px; font-weight: 600; color: #64748b;">STATUS:</span>
                  <span class="gm-status-badge ${ambulance.latitude && ambulance.longitude ? 'gm-status-online' : 'gm-status-offline'}" style="font-size: 10px; padding: 3px 8px;">
                    <i class="fas ${ambulance.latitude && ambulance.longitude ? 'fa-check-circle' : 'fa-times-circle'}"></i>
                    ${ambulance.latitude && ambulance.longitude ? 'Online' : 'Offline'}
                  </span>
                </div>
                
                <div style="display: flex; align-items: center; gap: 8px;">
                  <span style="font-size: 12px; font-weight: 600; color: #64748b;">LOCATION:</span>
                  <span style="font-size: 11px; color: #031273;">${ambulance.latitude && ambulance.longitude ? `${ambulance.latitude}, ${ambulance.longitude}` : 'No GPS data'}</span>
                </div>
                
                <div style="display: flex; align-items: center; gap: 8px;">
                  <span style="font-size: 12px; font-weight: 600; color: #64748b;">LAST UPDATED:</span>
                  <span style="font-size: 11px; color: #031273;">${ambulance.updated_at ? new Date(ambulance.updated_at).toLocaleString() : 'Unknown'}</span>
                </div>
              </div>
            </div>
          `;
          openSidePanel();
        }
        
        // Create minimap for case information
        function createMinimapForCase(caseData, ambulanceData) {
          if (!caseData.latitude || !caseData.longitude) {
            return `
              <div class="minimap-container" style="display: flex; align-items: center; justify-content: center; color: #6b7280;">
                <div style="text-align: center;">
                  <i class="fas fa-map-marker-alt" style="font-size: 24px; margin-bottom: 8px; display: block;"></i>
                  <span style="font-size: 12px; font-weight: 600;">No location data available</span>
                </div>
              </div>
            `;
          }

          const caseLat = parseFloat(caseData.latitude);
          const caseLng = parseFloat(caseData.longitude);
          const ambLat = ambulanceData ? parseFloat(ambulanceData.latitude) : null;
          const ambLng = ambulanceData ? parseFloat(ambulanceData.longitude) : null;

          // Calculate center point for minimap
          let centerLat = caseLat;
          let centerLng = caseLng;
          if (ambLat && ambLng) {
            centerLat = (caseLat + ambLat) / 2;
            centerLng = (caseLng + ambLng) / 2;
          }

          // Create unique ID for this minimap
          const minimapId = `minimap-${Date.now()}-${Math.random().toString(36).substr(2, 9)}`;

          return `
            <div class="minimap-container">
              <div id="${minimapId}" style="width: 100%; height: 100%; border-radius: 6px;"></div>
            </div>
          `;
        }

        // Initialize minimap after DOM is updated
        function initializeMinimap(minimapId, caseData, ambulanceData) {
          setTimeout(() => {
            const container = document.getElementById(minimapId);
            if (!container) return;

            const caseLat = parseFloat(caseData.latitude);
            const caseLng = parseFloat(caseData.longitude);
            const ambLat = ambulanceData ? parseFloat(ambulanceData.latitude) : null;
            const ambLng = ambulanceData ? parseFloat(ambulanceData.longitude) : null;

            // Calculate center point
            let centerLat = caseLat;
            let centerLng = caseLng;
            if (ambLat && ambLng) {
              centerLat = (caseLat + ambLat) / 2;
              centerLng = (caseLng + ambLng) / 2;
            }

            // Initialize mini Leaflet map
            const miniMap = L.map(minimapId, {
              zoomControl: false,
              dragging: false,
              scrollWheelZoom: false,
              doubleClickZoom: false,
              boxZoom: false,
              keyboard: false,
              tap: false,
              attributionControl: false
            }).setView([centerLat, centerLng], 15);

            // Add tile layer
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
              attribution: ''
            }).addTo(miniMap);

            // Case marker - same as GPS map
            // Check if case is active (ambulance is OTW)
            const isActiveCase = (caseData.driver_accepted === true || caseData.status === 'In Progress' || caseData.status === 'Accepted') && ambulanceData;
            const caseIcon = createCaseIcon(caseData.case_num, isActiveCase);
            L.marker([caseLat, caseLng], { icon: caseIcon }).addTo(miniMap);

            // Ambulance marker (if available) - same as GPS map
            if (ambLat && ambLng) {
              const ambName = ambulanceData.name || ambulanceData.ambulance_name || '';
              const driverName = ambulanceData.driver_name || ambulanceData.driver_first_name || '';
              const computed = ambName ? ambName : (driverName ? `Driver ${getFirstName(driverName)}` : 'Ambulance');
              const ambIcon = createDriverIcon(computed);
              
              L.marker([ambLat, ambLng], { icon: ambIcon }).addTo(miniMap);

              // Road-following trail line using OSRM routing
              drawRoutedLineForMinimap([caseLat, caseLng], [ambLat, ambLng], miniMap);

              // Fit bounds to show both markers
              const group = new L.featureGroup([L.marker([caseLat, caseLng]), L.marker([ambLat, ambLng])]);
              miniMap.fitBounds(group.getBounds().pad(0.1));
            } else {
              miniMap.setView([caseLat, caseLng], 16);
            }
          }, 100);
        }

        // Calculate distance and ETA between case and ambulance
        function calculateDistanceAndETA(caseData, ambulanceData) {
          if (!caseData.latitude || !caseData.longitude || !ambulanceData || !ambulanceData.latitude || !ambulanceData.longitude) {
            return {
              distance: 'N/A',
              eta: 'N/A'
            };
          }

          const caseLat = parseFloat(caseData.latitude);
          const caseLng = parseFloat(caseData.longitude);
          const ambLat = parseFloat(ambulanceData.latitude);
          const ambLng = parseFloat(ambulanceData.longitude);

          // Calculate distance using Haversine formula
          const R = 6371; // Earth's radius in kilometers
          const dLat = (ambLat - caseLat) * Math.PI / 180;
          const dLng = (ambLng - caseLng) * Math.PI / 180;
          const a = Math.sin(dLat/2) * Math.sin(dLat/2) +
                    Math.cos(caseLat * Math.PI / 180) * Math.cos(ambLat * Math.PI / 180) *
                    Math.sin(dLng/2) * Math.sin(dLng/2);
          const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
          const distanceKm = R * c;

          // Format distance
          let distance;
          if (distanceKm < 1) {
            distance = `${Math.round(distanceKm * 1000)}m`;
          } else {
            distance = `${distanceKm.toFixed(1)}km`;
          }

          // Calculate ETA (assuming average ambulance speed of 60 km/h in city, 80 km/h on highway)
          // For simplicity, using 50 km/h average speed
          const avgSpeed = 50; // km/h
          const timeHours = distanceKm / avgSpeed;
          const timeMinutes = Math.round(timeHours * 60);

          let eta;
          if (timeMinutes < 60) {
            eta = `${timeMinutes}min`;
          } else {
            const hours = Math.floor(timeMinutes / 60);
            const minutes = timeMinutes % 60;
            eta = minutes > 0 ? `${hours}h ${minutes}m` : `${hours}h`;
          }

          return {
            distance: distance,
            eta: eta
          };
        }

        // Road-following trail for minimap
        async function drawRoutedLineForMinimap(start, end, miniMap) {
          try {
            const coords = `${start[1]},${start[0]};${end[1]},${end[0]}`;
            const url = `https://router.project-osrm.org/route/v1/driving/${coords}?overview=full&geometries=geojson`;
            const res = await fetch(url);
            if (!res.ok) throw new Error('routing_failed');
            const data = await res.json();
            const points = (data && data.routes && data.routes[0] && data.routes[0].geometry && data.routes[0].geometry.coordinates) || [];
            if (!points.length) {
              // Fallback to straight line
              L.polyline([start, end], { color: '#ff8c42', weight: 6, opacity: 0.95 }).addTo(miniMap);
              return;
            }
            const latlngs = points.map(([lng, lat]) => [lat, lng]);
            L.polyline(latlngs, { color: '#ff8c42', weight: 6, opacity: 0.95, lineCap: 'round', lineJoin: 'round' }).addTo(miniMap);
          } catch (e) {
            // Fallback to straight line
            L.polyline([start, end], { color: '#ff8c42', weight: 6, opacity: 0.95 }).addTo(miniMap);
          }
        }

        // Show case information in side panel
        function showCaseInfo(caseData) {
          panelTitle.innerHTML = '<img src="{{ asset("image/mdrrmologo.jpg") }}" alt="MDRRMO" style="width: 32px; height: 32px; border-radius: 50%; margin-right: 12px; object-fit: cover; border: 2px solid rgba(255,255,255,0.3);"> Case Information';
          
          // Get ambulance data for driver photo
          const ambulanceData = ambulancesCache.find(amb => String(amb.id) === String(caseData.ambulance_id));
          const driverPhoto = ambulanceData && ambulanceData.driver_photo ? 
            `<img src="${ambulanceData.driver_photo}" alt="Driver Photo" style="width: 24px; height: 24px; border-radius: 50%; object-fit: cover; margin-right: 8px; border: 2px solid #fff;">` : 
            `<div style="width: 24px; height: 24px; border-radius: 50%; background: #e5e7eb; display: inline-flex; align-items: center; justify-content: center; margin-right: 8px; border: 2px solid #fff;"><i class="fas fa-user" style="font-size: 10px; color: #6b7280;"></i></div>`;
          
          // Create minimap
          const minimapHtml = createMinimapForCase(caseData, ambulanceData);
          const minimapId = minimapHtml.match(/id="([^"]+)"/)?.[1];
          
          // Calculate distance and ETA
          const distanceInfo = calculateDistanceAndETA(caseData, ambulanceData);
          
          panelContent.innerHTML = `
            <div style="display: flex; flex-direction: column; gap: 8px;">
              <!-- Minimap Section -->
              <div style="height: 400px;">
                ${minimapHtml}
              </div>
              
              <!-- Distance & ETA - NO CONTAINER, just content -->
              <div style="display: flex; align-items: center; justify-content: space-around; padding: 8px; margin-top: 25px; margin-bottom: 25px; background: #f8fafc; border-radius: 6px;">
                <!-- Distance -->
                <div style="display: flex; align-items: center; gap: 6px;">
                  <i class="fas fa-route" style="font-size: 14px; color: #ff8c42;"></i>
                  <span style="font-size: 11px; font-weight: 600; color: #64748b;">DISTANCE:</span>
                  <span style="font-size: 13px; font-weight: 800; color: #031273;">${distanceInfo.distance}</span>
                </div>
                <!-- ETA -->
                <div style="display: flex; align-items: center; gap: 6px;">
                  <i class="fas fa-clock" style="font-size: 14px; color: #10b981;"></i>
                  <span style="font-size: 11px; font-weight: 600; color: #64748b;">ETA:</span>
                  <span style="font-size: 13px; font-weight: 800; color: #031273;">${distanceInfo.eta}</span>
                </div>
              </div>
              
              <!-- Info Section - CONTAINER HEIGHT MATCHES CONTENT -->
              <div class="gm-info-item" style="padding: 10px; display: flex; flex-direction: column; gap: 8px;">
                <div style="display: flex; align-items: center; gap: 8px;">
                  <span style="font-size: 12px; font-weight: 600; color: #64748b;">CASE NUMBER:</span>
                  <span style="font-size: 14px; font-weight: 800; color: #031273;">#${caseData.case_num || 'Unknown'}</span>
                </div>
                
                <div style="display: flex; align-items: center; gap: 8px;">
                  <span style="font-size: 12px; font-weight: 600; color: #64748b;">STATUS:</span>
                  <span class="gm-status-badge ${caseData.status === 'Completed' ? 'gm-status-online' : caseData.status === 'In Progress' ? 'gm-status-pending' : 'gm-status-offline'}" style="font-size: 10px; padding: 3px 8px;">
                    <i class="fas ${caseData.status === 'Completed' ? 'fa-check-circle' : caseData.status === 'In Progress' ? 'fa-clock' : 'fa-exclamation-circle'}"></i>
                    ${caseData.status || 'Pending'}
                  </span>
                </div>
                
                <div style="display: flex; align-items: center; gap: 8px;">
                  <span style="font-size: 12px; font-weight: 600; color: #64748b;">LOCATION:</span>
                  <span style="font-size: 11px; color: #031273;">${caseData.latitude && caseData.longitude ? `${caseData.latitude}, ${caseData.longitude}` : 'No location data'}</span>
                </div>
                
                <div style="display: flex; align-items: center; gap: 8px;">
                  <span style="font-size: 12px; font-weight: 600; color: #64748b;">AMBULANCE DRIVER:</span>
                  <div style="display: flex; align-items: center; gap: 6px;">
                    ${driverPhoto}
                    <span style="font-size: 11px; color: #031273;">${caseData.ambulance_id ? `Ambulance ${caseData.ambulance_id}` : 'Not assigned'}</span>
                  </div>
                </div>
                
                <div style="display: flex; align-items: center; gap: 8px;">
                  <span style="font-size: 12px; font-weight: 600; color: #64748b;">CREATED:</span>
                  <span style="font-size: 11px; color: #031273;">${caseData.created_at ? new Date(caseData.created_at).toLocaleDateString() : 'Unknown'}</span>
                </div>
                
                <div style="display: flex; align-items: center; gap: 8px;">
                  <span style="font-size: 12px; font-weight: 600; color: #64748b;">UPDATED AT:</span>
                  <span style="font-size: 11px; color: #031273;">${caseData.updated_at ? new Date(caseData.updated_at).toLocaleDateString() : 'Unknown'}</span>
                </div>
              </div>
            </div>
          `;
          
          // Initialize the minimap after DOM is updated
          if (minimapId) {
            initializeMinimap(minimapId, caseData, ambulanceData);
          }
          
          openSidePanel();
        }

        // Update status panel counts
        function updateStatusCounts() {
          const totalCasesEl = document.getElementById('totalCases');
          const totalAmbulancesEl = document.getElementById('totalAmbulances');
          const activeCasesEl = document.getElementById('activeCases');
          
          if (totalCasesEl) totalCasesEl.textContent = totalCasesCount;
          if (totalAmbulancesEl) totalAmbulancesEl.textContent = ambulancesCache.length;
          if (activeCasesEl) activeCasesEl.textContent = activeCasesCount;
          
          // Also update left panel
          updateLeftPanelMetrics();
        }
        
        // Update left panel dashboard metrics
        function updateLeftPanelMetrics() {
          // Count active drivers (drivers who have accepted a case or have a case in progress)
          const activeDrivers = ambulancesCache.filter(a => {
            const ambId = parseInt(a.id);
            return activeAmbulanceIds && activeAmbulanceIds.has(ambId);
          }).length;
          
          // Count available drivers (online drivers without active cases)
          const availableDrivers = ambulancesCache.filter(a => {
            const lat = parseFloat(a.latitude);
            const lng = parseFloat(a.longitude);
            const hasGps = !(Number.isNaN(lat) || Number.isNaN(lng));
            const ambId = parseInt(a.id);
            const hasActiveCase = activeAmbulanceIds && activeAmbulanceIds.has(ambId);
            // Available = online (has GPS) AND doesn't have an active case
            return hasGps && !hasActiveCase;
          }).length;
          
          const totalAmbulances = ambulancesCache.length;
          
          const activeDriversEl = document.getElementById('leftPanelActiveDrivers');
          const totalAmbulancesEl = document.getElementById('leftPanelTotalAmbulances');
          const activeCasesEl = document.getElementById('leftPanelActiveCases');
          const availableDriversEl = document.getElementById('leftPanelAvailableDrivers');
          
          if (activeDriversEl) activeDriversEl.textContent = activeDrivers;
          if (totalAmbulancesEl) totalAmbulancesEl.textContent = totalAmbulances;
          if (activeCasesEl) activeCasesEl.textContent = activeCasesCount;
          if (availableDriversEl) availableDriversEl.textContent = availableDrivers;
          
        }

        // Back button functionality
        const backButton = document.getElementById('backButton');
        backButton.addEventListener('click', function() {
          window.location.href = '{{ route("dashboard") }}';
        });
        
        // UI Toggle functionality - Hide/Show UI elements
        const uiToggle = document.getElementById('uiToggle');
        const uiToggleIcon = document.getElementById('uiToggleIcon');
        let uiHidden = false;
        
        uiToggle.addEventListener('click', function() {
          uiHidden = !uiHidden;
          
          if (uiHidden) {
            // Hide UI elements
            document.body.classList.add('ui-hidden');
            uiToggleIcon.classList.remove('fa-eye');
            uiToggleIcon.classList.add('fa-eye-slash');
            uiToggle.title = 'Show UI';
          } else {
            // Show UI elements
            document.body.classList.remove('ui-hidden');
            uiToggleIcon.classList.remove('fa-eye-slash');
            uiToggleIcon.classList.add('fa-eye');
            uiToggle.title = 'Hide UI';
          }
        });
      })();

      // ===== Super Admin Presence System =====
      (function() {
          const banner = document.getElementById('superadmin-banner');
          const superAdminEmails = [
              'jaymarkroce@superadmin.com',
              'princenipaya@superadmin.com',
              'ahlencorpuz@superadmin.com'
          ];
          
          // Check if current user is super admin (for admin dashboard view)
          const currentUserEmail = @json(auth()->check() ? strtolower(auth()->user()->email ?? '') : '');
          const isSuperAdmin = superAdminEmails.includes(currentUserEmail);
          
          // Heartbeat function for super admins
          function sendHeartbeat() {
              if (!isSuperAdmin) return;
              fetch('/presence/superadmin/heartbeat', {
                  method: 'POST',
                  headers: {
                      'Content-Type': 'application/json',
                      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                  }
              }).catch(err => console.error('Heartbeat error:', err));
          }
          
          // Check super admin status
          function checkSuperAdminStatus() {
              fetch('/presence/superadmin/status', {
                  headers: {
                      'Accept': 'application/json'
                  }
              })
              .then(res => res.json())
              .then(data => {
                  if (data.active) {
                      banner.style.display = 'block';
                      // Adjust status bar position
                      const statusBar = document.querySelector('.gm-status-bar');
                      if (statusBar) statusBar.style.top = '56px';
                  } else {
                      banner.style.display = 'none';
                      const statusBar = document.querySelector('.gm-status-bar');
                      if (statusBar) statusBar.style.top = '10px';
                  }
              })
              .catch(err => console.error('Status check error:', err));
          }
          
          // Initial check
          checkSuperAdminStatus();
          
          // Poll every 5 seconds
          setInterval(checkSuperAdminStatus, 5000);
          
          // Send heartbeat every 30 seconds if super admin
          if (isSuperAdmin) {
              sendHeartbeat();
              setInterval(sendHeartbeat, 30000);
          }
      })();
    </script>
  </body>
</html>