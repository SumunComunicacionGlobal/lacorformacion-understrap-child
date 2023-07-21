<?php

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

if ( !isset($args['post_ids']) || !$args['post_ids'] ) return false;

$posts_ids = $args['post_ids'];
$custom_class = ( isset($args['custom_class']) ) ? $args['custom_class'] : '';

if ( $posts_ids ) {

	$args = array(
		'post_type'			=> 'content_fragment',
		'post__in'			=> $posts_ids,
		'orderby'			=> 'post__in',
		'order'				=> 'ASC',
		'ignore_row'		=> true,
	);


	$q = new WP_Query($args);

	if ( $q->have_posts() ) {

		while ( $q->have_posts() ) { $q->the_post();

			global $post;

			if ( $post->post_content ) { ?>

				<div class="wrapper product-seo-content-wrapper">

					<?php the_content(); ?>

				</div>

			<?php } 
			
		}

	}

	wp_reset_postdata();

}