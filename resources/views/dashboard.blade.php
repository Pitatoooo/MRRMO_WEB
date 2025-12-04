

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard · MDRRMO</title>
    <link rel="stylesheet" href="{{ asset('css/stylish.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        :root {
            --bg: #f4f6fb;
            --panel: #ffffff;
            --border: #e2e8f0;
            --brand: #0b2a5a;
            --brand-light: #dbeafe;
            --text-dark: #0f172a;
            --text-muted: #6b7280;
        }

        body {
            margin: 0;
            font-family: 'Nunito', sans-serif;
            background: var(--bg);
            min-height: 100vh;
            padding-top: 80px;
        }

        [data-animate] {
            opacity: 0;
            transform: translateY(24px) scale(0.985);
            filter: blur(8px);
            will-change: opacity, transform, filter;
        }

        [data-animate="chart"] {
            transform: translateY(30px) scale(0.98);
        }

        [data-animate="queue"] {
            transform: translateY(18px) scale(0.99);
        }

        [data-animate="summary"] {
            transform: translateY(20px) scale(0.99);
        }

        [data-animate="team"] {
            transform: translateY(26px) scale(0.985);
        }

        [data-animate].is-visible {
            animation: dashboardFadeIn var(--animate-duration, 0.85s) cubic-bezier(0.16, 1, 0.3, 1) forwards;
            animation-delay: var(--animate-delay, 0s);
        }

        @keyframes dashboardFadeIn {
            from {
                opacity: 0;
                transform: translateY(24px) scale(0.985);
                filter: blur(8px);
            }
            to {
                opacity: 1;
                transform: none;
                filter: blur(0);
            }
        }

        @media (prefers-reduced-motion: reduce) {
            [data-animate],
            [data-animate="chart"],
            [data-animate="queue"] {
                transform: none !important;
                filter: none !important;
            }

            [data-animate].is-visible {
                animation: none !important;
                opacity: 1;
            }
        }

        .dashboard-wrap {
            margin-left: 260px;
            padding: 28px clamp(16px, 4vw, 48px);
        }

        @media (max-width: 992px) {
            .dashboard-wrap {
                margin-left: 0;
                padding-top: 100px;
            }
        }

        .summary-bar {
            background: var(--panel);
            border-radius: 16px;
            padding: 20px 24px;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            gap: 18px;
            border: 1px solid var(--border);
            box-shadow: 0 8px 20px rgba(15, 23, 42, 0.08);
        }

        .summary-bar h1 {
            margin: 0;
            font-size: clamp(1.4rem, 2.4vw, 2.2rem);
            color: var(--text-dark);
        }

        .summary-bar p {
            margin: 4px 0 0;
            color: var(--text-muted);
            font-weight: 600;
        }

        .summary-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            align-items: center;
        }

        label.small {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: .25em;
            font-weight: 700;
            color: var(--text-muted);
        }

        #metrics-date {
            border: 1px solid #d1d5db;
            border-radius: 10px;
            padding: 10px 14px;
            font-weight: 700;
            color: var(--text-dark);
            background: #fff;
        }

        .btn-primary,
        .btn-secondary {
            border: none;
            border-radius: 10px;
            padding: 10px 16px;
            font-weight: 700;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }

        .btn-primary {
            background: var(--brand);
            color: #fff;
            box-shadow: 0 10px 20px rgba(30, 58, 138, 0.15);
        }

        .btn-secondary {
            background: #fff;
            color: var(--brand);
            border: 1px solid #dbeafe;
        }

        .metric-strip {
            margin: 22px 0 10px;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: .35em;
            color: var(--text-muted);
            display: flex;
            justify-content: space-between;
        }

        .cards-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 14px;
        }

        .card {
            background: var(--panel);
            border-radius: 16px;
            padding: 18px;
            border: 1px solid var(--border);
            box-shadow: 0 6px 16px rgba(15, 23, 42, 0.1);
        }

        .card h3 {
            margin: 0;
            font-size: 0.83rem;
            letter-spacing: .3em;
            text-transform: uppercase;
            color: var(--text-muted);
        }

        .card-value {
            margin: 10px 0 0;
            font-size: clamp(1.8rem, 3vw, 2.6rem);
            font-weight: 900;
            color: var(--text-dark);
        }

        .card-sub {
            margin: 4px 0 0;
            font-size: 0.85rem;
            color: var(--text-muted);
            font-weight: 600;
        }

        .split-grid {
            margin-top: 24px;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 16px;
        }

        .list-item {
            display: flex;
            justify-content: space-between;
            font-weight: 700;
            color: var(--text-dark);
        }

        .list-item span {
            color: var(--text-muted);
            font-size: 0.8rem;
            letter-spacing: .25em;
        }
    </style>
