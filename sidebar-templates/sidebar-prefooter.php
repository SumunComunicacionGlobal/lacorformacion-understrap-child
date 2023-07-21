<?php

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

if ( is_singular() ) {
	$ocultar_prefooter = get_post_meta( get_the_ID(), 'ocultar_prefooter', true );
	if ( $ocultar_prefooter ) return false;
}

?>

<?php if ( is_active_sidebar( 'logos' ) ) : ?>

	<div class="container" id="logos-content" tabindex="-1">

		<div class="wrapper" id="wrapper-logos">

			<?php dynamic_sidebar( 'logos' ); ?>

		</div><!-- #wrapper-logos -->

	</div>

	<?php

endif;

if ( is_active_sidebar( 'prefooter' ) ) : ?>

	<div class="container" id="prefooter-content" tabindex="-1">

		<div class="wrapper" id="wrapper-prefooter">

			<?php dynamic_sidebar( 'prefooter' ); ?>

		</div><!-- #wrapper-prefooter -->

	</div>
	<?php
	
endif;
