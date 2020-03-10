import { SelectControl } from '@wordpress/components';

export const FormSelect = ({forms, formId, onChange}) => {

    const formOptions = Object.values(forms).map(function(form) {
        return { key: form.formId, label: form.formTitle, value: form.formId }
    })

    return (<SelectControl
        label="Select a form"
        value={ formId }
        onChange={ onChange }
        options={ [{ key: 0, label: '-', value: 0 }].concat(formOptions) }
    />)
}