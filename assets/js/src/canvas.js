
( function( ElementAPI, SettingAPI ) {

    'use strict';

	// Components
	require( './shared/components/ui/video' );
	require( './shared/components/ui/stretch' );

	// Render callbacks
	ElementAPI.onRender( 'tailor_section', function( atts, model ) {
		if ( 'stretch' == atts['width'] ) {
			this.$el.tailorStretch();
		}
		else if ( 'stretch_content' == atts['width'] ) {
			this.$el.tailorStretch( { retainContentWidth: false } );
		}
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