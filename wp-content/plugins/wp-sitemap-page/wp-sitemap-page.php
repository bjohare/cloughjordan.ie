<?php
/**
Plugin Name: WP Sitemap Page
Plugin URI: http://tonyarchambeau.com/
Description: Add a sitemap on any page/post using the simple shortcode [wp_sitemap_page]
Version: 1.3.0
Author: Tony Archambeau
Author URI: http://tonyarchambeau.com/
Text Domain: wp-sitemap-page
Domain Path: /languages

Copyright 2013 Tony Archambeau
*/


// SECURITY : Exit if accessed directly
if ( !defined('ABSPATH') ) {
	exit;
}


// i18n
load_plugin_textdomain( 'wp_sitemap_page', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );



/***************************************************************
 * Define
 ***************************************************************/

if ( !defined('WSP_USER_NAME') ) {
	define('WSP_USER_NAME', basename(dirname(__FILE__)) );
}
if ( !defined('WSP_USER_PLUGIN_DIR') ) {
	define('WSP_USER_PLUGIN_DIR', WP_PLUGIN_DIR .'/'. WSP_USER_NAME );
}
if ( !defined('WSP_USER_PLUGIN_URL') ) {
	define('WSP_USER_PLUGIN_URL', WP_PLUGIN_URL .'/'. WSP_USER_NAME );
}

if ( !defined('WSP_PLUGIN_NAME') ) {
	define('WSP_PLUGIN_NAME', 'wp_sitemap_page');
}
if ( !defined('WSP_VERSION') ) {
	define('WSP_VERSION', '1.3.0');
}
if ( !defined('WSP_DONATE_LINK') ) {
	define('WSP_DONATE_LINK', 'https://www.paypal.com/cgi-bin/webscr?cmd=_donations&amp;business=FQKK22PPR3EJE&amp;lc=GB&amp;item_name=WP%20Sitemap%20Page&amp;item_number=wp%2dsitemap%2dpage&amp;currency_code=EUR&amp;bn=PP%2dDonationsBF%3abtn_donate_LG%2egif%3aNonHosted');
}
if (!defined('WSP_VERSION_NUM')) {
	define('WSP_VERSION_NUM', '1.3.0');
}


/***************************************************************
 * Install and uninstall
 ***************************************************************/


/**
 * Hooks for install
 */
if (function_exists('register_uninstall_hook')) {
	register_deactivation_hook(__FILE__, 'wsp_uninstall');
}


/**
 * Hooks for uninstall
 */
if ( function_exists('register_activation_hook')) {
	register_activation_hook(__FILE__, 'wsp_install');
}


/**
 * Install this plugin
 */
function wsp_install() {
	// Initialise the RSS footer and save it
	$wsp_posts_by_category = '<a href="{permalink}">{title}</a>';
	add_option( 'wsp_posts_by_category', $wsp_posts_by_category );
	
	// by default deactivate the ARCHIVE and AUTHOR
	add_option( 'wsp_exclude_cpt_archive', '1' );
	add_option( 'wsp_exclude_cpt_author', '1' );
}


/**
 * Uninstall this plugin
 */
function wsp_uninstall() {
	// Unregister an option
	delete_option( 'wsp_posts_by_category' );
	delete_option( 'wsp_exclude_pages' );
	delete_option( 'wsp_exclude_cpt_page' );
	delete_option( 'wsp_exclude_cpt_post' );
	delete_option( 'wsp_exclude_cpt_archive' );
	delete_option( 'wsp_exclude_cpt_author' );
	delete_option( 'wsp_is_display_copyright' );
	unregister_setting('wp-sitemap-page', 'wsp_posts_by_category');
}


/***************************************************************
 * UPGRADE
 ***************************************************************/

// Manage the upgrade to version 1.1.0
if (get_option('wsp_version_key') != WSP_VERSION_NUM) {
    // Add option
	
	// by default deactivate the ARCHIVE and AUTHOR
	add_option( 'wsp_exclude_cpt_archive', '1' );
	add_option( 'wsp_exclude_cpt_author', '1' );
	
    // Update the version value
    update_option('wsp_version_key', WSP_VERSION_NUM);
}


