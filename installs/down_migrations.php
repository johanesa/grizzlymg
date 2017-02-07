<?php 

//require for dbDelta
require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
class Down_migrations {
    /**
     * @param boolean $datas
     * @param boolean $tables
     */
    public function rollback_schema($datas = false, $tables = false) {
        //Schema Removing during deactivation
        if($datas)
        self::remove_default_to_database();
        //Must be called after remove default_to_database
        if($tables)
        self::delete_database_tables();
    }

    public static function delete_database_tables() {
        //Here come the functions (or queries) to create database tables for the plugin
        //An example creating a table "plugin_uninstall_options"
        self::delete_uninstall_options_table();
    }

    public static function remove_default_to_database() {
        //Here come the functions (or queries) to insert values in created_tables
        //An example creating a table "plugin_uninstall_options"
        self::remove_default_uninstall_options_values();
    }

    public static function delete_uninstall_options_table() {
        $table_name = PLUGIN_TABLE_PREFIX.'uninstall_options';
        $uninstall_options_table_request = sprintf("DROP TABLE IF EXISTS %s", $table_name);
        dbDelta($uninstall_options_table_request);
    }

    public static function remove_default_uninstall_options_values() {
        
        global $wpdb;
        $table_name = PLUGIN_TABLE_PREFIX.'uninstall_options';
        $wpdb->query("TRUNCATE TABLE ".$table_name."");

    }

}