<a href="{{ route('stock', $model) }}"
  class="relative rounded-full py-1 px-2 m-0.5 border font-mono text-xs text-gray-600 flex items-center hover:bg-gray-100
  {{ $model->marked ? ($model->up ? 'border-green-700 bg-green-100 pr-7' : 'border-red-700 bg-red-100 pr-7') : 'border-gray-300' }}
  ">
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

  @if ($model->marked)
    <x-heroicon-s-bookmark class="h-6 w-6 absolute -top-1 right-1 {{ $model->up ? 'text-green-700' : 'text-red-700' }}"/>
  @endif
</a>
