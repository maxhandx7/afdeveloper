<ul class="posts">
    @foreach ($posts as $post)
        <li>
            <a href="{{ route('blog.show', $post) }}">
                <time datetime="{{ $post->published_at?->toDateString() }}">{{ $post->published_at?->translatedFormat('j M Y') }}</time>
                <div>
                    <h3>{{ $post->title }}</h3>
                    <p>{{ $post->summary(150) }}</p>
                </div>
                <span class="read">{{ $post->readingMinutes() }} min de lectura</span>
            </a>
        </li>
    @endforeach
</ul>
