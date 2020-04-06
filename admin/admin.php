<?php
namespace obp_score\admin;


//spin it up
\obp_score\admin\Admin::get_instance();

class Admin extends \ScoringEngine{

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
        # ADD THE RATINGS METABOX AND SAVE FUNCTIONS IF THE USER'S ROLE IS INCLUDED IN THE ACCESS LEVEL VARIABLE 
        if ( is_admin() ) {
            $user = \wp_get_current_user();
            $can_edit = false;
            foreach( $user->roles as $key=>$val){
                if( in_array( $val, self::$access_level, TRUE ) ){
                    $can_edit = true;
                }
            }

            if( $can_edit ) 
            {
                \add_action( 'add_meta_boxes', array( get_class(), 'obp_search_score_add' ) ); 

                \add_action( 'save_post', array( get_class(), 'obp_search_score_save' ) );
            }
        }            
    
    }
    
    public static function obp_search_score_add(){
        \add_meta_box(
            'obp-search-score',
            __( 'Scores', self::text_domain ),
            array( get_class(), 'obp_search_score_callback'),
            self::$scored_posttypes,
            'side',
            'default'
        );

    }
    public static function obp_search_score_callback(){
        //Do we have post ID?
        $post_id = isset($_GET['post']) ? $_GET['post'] : false;
        //If not, is it a new post?
        if(!$post_id){
            if( $_SERVER['PHP_SELF'] == '/wp-admin/post-new.php'){
                global $post;
                $post_id = $post->ID;
            }
        }
        if($post_id && isset($post_id)){

            $score_terms = \get_terms( array( 
                'taxonomy'      => 'obp_scores',
                'hide_empty'    => false,
            ));
            \wp_nonce_field( 'obp_search_score_nonce', 'obp_search_score_nonce' ); #add nonce field to check later
            echo '<h5>Please set the search scores for this item</h5>';
            echo '<div id="obp_admin_scores_container">';
            foreach( $score_terms as $score_term ){
                $name = trim( str_replace(' ','_',$score_term->name) );
                $previous_score = \get_post_meta( $post_id, 'obp_term_score_'.$name, true );
                $value = ( ! $previous_score || $previous_score == '' ) ? '0' : esc_attr( $previous_score );
                $steps = self::$allow_halves ? self::$score_base * 2 : self::$score_base;

                ?>
                    <div style="display:flex;flex-direction:row;justify-content:space-between;" id="post_score_<?php echo $name; ?>">
                        <label><?php echo $name; ?>:</label>
                        <select style="align-self:flex-end;" name="obp_term_score_<?php echo $name; ?>" id="obp_term_score_<?php echo $name; ?>">
                <?php

                #for( $i=0; $i < $steps; $i++ ){
                if( self::$allow_halves === true )
                    {
                        self::do_halfsteps( $value );
                    } else 
                        {
                            self::do_wholesteps ( $value );
                        }
                #}
                echo '</select>';
                echo '</div>'; //close score selectbox
            } //end search score terms
            echo '</div>';
        }   
        else 
            {
                echo 'Sorry, we couldn\'t find the Scores for this item.';
            }
    }
    public static function obp_search_score_save( $post_id ){

        // Check if our nonce is set & valid
        if ( ! isset( $_POST['obp_search_score_nonce'] ) ) {
            if( self::$debug ) error_log('Couldn\'t save OBP Score updates: Nonce not set' );
            return;
        }
        if ( ! wp_verify_nonce( $_POST['obp_search_score_nonce'], 'obp_search_score_nonce' ) ) {
            if( self::$debug ) error_log('Couldn\'t save OBP Score updates: Nonce failed' );
            return;
        }

        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {

            return;
        }
        if ( isset( $_POST['post_type'] ) /*&& \is_singular( self::$scored_posttypes ) */ ) {

            if ( ! current_user_can( 'edit_page', $post_id ) ) {
                if( self::$debug ) error_log('Couldn\'t save OBP Score updates: User does not have permissions' );
                return;
            }
             /*global $post_id available by default; */

            $all_terms = \get_terms( array( 
                'taxonomy'      => 'obp_scores',
                'hide_empty'    => false,
            ));
            foreach( $all_terms as $this_term ){
                $name = trim( str_replace(' ','_',$this_term->name) );
                if( isset( $_POST[ 'obp_term_score_'.$name ]) ){
                    $meta_key = 'obp_term_score_'.$name;
                    $meta_val = sanitize_text_field( $_POST[ $meta_key ] );
                    \update_post_meta( $post_id, $meta_key, $meta_val);
                }
            }
        } 
    }
    public static function do_halfsteps($value){
        $steps = self::$score_base * 2;
        $start = '0';
        ?>
            <option value="0"<?php echo $value ? '' : ' selected' ; ?>>0</option>
        <?php

        for($i=0;$i<$steps;$i++){
            if( strrpos( $start , '.') === false ) {
                $anustart = $start.'.5';
            } else {             
                $anustart = strval( round($start) ); //intval rounds up to nearest whole number if there is any half number
            }
            $start = $anustart;
            ?>
            <option value ="<?php echo $anustart .'"';if( $anustart == $value ) echo ' selected'; ?>><?php echo $anustart; ?>
            </option>
            <?php 
            }

    }
    public static function do_wholesteps($value){
        $steps = self::$score_base + 1; //allow for zero
        for($i=0;$i<$steps;$i++){
        ?>
            <option value ="<?php echo $i .'"';if( $i == $value ) echo ' selected'; ?>"><?php echo $i; ?>
            </option>
        <?php 
        }
    }    

}
