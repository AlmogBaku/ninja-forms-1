define( ['models/repeatedField'], function( repeatedField ) {
	var collection = Backbone.Collection.extend( {
		model: repeatedField
	} );
	return collection;
} );