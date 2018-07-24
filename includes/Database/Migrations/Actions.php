<?php if ( ! defined( 'ABSPATH' ) ) exit;

class NF_Database_Migrations_Actions extends NF_Abstracts_Migration
{
    public function __construct()
    {
        parent::__construct(
            'nf3_actions',
            'nf_migration_create_table_actions'
        );
    }

    public function run()
    {
        $query = "CREATE TABLE IF NOT EXISTS {$this->table_name()} (
            `id` int NOT NULL AUTO_INCREMENT,
            `title` longtext,
            `key` longtext,
            `type` longtext,
            `active` boolean DEFAULT TRUE,
            `parent_id` int NOT NULL,
            `created_at` TIMESTAMP,
            `updated_at` DATETIME,
            `label` longtext,
            UNIQUE KEY (`id`)
        ) {$this->charset_collate()};";

        dbDelta( $query );
    }

    /**
     * Do Stage Two
     * This method runs as a part of the stage two step processor
     * it will add tables that we need that are missing currently.
     *
     * @since  3.3.12
     * @return void
     */
    public function do_stage_two()
    {
        global $wpdb;

        $query = "ALTER TABLE {$this->table_name()}
            ADD `label` longtext {$this->collate()}";


        $wpdb->query( $query );
    }

}
