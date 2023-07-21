<?php 

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/* Activar PayPal estandar en WooCommerce */ 
add_filter( 'woocommerce_should_load_paypal_standard', '__return_true' );

function smn_remove_gallery_support() {
    remove_theme_support( 'wc-product-gallery-zoom' );
    remove_theme_support( 'wc-product-gallery-lightbox' );
    remove_theme_support( 'wc-product-gallery-slider' );
}
add_action( 'wp', 'smn_remove_gallery_support', 100 );

add_filter( 'woocommerce_cart_item_thumbnail', '__return_false' );

add_filter( 'woocommerce_product_description_heading', '__return_null' );

// add_filter( 'woocommerce_product_subcategories_hide_empty', '__return_false' );

// add_filter( 'woocommerce_taxonomy_args_product_cat', function( $tax_args ) { $tax_args['hierarchical'] =false; return $tax_args;} );

remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );

add_filter('gettext', 'smn_translate_woocommerce', 10, 3);
add_filter('ngettext', 'smn_translate_woocommerce', 10, 3);
function smn_translate_woocommerce($translation, $text, $domain) {
   if ($domain == 'woocommerce') {
        switch ($text) {
            case 'SKU':
                $translation = __( 'Referencia', 'smn' );
                break;
            case 'SKU:':
                $translation = __( 'Referencia:', 'smn' );
                break;
            case 'Search products&hellip;':
                $translation = __( 'Buscar curso…', 'smn' );
                break;
            case '%s customer review':
                $translation = __( '% opinión', 'smn');
                break;
            case '%s customer reviews':
                $translation = __( '% opiniones', 'smn');
                break;
            case 'Reviews':
                global $product;
                if ( $product ) {
                    $translation = sprintf( __( 'Opiniones del curso %s', 'smn' ), $product->get_title() );
                }
                break;
            case 'Browse products':
                $translation = __( 'Explorar los cursos', 'smn');
                break;
            }
   }
    return $translation;
}

add_filter('loop_shop_columns' , 'smn_loop_columns', 999);
function smn_loop_columns( $columns ) { 

    if ( smn_term_has_children( get_queried_object_id() ) )
        return 2;

    return $columns;
} 

add_filter( 'woocommerce_product_single_add_to_cart_text', 'smn_custom_cart_button_text' ); 
add_filter( 'woocommerce_product_add_to_cart_text', 'smn_custom_cart_button_text' );
function smn_custom_cart_button_text( $text ) {
   global $product;       
   if ( $product && ! $product->is_in_stock() ) {           
        return __( 'Detalles', 'smn' );
   } else {
        return __( 'Matricúlate ya', 'smn' );
   }
   return $text;
}

add_action( 'woocommerce_after_add_to_cart_form', 'smn_text_next_add_to_cart' );
function smn_text_next_add_to_cart() {
    $text = get_field( 'text_next_add_to_cart', 'option' );
    if ( $text ) {
        echo '<span class="text-next-add-to-cart">'.$text.'</span>';
    }
}

add_filter( 'woocommerce_variable_sale_price_html', 'smn_hide_price_if_out_stock_frontend', 10, 2 );
add_filter( 'woocommerce_variable_price_html', 'smn_hide_price_if_out_stock_frontend', 10, 2 );
add_filter( 'woocommerce_get_price_html', 'smn_hide_price_if_out_stock_frontend', 9999, 2 );
 function smn_hide_price_if_out_stock_frontend( $price, $product ) {
   if ( is_admin() ) return $price; // BAIL IF BACKEND
   if ( ! $product->is_in_stock() ) {
      $price = apply_filters( 'woocommerce_empty_price_html', '', $product );
   }
   return $price;
}

add_filter( 'woocommerce_get_stock_html', function ( $html, $product ) {
    return '';
  }, 10, 2);

remove_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10 );
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5 );
remove_action ( 'woocommerce_before_subcategory', 'woocommerce_template_loop_category_link_open', 10 );
remove_action ( 'woocommerce_after_subcategory', 'woocommerce_template_loop_category_link_close', 10 );

