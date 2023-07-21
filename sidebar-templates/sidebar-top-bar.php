<?php

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;
?>

<div id="wrapper-top-bar" class="top-bar bg-white d-none d-xl-block">

    <div class="container">

        <div class="top-bar-widgets-wrapper d-flex justify-content-center justify-content-md-end">

            <a class="top-bar-search-button align-self-end" href="#search-collapse" data-toggle="collapse" title="<?php echo __( 'Search' ); ?>"><img src="<?php echo get_stylesheet_directory_uri(); ?>/img/search.svg" alt="<?php echo __( 'Search' ); ?>" width="20" height="20"></a>

            <div class="d-none d-md-flex align-self-end pb-1">
                <?php dynamic_sidebar( 'social' ); ?>
            </div>

            <?php dynamic_sidebar( 'top-bar' ); ?>

            <?php if ( class_exists( 'WooCommerce') ) { 

                $items_count = WC()->cart->get_cart_contents_count(); ?>

                <a class="cart-button" href="<?php echo get_permalink( wc_get_page_id( 'cart' ) ); ?>" title="<?php echo get_the_title( wc_get_page_id( 'cart' ) ); ?>"><img src="<?php echo get_stylesheet_directory_uri(); ?>/img/cart.svg" alt="<?php echo get_the_title( wc_get_page_id( 'cart' ) ); ?>" width="24" height="24">

                    <?php echo $items_count ? '<span class="cart-count">' . $items_count . '</span>' : ''; ?>

                </a>

            <?php } ?>

        </div>

    </div>

</div>

<div class="collapse bg-warning" id="search-collapse">

    <div class="wrapper">

        <div class="container">

                <?php if ( class_exists( 'woocommerce') ) {
                    get_product_search_form();
                } else {
                    get_search_form();
                } ?>

        </div>

    </div>

</div>
