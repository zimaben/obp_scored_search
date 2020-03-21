  
<div class="container">
  <h1 class="row"><?php the_title(); ?></h1>

	<section class="container content-area">
		<main id="main" class="site-main">

			<?php
			/* Start the Loop */
			while ( have_posts() ) :
				the_post();

				the_content();

			endwhile; // End of the loop.
			?>

		</main><!-- #main -->
	</section><!-- #primary -->

</div>