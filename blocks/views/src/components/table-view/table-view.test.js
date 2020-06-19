import { Pagination } from "./index";
import { render } from "@testing-library/react";

describe("Pagination", () => {
	const props = {
		previousPage: jest.fn(),
		nextPage: jest.fn(),
		loadMore: jest.fn(),
		more: 2,
		loading: false
	};

	it("Shows button for previous page when it should ", () => {
		const { container } = render(
			<Pagination {...props} canPreviousPage={true} canNextPage={false} />
		);
		expect(container.querySelectorAll("button").length).toBe(1);
		expect(container).toMatchSnapshot();
	});

	it("Shows button for next page when it should ", () => {
		const { container } = render(
			<Pagination {...props} canPreviousPage={false} canNextPage={true} />
		);
		expect(container.querySelectorAll("button").length).toBe(1);
		expect(container).toMatchSnapshot();
	});

	it("Shows neither button when it should show neither button ", () => {
		const { container } = render(
			<Pagination {...props} canPreviousPage={false} canNextPage={false} />
		);
		expect(container.querySelectorAll("button").length).toBe(0);
		expect(container).toMatchSnapshot();
	});
});
