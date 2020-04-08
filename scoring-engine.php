<?php
/* 
 * Site Templating Engine
 *
 * @package         obp_scoring
 * @author          Ben Toth
 * @license         GPL-2.0+
 * @link            https://ben-toth.com
 * @copyright       2019 Ben Toth
 *
 * @wordpress-plugin
 * Plugin Name:     OBP Scoring Engine
 * Plugin URI:      https://ben-toth.com
 * Description:     Registers a Scoring taxonomy and allows search & filter based on those scores
 * Version:         1.0.0
 * Author:          Ben Toth
 * Author URI:      https://ben-toth.com
 * License:         GPL
 * Copyright:       Ben Toth
 * Class:           ScoringEngine
 * Text Domain:     obp_scoring
 * Domain Path:     /languages
*/

defined( 'ABSPATH' ) OR exit;

if ( ! class_exists( 'ScoringEngine' ) ) {

    register_activation_hook( __FILE__, array ( 'ScoringEngine', 'register_activation_hook' ) );    
    add_action( 'plugins_loaded', array ( 'ScoringEngine', 'get_instance' ), 5 );
    
    class ScoringEngine {
 
        private static $instance = null;

        // Plugin Settings Generic
        const version = '1.0.0';
        static $debug = true; //turns PHP and javascript logging on/off
        const text_domain = 'engine-scoring'; // for translation ##
        const js_domain = 'scoring_engine';

        //Plugin Options
        static $scored_posttypes = array('posttype_1', 'posttype_2'); //MUST BE ARRAY
        static $score_base = 5; //base 10 is a scale between 0 and 10 etc.
        static $allow_halves = true; // turn half steps on/off
        static $access_level = array( 'administrator' ); // roles that can edit scores
        static $logo_url = ''; //the logo image animated between searches (please use circular image)
        static $default_search = 'best'; //default either 'closest' or 'best'
        static $query_size = -1; // the default is -1, which means query the whole DB for related posts before paring down


        /**
         * Creates or returns a single instance of this class
         */
        public static function get_instance() 
        {

            if ( 
                null == self::$instance 
            ) {

                self::$instance = new self;

            }

            return self::$instance;

        }
        

        private function __construct() 
        {

            // deactvation ##
            \register_deactivation_hook( __FILE__, array ( $this, 'register_deactivation_hook' ) );

            // set text domain ##
            \add_action( 'init', array( $this, 'load_plugin_textdomain' ), 1 );

            #execute deactivation options
            \add_action( 'wp_ajax_deactivate', array( $this, 'deactivate_callback') );

            // load libraries ##
            self::load_libraries();

            // enqueue scripts & styles


        }      
        public static function register_activation_hook() 
        {
            $option = self::text_domain . '-version';
            \update_option( $option, self::version );

        }

        public static function deactivate_callback( $delete_table )
        {

        }

        public function register_deactivation_hook() 
        {
            
            $option = self::text_domain . '-version';
            \delete_option( $option );
            
        }

        public function load_plugin_textdomain() 
        {
            
            // set text-domain ##
            $domain = self::text_domain;
            
            // The "plugin_locale" filter is also used in load_plugin_textdomain()
            $locale = apply_filters('plugin_locale', get_locale(), $domain);

            // try from global WP location first ##
            load_textdomain( $domain, WP_LANG_DIR.'/plugins/'.$domain.'-'.$locale.'.mo' );
            
            // try from plugin last ##
            load_plugin_textdomain( $domain, FALSE, plugin_dir_path( __FILE__ ).'library/language/' );
            
        }

        public static function get_plugin_url( $path = '' ) 
        {

            return plugins_url( $path, __FILE__ );

        }
        
        public static function get_plugin_path( $path = '' ) 
        {

            return plugin_dir_path( __FILE__ ).$path;

        }

		private static function load_libraries()
        {

            // backend ##
            require_once self::get_plugin_path( 'admin/admin.php' );
            require_once self::get_plugin_path( 'admin/setup.php'); //REGISTER IMAGE SIZES, CUSTOM POST TYPES, ETC
            require_once self::get_plugin_path( 'admin/ajax.php' );

            // frontend ##
            require_once self::get_plugin_path( 'theme/theme.php' ); //FRONTEND THEME FUNCTIONS

        }

    }

}
