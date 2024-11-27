@extends('website.template.layout')
@section('content')



<div class="contentPage createPage">

<div class="center">
	<h1>New Color Palette</h1>
	<h2>Create a new palette and contribute to Color Hunt’s collection</h2>
</div>

<div class="item">
	<div class="palette">
		<div class="place c3" onclick="openColorPicker(this)"></div>
		<div class="place c2" onclick="openColorPicker(this)"></div>
		<div class="place c1" onclick="openColorPicker(this)"></div>
		<div class="place c0" onclick="openColorPicker(this)"></div>
	</div>

	<div class="colorpicker hide">
    <div class="container">
        <input type="text" id="colorInput" placeholder="Enter color code">
        <input type="text" id="picker">
        <!-- <button class="button">Apply Color</button> -->
    </div>
</div>
</div>

<div class="suggestedTags">
	<span class="label">Suggested:</span>
	<div class="spot spot3"></div>
	<div class="spot spot2"></div>
	<div class="spot spot1"></div>
	<div class="spot spot0"></div>
</div>

<div class="center buttonContainer " style="margin-top:30px">
	<div class="button submit" onclick="submit()">
		<div class="icon" icon="send"></div>Submit Palette
	</div>
</div>

<div class="error center"></div>

</div>


<div class="feed global">
<div class="item hide">
<div class="palette">
    <div class="place c3"></div>
    <div class="place c2"></div>
    <div class="place c1"></div>
    <div class="place c0"></div>
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
<script>

</script>
@endsection