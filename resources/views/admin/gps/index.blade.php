@extends('layouts.app')
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ambulance GPS Tracking</title>

    <!-- ✅ Your local CSS (do NOT comment this, it's your design) -->
    <link rel="stylesheet" href="{{ asset('css/stylish.css') }}">
    <!-- ✅ Dashboard view styles for filter UI parity -->
    <link rel="stylesheet" href="{{ asset('css/dashboard-view.css') }}">

    <!-- ✅ Font Awesome icons for UI -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- ✅ Laravel CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- ✅ Keep the JS, it's needed for the map to work -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <!-- ✅ Inline Leaflet CSS to reduce load time -->
    <style>
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

        
    /* ==== Inline Minimal Leaflet CSS ==== */
.leaflet-container {
  width: 100%;
  height: 100%;
  position: relative;
  overflow: hidden;
}
/* Keep Leaflet map panes behind custom content within #map-container */
#map-container .leaflet-map-pane,
#map-container .leaflet-tile-pane,
#map-container .leaflet-pane {
  z-index: 0 !important;
}
.leaflet-tile {
  position: absolute;
  left: 0;
  top: 0;
  will-change: transform;
}
.leaflet-marker-icon,
.leaflet-marker-shadow,
.leaflet-pane > svg,
.leaflet-pane > canvas {
  position: absolute;
}
.leaflet-tooltip {
  position: absolute;
  background: transparent;
  border: none;
  padding: 0;
  white-space: nowrap;
  pointer-events: none;
}
.leaflet-tooltip.case-label {
  background: rgba(255,255,255,0.95);
  border: 1px solid #cbd5e1;
  border-radius: 4px;
  padding: 0 6px;
}
.leaflet-control-attribution {
  font-size: 11px;
  background: rgba(255,255,255,0.7);
  margin: 0;
  padding: 0 3px;
}
.case-label {
  background: rgba(255,255,255,0.95);
  color: #111827;
  border: 1px solid #cbd5e1;
  border-radius: 4px;
  padding: 0 6px;
  font-weight: 700;
  font-size: 12px;
  line-height: 18px;
  box-shadow: 0 1px 2px rgba(0,0,0,0.1);
}
.amb-icon {
  font-size: 22px;
  line-height: 24px;
  text-align: center;
}
.leaflet-routing-container {
  background: white;
  border-radius: 5px;
  padding: 6px 10px;
  font-family: sans-serif;
  font-size: 13px;
  box-shadow: 0 1px 5px rgba(0,0,0,0.4);
  line-height: 1.4;
}
    /* ==== End Inline Leaflet CSS ==== */

/* Ensure nav-links styling matches other admin pages exactly */
body.gps-page .nav-links {
    display: flex !important;
    flex-direction: column !important;
    gap: 1rem !important;
    width: 100% !important;
}

body.gps-page .nav-links a,
body.gps-page .nav-link-locked {
    text-decoration: none !important;
    color: white !important;
    font-size: 1rem !important;
    font-weight: 600 !important;
    padding: 0.75rem 1rem !important;
    border-radius: 8px !important;
    transition: background-color 0.2s !important;
    display: block !important;
    width: 100% !important;
    box-sizing: border-box !important;
}

body.gps-page .nav-link-locked {
    color: #9ca3af !important;
    cursor: not-allowed !important;
    opacity: 0.6 !important;
}

body.gps-page .nav-links i {
    margin-right: 0.5rem !important;
}

body.gps-page .nav-links a:hover {
    background-color: var(--brand-orange) !important;
    color: var(--brand-navy) !important;
}

body.gps-page .nav-links a.active {
    background-color: var(--brand-orange) !important;
    color: var(--brand-navy) !important;
}

/* GPS Index Specific - Base Marker Popup Styling (Compact) */
/* Only applies to GPS index page, not dashboard view */
#map-container .leaflet-popup.mdrrmo-base-popup {
  margin-bottom: 70px !important; /* Space above marker - matching dashboard view */
  z-index: 3000 !important;
  pointer-events: auto !important;
}

#map-container .leaflet-popup.mdrrmo-base-popup .leaflet-popup-content-wrapper {
  z-index: 3001 !important;
  position: relative;
  background: #ffffff !important;
  background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%) !important;
  border: 2px solid #e5e7eb !important;
  border-radius: 12px !important;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15), 0 2px 8px rgba(0, 0, 0, 0.1) !important;
  padding: 0 !important;
  max-width: 220px !important;
  width: auto !important;
}

#map-container .leaflet-popup.mdrrmo-base-popup .leaflet-popup-content {
  z-index: 3002 !important;
  position: relative;
  margin: 0 !important;
  padding: 12px !important;
  background: transparent !important;
  color: #1f2937 !important;
  width: auto !important;
  max-width: 220px !important;
}

/* Arrow tip pointing down to marker - styled like dashboard view */
#map-container .leaflet-popup.mdrrmo-base-popup .leaflet-popup-tip {
  z-index: 3001 !important;
  background: #ffffff !important;
  border: 2px solid #e5e7eb !important;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1) !important;
  width: 16px !important;
  height: 16px !important;
  transform: rotate(45deg) !important;
  position: absolute !important;
  bottom: -8px !important;
  left: 50% !important;
  margin-left: -8px !important;
  border-top: 2px solid #e5e7eb !important;
  border-left: 2px solid #e5e7eb !important;
  border-bottom: none !important;
  border-right: none !important;
}

/* CSS Variables */
:root {
    --header-height: 80px;
    --brand-navy: #0c2d5a;
    --brand-orange: #f28c28;
}

/* GPS Page - No Scroll */
body.gps-page {
    overflow: hidden !important;
    height: 100vh !important;
}

/* GPS page specific styles - match dashboard layout exactly */
.sidenav {
    position: fixed;
    top: 0;
    left: 0;
    width: 260px;
    height: 100vh;
    background-color: var(--brand-navy);
    color: white;
    padding: 2rem 1rem;
    box-sizing: border-box;
    border-right: 2px solid var(--brand-orange);
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 2rem;
    z-index: 1000;
}

.mdrrmo-header {
    position: fixed;
    top: 0;
    margin-left: 260px;
    width: calc(100% - 260px);
    z-index: 1000;
    background-color: var(--brand-navy);
    color: white;
    padding: 1rem 2rem;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
    display: flex;
    justify-content: center;
    align-items: center;
}

.maincontentt {
    margin-left: 260px;
    width: calc(100% - 260px);
    padding: 2rem;
    padding-top: var(--header-height);
    box-sizing: border-box;
    overflow: hidden; /* Prevent scrolling */
    height: calc(100vh - var(--header-height)); /* Fixed height */
    display: flex;
    flex-direction: column;
    align-items: center;
    position: relative;
}

.gps-clear-btn {
    background-color: #dc2626 !important;
    color: #fff !important;
    border: none;
    transition: background 0.2s;
}
.gps-clear-btn:hover {
    background-color: #b91c1c !important;
}

/* GPS Control Panel Styling */
.gps-panel-toggle {
    position: absolute;
    bottom: 20px;
    right: 20px;
    width: 50px;
    height: 50px;
    background: linear-gradient(135deg, #3b82f6, #1d4ed8);
    color: white;
    border: none;
    border-radius: 50%;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
    box-shadow: 0 4px 15px rgba(59, 130, 246, 0.4);
    transition: all 0.3s ease;
    z-index: 2000;
    pointer-events: auto;
}

.gps-panel-toggle:hover {
    transform: scale(1.1);
    box-shadow: 0 6px 20px rgba(59, 130, 246, 0.5);
}

.fullscreen-toggle {
    position: absolute;
    bottom: 20px;
    left: 20px;
    width: 50px;
    height: 50px;
    background: linear-gradient(135deg, #f59e0b, #ff8c42);
    border: none;
    border-radius: 50%;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.2rem;
    z-index: 2000;
    transition: all 0.3s ease;
    box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
    pointer-events: auto;
}

.fullscreen-toggle:hover {
    transform: scale(1.1);
    box-shadow: 0 6px 20px rgba(245, 158, 11, 0.5);
}

/* Update Indicator - Pop-up Notification Style */
.update-indicator {
    position: absolute;
    top: 96px;
    left: 50%;
    transform: translateX(-50%) translateY(-20px);
    background: white;
    color: #111827;
    padding: 12px 16px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 0.9rem;
    font-weight: 600;
    z-index: 10000;
    opacity: 0;
    pointer-events: none;
    transition: all 0.3s ease;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2), 0 4px 10px rgba(0, 0, 0, 0.15);
    border-left: 4px solid;
    min-width: 44px;
    width: fit-content;
    justify-content: center;
}

.update-indicator.active {
    opacity: 1;
    transform: translateX(-50%) translateY(0);
}

.update-indicator.active i {
    animation: spin 1s linear infinite;
}

.update-indicator.paused {
    border-left-color: #f59e0b;
    background: #fff7ed;
}

.update-indicator:not(.paused) {
    border-left-color: #10b981;
}


@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

/* Fullscreen Mode Styles */
body.fullscreen-mode {
    overflow: hidden !important;
    background: white !important;
    margin: 0 !important;
    padding: 0 !important;
}

/* Create a fullscreen overlay that covers everything */
body.fullscreen-mode::before {
    content: '';
    position: fixed !important;
    top: 0 !important;
    left: 0 !important;
    width: 100vw !important;
    height: 100vh !important;
    background: transparent !important;
    z-index: 0 !important; /* keep below map/panes */
    pointer-events: none !important;
}

.fullscreen-mode #map-container {
    position: fixed !important;
    top: 0 !important;
    left: 0 !important;
    width: 100vw !important;
    height: 100vh !important;
    max-width: none !important;
    margin: 0 !important;
    padding: 0 !important;
    transform: none !important;
    z-index: 100 !important;
    border-radius: 0 !important;
    background: white !important;
    border: none !important;
    overflow: hidden !important;
}

/* Force the map canvas to fill the entire container */
.fullscreen-mode #map-container .leaflet-container {
    width: 100vw !important;
    height: 100vh !important;
    position: fixed !important;
    top: 0 !important;
    left: 0 !important;
    z-index: 90 !important; /* reduce so custom panes can overlay */
}

/* Force the map tiles canvas to fill the container */
.fullscreen-mode #map-container .leaflet-map-pane,
.fullscreen-mode #map-container .leaflet-tile-pane,
.fullscreen-mode #map-container .leaflet-layer {
    width: 100vw !important;
    height: 100vh !important;
    position: absolute !important;
    top: 0 !important;
    left: 0 !important;
}

/* Ensure modals appear above fullscreen map */
.fullscreen-mode .modal-overlay {
    z-index: 10000 !important;
}

.fullscreen-mode .modal-content {
    z-index: 10001 !important;
}

/* Hide all other content when in fullscreen mode */
.fullscreen-mode .mdrrmo-header,
.fullscreen-mode .sidenav,
.fullscreen-mode .subnav,
.fullscreen-mode .main-content,
.fullscreen-mode .main-content > *:not(#map-container):not(.gps-control-panel):not(.gps-panel-toggle):not(.fullscreen-toggle):not(.fullscreen-close),
.fullscreen-mode header,
.fullscreen-mode nav,
.fullscreen-mode aside,
.fullscreen-mode body > *:not(#map-container):not(.gps-control-panel):not(.gps-panel-toggle):not(.fullscreen-toggle):not(.fullscreen-close):not(.modal-overlay) {
    display: none !important;
    visibility: hidden !important;
    opacity: 0 !important;
    position: absolute !important;
    left: -9999px !important;
}

/* Ensure GPS control panel appears above fullscreen map */
.fullscreen-mode .gps-control-panel {
    z-index: 101 !important;
    position: fixed !important;
    top: 90px !important; // <-- push downward
    right: 20px !important;
}

.fullscreen-mode .gps-panel-toggle {
    z-index: 101 !important;
    position: fixed !important;
    bottom: 20px !important;
    right: 20px !important;
}

.fullscreen-mode .fullscreen-toggle {
    display: none;
}

.fullscreen-mode .fullscreen-close {
    position: fixed !important;
    bottom: 24px !important;
    left: 36px !important;
    top: auto !important;
    width: 50px !important;
    height: 40px !important;
    background: linear-gradient(135deg, #f59e0b, #ff8c42) !important;
    border: 2px solid rgba(255,255,255,0.9) !important;
    border-radius: 8px !important;
    cursor: pointer !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    color: white !important;
    font-size: 1.2rem !important;
    z-index: 110 !important;
    transition: all 0.3s ease !important;
    box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3) !important;
    pointer-events: auto !important;
}

.fullscreen-mode .fullscreen-close:hover {
    transform: scale(1.05);
    box-shadow: 0 6px 20px rgba(245, 158, 11, 0.5);
}

/* UI Toggle Button - Only visible in fullscreen mode (matching dashboard view) */
.gps-ui-toggle {
    display: none;
}

.fullscreen-mode .gps-ui-toggle {
    position: fixed !important;
    top: 70px !important;
    right: 10px !important;
    z-index: 10050 !important;
    background: transparent !important;
    border: none !important;
    width: auto !important;
    height: auto !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    cursor: pointer !important;
    transition: all 0.2s ease !important;
    padding: 0 !important;
    pointer-events: auto !important;
}

.fullscreen-mode .gps-ui-toggle i {
    color: #374151 !important;
    font-size: 20px !important;
    text-shadow: 0 1px 3px rgba(0,0,0,0.3) !important;
    transition: all 0.2s ease !important;
}

.fullscreen-mode .gps-ui-toggle:hover i {
    color: #031273 !important;
    transform: scale(1.1) !important;
    text-shadow: 0 2px 6px rgba(3,18,115,0.4) !important;
}

/* UI Hidden State in Fullscreen - Hide UI elements except toggle button */
.fullscreen-mode.ui-hidden .gm-status-bar,
.fullscreen-mode.ui-hidden .gps-control-panel,
.fullscreen-mode.ui-hidden .gps-panel-toggle,
.fullscreen-mode.ui-hidden .fullscreen-close,
.fullscreen-mode.ui-hidden #driver-quick-panel,
.fullscreen-mode.ui-hidden #quick-driver-open,
.fullscreen-mode.ui-hidden #quick-driver-results,
.fullscreen-mode.ui-hidden #quick-driver-form,
.fullscreen-mode.ui-hidden .driver-quick-panel,
.fullscreen-mode.ui-hidden .leaflet-control-zoom {
    display: none !important;
    visibility: hidden !important;
    opacity: 0 !important;
    pointer-events: none !important;
}

/* Toggle button always visible when in fullscreen */
.fullscreen-mode.ui-hidden .gps-ui-toggle {
    display: flex !important;
}

.gps-control-panel {
    position: absolute;
    top: 20px;
    right: 20px;
    width: 320px;
    background: white;
    border-radius: 16px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
    border: 1px solid rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(10px);
    transform: translateX(100%);
    opacity: 0;
    transition: all 0.3s ease;
    z-index: 111;
    overflow: hidden;
    pointer-events: auto;
}

.gps-control-panel.active {
    transform: translateX(0);
    opacity: 1;
}

.panel-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem 1.5rem;
    background: linear-gradient(135deg, #f8fafc, #f1f5f9);
    border-bottom: 1px solid #e5e7eb;
}

.panel-title {
    font-weight: 700;
    font-size: 1.1rem;
    color: #1e293b;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin: 0;
}

.close-panel-btn {
    background: #ef4444;
    color: white;
    border: none;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s ease;
    font-size: 0.9rem;
}

.close-panel-btn:hover {
    background: #dc2626;
    transform: scale(1.1);
}

.panel-content {
    padding: 1.5rem;
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.panel-btn {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    border: none;
    border-radius: 12px;
    cursor: pointer;
    transition: all 0.3s ease;
    text-align: left;
    position: relative;
    overflow: hidden;
    width: 100%;
}

.panel-btn::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, transparent 0%, rgba(255,255,255,0.1) 100%);
    opacity: 0;
    transition: opacity 0.3s ease;
}

.panel-btn:hover::before {
    opacity: 1;
}

.panel-btn .btn-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    border-radius: 8px;
    font-size: 1.2rem;
    flex-shrink: 0;
}

.panel-btn .btn-content {
    display: flex;
    flex-direction: column;
    gap: 0.2rem;
}

.panel-btn .btn-title {
    font-weight: 600;
    font-size: 0.9rem;
    line-height: 1.2;
    color: white;
}

.panel-btn .btn-subtitle {
    font-size: 0.75rem;
    opacity: 0.8;
    font-weight: 500;
    color: white;
}


.panel-btn.status-btn {
    background: linear-gradient(135deg, #10b981, #059669);
    color: white;
    box-shadow: 0 2px 8px rgba(16, 185, 129, 0.3);
}

.panel-btn.status-btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4);
}

.panel-btn.status-btn .btn-icon {
    background: rgba(255, 255, 255, 0.2);
}

.panel-btn.stops-btn {
    background: linear-gradient(135deg, #8b5cf6, #7c3aed);
    color: white;
    box-shadow: 0 2px 8px rgba(139, 92, 246, 0.3);
}

.panel-btn.stops-btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(139, 92, 246, 0.4);
}

.panel-btn.stops-btn .btn-icon {
    background: rgba(255, 255, 255, 0.2);
}

/* Modal Improvements */
.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    backdrop-filter: blur(4px);
    z-index: 2000;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 1rem;
}

.modal-content {
    background: white;
    border-radius: 16px;
    box-shadow: 0 25px 50px rgba(0, 0, 0, 0.25);
    max-height: 80vh;
    width: 100%;
    max-width: 600px;
    overflow-y: auto;
    position: relative;
    border: 1px solid rgba(255, 255, 255, 0.2);
    display: flex;
    flex-direction: column;
    margin: auto;
    transform: translateY(0);
}

/* Ensure modal is always centered */
.modal-overlay[style*="display: block"] {
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem 1.5rem;
    border-bottom: 1px solid #e5e7eb;
    background: linear-gradient(135deg, #f8fafc, #f1f5f9);
    border-radius: 16px 16px 0 0;
    flex-shrink: 0;
}

.modal-title {
    font-weight: 700;
    font-size: 1.1rem;
    color: #1e293b;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin: 0;
}

.modal-close {
    background: #ef4444;
    color: white;
    border: none;
    width: 32px;
    height: 32px;
    border-radius: 8px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s ease;
    font-size: 0.9rem;
}

.modal-close:hover {
    background: #dc2626;
    transform: scale(1.05);
}

/* Case Details Modal Styling */
#case-details-modal .modal-content {
    max-height: 90vh;
    overflow-y: auto;
}

#case-details-modal .modal-header button:hover {
    background: rgba(255, 255, 255, 0.2);
}

#case-details-modal .modal-body {
    max-height: 70vh;
    overflow-y: auto;
}

#case-details-modal button {
    transition: all 0.3s ease;
}

#case-details-modal button:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.modal-body {
    padding: 1.5rem;
    flex: 1;
    overflow-y: auto;
}

/* Form Elements */
.form-group {
    margin-bottom: 1rem;
}

.form-label {
    display: block;
    font-weight: 600;
    color: #374151;
    margin-bottom: 0.4rem;
    font-size: 0.9rem;
}

.form-input, .form-select {
    width: 100%;
    padding: 0.6rem 0.8rem;
    border: 2px solid #e5e7eb;
    border-radius: 8px;
    font-size: 0.95rem;
    transition: all 0.2s ease;
    background: white;
}

.form-input:focus, .form-select:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.suggestions-list {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    background: white;
    border: 2px solid #e5e7eb;
    border-top: none;
    border-radius: 0 0 10px 10px;
    max-height: 200px;
    overflow-y: auto;
    z-index: 50;
    display: none;
}

.suggestions-list li {
    padding: 0.75rem 1rem;
    cursor: pointer;
    border-bottom: 1px solid #f3f4f6;
    transition: background-color 0.2s ease;
}

.suggestions-list li:hover {
    background-color: #f8fafc;
}

.suggestions-list li:last-child {
    border-bottom: none;
}

.action-btn {
    background: linear-gradient(135deg, #3b82f6, #1d4ed8);
    color: white;
    border: none;
    padding: 0.7rem 1.5rem;
    border-radius: 8px;
    font-weight: 600;
    font-size: 0.95rem;
    cursor: pointer;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);
}

.action-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(59, 130, 246, 0.4);
}

/* Status Modal Styles */
.status-section, .notifications-section {
    background: #f8fafc;
    border-radius: 10px;
    padding: 1rem;
    border: 1px solid #e5e7eb;
    margin-bottom: 1rem;
}

.section-header {
    margin-bottom: 0.75rem;
}

.section-title {
    font-weight: 700;
    font-size: 1rem;
    color: #1e293b;
    display: flex;
    align-items: center;
    gap: 0.4rem;
}

.table-container {
    overflow-x: auto;
    border-radius: 6px;
    border: 1px solid #e5e7eb;
}

.status-table {
    width: 100%;
    border-collapse: collapse;
    background: white;
}

.status-table th {
    background: #f1f5f9;
    padding: 0.5rem 0.75rem;
    text-align: left;
    font-weight: 600;
    color: #475569;
    border-bottom: 1px solid #e5e7eb;
    font-size: 0.8rem;
}

.status-table td {
    padding: 0.5rem 0.75rem;
    border-bottom: 1px solid #f1f5f9;
    color: #374151;
    font-size: 0.85rem;
}

.status-table tbody tr:hover {
    background-color: #f8fafc;
}

.divider {
    height: 1px;
    background: linear-gradient(90deg, #e5e7eb, #cbd5e1, #e5e7eb);
    margin: 1rem 0;
    border-radius: 1px;
}

.notifications-container {
    max-height: 200px;
    overflow-y: auto;
}

.notifications-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.notifications-list li {
    background: white;
    padding: 0.75rem;
    margin-bottom: 0.5rem;
    border-radius: 6px;
    border-left: 3px solid #3b82f6;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    transition: all 0.2s ease;
    font-size: 0.85rem;
}

.notifications-list li:hover {
    transform: translateX(2px);
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
}

/* Stops Modal Styles */
.controls-section {
    background: #f8fafc;
    border-radius: 10px;
    padding: 1rem;
    border: 1px solid #e5e7eb;
    margin-bottom: 1rem;
}

.controls-row {
    display: flex;
    align-items: end;
    gap: 0.75rem;
    flex-wrap: wrap;
}

.control-group {
    flex: 1;
    min-width: 180px;
}

.control-label {
    display: block;
    font-weight: 600;
    color: #374151;
    margin-bottom: 0.4rem;
    font-size: 0.9rem;
}

.refresh-btn {
    background: linear-gradient(135deg, #10b981, #059669);
    color: white;
    border: none;
    padding: 0.6rem 1.2rem;
    border-radius: 6px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 0.4rem;
    box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
    font-size: 0.9rem;
}

.refresh-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(16, 185, 129, 0.4);
}

.stops-section {
    background: #f8fafc;
    border-radius: 10px;
    padding: 1rem;
    border: 1px solid #e5e7eb;
}

.stops-container {
    max-height: 300px;
    overflow-y: auto;
}

.stops-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.stops-list li {
    background: white;
    padding: 0.75rem;
    margin-bottom: 0.5rem;
    border-radius: 6px;
    border: 1px solid #e5e7eb;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    transition: all 0.2s ease;
    font-size: 0.85rem;
}

.stops-list li:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
}

/* Responsive Design */
@media (max-width: 768px) {
    .gps-control-panel {
        width: 280px;
        top: 10px;
        right: 10px;
    }
    
    .gps-panel-toggle {
        top: 10px;
        right: 10px;
        width: 45px;
        height: 45px;
        font-size: 1.1rem;
    }
    
    .panel-content {
        padding: 1rem;
    }
    
    .panel-btn {
        padding: 0.75rem;
    }
    
    .panel-btn .btn-icon {
        width: 35px;
        height: 35px;
        font-size: 1rem;
    }
    
    .panel-btn .btn-title {
        font-size: 0.85rem;
    }
    
    .panel-btn .btn-subtitle {
        font-size: 0.7rem;
    }
    
    .modal-overlay {
        padding: 0.5rem;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
    }
    
    .modal-content {
        max-height: 90vh;
        max-width: 95vw;
    width: 100% !important;
        margin: 0 !important;
    }
    
    .modal-header {
        padding: 0.75rem 1rem;
    }
    
    .modal-body {
        padding: 1rem;
    }
    
    .modal-title {
        font-size: 1rem;
    }
    
    .form-group {
        margin-bottom: 0.75rem;
    }
    
    .status-section, .notifications-section, .controls-section, .stops-section {
        padding: 0.75rem;
    }
    
    .controls-row {
        flex-direction: column;
        align-items: stretch;
    }
    
    .control-group {
        min-width: auto;
    }
    
    .status-table th, .status-table td {
        padding: 0.4rem 0.5rem;
        font-size: 0.75rem;
    }
    
    .notifications-container, .stops-container {
        max-height: 150px;
    }
    
    /* Mobile responsive - match dashboard */
    .sidenav {
        position: fixed;
        left: 0;
        top: 0;
        height: 100vh;
        width: 80%;
        max-width: 300px;
        background-color: var(--brand-navy);
        padding: 2rem 1rem;
        transform: translateX(-100%);
        transition: transform 0.3s ease-in-out;
        z-index: 999;
        border-right: 2px solid var(--brand-orange);
    }

    .sidenav.active {
        transform: translateX(0);
    }

    .maincontentt {
        margin-left: 0;
        width: 100%;
        padding: 1rem;
    }

    .mdrrmo-header {
        margin-left: 0;
        width: 100%;
        padding: 1rem;
        justify-content: center;
    }
}

/* Toggle Button - hidden on desktop, shown on mobile */
.toggle-btn {
    display: none;
    position: fixed;
    top: 1rem;
    left: 1rem;
    background-color: #031273;
    color: white;
    border: none;
    font-size: 1.5rem;
    padding: 0.5rem 0.75rem;
    z-index: 1001;
    border-radius: 5px;
    cursor: pointer;
}

@media (max-width: 768px) {
    .toggle-btn {
        display: block;
    }
}

/* Inline notification modal */
.inline-modal {
    position: fixed;
    inset: 0;
    display: none;
    align-items: center;
    justify-content: center;
    background: rgba(15, 23, 42, 0.55);
    backdrop-filter: blur(2px);
    z-index: 20050;
    padding: 1.5rem;
}

.inline-modal.show {
    display: flex;
    animation: inlineModalFade 0.2s ease;
}

.inline-modal-content {
    width: min(440px, 92vw);
    background: #ffffff;
    border-radius: 20px;
    box-shadow: 0 24px 60px rgba(15, 23, 42, 0.35);
    overflow: hidden;
    transform: translateY(16px);
    animation: inlineModalSlide 0.3s cubic-bezier(0.25, 1, 0.5, 1);
}

