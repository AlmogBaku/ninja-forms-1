define( ['views/app/drawer/itemSettingGroup'], function( itemSettingGroupView ) {
	var view = NF_Marionette.CollectionView.extend( {
		tagName: 'div',
		childView: itemSettingGroupView,

		initialize: function( data ) {
			this.childViewOptions = { dataModel: data.dataModel };
		}
	} );

	return view;
} );