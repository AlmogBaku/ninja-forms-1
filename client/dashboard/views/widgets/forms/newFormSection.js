/**
 * Forms Widget Template Section View
 *
 * @package Ninja Forms
 * @subpackage Dashboard
 * @copyright (c) 2017 WP Ninjas
 * @since 3.4.25
 */
define( [
    'models/formTemplateCollection',
    'views/widgets/forms/newFormTemplate'
], function( TemplateCollection, TemplateView ) {
    var view = Marionette.CollectionView.extend( {
        tagName: 'div',
        className: 'template-list',
        childView: TemplateView,

        initialize: function(){
            this.listenTo( nfRadio.channel( 'widget-forms' ), 'update:filter', this.updateFilter );
        },

        updateFilter: function( term ){
            if ( nfi18n.noResult === this.el.innerHTML ) {
                this.el.innerHTML = '';
            }
            this.setFilter(function (child, index, collection) {
                return 0 <= child.get( 'title' ).toLowerCase().indexOf( term.toLowerCase() );
            });
            if ( this.children.length == 0 ) {
                this.el.innerHTML = nfi18n.noResult;
            }
        }
    } );
    return view;
} );
