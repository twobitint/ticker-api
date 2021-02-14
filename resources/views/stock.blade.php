<x-layouts.base>
  <x-navbar/>
  <div class="container mx-auto grid grid-cols-12 gap-4 pt-10 mb-20">

    <section class="col-span-2">
      <x-menu/>
    </section>

    <section class="col-span-6 grid gap-4">

      <article class="bg-white rounded-md shadow p-6">
        <header class="flex mb-4">
          {{ $stock->symbol }}
        </header>

        <div>

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
      <x-trending-stocks/>
    </section>

  </div>
</x-layouts.base>
