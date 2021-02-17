@props(['list' => []])

<div {{ $attributes }}>
  @foreach ($list as $stock)
    <a href="{{ route('stock', $stock) }}" class="flex items-center font-medium p-2 rounded my-2 hover:bg-gray-100">
      <div class="flex flex-col flex-grow">
        <div class="rounded text-white p-1 w-12 text-center text-xs font-bold" style="background-color: #{{ $stock->color }}">
          {{ $stock->symbol }}
        </div>
        <div class="text-xs line-clamp-1">
          {{ $stock->name }}
        </div>
        <div class="text-xs text-gray-400">
          {{ $stock->posts_sum_popularity }}
        </div>
      </div>

      @php
        $chartId = md5(rand())
      @endphp

      <div class="ct-chart" style="width: 150px;" id="stock-chart-{{ $chartId }}"></div>

      {{-- <div class="w-20 flex-none text-right">
        ${{ number_format($stock->regular_market_price, 2) }}
      </div>
      <div class="w-24 flex-none flex flex-row-reverse">
        <div class="rounded-md flex items-center px-2 py-1 {{
            $stock->up
              ? 'bg-green-200 text-green-700'
              : 'bg-red-200 text-red-700'
          }}">
          <x-dynamic-component
            :component="'heroicon-s-arrow-' . ($stock->up ? 'up' : 'down')"
            class="w-4 h-4 {{
              $stock->up
                ? 'text-green-700'
                : 'text-red-700'
            }}"/>
          {{ number_format(abs($stock->regular_market_change_percent), 2) }}%
        </div>
      </div>
      <x-heroicon-o-plus-circle class="flex-none w-6 h-6 text-gray-500 ml-2"/> --}}
    </a>
    {{-- <div class="w-full h-20">
      <div class="ct-chart" id="stock-chart-{{ $stock->symbol }}"></div>
    </div> --}}
    <script>
      new Chartist.Line('#stock-chart-{{ $chartId }}', {
        series: [
          //[200, 187, 176, 154, 111, 109, 123, 134, 198, 193, 179],
          //{{ $stock->snapshots->pluck('popularity') }}
          {{ $stock->popularityGraph }}
        ]
      }, {
        axisX: {
          offset: 0,
          showLabel: false,
          showGrid: false,
        },
        axisY: {
          offset: 0,
          showLabel: false,
          showGrid: false,
        },
        fullWidth: true,
        showPoint: false,
        lineSmooth: false,
        //showArea: true,
        height: 50,
        high: {{ $stock->popularityGraphHigh }},
        low: {{ $stock->popularityGraphLow }},
        chartPadding: {
          top: 0,
          right: 0,
          bottom: 0,
          left: 0,
        },
      });
    </script>
  @endforeach
</div>
