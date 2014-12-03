<?php
//* Start the engine
require_once( get_template_directory() . '/lib/init.php' );

//* Set Localization (do not remove)
load_child_theme_textdomain( 'executive', apply_filters( 'child_theme_textdomain', get_stylesheet_directory() . '/languages', 'executive' ) );

//* Child theme (do not remove)
define( 'CHILD_THEME_NAME', __( 'Executive Pro Theme', 'executive' ) );
define( 'CHILD_THEME_URL', 'http://my.studiopress.com/themes/executive/' );
define( 'CHILD_THEME_VERSION', '3.0.0' );

//* Add HTML5 markup structure
add_theme_support( 'html5' );
//* site-inner
add_theme_support( 'genesis-structural-wraps', array('header','nav','subnav','site-inner','footer-widgets','footer') );


//* Add viewport meta tag for mobile browsers
add_theme_support( 'genesis-responsive-viewport' );

//*responsive-menu
add_action( 'wp_enqueue_scripts', 'executive_enqueue_scripts' );
/**
 * Enqueue responsive javascript
 * @author Ozzy Rodriguez
 * @todo Change 'prefix' to your theme's prefix
 */
function executive_enqueue_scripts() {
 
	wp_enqueue_script( 'executive-responsive-menu', get_stylesheet_directory_uri() . '/lib/js/responsive-menu.js', array( 'jquery' ), '1.0.0', true ); // Change 'prefix' to your theme's prefix
 
}

//* Enqueue Google fonts
add_action( 'wp_enqueue_scripts', 'executive_google_fonts');
function executive_google_fonts() {

	wp_enqueue_style( 'google-font', '//fonts.googleapis.com/css?family=Open+Sans:400italic,700italic,400,700', array(), CHILD_THEME_VERSION );

}

//* Load map scripts on pages where required.. and nowhere else!
function load_map_scripts(){
    if (is_page(418)){
        //* Enqueue OpenLayers
        add_action('wp_enqueue_scripts', 'openlayers');
        function openlayers() {
             wp_enqueue_script('openlayers', get_stylesheet_directory_uri().'/api/OpenLayers/OpenLayers.js', array(), '2.13.1', false);
        }

        add_action('wp_enqueue_scripts','showmap');
        function showmap(){
             wp_enqueue_script('showmap','/mapping/showmap.js', array('openlayers'));
        }
    }
}
add_action('wp','load_map_scripts');

//* End load map scripts

//* Load custom css for mapping pages

function register_map_styles()
{
    wp_register_style( 'map-style', get_stylesheet_directory_uri() . '/css/mapping-styles.css', array(), '20120208', 'all' );
                     
    wp_enqueue_style( 'map-style' );
    }
add_action( 'wp_enqueue_scripts', 'register_map_styles' );


//* Add new image sizes
add_image_size( 'featured', 300, 100, TRUE );
add_image_size( 'portfolio', 300, 200, TRUE );
add_image_size( 'slider', 1140, 445, TRUE );

//* Add support for custom background
add_theme_support( 'custom-background' );

//* Add support for custom header
add_theme_support( 'custom-header', array(
	'width'           => 260,
	'height'          => 100,
	'header-selector' => '.site-title a',
	'header-text'     => false
) );

//* Add support for additional color style options
add_theme_support( 'genesis-style-selector', array(
	'executive-pro-brown'  => __( 'Executive Pro Brown', 'executive' ),
	'executive-pro-green'  => __( 'Executive Pro Green', 'executive' ),
	'executive-pro-orange' => __( 'Executive Pro Orange', 'executive' ),
	'executive-pro-purple' => __( 'Executive Pro Purple', 'executive' ),
	'executive-pro-red'    => __( 'Executive Pro Red', 'executive' ),
	'executive-pro-teal'   => __( 'Executive Pro Teal', 'executive' ),
) );

//* Add support for 3-column footer widgets
add_theme_support( 'genesis-footer-widgets', 3 );

//* Unregister layout settings
genesis_unregister_layout( 'content-sidebar-sidebar' );
genesis_unregister_layout( 'sidebar-content-sidebar' );
genesis_unregister_layout( 'sidebar-sidebar-content' );

//* Unregister secondary sidebar
unregister_sidebar( 'sidebar-alt' );

//* Load Admin Stylesheet
add_action( 'admin_enqueue_scripts', 'executive_load_admin_styles' );
function executive_load_admin_styles() {

	wp_register_style( 'custom_wp_admin_css', get_stylesheet_directory_uri() . '/lib/admin-style.css', false, '1.0.0' );
	wp_enqueue_style( 'custom_wp_admin_css' );

}

