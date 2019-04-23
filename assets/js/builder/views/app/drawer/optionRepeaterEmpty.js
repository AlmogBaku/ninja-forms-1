define( [], function() {
	var view = NF_Marionette.ItemView.extend({
		tagName: 'tr',
		template: '#tmpl-nf-edit-setting-option-repeater-empty'
	});

	return view;
} );