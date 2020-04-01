import React from "react";
import {
	Placeholder,
	ServerSideRender,
	SelectControl
} from "@wordpress/components";
import { InspectorControls } from "@wordpress/block-editor";
import useForms from "./useForms";

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
			label={labelText ? labelText : "Choose Form"}
			value={formId}
			onChange={onChange}
			options={options}
		/>
	);
};

/**
 * Ninja Forms block UI
 */
export default function Edit(formId, formTitle, forms, setAttributes) {
	const { getFormTitle } = useForms({ forms });
	const updateChosenForm = formId => {
		setAttributes({
			formId: formId,
			formTitle: getFormTitle(formId)
		});
	};

	return (
		<React.Fragment>
			<InspectorControls>
				<ChooseForm formId={formId} forms={forms} onChange={updateChosenForm} />
			</InspectorControls>
			<ServerSideRender
				block="ninja-forms/form"
				attributes={{
					formId,
					formTitle
				}}
			/>
		</React.Fragment>
	);
}
