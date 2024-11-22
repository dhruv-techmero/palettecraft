@extends('website.template.layout')

@section('content')
<style>
    .c0 {
  padding-bottom:100%;
  animation-duration: 1s;
  border-radius: 0;
  border-radius: 10px 10px 0 0;
}
.c1 {
  padding-bottom:85%;
  animation-duration: 0.6s;
}
.c2 {
  padding-bottom:67%;
  animation-duration: 0.2s;
}
.c3 {
  padding-bottom:35%;
  animation-duration: 0s;
  border-radius: 20px 20px 0 0;
}
</style>
<div id="palette-container" class="single">
    <div class="singleItem">
        <div class="item">
            <div class="palette">
                <!-- Loop through the colors and display them -->
                @foreach($colors as $index => $color)
                <!-- {{ dump($color)}} -->
                <div class="place c{{ $index }}" style="background-color: #{{ $color }}">
                    <span>#{{ $color }}</span>
                </div>
                @endforeach
            </div>
            <div class="actions">
                <!-- Action buttons (e.g., like, download, etc.) -->
                <div class="button like">
                    <div class="icon" icon="like"></div><span>Like</span>
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
        <a class="button small tag" href="/palettes/purple" tag="{{ $tag }}" type="{{ $tag }}">{{ $tag }}</a>
        @endforeach
    </div>
    <div class="line"></div>
</div>

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