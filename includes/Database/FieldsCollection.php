<?php

class NF_Database_FieldsCollection implements JsonSerializable
{
    protected $fields = array();

    /**
     * @param array $field_data [ field_id => [ field_settig, ... ], ... ]
     */
    public function __construct( $fields_data = array() )
    {
        $this->fields = $fields_data;
    }

    /**
     * @param int $field_id
     * @return array $field_settings
     */
    public function get_field_settings( $field_id )
    {
        if( ! isset( $this->fields[ $field_id ] ) ){
            return array();
        }
        return $field_settings = $this->fields[ $field_id ];
    }

    /**
     * @return array [ field_id => [ field_settig, ... ], ... ]
     */
    public function jsonSerialize() {
        return $this->fields;
    }

    /**
     * @return array
     */
    public function __debugInfo()
    {
        return array(
            'fields' => $this->fields
        );
    }
}