@extends('admin.layout')
@section('title','Missions')
@section('content')
<div class="topbar">
  <div class="page-title">Game <span>Missions</span></div>
  <button class="btn btn-primary" onclick="document.getElementById('addModal').classList.add('open')">+ Add Mission</button>
</div>
<div class="panel">
  <div class="panel-header"><div class="panel-title">All Missions</div></div>
  <table>
    <thead><tr><th>#</th><th>Mission</th><th>Module</th><th>Strand</th><th>Difficulty</th><th>Max Score</th><th>Actions</th></tr></thead>
    <tbody>
      @forelse($missions as $i => $m)
      <tr>
        <td style="color:var(--muted)">{{ $i+1 }}</td>
        <td>
          <div style="font-weight:500">{{ $m->mission_title }}</div>
          <div style="font-size:11px;color:var(--muted)">{{ Str::limit($m->scenario_description,55) }}</div>
        </td>
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
        <td>
          <div style="display:flex;gap:6px">
            <button class="btn btn-ghost" style="padding:5px 12px;font-size:12px"
              onclick="openEdit({{ $m->mission_id }},{{ $m->module_id }},'{{ addslashes($m->mission_title) }}','{{ addslashes($m->scenario_description) }}',{{ $m->max_score }},{{ $m->difficulty_level }})">Edit</button>
            <form method="POST" action="{{ route('missions.destroy',$m->mission_id) }}" onsubmit="return confirm('Delete this mission?')">
              @csrf @method('DELETE')
              <button type="submit" class="btn btn-danger" style="padding:5px 12px;font-size:12px">Delete</button>
            </form>
          </div>
        </td>
      </tr>
      @empty
      <tr><td colspan="7"><div class="empty-state"><div class="empty-label">No missions yet.</div></div></td></tr>
      @endforelse
    </tbody>
  </table>
</div>

<div class="modal-overlay" id="addModal" onclick="if(event.target===this)this.classList.remove('open')">
  <div class="modal">
    <button class="modal-close" onclick="document.getElementById('addModal').classList.remove('open')">×</button>
    <div class="modal-title">Add New Mission</div>
    <form method="POST" action="{{ route('missions.store') }}">
      @csrf
      <div class="form-group"><label class="form-label">Module</label>
        <select class="form-input" name="module_id" required>
          <option value="">Select Module</option>
          @foreach($modules as $mod)<option value="{{ $mod->module_id }}">{{ $mod->module_name }} — {{ $mod->strand->strand_name }}</option>@endforeach
        </select>
      </div>
      <div class="form-group"><label class="form-label">Mission Title</label><input class="form-input" name="mission_title" required/></div>
      <div class="form-group"><label class="form-label">Scenario Description</label><textarea class="form-input" name="scenario_description" rows="3" required></textarea></div>
      <div class="grid-2">
        <div class="form-group"><label class="form-label">Max Score</label><input class="form-input" name="max_score" type="number" value="100" required/></div>
        <div class="form-group"><label class="form-label">Difficulty</label>
          <select class="form-input" name="difficulty_level" required>
            <option value="1">Easy</option><option value="2">Medium</option><option value="3">Hard</option>
          </select>
        </div>
      </div>
      <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;margin-top:8px">Create Mission</button>
    </form>
  </div>
</div>

<div class="modal-overlay" id="editModal" onclick="if(event.target===this)this.classList.remove('open')">
  <div class="modal">
    <button class="modal-close" onclick="document.getElementById('editModal').classList.remove('open')">×</button>
    <div class="modal-title">Edit Mission</div>
    <form method="POST" id="editForm">
      @csrf @method('PUT')
      <div class="form-group"><label class="form-label">Module</label>
        <select class="form-input" name="module_id" id="edit-module" required>
          @foreach($modules as $mod)<option value="{{ $mod->module_id }}">{{ $mod->module_name }} — {{ $mod->strand->strand_name }}</option>@endforeach
        </select>
      </div>
      <div class="form-group"><label class="form-label">Mission Title</label><input class="form-input" name="mission_title" id="edit-title" required/></div>
      <div class="form-group"><label class="form-label">Scenario Description</label><textarea class="form-input" name="scenario_description" id="edit-desc" rows="3" required></textarea></div>
      <div class="grid-2">
        <div class="form-group"><label class="form-label">Max Score</label><input class="form-input" name="max_score" id="edit-score" type="number" required/></div>
        <div class="form-group"><label class="form-label">Difficulty</label>
          <select class="form-input" name="difficulty_level" id="edit-diff" required>
            <option value="1">Easy</option><option value="2">Medium</option><option value="3">Hard</option>
          </select>
        </div>
      </div>
      <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;margin-top:8px">Update Mission</button>
    </form>
  </div>
</div>
<script>
function openEdit(id,modId,title,desc,score,diff){
  document.getElementById('editForm').action='/missions/'+id;
  document.getElementById('edit-module').value=modId;
  document.getElementById('edit-title').value=title;
  document.getElementById('edit-desc').value=desc;
  document.getElementById('edit-score').value=score;
  document.getElementById('edit-diff').value=diff;
  document.getElementById('editModal').classList.add('open');
}
</script>
@endsection
