<section class="bg-white rounded-md shadow p-6">
  <header class="mb-6 text-lg font-medium">Trending</header>
  <div class="grid gap-5">
    @foreach ($stocks as $stock)
      <div class="flex items-center font-medium">
        <div class="flex flex-col flex-grow">
          <div class="rounded text-white p-1 w-12 text-center text-xs font-bold" style="background-color: #{{ $stock->color }}">
            {{ $stock->symbol }}
          </div>
          <div class="text-xs line-clamp-1">
            {{ $stock->name }}
          </div>
          <div class="text-xs text-gray-400">
            {{ $stock->market_cap_for_humans }}
          </div>
        </div>
        <div class="w-20 flex-none text-right">
          ${{ number_format($stock->regular_market_price, 2) }}
        </div>
        <div class="w-24 flex-none flex flex-row-reverse">
          <div class="rounded-md flex items-center p-1 {{
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
            {{ abs(number_format($stock->regular_market_change_percent, 2)) }}%
          </div>
        </div>
        <x-heroicon-o-plus-circle class="flex-none w-6 h-6 text-gray-500 ml-2"/>
      </div>
    @endforeach
  </div>
</section>