<?php

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

$post_type = 'testimonio';

$args = array(
	'post_type'			=> $post_type,
	'orderby'			=> 'rand',
	'posts_per_page'	=> 5
);

$q = new WP_Query($args);

if ( $q->have_posts() ) { ?>

	<div class="wrapper">

		<div class="slick-slider slider-testimonios">

			<?php while ( $q->have_posts() ) { $q->the_post();

				get_template_part( 'loop-templates/content', $post_type );

			} ?>

		</div>

	</div>

<?php }

wp_reset_postdata();
