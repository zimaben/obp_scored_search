<?php 
namespace template\theme\view;
use \template\core\Core as Core;
use \tempate\core\Mobile_Detect as Mobile_Detect;
use \template\theme\MultiPostThumbnails as MultiPostThumbnails;
?>
<div class="container">
  <h1 class="row"><?php the_title(); ?></h1>
<!--<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="#">Activities</a></li>
    <li class="breadcrumb-item active"><a href="#">Category</a></li>
  </ol>
</nav> -->
<!-- FOR NOW NO BREADCRUMBS -->
  <div class="row">
  <?php   
  if( isset( $post ) ) {
      Core::featured_images_carousel( $post );
      } else {
        global $post;
        Core::featured_images_carousel( $post );
      } ?>
  </div>
  <div class="container">
    <div class="row">
      <div class="col-12 col-sm-7">
        <h6>Take the worry out of your transportation needs between Bali and Gili Air, or vice versa, and catch a fast boat to spend less time traveling and more time on the beach.</h6>
        
        <h4>About this Activity</h4>
        
        <ul class="list-group">
          <li class="list-group-item">About three hours</li>
          <li class="list-group-item">Payment Options</li>
          <li class="list-group-item">Instant Confirmation</li>
          <li class="list-group-item">Languages:
            <ul>
              <li>English</li>
              <li>Indonesian</li>
              <li>Chinese</li>
            </ul>
          </li>
          <li class="list-group-item">Transportation Options</li>
        </ul>
      </div><!-- end Activity Column1 -->
      <div class="col-12 col-sm-5">
        <div class="container buy-block">
          <div class="row h-100">
          <div class="col-6">
            <p>From</p>
            <h4>$00 </h4>
            <p>per person</p>
            </div>
          <div class="col-6 my-auto text-center">
            <button type="button" class="btn btn-primary">Book Now</button>
            
            </div>
          </div>
        </div>
        <div class="container">
          <div class="row">
            <div class="col-12">
            <ul class="list-group">
              <li class="list-group-item">Save for later</li>
              <li class="list-group-item">Group discounts available</li>
              <li class="list-group-item">Let's talk about it! (schedule a call)</li>
            </ul>
            </div>
          </div>
        </div>
        
      </div><!-- END COL 2 -->
      
    </div><!--end container-->
  </div><!-- END ACTIVITY (row)-->
</div><!-- END CONTAINER -->
<div class="container-fluid">
  <h4>You might also like...</h4>
  <div class="row">
    <!-- CARD -->
    <div class="card feature col-12 col-sm-3">
      <img class="card-img-top placeholder" src="http://localhost/bt/gili/wp-content/plugins/template-engine/assets/img/camera.svg" alt="Card image">
      <div class="card-img-overlay">
        <h4 class="card-title">Featured Thing</h4>
        <p class="card-text">Do this cool kickass thing dude...</p>
        <a href="#" class="btn btn-primary text-right">See Profile</a>
      </div>
    </div>
    <!-- END CARD -->
    <!-- CARD -->
    <div class="card feature col-12 col-sm-3">
      <img class="card-img-top placeholder" src="http://localhost/bt/gili/wp-content/plugins/template-engine/assets/img/camera.svg" alt="Card image">
      <div class="card-img-overlay">
        <h4 class="card-title">Featured Thing</h4>
        <p class="card-text">Do this cool kickass thing dude...</p>
        <a href="#" class="btn btn-primary text-right">See Profile</a>
      </div>
    </div>
    <!-- END CARD -->
    <!-- CARD -->
    <div class="card feature col-12 col-sm-3">
      <img class="card-img-top placeholder" src="http://localhost/bt/gili/wp-content/plugins/template-engine/assets/img/camera.svg" alt="Card image">
      <div class="card-img-overlay">
        <h4 class="card-title">Featured Thing</h4>
        <p class="card-text">Do this cool kickass thing dude...</p>
        <a href="#" class="btn btn-primary text-right">See Profile</a>
      </div>
    </div>
    <!-- END CARD -->
    <!-- CARD -->
    <div class="card feature col-12 col-sm-3">
      <img class="card-img-top placeholder" src="http://localhost/bt/gili/wp-content/plugins/template-engine/assets/img/camera.svg" alt="Card image">
      <div class="card-img-overlay">
        <h4 class="card-title">Featured Thing</h4>
        <p class="card-text">Do this cool kickass thing dude...</p>
        <a href="#" class="btn btn-primary text-right">See Profile</a>
      </div>
    </div>
    <!-- END CARD -->
  </div>
  <button type="button" class="btn btn-outline-primary row float-right">More Activities</button>
</div>
<div class="container">
  <h4>Hear from our customers...</h4>
  <div class="row">
        <!-- CARD -->
        <div class="card story col-12 col-sm-4">
          <div class="card-header container-fluid">
            <div class="row h-100">
              <div class="col-4 col-sm-12 col-lg-4 text-center"><i class="fas fa-user"></i></div>
              <div class="col-8 col-sm-12 col-lg-8">
                <h5 class="text-right">User Name</h5>
                <p class="lead">More believably long headline here...</p>


              </div>
            </div>
            </div>
            <div class="card-body">
            <p class="card-text">On the other hand, we denounce with righteous indignation and dislike men who are so beguiled and demoralized by the charms of pleasure of the moment, so blinded by desire, that they cannot foresee the pain and trouble that are bound to ensue.</p>
          </div>
          <div class="card-footer text-right small">reccomendation - hard yes</div>
        </div>
        <!-- END CARD -->
        <!-- CARD -->
        <div class="card story col-12 col-sm-4">
          <div class="card-header container-fluid">
            <div class="row h-100">
              <div class="col-4 col-sm-12 col-lg-4 text-center"><i class="fas fa-user"></i></div>
              <div class="col-8 col-sm-12 col-lg-8">
                <h5 class="text-right">User Name</h5>
                <p class="lead">More believably long headline here...</p>


              </div>
            </div>
            </div>
            <div class="card-body">
            <p class="card-text">On the other hand, we denounce with righteous indignation and dislike men who are so beguiled and demoralized by the charms of pleasure of the moment, so blinded by desire, that they cannot foresee the pain and trouble that are bound to ensue.</p>
          </div>
          <div class="card-footer text-right small">reccomendation - hard yes</div>
        </div>
        <!-- END CARD -->
        <!-- CARD -->
        <div class="card story col-12 col-sm-4">
          <div class="card-header container-fluid">
            <div class="row h-100">
              <div class="col-4 col-sm-12 col-lg-4 text-center"><i class="fas fa-user"></i></div>
              <div class="col-8 col-sm-12 col-lg-8">
                <h5 class="text-right">User Name</h5>
                <p class="lead">More believably long headline here...</p>


              </div>
            </div>
            </div>
            <div class="card-body">
            <p class="card-text">On the other hand, we denounce with righteous indignation and dislike men who are so beguiled and demoralized by the charms of pleasure of the moment, so blinded by desire, that they cannot foresee the pain and trouble that are bound to ensue.</p>
          </div>
          <div class="card-footer text-right small">reccomendation - hard yes</div>
        </div>
        <!-- END CARD -->

  </div>
  <button type="button" class="btn btn-outline-primary row float-right">More Stories</button>
</div>

    
