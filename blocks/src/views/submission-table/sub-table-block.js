import { __ } from '@wordpress/i18n';
import { registerBlockType } from '@wordpress/blocks';
import { withSelect, registerStore } from '@wordpress/data';
import { Placeholder, Spinner, SelectControl } from '@wordpress/components';

import metadata from './block/block.json'
import { Edit } from './block/edit'
import Store from '../../data/store'

import apiFetch from '@wordpress/api-fetch';
import { createAuthMiddleware } from '../../data/middleware'
apiFetch.use( createAuthMiddleware( window.ninjaFormsViews.token ) )

registerStore( 'ninja-forms-views', Store )
 
registerBlockType( 'ninja-forms/submissions-table', {
    title: 'Ninja Forms Submissions Table',
    icon: 'editor-table',
    category: 'common',
    ...metadata,
    //pass edit callback props to Editor component
    edit: withSelect( ( select ) => {
        return {
            forms: select( 'ninja-forms-views' ).getForms(),
        };
    } )((props) => {

        if(!props.attributes.formId) {

            if(!props.forms) return <Placeholder label={ __( 'Ninja Forms Submissions Table' ) }><Spinner /></Placeholder>

            const formOptions = Object.values(props.forms).map(function(form) {
                return { key: form.formId, label: form.formTitle, value: form.formId }
            })

            return <Placeholder>
                <SelectControl
                        label="Select a form"
                        onChange={ (newFormId) => props.setAttributes({ formId: newFormId }) }
                        options={ [{ key: 0, label: '-', value: 0 }].concat(formOptions) }
                    />
            </Placeholder>;
        }

        const WithFormFields = withSelect( ( select ) => ({
            fields: select( 'ninja-forms-views' ).getFormFields( props.attributes.formId ),
            submissions: select( 'ninja-forms-views' ).getFormSubmissions( props.attributes.formId ),
        }) )(({fields, submissions}) => {
            return (fields && submissions)
                ? <Edit {...{...props, fields, submissions}} />
                : <Placeholder label={ __( 'Loading Form Data' ) }><Spinner /></Placeholder>
        })

        return <WithFormFields />
    }),
    getEditWrapperProps( attributes ) {
        const { alignment } = attributes;
		if (
			'left' === alignment ||
			'center' === alignment ||
			'right' === alignment ||
			'wide' === alignment ||
			'full' === alignment
		) {
			return { 'data-align': alignment };
		}
	},
    //pass save callback props to Save component
    save: () => null
} );