@unless ($breadcrumbs->isEmpty())


    <section class="breadcrumbs-section">
        <div class="container">
            <ol class="breadcrumbs">
                @foreach ($breadcrumbs as $breadcrumb)
                    @if (!is_null($breadcrumb->url) && !$loop->last)
                        <li class="breadcrumb-item"><a href="{{ $breadcrumb->url }}">{{ $breadcrumb->title }} </a></li>
                        <li>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16"
                                fill="none">
                                <path d="M14 8.0013L10.6667 4.66797M14 8.0013L10.6667 11.3346M14 8.0013H2" stroke="#1E1E1E"
                                    stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </li>
                    @else
                        <li class="breadcrumb-item active">{{ $breadcrumb->title }} </li>
                    @endif
                @endforeach
            </ol>

        </div>
    </section>
@endunless
