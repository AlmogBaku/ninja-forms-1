<?php
final class NF_Database_FieldsController
{
    private $db;
    private $factory;
    private $fields_data;
    private $new_field_ids;
    private $insert_fields;
    private $insert_field_meta = array();
    private $insert_field_meta_chunk = 0;
    /**
     * An array of UPDATE SQL strings.
     *
     * i.e. array( 'key' => 'WHERE `id` = X THEN...' )
     * 
     * @var array
     */
    private $update_fields = array( 'id' => '', 'key' => '', 'label' => '', 'type' => '', 'field_key' => '', 'field_label' => '', 'order' => '', 'default_value' => '', 'label_pos' => '', 'required' => '' );
    private $update_field_meta = array();
    private $update_field_meta_chunk = 0;

    /**
     * Store an array of columns that we want to store in our table rather than meta
     */
    private $db_columns = array(
        'id',
        'key',
        'type',
        'label',
        'field_key',
        'field_label',
        'order',
        'required',
        'default_value',
        'label_pos',
        'parent_id',
    );


    public function __construct( $form_id, $fields_data )
    {
        global $wpdb;
        $this->db = $wpdb;
        $this->form_id = $form_id;
        $this->fields_data = $fields_data;
    }
    public function run()
    {
        $this->db->hide_errors();
        
        /* FIELDS */
        $this->parse_fields();
        
        $insert_fields_query = $this->get_insert_fields_query();
        if( ! empty( $insert_fields_query ) ){
            $this->db->query( $insert_fields_query );
            $this->update_new_field_ids();
        }
        
        $update_fields_query = $this->get_update_fields_query();
        if( ! empty( $update_fields_query ) ){
            $this->db->query( $update_fields_query );
        }

        /* FIELD META */
        $this->parse_field_meta();
        $this->run_insert_field_meta_query();
        $this->run_update_field_meta_query();
    }
    public function get_updated_fields_data()
    {
        return $this->fields_data;
    }
    private function parse_fields()
    {
        foreach( $this->fields_data as $field_data ){
            $field_id = $field_data[ 'id' ];
            $settings = array(
                'key' => $field_data[ 'settings' ][ 'key' ],
                'label' => $field_data[ 'settings' ][ 'label' ],
                'type' => $field_data[ 'settings' ][ 'type' ],
                'field_key' => $field_data[ 'settings' ][ 'key' ],
                'field_label' => $field_data[ 'settings' ][ 'label' ],
                'required' => absint( $field_data[ 'settings' ][ 'required' ] ),
                'order' => $field_data[ 'settings' ][ 'order' ],
                'default_value' => $field_data[ 'settings' ][ 'default' ],
                'label_pos' => $field_data[ 'settings' ][ 'label_pos' ],
            );

            /**
             * We need to decide if we need to insert this field or update it in our fields table.
             * 
             * If we don't have a numeric field id, we're dealing with a tmp field, which is a new field
             *
             * If this field exists in our cache, but doesn't exist in our table, we need to insert it.
             *
             * Otherwise, we're updating.
             *
             * Check our DB for a field with this id.
             */
            if ( is_numeric( $field_id ) ) {
                $field_in_db = $this->db->get_row( "SELECT `id` FROM `wp_nf3_fields` WHERE `id` = {$field_id}" );
            } else {
                $field_in_db = array();
            }

            /**
             * If $field_id isn't a number, then it's a tmp-id.
             *
             * If we have a tmp-id OR the field hasn't been found in our DB, we need to insert it.
             */
            if( ! is_numeric( $field_id ) || empty( $field_in_db ) ) {

                /**
                 * If our $field_id is numeric, we want to insert it into the db with the row.
                 *
                 * If it's not, we want to pass NULL so that we get an autoincrement.
                 */
                if ( is_numeric( $field_id ) ) {
                    $settings[ 'id' ] = $field_id;
                } else {
                    $settings[ 'id' ] = NULL;
                }
                // New Field.
                $this->insert_field( $settings );
            } else {
                // We're updating field settings.
                $this->update_field( $field_id, $settings );
            }
        }
    }
    private function parse_field_meta()
    {
        $existing_meta = $this->get_existing_meta();
        foreach( $this->fields_data as $field_data ){
            $field_id = $field_data[ 'id' ];
            foreach( $field_data[ 'settings' ] as $key => $value ){
                // we don't need object type or domain stored in the db
                if( ! in_array( $key, array( 'objectType', 'objectDomain' ) ) ) {
                    if( isset( $existing_meta[ $field_id ][ $key ] ) ){
                        if( $value == $existing_meta[ $field_id ][ $key ] ) continue;
                        $this->update_field_meta( $field_id, $key, $value );
                    } else {
                        $this->insert_field_meta( $field_id, $key, $value );
                    }
                }
            }
        }
    }
    private function get_existing_meta()
    {
        $results = $this->db->get_results("
        SELECT m.parent_id, m.key, m.value
        FROM `{$this->db->prefix}nf3_field_meta` AS m
        LEFT JOIN `{$this->db->prefix}nf3_fields` AS f
            ON m.parent_id = f.id
        WHERE f.parent_id = {$this->form_id}
        ");
        $field_meta = array();
        foreach( $results as $meta ){
            if( ! isset( $field_meta[ $meta->parent_id ] ) ) $field_meta[ $meta->parent_id ] = array();
            $field_meta[ $meta->parent_id ][ $meta->key ] = $meta->value;
        }
        return $field_meta;
    }
    private function update_new_field_ids()
    {
        $field_id_lookup = $this->db->get_results("
            SELECT `key`, `id`
            FROM {$this->db->prefix}nf3_fields
            WHERE `parent_id` = {$this->form_id}
        ", OBJECT_K);
        foreach( $this->fields_data as $i => $field_data ){
            $field_key = $field_data[ 'settings' ][ 'key' ];
            if( ! is_numeric( $field_data[ 'id' ] ) && isset( $field_id_lookup[ $field_key ] ) ){
                $tmp_id = $field_data[ 'id' ];
                $this->fields_data[ $i ][ 'id' ] = $this->new_field_ids[ $tmp_id ] = $field_id_lookup[ $field_key ]->id;
            }
        }
    }
    public function get_new_field_ids()
    {
        return $this->new_field_ids;
    }
    /*
    |--------------------------------------------------------------------------
    | INSERT (NEW) FIELDS
    |--------------------------------------------------------------------------
    */
    private function insert_field( $settings )
    {
        // Add our initial opening parenthesis.
        $this->insert_fields .= "(";
        // Add our form id to our settings as 'parent_id'.
        $settings[ 'parent_id' ] = $this->form_id;

        /**
         * Loop over each of our $this->db_columns to create a value list for our SQL statement.
         */
        foreach ( $this->db_columns as $col ) {
            $value = $settings[ $col ];
            $this->db->escape_by_ref( $value );
            if ( is_numeric( $value ) ) {
                $this->insert_fields .= "{$value},";
            } else {
                $this->insert_fields .= "'{$value}',";
            }
            
        }
        // Remove any trailing commas from our SQL string.
        $this->insert_fields = rtrim( $this->insert_fields, ',' );
        $this->insert_fields .=  '),';
    }
    public function get_insert_fields_query()
    {
        if( ! $this->insert_fields ) return "";
        $insert_fields = rtrim( $this->insert_fields, ',' ); // Strip trailing comma from SQl.
        
        /**
         * Loop over each of our $this->db_columns to create a column list for our SQL statement below.
         */
        $columns = '';
        foreach( $this->db_columns as $col ) {
            $columns .= "`{$col}` ,";
        }

        $columns = rtrim( $columns, ',' );

        return "
            INSERT INTO {$this->db->prefix}nf3_fields ( {$columns} )
            VALUES {$insert_fields}
        ";
    }
    /*
    |--------------------------------------------------------------------------
    | UPDATE (EXISTING) FIELDS
    |--------------------------------------------------------------------------
    */
    private function update_field( $field_id, $settings )
    {
        foreach ( $settings as $setting => $value ) {
            $line = "WHEN `id` = '{$field_id}' ";
            $this->db->escape_by_ref( $value );
            $line .= "THEN ";
            if ( is_numeric( $value ) ) {
                $line .= "{$value} ";
            } else {
                $line .= "'{$value}' ";
            }
            
            $this->update_fields[ $setting ] .= $line;
        }
    }
    public function get_update_fields_query()
    {
        if(
            empty( $this->update_fields[ 'key'   ] ) ||
            empty( $this->update_fields[ 'label' ] ) ||
            empty( $this->update_fields[ 'type'  ] ) ||
            empty( $this->update_fields[ 'field_key'  ] ) ||
            empty( $this->update_fields[ 'field_label'  ] ) ||
            empty( $this->update_fields[ 'order'  ] ) ||
            empty( $this->update_fields[ 'required'  ] ) ||
            empty( $this->update_fields[ 'default_value'  ] ) ||
            empty( $this->update_fields[ 'label_pos'  ] )

            ) return "";
        return "
            UPDATE {$this->db->prefix}nf3_fields
            SET `key` = CASE {$this->update_fields[ 'key' ]}
                ELSE `key`
                END
            , `label` = CASE {$this->update_fields[ 'label' ]}
                ELSE `label`
                END
            , `type` = CASE {$this->update_fields[ 'type' ]}
                ELSE `type`
                END
            , `field_key` = CASE {$this->update_fields[ 'key' ]}
                ELSE `field_key`
                END
            , `field_label` = CASE {$this->update_fields[ 'label' ]}
                ELSE `field_label`
                END
            , `order` = CASE {$this->update_fields[ 'order' ]}
                ELSE `order`
                END
            , `default_value` = CASE {$this->update_fields[ 'default_value' ]}
                ELSE `default_value`
                END
            , `label_pos` = CASE {$this->update_fields[ 'label_pos' ]}
                ELSE `label_pos`
                END
            , `required` = CASE {$this->update_fields[ 'required' ]}
                ELSE `required`
                END
        ";
    }
    /*
    |--------------------------------------------------------------------------
    | INSERT (NEW) META
    |--------------------------------------------------------------------------
    */
    private function insert_field_meta( $field_id, $key, $value )
    {
        static $counter;
        
        $value = maybe_serialize( $value );
        
        $this->db->escape_by_ref( $field_id );
        $this->db->escape_by_ref( $key );
        $this->db->escape_by_ref( $value );

        if( ! isset( $this->insert_field_meta[ $this->insert_field_meta_chunk ] ) || ! $this->insert_field_meta[ $this->insert_field_meta_chunk ] ) {
            $this->insert_field_meta[ $this->insert_field_meta_chunk ] = '';
        }
        $this->insert_field_meta[ $this->insert_field_meta_chunk ] .= "('{$field_id}','{$key}','{$value}' ),";
        $counter++;
        if( 0 == $counter % 5000 ) $this->insert_field_meta_chunk++;
    }
    public function run_insert_field_meta_query()
    {
        if( ! $this->insert_field_meta ) return "";
        foreach( $this->insert_field_meta as $insert_field_meta ){
            $insert_field_meta = rtrim( $insert_field_meta, ',' ); // Strip trailing comma from SQl.
            $this->db->query( "
                INSERT INTO {$this->db->prefix}nf3_field_meta ( `parent_id`, `key`, `value` )
                VALUES {$insert_field_meta}
            ");
        }
    }
    /*
    |--------------------------------------------------------------------------
    | UPDATE (EXISTING) META
    |--------------------------------------------------------------------------
    */
    private function update_field_meta( $field_id, $key, $value )
    {
        static $counter;

        $value = maybe_serialize( $value );
        $this->db->escape_by_ref( $key   );
        $this->db->escape_by_ref( $value );
        if( ! isset( $this->update_field_meta[ $this->update_field_meta_chunk ] ) || ! $this->update_field_meta[ $this->update_field_meta_chunk ] ) {
            $this->update_field_meta[ $this->update_field_meta_chunk ] = '';
        }
        $this->update_field_meta[ $this->update_field_meta_chunk ] .= " WHEN `parent_id` = '{$field_id}' AND `key` = '{$key}' THEN '{$value}'";

        $counter++;
        if( 0 == $counter % 5000 ) $this->update_field_meta_chunk++;
    }
    public function run_update_field_meta_query()
    {
        if( empty( $this->update_field_meta ) ) return '';
        foreach( $this->update_field_meta as $update_field_meta ){
            $this->db->query("
                UPDATE {$this->db->prefix}nf3_field_meta as field_meta
                SET `value` = CASE {$update_field_meta} ELSE `value` END
            ");
            return;
        }
    }
}