</head>
<body>
@php
    $firstName = auth()->check() ? explode(' ', auth()->user()->name ?? 'Admin')[0] : 'Guest';
@endphp

<button class="toggle-btn" onclick="toggleSidebar()">
    <i class="fas fa-bars"></i>
</button>

<aside class="sidenav" id="sidenav">
    <div class="logo-container" style="display: flex; flex-direction: column; align-items: center;">
        <img src="{{ asset('image/mdrrmologo.jpg') }}" alt="Logo" class="logo-img" style="display: block; margin: 0 auto;">
        <div style="margin-top: 8px; display: block; width: 100%; text-align: center; font-weight: 800; color: #ffffff; letter-spacing: .5px;">SILANG MDRRMO</div>
    </div>
    <nav class="nav-links">
        <a href="{{ url('/') }}" class="{{ request()->is('/') ? 'active' : '' }}"><i class="fas fa-chart-pie"></i> Dashboard</a>
        @if(auth()->check())
            <span class="nav-link-locked" style="display: block; padding: 12px 16px; color: #9ca3af; cursor: not-allowed; opacity: 0.6; position: relative;">
                <i class="fas fa-pen"></i> Posting
                <i class="fas fa-lock" style="font-size: 10px; margin-left: 8px; opacity: 0.7;"></i>
            </span>
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

<div class="mdrrmo-header" style="background:#F7F7F7; box-shadow: 0 2px 8px rgba(0,0,0,0.12); border: none; min-height: var(--header-height); padding: 1rem 2rem; display: flex; align-items: center; justify-content: center; position:fixed; top:0; left:0; right:0; z-index:1000;">
    <h2 class="header-title" style="display:none;"></h2>
    <div id="userMenu" style="position: fixed; right: 16px; top: 16px; display: inline-flex; align-items: center; gap: 10px; cursor: pointer; color: #e5e7eb; z-index: 1000; background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.15); padding: 6px 10px; border-radius: 9999px; box-shadow: 0 6px 18px rgba(0,0,0,0.18); backdrop-filter: saturate(140%);">
        <div style="width: 28px; height: 28px; border-radius: 9999px; background: linear-gradient(135deg,#4CC9F0,#031273); display: inline-flex; align-items: center; justify-content: center; position: relative;">
            <i class="fas fa-user-shield" style="font-size: 14px; color: #ffffff;"></i>
            <span style="position: absolute; right: -1px; bottom: -1px; width: 8px; height: 8px; border-radius: 9999px; background: #22c55e; box-shadow: 0 0 0 2px #0c2d5a;"></span>
            </div>
        <span style="font-weight: 800; color: #000000; letter-spacing: .2px;">{{ $firstName }}</span>
        <i class="fas fa-chevron-down" style="font-size: 10px; color: rgba(255,255,255,0.85);"></i>
        <div id="userDropdown" style="display: none; position: absolute; right: 0; top: calc(100% + 12px); background: #ffffff; color: #0f172a; border-radius: 10px; box-shadow: 0 10px 24px rgba(0,0,0,0.2); padding: 8px; min-width: 160px; z-index: 1001;">
            <div style="position: absolute; right: 12px; top: -8px; width: 0; height: 0; border-left: 8px solid transparent; border-right: 8px solid transparent; border-bottom: 8px solid #ffffff;"></div>
                @if(auth()->check())
                <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                        @csrf
                    <button id="changeAccountBtn" type="submit" style="width: 100%; background: linear-gradient(135deg,#ef4444,#b91c1c); color: #ffffff; border: none; border-radius: 8px; padding: 6px 8px; font-weight: 700; font-size: 12px; display: inline-flex; align-items: center; justify-content: center; gap: 6px; cursor: pointer; box-shadow: 0 4px 12px rgba(239,68,68,0.28);">
                        <i class="fas fa-right-left" style="font-size: 12px;"></i>
                        <span>Change account</span>
                        </button>
                    </form>
                @else
                <a href="{{ route('login') }}" style="width: 100%; background: linear-gradient(135deg,#2563eb,#1d4ed8); color: #ffffff; border-radius: 8px; padding: 6px 8px; font-weight: 700; font-size: 12px; display: inline-flex; align-items: center; justify-content: center; gap: 6px; text-decoration:none;">
                    <i class="fas fa-sign-in-alt" style="font-size: 12px;"></i>
                    <span>Login</span>
                </a>
                @endif
        </div>
    </div>
