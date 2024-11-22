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
 @if (!Request::get('tags'))
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
    @foreach ($palettes as $index => $palette)
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

@endsection