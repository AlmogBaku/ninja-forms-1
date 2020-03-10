import apiFetch from '@wordpress/api-fetch';

const DEFAULT_STATE = { forms: false, fields: {}, submissions: {} }

const actions = {
    setForms( forms ) {
        return {
            type: 'SET_FORMS',
            forms,
        }
    },
    setFields( formId, fields ) {
        return {
            type: 'SET_FIELDS',
            formId,
            fields,
        }
    },
    setSubmissions( formId, submissions, page ) {
        return {
            type: 'SET_SUBMISSIONS',
            formId,
            submissions,
            page,
        }
    },
    fetchFromAPI( path, data ) {
        return {
            type: 'FETCH_FROM_API',
            path,
            data,
        }    
    },
}

const selectors = {
    getForms( state ) {
        const { forms } = state;
        return forms;
    },
    getForm( state, formId ) {
        const forms = selectors.getForms( state )
        return Object.values( forms )
                .find((form) => form.formId == formId )
    },
    getFormFields( state, formId ) {
        const { fields } = state;
        return fields[formId];
    },
    getFormSubmissions( state, formId ) {
        const { submissions } = state
        return submissions[formId]
    },
    getFormSubmissionsPage( state, formId, page ) {
        const { submissions } = state
        if('undefined' !== typeof submissions[formId] ){
            return submissions[formId][page]
        }
        return false
    }
}

export default {
    reducer( state = DEFAULT_STATE, action ) {
        switch ( action.type ) {
            case 'SET_FORMS':
                return {
                    ...state,
                    forms: action.forms,
                };
            case 'SET_FIELDS':
                return {
                    ...state,
                    fields: {
                        ...state.fields,
                        [ action.formId ]: action.fields
                    }
                };
            case 'SET_SUBMISSIONS':
                return {
                    ...state,
                    submissions: {
                        ...state.submissions,
                        [ action.formId ]: {
                            ... state.submissions[ action.formId ],
                            [action.page]: action.submissions
                        }
                    }
                }
        }
        return state;
    },
    actions,
    selectors,
    controls: {
        FETCH_FROM_API( action ) {
            return apiFetch( { path: action.path } );
        },
    },
    resolvers: {
        * getForms() {
            const path = '/ninja-forms-views/forms';
            const forms = yield actions.fetchFromAPI( path );
            return actions.setForms( forms );
        },
        * getFormFields( formId ) {
            const path = '/ninja-forms-views/forms/' + formId + '/fields';
            const fields = yield actions.fetchFromAPI( path );
            return actions.setFields( formId, fields );
        },
        * getFormSubmissions( formId ) {
            const path = '/ninja-forms-views/forms/' + formId + '/submissions?page=1&perPage=10';
            const submissions = yield actions.fetchFromAPI( path );
            return actions.setSubmissions( formId, submissions, 1 );
        },
        * getFormSubmissionsPage( formId, page ) {
            const path = '/ninja-forms-views/forms/' + formId + '/submissions?page=' + page + '&perPage=10';
            const submissions = yield actions.fetchFromAPI( path );
            return actions.setSubmissions( formId, submissions, page );
        }
    },
}

