@extends('admin.layout')
@section('title','Manage Teachers')
@section('content')
<div class="topbar">
  <div class="page-title">Manage <span>Teachers</span></div>
  <button class="btn btn-primary" onclick="document.getElementById('addModal').classList.add('open')">+ Add Teacher</button>
</div>

@php
  $counts = $teachers->groupBy('specialization')->map->count();
@endphp

<div style="display:grid;grid-template-columns:repeat(3,1fr);gap:16px;margin-bottom:24px">
  @foreach([
    ['ict','ICT','blue',2],
    ['smaw','SMAW','orange',1],
    ['cookery','Cookery','purple',1],
  ] as [$key,$label,$badge,$expected])
  <div class="panel" style="padding:20px">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:6px">
      <span class="badge {{ $badge }}">{{ $label }}</span>
      <span style="font-size:12px;color:var(--muted)">expected: {{ $expected }}</span>
    </div>
    <div style="font-family:'Syne',sans-serif;font-size:28px;font-weight:800">
      {{ $counts[$key] ?? 0 }}
    </div>
  </div>
  @endforeach
</div>

<div class="panel">
  <div class="panel-header"><div class="panel-title">All Teacher Accounts</div></div>
  <table>
    <thead><tr><th>#</th><th>Teacher</th><th>Specialization</th><th>Username</th><th>Contact</th><th>Actions</th></tr></thead>
    <tbody>
      @forelse($teachers as $i => $t)
      <tr>
        <td style="color:var(--muted)">{{ $i+1 }}</td>
        <td>
          <div style="font-weight:500">{{ $t->first_name }} {{ $t->last_name }}</div>
          <div style="font-size:11px;color:var(--muted)">{{ $t->email }}</div>
        </td>
        <td>
          @php $specMap = ['ict'=>['ICT','blue'],'smaw'=>['SMAW','orange'],'cookery'=>['Cookery','purple']]; @endphp
          <span class="badge {{ $specMap[$t->specialization][1] ?? '' }}">{{ $specMap[$t->specialization][0] ?? '—' }}</span>
        </td>
        <td style="color:var(--muted);font-size:13px">{{ $t->username }}</td>
        <td style="color:var(--muted);font-size:13px">{{ $t->contact_number ?? '—' }}</td>
        <td>
          <form method="POST" action="{{ route('teachers.destroy',$t->user_id) }}" onsubmit="return confirm('Remove this teacher account?')">
            @csrf @method('DELETE')
            <button type="submit" class="btn btn-danger" style="padding:5px 12px;font-size:12px">Delete</button>
          </form>
        </td>
      </tr>
      @empty
      <tr><td colspan="6"><div class="empty-state"><div class="empty-label">No teacher accounts yet.</div></div></td></tr>
      @endforelse
    </tbody>
  </table>
</div>

<div class="modal-overlay" id="addModal" onclick="if(event.target===this)this.classList.remove('open')">
  <div class="modal">
    <button class="modal-close" onclick="document.getElementById('addModal').classList.remove('open')">×</button>
    <div class="modal-title">Add Teacher Account</div>
    <form method="POST" action="{{ route('teachers.store') }}">
      @csrf
      <div class="grid-2">
        <div class="form-group"><label class="form-label">First Name</label><input class="form-input" name="first_name" required/></div>
        <div class="form-group"><label class="form-label">Last Name</label><input class="form-input" name="last_name" required/></div>
      </div>
      <div class="form-group"><label class="form-label">Email</label><input class="form-input" type="email" name="email" required/></div>
      <div class="form-group"><label class="form-label">Username</label><input class="form-input" name="username" required/></div>
      <div class="form-group">
        <label class="form-label">Specialization</label>
        <select class="form-input" name="specialization" required>
          <option value="">Select specialization</option>
          <option value="ict">ICT</option>
          <option value="smaw">SMAW (Industrial Arts)</option>
          <option value="cookery">Cookery (Home Economics)</option>
        </select>
      </div>
      <div class="form-group"><label class="form-label">Contact Number</label><input class="form-input" name="contact_number"/></div>
      <div class="grid-2">
        <div class="form-group"><label class="form-label">Password</label><input class="form-input" type="password" name="password" required/></div>
        <div class="form-group"><label class="form-label">Confirm Password</label><input class="form-input" type="password" name="password_confirmation" required/></div>
      </div>
      <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;margin-top:8px">Create Teacher Account</button>
    </form>
  </div>
</div>
@endsection
