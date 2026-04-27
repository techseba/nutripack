@if ($value)
    <div class="font-medium">
        {{ $value->format('M j, Y') }}
    </div>
    <div class="text-gray-500 text-xs">
        {{ $value->format('h:i A') }}
    </div>
@else
    <span class="text-gray-400 text-xs">—</span>
@endif
