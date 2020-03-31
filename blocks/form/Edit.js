import React from 'react';
import { Placeholder, ServerSideRender, SelectControl } from '@wordpress/components';
import {
    InspectorControls,
} from '@wordpress/block-editor';
const ChooseForm = ({formId,forms,chooseForm})=> {
    const options = React.useMemo( () => {
        let opts = Object.values(forms).map( ({formId,formTitle}) => {
                return {label:formTitle,value:formId}
        });

        return [...[{label: '---', }],...opts];
    }, [forms]);
    return <SelectControl
        label={'Form'}
        value={formId}
        onChange={onChange}
        options={options}

    />
}
export default  function Edit(formId,forms,onChange) {

    const formTitle = React.useMemo( () => {
        const form = Object.values(forms).find( (form) =>
            form.formID === formId
        );
        return form ?  form.formTitle : '';
    },[forms,formId]);
    if( formId){
       return( <Placeholder>
            <ChooseForm formId={formId} forms={forms} onChange={onChange} />
        </Placeholder>
       );
    }

    return  (
        <React.Fragment>
            <InspectorControls>
                <ChooseForm formId={formId} forms={forms} onChange={onChange} />
            </InspectorControls>
            <ServerSideRender
                block="ninja-forms/form"
                attributes={ {
                    formId,
                    formTitle
                } }
            />
        </React.Fragment>

    )

}