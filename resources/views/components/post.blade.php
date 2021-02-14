<article class="bg-white rounded-md shadow p-6" x-data="post()">
    <header class="flex mb-4">
        <div title="{{ $model->subcategory }}">
            <x-dynamic-component
                :component="'heroicon-o-' . $model->hero_icon_name"
                class="w-9 h-9 text-gray-500"/>
        </div>
        <div class="ml-2">
            <p class="font-semibold text-sm">r/{{ $model->category }}</p>
            <p class="text-gray-600 text-sm">{{ $model->posted_at->diffForHumans() }}</p>
        </div>
    </header>
    <p class="font-bold text-base">
        <a href="{{ $model->url }}">{{ $model->title }}</a>
    </p>
    {{-- There is a bug with grid and word break that has to be handled via inline style here --}}
    <div class="user-content pt-3 mb-4 text-gray-600 max-w-full line-clamp-6"
      x-ref="content"
      :class="{ 'line-clamp-6': clamp }"
      @click="toggleClamp()"
      style="word-break: break-word;">
      {!! $model->content_html !!}
    </div>
    <footer>
        <div class="flex flex-wrap -m-0.5">
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
