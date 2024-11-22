<div class="header">
	<div class="wrap flex">
		<div class="left">
			<a href="{{ route('website-home') }}" class="logo flex">
				<img class="tongue" src="{{ asset('website-assets/img/color-hunt-logo-tongue.svg') }}" alt="color-hunt-logo" />
				<img src="{{ asset('website-assets/img/color-hunt-logo-face.svg') }}" alt="color-hunt-logo" />
				<span class="mobileHide">Palette Craft</span>
			</a>
		</div>
		<div class="middle filterContainer">
			<div class="inputContainer flex">
				<input placeholder="Search palettes" onkeyup="showTags()" />
				<div class="searchIcon icon" icon="search"></div>
				<a class="clear" href="{{ route('website-home') }}">✕</a>
			</div>
			<div class="filterWindow dropdown hidden card">
				<div class="color section">
					<div class="title">Colors</div>
				</div>
				<div class="line"></div>
				<div class="collection section">
					<div class="title">Collections</div>
				</div>
				<div class="line"></div>
				<div class="related section hide">
					<div class="title">Related</div>
				</div>
			</div>
		</div>
		<div class="right flex">
			
			<div class="kebab button iconButton"><span></span><span></span><span></span></div>
			<div class="littleMenu dropdown card hidden">
				<a class="tab button small" tab="palettes" href="{{ route('website-home') }}">Palettes</a>
				<a class="tab button small" tab="create" href="{{ route('website-palette-create') }}">Create</a>
				<a class="tab button small" tab="collection" href="collection.html">Collection</a>
			
			</div>
		</div>
	</div>
	<div class="line bottom"></div>
</div>