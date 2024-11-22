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


.likesPage {
	padding-right: 10px;
}
.likesPage .meta h1 {
	display: flex;
	justify-content: space-between;
	align-items: center;
	margin-bottom: 10px;
}
.likesPage .meta h1 span {
	font-size: 13px;
	opacity: 0.5;
}
.likesGrid {
	margin: 10px auto;
}
.likesGrid .date {
	display: none;
}
.likesGrid .item .like span {
	display: none;
}
.noLikes {
	display: none;
	margin-top: 60px;
}
.noLikes .icon.big {
	width: 76px;
	height: 76px;
	background-size: 800%;
	margin: 0 auto;
	display: block;
	opacity: 0.5;
	animation-name: likeIcon;
	animation-duration: 2s;
	background-position: -335px 50%;
}
@keyframes likeIcon {
	0% { transform: scale(1); }
	50% { transform: scale(1.1); }
	100% { transform: scale(1); }
}
.noLikes .button {
	margin-top: 20px;
}
@media screen and (max-width: 600px) {
	.likesPage { padding-right: 0}
}
</style>
<div class="contentPage likesPage">

<div class="meta">
	<h1>My collection <span>{{ count($collections) }} palette</span></h1>
	<div class="line"></div>
</div>
@if (count($collections)>0)
    
<div class="feed likesGrid  global">
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
    @foreach ($collections as $index =>  $collection)
 
    <div class="item" data-index="{{ $index }}" data-code="{{ $collection->palette->code }}" style="animation-delay: 0ms;">
        <div class="palette">
            <div class="place c0" style="background-color: #{{ $collection->palette->color_1 }};"><a href="{{ route('website-palette-single',['id' => $collection->palette->id]) }}" aria-label="Palette {{ $collection->palette->code }}"></a><span onclick="copy(this)" data-copy="#FF7F3E">#FF7F3E</span></div>
            <div class="place c1" style="background-color: #{{ $collection->palette->color_2 }};"><a href="{{ route('website-palette-single',['id' => $collection->palette->id]) }}" aria-label="Palette {{ $collection->palette->code }}"></a><span onclick="copy(this)" data-copy="#FFF6E9">#FFF6E9</span></div>
            <div class="place c2" style="background-color: #{{ $collection->palette->color_3 }};"><a href="{{ route('website-palette-single',['id' => $collection->palette->id]) }}" aria-label="Palette {{ $collection->palette->code }}"></a><span onclick="copy(this)" data-copy="#80C4E9">#80C4E9</span></div>
            <div class="place c3" style="background-color: #{{ $collection->palette->color_4 }};"><a href="{{ route('website-palette-single',['id' => $collection->palette->id]) }}" aria-label="Palette {{ $collection->palette->code }}"></a><span onclick="copy(this)" data-copy="#4335A7">#4335A7</span></div>
        </div>
        <div class="flex">
            <div class="actions flex">
                <div class="button like" onclick="like('{{ $collection->palette->code }}')" status="{{ $collection ? 'on' : 'off'}}">
                    <div class="icon" icon="like"></div>
                    
                    <p>Like</p>
                </div>
            </div>
            <span class="date">{{ $collection->palette->created_at->diffForHumans(null, true) }}</span>
        </div>
    </div>     
    @endforeach
   
</div>
@else
<div class="center noLikes" style="display: block;">
	<div class="icon big" icon="like"></div>
	<h1>No palettes in collection</h1>
	<p>You haven't liked anything yet!</p><p>
	<a href="{{ route('website-home') }}" class="button">Find beautiful palettes</a>
</p></div>
@endif


</div>
@endsection