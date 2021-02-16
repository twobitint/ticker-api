<x-layouts.base>
  <x-navbar/>
  <div class="container mx-auto grid grid-cols-12 gap-4 pt-10 mb-20">
    <section class="col-span-2">
      <x-menus.profile/>
    </section>
    <section class="col-span-10 grid gap-4">
      {{ $slot }}
    </section>
  </div>
</x-layouts.base>
