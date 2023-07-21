<?php
/**
 * Partial template for content in page.php
 *
 * @package Understrap
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;
?>

<div>

	<div <?php post_class(); ?> id="post-<?php the_ID(); ?>">

		<div class="row">

			<div class="col-md-4 mb-2">

				<?php the_post_thumbnail( 'medium', array( 'class' => 'ml-md-auto' ) ); ?>

			</div>

			<div class="col-md-8">

				<blockquote class="wp-block-quote">

					<?php the_content(); ?>

					<cite>

						<p class="blockquote-name"><?php the_title(); ?></p>

						<p class="has-small-font-size has-secondary-color"><?php echo $post->post_excerpt; ?></p>

					</cite>

				</blockquote>

				<footer class="entry-footer">

					<?php understrap_edit_post_link(); ?>

				</footer><!-- .entry-footer -->

			</div>

		</div><!-- #post-## .row-->

	</div>

</div>