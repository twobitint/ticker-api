<x-layouts.base>
  <x-navbar/>
  <div class="container mx-auto grid grid-cols-12 gap-4 pt-10 mb-20">
    <section class="col-span-2">
      <x-menu/>
    </section>
    <section class="col-span-6 grid gap-4">
      <div class="inline-flex shadow rounded-md font-bold">
        <a href="#" class="flex-1 text-center px-2 py-4 rounded-l-md border-r border-gray-200 bg-white">Recent</a>
        <a href="#" class="flex-1 text-center px-2 py-4 bg-white border-r border-gray-200 text-gray-500">Most Liked</a>
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
