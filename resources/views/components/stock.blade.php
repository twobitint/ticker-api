<div class="rounded-full py-1 px-2 m-0.5 border border-gray-300 font-mono text-xs text-gray-600 flex items-center">
  <div class="pr-1">
    {{ $model->symbol }}
  </div>
  @if ($model->up)
    <x-heroicon-s-arrow-up class="w-3 h-3 text-green-700"/>
    <div class="text-green-700">
      {{ number_format(abs($model->regular_market_change_percent), 2) }}%
    </div>
  @else
    <x-heroicon-s-arrow-down class="w-3 h-3 text-red-700"/>
    <div class="text-red-700">
      {{ number_format(abs($model->regular_market_change_percent), 2) }}%
    </div>
  @endif
</div>
