<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>SkillCraft — Admin Dashboard</title>
<link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet"/>
<style>
:root {
  --bg:      #0a0c10;
  --surface: #111318;
  --card:    #161a22;
  --border:  #1f2535;
  --accent:  #4f8ef7;
  --accent2: #e05fff;
  --accent3: #00e5a0;
  --warn:    #f7a94f;
  --text:    #eef0f6;
  --muted:   #5a6180;
  --radius:  14px;
}
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
body{font-family:'DM Sans',sans-serif;background:var(--bg);color:var(--text);min-height:100vh;display:flex;overflow-x:hidden}
/* Sidebar */
.sidebar{width:240px;min-height:100vh;background:var(--surface);border-right:1px solid var(--border);display:flex;flex-direction:column;padding:28px 0;position:fixed;top:0;left:0;bottom:0;z-index:100}
.logo{padding:0 24px 32px;display:flex;align-items:center;gap:12px}
.logo-icon{width:38px;height:38px;border-radius:10px;object-fit:cover;}
.logo-text{font-family:'Syne',sans-serif;font-weight:800;font-size:18px;letter-spacing:-.5px}
.logo-text span{color:var(--accent)}
.nav-label{font-size:10px;text-transform:uppercase;letter-spacing:1.5px;color:var(--muted);padding:0 12px 8px;margin-top:16px}
.nav-section{padding:0 12px;margin-bottom:8px}
.nav-item{display:flex;align-items:center;gap:11px;padding:10px 12px;border-radius:9px;cursor:pointer;transition:all .2s;font-size:14px;color:var(--muted);margin-bottom:2px;text-decoration:none}
.nav-item:hover{background:var(--card);color:var(--text)}
.nav-item.active{background:rgba(79,142,247,.13);color:var(--accent)}
.nav-item .icon{font-size:16px;width:20px;text-align:center}
.sidebar-footer{margin-top:auto;padding:16px 24px;border-top:1px solid var(--border)}
.avatar{width:34px;height:34px;border-radius:50%;background:linear-gradient(135deg,var(--accent),var(--accent2));display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:700;color:#fff;flex-shrink:0}
.admin-chip{display:flex;align-items:center;gap:10px}
.admin-name{font-size:13px;font-weight:500}
.admin-role{font-size:11px;color:var(--muted)}
/* Main */
.main{margin-left:240px;flex:1;padding:32px 36px;min-height:100vh}
.topbar{display:flex;align-items:center;justify-content:space-between;margin-bottom:36px}
.page-title{font-family:'Syne',sans-serif;font-size:26px;font-weight:800;letter-spacing:-.5px}
.page-title span{color:var(--accent)}
.live-dot{display:inline-block;width:7px;height:7px;border-radius:50%;background:var(--accent3);margin-right:6px;vertical-align:middle;animation:pulse 2s infinite}
/* Stats */
.stats-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-bottom:28px}
.stat-card{background:var(--card);border:1px solid var(--border);border-radius:var(--radius);padding:22px 22px 18px;position:relative;overflow:hidden;transition:transform .2s,box-shadow .2s}
.stat-card:hover{transform:translateY(-2px);box-shadow:0 8px 32px rgba(0,0,0,.4)}
.stat-card::before{content:'';position:absolute;top:0;left:0;right:0;height:2px;border-radius:var(--radius) var(--radius) 0 0}
.stat-card.blue::before{background:var(--accent)}
.stat-card.purple::before{background:var(--accent2)}
.stat-card.green::before{background:var(--accent3)}
.stat-card.orange::before{background:var(--warn)}
.stat-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:14px}
.stat-label{font-size:12px;color:var(--muted);text-transform:uppercase;letter-spacing:.8px}
.stat-icon{width:36px;height:36px;border-radius:9px;display:flex;align-items:center;justify-content:center;font-size:16px}
.stat-card.blue   .stat-icon{background:rgba(79,142,247,.15)}
.stat-card.purple .stat-icon{background:rgba(224,95,255,.15)}
.stat-card.green  .stat-icon{background:rgba(0,229,160,.15)}
.stat-card.orange .stat-icon{background:rgba(247,169,79,.15)}
.stat-value{font-family:'Syne',sans-serif;font-size:32px;font-weight:800;line-height:1;margin-bottom:6px}
.stat-card.blue   .stat-value{color:var(--accent)}
.stat-card.purple .stat-value{color:var(--accent2)}
.stat-card.green  .stat-value{color:var(--accent3)}
.stat-card.orange .stat-value{color:var(--warn)}
.stat-sub{font-size:12px;color:var(--muted)}
/* Grid */
.content-grid{display:grid;grid-template-columns:1fr 340px;gap:20px;margin-bottom:24px}
.bottom-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:20px}
.panel{background:var(--card);border:1px solid var(--border);border-radius:var(--radius);overflow:hidden}
.panel-header{display:flex;align-items:center;justify-content:space-between;padding:18px 22px;border-bottom:1px solid var(--border)}
.panel-title{font-family:'Syne',sans-serif;font-size:15px;font-weight:700}
/* Table */
.table-wrap{overflow-x:auto}
table{width:100%;border-collapse:collapse}
thead th{font-size:11px;text-transform:uppercase;letter-spacing:.8px;color:var(--muted);padding:12px 22px;text-align:left;background:rgba(255,255,255,.02);border-bottom:1px solid var(--border)}
tbody tr{border-bottom:1px solid var(--border);transition:background .15s}
tbody tr:last-child{border-bottom:none}
tbody tr:hover{background:rgba(255,255,255,.03)}
tbody td{padding:13px 22px;font-size:13.5px}
.badge{display:inline-flex;align-items:center;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:500;letter-spacing:.3px}
.badge.ict    {background:rgba(79,142,247,.12);color:var(--accent)}
.badge.he     {background:rgba(224,95,255,.12);color:var(--accent2)}
.badge.ia     {background:rgba(247,169,79,.12);color:var(--warn)}
.badge.easy   {background:rgba(0,229,160,.12);color:var(--accent3)}
.badge.medium {background:rgba(247,169,79,.12);color:var(--warn)}
.badge.hard   {background:rgba(247,79,79,.12);color:#f74f4f}
/* Login items */
.login-item{display:flex;align-items:center;gap:12px;padding:13px 22px;border-bottom:1px solid var(--border)}
.login-item:last-child{border-bottom:none}
.login-icon{width:32px;height:32px;border-radius:8px;background:rgba(79,142,247,.1);display:flex;align-items:center;justify-content:center;font-size:15px;flex-shrink:0}
.login-info{flex:1}
.login-user{font-size:13px;font-weight:500}
.login-time{font-size:11.5px;color:var(--muted)}
/* Strand items */
.strand-item{padding:16px 22px;border-bottom:1px solid var(--border)}
.strand-item:last-child{border-bottom:none}
.strand-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:8px}
.strand-label{font-size:13.5px;font-weight:500}
.strand-count{font-size:12px;color:var(--muted)}
.strand-progress{height:6px;background:var(--border);border-radius:99px;overflow:hidden}
.strand-progress-fill{height:100%;border-radius:99px}
/* Empty state */
.empty-state{padding:40px 22px;text-align:center;color:var(--muted);font-size:13px}
.empty-icon{font-size:32px;margin-bottom:10px}
/* Animations */
@keyframes pulse{0%,100%{opacity:1;transform:scale(1)}50%{opacity:.5;transform:scale(.85)}}
@keyframes fadeUp{from{opacity:0;transform:translateY(16px)}to{opacity:1;transform:translateY(0)}}
.fade-up{animation:fadeUp .5s ease both}
.d1{animation-delay:.05s}.d2{animation-delay:.1s}.d3{animation-delay:.15s}.d4{animation-delay:.2s}
/* Scrollbar */
::-webkit-scrollbar{width:5px;height:5px}
::-webkit-scrollbar-track{background:transparent}
::-webkit-scrollbar-thumb{background:var(--border);border-radius:99px}
</style>
</head>
<body>

