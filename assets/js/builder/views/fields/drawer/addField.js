define( ['views/fields/drawer/stagingCollection', 'models/fields/stagingCollection', 'views/fields/drawer/typeSectionCollection'], function( drawerStagingView, StagingCollection, fieldTypeSectionCollectionView ) {

	var view = Marionette.LayoutView.extend( {
		template: '#tmpl-nf-drawer-content-add-field',

		regions: {
			staging: '#nf-drawer-staging .nf-reservoir',
			saved: '#nf-drawer-saved',
			premium: '#nf-drawer-premium',
			fieldTypes: '#nf-drawer-fieldTypes',
		},

		initialize: function() {
			this.listenTo( nfRadio.channel( 'drawer' ), 'filter:fieldTypes', this.filterFieldTypes );
			this.listenTo( nfRadio.channel( 'drawer' ), 'clear:filter', this.removeFieldTypeFilter );

			this.savedCollection = nfRadio.channel( 'fields' ).request( 'get:savedFields' );
			this.premiumCollection = nfRadio.channel( 'fields' ).request( 'get:premiumFields' );
			this.fieldTypeCollection = nfRadio.channel( 'fields' ).request( 'get:typeSections' );
		},

		onShow: function() {
			var stagingCollection = nfRadio.channel( 'fields' ).request( 'get:staging' );
			this.staging.show( new drawerStagingView( { collection: stagingCollection } ) );

			this.saved.show( new fieldTypeSectionCollectionView( { collection: this.savedCollection } ) );
			this.premium.show( new fieldTypeSectionCollectionView( { collection: this.premiumCollection } ) );
			this.fieldTypes.show( new fieldTypeSectionCollectionView( { collection: this.fieldTypeCollection } ) );
		},

		getEl: function() {
			return jQuery( this.el ).parent();
		},

		filterFieldTypes: function( filteredSectionCollection ) {
			this.saved.reset();
			this.premium.reset();
			this.fieldTypes.reset();
			this.filteredSectionCollection = filteredSectionCollection;
			this.primary.show( new fieldTypeSectionCollectionView( { collection: this.filteredSectionCollection } ) );
		},

		removeFieldTypeFilter: function () {
			this.saved.show( new fieldTypeSectionCollectionView( { collection: this.savedCollection } ) );
			this.premium.show( new fieldTypeSectionCollectionView( { collection: this.premiumCollection } ) );
			this.fieldTypes.show( new fieldTypeSectionCollectionView( { collection: this.fieldTypeCollection } ) );
		}

	} );

	return view;
} );