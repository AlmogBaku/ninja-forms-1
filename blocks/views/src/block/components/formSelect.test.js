import forms from '../../data/forms'
import { FormSelect } from './formSelect';
import {
render, //test renderer
cleanup, //resets the JSDOM
fireEvent, //fires events on nodes,
} from "@testing-library/react";

describe("Editor component", () => {
    afterEach(cleanup);

    it("matches snapshot", () => {
        const props = { forms: forms, formId: 1, onChange: jest.fn() }
        expect(render(
            <FormSelect {...props} />
        )).toMatchSnapshot();
    });

    it("changes value", () => {
        const onFormChange = jest.fn();
        const props = { forms: forms, formId: 1, onChange: onFormChange }
        const {getByLabelText} = render(
            <FormSelect {...props} />
        )
        
        fireEvent.change(
            getByLabelText('Select a form'),
            {target: { value: 1 }}
        );
        
        expect(onFormChange).toHaveBeenCalledTimes(1);
        expect(onFormChange).toHaveBeenCalledWith("1");
    })

});