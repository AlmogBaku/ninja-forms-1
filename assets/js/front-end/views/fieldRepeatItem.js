define( [], function() {
	var view = Marionette.ItemView.extend({
		tagName: 'div',
		template: '#tmpl-nf-field-repeat',

		onRender: function() {
            // ...
		},
	});

	return view;
} );