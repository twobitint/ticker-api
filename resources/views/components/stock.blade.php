<span class="rounded-full py-2 px-3 border border-gray-400 font-mono text-sm text-gray-600">
    {{ $model->symbol }}
    @if ($model->regular_market_change_percent >= 0)
        <span class="text-green-700">
            <x-heroicon-s-arrow-up class="w-3 h-4 inline"/>{{ number_format(abs($model->regular_market_change_percent), 2) }}%
        </span>
    @else
        <span class="text-red-700">
            <x-heroicon-s-arrow-down class="w-3 h-4 inline"/>{{ number_format(abs($model->regular_market_change_percent), 2) }}%
        </span>
    @endif
</span>
