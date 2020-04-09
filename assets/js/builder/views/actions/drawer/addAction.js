/**
 * Add action drawer.
 *
 * TODO: make dynamic
 * 
 * @package Ninja Forms builder
 * @subpackage Actions
 * @copyright (c) 2015 WP Ninjas
 * @since 3.0
 */
define( ['views/actions/drawer/typeCollection', 'models/app/typeCollection'], function( actionTypeCollectionView, actionTypeCollection ) {

	var view = Marionette.LayoutView.extend( {
		template: '#tmpl-nf-drawer-content-add-action',

		regions: {
			primary: '#nf-drawer-primary',
			
			payments: '#nf-drawer-secondary-payments',
			marketing: '#nf-drawer-secondary-marketing',
			management: '#nf-drawer-secondary-management',
			workflow: '#nf-drawer-secondary-workflow',
			notifications: '#nf-drawer-secondary-notifications',
			misc: '#nf-drawer-secondary-misc',
		},

		initialize: function() {
			this.listenTo( nfRadio.channel( 'drawer' ), 'filter:actionTypes', this.filteractionTypes );
			this.listenTo( nfRadio.channel( 'drawer' ), 'clear:filter', this.removeactionTypeFilter );
		
			this.installedActions = nfRadio.channel( 'actions' ).request( 'get:installedActions' );
			this.primaryCollection = this.installedActions;

			this.availableActions = nfRadio.channel( 'actions' ).request( 'get:availableActions' );
			this.secondaryCollection = this.availableActions;

			this.paymentsCollection = new actionTypeCollection(
				this.secondaryCollection.where({group: 'payments'}),
				{
					slug: 'payments',
					nicename: nfi18n.paymentsActionNicename
				} 
			);

			this.marketingCollection = new actionTypeCollection(
				this.secondaryCollection.where({group: 'marketing'}),
				{
					slug: 'marketing',
					nicename: nfi18n.marketingActionNicename
				} 
			);

			this.managementCollection = new actionTypeCollection(
				this.secondaryCollection.where({group: 'management'}),
				{
					slug: 'management',
					nicename: nfi18n.managementActionNicename
				} 
			);

			this.workflowCollection = new actionTypeCollection(
				this.secondaryCollection.where({group: 'workflow'}),
				{
					slug: 'workflow',
					nicename: nfi18n.workflowActionNicename
				} 
			);

			this.notificationsCollection = new actionTypeCollection(
				this.secondaryCollection.where({group: 'notifications'}),
				{
					slug: 'notifications',
					nicename: nfi18n.notificationsActionNicename
				} 
			);

			this.miscCollection = new actionTypeCollection(
				this.secondaryCollection.where({group: 'misc'}),
				{
					slug: 'misc',
					nicename: nfi18n.miscActionNicename
				} 
			);

		},

		onShow: function() {
			this.primary.show( new actionTypeCollectionView( { collection: this.primaryCollection } ) );

			this.payments.show( new actionTypeCollectionView( { collection: this.paymentsCollection } ) );
			this.marketing.show( new actionTypeCollectionView( { collection: this.marketingCollection } ) );
			this.management.show( new actionTypeCollectionView( { collection: this.managementCollection } ) );
			this.workflow.show( new actionTypeCollectionView( { collection: this.workflowCollection } ) );
			this.notifications.show( new actionTypeCollectionView( { collection: this.notificationsCollection } ) );
			this.misc.show( new actionTypeCollectionView( { collection: this.miscCollection } ) );		
		},

		getEl: function() {
			return jQuery( this.el ).parent();
		},

		filteractionTypes: function( filteredInstalled, filteredAvailable ) {
			this.primary.reset();
			this.secondary.reset();
			this.primary.show( new actionTypeCollectionView( { collection: filteredInstalled } ) );
			this.secondary.show( new actionTypeCollectionView( { collection: filteredAvailable } ) );
			
		},

		removeactionTypeFilter: function () {
			this.primary.show( new actionTypeCollectionView( { collection: this.installedActions } ) );
			this.secondary.show( new actionTypeCollectionView( { collection: this.availableActions } ) );
		}

	} );

	return view;
} );