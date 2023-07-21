<?php
/**
 * Single Product Meta
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/meta.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce\Templates
 * @version     3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $product;
?>
<div class="product_meta">

	<?php do_action( 'woocommerce_product_meta_start' ); ?>

	<?php // echo strip_tags( get_the_term_list( $product->get_id(), 'metodologia', '<span class="product_meta-item smn-has-icon smn-has-icon-metodologia">', ' · ', '</span>' ), '<span>' ); ?>

	<?php // echo wc_get_product_category_list( $product->get_id(), ' · ', '<span class="posted_in">' . _n( 'Category:', 'Categories:', count( $product->get_category_ids() ), 'woocommerce' ) . ' ', '</span>' ); ?>

	<?php $hour_fields = array(
		array(
			'field_name'			=> 'presential_hours',
			'icon'					=> 'duration',
		),
		array(
			'field_name'			=> 'online_hours',
			'icon'					=> 'duration',
		),
	);

	$hour_fields_array = array();
	$total_hours = 0;

	foreach ( $hour_fields as $field ) {

		$value = get_post_meta( get_the_ID(), $field['field_name'], true );
		$total_hours += intval($value);

		if ( $value ) {

			$field_obj = acf_get_field( $field['field_name'] );

			$hour_fields_array[] = $field_obj['prepend'] . ' ' . $value . $field_obj['append'];

		}
	}

	if ( $total_hours && count($hour_fields_array) > 1 ) {
		$total_hours .= 'h - ';
	} else {
		$total_hours = '';
	}

	echo '<span class="product_meta-item smn-has-icon smn-has-icon-'.$field['icon'].'">' . $total_hours .  implode( ' + ', $hour_fields_array ) . '</span>';
	?>

	<?php // echo wc_get_product_tag_list( $product->get_id(), ' · ', '<span class="tagged_as">' . _n( 'Tag:', 'Tags:', count( $product->get_tag_ids() ), 'woocommerce' ) . ' ', '</span>' ); ?>

	<?php if ( has_term( 62, 'product_cat', $product->get_id() )) {
			echo '<span class="product_meta-item smn-has-icon smn-has-icon-certificado">'. __( 'Certificado de profesionalidad', 'smn' ) . '</span>';
	} ?>

	<?php
	if ( is_singular( 'product' )) {
		$ubicacion_cursos = get_field('ubicacion_cursos', 'option');
		if ( $ubicacion_cursos ) {
			echo '<span class="product_meta-item smn-has-icon smn-has-icon-map-line">'.$ubicacion_cursos.'</span>';
		}
	}
	?>

	<?php do_action( 'woocommerce_product_meta_end' ); ?>

</div>
