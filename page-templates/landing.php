<?php
/**
 * Template Name: Landing Page
 *
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();
	echo '<div class="container">';
		the_content();
	echo '</div>';
endwhile;

get_footer();
