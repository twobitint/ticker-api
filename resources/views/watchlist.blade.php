<x-layouts.profile>
  <x-header>Watchlist</x-header>

  <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
    <table class="min-w-full divide-y divide-gray-200">
      <thead class="bg-gray-50">
        <tr>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
          <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Value</th>
          <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Change</th>
          <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Target</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Owned</th>
          <th></th>
        </tr>
      </thead>
      <tbody class="bg-white divide-y divide-gray-200">
        @foreach (Auth::user()->stocksInWatchlist as $stock)
          <tr>
            <td class="px-6 py-4 whitespace-nowrap">
              <a href="{{ route('stock', $stock) }}">{{ $stock->symbol }}</a>
            </td>
            <td class="px-6 py-4 font-mono text-right whitespace-nowrap">
              ${{ number_format($stock->regular_market_price, 2) }}
            </td>
            <td class="px-6 py-4 font-mono whitespace-nowrap flex flex-row-reverse">
              <div class="rounded-md flex items-center px-2 py-1 justify-center {{
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
            </td>
            <td class="px-6 py-4 font-mono text-right whitespace-nowrap">
              ${{ number_format($stock->regular_market_price + rand(-2, 2), 2) }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap">Yes</td>
            <td class="px-6 py-4 text-right whitespace-nowrap"><a href="">Edit</a></td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</x-layouts.profile>
