<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after
 *
 * @package Understrap
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;
?>

<?php get_template_part( 'sidebar-templates/sidebar', 'prefooter' ); ?>

<?php get_template_part( 'sidebar-templates/sidebar', 'footerfull' ); ?>

<div class="container" id="footer-content">

	<div class="wrapper" id="wrapper-footer">
	
		<footer class="site-footer" id="colophon">

			<nav id="legal-nav" class="navbar navbar-expand navbar-light p-0" aria-labelledby="legal-nav-label">

				<p id="legal-nav-label" class="screen-reader-text">
					<?php esc_html_e( 'Legal Navigation', 'understrap' ); ?>
				</p>

				<?php wp_nav_menu( array(
					'theme_location'		  => 'legal',
					'container_class' => 'collapse navbar-collapse',
					'container_id'    => 'navbarLegal',
					'menu_class'      => 'navbar-nav flex-wrap font-weight-bold',
					'fallback_cb'     => '',
					'menu_id'         => 'legal-menu',
					'depth'           => 1,
					'walker'          => new Understrap_WP_Bootstrap_Navwalker(),
				) ); ?>

			</nav>

			<div class="site-info">

				<?php understrap_site_info(); ?>

			</div><!-- .site-info -->

		</footer><!-- #colophon -->

	</div><!-- wrapper end -->

</div><!-- container end -->

</div><!-- #page we need this extra closing tag here -->

<?php wp_footer(); ?>

</body>

</html>

