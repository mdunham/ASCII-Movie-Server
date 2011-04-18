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

		var nativePlaceholderSupport = (function(){
			var i = document.createElement('input');
			return ('placeholder' in i);
		})();

		if (nativePlaceholderSupport){
			$('label').hide();
		}

		// Set the slider to be sliding
		$("#unlock-slider").slider({
			handle: "#unlock-handle",
			animate:true,
			slide: function(e,ui)
			{
				$("#slide-to-unlock").css("opacity", 1-(parseInt($("#unlock-handle").css("left"))/120));
			},
			stop: function(e,ui)
			{
				if($("#unlock-handle").position().left == 205)
				{
					unlock();
				}
				else
				{
					$("#unlock-handle").animate({left: 0}, 200 );
					$("#slide-to-unlock").animate({opacity: 1}, 200 );
				}
			}
			}
		);

		var unlock = function()
		{
			//$("#iphone-inside").animate({backgroundPosition: '0 40'}, 400, '', function()
			//{
			alert('unlock()');
				$("#unlock-bottom").animate({bottom: -100}, 300);
				$("#unlock-top").animate({top: -100}, 300, '', function()
				{});
				$("#iphone-inside").fadeOut("normal", function(){window.location="index.html";});
			//});
		}

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