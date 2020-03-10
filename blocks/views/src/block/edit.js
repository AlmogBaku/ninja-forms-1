// import forms from '../data/forms'
import React, { Fragment } from "react";
import { select } from '@wordpress/data';
import {
    BlockAlignmentToolbar,
    BlockControls,
    InspectorControls,
} from '@wordpress/block-editor';
import {
    ToggleControl,
    PanelBody,
} from '@wordpress/components';
import {
    Placeholder,
    TableStyles,
    FormSelect,
    FieldCheckboxList
} from "./components"

import FormsSubmissionsTable from '../components/table-view'

export const Edit = (props) => {

    // return <pre>{JSON.stringify(props, null, 2)}</pre>
    // const newSelectedFields = form.formFields.slice(0, 5).map((field) => field.fieldId)

    const forms = props.forms;
    const fields = Object.values(props.fields);
    const submissions = Object.values(props.submissions);
    const selectedFields = ('undefined' !== typeof props.attributes.selectedFields ) ? props.attributes.selectedFields : []

    /**
     * Alignment Change Handler
     * 
     * @note Alignment can be "unset", which is represented by the value 'none';
     * 
     * @param {string} newAlignment 
     */
    const onChangeAlignment = ( newAlignment ) => {
        props.setAttributes( { alignment: newAlignment === undefined ? 'none' : newAlignment } );
    };

    /**
     * Field Change Handler
     * 
     * Adds or Removes a field to/from the selectedFields attribute.
     * 
     * @param {bool } isChecked 
     * @param {*} field
     */
    const onFieldChange = ( isChecked, changedFieldId ) => {
        const newSelectedFields = (isChecked)
            ? selectedFields.concat([changedFieldId])
            : selectedFields.filter((fieldId) => fieldId != changedFieldId )
        props.setAttributes( { selectedFields: newSelectedFields } )
    }

    // const formFields = props.attributes.formId ? Object.values(forms[props.attributes.formId].formFields) : []

    return <Fragment>
        {props.attributes.formId && (
            <TableStyles>
                <FormsSubmissionsTable {...props.attributes} selectedFields={selectedFields} fields={fields} submissions={submissions} />
            </TableStyles>
        )}
        {props.isSelected && (
            <BlockControls>
                <BlockAlignmentToolbar
                    value={ props.attributes.alignment }
                    onChange={ onChangeAlignment }
                />
            </BlockControls>
        )}
        {props.isSelected && (
            <InspectorControls>
                <PanelBody title="Fields" initialOpen={ true }>
                    <FieldCheckboxList {...{
                        fields: fields,
                        isChecked: (fieldId) => -1 !== selectedFields.indexOf(fieldId),
                        onChange: onFieldChange
                    }} />
                </PanelBody>
            </InspectorControls>
        )}
    </Fragment>
}