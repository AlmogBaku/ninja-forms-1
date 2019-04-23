define( ['views/advanced/settingItem'], function( settingItem ) {
	var view = NF_Marionette.CollectionView.extend({
		childView: settingItem
		
	});

	return view;
} );