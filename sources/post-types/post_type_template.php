<?php 

if(!class_exists('GrizzlyPostTypeTemplate')) {
    class GrizzlyPostTypeTemplate {

        public function __construct() {
            add_action('init', array(&$this, 'init'));
            add_action('admin_init', array(&$this, 'admin_init'));
        }

        const POST_TYPE = "Grizzly";
        private $_meta  = array(
            'meta_a',
            'meta_b',
            'meta_c',
        );

        public function init() {
            $this->create_post_type();
            add_action('save_post', array(&$this, 'save_post'));
        } 

        public function admin_init() {           
            add_action('add_meta_boxes', array(&$this, 'add_meta_boxes'));
        } 

        public function add_meta_boxes() {
            add_meta_box( 
                sprintf('grizzly_%s_section', self::POST_TYPE),
                sprintf('%s Information', ucwords(str_replace("_", " ", self::POST_TYPE))),
                array(&$this, 'add_inner_meta_boxes'),
                self::POST_TYPE
            );                  
        }
         

        public function add_inner_meta_boxes($post) {       
            include(sprintf("%s/settings/_%s_metabox.php", VIEWS_BASE_PATH, self::POST_TYPE));         
        } 
        public function create_post_type() {
            register_post_type(self::POST_TYPE,
                array(
                    'labels' => array(
                        'name' => __(sprintf('%ss', ucwords(str_replace("_", " ", self::POST_TYPE)))),
                        'singular_name' => __(ucwords(str_replace("_", " ", self::POST_TYPE)))
                    ),
                    'public' => true,
                    'has_archive' => true,
                    'description' => __("This is a sample post type meant only to illustrate a preferred structure of plugin development"),
                    'supports' => array(
                        'title', 'editor', 'excerpt', 
                    ),
                )
            );
        }

        public function save_post($post_id) {
        
            if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
                return;
            }

            if(isset($_POST['post_type']) && $_POST['post_type'] == self::POST_TYPE && current_user_can('edit_post', $post_id)) {
                foreach($this->_meta as $field_name) {
                    update_post_meta($post_id, $field_name, $_POST[$field_name]);
                }
            }
            else {
                return;
            } 
        }
    } 
}