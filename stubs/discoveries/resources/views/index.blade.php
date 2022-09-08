<x-layouts.base :meta="$meta">

    @section('header')
        <x-headers.default />
    @endsection

    <div class="py-8 lg:py-12">
        <x-layouts.two-column-right>
            <x-prose>
                @if ($topics)
                    <h1>Discovery Center</h1>
                    <ul role="list">
                        @foreach ($topics as $topic)
                            <li>
                                <article>
                                    <h2><a href="{{ route('discovery-topics.show', $topic) }}">{{ $topic->title }}</a></h2>
                                    <p>{{ $topic->excerpt }}</p>
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
