<?php
use PHPUnit\Framework\TestCase;

class fieldsCollection extends TestCase
{
    public function testExample()
    {
        include '../ninja-forms/includes/database/FieldsCollection.php';
        $fields = [ 1 => [ 'id' => 1 ] ];
        $collection = new NF_Database_FieldsCollection( $fields );
        $field = $collection->get_field_settings( 1 );
        $this->assertTrue( 1 == $field[ 'id' ] );
    }
}