add_action( 'woocommerce_before_shop_loop_item', 'smn_woocommerce_product_card_begin', 0 );
function smn_woocommerce_product_card_begin() {
    echo '<div class="card stretch-linked-block">';
}

add_action( 'woocommerce_before_shop_loop_item_title', 'smn_woocommerce_product_card_body_begin', 11);
function smn_woocommerce_product_card_body_begin() {
    echo '<div class="card-body">';
        echo '<div class="card-fixed-height-intro">';
}

add_action( 'woocommerce_after_shop_loop_item_title', function() {
    echo '</div>'; // .card-fixed-height-intro
}, 7 );


add_action( 'woocommerce_after_shop_loop_item', 'smn_woocommerce_product_card_end', 0 );
function smn_woocommerce_product_card_end() {
    echo '</div>'; // .card-body
    echo '</div>'; // .card
}



if ( ! function_exists( 'woocommerce_template_loop_product_title' ) ) {

    /**
     * Show the product title in the product loop. By default this is an H2.
     */
    function woocommerce_template_loop_product_title() {
        woocommerce_template_loop_product_link_open();
            echo '<h3 class="' . esc_attr( apply_filters( 'woocommerce_product_loop_title_classes', 'woocommerce-loop-product__title' ) ) . '">' . get_the_title() . '</h3>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        woocommerce_template_loop_product_link_close();
    }
}

if ( ! function_exists( 'woocommerce_template_loop_category_title' ) ) {

    function woocommerce_template_loop_category_title( $category ) {
        woocommerce_template_loop_category_link_open( $category );
        ?>

        <h3 class="woocommerce-loop-category__title">
            <?php
                echo esc_html( $category->name );

            // if ( $category->count > 0 ) {
            //     // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            //     echo apply_filters( 'woocommerce_subcategory_count_html', ' <mark class="count">(' . esc_html( $category->count ) . ')</mark>', $category );
            // }
            ?>
        </h3>
        <?php
        woocommerce_template_loop_category_link_close( $category );
    }

}

//Add new custom product tabs
add_filter( 'woocommerce_product_tabs', 'smn_custom_product_tabs' );

function smn_custom_product_tabs( $tabs ) {

    if ( isset($tabs['reviews']) ) unset($tabs['reviews']);

    $curso_tabs = array(
        'description'            => __( 'Descripción', 'smn' ),
        'objectives'            => __( 'Objetivos', 'smn' ),
        'theoretical_contents'  => __( 'Teoría', 'smn' ),
        'practical_contents'    => __( 'Práctica', 'smn' ),
    );

    foreach ( $curso_tabs as $tab_key => $tab_name ) {

        $tab_content = get_post_meta( get_the_ID(), 'curso_tab_' . $tab_key, true );

        if ( $tab_content ) {

            $tabs[$tab_key] = array(
            'title' => $tab_name,
            'priority' => 50,
            'callback'  => function( $tab_key, $tab_content ) {
                                echo get_post_meta( get_the_ID(), 'curso_tab_' . $tab_key, true );
                            }
            );

        }

    }

    return $tabs;

}

add_action( 'woocommerce_after_single_product_summary', 'smn_cta_curso', 12 );
function smn_cta_curso() {
    global $product;

    if ( $product->is_in_stock() && is_active_sidebar( 'cta-product' ) ) { ?>

        <?php dynamic_sidebar( 'cta-product' ); ?>

    <?php }

    global $post;

    // if ( $post->post_content ) {

        echo '<div class="wrapper product-seo-content-wrapper alignnarrow">';

            the_content();

            // comments_template();
            echo do_shortcode( '[testimonios]' );

        echo '</div>';

    // }


}

add_action( 'woocommerce_after_single_product_summary', 'smn_after_related_products', 30 );
function smn_after_related_products() {

    smn_faqs();

}

