import Edit, { ChooseForm } from "./Edit";
import { render, fireEvent } from "@testing-library/react";
import forms from "./forms.fixture.js";

describe("Form block Edit callback", () => {
	it.skip("Matches snapshot", () => {
		const onChange = jest.fn();

		const { container } = render(
			<Edit
				onChange={onChange}
				formId={forms["1"].formId}
				formTitle={forms["1"].formTitle}
				forms={forms}
			/>
		);
		expect(container).toMatchSnapshot();
	});

	it.skip("Calls onChange when changing form", () => {
		const onChange = jest.fn();
		const { getByLabelText } = render(
			<Edit
				onChange={onChange}
				formId={forms[1].formId}
				formTitle={forms[1].formTitle}
				forms={forms}
			/>
		);
		fireEvent.change(getByLabelText("Form"), {
			target: { value: "3" }
		});
		expect(onChange).toBeCalledTimes(1);
		expect(onChange).toBeCalledWith("3");
	});

	it.skip("Shows place holder when no formId passed", () => {
		const onChange = jest.fn();
		const { container } = render(<Edit onChange={onChange} forms={forms} />);
		expect(container).toBe("I do not know?");
	});

	it.skip("Shows serve-side-render when formId is passed", () => {
		const onChange = jest.fn();
		const { container } = render(
			<Edit
				onChange={onChange}
				formId={forms[0].formId}
				formTitle={forms[1].formTitle}
				forms={forms}
			/>
		);
		expect(container).toBe("I do not know?");
	});
});

describe("ChooseForm", () => {
	it.skip("matches snapshot", () => {
		const { container } = render(
			<ChooseForm formId={2} forms={forms} onChange={jest.fn()} />
		);
		expect(container).toMatchSnapshot();
	});
	it.skip("Calls on change with chosen form id", () => {
		const onChange = jest.fn();
		const labelText = "Test";
		const { getByLabelText } = render(
			<ChooseForm
				formId={2}
				forms={forms}
				onChange={onChange}
				labelText={labelText}
			/>
		);
		fireEvent.change(getByLabelText(labelText), {
			target: { value: "3" }
		});
		expect(onChange).toBeCalledTimes(1);
		expect(onChange).toBeCalledWith("3");
	});
});