.inline-modal-header {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 20px 24px;
    color: #ffffff;
    background: linear-gradient(135deg, #2563eb, #1d4ed8);
}

.inline-modal-header i {
    font-size: 1.3rem;
}

.inline-modal-header h3 {
    margin: 0;
    font-size: 1.05rem;
    font-weight: 700;
}

.inline-modal-body {
    padding: 22px 26px;
    color: #1f2937;
    font-size: 0.95rem;
    line-height: 1.6;
}

.inline-modal-footer {
    padding: 16px 24px 24px;
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

.inline-modal-btn.primary {
    background: linear-gradient(135deg, #2563eb, #1d4ed8);
    color: #ffffff;
    box-shadow: 0 12px 24px rgba(37, 99, 235, 0.35);
}

.inline-modal-btn.secondary {
    background: #f1f5f9;
    color: #0f172a;
}

.inline-modal-btn:focus {
    outline: none;
}

.inline-modal-btn:active {
    transform: translateY(1px);
}

@keyframes inlineModalFade {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes inlineModalSlide {
    from { transform: translateY(26px); }
    to { transform: translateY(0); }
}

/* Case logs modal */
.logs-modal-content {
    width: min(1120px, 97vw);
    background: #ffffff;
    border-radius: 24px;
    padding: 32px clamp(18px, 4vw, 36px);
    box-shadow: 0 25px 60px rgba(15, 23, 42, 0.25);
    border: 1px solid #e2e8f0;
    position: relative;
    display: flex;
    flex-direction: column;
    gap: 1.25rem;
}

.logs-modal-header {
    text-align: center;
    display: flex;
    flex-direction: column;
    gap: 0.35rem;
}

.logs-modal-icon {
    width: 52px;
    height: 52px;
    border-radius: 16px;
    margin: 0 auto;
    background: linear-gradient(135deg, #1f2937, #4b5563);
    color: #ffffff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.3rem;
}

.logs-modal-title {
    margin: 0;
    font-size: 1.25rem;
    color: #0f172a;
    font-weight: 800;
}

.logs-modal-subtitle {
    margin: 0;
    color: #6b7280;
    font-size: 0.95rem;
}

.logs-close-btn {
    position: absolute;
    top: 16px;
    right: 16px;
    background: #0f172a;
    color: #ffffff;
    border: none;
    width: 38px;
    height: 38px;
    border-radius: 12px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.9rem;
    box-shadow: 0 10px 25px rgba(15, 23, 42, 0.25);
}

.logs-filters {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 0.85rem;
    align-items: end;
}

.logs-field {
    display: flex;
    flex-direction: column;
    gap: 0.35rem;
}

.logs-field span {
    font-size: 0.8rem;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    font-weight: 700;
    color: #94a3b8;
}

.logs-input,
.logs-select {
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 0.55rem 0.75rem;
    font-size: 0.95rem;
    font-weight: 600;
    color: #0f172a;
    background: #f8fafc;
    transition: border-color 0.2s ease, box-shadow 0.2s ease;
}

.logs-input:focus,
.logs-select:focus {
    border-color: #2563eb;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
    outline: none;
    background: #ffffff;
}

.logs-field--button {
    align-self: end;
}

.logs-clear-btn {
    width: 50%;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 0.6rem 0.75rem;
    font-weight: 700;
    font-size: 0.9rem;
    background: #cc0000;
    color: white;
    cursor: pointer;
    transition: background 0.2s ease, color 0.2s ease, border-color 0.2s ease;
    margin-left: 100px;
    height: 45px;
}

.logs-clear-btn:hover {
    background: #0f172a;
    color: #ffffff;
    border-color: #0f172a;
}

.logs-table-wrapper {
    border: 1px solid #e2e8f0;
    border-radius: 20px;
    background: #ffffff;
    overflow-y: auto;
    overflow-x: hidden;
    max-height: 420px;
}

.logs-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 0.9rem;
}

.logs-table thead {
    background: #f1f5f9;
}

.logs-table th {
    text-align: left;
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    color: #94a3b8;
    font-weight: 800;
    padding: 0.75rem;
    border-bottom: 1px solid #e2e8f0;
}

.logs-table td {
    padding: 0.75rem;
    border-bottom: 1px solid #e2e8f0;
    vertical-align: top;
    color: #0f172a;
}

.logs-table tbody tr:hover {
    background: #f8fafc;
}

.logs-cell-meta {
    margin: 0.15rem 0 0;
    color: #64748b;
    font-size: 0.82rem;
}

.logs-empty,
.logs-error {
    padding: 1.5rem;
    text-align: center;
    color: #6b7280;
    display: flex;
    flex-direction: column;
    gap: 0.4rem;
    align-items: center;
}

.logs-empty i,
.logs-error i {
    font-size: 1.6rem;
    opacity: 0.6;
}

.logs-empty p,
.logs-error p {
    margin: 0;
}

.logs-error-title {
    font-weight: 700;
    color: #0f172a;
}

.logs-error-subtitle {
    font-size: 0.85rem;
    color: #94a3b8;
}

.logs-pill {
    border-radius: 999px;
    padding: 0.25rem 0.7rem;
    font-size: 0.78rem;
    font-weight: 700;
    display: inline-flex;
    align-items: center;
    gap: 0.2rem;
}

.logs-pill--status {
    background: #10b981;
    color: #ffffff;
}

.logs-pill--info {
    background: #3b82f6;
    color: #ffffff;
}

.logs-pill--muted {
    background: #e2e8f0;
    color: #475569;
}

.logs-contact-cell {
    white-space: nowrap;
    color: #475569;
    font-weight: 600;
}

.logs-ambulance-cell {
    white-space: nowrap;
    color: #0f172a;
    font-weight: 700;
}

.logs-pill--priority-low {
    background: #0ea5e9;
    color: #ffffff;
}

.logs-pill--priority-medium {
    background: #f59e0b;
    color: #ffffff;
}

.logs-pill--priority-high {
    background: #ef4444;
    color: #ffffff;
}

.logs-pill--priority-critical {
    background: #b91c1c;
    color: #ffffff;
}

.logs-print-btn {
    border: none;
    border-radius: 999px;
    padding: 0.35rem 0.85rem;
    font-size: 0.78rem;
    font-weight: 700;
    background: #0f172a;
    color: #ffffff;
    cursor: pointer;
    transition: opacity 0.2s ease;
    white-space: nowrap;
}

.logs-print-btn:hover {
    opacity: 0.8;
}
</style>
</head>

<body class="gps-page">
<!-- Super Admin Presence Banner -->
<div id="superadmin-banner" style="display: none; position: fixed; top: 0; left: 0; right: 0; background: linear-gradient(135deg, #1e40af, #1e3a8a); color: white; padding: 10px 20px; text-align: center; font-weight: 600; font-size: 14px; z-index: 10000; box-shadow: 0 2px 8px rgba(0,0,0,0.2);">
    <i class="fas fa-user-shield" style="margin-right: 8px;"></i>
    <span>Super Admin Active</span>
</div>

<!-- Inline Notification Modal -->
<div id="inlineModal" class="inline-modal" role="dialog" aria-modal="true" aria-labelledby="inlineModalTitle">
    <div class="inline-modal-content">
        <div id="inlineModalHeader" class="inline-modal-header">
            <i id="inlineModalIcon" class="fas fa-info-circle"></i>
            <h3 id="inlineModalTitle">Notice</h3>
        </div>
        <div class="inline-modal-body">
            <p id="inlineModalMessage" style="margin:0;">This is an inline message.</p>
        </div>
        <div class="inline-modal-footer" id="inlineModalActions">
            <button type="button" class="inline-modal-btn primary" onclick="closeInlineModal()">OK</button>
        </div>
    </div>
</div>

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
      @if(auth()->check())
        <span class="nav-link-locked" style="display: block; text-decoration: none; color: #9ca3af; font-size: 1rem; font-weight: 600; padding: 0.75rem 1rem; border-radius: 8px; cursor: not-allowed; opacity: 0.6; position: relative;"><i class="fas fa-pen"></i> Posting <i class="fas fa-lock" style="font-size: 10px; margin-left: 8px; opacity: 0.7;"></i></span>
        <a href="{{ url('/admin/pairing') }}" class="{{ request()->is('admin/pairing') ? 'active' : '' }}"><i class="fas fa-link"></i> Pairing</a>
        <a href="{{ url('/admin/drivers') }}" class="{{ request()->is('admin/drivers*') ? 'active' : '' }}"><i class="fas fa-car"></i> Drivers</a>
        <a href="{{ url('/admin/medics') }}" class="{{ request()->is('admin/medics*') ? 'active' : '' }}"><i class="fas fa-plus"></i> Create</a>
        <a href="{{ url('/admin/gps') }}" class="{{ request()->is('admin/gps') ? 'active' : '' }}"><i class="fas fa-map-marker-alt mr-1"></i> GPS Tracker</a>
        <a href="{{ url('/admin/reported-cases') }}" class="{{ request()->is('admin/reported-cases') ? 'active' : '' }}"><i class="fas fa-file-alt"></i> Reported Cases</a>
        
      @else
        <a href="{{ route('login') }}"><i class="fas fa-sign-in-alt"></i> Login</a>
      @endif
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

<!-- Main content (with padding top to not hide under fixed header) -->
<main class="maincontentt pt-24">
    <div class="main-gps-grid mx-auto w-full" style="max-width:100%; padding:2rem; height: 100%; display: flex; flex-direction: column;">
        
        <!-- Map Section with Integrated Control Panel -->
        <div class="sector-2 flex justify-center items-center p-4 mx-auto my-4 rounded" style="width: 100%; display: flex; justify-content: center; align-items: center; padding: 0; flex-direction: column; flex: 1; min-height: 0;">
            <div id="map-container" class="rounded shadow" style="height: calc(100vh - var(--header-height) - 4rem); width: 75vw; min-height: 500px; max-width: 1400px; margin: 0 auto; border: 6px solid; border-image: linear-gradient(90deg, #f59e0b, #ff8c42) 1; border-radius: 0; box-shadow: 0 8px 32px rgba(245, 158, 11, 0.15), 0 2px 8px rgba(255, 140, 66, 0.10); display: block; position: relative; left: 50%; transform: translateX(-50%);">
                <!-- Dashboard-style Status Bar -->
                <div class="gm-status-bar" style="z-index: 108;">
                    <div class="gm-status-item">
                        <div class="gm-status-icon gm-status-case-inactive">
                            <i class="fas fa-map-marker-alt" style="font-size:16px; color:#9ca3af; text-shadow:0 1px 2px rgba(0,0,0,0.2);"></i>
                        </div>
                        <span>Total Cases: <span id="totalCases">0</span></span>
                    </div>
                    <div class="gm-status-item">
                        <div class="gm-status-icon gm-status-red"><i class="fas fa-user"></i></div>
                        <span>Active Drivers: <span id="totalDrivers">0</span></span>
                    </div>
                    <div class="gm-status-item">
                        <div class="gm-status-icon gm-status-case-active">
                            <i class="fas fa-map-marker-alt" style="font-size:16px; color:#f28c28; text-shadow:0 1px 2px rgba(0,0,0,0.2);"></i>
                        </div>
                        <span>Active Cases: <span id="activeCases">0</span></span>
                    </div>
                </div>
                
                <!-- Driver Quick Search Overlay (appears in front of the map) -->
                <div id="driver-quick-panel" style="position:absolute; top: 96px; left:16px; z-index: 10020; background: transparent; border: none; border-radius: 9999px; display:flex; align-items:center; gap:8px; padding:0; box-shadow:none; pointer-events:auto;">
                    <button id="quick-driver-open" title="Open driver search" style="width: 44px; height: 44px; background: linear-gradient(135deg, #3b82f6, #1d4ed8); color: #ffffff; border: none; border-radius: 50%; cursor: pointer; display: inline-flex; align-items: center; justify-content: center; font-size: 1rem; box-shadow: 0 4px 12px rgba(59,130,246,0.35); transition: all 0.3s ease;">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
                <div id="quick-driver-results" style="position:absolute; top: 132px; left:16px; z-index: 10040; background:#fff; border:1px solid #e5e7eb; border-radius: 8px; box-shadow: 0 8px 18px rgba(0,0,0,0.12); max-height: 220px; overflow:auto; display:none; min-width: 240px; pointer-events:auto;"></div>

                <!-- Floating small form for search (high z-index) -->
                <div id="quick-driver-form" style="position:absolute; top: 132px; left:16px; z-index: 10030; background:#ffffff; border:1px solid rgba(255,255,255,0.2); border-radius:12px; padding:8px; box-shadow: 0 8px 20px rgba(0,0,0,0.16); display:none; pointer-events:auto; min-width: 260px; backdrop-filter: blur(8px);">
                    <div style="display:flex; align-items:center; justify-content:space-between; gap:6px; margin-bottom:6px; padding: 6px 8px; background: linear-gradient(135deg, #f8fafc, #f1f5f9); border-radius: 10px; border: 1px solid #e5e7eb;">
                        <div style="font-weight:800; color:#1e293b; font-size:12px; display:flex; align-items:center; gap:6px;">
                            <i class="fas fa-search" style="color:#2563eb;"></i>
                            Driver Search
                        </div>
                        <button id="quick-driver-close" aria-label="Close" style="background:#ef4444; color:#fff; border:none; width: 28px; height: 28px; border-radius:6px; font-weight:900; font-size:14px; display:inline-flex; align-items:center; justify-content:center;">&times;</button>
                    </div>
                    <div style="display:flex; align-items:center; gap:6px; margin-bottom:6px; padding: 0 2px;">
                        <i class="fas fa-user" style="color:#111827;"></i>
                        <input id="quick-driver-input" type="text" placeholder="Type driver name..." style="flex:1; border:1.5px solid #e5e7eb; outline:none; background:#fff; padding:6px 8px; border-radius:6px; font-weight:600; color:#111827; font-size: 13px;" />
                        <button id="quick-driver-clear" style="background: linear-gradient(135deg, #ef4444, #dc2626); color:#fff; border:none; border-radius:6px; padding:6px 8px; font-weight:800; font-size:12px; box-shadow: 0 2px 6px rgba(239,68,68,0.25);">Clear</button>
                    </div>
                    <div style="display:flex; align-items:center; gap:6px;">
                        <button id="btn-all-drivers" title="Show all drivers & cases" style="background: linear-gradient(135deg, #111827, #0f172a); color:#fff; border:none; border-radius:6px; padding:6px 8px; font-weight:800; font-size:11px; flex:1; box-shadow: 0 2px 6px rgba(17,24,39,0.25);">All Drivers</button>
                        <button id="btn-active-cases" title="Show only active cases & assigned drivers" style="background: linear-gradient(135deg, #3b82f6, #1d4ed8); color:#fff; border:none; border-radius:6px; padding:6px 8px; font-weight:800; font-size:11px; flex:1; box-shadow: 0 2px 6px rgba(59,130,246,0.25);">Active Cases</button>
                    </div>
                </div>
                
                <!-- GPS Control Panel Toggle Button (original floating button) -->
                <div id="gps-panel-toggle" class="gps-panel-toggle" style="bottom: 20px; right: 20px; height: 40px; width: 50px; border-radius: 8px;">
                    <i class="fas fa-cogs"></i>
                </div>
                
                <!-- Fullscreen Toggle Button (centered under status bar) -->
                <div id="fullscreen-toggle" class="fullscreen-toggle" style="bottom: 20px; left: 20px; transform: none; z-index: 105; height: 40px; width: 50px; border-radius: 8px; display:flex; align-items:center; justify-content:center;">
                    <i class="fas fa-expand"></i>
                </div>
                
                <!-- Fullscreen Close Button (hidden by default) -->
                <div id="fullscreen-close" class="fullscreen-close" style="display: none;">
                    <i class="fas fa-compress"></i>
                </div>
                
                <!-- UI Toggle Button (only visible in fullscreen mode, matching dashboard view) -->
                <button id="gps-ui-toggle" class="gps-ui-toggle" title="Hide/Show UI">
                    <i class="fas fa-eye" id="gps-ui-toggle-icon"></i>
                </button>
                
                <!-- Legacy driver filter elements are hidden -->
                <div id="driver-filter-toggle" style="display:none;"></div>
                <div id="driver-filter-panel" style="display:none;"></div>

                <!-- Dashboard-style controls: Filter removed by request -->
                
                <!-- Update Indicator -->
                <div id="update-indicator" class="update-indicator" style="z-index: 104;">
                    <i class="fas fa-sync-alt"></i>
                    <span class="update-text">Updating...</span>
                </div>

                
                
                <!-- GPS Control Panel (pushed below status bar and top controls) -->
                <div id="gps-control-panel" class="gps-control-panel" style="top: 96px; right: 20px;">
                    <div class="panel-header">
                        <h3 class="panel-title">
                            <i class="fas fa-cogs"></i>
                            GPS Control
                        </h3>
                        <button id="close-panel" class="close-panel-btn">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
    <div class="panel-content" style="position: relative;">
                        <button id="open-active-modal" class="panel-btn" style="background: linear-gradient(135deg, #2563eb, #1d4ed8); color: white; box-shadow: 0 2px 8px rgba(37, 99, 235, 0.3);">
                            <div class="btn-icon" style="background: rgba(255, 255, 255, 0.2);">
                                <i class="fas fa-list"></i>
                            </div>
                            <div class="btn-content">
                                <span class="btn-title" style="color: white;">Active Cases</span>
                                <span class="btn-subtitle" style="color: white;">Ongoing & Pending</span>
                            </div>
                        </button>
                        
        <button id="open-logs-modal" class="panel-btn" style="background: linear-gradient(135deg, #6b7280, #4b5563); color: white; box-shadow: 0 2px 8px rgba(107, 114, 128, 0.3);">
                            <div class="btn-icon" style="background: rgba(255, 255, 255, 0.2);">
                                <i class="fas fa-history"></i>
                            </div>
                            <div class="btn-content">
                                <span class="btn-title" style="color: white;">Case Logs</span>
                                <span class="btn-subtitle" style="color: white;">Completed Cases</span>
                            </div>
                        </button>
        <button id="open-geocode-modal" class="panel-btn" style="background: linear-gradient(135deg, #0ea5e9, #0284c7); color: white; box-shadow: 0 2px 8px rgba(2, 132, 199, 0.3);">
                            <div class="btn-icon" style="background: rgba(255, 255, 255, 0.2);">
                                <i class="fas fa-search-location"></i>
                            </div>
                            <div class="btn-content">
                                <span class="btn-title" style="color: white;">Pin by Address</span>
                                <span class="btn-subtitle" style="color: white;">Type address to drop pin</span>
                            </div>
                        </button>
        <!-- Posting-style compact menu below toggle -->
        
        </div>
                    </div>
                </div>
                
            </div>
        </div>


        

<!-- Case Logs Modal -->
<div id="logs-modal" class="modal-overlay" style="display:none; position:fixed; inset:0; background: rgba(0,0,0,0.35); backdrop-filter: blur(4px); z-index:3000; align-items:center; justify-content:center;">
    <div class="logs-modal-content">
        <button type="button" id="close-logs-modal" class="logs-close-btn">
            <i class="fas fa-times"></i>
        </button>
        <div class="logs-modal-header">
            <div class="logs-modal-icon">
                <i class="fas fa-history"></i>
            </div>
            <h3 class="logs-modal-title">Completed Cases Log</h3>
            <p class="logs-modal-subtitle">Review past emergency responses and print concise summaries.</p>
        </div>
        <div class="logs-filters">
            <label class="logs-field">
                <span>Search</span>
                <input id="logs-search" type="text" placeholder="Search name, address or case #" class="logs-input">
            </label>
            <label class="logs-field">
                <span>Priority</span>
                <select id="logs-priority" class="logs-select">
                    <option value="">All</option>
                    <option>Low</option>
                    <option>Medium</option>
                    <option>High</option>
                    <option>Critical</option>
                </select>
            </label>
            <label class="logs-field">
                <span>Ambulance</span>
                <input id="logs-ambulance" type="text" placeholder="Ambulance name" class="logs-input">
            </label>
            <div class="logs-field logs-field--button">
                <button id="logs-clear" class="logs-clear-btn">Clear</button>
            </div>
        </div>
        <div class="logs-table-wrapper">
            <table class="logs-table">
                <thead>
                    <tr>
                        <th>Case #</th>
                        <th>Patient</th>
                        <th>Contact</th>
                        <th>Pin Address</th>
                        <th>Priority</th>
                        <th>Type</th>
                        <th>Ambulance</th>
                        <th>Created</th>
                        <th>Completed</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="logs-table-body">
                    <tr>
                        <td colspan="10">
                            <div class="logs-empty">
                                <i class="fas fa-spinner fa-spin"></i>
                                <p>Loading completed cases...</p>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Active Cases Modal -->
<div id="active-modal" class="modal-overlay" style="display:none; position:fixed; inset:0; background: rgba(0,0,0,0.35); backdrop-filter: blur(4px); z-index:3000; align-items:center; justify-content:center;">
    <div class="modal-content" style="background: #ffffff; width: 90%; max-width: 1000px; height: auto; border-radius: 12px; border: 2px solid var(--brand-navy); box-shadow: 0 10px 25px rgba(3,18,115,0.25); position: relative; overflow: hidden; display: flex; flex-direction: column; align-items: center; justify-content: center;">
        <button type="button" id="close-active-modal" class="modal-close" style="position: absolute; top: 10px; right: 12px; background: var(--brand-navy); color: #ffffff; border: 0; width: 36px; height: 36px; border-radius: 6px; cursor: pointer; display: flex; align-items: center; justify-content: center; z-index: 10;">
            <i class="fas fa-times"></i>
        </button>
        <div id="activeContainer" class="modal-iframe" title="Active Cases" style="background:#fff; overflow:auto; border: 0; display: block; width: 100%; height: 100%; padding: 1rem; box-sizing: border-box; overflow: auto;">
            <div style="padding: 1rem; text-align: center; color: var(--brand-navy); font-weight: 700; margin-bottom: 1rem;">
                <h3 style="margin: 0 0 1rem 0; font-size: 1.2rem; display: flex; align-items: center; justify-content: center; gap: 0.5rem;">
                    <i class="fas fa-list" style="color: #2563eb;"></i>
                    Active Cases
                </h3>
            </div>
            <div style="background: #ffffff; border-radius: 12px; padding: 1rem; box-shadow: 0 8px 18px rgba(3,18,115,0.08);">
                <div style="display:flex; gap:0.5rem; flex-wrap:wrap; align-items:end; margin-bottom:0.75rem;">
                    <div style="flex:1; min-width:180px;">
                        <label style="display:block; font-weight:600; color:#374151; margin-bottom:0.25rem; font-size:0.85rem;">Search</label>
                        <input id="active-search" type="text" placeholder="Search by name, address, case #" class="form-input" style="padding:0.5rem 0.6rem;">
                    </div>
                    <div>
                        <label style="display:block; font-weight:600; color:#374151; margin-bottom:0.25rem; font-size:0.85rem;">Priority</label>
                        <select id="active-priority" class="form-select" style="padding:0.5rem 0.6rem; min-width:150px;">
                            <option value="">All</option>
                            <option selected>Medium</option>
                            <option>High</option>
                        </select>
                    </div>
                    <div>
                        <label style="display:block; font-weight:600; color:#374151; margin-bottom:0.25rem; font-size:0.85rem;">Status</label>
                        <select id="active-status" class="form-select" style="padding:0.5rem 0.6rem; min-width:150px;">
                            <option value="">All</option>
                            <option>Pending</option>
                            <option>Accepted</option>
                            <option>Rejected</option>
                            <option>In Progress</option>
                        </select>
                    </div>
                    <div>
                        <label style="display:block; font-weight:600; color:#374151; margin-bottom:0.25rem; font-size:0.85rem;">Ambulance</label>
                        <input id="active-ambulance" type="text" placeholder="Ambulance name" class="form-input" style="padding:0.5rem 0.6rem; min-width:180px;">
                    </div>
                    <div>
                        <button id="active-clear" class="action-btn" style="margin-top:1.6rem; background:red; box-shadow:none; width:80px; margin-right:15px;">Clear</button>
                    </div>
                </div>
                <div style="overflow-x: auto; border-radius: 6px; border: 1px solid #e5e7eb;">
                    <table id="active-cases-table" style="width: 100%; border-collapse: collapse; background: white; ">
                        <thead>
                            <tr>
                                <th style="background: #f1f5f9; padding: 0.5rem 0.75rem; text-align: left; font-weight: 600; color: #475569; border-bottom: 1px solid #e5e7eb; font-size: 0.8rem;">Case #</th>
                                <th style="background: #f1f5f9; padding: 0.5rem 0.75rem; text-align: left; font-weight: 600; color: #475569; border-bottom: 1px solid #e5e7eb; font-size: 0.8rem;">Name</th>
                                <th style="background: #f1f5f9; padding: 0.5rem 0.75rem; text-align: left; font-weight: 600; color: #475569; border-bottom: 1px solid #e5e7eb; font-size: 0.8rem;">Priority</th>
                                <th style="background: #f1f5f9; padding: 0.5rem 0.75rem; text-align: left; font-weight: 600; color: #475569; border-bottom: 1px solid #e5e7eb; font-size: 0.8rem;">Type</th>
                                <th style="background: #f1f5f9; padding: 0.5rem 0.75rem; text-align: left; font-weight: 600; color: #475569; border-bottom: 1px solid #e5e7eb; font-size: 0.8rem;">Status</th>
                                <th style="background: #f1f5f9; padding: 0.5rem 0.75rem; text-align: left; font-weight: 600; color: #475569; border-bottom: 1px solid #e5e7eb; font-size: 0.8rem;">Ambulance</th>
                                <th style="background: #f1f5f9; padding: 0.5rem 0.75rem; text-align: left; font-weight: 600; color: #475569; border-bottom: 1px solid #e5e7eb; font-size: 0.8rem;">Created</th>
                                <th style="background: #f1f5f9; padding: 0.5rem 0.75rem; text-align: left; font-weight: 600; color: #475569; border-bottom: 1px solid #e5e7eb; font-size: 0.8rem;">Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    </div>

<!-- Geocode (Pin by Address) Modal -->
<div id="geocode-modal" class="modal-overlay" style="display:none; position:fixed; inset:0; background: rgba(0,0,0,0.35); backdrop-filter: blur(4px); z-index:3000; align-items:center; justify-content:center;">
    <div class="modal-content" style="background: #ffffff; width: 90%; max-width: 700px; height: auto; border-radius: 12px; border: 2px solid var(--brand-navy); box-shadow: 0 10px 25px rgba(3,18,115,0.25); position: relative; overflow: hidden; display: flex; flex-direction: column; align-items: center; justify-content: center;">
        <button type="button" id="close-geocode-modal" class="modal-close" style="position: absolute; top: 10px; right: 12px; background: var(--brand-navy); color: #ffffff; border: 0; width: 36px; height: 36px; border-radius: 6px; cursor: pointer; display: flex; align-items: center; justify-content: center; z-index: 10;">
            <i class="fas fa-times"></i>
        </button>
        <div id="geocodeContainer" class="modal-iframe" title="Pin by Address" style="background:#fff; overflow:auto; border: 0; display: block; width: 100%; height: 100%; padding: 1rem; box-sizing: border-box; overflow: auto;">
            <div style="padding: 1rem; text-align: center; color: var(--brand-navy); font-weight: 700; margin-bottom: 1rem;">
                <h3 style="margin: 0 0 0.75rem 0; font-size: 1.2rem; display: flex; align-items: center; justify-content: center; gap: 0.5rem;">
                    <i class="fas fa-search-location" style="color: #0ea5e9;"></i>
                    Pin by Address
                </h3>
            </div>
            <div style="background: #ffffff; border-radius: 12px; padding: 1rem; box-shadow: 0 8px 18px rgba(3,18,115,0.08);">
                <div style="display:flex; gap:0.5rem; align-items:end; flex-wrap:wrap;">
                    <div style="flex:1; min-width:220px; position: relative;">
                        <label style="display:block; font-weight:600; color:#374151; margin-bottom:0.25rem; font-size:0.9rem;">Address</label>
                        <input id="geocode-address-input" type="text" placeholder="Enter full address" class="form-input" style="padding:0.6rem 0.8rem;">
                        <ul id="geocode-suggestions" class="suggestions-list"></ul>
                    </div>
                    <div>
                        <button id="geocode-pin-btn" class="action-btn" style="background: linear-gradient(135deg, #0ea5e9, #0284c7); width:150px; height:40px;">
                            <i class="fas fa-map-pin"></i>
                            Pin On Map
                        </button>
                    </div>
                </div>
                <div style="display: flex; gap: 0.5rem; margin-top: 0.75rem;">
                    <label style="display: flex; align-items: center; gap: 0.3rem; font-size: 0.85rem; color: #374151;">
                        <input type="radio" name="search-type" value="pickup" checked style="margin: 0;">
                        <span>Pickup Location</span>
                    </label>
                    <label style="display: flex; align-items: center; gap: 0.3rem; font-size: 0.85rem; color: #374151;">
                        <input type="radio" name="search-type" value="destination" style="margin: 0;">
                        <span>Destination</span>
                    </label>
                </div>
                <p id="geocode-help" style="margin-top:0.5rem; font-size:0.8rem; color:#6b7280;">Choose location type above, then search to find and pin the location on the map.</p>
            </div>
        </div>
    </div>
</div>

<!-- Case Details Modal -->
<div id="case-details-modal" class="modal-overlay" style="display:none; position:fixed; inset:0; background: rgba(0,0,0,0.35); backdrop-filter: blur(4px); z-index:3000; align-items:center; justify-content:center;">
    <div class="modal-content" style="background: #ffffff; width: 90%; max-width: 500px; height: auto; border-radius: 12px; border: 2px solid var(--brand-navy); box-shadow: 0 10px 25px rgba(3,18,115,0.25); position: relative; overflow: hidden; display: flex; flex-direction: column; align-items: center; justify-content: center;">
        <button type="button" id="close-case-details-modal" class="modal-close" style="position: absolute; top: 10px; right: 12px; background: var(--brand-navy); color: #ffffff; border: 0; width: 36px; height: 36px; border-radius: 6px; cursor: pointer; display: flex; align-items: center; justify-content: center; z-index: 10;">
            <i class="fas fa-times"></i>
        </button>
        <div class="modal-iframe" title="Case Details" style="background:#fff; overflow:auto; border: 0; display: block; width: 100%; height: 100%; padding: 1rem; box-sizing: border-box; overflow: auto;">
            <div style="padding: 1rem; text-align: center; color: var(--brand-navy); font-weight: 700; margin-bottom: 1rem;">
                <h3 style="margin: 0 0 1rem 0; font-size: 1.2rem; display: flex; align-items: center; justify-content: center; gap: 0.5rem;">
                    <i class="fas fa-file-medical" style="color: #10b981;"></i>
                    Case Details
                </h3>
            </div>
            
            <div id="case-details-content">
                <!-- Case details will be loaded here -->
            </div>
        </div>
    </div>
</div>

<!-- Geofence Notification Modal -->
<div id="geofence-notification-modal" class="modal-overlay" style="display:none; position:fixed; inset:0; background: rgba(0,0,0,0.35); backdrop-filter: blur(4px); z-index:10000; align-items:center; justify-content:center;">
    <div class="modal-content" style="background: #ffffff; width: 92%; max-width: 420px; height: auto; border-radius: 12px; border: 2px solid var(--brand-navy); box-shadow: 0 10px 25px rgba(3,18,115,0.25); position: relative; overflow: hidden; display:flex; flex-direction:column;">
        <div class="modal-header" style="background: linear-gradient(135deg, var(--brand-navy) 0%, #1e3a8a 65%); color: #ffffff; border-radius: 12px 12px 0 0; width:100%; display:flex; align-items:center; justify-content:space-between; padding: .6rem .75rem; box-sizing:border-box;">
            <h3 class="modal-title" id="geofence-modal-title" style="color: #ffffff; display: flex; align-items: center; gap: 0.5rem; font-size: .95rem; margin:0;">
                <i class="fas fa-map-marker-alt" style="font-size: 1.1rem; color:#4ade80;"></i>
                Driver Reached Location
            </h3>
            <button type="button" onclick="closeGeofenceModal()" class="modal-close" style="position: static; top:auto; right:auto; background: rgba(255,255,255,0.18); color: #ffffff; width: 28px; height: 28px; border-radius: 6px; display: inline-flex; align-items: center; justify-content: center; flex: 0 0 auto;">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body" id="geofence-notification-content" style="padding: .75rem;">
            <!-- Notification content will be inserted here -->
        </div>
        <div style="padding: .6rem .75rem; border-top: 1px solid #e5e7eb; display: flex; gap: .5rem; justify-content: flex-end; background: #f8fafc;">
            <button onclick="closeGeofenceModal()" style="background: #6b7280; color: #ffffff; border: none; padding: .45rem .85rem; border-radius: 8px; font-weight: 800; cursor: pointer;">
                Dismiss
            </button>
            <button id="geofence-complete-btn" onclick="completeCaseFromGeofence()" style="background: linear-gradient(135deg, #10b981, #059669); color: #ffffff; border: none; padding: .5rem .9rem; border-radius: 8px; font-weight: 800; cursor: pointer; display: inline-flex; align-items: center; gap: .45rem;">
                <i class="fas fa-check-circle"></i>
                Mark as Complete
            </button>
        </div>
    </div>
</div>

<!-- Case Creation Modal -->
        <div id="case-creation-modal" class="modal-overlay" style="display:none; position:fixed; inset:0; background: rgba(0,0,0,0.35); backdrop-filter: blur(4px); z-index:3000; align-items:center; justify-content:center;">
            <div class="modal-content" style="background: #ffffff; width: 90%; max-width: 600px; height: auto; border-radius: 12px; border: 2px solid var(--brand-navy); box-shadow: 0 10px 25px rgba(3,18,115,0.25); position: relative; overflow: hidden; display: flex; flex-direction: column; align-items: center; justify-content: center;">
                <button type="button" id="close-case-creation-modal" class="modal-close" style="position: absolute; top: 10px; right: 12px; background: var(--brand-navy); color: #ffffff; border: 0; width: 36px; height: 36px; border-radius: 6px; cursor: pointer; display: flex; align-items: center; justify-content: center; z-index: 10;">
                    <i class="fas fa-times"></i>
                </button>
                <div id="caseCreationContainer" class="modal-iframe" title="Create New Case" style="background:#fff; overflow:auto; border: 0; display: block; width: 100%; height: 100%; padding: 1rem; box-sizing: border-box; overflow: auto;">
                    <div style="padding: 1rem; text-align: center; color: var(--brand-navy); font-weight: 700; margin-bottom: 1rem;">
                        <h3 style="margin: 0 0 1rem 0; font-size: 1.2rem; display: flex; align-items: center; justify-content: center; gap: 0.5rem;">
                            <i class="fas fa-plus-circle" style="color: #10b981;"></i>
                            Create New Case
                        </h3>
                        <p style="margin: 0 0 1rem 0; font-size: 0.9rem; color: #6b7280; font-weight: 500;">
                            Pin location: <span id="pin-coordinates"></span>
                        </p>
                        <div style="background: #f0f9ff; border: 1px solid #0ea5e9; border-radius: 8px; padding: 0.75rem; margin-bottom: 1rem;">
                            <p style="margin: 0; font-size: 0.8rem; color: #0369a1;">
                                <strong>💡 Tips:</strong><br>
                                • Click on map for pickup location, Ctrl+Click for destination<br>
                                • Use "Pin by Address" button above to search for locations<br>
                                • Select multiple ambulances - first to accept gets the case
                            </p>
                        </div>
                        <div style="display: flex; gap: 0.5rem; justify-content: center; margin-bottom: 1rem;">
                            <button type="button" id="move-pin-btn" style="background: #f59e0b; color: white; border: none; padding: 0.5rem 1rem; border-radius: 6px; font-size: 0.8rem; cursor: pointer; display: flex; align-items: center; gap: 0.3rem;">
                                <i class="fas fa-hand-paper"></i>
                                Move Pin
                            </button>
                            <button type="button" id="cancel-pin-btn" style="background: #ef4444; color: white; border: none; padding: 0.5rem 1rem; border-radius: 6px; font-size: 0.8rem; cursor: pointer; display: flex; align-items: center; gap: 0.3rem;">
                                <i class="fas fa-times"></i>
                                Cancel Pin
                            </button>
                        </div>
                    </div>
                    
                    <form id="case-creation-form" class="compact-case-form">
                        <!-- Personal Information Row -->
                        <div class="form-row">
                            <div class="form-group compact">
                                <label class="form-label compact">
                                    <i class="fas fa-user"></i>
                                    Name *
                                </label>
                                <input type="text" id="case-name" class="form-input compact" placeholder="Enter name" required>
                            </div>
                            <div class="form-group compact">
                                <label class="form-label compact">
                                    <i class="fas fa-phone"></i>
                                    Contact *
                                </label>
                                <input type="tel" id="case-contact" class="form-input compact" placeholder="Enter contact number" required>
                            </div>
                        </div>

                        <!-- Additional Details Row -->
                        <div class="form-row">
                            <div class="form-group compact">
                                <label class="form-label compact">
                                    <i class="fas fa-calendar-alt"></i>
                                    Age
                                </label>
                                <input type="number" id="case-age" class="form-input compact" placeholder="Enter age" min="0" max="120">
                            </div>
                            <div class="form-group compact">
                                <label class="form-label compact">
                                    <i class="fas fa-birthday-cake"></i>
                                    Date of Birth
                                </label>
                                <input type="date" id="case-date-of-birth" class="form-input compact" max="{{ date('Y-m-d') }}">
                            </div>
                        </div>

                        <!-- Address Information Row -->
                        <div class="form-row">
                            <div class="form-group compact" >
                                <label class="form-label compact" >
                                    <i class="fas fa-map-marker-alt"></i>
                                    Pickup Address *
                                </label>
                                <textarea id="case-address" class="form-textarea compact" placeholder="Enter pickup address" rows="2" required ></textarea>
                            </div>
                            <div class="form-group compact">
                                <label class="form-label compact">
                                    <i class="fas fa-flag-checkered"></i>
                                    Destination *
                                </label>
                                <textarea id="case-destination" class="form-textarea compact" placeholder="Enter destination address" rows="2" required></textarea>
                            </div>
                        </div>

                        <!-- Incident Details Row -->
                        <div class="form-row">
                            <div class="form-group compact">
                                <label class="form-label compact">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    Incident Type
                                </label>
                                <select id="case-type" class="form-select compact">
                                    <option value="">Select type</option>
                                    <option value="VA">VA</option>
                                    <option value="TR">TR</option>
                                    <option value="ME">ME</option>
                                    <option value="SB">SB</option>
                                    <option value="OB">OB</option>
                                    <option value="NVAT">NVAT</option>
                                    <option value="COORDINATION">COORDINATION</option>
                                    <option value="TRAINING">TRAINING</option>
                                </select>
                            </div>
                        </div>

                        <!-- Ambulance Assignment -->
                        <div class="form-group" style="margin-bottom: 1rem;">
                            <label class="form-label compact">
                                <i class="fas fa-ambulance" style="color: #ef4444; margin-right: 0.5rem;"></i>
                                Assign Ambulances * <span id="selected-count" style="color: #6b7280; font-size: 0.8rem;">(0 selected)</span>
                            </label>
                            <div id="ambulance-checkboxes" style="max-height: 200px; overflow-y: auto; border: 1px solid #d1d5db; border-radius: 8px; padding: 0.75rem; background: #f9fafb;">
                                @foreach ($ambulances as $ambulance)
                                    <label style="display: flex; align-items: center; padding: 0.5rem; margin-bottom: 0.5rem; background: white; border-radius: 6px; cursor: pointer; border: 1px solid #e5e7eb; transition: all 0.2s ease;" 
                                           onmouseover="this.style.background='#f3f4f6'" onmouseout="this.style.background='white'">
                                        <input type="checkbox" name="ambulances[]" value="{{ $ambulance->id }}" 
                                               style="margin-right: 0.75rem; transform: scale(1.2);" 
                                               onchange="updateSelectedCount()">
                                        <div style="flex: 1;">
                                            <div style="font-weight: 600; color: #1f2937;">{{ $ambulance->name }} (ID: {{ $ambulance->id }})</div>
                                            <div style="display: flex; justify-content: flex-end; align-items: center; margin-top: 0.2rem;">
                                                <div id="case-count-{{ $ambulance->id }}" style="font-size: 0.75rem; color: #6b7280; background: #f3f4f6; padding: 0.2rem 0.4rem; border-radius: 4px;">
                                                    Loading...
                                                </div>
                                            </div>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="form-actions">
                            <button type="submit" class="submit-btn">
                                <i class="fas fa-save"></i>
                                Create Case
                            </button>
                        </div>
                    </form>
                </div>
            </div>
</div>

    </div>
</main>
<hr class="my-divider" style="border: none; height: 4px; background: linear-gradient(to right, #031273, #4CC9F0); width: 100%; margin: 2rem auto; border-radius: 2px; box-shadow: 0 2px 5px rgba(0,0,0,0.12);">

<script>
    
// Cleanup: remove deprecated modals if still present (avoids brief flash from cached DOM)
(function removeDeprecatedModals() {
    try {
        // Remove by id
        document.getElementById('status-modal')?.remove();
        document.getElementById('stops-modal')?.remove();
        document.getElementById('open-status-modal')?.remove();
        document.getElementById('open-stops-modal')?.remove();
    } catch (e) { /* no-op */ }
})();

function toggleSidebar() {
    document.querySelector('.sidenav')?.classList.toggle('active');
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

const inlineModalThemes = {
    info: { bg: 'linear-gradient(135deg, #2563eb, #1d4ed8)', icon: 'fas fa-info-circle' },
    success: { bg: 'linear-gradient(135deg, #16a34a, #15803d)', icon: 'fas fa-check-circle' },
    warning: { bg: 'linear-gradient(135deg, #f59e0b, #d97706)', icon: 'fas fa-triangle-exclamation' },
    danger: { bg: 'linear-gradient(135deg, #ef4444, #dc2626)', icon: 'fas fa-exclamation-circle' }
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
        const finalActions = actions.length ? actions : [{
            label: 'OK',
            variant: 'primary',
            handler: closeInlineModal
        }];

        finalActions.forEach(({ label, variant = 'primary', handler }) => {
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
    if (modal) {
        modal.classList.remove('show');
    }
}

function showInlineNotice(message, options = {}) {
    openInlineModal({
        title: options.title || 'Notice',
        message,
        type: options.type || 'info',
        actions: options.actions
    });
}

function confirmInline(message, options = {}) {
    return new Promise(resolve => {
        openInlineModal({
            title: options.title || 'Confirm Action',
            message,
            type: options.type || 'warning',
            actions: [
                {
                    label: options.cancelLabel || 'Cancel',
                    variant: 'secondary',
                    handler: () => {
                        closeInlineModal();
                        resolve(false);
                    }
                },
                {
                    label: options.confirmLabel || 'Proceed',
                    variant: 'primary',
                    handler: () => {
                        closeInlineModal();
                        resolve(true);
                    }
                }
            ]
        });
    });
}

document.addEventListener('click', function(event) {
    const modal = document.getElementById('inlineModal');
    if (modal && modal.classList.contains('show') && event.target === modal) {
        closeInlineModal();
    }
});

document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeInlineModal();
    }
});

// Update selected ambulance count
function updateSelectedCount() {
    const checkboxes = document.querySelectorAll('input[name="ambulances[]"]:checked');
    const count = checkboxes.length;
    const countSpan = document.getElementById('selected-count');
    if (countSpan) {
        countSpan.textContent = `(${count} selected)`;
    }
}

// Variables for destination pin management
let currentDestinationMarker = null;
let isSettingDestination = false;


// GPS Control Panel functionality
function toggleGPSPanel(event) {
    event.preventDefault();
    event.stopPropagation();
    
    const panel = document.getElementById('gps-control-panel');
    const toggle = document.getElementById('gps-panel-toggle');
    
    if (panel.classList.contains('active')) {
        panel.classList.remove('active');
        toggle.style.transform = 'scale(1)';
    } else {
        panel.classList.add('active');
        toggle.style.transform = 'scale(0.9)';
    }
}

function closeGPSPanel(event) {
    if (event) {
    event.preventDefault();
        event.stopPropagation();
    }
    
    const panel = document.getElementById('gps-control-panel');
    const toggle = document.getElementById('gps-panel-toggle');
    
    panel.classList.remove('active');
    toggle.style.transform = 'scale(1)';
}

// Panel event listeners with proper event handling
document.getElementById('gps-panel-toggle')?.addEventListener('click', toggleGPSPanel);
document.getElementById('close-panel')?.addEventListener('click', closeGPSPanel);

// Fullscreen toggle functionality
function toggleFullscreen(event) {
    // Prevent event propagation to avoid triggering other functions
    event.preventDefault();
    event.stopPropagation();
    event.stopImmediatePropagation();
    
    const body = document.body;
    const mapContainer = document.getElementById('map-container');
    const fullscreenToggle = document.getElementById('fullscreen-toggle');
    const fullscreenClose = document.getElementById('fullscreen-close');
    
    if (body.classList.contains('fullscreen-mode')) {
        // Exit fullscreen
        body.classList.remove('fullscreen-mode');
        
        // Reset UI hidden state when exiting fullscreen
        if (body.classList.contains('ui-hidden')) {
            body.classList.remove('ui-hidden');
            const gpsUiToggleIcon = document.getElementById('gps-ui-toggle-icon');
            if (gpsUiToggleIcon) {
                gpsUiToggleIcon.classList.remove('fa-eye-slash');
                gpsUiToggleIcon.classList.add('fa-eye');
            }
            uiHidden = false;
        }
        fullscreenToggle.style.display = 'flex';
        fullscreenToggle.style.left = '50%';
        fullscreenToggle.style.transform = 'translateX(-50%)';
        fullscreenClose.style.display = 'none';
        
        // Restore original map styles
        mapContainer.style.cssText = 'height: calc(100vh - var(--header-height) - 4rem); width: 75vw; min-height: 500px; max-width: 1400px; margin: 0 auto; border: 6px solid; border-image: linear-gradient(90deg, #f59e0b, #ff8c42) 1; border-radius: 0; box-shadow: 0 8px 32px rgba(245, 158, 11, 0.15), 0 2px 8px rgba(255, 140, 66, 0.10); display: block; position: relative; left: 50%; transform: translateX(-50%);';

        // Restore control positions in normal view
        try {
            positionTopControls();
            setTimeout(positionTopControls, 10);
        } catch (e) { /* no-op */ }
        
        // Trigger map resize to ensure proper rendering
        setTimeout(() => {
            map.invalidateSize();
            try { redact = 0; } catch(_){}
            try { redrawTrailsFromCache(); } catch(_){}
        }, 200);
    } else {
        // Enter fullscreen
        body.classList.add('fullscreen-mode');
        fullscreenToggle.style.display = 'none';
        fullscreenClose.style.display = 'flex';
        fullscreenClose.style.left = '50%';
        fullscreenClose.style.transform = 'translateX(-50%)';
        
        // Override inline styles to force fullscreen
        mapContainer.style.cssText = 'position: fixed !important; top: 0 !important; left: 0 !important; width: 100vw !important; height: 100vh !important; max-width: none !important; margin: 0 !important; padding: 0 !important; transform: none !important; z-index: 1000 !important; border-radius: 0 !important; background: white !important; border: none !important; overflow: hidden !important;';

        // Ensure top-left filter controls and top-right GPS toggle are visible and aligned in fullscreen
        try {
            positionTopControls();
            alignGpsToggleWithRow();
            const gpsRow = document.getElementById('gps-row-toggle');
            if (gpsRow) gpsRow.style.display = 'none';
            const filterPanel = document.getElementById('filterPanel');
            if (filterPanel) filterPanel.style.zIndex = '10003';
        } catch (e) { /* no-op */ }
        
        // Trigger map resize to ensure proper rendering
        setTimeout(() => {
            try {
                map.invalidateSize(true);
            } catch(_){}
            try { redrawTrailsFromCache(); } catch(_){}
        }, 250);
    }
}

function exitFullscreen(event) {
    // Reset UI hidden state when exiting fullscreen
    if (document.body.classList.contains('ui-hidden')) {
        document.body.classList.remove('ui-hidden');
        const gpsUiToggleIcon = document.getElementById('gps-ui-toggle-icon');
        if (gpsUiToggleIcon) {
            gpsUiToggleIcon.classList.remove('fa-eye-slash');
            gpsUiToggleIcon.classList.add('fa-eye');
        }
        uiHidden = false;
    }
    // Prevent event propagation to avoid triggering other functions
    event.preventDefault();
    event.stopPropagation();
    event.stopImmediatePropagation();
    
    const body = document.body;
    const mapContainer = document.getElementById('map-container');
    const fullscreenToggle = document.getElementById('fullscreen-toggle');
    const fullscreenClose = document.getElementById('fullscreen-close');
    
    body.classList.remove('fullscreen-mode');
    fullscreenToggle.style.display = 'flex';
    fullscreenClose.style.display = 'none';
    // Restore GPS toggle/button for normal view
    try {
        const gpsToggle = document.getElementById('gps-panel-toggle');
        if (gpsToggle) {
            gpsToggle.style.display = '';
            gpsToggle.style.position = 'absolute';
            gpsToggle.style.right = '20px';
        }
        const gpsRow = document.getElementById('gps-row-toggle');
        if (gpsRow) gpsRow.style.display = 'none';
    } catch (e) { /* no-op */ }
    // restore gps toggle positioning mode
    try {
        const gpsToggle = document.getElementById('gps-panel-toggle');
        if (gpsToggle) gpsToggle.style.position = 'absolute';
    } catch (e) { /* no-op */ }
    
    // Restore original map styles
    mapContainer.style.cssText = 'height: calc(100vh - var(--header-height) - 4rem); width: 75vw; min-height: 500px; max-width: 1400px; margin: 0 auto; border: 6px solid; border-image: linear-gradient(90deg, #f59e0b, #ff8c42) 1; border-radius: 0; box-shadow: 0 8px 32px rgba(245, 158, 11, 0.15), 0 2px 8px rgba(255, 140, 66, 0.10); display: block; position: relative; left: 50%; transform: translateX(-50%);';
    
    // Trigger map resize to ensure proper rendering
    setTimeout(() => {
        map.invalidateSize();
        try { redrawTrailsFromCache(); } catch(_){}
    }, 200);
}

// Add event listeners for fullscreen buttons with proper event handling
document.getElementById('fullscreen-toggle')?.addEventListener('click', toggleFullscreen, true);
document.getElementById('fullscreen-close')?.addEventListener('click', exitFullscreen, true);

// UI Toggle functionality - Hide/Show UI elements in fullscreen mode (matching dashboard view)
const gpsUiToggle = document.getElementById('gps-ui-toggle');
const gpsUiToggleIcon = document.getElementById('gps-ui-toggle-icon');
let uiHidden = false;

if (gpsUiToggle && gpsUiToggleIcon) {
    gpsUiToggle.addEventListener('click', function(event) {
        // Prevent event propagation to map to avoid triggering map clicks
        event.preventDefault();
        event.stopPropagation();
        event.stopImmediatePropagation();
        
        uiHidden = !uiHidden;
        
        if (uiHidden) {
            // Hide UI elements
            document.body.classList.add('ui-hidden');
            gpsUiToggleIcon.classList.remove('fa-eye');
            gpsUiToggleIcon.classList.add('fa-eye-slash');
            gpsUiToggle.title = 'Show UI';
        } else {
            // Show UI elements
            document.body.classList.remove('ui-hidden');
            gpsUiToggleIcon.classList.remove('fa-eye-slash');
            gpsUiToggleIcon.classList.add('fa-eye');
            gpsUiToggle.title = 'Hide UI';
        }
    });
}

// Prevent map interaction when clicking on panel elements
document.getElementById('gps-control-panel')?.addEventListener('click', function(event) {
    event.stopPropagation();
});
document.getElementById('filterPanel')?.addEventListener('click', function(event) {
    event.preventDefault();
    event.stopPropagation();
});
document.getElementById('filterToggle')?.addEventListener('click', function(event) {
    event.preventDefault();
    event.stopPropagation();
});

// Row GPS Control button toggles the existing GPS control panel (fullscreen only)
document.getElementById('gps-row-toggle')?.addEventListener('click', function(e){
    e.preventDefault(); e.stopPropagation();
    const panel = document.getElementById('gps-control-panel');
    if (!panel) return;
    panel.style.display = (panel.style.display === 'none') ? '' : (panel.style.display === '' ? 'none' : 'none');
});

// Dynamically position top controls below status bar (prevents overlap on fullscreen/varied heights)
function positionTopControls(){
    try {
        const statusBar = document.querySelector('.gm-status-bar');
        const rect = statusBar && statusBar.getBoundingClientRect ? statusBar.getBoundingClientRect() : null;
        const barH = rect && rect.height ? rect.height : (statusBar ? statusBar.offsetHeight : 40);
        const pad = 24; // spacing below bar
        const topPx = (Math.round(barH) + pad) + 'px';

        const gmControls = document.querySelector('.gm-controls');
        if (gmControls) gmControls.style.top = topPx;

        // gps toggle and fullscreen are anchored bottom now; don't override their top in normal mode
        const gpsToggle = document.getElementById('gps-panel-toggle');
        if (gpsToggle) {
            gpsToggle.style.bottom = '20px';
            gpsToggle.style.top = '';
        }

        const fullscreenBtn = document.getElementById('fullscreen-toggle');
        if (fullscreenBtn) {
            fullscreenBtn.style.bottom = '20px';
            fullscreenBtn.style.left = '20px';
            fullscreenBtn.style.top = '';
            fullscreenBtn.style.transform = 'none';
            fullscreenBtn.style.position = 'absolute';
        }

        const fullscreenClose = document.getElementById('fullscreen-close');
        if (fullscreenClose) {
            fullscreenClose.style.top = topPx;
        }

        // Push GPS control sliding panel below top controls as well
        const gpsPanel = document.getElementById('gps-control-panel');
        if (gpsPanel) gpsPanel.style.top = `calc(${topPx} + 48px)`; // panel sits further down

        // Ensure buttons sit within the map container bounds (absolute anchor from container)
        const mapRect = map.getBoundingClientRect ? document.getElementById('map-container').getBoundingClientRect() : null;
        if (mapRect) {
            const fsBtn = document.getElementById('fullscreen-toggle');
            const cogBtn = document.getElementById('gps-panel-toggle');
            if (fsBtn) { fsBtn.style.position = 'absolute'; fsBtn.style.left = '20px'; fsBtn.style.bottom = '20px'; }
            if (cogBtn) { cogBtn.style.position = 'absolute'; cogBtn.style.right = '20px'; cogBtn.style.bottom = '20px'; }
        }
    } catch (e) { /* no-op */ }
}

    // Initial positioning and on resize (and after fonts/layout stabilize)
    window.addEventListener('load', () => { positionTopControls(); setTimeout(positionTopControls, 50); });
    window.addEventListener('resize', positionTopControls);

// Ensure GPS toggle exactly matches the filter row top even if styles change later
function alignGpsToggleWithRow(){
    try {
        const map = document.getElementById('map-container');
        const row = document.querySelector('.gm-controls');
        const cog = document.getElementById('gps-panel-toggle');
        if (!map || !row || !cog) return;
        // Keep cog bottom-right anchored
        cog.style.bottom = '20px';
        cog.style.right = '20px';
        cog.style.top = '';
        cog.style.position = 'absolute';
        cog.style.zIndex = '10002';
        // ensure paths are refreshed after layout shifts
        try { refreshCaseStatuses(); } catch(_){}
    } catch(e) { /* no-op */ }
}

// Call alongside other adjustments
window.addEventListener('load', () => setTimeout(alignGpsToggleWithRow, 60));
window.addEventListener('resize', () => setTimeout(alignGpsToggleWithRow, 10));

// Filter UI removed by request

// ===== Driver Filter UI wiring =====
(function initDriverFilterUI(){
    const toggle = document.getElementById('driver-filter-toggle');
    const panel = document.getElementById('driver-filter-panel');
    const closeBtn = document.getElementById('close-driver-filter');
    const applyBtn = document.getElementById('driver-filter-apply');
    const prevBtn = document.getElementById('driver-filter-prev');
    const nextBtn = document.getElementById('driver-filter-next');
    const listContainer = document.getElementById('driver-filter-list');

    function openPanel(){ panel.classList.add('active'); }
    function closePanel(){ panel.classList.remove('active'); }

    function applySearch(){ filteredDriversForFilter = allDriversForFilter; }

    function updatePagination(){
        driverFilterTotalPages = Math.max(1, Math.ceil((filteredDriversForFilter.length || 0) / driverFilterItemsPerPage));
        if (driverFilterCurrentPage > driverFilterTotalPages) driverFilterCurrentPage = Math.max(1, driverFilterTotalPages);
        prevBtn.disabled = driverFilterCurrentPage <= 1;
        nextBtn.disabled = driverFilterCurrentPage >= driverFilterTotalPages;
    }

    function renderList(){
        listContainer.innerHTML = '';
        const start = (driverFilterCurrentPage - 1) * driverFilterItemsPerPage;
        const end = start + driverFilterItemsPerPage;
        const page = filteredDriversForFilter.slice(start, end);

        // Add "All drivers"
        const selected = String(currentFilterDriverId);
        const lblAll = document.createElement('label');
        lblAll.className = 'gm-filter-option';
        lblAll.style.cssText = 'display:block; padding:6px 8px; border-radius:6px;';
        lblAll.innerHTML = `<input type="radio" name="driverFilter" value="all" ${selected==='all'?'checked':''}> All drivers`;
        listContainer.appendChild(lblAll);

        page.forEach(d => {
            const lbl = document.createElement('label');
            lbl.className = 'gm-filter-option';
            lbl.style.cssText = 'display:block; padding:6px 8px; border-radius:6px;';
            lbl.innerHTML = `<input type=\"radio\" name=\"driverFilter\" value=\"${d.id}\" ${selected===String(d.id)?'checked':''}> ${d.label}`;
            listContainer.appendChild(lbl);
        });
    }

    function rebuild(){
        applySearch();
        updatePagination();
        renderList();
    }

    toggle?.addEventListener('click', function(e){ e.preventDefault(); e.stopPropagation(); panel.classList.contains('active') ? closePanel() : openPanel(); });
    closeBtn?.addEventListener('click', function(e){ e.preventDefault(); e.stopPropagation(); closePanel(); });
    prevBtn?.addEventListener('click', function(){ if (driverFilterCurrentPage > 1){ driverFilterCurrentPage--; renderList(); updatePagination(); }});
    nextBtn?.addEventListener('click', function(){ if (driverFilterCurrentPage < driverFilterTotalPages){ driverFilterCurrentPage++; renderList(); updatePagination(); }});
    // no search input; we list available drivers only

    applyBtn?.addEventListener('click', function(e){
        e.preventDefault(); e.stopPropagation();
        const sel = document.querySelector('input[name="driverFilter"]:checked');
        currentFilterDriverId = sel ? sel.value : 'all';
        closePanel();
        // Refresh markers with current filter
        fetchAmbulanceData();
        // Re-render cases to respect driver-case filtering
        if (typeof loadExistingCases === 'function') { try { loadExistingCases(); } catch(_){} }
        if (typeof refreshCaseStatuses === 'function') { try { refreshCaseStatuses(); } catch(_){} }
    });

    // Close when clicking outside
    document.addEventListener('click', function(e){
        if (panel && panel.classList.contains('active')){
            const within = panel.contains(e.target) || toggle.contains(e.target);
            if (!within) closePanel();
        }
    });

    // Expose rebuild to be called after data load
    window.__driverFilterRebuild = function(){ rebuild(); };
})();

// ===== Quick driver search overlay wiring =====
(function initQuickDriverSearch(){
    const input = document.getElementById('quick-driver-input');
    const results = document.getElementById('quick-driver-results');
    const clearBtn = document.getElementById('quick-driver-clear');
    const btnAll = document.getElementById('btn-all-drivers');
    const btnActive = document.getElementById('btn-active-cases');
    const btnOpen = document.getElementById('quick-driver-open');
    const form = document.getElementById('quick-driver-form');
    const formInput = document.getElementById('quick-driver-input');
    const btnClose = document.getElementById('quick-driver-close');
    if (!input || !results) return;

    // Prevent map clicks/other handlers when interacting with search
    const panel = document.getElementById('driver-quick-panel');
    [panel, input, results, clearBtn, btnAll, btnActive, btnOpen, form, formInput, btnClose].forEach(el => {
        if (!el) return;
        el.addEventListener('click', function(e){ e.stopPropagation(); e.preventDefault(); });
        el.addEventListener('mousedown', function(e){ e.stopPropagation(); });
        el.addEventListener('touchstart', function(e){ e.stopPropagation(); });
    });

    function hideResults(){ results.style.display = 'none'; results.innerHTML=''; }
    function showResults(items){
        results.innerHTML = '';
        if (!items || items.length === 0){ hideResults(); return; }
        items.slice(0, 20).forEach(it => {
            const row = document.createElement('div');
            row.style.cssText = 'padding:8px 10px; cursor:pointer; border-bottom:1px solid #f3f4f6;';
            row.textContent = it.label;
            row.addEventListener('mouseenter', () => row.style.background = '#f8fafc');
            row.addEventListener('mouseleave', () => row.style.background = '#ffffff');
            row.addEventListener('click', (e) => {
                e.preventDefault(); e.stopPropagation();
                input.value = it.label;
                clearBtn.style.display = 'inline-block';
                // it.id is already using 'amb-{id}' per build; also support raw driver id labels
                applyDriverSelection(String(it.id));
                hideResults();
            });
            results.appendChild(row);
        });
        results.style.display = 'block';
    }

    input.addEventListener('input', function(e){
        const term = (e.target.value || '').toLowerCase().trim();
        if (!term){ hideResults(); return; }
        const list = (allDriversForFilter || []).filter(d => {
            const lbl = (d.label || '').toLowerCase();
            const idStr = String(d.id || '').toLowerCase();
            return lbl.includes(term) || idStr.includes(term);
        });
        showResults(list);
    });

    input.addEventListener('focus', function(){
        const term = (input.value || '').toLowerCase().trim();
        if (!term){ hideResults(); return; }
        const list = (allDriversForFilter || []).filter(d => (d.label||'').toLowerCase().includes(term));
        showResults(list);
    });

    document.addEventListener('click', function(e){
        if (!results) return;
        const within = results.contains(e.target) || (input && input.contains && input.contains(e.target));
        if (!within) hideResults();
    });

    if (clearBtn){
        clearBtn.addEventListener('click', function(e){
            e.preventDefault(); e.stopPropagation();
            input.value='';
            hideResults();
            showActiveOnly = false;
            applyDriverSelection('all');
        });
    }

    if (btnAll){
        btnAll.addEventListener('click', function(e){
            e.preventDefault(); e.stopPropagation();
            showActiveOnly = false;
            applyDriverSelection('all');
            try { applyDriverVisibility(); } catch(_){ }
            try { refreshCaseStatuses(); } catch(_){ }
        });
    }

    if (btnActive){
        btnActive.addEventListener('click', async function(e){
            e.preventDefault(); e.stopPropagation();
            if (isActiveModeApplying) return;
            isActiveModeApplying = true;
            try {
                showActiveOnly = true;
                currentFilterDriverId = 'all';
                await Promise.resolve(refreshCaseStatuses());
                // Guarantee visibility after trails/sets are up to date
                applyDriverVisibility();
            } finally {
                isActiveModeApplying = false;
            }
        });
    }

    // Open/Close small form; keep z-index high
    if (btnOpen && form){
        btnOpen.addEventListener('click', function(e){
            e.preventDefault(); e.stopPropagation();
            form.style.display = 'block';
            try { form.style.zIndex = '10030'; } catch(_){ }
            setTimeout(() => { try { formInput && formInput.focus(); } catch(_){ } }, 0);
        });
    }
    if (btnClose && form){
        btnClose.addEventListener('click', function(e){
            e.preventDefault(); e.stopPropagation();
            form.style.display = 'none';
        });
    }

    // Sync form input to main search input behavior
    if (formInput){
        formInput.addEventListener('input', function(e){
            const val = e.target.value || '';
            // Drive suggestions list directly
            const term = val.toLowerCase().trim();
            if (!term){ results.style.display='none'; results.innerHTML=''; return; }
            const list = (allDriversForFilter || []).filter(d => {
                const lbl = (d.label || '').toLowerCase();
                const idStr = String(d.id || '').toLowerCase();
                return lbl.includes(term) || idStr.includes(term);
            });
            // Render results under the form
            // Position suggestions right below the input field
            try {
                const rect = formInput.getBoundingClientRect();
                const mapRect = document.getElementById('map-container').getBoundingClientRect();
                const topPx = Math.max(0, rect.bottom - mapRect.top + 4);
                const leftPx = Math.max(0, rect.left - mapRect.left);
                results.style.top = topPx + 'px';
                results.style.left = (leftPx) + 'px';
                results.style.minWidth = Math.max(300, rect.width) + 'px';
            } catch(_){
                results.style.left = '16px';
                results.style.top = '172px';
            }
            results.innerHTML = '';
            list.slice(0, 20).forEach(it => {
                const row = document.createElement('div');
                row.style.cssText = 'padding:8px 10px; cursor:pointer; border-bottom:1px solid #f3f4f6;';
                row.textContent = it.label;
                row.addEventListener('mouseenter', () => row.style.background = '#f8fafc');
                row.addEventListener('mouseleave', () => row.style.background = '#ffffff');
                row.addEventListener('click', (ev) => {
                    ev.preventDefault(); ev.stopPropagation();
                    formInput.value = it.label;
                    applyDriverSelection(String(it.id));
                    try { applyDriverVisibility(); } catch(_){ }
                    try { refreshCaseStatuses(); } catch(_){ }
                    results.style.display = 'none';
                    form.style.display = 'none';
                });
                results.appendChild(row);
            });
            results.style.display = list.length ? 'block' : 'none';
        });
        formInput.addEventListener('keydown', function(e){
            if (e.key === 'Enter'){
                e.preventDefault(); e.stopPropagation();
                // pick first result if available
                const first = (allDriversForFilter || []).find(d => {
                    const term = (formInput.value||'').toLowerCase();
                    return (d.label||'').toLowerCase().includes(term) || String(d.id||'').toLowerCase().includes(term);
                });
                if (first){
                    applyDriverSelection(String(first.id));
                    try { applyDriverVisibility(); } catch(_){ }
                    try { refreshCaseStatuses(); } catch(_){ }
                    form.style.display = 'none';
                }
            }
        });
    }

    // Removed Apply button; click suggestion or press Enter to apply
})();

// Modal functionality
function openModal(modalId) {
    const modal = document.getElementById(modalId);
    // Hide other known modals only (avoid touching core layout like map container)
    ['active-modal','logs-modal','case-creation-modal','case-details-modal','geocode-modal']
        .forEach(id => { if (id !== modalId) { const el = document.getElementById(id); if (el) el.style.display = 'none'; }});
    modal.style.display = 'flex';
    modal.style.alignItems = 'center';
    modal.style.justifyContent = 'center';
    document.body.classList.add('modal-open');
    closeGPSPanel(); // Close panel when modal opens
    
    // If it's the case creation modal, show coordinates
    if (modalId === 'case-creation-modal' && window.clickedLatitude && window.clickedLongitude) {
        document.getElementById('pin-coordinates').textContent = 
            `${window.clickedLatitude.toFixed(6)}, ${window.clickedLongitude.toFixed(6)}`;
    }
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    modal.style.display = 'none';
    document.body.classList.remove('modal-open');
    // Don't remove pins when closing - let user keep them when re-opening to adjust
}

// (Test geofence trigger removed)

// Case details modal functions
function openCaseDetailsModal(caseData) {
    const modal = document.getElementById('case-details-modal');
    const content = document.getElementById('case-details-content');
    
    if (!modal || !content) {
        console.error('Modal or content element not found');
        return;
    }
    
    // Create detailed case information
    const caseDetails = `
        <div style="background: #ffffff; border: 2px solid #e5e7eb; border-radius: 12px; padding: 1rem; box-shadow: 0 4px 12px rgba(3, 18, 115, 0.08);">
            <!-- Case Header -->
            <div style="text-align: center; margin-bottom: 1rem; padding-bottom: 0.75rem; border-bottom: 1px solid #e5e7eb;">
                <h3 style="margin: 0 0 0.5rem 0; color: #1e293b; font-size: 1.1rem; font-weight: 700;">
                    Case #${caseData.case_num}
                </h3>
                <div style="display: flex; gap: 0.3rem; justify-content: center; flex-wrap: wrap;">
                    <span style="background: ${getPriorityColor(caseData.priority || 'Medium')}; color: white; padding: 0.2rem 0.5rem; border-radius: 8px; font-size: 0.7rem; font-weight: 600;">
                        ${caseData.priority || 'Medium'}
                    </span>
                    ${caseData.type ? `<span style="background: #3b82f6; color: white; padding: 0.2rem 0.5rem; border-radius: 8px; font-size: 0.7rem; font-weight: 600;">${caseData.type}</span>` : ''}
                    <span style="background: #f59e0b; color: white; padding: 0.2rem 0.5rem; border-radius: 8px; font-size: 0.7rem; font-weight: 600;">
                        🚑 Assigned
                    </span>
                </div>
            </div>
            
            <!-- Compact Content -->
            <div style="margin-bottom: 1rem;">
                <!-- Patient Information -->
                <div style="margin-bottom: 0.75rem;">
                    <h4 style="margin: 0 0 0.3rem 0; color: #374151; font-size: 0.8rem; font-weight: 600; display: flex; align-items: center; gap: 0.3rem;">
                        <i class="fas fa-user" style="color: #3b82f6; font-size: 0.7rem;"></i>
                        Patient Information
                    </h4>
                    <div style="background: #f8fafc; padding: 0.5rem; border-radius: 6px; border-left: 3px solid #3b82f6; font-size: 0.75rem;">
                        <div style="margin-bottom: 0.2rem;"><strong>Name:</strong> ${caseData.name}</div>
                        <div style="margin-bottom: 0.2rem;"><strong>Contact:</strong> ${caseData.contact}</div>
                        <div><strong>Address:</strong> ${caseData.address}</div>
                    </div>
                </div>
                
                <!-- Location Information -->
                <div style="margin-bottom: 0.75rem;">
                    <h4 style="margin: 0 0 0.3rem 0; color: #374151; font-size: 0.8rem; font-weight: 600; display: flex; align-items: center; gap: 0.3rem;">
                        <i class="fas fa-map-marker-alt" style="color: #ef4444; font-size: 0.7rem;"></i>
                        Location Details
                    </h4>
                    <div style="background: #fef2f2; padding: 0.5rem; border-radius: 6px; border-left: 3px solid #ef4444; font-size: 0.75rem;">
                        <div style="margin-bottom: 0.2rem;"><strong>Coordinates:</strong> ${caseData.latitude ? parseFloat(caseData.latitude).toFixed(4) : 'N/A'}, ${caseData.longitude ? parseFloat(caseData.longitude).toFixed(4) : 'N/A'}</div>
                        <div><strong>Status:</strong> <span style="color: #f59e0b; font-weight: 600;">${caseData.status || 'Pending'}</span></div>
                    </div>
                </div>
                
                <!-- Assignment Information -->
                ${caseData.ambulance ? `
                <div style="margin-bottom: 0.75rem;">
                    <h4 style="margin: 0 0 0.3rem 0; color: #374151; font-size: 0.8rem; font-weight: 600; display: flex; align-items: center; gap: 0.3rem;">
                        <i class="fas fa-ambulance" style="color: #10b981; font-size: 0.7rem;"></i>
                        Assignment
                    </h4>
                    <div style="background: #f0fdf4; padding: 0.5rem; border-radius: 6px; border-left: 3px solid #10b981; font-size: 0.75rem;">
                        <div style="margin-bottom: 0.2rem;"><strong>Ambulance:</strong> ${caseData.ambulance.name}</div>
                        <div><strong>Driver:</strong> ${caseData.driver || 'Not assigned'}</div>
                    </div>
                </div>
                ` : ''}
                
                <!-- Timeline -->
                <div style="margin-bottom: 0.75rem;">
                    <h4 style="margin: 0 0 0.3rem 0; color: #374151; font-size: 0.8rem; font-weight: 600; display: flex; align-items: center; gap: 0.3rem;">
                        <i class="fas fa-clock" style="color: #8b5cf6; font-size: 0.7rem;"></i>
                        Timeline
                    </h4>
                    <div style="background: #faf5ff; padding: 0.5rem; border-radius: 6px; border-left: 3px solid #8b5cf6; font-size: 0.75rem;">
                        <div style="margin-bottom: 0.2rem;"><strong>Created:</strong> ${new Date(caseData.created_at).toLocaleDateString()} ${new Date(caseData.created_at).toLocaleTimeString()}</div>
                        <div><strong>Updated:</strong> ${new Date(caseData.updated_at).toLocaleDateString()} ${new Date(caseData.updated_at).toLocaleTimeString()}</div>
                    </div>
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div style="display: flex; gap: 0.4rem; justify-content: center; margin-top: 1rem; padding-top: 0.75rem; border-top: 1px solid #e5e7eb; flex-wrap: wrap;">
                <button onclick="closeCaseDetailsModal()" style="background: #6b7280; color: white; border: none; padding: 0.5rem 1rem; border-radius: 6px; font-weight: 600; cursor: pointer; transition: all 0.3s ease; font-size: 0.75rem; display: flex; align-items: center; gap: 0.3rem;">
                    <i class="fas fa-times"></i>
                    Close
                </button>
                <button onclick="completeCaseAsAdmin(${caseData.case_num})" style="background: linear-gradient(135deg, #8b5cf6, #7c3aed); color: white; border: none; padding: 0.5rem 1rem; border-radius: 6px; font-weight: 600; cursor: pointer; transition: all 0.3s ease; font-size: 0.75rem; display: flex; align-items: center; gap: 0.3rem;">
                    <i class="fas fa-check-circle"></i>
                    Complete Case
                </button>
                <button onclick="updateCaseStatus(${caseData.case_num})" style="background: linear-gradient(135deg, #10b981, #059669); color: white; border: none; padding: 0.5rem 1rem; border-radius: 6px; font-weight: 600; cursor: pointer; transition: all 0.3s ease; font-size: 0.75rem; display: flex; align-items: center; gap: 0.3rem;">
                    <i class="fas fa-edit"></i>
                    Update
                </button>
                <button onclick="deleteCase(${caseData.case_num})" style="background: linear-gradient(135deg, #ef4444, #dc2626); color: white; border: none; padding: 0.5rem 1rem; border-radius: 6px; font-weight: 600; cursor: pointer; transition: all 0.3s ease; font-size: 0.75rem; display: flex; align-items: center; gap: 0.3rem;">
                    <i class="fas fa-trash"></i>
                    Delete
                </button>
            </div>
        </div>
    `;
    
    content.innerHTML = caseDetails;
    openModal('case-details-modal');
}

function closeCaseDetailsModal() {
    closeModal('case-details-modal');
}

function updateCaseStatus(caseNum) {
    // This function can be implemented later for status updates
    showInlineNotice('Status update functionality will be implemented soon!', {
        title: 'Coming Soon'
    });
}

async function deleteCase(caseNum) {
    // Show confirmation dialog
    const confirmed = await confirmInline(`Are you sure you want to delete Case #${caseNum}? This action cannot be undone.`, {
        title: 'Delete Case',
        confirmLabel: 'Delete',
        type: 'danger'
    });
    if (!confirmed) return;
    
    try {
        const response = await fetch(`/admin/cases/${caseNum}`, {
            method: 'DELETE',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        
        if (response.ok) {
            // Show success notification
            showNotification(`Case #${caseNum} deleted successfully!`, 'success');
            
            // Close the modal
            closeCaseDetailsModal();
            
            // Remove marker from map
            if (caseMarkers[caseNum]) {
                map.removeLayer(caseMarkers[caseNum]);
                delete caseMarkers[caseNum];
            }
            
            // Refresh case counts
            loadAmbulanceCaseCounts();
        } else {
            const error = await response.json();
            showInlineNotice('Error deleting case: ' + (error.message || 'Unknown error'), {
                title: 'Delete Failed',
                type: 'danger'
            });
        }
    } catch (error) {
        console.error('Error deleting case:', error);
        showInlineNotice('Error deleting case. Please try again.', {
            title: 'Delete Failed',
            type: 'danger'
        });
    }
}

async function completeCaseAsAdmin(caseNum) {
    console.log(`🎯 Admin completing case #${caseNum}`);
    
    // Show confirmation dialog
    const confirmed = await confirmInline(`Are you sure you want to mark Case #${caseNum} as completed?\n\nThis will:\n- Mark the case as completed\n- Remove it from the map\n- Move it to the completed cases log\n- This action cannot be undone`, {
        title: 'Complete Case',
        confirmLabel: 'Complete',
        type: 'warning'
    });
    if (!confirmed) return;
    
    try {
        const response = await fetch(`/admin/cases/${caseNum}/complete`, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        
        if (response.ok) {
            const result = await response.json();
            console.log('✅ Case completed successfully:', result);
            
            // Verify completion from server to ensure consistency
            console.log('🔍 Verifying case completion from server...');
            await verifyAndSyncCase(caseNum, true);
            
            // Show success notification
            showNotification(`Case #${caseNum} completed successfully!`, 'success');
            
            // Close the modal
            closeCaseDetailsModal();
            
            // Immediately remove markers to reflect completion without waiting for refresh
            removeCaseFromMap(caseNum);
            
            // Update in-memory caches so UI lists stay in sync
            if (Array.isArray(activeCasesCache)) {
                activeCasesCache = activeCasesCache.filter(c => String(c.case_num) !== String(caseNum));
            }
            
            // Refresh case counts
            loadAmbulanceCaseCounts();
        } else {
            const error = await response.json();
            console.error('❌ Error completing case:', error);
            showInlineNotice('Error completing case: ' + (error.message || 'Unknown error'), {
                title: 'Completion Failed',
                type: 'danger'
            });
        }
    } catch (error) {
        console.error('❌ Error completing case:', error);
        showInlineNotice('Error completing case. Please try again.', {
            title: 'Completion Failed',
            type: 'danger'
        });
    }
}

async function markNotificationSent(caseNum) {
    try {
        const response = await fetch(`/admin/cases/${caseNum}/status`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                status: 'Pending',
                driver_accepted: false,
                notification_sent: true
            })
        });
        
        if (response.ok) {
            console.log(`Notification sent for case #${caseNum}`);
        }
    } catch (error) {
        console.error('Error marking notification sent:', error);
    }
}

// Modal event listeners with proper event handling

// Removed Status/Stops modals and triggers

document.getElementById('open-logs-modal')?.addEventListener('click', function(event) {
    event.preventDefault();
    event.stopPropagation();
    openModal('logs-modal');
    loadCompletedCases();
});

document.getElementById('open-active-modal')?.addEventListener('click', function(event) {
    event.preventDefault();
    event.stopPropagation();
    openModal('active-modal');
    loadActiveCases();
});

document.getElementById('open-geocode-modal')?.addEventListener('click', function(event) {
    event.preventDefault();
    event.stopPropagation();
    openModal('geocode-modal');
});

document.getElementById('close-logs-modal')?.addEventListener('click', function(event) {
    event.preventDefault();
    event.stopPropagation();
    closeModal('logs-modal');
});

document.getElementById('close-active-modal')?.addEventListener('click', function(event) {
    event.preventDefault();
    event.stopPropagation();
    closeModal('active-modal');
});

document.getElementById('close-geocode-modal')?.addEventListener('click', function(event) {
    event.preventDefault();
    event.stopPropagation();
    closeModal('geocode-modal');
});

document.getElementById('geocode-pin-btn')?.addEventListener('click', function(event) {
    event.preventDefault();
    event.stopPropagation();
    const input = document.getElementById('geocode-address-input');
    const address = input ? input.value.trim() : '';
    if (!address) {
        showInlineNotice('Please enter an address.', {
            title: 'Missing Address'
        });
        return;
    }
    
    // Get selected search type
    const searchType = document.querySelector('input[name="search-type"]:checked')?.value || 'pickup';
    geocodeAndPinFromAddress(address, searchType);
    
    // Close the geocode modal after initiating
    closeModal('geocode-modal');
});

// Autocomplete for geocoding input (Nominatim search)
let geocodeSuggestController = null;
document.getElementById('geocode-address-input')?.addEventListener('input', async function(e) {
    const q = (e.target.value || '').trim();
    const ul = document.getElementById('geocode-suggestions');
    if (!ul) return;
    if (q.length < 3) { ul.style.display = 'none'; ul.innerHTML=''; return; }

    // Abort previous request
    if (geocodeSuggestController) geocodeSuggestController.abort();
    geocodeSuggestController = new AbortController();
    try {
        const url = `https://nominatim.openstreetmap.org/search?format=jsonv2&limit=8&q=${encodeURIComponent(q)}`;
        const res = await fetch(url, { signal: geocodeSuggestController.signal, headers: { 'Accept': 'application/json' } });
        if (!res.ok) throw new Error('Suggest failed');
        let items = await res.json();
        // Keep only Luzon results in the Philippines, then prioritize Luzon in ordering
        const luzonKeywords = [
            'luzon','ncr','metro manila','manila','quezon city','makati','pasig','taguig','mandaluyong','muntinlupa','parañaque','pasay','caloocan','navotas','valenzuela','malabon','marikina','san juan',
            'cavite','laguna','batangas','rizal','quezon province','lucena','calabarzon','region iv-a',
            'bulacan','pampanga','bataan','zambales','tarlac','nueva ecija','aurora','central luzon','region iii',
            'pangasinan','la union','ilocos norte','ilocos sur','ilocos region','region i',
            'benguet','abra','apayao','ifugao','kalinga','mountain province','cordillera','car',
            'isabela','cagayan','nueva vizcaya','quirino','batanes','cagayan valley','region ii',
            'albay','sorsogon','masbate','catanduanes','camarines norte','camarines sur','bicol','region v'
        ];
        const isLuzon = (name) => {
            const s = (name || '').toLowerCase();
            if (!s.includes('philippines')) return false;
            return luzonKeywords.some(k => s.includes(k));
        };
        items = Array.isArray(items) ? items.filter(it => isLuzon(it.display_name)) : [];
        // Prioritize Luzon terms (already filtered) and ensure stable order by scoring
        const score = (name) => {
            const s = (name || '').toLowerCase();
            let v = 0;
            if (s.includes('luzon')) v += 3;
            if (s.includes('philippines')) v += 1;
            return v;
        };
        items = Array.isArray(items) ? items.sort((a,b) => score(b.display_name) - score(a.display_name)) : [];
        ul.innerHTML = '';
        if (!Array.isArray(items) || items.length === 0) { ul.style.display='none'; return; }
        items.forEach(it => {
            const li = document.createElement('li');
            li.textContent = it.display_name;
            li.addEventListener('click', () => {
                const inputEl = document.getElementById('geocode-address-input');
                if (inputEl) inputEl.value = it.display_name;
                ul.style.display = 'none';
                ul.innerHTML = '';
                // Get selected search type
                const searchType = document.querySelector('input[name="search-type"]:checked')?.value || 'pickup';
                geocodeAndPinFromAddress(it.display_name, searchType);
                closeModal('geocode-modal');
            });
            ul.appendChild(li);
        });
        ul.style.display = 'block';
    } catch (err) {
        if (err.name === 'AbortError') return;
        ul.style.display = 'none';
        ul.innerHTML = '';
    }
});

// Hide suggestions when clicking outside
document.addEventListener('click', function(e) {
    const ul = document.getElementById('geocode-suggestions');
    const input = document.getElementById('geocode-address-input');
    if (!ul || !input) return;
    if (e.target !== ul && e.target !== input && !ul.contains(e.target)) {
        ul.style.display = 'none';
    }
});

document.getElementById('close-case-creation-modal')?.addEventListener('click', function(event) {
    event.preventDefault();
    event.stopPropagation();
    closeModal('case-creation-modal');
});

document.getElementById('close-case-details-modal')?.addEventListener('click', function(event) {
    event.preventDefault();
    event.stopPropagation();
    closeCaseDetailsModal();
});

// Pin control buttons
document.getElementById('move-pin-btn')?.addEventListener('click', function(event) {
    event.preventDefault();
    event.stopPropagation();
    
    if (isMovingPin) {
        // Cancel moving mode
        isMovingPin = false;
        this.textContent = 'Move Pin';
        this.style.background = '#f59e0b';
    } else {
        // Enter moving mode
        isMovingPin = true;
        this.textContent = 'Click to Move';
        this.style.background = '#10b981';
    }
});

document.getElementById('cancel-pin-btn')?.addEventListener('click', function(event) {
    event.preventDefault();
    event.stopPropagation();
    
    // Remove the temporary pins
    if (currentPinMarker) {
        map.removeLayer(currentPinMarker);
        currentPinMarker = null;
    }
    if (currentDestinationMarker) {
        map.removeLayer(currentDestinationMarker);
        currentDestinationMarker = null;
    }
    
    // Close the modal
    closeModal('case-creation-modal');
    
    // Reset moving mode
    isMovingPin = false;
    document.getElementById('move-pin-btn').textContent = 'Move Pin';
    document.getElementById('move-pin-btn').style.background = '#f59e0b';
    
    // Clear coordinates
    window.clickedLatitude = null;
    window.clickedLongitude = null;
});

// Close modals when clicking outside
document.addEventListener('click', function(event) {
    if (event.target.classList.contains('modal-overlay')) {
        const modalId = event.target.id;
        closeModal(modalId);
    }
});

// Center map on Silang, Cavite by default (disable zoom animation to prevent marker blur/flicker)
const map = L.map('map-container', {
    zoomAnimation: false,
    markerZoomAnimation: false,
    fadeAnimation: true
}).setView([14.2154, 120.9714], 13);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; OpenStreetMap contributors'
}).addTo(map);

// Create a dedicated pane for routed trails so they render above tiles and under tooltips
try {
    map.createPane('trailsPane');
    const p = map.getPane('trailsPane');
    if (p) {
        // Place trails above markers to ensure visibility in fullscreen on some browsers
        p.style.zIndex = 700;
        p.style.pointerEvents = 'none';
    }
} catch (e) { /* pane creation is best-effort */ }

let markers = {}, trails = {}, positions = {}, destMarkers = {};
let stopMarkers = [];
let ambulanceDataMap = {}; // Store ambulance data by ID for navigation checking

// Auto-fit map bounds variables (matching dashboard view behavior)
let isUserInteracting = false; // Track if user is manually interacting with map
let autoFitBoundsEnabled = true; // Enable/disable auto-fit bounds
let lastAutoFitBounds = null; // Store last bounds to avoid unnecessary refits
let caseMarkers = {}; // Store case markers
let geofenceCircles = {}; // Store geofence circles for case destinations
const GEOFENCE_RADIUS = 100; // 100 meters radius
let currentPinMarker = null; // Current pin being placed
let isMovingPin = false; // Flag for pin movement mode
let hospitalMarkers = {}; // Store hospital markers
// ===== TIMING CONSTANTS (standardized across system) =====
const GPS_TIMING = {
    STALE_THRESHOLD_SEC: 120,        // 2 minutes = marker shows as "stale" (grayed)
    REMOVAL_THRESHOLD_MIN: 30,       // 30 minutes = marker removed from map
    POLL_INTERVAL_MS: 10000,         // 10 seconds = admin poll frequency
    STALE_UPDATE_INTERVAL_MS: 5000,  // 5 seconds = how often to refresh stale styling
    ONLINE_THRESHOLD_SEC: 120,       // 2 minutes = backend considers "online"
    INTERACTION_RESUME_DELAY_MS: 5000, // 5 seconds = delay before resuming after map interaction
    NOTIFICATION_DURATION_MS: 15000,   // 15 seconds = how long notifications stay visible
    REDEPLOYMENT_CHECK_INTERVAL_MS: 30000, // 30 seconds = check for redeployment needs
    RECENT_ACTIONS_CHECK_INTERVAL_MS: 10000 // 10 seconds = check for recent driver actions
};

// Dashboard-style driver markers and routed trails
let driverMarkers = {};
let driverLastSeen = {}; // ambulanceId -> timestamp (ms)

// Auto-fit map bounds to show all visible drivers and cases (matching dashboard view behavior)
function autoFitMapBounds() {
    if (!autoFitBoundsEnabled || isUserInteracting) return;
    
    try {
        const visibleMarkers = [];
        
        // Collect all visible driver markers
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
    
    // Always load known hospitals to ensure they're visible (matching dashboard view behavior)
    // This ensures the specific hospitals listed are always shown
    loadKnownHospitals();
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

// Base marker icon (for SILANG MDRRMO base location)
function createBaseIcon() {
  const html = `
    <div style="position: relative;">
      <div style="width:48px; height:48px; background: linear-gradient(135deg, #031273 0%, #0c2d5a 100%); color:#fff; border-radius:50%; display:flex; align-items:center; justify-content:center; box-shadow:0 4px 16px rgba(3,18,115,0.6), 0 0 0 4px rgba(255,255,255,0.95), 0 0 0 5px rgba(242,140,40,0.4); border:3px solid #fff; cursor:pointer; position: relative;">
        <i class="fas fa-landmark" style="font-size:22px; text-shadow: 0 2px 4px rgba(0,0,0,0.3);"></i>
      </div>
      <div style="position: absolute; bottom: -10px; left: 50%; transform: translateX(-50%); background: #f28c28; color: #fff; padding: 3px 8px; border-radius: 6px; font-size: 9px; font-weight: 800; white-space: nowrap; box-shadow: 0 2px 8px rgba(242,140,40,0.5); border: 2px solid #fff; letter-spacing: 0.5px;">
        SILANG MDRRMO
      </div>
    </div>
  `;
  return L.divIcon({ className: '', html, iconSize: [48, 58], iconAnchor: [24, 58] });
}

// Base location marker
let baseMarker = null;
function addBaseMarker() {
  // Base coordinates
  const baseLat = 14.240252979236983;
  const baseLng = 120.9792923735777;
  
  // Remove existing base marker if any
  if (baseMarker && map.hasLayer(baseMarker)) {
    map.removeLayer(baseMarker);
  }
  
  // Create and add base marker with very high z-index
  baseMarker = L.marker([baseLat, baseLng], {
    icon: createBaseIcon(),
    zIndexOffset: 2000, // Very high z-index to appear above everything
    interactive: true,
    keyboard: true,
    riseOnHover: true,
    bubblingMouseEvents: true
  }).addTo(map);
  
  // Ensure base marker is visible
  ensureStaticMarkersVisible();
}

// Load hospitals after map initialization
setTimeout(() => {
  try {
    loadHospitals();
    addBaseMarker(); // Add base marker
  } catch (e) {
    console.warn('Failed to load hospitals:', e);
  }
}, 500);
let driverMetaByAmbId = {}; // ambulanceId -> { label, photoUrl }
let driverPollHistory = {}; // ambulanceId -> Array<boolean> (last 5 polls: true=present, false=missing)
let driverToCaseTraces = {};

/**
 * Completely removes all visual layers (pickup marker, destination marker,
 * geofence circles, connection lines, etc.) associated with a case.
 */
function removeCaseFromMap(caseNum) {
    if (!map) return;

    const caseStr = String(caseNum);
    const caseInt = parseInt(caseStr);

    // Remove pickup marker
    if (caseMarkers && caseMarkers[caseStr]) {
        try {
            if (map.hasLayer(caseMarkers[caseStr])) {
                map.removeLayer(caseMarkers[caseStr]);
            }
        } catch (e) {
            console.error('Error removing pickup marker:', e);
        }
        delete caseMarkers[caseStr];
    }

    // Remove destination marker
    const destKey = `dest_${caseStr}`;
    if (caseMarkers && caseMarkers[destKey]) {
        try {
            if (map.hasLayer(caseMarkers[destKey])) {
                map.removeLayer(caseMarkers[destKey]);
            }
        } catch (e) {
            console.error('Error removing destination marker:', e);
        }
        delete caseMarkers[destKey];
    }

    // Remove driver-to-case trace line
    if (driverToCaseTraces && driverToCaseTraces[caseStr]) {
        try {
            if (map.hasLayer(driverToCaseTraces[caseStr])) {
                map.removeLayer(driverToCaseTraces[caseStr]);
            }
        } catch (e) {
            console.error('Error removing driver trace:', e);
        }
        delete driverToCaseTraces[caseStr];
    }

    // Remove geofence circles
    ['pickup', 'destination'].forEach(locationType => {
        const circleKey = `${caseStr}_${locationType}`;
        if (geofenceCircles && geofenceCircles[circleKey]) {
            try {
                if (map.hasLayer(geofenceCircles[circleKey])) {
                    map.removeLayer(geofenceCircles[circleKey]);
                }
            } catch (e) {
                console.error('Error removing geofence circle:', e);
            }
            delete geofenceCircles[circleKey];
        }
    });

    // Keep caches/sets in sync so the UI doesn't re-render completed cases
    if (Array.isArray(activeCasesCache)) {
        activeCasesCache = activeCasesCache.filter(c => String(c.case_num) !== caseStr);
    }
    if (currentActiveCaseNums && currentActiveCaseNums.delete) {
        currentActiveCaseNums.delete(caseInt);
    }
    if (casesNeedingRedeployment && casesNeedingRedeployment.delete) {
        casesNeedingRedeployment.delete(caseInt);
    }
    if (completedCaseNums && completedCaseNums.add) {
        completedCaseNums.add(caseInt);
    }
}
// Set of ambulance IDs that currently have an active case (Accepted/In Progress/driver_accepted)
let activeAmbulanceIds = new Set();
// Set of case numbers considered "current active" per ambulance (shown with trail)
let currentActiveCaseNums = new Set();
let completedCaseNums = new Set(); // Track cases already removed/completed to prevent redraw

// ===== Driver filter state (aligned with dashboard filter) =====
let currentFilterDriverId = 'all';
let allDriversForFilter = [];
let filteredDriversForFilter = [];
let driverFilterCurrentPage = 1;
let driverFilterItemsPerPage = 5;
let driverFilterTotalPages = 1;
let driverFilterSearchTerm = '';
let ambulanceIdToDriverId = {};
let showActiveOnly = false; // when true, show only drivers with active cases and only those cases

// ===== Visibility control for driver markers depending on selection =====
function applyDriverVisibility(){
    try {
        const selected = String(currentFilterDriverId || 'all');
        const isAll = selected === 'all';
        Object.keys(driverMarkers || {}).forEach((ambIdStr) => {
            const ambId = parseInt(ambIdStr);
            const marker = driverMarkers[ambIdStr];
            if (!marker) return;
            let shouldShow = true;
            if (showActiveOnly){
                // In active-only mode, only show drivers who have an active case
                shouldShow = activeAmbulanceIds && activeAmbulanceIds.has(ambId);
            }
            if (!isAll && selected !== 'all'){
                // Support both "amb-{id}" and raw driver id matches
                const selectedIsAmb = selected.startsWith('amb-');
                if (selectedIsAmb){
                    const selAmbId = parseInt(selected.replace('amb-',''));
                    shouldShow = (selAmbId === ambId);
                } else {
                    // Match by driver id via ambulanceIdToDriverId map
                    const drvId = ambulanceIdToDriverId ? ambulanceIdToDriverId[ambId] : null;
                    shouldShow = (drvId && String(drvId) === selected);
                }
                // If in active-only as well, both conditions must pass
                if (showActiveOnly){ shouldShow = shouldShow && activeAmbulanceIds && activeAmbulanceIds.has(ambId); }
            }
            const onMap = map.hasLayer(marker);
            if (shouldShow && !onMap) { try { marker.addTo(map); } catch(_){} }
            if (!shouldShow && onMap) { try { map.removeLayer(marker); } catch(_){} }
        });
        
        // Ensure hospitals and base marker are always visible (not affected by filter)
        ensureStaticMarkersVisible();
    } catch(_){}
}

// ===== Status bar counters =====
let totalCasesCount = 0; // cases currently shown on the map (non-completed)
let activeCasesCount = 0; // cases with driver_accepted true or In Progress / Accepted

function updateStatusCounts(){
    const totalCasesEl = document.getElementById('totalCases');
    const totalDriversEl = document.getElementById('totalDrivers');
    const activeCasesEl = document.getElementById('activeCases');
    const activeCasesSmall = document.getElementById('activeCasesSmall');

    // Counts are set by case-loading functions to mirror dashboard semantics

    if (totalCasesEl) totalCasesEl.textContent = String(totalCasesCount);
    if (totalDriversEl) totalDriversEl.textContent = String(Object.keys(driverMarkers || {}).length);
    if (activeCasesEl) activeCasesEl.textContent = String(activeCasesCount);
    if (activeCasesSmall) activeCasesSmall.textContent = String(activeCasesCount);
}

const ambulanceIcon = L.icon({
    iconUrl: 'https://cdn-icons-png.flaticon.com/512/843/843313.png',
    iconSize: [40, 40], iconAnchor: [20, 40], popupAnchor: [0, -40]
});
const destIcon = L.icon({
    iconUrl: 'https://cdn-icons-png.flaticon.com/128/684/684908.png',
    iconSize: [30, 30], iconAnchor: [15, 30], popupAnchor: [0, -30]
});
// Case marker icon - pin icon only (matching dashboard view design)
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

const caseIcon = L.icon({
    iconUrl: 'https://cdn-icons-png.flaticon.com/512/684/684908.png',
    iconSize: [30, 30], iconAnchor: [15, 30], popupAnchor: [0, -30]
});
const pinIcon = L.icon({
    iconUrl: 'https://cdn-icons-png.flaticon.com/512/684/684908.png',
    iconSize: [25, 25], iconAnchor: [12, 25], popupAnchor: [0, -25]
});

const destinationIcon = L.icon({
    iconUrl: 'data:image/svg+xml;base64,' + btoa('<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#f59e0b"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>'),
    iconSize: [25, 25], iconAnchor: [12, 25], popupAnchor: [0, -25]
});


// Normalize address context to Philippines, without constraining to Silang
function filterAddressForSilangCavite(address) {
    const lowerAddress = (address || '').toLowerCase();
    if (lowerAddress.includes('philippines')) return address;
    return `${address}, Philippines`;
}

// ===== Dashboard-style helpers for driver icon and routed trails =====
function formatAge(seconds){
    if (!Number.isFinite(seconds) || seconds < 0) return '';
    if (seconds < 60) return `${Math.max(1, Math.floor(seconds))}s`;
    const m = Math.floor(seconds / 60);
    const s = Math.floor(seconds % 60);
    if (m < 60) return s ? `${m}m ${s}s` : `${m}m`;
    const h = Math.floor(m / 60);
    const rm = m % 60;
    return rm ? `${h}h ${rm}m` : `${h}h`;
}
function getFirstName(fullName) {
    if (!fullName || typeof fullName !== 'string') return '';
    const parts = fullName.trim().split(/\s+/);
    return parts[0] || fullName;
}

// Calculate connection health from poll history
function getConnectionHealth(ambId) {
    const history = driverPollHistory[ambId] || [];
    if (history.length === 0) return 'unknown'; // No data yet
    
    const recentPolls = history.slice(-5); // Last 5 polls
    const successCount = recentPolls.filter(p => p === true).length;
    const successRate = successCount / recentPolls.length;
    
    if (successRate >= 0.8) return 'good';      // 80%+ success = green
    if (successRate >= 0.5) return 'warning';  // 50-79% = yellow
    return 'poor';                               // <50% = red
}

// Open driver details modal when clicking driver marker
function openDriverDetailsModal(ambId, amb, ageSec) {
    const minutesAgo = amb.last_seen_minutes_ago !== undefined && amb.last_seen_minutes_ago !== null 
        ? amb.last_seen_minutes_ago 
        : Math.floor(ageSec / 60);
    const isStale = ageSec > GPS_TIMING.STALE_THRESHOLD_SEC;
    const statusText = isStale ? `Last seen ${minutesAgo} min ago` : 'Online';
    const statusColor = isStale ? '#ef4444' : '#10b981';
    const driverName = amb.driver_name || amb.name || `Driver ${ambId}`;
    const photoUrl = amb.driver_photo || amb.driverPhoto || amb.photo || null;
    
    // Create or get modal
    let modal = document.getElementById('driver-details-modal');
    if (!modal) {
        modal = document.createElement('div');
        modal.id = 'driver-details-modal';
        modal.style.cssText = 'display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.4); z-index: 10000; align-items: center; justify-content: center; backdrop-filter: blur(2px);';
        modal.innerHTML = `
            <div id="driver-modal-container" style="background: #ffffff; width: 320px; border-radius: 12px; box-shadow: 0 8px 32px rgba(0,0,0,0.12), 0 2px 8px rgba(0,0,0,0.08); position: relative; overflow: hidden;">
                <div id="driver-modal-header" style="background: #1e40af; padding: 10px 16px; display: flex; justify-content: space-between; align-items: center;">
                    <span style="color: white; font-size: 12px; font-weight: 600; letter-spacing: 0.5px; text-transform: uppercase;">Driver Details</span>
                    <span onclick="closeDriverDetailsModal()" style="color: rgba(255,255,255,0.85); font-size: 18px; cursor: pointer; line-height: 1; width: 18px; height: 18px; display: flex; align-items: center; justify-content: center; transition: all 0.2s; border-radius: 4px;" onmouseover="this.style.background='rgba(255,255,255,0.15)'; this.style.color='white'" onmouseout="this.style.background='transparent'; this.style.color='rgba(255,255,255,0.85)'">&times;</span>
                </div>
                <div id="driver-details-body" style="padding: 18px; text-align: center;">
                    <!-- Content will be inserted here -->
                </div>
            </div>
        `;
        document.body.appendChild(modal);
        
        // Close modal when clicking outside
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                closeDriverDetailsModal();
            }
        });
    }
    
    // Update modal content
    const body = document.getElementById('driver-details-body');
    body.innerHTML = `
        <div style="margin-bottom: 16px;">
            ${photoUrl ? `
                <img src="${photoUrl}" alt="${driverName}" style="width: 64px; height: 64px; border-radius: 50%; object-fit: cover; border: 2px solid ${statusColor}; margin: 0 auto 10px; display: block;">
            ` : `
                <div style="width: 64px; height: 64px; border-radius: 50%; background: linear-gradient(135deg, #bfdbfe, #93c5fd); margin: 0 auto 10px; display: flex; flex-direction: column; align-items: center; justify-content: center; border: 2px solid ${statusColor}; position: relative; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                    <i class="fas fa-user" style="font-size: 26px; color: white; margin-top: -6px;"></i>
                    <div style="font-size: 7px; font-weight: 700; color: white; letter-spacing: 0.5px; position: absolute; bottom: 5px;">DR</div>
                </div>
            `}
            <div style="margin-bottom: 4px;">
                <div style="font-size: 16px; font-weight: 600; color: #111827; margin: 0;">${driverName}</div>
            </div>
            <div style="font-size: 12px; color: ${statusColor}; font-weight: 500;">
                ${statusText}
            </div>
        </div>
        <div style="border-top: 1px solid #f1f5f9; padding-top: 14px; margin-top: 14px;">
            <button onclick="sendGPSResendRequestFromModal(${ambId}); closeDriverDetailsModal();" 
                style="width: 100%; padding: 10px 16px; background: #3b82f6; color: white; border: none; border-radius: 6px; font-weight: 500; cursor: pointer; font-size: 12px; transition: all 0.15s; display: flex; align-items: center; justify-content: center; gap: 6px; box-shadow: 0 1px 3px rgba(59, 130, 246, 0.3);"
                onmouseover="this.style.background='#2563eb'; this.style.boxShadow='0 2px 6px rgba(59, 130, 246, 0.4)';" 
                onmouseout="this.style.background='#3b82f6'; this.style.boxShadow='0 1px 3px rgba(59, 130, 246, 0.3)';">
                <i class="fas fa-paper-plane" style="font-size: 11px;"></i>
                Request GPS Resend
            </button>
        </div>
    `;
    
    modal.style.display = 'flex';
}

function closeDriverDetailsModal() {
    const modal = document.getElementById('driver-details-modal');
    if (modal) {
        modal.style.display = 'none';
    }
}

function sendGPSResendRequestFromModal(ambulanceId) {
    sendGPSResendRequest(ambulanceId);
}

function createDriverIcon(label, photoUrl, ageSec = 0, ambId = null) {
    const isStale = Number.isFinite(ageSec) && ageSec > GPS_TIMING.STALE_THRESHOLD_SEC;
    const staleStyle = isStale
        ? 'filter: grayscale(1); opacity: 0.7;'
        : '';
    
    // Always use CSS-based driver icon (no photo)
    // Create a simplified driver icon using Font Awesome and CSS
    const driverIcon = `
      <div style="display:flex; align-items:center; justify-content:center; width:44px; height:44px; border-radius:9999px; background:linear-gradient(135deg,#031273,#1e40af); color:#fff; box-shadow:0 4px 12px rgba(0,0,0,0.3); border:3px solid #fff; ${staleStyle}; position:relative;">
        <i class="fas fa-user" style="font-size:20px; text-shadow: 0 1px 3px rgba(0,0,0,0.3);"></i>
      </div>
    `;
    
    const safeLabel = label || '';
    const lastSeenHtml = isStale
        ? `<div style="margin-top:2px; font-size:10px; color:#9ca3af; font-weight:600;">Last seen ${formatAge(ageSec)} ago</div>`
        : '';
    
    // Connection health badge
    let healthBadge = '';
    if (ambId) {
        const health = getConnectionHealth(ambId);
        let healthColor, healthText;
        if (health === 'good') {
            healthColor = '#10b981'; // green
            healthText = '●';
        } else if (health === 'warning') {
            healthColor = '#f59e0b'; // yellow/orange
            healthText = '●';
        } else if (health === 'poor') {
            healthColor = '#ef4444'; // red
            healthText = '●';
        } else {
            healthColor = '#6b7280'; // gray (unknown)
            healthText = '○';
        }
        healthBadge = `<div style="position:absolute; top:-2px; right:-2px; width:12px; height:12px; background:${healthColor}; border:2px solid #fff; border-radius:50%; box-shadow:0 2px 4px rgba(0,0,0,0.3);" title="${health === 'good' ? 'Good connection' : health === 'warning' ? 'Intermittent connection' : health === 'poor' ? 'Poor connection' : 'Connection status unknown'}"></div>`;
    }
    
    const html = `
      <div class="driver-marker" style="display:flex; flex-direction:column; align-items:center; position:relative;">
        ${driverIcon}
        ${healthBadge}
        <div class="driver-badge" style="margin-top:4px; background:#111827; color:#fff; padding:2px 6px; border-radius:6px; font-size:11px; font-weight:800; white-space:nowrap; box-shadow:0 2px 6px rgba(0,0,0,0.2);">${safeLabel}</div>
        ${lastSeenHtml}
      </div>
    `;
    return L.divIcon({ className: '', html, iconSize: [1,1], iconAnchor: [22, 52] });
}

// Routing helper using OSRM to follow roads
async function drawRoutedLine(start, end, style){
    try {
        const coords = `${start[1]},${start[0]};${end[1]},${end[0]}`;
        const url = `https://router.project-osrm.org/route/v1/driving/${coords}?overview=full&geometries=geojson`;
        const res = await fetch(url, { headers: { 'Accept': 'application/json' } });
        if (!res.ok) throw new Error('routing_failed');
        const data = await res.json();
        const points = (data && data.routes && data.routes[0] && data.routes[0].geometry && data.routes[0].geometry.coordinates) || [];
        if (!points.length) {
            const pl = L.polyline([start, end], Object.assign({ pane: 'trailsPane' }, style)).addTo(map);
            try { pl.bringToFront(); } catch(_){}
            return pl;
        }
        const latlngs = points.map(([lng, lat]) => [lat, lng]);
        const pl = L.polyline(latlngs, Object.assign({ lineCap: 'round', lineJoin: 'round', pane: 'trailsPane' }, style)).addTo(map);
        try { pl.bringToFront(); } catch(_){}
        return pl;
    } catch (e) {
        const pl = L.polyline([start, end], Object.assign({ lineCap: 'round', lineJoin: 'round', pane: 'trailsPane' }, style)).addTo(map);
        try { pl.bringToFront(); } catch(_){}
        return pl;
    }
}

// Filter to Luzon-only results and order by relevance
function filterResultsForPhilippines(results) {
    if (!Array.isArray(results)) return [];
    const luzonKeywords = [
        'luzon','ncr','metro manila','manila','quezon city','makati','pasig','taguig','mandaluyong','muntinlupa','parañaque','pasay','caloocan','navotas','valenzuela','malabon','marikina','san juan',
        'cavite','laguna','batangas','rizal','quezon province','lucena','calabarzon','region iv-a',
        'bulacan','pampanga','bataan','zambales','tarlac','nueva ecija','aurora','central luzon','region iii',
        'pangasinan','la union','ilocos norte','ilocos sur','ilocos region','region i',
        'benguet','abra','apayao','ifugao','kalinga','mountain province','cordillera','car',
        'isabela','cagayan','nueva vizcaya','quirino','batanes','cagayan valley','region ii',
        'albay','sorsogon','masbate','catanduanes','camarines norte','camarines sur','bicol','region v'
    ];
    const isLuzon = (name) => {
        const s = (name || '').toLowerCase();
        if (!s.includes('philippines')) return false;
        return luzonKeywords.some(k => s.includes(k));
    };
    const score = (name) => {
        const s = (name || '').toLowerCase();
        let v = 0;
        if (s.includes('luzon')) v += 3;
        if (s.includes('philippines')) v += 1;
        return v;
    };
    return results.filter(r => isLuzon(r.display_name)).sort((a,b) => score(b.display_name) - score(a.display_name));
}

// Connection line between pickup and destination
let connectionLine = null;

function updateConnectionLine() {
    // Remove existing line
    if (connectionLine) {
        map.removeLayer(connectionLine);
        connectionLine = null;
    }
    
    // Add new line if both pins exist
    if (window.clickedLatitude && window.clickedLongitude && 
        window.destinationLatitude && window.destinationLongitude) {
        
        const pickup = [window.clickedLatitude, window.clickedLongitude];
        const destination = [window.destinationLatitude, window.destinationLongitude];
        
        connectionLine = L.polyline([pickup, destination], {
            color: '#3b82f6',
            weight: 3,
            opacity: 0.7,
            dashArray: '10, 10'
        }).addTo(map);
    }
}

// Show connection line for existing cases
function showCaseConnectionLine(caseData) {
    // Remove existing line
    if (connectionLine) {
        map.removeLayer(connectionLine);
        connectionLine = null;
    }
    
    // Check if case has both pickup and destination coordinates
    if (caseData.latitude && caseData.longitude && 
        caseData.to_go_to_latitude && caseData.to_go_to_longitude) {
        
        const pickup = [parseFloat(caseData.latitude), parseFloat(caseData.longitude)];
        const destination = [parseFloat(caseData.to_go_to_latitude), parseFloat(caseData.to_go_to_longitude)];
        
        // Create connection line with different color for existing cases
        connectionLine = L.polyline([pickup, destination], {
            color: caseData.status === 'Completed' ? '#10b981' : '#f59e0b',
            weight: 3,
            opacity: 0.8,
            dashArray: '10, 10'
        }).addTo(map);
        
        // Also add destination marker if it doesn't exist
        const destMarkerId = `dest_${caseData.case_num}`;
        if (!caseMarkers[destMarkerId]) {
            const destMarker = L.marker(destination, { 
                icon: destinationIcon,
                draggable: false
            }).addTo(map);
            
            destMarker.bindTooltip(`Destination #${caseData.case_num}`, {
                permanent: false,
                direction: 'bottom',
                offset: [0, 20],
                className: 'case-label'
            });
            
            caseMarkers[destMarkerId] = destMarker;
        }
        
        // Fit map to show both points
        const group = new L.featureGroup([
            L.marker(pickup),
            L.marker(destination)
        ]);
        map.fitBounds(group.getBounds().pad(0.1));
    }
}

// Reverse geocode helper to fill address from coordinates
async function setAddressFromLatLng(latitude, longitude, type = 'pickup') {
    try {
        const addressField = type === 'pickup' ? 
            document.getElementById('case-address') : 
            document.getElementById('case-destination');
            
        if (addressField) {
            addressField.value = 'Fetching address...';
        }
        const url = `https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${encodeURIComponent(latitude)}&lon=${encodeURIComponent(longitude)}`;
        const res = await fetch(url, { headers: { 'Accept': 'application/json' } });
        if (!res.ok) throw new Error('Reverse geocode failed');
        const data = await res.json();
        const display = data && (data.display_name || (data.address && Object.values(data.address).join(', '))) || '';
        if (addressField && display) {
            addressField.value = display;
        } else if (addressField) {
            addressField.value = '';
        }
    } catch (e) {
        const addressField = type === 'pickup' ? 
            document.getElementById('case-address') : 
            document.getElementById('case-destination');
        if (addressField) addressField.value = '';
        console.error('Reverse geocoding error:', e);
    }
}

// Geocode an address, drop a pin, and open the case creation form
async function geocodeAndPinFromAddress(address, type = 'pickup') {
    if (!address) return;
    
    // Filter for Silang, Cavite, Philippines
    const filteredAddress = filterAddressForSilangCavite(address);
    
    try {
        const url = `https://nominatim.openstreetmap.org/search?format=jsonv2&q=${encodeURIComponent(filteredAddress)}`;
        const res = await fetch(url, { headers: { 'Accept': 'application/json' } });
        if (!res.ok) throw new Error('Geocoding failed');
        const results = await res.json();
        
        // Filter results to prioritize Philippines/Cavite
        const filteredResults = filterResultsForPhilippines(results);
        
        if (!Array.isArray(filteredResults) || filteredResults.length === 0) {
            showInlineNotice('Address not found. Please refine the address.', {
                title: 'Address Not Found',
                type: 'warning'
            });
            return;
        }
        
        const best = filteredResults[0];
        const lat = parseFloat(best.lat), lng = parseFloat(best.lon);
        if (Number.isNaN(lat) || Number.isNaN(lng)) throw new Error('Invalid geocode result');

        if (type === 'pickup') {
            // Remove existing pickup pin if any
            if (currentPinMarker) {
                map.removeLayer(currentPinMarker);
            }

            // Set globals for pickup
            window.clickedLatitude = lat;
            window.clickedLongitude = lng;

            // Add pickup marker
            currentPinMarker = L.marker([lat, lng], { icon: pinIcon, draggable: true }).addTo(map);
            
            // Update coords label if present
            const coordEl = document.getElementById('pin-coordinates');
            if (coordEl) coordEl.textContent = `${lat.toFixed(6)}, ${lng.toFixed(6)}`;

            // When dragging pickup pin
            currentPinMarker.on('dragend', function(e) {
                const newPos = e.target.getLatLng();
                window.clickedLatitude = newPos.lat;
                window.clickedLongitude = newPos.lng;
                if (coordEl) coordEl.textContent = `${newPos.lat.toFixed(6)}, ${newPos.lng.toFixed(6)}`;
                setTimeout(() => setAddressFromLatLng(window.clickedLatitude, window.clickedLongitude, 'pickup'), 0);
                updateConnectionLine();
            });

            // Open case creation and fill pickup address
            openModal('case-creation-modal');
            const addrField = document.getElementById('case-address');
            if (addrField) addrField.value = best.display_name || address;
            
        } else if (type === 'destination') {
            // Remove existing destination pin if any
            if (currentDestinationMarker) {
                map.removeLayer(currentDestinationMarker);
            }

            // Set globals for destination
            window.destinationLatitude = lat;
            window.destinationLongitude = lng;

            // Add destination marker
            currentDestinationMarker = L.marker([lat, lng], { icon: destinationIcon, draggable: true }).addTo(map);
            
            // When dragging destination pin
            currentDestinationMarker.on('dragend', function(e) {
                const newPos = e.target.getLatLng();
                window.destinationLatitude = newPos.lat;
                window.destinationLongitude = newPos.lng;
                setTimeout(() => setAddressFromLatLng(window.destinationLatitude, window.destinationLongitude, 'destination'), 0);
                updateConnectionLine();
            });

            // Fill destination address
            const destField = document.getElementById('case-destination');
            if (destField) destField.value = best.display_name || address;
        }

        // Update the connection line between pickup and destination
        updateConnectionLine();
        
        // Fit map to show both pins if both exist
        if (currentPinMarker && currentDestinationMarker) {
            const group = new L.featureGroup([currentPinMarker, currentDestinationMarker]);
            map.fitBounds(group.getBounds().pad(0.1));
        } else {
            map.setView([lat, lng], Math.max(map.getZoom(), 16));
        }

    } catch (e) {
        console.error('Geocode error:', e);
        showInlineNotice('Error finding address. Please try again.', {
            title: 'Geocode Failed',
            type: 'danger'
        });
    }
}


function fetchAmbulanceData() {
    fetch("{{ route('admin.gps.data') }}")
        .then(res => {
            if (!res.ok) {
                throw new Error(`HTTP error! status: ${res.status}`);
            }
            return res.json();
        })
        .then(data => {
            console.log('GPS Data received:', data);
            
            // Ensure data is an array
            if (!Array.isArray(data)) {
                console.error('GPS data is not an array:', typeof data, data);
                return;
            }
            
            // Build driver list for filter
            try {
                // Map ambulance id to driver id for later case filtering
                ambulanceIdToDriverId = {};
                data.forEach(a => { if (a && a.id && (a.driver_id || a.driverId || a.driver_id_fk)) ambulanceIdToDriverId[a.id] = a.driver_id || a.driverId || a.driver_id_fk; });
            setTimeout(positionTopControls, 10);
        } catch (e) { /* no-op */ }
            
            // Removed deprecated status table rendering
            if (!window.displayedNotifications) window.displayedNotifications = {};

            console.log(`Processing ${data.length} drivers...`);
            
            const visibleDrivers = [];
            const currentPollIds = new Set(); // Track which drivers are in current poll
            
            // Update poll history: mark all existing drivers as "missing" first, then we'll mark present ones as true
            Object.keys(driverPollHistory).forEach(ambId => {
                const history = driverPollHistory[ambId] || [];
                history.push(false); // Mark as missing
                if (history.length > 5) history.shift(); // Keep only last 5 polls
                driverPollHistory[ambId] = history;
            });
            
            data.forEach((amb, index) => {
                console.log(`Processing driver ${index + 1}: ${amb.driver_name} (${amb.name})`);
                const latLng = [amb.latitude, amb.longitude];
                console.log(`Location: ${latLng[0]}, ${latLng[1]}`);
                
                currentPollIds.add(amb.id);
                
                // Store ambulance data for navigation checking
                ambulanceDataMap[amb.id] = amb;
                
                // Update poll history: mark this driver as present
                if (!driverPollHistory[amb.id]) {
                    // New driver: initialize with good history (assume they've been present)
                    driverPollHistory[amb.id] = [true, true, true]; // Start with 3 successful polls
                }
                const history = driverPollHistory[amb.id];
                if (history.length > 0 && history[history.length - 1] === false) {
                    // Replace the "missing" mark we just added with "present"
                    history[history.length - 1] = true;
                } else {
                    history.push(true);
                    if (history.length > 5) history.shift(); // Keep only last 5 polls
                }
                driverPollHistory[amb.id] = history;
                
                // Do not hide drivers based on filter; show all drivers always.

                if (amb.latitude && amb.longitude) {
                    // Track drivers visible on the map (label: driver name, value: ambulance id)
                    const labelName = (amb.driver_name || amb.driver_first_name || amb.name || `Ambulance ${amb.id}`).toString().trim();
                    visibleDrivers.push({ id: amb.id, label: labelName });

                    // Dashboard-style driver icon with label below
                    const computedLabel = (
                        amb.driver_name || amb.driver_first_name || `Driver ${amb.id}`
                    );
                    const photoUrl = (amb.driver_photo || amb.driverPhoto || amb.photo || null);
                    // Track meta and last seen for stale styling
                    driverMetaByAmbId[amb.id] = { label: computedLabel, photoUrl };
                    
                    // Use backend last_seen_minutes_ago if available, otherwise use current time
                    if (amb.last_seen_minutes_ago !== undefined && amb.last_seen_minutes_ago !== null) {
                        // Backend provides minutes ago, convert to seconds for icon display
                        const ageSec = amb.last_seen_minutes_ago * 60;
                        driverLastSeen[amb.id] = Date.now() - (ageSec * 1000);
                    } else {
                        // Fallback to current time if backend doesn't provide it
                        driverLastSeen[amb.id] = Date.now();
                    }
                    
                    if (driverMarkers[amb.id]) {
                        driverMarkers[amb.id].setLatLng(latLng);
                        // Calculate age for icon display
                        const ageSec = amb.last_seen_minutes_ago !== undefined && amb.last_seen_minutes_ago !== null 
                            ? amb.last_seen_minutes_ago * 60 
                            : 0;
                        driverMarkers[amb.id].setIcon(createDriverIcon(computedLabel, photoUrl, ageSec, amb.id));
                        // Ensure click handler is attached (in case marker was created without it)
                        if (!driverMarkers[amb.id]._gpsClickHandler) {
                            driverMarkers[amb.id].on('click', function() {
                                openDriverDetailsModal(amb.id, amb, ageSec);
                            });
                            driverMarkers[amb.id]._gpsClickHandler = true;
                        }
                    } else {
                        const ageSec = amb.last_seen_minutes_ago !== undefined && amb.last_seen_minutes_ago !== null 
                            ? amb.last_seen_minutes_ago * 60 
                            : 0;
                        driverMarkers[amb.id] = L.marker(latLng, { icon: createDriverIcon(computedLabel, photoUrl, ageSec, amb.id), interactive: true }).addTo(map);
                        // Add click handler to open driver details modal
                        driverMarkers[amb.id].on('click', function() {
                            openDriverDetailsModal(amb.id, amb, ageSec);
                        });
                    }
                }

                if (amb.destination_latitude && amb.destination_longitude) {
                    const destLatLng = [amb.destination_latitude, amb.destination_longitude];
                    destMarkers[amb.id]?.setLatLng(destLatLng) ||
                    (destMarkers[amb.id] = L.marker(destLatLng, { icon: destIcon })
                        .addTo(map).bindPopup(`📍 Destination for ${amb.name}`));
                } else if (destMarkers[amb.id]) {
                    map.removeLayer(destMarkers[amb.id]);
                    delete destMarkers[amb.id];
                }

                // 🔔 Arrival Notification
                if (amb.status === 'Available' && !amb.destination_latitude && !amb.destination_longitude) {
                    const notifKey = `arrived-${amb.id}-${amb.updated_at}`;
                    if (!window.displayedNotifications[notifKey]) {
                        const notif = document.createElement('li');
                        notif.innerHTML = `<strong>[${new Date(amb.updated_at).toLocaleTimeString()}]</strong> ✅ Ambulance <strong>${amb.name}</strong> has arrived.`;
                        document.getElementById('notifications')?.prepend(notif);
                        window.displayedNotifications[notifKey] = true;
                        setTimeout(() => notif.remove(), GPS_TIMING.NOTIFICATION_DURATION_MS);
                    }
                }

                // Removed table row creation as status modal was deleted
            });

            // Cleanup: NEVER remove markers just because they're missing from a poll
            // Only remove markers if they truly haven't been seen for 30+ minutes AND are missing from poll
            // This prevents drivers from disappearing on brief connection losses (even 1 second)
            Object.keys(driverMarkers || {}).forEach((ambIdStr) => {
                const ambId = parseInt(ambIdStr);
                if (!currentPollIds.has(ambId)) {
                    // Driver not in current poll - DO NOT REMOVE immediately
                    // This could be a brief connection loss (1 second, 5 seconds, etc.)
                    // Note: Poll history was already updated above (marked as missing)
                    const last = driverLastSeen[ambId];
                    if (last) {
                        const secondsSinceLastSeen = Math.floor((Date.now() - last) / 1000);
                        const minutesSinceLastSeen = Math.floor(secondsSinceLastSeen / 60);
                        
                        // STRICT: Only remove if they haven't been seen for REMOVAL_THRESHOLD
                        // This means even if they miss many polls, they still stay visible
                        // Drivers get REMOVAL_THRESHOLD_MIN FULL MINUTES to reconnect before removal
                        if (minutesSinceLastSeen >= GPS_TIMING.REMOVAL_THRESHOLD_MIN) {
                            try {
                                map.removeLayer(driverMarkers[ambId]);
                                delete driverMarkers[ambId];
                                delete driverMetaByAmbId[ambId];
                                delete driverLastSeen[ambId];
                                delete driverPollHistory[ambId]; // Clean up poll history too
                                delete ambulanceDataMap[ambId]; // Clean up ambulance data
                                console.log(`✓ Removed driver ${ambId} (not seen for ${minutesSinceLastSeen} minutes)`);
                            } catch(e) {
                                console.warn(`Failed to remove marker for driver ${ambId}:`, e);
                            }
                        } else {
                            // PRESERVE the marker - even if missing from current poll
                            // Keep it visible at last known position
                            // Update icon to reflect connection health (yellow/red badge)
                            // updateStaleMarkers will also handle this, but update now for immediate feedback
                            const meta = driverMetaByAmbId[ambId] || { label: `Driver ${ambId}`, photoUrl: null };
                            const ageSec = secondsSinceLastSeen;
                            try {
                                driverMarkers[ambId].setIcon(createDriverIcon(meta.label, meta.photoUrl, ageSec, ambId));
                            } catch(e) { /* no-op: marker update failed */ }
                            if (secondsSinceLastSeen > 30) {
                                console.log(`✓ Preserving driver ${ambId} marker (last seen ${secondsSinceLastSeen}s / ${minutesSinceLastSeen}m ago, will remove after 30min)`);
                            }
                        }
                    } else {
                        // No last_seen data yet - PRESERVE the marker anyway
                        // It will get last_seen data on next successful poll
                        // Don't remove just because we don't have timing data yet
                        console.log(`✓ Preserving driver ${ambId} marker (waiting for last_seen data)`);
                    }
                }
                // If driver IS in current poll, we already updated them above - no cleanup needed
            });

            // Update active drivers in status bar
            try { 
                if (Array.isArray(data)) { 
                    updateStatusCounts(); 
                } 
            } catch(e) { 
                console.warn('Failed to update status counts:', e); 
            }
            
            // Auto-fit bounds to show all visible drivers after data update
            setTimeout(() => autoFitMapBounds(), 300);

            // Build the filter list from ALL drivers returned by the API (not only visible on map)
            try {
                const seen = new Set();
                allDriversForFilter = (Array.isArray(data) ? data : [])
                    .filter(a => a && a.id)
                    .map(a => {
                        const ambId = String(a.id);
                        const driverId = (a.driver_id || a.driverId || a.driver_id_fk || a.driverID || null);
                        // Always provide a fallback label so searching numbers works
                        const label = driverId ? `Driver ${driverId}` : `Driver ${ambId}`;
                        const id = `amb-${ambId}`; // use ambulance-scoped id consistently
                        return { id, label };
                    })
                    .filter(d => d && d.id && d.label)
                    .filter(d => { const k = String(d.id); if (seen.has(k)) return false; seen.add(k); return true; })
                    .sort((a,b) => a.label.localeCompare(b.label));
                if (typeof window.__driverFilterRebuild === 'function') window.__driverFilterRebuild();
            } catch (e) { /* no-op */ }
            
            console.log(`GPS update complete. Total driver markers on map: ${Object.keys(driverMarkers).length}`);
            console.log('Active driver markers:', Object.keys(driverMarkers).map(id => `${id}: ${driverMarkers[id].getLatLng()}`));
            
            // Log summary of online vs offline drivers
            if (data.length === 0) {
                console.log('⚠️ No drivers are currently online and sharing location');
                console.log('Drivers need to:');
                console.log('1. Be logged in as a driver');
                console.log('2. Be on the send-location page');
                console.log('3. Have location permissions enabled');
                console.log('4. Have clicked "Start GPS Tracking"');
                console.log('5. Have been active within the last 10 minutes');
            } else {
                console.log(`✅ ${data.length} drivers are online and sharing location`);
            }
        })
        .catch(err => {
            console.error("❌ Failed to fetch ambulance data:", err);
            console.error("Error details:", {
                message: err.message,
                stack: err.stack,
                url: "{{ route('admin.gps.data') }}"
            });
            
            // IMPORTANT: Don't remove markers on poll failure
            // Network errors shouldn't cause drivers to disappear
            // Markers will persist based on their last_seen timestamps
            console.log("⚠️ Poll failed - preserving existing markers (won't remove drivers due to network error)");
            
            // Show user-friendly error message (but don't affect marker persistence)
            if (window.displayedNotifications && !window.displayedNotifications['gps-error']) {
                window.displayedNotifications['gps-error'] = true;
                console.log("⚠️ GPS data fetch failed. This might be because:");
                console.log("1. Network connection issue");
                console.log("2. Database connection issues");
                console.log("3. Server-side error");
                console.log("Note: Existing driver markers are preserved during errors.");
            }
            
            // DO NOT update lastPollOkAt on error - this preserves markers during failures
            // Only update lastPollOkAt on successful polls
        });
}

// Periodically refresh icon styling to reflect "last seen" status and connection health
function updateStaleMarkers(){
    try {
        // If admin hasn't had a successful poll recently, avoid aging everyone to stale
        // Allow up to 2x poll interval before pausing stale updates (prevents false stale during network hiccups)
        if (!lastPollOkAt || (Date.now() - lastPollOkAt) > (GPS_TIMING.POLL_INTERVAL_MS * 2)) return;
        Object.keys(driverMarkers || {}).forEach((ambIdStr) => {
            const ambId = parseInt(ambIdStr);
            const marker = driverMarkers[ambId];
            if (!marker) return;
            const meta = driverMetaByAmbId[ambId] || { label: `Driver ${ambId}`, photoUrl: null };
            const last = driverLastSeen[ambId];
            const ageSec = last ? Math.floor((Date.now() - last) / 1000) : Infinity;
            try { marker.setIcon(createDriverIcon(meta.label, meta.photoUrl, ageSec, ambId)); } catch(_){ }
        });
    } catch(_){ }
}

function clearDestination(id) {
    fetch(`/admin/ambulances/${id}/clear-destination`, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') }
    })
    .then(res => res.json())
    .then(() => fetchAmbulanceData())
    .catch(err => console.error("❌ Clear failed:", err));
}

