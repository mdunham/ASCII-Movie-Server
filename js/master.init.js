/*
 * Initialize all JavaScript pieces.
 *
 * @author Matthew Dunham <matt@matthewdunham.com>
 */

var checkTimer;

if (window.jQuery) {
	jQuery(function($){

		$(window).load(function() {
			checkTimer = setInterval( runCheck, 100);
		});

		$('#main div.nav a').click(function(e){
			e.preventDefault();
			scrollToPage($(this).attr('href'));
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
		$('#top_jaw').animate({'height': '50px'}, 800,'swing');
		$('#bottom_jaw').animate({'height': '50px'}, 800);
	});
}

function scrollToPage(anchor){
     	$('html,body').animate({
			scrollTop: $(anchor).offset().top
		}, 'slow');
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