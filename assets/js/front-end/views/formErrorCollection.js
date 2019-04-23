define( ['views/formErrorItem'], function( formErrorItem ) {
	var view = NF_Marionette.CollectionView.extend({
		tagName: "nf-errors",
		childView: formErrorItem
	});

	return view;
} );