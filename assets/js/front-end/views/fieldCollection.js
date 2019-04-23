define( ['views/fieldLayout'], function( fieldLayout ) {
	var view = NF_Marionette.CollectionView.extend({
		tagName: 'nf-fields-wrap',
		childView: fieldLayout

	});

	return view;
} );