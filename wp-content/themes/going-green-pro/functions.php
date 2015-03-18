<?php
//* Start the engine
include_once( get_template_directory() . '/lib/init.php' );

//* Set Localization (do not remove)
load_child_theme_textdomain( 'going-green', apply_filters( 'child_theme_textdomain', get_stylesheet_directory() . '/languages', 'going-green' ) );

//* Child theme (do not remove)
define( 'CHILD_THEME_NAME', __( 'Going Green Pro Theme', 'going-green' ) );
define( 'CHILD_THEME_URL', 'http://my.studiopress.com/themes/goinggreen/' );
define( 'CHILD_THEME_VERSION', '3.0.0' );

//* Add HTML5 markup structure
add_theme_support( 'html5' );

//* Add viewport meta tag for mobile browsers
add_theme_support( 'genesis-responsive-viewport' );

//* Enqueue Google fonts
add_action( 'wp_enqueue_scripts', 'going_green_google_fonts' );
function going_green_google_fonts() {
	wp_enqueue_style( 'google-font', '//fonts.googleapis.com/css?family=Lato:300,700|Lora:700', array(), CHILD_THEME_VERSION );
}

//* Add new image sizes
add_image_size( 'featured-image', 900, 440, true );

//* Add support for custom background
add_theme_support( 'custom-background' );

//* Add support for custom header
add_theme_support( 'custom-header', array(
	'width'           => 340,
	'height'          => 70,
	'header_image'    => '',
	'header-selector' => '.site-header .title-area',
	'header-text'     => false
) );

//* Add support for additional color style options
add_theme_support( 'genesis-style-selector', array(
	'going-green-pro-forest' => __( 'Going Green Pro Forest', 'going-green' ),
	'going-green-pro-mint'   => __( 'Going Green Pro Mint', 'going-green' ),
	'going-green-pro-olive'  => __( 'Going Green Pro Olive', 'going-green' ),
) );

//* Add support for structural wraps
add_theme_support( 'genesis-structural-wraps', array(
	'header',
	'nav',
	'subnav',
	'inner',
	'footer-widgets',
	'footer'
) );

//* Add support for 3-column footer widgets
add_theme_support( 'genesis-footer-widgets', 3 );

//* Unregister layout settings
genesis_unregister_layout( 'content-sidebar-sidebar' );
genesis_unregister_layout( 'sidebar-content-sidebar' );
genesis_unregister_layout( 'sidebar-sidebar-content' );

//* Unregister secondary sidebar
unregister_sidebar( 'sidebar-alt' );

//* Reposition the navigation
remove_action( 'genesis_after_header', 'genesis_do_nav' );
remove_action( 'genesis_after_header', 'genesis_do_subnav' );
add_action( 'genesis_before_header', 'genesis_do_nav' );
add_action( 'genesis_before_header', 'genesis_do_subnav' );

//* Remove default post image
remove_action( 'genesis_entry_content', 'genesis_do_post_image', 8 );

//* Add featured image above the entry content
add_action( 'genesis_entry_header', 'going_green_featured_photo', 5 );
function going_green_featured_photo() {
	if ( is_page() || ! genesis_get_option( 'content_archive_thumbnail' ) )
		return;

	if ( $image = genesis_get_image( array( 'format' => 'url', 'size' => genesis_get_option( 'image_size' ) ) ) ) {
		printf( '<div class="featured-image"><img src="%s" alt="%s" /></div>', $image, the_title_attribute( 'echo=0' ) );
	}
}

//* Customize the post meta function
add_filter( 'genesis_post_meta', 'post_meta_filter' );
function post_meta_filter($post_meta) {
	if (!is_page()) {
		$post_meta = '[post_categories before=""] [post_tags before="' . __( 'Tagged: ', 'going-green' ) . '"]';
		return $post_meta;
	}
}