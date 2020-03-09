import React from 'react';

import FormsSubmissionsTable from './form-submissions-table'
import Styles from './form-submissions-table.styles.js'

export default { title: 'Submissions Table' };

const attributes = {
    formId: 1,
    selectedFields: [ 1, 2 ]
}

export const example = () => (
    <Styles>
        <code>{JSON.stringify(attributes)}</code>
        <FormsSubmissionsTable formId={attributes.formId} selectedFields={attributes.selectedFields} />
    </Styles>
);