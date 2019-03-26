<?php
/**
 * Created by PhpStorm.
 * User: DHM
 * Date: 2019/3/25
 * Time: 16:53
 */

namespace MyFAQs\Install;


class Deactivate
{
	/**
	 * The plugin uninstall performs the delete table operation 
	 */
    public function plugin_deactivation(){
    	$this->remove_database_tables();
    }

    /**
     * Drops all database tables known to MyFAQs.
     */
    protected function remove_database_tables()
	{
		global $wpdb;

		$tables = $this->get_table_data();
		foreach ($tables as $table) {
			$sql = "DROP TABLE IF EXISTS `_faq_{$table}`";
			$wpdb->query($sql);
		}
	}

	/**
	 * Returns array containing information on database tables
	 * @return array Database information
	 */
	protected function get_table_data()
	{
		$tables = array(
			'question', // Because of the foreign key relationship, the `question` table must be dropped first.
			'categories',
			'shortcode',
		);

		return $tables;
	}
}