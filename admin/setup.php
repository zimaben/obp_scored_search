<?php

namespace obp_score\admin;
#use \template\theme\MultiPostThumbnails as MultiPostThumbnails;
// spin it up ##
\obp_score\admin\Setup::get_instance();

class Setup extends \ScoringEngine {

    private static $instance = null;

    public static function get_instance() 
    {

        if ( 
            null == self::$instance 
        ) {

            self::$instance = new self;

        }

        return self::$instance;

    }
    
    private function __construct() //DEFINE AT RUNTIME...INELEGANT 
    {

        $scores_labels = array(

            'name'              => _x( 'Scores', 'scores', \ScoringEngine::text_domain ),
            'singular_name'     => _x( 'Score', 'score', \ScoringEngine::text_domain ),
            'all_items'         => __( 'All Scores', \ScoringEngine::text_domain ),
            'parent_item'       => __( 'Parent Score', \ScoringEngine::text_domain ),
            'parent_item_colon' => __( 'Parent Score:', \ScoringEngine::text_domain),
            'edit_item'         => __( 'Edit Score', \ScoringEngine::text_domain ),
            'update_item'       => __( 'Update Score', \ScoringEngine::text_domain ),
            'add_new_item'      => __( 'Add New Score', \ScoringEngine::text_domain ),
            'new_item_name'     => __( 'New Score', \ScoringEngine::text_domain ),
            'menu_name'         => __( 'Scores', \ScoringEngine::text_domain ),
        );
        $scores_args = array(
            'hierarchical'          => true,
            'labels'                => $scores_labels,
            'show_ui'               => true,
            'show_admin_column'     => false,
            'update_count_callback' => '_update_post_term_count',
            'query_var'             => true,
            'rewrite'               => array( 'slug' => 'scores' ),
            'capabilities'          => self::$access_level,
            'meta_box_cb'       => false,
        );

        /*  */
        $this->register_taxonomy( 'obp_scores', self::$scored_posttypes, $scores_args )
             ->addScript( self::text_domain , self::get_plugin_url('/build/js/' . self::text_domain . '.js'), array('theme-js'), self::version, true )
             ->addStyle( self::text_domain , self::get_plugin_url('/build/css/' . self::text_domain . '.css'), array('theme-css'), self::version );

    }
    private function actionAddMeta($function)
    {
        \add_action( 'add_meta_boxes', function() use ($function){
            $function();
        });
    }
    public function addMetaBox($name, $title, $posttype, $function_name = 'render_meta_box', $textdomain = \ScoringEngine::text_domain, $context = 'side', $priority = 'default')
    {
        $this->actionAddMeta(function() use ($name, $title, $posttype, $function_name, $textdomain, $context, $priority){
            \add_meta_box(
                $name,
                \__( $title, $textdomain ),
                array( $this, $function_name ),
                $posttype,
                $context,
                $priority
            );
        });
    return $this;
    }
    private function actionSavePost($function)
    {
        \add_action( 'save_post', function() use ($function){
            $function();
        });
    }
    public function savePostFunction($function_name)
    {
        $this->actionSavePost(function() use($function_name){
            $this->$function_name();
        });
    return $this;
    }

    private function init($function) {
        \add_action('init', function() use ($function) {
            $function();
        });
     }
    private static function admin_init($function){
        \add_action('admin_init', function() use($function){
            $function;
        });
    }
    private function actionAfterSetup($function)
    {
        \add_action('after_setup_theme', function() use ($function) {
            $function();
        });
    }
    private function actionEnqueueScripts($function)
    {
        \add_action('wp_enqueue_scripts', function() use ($function){
            $function();
        });
    }
    private function adminActionEnqueueScripts($function)
    {
        \add_action('admin_enqueue_scripts', function() use ($function){
            $function();
        });
    }
    public function addPostTypeSupport ( $posttype, $supports )
    {
        $this->init(function() use ($posttype, $supports) {
            \add_post_type_support( $posttype, $supports );
        });
    return $this;
    }

    public function register_taxonomy( $name, $post_type = array(), $args )
    {
        $this->init(function() use ($name, $post_type, $args){
            \register_taxonomy( $name, $post_type, $args );
        });
        return $this;
    }
    public function addImageSize($name, $width = 0, $height = 0, $crop = false)
    {
        $this->actionAfterSetup(function() use ($name, $width, $height, $crop){
            \add_image_size($name, $width, $height, $crop);
        });
        return $this;
    }
    public function removeImageSize($name)
    {
        $this->actionAfterSetup(function() use ($name){
            \remove_image_size($name);
        });
        return $this;
    }    
    public function addStyle($handle,  $src = '',  $deps = array(), $ver = false, $media = 'all')
    {
        $this->actionEnqueueScripts(function() use ($handle, $src, $deps, $ver, $media){
            \wp_enqueue_style($handle,  $src,  $deps, $ver, $media);
        });
        return $this;
    }
    public function addScript($handle,  $src = '',  $deps = array(), $ver = false, $in_footer = false)
    {
        $this->actionEnqueueScripts(function() use ($handle, $src, $deps, $ver, $in_footer){
            \wp_enqueue_script($handle,  $src,  $deps, $ver, $in_footer);
        });
        return $this;
    }
    public function adminAddStyle($handle,  $src = '',  $deps = array(), $ver = false, $media = 'all')
    {
        if( \is_admin() ){
        $this->adminActionEnqueueScripts(function() use ($handle, $src, $deps, $ver, $media){
            \wp_enqueue_style($handle,  $src,  $deps, $ver, $media);
        });
        
        }
    return $this;
    }
    public function adminAddScript($handle,  $src = '',  $deps = array(), $ver = false, $in_footer = false)
    {
        if( \is_admin() ){
        $this->adminActionEnqueueScripts(function() use ($handle, $src, $deps, $ver, $in_footer){
            \wp_enqueue_script($handle,  $src,  $deps, $ver, $in_footer);
        });
        }
    return $this;
    }
    public function register_cpt($name, $args = array() ) 
    {
        $this->init(function() use ($name, $args) {
            \register_post_type( $name , $args );
        });
    return $this;
    }

    public function save_meta_box() //this is generic, see the admin setup page for real save functions
    {
        //Bail if we don't know the postid
        if( !isset($post_id) && isset($_POST['ID'] ) )
        {
            $post_id = $_POST['ID'];

        } else if(!isset($post_id) && !isset($_POST['ID']) ) return;
        // Bail if not our content types
        if ( !in_array($_POST['post_type'], self::$scored_posttypes ) ) return $post_id;
        // Check if our nonce is set.
        if ( !isset( $_POST['user_id_metabox_nonce'] ) ) return $post_id;

        $nonce = $_POST['user_id_metabox_nonce'];
 
        // Verify that the nonce is valid.
        if ( ! wp_verify_nonce( $nonce, 'user_id_metabox' ) ) return $post_id;
 
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return $post_id;
 
        // Check the user's permissions.
        if ( ! current_user_can( 'edit_post', $post_id ) ) return $post_id;
 
        // Sanitize the user input.
        $data = sanitize_text_field( $_POST['attached_user_id'] );
 
        // Update the meta field.
        if( is_numeric( $data ) ) 
        {
            update_post_meta( $post_id, 'attached_user_id', $data );
        }
        return $post_id;
    }     
}