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
        $chartId = '_'.md5(rand())
      @endphp

      <div class="ct-chart" id="{{ $chartId }}"></div>
      <style>
        #{{ $chartId }} .ct-series-a .ct-bar {
          stroke: #{{ $stock->color }};
        }
      </style>
      <script>
        new Chartist.Bar('#{{ $chartId }}', {
          series: [
            @json($stock->popularityGraph)
          ]
        }, {
          axisX: {
            offset: 0,
            showLabel: false,
            showGrid: false,
            scaleMinSpace: 0,
          },
          axisY: {
            offset: 0,
            showLabel: false,
            showGrid: false,
          },
          seriesBarDistance: 0,
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