{{-- SIDEBAR --}}
<aside class="sidebar">
  <div class="logo">
    <img src="{{ asset('images/skillcraft_logo.jpg') }}" alt="SkillCraft Logo" class="logo-icon"/>
    <div class="logo-text">Skill<span>Craft</span></div>
  </div>
  <div class="nav-section">
    <div class="nav-label">Main</div>
    <a href="{{ url('/dashboard') }}" class="nav-item active">
      <span class="icon">📊</span> Dashboard
    </a>
    <a href="{{ url('/api/strands') }}" class="nav-item">
      <span class="icon">📚</span> Strands
    </a>
    <a href="{{ url('/api/missions') }}" class="nav-item">
      <span class="icon">🎯</span> Missions
    </a>
    <a href="{{ url('/api/modules') }}" class="nav-item">
      <span class="icon">🗂️</span> Modules
    </a>
  </div>
  <div class="nav-section">
    <div class="nav-label">Players</div>
    <a href="#" class="nav-item">
      <span class="icon">👥</span> All Players
    </a>
    <a href="#" class="nav-item">
      <span class="icon">🔐</span> Login Logs
    </a>
  </div>
  <div class="nav-section">
    <div class="nav-label">Analytics</div>
    <a href="#" class="nav-item">
      <span class="icon">📈</span> Assessments
    </a>
    <a href="#" class="nav-item">
      <span class="icon">🏆</span> Leaderboard
    </a>
    <a href="#" class="nav-item">
      <span class="icon">⭐</span> Survey Results
    </a>
  </div>
  <div class="sidebar-footer">
    <div class="admin-chip">
      <div class="avatar">AD</div>
      <div>
        <div class="admin-name">Administrator</div>
        <div class="admin-role">SkillCraft Admin</div>
      </div>
    </div>
  </div>
