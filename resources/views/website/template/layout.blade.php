<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0, maximum-scale=5.0'>
    <meta name="theme-color" content="#ffffff" />
    <meta name="google-site-verification" content="dvEhKnec-YPDrnTWvPxBpRmeKYfOin0w0OeHanN_eQ0" />
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>PaletteCraft - Search is over here</title>

    <link rel="shortcut icon" href="{{ asset('website-assets/img/colorhunt-favicon.svg') }}">
    <link rel="apple-touch-icon" sizes="192x192" href="{{ asset('website-assets/img/color-hunt-icon-ios.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,100..900&amp;display=swap" rel="stylesheet">
    <link href="{{ asset('website-assets/css/main.css') }}" rel="stylesheet" media="all" />
    <link href="{{ asset('website-assets/plugins/spectrum/spectrum.css') }}" rel='stylesheet' />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>

<body>


    @extends('website.template.header')

    <div class="loader">
        <div class="a"></div>
        <div class="b"></div>
    </div>
    @php
    $filePath = public_path('website-assets/json/colours.json');

    if (!file_exists($filePath)) {
    return 'File not found';
    }

    $colorData = json_decode(file_get_contents($filePath), true);

    if (json_last_error() !== JSON_ERROR_NONE) {
    return 'Invalid JSON file';
    }
    $colors = $colorData['colors'];
    @endphp
    <div class=" tagBank hide">
        @foreach($colors as $color)
        @php
        $name = strtolower(str_replace(' ', '-', $color['name'])); // Convert name to lowercase and replace spaces with dashes
        @endphp
        <div class="button tag" onclick="toggleTag(this)" tag="{{ $name }}" alt="{{ $color['name'] }}" type="color">
            {{ $color['name'] }}<span class="x">✕</span>
        </div>
        @endforeach
        <!-- <div class="button tag" onclick="toggleTag(this)" tag="{{ $name }}" alt="teal cold sky celeste azul cyan sea tech aqua business indigo calm royal primary professional biru denim bleu" type="color">{{ $name }}<span class="x">✕</span></div> -->
        @foreach (\App\Models\Tag::all() as $tag )
        <div class="button tag" onclick="toggleTag(this)" tag="{{ $tag->name}}" alt="vintage minimalist minimal light soft aesthetic baby calm cute history beauty muted boho">{{ $tag->name}}<span class="x">✕</span></div>
        @endforeach
    </div>

    <div class="wrap main flex">
        @if (!Route::is('website-palette-create'))
        @include('website.template.sidebar')
        @endif

        <div class="page">
            @yield('content')
            <!-- ColorHunt_S2S_Mobile_Leaderboard_ROS_Pos1 -->
            <style>
                div[id^="bsa-zone_1723563263858-8_123456"] {
                    display: none;
                }

                @media only screen and (max-width: 640px) {
                    div[id^="bsa-zone_1723563263858-8_123456"] {
                        display: block;
                        margin: 0 auto;
                        padding: 20px 0;
                    }
                }
            </style>
            <div id="bsa-zone_1723563263858-8_123456"></div>

            <div class="feed global">
                <div class="item hide">
                    <div class="palette">
                        <div class="place c3"><a></a><span onclick="copy(this)"></span></div>
                        <div class="place c2"><a></a><span onclick="copy(this)"></span></div>
                        <div class="place c1"><a></a><span onclick="copy(this)"></span></div>
                        <div class="place c0"><a></a><span onclick="copy(this)"></span></div>
                    </div>
                    <div class="flex">
                        <div class="actions flex">
                            <div class="button like">
                                <div class="icon" icon="like"></div>
                                <span>Like</span>
                            </div>
                        </div>
                        <span class="date">Today</span>
                    </div>
                </div>
            </div>
        </div>
        @if (!Route::is('website-palette-create'))
        @include('website.template.right-sidebar')
        @endif
    </div>
    <script>
        const paletteStoreUrl = "{{ route('website-palette-create') }}"; 
        const paletteLikeUrl = "{{ route('website-palette-like') }}"; 
        const collectionUrl = "{{ route('website-collection-api') }}"; 
        const filterUrl = "{{ route('website-home')  }}";
        const currentRouteName = "{{ Route::currentRouteName() }}";


    </script>
    <script src="{{ asset('/website-assets/js/main.js') }}"></script>
    <script src="{{ asset('/website-assets/js/ajax.js') }}"></script>
    <script src="{{ asset('website-assets/plugins/spectrum/spectrum.js') }}"></script>

</body>

</html>