function showTags() {
	$('.filterWindow .section').hide();
	$('.filterWindow .button').remove();
	query = $('.filterContainer input').val().toLowerCase();
	$('.tagBank .button').each(function () {
		tag = $(this).attr('tag');
		type = $(this).attr('type');
		if (!type) { type = "collection"; }
		alt = $(this).attr('alt').split(" ");
		if (tag.substring(0, query.length) == query) {
			$('.filterWindow .' + type).append($(this).clone()).show();
		}
		if (alt.includes(query)) {
			$('.filterWindow .related').append($(this).clone()).show();
		}
	});
}

function toggleTag(tag) {
	isCreateRoute =  currentRouteName == 'website-palette-create' ? true : false; 
    filterRedirect = isCreateRoute ? false :  true;
    // Toggle tag status and manage the input container
    if ($(tag).attr('status') != 'on') {
        applyTag($(tag).attr('tag')); // Add the tag
    } else {
        $('[tag=' + $(tag).attr('tag') + ']').attr('status', 'off');
        $('.inputContainer [tag=' + $(tag).attr('tag') + ']').remove(); // Remove the tag
    }

    // Construct the tag string
    tagString = "";
    $('.inputContainer .tag[status=on]').each(function () {
        tagString += "-" + $(this).attr('tag');
    });
    tagString = tagString.substring(1); // Remove the leading "-"
// alert(filterRedirect);
    // Update the URL without refreshing the page
    if (filterRedirect) {
        $('.loader').show()
	 // Show loader for visual feedback
        const newUrl = `${filterUrl}/?${tagString}`;
			// alert(newUrl);
        history.pushState({ path: newUrl }, '', newUrl); // Update the URL dynamically

        // Optional: Trigger an action or update the UI
        // For example: dynamically fetch and apply filtered data via AJAX
        fetchFilteredData(tagString);
    }
}

function fetchFilteredData(tagString) {
    $.get(filterUrl, { tags: tagString })
        .done(function (response) {
			$('.loader').hide();
			$('.filterWindow').addClass('hidden');
            const container = $('#dynamic'); // Target the container
            container.html(''); // Clear existing palettes
            if (response.html) {
                container.append(response.html); // Append the rendered components
            } else {
                container.append('<p>No palettes found</p>'); // Handle empty response
            }
        })
        .fail(function () {
            alert('Failed to fetch data. Please try again.');
        });
}



function applyTag(tag) {
	$('[tag=' + tag + ']').attr('status', 'on');
	$('.tagBank [tag=' + tag + ']').clone().insertBefore($('.inputContainer input'));
	$('.filterContainer input').addClass('filled');
	$('.filterContainer input').val('');
	$('.filterContainer input').attr('placeholder', 'Add tag');
}

function changeTimeframe(obj) {
	$('.timeframe .button').attr('status', 'off')
	$(obj).attr('status', 'on')
	$('.loader').show();
	$('.feed .item').not('.hide').fadeOut(100);
	step = 0;
	oktoload = "yes";
	getFeed();
}

function copy(obj, url) {
	input = document.createElement('input');
	if (url) {
		input.setAttribute('value', document.URL);
	} else {
		input.setAttribute('value', $(obj).attr('data-copy').replace('#', ''));
	}
	document.body.appendChild(input);
	input.select();
	result = document.execCommand('copy');
	document.body.removeChild(input)
	setTimeout(function () { $('.copied').remove(); }, 1000);
	$(obj).append("<div class='copied'>Copied</div>");
}

function formatThousands(n, dp) {
	var s = '' + (Math.floor(n)), d = n % 1, i = s.length, r = '';
	while ((i -= 3) > 0) { r = ',' + s.substr(i, 3) + r; }
	return s.substr(0, i + 3) + r + (d ? '.' + Math.round(d * Math.pow(10, dp || 2)) : '');
}

// function like(code) {
// 	if ( myCollection.indexOf(code) == -1 ) {
// 		myCollection.push(code);
// 		$('.item[data-code=' + code +'] .like').attr('status','on');
// 		$.post(paletteLikeUrl,  { code: code, status:'on',  _token: $('meta[name="csrf-token"]').attr('content') }, function(data){  });
// 		curlikes = $('.item[data-code=' + code + ']:last').find('.like span').text().replace(',','');
// 		curlikes++;
// 		curlikes = formatThousands(curlikes);
// 		$('.item[data-code=' + code + ']').find('.like span').text(curlikes);
// 		placeItem('likesList', code);
// 		$('.right .likes').show();
// 		if ( $('#like_tip').length > 0 ) {
// 			$('#like_tip').remove();
// 		}
// 		$('.likes').append("<div class='tip saved'>Saved!</div>");
// 		$('.tongue').addClass('animate');
// 		setTimeout(function() { $('.tongue').removeClass('animate'); }, 800)
// 	} else {
// 		myCollection.splice(myCollection.indexOf(code), 1);
// 		$('.item[data-code=' + code +'] .like').attr('status','off');
// 		$('.likes .palette[data-code=' + code +']').remove();
// 		var curlikes = $('.item[data-code=' + code + ']:last').find('.like span').text().replace(',','');
// 		curlikes--;
// 		curlikes = formatThousands(curlikes);
// 		$('.item[data-code=' + code + ']').find('.like span').text(curlikes);
// 	}
// localStorage.setItem('myCollection',myCollection);
// }