</div>

<main class="dashboard-wrap" style="padding-top:16px;">
    <section class="summary-bar" data-animate="summary" data-animate-delay="0.05">
        <div>
            <h1>Dashboard Overview</h1>
            <p id="selected-date-label">Focusing on {{ $selectedDateHuman }}</p>
        </div>
        <div class="summary-actions">
            <div>
                <label for="metrics-date" class="small">Focus Date</label>
                <input type="date" id="metrics-date" value="{{ $selectedDate }}">
            </div>
            <button id="refresh-metrics" class="btn-primary">
                <i class="fas fa-rotate"></i> Refresh
            </button>
            <a href="{{ route('dashboard.view') }}" class="btn-secondary">
                <i class="fas fa-display"></i> Dashboard View
            </a>
        </div>
    </section>

    <div class="metric-strip" data-animate="summary" data-animate-delay="0.12">
        <span>Live metrics</span>
        <span id="metrics-status">Updated {{ $lastUpdated }}</span>
    </div>

    <section class="cards-grid" style="display:grid; grid-template-columns:repeat(auto-fit,minmax(220px,1fr)); gap:18px; margin-top:18px;">
        @php
            $metricCards = [
                ['label' => 'Pending Cases', 'id' => 'case-pending', 'value' => number_format($caseMetrics['pending']), 'sub' => 'Awaiting dispatch', 'accent' => ['#FF8C42', '#F97316']],
                ['label' => 'Active Cases', 'id' => 'case-active', 'value' => number_format($caseMetrics['active']), 'sub' => 'Accepted / in progress', 'accent' => ['#4CC9F0', '#0EA5E9']],
                ['label' => 'Cases Today', 'id' => 'case-daily', 'value' => number_format($caseMetrics['daily']), 'sub' => 'Logged incidents', 'accent' => ['#6366F1', '#4F46E5']],
                ['label' => 'Completed Today', 'id' => 'case-completed', 'value' => number_format($caseMetrics['completed']), 'sub' => 'Closed after arrival', 'accent' => ['#22C55E', '#16A34A']],
                ['label' => 'Ambulances Ready', 'id' => 'ambulance-available', 'value' => number_format($systemMetrics['ambulances']['available']), 'sub' => number_format($systemMetrics['ambulances']['total']).' fleet size', 'accent' => ['#031273', '#4CC9F0']],
            ];

            $metricIcons = [
                'case-pending' => 'fas fa-hourglass-half',
                'case-active' => 'fas fa-play',
                'case-daily' => 'fas fa-calendar-day',
                'case-completed' => 'fas fa-check-circle',
                'ambulance-available' => 'fas fa-ambulance',
            ];

            $totalCasesCard = [
                'label' => 'Total Cases',
                'id' => 'case-total',
                'value' => number_format($caseMetrics['total']),
                'sub' => 'Since launch',
                'accent' => ['#FF8C42', '#4CC9F0'],
            ];

            $wavePaths = [
                'M0 24 C 10 18, 22 26, 35 19 C 48 12, 60 22, 72 16 C 84 10, 92 18, 100 14',
                'M0 20 C 8 25, 18 17, 30 22 C 42 27, 55 18, 68 23 C 80 28, 90 21, 100 25',
                'M0 22 C 12 14, 24 26, 38 18 C 52 10, 65 22, 78 14 C 89 10, 95 18, 100 20',
                'M0 23 C 9 19, 20 27, 32 21 C 44 15, 55 24, 67 18 C 79 12, 90 20, 100 16',
                'M0 21 C 11 26, 23 18, 36 24 C 49 30, 61 20, 73 26 C 85 32, 93 22, 100 27',
                'M0 23 C 10 17, 21 25, 33 19 C 45 13, 57 23, 69 17 C 81 11, 92 19, 100 15',
            ];

            $monthlyCaseSource = [
                ['label' => 'Jan', 'pending' => $caseMetrics['pending'], 'active' => $caseMetrics['active'], 'completed' => $caseMetrics['completed']],
                ['label' => 'Mar', 'pending' => max(0, $caseMetrics['pending'] - 2), 'active' => $caseMetrics['active'], 'completed' => $caseMetrics['completed'] + 3],
                ['label' => 'May', 'pending' => $caseMetrics['pending'] + 1, 'active' => max(0, $caseMetrics['active'] - 1), 'completed' => $caseMetrics['completed']],
                ['label' => 'Jul', 'pending' => $caseMetrics['pending'], 'active' => $caseMetrics['active'] + 2, 'completed' => $caseMetrics['completed'] - 1],
                ['label' => 'Sep', 'pending' => $caseMetrics['pending'] + 3, 'active' => $caseMetrics['active'], 'completed' => $caseMetrics['completed'] + 2],
                ['label' => 'Nov', 'pending' => max(0, $caseMetrics['pending'] - 1), 'active' => $caseMetrics['active'] + 1, 'completed' => $caseMetrics['completed']],
            ];

            $allMonths = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
            $monthlyLookup = [];
            foreach ($monthlyCaseSource as $row) {
                $monthlyLookup[$row['label']] = $row;
            }

            $lineChartData = [];
            foreach ($allMonths as $monthLabel) {
                $row = $monthlyLookup[$monthLabel] ?? ['pending' => 0, 'active' => 0, 'completed' => 0];
                $total = ($row['pending'] ?? 0) + ($row['active'] ?? 0) + ($row['completed'] ?? 0);
                $lineChartData[] = [
                    'label' => $monthLabel,
                    'total' => $total,
                ];
            }
        @endphp

        <div style="display:grid; grid-template-columns:repeat(auto-fit,minmax(220px,1fr)); gap:18px; grid-column:1/-1;">
            @foreach($metricCards as $index => $card)
                <article class="card"
                         data-animate="card"
                         data-animate-delay="{{ number_format($index * 0.08, 2, '.', '') }}"
                         style="position:relative; overflow:hidden; border-radius:18px; padding:1.15rem 1.25rem; background:#ffffff; border:1px solid #e2e8f0; box-shadow:0 10px 25px rgba(15,23,42,0.08); transition:transform 0.2s ease, box-shadow 0.2s ease;">
                    <div style="position:absolute; inset:0; border-radius:18px; border:1px solid transparent;"></div>
                    <div style="display:flex; flex-direction:column; gap:0.65rem;">
                        <div style="display:flex; align-items:center; justify-content:space-between;">
                            <h3 style="margin:0; font-size:0.78rem; letter-spacing:0.18em; text-transform:uppercase; color:#475569;">{{ $card['label'] }}</h3>
                            <span style="width:28px; height:28px; border-radius:8px; background:#F8FAFF; color:#031273; display:flex; align-items:center; justify-content:center; font-size:0.8rem;">
                                <i class="{{ $metricIcons[$card['id']] ?? 'fas fa-bolt' }}"></i>
                            </span>
                        </div>
                        <div style="display:flex; align-items:flex-end; justify-content:space-between; gap:0.5rem;">
                            <div id="{{ $card['id'] }}" style="font-size:clamp(1.8rem,3vw,2.5rem); font-weight:800; color:#031273; line-height:1;">{{ $card['value'] }}</div>
                            <div style="font-size:0.85rem; color:#FF8C42; font-weight:700;">↑</div>
                        </div>
                        <div style="height:36px;">
                            <svg viewBox="0 0 100 30" preserveAspectRatio="none" style="width:100%; height:100%;">
                                <polyline points="0,20 15,15 30,23 45,12 60,18 75,9 90,16 100,11"
                                          fill="none"
                                          stroke="{{ $card['accent'][0] }}"
                                          stroke-width="2.3"
                                          stroke-linecap="round"
                                          stroke-linejoin="round"/>
                            </svg>
                        </div>
                        <p style="margin:0; font-size:0.85rem; font-weight:600; color:#64748b;">{{ $card['sub'] }}</p>
                    </div>
        </article>
            @endforeach
        </div>

        @php
            $driverPercent = $staffMetrics['drivers']['total'] ? round(($staffMetrics['drivers']['online'] / max(1, $staffMetrics['drivers']['total'])) * 100) : 0;
            $medicPercent = $staffMetrics['medics']['total'] ? round(($staffMetrics['medics']['active'] / max(1, $staffMetrics['medics']['total'])) * 100) : 0;
            $queueTotal = max(1, $caseMetrics['pending'] + $caseMetrics['active'] + $caseMetrics['completed']);
        @endphp

        <div style="display:grid; grid-template-columns:minmax(0,1fr) minmax(280px,30%); gap:22px; margin-top:22px; align-items:start; grid-column:1 / -1;">
            <article class="card" data-animate="chart" data-animate-delay="0.25" style="padding:0; background:transparent; border:none; box-shadow:none;">
                <div style="border-radius:28px; background:#ffffff; border:1px solid #e2e8f0; box-shadow:0 20px 45px rgba(15,23,42,0.1); padding:1.5rem 2rem; min-height:450px; display:flex; flex-direction:column; gap:1.25rem;">
                    <div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:1rem;">
                        <div>
                            <h1 style="color:red">NOT WORKING YET</h1>
                            <h3 style="margin:0; font-size:0.78rem; letter-spacing:0.2em; text-transform:uppercase; color:#94a3b8;">Total Case Overview</h3>
                            <p style="margin:0.2rem 0 0; font-size:0.9rem; color:#b0bbd1;">Since launch</p>
                        </div>
                        <div style="display:flex; align-items:baseline; gap:0.75rem;">
                            <div id="{{ $totalCasesCard['id'] }}" style="font-size:clamp(2.4rem,4vw,3.3rem); font-weight:900; color:#031273;">{{ $totalCasesCard['value'] }}</div>
                            <span style="font-size:0.9rem; color:#94a3b8; font-weight:600;">cases</span>
                        </div>
                    </div>
                    <div style="flex:1; display:flex; flex-direction:column; justify-content:center; min-height:300px;">
                        <canvas id="caseLineChart" height="260"></canvas>
                    </div>
                    <div style="display:grid; grid-template-columns:repeat(7,minmax(0,1fr)); gap:0.35rem; margin-top:1rem;">
                        <button data-month-btn="overall" style="padding:0.35rem 0.6rem; border-radius:12px; border:1px solid #dbe0ea; background:#fff; color:#475569; font-weight:700; cursor:pointer;">All</button>
                        @foreach($lineChartData as $index => $entry)
                            <button data-month-btn="{{ $entry['label'] }}" data-month-index="{{ $index }}" style="padding:0.35rem 0.6rem; border-radius:12px; border:1px solid #dbe0ea; background:#fff; color:#475569; font-weight:700; cursor:pointer;">{{ $entry['label'] }}</button>
                        @endforeach
            </div>
            </div>
        </article>

            <div style="display:flex; flex-direction:column; gap:22px; height:100%;">
                <article class="card" data-animate="team" data-animate-delay="0.3" style="border-radius:22px; padding:1.5rem; background:#fff; border:1px solid #e5e7eb; box-shadow:0 12px 28px rgba(15,23,42,0.08);">
                    <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1rem;">
                        <h3 style="margin:0; font-size:0.9rem; color:#031273;">Team Readiness</h3>
                        <span style="width:28px; height:28px; border-radius:8px; background:#F8FAFF; color:#031273; display:flex; align-items:center; justify-content:center;">
                            <i class="fas fa-heartbeat"></i>
                        </span>
                    </div>
                    <div style="display:flex; gap:1rem; justify-content:space-between;">
                        @foreach([['label'=>'Drivers','id'=>'driver-online-text','percent'=>$driverPercent,'value'=>number_format($staffMetrics['drivers']['online']).'/'.number_format($staffMetrics['drivers']['total'])],
                                  ['label'=>'Medics','id'=>'medic-online-text','percent'=>$medicPercent,'value'=>number_format($staffMetrics['medics']['active']).'/'.number_format($staffMetrics['medics']['total'])]] as $readiness)
                            <div style="flex:1; text-align:center;">
                                <svg viewBox="0 0 120 120" style="width:100%; height:auto;">
                                    <circle cx="60" cy="60" r="48" stroke="#e5e7eb" stroke-width="10" fill="none"/>
                                    <circle cx="60" cy="60" r="48" stroke="#FF8C42" stroke-width="10" fill="none"
                                            stroke-dasharray="{{ $readiness['percent'] * 3 }}, 500" stroke-linecap="round"
                                            transform="rotate(-90 60 60)"/>
                                    <text x="60" y="65" text-anchor="middle" font-size="20" font-weight="800" fill="#031273">{{ $readiness['percent'] }}%</text>
                                </svg>
                                <div style="margin-top:0.5rem; font-size:0.85rem; color:#475569;">{{ $readiness['label'] }}</div>
                                <div id="{{ $readiness['id'] }}" style="font-size:0.9rem; font-weight:700; color:#031273;">{{ $readiness['value'] }}</div>
            </div>
                        @endforeach
            </div>
        </article>

                <article class="card" data-animate="queue" data-animate-delay="0.35" style="border-radius:22px; padding:1.5rem; background:#fff; border:1px solid #e5e7eb; box-shadow:0 12px 28px rgba(15,23,42,0.08); margin-top:auto;">
                    <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1rem;">
                        <h3 style="margin:0; font-size:0.9rem; color:#031273;">Queue Overview</h3>
                        <span style="font-size:0.78rem; color:#94a3b8;">Status</span>
                    </div>
                    @foreach([['label'=>'Pending','id'=>'queue-pending','value'=>$caseMetrics['pending'],'color'=>'#F97316'],
                              ['label'=>'Active','id'=>'queue-active','value'=>$caseMetrics['active'],'color'=>'#3B82F6'],
                              ['label'=>'Completed','id'=>'queue-completed','value'=>$caseMetrics['completed'],'color'=>'#10B981']] as $queue)
                        @php $width = min(100, round(($queue['value'] / $queueTotal) * 100)); @endphp
                        <div style="margin-bottom:0.9rem;">
                            <div style="display:flex; justify-content:space-between; font-size:0.85rem; color:#475569; font-weight:600;">
                                <span>{{ $queue['label'] }}</span>
                                <span id="{{ $queue['id'] }}">{{ number_format($queue['value']) }}</span>
                            </div>
                            <div style="margin-top:0.35rem; height:10px; border-radius:999px; background:#f1f5f9;">
                                <div style="width:{{ $width }}%; height:100%; border-radius:999px; background:{{ $queue['color'] }};"></div>
                            </div>
                        </div>
                    @endforeach
                </article>
            </div>
        </div>
    </section>
