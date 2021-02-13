<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>tkkr.dev</title>

        <!-- Styles -->
        <link href="/css/app.css" rel="stylesheet">
    </head>
    <body class="antialiased bg-gray-100">
        <x-navbar/>
        <div class="container mx-auto grid grid-cols-12 gap-4 pt-10">
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
    </body>
</html>
