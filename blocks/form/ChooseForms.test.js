import React from "react";
import { ChooseForm } from "./Edit";
import { render, fireEvent } from "@testing-library/react";
import forms from "./forms.fixture.js";

describe("ChooseForm", () => {
	it("matches snapshot", () => {
		const { container } = render(
			<ChooseForm formId={2} forms={forms} onChange={jest.fn()} />
		);
		expect(container).toMatchSnapshot();
	});
	it("Calls on change with chosen form id", () => {
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
