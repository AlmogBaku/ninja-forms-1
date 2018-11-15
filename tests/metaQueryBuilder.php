<?php
use PHPUnit\Framework\TestCase;

class metaQueryBuilder extends TestCase
{
    protected $query_builder;

    protected function setUp()
    {
        include '../ninja-forms/includes/database/MetaQueryBuilder.php';
        $this->query_builder = new NF_Database_MetaQueryBuilder( 'object', 'object_meta', 1 );
    }

    public function testMetaSql()
    {
        $expected = "
            SELECT Meta.parent_id, Meta.key, Meta.value
            FROM object as Object
            JOIN object_meta as Meta
            ON Object.id = Meta.parent_id
            WHERE Object.id IN ( SELECT DISTINCT object.id FROM object WHERE object.parent_id = 1 )
        ";
        $actual = $this->query_builder->get_meta_sql();
        $this->assertEquals( trim($expected), trim($actual) );
    }
}