/***************************************************************
 * Menu + settings page
 ***************************************************************/


/**
 * Add menu on the Back-Office for the plugin
 */
function wsp_add_options_page() {
	if (function_exists('add_options_page')) {
		$page_title = __('WP Sitemap Page', 'wp_sitemap_page');
		$menu_title = __('WP Sitemap Page', 'wp_sitemap_page');
		$capability = 'administrator';
		$menu_slug = 'wp_sitemap_page';
		$function = 'wsp_settings_page'; // function that contain the page
		add_options_page( $page_title, $menu_title, $capability, $menu_slug, $function );
	}
}
add_action('admin_menu', 'wsp_add_options_page');


/**
 * Add the settings page
 * 
 * @return boolean
 */
function wsp_settings_page() {
	$path = trailingslashit(dirname(__FILE__));
	
	if (!file_exists( $path . 'settings.php')) {
		return false;
	}
	require_once($path . 'settings.php');
}


/**
 * Additional links on the plugin page
 *
 * @param array $links
 * @param str $file
 * @return array
 */
function wsp_plugin_row_meta($links, $file) {
	if ($file == plugin_basename(__FILE__)) {
		$settings_page = 'wp_sitemap_page';
		$links[] = '<a href="options-general.php?page=' . $settings_page .'">' . __('Settings','wp_sitemap_page') . '</a>';
		$links[] = '<a href="' . WSP_DONATE_LINK . '">'.__('Donate', 'wp_sitemap_page').'</a>';
	}
	return $links;
}
add_filter('plugin_row_meta', 'wsp_plugin_row_meta',10,2);



/**
 * Manage the option when we submit the form
 */
function wsp_save_settings() {
	
	// Register the settings
	register_setting( 'wp-sitemap-page', 'wsp_posts_by_category' );
	register_setting( 'wp-sitemap-page', 'wsp_exclude_pages' );
	register_setting( 'wp-sitemap-page', 'wsp_exclude_cpt_page' );
	register_setting( 'wp-sitemap-page', 'wsp_exclude_cpt_post' );
	register_setting( 'wp-sitemap-page', 'wsp_exclude_cpt_archive' );
	register_setting( 'wp-sitemap-page', 'wsp_exclude_cpt_author' );
	register_setting( 'wp-sitemap-page', 'wsp_is_display_copyright' );
	
	// Get the CPT (Custom Post Type)
	$args = array(
		'public'   => true,
		'_builtin' => false
	);
	$post_types = get_post_types( $args, 'names' ); 
	
	// list all the CPT
	foreach ( $post_types as $post_type ) {
		
		// extract CPT object
		$cpt = get_post_type_object( $post_type );
		
		// register settings
		register_setting( 'wp-sitemap-page', 'wsp_exclude_cpt_'.$cpt->name );
	}
} 
add_action( 'admin_init', 'wsp_save_settings' );



/***************************************************************
 * Manage the option
 ***************************************************************/

/**
 * Fonction de callback
 * 
 * @param array $matches
 */
function wsp_manage_option( array $matches = array() ) {
	
	global $the_post_id;
	
	if (isset($matches[1])) {
		$key = strtolower( $matches[1] );
		
		switch ($key) {
			// Get the title of the post
			case 'title':
				return get_the_title($the_post_id);
				break;
			
			// Get the URL of the post
			case 'permalink':
				return get_permalink($the_post_id);
				break;
			
			// Get the year of the post
			case 'year':
				return get_the_time('Y', $the_post_id);
				break;
			
			// Get the month of the post
			case 'monthnum':
				return get_the_time('m', $the_post_id);
				break;
			
			// Get the day of the post
			case 'day':
				return get_the_time('d', $the_post_id);
				break;
			
			// Get the day of the post
			case 'hour':
				return get_the_time('H', $the_post_id);
				break;
			
			// Get the day of the post
			case 'minute':
				return get_the_time('i', $the_post_id);
				break;
			
			// Get the day of the post
			case 'second':
				return get_the_time('s', $the_post_id);
				break;
			
			// Get the day of the post
			case 'post_id':
				return $the_post_id;
				break;
			
			// Get the day of the post
			case 'category':
				$categorie_info = get_the_category($the_post_id);
				if (!empty($categorie_info)) {
					$categorie_info = current($categorie_info);
					//return print_r($categorie_info,1);
					return (isset($categorie_info->name) ? $categorie_info->name : '');
				}
				return '';
				break;
			
			// default value
			default:
				if (isset($matches[0])) {
					return $matches[0];
				}
				return false;
				break;
		}
		
	}
	return false;
}