map.on('click', async function (e) {
    // Block interactions while driver filter is open
    try { if (typeof isDriverFilterOpen !== 'undefined' && isDriverFilterOpen) return; } catch(_){ }
    // Check if the click is on the GPS panel elements
    const panel = document.getElementById('gps-control-panel');
    const toggle = document.getElementById('gps-panel-toggle');
    
    // If panel is active, don't process map clicks
    if (panel && panel.classList.contains('active')) {
        return;
    }
    
    // If we're in pin moving mode, update the pin position
    if (isMovingPin && currentPinMarker) {
        currentPinMarker.setLatLng(e.latlng);
        window.clickedLatitude = e.latlng.lat;
        window.clickedLongitude = e.latlng.lng;
        document.getElementById('pin-coordinates').textContent = 
            `${e.latlng.lat.toFixed(6)}, ${e.latlng.lng.toFixed(6)}`;
        // Update address on move
        setTimeout(() => setAddressFromLatLng(window.clickedLatitude, window.clickedLongitude, 'pickup'), 0);
        updateConnectionLine();
        isMovingPin = false;
        document.getElementById('move-pin-btn').textContent = 'Move Pin';
        return;
    }
    
    // Check if we're setting destination (Ctrl+Click for destination)
    if (e.originalEvent.ctrlKey || e.originalEvent.metaKey) {
        // Remove existing destination pin
        if (currentDestinationMarker) {
            map.removeLayer(currentDestinationMarker);
        }
        
        // Store destination coordinates
        window.destinationLatitude = e.latlng.lat;
        window.destinationLongitude = e.latlng.lng;
        
        // Place destination marker
        currentDestinationMarker = L.marker([e.latlng.lat, e.latlng.lng], { 
            icon: destinationIcon,
            draggable: true
        }).addTo(map);
        
        // Make destination pin draggable
        currentDestinationMarker.on('dragend', function(e) {
            const newPos = e.target.getLatLng();
            window.destinationLatitude = newPos.lat;
            window.destinationLongitude = newPos.lng;
            setTimeout(() => setAddressFromLatLng(window.destinationLatitude, window.destinationLongitude, 'destination'), 0);
            updateConnectionLine();
        });
        
        // Auto-fill destination address and update connection line
        setTimeout(() => setAddressFromLatLng(window.destinationLatitude, window.destinationLongitude, 'destination'), 0);
        updateConnectionLine();
        
        // Open the case creation modal if not already open
        const modal = document.getElementById('case-creation-modal');
        if (!modal || modal.style.display === 'none') {
            openModal('case-creation-modal');
        }
        return;
    }
    
    // Remove any existing pickup pin
    if (currentPinMarker) {
        map.removeLayer(currentPinMarker);
    }
    
    // Store the clicked coordinates for pickup
    window.clickedLatitude = e.latlng.lat;
    window.clickedLongitude = e.latlng.lng;
    
    // Place a temporary pickup pin marker
    currentPinMarker = L.marker([e.latlng.lat, e.latlng.lng], { 
        icon: pinIcon,
        draggable: true
    }).addTo(map);
    
    // Make pin draggable
    currentPinMarker.on('dragend', function(e) {
        const newPos = e.target.getLatLng();
        window.clickedLatitude = newPos.lat;
        window.clickedLongitude = newPos.lng;
        document.getElementById('pin-coordinates').textContent = 
            `${newPos.lat.toFixed(6)}, ${newPos.lng.toFixed(6)}`;
        // Auto-fill address after drag
        setTimeout(() => setAddressFromLatLng(window.clickedLatitude, window.clickedLongitude, 'pickup'), 0);
        updateConnectionLine();
    });
    
    // Open the case creation modal
    openModal('case-creation-modal');
    // Auto-fill address from coordinates
    setTimeout(() => setAddressFromLatLng(window.clickedLatitude, window.clickedLongitude, 'pickup'), 0);
    updateConnectionLine();
});

