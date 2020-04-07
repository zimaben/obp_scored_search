<?php
namespace obp_score\admin;
use \obp_score\Admin as Admin;

//spin it up
\obp_score\admin\Ajax::get_instance();

class Ajax extends \ScoringEngine{

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
    
    private function __construct() 
    {
        $this->addAjaxHandler('do_obp_scored_search' )
             ->addAjaxHandlerNoPriv('do_obp_scored_search' )
             ->addWPHead('obp_ajax_data');
             #->addAjaxHandler('unfeature_post');
    }

    private function addAjaxHandlerNoPriv( $function_name ){

        \add_action( 'wp_ajax_nopriv_' . $function_name, function() use( $function_name){
            self::$function_name();
        } );
    return $this;    
    }
    private function addAjaxHandler( $function_name ){

        \add_action( 'wp_ajax_' . $function_name, function() use( $function_name){
            self::$function_name();
        } );
    return $this;    
    }
    private function actionAfterSetup($function)
    {
        add_action('after_setup_theme', function() use ($function) {
            $function();
        });
    }
    public function addWPHead ( $handle = '' ) {
        $this->actionAfterSetup( function() use ( $handle )  {
            \add_action( 'wp_head', array( get_class(), $handle ) );
        });
    return $this;
    }

    public static function obp_ajax_data()
    {
        $ajaxurl = json_encode( \admin_url( 'admin-ajax.php') );
        $obp_ajax_nonce = json_encode( \wp_create_nonce( 'obp-ajax-data-nonce') );
        $obp_postid = json_encode( \get_queried_object_id() );
        $debug = json_encode( self::$debug );
        ?>
        <script>
            var obp_ajax_data = {
                ajaxurl: <?php echo $ajaxurl ?>,
                obp_ajax_nonce: <?php echo $obp_ajax_nonce ?>,
                post_id: <?php echo $obp_postid ?>,
                debug: <?php echo $debug ?>
            };
        </script>
        <?php
    }

    public static function get_scores( $post_id )
    {
        $score_meta_array = array();

        $postmeta = \get_post_meta( $post_id );

        foreach( $postmeta as $key=>$val ){
            if( strpos($key, 'obp_term_score_' ) === 0 )
            {
                $score_meta_array[$key] = $val[0];
            }
        }
    return empty($score_meta_array) ? false : $score_meta_array;
    }

    public static function sort_related_best( $results, $related, $related_id )
    {

        $base = 0;

        $index_array = array();

        $return_array = array();
        
        foreach( $related as $key=>$val ){ //sum total score
          
            $base += $val; 
        
        } 
        
        foreach( $related as $key=>$val ){
         
            $this_percentage = $val / $base;
         
            if( $this_percentage ) 
         
            {  
                $multiplier = 1 + $this_percentage;
         
                $index_array[$key] = $multiplier;

            } else {

                $index_array[$key] = 0;

            }

        }

        foreach( $results as $result ) {

            if( intval( $result->ID ) !== intval( $related_id ) ) #strip related posts target from return of posts
            {
                $result_postmeta = self::get_scores( $result->ID );
               
                $result_score = 0;
                
                if( $result_postmeta ){
                    
                    foreach( $result_postmeta as $k=>$v)
                    {
                        $this_multiplier = $index_array[$k]; //get the value of the multiplier
                        
                        $this_score = $v * $this_multiplier;

                        if( $this_score ){ $result_score += $this_score; }
                    }
                    
                }
                # get thumbnail image
                $attachment_id = \get_post_thumbnail_id( $result->ID  );

                $thumb = \wp_get_attachment_image_src( $attachment_id, $size = 'thumbnail', $icon = false );

                $post_url = \get_permalink( $result->ID );

                #weave the score and thumbnail property into the WP Post object
                $result = (object) array_merge( (array) $result, array( 
                    'obp_score' => $result_score,
                    'thumbnail_url' => $thumb[0],
                    'post_url' => $post_url,
                    ) );
                
                array_push( $return_array, (array) $result );   
            }
             
        }

        array_multisort (array_column($return_array, 'obp_score'), SORT_DESC, $return_array);

        return $return_array;       
    }
    public static function sort_related_closest( $results, $related, $related_id )
    {
        $return_array = array();

        $all_terms = \get_terms( array( 
            'taxonomy'      => 'obp_scores',
            'hide_empty'    => false,
        ));
        foreach ($results as $result)
        {
            if( intval( $result->ID ) !== intval( $related_id ) ) #strip related posts target from return of posts
            {
                $result_postmeta = self::get_scores( $result->ID );

                $master_result_score = 100;

                foreach( $all_terms as $this_term )
                {
                    $name = trim( str_replace(' ','_',$this_term->name) );

                    $key = 'obp_term_score_'.$name;
                    
                    $result_score = isset($result_postmeta[$key]) ? $result_postmeta[$key] : 0;

                    $related_score = isset($related[$key]) ? $related[$key] : 0;

                    $difference =  abs( $related_score - $result_score );

                    $master_result_score -= $difference;

                }
            # get thumbnail image
            $attachment_id = \get_post_thumbnail_id( $result->ID  );

            $thumb = \wp_get_attachment_image_src( $attachment_id, $size = 'thumbnail', $icon = false );

            $post_url = \get_permalink( $result->ID );

            #weave the score and thumbnail property into the WP Post object
            $result = (object) array_merge( (array) $result, array( 
                'obp_score' => $result_score,
                'thumbnail_url' => $thumb[0],
                'post_url' => $post_url,
                ) );

            array_push( $return_array, (array) $result ); 
            } 
        }

        array_multisort (array_column($return_array, 'obp_score'), SORT_DESC, $return_array);

    return $return_array;       
    }

