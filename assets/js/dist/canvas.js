(function e(t,n,r){function s(o,u){if(!n[o]){if(!t[o]){var a=typeof require=="function"&&require;if(!u&&a)return a(o,!0);if(i)return i(o,!0);var f=new Error("Cannot find module '"+o+"'");throw f.code="MODULE_NOT_FOUND",f}var l=n[o]={exports:{}};t[o][0].call(l.exports,function(e){var n=t[o][1][e];return s(n?n:e)},l,l.exports,e,t,n,r)}return n[o].exports}var i=typeof require=="function"&&require;for(var o=0;o<r.length;o++)s(r[o]);return s})({1:[function(require,module,exports){

( function( ElementAPI, SettingAPI ) {

    'use strict';

	// Components
	require( './shared/components/ui/video' );

	// Render callbacks
	ElementAPI.onRender( 'tailor_section', function( atts, model ) {
		if ( atts['background_video'] ) {
			if ( atts['background_image'] && atts['parallax'] ) {
				this.$el.data( 'tailorParallax' ).destroy();
			}
			this.$el.tailorVideo();
		}
	} );

	ElementAPI.onRender( 'tailor_image', function( atts, model ) {
		var $el = this.$el;
		if ( 'lightbox' == atts.image_link ) {
			$el.tailorLightbox( {
				disableOn : function() {
					return $el.hasClass( 'is-selected' );
				}
			} );
		}
	} );

	// Setting change callbacks
	SettingAPI.onChange( 'element:stretch', function( to, from, model ) {
		return [ {
			'selectors' : [ '.wp-caption-text' ],
			'declarations' : {
				'color' : tailorValidateColor( to )
			}
		} ]
	} );
	
	SettingAPI.onChange( 'element:id', function( to, from, model ) {
		this.el.id = to.replace( / /g,'' );
	} );

	SettingAPI.onChange( 'element:caption_color', function( to, from, model ) {
		return [ {
			'selectors' : [ '.wp-caption-text' ],
			'declarations' : {
				'color' : tailorValidateColor( to )
			}
		} ]
	} );

	SettingAPI.onChange( 'element:overlay_color', function( to, from, model ) {
		return [ {
			'selectors' : [ '.tailor-video-overlay' ],
			'declarations' : {
				'background-color' : tailorValidateColor( to )
			}
		} ]
	} );

	SettingAPI.onChange( 'element:animation', function( to, from, model ) {
		if ( ! _.isEmpty( from ) ) {
			this.el.classList.remove( from );
		}
		if ( ! _.isEmpty( to ) ) {
			this.el.classList.add( 'animated' );
			this.el.classList.add( to );
		}
		else {
			this.el.classList.remove( 'animated' );
		}
	} );

	SettingAPI.onChange( 'element:animation_timing', function( to, from, model ) {
		var rules = [];
		to = to.split( ',' );
		if ( to[0] ) {
			rules.push( {
				selectors: [],
				declarations: { 'animation-duration' : to[0] + 'ms' }
			} );
		}
		if ( to[1] ) {
			rules.push( {
				selectors: [],
				declarations: { 'animation-delay' : to[1] + 'ms' }
			} );
		}
		return rules;
	} );

	/**
	 * Returns a value as a percentage.
	 *
	 * @since 1.0.0
	 *
	 * @param value
	 * @returns {string}
	 */
	window.tailorValidatePercentage = function( value ) {
		return tailorValidateNumber( value ) + '%';
	};

	/**
	 * Returns a box shadow CSS rule.
	 *
	 * @since 1.0.0
	 *
	 * @param atts
	 * @returns {*[]}
	 */
	function boxShadowCSS( atts ) {
		var shadow = ( '1' == atts.shadow ) ? (
			atts.shadow_horizontal + 'px ' +
			atts.shadow_vertical + 'px ' +
			atts.shadow_blur + 'px ' +
			atts.shadow_spread + 'px ' +
			atts.shadow_color
		) : 'none';
		return [ {
			'setting' : 'shadow',
			'selectors' : [],
			'declarations' : {
				'box-shadow' : shadow
			}
		} ];
	}

	app.on( 'start', function() {

		// Image-specific CSS rule overrides
		window.Tailor.Settings.overrides['tailor_image'] = {
			'max_width' : [ [ 'img' ], 'max-width', 'tailorValidatePercentage' ],
			'max_width_tablet' : [ [ 'img' ], 'max-width', 'tailorValidatePercentage' ],
			'max_width_mobile' : [ [ 'img' ], 'max-width', 'tailorValidatePercentage' ]
		};

		// Global CSS rule overrides
		window.Tailor.Settings.overrides['*']['shadow'] = function( to, from, model ) {

			/**
			 * Triggers the deletion of CSS rules associated with this element/setting.
			 *
			 * @since 1.7.3
			 */
			app.channel.trigger( 'css:delete', model.get( 'id' ), 'shadow' );

			return boxShadowCSS( model.get( 'atts' ) );
		};

		_.each( [
			'shadow_horizontal',
			'shadow_vertical',
			'shadow_blur',
			'shadow_spread',
			'shadow_color'
		], function( id ) {
			SettingAPI.onChange( ( 'element:' + id ), function( to, from, model ) {

				/**
				 * Triggers the deletion of CSS rules associated with this element/setting.
				 *
				 * @since 1.7.3
				 */
				app.channel.trigger( 'css:delete', model.get( 'id' ), 'shadow' );

				return boxShadowCSS( model.get( 'atts' ) );
			} );
		} );
	} );
	
} ) ( window.Tailor.Api.Element || {}, window.Tailor.Api.Setting || {} );
},{"./shared/components/ui/video":2}],2:[function(require,module,exports){
/**
 * Tailor.Components.Video
 *
 * A video background component.
 *
 * @class
 */
var $ = window.jQuery,
    Components = window.Tailor.Components,
    Video;

var youTubeScriptAdded = false;
var youTubeApiReady = 'undefined' != typeof window.YT;
var youTubePlayers = [];

/**
 * Creates a new YouTube player.
 * 
 * @since 1.0.0
 * 
 * @param el
 * @param videoId
 * @param callback
 */
function createYouTubePlayer( el, videoId, callback ) {
    if ( ! youTubeApiReady ) {
        if ( ! youTubeScriptAdded ) {
            var tag = document.createElement( 'script' );
            tag.src = "https://www.youtube.com/iframe_api";
            var firstScriptTag = document.getElementsByTagName('script')[0];
            firstScriptTag.parentNode.insertBefore( tag, firstScriptTag );
            youTubeScriptAdded = true;
        }
        youTubePlayers.push( [ el, videoId, callback ] );
    }
    else {
        var player = new YT.Player( el, {
            height: '390',
            width: '640',
            videoId: videoId,
            playerVars: {
                enablejsapi: 1,
                playsinline: 1,
                modestbranding : 1,
                rel : 0,
                showsearch : 0,
                showinfo: 0,
                autoplay: 1,
                controls : 0,
                loop : 1,
                playlist: videoId
            },
            events: {
                'onReady': function() {
                    player.mute();
                    if ( 'function' == typeof callback ) {
                        callback.call( player );
                    }
                }
            }
        } );
    }
}

/**
 * Creates new YouTube youTubePlayers when the API is available.
 * 
 * @since 1.0.0
 */
window.onYouTubeIframeAPIReady = function() {
    youTubeApiReady = true;
    if ( youTubePlayers.length ) {
        _.each( youTubePlayers, function( player ) {
            createYouTubePlayer( player[0], player[1], player[2] );
        } )
    }
};

var vimeoScriptAdded = false;
var vimeoApiReady = false;
var vimeoPlayers = [];

/**
 * Creates a new Vimeo player.
 *
 * @since 1.0.0
 *
 * @param el
 * @param videoId
 * @param callback
 */
function createVimeoVideo( el, videoId, callback ) {
    if ( ! vimeoApiReady ) {
        if ( ! vimeoScriptAdded ) {
            var script = document.createElement( 'script' );
            script.setAttribute( 'type', 'text/javascript' );
            script.setAttribute( 'src', 'https://player.vimeo.com/api/player.js' );
            document.getElementsByTagName( 'head' ).item(0).appendChild( script );
            script.addEventListener( 'load', onVimeoAPIReady, false );
            vimeoScriptAdded = true;
        }
        vimeoPlayers.push( [ el, videoId, callback ] );
    }
    else {
        var options = {
            id: videoId,
            width: 640,
            autoplay: true,
            loop: true,
            title: false
        };

        var player = new Vimeo.Player( el, options );
        player.setVolume(0);
        player.on( 'loaded', function() {
            if ( 'function' == typeof callback ) {
                callback.call( player );
            }
        } );
    }
}

/**
 * Creates new Vimeo youTubePlayers when the API is available.
 *
 * @since 1.0.0
 */
window.onVimeoAPIReady = function() {
    vimeoApiReady = true;

    if ( vimeoPlayers.length ) {
        _.each( vimeoPlayers, function( player ) {
            createVimeoVideo( player[0], player[1], player[2] );
        } )
    }
};

Video = Components.create( {

    video: false,

    onInitialize: function() {
        var video = this.el.querySelector( '.tailor-video' );
        if ( video ) {
            video.id = this.id;
            if ( video.classList.contains( 'tailor-video--youtube' ) ) {
                createYouTubePlayer( video, video.getAttribute( 'data-video-id' ), this.initVideo.bind( this ) );
            }
            else if ( video.classList.contains( 'tailor-video--vimeo' ) ) {
                createVimeoVideo( video, video.getAttribute( 'data-video-id' ), this.initVideo.bind( this ) );
            }
            else {
                this.initVideo();
            }
        }
    },

	/**
     * Initializes the video background.
     * 
     * @since 1.0.0
     */
    initVideo : function() {
        this.video = document.getElementById( this.id );
        this.fitVideo();
    },

	/**
     * Fits the video to the section.
     * 
     * @since 1.0.0
     */
    fitVideo : function() {
        if ( ! this.video ) {
            return;
        }

        var sectionWidth = this.el.offsetWidth;
        var sectionHeight = this.el.offsetHeight;
        var videoWidth = 16;
        var videoHeight = 9;

        if ( ( sectionWidth / sectionHeight ) < ( videoWidth / videoHeight ) ) {
            var width = ( sectionHeight / videoHeight ) * videoWidth;
            this.video.style.top = '';
            this.video.style.left = -( width - sectionWidth ) / 2 + 'px';
            this.video.style.height = sectionHeight + 'px';
            this.video.style.width = width + 'px';
        }
        else {
            var height	= ( sectionWidth / videoWidth ) * videoHeight;
            this.video.style.top = -( height - sectionHeight ) / 2 + 'px';
            this.video.style.left = '';
            this.video.style.height = height + 'px';
            this.video.style.width = '100%';
        }
    },

    /**
     * Element listeners
     */
    onJSRefresh: function() {
        this.fitVideo();
    },

    onDestroy: function() {
        this.video.removeAttribute('style');
    },
    
    /**
     * Child listeners
     */
    onChangeChild: function() {
        this.fitVideo();
    },
    
    /**
     * Descendant listeners
     */
    onChangeDescendant: function() {
        this.fitVideo();
    },

    /**
     * Window listeners
     */
    onResize: function() {
        this.fitVideo();
    }
    
} );

/**
 * Video background jQuery plugin.
 *
 * @since 1.0.0
 *
 * @param options
 * @param callbacks
 * @returns {*}
 */
$.fn.tailorVideo = function( options, callbacks ) {
    return this.each( function() {
        var instance = $.data( this, 'tailorVideo' );
        if ( ! instance ) {
            $.data( this, 'tailorVideo', new Video( this, options, callbacks ) );
        }
    } );
};

module.exports = Video;
},{}]},{},[1]);