async function resolveDriverId(ambulanceId) {
    try {
        const res = await fetch(`/admin/ambulances/${ambulanceId}/driver`);
        const j = await res.json();
        return j.driver_id;
    } catch (e) { return null; }
}

async function renderStopsForDriver(driverId) {
    try {
        const res = await fetch(`/admin/assignments/${driverId}`);
        const assignment = await res.json();

        stopMarkers.forEach(m => map.removeLayer(m));
        stopMarkers = [];

        const ul = document.getElementById('stops-list');
        ul.innerHTML = '';

        if (!assignment || !assignment.stops?.length) return;

        // Notify on completed stops (once)
        window.seenCompletedStops = window.seenCompletedStops || {};
        assignment.stops
            .filter(s => s.status === 'completed')
            .forEach(s => {
                if (!window.seenCompletedStops[s.id]) {
                    const notif = document.createElement('li');
                    notif.innerHTML = `<strong>[${new Date().toLocaleTimeString()}]</strong> ✅ Stop #${s.sequence} completed by driver.`;
                    document.getElementById('notifications')?.prepend(notif);
                    window.seenCompletedStops[s.id] = true;
                    setTimeout(() => notif.remove(), GPS_TIMING.NOTIFICATION_DURATION_MS);
                }
            });

        // Map: show pending stops; List: pending first then completed/canceled
        const pendingStops = assignment.stops.filter(s => s.status !== 'completed' && s.status !== 'canceled');
        pendingStops.forEach((s) => {
            const color = s.priority === 'high' ? 'red' : s.priority === 'low' ? 'gray' : 'blue';
            const m = L.circleMarker([s.latitude, s.longitude], { radius: 8, color }).addTo(map)
                .bindPopup(`#${s.sequence} • ${s.priority}`);
            stopMarkers.push(m);

            const li = document.createElement('li');
            li.style.cssText = 'background: white; margin-bottom: 0.5rem; padding: 0.75rem; border-radius: 8px; border: 1px solid #e5e7eb; box-shadow: 0 1px 3px rgba(0,0,0,0.1); display: flex; align-items: center; justify-content: space-between; transition: all 0.2s ease;';
            li.dataset.stopId = s.id;
            li.innerHTML = `
                <div style="display: flex; align-items: center; gap: 0.75rem;">
                    <div style="background: #f3f4f6; color: #374151; font-weight: 600; padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.8rem;">#${s.sequence}</div>
                    <span style="padding: 0.25rem 0.5rem; border-radius: 12px; font-size: 0.75rem; font-weight: 600; color: white; ${s.priority === 'high' ? 'background: #dc2626;' : s.priority === 'low' ? 'background: #6b7280;' : 'background: #2563eb;'}">${s.priority}</span>
                </div>
                <div style="display: flex; gap: 0.5rem;">
                    <button style="background: #3b82f6; color: white; border: none; padding: 0.375rem 0.75rem; border-radius: 6px; font-size: 0.75rem; font-weight: 600; cursor: pointer; transition: all 0.2s ease;" onmouseover="this.style.background='#2563eb'" onmouseout="this.style.background='#3b82f6'" onclick="moveStop(${s.id}, -1, ${driverId})">Up</button>
                    <button style="background: #10b981; color: white; border: none; padding: 0.375rem 0.75rem; border-radius: 6px; font-size: 0.75rem; font-weight: 600; cursor: pointer; transition: all 0.2s ease;" onmouseover="this.style.background='#059669'" onmouseout="this.style.background='#10b981'" onclick="moveStop(${s.id}, 1, ${driverId})">Down</button>
                    <button style="background: #ef4444; color: white; border: none; padding: 0.375rem 0.75rem; border-radius: 6px; font-size: 0.75rem; font-weight: 600; cursor: pointer; transition: all 0.2s ease;" onmouseover="this.style.background='#dc2626'" onmouseout="this.style.background='#ef4444'" onclick="cancelStop(${s.id}, ${driverId})">Cancel</button>
                </div>`;
            ul.appendChild(li);
        });

        assignment.stops.filter(s => s.status === 'completed' || s.status === 'canceled').forEach((s) => {
            const li = document.createElement('li');
            li.style.cssText = 'background: #f9fafb; margin-bottom: 0.5rem; padding: 0.75rem; border-radius: 8px; border: 1px solid #e5e7eb; display: flex; align-items: center; justify-content: space-between; opacity: 0.7;';
            li.dataset.stopId = s.id;
            const statusBadge = s.status === 'completed' ? '<span style="background: #10b981; color: white; padding: 0.25rem 0.5rem; border-radius: 12px; font-size: 0.75rem; font-weight: 600; margin-left: 0.5rem;">Completed</span>' : '<span style="background: #ef4444; color: white; padding: 0.25rem 0.5rem; border-radius: 12px; font-size: 0.75rem; font-weight: 600; margin-left: 0.5rem;">Canceled</span>';
            li.innerHTML = `
                <div style="display: flex; align-items: center; gap: 0.75rem;">
                    <div style="background: #e5e7eb; color: #6b7280; font-weight: 600; padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.8rem;">#${s.sequence}</div>
                    <span style="padding: 0.25rem 0.5rem; border-radius: 12px; font-size: 0.75rem; font-weight: 600; color: white; ${s.priority === 'high' ? 'background: #dc2626;' : s.priority === 'low' ? 'background: #6b7280;' : 'background: #2563eb;'}">${s.priority}</span>
                    ${statusBadge}
                </div>
                <div style="display: flex; gap: 0.5rem;">
                    <button style="background: #d1d5db; color: #9ca3af; border: none; padding: 0.375rem 0.75rem; border-radius: 6px; font-size: 0.75rem; font-weight: 600; cursor: not-allowed;" disabled>Up</button>
                    <button style="background: #d1d5db; color: #9ca3af; border: none; padding: 0.375rem 0.75rem; border-radius: 6px; font-size: 0.75rem; font-weight: 600; cursor: not-allowed;" disabled>Down</button>
                    <button style="background: #d1d5db; color: #9ca3af; border: none; padding: 0.375rem 0.75rem; border-radius: 6px; font-size: 0.75rem; font-weight: 600; cursor: not-allowed;" disabled>Cancel</button>
                </div>`;
            ul.appendChild(li);
        });
    } catch (e) { console.error(e); }
}


