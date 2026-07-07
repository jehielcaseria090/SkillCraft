@extends('admin.layout')
@section('title','Assessments')
@section('content')
<div class="topbar">
  <div class="page-title">Player <span>Assessments</span></div>
  <form method="GET" style="display:flex;gap:8px">
    <select class="form-input" style="width:160px;padding:7px 12px" name="type" onchange="this.form.submit()">
      <option value="">All Types</option>
      <option value="pre_test"  {{ request('type')=='pre_test' ?'selected':'' }}>Pre-Test</option>
      <option value="post_test" {{ request('type')=='post_test'?'selected':'' }}>Post-Test</option>
      <option value="practice"  {{ request('type')=='practice' ?'selected':'' }}>Practice</option>
    </select>
  </form>
</div>
<div class="panel">
  <div class="panel-header"><div class="panel-title">All Assessment Records</div></div>
  <table>
    <thead><tr><th>Player</th><th>Mission</th><th>Type</th><th>Score</th><th>Accuracy</th><th>Date Taken</th></tr></thead>
    <tbody>
      @forelse($assessments as $a)
      <tr>
        <td>
          <div style="font-weight:500">{{ $a->user->first_name ?? '—' }} {{ $a->user->last_name ?? '' }}</div>
          <div style="font-size:11px;color:var(--muted)">{{ $a->user->email ?? '' }}</div>
        </td>
        <td style="font-size:13px;color:var(--muted)">{{ $a->mission->mission_title ?? '—' }}</td>
        <td>
          <span class="badge {{ $a->assessment_type==='pre_test'?'orange':($a->assessment_type==='post_test'?'green':'blue') }}">
            {{ str_replace('_',' ',ucfirst($a->assessment_type)) }}
          </span>
        </td>
        <td style="font-family:'Syne',sans-serif;font-weight:700;color:var(--accent3)">{{ $a->score }}</td>
        <td>
          <div style="display:flex;align-items:center;gap:8px">
            <div style="width:70px;height:5px;background:var(--border);border-radius:99px;overflow:hidden">
              <div style="height:100%;width:{{ number_format($a->accuracy_percentage, 1) }}%;background:linear-gradient(90deg,var(--accent),var(--accent2));border-radius:99px"></div>
            </div>
            <span style="font-size:12px;color:var(--muted)">{{ number_format($a->accuracy_percentage, 1) }}%</span>
          </div>
        </td>
        <td style="font-size:12px;color:var(--muted)">{{ $a->taken_at->format('M d, Y h:i A') }}</td>
      </tr>
      @empty
      <tr><td colspan="6"><div class="empty-state"><div class="empty-label">No assessments recorded yet.</div></div></td></tr>
      @endforelse
    </tbody>
  </table>
  <div class="pagination">{{ $assessments->withQueryString()->links() }}</div>
</div>
@endsection
