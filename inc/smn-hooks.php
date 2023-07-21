<?php
/**
 * Custom hooks.
 *
 * @package understrap
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

add_filter( 'wpcf7_form_tag', 'smn_wpcf7_form_control_class', 10, 2 );
function smn_wpcf7_form_control_class( $scanned_tag, $replace ) {

   $excluded_types = array(
        'acceptance',
        'checkbox',
        'radio',
   );

   if ( in_array( $scanned_tag['type'], $excluded_types ) ) return $scanned_tag;

   switch ($scanned_tag['type']) {
    case 'submit':
        $scanned_tag['options'][] = 'class:btn';
        $scanned_tag['options'][] = 'class:btn-primary';
        break;
    
    default:
        $scanned_tag['options'][] = 'class:form-control';
        break;
   }
   
   return $scanned_tag;
}

add_action( 'loop_start', 'archive_loop_start', 10 );
function archive_loop_start( $query ) {

    if ( isset( $query->query['ignore_row'] ) && $query->query['ignore_row'] ) return false;
    if ( 'product' == get_post_type() ) return false;

    
    if ( ( isset( $query->query['add_row'] ) && $query->query['add_row'] ) || ( is_archive() || is_home() || is_search() ) ) {
        echo '<div class="row">';
    }
}

add_action( 'loop_end', 'archive_loop_end', 10 );
function archive_loop_end( $query ) {

    if ( isset( $query->query['ignore_row'] ) && $query->query['ignore_row'] ) return false;
    if ( 'product' == get_post_type() ) return false;

    if ( ( isset( $query->query['add_row'] ) && $query->query['add_row'] ) || ( is_archive() || is_home() || is_search() ) ) {
        echo '</div>';
    }
}

add_filter( 'body_class', 'smn_body_classes' );
function smn_body_classes( $classes ) {

    if ( is_singular() ) {
        $navbar_bg = get_post_meta( get_the_ID(), 'navbar_bg', true );
        if ( 'transparent' == $navbar_bg ) {
            $classes[] = 'navbar-transparent';
        }
    } elseif( is_tax( 'product_cat' ) ) {
        $term = get_queried_object();
        $classes[] = 'term-parent-' . $term->parent;
    } else {
        // $classes[] = 'navbar-transparent';
    }

    return $classes;
}


add_filter( 'post_class', 'bootstrap_post_class', 10, 3 );
function bootstrap_post_class( $classes, $class, $post_id ) {
    if ( is_search() ) return $classes;
    if ( 'product' == get_post_type( $post_id) ) return $classes;
    if ( 'content_fragment' == get_post_type( $post_id) ) return $classes;

    if ( is_archive() || is_home() || is_search() || in_array( 'hfeed-post', $class ) ) {
        $classes[] = 'col-sm-6 col-lg-4 stretch-linked-block'; 
    }

    return $classes;
}


// function understrap_add_site_info() {
	
//     if (is_active_sidebar( 'copyright' )) {
//         echo '<div class="row">';
//             dynamic_sidebar( 'copyright' );
//         echo '</div>';
//     }

// }

add_filter( 'understrap_site_info_content', 'site_info_do_shortcode' );
function site_info_do_shortcode( $site_info ) {
    return do_shortcode( $site_info );
}

add_action( 'wp_body_open', 'top_anchor');
function top_anchor() {
    echo '<div id="top">';
}

add_action( 'wp_footer', 'back_to_top' );
function back_to_top() {
    echo '<a href="#top" class="back-to-top"></a>';
}

function author_page_redirect() {
    if ( is_author() ) {
        wp_redirect( home_url() );
    }
}
add_action( 'template_redirect', 'author_page_redirect' );

function es_blog() {

    if( is_singular('post') || is_category() || is_tag() || ( is_home() && !is_front_page() ) ) {
        return true;
    }

    return false;
}

add_filter( 'theme_mod_understrap_sidebar_position', 'cargar_sidebar');
function cargar_sidebar( $valor ) {
    // global $wp_query;
    if ( is_singular( 'post' ) ) {
        $valor = 'right';
    } else {
        $valor = 'none';
    }
    return $valor;
}


function smn_nav_menu_submenu_css_class( $classes, $args, $depth ) {

    if ( !$args->walker && 'primary' === $args->theme_location ) {
        $classes[] = 'dropdown-menu';
        // $classes[] = 'collapse';
    }

    return $classes;

}
add_filter( 'nav_menu_submenu_css_class', 'smn_nav_menu_submenu_css_class', 10, 3 );

function smn_add_menu_item_classes( $classes, $item, $args ) {

    // echo '<pre>'; print_r($args); echo '<pre>';
 
    if ( !$args->walker && 'primary' === $args->theme_location ) {
        $classes[] = "nav-item";

        if( in_array( 'current-menu-item', $classes ) ) {
            $classes[] = "active";
        }

        if ( in_array( 'menu-item-has-children', $classes ) ) {
            $classes[] = 'dropdown';
        }
    
    }
 
    return $classes;
}
add_filter( 'nav_menu_css_class' , 'smn_add_menu_item_classes' , 10, 4 );

function smn_add_menu_link_classes( $atts, $item, $args ) {

    if ( !$args->walker && 'primary' == $args->theme_location ) {

    // echo '<pre>'; print_r($atts); echo '<pre>';

    if ( 0 == $item->menu_item_parent ) {
            $atts['class'] = 'nav-link';
        } else {
            $atts['class'] = 'dropdown-item';
        }
    }

    if ( in_array( 'menu-item-has-children', $item->classes ) ) {
        if ( isset( $atts['class'] ) ) $atts['class'] .= ' dropdown-toggle';
    }

    return $atts;
}
add_filter( 'nav_menu_link_attributes', 'smn_add_menu_link_classes', 10, 3 );

add_filter('nav_menu_item_args', function ($args, $item, $depth) {

    if ( !$args->walker && 'primary' == $args->theme_location ) {
        
        $args->link_after  = '<span class="sub-menu-toggler"></span>';

    }
    return $args;
}, 10, 3);

add_filter( 'parse_tax_query', 'smn_do_not_include_children_in_product_cat_archive' );
function smn_do_not_include_children_in_product_cat_archive( $query ) {
    if ( 
        ! is_admin() 
        && $query->is_main_query()
        && $query->is_tax( 'product_cat' )
    ) {
        $query->tax_query->queries[0]['include_children'] = 0;
    }
}


add_filter( 'single_cat_title', 'smn_single_term_title' );
add_filter( 'single_tag_title', 'smn_single_term_title' );
add_filter( 'single_term_title', 'smn_single_term_title' );
// add_filter( 'rank_math/frontend/title', 'smn_single_term_title' );
function smn_single_term_title( $title ) {

    $h1_text = get_term_meta( get_queried_object_id(), 'h1_text', true );
    if ( $h1_text ) $title = $h1_text;

	return $title;

}

function get_categorias_hijas( $parent_term_id = 55 ) {
    
    $cat_args = array(
        'taxonomy'   => 'product_cat',
        'orderby'    => 'name',
        'order'      => 'ASC',
        'hide_empty' => false,
        'parent'     => $parent_term_id,
        'fields'     => 'ids',
    );

    $child_categories = get_terms($cat_args);
    $child_categories[] = $parent_term_id;

    return $child_categories;

}

add_action('wpcf7_init', function (){
    wpcf7_add_form_tag( 'session_var' , 'cf7_session_variable_callback', array('name-attr' => true) );
}); 
function cf7_session_variable_callback($tag){
  $tag = new WPCF7_FormTag( $tag );
  $value = (!empty($_SESSION[$tag->name])) ? $_SESSION[$tag->name] : 'not set';
  $output = '<input type="hidden" name="'.$tag->name.'" value="'.$value.'">';
  return $output;
}

add_action( 'wp', 'smn_register_session' );
function smn_register_session() {

    if ( !session_id() ) {
        session_start();
    }

    if(isset($_GET['origen'])) $_SESSION['origen'] = $_GET['origen'];
    if(isset($_GET['campaign'])) $_SESSION['campaign'] = $_GET['campaign'];

    $categorias_subvencionadas = get_categorias_hijas(55);

    if ( 
        ( is_tax( 'product_cat' ) && in_array( get_queried_object_id(), $categorias_subvencionadas ) ) ||
        ( is_singular('product') && has_term( $categorias_subvencionadas, 'product_cat' ) )
    ) {
        $_SESSION['business_line'] = 4; // Curso subvencionado
    } else {
        $_SESSION['business_line'] = 1; // Por defecto
    }

    if ( is_singular('product') && has_term( 55, 'product_cat', get_the_ID() ) ) {
        $_SESSION['business_line'] = 4; // Curso subvencionado
    }

    $_SESSION['curso_uno'] = '';

    if ( is_product_category() ) {
        $_SESSION['curso_uno'] = get_queried_object()->name;
    } elseif ( is_singular( 'product' ) ) {
        $_SESSION['curso_uno'] = get_queried_object()->post_title;
    }

}

function wpcf7_to_lacor_crm ( $contact_form, $abort ) {
    
    if ( !in_array( $contact_form->id(), [1107, 987] ) ) return;

    $submission = WPCF7_Submission::get_instance();
    $posted_data = $submission->get_posted_data();

    $name = $posted_data['your-name'];
    $phone_number = $posted_data['your-phone'];
    $email = $posted_data['your-email'];


    $origen = isset( $posted_data['origen'] ) ? trim($posted_data['origen']) : '';
    $campaign = isset( $posted_data['campaign'] ) ? trim($posted_data['campaign']) : '';
    $business_line = isset( $posted_data['business_line'] ) ? trim($posted_data['business_line']) : '';
    $curso_uno = $posted_data['curso_uno'];

    if ( isset( $posted_data['your-categoria-curso']) ) {
        $curso_uno = implode( ', ', $posted_data['your-categoria-curso'] );
    }

    /* adaptamos el origen al texto  */
    switch ($origen) {
        case '1':
            $origen = "AdWords Búsqueda";
            break;
        case '2':
            $origen = "AdWords Display";
            break;
        case '3':
            $origen = "Facebook Orgánicos";
            break;
        case '4':
            $origen = "Facebook Ads";
            break;
        case '5':
            $origen = "Mailing";
            break;
        case '6':
            $origen = "Twitter";
            break;
        case '7':
            $origen = "linkedin";
            break;
        default:
            $origen = "Contenidos / orgánico";
            $codigo_cupon = '1/0/'.$_POST['message']; 
            break;
    }
    
    $url = 'https://crm.ordesactiva.com/index.php?-action=intro_lead';

    $ch = curl_init($url);
    $info = array(
        'origin'=>'wanatop', //Siempre Wanatop
        'extra'=>$origen, //Categorización extra del cupón
        'ACCESS_KEY'=>'nRSf1G8l', //El ACCESS_KEY es configurable en el crm
        //Datos del cupón
        'name'=>$name,
        'lastname'=>'',
        'phone'=>$phone_number,
        'mail'=>$email,
        'campaign_name'=>$campaign,
        'business_line'=>$business_line,
        'product'=>$curso_uno
    );

    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($info));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

}

add_action( 'wpcf7_before_send_mail', 'wpcf7_to_lacor_crm', 10, 2);