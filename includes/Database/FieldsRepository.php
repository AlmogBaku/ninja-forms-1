<?php

class NF_Database_FieldsRepository
{
    protected $db;

    public function __construct( $db ) {
        $this->db = $db;
    }

    public function fetch( $form_id ) {
        $table_name = $this->db->prefix . 'nf3_fields';
        $meta_table_name = $this->db->prefix . 'nf3_field_meta';
        $query_builder = new NF_Database_MetaQueryBuilder( $table_name, $meta_table_name, $form_id );
        
        $fields_sql = $query_builder->get_sql();
        $field_meta_sql = $query_builder->get_meta_sql();

        $fields = array();

        $field_data = $this->db->get_results( $fields_sql, ARRAY_A );
        foreach( $field_data as $field_data ) {
            $field_id = $field_data[ 'id' ];
            unset( $field_data[ 'id' ] );
            $fields[ $field_id ] = $field_data;
        }

        $field_meta = $this->db->get_results( $field_meta_sql );
        foreach( $field_meta as $meta ) {
            if( ! isset( $fields[ $meta->parent_id ][ $meta->key ] ) ){
                $fields[ $meta->parent_id ][ $meta->key ] = $meta->value;
            }
        }

        return $collection = new NF_Database_FieldsCollection( $fields );
    }
}