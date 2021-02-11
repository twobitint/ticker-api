<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Styles -->
        <link href="/css/app.css" rel="stylesheet">
    </head>
    <body class="antialiased bg-blue-100">
        <div class="container mx-auto grid grid-cols-12 gap-4">
            <section class="col-span-2">
                <x-menu/>
            </section>
            <section class="col-span-7 grid gap-3">
                @foreach ($posts as $post)
                    <x-post :model="$post" />
                @endforeach
            </section>
            <section class="col-span-3"></section>
        </div>
    </body>
</html>