/***************************************************************
 * Tabs
 ***************************************************************/

/**
 * Get the current tab
 * 
 * @return Ambigous <string, mixed>|string
 */
function wsp_get_current_tab() {
	if (isset($_GET['tab'])) {
		return esc_html($_GET['tab']);
	} else {
		return 'main';
	}
}


/**
 * Display the tabs
 */
function wsp_show_tabs() {
	global $wp_db_version;
	
	// Get the current tab
	$current_tab = wsp_get_current_tab();
	
	// All tabs
	$tabs = array();
	$tabs['main']    = __('Settings', 'wp_sitemap_page');
	$tabs['about']   = __('How to use', 'wp_sitemap_page');
	
	// Generate the tab links
	$tab_links = array();
	foreach ($tabs as $tab_k => $tab_name) {
		$tab_curent = ($tab_k === $current_tab ? ' nav-tab-active' : '' );
		$tab_url = '?page=' . WSP_PLUGIN_NAME .'&amp;tab='.$tab_k;
		$tab_links[] = '<a class="nav-tab'.$tab_curent.'" href="'.$tab_url.'">'.$tab_name.'</a>';
	}
	
	// Since the 25 oct. 2010 WordPress include the tabs (in CSS)
	// The 25 oct. 2010 = WordPress version was "3.1-alpha"
	if ( $wp_db_version >= 15477 ) {
		// Tabs in CSS
		?>
		<h2 class="nav-tab-wrapper">
			<?php echo implode("\n", $tab_links); ?>
		</h2>
		<?php
	} else {
		// Tabs without CSS (instead, separate links with "|")
		?>
		<div>
			<?php echo implode(' | ', $tab_links); ?>
		</div>
		<?php
	}
	
	return;
}


/***************************************************************
 * Generate the sitemap
 ***************************************************************/


/**
 * Shortcode function that generate the sitemap
 * Use like this : [wp_sitemap_page]
 * 
 * @param $atts
 * @param $content
 */
