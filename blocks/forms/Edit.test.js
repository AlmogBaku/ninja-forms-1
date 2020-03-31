import Edit from './Edit';
import {render,fireEvent} from '@testing-library/react';
describe( 'Form block Edit callback', () => {

    it( 'Matches snapshot', () => {
        const onChange = jest.fn();

        const {container} = render(<Edit onChange={onChange} form={'1'}/> );
        expect(container).toMatchSnapshot();
    });

    it( 'Calls onChange when changing form', () =>{
        const onChange = jest.fn();
        const {getByLabel} = render(<Edit onChange={onChange} form={'1'} /> );
        fireEvent.change(getByLabel('Form'), {
            target: {value: '3'}
        });
        expect(onChange).toBeCalledTimes(1);
        expect(onChange).toBeCalledWith('3')
    });
});