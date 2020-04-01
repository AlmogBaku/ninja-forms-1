import Edit from "./Edit";
import block from "./block";
import { __ } from "@wordpress/i18n";
import { registerBlockType } from "@wordpress/blocks";

//Saved forms should have been set in this variable using wp_localize_script
const nfFormsBlock = window.nfFormsBlock || {};
const { forms } = nfFormsBlock;
registerBlockType("ninja-forms/form", {
	...block,
	title: __("Ninja Form", "ninja-forms"),
	edit: ({ attributes, setAttributes }) => {
		return (
			<Edit
				forms={forms}
				formId={attributes.formId}
				formTitle={attributes.formTitle}
				setAttributes={setAttributes}
			/>
		);
	},
	save: () => null
});