async function moveStop(stopId, direction, driverId) {
    const res = await fetch(`/admin/assignments/${driverId}`);
    const assignment = await res.json();
    const ids = assignment.stops.map(s => s.id);
    const idx = ids.indexOf(stopId);
    const swapIdx = idx + direction;
    if (swapIdx < 0 || swapIdx >= ids.length) return;
    [ids[idx], ids[swapIdx]] = [ids[swapIdx], ids[idx]];
    await fetch(`/admin/assignments/${driverId}/reorder`, {
        method: 'POST', headers: { 'Content-Type':'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') },
        body: JSON.stringify({ order: ids })
    });
    renderStopsForDriver(driverId);
}

async function cancelStop(stopId, driverId) {
    await fetch(`/admin/assignments/${driverId}/stops/${stopId}`, {
        method: 'DELETE', headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') }
    });
    renderStopsForDriver(driverId);
}

document.getElementById('refresh-stops')?.addEventListener('click', () => {
    // Refresh stops functionality - can be implemented later if needed
    console.log('Refresh stops clicked');
});

// Helper function to get priority color
function getPriorityColor(priority) {
    const colors = {
        'Low': '#10b981',
        'Medium': '#f59e0b', 
        'High': '#ef4444',
        'Critical': '#dc2626',
        'Emergency': '#991b1b'
    };
    return colors[priority] || '#6b7280';
}

function getPriorityClassName(priority) {
    const value = (priority || '').toLowerCase();
    if (value === 'low') return 'logs-pill--priority-low';
    if (value === 'high') return 'logs-pill--priority-high';
    if (value === 'critical' || value === 'emergency') return 'logs-pill--priority-critical';
    return 'logs-pill--priority-medium';
}

