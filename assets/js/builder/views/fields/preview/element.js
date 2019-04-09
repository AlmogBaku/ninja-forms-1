define( [], function() {
	var view = Marionette.ItemView.extend({
		tagName: 'div',
		template: '#tmpl-nf-field-input',

		initialize: function() {

			var type = this.model.get('type');
			
			console.log(this.model);

			if('phone' == type) type = 'tel';
			if('spam' == type) type = 'input';
			if('date' == type) type = 'input';
			if('confirm' == type) type = 'input';
			if('quantity' == type) type = 'number';
			if('terms' == type) type = 'listcheckbox';
			if('liststate' == type) type = 'listselect';
			if('listcountry' == type) type = 'listselect';
			if('listmultiselect' == type) type = 'listselect';

			this.template = '#tmpl-nf-field-' + type;
		},

		onRender: function() {
			if(this.model.get('container_class').includes('two-col-list')) {
				jQuery(this.el).find('> ul').css('display', 'grid');
				jQuery(this.el).find('> ul').css('grid-template-columns', 'repeat(2, 1fr)');
			}
			if(this.model.get('container_class').includes('three-col-list')) {
				jQuery(this.el).find('> ul').css('display', 'grid');
				jQuery(this.el).find('> ul').css('grid-template-columns', 'repeat(3, 1fr)');
			}
			if(this.model.get('container_class').includes('four-col-list')) {
				jQuery(this.el).find('> ul').css('display', 'grid');
				jQuery(this.el).find('> ul').css('grid-template-columns', 'repeat(4, 1fr)');
			}
		},
        
		templateHelpers: function () {
	    	return {
	    		renderClasses: function() {
	    			// ...
                },
                renderPlaceholder: function() {
                    if('undefined' == typeof this.placeholder) return;
					return 'placeholder="' + jQuery.trim( this.placeholder ) + '"';
                },
                maybeDisabled: function() {
                    if('undefined' == typeof this.disable_input) return;
                    if(!this.disable_input) return;
                    return 'disabled="disabled"';
                },
                maybeRequired: function() {
					// ...
				},
				maybeInputLimit: function() {
					// ...
				},
				maybeDisableAutocomplete: function() {
					// ..
				},
				maybeChecked: function() {
					// ...
				},
				renderOptions: function() {
					switch(this.type) {
						case 'terms':

							if( ! this.taxonomy ){
								return '(No taxonomy selected)';
							}

							var taxonomyTerms = fieldTypeData.find(function(typeData){
								return 'terms' == typeData.id;
							}).settingGroups.find(function(settingGroup){
								return 'primary' == settingGroup.id;
							}).settings.find(function(setting){
								return 'taxonomy_terms' == setting.name;
							}).settings;

							var attributes = Object.keys(this);
							var enabledTaxonomyTerms = attributes.filter(function(attribute){
								return 0 == attribute.indexOf('taxonomy_term_') && this[attribute];
							}.bind(this));

							if(0 == enabledTaxonomyTerms.length) {
								return '(No available terms selected)';
							}

							return enabledTaxonomyTerms.reduce(function(html, enabledTaxonomyTerm) {
								var term = taxonomyTerms.find(function(terms){
									return enabledTaxonomyTerm == terms.name;
								});
								return html += '<li><input type="checkbox"><div>' + term.label  + '</div></li>';
							}.bind(this), '');
						case 'liststate':
						case 'listselect':
							var options = this.options.models.filter(function(option){
								return option.get('selected');
							});
							if(0 == options.length) options = this.options.models;
							return '<option>' + options[0].get('label') + '</option>';
						case 'listmultiselect':
							return this.options.models.reduce(function(html, option) {
								return html += '<option>' + option.get('label')  + '</option>';
							}, '');
						case 'listcheckbox':
							return this.options.models.reduce(function(html, option) {
								return html += '<li><input type="checkbox"><div>' + option.get('label')  + '</div></li>';
							}, '');
						case 'listradio':
							return this.options.models.reduce(function(html, option) {
								return html += '<li><input type="radio"><div>' + option.get('label')  + '</div></li>';
							}, '');
						case 'listcountry':
							var defaultValue = this.default;
							var defaultOption = window.fieldTypeData.find(function(data) {
								return 'listcountry' == data.id;
							}).settingGroups.find(function(group){
								return 'primary' == group.id;
							}).settings.find(function(setting){
								return 'default' == setting.name;
							}).options.find(function(option) {
								return defaultValue == option.value;
							});
							var optionLabel = ('undefined' !== typeof defaultOption ) ? defaultOption.label : '--';
							return '<option>' + optionLabel + '</option>';
						default:
							return '';
					}
				},
				renderOtherAttributes: function() {
					var attributes = [];
					if('listmultiselect' == this.type) {
						attributes.push('multiple');
					}

					return attributes.join(' ');
				},
				renderProduct: function() {
					// ...
				},
				renderNumberDefault: function() {
					// ...
				},
				renderCurrencyFormatting: function() {
					// ...
				},
				renderRatings: function() {
					var ratingOutput = '';
					for (var i = 0; i < this.number_of_stars; i++) {
						ratingOutput += '<i class="fa fa-star" aria-hidden="true"></i>&nbsp;';
					  }
					return ratingOutput;
				}
            }
        }

	});

	return view;
} );