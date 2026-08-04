@extends('admin.layout')
@section('title','Live Monitoring')
@section('content')

<div class="topbar">
  <div>
    <div class="page-title">Live <span>Monitoring</span></div>
    <div style="font-size:13px;color:var(--muted);margin-top:4px;">
      <span class="live-dot"></span>
      @if($scopedStrand)
        Watching: <strong style="color:var(--accent)">{{ $scopedStrand }}</strong> students only
      @else
        Watching all students · Auto-refreshes every 15s
      @endif
    </div>
  </div>
</div>

<div style="display:grid;grid-template-columns:repeat(2,1fr);gap:16px;margin-bottom:24px">
  <div class="panel" style="padding:22px;position:relative;overflow:hidden">
    <div style="position:absolute;top:0;left:0;right:0;height:2px;background:var(--accent3)"></div>
    <div style="font-size:11px;text-transform:uppercase;letter-spacing:.8px;color:var(--muted);margin-bottom:12px">Online Now</div>
    <div style="font-family:'Poppins',sans-serif;font-size:34px;font-weight:800;color:var(--accent3)">{{ $onlineCount }}</div>
    <div style="font-size:12px;color:var(--muted)">Active in the last 15 minutes</div>
  </div>
  <div class="panel" style="padding:22px;position:relative;overflow:hidden">
    <div style="position:absolute;top:0;left:0;right:0;height:2px;background:var(--accent)"></div>
    <div style="font-size:11px;text-transform:uppercase;letter-spacing:.8px;color:var(--muted);margin-bottom:12px">Currently In a Mission</div>
    <div style="font-family:'Poppins',sans-serif;font-size:34px;font-weight:800;color:var(--accent)">{{ $inMissionCount }}</div>
    <div style="font-size:12px;color:var(--muted)">Mid pre-test / post-test right now</div>
  </div>
</div>

<div class="panel">
  <div class="panel-header">
    <div class="panel-title">Student Activity</div>
    <span style="font-size:12px;color:var(--muted)">{{ $rows->count() }} student(s)</span>
  </div>
  <table>
    <thead>
      <tr>
        <th>Status</th>
        <th>Student</th>
        <th>Current Mission</th>
        <th>Type</th>
        <th>Started</th>
        <th>Last Seen</th>
      </tr>
    </thead>
    <tbody>
      @forelse($rows as $row)
      <tr>
        <td>
          <span class="status-dot {{ $row['is_online'] ? 'online' : 'offline' }}"></span>
          <span style="font-size:12px;color:{{ $row['is_online'] ? 'var(--accent3)' : 'var(--muted)' }};margin-left:6px">
            {{ $row['is_online'] ? 'Online' : 'Offline' }}
          </span>
        </td>
        <td>
          <div style="display:flex;align-items:center;gap:10px">
            <div style="width:30px;height:30px;border-radius:50%;background:linear-gradient(135deg,var(--accent),var(--accent2));display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:700;color:#fff;flex-shrink:0">
              {{ strtoupper(substr($row['first_name'],0,1).substr($row['last_name'],0,1)) }}
            </div>
            <div>
              <div style="font-weight:500">{{ $row['first_name'] }} {{ $row['last_name'] }}</div>
              <div style="font-size:11px;color:var(--muted)">{{ '@'.$row['username'] }}</div>
            </div>
          </div>
        </td>
        <td style="font-size:13px">
          {{ $row['active_mission']['mission_title'] ?? '— idle in lobby —' }}
        </td>
        <td>
          @if($row['active_mission'])
            @php
              $t = $row['active_mission']['assessment_type'] ?? '';
              $tc = $t === 'pre_test' ? 'orange' : ($t === 'post_test' ? 'green' : 'blue');
            @endphp
            <span class="badge {{ $tc }}">{{ str_replace('_',' ',ucfirst($t)) }}</span>
          @else
            <span style="color:var(--muted);font-size:12px">—</span>
          @endif
        </td>
        <td style="font-size:12px;color:var(--muted)">
          {{ $row['active_mission']['started_at'] ?? '—' }}
        </td>
        <td style="font-size:12px;color:var(--muted)">
          {{ \Carbon\Carbon::parse($row['last_seen'])->diffForHumans() }}
        </td>
      </tr>
      @empty
      <tr>
        <td colspan="6">
          <div class="empty-state">
            <div class="empty-label">
              @if($scopedStrand)
                No students currently active in {{ $scopedStrand }}.
              @else
                No students online right now.
              @endif
            </div>
          </div>
        </td>
      </tr>
      @endforelse
    </tbody>
  </table>
</div>

<script>
// Auto-refresh so the teacher/admin doesn't have to manually reload
// to see updated online status and mission progress.
setTimeout(() => window.location.reload(), 15000);
</script>
@endsection
