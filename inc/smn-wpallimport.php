<?php

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

add_filter( 'wp_all_import_feed_type', 'smn_feed_type', 10, 2 );
function smn_feed_type( $type, $url ){
    
    // Check $url and return $type.
    if ( strpos( $url, '//crm.ordesactiva.com' ) !== false ) {
        $type = 'json';
    }

    return $type;
}

function smn_sanitize_import_post_content($data) {

    $data['post_content'] = smn_sanitize_import_string( $data['post_content'] );
    return $data;

}
// add_filter('wp_insert_post_data', 'smn_sanitize_import_post_content', 99);

function smn_sanitize_import_post_meta( $value, $post_id, $key, $original_value, $existing_meta_keys, $import_id ) {

    $value = smn_sanitize_import_string( $value );
    return $value;

}
add_filter( 'pmxi_custom_field', 'smn_sanitize_import_post_meta', 10, 6 );

function smn_sanitize_import_string( $string ) {

    // $string = str_replace( '>\r\n', '>', $string );
    $string = str_replace( '>&#13;', '>', $string );
    // $string = str_replace( '>' . PHP_EOL, '>', $string );
    $string = str_replace( 'style="text-align: justify;"', '', $string );
    $string = preg_replace(array('/<\s*div[^>]*>/', '/<\/div>/'), '', $string );

    return $string;
}

add_filter( 'wp_all_import_set_post_terms', 'dont_remove_featured_category', 10, 4 );
function dont_remove_featured_category( $term_taxonomy_ids, $tx_name, $pid, $import_id ) {

    // Only check Product Categories.
    if ( $tx_name == 'product_cat' ){

        // Retrieve all currently assigned categories.
        $txes_list = get_the_terms($pid, $tx_name);

        // Do nothing if no categories are set.
        if ( ! empty($txes_list) ){
           foreach ($txes_list as $cat){

                // Si la categoría es Espacialidades o es Certificados de profesionalidad, añádela a la importación.
                if ( in_array( $cat->term_id, [62, 97] ) ) {
                    $term_taxonomy_ids[] = $cat->term_taxonomy_id;
                    break;
                } 
            }
        }
    }

    // Return the updated list of taxonomies to import.
    return $term_taxonomy_ids;

}


// add_filter( 'pmxi_single_category', 'dont_create_terms', 10, 2 ); 
function dont_create_terms( $term_into, $tx_name ) {
    
    // Check if term exists, checking both top-level and child
    // taxonomy terms. 
    $term = empty($term_into['parent']) ? term_exists( $term_into['name'], $tx_name, 0 ) : term_exists( $term_into['name'], $tx_name, $term_into['parent'] );

    // Don't allow WP All Import to create the term if it doesn't
    // already exist.
    if ( empty($term) and !is_wp_error($term) ) { 
        return false;
    }

    // If the term already exists assign it.
    return $term_into;

}

# Automatizar el borrado de caché WP Rocket cada vez que se actualiza contenido a través de WP All Import
add_action('pmxi_saved_post', function($post_id) {
    if (function_exists('rocket_clean_post')) {
        rocket_clean_domain();
    }
});