add_action( 'admin_footer', 'smn_wc_disable_product_fields' );
function smn_wc_disable_product_fields() { 

    $current_screen = get_current_screen();
    if ( $current_screen->base !== 'post' && $current_screen->post_type !== 'product' ) return false;
    ?>

    <script>
        // jQuery('.wc_input_price, #_sku, #_stock_status, #_manage_stock, #_sold_individually').prop('disabled', true);
        jQuery('.wc_input_price, #_sku, #_stock_status, #_manage_stock').prop('disabled', true);
    </script>


<?php }


function smn_woocommerce_catalog_orderby( $orderby ) {
    unset($orderby['price']);
    unset($orderby['popularity']);
    unset($orderby['price-desc']);

    $orderby['menu_order'] = __( 'Más relevantes', 'smn' );
    $orderby['rating'] = __( 'Mejor valorados', 'smn' );
    $orderby['date'] = __( 'Más recientes', 'smn' );
    
    return $orderby;
}
add_filter( 'woocommerce_catalog_orderby', 'smn_woocommerce_catalog_orderby', 20 );

add_filter('woocommerce_product_related_products_heading',function(){

    return __( 'También te puede interesar', 'smn' );
 
 });

 add_filter('woocommerce_product_upsells_products_heading',function(){

    return __( 'También te puede interesar', 'smn' );
 
 });
 

 function smn_faqs() {

    $title = false;

    if ( is_singular() ) {
        $title = sprintf( __( 'Preguntas frecuentes sobre el curso %s', 'smn' ), get_the_title() );
    }

    if ( have_rows( 'faqs') ) {

        echo '<div class="wrapper">';

            if ( $title ) echo '<h3 class="mb-3">'. $title .'</h3>';

            while( have_rows( 'faqs' ) ) : the_row();

                $pregunta = get_sub_field('pregunta');
                $respuesta = get_sub_field('respuesta');

                echo '<p class="faq-title h5">'.$pregunta.'</p>';
                echo '<div class="faq-content">'.$respuesta.'</div>';

            endwhile;

        echo '</div>';

    }

 }

 function smn_dynamic_select_field_values ( $scanned_tag, $replace ) {  
  
    $tags = array(
        'your-curso',
        'your-curso-presencial',
        'your-curso-online',
        'your-curso-subvencionado',
    );
    if ( !in_array( $scanned_tag['name'], $tags) )  
        return $scanned_tag;

    $args = array ( 
        'post_type' => 'product',  
        'numberposts' => -1,  
        'orderby' => 'title',  
        'order' => 'ASC' 
    );

    if ( 'your-curso-presencial' == $scanned_tag['name'] ) {
        $args['tax_query'] = array(array(
            'taxonomy'          => 'product_cat',
            'terms'             => 53,
        ));
    } elseif ( 'your-curso-online' == $scanned_tag['name'] ) {
        $args['tax_query'] = array(array(
            'taxonomy'          => 'product_cat',
            'terms'             => 54,
        ));
    } elseif ( 'your-curso-subvencionado' == $scanned_tag['name'] ) {
        $args['tax_query'] = array(array(
            'taxonomy'          => 'product_cat',
            'terms'             => 55,
        ));
    }

    $rows = get_posts( $args );  
  
    if ( ! $rows )  
        return $scanned_tag;

    foreach ( $rows as $row ) {  
        $scanned_tag['raw_values'][] = $row->post_title . '|' . $row->post_title;
    }

    $pipes = new WPCF7_Pipes($scanned_tag['raw_values']);

    $scanned_tag['values'] = $pipes->collect_befores();
    $scanned_tag['labels'] = $pipes->collect_afters();
    $scanned_tag['pipes'] = $pipes;
  
    return $scanned_tag;  
}  

add_filter( 'wpcf7_form_tag', 'smn_dynamic_select_field_values', 10, 2); 

