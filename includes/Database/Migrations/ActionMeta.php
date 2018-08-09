<?php if ( ! defined( 'ABSPATH' ) ) exit;

class NF_Database_Migrations_ActionMeta extends NF_Abstracts_Migration
{
    public function __construct()
    {
        parent::__construct(
            'nf3_action_meta',
            'nf_migration_create_table_action_meta'
        );
    }

    public function run()
    {
        $query = "CREATE TABLE IF NOT EXISTS {$this->table_name()} (
            `id` int NOT NULL AUTO_INCREMENT,
            `parent_id` int NOT NULL,
            `key` longtext NOT NULL,
            `value` longtext,
            `meta_key` longtext,
            `meta_value` longtext,
            UNIQUE KEY (`id`)
        ) {$this->charset_collate()};";

        dbDelta( $query );
    }

	/**
	 * Do Stage Two
	 * This method runs as a part of the stage two step processor
	 * it will add tables that we need that are missing currently.
	 *
	 * @since 3.3.12
	 * @return void
	 */
    public function do_stage_two()
    {
        global $wpdb;

        $query = "ALTER TABLE {$this->table_name()}
            ADD `meta_key` longtext {$this->collate()},
            ADD `meta_value` longtext {$this->collate()}";

        $wpdb->query( $query );
    }

}
