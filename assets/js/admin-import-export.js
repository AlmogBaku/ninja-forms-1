jQuery( document ).ready( function( $ ) {
	/**
	 * Stores the selected file details for form imports.
	 *
	 * name is the filename from the user's computer, including extension.
	 * content is the base64 encoded contents as a result of using the HTML5 FileReader API.
	 * @type {Object}
	 */
	var importFormFile = {
		name: '',
		content: ''
	};

	/**
	 * Listen for clicks on our "import" button.
	 * It sets data for our batch processor and then instantiates a batch process.
	 * 
	 * @since  UPDATE_VERSION_ON_MERGE
	 * @param  object e	Click Event
	 * @return void
	 */
	$( document ).on( 'click', '#nf-import-form', function( e ) {
		// Settings object for our batch processor
		var settings = {
			// Initial content for the popup modal
    		content: '<h3>Select a file</h3><p><input id="nf-import-file" type="file" /></p>',
    		btnPrimaryText: 'Import!',
    		btnSecondaryText: 'Cancel',
    		// Batch processor slug. Must match what we have set in our PHP settings array.
    		batch_type: 'import_form',
    		extraData: importFormFile
    	}
    	/**
    	 * Instantiate our batch processor.
    	 *
    	 * This will open the modal and present the user with content and buttons.
    	 */
    	new NinjaBatchProcessor( settings );
	} );

	/**
	 * Selecting a file within an input field triggers a jQuery change event.
	 * 
	 * When we select a form file to import, we need to do a few things:
	 *
	 * Disable the primary button of our batch processing modal.
	 * Grab the file and make sure that it has a .nff extension.
	 * Read the contents and base64 encode them using the HTML5 FileReader API.
	 * Set the contents to our importFormFile variable.
	 * 
	 * @since  UPDATE_VERSION_ON_MERGE
	 * @param  object e Change Event
	 * @return {[type]}             [description]
	 */
	$( document ).on( 'change', '#nf-import-file', function( e ) {
		// Grab the file from the input.
		var file = e.target.files[0];
		// If our file var is empty, bail.
		if ( ! file ) {
			return false;
		}

		// Use some Regex to get the extension
		var extension = file.name.match(/\.[0-9a-z]+$/i);

		// If we don't have a .nff extension, bail.
		if ( '.nff' !== extension[0] ) {
			return false;
		}
		
		// Disable the primary button for our batch processing modal.
		jQuery( '#nf-button-primary-1' ).hide();

		// Instantiate the HTML5 FileReader API.
		var reader  = new FileReader();

		/**
		 * When the HTML5 API says that we've successfully loaded the file contents:
		 * 	Set our importFormFile var.
		 *  Enable our batch processor primary button.
		 * We use Javascript's addEventListener to update our var.
		 */
		reader.addEventListener( 'load', function () {
			importFormFile.name = file.name;
			importFormFile.content = reader.result;
			jQuery( '#nf-button-primary-1' ).show();
		}, false);

		// Use the readAsDataURL method of the FileReader API.
		reader.readAsDataURL( file );
	} );

	var clickedElement;

	$(document).off( 'mousedown' ).on( 'mousedown', function( e ) {
		clickedElement = e.target;
	});

	$( '#nf_export_form_2' ).off( 'focus' ).on( 'focus', function () {
		//show the dropdown on focus of the input
		$( '.nf-form-dropdown' ).show();
	});

	$( '#nf_export_form_2' ).off( 'keyup' ).on( 'keyup', function () {
		//show the dropdown if it isn't show
		$( '.nf-form-dropdown' ).show();
		// get the value of the input, which we filter on
		var filter = $( this ).val();
		if( '' === filter ) {
			//if the filter val is empty, show all form options
			$( '.nf-form-dropdown' ).find( 'li' ).show();
		} else {

			$.each( $( '#nf_form_export_options span' ), function ( index, span ) {
				var tmpSpan = $( span );
				// test to see if span text contains the entered value
				if ( 0 <= tmpSpan.text().toLowerCase().indexOf( filter.toLowerCase() ) ) {
					// shows options that DO contain the text entered
					tmpSpan.parent().show();
				} else {
					// hides options the do not contain the text entered
					tmpSpan.parent().hide();
				}
			});
		}
	});

	$( '#nf_export_form_2' ).off( 'blur' ).on( 'blur' , function( e ) {
		if( 'undefined' !== typeof clickedElement ) {
			if ( ! $( clickedElement ).hasClass( 'nf-form-option-item' ) ) {
				$( '#nf_export_form_2' ).val( '' );
				$( '.nf-form-dropdown' ).hide();
			}
		}
	});

	$( '.nf-form-option' ).off( 'click' ).on( 'click', function() {
		// on click get the value of the input
		var val = $( this ).data( 'val' );
		// nf_export_form is now a hidden field instead of select element
		$( '#nf_export_form' ).val( val );
		// set the text of the input field
		$( '#nf_export_form_2' ).val( '' );
		// and hide the option.
		$( '.nf-form-dropdown' ).hide();
	});

});