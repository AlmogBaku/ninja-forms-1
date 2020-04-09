/*
 * Handles setting up our template list.
 *
 * Holds a collection of our templates.
 * Replies to requests for template data.
 * Updates template models.
 */
define([ 'models/formTemplateModel', 'models/formTemplateCollection' ], function( TemplateModel, TemplateCollection ) {
	var controller = Marionette.Object.extend( {
		initialize: function() {
            this.templates = new TemplateCollection();
            this.installed = new Backbone.Collection();
            this.available = new Backbone.Collection();

            nfRadio.channel( 'dashboard' ).reply( 'get:formTemplates', this.getTemplates, this );
            nfRadio.channel( 'dashboard' ).reply( 'get:installedFormTemplates', this.getInstalledTemplates, this );
            nfRadio.channel( 'dashboard' ).reply( 'get:availableFormTemplates', this.getAvailableTemplates, this );
            this.listenTo( nfRadio.channel('dashboard'), 'fetch:formTemplates', this.sortTemplates, this );

			this.templates.fetch({
				success: function( collection ){
                    nfRadio.channel( 'dashboard' ).trigger( 'fetch:formTemplates', collection );
				}
            });

		},

		getTemplates: function() {
			return this.templates;
        },

        getInstalledTemplates: function() {
            return this.installed;
        },

        getAvailableTemplates: function() {
            return this.available;
        },

        sortTemplates: function( collection ) {
            var that = this;
            collection.each(function(model){
                if ( 'undefined' == typeof model.get('type') ) {
                    return;
                } else if ( 'ad' == model.get('type') ) {
                    that.available.add( new Backbone.Model( model.toJSON() ) );
                } else {
                    that.installed.add( new Backbone.Model( model.toJSON() ) );
                }
            });
            nfRadio.channel( 'dashboard' ).trigger( 'sort:installedFormTemplates', that.installed );
            nfRadio.channel( 'dashboard' ).trigger( 'sort:availableFormTemplates', that.available );
        }

	});

	return controller;
} );