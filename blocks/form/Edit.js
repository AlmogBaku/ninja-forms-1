import {
	Placeholder,
	SelectControl,
} from "@wordpress/components";
import { InspectorControls } from "@wordpress/block-editor";
import useForms from "./useForms";
import FormPreviewIFrame from "./FormPreviewIFrame";
import {ServerSideRender} from "@wordpress/server-side-render";

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
export default function Edit({
	formId,
	forms,
	setAttributes,
	labelText,
	siteUrl,
 	previewToken
}) {
	const { getFormTitle } = useForms({ forms });
	const formTitle =  formId ? getFormTitle(formId) : '';
	const updateChosenForm = formId => {
		setAttributes({
			formId: formId,
			formTitle
		});
	};

	if (!formId) {
		return (
			<Placeholder>
				<ChooseForm
					label={labelText ? labelText : "Choose Form"}
					formId={formId}
					forms={forms}
					onChange={updateChosenForm}
					labelText={labelText}
				/>
			</Placeholder>
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
			<FormPreviewIFrame siteUrl={siteUrl} previewToken={previewToken} formId={formId} />
		</React.Fragment>
	);
}
