define( [], function() {
    var view = NF_Marionette.ItemView.extend({
        tagName: 'nf-section',
        template: '#tmpl-nf-field-before'
    });

    return view;
} );