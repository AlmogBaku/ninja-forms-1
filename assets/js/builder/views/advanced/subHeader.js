define( [], function() {
	var view = NF_Marionette.ItemView.extend({
		tagName: 'div',
		template: '#tmpl-nf-sub-header-settings'
	});

	return view;
} );