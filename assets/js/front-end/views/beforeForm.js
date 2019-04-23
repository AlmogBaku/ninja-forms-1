define( [], function( ) {

	var view = NF_Marionette.ItemView.extend({
		tagName: "nf-section",
		template: "#tmpl-nf-before-form",

	});

	return view;
} );