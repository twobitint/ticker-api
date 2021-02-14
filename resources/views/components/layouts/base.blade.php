<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>tkkr.dev</title>

    <link rel="icon" type="image/svg+xml" href="/logo.svg">

    <!-- Styles -->
    <link href="/css/app.css" rel="stylesheet">

    <!-- Temporary JS -->
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>

  </head>
  <body {{ $attributes->merge(['class' => 'antialiased']) }}>
    {{ $slot }}

    <x-footer/>

    <!-- Temporary JS -->
    <script>
      function post() {
        return {
          clamp: true,
          toggleClamp() {
            const el = this.$refs.content
            if (this.clamp && el.scrollHeight > el.clientHeight) {
              this.clamp = false
            } else if (!this.clamp) {
              this.clamp = true
            }
          }
        }
      }
    </script>

  </body>
</html>
