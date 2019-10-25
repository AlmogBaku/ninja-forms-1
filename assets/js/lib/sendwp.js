function ninja_forms_sendwp_remote_install() {
    var data = {
        'action': 'ninja_forms_sendwp_remote_install',
    };
    
    // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
    jQuery.post(ajaxurl, data, function(response) {
        var data = JSON.parse(response);
        /*
         * If we're on the builder page, then we want to override the redirect so that the user is brought back to this form.
         */
        if ( 'undefined' !== typeof nfAdmin.formID ) {
          data.client_redirect = window.location.href;
        }

        ninja_forms_sendwp_register_client(data.register_url, data.client_name, data.client_secret, data.client_redirect, data.partner_id);
    });
}

function ninja_forms_sendwp_register_client(register_url, client_name, client_secret, client_redirect, partner_id) {

    var form = document.createElement("form");
    form.setAttribute("method", 'POST');
    form.setAttribute("action", register_url);

    function ninja_forms_sendwp_append_form_input(name, value) {
        var input = document.createElement("input");
        input.setAttribute("type", "hidden");
        input.setAttribute("name", name);
        input.setAttribute("value", value);
        form.appendChild(input);
    }

    ninja_forms_sendwp_append_form_input('client_name', client_name);    
    ninja_forms_sendwp_append_form_input('client_secret', client_secret);
    ninja_forms_sendwp_append_form_input('client_redirect', client_redirect);   
    ninja_forms_sendwp_append_form_input('partner_id', partner_id);    
    
    document.body.appendChild(form);
    form.submit();
}
    
var builderModal = {
    init: function() {
      Backbone.Radio.channel( 'setting-sendwp_promo' ).on( 'render:setting', this.setupModal );
    },

    setupModal: function( settingModel, dataModel, view ) {
        var sendwpModal = {};

        var data = {
          width: 450,
          closeOnClick: 'body',
          closeOnEsc: true,
          content: '<p><h2>Frustrated that WordPress email isn’t being received?</h2><p>Form submission notifications not hitting your inbox? Some of your visitors getting form feedback via email, others not? By default, your WordPress site sends emails through your web host, which can be unreliable. Your host has spent lots of time and money optimizing to serve your pages, not send your emails.</p><h3>Sign up for SendWP today, and never deal with WordPress email issues again!</h3><p>SendWP is an email service that removes your web host from the email equation.</p><ul style=&quot;list-style-type:initial;margin-left: 20px;&quot;><li>Sends email through dedicated email service, increasing email deliverability.</li><li>Keeps form submission emails out of spam by using a trusted email provider.</li><li>On a shared web host? Don’t worry about emails being rejected because of blocked IP addresses.</li><li><strong>$1 for the first month. $9/month after. Cancel anytime!</strong></li></ul></p><br />',
          createOnInit: false,
          btnPrimary: {
            text: 'Sign me up!',
            callback: function() {
              var spinner = document.createElement('span');
              spinner.classList.add('dashicons', 'dashicons-update', 'spin');
              var w = this.offsetWidth;
              this.innerHTML = spinner.outerHTML;
              this.style.width = w+'px';
              /**
               * Publish our form before we start installing SendWP
               * If we don't have any unsaved changes, begin installation.
               * Otherwise, register a publish response listener and then request a publish.
               */
              if ( Backbone.Radio.channel( 'app' ).request( 'get:setting', 'clean' ) ) {
                ninja_forms_sendwp_remote_install();
              } else {
                Backbone.Radio.channel( 'app' ).on( 'response:updateDB', ninja_forms_sendwp_remote_install );
                Backbone.Radio.channel( 'app' ).request( 'update:db', 'publish' ); 
              }

            }
          },
          btnSecondary: {
            text: 'Cancel',
            callback: function() {
              sendwpModal.toggleModal(false);
            }
          }
        }
        
        jQuery( view.el ).find( '.nf-send-wp-promo' ).click( function( e ) {
            sendwpModal = new NinjaModal( data );
        } );
    }

};

jQuery( document ).ready( function() {
    builderModal.init();
} );