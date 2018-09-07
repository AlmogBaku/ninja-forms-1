/**
 * @package Ninja Forms
 * @subpackage Dashboard
 * @copyright (c) 2017 WP Ninjas
 * @since 3.2
 */
define( [], function() {
    var view = Marionette.View.extend( {
        template: '#tmpl-nf-requiredUpdates',
       
        currentUpdate: 0,  // current update out of totalUpdate

        totalUpdates: -1, // we start with -1 and overwrite it 

        updatesRemaining: -1,

        ui: {
            requiredUpdates: '.nf-required-update',
    
        },

        events: {
            'click @ui.requiredUpdates': 'doRequiredUpdates',
        },

        /**
         * Function that starts running required updates if we have any
         */

        doRequiredUpdates: function(){
            window.location.hash = '#requiredUpdates';

            var context = this;
            
            // disable the button once we've clicked
            jQuery( '#nf-required-updates-btn' ).addClass( 'disabled' ).attr( 'disabled', 'disabled' );
            
            //make initial call to initiate required updates
            jQuery.post( ajaxurl, {action: 'nf_required_update' } )
                .then( function( response ) {
                    var res = JSON.parse( response );

                    // if we still have updates remaining, call the ajax again
                    if( res.updatesRemaining > 0 ) {

                        /**
                         * We had to add this if/else b/c the classes were returning
                         * results where the currentStep and stepsTotal values
                         * were the same, but the updatesRemaining value had changed,
                         * thus causing any progress bars after the first to 
                         * automatically show 100% even though the updates continue
                         */
                        if( context.updatesRemaining !== res.updatesRemaining 
                            && res.currentStep === res.stepsTotal ) {
                            
                            context.doRequiredUpdates();
                            
                        } else {
                            context.showProgressBars( res );
                            context.doRequiredUpdates();
                            context.updatesRemaining = res.updatesRemaining;
                        }
                    } else {
                        // get our main progess bar(s) container
                        var mainProgressBarDiv = document.getElementById( 'nf-required-updates-progress' );
                        var doneDiv = document.createElement( 'div' );
                        doneDiv.innerHTML = "<strong>Updates Done!</strong>";
                        mainProgressBarDiv.appendChild( doneDiv );
                        console.log( "UPDATE DONE" );
                    }
                });
        },

        /**
         * Function create and display progress bars. 
         * We create one for each update
         * 
         * @param data 
         */
        showProgressBars: function( data ) {
            var update = data.updatesRemaining;
            var progress = data.currentStep;
            var totalSteps = data.stepsTotal;

            var currentProgressBar = document.getElementById( 'nf_progressBar_' + update );

            if( null == currentProgressBar ) {
                // if the element requested is null, then we know this is a new update
                this.currentUpdate += 1;
                if( 1 === this.currentUpdate && -1 === this.totalUpdates ) {
                    // the initial 'update' value with be how many remaining(total updates)
                    this.totalUpdates = update;
                }
                // create a new progress bar if it doesn't exist
                currentProgressBar = this.createNewProgressBar( update );
            }

            // get the update text element for the progress bar
            var currentUpdateText = document.getElementById( 'update-text-' + update );

            // Initial text inidicating which update of total updates we are on
            var updateText = "Doing Update " + this.currentUpdate
                + " of " + this.totalUpdates;
                
            if( progress === totalSteps ) {
                // if we are done with this update, then mark it DONE
                updateText = updateText + " <strong>...DONE</strong>";
            } else {
                // otherwise, tell us the progress of steps within the udpate
                updateText = updateText + " <em>( Step " + progress + " of " 
                + totalSteps + " )</em>";
            }

            // set the text
            currentUpdateText.innerHTML = updateText

            // update the progress bar
            this.incrementProgress( update, progress, totalSteps)
        },

        /**
         * Create a new progress bar for the new update
         * 
         * @param update
         * 
         * @returns newProgressBarContainer
         */
        createNewProgressBar: function( update ) {
            //create new container
            var newProgressBarContainer = document.createElement( 'div' );
            newProgressBarContainer.id = 'nf_progressBar_' + update;

            // create update text element
            var updateText = document.createElement( 'p' );
            updateText.id = 'update-text-' + update;

            // create new progress bar
            var newProgressBar = document.createElement( 'div' );
            newProgressBar.classList.add( 'nf-progress-bar' );

            // create the slider
            var newProgressSlider = document.createElement( 'div' );
            newProgressSlider.id = 'nf-progress-bar-slider-' + update;
            newProgressSlider.classList.add( 'nf-progress-bar-slider' );

            // append text to the container
            newProgressBarContainer.appendChild( updateText );

            // append the slider to the progress bar
            newProgressBar.appendChild( newProgressSlider );

            // append the progress bar to the container
            newProgressBarContainer.appendChild( newProgressBar );

            // get our main progess bar(s) container
            var mainProgressBarDiv = document.getElementById( 'nf-required-updates-progress' );

            // append the new progress bar to the main container
            mainProgressBarDiv.appendChild( newProgressBarContainer );

            return newProgressBarContainer;
        },


        /**
         * Increment the progress based on total steps and current progress
         * 
         * @param update
         * @param progress
         * @param totalSteps
         */
        incrementProgress: function( update, currentStep, totalSteps ) {
            
            // get the slider element
            var progressBar = document.getElementById( 'nf-progress-bar-slider-' + update );

            // get the current progress(%) based on total steps and currentStep
            var newValue = ( Number( currentStep ) / Number( totalSteps ) ) * 100;
            
            // Get our current progress.
            var currentProgress = progressBar.offsetWidth / progressBar.parentElement.offsetWidth * 100;
            
            // If the new value is greater than the currentProgress, update it
            if ( newValue > currentProgress ) {
                this.setProgress( update, newValue );
            }
        },

        /**
         * Sets the current progress for the current progress bar
         * 
         * @param update
         * @param percent
         */
        setProgress: function( update, percent ) {
            // Update the width of the element as a percentage.
            var progressBar = document.getElementById( 'nf-progress-bar-slider-' + update );
            progressBar.style.width = percent + '%';
        }
    } );
    return view;
} );
