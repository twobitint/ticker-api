<article class="post rounded-md shadow p-6 relative overflow-hidden {{ $model->hot ? 'bg-red-50' : 'bg-white' }}" x-data="post()">
  @if ($model->hot)
    <x-heroicon-o-fire class="absolute text-red-900 opacity-10" style="height: 400px; width: 400px; bottom: -100px; right: -100px;" />
  @endif
  <header class="flex mb-4 items-center space-x-2">
    <div title="{{ $model->subcategory }}">
      <x-dynamic-component
        :component="'heroicon-o-' . $model->hero_icon_name"
        class="w-9 h-9 text-gray-500"/>
    </div>
    <div>
      <p class="font-semibold text-sm">r/{{ $model->subreddit }}</p>
      <p class="text-gray-600 text-sm">{{ $model->posted_at->diffForHumans() }}</p>
    </div>
  </header>
  <p class="font-bold text-gray-700">
    {!! $model->title !!}
  </p>
  {{-- There is a bug with grid and word break that has to be handled via inline style here --}}
  <div class="user-content pt-3 mb-4 text-gray-600 max-w-full line-clamp-6 text-justify"
    x-ref="content"
    :class="{ 'line-clamp-6': clamp }"
    @click="toggleClamp()"
    style="word-break: break-word;">
    {!! $model->content_html !!}
  </div>
  <footer>
    <div class="flex mt-6 items-center">
      <a href="{{ $model->url }}" class="flex rounded-full p-2 hover:bg-gray-200 mr-4">
        <x-heroicon-s-thumb-up class="w-5 h-5 text-gray-500"/>
        <span class="font-bold text-gray-500">{{ $model->score }}</span>
      </a>

      <a href="{{ $model->url }}" class="flex rounded-full p-2 hover:bg-gray-200 mr-4">
        <x-heroicon-s-chat-alt class="w-5 h-5 text-gray-500"/>
        <span class="font-bold text-gray-500">{{ $model->comment_count }}</span>
      </a>

      @foreach ($model->stocks->take(4) as $stock)
        <x-stock :model="$stock"/>
      @endforeach
      {{-- @if ($model->stocks->count() > 4)
        <x-heroicon-s-dots-horizontal class="w-6 h-6 pt-2 text-gray-500"/>
      @endif --}}
    </div>
  </footer>
</article>
