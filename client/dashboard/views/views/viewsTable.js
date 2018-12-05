/**
 * Forms Widget Table View
 *
 * @package Ninja Forms
 * @subpackage Dashboard
 * @copyright (c) 2017 WP Ninjas
 * @since 3.2
 */
define( [
    'views/views/viewsTableBody',
    'views/views/viewsTableLoading',
    'models/viewCollection'
], function( TableBodyView, TableLoadingView, ViewsCollection ) {
    var view = Marionette.View.extend( {
        template: "#tmpl-nf-views-table",
        className: 'nf-table-display',
        tagName: 'table',

        initialize: function(){
            var viewsTable = this;
            this.listenTo( nfRadio.channel( 'dashboard' ), 'fetch:views', function( collection ){
                viewsTable.showChildView( 'body', new TableBodyView( { collection: collection } ) );
            });
        },

        regions: {
            body: {
                el: 'tbody',
                replaceElement: true
            }
        },

        ui: {
            sortable: '.sortable',
            body: 'tbody',
            action2: '.action2',
        },

        onRender: function() {
            var collection = nfRadio.channel( 'dashboard' ).request( 'get:views' );
            if( 'undefined' == typeof collection ) {
                this.showChildView( 'body', new TableLoadingView());
            } else {
                this.showChildView( 'body', new TableBodyView( { collection: collection } ) );
            }
        },

        events: {
//            'click @ui.sortable': 'sortViewsTable',
        },

        sortViewsTable: function( event ){
            this.getUI( 'sortable' ).removeClass( 'sorted-asc' );
            this.getUI( 'sortable' ).removeClass( 'sorted-desc' );
            var sortBy = jQuery( event.target ).data( 'sort' );
            var reverse = jQuery( event.target ).data( 'reverse' ) || 0;
            if( reverse ){
                jQuery( event.target ).addClass( 'sorted-desc' );
                jQuery( event.target ).removeClass( 'sorted-asc' );
            } else {
                jQuery( event.target ).addClass( 'sorted-asc' );
                jQuery( event.target ).removeClass( 'sorted-desc' );
            }

            var collection = this.getChildView( 'body' ).collection;

            collection.comparator = function( a, b ) {
                name1 = a.get( sortBy ).toLowerCase();
                name2 = b.get( sortBy ).toLowerCase();

                if ( name1 < name2 ) {
                    ret = -1;
                } else if ( name1 > name2 ) {
                    ret = 1;
                } else {
                    ret = 0;
                }

                if( reverse ){
                    ret = -ret;
                }
                return ret;
            }
            collection.sort();

            if( reverse ){
                collection.models.reverse();
                jQuery( event.target ).data( 'reverse', 0 );
            } else {
                jQuery( event.target ).data( 'reverse', 1 );
            }
        },
        
    } );
    return view;
} );
