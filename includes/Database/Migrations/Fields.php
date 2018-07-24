<?php if ( ! defined( 'ABSPATH' ) ) exit;

class NF_Database_Migrations_Fields extends NF_Abstracts_Migration
{
    public function __construct()
    {
        parent::__construct(
            'nf3_fields',
            'nf_migration_create_table_fields'
        );
    }

    public function run()
    {
        $query = "CREATE TABLE IF NOT EXISTS {$this->table_name()} (
            `id` int NOT NULL AUTO_INCREMENT,
            `label` longtext,
            `key` longtext,
            `type` longtext,
            `parent_id` int NOT NULL,
            `created_at` TIMESTAMP,
            `updated_at` DATETIME,
            `field_label` longtext,
            `field_key` longtext,
            `order` int(11),
            `required` bit,
            `default_value` longtext,
            `label_pos` varchar(15),
            `personally_identifiable` bit,
            UNIQUE KEY (`id`)
        ) {$this->charset_collate()};";

        dbDelta( $query );
    }

	/**
	 * Function to run our stage two upgrades.
     *
     * @since 3.3.12
	 */
	public function do_stage_two()
	{
		$query = "ALTER TABLE {$this->table_name()}
            ADD `field_label` longtext {$this->collate()},
            ADD `field_key` longtext {$this->collate()},
            ADD `order` int(11),
            ADD `required` bit,
            ADD `default_value` longtext {$this->collate()},
            ADD `label_pos` varchar(15) {$this->collate()},
            ADD `personally_identifiable` bit";
		global $wpdb;
		$wpdb->query( $query );
	}

}
