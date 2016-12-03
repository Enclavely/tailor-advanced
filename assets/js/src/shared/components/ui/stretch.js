/**
 * Tailor.Components.Stretch
 *
 * A component used to stretch sections.
 *
 * @class
 */
var $ = window.jQuery,
    Components = window.Tailor.Components,
    Stretch;


Stretch = Components.create( {

    getDefaults : function () {
        return {
            retainContentWidth : true
        };
    },

	/**
     * Initializes the component.
     *
     * @since 1.0.1
     */
    onInitialize : function () {
        this.parent = this.el.parentNode;
        this.content = this.$el.children( '.tailor-section__content' ).get( 0 );
        
        this.applyStyles();
    },

	/**
     * Refreshes styles when the screen is resized.
     *
     * @since 1.0.1
     */
    onResize: function() {
        this.refreshStyles();
    },

	/**
     * Apply styles to the Section and its content.
     *
     * @since 1.0.1
     */
    applyStyles: function() {
        var rect = this.el.getBoundingClientRect();
        var width = rect.width;
        var left = rect.left;

        this.el.style.width = window.innerWidth + 'px';
        this.el.style.marginLeft = - left + 'px';

        if ( this.options.retainContentWidth ) {
            this.content.style.maxWidth = width + 'px';
            this.content.style.marginLeft = left + 'px';
        }
        else {
            this.content.style.width = '100%';
            this.content.style.marginLeft = '0px';
        }
    },

	/**
     * Resets styles for the Section and its content.
     *
     * @since 1.0.1
     */
    resetStyles : function() {
        this.el.style = '';
        this.content.style = '';
    },

	/**
	 * Refreshes styles for the Section and its content.
     *
     * @since 1.0.1
     */
    refreshStyles: function() {
        this.resetStyles();
        this.applyStyles();
    }

} );

$.fn.tailorStretch = function( options, callbacks ) {
    return this.each( function() {
        var instance = $.data( this, 'tailorStretch' );
        if ( ! instance ) {
            $.data( this, 'tailorStretch', new Stretch( this, options, callbacks ) );
        }
    } );
};

module.exports = Stretch;