<?php
namespace obp_score\theme;

//spin it up
\obp_score\theme\ScoreTheme::get_instance();


class ScoreTheme extends \ScoringEngine {

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


    }

    public static function obp_scored_search( $args = array(), $obp_args = array() )  
    {
        
        if( empty( $args ) ) { #bounce early if nothing to do
        
            if( self::$debug ) error_log('OBP Scored Search called with no search arguments');
        
            return false; 
        
        } 
        if( empty( $obp_args ) ) { #bounce early if nothing to do
        
            if( self::$debug ) error_log('OBP Scored Search called with no obp arguments. Refer to documentation');
        
            return false; 
        
        } 
        if( !isset( $obp_args['container_id'] ) ) { #nowhere to render results
        
            if( self::$debug ) error_log('OBP Scored Search called without a container ID to put results. Refer to documentation');
        
            return false; 
        
        } 
        
        ?>
        <script>
            let containerid = "<?php echo $obp_args['container_id'] ?>" ;
            let loadingimg = "<?php echo self::$logo_url ?>";
            var request = new XMLHttpRequest();
                //AJAX request type, url, & handler function
                request.open('POST', obp_ajax_data.ajaxurl + '?action=do_obp_scored_search', true);
                //AJAX dataType header 
                request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
                //So we don't need to parse or decode later
                request.responseType = 'json';
            // @Todo...this is a somewhat rickety way to pass params. Switch to full JSON payload in the future
            var params = 'args=' +  <?php echo "'" . base64_encode(serialize($args)) . "'" ?>;
                params += '&obpargs=' + <?php echo "'" . base64_encode(serialize($obp_args)) . "'" ?>;
                params += '&nonce=' + obp_ajax_data.obp_ajax_nonce;
                params += '&postid=' + obp_ajax_data.post_id;   
                        
                //Start Spinner
                request.onloadstart = function() {
                    render_loading_graphic( containerid, loadingimg );
                }
                //Response received, render query results
                request.onload = function() {
                    stop_loading_graphic( containerid );
                    if(request.response.type == "success") {
                        //render functions at /build/js/scoring_engine.js
                        for (let [index, item] of Object.entries(request.response)) {

                            render_related_posts( item, containerid );
                        }
                            
                    } else {
                        if( obp_ajax_data.debug === 'true' ){
                            if(typeof request.response.message !== 'undefined') {
                                console.log( request.response.message );
                            } else { console.log('No AJAX response from do_obp_scored_search'); }
                        }
                    }
                }

                request.ontimeout = () => {
                    if( obp_ajax_data.debug === 'true' ) console.log('Request from do_obp_related_search timed out')
                };

                request.send( params );

        </script>
        <?php

    }

}
