@if ($value)
    <div class="font-medium">
        {{ Carbon\Carbon::parse($value)->format('j M Y') }}
    </div>
@else
    <span class="text-gray-400 text-xs">—</span>
@endif
