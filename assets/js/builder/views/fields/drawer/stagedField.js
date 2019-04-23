define( [], function() {
	var view = NF_Marionette.ItemView.extend({
		tagName: 'div',
		template: '#tmpl-nf-drawer-staged-field',

		onRender: function() {
			this.$el = this.$el.children();
			this.$el.unwrap();
			this.setElement( this.$el );
		},

		events: {
			'click .dashicons-dismiss': 'removeStagedField'
		},

		removeStagedField: function( el ) {
			nfRadio.channel( 'drawer-addField' ).trigger( 'click:removeStagedField', el, this.model );
		}
	});

	return view;
} );
