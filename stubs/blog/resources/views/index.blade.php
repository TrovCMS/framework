<x-layouts.base :meta="$meta">

    @section('header')
        <x-headers.default />
    @endsection

    <div class="py-8 lg:py-12">
        <x-layouts.two-column-right>
            <x-prose>
                @if ($posts)
                    <h1>Recent Blog Posts</h1>
                    <ul role="list">
                        @foreach ($posts as $post)
                            <li>
                                <article>
                                    <h2><a href="{{ route('blog.show', $post) }}">{{ $post->title }}</a></h2>
                                    <span class="sr-only">Post Tags</span>
                                    <ul role="list" class="flex items-center gap-3 flex-wrap">
                                        @foreach ($post->tags->pluck('name') as $tag)
                                            <li>
                                                <x-pill>
                                                    {{ $tag }}
                                                </x-pill>
                                            </li>
                                        @endforeach
                                    </ul>
                                    <p>{{ $post->excerpt }}</p>
                                </article>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </x-prose>

            <x-slot name="sidebar">
                <x-widget heading="B-E-A-utiful!">
                    <p>Check out this awesome widget.</p>
                </x-widget>
            </x-slot>

        </x-layouts.two-column-right>
    </div>

    @section('footer')
        <x-footers.default />
    @endsection

</x-layouts.base>
