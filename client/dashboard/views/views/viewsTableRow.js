/**
 * Forms Widget Table Row View
 *
 * @package Ninja Forms
 * @subpackage Dashboard
 * @copyright (c) 2017 WP Ninjas
 * @since 3.2
 */
define( [], function() {
    var view = Marionette.View.extend( {
        template: "#tmpl-nf-views-table-row",
        tagName: 'tr',
        replaceElement: true,
        ui: {
            delete: '.delete',
//            duplicate: '.duplicate',
            edit: '.nf-item-edit'
        },
        events: {
            'click @ui.delete': function() {
                nfRadio.channel( 'dashboard' ).trigger( 'views:delete', this );
            },
//            'click @ui.duplicate': function() {
//                nfRadio.channel( 'dashboard' ).trigger( 'forms:duplicate', this );
//            },
            'click @ui.edit': function( event ) {
                this.$el.toggleClass( 'show-actions' ).siblings().removeClass( 'show-actions' );
            }
        },
    } );
    return view;
} );
