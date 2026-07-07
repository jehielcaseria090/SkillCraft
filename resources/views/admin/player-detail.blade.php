@extends('admin.layout')
@section('title','Player Detail')
@section('content')

<div class="topbar">
  <div style="display:flex;align-items:center;gap:16px">
    <a href="{{ route('players.index') }}" class="btn btn-ghost" style="padding:8px 14px">
      <svg style="width:14px;height:14px" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
      Back
    </a>
    <div>
      <div class="page-title">Player <span>Detail</span></div>
      <div style="font-size:13px;color:var(--muted);margin-top:2px">{{ $player->first_name }} {{ $player->last_name }}</div>
    </div>
  </div>
</div>

{{-- Player Info Card --}}
<div style="display:grid;grid-template-columns:300px 1fr;gap:20px;margin-bottom:24px">

  {{-- Left: Profile --}}
  <div class="panel" style="padding:28px;text-align:center">
    {{-- Avatar --}}
    <div style="width:80px;height:80px;border-radius:50%;background:linear-gradient(135deg,var(--accent),var(--accent2));display:flex;align-items:center;justify-content:center;font-family:'Syne',sans-serif;font-size:28px;font-weight:800;color:#fff;margin:0 auto 16px">
      {{ strtoupper(substr($player->first_name,0,1).substr($player->last_name,0,1)) }}
    </div>
    <div style="font-family:'Syne',sans-serif;font-size:18px;font-weight:700;margin-bottom:4px">
      {{ $player->first_name }} {{ $player->last_name }}
    </div>
    <div style="font-size:13px;color:var(--muted);margin-bottom:12px">{{ $player->email }}</div>
    <span class="badge {{ $player->role === 'student' ? 'blue' : 'purple' }}" style="margin-bottom:20px;display:inline-flex">
      {{ ucfirst($player->role) }}
    </span>

    <div style="border-top:1px solid var(--border);padding-top:16px;text-align:left">
      <div style="margin-bottom:10px">
        <div style="font-size:11px;color:var(--muted);text-transform:uppercase;letter-spacing:.8px;margin-bottom:3px">Username</div>
        <div style="font-size:13px">{{ $player->username ?? '—' }}</div>
      </div>
      <div style="margin-bottom:10px">
        <div style="font-size:11px;color:var(--muted);text-transform:uppercase;letter-spacing:.8px;margin-bottom:3px">Contact</div>
        <div style="font-size:13px">{{ $player->contact_number ?? '—' }}</div>
      </div>
      <div style="margin-bottom:10px">
        <div style="font-size:11px;color:var(--muted);text-transform:uppercase;letter-spacing:.8px;margin-bottom:3px">Joined</div>
        <div style="font-size:13px">{{ $player->created_at->format('M d, Y') }}</div>
      </div>
      <div>
        <div style="font-size:11px;color:var(--muted);text-transform:uppercase;letter-spacing:.8px;margin-bottom:3px">Total Assessments</div>
        <div style="font-family:'Syne',sans-serif;font-size:22px;font-weight:800;color:var(--accent)">{{ $assessments->count() }}</div>
      </div>
    </div>
  </div>

  {{-- Right: Stats --}}
  <div style="display:flex;flex-direction:column;gap:16px">

    {{-- Score Summary --}}
    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:14px">
      @php
        $postTests    = $assessments->where('assessment_type','post_test');
        $avgScore     = $postTests->count() ? round($postTests->avg('score'),1) : 0;
        $avgAccuracy  = $postTests->count() ? round($postTests->avg('accuracy_percentage'),1) : 0;
        $highestScore = $assessments->max('score') ?? 0;
      @endphp

      <div class="panel" style="padding:20px;position:relative;overflow:hidden">
        <div style="position:absolute;top:0;left:0;right:0;height:2px;background:var(--accent)"></div>
        <div style="font-size:11px;color:var(--muted);text-transform:uppercase;letter-spacing:.8px;margin-bottom:8px">Avg Score</div>
        <div style="font-family:'Syne',sans-serif;font-size:30px;font-weight:800;color:var(--accent)">{{ $avgScore }}</div>
        <div style="font-size:11px;color:var(--muted)">Post-test average</div>
      </div>

      <div class="panel" style="padding:20px;position:relative;overflow:hidden">
        <div style="position:absolute;top:0;left:0;right:0;height:2px;background:var(--accent3)"></div>
        <div style="font-size:11px;color:var(--muted);text-transform:uppercase;letter-spacing:.8px;margin-bottom:8px">Avg Accuracy</div>
        <div style="font-family:'Syne',sans-serif;font-size:30px;font-weight:800;color:var(--accent3)">{{ $avgAccuracy }}%</div>
        <div style="font-size:11px;color:var(--muted)">Across all missions</div>
      </div>

      <div class="panel" style="padding:20px;position:relative;overflow:hidden">
        <div style="position:absolute;top:0;left:0;right:0;height:2px;background:var(--warn)"></div>
        <div style="font-size:11px;color:var(--muted);text-transform:uppercase;letter-spacing:.8px;margin-bottom:8px">Highest Score</div>
        <div style="font-family:'Syne',sans-serif;font-size:30px;font-weight:800;color:var(--warn)">{{ $highestScore }}</div>
        <div style="font-size:11px;color:var(--muted)">Best performance</div>
      </div>
    </div>

    {{-- Pre vs Post Comparison --}}
    @php
      $missions = $assessments->pluck('mission')->filter()->unique('mission_id');
    @endphp
    @if($missions->count())
    <div class="panel">
      <div class="panel-header">
        <div class="panel-title">📊 Pre-test vs Post-test</div>
      </div>
      <table>
        <thead>
          <tr>
            <th>Mission</th>
            <th>Strand</th>
            <th>Pre-test</th>
            <th>Post-test</th>
            <th>Improvement</th>
          </tr>
        </thead>
        <tbody>
          @foreach($missions as $mission)
          @php
            $pre  = $assessments->where('mission_id', $mission->mission_id)->where('assessment_type','pre_test')->first();
            $post = $assessments->where('mission_id', $mission->mission_id)->where('assessment_type','post_test')->first();
            $improvement = ($pre && $post) ? ($post->score - $pre->score) : null;
          @endphp
          <tr>
            <td style="font-weight:500">{{ $mission->mission_title }}</td>
            <td>
              @php $strand = $mission->module->strand->strand_name ?? '—'; @endphp
              <span class="badge {{ $strand==='ICT'?'blue':($strand==='Home Economics'?'purple':'orange') }}">
                {{ $strand }}
              </span>
            </td>
            <td style="font-family:'Syne',sans-serif;font-weight:700;color:var(--muted)">
              {{ $pre ? $pre->score : '—' }}
            </td>
            <td style="font-family:'Syne',sans-serif;font-weight:700;color:var(--accent3)">
              {{ $post ? $post->score : '—' }}
            </td>
            <td>
              @if($improvement !== null)
                <span style="font-family:'Syne',sans-serif;font-weight:700;color:{{ $improvement >= 0 ? 'var(--accent3)' : '#f74f4f' }}">
                  {{ $improvement >= 0 ? '+' : '' }}{{ $improvement }}
                </span>
              @else
                <span style="color:var(--muted)">—</span>
              @endif
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    @endif

  </div>
