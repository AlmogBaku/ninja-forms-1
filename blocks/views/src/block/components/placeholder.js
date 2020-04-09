import { Placeholder } from "@wordpress/components";

import { NinjaIcon } from "../icon";

export default props => {
	return (
		<Placeholder
			icon={<div style={{ marginRight: "10px" }}>{NinjaIcon}</div>}
			label="Submissions Table"
			instructions="Select a form to display the submissions."
			style={{ alignItems: "center" }}
		>
			<div
				style={{
					margin: "auto",
					width: "50%"
				}}
			>
				{props.children}
			</div>
		</Placeholder>
	);
};
