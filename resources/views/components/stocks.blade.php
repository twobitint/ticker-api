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
        {{-- <div class="text-xs text-gray-400">
          {{ $stock->mentions_count }}
        </div> --}}
      </div>

      @php
        $chartId = md5(rand())
      @endphp

      <div class="ct-chart" id="stock-chart-{{ $chartId }}"></div>

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

      {{-- <div class="w-full h-20">
        <div class="ct-chart" id="stock-chart-{{ $stock->symbol }}"></div>
      </div> --}}
      <script>
        new Chartist.Bar('#stock-chart-{{ $chartId }}', {
          series: [
            @json($stock->popularityGraph)
          ]
        }, {
          axisX: {
            offset: 0,
            showLabel: false,
            showGrid: false,
            scaleMinSpace: 0,
            //type: Chartist.AutoScaleAxis,
          },
          axisY: {
            offset: 0,
            showLabel: false,
            showGrid: false,
          },
          //referenceValue: null,
          seriesBarDistance: 0,
          //distributeSeries: true,
          //fullWidth: true,
          //showArea: true,
          width: 76,
          height: 40,
          chartPadding: {
            top: 0,
            right: 0,
            bottom: 0,
            left: 0,
          },
        });
      </script>
    </a>
  @endforeach
</div>
