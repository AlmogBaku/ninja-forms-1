import React from 'react';
import FormPreviewIFrame from './FormPreviewIFrame';
import {render} from "@testing-library/react";
//Probably need to run this in e2e tests
test.skip( "matches spanpshot",() => {
    const siteUrl  = 'https://ninjaforms.com';
    const previewToken = 'fff';
    const {container} = render(
        <FormPreviewIFrame
            formId={2}
            siteUrl={siteUrl}
            previewToken={previewToken}
        />
    );
    expect(container).toMatchSnapshot();
});
