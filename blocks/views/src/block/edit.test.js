import { Edit } from './edit';
import {
   render, //test renderer
   cleanup, //resets the JSDOM
   fireEvent, //fires events on nodes,
} from "@testing-library/react";

import forms from "../data/forms";

describe("Editor component", () => {
    afterEach(cleanup);

    it("matches snapshot", () => {
      const attributes = {
            formId: 1
    };
      const setAttributes = jest.fn();

      const {container} = render(
        <Edit
          {...{
            attributes,
            setAttributes,
            forms: {},
            fields: forms[1].fields,
            submissions: forms[1].submissions,
            clientId: "random-id",
            className: "wp-blocks-whatever"
          }}
        />
      )
      
      expect(container).toMatchSnapshot();
    });

});