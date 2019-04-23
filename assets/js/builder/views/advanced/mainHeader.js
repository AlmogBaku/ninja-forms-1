define( [], function() {
	var view = NF_Marionette.ItemView.extend({
		tagName: 'div',
		template: '#tmpl-nf-main-header-settings'
	});

	return view;
} );