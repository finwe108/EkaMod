<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>EduCore — School Enterprise System</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Sans:wght@300;400;500;600&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
<style>
  :root {
    --bg: #0a0c10;
    --bg2: #111318;
    --bg3: #181c24;
    --bg4: #1e2330;
    --border: rgba(255,255,255,0.07);
    --border2: rgba(255,255,255,0.13);
    --text: #e8eaf0;
    --muted: #7a8194;
    --accent: #4f8cff;
    --accent2: #7c5cfc;
    --green: #2dd4a0;
    --amber: #f5a623;
    --red: #f06060;
    --pink: #e975c3;
    --teal: #38d9d9;
    --sidebar-w: 240px;
  }
  *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
  html { font-size: 14px; }
  body {
    font-family: 'DM Sans', sans-serif;
    background: var(--bg);
    color: var(--text);
    min-height: 100vh;
    display: flex;
    overflow: hidden;
  }

  /* SIDEBAR */
  .sidebar {
    width: var(--sidebar-w);
    background: var(--bg2);
    border-right: 1px solid var(--border);
    display: flex;
    flex-direction: column;
    height: 100vh;
    position: fixed;
    left: 0; top: 0;
    z-index: 100;
    transition: transform .3s ease;
  }
  .logo {
    padding: 22px 20px 18px;
    border-bottom: 1px solid var(--border);
    display: flex;
    align-items: center;
    gap: 10px;
  }
  .logo-icon {
    width: 34px; height: 34px;
    background: linear-gradient(135deg, var(--accent2), var(--accent));
    border-radius: 9px;
    display: flex; align-items: center; justify-content: center;
    font-size: 16px; font-weight: 600; color: #fff;
    font-family: 'DM Serif Display', serif;
    letter-spacing: -1px;
  }
  .logo-text { font-size: 15px; font-weight: 600; letter-spacing: -.3px; }
  .logo-sub { font-size: 10px; color: var(--muted); letter-spacing: .5px; text-transform: uppercase; margin-top: 1px; }

  .nav-section { padding: 14px 10px 4px; }
  .nav-label {
    font-size: 10px; font-weight: 600; color: var(--muted);
    text-transform: uppercase; letter-spacing: .8px;
    padding: 0 8px 8px;
  }
  .nav-item {
    display: flex; align-items: center; gap: 10px;
    padding: 9px 12px;
    border-radius: 8px;
    cursor: pointer;
    font-size: 13px; font-weight: 400; color: var(--muted);
    transition: all .18s;
    margin-bottom: 1px;
  }
  .nav-item:hover { background: var(--bg3); color: var(--text); }
  .nav-item.active { background: rgba(79,140,255,0.12); color: var(--accent); font-weight: 500; }
  .nav-item .icon { width: 16px; height: 16px; opacity: .7; font-size: 14px; text-align: center; flex-shrink: 0; }
  .nav-item.active .icon { opacity: 1; }
  .nav-badge {
    margin-left: auto;
    background: var(--accent);
    color: #fff;
    font-size: 10px; font-weight: 600;
    padding: 2px 6px; border-radius: 99px;
    min-width: 18px; text-align: center;
  }

  .sidebar-footer {
    margin-top: auto;
    padding: 14px 10px;
    border-top: 1px solid var(--border);
  }
  .user-card {
    display: flex; align-items: center; gap: 10px;
    padding: 8px 10px; border-radius: 8px; cursor: pointer;
  }
  .user-card:hover { background: var(--bg3); }
  .avatar {
    width: 32px; height: 32px; border-radius: 50%;
    background: linear-gradient(135deg, #4f8cff, #7c5cfc);
    display: flex; align-items: center; justify-content: center;
    font-size: 12px; font-weight: 600; color: #fff; flex-shrink: 0;
  }
  .user-name { font-size: 12px; font-weight: 500; }
  .user-role { font-size: 10px; color: var(--muted); }

  /* MAIN */
  .main {
    margin-left: var(--sidebar-w);
    flex: 1;
    display: flex;
    flex-direction: column;
    height: 100vh;
    overflow: hidden;
  }

  /* TOPBAR */
  .topbar {
    background: var(--bg2);
    border-bottom: 1px solid var(--border);
    padding: 0 28px;
    height: 56px;
    display: flex; align-items: center; gap: 16px;
    flex-shrink: 0;
  }
  .page-title {
    font-family: 'DM Serif Display', serif;
    font-size: 20px; color: var(--text);
    letter-spacing: -.5px;
  }
  .breadcrumb {
    font-size: 12px; color: var(--muted);
    margin-left: 4px;
  }
  .topbar-right {
    margin-left: auto;
    display: flex; align-items: center; gap: 10px;
  }
  .search-bar {
    background: var(--bg3);
    border: 1px solid var(--border);
    border-radius: 8px;
    padding: 7px 12px;
    font-size: 12px; color: var(--text);
    width: 200px;
    outline: none;
    font-family: 'DM Sans', sans-serif;
    transition: border .2s;
  }
  .search-bar:focus { border-color: var(--accent); }
  .search-bar::placeholder { color: var(--muted); }
  .btn {
    padding: 7px 14px; border-radius: 8px;
    font-size: 12px; font-weight: 500;
    cursor: pointer; border: none;
    font-family: 'DM Sans', sans-serif;
    transition: all .18s;
  }
  .btn-primary {
    background: var(--accent);
    color: #fff;
  }
  .btn-primary:hover { background: #3a7ae0; }
  .btn-ghost {
    background: var(--bg3);
    color: var(--muted);
    border: 1px solid var(--border);
  }
  .btn-ghost:hover { color: var(--text); border-color: var(--border2); }
  .notif-btn {
    width: 34px; height: 34px; border-radius: 8px;
    background: var(--bg3); border: 1px solid var(--border);
    cursor: pointer; display: flex; align-items: center; justify-content: center;
    position: relative; font-size: 14px;
    transition: all .18s;
  }
  .notif-btn:hover { border-color: var(--border2); }
  .notif-dot {
    position: absolute; top: 6px; right: 6px;
    width: 6px; height: 6px; border-radius: 50%;
    background: var(--red);
  }

  /* CONTENT */
  .content {
    flex: 1; overflow-y: auto; padding: 24px 28px;
  }
  .content::-webkit-scrollbar { width: 4px; }
  .content::-webkit-scrollbar-track { background: transparent; }
  .content::-webkit-scrollbar-thumb { background: var(--bg4); border-radius: 99px; }

  /* PAGE views */
  .page { display: none; }
  .page.active { display: block; }

  /* STAT CARDS */
  .stat-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 14px;
    margin-bottom: 24px;
  }
  .stat-card {
    background: var(--bg2);
    border: 1px solid var(--border);
    border-radius: 12px;
    padding: 18px 20px;
    position: relative;
    overflow: hidden;
    cursor: pointer;
    transition: border .2s, transform .18s;
  }
  .stat-card:hover { border-color: var(--border2); transform: translateY(-1px); }
  .stat-accent { position: absolute; top: 0; left: 0; right: 0; height: 2px; }
  .stat-label { font-size: 11px; color: var(--muted); text-transform: uppercase; letter-spacing: .6px; margin-bottom: 10px; }
  .stat-value { font-family: 'DM Serif Display', serif; font-size: 30px; line-height: 1; margin-bottom: 6px; }
  .stat-change { font-size: 11px; color: var(--green); }
  .stat-change.down { color: var(--red); }
  .stat-icon {
    position: absolute; right: 18px; top: 18px;
    font-size: 20px; opacity: .15;
  }

  /* GRIDS */
  .two-col { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 20px; }
  .three-col { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px; margin-bottom: 20px; }
  .wide-narrow { display: grid; grid-template-columns: 2fr 1fr; gap: 16px; margin-bottom: 20px; }

  /* CARD */
  .card {
    background: var(--bg2);
    border: 1px solid var(--border);
    border-radius: 12px;
    overflow: hidden;
  }
  .card-header {
    padding: 16px 20px;
    border-bottom: 1px solid var(--border);
    display: flex; align-items: center; gap: 10px;
  }
  .card-title { font-size: 13px; font-weight: 600; }
  .card-subtitle { font-size: 11px; color: var(--muted); margin-top: 1px; }
  .card-body { padding: 18px 20px; }
  .card-action { margin-left: auto; }

  /* TABLE */
  table { width: 100%; border-collapse: collapse; }
  th {
    font-size: 10px; font-weight: 600; color: var(--muted);
    text-transform: uppercase; letter-spacing: .6px;
    padding: 10px 16px; text-align: left;
    background: var(--bg3); border-bottom: 1px solid var(--border);
  }
  td {
    padding: 12px 16px; font-size: 12.5px;
    border-bottom: 1px solid var(--border);
    color: var(--text);
    vertical-align: middle;
  }
  tr:last-child td { border-bottom: none; }
  tr:hover td { background: var(--bg3); }
  .table-wrap { overflow-x: auto; }

  /* BADGES */
  .badge {
    display: inline-flex; align-items: center;
    padding: 3px 8px; border-radius: 99px;
    font-size: 10px; font-weight: 600;
    text-transform: uppercase; letter-spacing: .4px;
  }
  .badge-green { background: rgba(45,212,160,.12); color: var(--green); }
  .badge-red { background: rgba(240,96,96,.12); color: var(--red); }
  .badge-amber { background: rgba(245,166,35,.12); color: var(--amber); }
  .badge-blue { background: rgba(79,140,255,.12); color: var(--accent); }
  .badge-purple { background: rgba(124,92,252,.12); color: var(--accent2); }

  /* PROGRESS */
  .progress-bar { height: 5px; background: var(--bg4); border-radius: 99px; overflow: hidden; }
  .progress-fill { height: 100%; border-radius: 99px; transition: width .6s ease; }

  /* AVATAR GROUP */
  .avatar-sm {
    width: 26px; height: 26px; border-radius: 50%;
    border: 2px solid var(--bg2);
    font-size: 9px; font-weight: 600; color: #fff;
    display: flex; align-items: center; justify-content: center;
    margin-left: -8px; first:margin-left: 0;
  }
  .avatar-group { display: flex; }
  .avatar-group .avatar-sm:first-child { margin-left: 0; }

  /* CHARTS (pure CSS) */
  .bar-chart { display: flex; align-items: flex-end; gap: 6px; height: 100px; }
  .bar-col { flex: 1; display: flex; flex-direction: column; align-items: center; gap: 4px; }
  .bar {
    width: 100%; border-radius: 4px 4px 0 0;
    background: rgba(79,140,255,.35);
    transition: background .2s;
    cursor: pointer;
  }
  .bar:hover { background: var(--accent); }
  .bar.accent { background: var(--accent); }
  .bar-label { font-size: 9px; color: var(--muted); }

  /* DONUT CHART */
  .donut-wrap { position: relative; width: 90px; height: 90px; flex-shrink: 0; }
  .donut-wrap svg { transform: rotate(-90deg); }
  .donut-center {
    position: absolute; inset: 0;
    display: flex; flex-direction: column;
    align-items: center; justify-content: center;
  }
  .donut-val { font-family: 'DM Serif Display', serif; font-size: 18px; }
  .donut-lbl { font-size: 9px; color: var(--muted); }

  /* TIMELINE */
  .timeline { display: flex; flex-direction: column; gap: 0; }
  .tl-item { display: flex; gap: 14px; padding-bottom: 16px; position: relative; }
  .tl-item:not(:last-child)::before {
    content: ''; position: absolute; left: 9px; top: 20px;
    width: 1px; bottom: 0; background: var(--border);
  }
  .tl-dot {
    width: 20px; height: 20px; border-radius: 50%;
    flex-shrink: 0; display: flex; align-items: center; justify-content: center;
    font-size: 9px; margin-top: 2px;
  }
  .tl-content { flex: 1; }
  .tl-title { font-size: 12px; font-weight: 500; margin-bottom: 2px; }
  .tl-time { font-size: 10px; color: var(--muted); }

  /* ATTENDANCE GRID */
  .att-grid { display: grid; grid-template-columns: repeat(7, 1fr); gap: 4px; }
  .att-cell {
    aspect-ratio: 1; border-radius: 4px;
    display: flex; align-items: center; justify-content: center;
    font-size: 9px; color: var(--muted); cursor: pointer;
    transition: transform .15s;
  }
  .att-cell:hover { transform: scale(1.15); }
  .att-cell.present { background: rgba(45,212,160,.25); color: var(--green); }
  .att-cell.absent { background: rgba(240,96,96,.18); color: var(--red); }
  .att-cell.late { background: rgba(245,166,35,.18); color: var(--amber); }
  .att-cell.empty { background: var(--bg3); }

  /* FORM */
  .form-group { margin-bottom: 14px; }
  .form-label { font-size: 11px; font-weight: 500; color: var(--muted); margin-bottom: 6px; display: block; text-transform: uppercase; letter-spacing: .5px; }
  .form-input {
    width: 100%; background: var(--bg3); border: 1px solid var(--border);
    border-radius: 8px; padding: 9px 12px; font-size: 13px;
    color: var(--text); outline: none; font-family: 'DM Sans', sans-serif;
    transition: border .2s;
  }
  .form-input:focus { border-color: var(--accent); }
  .form-select {
    width: 100%; background: var(--bg3); border: 1px solid var(--border);
    border-radius: 8px; padding: 9px 12px; font-size: 13px;
    color: var(--text); outline: none; font-family: 'DM Sans', sans-serif;
    cursor: pointer;
    -webkit-appearance: none;
  }
  .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }

  /* SCHEDULE GRID */
  .schedule-grid {
    display: grid;
    grid-template-columns: 60px repeat(5, 1fr);
    border: 1px solid var(--border);
    border-radius: 10px;
    overflow: hidden;
  }
  .sch-header {
    background: var(--bg3); padding: 10px 8px;
    font-size: 10px; font-weight: 600; color: var(--muted);
    text-align: center; text-transform: uppercase; letter-spacing: .5px;
    border-bottom: 1px solid var(--border); border-right: 1px solid var(--border);
  }
  .sch-time {
    background: var(--bg3); padding: 20px 8px;
    font-size: 10px; color: var(--muted); text-align: center;
    border-right: 1px solid var(--border); border-bottom: 1px solid var(--border);
    font-family: 'JetBrains Mono', monospace;
  }
  .sch-cell {
    border-right: 1px solid var(--border);
    border-bottom: 1px solid var(--border);
    padding: 6px;
    min-height: 60px;
  }
  .sch-event {
    border-radius: 6px; padding: 6px 8px; height: 100%;
    font-size: 10px; font-weight: 500;
  }
  .sch-event.math { background: rgba(79,140,255,.18); color: #6da7ff; }
  .sch-event.science { background: rgba(45,212,160,.18); color: var(--green); }
  .sch-event.english { background: rgba(233,117,195,.18); color: var(--pink); }
  .sch-event.history { background: rgba(245,166,35,.18); color: var(--amber); }
  .sch-event.pe { background: rgba(56,217,217,.18); color: var(--teal); }

  /* GRADES */
  .grade-row { display: flex; align-items: center; gap: 12px; margin-bottom: 12px; }
  .grade-subject { font-size: 12px; font-weight: 500; min-width: 110px; }
  .grade-bar-wrap { flex: 1; }
  .grade-score {
    font-family: 'JetBrains Mono', monospace;
    font-size: 12px; font-weight: 500; min-width: 42px; text-align: right;
  }

  /* QUICK ACTIONS */
  .quick-actions { display: grid; grid-template-columns: repeat(4, 1fr); gap: 10px; margin-bottom: 20px; }
  .qa-btn {
    background: var(--bg2); border: 1px solid var(--border);
    border-radius: 10px; padding: 14px 12px;
    display: flex; flex-direction: column; align-items: center; gap: 8px;
    cursor: pointer; transition: all .18s; text-align: center;
  }
  .qa-btn:hover { border-color: var(--accent); transform: translateY(-1px); }
  .qa-icon { font-size: 20px; }
  .qa-label { font-size: 11px; color: var(--muted); font-weight: 500; }

  /* STUDENT LIST */
  .student-item {
    display: flex; align-items: center; gap: 12px;
    padding: 10px 20px; border-bottom: 1px solid var(--border);
    cursor: pointer; transition: background .15s;
  }
  .student-item:hover { background: var(--bg3); }
  .student-item:last-child { border-bottom: none; }
  .student-info { flex: 1; }
  .student-name { font-size: 13px; font-weight: 500; }
  .student-meta { font-size: 11px; color: var(--muted); margin-top: 1px; }

  /* ANNOUNCEMENT */
  .announce-item {
    padding: 14px 20px; border-bottom: 1px solid var(--border);
  }
  .announce-item:last-child { border-bottom: none; }
  .announce-title { font-size: 13px; font-weight: 500; margin-bottom: 4px; }
  .announce-body { font-size: 12px; color: var(--muted); line-height: 1.6; }
  .announce-meta { font-size: 10px; color: var(--muted); margin-top: 6px; }

  /* FINANCE */
  .finance-item {
    display: flex; align-items: center; gap: 12px;
    padding: 12px 20px; border-bottom: 1px solid var(--border);
  }
  .finance-item:last-child { border-bottom: none; }
  .finance-icon {
    width: 32px; height: 32px; border-radius: 8px;
    display: flex; align-items: center; justify-content: center; font-size: 14px;
    flex-shrink: 0;
  }
  .finance-details { flex: 1; }
  .finance-name { font-size: 12px; font-weight: 500; }
  .finance-date { font-size: 10px; color: var(--muted); }
  .finance-amount { font-family: 'JetBrains Mono', monospace; font-size: 13px; font-weight: 500; }

  /* SCROLLBARS */
  * { scrollbar-width: thin; scrollbar-color: var(--bg4) transparent; }

  /* RESPONSIVE hint */
  @media (max-width: 900px) {
    .stat-grid { grid-template-columns: repeat(2, 1fr); }
    .two-col, .wide-narrow { grid-template-columns: 1fr; }
    .three-col { grid-template-columns: 1fr 1fr; }
  }

  /* ANIMATIONS */
  @keyframes fadeUp {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
  }
  .page.active { animation: fadeUp .28s ease; }

  /* EMPTY STATE */
  .empty { text-align: center; padding: 40px 20px; color: var(--muted); font-size: 13px; }

  /* CHIP */
  .chip {
    display: inline-flex; align-items: center; gap: 4px;
    background: var(--bg3); border: 1px solid var(--border);
    border-radius: 99px; padding: 4px 10px; font-size: 11px;
    cursor: pointer; transition: all .18s;
  }
  .chip:hover { border-color: var(--border2); }
  .chip.active { background: rgba(79,140,255,.12); border-color: var(--accent); color: var(--accent); }

  .section-title {
    font-family: 'DM Serif Display', serif;
    font-size: 17px; margin-bottom: 16px; color: var(--text);
  }

  .mono { font-family: 'JetBrains Mono', monospace; }

  .divider { height: 1px; background: var(--border); margin: 16px 0; }
</style>
</head>
<body>

<!-- SIDEBAR -->
<aside class="sidebar">
  <div class="logo">
    <div class="logo-icon">E</div>
    <div>
      <div class="logo-text">EduCore</div>
      <div class="logo-sub">Enterprise SIS</div>
    </div>
  </div>

  <div class="nav-section">
    <div class="nav-label">Overview</div>
    <div class="nav-item active" onclick="switchPage('dashboard', this)">
      <span class="icon">⊞</span> Dashboard
    </div>
    <div class="nav-item" onclick="switchPage('announcements', this)">
      <span class="icon">📢</span> Announcements
      <span class="nav-badge">3</span>
    </div>
  </div>

  <div class="nav-section">
    <div class="nav-label">Academic</div>
    <div class="nav-item" onclick="switchPage('students', this)">
      <span class="icon">👤</span> Students
    </div>
    <div class="nav-item" onclick="switchPage('teachers', this)">
      <span class="icon">🎓</span> Faculty
    </div>
    <div class="nav-item" onclick="switchPage('classes', this)">
      <span class="icon">📚</span> Classes
    </div>
    <div class="nav-item" onclick="switchPage('schedule', this)">
      <span class="icon">📅</span> Schedule
    </div>
    <div class="nav-item" onclick="switchPage('grades', this)">
      <span class="icon">📊</span> Grades
    </div>
    <div class="nav-item" onclick="switchPage('attendance', this)">
      <span class="icon">✅</span> Attendance
    </div>
  </div>

  <div class="nav-section">
    <div class="nav-label">Management</div>
    <div class="nav-item" onclick="switchPage('finance', this)">
      <span class="icon">💳</span> Finance
    </div>
    <div class="nav-item" onclick="switchPage('library', this)">
      <span class="icon">📖</span> Library
    </div>
    <div class="nav-item" onclick="switchPage('events', this)">
      <span class="icon">🎉</span> Events
    </div>
    <div class="nav-item" onclick="switchPage('settings', this)">
      <span class="icon">⚙️</span> Settings
    </div>
  </div>

  <div class="sidebar-footer">
    <div class="user-card">
      <div class="avatar">AP</div>
      <div>
        <div class="user-name">Admin Principal</div>
        <div class="user-role">Super Administrator</div>
      </div>
    </div>
  </div>
</aside>

<!-- MAIN -->
<main class="main">
  <!-- TOPBAR -->
  <header class="topbar">
    <div>
      <div class="page-title" id="pageTitle">Dashboard</div>
    </div>
    <div class="topbar-right">
      <input class="search-bar" placeholder="🔍  Search anything…" type="text">
      <button class="btn btn-ghost">Export</button>
      <button class="btn btn-primary" onclick="handleAdd()">+ Add New</button>
      <div class="notif-btn">🔔<div class="notif-dot"></div></div>
    </div>
  </header>

  <div class="content">

    <!-- DASHBOARD -->
    <div class="page active" id="page-dashboard">
      <div class="stat-grid">
        <div class="stat-card" onclick="switchPage('students', document.querySelector('[onclick*=students]'))">
          <div class="stat-accent" style="background: linear-gradient(90deg,var(--accent),var(--accent2))"></div>
          <div class="stat-icon">👤</div>
          <div class="stat-label">Total Students</div>
          <div class="stat-value">2,847</div>
          <div class="stat-change">↑ 12% this semester</div>
        </div>
        <div class="stat-card" onclick="switchPage('teachers', document.querySelector('[onclick*=teachers]'))">
          <div class="stat-accent" style="background: linear-gradient(90deg,var(--green),#1da87a)"></div>
          <div class="stat-icon">🎓</div>
          <div class="stat-label">Faculty Members</div>
          <div class="stat-value">148</div>
          <div class="stat-change">↑ 4 new this year</div>
        </div>
        <div class="stat-card" onclick="switchPage('attendance', document.querySelector('[onclick*=attendance]'))">
          <div class="stat-accent" style="background: linear-gradient(90deg,var(--amber),#d48920)"></div>
          <div class="stat-icon">✅</div>
          <div class="stat-label">Avg Attendance</div>
          <div class="stat-value">94.2%</div>
          <div class="stat-change down">↓ 0.3% vs last week</div>
        </div>
        <div class="stat-card" onclick="switchPage('finance', document.querySelector('[onclick*=finance]'))">
          <div class="stat-accent" style="background: linear-gradient(90deg,var(--pink),var(--accent2))"></div>
          <div class="stat-icon">💳</div>
          <div class="stat-label">Fees Collected</div>
          <div class="stat-value">₱4.2M</div>
          <div class="stat-change">↑ 18% vs last year</div>
        </div>
      </div>

      <div class="quick-actions">
        <div class="qa-btn" onclick="switchPage('students', document.querySelector('[onclick*=students]'))">
          <div class="qa-icon">👤</div><div class="qa-label">Enroll Student</div>
        </div>
        <div class="qa-btn" onclick="switchPage('attendance', document.querySelector('[onclick*=attendance]'))">
          <div class="qa-icon">✅</div><div class="qa-label">Take Attendance</div>
        </div>
        <div class="qa-btn" onclick="switchPage('grades', document.querySelector('[onclick*=grades]'))">
          <div class="qa-icon">📊</div><div class="qa-label">Post Grades</div>
        </div>
        <div class="qa-btn" onclick="switchPage('finance', document.querySelector('[onclick*=finance]'))">
          <div class="qa-icon">💳</div><div class="qa-label">Record Payment</div>
        </div>
      </div>

      <div class="wide-narrow">
        <!-- Enrollment Chart -->
        <div class="card">
          <div class="card-header">
            <div>
              <div class="card-title">Enrollment Trend</div>
              <div class="card-subtitle">Monthly enrollment this school year</div>
            </div>
            <div class="card-action">
              <span class="chip active">2025–26</span>
            </div>
          </div>
          <div class="card-body">
            <div class="bar-chart" style="height:120px">
              <div class="bar-col"><div class="bar" style="height:55%" title="Jun"></div><div class="bar-label">Jun</div></div>
              <div class="bar-col"><div class="bar" style="height:70%" title="Jul"></div><div class="bar-label">Jul</div></div>
              <div class="bar-col"><div class="bar" style="height:88%" title="Aug"></div><div class="bar-label">Aug</div></div>
              <div class="bar-col"><div class="bar accent" style="height:95%" title="Sep"></div><div class="bar-label">Sep</div></div>
              <div class="bar-col"><div class="bar" style="height:90%" title="Oct"></div><div class="bar-label">Oct</div></div>
              <div class="bar-col"><div class="bar" style="height:85%" title="Nov"></div><div class="bar-label">Nov</div></div>
              <div class="bar-col"><div class="bar" style="height:80%" title="Dec"></div><div class="bar-label">Dec</div></div>
              <div class="bar-col"><div class="bar" style="height:82%" title="Jan"></div><div class="bar-label">Jan</div></div>
              <div class="bar-col"><div class="bar" style="height:84%" title="Feb"></div><div class="bar-label">Feb</div></div>
              <div class="bar-col"><div class="bar" style="height:79%" title="Mar"></div><div class="bar-label">Mar</div></div>
            </div>
          </div>
        </div>

        <!-- Gender Ratio -->
        <div class="card">
          <div class="card-header">
            <div>
              <div class="card-title">Gender Ratio</div>
              <div class="card-subtitle">Current enrollment</div>
            </div>
          </div>
          <div class="card-body" style="display:flex;flex-direction:column;align-items:center;gap:14px">
            <div class="donut-wrap">
              <svg width="90" height="90" viewBox="0 0 90 90">
                <circle cx="45" cy="45" r="35" fill="none" stroke="var(--bg4)" stroke-width="10"/>
                <circle cx="45" cy="45" r="35" fill="none" stroke="var(--accent)" stroke-width="10"
                  stroke-dasharray="131 89" stroke-linecap="round"/>
                <circle cx="45" cy="45" r="35" fill="none" stroke="var(--pink)" stroke-width="10"
                  stroke-dasharray="89 131" stroke-dashoffset="-131" stroke-linecap="round"/>
              </svg>
              <div class="donut-center">
                <div class="donut-val">59%</div>
                <div class="donut-lbl">Male</div>
              </div>
            </div>
            <div style="display:flex;flex-direction:column;gap:6px;width:100%">
              <div style="display:flex;align-items:center;gap:8px">
                <div style="width:10px;height:10px;border-radius:50%;background:var(--accent);flex-shrink:0"></div>
                <div style="font-size:11px;color:var(--muted);flex:1">Male</div>
                <div style="font-size:12px;font-weight:600">1,677</div>
              </div>
              <div style="display:flex;align-items:center;gap:8px">
                <div style="width:10px;height:10px;border-radius:50%;background:var(--pink);flex-shrink:0"></div>
                <div style="font-size:11px;color:var(--muted);flex:1">Female</div>
                <div style="font-size:12px;font-weight:600">1,170</div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="two-col">
        <!-- Recent Activity -->
        <div class="card">
          <div class="card-header">
            <div>
              <div class="card-title">Recent Activity</div>
              <div class="card-subtitle">System-wide events</div>
            </div>
          </div>
          <div class="card-body" style="padding:14px 20px">
            <div class="timeline">
              <div class="tl-item">
                <div class="tl-dot" style="background:rgba(79,140,255,.18);color:var(--accent)">📝</div>
                <div class="tl-content">
                  <div class="tl-title">Grade 10 midterm grades posted</div>
                  <div class="tl-time">2 minutes ago · Ms. Reyes</div>
                </div>
              </div>
              <div class="tl-item">
                <div class="tl-dot" style="background:rgba(45,212,160,.18);color:var(--green)">✅</div>
                <div class="tl-content">
                  <div class="tl-title">Attendance marked — Section 9-B</div>
                  <div class="tl-time">18 minutes ago · Mr. Santos</div>
                </div>
              </div>
              <div class="tl-item">
                <div class="tl-dot" style="background:rgba(245,166,35,.18);color:var(--amber)">💳</div>
                <div class="tl-content">
                  <div class="tl-title">Tuition payment received — Juan dela Cruz</div>
                  <div class="tl-time">1 hour ago · Cashier</div>
                </div>
              </div>
              <div class="tl-item">
                <div class="tl-dot" style="background:rgba(233,117,195,.18);color:var(--pink)">👤</div>
                <div class="tl-content">
                  <div class="tl-title">New student enrolled — Maria Santos</div>
                  <div class="tl-time">3 hours ago · Registrar</div>
                </div>
              </div>
              <div class="tl-item">
                <div class="tl-dot" style="background:rgba(124,92,252,.18);color:var(--accent2)">📢</div>
                <div class="tl-content">
                  <div class="tl-title">Announcement posted — Foundation Day</div>
                  <div class="tl-time">5 hours ago · Admin</div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Top Classes -->
        <div class="card">
          <div class="card-header">
            <div>
              <div class="card-title">Section Performance</div>
              <div class="card-subtitle">Average GPA by section</div>
            </div>
          </div>
          <div class="card-body">
            <div class="grade-row">
              <div class="grade-subject">Grade 10-A</div>
              <div class="grade-bar-wrap">
                <div class="progress-bar"><div class="progress-fill" style="width:92%;background:var(--green)"></div></div>
              </div>
              <div class="grade-score" style="color:var(--green)">92.4</div>
            </div>
            <div class="grade-row">
              <div class="grade-subject">Grade 9-B</div>
              <div class="grade-bar-wrap">
                <div class="progress-bar"><div class="progress-fill" style="width:88%;background:var(--accent)"></div></div>
              </div>
              <div class="grade-score" style="color:var(--accent)">88.1</div>
            </div>
            <div class="grade-row">
              <div class="grade-subject">Grade 8-C</div>
              <div class="grade-bar-wrap">
                <div class="progress-bar"><div class="progress-fill" style="width:85%;background:var(--accent2)"></div></div>
              </div>
              <div class="grade-score" style="color:var(--accent2)">85.7</div>
            </div>
            <div class="grade-row">
              <div class="grade-subject">Grade 11-STEM</div>
              <div class="grade-bar-wrap">
                <div class="progress-bar"><div class="progress-fill" style="width:84%;background:var(--teal)"></div></div>
              </div>
              <div class="grade-score" style="color:var(--teal)">84.2</div>
            </div>
            <div class="grade-row">
              <div class="grade-subject">Grade 7-A</div>
              <div class="grade-bar-wrap">
                <div class="progress-bar"><div class="progress-fill" style="width:81%;background:var(--amber)"></div></div>
              </div>
              <div class="grade-score" style="color:var(--amber)">81.0</div>
            </div>
            <div class="grade-row">
              <div class="grade-subject">Grade 12-ABM</div>
              <div class="grade-bar-wrap">
                <div class="progress-bar"><div class="progress-fill" style="width:79%;background:var(--pink)"></div></div>
              </div>
              <div class="grade-score" style="color:var(--pink)">79.5</div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- STUDENTS PAGE -->
    <div class="page" id="page-students">
      <div style="display:flex;gap:10px;margin-bottom:18px;flex-wrap:wrap">
        <span class="chip active">All (2,847)</span>
        <span class="chip">Grade 7 (480)</span>
        <span class="chip">Grade 8 (510)</span>
        <span class="chip">Grade 9 (494)</span>
        <span class="chip">Grade 10 (502)</span>
        <span class="chip">Grade 11 (445)</span>
        <span class="chip">Grade 12 (416)</span>
      </div>
      <div class="card">
        <div class="card-header">
          <div>
            <div class="card-title">Student Directory</div>
            <div class="card-subtitle">2025–2026 school year</div>
          </div>
          <div class="card-action" style="display:flex;gap:8px">
            <button class="btn btn-ghost">Import CSV</button>
            <button class="btn btn-primary">+ Enroll</button>
          </div>
        </div>
        <div class="table-wrap">
          <table>
            <thead>
              <tr>
                <th>Student</th>
                <th>LRN</th>
                <th>Grade & Section</th>
                <th>Contact</th>
                <th>GWA</th>
                <th>Attendance</th>
                <th>Fee Status</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>
                  <div style="display:flex;align-items:center;gap:10px">
                    <div class="avatar" style="width:30px;height:30px;font-size:11px">JD</div>
                    <div><div style="font-weight:500">Juan Dela Cruz</div><div style="font-size:10px;color:var(--muted)">M · 15 yrs</div></div>
                  </div>
                </td>
                <td class="mono">100234-2025</td>
                <td><span class="badge badge-blue">Grade 10-A</span></td>
                <td style="color:var(--muted)">09171234567</td>
                <td style="font-weight:600;color:var(--green)">92.5</td>
                <td>
                  <div style="display:flex;align-items:center;gap:6px">
                    <div class="progress-bar" style="width:60px"><div class="progress-fill" style="width:97%;background:var(--green)"></div></div>
                    <span style="font-size:11px">97%</span>
                  </div>
                </td>
                <td><span class="badge badge-green">Paid</span></td>
                <td><button class="btn btn-ghost" style="font-size:11px;padding:4px 10px">View</button></td>
              </tr>
              <tr>
                <td>
                  <div style="display:flex;align-items:center;gap:10px">
                    <div class="avatar" style="width:30px;height:30px;font-size:11px;background:linear-gradient(135deg,#e975c3,#7c5cfc)">MS</div>
                    <div><div style="font-weight:500">Maria Santos</div><div style="font-size:10px;color:var(--muted)">F · 14 yrs</div></div>
                  </div>
                </td>
                <td class="mono">100235-2025</td>
                <td><span class="badge badge-purple">Grade 9-B</span></td>
                <td style="color:var(--muted)">09289876543</td>
                <td style="font-weight:600;color:var(--accent)">88.0</td>
                <td>
                  <div style="display:flex;align-items:center;gap:6px">
                    <div class="progress-bar" style="width:60px"><div class="progress-fill" style="width:92%;background:var(--accent)"></div></div>
                    <span style="font-size:11px">92%</span>
                  </div>
                </td>
                <td><span class="badge badge-amber">Partial</span></td>
                <td><button class="btn btn-ghost" style="font-size:11px;padding:4px 10px">View</button></td>
              </tr>
              <tr>
                <td>
                  <div style="display:flex;align-items:center;gap:10px">
                    <div class="avatar" style="width:30px;height:30px;font-size:11px;background:linear-gradient(135deg,#38d9d9,#1da87a)">RR</div>
                    <div><div style="font-weight:500">Ramon Reyes</div><div style="font-size:10px;color:var(--muted)">M · 16 yrs</div></div>
                  </div>
                </td>
                <td class="mono">100201-2025</td>
                <td><span class="badge badge-green">Grade 11-STEM</span></td>
                <td style="color:var(--muted)">09321122334</td>
                <td style="font-weight:600;color:var(--teal)">85.3</td>
                <td>
                  <div style="display:flex;align-items:center;gap:6px">
                    <div class="progress-bar" style="width:60px"><div class="progress-fill" style="width:88%;background:var(--teal)"></div></div>
                    <span style="font-size:11px">88%</span>
                  </div>
                </td>
                <td><span class="badge badge-green">Paid</span></td>
                <td><button class="btn btn-ghost" style="font-size:11px;padding:4px 10px">View</button></td>
              </tr>
              <tr>
                <td>
                  <div style="display:flex;align-items:center;gap:10px">
                    <div class="avatar" style="width:30px;height:30px;font-size:11px;background:linear-gradient(135deg,#f5a623,#f06060)">AL</div>
                    <div><div style="font-weight:500">Ana Lopez</div><div style="font-size:10px;color:var(--muted)">F · 13 yrs</div></div>
                  </div>
                </td>
                <td class="mono">100244-2025</td>
                <td><span class="badge badge-amber">Grade 7-A</span></td>
                <td style="color:var(--muted)">09173456789</td>
                <td style="font-weight:600;color:var(--amber)">79.8</td>
                <td>
                  <div style="display:flex;align-items:center;gap:6px">
                    <div class="progress-bar" style="width:60px"><div class="progress-fill" style="width:78%;background:var(--amber)"></div></div>
                    <span style="font-size:11px">78%</span>
                  </div>
                </td>
                <td><span class="badge badge-red">Unpaid</span></td>
                <td><button class="btn btn-ghost" style="font-size:11px;padding:4px 10px">View</button></td>
              </tr>
              <tr>
                <td>
                  <div style="display:flex;align-items:center;gap:10px">
                    <div class="avatar" style="width:30px;height:30px;font-size:11px;background:linear-gradient(135deg,#4f8cff,#38d9d9)">PG</div>
                    <div><div style="font-weight:500">Paolo Garcia</div><div style="font-size:10px;color:var(--muted)">M · 17 yrs</div></div>
                  </div>
                </td>
                <td class="mono">100189-2025</td>
                <td><span class="badge badge-blue">Grade 12-ABM</span></td>
                <td style="color:var(--muted)">09456789012</td>
                <td style="font-weight:600;color:var(--red)">74.2</td>
                <td>
                  <div style="display:flex;align-items:center;gap:6px">
                    <div class="progress-bar" style="width:60px"><div class="progress-fill" style="width:72%;background:var(--red)"></div></div>
                    <span style="font-size:11px">72%</span>
                  </div>
                </td>
                <td><span class="badge badge-amber">Partial</span></td>
                <td><button class="btn btn-ghost" style="font-size:11px;padding:4px 10px">View</button></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- TEACHERS PAGE -->
    <div class="page" id="page-teachers">
      <div class="card">
        <div class="card-header">
          <div><div class="card-title">Faculty Directory</div><div class="card-subtitle">148 active faculty members</div></div>
          <div class="card-action"><button class="btn btn-primary">+ Add Faculty</button></div>
        </div>
        <div class="table-wrap">
          <table>
            <thead>
              <tr><th>Faculty</th><th>Employee ID</th><th>Department</th><th>Subjects</th><th>Classes</th><th>Status</th><th>Action</th></tr>
            </thead>
            <tbody>
              <tr>
                <td><div style="display:flex;align-items:center;gap:10px"><div class="avatar" style="width:30px;height:30px;font-size:11px;background:linear-gradient(135deg,#2dd4a0,#4f8cff)">LR</div><div><div style="font-weight:500">Liza Reyes</div><div style="font-size:10px;color:var(--muted)">Mathematics Dept.</div></div></div></td>
                <td class="mono">EMP-2019-042</td>
                <td><span class="badge badge-blue">Mathematics</span></td>
                <td style="font-size:11px;color:var(--muted)">Algebra, Calculus</td>
                <td style="font-weight:500">5 sections</td>
                <td><span class="badge badge-green">Active</span></td>
                <td><button class="btn btn-ghost" style="font-size:11px;padding:4px 10px">View</button></td>
              </tr>
              <tr>
                <td><div style="display:flex;align-items:center;gap:10px"><div class="avatar" style="width:30px;height:30px;font-size:11px;background:linear-gradient(135deg,#e975c3,#f5a623)">JS</div><div><div style="font-weight:500">Jose Santos</div><div style="font-size:10px;color:var(--muted)">Science Dept.</div></div></div></td>
                <td class="mono">EMP-2021-067</td>
                <td><span class="badge badge-green">Science</span></td>
                <td style="font-size:11px;color:var(--muted)">Physics, Chemistry</td>
                <td style="font-weight:500">4 sections</td>
                <td><span class="badge badge-green">Active</span></td>
                <td><button class="btn btn-ghost" style="font-size:11px;padding:4px 10px">View</button></td>
              </tr>
              <tr>
                <td><div style="display:flex;align-items:center;gap:10px"><div class="avatar" style="width:30px;height:30px;font-size:11px;background:linear-gradient(135deg,#7c5cfc,#38d9d9)">AC</div><div><div style="font-weight:500">Ana Cruz</div><div style="font-size:10px;color:var(--muted)">English Dept.</div></div></div></td>
                <td class="mono">EMP-2018-023</td>
                <td><span class="badge badge-purple">English</span></td>
                <td style="font-size:11px;color:var(--muted)">Literature, Grammar</td>
                <td style="font-weight:500">6 sections</td>
                <td><span class="badge badge-green">Active</span></td>
                <td><button class="btn btn-ghost" style="font-size:11px;padding:4px 10px">View</button></td>
              </tr>
              <tr>
                <td><div style="display:flex;align-items:center;gap:10px"><div class="avatar" style="width:30px;height:30px;font-size:11px;background:linear-gradient(135deg,#f06060,#f5a623)">RM</div><div><div style="font-weight:500">Roberto Mañosa</div><div style="font-size:10px;color:var(--muted)">Social Studies</div></div></div></td>
                <td class="mono">EMP-2020-055</td>
                <td><span class="badge badge-amber">Social Studies</span></td>
                <td style="font-size:11px;color:var(--muted)">History, Araling Panlipunan</td>
                <td style="font-weight:500">4 sections</td>
                <td><span class="badge badge-amber">On Leave</span></td>
                <td><button class="btn btn-ghost" style="font-size:11px;padding:4px 10px">View</button></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- CLASSES PAGE -->
    <div class="page" id="page-classes">
      <div class="three-col" style="margin-bottom:16px">
        <div class="stat-card"><div class="stat-accent" style="background:var(--accent)"></div><div class="stat-label">Total Sections</div><div class="stat-value" style="font-size:22px">62</div><div class="stat-change">Across 6 grade levels</div></div>
        <div class="stat-card"><div class="stat-accent" style="background:var(--green)"></div><div class="stat-label">Active Classes</div><div class="stat-value" style="font-size:22px">58</div><div class="stat-change">4 under re-assignment</div></div>
        <div class="stat-card"><div class="stat-accent" style="background:var(--amber)"></div><div class="stat-label">Avg Class Size</div><div class="stat-value" style="font-size:22px">46</div><div class="stat-change">↓ 2 from last year</div></div>
      </div>
      <div class="card">
        <div class="card-header">
          <div class="card-title">All Sections</div>
          <div class="card-action"><button class="btn btn-primary">+ New Section</button></div>
        </div>
        <div class="table-wrap">
          <table>
            <thead><tr><th>Section</th><th>Level</th><th>Adviser</th><th>Room</th><th>Students</th><th>Avg GWA</th><th>Status</th></tr></thead>
            <tbody>
              <tr><td style="font-weight:600">10-Rizal</td><td>Grade 10</td><td>Ms. Reyes</td><td>Room 201</td><td>48</td><td style="color:var(--green)">92.4</td><td><span class="badge badge-green">Active</span></td></tr>
              <tr><td style="font-weight:600">10-Bonifacio</td><td>Grade 10</td><td>Mr. Tan</td><td>Room 202</td><td>45</td><td style="color:var(--accent)">88.7</td><td><span class="badge badge-green">Active</span></td></tr>
              <tr><td style="font-weight:600">9-Luna</td><td>Grade 9</td><td>Ms. Santos</td><td>Room 105</td><td>50</td><td style="color:var(--accent)">87.2</td><td><span class="badge badge-green">Active</span></td></tr>
              <tr><td style="font-weight:600">11-STEM A</td><td>Grade 11</td><td>Mr. Garcia</td><td>STEM Lab</td><td>42</td><td style="color:var(--teal)">85.1</td><td><span class="badge badge-green">Active</span></td></tr>
              <tr><td style="font-weight:600">12-ABM A</td><td>Grade 12</td><td>Ms. Dizon</td><td>Room 301</td><td>38</td><td style="color:var(--amber)">81.9</td><td><span class="badge badge-green">Active</span></td></tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- SCHEDULE PAGE -->
    <div class="page" id="page-schedule">
      <div style="display:flex;gap:10px;margin-bottom:18px;align-items:center">
        <span style="font-size:13px;color:var(--muted)">Section:</span>
        <span class="chip active">10-Rizal</span>
        <span class="chip">9-Luna</span>
        <span class="chip">11-STEM</span>
        <span style="margin-left:auto;font-size:12px;color:var(--muted)">S.Y. 2025–2026 · Q3</span>
      </div>
      <div class="card">
        <div class="card-header"><div class="card-title">Class Schedule — Grade 10-Rizal</div></div>
        <div style="padding:16px;overflow-x:auto">
          <div class="schedule-grid">
            <div class="sch-header">Time</div>
            <div class="sch-header">Monday</div>
            <div class="sch-header">Tuesday</div>
            <div class="sch-header">Wednesday</div>
            <div class="sch-header">Thursday</div>
            <div class="sch-header">Friday</div>

            <div class="sch-time">7:30</div>
            <div class="sch-cell"><div class="sch-event math"><div style="font-weight:600">Mathematics</div><div style="opacity:.7">Ms. Reyes</div></div></div>
            <div class="sch-cell"><div class="sch-event science"><div style="font-weight:600">Science</div><div style="opacity:.7">Mr. Santos</div></div></div>
            <div class="sch-cell"></div>
            <div class="sch-cell"><div class="sch-event math"><div style="font-weight:600">Mathematics</div><div style="opacity:.7">Ms. Reyes</div></div></div>
            <div class="sch-cell"><div class="sch-event english"><div style="font-weight:600">English</div><div style="opacity:.7">Ms. Cruz</div></div></div>

            <div class="sch-time">8:30</div>
            <div class="sch-cell"><div class="sch-event english"><div style="font-weight:600">English</div><div style="opacity:.7">Ms. Cruz</div></div></div>
            <div class="sch-cell"><div class="sch-event history"><div style="font-weight:600">AP/History</div><div style="opacity:.7">Mr. Mañosa</div></div></div>
            <div class="sch-cell"><div class="sch-event science"><div style="font-weight:600">Science</div><div style="opacity:.7">Mr. Santos</div></div></div>
            <div class="sch-cell"><div class="sch-event history"><div style="font-weight:600">AP/History</div><div style="opacity:.7">Mr. Mañosa</div></div></div>
            <div class="sch-cell"><div class="sch-event math"><div style="font-weight:600">Mathematics</div><div style="opacity:.7">Ms. Reyes</div></div></div>

            <div class="sch-time">9:30</div>
            <div class="sch-cell"></div>
            <div class="sch-cell"></div>
            <div class="sch-cell"></div>
            <div class="sch-cell"></div>
            <div class="sch-cell"></div>

            <div class="sch-time">10:00</div>
            <div class="sch-cell"><div class="sch-event history"><div style="font-weight:600">AP/History</div><div style="opacity:.7">Mr. Mañosa</div></div></div>
            <div class="sch-cell"><div class="sch-event english"><div style="font-weight:600">English</div><div style="opacity:.7">Ms. Cruz</div></div></div>
            <div class="sch-cell"><div class="sch-event math"><div style="font-weight:600">Mathematics</div><div style="opacity:.7">Ms. Reyes</div></div></div>
            <div class="sch-cell"><div class="sch-event science"><div style="font-weight:600">Science</div><div style="opacity:.7">Mr. Santos</div></div></div>
            <div class="sch-cell"><div class="sch-event pe"><div style="font-weight:600">P.E.</div><div style="opacity:.7">Mr. Lim</div></div></div>

            <div class="sch-time">11:00</div>
            <div class="sch-cell"><div class="sch-event science"><div style="font-weight:600">Science</div><div style="opacity:.7">Mr. Santos</div></div></div>
            <div class="sch-cell"><div class="sch-event pe"><div style="font-weight:600">P.E.</div><div style="opacity:.7">Mr. Lim</div></div></div>
            <div class="sch-cell"><div class="sch-event english"><div style="font-weight:600">English</div><div style="opacity:.7">Ms. Cruz</div></div></div>
            <div class="sch-cell"><div class="sch-event math"><div style="font-weight:600">Mathematics</div><div style="opacity:.7">Ms. Reyes</div></div></div>
            <div class="sch-cell"><div class="sch-event history"><div style="font-weight:600">AP/History</div><div style="opacity:.7">Mr. Mañosa</div></div></div>
          </div>
        </div>
        <div style="padding:14px 20px;display:flex;gap:12px;border-top:1px solid var(--border)">
          <div style="display:flex;align-items:center;gap:6px"><div style="width:12px;height:12px;border-radius:3px;background:rgba(79,140,255,.35)"></div><span style="font-size:11px;color:var(--muted)">Mathematics</span></div>
          <div style="display:flex;align-items:center;gap:6px"><div style="width:12px;height:12px;border-radius:3px;background:rgba(45,212,160,.18)"></div><span style="font-size:11px;color:var(--muted)">Science</span></div>
          <div style="display:flex;align-items:center;gap:6px"><div style="width:12px;height:12px;border-radius:3px;background:rgba(233,117,195,.18)"></div><span style="font-size:11px;color:var(--muted)">English</span></div>
          <div style="display:flex;align-items:center;gap:6px"><div style="width:12px;height:12px;border-radius:3px;background:rgba(245,166,35,.18)"></div><span style="font-size:11px;color:var(--muted)">AP/History</span></div>
          <div style="display:flex;align-items:center;gap:6px"><div style="width:12px;height:12px;border-radius:3px;background:rgba(56,217,217,.18)"></div><span style="font-size:11px;color:var(--muted)">P.E.</span></div>
        </div>
      </div>
    </div>

    <!-- GRADES PAGE -->
    <div class="page" id="page-grades">
      <div style="display:flex;gap:10px;margin-bottom:18px">
        <span class="chip active">Q1</span><span class="chip">Q2</span><span class="chip">Q3</span><span class="chip">Q4</span>
        <span style="margin-left:auto;display:flex;gap:8px">
          <button class="btn btn-ghost">Download Report</button>
          <button class="btn btn-primary">Post Grades</button>
        </span>
      </div>
      <div class="card">
        <div class="card-header"><div class="card-title">Grading Sheet — Grade 10-Rizal · Q3</div></div>
        <div class="table-wrap">
          <table>
            <thead><tr><th>Student</th><th>Math</th><th>Science</th><th>English</th><th>AP</th><th>P.E.</th><th>GWA</th><th>Remarks</th></tr></thead>
            <tbody>
              <tr><td style="font-weight:500">Juan Dela Cruz</td><td>95</td><td>92</td><td>90</td><td>88</td><td>97</td><td style="color:var(--green);font-weight:600">92.4</td><td><span class="badge badge-green">Excellent</span></td></tr>
              <tr><td style="font-weight:500">Maria Santos</td><td>88</td><td>85</td><td>91</td><td>84</td><td>92</td><td style="color:var(--accent);font-weight:600">88.0</td><td><span class="badge badge-blue">Good</span></td></tr>
              <tr><td style="font-weight:500">Ramon Reyes</td><td>82</td><td>90</td><td>84</td><td>79</td><td>91</td><td style="color:var(--accent);font-weight:600">85.2</td><td><span class="badge badge-blue">Good</span></td></tr>
              <tr><td style="font-weight:500">Ana Lopez</td><td>76</td><td>80</td><td>83</td><td>74</td><td>85</td><td style="color:var(--amber);font-weight:600">79.6</td><td><span class="badge badge-amber">Satisfactory</span></td></tr>
              <tr><td style="font-weight:500">Paolo Garcia</td><td>72</td><td>70</td><td>75</td><td>76</td><td>78</td><td style="color:var(--red);font-weight:600">74.2</td><td><span class="badge badge-red">Needs Improvement</span></td></tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- ATTENDANCE PAGE -->
    <div class="page" id="page-attendance">
      <div class="two-col">
        <div class="card">
          <div class="card-header">
            <div><div class="card-title">Mark Attendance</div><div class="card-subtitle">Grade 10-Rizal · March 25, 2026</div></div>
            <div class="card-action"><button class="btn btn-primary">Save</button></div>
          </div>
          <div class="table-wrap">
            <table>
              <thead><tr><th>Student</th><th>Status</th><th>Remarks</th></tr></thead>
              <tbody>
                <tr><td style="font-weight:500">Juan Dela Cruz</td><td><span class="badge badge-green">Present</span></td><td style="color:var(--muted);font-size:11px">—</td></tr>
                <tr><td style="font-weight:500">Maria Santos</td><td><span class="badge badge-amber">Late</span></td><td style="color:var(--muted);font-size:11px">10 min late</td></tr>
                <tr><td style="font-weight:500">Ramon Reyes</td><td><span class="badge badge-green">Present</span></td><td style="color:var(--muted);font-size:11px">—</td></tr>
                <tr><td style="font-weight:500">Ana Lopez</td><td><span class="badge badge-red">Absent</span></td><td style="color:var(--muted);font-size:11px">Sick — notified</td></tr>
                <tr><td style="font-weight:500">Paolo Garcia</td><td><span class="badge badge-green">Present</span></td><td style="color:var(--muted);font-size:11px">—</td></tr>
              </tbody>
            </table>
          </div>
        </div>
        <div class="card">
          <div class="card-header"><div class="card-title">Monthly Overview</div><div class="card-subtitle">March 2026</div></div>
          <div class="card-body">
            <div style="display:flex;gap:12px;margin-bottom:14px">
              <div style="display:flex;align-items:center;gap:6px"><div style="width:10px;height:10px;border-radius:2px;background:rgba(45,212,160,.25)"></div><span style="font-size:11px;color:var(--muted)">Present</span></div>
              <div style="display:flex;align-items:center;gap:6px"><div style="width:10px;height:10px;border-radius:2px;background:rgba(240,96,96,.18)"></div><span style="font-size:11px;color:var(--muted)">Absent</span></div>
              <div style="display:flex;align-items:center;gap:6px"><div style="width:10px;height:10px;border-radius:2px;background:rgba(245,166,35,.18)"></div><span style="font-size:11px;color:var(--muted)">Late</span></div>
            </div>
            <div style="display:grid;grid-template-columns:repeat(7,1fr);gap:3px;margin-bottom:8px">
              <div style="font-size:9px;color:var(--muted);text-align:center">M</div>
              <div style="font-size:9px;color:var(--muted);text-align:center">T</div>
              <div style="font-size:9px;color:var(--muted);text-align:center">W</div>
              <div style="font-size:9px;color:var(--muted);text-align:center">T</div>
              <div style="font-size:9px;color:var(--muted);text-align:center">F</div>
              <div style="font-size:9px;color:var(--muted);text-align:center">S</div>
              <div style="font-size:9px;color:var(--muted);text-align:center">S</div>
            </div>
            <div class="att-grid" id="attGrid"></div>
            <div style="margin-top:16px;display:flex;gap:16px">
              <div><div style="font-size:10px;color:var(--muted)">Present Rate</div><div style="font-size:18px;font-weight:700;color:var(--green)">94%</div></div>
              <div><div style="font-size:10px;color:var(--muted)">Total Absent</div><div style="font-size:18px;font-weight:700;color:var(--red)">12</div></div>
              <div><div style="font-size:10px;color:var(--muted)">Total Late</div><div style="font-size:18px;font-weight:700;color:var(--amber)">8</div></div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- FINANCE PAGE -->
    <div class="page" id="page-finance">
      <div class="stat-grid" style="grid-template-columns:repeat(3,1fr)">
        <div class="stat-card"><div class="stat-accent" style="background:var(--green)"></div><div class="stat-label">Total Collected</div><div class="stat-value" style="font-size:24px">₱4,218,500</div><div class="stat-change">SY 2025-2026</div></div>
        <div class="stat-card"><div class="stat-accent" style="background:var(--amber)"></div><div class="stat-label">Outstanding</div><div class="stat-value" style="font-size:24px">₱812,000</div><div class="stat-change down">382 students</div></div>
        <div class="stat-card"><div class="stat-accent" style="background:var(--accent)"></div><div class="stat-label">Scholarships</div><div class="stat-value" style="font-size:24px">₱640,000</div><div class="stat-change">48 scholars</div></div>
      </div>
      <div class="card">
        <div class="card-header">
          <div class="card-title">Recent Transactions</div>
          <div class="card-action"><button class="btn btn-primary">+ Record Payment</button></div>
        </div>
        <div>
          <div class="finance-item">
            <div class="finance-icon" style="background:rgba(45,212,160,.12)">💳</div>
            <div class="finance-details"><div class="finance-name">Juan Dela Cruz — Tuition Q3</div><div class="finance-date">Mar 25, 2026 · OR#0042881</div></div>
            <div class="finance-amount" style="color:var(--green)">+₱8,500</div>
          </div>
          <div class="finance-item">
            <div class="finance-icon" style="background:rgba(45,212,160,.12)">💳</div>
            <div class="finance-details"><div class="finance-name">Maria Santos — Tuition Q3 (Partial)</div><div class="finance-date">Mar 24, 2026 · OR#0042880</div></div>
            <div class="finance-amount" style="color:var(--amber)">+₱4,250</div>
          </div>
          <div class="finance-item">
            <div class="finance-icon" style="background:rgba(240,96,96,.12)">🏫</div>
            <div class="finance-details"><div class="finance-name">Utilities — March 2026</div><div class="finance-date">Mar 20, 2026 · EXP#0012</div></div>
            <div class="finance-amount" style="color:var(--red)">-₱24,800</div>
          </div>
          <div class="finance-item">
            <div class="finance-icon" style="background:rgba(45,212,160,.12)">💳</div>
            <div class="finance-details"><div class="finance-name">Ramon Reyes — Tuition Q3 + Misc</div><div class="finance-date">Mar 19, 2026 · OR#0042879</div></div>
            <div class="finance-amount" style="color:var(--green)">+₱9,000</div>
          </div>
          <div class="finance-item">
            <div class="finance-icon" style="background:rgba(240,96,96,.12)">📦</div>
            <div class="finance-details"><div class="finance-name">Lab Supplies — Science Dept.</div><div class="finance-date">Mar 18, 2026 · EXP#0011</div></div>
            <div class="finance-amount" style="color:var(--red)">-₱15,600</div>
          </div>
        </div>
      </div>
    </div>

    <!-- LIBRARY -->
    <div class="page" id="page-library">
      <div class="stat-grid" style="grid-template-columns:repeat(4,1fr);margin-bottom:20px">
        <div class="stat-card"><div class="stat-accent" style="background:var(--accent2)"></div><div class="stat-label">Total Books</div><div class="stat-value" style="font-size:22px">8,420</div></div>
        <div class="stat-card"><div class="stat-accent" style="background:var(--accent)"></div><div class="stat-label">Available</div><div class="stat-value" style="font-size:22px">7,189</div></div>
        <div class="stat-card"><div class="stat-accent" style="background:var(--amber)"></div><div class="stat-label">Checked Out</div><div class="stat-value" style="font-size:22px">1,231</div></div>
        <div class="stat-card"><div class="stat-accent" style="background:var(--red)"></div><div class="stat-label">Overdue</div><div class="stat-value" style="font-size:22px">47</div><div class="stat-change down">↑ 3 this week</div></div>
      </div>
      <div class="card">
        <div class="card-header"><div class="card-title">Book Inventory</div><div class="card-action"><button class="btn btn-primary">+ Add Book</button></div></div>
        <div class="table-wrap">
          <table>
            <thead><tr><th>Title</th><th>Author</th><th>ISBN</th><th>Category</th><th>Copies</th><th>Status</th></tr></thead>
            <tbody>
              <tr><td style="font-weight:500">Calculus: Early Transcendentals</td><td>James Stewart</td><td class="mono">978-1-285-74155-0</td><td><span class="badge badge-blue">Mathematics</span></td><td>12</td><td><span class="badge badge-green">10 Available</span></td></tr>
              <tr><td style="font-weight:500">Biology: The Living Science</td><td>Miller & Levine</td><td class="mono">978-0-13-379659-9</td><td><span class="badge badge-green">Science</span></td><td>20</td><td><span class="badge badge-amber">8 Available</span></td></tr>
              <tr><td style="font-weight:500">World History: Connections</td><td>Ellis & Esler</td><td class="mono">978-0-13-337419-7</td><td><span class="badge badge-amber">History</span></td><td>15</td><td><span class="badge badge-green">15 Available</span></td></tr>
              <tr><td style="font-weight:500">Noli Me Tangere</td><td>Jose Rizal</td><td class="mono">978-971-27-1606-0</td><td><span class="badge badge-purple">Filipino Lit</span></td><td>30</td><td><span class="badge badge-red">3 Overdue</span></td></tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- EVENTS -->
    <div class="page" id="page-events">
      <div class="card" style="margin-bottom:16px">
        <div class="card-header"><div class="card-title">Upcoming Events</div><div class="card-action"><button class="btn btn-primary">+ Create Event</button></div></div>
        <div>
          <div class="announce-item">
            <div style="display:flex;gap:14px;align-items:flex-start">
              <div style="background:rgba(79,140,255,.12);border-radius:10px;padding:10px 14px;text-align:center;flex-shrink:0">
                <div style="font-size:22px;font-weight:700;color:var(--accent);line-height:1">28</div>
                <div style="font-size:10px;color:var(--muted)">MAR</div>
              </div>
              <div>
                <div class="announce-title">Foundation Day Celebration</div>
                <div class="announce-body">Annual school foundation day with parade, performances, and cultural presentations from all grade levels.</div>
                <div style="display:flex;gap:8px;margin-top:8px">
                  <span class="badge badge-blue">Whole School</span>
                  <span class="badge badge-green">Approved</span>
                </div>
              </div>
            </div>
          </div>
          <div class="announce-item">
            <div style="display:flex;gap:14px;align-items:flex-start">
              <div style="background:rgba(45,212,160,.12);border-radius:10px;padding:10px 14px;text-align:center;flex-shrink:0">
                <div style="font-size:22px;font-weight:700;color:var(--green);line-height:1">04</div>
                <div style="font-size:10px;color:var(--muted)">APR</div>
              </div>
              <div>
                <div class="announce-title">Q3 Periodic Examinations</div>
                <div class="announce-body">Third quarter examinations for all grade levels. Schedule distribution will be posted in all classrooms.</div>
                <div style="display:flex;gap:8px;margin-top:8px">
                  <span class="badge badge-amber">Academic</span>
                  <span class="badge badge-green">Confirmed</span>
                </div>
              </div>
            </div>
          </div>
          <div class="announce-item">
            <div style="display:flex;gap:14px;align-items:flex-start">
              <div style="background:rgba(233,117,195,.12);border-radius:10px;padding:10px 14px;text-align:center;flex-shrink:0">
                <div style="font-size:22px;font-weight:700;color:var(--pink);line-height:1">15</div>
                <div style="font-size:10px;color:var(--muted)">APR</div>
              </div>
              <div>
                <div class="announce-title">Science Fair 2026</div>
                <div class="announce-body">Annual inter-school science fair. Entries deadline is April 1. All Grade 9–12 students are eligible to participate.</div>
                <div style="display:flex;gap:8px;margin-top:8px">
                  <span class="badge badge-purple">Grades 9–12</span>
                  <span class="badge badge-amber">Open for Entries</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- ANNOUNCEMENTS -->
    <div class="page" id="page-announcements">
      <div class="card">
        <div class="card-header"><div class="card-title">School Announcements</div><div class="card-action"><button class="btn btn-primary">+ Post Announcement</button></div></div>
        <div>
          <div class="announce-item">
            <div style="display:flex;gap:10px;margin-bottom:8px;align-items:center">
              <span class="badge badge-red">Urgent</span>
              <div class="announce-title" style="margin:0">Foundation Day Parade Route Update</div>
            </div>
            <div class="announce-body">The parade route for the upcoming Foundation Day Celebration has been updated. Students must report by 6:30 AM at the quadrangle. PE uniform required.</div>
            <div class="announce-meta">Posted by Admin · March 25, 2026 · 8:30 AM</div>
          </div>
          <div class="announce-item">
            <div style="display:flex;gap:10px;margin-bottom:8px;align-items:center">
              <span class="badge badge-blue">Academic</span>
              <div class="announce-title" style="margin:0">Q3 Exam Schedule Released</div>
            </div>
            <div class="announce-body">The third quarter examination schedule has been finalized and posted. Room assignments will be announced by advisers. Bring your school ID on exam days.</div>
            <div class="announce-meta">Posted by Registrar · March 22, 2026 · 2:00 PM</div>
          </div>
          <div class="announce-item">
            <div style="display:flex;gap:10px;margin-bottom:8px;align-items:center">
              <span class="badge badge-green">Finance</span>
              <div class="announce-title" style="margin:0">Tuition Deadline Reminder — Q3</div>
            </div>
            <div class="announce-body">Kindly settle outstanding tuition balances for Q3 on or before March 31. Unpaid students may not be allowed to take the periodic examinations.</div>
            <div class="announce-meta">Posted by Finance Office · March 20, 2026 · 9:00 AM</div>
          </div>
        </div>
      </div>
    </div>

    <!-- SETTINGS -->
    <div class="page" id="page-settings">
      <div class="two-col">
        <div class="card">
          <div class="card-header"><div class="card-title">School Information</div></div>
          <div class="card-body">
            <div class="form-group"><label class="form-label">School Name</label><input class="form-input" value="Cagayan de Oro National High School"></div>
            <div class="form-group"><label class="form-label">School ID (DepEd)</label><input class="form-input" value="301234"></div>
            <div class="form-group"><label class="form-label">Region</label><input class="form-input" value="Region X — Northern Mindanao"></div>
            <div class="form-group"><label class="form-label">Division</label><input class="form-input" value="Cagayan de Oro City"></div>
            <div class="form-row">
              <div class="form-group"><label class="form-label">School Year</label><select class="form-select"><option>2025–2026</option><option>2024–2025</option></select></div>
              <div class="form-group"><label class="form-label">Quarter</label><select class="form-select"><option>Q3</option><option>Q2</option><option>Q1</option><option>Q4</option></select></div>
            </div>
            <button class="btn btn-primary" style="width:100%;margin-top:6px">Save Changes</button>
          </div>
        </div>
        <div class="card">
          <div class="card-header"><div class="card-title">System Settings</div></div>
          <div class="card-body">
            <div class="form-group"><label class="form-label">Grading System</label><select class="form-select"><option>DepEd K-12 (1-100)</option><option>GPA (4.0 scale)</option></select></div>
            <div class="form-group"><label class="form-label">Passing Grade</label><input class="form-input" value="75"></div>
            <div class="form-group"><label class="form-label">Attendance Threshold (%)</label><input class="form-input" value="80"></div>
            <div class="form-group"><label class="form-label">Default Currency</label><select class="form-select"><option>PHP (₱)</option><option>USD ($)</option></select></div>
            <div class="form-group"><label class="form-label">Timezone</label><select class="form-select"><option>Asia/Manila (PHT, UTC+8)</option></select></div>
            <button class="btn btn-primary" style="width:100%;margin-top:6px">Update Settings</button>
          </div>
        </div>
      </div>
    </div>

  </div>
</main>

<script>
const pageTitles = {
  dashboard: 'Dashboard',
  students: 'Students',
  teachers: 'Faculty',
  classes: 'Classes & Sections',
  schedule: 'Class Schedule',
  grades: 'Grades & Assessments',
  attendance: 'Attendance',
  finance: 'Finance',
  library: 'Library',
  events: 'Events & Calendar',
  announcements: 'Announcements',
  settings: 'Settings',
};

function switchPage(name, el) {
  document.querySelectorAll('.page').forEach(p => p.classList.remove('active'));
  document.querySelectorAll('.nav-item').forEach(n => n.classList.remove('active'));
  const pg = document.getElementById('page-' + name);
  if (pg) pg.classList.add('active');
  if (el) el.classList.add('active');
  document.getElementById('pageTitle').textContent = pageTitles[name] || name;
}

function handleAdd() {
  const title = document.getElementById('pageTitle').textContent;
  alert('Open "Add New" modal for: ' + title + '\n(Wire up your Laravel controller here)');
}

// Generate attendance calendar
(function() {
  const grid = document.getElementById('attGrid');
  if (!grid) return;
  const statuses = ['present','present','present','present','absent','late','present','empty','present','present','late','present','present','empty','empty','present','absent','present','present','present','empty','empty','present','present','present','absent','late','present','present','present','empty'];
  statuses.forEach((s, i) => {
    const d = document.createElement('div');
    d.className = 'att-cell ' + s;
    d.textContent = s !== 'empty' ? (i + 1) : '';
    grid.appendChild(d);
  });
})();
</script>
</body>
</html>