function wsp_wp_sitemap_page_func( $atts, $content=null ) {
	
	// init
	$return = '';
	
	// display only some CPT
	// the "only" parameter always is higher than "exclude" options
	$only_cpt = (isset($atts['only']) ? sanitize_text_field($atts['only']) : '');
	
	
	// Exclude some pages (separated by a coma)
	$wsp_exclude_pages = trim(get_option('wsp_exclude_pages'));
	$wsp_is_display_copyright = get_option('wsp_is_display_copyright');
	
	$copyright_link = '';
	// add a copyright link
	if ($wsp_is_display_copyright==1) {
		$copyright_link = '<p><a href="http://wordpress.org/plugins/wp-sitemap-page/">'.__('Powered by "WP Sitemap Page"').'</a></p>';
	}
	
	// check if the attribute "only" is used
	switch ($only_cpt) {
		// display only PAGE
		case 'page':
			return wsp_return_content_type_page($wsp_exclude_pages).$copyright_link;
			break;
		// display only POST
		case 'post':
			return wsp_return_content_type_post().$copyright_link;
			break;
		// display only ARCHIVE
		case 'archive':
			return wsp_return_content_type_archive().$copyright_link;
			break;
		// display only AUTHOR
		case 'author':
			return wsp_return_content_type_author().$copyright_link;
			break;
		// display only CATEGORY
		case 'category':
			return wsp_return_content_type_categories().$copyright_link;
			break;
		// display only TAGS
		case 'tag':
			return wsp_return_content_type_tag().$copyright_link;
			break;
		// empty
		case '':
			// nothing but do
			break;
		default:
			// check if it's the name of a CPT
			
			// extract CPT object
			$cpt = get_post_type_object( $only_cpt );
			
			if ( !empty($cpt) ) {
				return wsp_return_content_type_items( $cpt, $only_cpt, $wsp_exclude_pages );
			}
			// end
	}
	
	
	//===============================================
	// Otherwise, display traditionnal sitemap
	//===============================================
	
	// exclude some custome post type (page, post, archive or author)
	// value : 0=do not exclude ; 1=exclude
	$wsp_exclude_cpt_page    = get_option('wsp_exclude_cpt_page');
	$wsp_exclude_cpt_post    = get_option('wsp_exclude_cpt_post');
	$wsp_exclude_cpt_archive = get_option('wsp_exclude_cpt_archive');
	$wsp_exclude_cpt_author  = get_option('wsp_exclude_cpt_author');
	
	
	// List the PAGES
	if ( empty($wsp_exclude_cpt_page) ) {
		$return .= wsp_return_content_type_page($wsp_exclude_pages);
	}
	
	// List the POSTS by CATEGORY
	if ( empty($wsp_exclude_cpt_post) ) {
		$return .= wsp_return_content_type_post();
	}
	
	// List the CPT
	$return .= wsp_return_content_type_lists($wsp_exclude_pages);
	
	// List the ARCHIVES
	if ( empty($wsp_exclude_cpt_archive) ) {
		$return .= wsp_return_content_type_archive();
	}
	
	// List the AUTHORS
	if ( empty($wsp_exclude_cpt_author) ) {
		$return .= wsp_return_content_type_author();
	}
	
	// return the content
	return $return.$copyright_link;
}
add_shortcode( 'wp_sitemap_page', 'wsp_wp_sitemap_page_func' );


/**
 * Return list of pages
 * 
 * @param str $return
 * @param str $wsp_exclude_pages
 */
function wsp_return_content_type_page($wsp_exclude_pages) {
	
	// init
	$return = '';
	
	// define the way the pages should be displayed
	$args = array();
	$args['title_li'] = '';
	$args['echo']     = '0';
	
	// exclude some pages ?
	if (!empty($wsp_exclude_pages)) {
		$args['exclude'] = $wsp_exclude_pages;
	}
	
	// get data
	$list_pages = wp_list_pages($args);
	
	// check it's not empty
	if (empty($list_pages)) {
		return '';
	}
	
	// add content
	$return .= '<h2 class="wsp-pages-title">'.__('Pages', 'wp_sitemap_page').'</h2>'."\n";
	$return .= '<ul class="wsp-pages-list">'."\n";
	$return .= $list_pages;
	$return .= '</ul>'."\n";
	
	// return content
	return $return;
}


/**
 * Return list of posts in the categories
 * 
 * @param str $return
 */
function wsp_return_content_type_post() {
	
	// init
	$return = '';
	
	// Get the categories
	$cats = get_categories();
	
	// check it's not empty
	if (empty($cats)) {
		return '';
	}
	
	// Get the categories
	$cats = wsp_generateMultiArray($cats);
	
	// add content
	$return .= '<h2 class="wsp-posts-list">'.__('Posts by category', 'wp_sitemap_page').'</h2>'."\n";
	$return .= wsp_htmlFromMultiArray($cats);
	
	// return content
	return $return;
}


/**
 * Return list of posts in the categories
 * 
 * @param str $return
 */
function wsp_return_content_type_categories() {
	
	// init
	$return = '';
	
	// Get the categories
	$cats = get_categories();
	
	// check it's not empty
	if (empty($cats)) {
		return '';
	}
	
	// add content
	$return .= '<h2 class="wsp-categories-list">'.__('Categories', 'wp_sitemap_page').'</h2>'."\n";
	
	foreach ($cats as $cat) {
		$return .= "\t".'<li><a href="'.get_category_link($cat->cat_ID).'">'.$cat->name.'</a></li>'."\n";
	}
	
	// return content
	return $return;
}