function like(code) {
	const item = $('.item[data-code=' + code + ']');
	const likeButton = item.find('.like');
	const isLiked = likeButton.attr('status');
	const csrfToken = $('meta[name="csrf-token"]').attr('content');
	const status = isLiked == 'on' ? 'off' : 'on';
	$.post(paletteLikeUrl, { code, status, _token: csrfToken }, function (response) {
		if (response.status === 'success') {
			// Update `myCollection` and button status
			if (status === 'on') {
				myCollection.push(code);
				likeButton.attr('status', 'on');
				$('.tongue').addClass('animate');
				// getCollections();
			} else {
				myCollection.splice(myCollection.indexOf(code), 1);
				likeButton.attr('status', 'off');
				$('.tongue').removeClass('animate');
				// getCollections();
			}

			// Update the likes count in the UI
			const totalLikes = response.total_likes;
			item.find('.like span').text(formatThousands(totalLikes));
			
			// Optionally display a success message
			$('.likes').append("<div class='tip saved'>Saved!</div>");
			getCollections()
			setTimeout(() => $('.tip.saved').remove(), 2000);
		} else {
			// Handle error from server
			alert(response.message || 'An error occurred. Please try again.');
		}
	}).fail(() => {
		alert('Failed to update like. Please check your connection and try again.');
	});
}

let addedPaletteCodes = []; // Track already added palette codes
// let collectionsFetched = false;  // Flag to track if collections have already been fetched

function getCollections() {
    // if (collectionsFetched) return;  // Prevent fetching again if already fetched
    // collectionsFetched = true;
	
    $.get(collectionUrl)
        .done(function (data) {
            $('.loader').hide();

            if (data.status === 'success') {
                const collections = data.collections;

                const uniqueCollections = collections.filter((value, index, self) => 
                    index === self.findIndex((t) => (
                        t.palette.code === value.palette.code // Ensure unique palette codes
                    ))
                );

                uniqueCollections.forEach(collection => {
                    const palette = collection.palette;

                    if (palette && !addedPaletteCodes.includes(palette.code)) {
                        addedPaletteCodes.push(palette.code);
                        placeItem('likesList', palette.code, palette.likes || 0, palette.created_at, palette);
                    } else {
                        console.warn('Duplicate or no palette found for collection:', collection);
                    }
                });
            } else {
                $('.error').html(data.message || 'Failed to fetch collections.');
            }
        })
        .fail(function (xhr, status, error) {
            $('.loader').hide();
            $('.error').html(`An error occurred: ${status}`);
        });
}


function placeItem(place, code, likes, date, palette) {
    let item = $('.main .item.hide').clone().removeClass('hide')
        .attr('data-index', itemIndex)
        .attr('data-code', code)
        .css('animation-delay', itemIndex * 30 + "ms");
		$('.right .likes').show();
    paintPalette(item, palette);

    item.find('.palette a').attr('href', '/palette/' + code);
    item.find('.palette a').attr('aria-label', 'Palette ' + code);
    item.find('.like').attr('onclick', 'like("' + code + '")');

    if (myCollection.includes(code)) {
		// alert('asda');
        item.find('.like').attr('status', 'on');
    }

    item.find('.like span').text(likes);
    item.find('.date').text(new Date(date).toLocaleString());

    if (place === "likesList") {
        item = item.find('.palette').attr('data-code', code);
        item.find('span').remove();
        item.append('<div class="x" onclick="like(\'' + code + '\')">✕</div>');
        $('.likesList').prepend(item);
    } else {
        item.appendTo('.' + place);
    }

    itemIndex++;
}


function paintPalette(obj, palette) {
    console.log('Palette:', palette); // Log the entire palette object
    for (let i = 1; i <= 4; i++) {
        const colorKey = `color_${i}`;
        const hex = palette[colorKey]; // Access individual color keys like color_1, color_2, etc.
        console.log(`Color ${colorKey}:`, hex); // Log each color to check if it's correct

        if (hex) {
            obj.find('.c' + (i - 1))
                .css('background-color', '#' + hex)
                .find('span')
                .text('#' + hex.toUpperCase())
                .attr('data-copy', '#' + hex.toUpperCase());
        } else {
            console.warn(`Color ${colorKey} not found in palette`, palette);
        }
    }
}

