
/**
 * CodeCinch Movie Player
 * Plays ASCII movies and animations created at http://www.codecinch.com/
 *
 * @version 0.2.1
 * @copyright 2011 Matthew Dunham, all rights reserved.
 * @package codecinch
 * @author Matthew Dunham <matt@matthewdunham.com>
 * @link http://www.codecinch.com/player/api
 * @dependency jQuery 3+ (http://www.jquery.com/)
 * @example $('#movieOne').codecinchPlayer('http://www.codecinch.com/3S2F9E460');
 * @example $('#movieOne').codecinchPlayer({
 *		url: 'http://www.codecinch.com/3S2F9E460',
 *		allowFullscreen: false,
 *		autostart: false,
 *		fps: 5, // frames per second
 *		caption: '<strong>HTML</strong> can go <a href="#">here</a>',
 *		onStart: function(options) {
 *			// options are the options for this instance of the player
 *		},
 *		onError: function(options, error_type) {
 *			// error_type is the error that occured you can reference the error code list at
 *			// http://www.codecinch.com/player/api
 *		},
 *		onPause: function(options) {
 *			// called when pause is pressed
 *		},
 *		onFinish: function(options) {
 *			// called when the movie has played all the way through
 *		}
 * });
 */

/**
 * This is player object that gets attached to the player element
 *
 * @access private
 * @param this_ The jQuery reference to the player element
 * @param options The options that were used to create this player
 * @return object [this]
 */
function codecinchPlayer (this_, options) {

	// Set the main elements
	this.options = options;
	this.element = this_;
	this.frames = [];

	// Play state: idle | buffering | playing | paused | end | error
	this.status = 'idle';

	// On error this is passed back to the client with a reason why it errored
	this.error_message = '';

	// Current frame
	this.currentFrame = 0;

	// Number of frames currently downloaded
	this.framesLoaded = 0;

	// Max frame
	this.maxFrame = 177;

	// Frames per second
	this.fps = (this.options.fps / 1000);

	// First frame count
	this.startTimer = 0;
	this.startTime = 0;
	this.endTime = 0;
	this.totalTime = 0;

	var instance = this; // self association

	// This functions will start the buffering and play the movie from the current frame
	this.play = function() {
		if (instance.status != 'playing' && instance.status != 'buffering' && instance.status != 'error') {
			instance.status = 'buffering';
			setTimeout(instance.playAction, 200);//instance.fps);
			setTimeout(instance.download,100);

			document.title = 'buffing';
		}
	};

	// Take a guess what this does
	this.pause = function() {
		instance.status = 'idle';
	}

	// Stops the stream kills the download and sets the current frame to 0
	this.stop = function() {
		instance.status = 'idle';
		instance.currentFrame = 0;
	}

	this.setFPS = function(fps) {
		instance.fps = parseFloat(fps) / 1000;
	}

	// Change the URL of the movie, if autostart true will autorestart
	this.loadUrl = function(url) {
		instance.options.url = url;
		if (instance.options.autostart) {
			instance.stop();
			instance.start();
		}
	}

	this.download = function() {
		if (instance.status != 'error' && instance.status != 'idle') {
			if (instance.framesLoaded >= 0 && instance.framesLoaded < instance.maxFrame) {
				if (instance.startTimer === 0) {
					instance.startTimer = new Date();
					instance.startTime = instance.startTimer.getTime();
				}

				$.ajax({
					url: instance.options.url + '?start=' + instance.framesLoaded,
					dataType: 'json',
					success: function(data) {
						try {
							if (instance.endTime === 0) {
								instance.startTimer = new Date();
								instance.endTime = instance.startTimer.getTime();
								instance.totalTime = instance.endTime - instance.startTime;
								instance.downloadFramesPerSecond = data.length / instance.totalTime;
								instance.framesLeft = instance.options.fps * (1 - instance.downloadFramesPerSecond);
								instance.bufferTime = (instance.framesLeft - instance.framesLoaded) / instance.downloadFramesPerSecond;
								instance.bufferFrames = (instance.bufferTime / 1000) * instance.downloadFramesPerSecond;
							}

							if (!data.length) {
								console.log('Received no data from ' + instance.options.url);
								instance.error_message = 'Received no data from ' + instance.options.url;
								if (instance.frames.length == 0) {
									instance.status = 'error';
								} else {
									instance.downloaded = true;
								}
								return;
							}


							for (index = 0; index < data.length; index++) {
								instance.frames.push(data[index]);
								instance.downloaded = false;
								instance.framesLoaded++;
							}


							if (instance.bufferFrames < instance.frames.length && instance.status != 'playing') {
								instance.status = 'playing';
							} else if (instance.bufferFrames > instance.frames.length && instance.status != 'buffering') {
								instance.status = 'buffering';
							}

							
						} catch (e) {
							console.log('Error on  ' + instance.options.url + ' - ERROR: '+ e.description);
							instance.error_message = 'Error on  ' + instance.options.url + ' - ERROR: '+ e.description;
							document.title = instance.status;
							instance.status = 'error';
							return;
						}
						
						if (instance.status != 'error') {
							setTimeout(instance.download,300);
						}
					}
				})
			}
		}
	}

	this.playAction = function() {
		if (instance.status == 'playing' ) {
			instance.element.html(instance.frames.shift());
			instance.currentFrame++;
			setTimeout(instance.playAction, 200);// instance.fps);
		}

		if (instance.status == 'buffering') {
			setTimeout(instance.playAction,200);// instance.fps);
		}

	}

	return this;
}

(function($) {
	$.fn.codecinchPlayer = function(ops) {

		// Define the defaultOptions that allows $('selector').codecinchPlayer();
		var defaultOptions = {
			url: 'http://www.codecinch.com/demo',
			allowFullscreen: true,
			autostart: false,
			caption: '',
			fps: 10,
			showSeekbar: true,
			allowSeeking: true,
			onStart: null,
			onError: null,
			onPause: null,
			onFinish: null
		};

		// Handel .codecinchPlayer('http://url/');
		if (typeof(ops) === 'string') {
			defaultOptions.url = ops;
			ops = {};
		}

		//Merge the passed options with the default options
		var options = $.extend(defaultOptions, ops);

		var player;

		// Create the player(s)
		$(this).each(function() {
			player = new codecinchPlayer($(this), options);
			
		});
		return player;
	}
})(jQuery);


