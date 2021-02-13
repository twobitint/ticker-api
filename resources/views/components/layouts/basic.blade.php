<x-layouts.base class="bg-gradient-to-br from-white via-gray-200 to-gray-600 tracking-wide">
  <div class="mt-4">
    <nav class="container mx-auto flex justify-between items-center">

      <section class="flex-none w-1/4">
        {{-- <img class="max-h-16 w-auto" src="logo.svg" alt="Workflow"> --}}
        <x-logo class="max-h-16 w-auto"/>
      </section>

      <section class="space-x-8 text-lg font-semibold text-gray-500">
        <a href="{{ route('welcome') }}">Product</a>
        <a href="{{ route('welcome') }}">Features</a>
        <a href="{{ route('welcome') }}">Company</a>
      </section>

      <section class="flex-none w-1/4 text-right">
        <a class="rounded shadow-md font-medium bg-white text-md px-6 py-3" href="{{ route('login') }}">Log in</a>
      </section>

    </nav>
    <main>
      {{ $slot }}
    </main>
    <x-footer/>
  </div>
</x-layouts-base>
