export const FormFields = (forms, formId) => {
    return Object.values(forms[formId].formFields)
}

export const FormSubmissions = (forms, formId) => {
    return Object.values(forms[formId].submissions)
}

export const SelectedFormFields = (forms, selectedFields, formId) => {
    return FormFields(forms, formId).filter((field) => {
        return -1 !== selectedFields.indexOf(field.fieldId)
    })
}