// Function to update case marker color based on status
function updateCaseMarkerStatus(caseNum, caseData) {
    const marker = caseMarkers[caseNum];
    if (marker) {
        const isActive = (caseData.driver_accepted === true || caseData.status === 'In Progress' || caseData.status === 'Accepted');
        const newIcon = createCaseIcon(caseNum, isActive);
        marker.setIcon(newIcon);
    }
}

// Notification system - create container outside map if it doesn't exist
function ensureNotificationContainer() {
    let container = document.getElementById('notification-container');
    if (!container) {
        container = document.createElement('div');
        container.id = 'notification-container';
        container.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 99999;
            pointer-events: none;
        `;
        document.body.appendChild(container);
    }
    return container;
}

function showNotification(message, type = 'info') {
    const container = ensureNotificationContainer();
    
    // Create notification element
    const notification = document.createElement('div');
    notification.style.cssText = `
        background: ${type === 'success' ? '#10b981' : type === 'error' ? '#ef4444' : '#3b82f6'};
        color: white;
        padding: 1rem 1.5rem;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        font-weight: 600;
        max-width: 300px;
        margin-bottom: 10px;
        pointer-events: auto;
        animation: slideInRight 0.3s ease-out;
    `;
    
    notification.innerHTML = `
        <div style="display: flex; align-items: center; gap: 0.5rem;">
            <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'}"></i>
            <span>${message}</span>
        </div>
    `;
    
    // Add CSS animation if not already added
    if (!document.getElementById('notification-animations')) {
        const style = document.createElement('style');
        style.id = 'notification-animations';
        style.textContent = `
            @keyframes slideInRight {
                from { transform: translateX(100%); opacity: 0; }
                to { transform: translateX(0); opacity: 1; }
            }
            @keyframes slideOutRight {
                from { transform: translateX(0); opacity: 1; }
                to { transform: translateX(100%); opacity: 0; }
            }
        `;
        document.head.appendChild(style);
    }
    
    container.appendChild(notification);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        notification.style.animation = 'slideOutRight 0.3s ease-in';
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 300);
    }, GPS_TIMING.NOTIFICATION_DURATION_MS);
}

// Case creation form submission
document.getElementById('case-creation-form')?.addEventListener('submit', async function(event) {
    event.preventDefault();
    
    // Get selected ambulances
    const selectedAmbulances = Array.from(document.querySelectorAll('input[name="ambulances[]"]:checked'))
        .map(checkbox => checkbox.value);
    
    const formData = {
        name: document.getElementById('case-name').value,
        contact: document.getElementById('case-contact').value,
        age: document.getElementById('case-age').value || null,
        date_of_birth: document.getElementById('case-date-of-birth').value || null,
        address: document.getElementById('case-address').value,
        destination: document.getElementById('case-destination').value,
        type: document.getElementById('case-type').value,
        ambulance_ids: selectedAmbulances,
        latitude: window.clickedLatitude,
        longitude: window.clickedLongitude,
        destination_latitude: window.destinationLatitude,
        destination_longitude: window.destinationLongitude,
        timestamp: new Date().toISOString()
    };
    
    // Validate required fields
    if (!formData.name || !formData.contact || !formData.address || !formData.destination || selectedAmbulances.length === 0) {
        showInlineNotice('Please fill in all required fields and select at least one ambulance.', {
            title: 'Form Incomplete',
            type: 'warning'
        });
        return;
    }
    
    // Validate coordinates
    if (!formData.latitude || !formData.longitude) {
        showInlineNotice('Please set a pickup location by clicking on the map.', {
            title: 'Pickup Needed',
            type: 'warning'
        });
        return;
    }
    
    if (!formData.destination_latitude || !formData.destination_longitude) {
        showInlineNotice('Please set a destination location using the search button or Ctrl+Click on the map.', {
            title: 'Destination Needed',
            type: 'warning'
        });
        return;
    }
    
    try {
        const response = await fetch('/admin/cases', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                        },
            body: JSON.stringify(formData)
        });
        
        if (response.ok) {
            const result = await response.json();
            
            // Show success notification
            showNotification(`Case #${result.case_num} created successfully!`, 'success');
            
            // Close modal and reset form
            closeModal('case-creation-modal');
            document.getElementById('case-creation-form').reset();
            
            // Remove temporary pins after successful creation
            if (currentPinMarker) {
                map.removeLayer(currentPinMarker);
                currentPinMarker = null;
            }
            if (currentDestinationMarker) {
                map.removeLayer(currentDestinationMarker);
                currentDestinationMarker = null;
            }
            
            // Clear coordinates
            window.clickedLatitude = null;
            window.clickedLongitude = null;
            
            // Add a persistent marker on the map for the new case
            const caseMarker = L.marker([formData.latitude, formData.longitude], { 
                icon: createCaseIcon(result.case_num, false), // New cases start as pending (gray)
                draggable: false
            }).addTo(map);
            
            // Store case marker for future reference
            caseMarkers[result.case_num] = caseMarker;
            
            // Auto-fit bounds to show all markers including new case
            setTimeout(() => autoFitMapBounds(), 400);
            
            // Determine priority based on incident type (new set: VA, TR, ME, SB, OB, NVAT, COORDINATION, TRAINING)
            const highTypes = ['VA', 'TR', 'ME'];
            const priority = highTypes.includes(formData.type) ? 'High' : 'Medium';
            
            // Create detailed popup content
            const popupContent = `
                <div style="min-width: 200px;">
                    <h4 style="margin: 0 0 0.5rem 0; color: #1e293b;">Case #${result.case_num}</h4>
                    <p style="margin: 0 0 0.3rem 0; font-weight: 600;">${formData.name}</p>
                    <p style="margin: 0 0 0.3rem 0; color: #6b7280; font-size: 0.9rem;">${formData.contact}</p>
                    <p style="margin: 0 0 0.3rem 0; color: #6b7280; font-size: 0.9rem;">${formData.address}</p>
                    <div style="display: flex; gap: 0.5rem; margin-top: 0.5rem; flex-wrap: wrap;">
                        <span style="background: ${getPriorityColor(priority)}; color: white; padding: 0.2rem 0.5rem; border-radius: 12px; font-size: 0.8rem; font-weight: 600;">
                            ${priority}
                        </span>
                        ${formData.type ? `<span style="background: #3b82f6; color: white; padding: 0.2rem 0.5rem; border-radius: 12px; font-size: 0.8rem; font-weight: 600;">${formData.type}</span>` : ''}
                        <span style="background: #f59e0b; color: white; padding: 0.2rem 0.5rem; border-radius: 12px; font-size: 0.8rem; font-weight: 600;">
                            🚑 Assigned
                        </span>
                    </div>
                    <div style="margin-top: 0.5rem; font-size: 0.8rem; color: #6b7280;">
                        Created: ${new Date().toLocaleString()}
                    </div>
                </div>
            `;
            
            // Add click event to open case details modal
            caseMarker.on('click', function() {
                openCaseDetailsModal(result.case);
                showCaseConnectionLine(result.case);
            });
            
            // Refresh ambulance data to show updated status
            fetchAmbulanceData();
            
            // Refresh case counts and update dropdown
            loadAmbulanceCaseCounts();
            
            // Mark notification as sent to driver
            markNotificationSent(result.case_num);
            
            // Reset moving mode
            isMovingPin = false;
            document.getElementById('move-pin-btn').textContent = 'Move Pin';
            document.getElementById('move-pin-btn').style.background = '#f59e0b';
        } else {
            const error = await response.json();
            showInlineNotice('Error creating case: ' + (error.message || 'Unknown error'), {
                title: 'Creation Failed',
                type: 'danger'
            });
        }
    } catch (error) {
        console.error('Error creating case:', error);
        showInlineNotice('Error creating case. Please try again.', {
            title: 'Creation Failed',
            type: 'danger'
        });
    }
});


// Load case counts for ambulances
let ambulanceCaseCounts = {};

