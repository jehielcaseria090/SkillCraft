<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width,initial-scale=1"/>
<title>SkillCraft Admin — @yield('title','Dashboard')</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet"/>
<style>
:root {
  --accent:  #60a5fa;
  --accent2: #c084fc;
  --accent3: #34d399;
  --warn:    #fbbf24;
  --text:    #ffffff;
  --muted:   #cbd5e1;
  --border:  rgba(255,255,255,0.15);
  --card:    rgba(255,255,255,0.08);
  --radius:  14px;
}

*,*::before,*::after { box-sizing:border-box; margin:0; padding:0; }

body {
  font-family: 'Inter', sans-serif;
  color: var(--text);
  min-height: 100vh;
  display: flex;
  overflow-x: hidden;
  /* School background full page */
  background:
    linear-gradient(135deg,
      rgba(5,10,20,0.78) 0%,
      rgba(10,18,35,0.72) 100%),
    url('/images/juang_bg.png') center center / cover no-repeat fixed;
}

::-webkit-scrollbar { width:5px; height:5px; }
::-webkit-scrollbar-track { background:transparent; }
::-webkit-scrollbar-thumb { background:rgba(255,255,255,0.2); border-radius:99px; }

/* ── SIDEBAR ──────────────────────────────────────────────────────── */
.sidebar {
  width: 240px;
  min-height: 100vh;
  /* Glass effect — school photo visible through sidebar */
  background: rgba(5,10,22,0.60);
  backdrop-filter: blur(24px);
  -webkit-backdrop-filter: blur(24px);
  border-right: 1px solid rgba(255,255,255,0.12);
  display: flex;
  flex-direction: column;
  padding: 28px 0;
  position: fixed;
  top:0; left:0; bottom:0;
  z-index: 100;
}

.logo {
  padding: 0 24px 28px;
  display: flex;
  align-items: center;
  gap: 12px;
}

.logo-icon {
  width: 40px;
  height: 40px;
  border-radius: 10px;
  object-fit: cover;
  box-shadow: 0 0 0 2px rgba(96,165,250,0.4);
}

.logo-text {
  font-family: 'Poppins', sans-serif;
  font-weight: 800;
  font-size: 19px;
  color: #ffffff;
  text-shadow: 0 1px 8px rgba(0,0,0,0.5);
}
.logo-text span { color: var(--accent); }

.nav-label {
  font-size: 9.5px;
  text-transform: uppercase;
  letter-spacing: 2px;
  color: rgba(255,255,255,0.45);
  padding: 0 20px 7px;
  margin-top: 18px;
  font-family: 'Poppins', sans-serif;
  font-weight: 700;
}

.nav-section { padding: 0 10px; margin-bottom: 4px; }

.nav-item {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 10px 14px;
  border-radius: 10px;
  cursor: pointer;
  transition: all .18s ease;
  font-size: 13.5px;
  font-weight: 500;
  color: rgba(255,255,255,0.65);
  margin-bottom: 2px;
  text-decoration: none;
  font-family: 'Inter', sans-serif;
}

.nav-item:hover {
  background: rgba(255,255,255,0.10);
  color: #ffffff;
  transform: translateX(3px);
}

.nav-item.active {
  background: rgba(96,165,250,0.18);
  color: #93c5fd;
  border: 1px solid rgba(96,165,250,0.25);
}

.nav-item .nav-icon {
  width: 17px;
  height: 17px;
  flex-shrink: 0;
  opacity: .6;
  transition: opacity .18s;
}
.nav-item:hover .nav-icon,
.nav-item.active .nav-icon { opacity: 1; }

.sidebar-footer {
  margin-top: auto;
  padding: 16px 20px;
  border-top: 1px solid rgba(255,255,255,0.10);
}

.avatar {
  width: 36px;
  height: 36px;
  border-radius: 50%;
  background: linear-gradient(135deg, var(--accent), var(--accent2));
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 13px;
  font-weight: 700;
  color: #fff;
  flex-shrink: 0;
  font-family: 'Poppins', sans-serif;
  box-shadow: 0 2px 10px rgba(96,165,250,0.3);
}

