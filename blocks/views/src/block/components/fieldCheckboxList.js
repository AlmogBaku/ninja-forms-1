import { CheckboxControl } from '@wordpress/components';

export const FieldCheckboxList = ({fields, isChecked, onChange}) => {
    return (
        <ul>
            {fields.map((field) => {
                return (
                    <li key={"li_" + field.id}>
                        <CheckboxControl
                            key={field.id}
                            label={field.label}
                            checked={ isChecked(field.id) }
                            onChange={(isChecked) => onChange(isChecked, field.id)}
                            />
                    </li>
                )
            })}
        </ul>
    )
}