define( [], function() {
	var view = NF_Marionette.ItemView.extend({
		tagName: 'div',
		template: '#tmpl-nf-main-content-actions-empty',

		onBeforeDestroy: function() {
			jQuery( this.el ).parent().parent().removeClass( 'nf-actions-empty' );
			// jQuery( this.el ).parent().removeClass( 'nf-fields-empty-droppable' ).droppable( 'destroy' );
		},

		onRender: function() {
			this.$el = this.$el.children();
			this.$el.unwrap();
			this.setElement( this.$el );
		},

		onShow: function() {
			jQuery( this.el ).parent().parent().addClass( 'nf-actions-empty' );
			// if ( jQuery( this.el ).parent().hasClass( 'ui-sortable' ) ) {
			// 	jQuery( this.el ).parent().sortable( 'destroy' );
			// }
			// jQuery( this.el ).parent().addClass( 'nf-fields-empty-droppable' );
			// jQuery( this.el ).parent().droppable( {
			// 	accept: function( draggable ) {
			// 		if ( jQuery( draggable ).hasClass( 'nf-stage' ) || jQuery( draggable ).hasClass( 'nf-field-type-button' ) ) {
			// 			return true;
			// 		}
			// 	},
			// 	hoverClass: 'nf-droppable-hover',
			// 	tolerance: 'pointer',
			// 	over: function( e, ui ) {
			// 		ui.item = ui.draggable;
			// 		nfRadio.channel( 'app' ).request( 'over:fieldsSortable', ui );
			// 	},
			// 	out: function( e, ui ) {
			// 		ui.item = ui.draggable;
			// 		nfRadio.channel( 'app' ).request( 'out:fieldsSortable', ui );
			// 	},
			// 	drop: function( e, ui ) {
			// 		ui.item = ui.draggable;
			// 		nfRadio.channel( 'app' ).request( 'receive:fieldsSortable', ui );
			// 		var fieldCollection = nfRadio.channel( 'fields' ).request( 'get:collection' );
			// 		fieldCollection.trigger( 'reset', fieldCollection );
			// 	},
			// } );
		}
	});

	return view;
} );