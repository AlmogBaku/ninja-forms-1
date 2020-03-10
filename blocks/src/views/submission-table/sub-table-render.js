import { render } from '@wordpress/element';
import apiFetch from '@wordpress/api-fetch';
import FormsSubmissionsTable from '../../components/table-view'

import { createAuthMiddleware } from '../../data/middleware'
apiFetch.use( createAuthMiddleware( window.ninjaFormsViews.token ) )

import Store from '../../data/store'
import { withSelect, registerStore, select } from '@wordpress/data';
registerStore('ninja-forms-views', Store)

for (const root of document.getElementsByClassName('ninja-forms-views-submissions-table') ) {

    const {
        formId,
        selectedFields,
    } = JSON.parse(root.dataset.attributes)

    const AsyncFormsSubmissionsTable = withSelect((select) => {
        return {
            fields: select( 'ninja-forms-views' ).getFormFields( formId ),
            submissions: select( 'ninja-forms-views' ).getFormSubmissions( formId ),
        }
    })(({fields, submissions}) => {
        if(!fields || !submissions) return 'Loading again...'
        return <FormsSubmissionsTable
            formId={formId}
            selectedFields={selectedFields}
            fields={Object.values(fields)}
            submissions={Object.values(submissions)}
        />
    })

    render(<AsyncFormsSubmissionsTable />, root)
}
