import React from 'react';
import {Spinner} from '@wordpress/components'

/**
 * Display a form preview in an iframe
 */
export default  function FormPreviewIFrame({formId,siteUrl,previewToken}){
    const iFrameRef = React.useRef();
    const [isLoading,setIsLoading] = React.useState(true);
    const [height,setHeight] = React.useState('');
    const [width,setWidth] = React.useState('');
    const onLoad = () => {
        //Remove loading spinner
        setIsLoading(false);
            //Find form in iFrame
            const form = iFrameRef.current.contentWindow.document.getElementById(`nf-form-${formId}-cont`);
            //Try to find form wrap in iframe
            const formWrap = form.querySelectorAll( '.ninja-forms-form-wrap' );
            //If found wrap, set iFrame height and width to that.
            if( formWrap && formWrap.length){
                setWidth(formWrap[0].scrollWidth);
                setHeight(formWrap[0].scrollHeight);
            }
            //else, use form
            else{
                    setWidth(form.scrollWidth);
                    setHeight(form.scrollHeight);
            }

    };
    return (
        <div className={'nf-iframe-container'}>
            <div className={'nf-iframe-overlay'}>
                {isLoading && <Spinner/>}
                <iframe onLoad={onLoad}
                        scrolling={'no'}
                        style={{
                            width: 'initial'
                        }}
                        width={width ? width : 'auto'}
                        src={`${siteUrl}?nf_preview_form=${formId}&nf_iframe=${previewToken}`}
                        height={height ? height : 0}
                        ref={ref=>iFrameRef.current = ref} />
            </div>
        </div>
    )

}
