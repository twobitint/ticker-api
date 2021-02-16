<nav class="grid gap-6">

  <div class="flex items-center">
    <img class="h-12 w-12 rounded-full" src="{{ Auth::user()->picture_url }}" alt="avatar">
    <div class="p-2 font-semibold">
      {{ Auth::user()->name }}
    </div>
  </div>

  <hr class="border-t-2 my-10">

  <div class="flex text-gray-600 items-center">
    <x-heroicon-s-badge-check class="w-6 h-6"/>
    <span class="pl-2">Pro Member</span>
  </div>

  <div class="flex text-gray-600 items-center">
    <x-heroicon-s-collection class="w-6 h-6"/>
    <span class="pl-2">{{ Auth::user()->stocksInPositions->count() }} Stocks</span>
  </div>

</nav>
