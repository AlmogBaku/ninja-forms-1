import forms from '../../../data/forms'
import { FieldCheckboxList } from './fieldCheckboxList';
import {
    render, //test renderer
    cleanup, //resets the JSDOM
    fireEvent, //fires events on nodes,
} from "@testing-library/react";

describe("Editor componet", () => {
    afterEach(cleanup);

    it("matches snapshot", () => {
        const props = {
            fields: Object.values(forms[1].formFields),
            isChecked: () => false,
            onChange: jest.fn()
        }
        expect(render(
            <FieldCheckboxList {...props} />
        )).toMatchSnapshot();
    });

});