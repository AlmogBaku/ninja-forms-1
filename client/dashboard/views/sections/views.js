/**
 * @package Ninja Forms
 * @subpackage Dashboard
 * @copyright (c) 2017 WP Ninjas
 * @since 3.2
 */
define( [
    'views/views/viewsTable'
], function( ViewsTableView ) {
    var view = Marionette.View.extend( {
        
        template: '#tmpl-nf-views',
        
        regions: {
            content: '.content'
        },

        ui: {
            add: '.add'
        },

        initialize: function(){
            nfRadio.channel( 'views' ).reply( 'show:newViewModal', this.showNewViewModal, this );
        },
        
        onRender: function() {
            this.showChildView( 'content', new ViewsTableView() );
        },
        
        events: {
            'click @ui.add': 'showNewViewModal',
        },
        
        showNewViewModal: function(){
            // Save our context for callbacks.
            var context = this;
            // Get our forms collection.
            var collection = Backbone.Radio.channel( 'dashboard' ).request( 'get:forms' );
            var container = document.createElement( 'div' );
            var title = document.createElement( 'div' );
            title.classList.add( 'pick-a-form-title' );
            title.textContent = 'Select a form';
            container.appendChild( title );
            var list = document.createElement( 'select' );
            list.id = 'ninja-view-form-select';
            list.style.maxWidth = '300px';
            _.each( collection.models, function( model ) {
                var option = document.createElement( 'option' );
                option.textContent = model.get( 'title' );
                option.setAttribute( 'value', model.get( 'id' ) );
                list.appendChild( option );
            } );
            container.appendChild( list );

            var modalData = {
                content: container.innerHTML,
                closeOnClick: 'body',
                btnPrimary: {
                    text: 'Select',
                    callback: function( e ) {
                        var target = document.getElementById( 'ninja-view-form-select' ).value;
                        window.location = window.location.origin + window.location.pathname + '?page=ninja-forms-views&source_id=' + target;
                    }
                },
                btnSecondary: {
                    text: 'Cancel',
                    callback: function( e ) {
                        context.viewModal.toggleModal( false );
                        context.viewModal.destroy();
                    }
                }
            }
            this.viewModal = new NinjaModal( modalData );
        }
        
    } );
    return view;
} );
