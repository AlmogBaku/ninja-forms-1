<?php if ( ! defined( 'ABSPATH' ) ) exit;

class NF_Database_Migrations_FieldMeta extends NF_Abstracts_Migration
{
    public function __construct()
    {
        parent::__construct(
            'nf3_field_meta',
            'nf_migration_create_table_field_meta'
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
        ) {$this->charset_collate( true )};";

        dbDelta( $query );
    }

	/**
	 * Function to run our stage three upgrades.
     *
     * @since 3.3.12
	 */
	public function cache_collate_fields()
	{
		$query = "ALTER TABLE {$this->table_name()}
            ADD `meta_key` longtext {$this->charset_collate()},
            ADD `meta_value` longtext {$this->charset_collate()}";
		global $wpdb;
		$wpdb->query( $query );
	}

}
