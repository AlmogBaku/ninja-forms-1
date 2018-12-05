/**
 * Forms Widget Table Body Collection View
 *
 * @package Ninja Forms
 * @subpackage Dashboard
 * @copyright (c) 2017 WP Ninjas
 * @since 3.2
 */
define( [
    'views/views/viewsTableRow',
    'views/views/viewsTableEmpty'
], function( TableRowView, TableEmptyView ) {
    var view = Marionette.CollectionView.extend( {
        childView: TableRowView,
        emptyView: TableEmptyView,
        className: 'views-collection',
        tagName: 'tbody',

        initialize: function(){
//            this.listenTo( nfRadio.channel( 'widget-forms' ), 'update:filter', this.updateFilter );
        },

        updateFilter: function( term ){
            this.setFilter(function (child, index, collection) {
                return 0 <= child.get( 'title' ).toLowerCase().indexOf( term.toLowerCase() );
            });
        }
    } );
    return view;
} );
