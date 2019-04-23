define( [], function() {
	var view = NF_Marionette.ItemView.extend({
		tagName: 'div',
		template: '#tmpl-nf-drawer-content-edit-form-settings'
	});

	return view;
} );