function optimize() {
	setTimeout(function () {
		loadOptimize = false

		if ($('#sponsor-banner').html().length > 300) {
			$('#bsa-zone_1682448560669-3_123456').remove()
		} else {
			loadOptimize = true
		}

		if (single != '' || page == 'collection') {
			if ($('#badge-js').html().length > 300) {
				$('#bsa-zone_1682448365049-4_123456').remove()
			} else {
				loadOptimize = true
			}
		}

		if (loadOptimize == true) {
			console.log('Loading Optimize')
			var bsa_optimize = document.createElement('script');
			bsa_optimize.type = 'text/javascript';
			bsa_optimize.async = true;
			bsa_optimize.src = 'https://cdn4.buysellads.net/pub/colorhunt.js?' + (new Date() - new Date() % 600000);
			(document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(bsa_optimize);
		}

	}, 1500)


}


function getLikes() {
	myCollection = [];
	if (localStorage.getItem("myCollection") != null && localStorage.getItem("myCollection") != "") {
		myCollection = localStorage.getItem("myCollection").split(",");
	}
	itemIndex = 0;
	myCollection.forEach(function (code) {
		placeItem('likesList', code);
		$('.right .likes').show();
	});
}

// function placeItem(place, code, likes, date) {
// 	item = $('.main .item.hide').clone().removeClass('hide')
// 		.attr('data-index', itemIndex).attr('data-code', code)
// 		.css('animation-delay', itemIndex * 30 + "ms");
// 	paintPalette(item, code);
// 	item.find('.palette a').attr('href', '/palette/' + code);
// 	item.find('.palette a').attr('aria-label', 'Palette ' + code)
// 	item.find('.like').attr('onclick', 'like("' + code + '")');
// 	if (myCollection.indexOf(code) != -1) {
// 		item.find('.like').attr('status', 'on');
// 	}
// 	item.find('.like span').text(likes);
// 	item.find('.date').text(date);
// 	if (place == "likesList") {
// 		item = item.find('.palette').attr('data-code', code);
// 		item.find('span').remove();
// 		item.append('<div class="x" onclick="like(\'' + code + '\')\">✕</div>');
// 		$('.likesList').prepend(item);
// 	} else {
// 		item.appendTo('.' + place);
// 	}
// 	itemIndex++;
// }

// function paintPalette(obj, code) {
	
// 	i = 0;
// 	while (i < 4) {
// 		hex = code.substring(i * 6, i * 6 + 6);
// 		obj.find('.c' + i).css('background-color', '#' + hex).find('span').text('#' + hex.toUpperCase()).attr('data-copy', '#' + hex.toUpperCase());
// 		i++;
// 	}
// }



function placeBannerInFeed() {
	bannerInFeed = true
	$('.feed').append('<div class="item banner-in-feed"><img src="img/colorhunt-bookmark.png%3F3" /><div class="title">Bookmark Color Hunt</div><p>Press Ctrl/Cmd + D to add Color Hunt to your bookmarks bar!</p></div>')
}

document.addEventListener('keydown', function (event) {
	if ((event.ctrlKey || event.metaKey) && event.key === 'd') {
		gtag('event', 'added_to_bookmark', { 'event_category': 'user_engagement' });
	}
});

$(document).ready(function () {
	page = "";
	sort = "";
	tags = "";
	tagString = "";
	single = "";
	step = 0;
	oktoload = "yes";
	filterRedirect = true;
	carbonRendered = false;
	bannerInFeed = false

	if (page == "") { page = 'palettes'; }
	if (page == "palettes" && sort == "" && tags == "") { sort = "new"; }
	if (sort != "" && single == "") { $('.tab[tab=' + sort + ']').attr('status', 'on'); }
	if (sort == 'popular') { $('.timeframe').css('display', 'flex'); }

	if (page == 'palettes') {
		if (single != '') { getSingle(); }
		// else { getFeed(); }
		getLikes();
	} else {
		if (page != 'collection') { $('.main .left').hide(); $('.main .right').hide(); }
		if (page == 'collection') { $('.main .right .meta').hide(); carbonAd() }
	}

	if (tagString != "") {
		tagString = tagString.split('-');
		tagString.forEach(function (tag) { applyTag(tag); })
		$('.tab[tab=' + tags + ']').attr('status', 'on');
	}

	$('.tab[tab=' + page + ']').attr('status', 'on');

	window.addEventListener('scroll', function scrolling() {
		if (page == "palettes" && oktoload == "yes" && $(document).scrollTop() + $(window).height() >= $("body").height() - 300) {
			step++;
			// getFeed();
			oktoload = "no";
			setTimeout(function () { oktoload = "yes" }, 500);
		}
	});

	$(window).click(function (event) {
		if (!$(event.target).closest(".filterContainer").length) {
			$('.filterWindow').addClass('hidden');
		}
		if (!$(event.target).closest(".kebab").length) {
			$('.littleMenu').addClass('hidden');
			$('.kebab').attr('status', 'off');
		}
	});

	$('.tab').click(function () {
		$('.tab').attr('status', 'off')
		$(this).attr('status', 'on')
		$('.timeframe').hide();
		if ($(this).attr('tab') == 'popular') {
			$('.timeframe').css('display', 'flex');
		}
	});

	$('a').click(function () {
		$('.loader').show();
		setTimeout(function () {
			$('.loader').hide();
		}, 2000)
	});

	$('.filterContainer input').keydown(function (e) {
		if (e.keyCode == 13 && query != "") {
			gtag('event', query, { 'event_category': 'search_query' });
			if ($('.filterWindow .button.tag').length > 0) {
				$('.filterWindow .button.tag').eq(0).click()
			} else {
				tagString = $('.filterContainer input').val().replace(' and ', '-').replace(' ', '-')
				tagArray = tagString.split('-')
				for (word in tagArray) {
					if ($('.button.tag[tag=' + tagArray[word] + ']').length == 0) {
						delete tagArray[word]
					}
				}
				tagString = tagArray.filter(value => JSON.stringify(value) !== '{}').join('-')
				window.location = '/palettes/' + tagString
			}
		}
		if (e.keyCode == 8 && query == "") {
			$('.inputContainer .tag').last().click();
		}
	});

	$('.filterContainer input').focus(function (e) {
		showTags();
		$('.filterWindow').removeClass('hidden');
	});

	$('.kebab').click(function (e) {
		if ($('.littleMenu.hidden').length == 1) {
			$('.kebab').attr('status', 'on');
			$('.littleMenu').removeClass('hidden');
		} else {
			$('.kebab').attr('status', 'off');
			$('.littleMenu').addClass('hidden');
		}
	});

	if ('serviceWorker' in navigator) {
		navigator.serviceWorker.register('/service-worker.js', {
			scope: '.' // <--- THIS BIT IS REQUIRED
		}).then(function (registration) {
			// Registration was successful
		}, function (err) {
			// registration failed :(
		});
	}
});

$(document).ready(function () {
	filterRedirect = false;
	getCollections();
	// Move the filter container after items in the createPage
	$('.createPage .item').after($('.filterContainer'));

	// Set placeholder for the input field
	$('.inputContainer input').attr('placeholder', 'Add tags');
});

// Wrap the code in an IIFE to avoid polluting the global scope
(function () {
	let target;
	let spot;

	// Attach the function to the global window object
	window.openColorPicker = function (place) {
		$('.colorpicker').removeClass('hide');
		target = place;
		spot = $(place).index();

		let color = rgbToHex($(place).css('background-color').replace('#', ''));

		if ($("#picker").hasClass("sp-initialized")) {
			$("#picker").spectrum("destroy");
		}

		$("#picker").spectrum({
			preferredFormat: "hex",
			flat: true,
			showInput: true,
			color: color,
			change: function (selectedColor) {
				$('#colorInput').val(selectedColor.toHexString()); // Update input value
				setColor(); // Apply the selected color
				
			}
		});

		$('.colorpicker .container').toggleClass('open');
	};

	function rgbToHex(color) {
		if (!color.startsWith("rgb")) {
			return color.replace("#", "");
		}
		let a = color.split("(")[1].split(")")[0];
		a = a.split(",");
		let b = a.map(function (x) {
			x = parseInt(x).toString(16);
			return (x.length === 1) ? "0" + x : x;
		});
		return b.join("");
	}
	function setColor() {
		let color = $('#colorInput').val(); // Get the value from the input
		if (color) {
			// Ensure the color starts with a "#" before applying
			color = color.startsWith('#') ? color : `#${color}`;
			$(target).css('background', color); // Apply color to the selected target
			// color = '';
			// console.log(`Color set to: ${color}`); // Log for debugging
		} else {
			console.error('No color selected!');
		}
	}
	// Bind dynamically
	$(document).on('click', '.place', function () {
		openColorPicker(this);
	});
	$(document).on('click', function (event) {
		if (!$(event.target).closest('.colorpicker').length &&
			!$(event.target).closest('.place').length) {
			// Close the color picker
			$('.colorpicker').addClass('hide');
		}
	});


})();
