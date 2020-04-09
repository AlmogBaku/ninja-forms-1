import React from "react";
import { SelectControl, Placeholder } from "@wordpress/components";
import { InspectorControls } from "@wordpress/block-editor";
import useForms from "./useForms";
import FormPreviewIFrame from "./FormPreviewIFrame";
import { NinjaIcon } from "./icon";
import PropTypes from "prop-types";

const BlockPlaceholder = props => {
	return (
		<Placeholder
			icon={<div style={{ marginRight: "10px" }}>{NinjaIcon}</div>}
			label="Ninja Form"
			instructions="Display a form"
			style={{ alignItems: "center" }}
		>
			<div
				style={{
					margin: "auto",
					width: "50%"
				}}
			>
				{props.children}
			</div>
		</Placeholder>
	);
};

BlockPlaceholder.propTypes = {
	children: PropTypes.element.isRequired
};

/**
 * Form chooser select control
 */
export const ChooseForm = ({ formId, forms, onChange, labelText }) => {
	const { asSelectOptions } = useForms({ forms });
	const options = React.useMemo(() => {
		let opts = asSelectOptions();
		return [...[{ label: "---" }], ...opts];
	}, [forms, asSelectOptions]);
	return (
		<SelectControl
			label={labelText ? labelText : "Select Form"}
			value={formId}
			onChange={onChange}
			options={options}
		/>
	);
};

ChooseForm.propTypes = {
	formId: PropTypes.oneOfType([PropTypes.string, PropTypes.number]),
	forms: PropTypes.oneOfType([PropTypes.array, PropTypes.object]).isRequired,
	onChange: PropTypes.func.isRequired,
	labelText: PropTypes.string
};

/**
 * Ninja Forms block UI
 */
export default function Edit({
	formId,
	forms,
	labelText,
	setAttributes,
	siteUrl,
	previewToken
}) {
	const { getFormTitle } = useForms({ forms });
	const updateChosenForm = formId => {
		const formTitle = formId ? getFormTitle(formId) : "";
		setAttributes({
			formId: formId,
			formTitle
		});
	};

	if (!formId) {
		return (
			<BlockPlaceholder>
				<ChooseForm
					label={labelText ? labelText : "Select Form"}
					formId={formId}
					forms={forms}
					onChange={updateChosenForm}
					labelText={labelText}
				/>
			</BlockPlaceholder>
		);
	}

	return (
		<React.Fragment>
			<InspectorControls>
				<ChooseForm
					formId={formId}
					forms={forms}
					onChange={updateChosenForm}
					labelText={labelText}
				/>
			</InspectorControls>
			<FormPreviewIFrame
				siteUrl={siteUrl}
				previewToken={previewToken}
				formId={formId}
			/>
		</React.Fragment>
	);
}

Edit.propTypes = {
	formId: PropTypes.oneOfType([PropTypes.string, PropTypes.number]),
	forms: PropTypes.oneOfType([PropTypes.array, PropTypes.object]).isRequired,
	labelText: PropTypes.string,
	setAttributes: PropTypes.func.isRequired,
	siteUrl: PropTypes.string.isRequired,
	previewToken: PropTypes.string.isRequired
};
