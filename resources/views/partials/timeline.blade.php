{{-- FAIL: resources/views/partials/timeline.blade.php --}}
{{--
  Garis masa sejarah satu aduan. Ditulis SEKALI dan digunakan
  oleh halaman admin dan Report Log teknisyen.

  Cara guna:  @include('partials.timeline', ['logs' => $complaint->statusLogs])
--}}

@if ($logs->isEmpty())
  <p style="font-size:.85rem;color:var(--muted);margin:0">No history recorded yet.</p>
@else
  <ul class="tl">
    @foreach ($logs as $log)
      <li class="{{ $log->new_status === 'completed' ? 'tl-done' : '' }}">
        <div class="tl-title">
          {{ $log->label() }}
          <span class="tl-by">by {{ $log->updatedBy?->name ?? 'Unknown user' }}</span>
        </div>
        <div class="tl-time">{{ $log->created_at->format('d M Y, g:i a') }}</div>
        @if ($log->note)
          <div class="tl-note">{{ $log->note }}</div>
        @endif
      </li>
    @endforeach
  </ul>
@endif
