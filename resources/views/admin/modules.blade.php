@extends('admin.layout')
@section('title','Modules')
@section('content')

<div class="topbar">
  <div class="page-title">Game <span>Modules</span></div>
  <button class="btn btn-primary" onclick="document.getElementById('addModal').classList.add('open')">+ Add Module</button>
</div>

{{-- Filter by Strand --}}
<form method="GET" style="margin-bottom:20px;display:flex;align-items:center;gap:10px">
  <select class="form-input" style="width:220px;padding:8px 14px" name="strand_id" onchange="this.form.submit()">
    <option value="">-- All Strands --</option>
    @foreach($strands as $s)
      <option value="{{ $s->strand_id }}" {{ request('strand_id') == $s->strand_id ? 'selected' : '' }}>
        {{ $s->strand_name }}
      </option>
    @endforeach
  </select>
  @if(request('strand_id'))
    <a href="{{ route('modules.index') }}" class="btn btn-ghost" style="padding:8px 16px">Clear</a>
  @endif
</form>

<div class="panel">
  <div class="panel-header">
    <div class="panel-title">
      @if(request('strand_id'))
        @php $sel = $strands->firstWhere('strand_id', request('strand_id')); @endphp
        Showing: {{ $sel->strand_name ?? 'Selected Strand' }}
      @else
        All Modules
      @endif
    </div>
    <span style="font-size:12px;color:var(--muted)">{{ $modules->count() }} result(s)</span>
  </div>
  <table>
    <thead>
      <tr>
        <th>#</th>
        <th>Module Name</th>
        <th>Competency Area</th>
        <th>Strand</th>
        <th>Missions</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      @forelse($modules as $i => $m)
      <tr>
        <td style="color:var(--muted)">{{ $i + 1 }}</td>
        <td style="font-weight:500">{{ $m->module_name }}</td>
        <td style="color:var(--muted);font-size:13px">{{ $m->competency_area }}</td>
        <td>
          @php $sn = $m->strand->strand_name ?? ''; @endphp
          <span class="badge {{ $sn === 'ICT' ? 'blue' : ($sn === 'Cookery' ? 'purple' : 'orange') }}">
            {{ $sn ?: '—' }}
          </span>
        </td>
        <td><span class="badge green">{{ $m->missions_count }}</span></td>
        <td>
          <div style="display:flex;gap:6px">
            <button class="btn btn-ghost" style="padding:5px 12px;font-size:12px"
              onclick="openEdit({{ $m->module_id }},{{ $m->strand_id }},'{{ addslashes($m->module_name) }}','{{ addslashes($m->competency_area) }}')">
              Edit
            </button>
            <form method="POST" action="{{ route('modules.destroy', $m->module_id) }}" onsubmit="return confirm('Delete this module?')">
              @csrf @method('DELETE')
              <button type="submit" class="btn btn-danger" style="padding:5px 12px;font-size:12px">Delete</button>
            </form>
          </div>
        </td>
      </tr>
      @empty
      <tr>
        <td colspan="6">
          <div class="empty-state">
            <div class="empty-label">No modules found.</div>
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
    <div class="modal-title">Add New Module</div>
    <form method="POST" action="{{ route('modules.store') }}">
      @csrf
      <div class="form-group">
        <label class="form-label">Strand</label>
        <select class="form-input" name="strand_id" required>
          <option value="" disabled selected>-- Select Strand --</option>
          @foreach($strands as $s)
            <option value="{{ $s->strand_id }}">{{ $s->strand_name }}</option>
          @endforeach
        </select>
      </div>
      <div class="form-group">
        <label class="form-label">Module Name</label>
        <input class="form-input" name="module_name" required placeholder="e.g. Computer Hardware Servicing"/>
      </div>
      <div class="form-group">
        <label class="form-label">Competency Area</label>
        <input class="form-input" name="competency_area" required placeholder="e.g. Computer Repair and Troubleshooting"/>
      </div>
      <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;margin-top:8px">
        Create Module
      </button>
    </form>
  </div>
</div>

{{-- Edit Modal --}}
<div class="modal-overlay" id="editModal" onclick="if(event.target===this)this.classList.remove('open')">
  <div class="modal">
    <button class="modal-close" onclick="document.getElementById('editModal').classList.remove('open')">×</button>
    <div class="modal-title">Edit Module</div>
    <form method="POST" id="editForm">
      @csrf @method('PUT')
      <div class="form-group">
        <label class="form-label">Strand</label>
        <select class="form-input" name="strand_id" id="edit-strand" required>
          <option value="" disabled>-- Select Strand --</option>
          @foreach($strands as $s)
            <option value="{{ $s->strand_id }}">{{ $s->strand_name }}</option>
          @endforeach
        </select>
      </div>
      <div class="form-group">
        <label class="form-label">Module Name</label>
        <input class="form-input" name="module_name" id="edit-name" required/>
      </div>
      <div class="form-group">
        <label class="form-label">Competency Area</label>
        <input class="form-input" name="competency_area" id="edit-area" required/>
      </div>
      <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;margin-top:8px">
        Update Module
      </button>
    </form>
  </div>
</div>

<script>
function openEdit(id, strandId, name, area) {
  document.getElementById('editForm').action = '/modules/' + id;
  document.getElementById('edit-strand').value = strandId;
  document.getElementById('edit-name').value = name;
  document.getElementById('edit-area').value = area;
  document.getElementById('editModal').classList.add('open');
}
</script>
@endsection
