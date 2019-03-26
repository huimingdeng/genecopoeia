<?php
/**
 * Created by PhpStorm.
 * User: DHM
 * Date: 2019/3/25
 * Time: 16:51
 */

namespace MyFAQs\Install;


class Activate
{
    public function plugin_active($networ = FALSE){
       $this->create_database_tables();
    }

    /**
     * @return array
     */
    protected function get_table_data()
    {
        $ret = array(
            'categories' =>
                "CREATE TABLE `categories` (
                  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                  `name` varchar(255) NOT NULL COMMENT '分类名',
                  `slug` varchar(255) NOT NULL COMMENT '别名,必须英文',
                  `pubdate` datetime NOT NULL COMMENT '发布时间',
                  `editdate` datetime NOT NULL COMMENT '修改时间',
                  `sumfaq` int(10) unsigned NOT NULL COMMENT '统计当前分类faq数量',
                  `parent` int(10) DEFAULT NULL COMMENT '父级分类',
                  PRIMARY KEY (`id`),
                  UNIQUE KEY `name` (`name`)
                ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;",
            'question' =>
                "CREATE TABLE `question` (
                  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                  `title` varchar(255) NOT NULL COMMENT 'issue',
                  `answer` text NOT NULL COMMENT 'answer',
                  `pubdate` datetime NOT NULL,
                  `editdate` datetime NOT NULL,
                  `category` int(10) unsigned DEFAULT NULL,
                  PRIMARY KEY (`id`),
                  UNIQUE KEY `issue` (`title`),
                  KEY `catagory_id` (`category`),
                  CONSTRAINT `catagory_id` FOREIGN KEY (`category`) REFERENCES `_faq_categories` (`id`) ON DELETE CASCADE
                ) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;",
            'shortcode' =>
                "CREATE TABLE `shortcode` (
                  `id` int(11) NOT NULL AUTO_INCREMENT,
                  `short_code` varchar(50) DEFAULT NULL,
                  `location` varchar(100) DEFAULT NULL COMMENT '记录使用的wp_posts表的ID',
                  `pubdate` datetime NOT NULL,
                  `editdate` datetime NOT NULL,
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;",
        );
        return $ret;
    }

    /**
     * create tables.
     */
    protected function create_database_tables()
    {
        global $wpdb;
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

        // Hack to reduce errors. Removing backticks makes the dbDelta function work better. #164
        add_filter('dbdelta_create_queries', array($this, 'filter_dbdelta_queries'));
        add_filter('wp_should_upgrade_global_tables', '__return_true'); // The new version of the plugin updates information

        $errors = $wpdb->show_errors(FALSE);				// disable errors #164
        $tables = $this->get_table_data();
        foreach ($tables as $table => $sql) {
            $sql = str_replace('CREATE TABLE `', 'CREATE TABLE `' . '_faq_', $sql);
            $ret = dbDelta($sql);
        }

        $wpdb->show_errors($errors);						// reset errors #164

    }

    /**
     * @param $c_queries
     * @return array
     */
    public function filter_dbdelta_queries($c_queries)
    {
        $ret_queries = array();
        foreach ($c_queries as $query) {
            $ret_queries[] = str_replace('`', '', $query);
        }
        return $ret_queries;
    }
}