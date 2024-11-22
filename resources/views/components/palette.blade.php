<div>

    <div class="item" data-code="{{ $palette['code'] }}" style="animation-delay: 0ms;">
        <div class="palette">
            <div class="place c0" style="background-color: #{{ $palette['color_1'] }};">
                <a href="{{ route('website-palette-single',['id' => $palette->id]) }}" aria-label="Palette {{ $palette['code'] }}"></a>
                <span onclick="copy(this)" data-copy="#{{ $palette['color_1'] }}">#{{ $palette['color_1'] }}</span>
            </div>
            <div class="place c1" style="background-color: #{{ $palette['color_2'] }};">
                <a href="{{ route('website-palette-single',['id' => $palette->id]) }}" aria-label="Palette {{ $palette['code'] }}"></a>
                <span onclick="copy(this)" data-copy="#{{ $palette['color_2'] }}">#{{ $palette['color_2'] }}</span>
            </div>
            <div class="place c2" style="background-color: #{{ $palette['color_3'] }};">
                <a href="{{ route('website-palette-single',['id' => $palette->id]) }}" aria-label="Palette {{ $palette['code'] }}"></a>
                <span onclick="copy(this)" data-copy="#{{ $palette['color_3'] }}">#{{ $palette['color_3'] }}</span>
            </div>
            <div class="place c3" style="background-color: #{{ $palette['color_4'] }};">
                <a href="{{ route('website-palette-single',['id' => $palette->id]) }}" aria-label="Palette {{ $palette['code'] }}"></a>
                <span onclick="copy(this)" data-copy="#{{ $palette['color_4'] }}">#{{ $palette['color_4'] }}</span>
            </div>
        </div>
        <div class="flex">
            @php
            $lisLiked = \App\Models\Like::userLiked($palette->code,$palette->user_id)
            @endphp
            <div class="actions flex">
                <div class="button like" onclick="like('{{ $palette['code'] }}')" status="{{ $lisLiked ? 'on' : 'off'}}">
                    <div class="icon" icon="like"></div>
                    <span>{{ \App\Models\Palette::likesCount($palette->code) }}</span>
                </div>
            </div>
            <span class="date">{{ \Carbon\Carbon::parse($palette['created_at'])->diffForHumans() }}</span>
        </div>
    </div>

</div>