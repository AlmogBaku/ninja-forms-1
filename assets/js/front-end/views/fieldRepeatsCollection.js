define( ['views/fieldRepeatItem'], function( fieldRepeatItem ) {
	var view = Marionette.CollectionView.extend({
		tagName: "nf-repeats",
		childView: fieldRepeatItem,

		initialize: function( options ) {
			// ...
		},

		onRender: function() {
            // ...
		}
	});

	return view;
} );
