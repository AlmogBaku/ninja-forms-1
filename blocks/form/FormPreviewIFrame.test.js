import React from 'react';
import FormPreviewIFrame from './FormPreviewIFrame';
import {render} from "@testing-library/react";
//Probably need to run this in e2e tests
test.skip( "matches spanpshot",() => {
    const {container} = render(<FormPreviewIFrame formId={2} siteUrl={'https://ninjaforms.com'}/>);
    expect(container).toMatchSnapshot();
});
