import React from "react";
import { Spinner } from "@wordpress/components";
import PropTypes from "prop-types";
/**
 * Display a form preview in an iframe
 */
export default function FormPreviewIFrame({ formId, siteUrl, previewToken }) {
	//Reference to element with iFrame
	const iFrameRef = React.useRef();
	//track loading state of iframe so we can show a spinner
	const [isLoading, setIsLoading] = React.useState(true);
	//The height that the iFrame should have.
	const [height, setHeight] = React.useState(0);
	//The width that the iFrame should have.
	const [width, setWidth] = React.useState("auto");

	//On load callback for iFrame
	const onLoad = () => {
		//Remove loading spinner
		setIsLoading(false);
		//Find form in iFrame
		const form = iFrameRef.current.contentWindow.document.getElementById(
			`nf-form-${formId}-cont`
		);
		//Try to find form wrap in iframe
		const formWrap = form.querySelectorAll(".ninja-forms-form-wrap");
		//If found wrap, set iFrame height and width to that.
		if (formWrap && formWrap.length) {
			setWidth(formWrap[0].scrollWidth);
			setHeight(formWrap[0].scrollHeight);
		}
		//else, use form
		else {
			setWidth(form.scrollWidth);
			setHeight(form.scrollHeight);
		}
	};
	return (
		<div className={"nf-iframe-container"}>
			<div className={"nf-iframe-overlay"}>
				{isLoading && <Spinner />}
				<iframe
					//URL includes set form Id and nonce
					src={`${siteUrl}?nf_preview_form=${formId}&nf_iframe=${previewToken}`}
					//Capture ref
					ref={ref => (iFrameRef.current = ref)}
					style={{
						//WordPress sets 100% width on iFrames, this overrides that.
						width: "initial"
					}}
					onLoad={onLoad}
					scrolling={"no"}
					height={height ? height : 0}
					width={width ? width : "auto"}
				/>
			</div>
		</div>
	);
}

FormPreviewIFrame.propTypes = {
	formId: PropTypes.string.isRequired,
	siteUrl: PropTypes.string.isRequired,
	previewToken: PropTypes.string.isRequired
};
