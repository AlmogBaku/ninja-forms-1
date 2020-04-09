/**
 * Forms Widget Templates View
 *
 * @package Ninja Forms
 * @subpackage Dashboard
 * @copyright (c) 2017 WP Ninjas
 * @since 3.4.25
 */
define( [
    'views/widgets/forms/newFormSection'
], function( SectionView ) {
    var view = Marionette.View.extend( {
        template: "#tmpl-nf-widget-templates-grid",
        className: 'nf-template-grid',
        tagName: 'div',

        initialize: function(){
            var templateGrid = this;
            this.listenTo( nfRadio.channel( 'dashboard' ), 'sort:installedFormTemplates', function( collection ){
                templateGrid.showChildView( 'installed', new SectionView( { collection: collection } ) );
            });
            this.listenTo( nfRadio.channel( 'dashboard' ), 'sort:availableFormTemplates', function( collection ){
                templateGrid.showChildView( 'available', new SectionView( { collection: collection } ) );
            });
        },

        regions: {
            installed: {
                el: '.installed',
                replaceElement: true
            },
            available: {
                el: '.available',
                replaceElement: true
            }
        },

        onRender: function() {
            var collection = nfRadio.channel( 'dashboard' ).request( 'get:formTemplates' );
            if( 'undefined' != typeof collection ) {
                this.showChildView( 'installed', new SectionView( { collection: nfRadio.channel('dashboard').request('get:installedFormTemplates') } ) );
                this.showChildView( 'available', new SectionView( { collection: nfRadio.channel('dashboard').request('get:availableFormTemplates') } ) );
            }
        },
        
    } );
    return view;
} );
