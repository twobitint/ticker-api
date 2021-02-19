<section class="bg-white rounded-md shadow p-6" x-data="{ trending: 'all' }">
  <x-header size="lg" class="mb-3">Trending</x-header>
  <div class="flex mb-6 space-x-2">
    <a class="rounded-full py-1 px-3 m-0.5 border border-gray-300"
      :class="{ 'bg-gray-200': trending === 'all', 'font-medium': trending === 'all' }"
      href="#"
      @click="trending = 'all'"
    >Most active</a>
    <a class="rounded-full py-1 px-3 m-0.5 border border-gray-300"
      :class="{ 'bg-gray-200': trending === 'watchlist', 'font-medium': trending === 'watchlist' }"
      href="#"
      @click="trending = 'watchlist'"
    >Watchlist</a>
    <a class="rounded-full py-1 px-3 m-0.5 border border-gray-300"
      :class="{ 'bg-gray-200': trending === 'mine', 'font-medium': trending === 'mine' }"
      href="#"
      @click="trending = 'mine'"
    >Positions</a>
  </div>
  <x-stocks x-show="trending === 'all'" :list="App\Models\Stock::trending()"/>
  {{-- <x-stocks x-show="trending === 'mine'" :list="App\Models\Stock::trending('positions')"/>
  <x-stocks x-show="trending === 'watchlist'" :list="App\Models\Stock::trending('watchlist')"/> --}}
</section>
