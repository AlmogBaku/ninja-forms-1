/**
 * Batch Processor JS Object
 */
function NinjaBatchProcessor( settings ) {
	var that = this;
	var data = {
		closeOnClick: false,
        closeOnEsc: true,
        content: settings.content,
        btnPrimary: {
			text: settings.btnPrimaryText,
			callback: function( e ) {
                // Hide the buttons.
                modalInstance.maybeShowActions( false );
                // Show the progress bar.
                modalInstance.maybeShowProgress( true );
                // Begin our cleanup process.
                that.postToProcessor( that, -1, modalInstance );

			}
		},
        btnSecondary: {
        	text: settings.btnSecondaryText,
			callback: function( e ) {
        		modalInstance.toggleModal( false );
			}
		},
        useProgressBar: true,
	};

    this.postToProcessor = function( context, steps, modal, data ) {
        if ( 'undefined' == typeof data ) {
            var data = {
                action: 'nf_batch_process',
                batch_type: settings.batch_type,
                security: nf_settings.batch_nonce,
                extraData: settings.extraData
            };            
        }

        jQuery.post( nf_settings.ajax_url, data, function( response ) {
            response = JSON.parse( response );
            // If we're done...
            if ( response.batch_complete ) {
                // Push our progress bar to 100%.
                modal.setProgress( 100 );
                modal.toggleModal( false );
                // Exit.
                return false;
            }
            // If we do not yet have a determined number of steps...
            if ( -1 == steps ) {
                // If step_toal is defined...
                if ( 'undefined' != typeof response.step_total ) {
                    // Use the step_total.
                    steps = response.step_total;
                } // Otherwise... (step_total is not defined)
                else {
                    // Use step_remaining.
                    steps = response.step_remaining;
                }
            }
            // If our PHP edited our extraData variable, update our JS var and pass it along.
            if ( 'undefined' != typeof response.extraData ) {
                // Update our extraData property.
                data.extraData = response.extraData;                
            }

            // Calculate our current step.
            var step = steps - response.step_remaining;
            // Calculate our maximum progress for this step.
            var maxProgress = Math.round( step / steps * 100 );
            // Increment the progress.
            modal.incrementProgress ( maxProgress );
            // Recall our function...
            context.postToProcessor( context, steps, modal, data );
        } );
    }

	var modalInstance = new NinjaModal( data );
}