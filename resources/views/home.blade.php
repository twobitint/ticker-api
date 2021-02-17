<x-layouts.base>
  <x-navbar/>
  <div class="container mx-auto grid grid-cols-12 gap-4 pt-10 mb-20">
    <section class="col-span-2">
      <x-menus.main/>
    </section>
    <section class="col-span-6 grid gap-4">
      <div class="inline-flex shadow rounded-md font-bold">
        <a href="{{ route('home', ['sort' => 'posted_at']) }}"
          class="flex-1 text-center px-2 py-4 bg-white rounded-l-md border-r border-gray-200 {{ request()->query('sort') == 'posted_at' ? '' : 'text-gray-500' }}"
        >Recent</a>
        <a href="{{ route('home', ['sort' => 'popularity']) }}"
          class="flex-1 text-center px-2 py-4 bg-white border-r border-gray-200 {{ request()->query('sort') == 'popularity' ? '' : 'text-gray-500' }}">Hot</a>
        <a href="#" class="flex-1 text-center px-2 py-4 rounded-r-md bg-white text-gray-500">Most Answers</a>
      </div>
      @foreach ($posts as $post)
        <x-post :model="$post" />
      @endforeach
    </section>
    <section class="col-span-4">
      <x-trending-stocks/>
    </section>
  </div>
</x-layouts.base>
