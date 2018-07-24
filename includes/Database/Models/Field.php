<?php if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Class NF_Database_Models_Field
 */
final class NF_Database_Models_Field extends NF_Abstracts_Model
{
    private $form_id = '';

    protected $_type = 'field';

    protected $_table_name = 'nf3_fields';

    protected $_meta_table_name = 'nf3_field_meta';

    protected $_columns = array(
        'label',
        'key',
        'parent_id',
        'type',
        'created_at',
	    'field_label',
	    'field_key',
	    'order',
	    'required',
	    'default_value',
	    'label_pos',
	    'personally_identifiable',
    );
    public function __construct( $db, $id, $parent_id = '' )
    {
        parent::__construct( $db, $id, $parent_id );
    }

	/**
	 * Save Setting
	 *
	 * Save a single setting.
	 *
	 * @param $key
	 * @param $value
	 * @return bool|false|int
	 */
	protected function _save_setting( $key, $value )
	{
		// If the setting is a column, save the settings to the model's table.
		if( in_array( $key, $this->_columns ) ){

			$format = null;
			if( in_array( $key, array( 'required', 'personally_identifiable' ) ) ) {
				// gotta set the format for the columns that use bit type
				$format = '%d';
			}

			if( 'label' == $key ) {
				$this->_db->update(
					$this->_table_name,
					array(
						'field_label' => $value
					),
					array(
						'id' => $this->_id
					),
					$format
				);
			}

			if( 'key' == $key ) {
				$this->_db->update(
					$this->_table_name,
					array(
						'field_key' => $value
					),
					array(
						'id' => $this->_id
					),
					$format
				);
			}

			// Don't update the field_label or field_key. Duplicating issue for now
			if( ! in_array($key, [ 'field_label', 'field_key' ] ) ) {
				$update_model = $this->_db->update(
					$this->_table_name,
					array(
						$key => $value
					),
					array(
						'id' => $this->_id
					),
					$format
				);
			} else {
				return 1;
			}

			/*
			 * if it's not a form, you can return, but we are still saving some
			 * settings for forms in the form_meta table
			 */
			if( ! in_array( $key, [
				'order',
				'required',
				'default_value',
				'label_pos',
				'personally_identifiable', ]
				)
			) {
				return $update_model;
			}
		}

		$meta_row = $this->_db->get_row(
			"
                SELECT `value`
                FROM   `$this->_meta_table_name`
                WHERE  `parent_id` = $this->_id
                AND    `key` = '$key'
                "
		);

		if( $meta_row ){

			$update_values = array(
				'value' => $value,
				'meta_key' => $key,
				'meta_value' => $value,
			);

			$result = $this->_db->update(
				$this->_meta_table_name,
				$update_values,
				array(
					'key' => $key,
					'parent_id' => $this->_id
				)
			);

		} else {

			$insert_values = array(
				'key' => $key,
				'value' => $value,
				'meta_key' => $key,
				'meta_value' => $value,
				'parent_id' => $this->_id,
			);

			$result = $this->_db->insert(
				$this->_meta_table_name,
				$insert_values,
				array(
					'%s',
					'%s',
					'%s',
					'%s',
					'%d'
				)
			);
		}

		return $result;
	}

	/**
	 * Delete
	 *
	 * Delete the object, its children, and its relationships.
	 *
	 * Also deletes data associated with field
	 *
	 * @return bool
	 */
    public function delete() {
    	$parent_results = parent::delete();

    	// if parent returns false(no errors) delete data and return false
    	if( false == $parent_results ) {
		    // delete data for field if it exists
		    $this->deleteData();
		    return false;
	    } else {
    		// else return true for errors
    		return true;
	    }
    }

	/**
	 * Delete data for the field
	 *
	 * @return bool
	 */
    private function deleteData() {

    	// check for numeric ids only
    	if( is_numeric( $this->_id ) ) {

    		$query = "DELETE m FROM `" . $this->_db->prefix . "postmeta` m"
			    . " JOIN `" . $this->_db->prefix . "posts` p ON m.post_id = p.ID"
			    . " WHERE p.post_type='nf_sub' AND m.meta_key='_field_" .
		             $this->_id . "'";
    		// delete submitted values for deleted field
		    $this->_db->query( $query );
	    }
    }

    public static function import( array $settings, $field_id = '', $is_conversion = FALSE )
    {
        $settings = apply_filters( 'ninja_forms_before_import_fields', $settings );
        $settings[ 'saved' ] = 1;

        if( $field_id && $is_conversion ) {
            $field = Ninja_Forms()->form()->field( $field_id )->get();
        } else {
            $field = Ninja_Forms()->form()->field()->get();
        }
        $field->update_settings( $settings );
        $field->save();
    }

} // End NF_Database_Models_Field
