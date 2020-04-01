import Edit, { ChooseForm } from "./Edit";
import { render, fireEvent } from "@testing-library/react";
import forms from "./forms.fixture.js";
describe("Form block Edit callback", () => {
	it("Matches snapshot", () => {
		const onChange = jest.fn();

		const { container } = render(
			<Edit onChange={onChange} formId={"1"} forms={forms} />
		);
		expect(container).toMatchSnapshot();
	});

	it("Calls onChange when changing form", () => {
		const onChange = jest.fn();
		const { getByLabel } = render(
			<Edit onChange={onChange} formId={"1"} forms={forms} />
		);
		fireEvent.change(getByLabel("Form"), {
			target: { value: "3" }
		});
		expect(onChange).toBeCalledTimes(1);
		expect(onChange).toBeCalledWith("3");
	});

	it("Shows place holder when no formId passed", () => {
		const onChange = jest.fn();
		const { container } = render(<Edit onChange={onChange} forms={forms} />);
		expect(container).toBe("I do not know?");
	});

	it("Shows serve-side-render when formId is passed", () => {
		const onChange = jest.fn();
		const { container } = render(
			<Edit onChange={onChange} formId={"1"} forms={forms} />
		);
		expect(container).toBe("I do not know?");
	});
});

describe("ChooseForm", () => {
	it("", () => {
		const { container } = render(
			<ChooseForm formId={2} forms={forms} onChange={jest.fn()} />
		);
		expect(container).toMatchSnapshot();
	});
	it("Calls on change with chosen form id", () => {
		const onChange = jest.fn();
		const labelText = "Test";
		const { getByLabel } = render(
			<ChooseForm
				formId={2}
				forms={forms}
				onChange={onChange}
				labelText={labelText}
			/>
		);
		fireEvent.change(getByLabel(labelText), {
			target: { value: "3" }
		});
		expect(onChange).toBeCalledTimes(1);
		expect(onChange).toBeCalledWith("3");
	});
});
