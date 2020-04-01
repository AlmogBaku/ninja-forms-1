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

export default function Edit(formId, forms, onChange) {
	const { getFormTitle } = useForms({ forms });

	const formTitle = React.useMemo(() => {
		return getFormTitle(formId);
	}, [forms, formId, getFormTitle]);
	if (formId) {
		return (
			<Placeholder>
				<ChooseForm formId={formId} forms={forms} onChange={onChange} />
			</Placeholder>
		);
	}

	return (
		<React.Fragment>
			<InspectorControls>
				<ChooseForm formId={formId} forms={forms} onChange={onChange} />
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
