import forms from "../../data/forms";
import Placeholder from "./placeholder";
import {
	render, //test renderer
	cleanup, //resets the JSDOM
	fireEvent //fires events on nodes,
} from "@testing-library/react";

describe("Editor component", () => {
	afterEach(cleanup);
	it("matches snapshot", () => {
		const props = { inside: <div>Inside Content</div> };

		const { container } = render(
			<Placeholder
				icon={<svg height="100" width="100">
					<circle cx="50" cy="50" r="40" stroke="black"  fill="red" />
				</svg>}
			>

				<div>Inside Content</div>
			</Placeholder>
		);

		expect(container).toMatchSnapshot();
	});
});
