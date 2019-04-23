define( [], function( ) {

	var view = NF_Marionette.ItemView.extend({
		tagName: "nf-section",
		template: "#tmpl-nf-after-form",
		
	});

	return view;
} );