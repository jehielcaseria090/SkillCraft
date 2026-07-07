@extends('admin.layout')
@section('title','Login Logs')
@section('content')
<div class="topbar">
  <div class="page-title">Login <span>Logs</span></div>
</div>
<div class="panel">
  <div class="panel-header"><div class="panel-title">All Login Sessions</div></div>
  <table>
    <thead><tr><th>Player</th><th>Email</th><th>Login Time</th><th>Logout Time</th><th>IP Address</th><th>Status</th></tr></thead>
    <tbody>
      @forelse($logs as $log)
      <tr>
        <td style="font-weight:500">{{ $log->user->first_name ?? 'Unknown' }} {{ $log->user->last_name ?? '' }}</td>
        <td style="color:var(--muted);font-size:13px">{{ $log->email }}</td>
        <td style="font-size:12px;color:var(--muted)">{{ $log->login_at->format('M d, Y h:i A') }}</td>
        <td style="font-size:12px;color:var(--muted)">{{ $log->logout_at ? $log->logout_at->format('M d, Y h:i A') : '—' }}</td>
        <td style="font-size:12px;color:var(--muted)">{{ $log->ip_address ?? '—' }}</td>
        <td>
          <span class="badge {{ $log->logout_at ? '' : 'green' }}"
            style="{{ $log->logout_at ? 'background:rgba(90,97,128,.15);color:var(--muted)' : '' }}">
            {{ $log->logout_at ? 'Ended' : 'Active' }}
          </span>
        </td>
      </tr>
      @empty
      <tr><td colspan="6"><div class="empty-state"><div class="empty-label">No login sessions yet.</div></div></td></tr>
      @endforelse
    </tbody>
  </table>
  <div class="pagination">{{ $logs->links() }}</div>
</div>
@endsection
