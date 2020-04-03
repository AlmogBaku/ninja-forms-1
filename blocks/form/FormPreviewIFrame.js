import React from 'react';

/**
 * Display a form preview in an iframe
 */
export default  function FormPreviewIFrame({formId,siteUrl}){

    return (
        <div className={'nf-iframe-container'}>
            <div className={'nf-iframe-overlay'}>
                <iframe src={`${siteUrl}?nf_preview_form=${formId}&nf_iframe=1`} style={{ height: '0', width: '500', scrolling: 'no'}} />
            </div>
        </div>
    )

}
