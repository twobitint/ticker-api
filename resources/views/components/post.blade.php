<article class="bg-white rounded-md shadow p-6">
    <header class="font-bold text-base">
        {{ $model->title }}
    </header>
    <p class="line-clamp-6 pt-3 mb-4 text-sm">
        {!! $model->content_html !!}
    </p>
    <footer>
        <div>
            @foreach ($model->stocks as $stock)
                <x-stock :model="$stock"/>
            @endforeach
        </div>

        {{-- <x-heroicon-s-thumb-up class="w-6 h-6 text-gray-500"/>

        <a href="{{ $model->url }}">
            <x-heroicon-s-chat-alt class="w-6 h-6 text-gray-500"/>
        </a> --}}
    </footer>
</article>
