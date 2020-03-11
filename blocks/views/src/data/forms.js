// export const FormFields = (forms, formId) => {
//     return Object.values(forms[formId].formFields)
// }

// export const FormSubmissions = (forms, formId) => {
//     return Object.values(forms[formId].submissions)
// }

// export const SelectedFormFields = (forms, selectedFields, formId) => {
//     return FormFields(forms, formId).filter((field) => {
//         return -1 !== selectedFields.indexOf(field.fieldId)
//     })
// }
export default {
    1: {
        formId: 1,
        formTitle: 'Test Form',
        fields: {
            1: {
                id: 1,
                type: 'textbox',
                label: 'First Name'
            },
            2: {
                id: 2,
                type: 'textbox',
                label: 'Last Name',
            }
        },
        submissions: [
            {
                1: 'Taylor',
                2: 'Swift',
            },
            {
                1: 'Meghan',
                2: 'Trainor',
            },
        ]
    }
}