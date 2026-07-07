@extends('admin.layout')
@section('title','Surveys')
@section('content')
<div class="topbar">
  <div class="page-title">Acceptability <span>Surveys</span></div>
</div>

@if($avg && $avg->overall > 0)
<div style="display:grid;grid-template-columns:repeat(5,1fr);gap:16px;margin-bottom:24px">
  @foreach([
    ['Usability',            number_format($avg->usability,2), 'accent'],
    ['Interface',            number_format($avg->interface_r,2),'accent2'],
    ['Educational Value',   number_format($avg->educational,2),'accent3'],
    ['Curriculum Alignment',number_format($avg->curriculum,2), 'warn'],
    ['Overall Rating',      number_format($avg->overall,2),    'accent3'],
  ] as [$label,$val,$color])
  <div style="background:var(--card);border:1px solid var(--border);border-radius:var(--radius);padding:20px;text-align:center">
    <div style="font-size:11px;color:var(--muted);text-transform:uppercase;letter-spacing:.8px;margin-bottom:8px">{{ $label }}</div>
    <div style="font-family:'Syne',sans-serif;font-size:30px;font-weight:800;color:var(--{{ $color }})">{{ $val }}</div>
    <div style="font-size:11px;color:var(--muted);margin-top:4px">out of 5.00</div>
  </div>
  @endforeach
</div>
@endif

<div class="panel">
  <div class="panel-header"><div class="panel-title">All Survey Responses</div></div>
  <table>
    <thead><tr><th>Respondent</th><th>Usability</th><th>Interface</th><th>Educational</th><th>Curriculum</th><th>Overall</th><th>Date</th></tr></thead>
    <tbody>
      @forelse($surveys as $s)
      <tr>
        <td>
          <div style="font-weight:500">{{ $s->user->first_name ?? '—' }} {{ $s->user->last_name ?? '' }}</div>
          <div style="font-size:11px;color:var(--muted)">{{ ucfirst($s->user->role ?? '') }}</div>
        </td>
        <td style="color:var(--accent3);font-weight:600">{{ $s->usability_rating }}</td>
        <td style="color:var(--accent3);font-weight:600">{{ $s->interface_rating }}</td>
        <td style="color:var(--accent3);font-weight:600">{{ $s->educational_value }}</td>
        <td style="color:var(--accent3);font-weight:600">{{ $s->curriculum_alignment }}</td>
        <td style="font-family:'Syne',sans-serif;font-weight:800;color:var(--accent3)">{{ $s->overall_rating }}</td>
        <td style="font-size:12px;color:var(--muted)">{{ $s->submitted_at->format('M d, Y') }}</td>
      </tr>
      @empty
      <tr><td colspan="7"><div class="empty-state"><div class="empty-label">No surveys submitted yet.</div></div></td></tr>
      @endforelse
    </tbody>
  </table>
  <div class="pagination">{{ $surveys->links() }}</div>
</div>
@endsection
