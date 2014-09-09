<?php
/*
Plugin Name: Automatic Footer Copyright
Plugin URI: http://wpblogexperts.com/plugins
Description: A plugin to add automatic copyright info to your footer.
Author: Ishan Sharma
Author URI: http://ishansharma.com
Version: 1.3
License: GPL2
*/

// Following Function Gets Date Of First Post. FROM http://alex.leonard.ie
function first_post_date($format = "Y") {
	$fp_args = array (
		'numberposts' => 1,
		'post_status' => 'publish',
		'order' => 'ASC'
	);
	$fp_get_all = get_posts($fp_args);
	$fp_first_post = $fp_get_all[0];
	$fp_first_post_date = $fp_first_post->post_date;
	$output = date($format, strtotime($fp_first_post_date));
	return $output;
}

function display_copyright() {
	$current_year = date("Y");
	$first_post_year = first_post_date();
	if ($current_year == $first_post_year)
	{
		$copy_text = sprintf("&copy; %s <a href = \"%s\">%s</a>", $current_year, get_bloginfo('url'), get_bloginfo('name'));
	}
	else
	{
		$copy_text = sprintf("&copy; %s-%s <a href = \"%s\">%s</a>", $first_post_year, $current_year,get_bloginfo('url'), get_bloginfo('name'));
	}
	return "<div class = \"creds\">$copy_text</div>";
}
function footer_auto_copyright() {
    echo display_copyright();
}
add_filter( 'genesis_footer_creds_text', 'footer_auto_copyright' );