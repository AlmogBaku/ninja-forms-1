import React from "react";
import { withSelect, select } from "@wordpress/data";
import { useTable, usePagination } from "react-table";
import { FormSubmissions, SelectedFormFields } from "../../data/forms";

export default ({ formId, selectedFields, fields, submissions }) => {

	const selectedFormFields = fields.filter(field => {
		return -1 !== selectedFields.indexOf(field.id);
	});

	const columns = selectedFormFields.map(field => ({
		Header: field.label,
		accessor: field.id.toString()
	}));

	const [pageIndex, setPageIndex] = React.useState(0);
	const [loadingPageIndex, setLoadingPageIndex] = React.useState(false);

	if (submissions[loadingPageIndex]) {
		if (submissions[loadingPageIndex].length) {
			setPageIndex(loadingPageIndex);
		}
		setLoadingPageIndex(false);
	}

	const loadMore = nextPageIndex => {
		setLoadingPageIndex(nextPageIndex);
		select("ninja-forms-views").getFormSubmissionsPage(
			formId,
			nextPageIndex + 1
		);
	};

	const more =
		submissions[submissions.length - 1] &&
		submissions[submissions.length - 1].length;

	const data = submissions.map(sub_item => {
		return sub_item[0];
	})
	// Filter out undefined rows to avoid throwing an error when there are no submissions.
	.filter(sub_item => {
		return 'undefined' !== typeof sub_item;
	});

	return (
		<Table
			columns={columns}
			data={data}
			initialPageIndex={pageIndex}
			loadMore={loadMore}
			more={more}
			loading={loadingPageIndex}
		/>
	);
};

/**
 * tannerlinsley/react-table: basic
 * @link https://codesandbox.io/s/github/tannerlinsley/react-table/tree/master/examples/basic
 */
function Table(props) {
	const { columns, data, initialPageIndex, loadMore, more, loading } = props;
	const instance = useTable(
		{
			columns,
			data,
			initialState: { pageIndex: initialPageIndex, pageSize: 10 }
		},
		usePagination
	);

	// Use the state and functions returned from useTable to build your UI
	const {
		state: { pageIndex },
		getTableProps,
		getTableBodyProps,
		canNextPage,
		canPreviousPage
	} = instance;

	const dataset = instance.page;

	// Render the UI for your table
	return (
		<>
			<table {...getTableProps()}>
				<thead>
					<Headers {...instance} />
				</thead>
				<tbody {...getTableBodyProps()}>
					<Rows {...instance} dataset={dataset} />
				</tbody>
			</table>
			<Pagination
				{...instance}
				loadMore={() => loadMore(pageIndex + 1)}
				more={more}
				loading={loading}
				canNextPage={canNextPage}
				canPreviousPage={canPreviousPage}
			/>
		</>
	);
}

function Headers({ headerGroups }) {
	return headerGroups.map(headerGroup => (
		<tr {...headerGroup.getHeaderGroupProps()}>
			{headerGroup.headers.map(column => (
				<th {...column.getHeaderProps()}>{column.render("Header")}</th>
			))}
		</tr>
	));
}

function Rows({ dataset, prepareRow }) {
	return dataset.map((row, i) => {
		prepareRow(row);
		return (
			<tr {...row.getRowProps()}>
				{row.cells.map(cell => (
					<td {...cell.getCellProps()} data-header={cell.column.Header}>
						{cell.render("Cell")}
					</td>
				))}
			</tr>
		);
	});
}

export function Pagination({
	previousPage,
	nextPage,
	canPreviousPage,
	canNextPage,
	loadMore,
	more,
	loading
}) {
	const Style = {
		display: "flex",
		justifyContent: "space-between"
	};

	let NextButton;
	if (canNextPage) {
		NextButton = <button onClick={() => nextPage()}>{">"}</button>;
	} else {
		if (more) {
			NextButton = (
				<button onClick={() => loadMore()}>{loading ? "..." : ">"}</button>
			);
		}
	}

	return (
		<div style={Style}>
			<div>
				{canPreviousPage && (
					<button onClick={() => previousPage()}>{"<"}</button>
				)}
			</div>
			{canNextPage && <div>{NextButton}</div>}
		</div>
	);
}
