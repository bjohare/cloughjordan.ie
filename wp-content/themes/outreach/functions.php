<?php
/** Start the engine */
include_once( get_template_directory() . '/lib/init.php' );

/** Child theme (do not remove) */
define( 'CHILD_THEME_NAME', 'Outreach Theme' );
define( 'CHILD_THEME_URL', 'http://my.studiopress.com/themes/outreach' );

/** Set Localization (do not remove) */
load_child_theme_textdomain( 'outreach', apply_filters( 'child_theme_textdomain', get_stylesheet_directory() . '/languages', 'outreach' ) );

/** Sets Content Width */
$content_width = apply_filters( 'content_width', 650, 450, 960 );

/** Load Google fonts */
add_action( 'wp_enqueue_scripts', 'outreach_load_google_fonts' );
function outreach_load_google_fonts() {
    wp_enqueue_style( 
    	'google-fonts', 
    	'http://fonts.googleapis.com/css?family=Lato', 
    	array(), 
    	PARENT_THEME_VERSION 
    );
}

/** Add Viewport meta tag for mobile browsers */
add_action( 'genesis_meta', 'outreach_add_viewport_meta_tag' );
function outreach_add_viewport_meta_tag() {
    echo '<meta name="viewport" content="width=device-width, initial-scale=1.0"/>';
}

/** Add new image sizes */
add_image_size( 'featured', 1040, 400, TRUE );
add_image_size( 'home-sections', 215, 140, TRUE );
add_image_size( 'sidebar', 300, 150, TRUE );

/** Create additional color style options */
add_theme_support( 'genesis-style-selector', array(
	'outreach-blue' 	=>	__( 'Blue', 'outreach' ),
	'outreach-orange' 	=> 	__( 'Orange', 'outreach' ),
	'outreach-red' 		=> 	__( 'Red', 'outreach' ) 
) );

/** Add support for custom header */
add_theme_support( 'custom-header', array(
    'width'         	=> 330,
    'height'        	=> 100,
    'header-selector'   => '#title-area a',
    'header-text'       => false
) );

/** Add support for structural wraps */
add_theme_support( 'genesis-structural-wraps', array(
	'header',
	'nav',
	'subnav',
	'inner',
	'footer-widgets',
	'footer'
) );

/** Reposition the secondary navigation */
remove_action( 'genesis_after_header', 'genesis_do_subnav' );
add_action( 'genesis_before_content_sidebar_wrap', 'genesis_do_subnav' );

/** Modify the size of the Gravatar in the author box */
add_filter( 'genesis_author_box_gravatar_size', 'outreach_author_box_gravatar_size' );
function outreach_author_box_gravatar_size( $size ) {
    return '80';
}

/** Add the sub footer section */
add_action( 'genesis_before_footer', 'outreach_sub_footer', 5 );
function outreach_sub_footer() {
	if ( is_active_sidebar( 'sub-footer-left' ) || is_active_sidebar( 'sub-footer-right' ) ) {
		echo '<div id="sub-footer"><div class="wrap">';
		
		   genesis_widget_area( 'sub-footer-left', array(
		       'before' => '<div class="sub-footer-left">'
		   ) );
	
		   genesis_widget_area( 'sub-footer-right', array(
		       'before' => '<div class="sub-footer-right">'
		   ) );
	
		echo '</div><!-- end .wrap --></div><!-- end #sub-footer -->';	
	}
}

/** Add support for 4-column footer widgets */
add_theme_support( 'genesis-footer-widgets', 4 );

/** Customizes go to top text */
add_filter( 'genesis_footer_backtotop_text', 'footer_backtotop_filter' );
function footer_backtotop_filter($backtotop) {
	return '[footer_backtotop text="' . __('Top' , 'outreach' ) . '"]';
}

/** Register widget areas */
genesis_register_sidebar( array(
	'id'				=> 'home-featured',
	'name'			=> __( 'Home Featured', 'outreach' ),
	'description'	=> __( 'This is the home featured section.', 'outreach' )
) );
genesis_register_sidebar( array(
	'id'				=> 'home-1',
	'name'			=> __( 'Home #1', 'outreach' ),
	'description'	=> __( 'This is the home #1 section.', 'outreach' )
) );
genesis_register_sidebar( array(
	'id'				=> 'home-2',
	'name'			=> __( 'Home #2', 'outreach' ),
	'description'	=> __( 'This is the home #2 section.', 'outreach' )
) );
genesis_register_sidebar( array(
	'id'				=> 'home-3',
	'name'			=> __( 'Home #3', 'outreach' ),
	'description'	=> __( 'This is the home #3 section.', 'outreach' )
) );
genesis_register_sidebar( array(
	'id'				=> 'home-4',
	'name'			=> __( 'Home #4', 'outreach' ),
	'description'	=> __( 'This is the home #4 section.', 'outreach' )
) );
genesis_register_sidebar( array(
	'id'				=> 'sub-footer-left',
	'name'			=> __( 'Sub Footer Left', 'outreach' ),
	'description'	=> __( 'This is the sub footer left section.', 'outreach' )
) );
genesis_register_sidebar( array(
	'id'				=> 'sub-footer-right',
	'name'			=> __( 'Sub Footer Right', 'outreach' ),
	'description'	=> __( 'This is the sub footer right section.', 'outreach' )
) );