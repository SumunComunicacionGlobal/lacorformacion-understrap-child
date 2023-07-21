<?php
/**
 * Header Navbar (bootstrap4)
 *
 * @package Understrap
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

$container = get_theme_mod( 'understrap_container_type' );
$navbar_class = 'bg-white navbar-light';
?>

<nav id="main-nav" class="navbar navbar-expand-xl <?php echo $navbar_class; ?>" aria-labelledby="main-nav-label">

	<p id="main-nav-label" class="screen-reader-text">
		<?php esc_html_e( 'Main Navigation', 'understrap' ); ?>
	</p>


<?php if ( 'container' === $container ) : ?>
	<div class="container">
<?php endif; ?>

		<!-- Your site title as branding in the menu -->
		<?php if ( file_exists( get_stylesheet_directory() . '/img/logo-dark.svg' ) && file_exists( get_stylesheet_directory() . '/img/logo-light.svg' ) ) { ?>
			
			<a class="navbar-brand" rel="home" href="<?php echo esc_url( home_url( '/' ) ); ?>" itemprop="url">
				<img class="logo-dark" src="<?php echo get_stylesheet_directory_uri(); ?>/img/logo-smn-dark.svg" alt="<?php bloginfo( 'name' ); ?>" />
				<img class="logo-light" src="<?php echo get_stylesheet_directory_uri(); ?>/img/logo-smn-light.svg" alt="<?php bloginfo( 'name' ); ?>" />
			</a>

		<?php } elseif( file_exists( get_stylesheet_directory() . '/img/logo.svg' ) ) { ?>
			
			<a class="navbar-brand" rel="home" href="<?php echo esc_url( home_url( '/' ) ); ?>" itemprop="url">
				<img class="logo-dark" src="<?php echo get_stylesheet_directory_uri(); ?>/img/logo.svg" alt="<?php bloginfo( 'name' ); ?>" />
			</a>

		<?php } elseif ( ! has_custom_logo() ) { ?>
			
			<?php if ( is_front_page() && is_home() ) : ?>

				<h1 class="navbar-brand mb-0"><a rel="home" href="<?php echo esc_url( home_url( '/' ) ); ?>" itemprop="url"><?php bloginfo( 'name' ); ?></a></h1>

			<?php else : ?>

				<a class="navbar-brand" rel="home" href="<?php echo esc_url( home_url( '/' ) ); ?>" itemprop="url"><?php bloginfo( 'name' ); ?></a>

			<?php endif; ?>

		<?php
		} else {
			the_custom_logo();
		}
		?>
		<!-- end custom logo -->

		<div class="d-flex">

			<?php if ( class_exists( 'WooCommerce') ) { 

			$items_count = WC()->cart->get_cart_contents_count(); ?>

				<a class="cart-button mr-2 d-xl-none" href="<?php echo get_permalink( wc_get_page_id( 'cart' ) ); ?>" title="<?php echo get_the_title( wc_get_page_id( 'cart' ) ); ?>"><img src="<?php echo get_stylesheet_directory_uri(); ?>/img/cart-dark.svg" alt="<?php echo get_the_title( wc_get_page_id( 'cart' ) ); ?>" width="24" height="24">

					<?php echo $items_count ? '<span class="cart-count">' . $items_count . '</span>' : ''; ?>

				</a>

			<?php } ?>

			<button class="navbar-toggler collapsed" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="<?php esc_attr_e( 'Toggle navigation', 'understrap' ); ?>">
				<span class="navbar-toggler-icon"></span>
			</button>

		</div>

		<div class="collapse navbar-collapse" id="navbarNavDropdown">

			<div class="d-xl-none mt-2">
			
				<?php if ( class_exists( 'woocommerce') ) {
					get_product_search_form();
				} else {
					get_search_form();
				} ?>
			
			</div>

			<!-- The WordPress Menu goes here -->
			<?php
			wp_nav_menu(
				array(
					'theme_location'  => 'primary',
					'container_class' => 'ml-auto',
					'container_id'    => '',
					'menu_class'      => 'navbar-nav',
					'fallback_cb'     => '',
					'menu_id'         => 'main-menu',
					// 'depth'           => 2,
					// 'walker'          => new Understrap_WP_Bootstrap_Navwalker(),
				)
			);
			?>

			<div class="d-xl-none">
				<?php dynamic_sidebar( 'top-bar' ); ?>
			</div>


			<div class="d-flex d-xl-none my-3">
				<?php dynamic_sidebar( 'social' ); ?>
			</div>

		</div>

<?php if ( 'container' === $container ) : ?>
	</div><!-- .container -->
<?php endif; ?>

</nav><!-- .site-navigation -->
