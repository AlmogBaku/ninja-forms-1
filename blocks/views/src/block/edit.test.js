import { Edit } from './edit';
import {
   render, //test renderer
   cleanup, //resets the JSDOM
   fireEvent, //fires events on nodes,
} from "@testing-library/react";

describe("Editor component", () => {
    afterEach(cleanup);

    it("matches snapshot", () => {
      const attributes = { formId: 1 };
      const setAttributes = jest.fn();
      expect(
        render(
          <Edit
            {...{
              attributes,
              setAttributes,
              clientId: "random-id",
              className: "wp-blocks-whatever"
            }}
          />
        )
      ).toMatchSnapshot();
    });

});