function smn_dynamic_select_categorias_cursos_field_values ( $scanned_tag, $replace ) {  
  
    $tags = array(
        'your-categoria-curso',
    );
    if ( !in_array( $scanned_tag['name'], $tags) )  
        return $scanned_tag;

    $args = array ( 
        'taxonomy'      => 'product_cat',  
        'parent'        => 53,
        'hide_empty'    => false  
    );

    $rows = get_terms( $args );

    if ( !$rows )
        return $scanned_tag;

    foreach ( $rows as $row ) {  
        $scanned_tag['raw_values'][] = $row->name . '|' . $row->name;
    }

    $pipes = new WPCF7_Pipes($scanned_tag['raw_values']);

    $scanned_tag['values'] = $pipes->collect_befores();
    $scanned_tag['labels'] = $pipes->collect_afters();
    $scanned_tag['pipes'] = $pipes;
  
    return $scanned_tag;  
}  
add_filter( 'wpcf7_form_tag', 'smn_dynamic_select_categorias_cursos_field_values', 10, 2); 

remove_action( 'wp_footer', 'woocommerce_demo_store' );
add_action ( 'wp_body_open', 'woocommerce_demo_store' );

add_filter( 'woocommerce_demo_store', 'smn_custom_woocommerce_demo_store', 10, 2 );
function smn_custom_woocommerce_demo_store( $output, $notice ) {

    if ( is_active_sidebar( 'footer-ad' ) ) :
        ob_start();
        dynamic_sidebar( 'footer-ad' );
        $footer_ad = ob_get_clean();
        
    endif;

    $notice_id = md5( $notice );
    $output = '<div class="woocommerce-store-notice" data-notice-id="' . esc_attr( $notice_id ) . '" style="display:none;" id="footer-ad">';
        $output .= '<div class="container">';
            $output .= '<a href="#" class="close woocommerce-store-notice__dismiss-link"><span aria-hidden="true">&times;</span></a>';
            $output .= $footer_ad;
            $output .= '<p class="demo_store">' . wp_kses_post( $notice ) . ' </p>';
            // $output .= '<p><a href="#" class="woocommerce-store-notice__dismiss-link">' . esc_html__( 'Close', 'woocommerce' ) . '</a></p>';
        $output .= '</div>';
    $output .= '</div>';

    return $output;

}

// enable gutenberg for woocommerce
function smn_activate_gutenberg_product( $can_edit, $post_type ) {
    if ( $post_type == 'product' ) {
           $can_edit = true;
       }
       return $can_edit;
}
add_filter( 'use_block_editor_for_post_type', 'smn_activate_gutenberg_product', 10, 2 );
   
// enable taxonomy fields for woocommerce with gutenberg on
function smn_enable_taxonomy_rest( $args ) {
    $args['show_in_rest'] = true;
    return $args;
}
add_filter( 'woocommerce_taxonomy_args_product_cat', 'smn_enable_taxonomy_rest' );
add_filter( 'woocommerce_taxonomy_args_product_tag', 'smn_enable_taxonomy_rest' );

remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0);
add_action( 'woocommerce_before_main_content', 'smn_breadcrumb', 5, 0);

add_filter( 'woocommerce_reviews_title', 'smn_woocommerce_reviews_title', 10, 3 );
function smn_woocommerce_reviews_title( $reviews_title, $count, $product ) {
    return sprintf( __( 'Opiniones del curso %s', 'smn' ), get_the_title() );
}

remove_action( 'woocommerce_review_before', 'woocommerce_review_display_gravatar' );
remove_action( 'woocommerce_review_meta', 'woocommerce_review_display_meta' );
add_action( 'woocommerce_review_after_comment_text', 'woocommerce_review_display_meta' );
remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10 );
remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_images', 20 );
add_action( 'woocommerce_after_single_product_summary', 'smn_woocommerce_show_product_hero_cta', 2 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20 );
add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 6 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 15 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 50 );
// add_action( 'woocommerce_after_single_product_summary', 'woocommerce_template_single_sharing', 3 );

remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );
add_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_single_excerpt', 6 );
add_action( 'woocommerce_after_shop_loop_item_title', 'smn_loop_card_bottom_content', 8 );
// add_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_single_meta', 8 );
// add_action( 'woocommerce_after_shop_loop_item_title', 'smn_woocommerce_btn_ver_curso', 8 );