/**
 * Return list of posts in the categories
 * 
 * @param str $return
 */
function wsp_return_content_type_tag() {
	
	// init
	$return = '';
	
	// Get the categories
	$posttags = get_tags();
	
	// check it's not empty
	if (empty($posttags)) {
		return '';
	}
	
	// add content
	$return .= '<h2 class="wsp-tags-list">'.__('Tags', 'wp_sitemap_page').'</h2>'."\n";
	
	foreach($posttags as $tag) {
		$return .= "\t".'<li><a href="'.get_tag_link($tag->term_id).'">'.$tag->name.'</a></li>'."\n";
	}
	
	// return content
	return $return;
}


/**
 * Return list of archives
 * 
 * @param str $return
 */
function wsp_return_content_type_archive() {
	
	// init
	$return = '';
	
	// define the way the pages should be displayed
	$args = array();
	$args['echo'] = 0;
	
	// get data
	$list_archives = wp_get_archives($args);
	
	// check it's not empty
	if (empty($list_archives)) {
		return '';
	}
	
	// add content
	$return .= '<h2 class="wsp-archives-title">'.__('Archives', 'wp_sitemap_page').'</h2>'."\n";
	$return .= '<ul class="wsp-archives-list">'."\n";
	$return .= $list_archives;
	$return .= '</ul>'."\n";
	
	// return content
	return $return;
}


/**
 * Return list of authors
 * 
 * @param str $return
 */
function wsp_return_content_type_author() {
	
	// init
	$return = '';
	
	// define the way the pages should be displayed
	$args = array();
	$args['echo'] = 0;
	
	// get data
	$list_authors = wp_list_authors($args);
	
	// check it's not empty
	if (empty($list_authors)) {
		return '';
	}
	
	// add content
	$return .= '<h2 class="wsp-authors-title">'.__('Authors', 'wp_sitemap_page').'</h2>'."\n";
	$return .= '<ul class="wsp-authors-list">'."\n";
	$return .= $list_authors;
	$return .= '</ul>'."\n";
	
	// return content
	return $return;
}


/**
 * Return list of all other custom post type
 * 
 * @param str $return
 */
function wsp_return_content_type_lists($wsp_exclude_pages) {
	
	// init
	$return = '';
	
	// Get the CPT (Custom Post Type)
	$args = array(
		'public'   => true,
		'_builtin' => false
	);
	$post_types = get_post_types( $args, 'names' ); 
	
	// check it's not empty
	if (empty($post_types)) {
		return '';
	}
	
	// list all the CPT
	foreach ( $post_types as $post_type ) {
		
		// extract CPT object
		$cpt = get_post_type_object( $post_type );
		
		// Is this CPT already excluded ?
		$wsp_exclude_cpt = get_option('wsp_exclude_cpt_'.$cpt->name);
		
		if ( empty($wsp_exclude_cpt) ) {
			$return .= wsp_return_content_type_items( $cpt, $post_type, $wsp_exclude_pages );
		}
	}
	
	// return content
	return $return;
}


/**
 * Return list of all other custom post type
 * 
 * @param str $return
 */
function wsp_return_content_type_items( $cpt, $post_type, $wsp_exclude_pages ) {
	
	// init
	$return = '';
	
	// List the pages
	$list_pages = '';
	
	// define the way the pages should be displayed
	$args = array();
	$args['post_type'] = $post_type;
	$args['posts_per_page'] = 999999;
	$args['suppress_filters'] = 0;
	
	// exclude some pages ?
	if (!empty($wsp_exclude_pages)) {
		$args['exclude'] = $wsp_exclude_pages;
	}
	
	// Query to get the current custom post type
	$posts_cpt = get_posts( $args );
	
	// List all the results
	if ( !empty($posts_cpt) ) {
		foreach( $posts_cpt as $post_cpt ) {
			$list_pages .= '<li><a href="'.get_permalink( $post_cpt->ID ).'">'.$post_cpt->post_title.'</a></li>'."\n";
		}
	}
	
	// Return the data (if it exists)
	if (!empty($list_pages)) {
		$return .= '<h2 class="wsp-'.$post_type.'s-title">' . $cpt->label . '</h2>'."\n";
		$return .= '<ul class="wsp-'.$post_type.'s-list">'."\n";
		$return .= $list_pages;
		$return .= '</ul>'."\n";
	}
	
	// return content
	return $return;
}


