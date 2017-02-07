<?php 

//require for dbDelta
require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

class Up_migrations {

    public function migrate_schema() {
        //Schema Migration during installation
        self::create_database_tables();
        //Must be called after create_database_tables
        self::add_default_to_database();
    }

    public static function create_database_tables() {
        //Here come the functions (or queries) to create database tables for the plugin
        //An example creating a table "plugin_uninstall_options"
        self::create_uninstall_options_table();
    }

    public static function add_default_to_database() {
        //Here come the functions (or queries) to insert values in created_tables
        //An example creating a table "plugin_uninstall_options"
        self::set_default_uninstall_options_values();
    }

    public static function create_uninstall_options_table() {
        $table_name = PLUGIN_TABLE_PREFIX.'uninstall_options';
        $uninstall_options_table_request = sprintf("CREATE TABLE %s 
            (id int(11) NOT NULL AUTO_INCREMENT,
            remove_concerned_table BOOLEAN NOT NULL default 0,
            PRIMARY KEY (id) )", $table_name);
        dbDelta($uninstall_options_table_request);
    }

    public static function set_default_uninstall_options_values() {
        //Inserting default values
        global $wpdb;
        $table_name = PLUGIN_TABLE_PREFIX.'uninstall_options';
            $wpdb->insert( 
            $table_name, 
                array( 
                    'remove_concerned_table' => true,
                    ) 
            );  
    }

}