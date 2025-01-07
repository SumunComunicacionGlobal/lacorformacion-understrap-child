<?php

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

$blog_page_id = get_option( 'page_for_posts' );

$args = array(
	'posts_per_page'	=> 4,
	'ignore_sticky_posts' => 1,
);

if ( wp_is_mobile() ) {
	$args['posts_per_page'] = 2;
}

$q = new WP_Query($args);

if ( $q->have_posts() ) { ?>

	<div class="wrapper hfeed blog-block" id="wrapper-blog">

		<div class="row">

			<?php if ( is_active_sidebar( 'blog-block-intro' ) ) { ?>

			<div class="col-md-4 align-self-center">

				<?php dynamic_sidebar( 'blog-block-intro' ); ?>
		
			</div>

			<?php } ?>

			<?php while ( $q->have_posts() ) { $q->the_post();

				get_template_part( 'loop-templates/content', 'post-card' );

			} ?>

			<div class="col-md-4 align-self-center">

				<?php if ( $blog_page_id ) { ?>
					<div class="wp-block-buttons d-flex justify-content-end">
						<div class="wp-block-button is-style-border-double">
							<a class="wp-block-button__link has-background has-secondary-background-color has-white-color has-text-color" href="<?php echo get_permalink( $blog_page_id ); ?>"><?php echo __( 'Ver más noticias', 'smn' ); ?></a>
						</div>
					</div>
				<?php } ?>

			</div>
		</div>

	</div>

<?php }

wp_reset_postdata();
