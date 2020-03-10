import { Placeholder } from '@wordpress/components';

import { NinjaIcon } from "../icon"

export default (props) => (
    <Placeholder
        icon={<div style={{marginRight: '10px'}}>{NinjaIcon}</div>}
        label="Submissions Table"
        instructions="Select a form to display the submissions."
    >
        {props.inside}
    </Placeholder>
)