.admin-chip { display:flex; align-items:center; gap:10px; }

/* ── MAIN ─────────────────────────────────────────────────────────── */
.main {
  margin-left: 240px;
  flex: 1;
  padding: 32px 36px;
  min-height: 100vh;
}

/* ── TOPBAR ───────────────────────────────────────────────────────── */
.topbar {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 36px;
}

.page-title {
  font-family: 'Poppins', sans-serif;
  font-size: 30px;
  font-weight: 800;
  letter-spacing: -.5px;
  color: #ffffff;
  text-shadow: 0 2px 20px rgba(0,0,0,0.6);
}
.page-title span { color: var(--accent); }

/* ── BUTTONS ──────────────────────────────────────────────────────── */
.btn {
  display: inline-flex;
  align-items: center;
  gap: 7px;
  padding: 9px 18px;
  border-radius: 9px;
  font-size: 13px;
  font-family: 'Inter', sans-serif;
  font-weight: 500;
  cursor: pointer;
  border: none;
  transition: all .18s ease;
  text-decoration: none;
}

.btn-primary { background: var(--accent); color: #fff; }
.btn-primary:hover { background: #3b82f6; transform: translateY(-1px); }

.btn-danger {
  background: rgba(248,82,82,.15);
  color: #fca5a5;
  border: 1px solid rgba(248,82,82,.25);
}
.btn-danger:hover { background: rgba(248,82,82,.25); }

.btn-ghost {
  background: rgba(255,255,255,0.08);
  color: #e2e8f0;
  border: 1px solid rgba(255,255,255,0.15);
}
.btn-ghost:hover { background: rgba(255,255,255,0.14); }

/* ── PANELS / CARDS ───────────────────────────────────────────────── */
.panel {
  background: rgba(255,255,255,0.08);
  backdrop-filter: blur(20px);
  -webkit-backdrop-filter: blur(20px);
  border: 1px solid rgba(255,255,255,0.14);
  border-radius: var(--radius);
  overflow: hidden;
  margin-bottom: 24px;
}

.panel-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 18px 22px;
  border-bottom: 1px solid rgba(255,255,255,0.10);
}

.panel-title {
  font-family: 'Poppins', sans-serif;
  font-size: 15px;
  font-weight: 700;
  color: #ffffff;
}

/* ── TABLES ───────────────────────────────────────────────────────── */
table { width:100%; border-collapse:collapse; }

thead th {
  font-size: 10.5px;
  text-transform: uppercase;
  letter-spacing: 1.2px;
  color: rgba(255,255,255,0.55);
  padding: 12px 22px;
  text-align: left;
  background: rgba(255,255,255,0.04);
  border-bottom: 1px solid rgba(255,255,255,0.10);
  font-family: 'Poppins', sans-serif;
  font-weight: 700;
}

tbody tr {
  border-bottom: 1px solid rgba(255,255,255,0.07);
  transition: background .12s;
}
tbody tr:last-child { border-bottom:none; }
tbody tr:hover { background: rgba(255,255,255,0.06); }
tbody td {
  padding: 13px 22px;
  font-size: 13.5px;
  color: #e2e8f0;
}

/* ── BADGES ───────────────────────────────────────────────────────── */
.badge {
  display: inline-flex;
  align-items: center;
  padding: 3px 11px;
  border-radius: 20px;
  font-size: 11px;
  font-weight: 600;
  letter-spacing: .3px;
  font-family: 'Poppins', sans-serif;
}

.badge.blue   { background:rgba(96,165,250,.20);  color:#93c5fd; border:1px solid rgba(96,165,250,.3); }
.badge.purple { background:rgba(192,132,252,.20); color:#d8b4fe; border:1px solid rgba(192,132,252,.3); }
.badge.green  { background:rgba(52,211,153,.20);  color:#6ee7b7; border:1px solid rgba(52,211,153,.3); }
.badge.orange { background:rgba(251,191,36,.20);  color:#fde68a; border:1px solid rgba(251,191,36,.3); }
.badge.red    { background:rgba(248,113,113,.20); color:#fca5a5; border:1px solid rgba(248,113,113,.3); }

/* ── FORMS ────────────────────────────────────────────────────────── */
.form-group { margin-bottom:16px; }

.form-label {
  display: block;
  font-size: 11px;
  color: rgba(255,255,255,0.60);
  text-transform: uppercase;
  letter-spacing: 1px;
  margin-bottom: 6px;
  font-family: 'Poppins', sans-serif;
  font-weight: 600;
}

.form-input {
  width: 100%;
  background: rgba(255,255,255,0.08);
  border: 1px solid rgba(255,255,255,0.15);
  border-radius: 9px;
  padding: 10px 14px;
  color: #ffffff;
  font-size: 14px;
  font-family: 'Inter', sans-serif;
  outline: none;
  transition: border-color .18s, background .18s;
}

.form-input::placeholder { color:rgba(255,255,255,0.35); }
.form-input:focus {
  border-color: var(--accent);
  background: rgba(96,165,250,0.08);
}
.form-input option { background: #0f172a; color:#fff; }

/* ── ALERTS ───────────────────────────────────────────────────────── */
.alert { padding:12px 16px; border-radius:10px; font-size:13px; margin-bottom:20px; }

.alert-success {
  background: rgba(52,211,153,.12);
  border: 1px solid rgba(52,211,153,.25);
  color: #6ee7b7;
}
.alert-error {
  background: rgba(248,113,113,.12);
  border: 1px solid rgba(248,113,113,.25);
  color: #fca5a5;
}

/* ── MODAL ────────────────────────────────────────────────────────── */
.modal-overlay {
  display: none;
  position: fixed;
  inset: 0;
  background: rgba(0,0,0,.70);
  backdrop-filter: blur(8px);
  z-index: 200;
  align-items: center;
  justify-content: center;
}
.modal-overlay.open { display:flex; }

.modal {
  background: rgba(10,18,35,0.92);
  backdrop-filter: blur(24px);
  border: 1px solid rgba(255,255,255,0.15);
  border-radius: 16px;
  padding: 28px;
  width: 500px;
  max-width: 95vw;
  animation: fadeUp .3s ease;
}

.modal-title {
  font-family: 'Poppins', sans-serif;
  font-size: 18px;
  font-weight: 700;
  margin-bottom: 20px;
  color: #ffffff;
}

.modal-close {
  float: right;
  background: none;
  border: none;
  color: rgba(255,255,255,0.45);
  font-size: 22px;
  cursor: pointer;
  margin-top: -4px;
  line-height: 1;
  transition: color .15s;
}
.modal-close:hover { color:#fff; }

/* ── HELPERS ──────────────────────────────────────────────────────── */
.grid-2 { display:grid; grid-template-columns:1fr 1fr; gap:14px; }

.empty-state {
  padding: 48px 22px;
  text-align: center;
  color: rgba(255,255,255,0.40);
  font-size: 13px;
}
.empty-label { font-size:13px; margin-top:8px; }

.pagination { display:flex; gap:6px; padding:16px 22px; justify-content:flex-end; }
.pagination a, .pagination span {
  padding: 6px 12px;
  border-radius: 7px;
  font-size: 12px;
  text-decoration: none;
  color: rgba(255,255,255,0.55);
  background: rgba(255,255,255,0.06);
  border: 1px solid rgba(255,255,255,0.12);
  font-family: 'Inter', sans-serif;
  transition: all .15s;
}
.pagination a:hover { background:rgba(255,255,255,0.12); color:#fff; }
.pagination .active span {
  background: var(--accent);
  color: #fff;
  border-color: var(--accent);
}

/* ── ANIMATIONS ───────────────────────────────────────────────────── */
@keyframes fadeUp {
  from { opacity:0; transform:translateY(16px); }
  to   { opacity:1; transform:translateY(0); }
}
@keyframes pulse {
  0%,100% { opacity:1; }
  50%      { opacity:.4; }
}

/* ── SCHOOL BADGE ─────────────────────────────────────────────────── */
.school-badge {
  display: flex;
  align-items: center;
  gap: 10px;
  background: rgba(255,255,255,0.10);
  backdrop-filter: blur(12px);
  border: 1px solid rgba(255,255,255,0.18);
  border-radius: 10px;
  padding: 8px 14px;
}
.school-badge-name {
  font-family: 'Poppins', sans-serif;
  font-size: 12px;
  font-weight: 700;
  color: #ffffff;
}
</style>
</head>
<body>

{{-- SIDEBAR --}}
<aside class="sidebar">
  <div class="logo">
    <img src="{{ asset('images/skillcraft_logo.jpg') }}" alt="SkillCraft" class="logo-icon"/>
    <div class="logo-text">Skill<span>Craft</span></div>
  </div>

  <div class="nav-section">
    <div class="nav-label">Main</div>
    <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
      <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg>
      Dashboard
    </a>
    <a href="{{ route('players.index') }}" class="nav-item {{ request()->routeIs('players*') ? 'active' : '' }}">
      <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
      Players
    </a>
  </div>

  <div class="nav-section">
    <div class="nav-label">Game Content</div>
    <a href="{{ route('strands.index') }}" class="nav-item {{ request()->routeIs('strands*') ? 'active' : '' }}">
      <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
      Strands
    </a>
    <a href="{{ route('modules.index') }}" class="nav-item {{ request()->routeIs('modules*') ? 'active' : '' }}">
      <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/></svg>
      Modules
    </a>
    <a href="{{ route('missions.index') }}" class="nav-item {{ request()->routeIs('missions*') ? 'active' : '' }}">
      <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
      Missions
    </a>
  </div>

  <div class="nav-section">
    <div class="nav-label">Analytics</div>
    <a href="{{ route('assessments.index') }}" class="nav-item {{ request()->routeIs('assessments*') ? 'active' : '' }}">
      <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
      Assessments
    </a>
    <a href="{{ route('surveys.index') }}" class="nav-item {{ request()->routeIs('surveys*') ? 'active' : '' }}">
      <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
      Surveys
    </a>
    <a href="{{ route('loginlogs.index') }}" class="nav-item {{ request()->routeIs('loginlogs*') ? 'active' : '' }}">
      <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
      Login Logs
    </a>
  </div>

  <div class="nav-section">
    <div class="nav-label">Account</div>
    <a href="{{ route('profile.index') }}" class="nav-item {{ request()->routeIs('profile*') ? 'active' : '' }}">
      <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
      My Profile
    </a>
  </div>

  <div class="sidebar-footer">
    <div class="admin-chip">
      @if(session('admin_picture'))
        <img src="{{ asset('storage/'.session('admin_picture')) }}"
             style="width:36px;height:36px;border-radius:50%;object-fit:cover;box-shadow:0 0 0 2px rgba(96,165,250,0.4)"
             alt="Admin"/>
      @else
        <div class="avatar">{{ strtoupper(substr(session('admin_name','A'),0,2)) }}</div>
      @endif
      <div>
        <div style="font-size:13px;font-weight:600;font-family:'Poppins',sans-serif;color:#ffffff">
          {{ session('admin_name','Admin') }}
        </div>
        <div style="font-size:10px;color:rgba(255,255,255,0.45);font-family:'Inter',sans-serif;margin-top:1px">
          Administrator
        </div>
      </div>
    </div>
    <form method="POST" action="{{ route('admin.logout') }}" style="margin-top:12px">
      @csrf
      <button type="submit" class="btn btn-ghost" style="width:100%;justify-content:center;font-size:12.5px">
        <svg style="width:13px;height:13px" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
        Logout
      </button>
    </form>
  </div>
</aside>

{{-- MAIN --}}
<main class="main">

  {{-- School badge top right --}}
  <div style="position:fixed;top:18px;right:26px;z-index:50">
    <div class="school-badge">
      <svg style="width:15px;height:15px;color:var(--accent);flex-shrink:0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
      <div>
        <div style="font-size:9px;color:rgba(255,255,255,0.45);font-family:'Inter',sans-serif">Partner School</div>
        <div class="school-badge-name">Juan G. Macaraeg NHS</div>
      </div>
    </div>
  </div>

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif
  @if($errors->any())
    <div class="alert alert-error">{{ $errors->first() }}</div>
  @endif

  @yield('content')
</main>
</body>
</html>