    public static function do_obp_scored_search(){
        #validation happens before ajax request is made so no need to check if the arguments are missing
        error_log(print_r($_POST, true));
        if ( !\wp_verify_nonce( $_POST['nonce'], "obp-ajax-data-nonce")) {

            $response = array( 'type' => 'failure', 'message' => 'do_obp_scored_search function failed nonce verification');
            echo json_encode( $response );
            wp_die();
            exit;
         }  

        $the_args = unserialize(base64_decode( $_POST["args"] ));
        $obp_args = unserialize(base64_decode( $_POST['obpargs'] ));
        error_log(print_r($the_args, true));
        error_log(print_r($obp_args, true));
        $the_search = \get_posts( $the_args  );
        $related_score = self::get_scores( $obp_args['post_id'] );

        if( empty($related_score ) )
        {
            if( self::$debug )
            
            {
            
                error_log( 'OBP Related Score attempted for post_id:'.$obp_args['post_id'].' but there are no scores for the post.'  );
            
            }
            if( isset($obp_args['maximum_posts']))
            {
                $the_search = array_slice($the_search, 0, intval( $obp_args['maximum_posts'] ) ); 
            }
            $the_search['type'] = "success";
            
            echo json_encode( $the_search ); #no resorting necessary, return the post array
            
            wp_die();  //ajax calls, like surf nazis, must die
        }
        $searchtype = isset($obp_args["searchtype"]) ? $obp_args["searchtype"] : 'not_set';
        switch ( $searchtype ) {
            
            case "best":
            
                $new_results = self::sort_related_best( $the_search, $related_score, $obp_args['post_id'] );
            
                break;
            
            case "closest":
              
                $new_results = self::sort_related_closest( $the_search, $related_score, $obp_args['post_id'] );
              
                break;

            default:
                
                $function_name = 'sort_related_'.self::$default_search;
                
                $new_results = self::$function_name( $the_search, $related_score, $obp_args['post_id'] ); #PHP will attempt to execute a variable with parens as a function
        }
        if( isset($obp_args['maximum_posts']))
        {
            $new_results = array_slice($new_results, 0, intval( $obp_args['maximum_posts'] ) ); 
        }
        $new_results['type'] = "success";
        echo json_encode( $new_results );
        wp_die();  //ajax calls, like surf nazis, must die

    }

    public static function no_content_found()
    {
        $return = '<div class="container"><div class="row"><h4 class="fourohfour">We\'re sorry, we can\'t find any more content. </h4></div></div>';
        return $return;
    }
    public static function render_page_content( $the_id )
    {
       return \get_the_content( $the_id );
    }

}
