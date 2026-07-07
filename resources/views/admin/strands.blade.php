@extends('admin.layout')
@section('title','Strands')
@section('content')

<div class="topbar">
  <div class="page-title">Game <span>Strands</span></div>
  <button class="btn btn-primary" onclick="document.getElementById('addModal').classList.add('open')">+ Add Strand</button>
</div>

{{-- Filter --}}
<form method="GET" style="margin-bottom:20px;display:flex;align-items:center;gap:10px">
  <select class="form-input" style="width:220px;padding:8px 14px" name="strand_name" onchange="this.form.submit()">
    <option value="">-- All Strands --</option>
    @foreach($strandNames as $name)
      <option value="{{ $name }}" {{ request('strand_name') == $name ? 'selected' : '' }}>
        {{ $name }}
      </option>
    @endforeach
  </select>
  @if(request('strand_name'))
    <a href="{{ route('strands.index') }}" class="btn btn-ghost" style="padding:8px 16px">Clear</a>
  @endif
</form>

<div class="panel">
  <div class="panel-header">
    <div class="panel-title">
      @if(request('strand_name'))
        Showing: {{ request('strand_name') }}
      @else
        All Strands
      @endif
    </div>
    <span style="font-size:12px;color:var(--muted)">{{ $strands->count() }} result(s)</span>
  </div>
  <table>
    <thead>
      <tr>
        <th>#</th>
        <th>Strand Name</th>
        <th>Description</th>
        <th>Modules</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      @forelse($strands as $i => $s)
      <tr>
        <td style="color:var(--muted)">{{ $i + 1 }}</td>
        <td>
          <span class="badge {{ $s->strand_name === 'ICT' ? 'blue' : ($s->strand_name === 'Cookery' ? 'purple' : 'orange') }}">
            {{ $s->strand_name }}
          </span>
        </td>
        <td style="color:var(--muted);font-size:13px">{{ $s->description }}</td>
        <td><span class="badge blue">{{ $s->modules_count }} modules</span></td>
        <td>
          <div style="display:flex;gap:6px">
            <button class="btn btn-ghost" style="padding:5px 12px;font-size:12px"
              onclick="openEdit({{ $s->strand_id }},'{{ addslashes($s->strand_name) }}','{{ addslashes($s->description) }}')">
              Edit
            </button>
            <form method="POST" action="{{ route('strands.destroy', $s->strand_id) }}" onsubmit="return confirm('Delete this strand?')">
              @csrf @method('DELETE')
              <button type="submit" class="btn btn-danger" style="padding:5px 12px;font-size:12px">Delete</button>
            </form>
          </div>
        </td>
      </tr>
      @empty
      <tr>
        <td colspan="5">
          <div class="empty-state">
            <div class="empty-label">No strands found.</div>
          </div>
        </td>
      </tr>
      @endforelse
    </tbody>
  </table>
</div>

{{-- Add Modal --}}
<div class="modal-overlay" id="addModal" onclick="if(event.target===this)this.classList.remove('open')">
  <div class="modal">
    <button class="modal-close" onclick="document.getElementById('addModal').classList.remove('open')">×</button>
    <div class="modal-title">Add New Strand</div>
    <form method="POST" action="{{ route('strands.store') }}">
      @csrf
      <div class="form-group">
        <label class="form-label">Strand Name</label>
        <input class="form-input" name="strand_name" required placeholder="e.g. ICT"/>
      </div>
      <div class="form-group">
        <label class="form-label">Description</label>
        <textarea class="form-input" name="description" rows="3" required></textarea>
      </div>
      <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;margin-top:8px">
        Create Strand
      </button>
    </form>
  </div>
</div>

{{-- Edit Modal --}}
<div class="modal-overlay" id="editModal" onclick="if(event.target===this)this.classList.remove('open')">
  <div class="modal">
    <button class="modal-close" onclick="document.getElementById('editModal').classList.remove('open')">×</button>
    <div class="modal-title">Edit Strand</div>
    <form method="POST" id="editForm">
      @csrf @method('PUT')
      <div class="form-group">
        <label class="form-label">Strand Name</label>
        <input class="form-input" name="strand_name" id="edit-name" required/>
      </div>
      <div class="form-group">
        <label class="form-label">Description</label>
        <textarea class="form-input" name="description" id="edit-desc" rows="3" required></textarea>
      </div>
      <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;margin-top:8px">
        Update Strand
      </button>
    </form>
  </div>
</div>

<script>
function openEdit(id, name, desc) {
  document.getElementById('editForm').action = '/strands/' + id;
  document.getElementById('edit-name').value = name;
  document.getElementById('edit-desc').value = desc;
  document.getElementById('editModal').classList.add('open');
}
</script>
@endsection
