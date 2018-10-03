/**
 * Forms Widget Individual Form Template View
 *
 * @package Ninja Forms
 * @subpackage Dashboard
 * @copyright (c) 2017 WP Ninjas
 * @since 3.2
 */
define( [], function() {
    var view = Marionette.View.extend( {
        template: "#tmpl-nf-widget-forms-template",

        events: {
        	'click': 'maybeOpenModal'
        },

        /**
         * Prevent page navigation and open our ad modal if this is an ad.
         * @since  3.2.22
         * @param  {object} e Event Object from jQuery
         * @return {void}
         */
        maybeOpenModal: function( e ) {
            e.preventDefault();

            // Settings object for our batch processor
            var settings = {
                // Initial content for the popup modal
                content: '',
                // Batch processor slug. Must match what we have set in our PHP settings array.
                batch_type: 'import_form_template',
                loadingText: 'Importing...',
                extraData: { template: this.model.get( 'id' ) },
                onCompleteCallback: function( response ) {
                    // Bail if we don't return a form ID.
                    if ( 'undefined' == response.form_id ) return false;

                    window.location.href = nfAdmin.builderURL + response.form_id;
                }
            }

            /**
             * Instantiate our batch processor.
             *
             * This will open the modal and present the user with content and buttons.
             */
            new NinjaBatchProcessor( settings );

        	// If this isn't an ad, then early return
        	if ( 'ad' != this.model.get( 'type' ) ) {
        		return true;
        	}
        	// Prevent page navigation.
        	e.preventDefault();

        	// Open our jBox modal
        	var modal = new jBox( 'Modal', {
        		width: 450,
        		title: this.model.get( 'modal-title' ),
        		content: this.model.get( 'modal-content' ),
                closeButton: 'box',
                blockScroll: true
        	} );

        	modal.open();
        }

    } );
    return view;
} );