function smn_loop_card_bottom_content() {
    
    echo '<div class="card-body-bottom">';
        woocommerce_template_single_meta();
        smn_woocommerce_btn_ver_curso();
    echo '</div>';

}

add_action( 'woocommerce_before_single_product_summary', 'smn_product_hero_before', 5, 0 );
function smn_product_hero_before() {
    echo '<div class="wp-block-cover is-style-product-hero alignfull overlay-back">';
    echo '<span aria-hidden="true" class="wp-block-cover__background has-background-dim-100 has-background-dim wp-block-cover__gradient-background has-background-gradient"></span>';
    echo '<img class="wp-block-cover__image-background" src="'.get_stylesheet_directory_uri().'/img/fondo-pattern-primary.svg">';
    echo '<div class="wp-block-cover__inner-container">';
    echo '<div class="row">';
}

add_action( 'woocommerce_after_single_product_summary', 'smn_product_hero_after', 5, 0 );
function smn_product_hero_after() {
    echo '</div>'; //.row
    echo '</div>'; //.wp-block-cover__inner-container
    echo '</div>'; //.wp-block-cover
}

add_action( 'woocommerce_before_single_product_summary', 'smn_product_summary_bootstrap_column_begin', 1000 );
function smn_product_summary_bootstrap_column_begin() {
    echo '<div class="col-md-6">';
}
add_action( 'woocommerce_after_single_product_summary', 'smn_product_summary_bootstrap_column_end', 0 );
function smn_product_summary_bootstrap_column_end() {
    echo '</div>';
}

add_filter( 'woocommerce_cart_item_quantity', 'smn_wc_cart_item_quantity', 10, 3 );
function smn_wc_cart_item_quantity( $product_quantity, $cart_item_key, $cart_item ){
    if( is_cart() ){
        $product_quantity = sprintf( '%2$s <input type="hidden" name="cart[%1$s][qty]" value="%2$s" />', $cart_item_key, $cart_item['quantity'] );
    }
    return $product_quantity;
}

function smn_woocommerce_show_product_hero_cta() {
    echo '<div class="col-md-6 col-xl-4 offset-xl-2">';
        dynamic_sidebar( 'product-hero-cta' );

        woocommerce_template_single_sharing();

    echo '</div>';
}

add_action( 'woocommerce_share', 'smn_woocommerce_share', 10, 0 );
function smn_woocommerce_share() {
    if ( function_exists('A2A_SHARE_SAVE_init')) {
        echo '<div class="mt-2 d-flex justify-content-end">';
            echo __( 'Compartir curso', 'smn' ) . ': ' . do_shortcode( '[addtoany]' );
        echo '</div>';
    }
}

function smn_woocommerce_btn_ver_curso() {
    echo '<div class="wp-block-buttons text-right"><div class="wp-block-button is-style-arrow-link"><span class="wp-block-button__link has-secondary-color has-text-color">'.__( 'Ver curso', 'smn' ).'</span></div></div>';
}

add_action( 'woocommerce_after_main_content', function() {

    $terciary_description = get_field( 'terciary_description', get_queried_object() );
    if ( $terciary_description ) {
        echo '<div class="wrapper alignnarrow">';
            echo $terciary_description;
        echo '</div>';
    }

    if ( !is_singular() ) dynamic_sidebar( 'cta-product-cat' );

    get_template_part( 'global-templates/content-fragments', '', array('post_ids' => get_term_meta( get_queried_object_id(), 'bottom_fragments', true ), 'custom_class' => 'alignnarrow' ) );

}, 1);

