<?php
use \template\core\Core as Core;
use \tempate\core\Mobile_Detect as Mobile_Detect;

?>
<div class="container-fluid">
  <div class="row">
    <div class="col-12 jumbotron tall" style="background:url(http://localhost/bt/gili/wp-content/uploads/2019/09/wide3-1400x394.jpg) center/cover">
      <h1>Things to Do in Gili Trawangan</h1>
    </div>
  </div>
</div>
<!-- TODO CATEGORY DIFFERENTIATOR -->
<div class="container">
  <div class="row">
        
<?php 

        $args = array(
          'post_type' => 'activities',
          'posts_per_page' => 3,
          'meta_query' => array(
              array(
                  'key' => '_featured',
                  'value' => '1',
                  'compare' => '=',
                  'type' => 'NUMERIC',
              ),
          ),
        );
        $featured = new WP_Query( $args );

        if( $featured->have_posts() ) 
        {
          echo '<h4 class="row">Featured:</h4>';
        }

        while ($featured->have_posts() ) : $featured->the_post();
# DO RENDER
        $pid = get_the_ID(); #to save calls
        Core::main_card_render('activities', $pid );
        // End the loop.
       endwhile;

    wp_reset_postdata();
?>
  </div>
  <!-- TODO Tell us what you're into block -->
  <h4 class="row">TODO - Tell us what you're into block</h4>

<div class="container">
  <div class="row">
    <!-- START GENERAL LOOP -->
      <?php 
      if ( have_posts() ) : 
      // Start the Loop.
      while ( have_posts() ) : the_post();
        $pid = get_the_ID(); #to save calls
        Core::main_card_render('activities', $pid );
        // End the loop.
      endwhile;
        // Previous/next page navigation.
       # figure out pagination 
        // If no content, include the "No posts found" template.
      else :
         Core::no_content_found();
      endif;
      ?>
  </div>
</div>
    <!-- END LOOP -->



     <!-- START WEEKLY MODULE -->
      <div class="jumbotron">
        <div class="row">
        <div class="col-6">
        <h1 class="display-4">Let's Connect!</h1>
        <p class="lead">Want to know about shows, things to do, or events during your stay? Get the lowdown on cool island happenings direct from locals.</p>
        <hr class="my-4">
        <p>We won't contact after your stay, and we won't spam you with basic nonsense like gelato coupons. Our weekly event calendar is handpicked and tailored to your tastes.</p>
        <p class="lead d-flex justify-content-end">
              <a class="btn btn-primary btn-lg m-1" href="#" role="button">Let's do it</a>
          <a class="btn btn-primary btn-lg m-1" href="#" role="button">You don't know me</a>
              <a class="btn btn-primary btn-lg m-1" href="#" role="button">but...I love gelato</a>
        </p>
        </div>
        <div class="col-6 d-flex justify-content-center">
          
          <ul class="list-group my-auto">
        <li class="list-group-item tex-align-center"><i class="fab fa-whatsapp fa-2x center-fa-icon" style="vertical-align: middle;"></i> WhatsApp</li>
        <li class="list-group-item"><i class="far fa-comment-alt fa-2x center-fa-icon" style="vertical-align: middle;"></i> SMS</li>
        <li class="list-group-item"><i class="far fa-envelope fa-2x center-fa-icon" style="vertical-align: middle;"></i> Email</li>
      </ul>
        </div>
        </div>
      </div>
      <!-- END WEEKLY MODULE -->
  </div>
</div>