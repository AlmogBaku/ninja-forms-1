/**
 * @package Ninja Forms
 * @subpackage Dashboard
 * @copyright (c) 2017 WP Ninjas
 * @since 3.2
 */
define( [], function() {
    var view = Marionette.View.extend( {
        template: '#tmpl-nf-requiredUpdates',

        ui: {
            requiredUpdates: '.nf-required-update',
    
        },

        events: {
            'click @ui.requiredUpdates': 'doRequiredUpdates',
        },

        doRequiredUpdates: function(){
            window.location.hash = '#requiredUpdates';
            
            jQuery( '#nf-required-updates-btn' ).addClass( 'disabled' ).attr( 'disabled', 'disabled' );
            
            jQuery.post( ajaxurl, {action: 'nf_required_update' } );
        },
    } );
    return view;
} );