</main>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const animatedElements = Array.from(document.querySelectorAll('[data-animate]'));
        if (!animatedElements.length) return;

        const reveal = (element, index) => {
            const fallbackDelay = index * 0.1;
            const delay = parseFloat(element.dataset.animateDelay ?? fallbackDelay);
            element.style.setProperty('--animate-delay', `${delay}s`);
            requestAnimationFrame(() => element.classList.add('is-visible'));
        };

        if ('IntersectionObserver' in window) {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (!entry.isIntersecting) return;
                    const element = entry.target;
                    const idx = animatedElements.indexOf(element);
                    reveal(element, idx === -1 ? 0 : idx);
                    observer.unobserve(element);
                });
            }, { threshold: 0.25, rootMargin: '0px 0px -10% 0px' });

            animatedElements.forEach(el => observer.observe(el));
        } else {
            animatedElements.forEach(reveal);
        }
    });

    function toggleSidebar() {
        const sidenav = document.getElementById('sidenav');
        if (!sidenav) return;
        sidenav.classList.toggle('active');
    }

    // User menu toggle + AJAX logout redirect to login (mirrors pairing page behavior)
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

    const dateInput = document.getElementById('metrics-date');
    const refreshBtn = document.getElementById('refresh-metrics');
    const metricsStatus = document.getElementById('metrics-status');
    const selectedDateLabel = document.getElementById('selected-date-label');
    const endpoint = "{{ route('dashboard.metrics') }}";

    const setNumber = (id, value) => {
        const el = document.getElementById(id);
        if (el) el.textContent = new Intl.NumberFormat('en-PH').format(value ?? 0);
    };

    const setPlain = (id, value) => {
        const el = document.getElementById(id);
        if (el) el.textContent = value ?? '';
    };

    async function fetchMetrics(date) {
        if (!date) return;
        metricsStatus.textContent = 'Syncing…';
        refreshBtn.disabled = true;
        refreshBtn.style.opacity = '0.6';
        try {
            const response = await fetch(`${endpoint}?date=${encodeURIComponent(date)}`, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            if (!response.ok) throw new Error('Unable to fetch metrics');

            const payload = await response.json();
            const caseMetrics = payload.caseMetrics || {};
            const staffMetrics = payload.staffMetrics || {};
            const systemMetrics = payload.systemMetrics || {};

            setNumber('case-pending', caseMetrics.pending);
            setNumber('case-active', caseMetrics.active);
            setNumber('case-daily', caseMetrics.daily);
            setNumber('case-completed', caseMetrics.completed);
            setNumber('case-total', caseMetrics.total);

            setNumber('ambulance-available', systemMetrics.ambulances?.available);
            setNumber('ambulance-total', systemMetrics.ambulances?.total);

            setPlain('driver-online-text', `${staffMetrics.drivers?.online ?? 0}/${staffMetrics.drivers?.total ?? 0}`);
            setPlain('medic-online-text', `${staffMetrics.medics?.active ?? 0}/${staffMetrics.medics?.total ?? 0}`);

            setNumber('queue-pending', caseMetrics.pending);
            setNumber('queue-active', caseMetrics.active);
            setNumber('queue-completed', caseMetrics.completed);

            if (caseMetrics.date) {
                selectedDateLabel.textContent = `Focusing on ${caseMetrics.date.human}`;
                dateInput.value = caseMetrics.date.raw;
            }

            const updateTime = payload.requested_at ? new Date(payload.requested_at).toLocaleTimeString() : new Date().toLocaleTimeString();
            metricsStatus.textContent = `Updated ${updateTime}`;
        } catch (error) {
            metricsStatus.textContent = 'Unable to refresh metrics. Please try again.';
            console.error(error);
        } finally {
            refreshBtn.disabled = false;
            refreshBtn.style.opacity = '1';
        }
    }

    refreshBtn.addEventListener('click', () => fetchMetrics(dateInput.value));
    dateInput.addEventListener('change', (event) => fetchMetrics(event.target.value));

    const caseTotalDisplay = document.getElementById('case-total');
    const defaultCaseTotalValue = {{ (int)($caseMetrics['total'] ?? 0) }};
    const lineChartData = @json($lineChartData);
    const lineChartCanvas = document.getElementById('caseLineChart');
    const monthButtons = document.querySelectorAll('[data-month-btn]');
    let caseChart = null;

    if (lineChartCanvas && window.Chart) {
        const ctx = lineChartCanvas.getContext('2d');
        const gradient = ctx.createLinearGradient(0, 0, 0, lineChartCanvas.height);
        gradient.addColorStop(0, 'rgba(255,140,66,0.30)');
        gradient.addColorStop(1, 'rgba(255,140,66,0)');

        caseChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: lineChartData.map(point => point.label),
                datasets: [{
                    label: 'Total Cases',
                    data: lineChartData.map(point => point.total),
                    fill: true,
                    backgroundColor: gradient,
                    borderColor: '#FF8C42',
                    borderWidth: 3,
                    tension: 0.35,
                    pointBackgroundColor: lineChartData.map(() => '#ffffff'),
                    pointBorderColor: lineChartData.map(() => '#FF8C42'),
                    pointBorderWidth: 2,
                    pointRadius: lineChartData.map(() => 5),
                    pointHoverRadius: 7,
                }]
            },
            options: {
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#031273',
                        titleColor: '#ffffff',
                        bodyColor: '#ffffff',
                        callbacks: {
                            label: (context) => ` ${context.parsed.y.toLocaleString('en-PH')} cases`
                        }
                    }
                },
                interaction: { intersect: false, mode: 'index' },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { color: '#a0aec0', font: { weight: '700' } }
                    },
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(148,163,184,0.2)', drawBorder: false },
                        ticks: { color: '#a0aec0' }
                    }
                }
            }
        });
    }

    function highlightMonth(value) {
        let totalValue = defaultCaseTotalValue;
        const dataset = caseChart ? caseChart.data.datasets[0] : null;
        let targetIndex = -1;

        if (value !== 'overall') {
            targetIndex = lineChartData.findIndex(entry => entry.label === value);
            if (targetIndex !== -1) {
                totalValue = lineChartData[targetIndex].total;
            }
        }

        if (caseTotalDisplay) {
            caseTotalDisplay.textContent = new Intl.NumberFormat('en-PH').format(totalValue);
        }

        monthButtons.forEach(btn => {
            const isActive = btn.getAttribute('data-month-btn') === value;
            btn.style.background = isActive ? '#FF8C42' : '#ffffff';
            btn.style.borderColor = isActive ? '#FF8C42' : '#dbe0ea';
            btn.style.color = isActive ? '#ffffff' : '#475569';
            btn.style.boxShadow = isActive ? '0 6px 18px rgba(255,140,66,0.35)' : 'none';
        });

        if (dataset) {
            if (value === 'overall' || targetIndex === -1) {
                dataset.data = lineChartData.map(point => point.total);
                dataset.pointBackgroundColor = lineChartData.map(() => '#ffffff');
                dataset.pointBorderColor = lineChartData.map(() => '#FF8C42');
                dataset.pointRadius = lineChartData.map(() => 5);
            } else {
                dataset.data = lineChartData.map((point, index) => index <= targetIndex ? point.total : null);
                dataset.pointBackgroundColor = lineChartData.map((point, index) => index === targetIndex ? '#FF8C42' : '#ffffff');
                dataset.pointBorderColor = lineChartData.map((point, index) => index <= targetIndex ? '#FF8C42' : '#FF8C42');
                dataset.pointRadius = lineChartData.map((point, index) => index === targetIndex ? 7 : 5);
            }
            caseChart.update('none');
        }
    }

    if (monthButtons.length) {
        monthButtons.forEach(btn => {
            btn.addEventListener('click', () => highlightMonth(btn.getAttribute('data-month-btn')));
        });
        highlightMonth('overall');
    }
</script>
</body>
</html>

