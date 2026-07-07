@extends('admin.layout')
@section('title','Dashboard')
@section('content')
<div class="topbar">
  <div>
    <div class="page-title">Game <span>Dashboard</span></div>
    <div style="font-size:13px;color:var(--muted);margin-top:4px;">
      <span style="display:inline-block;width:7px;height:7px;border-radius:50%;background:var(--accent3);margin-right:6px;vertical-align:middle;animation:pulse 2s infinite"></span>
      Live · {{ now()->format('F d, Y · h:i A') }}
    </div>
  </div>
</div>

<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-bottom:28px">
  @foreach([
    ['accent' ,'Total Players', $totalPlayers,  'Students and teachers'],
    ['accent2','Online Now',    $onlinePlayers,  'Active last 15 min'],
    ['accent3','Total Missions',$totalMissions,  $totalModules.' modules'],
    ['warn',   'Strands',       $totalStrands,   'Game categories'],
  ] as [$color,$label,$val,$sub])
  <div style="background:var(--card);border:1px solid var(--border);border-radius:var(--radius);padding:22px;position:relative;overflow:hidden">
    <div style="position:absolute;top:0;left:0;right:0;height:2px;background:var(--{{ $color }})"></div>
    <div style="font-size:11px;text-transform:uppercase;letter-spacing:.8px;color:var(--muted);margin-bottom:12px">{{ $label }}</div>
    <div style="font-family:'Syne',sans-serif;font-size:34px;font-weight:800;color:var(--{{ $color }});margin-bottom:4px">{{ $val }}</div>
    <div style="font-size:12px;color:var(--muted)">{{ $sub }}</div>
  </div>
  @endforeach
</div>

<div style="display:grid;grid-template-columns:1fr 320px;gap:20px;margin-bottom:24px">
  <div class="panel">
    <div class="panel-header">
      <div class="panel-title">Recent Missions</div>
      <a href="{{ route('missions.index') }}" style="font-size:12px;color:var(--accent);text-decoration:none">View all</a>
    </div>
    <table>
      <thead><tr><th>Mission</th><th>Module</th><th>Strand</th><th>Difficulty</th><th>Max Score</th></tr></thead>
      <tbody>
        @forelse($missions as $m)
        <tr>
          <td style="font-weight:500">{{ $m->mission_title }}</td>
          <td style="color:var(--muted);font-size:13px">{{ $m->module->module_name ?? '—' }}</td>
          <td>
            @php $sn = $m->module->strand->strand_name ?? ''; @endphp
            <span class="badge {{ $sn==='ICT'?'blue':($sn==='Home Economics'?'purple':'orange') }}">{{ $sn ?: '—' }}</span>
          </td>
          <td>
            <span class="badge {{ $m->difficulty_level==1?'green':($m->difficulty_level==2?'orange':'red') }}">
              {{ ['','Easy','Medium','Hard'][$m->difficulty_level] }}
            </span>
          </td>
          <td style="font-family:'Syne',sans-serif;font-weight:700;color:var(--accent3)">{{ $m->max_score }}</td>
        </tr>
        @empty
        <tr><td colspan="5" style="text-align:center;color:var(--muted);padding:32px">No missions yet.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div class="panel">
    <div class="panel-header"><div class="panel-title">Recent Logins</div></div>
    @forelse($recentLogins as $log)
    <div style="display:flex;align-items:center;gap:12px;padding:12px 22px;border-bottom:1px solid var(--border)">
      <div style="width:32px;height:32px;border-radius:50%;background:linear-gradient(135deg,var(--accent),var(--accent2));display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:700;color:#fff;flex-shrink:0">
        {{ strtoupper(substr($log->user->first_name ?? 'U',0,1).substr($log->user->last_name ?? '',0,1)) }}
      </div>
      <div style="flex:1">
        <div style="font-size:13px;font-weight:500">{{ $log->user->first_name ?? 'Unknown' }} {{ $log->user->last_name ?? '' }}</div>
        <div style="font-size:11px;color:var(--muted)">{{ $log->login_at->diffForHumans() }}</div>
      </div>
      <div style="font-size:11px;color:{{ $log->logout_at ? 'var(--muted)' : 'var(--accent3)' }}">
        {{ $log->logout_at ? 'Ended' : 'Active' }}
      </div>
    </div>
    @empty
    <div class="empty-state"><div class="empty-label">No logins yet.</div></div>
    @endforelse
  </div>
</div>
@endsection
