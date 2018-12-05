/*
 * Handles setting up our forms table.
 *
 * Holds a collection of our forms.
 * Replies to requests for form data.
 * Updates form models.
 */
define([ 'models/viewModel', 'models/viewCollection' ], function( ViewModel, ViewsCollection ) {
	var controller = Marionette.Object.extend( {
		initialize: function() {
			this.views = new ViewsCollection();

            nfRadio.channel( 'dashboard' ).reply( 'get:views', this.getViews, this );

			this.views.fetch({
				success: function( collection ){
                    nfRadio.channel( 'dashboard' ).trigger( 'fetch:views', collection );
				}
			});
		},

		getViews: function() {
			return this.views;
		},
	});

	return controller;
} );