<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>MDRRMO Dashboard</title>
    <link rel="stylesheet" href="{{ asset('css/stylish.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        /* Dashboard visual refresh */
        .containers-grid { background: transparent; border: none; border-radius: 0; padding: 0; box-shadow: none; margin-top: 1rem; }
        .container-row { 
            display: grid; 
            grid-template-columns: repeat(3, 1fr); 
            gap: 24px; 
            margin: 0 auto 24px auto; 
            max-width: 1200px;
            width: 100%;
        }
        @media (max-width: 900px){ .container-row { grid-template-columns: repeat(2, 1fr); gap: 20px; } }
        @media (max-width: 640px){ .container-row { grid-template-columns: 1fr; gap: 16px; } }

        /* Big modern metric cards */
        .dashboard-container { 
            background: rgba(255,255,255,0.95); 
            border: 1px solid #eef2f7;
            border-radius: 20px; 
            box-shadow: 0 10px 30px rgba(15,23,42,0.06); 
            display: flex; 
            align-items: center; 
            gap: 16px; 
            padding: 22px; 
            transition: transform .18s ease, box-shadow .18s ease;
            backdrop-filter: saturate(120%);
        }
        .dashboard-container.metric-card { border-radius: 14px; justify-content: space-between; min-height: 120px; }
        .metric-left { display:flex; flex-direction: column; align-items: flex-start; gap: 6px; }
        .metric-title { font-weight: 800; letter-spacing: .2px; color: #475569; font-size: 0.95rem; text-transform: none; display:flex; align-items:center; gap:8px; }
        .metric-title i { color: #FF8C42; }
        .metric-value { font-weight: 900; color: #0f172a; font-size: 2rem; line-height: 1.1; }
        .metric-subtitle { font-size: 0.75rem; color: #64748b; font-weight: 600; margin-top: 2px; }
        .metric-right { display:flex; align-items:center; justify-content:center; width: 96px; height: 96px; }
        .metric-right canvas { width: 96px !important; height: 96px !important; }
        .calendar-controlled { transition: all 0.2s ease; }
        .calendar-controlled:hover { transform: scale(1.02); background: rgba(255,255,255,1); }

        .container-icon { 
            width: 56px; height: 56px; border-radius: 14px; 
            display:flex; 
            align-items:center; 
            justify-content:center; 
            background: #eef2ff;
            color:#031273; 
            box-shadow: inset 0 0 0 1px rgba(3,18,115,0.06), 0 6px 14px rgba(3,18,115,0.10); 
            flex-shrink: 0;
        }
        .container-icon i { font-size: 22px; }

        .container-content h4 { margin: 0; font-size: 0.95rem; color: #64748b; font-weight: 800; letter-spacing: .2px; }
        .container-number { margin: 6px 0 0; font-size: 1.6rem; font-weight: 900; color: #0f172a; line-height: 1.1; }

        /* Analytics cards */
        .analytics-container { margin-top: 18px; }
        .chart-and-calendar { display:grid; grid-template-columns: 1fr 1fr; gap:16px; }
        @media (max-width: 900px){ .chart-and-calendar { grid-template-columns: 1fr; } }
        .chart-container, .calendar-card { background:#ffffff; border-radius:14px; box-shadow: 0 8px 22px rgba(0,0,0,0.08); padding:16px; }
        .calendar-card .calendar-header { display:flex; align-items:center; justify-content:space-between; margin-bottom:8px; }
        .cal-nav { background:#eff6ff; color:#1d4ed8; border:0; border-radius:8px; width:34px; height:34px; display:flex; align-items:center; justify-content:center; cursor:pointer; }
        .cal-title { font-weight:800; color:#0f172a; display:flex; align-items:center; gap:8px; }
        .cal-year-select { padding:6px 8px; border:2px solid #e5e7eb; border-radius:8px; background:#fff; font-weight:700; }
        .calendar-weekdays { display:grid; grid-template-columns: repeat(7,1fr); gap:4px; font-weight:700; color:#475569; margin:6px 0; }
        .calendar-grid { display:grid; grid-template-columns: repeat(7,1fr); gap:4px; }
        .cal-cell { background:#f8fafc; border:1px solid #e5e7eb; border-radius:8px; padding:8px; text-align:center; font-weight:700; color:#334155; }
        .cal-cell--today { background:#eff6ff; border-color:#93c5fd; color:#1d4ed8; }
        .cal-cell--selected { background:#031273; border-color:#031273; color:#ffffff; font-weight:800; }

        .analytics-insights { margin-top: 18px; }
        .insights-grid { display:grid; grid-template-columns: repeat(3,1fr); gap:16px; }
        @media (max-width: 900px){ .insights-grid { grid-template-columns: 1fr; } }
        .insight-card { background:#ffffff; border-radius:14px; box-shadow: 0 8px 22px rgba(0,0,0,0.08); padding:16px; display:flex; align-items:center; gap:12px; }
        .insight-icon { width:42px; height:42px; border-radius:10px; background:#f1f5f9; color:#031273; display:flex; align-items:center; justify-content:center; }
        .insight-label { font-weight:700; color:#334155; }
        .insight-value { font-weight:800; color:#0f172a; }
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
        <a href="{{ url('/') }}" class="active"><i class="fas fa-chart-pie"></i> Dashboard</a>
        <a href="{{ route('login') }}"><i class="fas fa-sign-in-alt"></i> Login</a>
    </nav>
</aside>

<!-- Fixed Top Header -->
<div class="mdrrmo-header" style="background:#F7F7F7; box-shadow: 0 2px 8px rgba(0,0,0,0.12); border: none; min-height: var(--header-height); padding: 1rem 2rem; display: flex; align-items: center; justify-content: center; position: fixed; top: 0; margin-left: 260px; width: calc(100% - 260px); z-index: 1200;">
    <h2 class="header-title" style="display:none;"></h2>
    <div id="userMenu" style="position: fixed; right: 16px; top: 16px; display: inline-flex; align-items: center; gap: 10px; cursor: pointer; color: #e5e7eb; z-index: 1000; background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.15); padding: 6px 10px; border-radius: 9999px; box-shadow: 0 6px 18px rgba(0,0,0,0.18); backdrop-filter: saturate(140%);">
        <div style="width: 28px; height: 28px; border-radius: 9999px; background: linear-gradient(135deg,#4CC9F0,#031273); display: inline-flex; align-items: center; justify-content: center; position: relative;">
            <i class="fas fa-user-shield" style="font-size: 14px; color: #ffffff;"></i>
            <span style="position: absolute; right: -1px; bottom: -1px; width: 8px; height: 8px; border-radius: 9999px; background: #22c55e; box-shadow: 0 0 0 2px #0c2d5a;"></span>
        </div>
        <span style="font-weight: 800; color: #000000; letter-spacing: .2px;">Guest</span>
        <i class="fas fa-chevron-down" style="font-size: 10px; color: rgba(255,255,255,0.85);"></i>
        <div id="userDropdown" style="display: none; position: absolute; right: 0; top: calc(100% + 12px); background: #ffffff; color: #0f172a; border-radius: 10px; box-shadow: 0 10px 24px rgba(0,0,0,0.2); padding: 8px; min-width: 160px; z-index: 1001;">
            <div style="position: absolute; right: 12px; top: -8px; width: 0; height: 0; border-left: 8px solid transparent; border-right: 8px solid transparent; border-bottom: 8px solid #ffffff;"></div>
            <a href="{{ route('login') }}" style="width: 100%; background: linear-gradient(135deg,#2563eb,#1d4ed8); color: #ffffff; border: none; border-radius: 8px; padding: 6px 8px; font-weight: 700; font-size: 12px; display: inline-flex; align-items: center; justify-content: center; gap: 6px; cursor: pointer; box-shadow: 0 4px 12px rgba(37,99,235,0.28); text-decoration: none;">
                <i class="fas fa-sign-in-alt" style="font-size: 12px;"></i>
                <span>Login</span>
            </a>
        </div>
    </div>
</div>

<main class="main-content pt-8" style="padding-top: calc(var(--header-height) + 8px);">
  <div style="width: 95%; max-width: 1200px; margin: 4px auto 8px auto; display:flex; align-items:center; justify-content: space-between; position: relative; z-index: 3001;">
    <div style="text-align: left; font-weight: 900; color: #031273; letter-spacing: .2px;">Dashboard Overview</div>
  </div>
  
  <!-- 6 Container Grid Section -->
  <div class="containers-grid">
    
    <!-- First Row - 3 containers -->
    <div class="container-row">
      <div class="dashboard-container metric-card calendar-controlled" data-metric="pendingCases">
        <div class="metric-left">
          <div class="metric-title"><i class="fas fa-clock"></i> Pending Cases</div>
          <div class="metric-value" id="pendingCasesValue">0</div>
          <div class="metric-subtitle" id="pendingCasesDate">Today</div>
        </div>
        <div class="metric-right"><canvas id="metricChart1"></canvas></div>
      </div>
      
      <div class="dashboard-container metric-card calendar-controlled" data-metric="activeCases">
        <div class="metric-left">
          <div class="metric-title"><i class="fas fa-exclamation-circle"></i> Active Cases</div>
          <div class="metric-value" id="activeCasesValue">0</div>
          <div class="metric-subtitle" id="activeCasesDate">Today</div>
        </div>
        <div class="metric-right"><canvas id="metricChart2"></canvas></div>
      </div>
      
      <div class="dashboard-container metric-card calendar-controlled" data-metric="onlineDrivers">
        <div class="metric-left">
          <div class="metric-title"><i class="fas fa-car"></i> Online Drivers</div>
          <div class="metric-value" id="onlineDriversValue">0/0</div>
          <div class="metric-subtitle" id="onlineDriversDate">Today</div>
        </div>
        <div class="metric-right"><canvas id="metricChart3"></canvas></div>
      </div>
    </div>
    
    <!-- Second Row - 3 containers -->
    <div class="container-row">
      <div class="dashboard-container metric-card">
        <div class="metric-left">
          <div class="metric-title"><i class="fas fa-users"></i> Total Drivers</div>
          <div class="metric-value">0</div>
        </div>
        <div class="metric-right"><canvas id="metricChart4"></canvas></div>
      </div>
      
      <div class="dashboard-container metric-card calendar-controlled" data-metric="totalCasesToday">
        <div class="metric-left">
          <div class="metric-title"><i class="fas fa-file-alt"></i> Total Cases Today</div>
          <div class="metric-value" id="totalCasesTodayValue">0</div>
          <div class="metric-subtitle" id="totalCasesTodayDate">Today</div>
        </div>
        <div class="metric-right"><canvas id="metricChart5"></canvas></div>
      </div>
      
      <div class="dashboard-container metric-card">
        <div class="metric-left">
          <div class="metric-title"><i class="fas fa-check-circle"></i> Past Emergency Cases</div>
          <div class="metric-value">0</div>
        </div>
        <div class="metric-right"><canvas id="metricChart6"></canvas></div>
      </div>
    </div>
  </div>

  <!-- Analytics Container -->
  <section class="chart-and-calendar" style="grid-template-columns: 1fr;">
    <div style="width:95%; max-width:1200px; margin: 0 auto; display:flex; gap:24px; align-items:stretch;">
      <!-- Pie chart card 30% -->
      <div style="flex:0 0 30%; background:#ffffff; border:1px solid #eef2f7; border-radius:12px; box-shadow:0 8px 22px rgba(0,0,0,0.08); padding:16px;">
      <div style="font-weight:900; color:#031273; padding:4px 6px 10px 6px;">Service Distribution</div>
      <div style="position:relative; height:300px;">
        <canvas id="servicePieChart"></canvas>
      </div>
      </div>
      <!-- Bar graph card 70% -->
      <div style="flex:1; background:#ffffff; border:1px solid #eef2f7; border-radius:12px; box-shadow:0 8px 22px rgba(0,0,0,0.08); padding:16px;">
        <div style="font-weight:900; color:#031273; padding:4px 6px 10px 6px;">Monthly Requests</div>
        <div style="position:relative; height:300px;">
          <canvas id="serviceBarChart"></canvas>
        </div>
      </div>
    </div>
  </section>
  
  <!-- Analytics Insights -->
  <section class="analytics-insights">
    <h3 class="text-2xl font-bold text-green-700 mb-4 text-center w-full flex items-center justify-center gap-2">
      <i class="fas fa-chart-line text-blue-500"></i>
      Analytics
    </h3>
    <div class="line-chart-container">
      <canvas id="bookingsLineChart"></canvas>
    </div>
    <div class="insights-grid">
      <div class="insight-card">
        <div class="insight-icon"><i class="fas fa-exclamation-triangle"></i></div>
        <div class="insight-meta">
          <div class="insight-label">Total Incidents ({{ date('Y') }})</div>
          <div class="insight-value">0</div>
        </div>
      </div>
      <div class="insight-card">
        <div class="insight-icon"><i class="fas fa-file-signature"></i></div>
        <div class="insight-meta">
          <div class="insight-label">Total Bookings</div>
          <div class="insight-value">0</div>
        </div>
      </div>
      <div class="insight-card">
        <div class="insight-icon"><i class="fas fa-check-circle"></i></div>
        <div class="insight-meta">
          <div class="insight-label">Completion Rate</div>
          <div class="insight-value">0%</div>
        </div>
      </div>
    </div>
  </section>
</main>

<hr class="my-divider" style="border: none; height: 4px; background: linear-gradient(to right, #031273, #4CC9F0); width: 100%; margin: 2rem auto; border-radius: 2px; box-shadow: 0 2px 5px rgba(0,0,0,0.12);">

<script>
    function toggleSidebar() {
        const sidenav = document.querySelector('.sidenav');
        sidenav.classList.toggle('active');
    }

    // User menu toggle
    document.addEventListener('DOMContentLoaded', function(){
        const userMenu = document.getElementById('userMenu');
        const userDropdown = document.getElementById('userDropdown');
        
        if (userMenu && userDropdown) {
            userMenu.addEventListener('click', function(e){
                e.stopPropagation();
                const isOpen = userDropdown.style.display === 'block';
                userDropdown.style.display = isOpen ? 'none' : 'block';
            });
            document.addEventListener('click', function(){
                if (userDropdown.style.display === 'block') {
                    userDropdown.style.display = 'none';
                }
            });
        }
    });

    // Charts
    document.addEventListener('DOMContentLoaded', function() {
        // Pie Chart
        const ctx = document.getElementById('servicePieChart').getContext('2d');
        const pieChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ['Emergency Services', 'Medical Transport', 'Rescue Operations', 'Disaster Response', 'Training Services'],
                datasets: [{
                    data: [35, 25, 20, 15, 5],
                    backgroundColor: [
                        '#dc2626', '#059669', '#2563eb', '#7c3aed', '#ea580c'
                    ],
                    borderColor: '#ffffff',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            usePointStyle: true,
                            font: {
                                size: 12,
                                weight: 'bold'
                            }
                        }
                    }
                }
            }
        });

        // Bar Chart
        const barEl = document.getElementById('serviceBarChart');
        if (barEl) {
            const bctx = barEl.getContext('2d');
            new Chart(bctx, {
                type: 'bar',
                data: {
                    labels: ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'],
                    datasets: [{
                        label: 'Requests',
                        data: [120, 150, 180, 160, 190, 210, 230, 220, 200, 240, 250, 270],
                        backgroundColor: 'rgba(255,140,66,0.6)',
                        borderColor: '#FF8C42',
                        borderWidth: 1.5,
                        borderRadius: 6,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.06)' } },
                        x: { grid: { display: false } }
                    }
                }
            });
        }

        // Line Chart
        const lineCtx = document.getElementById('bookingsLineChart');
        if (lineCtx) {
            const lctx = lineCtx.getContext('2d');
            new Chart(lctx, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                    datasets: [{
                        label: 'Incidents per Month',
                        data: [82, 97, 120, 110, 134, 150, 162, 155, 148, 170, 180, 190],
                        borderColor: '#FF8C42',
                        backgroundColor: 'rgba(255,140,66,0.15)',
                        pointBackgroundColor: '#2563eb',
                        pointBorderColor: '#2563eb',
                        fill: true,
                        tension: 0.35,
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: true, position: 'top' },
                        title: { display: false }
                    },
                    scales: {
                        y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.05)' } },
                        x: { grid: { display: false } }
                    }
                }
            });
        }

        // Mini metric pie charts
        const miniConfigs = [
            { id: 'metricChart1', data: [70, 30], colors: ['#2563eb','#e2e8f0'] },
            { id: 'metricChart2', data: [60, 40], colors: ['#ef4444','#e2e8f0'] },
            { id: 'metricChart3', data: [80, 20], colors: ['#10b981','#e2e8f0'] },
            { id: 'metricChart4', data: [55, 45], colors: ['#f59e0b','#e2e8f0'] },
            { id: 'metricChart5', data: [50, 50], colors: ['#7c3aed','#e2e8f0'] },
            { id: 'metricChart6', data: [65, 35], colors: ['#14b8a6','#e2e8f0'] }
        ];
        miniConfigs.forEach(cfg => {
            const el = document.getElementById(cfg.id);
            if (!el) return;
            new Chart(el.getContext('2d'), {
                type: 'doughnut',
                data: { datasets: [{ data: cfg.data, backgroundColor: cfg.colors, borderWidth: 0 }] },
                options: {
                    responsive: false,
                    cutout: '70%',
                    plugins: { legend: { display: false }, tooltip: { enabled: false } }
                }
            });
        });
    });
</script>
</body>
</html>
