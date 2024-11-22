<div class="left">
    <a href="{{  route('website-home') }}" class="tab button" tab="new">
        <div class="icon" icon=new></div>New
    </a>
    <a href="#" class="tab button" tab="popular">
        <div class="icon" icon=popular></div>Popular
    </a>
    <div class="timeframe hide">
        <a href="{{ route('website-home',['popular' => 'month']) }}">
            <div class="button small">Month</div>
        </a>
        <a href="{{ route('website-home',['popular' => 'year']) }}">
            <div class="button small">Year</div>
        </a>
        <a href="{{ route('website-home',['popular' => 'all']) }}">
            <div class="button small">All</div>
        </a>
        <!-- <div class="button small" timeframe="365" onclick="changeTimeframe(this)">Year</div>
        <div class="button small" timeframe="4000" onclick="changeTimeframe(this)">All time</div> -->
    </div>
    <a href="{{ route('website-home',['random' => 'yes']) }}" class="tab button" tab="random">
        <div class="icon" icon=random></div>Random
    </a>
    <a href="{{  route('website-pallete-collection') }}" class="tab button" tab="collection">
        <div class="icon" icon=like></div>Collection
    </a>
    <div class="line"></div>

    <div class="tags">
        @foreach (\App\Models\Tag::all() as $tag )
        <a href="{{ route('website-home',[ 'sidebar_filter'=> $tag->name ]) }}"  status class="tab button small tag" tab="{{$tag->name}}">{{ $tag->name }}</a>
        @endforeach
    </div>
</div>