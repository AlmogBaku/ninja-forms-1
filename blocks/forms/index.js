import Edit from './Edit';
import  block from './block';
import { __ } from '@wordpress/i18n';
import { registerBlockType } from '@wordpress/blocks';

registerBlockType('ninja-forms/form',{
    ...block,
    title: __('Ninja Form', 'ninja-forms'),
    edit: ({attributes,setAttributes}) => {

        return <Edit/>
    },
    save: () => null
});