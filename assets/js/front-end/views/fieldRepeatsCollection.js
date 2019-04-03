define( ['views/fieldItem'], function( fieldItem ) {
	var view = Marionette.CollectionView.extend({
		tagName: "nf-repeats",
		childView: fieldItem,

		initialize: function( options ) {
			// ...
		},

		onRender: function() {
            // ...
		}
	});

	return view;
} );
