@extends('admin.layout')
@section('title','Players')
@section('content')
<div class="topbar">
  <div class="page-title">
    {{ session('admin_role') === 'admin' ? 'All' : 'My' }} <span>Students</span>
  </div>
  <button class="btn btn-primary" onclick="document.getElementById('addModal').classList.add('open')">+ Add Student</button>
</div>

@if($scopedStrand)
<div style="font-size:13px;color:var(--muted);margin-bottom:16px">
  Showing students enrolled in or active in <strong style="color:var(--accent)">{{ $scopedStrand }}</strong> only.
</div>
@endif

<div class="panel">
  <div class="panel-header">
    <div class="panel-title">{{ session('admin_role') === 'admin' ? 'Registered Students' : 'Your Students' }}</div>
    <form method="GET" style="display:flex;gap:8px">
      <input class="form-input" style="width:220px;padding:7px 12px" name="search" value="{{ request('search') }}" placeholder="Search name or email..."/>
      <button type="submit" class="btn btn-ghost">Search</button>
    </form>
  </div>
  <table>
    <thead><tr><th>Player</th><th>Username</th><th>Strand</th><th>Contact</th><th>Joined</th><th>Actions</th></tr></thead>
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
        <td>
          @if($p->enrolled_strand)
            <span class="badge {{ $p->enrolled_strand === 'ICT' ? 'blue' : ($p->enrolled_strand === 'Home Economics' ? 'purple' : 'orange') }}">
              {{ $p->enrolled_strand }}
            </span>
          @else
            <span style="color:var(--muted);font-size:12px">Unassigned</span>
          @endif
        </td>
        <td style="color:var(--muted);font-size:13px">{{ $p->contact_number ?? '—' }}</td>
        <td style="color:var(--muted);font-size:12px">{{ $p->created_at->format('M d, Y') }}</td>
        <td>
          <div style="display:flex;gap:6px">
            <a href="{{ route('players.show',$p->user_id) }}" class="btn btn-ghost" style="padding:5px 12px;font-size:12px">View</a>
            <button class="btn btn-ghost" style="padding:5px 12px;font-size:12px"
              onclick="openEdit({{ $p->user_id }},'{{ addslashes($p->first_name) }}','{{ addslashes($p->last_name) }}','{{ addslashes($p->email) }}','{{ addslashes($p->contact_number) }}','{{ $p->enrolled_strand }}')">
              Edit
            </button>
            <form method="POST" action="{{ route('players.destroy',$p->user_id) }}" onsubmit="return confirm('Delete this student?')">
              @csrf @method('DELETE')
              <button type="submit" class="btn btn-danger" style="padding:5px 12px;font-size:12px">Delete</button>
            </form>
          </div>
        </td>
      </tr>
      @empty
      <tr><td colspan="6"><div class="empty-state"><div class="empty-label">No students found.</div></div></td></tr>
      @endforelse
    </tbody>
  </table>
  <div class="pagination">{{ $players->withQueryString()->links() }}</div>
</div>

{{-- Add Modal --}}
<div class="modal-overlay" id="addModal" onclick="if(event.target===this)this.classList.remove('open')">
  <div class="modal">
    <button class="modal-close" onclick="document.getElementById('addModal').classList.remove('open')">×</button>
    <div class="modal-title">Add Student Account</div>
    <form method="POST" action="{{ route('players.store') }}">
      @csrf
      <div class="grid-2">
        <div class="form-group"><label class="form-label">First Name</label><input class="form-input" name="first_name" required/></div>
        <div class="form-group"><label class="form-label">Last Name</label><input class="form-input" name="last_name" required/></div>
      </div>
      <div class="form-group"><label class="form-label">Email</label><input class="form-input" type="email" name="email" required/></div>
      <div class="form-group"><label class="form-label">Username</label><input class="form-input" name="username" required/></div>

      @if($scopedStrand)
        <div class="form-group">
          <label class="form-label">Strand</label>
          <input class="form-input" value="{{ $scopedStrand }}" disabled style="opacity:.6"/>
          <div style="font-size:11px;color:var(--muted);margin-top:5px">Automatically enrolled in your specialization</div>
        </div>
      @else
        <div class="form-group">
          <label class="form-label">Strand</label>
          <select class="form-input" name="enrolled_strand">
            <option value="">Unassigned</option>
            <option value="ICT">ICT</option>
            <option value="Home Economics">Home Economics</option>
            <option value="Industrial Arts">Industrial Arts</option>
          </select>
        </div>
      @endif

      <div class="form-group"><label class="form-label">Contact Number</label><input class="form-input" name="contact_number"/></div>
      <div class="grid-2">
        <div class="form-group"><label class="form-label">Password</label><input class="form-input" type="password" name="password" required/></div>
        <div class="form-group"><label class="form-label">Confirm Password</label><input class="form-input" type="password" name="password_confirmation" required/></div>
      </div>
      <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;margin-top:8px">Create Student Account</button>
    </form>
  </div>
</div>

{{-- Edit Modal --}}
<div class="modal-overlay" id="editModal" onclick="if(event.target===this)this.classList.remove('open')">
  <div class="modal">
    <button class="modal-close" onclick="document.getElementById('editModal').classList.remove('open')">×</button>
    <div class="modal-title">Edit Student</div>
    <form method="POST" id="editForm">
      @csrf @method('PUT')
      <div class="grid-2">
        <div class="form-group"><label class="form-label">First Name</label><input class="form-input" name="first_name" id="edit-first" required/></div>
        <div class="form-group"><label class="form-label">Last Name</label><input class="form-input" name="last_name" id="edit-last" required/></div>
      </div>
      <div class="form-group"><label class="form-label">Email</label><input class="form-input" type="email" name="email" id="edit-email" required/></div>

      @if(!$scopedStrand)
      <div class="form-group">
        <label class="form-label">Strand</label>
        <select class="form-input" name="enrolled_strand" id="edit-strand">
          <option value="">Unassigned</option>
          <option value="ICT">ICT</option>
          <option value="Home Economics">Home Economics</option>
          <option value="Industrial Arts">Industrial Arts</option>
        </select>
      </div>
      @endif

      <div class="form-group"><label class="form-label">Contact Number</label><input class="form-input" name="contact_number" id="edit-contact"/></div>
      <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;margin-top:8px">Update Student</button>
    </form>
  </div>
</div>

<script>
function openEdit(id, first, last, email, contact, strand) {
  document.getElementById('editForm').action = '/players/' + id;
  document.getElementById('edit-first').value = first;
  document.getElementById('edit-last').value = last;
  document.getElementById('edit-email').value = email;
  document.getElementById('edit-contact').value = contact === 'null' ? '' : contact;
  const strandField = document.getElementById('edit-strand');
  if (strandField) strandField.value = strand === 'null' ? '' : strand;
  document.getElementById('editModal').classList.add('open');
}
</script>
@endsection
