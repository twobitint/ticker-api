<x-layouts.base>
  <x-navbar/>
  <div class="container mx-auto grid grid-cols-12 gap-4 pt-10 mb-20">

    <section class="col-span-2">
      <x-menu/>
    </section>

    <section class="col-span-6 grid gap-4">

      <article class="bg-white rounded-md shadow px-6 py-1">
        {{-- <header class="flex mb-4">
          {{ $stock->symbol }}
        </header> --}}

        <x-tradingview.symbol-info :stock="$stock"/>
        {{-- <x-tradingview.symbol-overview :stock="$stock"/> --}}
        <x-tradingview.advanced-chart :stock="$stock" />

        <div class="flex flex-row-reverse">
          <div class="tradingview-widget-copyright">
            <a href="https://www.tradingview.com/symbols/{{ $stock->symbol }}/" rel="noopener" target="_blank">
              <span class="blue-text">{{ $stock->symbol }} Chart</span>
            </a> by TradingView
          </div>
        </div>

      </article>

      <header>
        Recent Posts
      </header>

      @foreach ($stock->posts as $post)
        <x-post :model="$post" />
      @endforeach

    </section>

    <section class="col-span-4">
      <aside class="bg-white rounded-md shadow px-6 py-1">
        {{-- <x-trending-stocks/> --}}
        <x-tradingview.symbol-profile :stock="$stock"/>
        <x-tradingview.fundamental-data :stock="$stock"/>
      </aside>
    </section>

  </div>
</x-layouts.base>
