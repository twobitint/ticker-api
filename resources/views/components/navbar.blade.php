<nav x-data="{ open: false }" class="bg-gray-800">
  <div class="container mx-auto grid grid-cols-12 gap-4 h-16 items-center">

    <section class="col-span-2">
      <a href="{{ route('home') }}" class="flex-shrink-0 flex items-center">
        <x-logo class="max-h-11 w-auto" mode="light" />
      </a>
    </section>

    <section class="col-span-6">
      <div class="text-gray-600 relative">
        <form action="{{ route('search') }}">
          <input class="border border-gray-300 bg-white w-full h-10 text-lg px-9 rounded focus:outline-none font-medium"
            type="search" name="symbol" placeholder="Search">
          <button type="submit" class="absolute left-2 top-2">
            <x-heroicon-s-search class="h-6 w-6 text-gray-400" />
          </button>
        </form>
      </div>
    </section>

    <section class="col-span-4 flex flex-row-reverse">
      <div class="flex items-center pr-2 sm:static sm:inset-auto sm:ml-6 sm:pr-0">
        <button class="bg-gray-800 p-1 rounded-full text-gray-400 hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-800 focus:ring-white">
          <span class="sr-only">View notifications</span>
          <svg class="h-6 w-6" x-description="Heroicon name: outline/bell" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
      </svg>
      </button>

      <!-- Profile dropdown -->
      <div @click.away="open = false" class="ml-3 relative" x-data="{ open: false }">
        <div>
          <button @click="open = !open" class="bg-gray-800 flex text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-800 focus:ring-white" id="user-menu" aria-haspopup="true" x-bind:aria-expanded="open">
            <span class="sr-only">Open user menu</span>
            <img class="h-8 w-8 rounded-full" src="{{ Auth::user()->picture_url }}" alt="">
          </button>
        </div>
        <div x-show="open"
          x-description="Profile dropdown panel, show/hide based on dropdown state."
          x-transition:enter="transition ease-out duration-100"
          x-transition:enter-start="transform opacity-0 scale-95"
          x-transition:enter-end="transform opacity-100 scale-100"
          x-transition:leave="transition ease-in duration-75"
          x-transition:leave-start="transform opacity-100 scale-100"
          x-transition:leave-end="transform opacity-0 scale-95"
          class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5"
          role="menu"
          aria-orientation="vertical"
          aria-labelledby="user-menu"
          style="display: none;"
        >
          <a href="{{ route('watchlist') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Watchlist</a>
          <a href="#" role="menuitem"
            class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
            @click="$refs.positionUploader.click()"
          >
            Upload Positions
            <form method="post"
              enctype="multipart/form-data"
              class="hidden"
              x-ref="positionUploaderForm"
              action="{{ route('upload-positions') }}"
            >
              @csrf
              <input name="positions"
                type="file"
                x-ref="positionUploader"
                @change="$refs.positionUploaderForm.submit()"
              >
            </form>
          </a>
          <a href="{{ route('logout') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Sign out</a>
        </div>
      </div>
    </section>

  </div>
</nav>
