@extends('admin.layout')
@section('title','Players')
@section('content')
<div class="topbar">
  <div class="page-title">All <span>Players</span></div>
</div>
<div class="panel">
  <div class="panel-header">
    <div class="panel-title">Registered Players</div>
    <form method="GET" style="display:flex;gap:8px">
      <input class="form-input" style="width:220px;padding:7px 12px" name="search" value="{{ request('search') }}" placeholder="Search name or email..."/>
      <select class="form-input" style="width:130px;padding:7px 12px" name="role" onchange="this.form.submit()">
        <option value="">All Roles</option>
        <option value="student" {{ request('role')=='student'?'selected':'' }}>Student</option>
        <option value="teacher" {{ request('role')=='teacher'?'selected':'' }}>Teacher</option>
      </select>
      <button type="submit" class="btn btn-ghost">Filter</button>
    </form>
  </div>
  <table>
    <thead><tr><th>Player</th><th>Username</th><th>Role</th><th>Contact</th><th>Joined</th><th>Actions</th></tr></thead>
    <tbody>
      @forelse($players as $p)
      <tr>
        <td>
          <div style="display:flex;align-items:center;gap:10px">
            <div style="width:32px;height:32px;border-radius:50%;background:linear-gradient(135deg,var(--accent),var(--accent2));display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:700;color:#fff;flex-shrink:0">
              {{ strtoupper(substr($p->first_name,0,1).substr($p->last_name,0,1)) }}
            </div>
            <div>
              <div style="font-weight:500">{{ $p->first_name }} {{ $p->last_name }}</div>
              <div style="font-size:11px;color:var(--muted)">{{ $p->email }}</div>
            </div>
          </div>
        </td>
        <td style="color:var(--muted);font-size:13px">{{ $p->username ?? '—' }}</td>
        <td><span class="badge {{ $p->role==='teacher'?'blue':'green' }}">{{ ucfirst($p->role) }}</span></td>
        <td style="color:var(--muted);font-size:13px">{{ $p->contact_number ?? '—' }}</td>
        <td style="color:var(--muted);font-size:12px">{{ $p->created_at->format('M d, Y') }}</td>
        <td>
          <div style="display:flex;gap:6px">
            <a href="{{ route('players.show',$p->user_id) }}" class="btn btn-ghost" style="padding:5px 12px;font-size:12px">View</a>
            <form method="POST" action="{{ route('players.destroy',$p->user_id) }}" onsubmit="return confirm('Delete this player?')">
              @csrf @method('DELETE')
              <button type="submit" class="btn btn-danger" style="padding:5px 12px;font-size:12px">Delete</button>
            </form>
          </div>
        </td>
      </tr>
      @empty
      <tr><td colspan="6"><div class="empty-state"><div class="empty-label">No players found.</div></div></td></tr>
      @endforelse
    </tbody>
  </table>
  <div class="pagination">{{ $players->withQueryString()->links() }}</div>
</div>
@endsection