</aside>

{{-- MAIN --}}
<main class="main">

  {{-- Topbar --}}
  <div class="topbar fade-up">
    <div>
      <div class="page-title">Game <span>Dashboard</span></div>
      <div style="font-size:13px;color:var(--muted);margin-top:4px;">
        <span class="live-dot"></span>
        Live · {{ now()->format('F d, Y · h:i A') }}
      </div>
    </div>
  </div>

  {{-- Stat Cards --}}
  <div class="stats-grid">
    <div class="stat-card blue fade-up d1">
      <div class="stat-header">
        <div class="stat-label">Total Players</div>
        <div class="stat-icon">👤</div>
      </div>
      <div class="stat-value">{{ $totalPlayers }}</div>
      <div class="stat-sub">Registered students &amp; teachers</div>
    </div>
    <div class="stat-card purple fade-up d2">
      <div class="stat-header">
        <div class="stat-label">Online Now</div>
        <div class="stat-icon">🟢</div>
      </div>
      <div class="stat-value">{{ $onlinePlayers }}</div>
      <div class="stat-sub">Active in last 15 minutes</div>
    </div>
    <div class="stat-card green fade-up d3">
      <div class="stat-header">
        <div class="stat-label">Total Missions</div>
        <div class="stat-icon">🎯</div>
      </div>
      <div class="stat-value">{{ $totalMissions }}</div>
      <div class="stat-sub">Across {{ $totalModules }} modules</div>
    </div>
    <div class="stat-card orange fade-up d4">
      <div class="stat-header">
        <div class="stat-label">Total Strands</div>
        <div class="stat-icon">📚</div>
      </div>
      <div class="stat-value">{{ $totalStrands }}</div>
      <div class="stat-sub">ICT · Home Ec · Ind. Arts</div>
    </div>
  </div>

  {{-- Missions + Login Logs --}}
  <div class="content-grid fade-up d2">

    {{-- Missions Table --}}
    <div class="panel">
      <div class="panel-header">
        <div class="panel-title">🎯 All Missions</div>
        <span style="font-size:12px;color:var(--muted);">{{ $totalMissions }} total</span>
      </div>
      <div class="table-wrap">
        @if($missions->count())
        <table>
          <thead>
            <tr>
              <th>#</th>
              <th>Mission Title</th>
              <th>Module</th>
              <th>Strand</th>
              <th>Difficulty</th>
              <th>Max Score</th>
            </tr>
          </thead>
          <tbody>
            @foreach($missions as $i => $mission)
            <tr>
              <td style="color:var(--muted);font-size:12px;">{{ $i + 1 }}</td>
              <td>
                <div style="font-weight:500;font-size:13.5px;">{{ $mission->mission_title }}</div>
                <div style="font-size:11.5px;color:var(--muted);">{{ Str::limit($mission->scenario_description, 50) }}</div>
              </td>
              <td style="font-size:13px;color:var(--muted);">{{ $mission->module->module_name ?? '—' }}</td>
              <td>
                @php
                  $strand = $mission->module->strand->strand_name ?? '';
                  $cls = $strand === 'ICT' ? 'ict' : ($strand === 'Home Economics' ? 'he' : 'ia');
                @endphp
                <span class="badge {{ $cls }}">{{ $strand }}</span>
              </td>
              <td>
                @php
                  $diff = $mission->difficulty_level;
                  $dcls = $diff == 1 ? 'easy' : ($diff == 2 ? 'medium' : 'hard');
                  $dlbl = $diff == 1 ? 'Easy' : ($diff == 2 ? 'Medium' : 'Hard');
                @endphp
                <span class="badge {{ $dcls }}">{{ $dlbl }}</span>
              </td>
              <td style="font-family:'Syne',sans-serif;font-weight:700;color:var(--accent3);">
                {{ $mission->max_score }}
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
        @else
        <div class="empty-state">
          <div class="empty-icon">🎯</div>
          No missions found. Run <code>php artisan db:seed</code> to seed data.
        </div>
        @endif
      </div>
    </div>

    {{-- Recent Logins --}}
    <div class="panel">
      <div class="panel-header">
        <div class="panel-title">🔐 Recent Logins</div>
      </div>
      @if($recentLogins->count())
        @foreach($recentLogins as $session)
        <div class="login-item">
          <div class="login-icon">🔑</div>
          <div class="login-info">
            <div class="login-user">
              {{ $session->user->first_name ?? 'Unknown' }}
              {{ $session->user->last_name ?? '' }}
            </div>
            <div class="login-time">{{ $session->email }}</div>
            <div class="login-time">{{ $session->login_at->diffForHumans() }}</div>
          </div>
          <div style="font-size:11px;color:{{ $session->logout_at ? 'var(--muted)' : 'var(--accent3)' }}">
            {{ $session->logout_at ? 'Ended' : 'Active' }}
          </div>
        </div>
        @endforeach
      @else
        <div class="empty-state">
          <div class="empty-icon">🔐</div>
          No login sessions yet.
        </div>
      @endif
    </div>

  </div>

  {{-- Bottom: Strands + Players + Modules --}}
  <div class="bottom-grid fade-up d3">

    {{-- Strand Breakdown --}}
    <div class="panel">
      <div class="panel-header">
        <div class="panel-title">📚 Strand Breakdown</div>
      </div>
      @php
        $strandColors = ['ICT'=>'#4f8ef7','Home Economics'=>'#e05fff','Industrial Arts'=>'#f7a94f'];
        $totalM = $strands->sum('modules_count') ?: 1;
      @endphp
      @foreach($strands as $strand)
      @php
        $color = $strandColors[$strand->strand_name] ?? '#4f8ef7';
        $pct = round(($strand->modules_count / $totalM) * 100);
      @endphp
      <div class="strand-item">
        <div class="strand-header">
          <div style="display:flex;align-items:center;gap:8px;">
            <div style="width:8px;height:8px;border-radius:50%;background:{{ $color }}"></div>
            <div class="strand-label">{{ $strand->strand_name }}</div>
          </div>
          <div class="strand-count">{{ $strand->modules_count }} modules · {{ $pct }}%</div>
        </div>
        <div class="strand-progress">
          <div class="strand-progress-fill" style="width:{{ $pct }}%;background:{{ $color }}"></div>
        </div>
        <div style="font-size:11.5px;color:var(--muted);margin-top:6px;">{{ $strand->description }}</div>
      </div>
      @endforeach
    </div>

    {{-- Recent Players --}}
    <div class="panel">
      <div class="panel-header">
        <div class="panel-title">👥 Recent Players</div>
        <span style="font-size:12px;color:var(--muted);">{{ $totalPlayers }} total</span>
      </div>
      @if($recentUsers->count())
      <table>
        <thead>
          <tr>
            <th>Player</th>
            <th>Role</th>
            <th>Joined</th>
          </tr>
        </thead>
        <tbody>
          @foreach($recentUsers as $user)
          <tr>
            <td>
              <div style="font-weight:500;font-size:13px;">{{ $user->first_name }} {{ $user->last_name }}</div>
              <div style="font-size:11px;color:var(--muted);">{{ $user->email }}</div>
            </td>
            <td>
              <span class="badge ict">{{ $user->role }}</span>
            </td>
            <td style="font-size:11.5px;color:var(--muted);">
              {{ $user->created_at->diffForHumans() }}
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
      @else
        <div class="empty-state">
          <div class="empty-icon">👥</div>
          No players registered yet.
        </div>
      @endif
    </div>

    {{-- Modules per Strand --}}
    <div class="panel">
      <div class="panel-header">
        <div class="panel-title">🗂️ Modules Overview</div>
        <span style="font-size:12px;color:var(--muted);">{{ $totalModules }} total</span>
      </div>
      @foreach($strands as $strand)
      <div class="login-item">
        <div class="login-icon">🗂️</div>
        <div class="login-info">
          <div class="login-user">{{ $strand->strand_name }}</div>
          <div class="login-time">{{ $strand->modules_count }} module(s)</div>
        </div>
        <div style="font-family:'Syne',sans-serif;font-size:18px;font-weight:800;color:var(--accent);">
          {{ $strand->modules_count }}
        </div>
      </div>
      @endforeach
    </div>

  </div>

</main>

</body>
</html>
