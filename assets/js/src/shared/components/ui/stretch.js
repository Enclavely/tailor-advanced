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
        return {};
    },

    onInitialize : function () {
        
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