async function loadAmbulanceCaseCounts() {
    try {
        const response = await fetch('/admin/cases/ambulance/counts', {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        ambulanceCaseCounts = await response.json();
        updateAmbulanceDropdown();
    } catch (error) {
        console.error('Error loading ambulance case counts:', error);
    }
}

function updateAmbulanceDropdown() {
    // Update the old dropdown (if it exists)
    const select = document.getElementById('case-ambulance');
    if (select) {
        Array.from(select.options).forEach(option => {
            if (option.value) {
                const ambulanceId = parseInt(option.value);
                const caseCount = ambulanceCaseCounts[ambulanceId] || 0;
                const originalText = option.textContent.replace(/\s*\(\d+ cases?\)\s*$/, '');
                option.textContent = `${originalText} (${caseCount} case${caseCount !== 1 ? 's' : ''})`;
            }
        });
    }
    
    // Update the checkbox form case counts - check all ambulance elements
    document.querySelectorAll('[id^="case-count-"]').forEach(element => {
        const ambulanceId = element.id.replace('case-count-', '');
        const caseCount = ambulanceCaseCounts[ambulanceId] || 0;
        element.textContent = `${caseCount} case${caseCount !== 1 ? 's' : ''}`;
        
        // Update color based on case count
        if (caseCount === 0) {
            element.style.background = '#f0fdf4';
            element.style.color = '#166534';
        } else if (caseCount <= 2) {
            element.style.background = '#fef3c7';
            element.style.color = '#92400e';
        } else {
            element.style.background = '#fee2e2';
            element.style.color = '#dc2626';
        }
    });
}

// Load existing cases (excluding completed ones)
async function loadExistingCases() {
    try {
        const response = await fetch('/admin/cases', {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        const cases = await response.json();
        
        cases.forEach(caseData => {
            // If a specific driver is selected, show only their accepted/in-progress cases
            if (currentFilterDriverId !== 'all') {
                const ambId = caseData.ambulance_id || (caseData.ambulance && (caseData.ambulance.id || caseData.ambulance.ambulance_id));
                const drvId = ambId ? ambulanceIdToDriverId[ambId] : null;
                const status = (caseData.status || 'Pending');
                const acceptedOrActive = (status === 'Accepted' || status === 'In Progress' || caseData.driver_accepted === true);
                const matchesByDriver = drvId && String(drvId) === String(currentFilterDriverId);
                const matchesByAmb = ambId && String(`amb-${ambId}`) === String(currentFilterDriverId);
                if (!(acceptedOrActive && (matchesByDriver || matchesByAmb))) {
                    return;
                }
            }
            // Skip completed cases - they should not appear on the map
            if (caseData.status === 'Completed') return;
            
            if (caseData.latitude && caseData.longitude) {
                // Check if case is active (ambulance is OTW)
                const isActive = (caseData.driver_accepted === true || caseData.status === 'In Progress' || caseData.status === 'Accepted');
                const caseMarker = L.marker([caseData.latitude, caseData.longitude], { 
                    icon: createCaseIcon(caseData.case_num, isActive), // Use dashboard-style case icon
                    draggable: false
                }).addTo(map);
                
                // Store case marker
                caseMarkers[caseData.case_num] = caseMarker;
                
                // Add geofence circle for pickup location
                if (caseData.latitude && caseData.longitude) {
                    const pickupGeofenceCircle = L.circle([caseData.latitude, caseData.longitude], {
                        radius: GEOFENCE_RADIUS,
                        color: '#6b7280', // Gray when outside
                        fillColor: '#6b7280',
                        fillOpacity: 0.2,
                        weight: 2
                    }).addTo(map);
                    
                    geofenceCircles[`${caseData.case_num}_pickup`] = pickupGeofenceCircle;
                }
                
                // Add geofence circle for destination if coordinates exist
                if (caseData.to_go_to_latitude && caseData.to_go_to_longitude) {
                    const destGeofenceCircle = L.circle([caseData.to_go_to_latitude, caseData.to_go_to_longitude], {
                        radius: GEOFENCE_RADIUS,
                        color: '#6b7280', // Gray when outside
                        fillColor: '#6b7280',
                        fillOpacity: 0.2,
                        weight: 2
                    }).addTo(map);
                    
                    geofenceCircles[`${caseData.case_num}_destination`] = destGeofenceCircle;
                }
                
                // Auto-fit bounds when case is added
                setTimeout(() => autoFitMapBounds(), 400);
                
                // Add click event to open case details modal and show connection line
                caseMarker.on('click', function() {
                    openCaseDetailsModal(caseData);
                    showCaseConnectionLine(caseData);
                });
            }
        });
    } catch (error) {
        console.error('Error loading existing cases:', error);
    }
}

// Load completed cases for logs
let completedCasesCache = [];

async function loadCompletedCases() {
    try {
        const response = await fetch('/admin/cases/completed', {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            credentials: 'same-origin' // Include cookies for authentication
        });
        
        if (!response.ok) {
            if (response.status === 401 || response.status === 403) {
                throw new Error('Authentication required. Please log in as admin.');
            }
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        // Check if response is HTML (login page redirect)
        const contentType = response.headers.get('content-type');
        if (contentType && contentType.includes('text/html')) {
            throw new Error('Authentication required. Please log in as admin.');
        }
        
        const data = await response.json();
        console.log('Completed cases response:', data); // Debug log
        
        // Handle different response formats
        const cases = Array.isArray(data) ? data : (data.cases || []);
        completedCasesCache = cases;
        
        renderLogsList();
    } catch (error) {
        console.error('Error loading completed cases:', error);
        const logsTableBody = document.getElementById('logs-table-body');
        if (logsTableBody) {
            let errorMessage = 'Error loading completed cases';
            if (error.message.includes('Authentication required')) {
                errorMessage = 'Please log in as admin to view completed cases';
            }

            logsTableBody.innerHTML = `
                <tr>
                    <td colspan="10">
                        <div class="logs-error">
                            <i class="fas fa-exclamation-triangle"></i>
                            <p class="logs-error-title">${errorMessage}</p>
                            <p class="logs-error-subtitle">${error.message}</p>
                        </div>
                    </td>
                </tr>
            `;
        }
    }
}

function getLogsFilters() {
    const search = (document.getElementById('logs-search')?.value || '').toLowerCase().trim();
    const priority = document.getElementById('logs-priority')?.value || '';
    const ambulance = (document.getElementById('logs-ambulance')?.value || '').toLowerCase().trim();
    return { search, priority, ambulance };
}

function filterCompletedCases(list) {
    const { search, priority, ambulance } = getLogsFilters();
    return list.filter(c => {
        const matchesPriority = !priority || (c.priority || 'Medium') === priority;
        const haystack = `${c.case_num || ''} ${c.name || ''} ${c.address || ''}`.toLowerCase();
        const matchesSearch = !search || haystack.includes(search);
        const ambName = (c.ambulance && c.ambulance.name ? c.ambulance.name : '').toLowerCase();
        const matchesAmb = !ambulance || ambName.includes(ambulance);
        return matchesPriority && matchesSearch && matchesAmb;
    });
}

function renderLogsList() {
    const logsTableBody = document.getElementById('logs-table-body');
    if (!logsTableBody) return;
    logsTableBody.innerHTML = '';
    const filtered = filterCompletedCases(completedCasesCache || []);

    if (!Array.isArray(filtered) || filtered.length === 0) {
        logsTableBody.innerHTML = `
            <tr>
                <td colspan="10">
                    <div class="logs-empty">
                        <i class="fas fa-inbox"></i>
                        <p>No completed cases found</p>
                    </div>
                </td>
            </tr>
        `;
        return;
    }

    filtered.forEach(caseData => {
        const priority = caseData.priority || 'Medium';
        const priorityClass = getPriorityClassName(priority);
        const typeTag = caseData.type ? `<span class="logs-pill logs-pill--info">${caseData.type}</span>` : '';
        const ambulanceNameRaw = caseData.ambulance && caseData.ambulance.name ? caseData.ambulance.name : 'Unknown Ambulance';
        const ambulanceName = String(ambulanceNameRaw).replace(/\s+/g, ' ').trim();
        const createdAt = caseData.created_at ? new Date(caseData.created_at).toLocaleString() : 'Unknown';
        const completedAt = caseData.completed_at ? new Date(caseData.completed_at).toLocaleString() : 'Unknown';
        const pinAddress = caseData.address || caseData.destination || caseData.destination_address || 'No pinned address';

        const row = document.createElement('tr');
        row.innerHTML = `
            <td>Case #${caseData.case_num ?? '—'}</td>
            <td><div style="font-weight:700;">${caseData.name ?? 'Unidentified Patient'}</div></td>
            <td class="logs-contact-cell">${caseData.contact ? `📞 ${caseData.contact}` : 'N/A'}</td>
            <td>${pinAddress}</td>
            <td><span class="logs-pill ${priorityClass}">${priority}</span></td>
            <td>${typeTag || '<span class="logs-pill logs-pill--muted">N/A</span>'}</td>
            <td class="logs-ambulance-cell">${ambulanceName}</td>
            <td>${createdAt}</td>
            <td>${completedAt}</td>
            <td><button class="logs-print-btn" data-case="${caseData.case_num ?? ''}">Print</button></td>
        `;

        const printBtn = row.querySelector('.logs-print-btn');
        if (printBtn) {
            printBtn.addEventListener('click', (e) => {
                e.preventDefault();
                printCase(caseData);
            });
        }

        logsTableBody.appendChild(row);
    });

}

// Logs controls events
document.addEventListener('input', function(e) {
    if (e.target && ['logs-search','logs-priority','logs-ambulance'].includes(e.target.id)) {
        renderLogsList();
    }
});

document.getElementById('logs-clear')?.addEventListener('click', function(e) {
    e.preventDefault();
    ['logs-search','logs-ambulance'].forEach(id => {
        const el = document.getElementById(id);
        if (el) el.value = '';
    });
    renderLogsList();
});

// Removed bulk print action

// Load active (non-completed) cases and render with filters
let activeCasesCache = [];

async function loadActiveCases() {
    try {
        const response = await fetch('/admin/cases', {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        const allCases = await response.json();
        const rawCases = Array.isArray(allCases) ? allCases : [];
        // Align with dashboard: total = all cases, active = accepted/in-progress/driver_accepted
        totalCasesCount = rawCases.length;
        activeCasesCount = rawCases.filter(c => c.driver_accepted === true || c.status === 'In Progress' || c.status === 'Accepted').length;
        updateStatusCounts();
        // For rendering markers: exclude completed
        activeCasesCache = rawCases.filter(c => c.status !== 'Completed');
        rawCases.forEach(c => {
            if (c && c.case_num && c.status !== 'Completed' && completedCaseNums && completedCaseNums.delete) {
                completedCaseNums.delete(parseInt(c.case_num));
            }
        });
        // Rebuild activeAmbulanceIds snapshot for "All active cases"
        try {
            activeAmbulanceIds.clear();
            activeCasesCache.forEach(c => {
                const isActive = (c.driver_accepted === true || c.status === 'In Progress' || c.status === 'Accepted');
                if (!isActive) return;
                const ambId = c.ambulance_id || (c.ambulance && (c.ambulance.id || c.ambulance.ambulance_id));
                if (ambId) activeAmbulanceIds.add(parseInt(ambId));
            });
        } catch(_){}
        renderActiveCasesTable();
    } catch (error) {
        console.error('Error loading active cases:', error);
        renderActiveCasesTable([], 'Error loading active cases');
    }
}

function getActiveFilters() {
    const search = (document.getElementById('active-search')?.value || '').toLowerCase().trim();
    const priority = document.getElementById('active-priority')?.value || '';
    const status = document.getElementById('active-status')?.value || '';
    const ambulance = (document.getElementById('active-ambulance')?.value || '').toLowerCase().trim();
    return { search, priority, status, ambulance };
}

// Generate printable HTML for a case prince
function generateCasePrintHtml(caseData) {
    const styles = `
        <style>
            @page { size: A4 portrait; margin: 0; }
            html, body { height: 100%; }
            body { font-family: Arial, sans-serif; color: #000; height: 100%; margin: 0; }
            .toolbar { text-align: right; margin-bottom: 8px; }
            .toolbar button { background:#111827;color:#fff;border:none;border-radius:6px;padding:6px 10px; font-weight:700; cursor:pointer; }
            .page { position: relative; width: 100%; height: 297mm; background: #fff; }
            .bg-img { position:absolute; inset:0; width:100%; height:100%; object-fit: cover; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            
            /* Individual field positioning for ConductionForm.png - compact spacing */
            .field-name { position: absolute; left: 19mm; top: 62mm; font-size: 7pt; font-weight: bold; color: #000; max-width: 80mm; }
            .field-age { position: absolute; left: 19mm; top: 66mm; font-size: 7pt; font-weight: bold; color: #000; max-width: 15mm; }
            .field-sex { position: absolute; left: 45mm; top: 66mm; font-size: 7pt; font-weight: bold; color: #000; max-width: 15mm; }
            .field-dob { position: absolute; left: 70mm; top: 66mm; font-size: 7pt; font-weight: bold; color: #000; max-width: 25mm; }
            .field-contact { position: absolute; left: 19mm; top: 69mm; font-size: 7pt; font-weight: bold; color: #000; max-width: 80mm; }
            .field-address { position: absolute; left: 19mm; top: 73mm; font-size: 7pt; font-weight: bold; color: #000; max-width: 80mm; }
            .field-from { position: absolute; left: 19mm; top: 80mm; font-size: 7pt; font-weight: bold; color: #000; max-width: 80mm; }
            .field-to { position: absolute; left: 19mm; top: 86mm; font-size: 7pt; font-weight: bold; color: #000; max-width: 80mm; }
            
            @media print { .no-print { display: none; } .toolbar { display:none; } }
        </style>
    `;
    
    const now = new Date().toLocaleString();
    const fromText = (caseData.address || '').toString();
    const toText = (caseData.destination || caseData.to_go_to || '').toString();
    
    // Determine 4Ps member status - you may need to adjust this logic based on your data structure
    let fourPsStatus = 'None';
    if (caseData.four_ps_member) {
        fourPsStatus = caseData.four_ps_member;
    }
    
    return `
        <!DOCTYPE html>
        <html><head><meta charset="utf-8">${styles}<title>Case #${caseData.case_num || ''}</title></head>
        <body>
            <div class="toolbar no-print"><button onclick="window.print()">Print</button></div>
            <div class="page">
                <img class="bg-img" src="${"{{ asset('image/ConductionForm.png') }}"}" alt="Conduction Form" />
                
                <!-- Patient Information Fields positioned to match the form -->
                <div class="field-name">Name: ${caseData.name || ''}</div>
                <div class="field-age">Age: ${caseData.age || '—'}</div>
                <div class="field-sex">Sex: ${caseData.sex || caseData.gender || '—'}</div>
                <div class="field-dob">Date of Birth: ${caseData.date_of_birth ? new Date(caseData.date_of_birth).toLocaleDateString() : '—'}</div>
                <div class="field-contact">Contact No/s.: ${caseData.contact || ''}</div>
                <div class="field-address">Address: ${caseData.address || '—'}</div>
                <div class="field-from">From: ${fromText}</div>
                <div class="field-to">To: ${toText}</div>
            </div>
        </body></html>
    `;
}

function printCase(caseData) {
    const html = generateCasePrintHtml(caseData);
    const w = window.open('', '_blank');
    if (!w) return;
    w.document.open();
    w.document.write(html);
    w.document.close();
    // Delay to ensure styles load before printing
    w.onload = () => w.print();
}

function renderActiveCasesTable(forcedList, errorText) {
    const tbody = document.querySelector('#active-cases-table tbody');
    if (!tbody) return;
    tbody.innerHTML = '';

    const filters = getActiveFilters();
    const list = Array.isArray(forcedList) ? forcedList : activeCasesCache;

    if (errorText) {
        const tr = document.createElement('tr');
        const td = document.createElement('td');
        td.colSpan = 8;
        td.style.padding = '1rem';
        td.style.textAlign = 'center';
        td.style.color = '#ef4444';
        td.textContent = errorText;
        tr.appendChild(td);
        tbody.appendChild(tr);
        return;
    }

    let filtered = list.filter(c => {
        const matchesPriority = !filters.priority || (c.priority || 'Medium') === filters.priority;
        const matchesStatus = !filters.status || (c.status || 'Pending') === filters.status;
        const haystack = `${c.case_num || ''} ${c.name || ''} ${c.address || ''}`.toLowerCase();
        const matchesSearch = !filters.search || haystack.includes(filters.search);
        const ambName = (c.ambulance && c.ambulance.name ? c.ambulance.name : '').toLowerCase();
        const matchesAmb = !filters.ambulance || ambName.includes(filters.ambulance);
        return matchesPriority && matchesStatus && matchesSearch && matchesAmb;
    });

    if (filtered.length === 0) {
        const tr = document.createElement('tr');
        const td = document.createElement('td');
        td.colSpan = 8;
        td.style.padding = '1rem';
        td.style.textAlign = 'center';
        td.textContent = 'No active cases';
        tr.appendChild(td);
        tbody.appendChild(tr);
        return;
    }

    filtered.forEach(caseData => {
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td style="padding: 0.5rem 0.75rem;">#${caseData.case_num}</td>
            <td style="padding: 0.5rem 0.75rem;">${caseData.name || ''}</td>
            <td style="padding: 0.5rem 0.75rem;"><span style="background:${getPriorityColor(caseData.priority || 'Medium')}; color:#fff; padding:2px 8px; border-radius:12px; font-size:12px; font-weight:700;">${caseData.priority || 'Medium'}</span></td>
            <td style="padding: 0.5rem 0.75rem;">${caseData.type || ''}</td>
            <td style="padding: 0.5rem 0.75rem;">${caseData.status || 'Pending'}</td>
            <td style="padding: 0.5rem 0.75rem;">${caseData.ambulance ? caseData.ambulance.name : '—'}</td>
            <td style="padding: 0.5rem 0.75rem;">${caseData.created_at ? new Date(caseData.created_at).toLocaleString() : ''}</td>
            <td style="padding: 0.5rem 0.75rem;">
                <div style="display:flex; gap:6px;">
                    <button class="btn-view" style="background:#2563eb;color:#fff;border:none;border-radius:6px;padding:0.35rem 0.6rem;font-size:12px; font-weight:700; cursor:pointer;">View</button>
                    <button class="btn-print" style="background:#10b981;color:#fff;border:none;border-radius:6px;padding:0.35rem 0.6rem;font-size:12px; font-weight:700; cursor:pointer;">Print</button>
                </div>
            </td>
        `;
        const btnView = tr.querySelector('.btn-view');
        btnView.addEventListener('click', (e) => {
            e.preventDefault();
            openCaseDetailsModal(caseData);
        });
        const btnPrint = tr.querySelector('.btn-print');
        btnPrint.addEventListener('click', (e) => {
            e.preventDefault();
            printCase(caseData);
        });
        tbody.appendChild(tr);
    });
}

// Filter input wiring
document.addEventListener('input', function(e) {
    if (e.target && ['active-search','active-priority','active-status','active-ambulance'].includes(e.target.id)) {
        renderActiveCasesTable();
    }
});

document.getElementById('active-clear')?.addEventListener('click', function(e) {
    e.preventDefault();
    const ids = ['active-search','active-priority','active-status','active-ambulance'];
    ids.forEach(id => {
        const el = document.getElementById(id);
        if (!el) return;
        el.value = '';
    });
    renderActiveCasesTable();
});

// Function to refresh case statuses
async function refreshCaseStatuses() {
    try {
        const response = await fetch('/admin/cases', {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        const cases = await response.json();
        
        // Update existing markers with new statuses, but skip completed cases
        cases.forEach(caseData => {
            // Skip completed cases - they should not appear on the map
            if (caseData.status === 'Completed') {
                // Remove completed case markers from map
                if (caseMarkers[caseData.case_num]) {
                    map.removeLayer(caseMarkers[caseData.case_num]);
                    delete caseMarkers[caseData.case_num];
                }
                // Remove geofence circles for completed cases
                if (geofenceCircles[`${caseData.case_num}_pickup`]) {
                    map.removeLayer(geofenceCircles[`${caseData.case_num}_pickup`]);
                    delete geofenceCircles[`${caseData.case_num}_pickup`];
                }
                if (geofenceCircles[`${caseData.case_num}_destination`]) {
                    map.removeLayer(geofenceCircles[`${caseData.case_num}_destination`]);
                    delete geofenceCircles[`${caseData.case_num}_destination`];
                }
                return;
            }
            
            if (caseMarkers[caseData.case_num]) {
                // Respect current driver selection: hide or show case markers
                let show = true;
                if (currentFilterDriverId !== 'all'){
                    const ambId = caseData.ambulance_id || (caseData.ambulance && (caseData.ambulance.id || caseData.ambulance.ambulance_id));
                    const selected = String(currentFilterDriverId);
                    if (selected.startsWith('amb-')){
                        show = String(`amb-${ambId}`) === selected;
                    } else {
                        const drvId = ambId ? ambulanceIdToDriverId[ambId] : null;
                        show = drvId && String(drvId) === selected;
                    }
                }
                if (showActiveOnly){
                    // Only show the single current active case per ambulance (the one with trail)
                    show = show && currentActiveCaseNums && currentActiveCaseNums.has(caseData.case_num);
                }
                const marker = caseMarkers[caseData.case_num];
                const onMap = map.hasLayer(marker);
                if (show && !onMap) { try { marker.addTo(map); } catch(_){} }
                if (!show && onMap) { try { map.removeLayer(marker); } catch(_){} }
                updateCaseMarkerStatus(caseData.case_num, caseData);
                
                // Ensure geofence circles exist for pickup and destination
                if (caseData.latitude && caseData.longitude && !geofenceCircles[`${caseData.case_num}_pickup`]) {
                    const pickupCircle = L.circle([caseData.latitude, caseData.longitude], {
                        radius: GEOFENCE_RADIUS,
                        color: '#6b7280',
                        fillColor: '#6b7280',
                        fillOpacity: 0.2,
                        weight: 2
                    }).addTo(map);
                    geofenceCircles[`${caseData.case_num}_pickup`] = pickupCircle;
                }
                if (caseData.to_go_to_latitude && caseData.to_go_to_longitude && !geofenceCircles[`${caseData.case_num}_destination`]) {
                    const destCircle = L.circle([caseData.to_go_to_latitude, caseData.to_go_to_longitude], {
                        radius: GEOFENCE_RADIUS,
                        color: '#6b7280',
                        fillColor: '#6b7280',
                        fillOpacity: 0.2,
                        weight: 2
                    }).addTo(map);
                    geofenceCircles[`${caseData.case_num}_destination`] = destCircle;
                }
            } else if (caseData.latitude && caseData.longitude) {
                // Case marker doesn't exist yet, but we should create geofence circles if they exist
                if (caseData.latitude && caseData.longitude && !geofenceCircles[`${caseData.case_num}_pickup`]) {
                    const pickupCircle = L.circle([caseData.latitude, caseData.longitude], {
                        radius: GEOFENCE_RADIUS,
                        color: '#6b7280',
                        fillColor: '#6b7280',
                        fillOpacity: 0.2,
                        weight: 2
                    }).addTo(map);
                    geofenceCircles[`${caseData.case_num}_pickup`] = pickupCircle;
                }
                if (caseData.to_go_to_latitude && caseData.to_go_to_longitude && !geofenceCircles[`${caseData.case_num}_destination`]) {
                    const destCircle = L.circle([caseData.to_go_to_latitude, caseData.to_go_to_longitude], {
                        radius: GEOFENCE_RADIUS,
                        color: '#6b7280',
                        fillColor: '#6b7280',
                        fillOpacity: 0.2,
                        weight: 2
                    }).addTo(map);
                    geofenceCircles[`${caseData.case_num}_destination`] = destCircle;
                }
            }
        });

            // Align counters with dashboard on every refresh
        try {
            totalCasesCount = Array.isArray(cases) ? cases.length : 0;
            activeCasesCount = (Array.isArray(cases) ? cases : []).filter(c => c.driver_accepted === true || c.status === 'In Progress' || c.status === 'Accepted').length;
            updateStatusCounts();
        } catch(e){}

        // Draw routed trail from driver to current case per ambulance (dashboard parity)
        try {
            // Build cases grouped by ambulance
            const casesByAmb = {};
            cases.forEach(c => {
                const ambId = c.ambulance_id || (c.ambulance && (c.ambulance.id || c.ambulance.ambulance_id));
                if (!ambId) return;
                // Respect current driver filter by mapping ambulance -> driver
                if (currentFilterDriverId !== 'all') {
                    const drvId = ambulanceIdToDriverId ? ambulanceIdToDriverId[ambId] : null;
                    const matchesByDriver = drvId && String(drvId) === String(currentFilterDriverId);
                    const matchesByAmb = String(`amb-${ambId}`) === String(currentFilterDriverId);
                    if (!(matchesByDriver || matchesByAmb)) return;
                }
                (casesByAmb[ambId] = casesByAmb[ambId] || []).push(c);
            });

            const pickCurrentCase = (list) => {
                if (!Array.isArray(list) || list.length === 0) return null;
                const open = list.filter(x => (x.status || 'Pending') !== 'Completed');
                if (open.length === 0) return null;
                const accepted = open.filter(x => x.driver_accepted === true || x.status === 'In Progress' || x.status === 'Accepted');
                const pool = accepted.length ? accepted : open;
                pool.sort((a,b) => new Date(b.updated_at || b.created_at || 0) - new Date(a.updated_at || a.created_at || 0));
                return pool[0];
            };

            // Clear previous trails and recompute active ambulance set and current active case set
            Object.values(driverToCaseTraces || {}).forEach(line => { try { map.removeLayer(line); } catch(_){} });
            driverToCaseTraces = {};
            activeAmbulanceIds.clear();
            currentActiveCaseNums.clear();

            for (const ambId of Object.keys(casesByAmb)) {
                const cur = pickCurrentCase(casesByAmb[ambId]);
                if (!cur) continue;
                const lat = parseFloat(cur.latitude), lng = parseFloat(cur.longitude);
                const marker = driverMarkers[ambId];
                if (!marker || Number.isNaN(lat) || Number.isNaN(lng)) continue;
                
                // Check if driver is actively navigating (has destination coordinates)
                const ambData = ambulanceDataMap[ambId];
                const isNavigating = ambData && ambData.destination_latitude && ambData.destination_longitude;
                
                // Only draw trail if driver is actively navigating to a pin
                if (isNavigating) {
                    const driverLatLng = marker.getLatLng();
                    if (!driverLatLng || Number.isNaN(driverLatLng.lat) || Number.isNaN(driverLatLng.lng)) continue;
                    const destLat = parseFloat(ambData.destination_latitude);
                    const destLng = parseFloat(ambData.destination_longitude);
                    
                    if (!Number.isNaN(destLat) && !Number.isNaN(destLng)) {
                        const routedTrail = await drawRoutedLine([driverLatLng.lat, driverLatLng.lng], [destLat, destLng], { color: '#ff8c42', weight: 6, opacity: 0.95 });
                        driverToCaseTraces[cur.case_num] = routedTrail;
                    }
                }
                
                // Track ambulance as active (regardless of navigation status)
                activeAmbulanceIds.add(parseInt(ambId));
                // Track the current active case number for filtering pins
                currentActiveCaseNums.add(parseInt(cur.case_num));
            }
            
            // If a specific driver is selected, clear any trails not belonging to that driver
            try {
                if (currentFilterDriverId !== 'all'){
                    const selected = String(currentFilterDriverId);
                    Object.keys(driverToCaseTraces).forEach(caseNum => {
                        const c = (Array.isArray(cases) ? cases : []).find(x => String(x.case_num) === String(caseNum));
                        if (!c) return;
                        const ambId = c.ambulance_id || (c.ambulance && (c.ambulance.id || c.ambulance.ambulance_id));
                        let belongs = true;
                        if (selected.startsWith('amb-')){
                            belongs = String(`amb-${ambId}`) === selected;
                        } else {
                            const drvId = ambId ? ambulanceIdToDriverId[ambId] : null;
                            belongs = drvId && String(drvId) === selected;
                        }
                        if (!belongs){
                            try { map.removeLayer(driverToCaseTraces[caseNum]); } catch(_){ }
                            delete driverToCaseTraces[caseNum];
                        }
                    });
                }
            } catch(_){ }
        } catch (e) { /* no-op */ }
    } catch (error) {
        console.error('Error refreshing case statuses:', error);
    }
}


// Re-draw trails using already-fetched data (no network), useful after fullscreen/resize
function redrawTrailsFromCache() {
    try {
        if (!activeCasesCache || !Array.isArray(activeCasesCache)) return;
        // Build cases grouped by ambulance (exclude completed)
        const casesByAmb = {};
        (activeCasesCache || []).forEach(c => {
            if (c.status === 'Completed') return;
            const ambId = c.ambulance_id || (c.ambulance && (c.ambulance.id || c.ambulance.ambulance_id));
            if (!ambId) return;
            if (currentFilterDriverId !== 'all' && String(ambId) !== String(currentFilterDriverId)) return;
            (casesByAmb[ambId] = casesByAmb[ambId] || []).push(c);
        });

        const pickCurrentCase = (list) => {
            if (!Array.isArray(list) || list.length === 0) return null;
            const open = list.filter(x => (x.status || 'Pending') !== 'Completed');
            if (open.length === 0) return null;
            const accepted = open.filter(x => x.driver_accepted === true || x.status === 'In Progress' || x.status === 'Accepted');
            const pool = accepted.length ? accepted : open;
            pool.sort((a,b) => new Date(b.updated_at || b.created_at || 0) - new Date(a.updated_at || a.created_at || 0));
            return pool[0];
        };

        // Clear previous trails
        Object.values(driverToCaseTraces || {}).forEach(line => { try { map.removeLayer(line); } catch(_){} });
        driverToCaseTraces = {};

Object.keys(casesByAmb).forEach(async (ambId) => {
            const cur = pickCurrentCase(casesByAmb[ambId]);
            if (!cur) return;
            const lat = parseFloat(cur.latitude), lng = parseFloat(cur.longitude);
            const marker = driverMarkers[ambId];
            if (!marker || Number.isNaN(lat) || Number.isNaN(lng)) return;
            
            // Check if driver is actively navigating (has destination coordinates)
            const ambData = ambulanceDataMap[ambId];
            const isNavigating = ambData && ambData.destination_latitude && ambData.destination_longitude;
            
            // Only draw trail if driver is actively navigating to a pin
            if (isNavigating) {
                const driverLatLng = marker.getLatLng();
                if (!driverLatLng || Number.isNaN(driverLatLng.lat) || Number.isNaN(driverLatLng.lng)) return;
                const destLat = parseFloat(ambData.destination_latitude);
                const destLng = parseFloat(ambData.destination_longitude);
                
                if (!Number.isNaN(destLat) && !Number.isNaN(destLng)) {
                    // Draw an immediate straight fallback, then upgrade to routed when available
                    const fallback = L.polyline([[driverLatLng.lat, driverLatLng.lng],[destLat,destLng]], { color: '#ff8c42', weight: 4, opacity: 0.6, dashArray: '6,6', pane: 'trailsPane' }).addTo(map);
                    const routedTrail = await drawRoutedLine([driverLatLng.lat, driverLatLng.lng], [destLat, destLng], { color: '#ff8c42', weight: 6, opacity: 0.95 });
                    try { map.removeLayer(fallback); } catch(_){}
                    const caseKey = String(cur.case_num);
                    if (completedCaseNums && completedCaseNums.has(parseInt(caseKey))) {
                        try { map.removeLayer(routedTrail); } catch(_){}
                    } else {
                        driverToCaseTraces[caseKey] = routedTrail;
                    }
                }
            }
        });
    } catch (e) { /* no-op */ }
}

// Auto-refresh tracking
let autoRefreshPaused = false;
let lastUpdateTime = null;
let autoRefreshInterval = null;
let isActiveModeApplying = false; // guard to prevent race/double-apply for Active Cases
let lastPollOkAt = 0; // last time a full poll and refresh succeeded

// Visual indicator for updates (kept for manual refresh button if needed)
function showUpdateIndicator() {
    const indicator = document.getElementById('update-indicator');
    if (indicator) {
        indicator.classList.add('active');
        lastUpdateTime = new Date();
        setTimeout(() => {
            indicator.classList.remove('active');
        }, 2000);
    }
}

// Do not pause auto-refresh during interactions anymore
function pauseAutoRefresh() {}
function resumeAutoRefresh() {}

// Track map interactions to pause auto-refresh and disable auto-fit
let interactionTimeout = null;

map.on('zoomstart', function() {
    isUserInteracting = true;
    clearTimeout(interactionTimeout);
    console.log('🛑 User started zooming - pausing auto-refresh');
    pauseAutoRefresh();
});
map.on('dragstart', function() {
    isUserInteracting = true;
    clearTimeout(interactionTimeout);
    console.log('🛑 User started dragging - pausing auto-refresh');
    pauseAutoRefresh();
});
map.on('zoomend', function() {
    clearTimeout(interactionTimeout);
    console.log('✅ Zoom ended - will resume auto-refresh in 5 seconds');
    interactionTimeout = setTimeout(() => {
        isUserInteracting = false;
        resumeAutoRefresh();
        autoFitMapBounds(); // Re-enable auto-fit after interaction
    }, GPS_TIMING.INTERACTION_RESUME_DELAY_MS);
    try { refreshCaseStatuses(); } catch(_){}
});
map.on('dragend', function() {
    clearTimeout(interactionTimeout);
    console.log('✅ Drag ended - will resume auto-refresh in 5 seconds');
    interactionTimeout = setTimeout(() => {
        isUserInteracting = false;
        resumeAutoRefresh();
        autoFitMapBounds(); // Re-enable auto-fit after interaction
    }, GPS_TIMING.INTERACTION_RESUME_DELAY_MS);
    try { refreshCaseStatuses(); } catch(_){}
});

// Modified refresh function
async function performAutoRefresh() {
    
    console.log('🔄 Auto-refreshing GPS data...');
    
    // Show updating indicator
    const indicator = document.getElementById('update-indicator');
    console.log('📊 Indicator element found:', indicator);
    
    if (indicator) {
        console.log('✅ Adding active class to indicator');
        indicator.classList.add('active');
        
        const updateText = indicator.querySelector('.update-text');
        console.log('📝 Update text element:', updateText);
        
        if (updateText) {
            updateText.textContent = 'Updating...';
            console.log('✅ Set text to "Updating..."');
        } else {
            console.log('⚠️ Update text element not found');
        }
    } else {
        console.log('❌ Indicator element not found!');
    }
    
    try {
        await fetchAmbulanceData();
        loadAmbulanceCaseCounts();
        lastPollOkAt = Date.now();
        await Promise.resolve(refreshCaseStatuses());
        // After cases/trails updated, enforce visibility mode to prevent mismatch (trails without pins/markers)
        try { applyDriverVisibility(); } catch(_){ }
        // Ensure visibility rules applied after refresh
        try { applyDriverVisibility(); } catch(_){}
        
        console.log('✅ All data fetched successfully');
        
        // Show success indicator
        if (indicator) {
            const updateText = indicator.querySelector('.update-text');
            if (updateText) {
                updateText.textContent = 'Map updated successfully';
                console.log('✅ Set text to "Map updated successfully"');
            }
        }
        
        // If active modal is open, refresh its list
        const activeModal = document.getElementById('active-modal');
        if (activeModal && activeModal.style.display === 'flex') {
            loadActiveCases();
        }
    } catch (error) {
        console.error('❌ Error during auto-refresh:', error);
        if (indicator) {
            const updateText = indicator.querySelector('.update-text');
            if (updateText) {
                updateText.textContent = 'Update failed';
            }
        }
    }
    
    // Hide indicator after 3 seconds
    setTimeout(() => {
        if (indicator) {
            console.log('⏱️ Hiding indicator after 3 seconds');
            indicator.classList.remove('active');
        }
    }, 3000);
}

// ⏱ Start polling - 10 second interval
console.log('🚀 Starting GPS auto-refresh system...');
fetchAmbulanceData();
loadExistingCases();
loadAmbulanceCaseCounts();
// Draw initial driver → case routes shortly after initial data loads
setTimeout(() => { try { refreshCaseStatuses(); } catch(_){} }, 800);

console.log(`⏰ Setting up auto-refresh interval (${GPS_TIMING.POLL_INTERVAL_MS/1000} seconds)`);
autoRefreshInterval = setInterval(performAutoRefresh, GPS_TIMING.POLL_INTERVAL_MS);
console.log('✅ Auto-refresh interval started:', autoRefreshInterval);

// Update stale marker styles periodically to reflect "last seen" age
setInterval(updateStaleMarkers, GPS_TIMING.STALE_UPDATE_INTERVAL_MS);
console.log(`✅ Stale marker updates every ${GPS_TIMING.STALE_UPDATE_INTERVAL_MS/1000} seconds`);

// ===== GPS RESEND REQUEST SYSTEM =====
let offlineDriverNotifications = {}; // Track which drivers we've notified about (ambId -> timestamp)
const OFFLINE_NOTIFICATION_THRESHOLD_MIN = 3; // Notify admin if driver offline for 3+ minutes

// Admin: Send GPS resend request to driver
async function sendGPSResendRequest(ambulanceId) {
    try {
        const response = await fetch(`/admin/gps/resend-request/${ambulanceId}`, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        
        const result = await response.json();
        
        if (result.success) {
            showNotification(`📡 GPS resend request sent to driver`, 'success');
            console.log(`✅ GPS resend request sent to ambulance ${ambulanceId}`);
            
            // Add to active requests and start checking status
            activeResendRequests.add(ambulanceId);
            checkResendRequestStatus(ambulanceId);
        } else {
            showNotification(`❌ Failed to send request: ${result.message}`, 'error');
            console.error('❌ Failed to send GPS resend request:', result);
        }
    } catch (error) {
        console.error('❌ Error sending GPS resend request:', error);
        showNotification(`❌ Error sending request: ${error.message}`, 'error');
    }
}

// Admin: Check resend request status
let resendStatusCheckInterval = null;
let activeResendRequests = new Set(); // Track which ambulance IDs have active requests

async function checkResendRequestStatus(ambulanceId) {
    try {
        const response = await fetch(`/admin/gps/resend-status/${ambulanceId}`, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        });
        
        // Check if response is OK before parsing JSON
        if (!response.ok) {
            if (response.status === 404) {
                // Route not found - this shouldn't happen, but handle gracefully
                activeResendRequests.delete(ambulanceId);
                return;
            }
            // For other errors, try to parse but handle gracefully
            const text = await response.text();
            console.warn(`⚠️ Resend status check failed for ambulance ${ambulanceId}: ${response.status}`);
            return;
        }
        
        const result = await response.json();
        
        if (!result.success || !result.exists) {
            // Request expired or doesn't exist
            activeResendRequests.delete(ambulanceId);
            return;
        }
        
        const requestData = result.data;
        const status = requestData.status;
        
        // Track active requests
        if (status === 'pending' || status === 'acknowledged') {
            activeResendRequests.add(ambulanceId);
        }
        
        if (status === 'acknowledged' && !requestData.notified_ack) {
            showNotification(`✅ Driver acknowledged GPS resend request`, 'success');
            console.log(`✅ Driver acknowledged resend request for ambulance ${ambulanceId}`);
            // Mark as notified (would need backend update, but for now just show once)
        } else if (status === 'completed') {
            showNotification(`✅ Driver successfully sent location after resend request`, 'success');
            console.log(`✅ Driver completed resend request for ambulance ${ambulanceId}`);
            activeResendRequests.delete(ambulanceId);
        }
    } catch (error) {
        // Only log if it's not a network/parsing error for routes that don't exist
        if (error.name !== 'SyntaxError') {
            console.error('❌ Error checking resend request status:', error);
        }
        activeResendRequests.delete(ambulanceId);
    }
}

// Check for offline drivers (3+ minutes) and notify admin
function checkOfflineDrivers() {
    const now = Date.now();
    Object.keys(driverLastSeen).forEach(ambIdStr => {
        const ambId = parseInt(ambIdStr);
        const lastSeen = driverLastSeen[ambId];
        if (!lastSeen) return;
        
        const minutesSinceLastSeen = Math.floor((now - lastSeen) / 1000 / 60);
        
        // Only notify if offline for 3+ minutes and we haven't notified recently (within last 2 minutes)
        if (minutesSinceLastSeen >= OFFLINE_NOTIFICATION_THRESHOLD_MIN) {
            const lastNotification = offlineDriverNotifications[ambId];
            const minutesSinceNotification = lastNotification 
                ? Math.floor((now - lastNotification) / 1000 / 60) 
                : Infinity;
            
            // Notify if we haven't notified in the last 2 minutes (to avoid spam)
            if (minutesSinceNotification >= 2 || !lastNotification) {
                const driverName = driverMetaByAmbId[ambId]?.label || `Driver ${ambId}`;
                showNotification(`⚠️ ${driverName} has not sent location for ${minutesSinceLastSeen} minutes`, 'error');
                offlineDriverNotifications[ambId] = now;
                console.log(`⚠️ Notified admin: ${driverName} offline for ${minutesSinceLastSeen} minutes`);
            }
        }
    });
}

// Check for driver recovery (was offline, now back online)
function checkDriverRecovery() {
    Object.keys(offlineDriverNotifications).forEach(ambIdStr => {
        const ambId = parseInt(ambIdStr);
        const lastSeen = driverLastSeen[ambId];
        if (!lastSeen) return;
        
        const minutesSinceLastSeen = Math.floor((Date.now() - lastSeen) / 1000 / 60);
        
        // Driver is back online (seen within last 2 minutes)
        if (minutesSinceLastSeen < 2) {
            const lastNotification = offlineDriverNotifications[ambId];
            // Only notify recovery if we previously notified about offline status
            if (lastNotification) {
                const driverName = driverMetaByAmbId[ambId]?.label || `Driver ${ambId}`;
                showNotification(`✅ ${driverName} is back online and sending location`, 'success');
                console.log(`✅ ${driverName} recovered - back online`);
                delete offlineDriverNotifications[ambId];
            }
        }
    });
}

// Poll resend request status every 5 seconds (only for active requests)
setInterval(() => {
    // Only check status for ambulance IDs that have active requests
    activeResendRequests.forEach(ambulanceId => {
        checkResendRequestStatus(ambulanceId);
    });
}, 5000);

// Check offline drivers every 30 seconds
setInterval(checkOfflineDrivers, 30000);
setInterval(checkDriverRecovery, 30000);

// ===== RE-DEPLOYMENT SYSTEM =====

let currentRedeploymentCase = null;
let lastNotificationCheck = 0;
let casesNeedingRedeployment = new Set(); // Track cases that need re-deployment

// Check for cases needing re-deployment every 30 seconds
setInterval(checkForRedeploymentNeeds, GPS_TIMING.REDEPLOYMENT_CHECK_INTERVAL_MS);

// Check for recent driver actions every 10 seconds
setInterval(checkRecentDriverActions, GPS_TIMING.RECENT_ACTIONS_CHECK_INTERVAL_MS);

// Check for completed cases every 2 seconds (more frequent for real-time sync)
setInterval(checkForCompletedCases, 2000);

// Initial checks when page loads
console.log('🚀 Re-deployment system initialized');
setTimeout(() => {
    console.log('🔍 Running initial checks...');
    checkForRedeploymentNeeds();
    checkRecentDriverActions();
    checkForCompletedCases();
}, 2000);

async function checkForRedeploymentNeeds() {
    console.log('🔍 Checking for cases needing redeployment...');
    try {
        const response = await fetch('/admin/cases/needing-redeployment');
        console.log('📡 Redeployment API response status:', response.status);
        
        if (!response.ok) {
            console.error('❌ Redeployment API error:', response.status, response.statusText);
            return;
        }
        
        const redeploymentList = await response.json();
        console.log('📋 Cases needing redeployment:', redeploymentList);
        
        // Update the global set of cases needing re-deployment
        casesNeedingRedeployment.clear();
        redeploymentList.forEach(item => {
            casesNeedingRedeployment.add(item.case.case_num);
        });
        
        // Update case marker icons to show re-deployment flags
        updateCaseMarkersForRedeployment();
        
        if (redeploymentList.length > 0) {
            // Show re-deployment modal for the first case needing attention
            showRedeploymentModal(redeploymentList[0]);
        }
    } catch (error) {
        console.error('Error checking redeployment needs:', error);
    }
}

async function checkRecentDriverActions() {
    console.log('🔍 Checking for recent driver actions...');
    try {
        const response = await fetch('/admin/cases/recent-actions');
        console.log('📡 Recent actions API response status:', response.status);
        
        if (!response.ok) {
            console.error('❌ Recent actions API error:', response.status, response.statusText);
            return;
        }
        
        const recentActions = await response.json();
        console.log('📋 Recent driver actions:', recentActions);
        
        // Show notifications for new actions
        let shouldRefreshCases = false;
        recentActions.forEach(action => {
            if (action.timestamp > lastNotificationCheck) {
                showDriverActionNotification(action);
                // If case was accepted/rejected, might need to refresh case markers
                if (action.action === 'accepted') {
                    shouldRefreshCases = true;
                }
            }
        });
        
        // If any actions detected, refresh case statuses immediately
        if (shouldRefreshCases) {
            console.log('🔄 Refreshing case statuses due to driver action...');
            try { await refreshCaseStatuses(); } catch(e) { console.error('Error refreshing cases:', e); }
        }
        
        lastNotificationCheck = Date.now();
    } catch (error) {
        console.error('Error checking recent actions:', error);
    }
}

// ===== DATA CONSISTENCY VERIFICATION SYSTEM =====
/**
 * Verifies case status from server to ensure data consistency across all admins
 * @param {string|number|Array} caseNumOrNums - Single case number or array of case numbers to verify
 * @returns {Promise<Object>} - Object with case numbers as keys and their verified status
 */
async function verifyCaseStatus(caseNumOrNums) {
    try {
        // Safety check: ensure input is valid
        if (caseNumOrNums === null || caseNumOrNums === undefined) {
            console.warn('⚠️ verifyCaseStatus called with null/undefined');
            return {};
        }
        
        const response = await fetch('/admin/cases', {
            method: 'GET',
            cache: 'no-store',
            credentials: 'same-origin',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
            }
        });
        
        if (!response.ok) {
            console.error('❌ Failed to verify case status: HTTP', response.status);
            return {};
        }
        
        const allCases = await response.json();
        if (!Array.isArray(allCases)) {
            console.error('❌ Invalid response format for case verification');
            return {};
        }
        
        // Convert single case number to array for uniform processing
        const caseNumsToCheck = Array.isArray(caseNumOrNums) ? caseNumOrNums : [caseNumOrNums];
        
        // Filter out any null/undefined values
        const validCaseNums = caseNumsToCheck.filter(cn => cn !== null && cn !== undefined);
        if (validCaseNums.length === 0) {
            console.warn('⚠️ No valid case numbers to verify');
            return {};
        }
        const verifiedStatus = {};
        
        validCaseNums.forEach(caseNum => {
            const caseStr = String(caseNum);
            const caseData = allCases.find(c => String(c.case_num) === caseStr);
            
            if (caseData) {
                verifiedStatus[caseStr] = {
                    case_num: caseData.case_num,
                    status: caseData.status || 'Unknown',
                    isCompleted: caseData.status === 'Completed',
                    verified: true,
                    timestamp: new Date().toISOString()
                };
            } else {
                // Case not found - might be deleted or doesn't exist
                verifiedStatus[caseStr] = {
                    case_num: caseNum,
                    status: 'Not Found',
                    isCompleted: false,
                    verified: false,
                    timestamp: new Date().toISOString()
                };
            }
        });
        
        return verifiedStatus;
    } catch (error) {
        console.error('❌ Error verifying case status:', error);
        return {};
    }
}

/**
 * Verifies and syncs UI state with server state for a specific case
 * @param {string|number} caseNum - Case number to verify and sync
 * @param {boolean} forceSync - If true, force UI update even if status matches
 */
async function verifyAndSyncCase(caseNum, forceSync = false) {
    const caseStr = String(caseNum);
    console.log(`🔍 Verifying case #${caseStr} status from server...`);
    
    const verified = await verifyCaseStatus(caseNum);
    const caseInfo = verified[caseStr];
    
    if (!caseInfo || !caseInfo.verified) {
        console.warn(`⚠️ Case #${caseStr} not found or could not be verified`);
        return false;
    }
    
    const isCompleted = caseInfo.isCompleted;
    const currentStatus = caseInfo.status;
    
    console.log(`✅ Verified: Case #${caseStr} status = "${currentStatus}" (Completed: ${isCompleted})`);
    
    // Check if UI state matches server state
    const markerExists = caseMarkers && caseMarkers[caseStr];
    const modalOpen = currentGeofenceNotification && 
                     String(currentGeofenceNotification.case_num) === caseStr &&
                     document.getElementById('geofence-notification-modal')?.style.display !== 'none';
    
    // If case is completed but still visible in UI, sync it
    if (isCompleted) {
        if (markerExists || modalOpen || forceSync) {
            console.log(`🔄 Syncing UI: Removing completed case #${caseStr} from display`);
            
            // Close geofence modal if open for this case
            if (modalOpen && currentGeofenceNotification) {
                const modal = document.getElementById('geofence-notification-modal');
                if (modal) {
                    modal.style.display = 'none';
                    if (typeof acknowledgeGeofenceNotification === 'function') {
                        const locationType = currentGeofenceNotification.location_type || 'destination';
                        acknowledgeGeofenceNotification(caseStr, locationType).catch(err => {
                            console.error('Error acknowledging notification:', err);
                        });
                    }
                    currentGeofenceNotification = null;
                }
            }
            
            removeCaseFromMap(caseStr);
            
            // Refresh case lists
            try {
                await loadActiveCases();
                await loadExistingCases();
            } catch(e) {
                console.error('Error refreshing cases:', e);
            }
            
            return true; // Successfully synced
        }
    }
    
    return true; // Already in sync or case is active
}

// Quick check for newly completed cases (runs more frequently)
let lastCompletedCaseCheck = 0;
async function checkForCompletedCases() {
    try {
        const response = await fetch('/admin/cases', {
            method: 'GET',
            cache: 'no-store',
            credentials: 'same-origin',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
            }
        });
        
        if (!response.ok) return;
        
        const allCases = await response.json();
        if (!Array.isArray(allCases)) return;
        
        // Check if current geofence modal is showing a completed case
        if (currentGeofenceNotification && currentGeofenceNotification.case_num) {
            const modalCaseNum = currentGeofenceNotification.case_num;
            const modalCaseData = allCases.find(c => String(c.case_num) === String(modalCaseNum));
            if (modalCaseData && modalCaseData.status === 'Completed') {
                // Close the geofence modal if the case is completed
                console.log(`🔒 Closing geofence modal - Case #${modalCaseNum} has been completed`);
                const modal = document.getElementById('geofence-notification-modal');
                if (modal && modal.style.display !== 'none') {
                    modal.style.display = 'none';
                    // Acknowledge the notification (fire and forget - don't await)
                    if (typeof acknowledgeGeofenceNotification === 'function') {
                        acknowledgeGeofenceNotification(modalCaseNum, currentGeofenceNotification.location_type || 'destination').catch(err => {
                            console.error('Error acknowledging notification:', err);
                        });
                    }
                    currentGeofenceNotification = null;
                }
            }
        }
        
        // Check if any currently visible case markers are now completed
        let foundCompleted = false;
        if (!caseMarkers || typeof caseMarkers !== 'object') {
            return; // Safety check: caseMarkers not initialized yet
        }
        if (!map || typeof map.hasLayer !== 'function') {
            return; // Safety check: map not initialized yet
        }
        
        // Use verification system to ensure consistency
        // Extract only numeric case numbers (filter out dest_ and other prefixed keys)
        const visibleCaseNums = Object.keys(caseMarkers)
            .filter(key => /^\d+$/.test(key)) // Only numeric keys (case numbers)
            .map(key => String(key));
        if (visibleCaseNums.length > 0) {
            // Verify all visible cases for consistency
            const verifiedStatuses = await verifyCaseStatus(visibleCaseNums);
            
            // Process all completed cases
            const syncPromises = [];
            for (const caseNum of visibleCaseNums) {
                const verified = verifiedStatuses[caseNum];
                if (verified && verified.isCompleted) {
                    // Use verifyAndSyncCase to ensure proper cleanup
                    syncPromises.push(
                        verifyAndSyncCase(caseNum, false).catch(err => {
                            console.error(`Error syncing case #${caseNum}:`, err);
                            return false;
                        })
                    );
                }
            }
            
            // Wait for all sync operations to complete and check results
            if (syncPromises.length > 0) {
                const syncResults = await Promise.all(syncPromises);
                // If any sync was successful, mark as found
                foundCompleted = syncResults.some(result => result === true);
            }
        }
        
        // If we found and removed completed cases, trigger full refresh to update trails and counters
        if (foundCompleted) {
            console.log('🔄 Triggering full case status refresh after detecting completed cases...');
            try { 
                await refreshCaseStatuses(); 
                // Also refresh active cases count
                try { await loadActiveCases(); } catch(e) {}
            } catch(e) { 
                console.error('Error refreshing cases:', e); 
            }
        }
        
        lastCompletedCaseCheck = Date.now();
    } catch (error) {
        console.error('Error checking for completed cases:', error);
    }
}

function showDriverActionNotification(action) {
    const panel = document.getElementById('driver-actions-panel');
    
    const notification = document.createElement('div');
    notification.style.cssText = `
        background: ${action.action === 'accepted' ? '#10b981' : '#ef4444'};
        color: white;
        padding: 12px 16px;
        border-radius: 8px;
        margin-bottom: 10px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        animation: slideInRight 0.3s ease-out;
        cursor: pointer;
    `;
    
    notification.innerHTML = `
        <div style="font-weight: 600; font-size: 14px;">
            ${action.action === 'accepted' ? '✅' : '❌'} Case ${action.action.toUpperCase()}
        </div>
        <div style="font-size: 12px; opacity: 0.9; margin-top: 2px;">
            Case #${action.case_num} - ${action.case_name}
        </div>
        <div style="font-size: 11px; opacity: 0.8; margin-top: 2px;">
            Driver: ${action.driver_name} (Ambulance #${action.ambulance_id})
        </div>
    `;
    
    // Auto-remove after 5 seconds
    setTimeout(() => {
        notification.style.animation = 'slideOutRight 0.3s ease-in';
        setTimeout(() => notification.remove(), 300);
    }, 5000);
    
    // Click to dismiss
    notification.onclick = () => {
        notification.style.animation = 'slideOutRight 0.3s ease-in';
        setTimeout(() => notification.remove(), 300);
    };
    
    panel.appendChild(notification);
}

async function showRedeploymentModal(redeploymentData) {
    currentRedeploymentCase = redeploymentData.case;
    
    // Build rejection history
    let rejectionHistory = '';
    if (redeploymentData.rejections && redeploymentData.rejections.length > 0) {
        rejectionHistory = `
            <div style="background: #fef2f2; border-left: 3px solid #ef4444; border-radius: 6px; padding: 12px; margin-top: 14px;">
                <div style="display: flex; align-items: center; gap: 6px; margin-bottom: 10px;">
                    <i class="fas fa-times-circle" style="color: #ef4444; font-size: 13px;"></i>
                    <span style="color: #dc2626; font-size: 12px; font-weight: 600;">Drivers who rejected this case:</span>
                </div>
                ${redeploymentData.rejections.map(rejection => `
                    <div style="font-size: 11px; color: #4b5563; margin-left: 19px; margin-bottom: 6px; line-height: 1.5;">
                        <span style="color: #ef4444; margin-right: 6px;">•</span>
                        <strong style="color: #111827;">${rejection.driver_name}</strong> (Ambulance #${rejection.ambulance_id})
                        <span style="color: #9ca3af; margin-left: 6px;">${new Date(rejection.rejected_at).toLocaleString()}</span>
                    </div>
                `).join('')}
            </div>
        `;
    }
    
    // Populate case info
    document.getElementById('redeployment-case-info').innerHTML = `
        <div style="background: #fef2f2; border: 1px solid #fee2e2; border-radius: 8px; padding: 16px; margin-bottom: 0;">
            <div style="display: flex; align-items: start; gap: 8px; margin-bottom: 14px;">
                <i class="fas fa-exclamation-triangle" style="color: #f59e0b; font-size: 16px; margin-top: 2px; flex-shrink: 0;"></i>
                <span style="color: #dc2626; font-size: 13px; font-weight: 600; line-height: 1.4;">All assigned drivers have rejected this case</span>
            </div>
            <div style="background: white; border-radius: 6px; padding: 14px; margin-bottom: ${rejectionHistory ? '0' : '0'};">
                <div style="margin-bottom: 10px; padding-bottom: 10px; border-bottom: 1px solid #f3f4f6;">
                    <div style="font-size: 11px; color: #6b7280; margin-bottom: 4px; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 600;">Case</div>
                    <div style="font-size: 14px; color: #111827; font-weight: 600;">Case #${redeploymentData.case.case_num}: ${redeploymentData.case.name}</div>
                </div>
                <div style="display: grid; gap: 10px;">
                    <div>
                        <div style="font-size: 11px; color: #6b7280; margin-bottom: 3px; font-weight: 500;">Type</div>
                        <div style="font-size: 12px; color: #374151;">${redeploymentData.case.type}</div>
                    </div>
                    <div>
                        <div style="font-size: 11px; color: #6b7280; margin-bottom: 3px; font-weight: 500;">Location</div>
                        <div style="font-size: 12px; color: #374151; line-height: 1.4;">${redeploymentData.case.address}</div>
                    </div>
                    ${redeploymentData.case.to_go_to_address ? `
                    <div>
                        <div style="font-size: 11px; color: #6b7280; margin-bottom: 3px; font-weight: 500;">Destination</div>
                        <div style="font-size: 12px; color: #374151; line-height: 1.4;">${redeploymentData.case.to_go_to_address}</div>
                    </div>
                    ` : ''}
                    <div>
                        <div style="font-size: 11px; color: #6b7280; margin-bottom: 3px; font-weight: 500;">Contact</div>
                        <div style="font-size: 12px; color: #374151;">${redeploymentData.case.contact}</div>
                    </div>
                </div>
            </div>
            ${rejectionHistory}
        </div>
    `;
    
    // Load ambulances for selection
    await loadAmbulancesForRedeployment();
    
    document.getElementById('redeployment-modal').style.display = 'flex';
}

async function loadAmbulancesForRedeployment() {
    console.log('🚑 Loading ambulances for redeployment...');
    try {
        const response = await fetch('/admin/cases/ambulances-for-redeployment');
        console.log('📡 Ambulances API response status:', response.status);
        
        if (!response.ok) {
            console.error('❌ Ambulances API error:', response.status, response.statusText);
            return;
        }
        
        const ambulances = await response.json();
        console.log('🚑 Ambulances data:', ambulances);
        
        const container = document.getElementById('redeployment-ambulance-list');
        container.innerHTML = '';
        
        ambulances.forEach(ambulance => {
            const card = document.createElement('div');
            card.style.cssText = `
                display: flex;
                align-items: center;
                padding: 12px 14px;
                border: 1.5px solid #e5e7eb;
                border-radius: 8px;
                margin-bottom: 10px;
                cursor: pointer;
                transition: all 0.2s;
                background: white;
            `;
            
            const driverName = ambulance.driver ? 
                (typeof ambulance.driver === 'string' ? ambulance.driver : ambulance.driver.name) : 
                'No Driver';
            
            const statusColor = ambulance.status === 'Available' ? '#10b981' : '#6b7280';
            
            card.innerHTML = `
                <input type="checkbox" id="redeploy-ambulance-${ambulance.id}" value="${ambulance.id}" style="margin-right: 12px; width: 16px; height: 16px; cursor: pointer; accent-color: #3b82f6;">
                <label for="redeploy-ambulance-${ambulance.id}" style="cursor: pointer; flex: 1; display: block;">
                    <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 4px;">
                        <span style="font-size: 13px; font-weight: 600; color: #111827;">Ambulance #${ambulance.id}</span>
                        <span style="font-size: 12px; color: #374151;">${driverName}</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 8px; font-size: 11px;">
                        <span style="color: ${statusColor}; font-weight: 500;">${ambulance.status}</span>
                        <span style="color: #9ca3af;">•</span>
                        <span style="color: #6b7280;">Cases: ${ambulance.case_count}</span>
                    </div>
                </label>
            `;
            
            card.onclick = (e) => {
                if (e.target.type !== 'checkbox') {
                    const cb = card.querySelector('input[type="checkbox"]');
                    cb.checked = !cb.checked;
                }
                
                const isChecked = card.querySelector('input').checked;
                card.style.background = isChecked ? '#eff6ff' : 'white';
                card.style.borderColor = isChecked ? '#3b82f6' : '#e5e7eb';
                card.style.boxShadow = isChecked ? '0 2px 4px rgba(59, 130, 246, 0.1)' : 'none';
            };
            
            container.appendChild(card);
        });
        
    } catch (error) {
        console.error('Error loading ambulances:', error);
    }
}

async function executeRedeployment() {
    if (!currentRedeploymentCase) return;
    
    const selectedAmbulances = Array.from(document.querySelectorAll('#redeployment-ambulance-list input[type="checkbox"]:checked'))
        .map(cb => parseInt(cb.value));
    
    if (selectedAmbulances.length === 0) {
        showInlineNotice('Please select at least one ambulance for re-deployment.', {
            title: 'Selection Required',
            type: 'warning'
        });
        return;
    }
    
    try {
        const response = await fetch(`/admin/cases/${currentRedeploymentCase.case_num}/redeploy`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                ambulance_ids: selectedAmbulances
            })
        });
        
        const result = await response.json();
        
        if (result.success) {
            showInlineNotice(`Case #${currentRedeploymentCase.case_num} has been re-deployed to ${selectedAmbulances.length} ambulance(s).`, {
                title: 'Re-deployment Success',
                type: 'success'
            });
            
            // Remove from re-deployment list
            casesNeedingRedeployment.delete(currentRedeploymentCase.case_num);
            
            closeRedeploymentModal();
            
            // Refresh the map and case data
            loadActiveCases();
            updateAmbulanceDropdown();
            updateCaseMarkersForRedeployment();
        } else {
            showInlineNotice('Error re-deploying case: ' + result.message, {
                title: 'Re-deployment Failed',
                type: 'danger'
            });
        }
        
    } catch (error) {
        console.error('Error executing redeployment:', error);
        showInlineNotice('Error re-deploying case. Please try again.', {
            title: 'Re-deployment Failed',
            type: 'danger'
        });
    }
}

function closeRedeploymentModal() {
    document.getElementById('redeployment-modal').style.display = 'none';
    currentRedeploymentCase = null;
}

function updateCaseMarkersForRedeployment() {
    // Update all existing case markers to reflect re-deployment status
    Object.keys(caseMarkers).forEach(caseNum => {
        const marker = caseMarkers[caseNum];
        if (marker && marker.caseData) {
            const caseData = marker.caseData;
            const isActive = (caseData.driver_accepted === true || caseData.status === 'In Progress' || caseData.status === 'Accepted');
            const newIcon = createCaseIcon(caseData.case_num || caseNum, isActive);
            marker.setIcon(newIcon);
            
            // Update tooltip to show re-deployment status
            if (casesNeedingRedeployment.has(parseInt(caseNum))) {
                marker.setTooltipContent(`🚨 Case #${caseNum} - NEEDS RE-DEPLOYMENT`);
            } else {
                marker.setTooltipContent(`Case #${caseNum}`);
            }
        }
    });
}

// Add CSS animations
const style = document.createElement('style');
style.textContent = `
    @keyframes slideInRight {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
    
    @keyframes slideOutRight {
        from { transform: translateX(0); opacity: 1; }
        to { transform: translateX(100%); opacity: 0; }
    }
    
    @keyframes pulse {
        0% { transform: scale(1); opacity: 1; }
        50% { transform: scale(1.1); opacity: 0.7; }
        100% { transform: scale(1); opacity: 1; }
    }
    
    .case-needs-redeployment {
        animation: pulse 2s infinite;
        filter: drop-shadow(0 0 8px #dc2626);
    }
    
    .modal {
        display: none;
        position: fixed;
        z-index: 3000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0,0,0,0.5);
        align-items: center;
        justify-content: center;
    }
    
    .modal-content {
        background-color: white;
        padding: 0;
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        max-width: 90%;
        max-height: 90%;
        overflow-y: auto;
    }
    
    .modal-header {
        padding: 20px 24px;
        border-bottom: 1px solid #e5e7eb;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .modal-body {
        padding: 24px;
    }
    
    .modal-actions {
        display: flex;
        gap: 12px;
        justify-content: flex-end;
        margin-top: 24px;
        padding-top: 16px;
        border-top: 1px solid #e5e7eb;
    }
    
    .btn {
        padding: 10px 20px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-weight: 600;
        transition: all 0.2s;
    }
    
    .btn-primary {
        background: #3b82f6;
        color: white;
    }
    
    .btn-primary:hover {
        background: #2563eb;
    }
    
    .btn-secondary {
        background: #e5e7eb;
        color: #374151;
    }
    
    .btn-secondary:hover {
        background: #d1d5db;
    }
    
    .close {
        font-size: 24px;
        font-weight: bold;
        cursor: pointer;
        color: #6b7280;
    }
    
    .close:hover {
        color: #374151;
    }
`;
document.head.appendChild(style);

function applyDriverSelection(id){
    currentFilterDriverId = id || 'all';
    // Normalize 'amb-{id}' to plain ambulance id mapping when comparing later
    if (typeof currentFilterDriverId === 'string' && currentFilterDriverId.startsWith('amb-')) {
        currentFilterDriverId = currentFilterDriverId; // keep as is; we'll handle in comparisons
    }
    // Refresh data and render trails/cases to reflect selected driver
    try { fetchAmbulanceData(); } catch(_){ }
    if (typeof loadExistingCases === 'function') { try { loadExistingCases(); } catch(_){} }
    if (typeof refreshCaseStatuses === 'function') { try { refreshCaseStatuses(); } catch(_){} }
    try { applyDriverVisibility(); } catch(_){}
}

// Wire legacy quick results helpers to current search elements (guarded)
var quickInput = document.getElementById('quick-driver-input');
var quickResults = document.getElementById('quick-driver-results');
var panel = document.getElementById('quick-driver-form') || document.getElementById('driver-quick-panel');

// Move quick results to body to escape map stacking context, and use fixed positioning
function ensureQuickResultsLayer(){
    if (!quickResults) return;
    if (!quickResults.__movedToBody){
        try {
            document.body.appendChild(quickResults);
            quickResults.__movedToBody = true;
            quickResults.style.position = 'fixed';
            quickResults.style.zIndex = '10020';
            quickResults.style.pointerEvents = 'auto';
        } catch(_){ }
    }
}

function positionQuickResults(){
    if (!quickInput || !quickResults || quickResults.style.display === 'none') return;
    try {
        const rect = quickInput.getBoundingClientRect();
        quickResults.style.left = rect.left + 'px';
        quickResults.style.top = (rect.bottom + 4) + 'px';
        quickResults.style.width = rect.width + 'px';
        quickResults.style.maxHeight = '240px';
    } catch(_){ }
}

    function renderQuickResults(items){
        if (!quickResults) return;
        if (!items || items.length === 0){ quickResults.style.display = 'none'; quickResults.innerHTML = ''; return; }
        ensureQuickResultsLayer();
        quickResults.innerHTML = '';
        items.forEach(d => {
            const row = document.createElement('div');
            row.style.cssText = 'padding: 8px 10px; cursor: pointer; border-bottom: 1px solid #f3f4f6; background:#fff;';
            row.dataset.id = d.id;
            row.textContent = d.label;
            row.addEventListener('mouseenter', () => row.style.background = '#f8fafc');
            row.addEventListener('mouseleave', () => row.style.background = '#ffffff');
            row.addEventListener('click', (ev) => {
                ev.preventDefault(); ev.stopPropagation();
                currentFilterDriverId = String(d.id);
                try {
                    const radio = panel.querySelector(`input[name=\\"ambFilter\\"][value=\\"${d.id}\\"]`);
                    if (radio) radio.checked = true;
                } catch(_){ }
                try { fetchAmbulanceData(); } catch(_){ }
                if (typeof loadExistingCases === 'function') { try { loadExistingCases(); } catch(_){} }
                if (typeof refreshCaseStatuses === 'function') { try { refreshCaseStatuses(); } catch(_){} }
                quickResults.style.display = 'none';
            });
            quickResults.appendChild(row);
        });
        quickResults.style.display = 'block';
        positionQuickResults();
    }

    quickInput?.addEventListener('input', function(e){
        const term = (e.target.value || '').toLowerCase().trim();
        if (!term){ renderQuickResults([]); return; }
        const matches = (allDriversForFilter || []).filter(d => {
            const lbl = (d.label || '').toLowerCase();
            const idStr = String(d.id || '').toLowerCase();
        return lbl.includes(term) || idStr.includes(term);
        }).slice(0, 20);
        renderQuickResults(matches);
        positionQuickResults();
    });

    quickInput?.addEventListener('focus', function(){ ensureQuickResultsLayer(); positionQuickResults(); });
    window.addEventListener('resize', positionQuickResults);
    window.addEventListener('scroll', positionQuickResults, true);

    document.addEventListener('click', function(e){
        if (!panel || !quickResults) return;
        const within = panel.contains(e.target);
        if (!within) { quickResults.style.display = 'none'; }
    });

    // Hide suggestions when panel closes
    function closePanel(){ panel.style.display = 'none'; if (quickResults) quickResults.style.display = 'none'; }

</script>

<!-- Re-deployment Modal -->
<div id="redeployment-modal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.4); z-index: 10000; align-items: center; justify-content: center; backdrop-filter: blur(2px);">
    <div style="background: #ffffff; width: 90%; max-width: 520px; border-radius: 12px; box-shadow: 0 8px 32px rgba(0,0,0,0.12), 0 2px 8px rgba(0,0,0,0.08); position: relative; overflow: hidden; max-height: 90vh; display: flex; flex-direction: column;">
        <div style="background: #1e40af; padding: 12px 18px; display: flex; align-items: center; gap: 10px;">
            <i class="fas fa-ambulance" style="color: #fef2f2; font-size: 18px;"></i>
            <span style="color: white; font-size: 13px; font-weight: 600; letter-spacing: 0.5px; text-transform: uppercase; flex: 1;">Case Re-deployment Required</span>
            <span onclick="closeRedeploymentModal()" style="color: rgba(255,255,255,0.85); font-size: 20px; cursor: pointer; line-height: 1; width: 20px; height: 20px; display: flex; align-items: center; justify-content: center; transition: all 0.2s; border-radius: 4px;" onmouseover="this.style.background='rgba(255,255,255,0.15)'; this.style.color='white'" onmouseout="this.style.background='transparent'; this.style.color='rgba(255,255,255,0.85)'">&times;</span>
        </div>
        <div style="padding: 20px; overflow-y: auto; flex: 1;">
            <div id="redeployment-case-info"></div>
            <div style="margin-top: 20px;">
                <div style="font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 12px; letter-spacing: 0.3px;">Select Ambulances for Re-deployment:</div>
                <div id="redeployment-ambulance-list"></div>
            </div>
        </div>
        <div style="padding: 16px 20px; border-top: 1px solid #f1f5f9; display: flex; gap: 10px; justify-content: flex-end;">
            <button onclick="closeRedeploymentModal()" style="padding: 9px 18px; background: #f3f4f6; color: #374151; border: none; border-radius: 6px; font-weight: 500; cursor: pointer; font-size: 12px; transition: all 0.15s;" onmouseover="this.style.background='#e5e7eb'" onmouseout="this.style.background='#f3f4f6'">Cancel</button>
            <button onclick="executeRedeployment()" style="padding: 9px 18px; background: #3b82f6; color: white; border: none; border-radius: 6px; font-weight: 500; cursor: pointer; font-size: 12px; transition: all 0.15s; display: flex; align-items: center; gap: 6px;" onmouseover="this.style.background='#2563eb'" onmouseout="this.style.background='#3b82f6'">
                <i class="fas fa-paper-plane" style="font-size: 11px;"></i>
                Re-deploy Case
            </button>
        </div>
    </div>
</div>

<!-- Driver Actions Notification Panel -->
<div id="driver-actions-panel" style="position: fixed; top: 20px; right: 20px; z-index: 2000; max-width: 350px;">
    <!-- Notifications will be dynamically added here -->
</div>


<!-- //================================================================================================== -->
<script>
// Base marker and hospitals are now loaded via addBaseMarker() and loadHospitals() functions above
// The old logo marker code has been replaced with the enhanced base marker functionality

// ===== Super Admin Presence System =====
(function() {
    const banner = document.getElementById('superadmin-banner');
    const superAdminEmails = [
        'jaymarkroce@superadmin.com',
        'princenipaya@superadmin.com',
        'ahlencorpuz@superadmin.com'
    ];
    
    // Check if current user is super admin
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
                // Adjust body padding to account for banner
                document.body.style.paddingTop = '46px';
            } else {
                banner.style.display = 'none';
                document.body.style.paddingTop = '0';
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

// ===== GEOFENCING SYSTEM =====
let currentGeofenceNotification = null;
let acknowledgedNotifications = new Set();

// Function to play notification sound
function playGeofenceSound() {
    try {
        const audioContext = new (window.AudioContext || window.webkitAudioContext)();
        const oscillator = audioContext.createOscillator();
        const gainNode = audioContext.createGain();
        
        oscillator.connect(gainNode);
        gainNode.connect(audioContext.destination);
        
        oscillator.frequency.value = 800;
        oscillator.type = 'sine';
        
        gainNode.gain.setValueAtTime(0.3, audioContext.currentTime);
        gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.5);
        
        oscillator.start(audioContext.currentTime);
        oscillator.stop(audioContext.currentTime + 0.5);
        
        // Play 3 beeps
        setTimeout(() => {
            const osc2 = audioContext.createOscillator();
            const gain2 = audioContext.createGain();
            osc2.connect(gain2);
            gain2.connect(audioContext.destination);
            osc2.frequency.value = 800;
            osc2.type = 'sine';
            gain2.gain.setValueAtTime(0.3, audioContext.currentTime);
            gain2.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.5);
            osc2.start(audioContext.currentTime);
            osc2.stop(audioContext.currentTime + 0.5);
        }, 600);
        
        setTimeout(() => {
            const osc3 = audioContext.createOscillator();
            const gain3 = audioContext.createGain();
            osc3.connect(gain3);
            gain3.connect(audioContext.destination);
            osc3.frequency.value = 800;
            osc3.type = 'sine';
            gain3.gain.setValueAtTime(0.3, audioContext.currentTime);
            gain3.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.5);
            osc3.start(audioContext.currentTime);
            osc3.stop(audioContext.currentTime + 0.5);
        }, 1200);
    } catch (e) {
        console.error('Error playing sound:', e);
    }
}

// Function to show geofence notification modal
function showGeofenceNotification(notification) {
    const notificationKey = `${notification.case_num}_${notification.location_type}`;
    if (acknowledgedNotifications.has(notificationKey)) {
        return; // Already acknowledged
    }
    
    currentGeofenceNotification = notification;
    const modal = document.getElementById('geofence-notification-modal');
    const content = document.getElementById('geofence-notification-content');
    
    if (!modal || !content) return;
    
    const locationType = notification.location_type || 'destination';
    const locationLabel = locationType === 'pickup' ? 'Patient' : 'Destination';
    const locationIcon = locationType === 'pickup' ? 'fa-user-injured' : 'fa-flag-checkered';
    
    // Update modal title
    const modalTitle = document.getElementById('geofence-modal-title');
    if (modalTitle) {
        if (locationType === 'pickup') {
            modalTitle.innerHTML = `<i class="fas ${locationIcon}" style="font-size: 1.1rem; color:#4ade80;"></i> Driver Has Reached the Patient`;
        } else {
            modalTitle.innerHTML = `<i class="fas ${locationIcon}" style="font-size: 1.1rem; color:#4ade80;"></i> Driver Has Reached Destination`;
        }
    }
    
    // Show/hide complete button based on location type
    const completeBtn = document.getElementById('geofence-complete-btn');
    if (completeBtn) {
        if (locationType === 'destination') {
            completeBtn.style.display = 'flex';
        } else {
            completeBtn.style.display = 'none';
        }
    }
    
    content.innerHTML = `
        <div style="text-align: center;">
            <div style="width: 56px; height: 56px; background: linear-gradient(135deg, #10b981, #059669); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto .75rem; box-shadow: 0 6px 16px rgba(16, 185, 129, 0.35);">
                <i class="fas ${locationIcon}" style="font-size: 1.6rem; color: white;"></i>
            </div>
            <h4 style="margin: 0 0 .5rem 0; color: #1e293b; font-size: 1rem; font-weight: 800;">
                ${locationType === 'pickup' ? 'Driver reached the patient' : 'Driver reached destination'}
            </h4>
            <div style="background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 10px; padding: .75rem; margin-bottom: .75rem; text-align:left;">
                <div style="margin-bottom: .5rem;">
                    <div style="font-size: .78rem; color: #6b7280; font-weight: 700; margin-bottom: .15rem;">DRIVER</div>
                    <div style="font-size: .95rem; color: #1e293b; font-weight: 800;">${notification.driver_name || 'Unknown'}</div>
                </div>
                <div style="margin-bottom: .5rem;">
                    <div style="font-size: .78rem; color: #6b7280; font-weight: 700; margin-bottom: .15rem;">AMBULANCE</div>
                    <div style="font-size: .95rem; color: #1e293b; font-weight: 800;">${notification.ambulance_name || 'Unknown'}</div>
                </div>
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:.5rem;">
                    <div>
                        <div style="font-size: .78rem; color: #6b7280; font-weight: 700; margin-bottom: .15rem;">CASE #</div>
                        <div style="font-size: .95rem; color: #1e293b; font-weight: 800;">#${notification.case_num}</div>
                    </div>
                    <div>
                        <div style="font-size: .78rem; color: #6b7280; font-weight: 700; margin-bottom: .15rem;">TYPE</div>
                        <div style="font-size: .95rem; color: #1e293b; font-weight: 800;">${notification.case_type || 'N/A'}</div>
                    </div>
                </div>
                <div style="margin-top: .5rem; padding-top: .5rem; border-top: 1px solid #bbf7d0;">
                    <div style="font-size: .78rem; color: #6b7280; font-weight: 700; margin-bottom: .15rem;">DISTANCE</div>
                    <div style="font-size: .95rem; color: #10b981; font-weight: 900;">${notification.distance}m from ${locationType === 'pickup' ? 'patient location' : 'destination'}</div>
                </div>
            </div>
            <p style="margin: 0; color: #6b7280; font-size: .85rem;">
                ${locationType === 'pickup' ? 'Within ' + GEOFENCE_RADIUS + 'm of the patient location.' : 'Within ' + GEOFENCE_RADIUS + 'm of the destination. You can now complete the case.'}
            </p>
        </div>
    `;
    
    modal.style.display = 'flex';
    playGeofenceSound();
    
    // Update geofence circle color to green
    const circleKey = `${notification.case_num}_${locationType}`;
    if (geofenceCircles[circleKey]) {
        geofenceCircles[circleKey].setStyle({
            color: '#10b981',
            fillColor: '#10b981',
            fillOpacity: 0.3
        });
    }
}

// Function to close geofence modal
function closeGeofenceModal() {
    const modal = document.getElementById('geofence-notification-modal');
    if (!modal) return;
    
    // Always allow closing the modal (user can dismiss it)
    // For destination notifications, closing just acknowledges it, doesn't complete the case
    modal.style.display = 'none';

    // Acknowledge the notification
    if (currentGeofenceNotification) {
        acknowledgeGeofenceNotification(currentGeofenceNotification.case_num, currentGeofenceNotification.location_type);
        currentGeofenceNotification = null;
    }
}

// Function to acknowledge geofence notification
async function acknowledgeGeofenceNotification(caseNum, locationType) {
    // Safety check: ensure acknowledgedNotifications is initialized
    if (!acknowledgedNotifications || typeof acknowledgedNotifications.has !== 'function') {
        console.warn('acknowledgedNotifications not initialized');
        return;
    }
    
    const notificationKey = `${caseNum}_${locationType || 'destination'}`;
    if (acknowledgedNotifications.has(notificationKey)) {
        return;
    }
    
    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        if (!csrfToken) {
            console.error('CSRF token not found');
            return;
        }
        
        const response = await fetch('{{ route("admin.gps.geofence-acknowledge") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ case_num: caseNum, location_type: locationType || 'destination' })
        });
        
        if (response.ok && acknowledgedNotifications && typeof acknowledgedNotifications.add === 'function') {
            acknowledgedNotifications.add(notificationKey);
        }
    } catch (error) {
        console.error('Error acknowledging notification:', error);
    }
}

// Function to complete case from geofence notification
async function completeCaseFromGeofence() {
    if (!currentGeofenceNotification) return;
    
    const caseNum = currentGeofenceNotification.case_num;
    
    // Only complete if it's a destination notification
        if (currentGeofenceNotification.location_type !== 'destination') {
            return;
        }
    
    // Show confirmation
    const confirmed = await confirmInline(`Are you sure you want to mark Case #${caseNum} as complete?`, {
        title: 'Complete Case',
        confirmLabel: 'Complete',
        type: 'warning'
    });
    if (!confirmed) return;
    
    try {
        const response = await fetch(`/admin/cases/${caseNum}/complete`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        
        if (response.ok) {
            // Close the modal immediately
            const modal = document.getElementById('geofence-notification-modal');
            if (modal) {
                modal.style.display = 'none';
            }
            currentGeofenceNotification = null;
            
            showNotification(`Case #${caseNum} completed successfully!`, 'success');

            // Immediately remove all related map elements
            removeCaseFromMap(caseNum);

            // Verify completion from server to ensure consistency across all admins
            console.log('🔍 Verifying case completion from server...');
            await verifyAndSyncCase(caseNum, true);
        } else {
            const error = await response.json();
            showNotification('Error completing case: ' + (error.message || 'Unknown error'), 'error');
        }
    } catch (error) {
        console.error('Error completing case:', error);
        showNotification('Error completing case. Please try again.', 'error');
    }
}

// Function to check for geofence notifications
async function checkGeofenceNotifications() {
    try {
        // Fetch all cases once to check both current modal and new notifications
        const casesResponse = await fetch('/admin/cases', {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
            }
        });
        
        let allCases = [];
        if (casesResponse.ok) {
            allCases = await casesResponse.json();
        }
        
        // Check if current geofence modal's case is still active
        if (currentGeofenceNotification && currentGeofenceNotification.case_num && Array.isArray(allCases)) {
            const modalCaseData = allCases.find(c => String(c.case_num) === String(currentGeofenceNotification.case_num));
            if (modalCaseData && modalCaseData.status === 'Completed') {
                // Close the modal if case is completed
                console.log(`🔒 Closing geofence modal - Case #${currentGeofenceNotification.case_num} has been completed`);
                const modal = document.getElementById('geofence-notification-modal');
                if (modal && modal.style.display !== 'none') {
                    modal.style.display = 'none';
                    // Acknowledge the notification (fire and forget - don't await)
                    if (typeof acknowledgeGeofenceNotification === 'function') {
                        acknowledgeGeofenceNotification(currentGeofenceNotification.case_num, currentGeofenceNotification.location_type || 'destination').catch(err => {
                            console.error('Error acknowledging notification:', err);
                        });
                    }
                    currentGeofenceNotification = null;
                    return; // Don't check for new notifications if we just closed one
                }
            }
        }
        
        const response = await fetch('{{ route("admin.gps.geofence-notifications") }}', {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        });
        
        if (!response.ok) return;
        
        const data = await response.json();
        
        if (data.notifications && data.notifications.length > 0) {
            data.notifications.forEach(notification => {
                const notificationKey = `${notification.case_num}_${notification.location_type || 'destination'}`;
                
                // Skip if already acknowledged (with safety check)
                if (acknowledgedNotifications && acknowledgedNotifications.has && acknowledgedNotifications.has(notificationKey)) {
                    return;
                }
                
                // Verify case is not completed before showing notification
                if (Array.isArray(allCases) && allCases.length > 0) {
                    const caseData = allCases.find(c => String(c.case_num) === String(notification.case_num));
                    if (caseData && caseData.status === 'Completed') {
                        // Case is completed, acknowledge and skip (fire and forget)
                        if (typeof acknowledgeGeofenceNotification === 'function') {
                            acknowledgeGeofenceNotification(notification.case_num, notification.location_type || 'destination').catch(err => {
                                console.error('Error acknowledging notification:', err);
                            });
                        }
                        return;
                    }
                }
                
                // Only show if modal is not already open
                const modal = document.getElementById('geofence-notification-modal');
                if (modal && modal.style.display === 'none') {
                    showGeofenceNotification(notification);
                }
            });
        }
    } catch (error) {
        console.error('Error checking geofence notifications:', error);
    }
}

// Function to update geofence circle colors based on driver proximity
function updateGeofenceCircleColors() {
    // Get all active cases
    fetch('/admin/cases', {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(res => res.json())
    .then(cases => {
        cases.forEach(caseData => {
            if (caseData.status === 'Completed') {
                return;
            }
            
            // Get ambulance assigned to this case
            const ambulanceId = caseData.ambulance_id;
            if (!ambulanceId) {
                // Keep gray if no ambulance assigned
                if (geofenceCircles[`${caseData.case_num}_pickup`]) {
                    geofenceCircles[`${caseData.case_num}_pickup`].setStyle({
                        color: '#6b7280',
                        fillColor: '#6b7280',
                        fillOpacity: 0.2
                    });
                }
                if (geofenceCircles[`${caseData.case_num}_destination`]) {
                    geofenceCircles[`${caseData.case_num}_destination`].setStyle({
                        color: '#6b7280',
                        fillColor: '#6b7280',
                        fillOpacity: 0.2
                    });
                }
                return;
            }
            
            // Get ambulance location (driver marker)
            const ambulance = driverMarkers[ambulanceId];
            if (!ambulance || !ambulance.getLatLng) {
                // Keep gray if ambulance location not available
                if (geofenceCircles[`${caseData.case_num}_pickup`]) {
                    geofenceCircles[`${caseData.case_num}_pickup`].setStyle({
                        color: '#6b7280',
                        fillColor: '#6b7280',
                        fillOpacity: 0.2
                    });
                }
                if (geofenceCircles[`${caseData.case_num}_destination`]) {
                    geofenceCircles[`${caseData.case_num}_destination`].setStyle({
                        color: '#6b7280',
                        fillColor: '#6b7280',
                        fillOpacity: 0.2
                    });
                }
                return;
            }
            
            const ambLat = ambulance.getLatLng().lat;
            const ambLon = ambulance.getLatLng().lng;
            
            // Update pickup circle
            if (caseData.latitude && caseData.longitude && geofenceCircles[`${caseData.case_num}_pickup`]) {
                const pickupLat = parseFloat(caseData.latitude);
                const pickupLon = parseFloat(caseData.longitude);
                const pickupDistance = calculateDistance(ambLat, ambLon, pickupLat, pickupLon);
                
                if (pickupDistance <= GEOFENCE_RADIUS) {
                    geofenceCircles[`${caseData.case_num}_pickup`].setStyle({
                        color: '#10b981',
                        fillColor: '#10b981',
                        fillOpacity: 0.3
                    });
                } else {
                    geofenceCircles[`${caseData.case_num}_pickup`].setStyle({
                        color: '#6b7280',
                        fillColor: '#6b7280',
                        fillOpacity: 0.2
                    });
                }
            }
            
            // Update destination circle
            if (caseData.to_go_to_latitude && caseData.to_go_to_longitude && geofenceCircles[`${caseData.case_num}_destination`]) {
                const destLat = parseFloat(caseData.to_go_to_latitude);
                const destLon = parseFloat(caseData.to_go_to_longitude);
                const destDistance = calculateDistance(ambLat, ambLon, destLat, destLon);
                
                if (destDistance <= GEOFENCE_RADIUS) {
                    geofenceCircles[`${caseData.case_num}_destination`].setStyle({
                        color: '#10b981',
                        fillColor: '#10b981',
                        fillOpacity: 0.3
                    });
                } else {
                    geofenceCircles[`${caseData.case_num}_destination`].setStyle({
                        color: '#6b7280',
                        fillColor: '#6b7280',
                        fillOpacity: 0.2
                    });
                }
            }
        });
    })
    .catch(err => console.error('Error updating geofence circles:', err));
}

// Helper function to calculate distance (Haversine formula)
function calculateDistance(lat1, lon1, lat2, lon2) {
    const R = 6371000; // Earth's radius in meters
    const dLat = (lat2 - lat1) * Math.PI / 180;
    const dLon = (lon2 - lon1) * Math.PI / 180;
    const a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
              Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
              Math.sin(dLon / 2) * Math.sin(dLon / 2);
    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
    return R * c;
}

// Start polling for geofence notifications (every 5 seconds)
setInterval(checkGeofenceNotifications, 5000);

// Update geofence circle colors periodically (every 3 seconds)
setInterval(updateGeofenceCircleColors, 3000);

// Initial check
checkGeofenceNotifications();
updateGeofenceCircleColors();

// ✅ AUTO-PIN LOGIC: Check URL for location and pin it automatically
// ... (keep all code above this line) ...

// Start polling for geofence notifications (every 5 seconds)
setInterval(checkGeofenceNotifications, 5000);

// Update geofence circle colors periodically (every 3 seconds)
setInterval(updateGeofenceCircleColors, 3000);

// Initial check
checkGeofenceNotifications();
updateGeofenceCircleColors();


// ✅ AUTO-PIN LOGIC: Intelligent Address Resolution & Pre-filling
document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    
    // 1. GET ALL PARAMS
    const locationToPin = urlParams.get('location');
    const latParam = urlParams.get('lat');
    const lngParam = urlParams.get('lng');
    const nameParam = urlParams.get('name');       // <--- NEW
    const contactParam = urlParams.get('contact'); // <--- NEW

    let targetLat = null;
    let targetLng = null;

    // Helper to fill form fields
    function fillCaseForm() {
        if (nameParam) {
            const nameField = document.getElementById('case-name');
            if (nameField) nameField.value = nameParam;
        }
        if (contactParam) {
            const contactField = document.getElementById('case-contact');
            if (contactField) contactField.value = contactParam;
        }
    }

    // 2. EXTRACT COORDINATES
    if (latParam && lngParam && !isNaN(parseFloat(latParam)) && !isNaN(parseFloat(lngParam))) {
        targetLat = parseFloat(latParam);
        targetLng = parseFloat(lngParam);
    } 
    else if (locationToPin) {
        const coordMatch = locationToPin.trim().match(/^(-?\d+(\.\d+)?)[,\s]+(-?\d+(\.\d+)?)$/);
        if (coordMatch) {
            targetLat = parseFloat(coordMatch[1]);
            targetLng = parseFloat(coordMatch[3]);
        }
    }

    // 3. PINNING LOGIC
    if (targetLat !== null && targetLng !== null) {
        const addrField = document.getElementById('case-address');
        const coordString = `${targetLat.toFixed(6)}, ${targetLng.toFixed(6)}`;

        if (addrField) {
            if (locationToPin && locationToPin.trim() !== "") {
                addrField.value = locationToPin;
            } else {
                addrField.value = coordString;
            }
        }

        // Wait slightly for map to load
        setTimeout(() => {
            if (currentPinMarker) map.removeLayer(currentPinMarker);
            
            // Set globals
            window.clickedLatitude = targetLat;
            window.clickedLongitude = targetLng;
            
            // Drop Pin & Pan
            currentPinMarker = L.marker([targetLat, targetLng], { icon: pinIcon, draggable: true }).addTo(map);
            map.setView([targetLat, targetLng], 17);
            
            // Open Modal & Fill Data
            openModal('case-creation-modal');
            fillCaseForm(); // <--- FILL THE NAME/CONTACT HERE

            // Refine address if it's just numbers
            if (addrField) {
                const isJustCoords = /^(-?\d+(\.\d+)?)[,\s]+(-?\d+(\.\d+)?)$/.test(addrField.value.trim());
                if (isJustCoords) {
                    setAddressFromLatLng(targetLat, targetLng, 'pickup');
                }
            }

            // Drag listener
            currentPinMarker.on('dragend', function(e) {
                const newPos = e.target.getLatLng();
                window.clickedLatitude = newPos.lat;
                window.clickedLongitude = newPos.lng;
                
                document.getElementById('pin-coordinates').textContent = `${newPos.lat.toFixed(6)}, ${newPos.lng.toFixed(6)}`;
                if (addrField) addrField.value = `${newPos.lat.toFixed(6)}, ${newPos.lng.toFixed(6)}`;
                setAddressFromLatLng(window.clickedLatitude, window.clickedLongitude, 'pickup');
                updateConnectionLine();
            });
            
            document.getElementById('pin-coordinates').textContent = coordString;
            
        }, 800); 

    } 
    // Fallback for text-only addresses (no coords)
    else if (locationToPin) {
        const geocodeInput = document.getElementById('geocode-address-input');
        if (geocodeInput) geocodeInput.value = locationToPin;
        
        const addrField = document.getElementById('case-address');
        if (addrField) addrField.value = locationToPin;

        setTimeout(() => {
            geocodeAndPinFromAddress(locationToPin, 'pickup');
            
            // Wait for geocoding to likely finish/open modal, then fill details
            setTimeout(fillCaseForm, 1500); 
        }, 1000);
    }
});
</script>

</body>
</html>