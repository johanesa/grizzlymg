<?php
/*
Plugin Name: Grizzly Mg
Plugin URI: http://johanesa.github.com/grizzlymg
Description: plugin <strong>Template</strong> for Wordpress
Version: 1.0-dev
Author: Hery Johanesa
Author URI: http://johanesa.github.com/
License: MIT
*/

/*
Copyright 2017  little Grizzly

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*/

//Constants (version & paths)
define ('PLUGIN_BASE_PATH', dirname((__FILE__)));
define ('SETTINGS_BASE_PATH', PLUGIN_BASE_PATH.'/sources/settings');
define ('VIEWS_BASE_PATH', PLUGIN_BASE_PATH.'/sources/views');
define ('PLUGIN_TABLE_PREFIX', $GLOBALS['table_prefix'].'grzz_');
define ('PLUGIN_VERSION', '1.0');
define ('REQUIRED_PHP_VERSION', '5.6');
define ('REQUIRED_WORDPRESS_VERSION', '4.6');
define ('ASSETS_PATH', PLUGIN_BASE_PATH.'/assets');
global $wp_version;   
//Check PHP version
if (!(version_compare(phpversion(), REQUIRED_PHP_VERSION, '<')) && !(version_compare($wp_version, REQUIRED_WORDPRESS_VERSION, '<'))) {

    if(!class_exists('grizzly')) {
        class grizzly { 
            public function __construct() {
                add_action('admin_init', array(&$this, 'admin_init'));
                add_action('admin_menu', array(&$this, 'add_menu'));
                // Comment following lines if you don't need custom post-type template with the Plug in
                require_once(sprintf("%s/sources/post-types/post_type_template.php", PLUGIN_BASE_PATH));
                $grizzlyPostTypeTemplate = new GrizzlyPostTypeTemplate();
            
            } 
            
            public static function activate() {
                // Activation
                // Up all migrations
                require_once(sprintf("%s/installs/up_migrations.php", PLUGIN_BASE_PATH));
                $up_instance = new Up_migrations();
                $up_instance->migrate_schema();
            } 

            public static function deactivate() {
                // Desactivation
                require_once(sprintf("%s/installs/down_migrations.php", PLUGIN_BASE_PATH));
                $down_instance = new Down_migrations();
                $down_instance->rollback_schema(true);
            } 

            public function admin_init() {
                $this->init_settings();
            } 
           
            public function init_settings() {
                register_setting('grizzly-group', 'setting_a');
                register_setting('grizzly-group', 'setting_b');
            }

            public function add_menu() {
                add_options_page('Grizzly settings', 'Grizzly', 'manage_options', 'grizzly', array(&$this, 'plugin_settings_page'));
            } 

            public function plugin_settings_page() {
                if(!current_user_can('manage_options')) {
                    wp_die(__('You do not have sufficient permissions to access this page.'));
                }

                include(sprintf("%s/settings/_settings.php", VIEWS_BASE_PATH));
            } 
        } 

        register_activation_hook(__FILE__, array('grizzly', 'activate'));
        register_deactivation_hook(__FILE__, array('grizzly', 'deactivate'));
       
        $grizzly = new grizzly();
        if(isset($grizzly)) {
            // Settings Options
            function plugin_settings_link($links) {
                if(current_user_can('manage_options')) {
                    $settings_link = '<a href="options-general.php?page=grizzly">Settings</a>'; 
                    array_unshift($links, $settings_link); 
                    return $links;
                }
                $settings_link = '<a href="#">Settings</a>'; 
                array_unshift($links, $settings_link);  
                return $links; 
            }
            // And/Or top_level_menu views
            function plugin_options_page_link() {
                // check user capabilities
                if (!current_user_can('manage_options'))
                    return;
                // you can later redirect to a full controller
                include_once (VIEWS_BASE_PATH . '/settings/_top_level_menu.php');
            }
            //Hook top level menus 
            function default_options_page() {
                add_menu_page(
                    'Grizzly',
                    'Grizzly options',
                    'manage_options',
                    'grizzly_tol_level_options',
                    'plugin_options_page_link',
                     plugin_dir_url(__FILE__) . 'assets/img/resized_bear_icon.png',
                    20
                );
            }
            add_action('admin_menu', 'default_options_page');

            $plugin = plugin_basename(__FILE__); 
            add_filter("plugin_action_links_$plugin", 'plugin_settings_link');
        }
    } 

} else {
    function notice_php_version() {
        include_once( VIEWS_BASE_PATH . '/settings/_notice_php_version.php' );
        include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
        $plugin_slug = basename( dirname( __FILE__ ) );
        $plugin      = $plugin_slug . "/" . 'grizzly-mg' . ".php";
        if( is_plugin_active( $plugin ) ) {
            deactivate_plugins( $plugin );
        }
        wp_die();
    }

    add_action( 'admin_notices', 'notice_php_version' );
}
