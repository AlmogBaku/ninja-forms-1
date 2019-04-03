define( [], function() {
	var model = Backbone.Model.extend( {
		defaults: {
			foo: 'bar',
		},
	} );
	return model;
} );