remove_action( 'woocommerce_archive_description', 'woocommerce_taxonomy_archive_description' );
add_action( 'woocommerce_archive_description', function( $category ) {
// add_action( 'woocommerce_before_shop_loop', function() {

    get_template_part( 'global-templates/content-fragments', '', array('post_ids' => get_term_meta( get_queried_object_id(), 'top_fragments', true ) ) );

    if ( is_tax() ) {

        $product_cat = get_queried_object();

        echo '<div class="alignnarrow">';

            the_field( 'secondary_description', $product_cat );

            $product_cat_name = get_field( 'h1_text', $product_cat );
            if ( !$product_cat_name ) {
                $product_cat_name = $product_cat->name;
            }

            // $hide_auto_h2 = get_field( 'hide_auto_h2', $product_cat );
            // if ( !$hide_auto_h2 ) {
            //     $h2_before_product_loop = sprintf( __( 'Nuestros %s', 'smn' ), $product_cat_name );
            //     echo '<h2 class="h3 mb-3">' . $h2_before_product_loop . '</h2>';
            // }

        echo '</div>';
    }

}, 15);

add_action( 'woocommerce_after_subcategory_title', function( $category ) {
    // echo smn_get_ultimos_cursos_list( $category->term_id );
    if ( $category->description ) {
        echo $category->description;
    }
    echo '<div class="wp-block-buttons border-top border-secondary mt-2"><div class="wp-block-button is-style-arrow-link"><a href="'.get_term_link( $category ).'" class="wp-block-button__link has-text-color has-secondary-color has-background">'.__( 'Ver todos', 'smn' ).'</a></div></div>';
}, 15 );

add_action( 'woocommerce_before_subcategory', 'smn_woocommerce_before_subcategory_inner_row', 0 );
function smn_woocommerce_before_subcategory_inner_row() {
    echo '<div class="row">';
        echo '<div class="col-md-4">';
}

add_action( 'woocommerce_before_subcategory_title', 'smn_woocommerce_before_subcategory_title_inner_row', 999 );
function smn_woocommerce_before_subcategory_title_inner_row( $category ) {
    echo '</div>';
    echo '<div class="col-md-8">';
    // echo '<div class="row">';
    // echo '<div class="col-md-3">';
    //     woocommerce_subcategory_thumbnail( $category );
    // echo '</div>';
    // echo '<div class="col-md-9">';

}

add_action( 'woocommerce_after_subcategory', 'smn_woocommerce_after_subcategory_inner_row', 999 );
function smn_woocommerce_after_subcategory_inner_row() {
    // echo '</div>'; // .col-9
    // echo '</div>'; // .row
    echo '</div>'; // .col-4
        echo '</div>'; // .row
}

add_filter( 'woocommerce_output_related_products_args', 'smn_related_products_args', 20 );
function smn_related_products_args( $args ) {
	$args['posts_per_page'] = 3;
	$args['columns'] = 3;
	return $args;
}

add_filter('woocommerce_get_star_rating_html', 'smn_replace_star_ratings', 10, 2);
function smn_replace_star_ratings($html, $rating) {
    $html = ""; // Erase default HTML
    for($i = 0; $i < 5; $i++) {
        $html .= $i < $rating ? '<i class="rating-circle active"></i>' : '<i class="rating-circle"></i>';
    }
    
    return $html;
}

add_filter( 'woocommerce_register_post_type_product', 'smn_custom_post_type_label_woo' );
function smn_custom_post_type_label_woo( $args ){
    $labels = smn_get_cpt_labels('Curso','Cursos');
    $args['labels'] = $labels;
    return $args;
}

function smn_get_cpt_labels($single,$plural){
    $arr = array(
       'name' => $plural,
       'singular_name' => $single,
       'menu_name' => $plural,
       'add_new' => 'Añadir '.$single,
       'add_new_item' => 'Add Nuevo '.$single,
       'edit' => 'Editar',
       'edit_item' => 'Editar '.$single,
       'new_item' => 'Nuevo '.$single,
       'view' => 'Ver '.$plural,
       'view_item' => 'Ver '.$single,
       'search_items' => 'Buscar '.$plural,
       'not_found' => 'No hay '.$plural.' Found',
       'not_found_in_trash' => 'No hay '.$plural.' en la papelera',
       'parent' => $single . ' superior',
    );
    return $arr;
 }