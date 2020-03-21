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
            var containerid = "<?php echo $obp_args['container_id'] ?>" ;
            jQuery.ajax({
                type : "post",
                dataType : "json",
                url : obp_ajax_data.ajaxurl,
                data : { action: "do_obp_scored_search", 
                         args : <?php echo json_encode( $args ) ?>, 
                         obpargs: <?php echo json_encode( $obp_args ) ?> ,
                         nonce: obp_ajax_data.obp_ajax_nonce,
                         postid: obp_ajax_data.post_id, },
                success: function(response) {
                    if(response.type == "success") {
                        console.log(response);
                        jQuery.each(response, function(index, item) {

                            render_related_posts( item, containerid );

                        });
                    } else {
                        if( obp_ajax_data.debug === 'true' ){
                            if(typeof response.message !== 'undefined') {
                                console.log( response.message );
                            } else { console.log('No AJAX response from do_obp_scored_search'); }
                        }
                    }
                }
            }) 

        </script>
        <?php

    }

}
