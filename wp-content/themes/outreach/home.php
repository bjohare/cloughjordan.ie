<?php

add_action( 'genesis_meta', 'outreach_home_genesis_meta' );
/**
 * Add widget support for homepage. If no widgets active, display the default loop.
 *
 */
function outreach_home_genesis_meta() {

	if ( is_active_sidebar( 'home-featured' ) || is_active_sidebar( 'home-1' ) || is_active_sidebar( 'home-2' ) || is_active_sidebar( 'home-3' ) || is_active_sidebar( 'home-4' ) ) {

		remove_action( 'genesis_loop', 'genesis_do_loop' );
		add_action( 'genesis_loop', 'outreach_home_featured' );
		add_action( 'genesis_before_footer', 'outreach_home_sections', 3 );
		add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );
		add_filter( 'body_class', 'add_body_class' );

		function add_body_class( $classes ) {
   			$classes[] = 'outreach';
  			return $classes;
		}

	}
}

function outreach_home_featured() {

	if ( is_active_sidebar( 'home-featured' ) ) {
	   genesis_widget_area( 'home-featured', array(
	       'before' => '<div class="home-featured widget-area">'
	   ) );
	}

}

function outreach_home_sections() {

	if ( is_active_sidebar( 'home-1' ) || is_active_sidebar( 'home-2' ) || is_active_sidebar( 'home-3' ) || is_active_sidebar( 'home-4' ) ) {

		echo '<div id="home-sections"><div class="wrap">';

		   genesis_widget_area( 'home-1', array(
		       'before' => '<div class="home-1 widget-area">',
		   ) );

		   genesis_widget_area( 'home-2', array(
		       'before' => '<div class="home-2 widget-area">',
		   ) );

		   genesis_widget_area( 'home-3', array(
		       'before' => '<div class="home-3 widget-area">',
		   ) );

		   genesis_widget_area( 'home-4', array(
		       'before' => '<div class="home-4 widget-area">',
		   ) );

		echo '</div><!-- end .wrap --></div><!-- end #home-sections -->';

	}

}

genesis();