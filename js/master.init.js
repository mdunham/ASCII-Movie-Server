/*
 * Initialize all JavaScript pieces.
 *
 * @author Matthew Dunham <matt@matthewdunham.com>
 */

var checkTimer,
baseUrl,
socket,
updater = null,
movieData = [],
movieFrame = 0;

var mapMake = function() {
	if (movieData.length>0) {
		var text = movieData.shift();
		$('#movie pre').text(text);
		document.title = movieData.length;
	}
}

var playMovie = function() {
	if (updater==null&&movieData.length>10) {
		updater = setInterval(mapMake, 130);
	}
	$.ajax({
		url: 'server/startDaemon.php',
		dataType: 'json',
		success: function(data){
			for (i=0;i<data.length;i++) {
				movieData.push(data[i]);
			}
			if (socket) setTimeout('playMovie()',100);
		}
	})
}


if (window.jQuery) {
	jQuery(function($){

		$(window).load(function() {
			checkTimer = setInterval( runCheck, 100);
		});

		if ($.cookie('noteeth')=='true') {
			$('#top_jaw, #bottom_jaw').hide();
			$('body').addClass('noteeth')
		}

		$('#movie').click(function(){
			
			if (!socket) {
				socket = true;
					$(this).find('pre').text('Loading');
			playMovie();
			}


		});

		$('#hideTeeth').click(function(e) {
			$.cookie('noteeth', 'true');
			$('body').addClass('noteeth')
			$('#top_jaw, #bottom_jaw').hide();
		});

		baseUrl = window.location.href;
		if (baseUrl.indexOf('#') > 0) {
			baseUrl = baseUrl.split('#');
			baseUrl = baseUrl[0];
		}

		var unlock = function() {
			$('#unlock').fadeOut('slow');
			$('#contact_form').attr('action', 'contact.php');
		};

		$('#main div.nav a, a.back_to_top').click(function(e){
			e.preventDefault();
			scrollToPage($(this).attr('href'));
		});

		var nativePlaceholderSupport = (function(){
			var i = document.createElement('input');
			return ('placeholder' in i);
		})();

		if (nativePlaceholderSupport){
			$('label').hide();
		}

		var fixSlider = function() {
			$('a.ui-slider-handle').attr('id', 'unlock-handle');
		};

		setTimeout(fixSlider, 1000);

		$("#unlock-slider").slider({
			handle: "#unlock-handle",
			animate:true,
			slide: function(e,ui)
			{
				
				$("#slide-to-unlock").css("opacity", 1-(parseInt($("#unlock-handle").css("left"))/120));
			},
			stop: function(e,ui)
			{
				if($("#unlock-handle").position().left > 195) {
					unlock();
				} else {
					
					$("#unlock-handle").animate({
						left: 0
					}, 200 );
					$("#unlock-handle").parent().find('a').animate({
						left: 0
					}, 200 );
					$("#slide-to-unlock").animate({
						opacity: 1
					}, 200 );
				}
			}
		});
	});
} else {
	alert('What the f\u2730ck, no jQuery?');
}

// Was here for the browser size restriction.
var runCheck = function() {
	var width = $(window).width(),
	height = $(window).height();

	if (width > 705 && height > 550) {
		clearInterval(checkTimer);
		setTimeout(runInit, 1700);
	}
}

var runInit = function() {
	$('.motto').fadeOut(1000, function(){
		$('.motto').remove();
		$('.teeth').show();
		$('#top_jaw').animate({
			'height': '50px'
		}, 800,'swing');
		$('#bottom_jaw').animate({
			'height': '50px'
		}, 800);
	});
}

function scrollToPage(anchor){
	$('html,body').animate({
		scrollTop: $(anchor).offset().top
	}, 'slow','linear',function(){
		window.location = baseUrl + anchor;
	});
}

/*
 
       *                               )                 (                )     )            *               )     *
     (  `     (       *   )  *   )  ( /(       (  (      )\ )          ( /(  ( /(   (      (  `       (   ( /(   (  `
     )\))(    )\    ` )  /(` )  /(  )\()) (    )\))(   '(()/(      (   )\()) )\())  )\     )\))(      )\  )\())  )\))(
    ((_)()\((((_)(   ( )(_))( )(_))((_)\  )\  ((_)()\ )  /(_))     )\ ((_)\ ((_)\((((_)(  ((_)()\   (((_)((_)\  ((_)()\
    (_()((_))\ _ )\ (_(_())(_(_())  _((_)((_) _(())\_)()(_))_   _ ((_) _((_) _((_))\ _ )\ (_()((_)  )\___  ((_) (_()((_)
    |  \/  |(_)_\(_)|_   _||_   _| | || || __|\ \((_)/ / |   \ | | | || \| || || |(_)_\(_)|  \/  | ((/ __|/ _ \ |  \/  |
    | |\/| | / _ \    | |    | |   | __ || _|  \ \/\/ /  | |) || |_| || .` || __ | / _ \  | |\/| | _| (__| (_) || |\/| |
    |_|  |_|/_/ \_\   |_|    |_|   |_||_||___|  \_/\_/   |___/  \___/ |_|\_||_||_|/_/ \_\ |_|  |_|(_)\___|\___/ |_|  |_|

 */