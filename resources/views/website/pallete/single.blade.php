@extends('website.template.layout')

@section('content')
<style>
    .c0 {
        padding-bottom: 100%;
        animation-duration: 1s;
        border-radius: 0;
        border-radius: 10px 10px 0 0;
    }

    .c1 {
        padding-bottom: 85%;
        animation-duration: 0.6s;
    }

    .c2 {
        padding-bottom: 67%;
        animation-duration: 0.2s;
    }

    .c3 {
        padding-bottom: 35%;
        animation-duration: 0s;
        border-radius: 20px 20px 0 0;
    }
</style>
<div id="palette-container" class="single">
    <div class="singleItem">
        <div class="item" data-code="{{ $palette->code }}" >
            <div class="palette">
                <!-- Loop through the colors and display them -->
                @foreach($colors as $index => $color)
                <!-- {{ dump($color)}} -->
                <div class="place c{{ $index }}" style="background-color: #{{ $color }}">
                    <span>#{{ $color }}</span>
                </div>
                @endforeach
            </div>
            @php
            $lisLiked = \App\Models\Like::userLiked($palette->code,$palette->user_id);
            @endphp
            <div class="actions flex">
                <!-- Action buttons (e.g., like, download, etc.) -->
                <div class="button like" onclick="like('{{ $palette->code  }}')" status="{{ $lisLiked ? 'on' : 'off'}}">
                    <div class="icon" icon="like"></div>
                    <p>Like</p>
                </div>
                <div class="button like" onclick='download("{{ $palette->id }}")'>
                    <div class="icon" icon="download"></div>Image
                </div>
                <!-- <div class="button" onclick="download('{{ $palette->id }}')">Download</div> -->
                <div class="button link" onclick="copy(this, true)">
                    <div class="icon" icon="link"></div>Link
                </div>
            </div>
        </div>
    </div>

    <div class="table">
        <div class="balls row">
            <!-- Display color balls dynamically -->
            @foreach(array_reverse($colors) as $color)
            <div class="ball" style="background-color: #{{ $color }}"></div>
            @endforeach
        </div>
        <div class="line"></div>
        <div class="hex row">
            <!-- Display HEX codes -->
            @foreach(array_reverse($colors) as $color)
            <div style="text-align: center;">
                <div class="button" onclick="copyText(this)">#{{ strtoupper($color) }}</div>
            </div>
            @endforeach
        </div>
        <div class="line"></div>
        <div class="rgb row">
            <!-- Display RGB codes -->
            @foreach(array_reverse($colors) as $color)
            <div style="text-align: center;">
                <div class="button rgb-value" data-hex="{{ $color }}" onclick="copyText(this)">
                    <!-- RGB value will be inserted by JavaScript -->
                </div>
            </div>
            @endforeach
        </div>

    </div>
    <div class="tags">
        @foreach ($tags as $tag)
        <a class="button small tag" href="{{ route('website-home',['sidebar_filter' => $tag])  }}" tag="{{ $tag }}" type="{{ $tag }}">{{ $tag }}</a>
        @endforeach
    </div>
    <div class="line"></div>
</div>
@if (count($related_palettes ) > 0 )
<div class="title related" style="text-align: center;">More palettes with <a href="/palettes/yellow" class="tag button" type="color" tag="yellow">yellow</a></div>
<div class="feed global" id="dynamic">

    <div class="item hide">
        <!-- <div class="palette">   
            <div class="place c3"><a></a><span onclick="copy(this)"></span></div>
            <div class="place c2"><a></a><span onclick="copy(this)"></span></div>
            <div class="place c1"><a></a><span onclick="copy(this)"></span></div>
            <div class="place c0"><a></a><span onclick="copy(this)"></span></div>
        </div> -->
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
    @foreach ($related_palettes as $index => $palette)
    <!-- <div class="item" data-index="{{ $index }}" data-code="{{ $palette->code }}" style="animation-delay: 0ms;">
        <div class="palette">
            <div class="place c0" style="background-color: #{{ $palette->color_1 }};"><a href="" aria-label="Palette {{ $palette->code }}"></a><span onclick="copy(this)" data-copy="#FF7F3E">#FF7F3E</span></div>
            <div class="place c1" style="background-color: #{{ $palette->color_2 }};"><a href="" aria-label="Palette {{ $palette->code }}"></a><span onclick="copy(this)" data-copy="#FFF6E9">#FFF6E9</span></div>
            <div class="place c2" style="background-color: #{{ $palette->color_3 }};"><a href="" aria-label="Palette {{ $palette->code }}"></a><span onclick="copy(this)" data-copy="#80C4E9">#80C4E9</span></div>
            <div class="place c3" style="background-color: #{{ $palette->color_4 }};"><a href="" aria-label="Palette {{ $palette->code }}"></a><span onclick="copy(this)" data-copy="#4335A7">#4335A7</span></div>
        </div>
        <div class="flex">
            @php
            $lisLiked = \App\Models\Like::userLiked($palette->code,$palette->user_id)
            @endphp
            <div class="actions flex">
                <div class="button like" onclick="like('{{ $palette->code }}')" status="{{ $lisLiked ? 'on' : 'off'}}">
                    <div class="icon" icon="like"></div>

                    <span>{{ \App\Models\Palette::likesCount($palette->code)}}</span>
                </div>
            </div>
            <span class="date">{{ $palette->created_at->diffForHumans(null, true) }}</span>
        </div>
    </div> -->
    <x-palette :palette="$palette" />
    @endforeach

</div>
@endif
<script>
    // Function to convert HEX to RGB
    function hexToRGB(hex) {
        hex = hex.replace('#', ''); // Remove the hash (#) if present
        var r = parseInt(hex.slice(0, 2), 16); // Extract red value
        var g = parseInt(hex.slice(2, 4), 16); // Extract green value
        var b = parseInt(hex.slice(4, 6), 16); // Extract blue value
        return `rgb(${r}, ${g}, ${b})`; // Return RGB string
    }

    // Function to copy the text to the clipboard
    function copyText(element) {
        var text = element.textContent || element.innerText;

        navigator.clipboard.writeText(text).then(function() {
            // Create a new div to show "Copied" message
            var copiedMessage = document.createElement('div');
            copiedMessage.textContent = 'Copied';
            copiedMessage.classList.add('copied'); // Add a class for styling
            element.appendChild(copiedMessage); // Append the message to the element

            // Optionally, remove the "Copied" message after a few seconds
            setTimeout(function() {
                copiedMessage.remove();
            }, 2000); // Remove after 2 seconds
        }, function() {
            alert('Failed to copy text.');
        });
    }


    // Once the DOM is fully loaded, populate RGB values
    document.addEventListener("DOMContentLoaded", function() {
        var rgbElements = document.querySelectorAll('.rgb-value'); // Find all elements with class 'rgb-value'

        rgbElements.forEach(function(element) {
            var hex = element.getAttribute('data-hex'); // Get the hex color code from the data-hex attribute
            var rgb = hexToRGB(hex); // Convert the hex to RGB
            element.textContent = rgb; // Set the text content to the RGB value
        });
    });

    function download(code) {
        a = $("<a>")
            .attr("href", "/image/" + code)
            .attr("download", "Palett-craft-" + code + ".png")
            .appendTo("body");
        a[0].click();
        a.remove();
        ga('send', 'event', 'interaction', 'save_image');
    }
</script>

@endsection