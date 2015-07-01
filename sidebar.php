<?php
/**
 * The sidebar
 */
echo Ads::get_instance()->render( 'upper_sidebar' );

get_template_part( 'templates/sidebar', 'social' );

wp_reset_query();
dynamic_sidebar( 'left-sidebar' );
?>

