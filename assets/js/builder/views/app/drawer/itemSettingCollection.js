define( [], function() {
	var view = NF_Marionette.CollectionView.extend( {
		tagName: 'div',

		initialize: function( data ) {
			this.childViewOptions = { dataModel: data.dataModel };
		},

		getChildView: function( model ) {
			return nfRadio.channel( 'app' ).request( 'get:settingChildView', model );
		}
	} );

	return view;
} );