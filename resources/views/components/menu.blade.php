<nav class="grid gap-2">
  <a href="/" class="{{ request()->is('/') ? 'bg-gray-300 rounded' : '' }} flex items-center p-2">
    <x-heroicon-o-home class="w-6 h-6 mr-3"/>
    <span class="font-semibold">Home</span>
  </a>
  <a href="" class="flex items-center p-2">
    <x-heroicon-o-fire class="w-6 h-6 mr-3"/>
    <span class="font-semibold">Popular</span>
  </a>
  <a href="" class="flex items-center p-2">
    <x-heroicon-o-user-group class="w-6 h-6 mr-3"/>
    <span class="font-semibold">Communities</span>
  </a>
</nav>
<hr class="border-t-2 my-10">
<div class="uppercase font-semibold mb-4">
  Categories
</div>
<nav class="grid gap-1 font-semibold">
  @foreach (config('categories') as $cat => $subs)
    <a class="capitalize px-2 py-1 {{ request()->is('cat/' . $cat) ? 'bg-gray-300 rounded' : '' }}"
      href="{{ route('cat', ['cat' => $cat]) }}"
    >
      {{ $cat }}
    </a>
  @endforeach
</nav>
