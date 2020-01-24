define( ['views/fieldErrorItem'], function( fieldErrorItem ) {
	var view = Marionette.CollectionView.extend({
		tagName: "nf-errors",
		childView: fieldErrorItem,

		initialize: function( options ) {
			this.fieldModel = options.fieldModel;
		},

		onRender: function() {
			if ( 0 == this.fieldModel.get( 'errors' ).models.length ) {
                this.fieldModel.removeWrapperClass( 'nf-error' );
                this.fieldModel.removeWrapperClass( 'nf-fail' );
                this.fieldModel.addWrapperClass( 'nf-pass' );
				this.fieldModel.setInvalid( false );
				jQuery("#nf-field-" + this.fieldModel.id).removeAttr('aria-describedby');

				document.getElementById("nf-field-" + this.fieldModel.id).removedAttribute('aria-describedby');
            } else {
                this.fieldModel.removeWrapperClass( 'nf-pass' );
                this.fieldModel.addWrapperClass( 'nf-fail' );
                this.fieldModel.addWrapperClass( 'nf-error' );
				this.fieldModel.setInvalid( true );
				
				document.getElementById("nf-field-" + this.fieldModel.id).setAttribute('aria-describedby', "nf-error-" + this.fieldModel.id);
            }

		}
	});

	return view;
} );
