@props(['size' => 'xl'])

<header {{ $attributes->merge(['class' => 'text-'.$size]) }}>
  {{ $slot }}
</header>