/**
 * Generate a multidimensional array from a simple linear array using a recursive function
 * 
 * @param array $arr
 * @param int $parent
 */
function wsp_generateMultiArray( array $arr = array() , $parent = 0 ) {
	
	// check if not empty
	if (empty($arr)) {
		return array();
	}
	
	$pages = array();
	// go through the array
	foreach($arr as $k => $page) {
		if ($page->parent == $parent) {
			$page->sub = isset($page->sub) ? $page->sub : wsp_generateMultiArray($arr, $page->cat_ID);
			$pages[] = $page;
		}
	}
	
	return $pages;
}


/**
 * Display the multidimensional array using a recursive function
 * 
 * @param array $nav
 * @param bool $useUL
 */
function wsp_htmlFromMultiArray( array $nav = array() , $useUL = true ) {
	
	// check if not empty
	if (empty($nav)) {
		return '';
	}
	
	$html = '';
	if ($useUL === true) {
		$html .= '<ul class="wsp-posts-list">'."\n";
	}
	
	// List all the categories
	foreach ($nav as $page) {
		$html .= "\t".'<li><strong class="wsp-category-title">'
			.sprintf( __('Category: %1$s', 'wp_sitemap_page'), '<a href="'.get_category_link($page->cat_ID).'">'.$page->name.'</a>' )
			.'</strong>'."\n";
		
		$post_by_cat = wsp_displayPostByCat($page->cat_ID);
		
		// List of posts for this category
		$category_recursive = '';
		if (!empty($page->sub)) {
			// Use recursive function to get the childs categories
			$category_recursive = wsp_htmlFromMultiArray($page->sub, false);
		}
		
		// display if it exist
		if ( !empty($post_by_cat) || !empty($category_recursive) ) {
			$html .= '<ul class="wsp-posts-list">';
		}
		if ( !empty($post_by_cat) ) {
			$html .= $post_by_cat;
		}
		if ( !empty($category_recursive) ) {
			$html .= $category_recursive;
		}
		if ( !empty($post_by_cat) || !empty($category_recursive) ) {
			$html .= '</ul>';
		}
		
		$html .= '</li>'."\n";
	}
	
	if ($useUL === true) {
		$html .= '</ul>'."\n";
	}
	return $html;
}


/**
 * Display the multidimensional array using a recursive function
 * 
 * @param int $cat_id
 */
function wsp_displayPostByCat( $cat_id ) {
	
	global $the_post_id;
	
	// init
	$html = '';
	
	// List of posts for this category
	$the_posts = get_posts('numberposts=9999&cat='.$cat_id);
	
	// check if not empty
	if (empty($the_posts)) {
		return '';
	}
	
	// determine the code to place in the textarea
	$wsp_posts_by_category = get_option('wsp_posts_by_category');
	if ( $wsp_posts_by_category === false ) {
		// this option does not exists
		$wsp_posts_by_category = __('<a href="{permalink}">{title}</a> ({monthnum}/{day}/{year})', 'wp_sitemap_page');
		
		// save this option
		add_option( 'wsp_posts_by_category', $wsp_posts_by_category );
	}
	
	foreach ( $the_posts as $the_post ) {
		// Display the line of a post
		$get_category = get_the_category($the_post->ID);
		
		// Display the post only if it is on the deepest category
		if ($get_category[0]->cat_ID == $cat_id) {
			
			// get post ID
			$the_post_id = $the_post->ID;
			
			// replace the ID by the real value
			$html .= "\t\t".'<li class="wsp-post">'
				.preg_replace_callback( '#\{(.*)\}#Ui', 'wsp_manage_option', $wsp_posts_by_category)
				.'</li>'."\n";
		}
	}
	
	return $html;
}