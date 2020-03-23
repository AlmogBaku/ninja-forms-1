import { __ } from '@wordpress/i18n';
import { registerBlockType } from '@wordpress/blocks';
import { withSelect, registerStore } from '@wordpress/data';
import { Placeholder, Spinner, SelectControl } from '@wordpress/components';
import ServerSideRender from "@wordpress/server-side-render";
import { createAuthMiddleware } from './data/middleware';
import Store from './data/store';
import {
    BlockAlignmentToolbar,
    BlockControls,
    InspectorControls,
} from '@wordpress/block-editor';

import metadata from './block/block.json'


import apiFetch from '@wordpress/api-fetch';
apiFetch.use( createAuthMiddleware( window.ninjaFormsBlock.token ) )

registerStore( 'ninja-forms-blocks', Store )
 
registerBlockType( 'ninja-forms/form-block', {
    title: 'Ninja Forms Form Block',
    icon: 'editor-table',
    category: 'common',
    ...metadata,
    //pass edit callback props to Editor component
    edit: withSelect( ( select ) => {
        return {
            forms: select( 'ninja-forms-blocks' ).getForms(),
        };
    } )((props) => {

        if(!props.attributes.formID) {

            if(!props.forms) return <Placeholder label={ __( 'Ninja Forms' ) }><Spinner /></Placeholder>

            const formOptions = Object.values(props.forms).map(function(form) {
                return { key: form.formId, label: form.formTitle, value: form.formId }
            })

            return <Placeholder>
                <SelectControl
                        label="Select a form"
                        onChange={ (newFormId) => props.setAttributes({ formID: newFormId }) }
                        options={ [{ key: 0, label: '-', value: 0 }].concat(formOptions) }
                    />
            </Placeholder>;
        }

        return (
            <ServerSideRender
                block="ninja-forms/form-block"
                attributes={{
                    formID: props.attributes.formID
                }}
            />
        )
    }),
    //pass save callback props to Save component
    save: () => null
} );