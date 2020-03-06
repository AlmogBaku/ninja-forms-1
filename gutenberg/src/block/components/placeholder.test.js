import forms from '../data/forms'
import Placeholder from './placeholder';
import {
    render, //test renderer
    cleanup, //resets the JSDOM
    fireEvent, //fires events on nodes,
} from "@testing-library/react";

describe("Editor componet", () => {
    afterEach(cleanup);

    it("matches snapshot", () => {
        const props = { inside: <div>Inside Content</div> }
        expect(render(
            <Placeholder {...props} />
        )).toMatchSnapshot();
    });

});