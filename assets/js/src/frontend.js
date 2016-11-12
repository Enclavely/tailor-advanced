
( function( win, $, Tailor ) {

	'use strict';

	require( './shared/components/ui/video' );

	Tailor.initAdvancedElements = function() {

		$( '.tailor-section.has-background-video' ).each( function() {
			var $el = $( this );
			var parallax = $el.data( 'tailorParallax' );
			if ( parallax ) {
				parallax.destroy();
			}
			$el.tailorVideo();
		} );

		$( '[data-animation]' ).each( function() {
			var el = this;
			if ( '1' !== this.getAttribute( 'data-animation-repeat' ) ) {
				var waypoint = new Waypoint({
					element: el,
					handler: function() {
						el.classList.add( 'animated' );
						el.classList.add( el.getAttribute( 'data-animation' ) );
						el.removeAttribute( 'data-animation' );
						waypoint.destroy();
					},
					offset: 'bottom-in-view'
				} )
			}
			else {
				new Waypoint.Inview( {
					element: el,
					enter: function( direction ) {},
					entered: function( direction ) {
						el.classList.add( 'animated' );
						el.classList.add( el.getAttribute( 'data-animation' ) );
					},
					exit: function( direction ) {},
					exited: function( direction ) {
						el.classList.remove( 'animated' );
						el.classList.remove( el.getAttribute( 'data-animation' ) );
					}
				} );
			}
		} );
	};

	$( document ).ready( function() {
		Tailor.initAdvancedElements();
	} );

} ) ( window, window.jQuery, window.Tailor || {} );