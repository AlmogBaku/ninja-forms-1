import React from "react";
/**
 * Hook to manage a simple collection of forms.
 *
 * Forms are an array of {formId: String, formTitle: String} objects.
 */
export default function(props) {
	const [forms] = React.useState(() => {
		return Array.isArray(props.forms)
			? props.forms
			: Object.values(props.forms);
	});
	/**
	 * Get the title of a form, by id.
	 */
	const getFormTitle = formId => {
		if (!forms.length) {
			return undefined;
		}
		let form = forms.find(f => f.formId === formId);
		if (form) {
			return form.formTitle;
		}
		return undefined;
	};

	/**
	 * Get forms as array of ooptions for select control
	 */
	const asSelectOptions = () => {
		if (!forms.length) {
			return [];
		}
		return forms.map(form => {
			return {
				label: form.formTitle,
				value: form.formId
			};
		});
	};

	return {
		getFormTitle,
		asSelectOptions
	};
}
