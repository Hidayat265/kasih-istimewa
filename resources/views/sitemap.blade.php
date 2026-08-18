<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">

    {{-- Homepage --}}
    <url>
        <loc>{{ url('/') }}</loc>
        <changefreq>weekly</changefreq>
        <priority>1.0</priority>
    </url>

    {{-- Public Events --}}
    @foreach ($events as $event)
        <url>
            <loc>{{ route('events.public.show', $event->event_id) }}</loc>

            @if ($event->updated_at)
                <lastmod>{{ $event->updated_at->toAtomString() }}</lastmod>
            @endif

            <changefreq>weekly</changefreq>
            <priority>0.8</priority>
        </url>
    @endforeach

</urlset>