</div>

{{-- All Assessments Table --}}
<div class="panel">
  <div class="panel-header">
    <div class="panel-title">📋 All Assessment Records</div>
    <span style="font-size:12px;color:var(--muted)">{{ $assessments->count() }} total</span>
  </div>

  @if($assessments->count())
  <table>
    <thead>
      <tr>
        <th>#</th>
        <th>Mission</th>
        <th>Strand</th>
        <th>Type</th>
        <th>Score</th>
        <th>Accuracy</th>
        <th>Date Taken</th>
      </tr>
    </thead>
    <tbody>
      @foreach($assessments as $i => $assessment)
      <tr>
        <td style="color:var(--muted);font-size:12px">{{ $i + 1 }}</td>
        <td style="font-weight:500">{{ $assessment->mission->mission_title ?? '—' }}</td>
        <td>
          @php $strand = $assessment->mission->module->strand->strand_name ?? '—'; @endphp
          <span class="badge {{ $strand==='ICT'?'blue':($strand==='Home Economics'?'purple':'orange') }}">
            {{ $strand }}
          </span>
        </td>
        <td>
          @php
            $typeColor = $assessment->assessment_type === 'post_test' ? 'green'
                       : ($assessment->assessment_type === 'pre_test' ? 'orange' : 'blue');
            $typeLabel = str_replace('_', ' ', ucfirst($assessment->assessment_type));
          @endphp
          <span class="badge {{ $typeColor }}">{{ $typeLabel }}</span>
        </td>
        <td style="font-family:'Syne',sans-serif;font-weight:700;color:var(--accent3)">
          {{ $assessment->score }}
        </td>
        <td style="color:var(--text)">
          {{ number_format($assessment->accuracy_percentage, 1) }}%
        </td>
        <td style="font-size:12px;color:var(--muted)">
          {{ \Carbon\Carbon::parse($assessment->taken_at)->format('M d, Y h:i A') }}
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>
  @else
  <div class="empty-state">
    <div style="font-size:32px;margin-bottom:10px">📋</div>
    <div>No assessments recorded yet for this player.</div>
  </div>
  @endif
</div>

{{-- Delete Player --}}
<div style="margin-top:16px;display:flex;justify-content:flex-end">
  <form method="POST" action="{{ route('players.destroy', $player->user_id) }}"
        onsubmit="return confirm('Delete {{ $player->first_name }}? This cannot be undone.')">
    @csrf @method('DELETE')
    <button type="submit" class="btn btn-danger">
      <svg style="width:14px;height:14px" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4h6v2"/></svg>
      Delete Player
    </button>
  </form>
</div>

@endsection
