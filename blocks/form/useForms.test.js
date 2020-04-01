import useForms from "./useForms";
import { renderHook } from "@testing-library/react-hooks";
import forms from "./forms.fixture.js";
describe("use forms hook", () => {
	it("gets form title by id", () => {
		const { result } = renderHook(() => {
			useForms({ forms: Object.values(forms) });
		});
		expect(result.current.getFormTitle(forms[1].formId)).toEqual(
			forms[1].formTitle
		);
	});

	it("gets undefined when searching for title with a non-existant id", () => {
		const { result } = renderHook(() => {
			useForms({ forms: Object.values(forms) });
		});
		expect(result.current.getFormTitle(42)).toEqual(undefined);
	});

	it("gets forms for select field options", () => {
		const { result } = renderHook(() => {
			useForms({ forms: Object.values(forms) });
		});
		expect(result.current.asSelectOptions().length).toEqual(2);
		expect(result.current.asSelectOptions()[0].label).toEqual(
			forms[0].formTitle
		);
		expect(result.current.asSelectOptions()[0].value).toEqual(forms[0].formId);
		expect(result.current.asSelectOptions()[1].label).toEqual(
			forms[1].formTitle
		);
		expect(result.current.asSelectOptions()[1].value).toEqual(forms[1].formId);
	});
});