//* Create Portfolio Type custom taxonomy
add_action( 'init', 'executive_type_taxonomy' );
function executive_type_taxonomy() {

	register_taxonomy( 'portfolio-type', 'portfolio',
		array(
			'labels' => array(
				'name'          => _x( 'Types', 'taxonomy general name', 'executive' ),
				'add_new_item'  => __( 'Add New Portfolio Type', 'executive' ),
				'new_item_name' => __( 'New Portfolio Type', 'executive' ),
			),
			'exclude_from_search' => true,
			'has_archive'         => true,
			'hierarchical'        => true,
			'rewrite'             => array( 'slug' => 'portfolio-type', 'with_front' => false ),
			'show_ui'             => true,
			'show_tagcloud'       => false,
		)
	);

}


//* Create portfolio custom post type
add_action( 'init', 'executive_portfolio_post_type' );
function executive_portfolio_post_type() {

	register_post_type( 'portfolio',
		array(
			'labels' => array(
				'name'          => __( 'Portfolio', 'executive' ),
				'singular_name' => __( 'Portfolio', 'executive' ),
			),
			'has_archive'  => true,
			'hierarchical' => true,
			'menu_icon'    => get_stylesheet_directory_uri() . '/lib/icons/portfolio.png',
			'public'       => true,
			'rewrite'      => array( 'slug' => 'portfolio', 'with_front' => false ),
			'supports'     => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'trackbacks', 'custom-fields', 'revisions', 'page-attributes', 'genesis-seo', 'genesis-cpt-archives-settings' ),
			'taxonomies'   => array( 'portfolio-type' ),

		)
	);
	
}

//* Add Portfolio Type Taxonomy to columns
add_filter( 'manage_taxonomies_for_portfolio_columns', 'portfolio_columns' );
function portfolio_columns( $taxonomies ) {

    $taxonomies[] = 'portfolio-type';
    return $taxonomies;

}

//* Remove the site description
remove_action( 'genesis_site_description', 'genesis_seo_site_description' );

//* Set Genesis Responsive Slider defaults
add_filter( 'genesis_responsive_slider_settings_defaults', 'executive_responsive_slider_defaults' );
function executive_responsive_slider_defaults( $defaults ) {

	$args = array(
		'location_horizontal'             => 'Left',
		'location_vertical'               => 'Top',
		'posts_num'                       => '3',
		'slideshow_excerpt_content_limit' => '100',
		'slideshow_excerpt_content'       => 'full',
		'slideshow_excerpt_width'         => '30',
		'slideshow_height'                => '445',
		'slideshow_more_text'             => __( 'Continue Reading&hellip;', 'executive' ),
		'slideshow_title_show'            => 1,
		'slideshow_width'                 => '1140',
	);

	$args = wp_parse_args( $args, $defaults );
	
	return $args;
}

//* Relocate the post info
remove_action( 'genesis_entry_header', 'genesis_post_info', 12 );
add_action( 'genesis_entry_header', 'genesis_post_info', 5 );

//* Change the number of portfolio items to be displayed (props Bill Erickson)
add_action( 'pre_get_posts', 'executive_portfolio_items' );
function executive_portfolio_items( $query ) {

	if( $query->is_main_query() && !is_admin() && is_post_type_archive( 'portfolio' ) ) {
		$query->set( 'posts_per_page', '12' );
	}

}

//* Customize Portfolio post info and post meta
add_filter( 'genesis_post_info', 'executive_portfolio_post_info_meta' );
add_filter( 'genesis_post_meta', 'executive_portfolio_post_info_meta' );
function executive_portfolio_post_info_meta( $output ) {

     if( 'portfolio' == get_post_type() )
        return '';

    return $output;

}

//* Register widget areas
genesis_register_sidebar( array(
	'id'          => 'home-slider',
	'name'        => __( 'Home - Slider', 'executive' ),
	'description' => __( 'This is the slider section on the home page.', 'executive' ),
) );
genesis_register_sidebar( array(
	'id'          => 'home-top',
	'name'        => __( 'Home - Top', 'executive' ),
	'description' => __( 'This is the top section of the home page.', 'executive' ),
) );
genesis_register_sidebar( array(
	'id'          => 'home-cta',
	'name'        => __( 'Home - Call To Action', 'executive' ),
	'description' => __( 'This is the call to action section on the home page.', 'executive' ),
) );
genesis_register_sidebar( array(
	'id'          => 'home-middle',
	'name'        => __( 'Home - Middle', 'executive' ),
	'description' => __( 'This is the middle section of the home page.', 'executive' ),
) );
