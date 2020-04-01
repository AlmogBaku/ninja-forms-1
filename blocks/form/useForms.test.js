import useForms from "./useForms";
import { renderHook } from "@testing-library/react-hooks";
import forms from "./forms.fixture.js";
describe("use forms hook", () => {
	it("gets form title by id", () => {
		const { result } = renderHook(() => {
			return useForms({ forms: Object.values(forms) });
		});
		expect(result.current.getFormTitle(forms[1].formId)).toEqual(
			forms[1].formTitle
		);
	});

	it("Converts object to array", () => {
		const { result } = renderHook(() => {
			return useForms({ forms });
		});
		expect(result.current.getFormTitle(forms[1].formId)).toEqual(
			forms[1].formTitle
		);
	});

	it("gets undefined when searching for title with a non-existant id", () => {
		const { result } = renderHook(() => {
			return useForms({ forms: Object.values(forms) });
		});
		expect(result.current.getFormTitle(42)).toEqual(undefined);
	});

	it("gets forms for select field options", () => {
		const { result } = renderHook(() => {
			return useForms({ forms: Object.values(forms) });
		});
		expect(result.current.asSelectOptions().length).toEqual(4);

		expect(result.current.asSelectOptions()[2].label).toEqual("Unreal Form");
		expect(result.current.asSelectOptions()[1].value).toEqual(2);
	});

	it("Always compares form ids as number", () => {
		const forms = {
			2: {
				formId: 2,
				formTitle: "Clay"
			},
			2: {
				formId: 2,
				formTitle: "Distortions"
			}
		};
		const { result } = renderHook(() => {
			return useForms({ forms });
		});
		//Pass as string
		expect(result.current.getFormTitle("2")).toEqual("Distortions");
	});
});
