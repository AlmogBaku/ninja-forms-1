<?php

use PHPUnit\Framework\TestCase;

final class EnvironmentTest extends TestCase
{
    public function testWpdb()
    {
        global $wpdb;
        $this->assertIsObject($wpdb);
    }

    public function testSavePost()
    {
        $id = wp_insert_post(['post_title' => 'Post title', 'post_content' => 'Hello Roy']);
        $this->assertIsNumeric($id);
    }
}