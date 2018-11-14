<?php

/**
 * Builds an SQL statement to fetch fields (with settings) for a given form ID.
 */
class NF_Database_FieldsQueryBuilder
{
    protected $table_name = '';
    protected $meta_table_name = '';
    protected $parent_id = 0;

    /**
     * @param string $table_name
     * @param string $meta_table_name
     * @param string $parent_id Form ID
     */
    public function __construct( $table_name = '', $meta_table_name = '', $parent_id = 0 ) {
        $this->table_name = $table_name;
        $this->meta_table_name = $meta_table_name;
        $this->parent_id = $parent_id;
    }

    /**
     * @return string
     */
    public function get_field_ids_sql()
    {
        return "SELECT DISTINCT $this->table_name.id FROM $this->table_name WHERE {$this->table_name}.parent_id = $this->parent_id";
    }

    /**
     * @return string
     */
    public function get_fields_sql()
    {
        return "
        SELECT *
        FROM $this->table_name
        WHERE id IN ( {$this->get_field_ids_sql()} )
        ";
    }

    public function get_field_meta_sql()
    {
        return "
            SELECT Meta.parent_id, Meta.key, Meta.value
            FROM $this->table_name as Object
            JOIN $this->meta_table_name as Meta
            ON Object.id = Meta.parent_id
            WHERE Object.id IN ( {$this->get_field_ids_sql()} )
        ";
    }
}