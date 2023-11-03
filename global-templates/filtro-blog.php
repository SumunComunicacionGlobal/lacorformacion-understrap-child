<?php

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

$terms = get_categories();
$page_for_posts_id = get_option( 'page_for_posts' );
$ver_todo_active_class = ( is_home() ) ? 'active' : '';
$queried_obj_id = get_queried_object_id();

if ( $terms ) { ?>

	<nav class="filter-navbar navbar navbar-expand-lg justify-content-center navbar-light mb-2">

		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#filter-navbar-collapse" aria-controls="filter-navbar-collapse" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-label mr-1"><?php echo __( 'Por temas', 'smn' ); ?></span> <span class="navbar-toggler-icon"></span>
		</button>

		<div class="collapse navbar-collapse" id="filter-navbar-collapse">

			<div class="nav navbar-nav mx-auto">

				<?php if ( $page_for_posts_id ) { ?>

					<a class="nav-item nav-link <?php echo $ver_todo_active_class; ?>" href="<?php echo esc_url( get_the_permalink( $page_for_posts_id ) ); ?>"><?php echo __( 'All' ); ?></a>

				<?php } ?>

				<?php foreach ( $terms as $term ) { 
					$active_class = ( $queried_obj_id == $term->term_id ) ? 'active' : '';
					?>

					<a class="nav-item nav-link <?php echo $active_class; ?>" href="<?php echo esc_url( get_term_link($term) ); ?>"><?php echo $term->name; ?></a>

				<?php } ?>

				<a class="nav-item nav-link nav-search-button" data-toggle="collapse" href="#search-form" aria-expanded="false" aria-controls="search-form">
					<?php echo __( 'Search' ); ?>
				</a>

			</div>

		</div>

	</nav>

	<div class="collapse" id="search-form">

		<div class="pb-2">

			<?php get_search_form(); ?>

		</div>

	</div>

<?php }

if ( is_home() ) {

	// Consulta para obtener solo los 3 posts fijos más recientes
	$sticky_posts_args = array(
		'post_type'      => 'post',
		'posts_per_page' => 3,
		'post__in'       => get_option('sticky_posts'),
		'ignore_sticky_posts' => 1,
	);

	$sticky_posts_query = new WP_Query($sticky_posts_args);

	if ($sticky_posts_query->have_posts()) {
		
		echo '<div class="h4 text-center col-12 mb-3 mt-3">' . __( 'Publicaciones destacadas', 'smn' ) . '</div>';
		
		while ($sticky_posts_query->have_posts()) {
			$sticky_posts_query->the_post();
			
			get_template_part( 'loop-templates/content', 'post-card', ['hide_excerpt' => 1 ] );
			
		}

		// Restaurar datos originales del loop principal
		wp_reset_postdata();
	}

	?>

	<div class="h4 text-center col-xs-12 mt-3 mb-3"><?php echo __( 'Publicaciones recientes', 'smn' ); ?></div>

<?php } ?>