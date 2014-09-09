<?php 
/*
Plugin Name: Custom Facebook Feed
Plugin URI: http://smashballoon.com/custom-facebook-feed
Description: Add a completely customizable Facebook feed to your WordPress site
Version: 1.9.9.3
Author: Smash Balloon
Author URI: http://smashballoon.com/
License: GPLv2 or later
*/
/* 
Copyright 2013  Smash Balloon LLC (email : hey@smashballoon.com)
This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.
This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.
You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
//Include admin
include dirname( __FILE__ ) .'/custom-facebook-feed-admin.php';

// Add shortcodes
add_shortcode('custom-facebook-feed', 'display_cff');
function display_cff($atts) {
    
    //Style options
    $options = get_option('cff_style_settings');
    //Create the types string to set as shortcode default
    $include_string = '';
    if($options[ 'cff_show_author' ]) $include_string .= 'author,';
    if($options[ 'cff_show_text' ]) $include_string .= 'text,';
    if($options[ 'cff_show_desc' ]) $include_string .= 'desc,';
    if($options[ 'cff_show_shared_links' ]) $include_string .= 'sharedlinks,';
    if($options[ 'cff_show_date' ]) $include_string .= 'date,';
    if($options[ 'cff_show_media' ]) $include_string .= 'media,';
    if($options[ 'cff_show_event_title' ]) $include_string .= 'eventtitle,';
    if($options[ 'cff_show_event_details' ]) $include_string .= 'eventdetails,';
    if($options[ 'cff_show_meta' ]) $include_string .= 'social,';
    if($options[ 'cff_show_link' ]) $include_string .= 'link,';
    if($options[ 'cff_show_like_box' ]) $include_string .= 'likebox,';
    //Pass in shortcode attrbutes
    $atts = shortcode_atts(
    array(
        'accesstoken' => trim( get_option('cff_access_token') ),
        'id' => get_option('cff_page_id'),
        'pagetype' => get_option('cff_page_type'),
        'num' => get_option('cff_num_show'),
        'limit' => get_option('cff_post_limit'),
        'others' => '',
        'showpostsby' => get_option('cff_show_others'),
        'cachetime' => get_option('cff_cache_time'),
        'cacheunit' => get_option('cff_cache_time_unit'),
        'locale' => get_option('cff_locale'),
        'ajax' => get_option('cff_ajax'),
        'width' => isset($options[ 'cff_feed_width' ]) ? $options[ 'cff_feed_width' ] : '',
        'height' => isset($options[ 'cff_feed_height' ]) ? $options[ 'cff_feed_height' ] : '',
        'padding' => isset($options[ 'cff_feed_padding' ]) ? $options[ 'cff_feed_padding' ] : '',
        'bgcolor' => isset($options[ 'cff_bg_color' ]) ? $options[ 'cff_bg_color' ] : '',
        'showauthor' => '',
        'showauthornew' => isset($options[ 'cff_show_author' ]) ? $options[ 'cff_show_author' ] : '',
        'class' => isset($options[ 'cff_class' ]) ? $options[ 'cff_class' ] : '',
        'layout' => isset($options[ 'cff_preset_layout' ]) ? $options[ 'cff_preset_layout' ] : '',
        'include' => $include_string,
        'exclude' => '',
        //Typography
        'textformat' => isset($options[ 'cff_title_format' ]) ? $options[ 'cff_title_format' ] : '',
        'textsize' => isset($options[ 'cff_title_size' ]) ? $options[ 'cff_title_size' ] : '',
        'textweight' => isset($options[ 'cff_title_weight' ]) ? $options[ 'cff_title_weight' ] : '',
        'textcolor' => isset($options[ 'cff_title_color' ]) ? $options[ 'cff_title_color' ] : '',
        'textlinkcolor' => isset($options[ 'cff_posttext_link_color' ]) ? $options[ 'cff_posttext_link_color' ] : '',
        'textlink' => isset($options[ 'cff_title_link' ]) ? $options[ 'cff_title_link' ] : '',
        'posttags' => isset($options[ 'cff_post_tags' ]) ? $options[ 'cff_post_tags' ] : '',
        'linkhashtags' => isset($options[ 'cff_link_hashtags' ]) ? $options[ 'cff_link_hashtags' ] : '',

        //Description
        'descsize' => isset($options[ 'cff_body_size' ]) ? $options[ 'cff_body_size' ] : '',
        'descweight' => isset($options[ 'cff_body_weight' ]) ? $options[ 'cff_body_weight' ] : '',
        'desccolor' => isset($options[ 'cff_body_color' ]) ? $options[ 'cff_body_color' ] : '',
        'linktitleformat' => isset($options[ 'cff_link_title_format' ]) ? $options[ 'cff_link_title_format' ] : '',
        'linktitlesize' => isset($options[ 'cff_link_title_size' ]) ? $options[ 'cff_link_title_size' ] : '',
        'linktitlecolor' => isset($options[ 'cff_link_title_color' ]) ? $options[ 'cff_link_title_color' ] : '',
        'linkurlcolor' => isset($options[ 'cff_link_url_color' ]) ? $options[ 'cff_link_url_color' ] : '',
        'linkbgcolor' => isset($options[ 'cff_link_bg_color' ]) ? $options[ 'cff_link_bg_color' ] : '',
        'linkbordercolor' => isset($options[ 'cff_link_border_color' ]) ? $options[ 'cff_link_border_color' ] : '',
        'disablelinkbox' => isset($options[ 'cff_disable_link_box' ]) ? $options[ 'cff_disable_link_box' ] : '',

        //Author
        'authorsize' => isset($options[ 'cff_author_size' ]) ? $options[ 'cff_author_size' ] : '',
        'authorcolor' => isset($options[ 'cff_author_color' ]) ? $options[ 'cff_author_color' ] : '',

        //Event title
        'eventtitleformat' => isset($options[ 'cff_event_title_format' ]) ? $options[ 'cff_event_title_format' ] : '',
        'eventtitlesize' => isset($options[ 'cff_event_title_size' ]) ? $options[ 'cff_event_title_size' ] : '',
        'eventtitleweight' => isset($options[ 'cff_event_title_weight' ]) ? $options[ 'cff_event_title_weight' ] : '',
        'eventtitlecolor' => isset($options[ 'cff_event_title_color' ]) ? $options[ 'cff_event_title_color' ] : '',
        'eventtitlelink' => isset($options[ 'cff_event_title_link' ]) ? $options[ 'cff_event_title_link' ] : '',
        //Event date
        'eventdatesize' => isset($options[ 'cff_event_date_size' ]) ? $options[ 'cff_event_date_size' ] : '',
        'eventdateweight' => isset($options[ 'cff_event_date_weight' ]) ? $options[ 'cff_event_date_weight' ] : '',
        'eventdatecolor' => isset($options[ 'cff_event_date_color' ]) ? $options[ 'cff_event_date_color' ] : '',
        'eventdatepos' => isset($options[ 'cff_event_date_position' ]) ? $options[ 'cff_event_date_position' ] : '',
        'eventdateformat' => isset($options[ 'cff_event_date_formatting' ]) ? $options[ 'cff_event_date_formatting' ] : '',
        'eventdatecustom' => isset($options[ 'cff_event_date_custom' ]) ? $options[ 'cff_event_date_custom' ] : '',
        //Event details
        'eventdetailssize' => isset($options[ 'cff_event_details_size' ]) ? $options[ 'cff_event_details_size' ] : '',
        'eventdetailsweight' => isset($options[ 'cff_event_details_weight' ]) ? $options[ 'cff_event_details_weight' ] : '',
        'eventdetailscolor' => isset($options[ 'cff_event_details_color' ]) ? $options[ 'cff_event_details_color' ] : '',
        'eventlinkcolor' => isset($options[ 'cff_event_link_color' ]) ? $options[ 'cff_event_link_color' ] : '',
        //Date
        'datepos' => isset($options[ 'cff_date_position' ]) ? $options[ 'cff_date_position' ] : '',
        'datesize' => isset($options[ 'cff_date_size' ]) ? $options[ 'cff_date_size' ] : '',
        'dateweight' => isset($options[ 'cff_date_weight' ]) ? $options[ 'cff_date_weight' ] : '',
        'datecolor' => isset($options[ 'cff_date_color' ]) ? $options[ 'cff_date_color' ] : '',
        'dateformat' => isset($options[ 'cff_date_formatting' ]) ? $options[ 'cff_date_formatting' ] : '',
        'datecustom' => isset($options[ 'cff_date_custom' ]) ? $options[ 'cff_date_custom' ] : '',
        'timezone' => isset($options[ 'cff_timezone' ]) ? $options[ 'cff_timezone' ] : 'America/Chicago',

        //Link to Facebook
        'linksize' => isset($options[ 'cff_link_size' ]) ? $options[ 'cff_link_size' ] : '',
        'linkweight' => isset($options[ 'cff_link_weight' ]) ? $options[ 'cff_link_weight' ] : '',
        'linkcolor' => isset($options[ 'cff_link_color' ]) ? $options[ 'cff_link_color' ] : '',
        'viewlinktext' => isset($options[ 'cff_view_link_text' ]) ? $options[ 'cff_view_link_text' ] : '',
        'linktotimeline' => isset($options[ 'cff_link_to_timeline' ]) ? $options[ 'cff_link_to_timeline' ] : '',
        //Social
        'iconstyle' => isset($options[ 'cff_icon_style' ]) ? $options[ 'cff_icon_style' ] : '',
        'socialtextcolor' => isset($options[ 'cff_meta_text_color' ]) ? $options[ 'cff_meta_text_color' ] : '',
        'socialbgcolor' => isset($options[ 'cff_meta_bg_color' ]) ? $options[ 'cff_meta_bg_color' ] : '',
        //Misc
        'textlength' => get_option('cff_title_length'),
        'desclength' => get_option('cff_body_length'),
        'likeboxpos' => isset($options[ 'cff_like_box_position' ]) ? $options[ 'cff_like_box_position' ] : '',
        'likeboxoutside' => isset($options[ 'cff_like_box_outside' ]) ? $options[ 'cff_like_box_outside' ] : '',
        'likeboxcolor' => isset($options[ 'cff_likebox_bg_color' ]) ? $options[ 'cff_likebox_bg_color' ] : '',
        'likeboxtextcolor' => isset($options[ 'cff_like_box_text_color' ]) ? $options[ 'cff_like_box_text_color' ] : '',
        'likeboxwidth' => isset($options[ 'cff_likebox_width' ]) ? $options[ 'cff_likebox_width' ] : '',
        'likeboxheight' => isset($options[ 'cff_likebox_height' ]) ? $options[ 'cff_likebox_height' ] : '',
        'likeboxfaces' => isset($options[ 'cff_like_box_faces' ]) ? $options[ 'cff_like_box_faces' ] : '',
        'likeboxborder' => isset($options[ 'cff_like_box_border' ]) ? $options[ 'cff_like_box_border' ] : '',

        //Page Header
        'showheader' => isset($options[ 'cff_show_header' ]) ? $options[ 'cff_show_header' ] : '',
        'headeroutside' => isset($options[ 'cff_header_outside' ]) ? $options[ 'cff_header_outside' ] : '',
        'headertext' => isset($options[ 'cff_header_text' ]) ? $options[ 'cff_header_text' ] : '',
        'headerbg' => isset($options[ 'cff_header_bg_color' ]) ? $options[ 'cff_header_bg_color' ] : '',
        'headerpadding' => isset($options[ 'cff_header_padding' ]) ? $options[ 'cff_header_padding' ] : '',
        'headertextsize' => isset($options[ 'cff_header_text_size' ]) ? $options[ 'cff_header_text_size' ] : '',
        'headertextweight' => isset($options[ 'cff_header_text_weight' ]) ? $options[ 'cff_header_text_weight' ] : '',
        'headertextcolor' => isset($options[ 'cff_header_text_color' ]) ? $options[ 'cff_header_text_color' ] : '',
        'headericon' => isset($options[ 'cff_header_icon' ]) ? $options[ 'cff_header_icon' ] : '',
        'headericoncolor' => isset($options[ 'cff_header_icon_color' ]) ? $options[ 'cff_header_icon_color' ] : '',
        'headericonsize' => isset($options[ 'cff_header_icon_size' ]) ? $options[ 'cff_header_icon_size' ] : '',

        'videoheight' => isset($options[ 'cff_video_height' ]) ? $options[ 'cff_video_height' ] : '',
        'videoaction' => isset($options[ 'cff_video_action' ]) ? $options[ 'cff_video_action' ] : '',
        'sepcolor' => isset($options[ 'cff_sep_color' ]) ? $options[ 'cff_sep_color' ] : '',
        'sepsize' => isset($options[ 'cff_sep_size' ]) ? $options[ 'cff_sep_size' ] : '',

        //Translate
        'seemoretext' => isset($options[ 'cff_see_more_text' ]) ? $options[ 'cff_see_more_text' ] : '',
        'seelesstext' => isset($options[ 'cff_see_less_text' ]) ? $options[ 'cff_see_less_text' ] : '',
        'facebooklinktext' => isset($options[ 'cff_facebook_link_text' ]) ? $options[ 'cff_facebook_link_text' ] : '',
        'photostext' => isset($options[ 'cff_translate_photos_text' ]) ? $options[ 'cff_translate_photos_text' ] : ''
    ), $atts);
    /********** GENERAL **********/
    $cff_page_type = $atts[ 'pagetype' ];
    ($cff_page_type == 'group') ? $cff_is_group = true : $cff_is_group = false;

    $cff_feed_width = $atts['width'];
    $cff_feed_height = $atts[ 'height' ];
    $cff_feed_padding = $atts[ 'padding' ];
    $cff_bg_color = $atts[ 'bgcolor' ];
    $cff_show_author = $atts[ 'showauthornew' ];
    $cff_cache_time = $atts[ 'cachetime' ];
    $cff_locale = $atts[ 'locale' ];
    if ( empty($cff_locale) || !isset($cff_locale) || $cff_locale == '' ) $cff_locale = 'en_US';
    if (!isset($cff_cache_time)) $cff_cache_time = 0;
    $cff_cache_time_unit = $atts[ 'cacheunit' ];
    $cff_class = $atts['class'];
    //Compile feed styles
    $cff_feed_styles = 'style="';
    if ( !empty($cff_feed_width) ) $cff_feed_styles .= 'width:' . $cff_feed_width . '; ';
    if ( !empty($cff_feed_height) ) $cff_feed_styles .= 'height:' . $cff_feed_height . '; ';
    if ( !empty($cff_feed_padding) ) $cff_feed_styles .= 'padding:' . $cff_feed_padding . '; ';
    if ( !empty($cff_bg_color) ) $cff_feed_styles .= 'background-color:#' . str_replace('#', '', $cff_bg_color) . '; ';
    $cff_feed_styles .= '"';
    //Like box
    $cff_like_box_position = $atts[ 'likeboxpos' ];
    $cff_like_box_outside = $atts[ 'likeboxoutside' ];
    //Open links in new window?
    $target = 'target="_blank"';
    /********** POST TYPES **********/
    $cff_show_links_type = true;
    $cff_show_event_type = true;
    $cff_show_video_type = true;
    $cff_show_photos_type = true;
    $cff_show_status_type = true;
    $cff_events_only = false;
    //Are we showing ONLY events?
    if ($cff_show_event_type && !$cff_show_links_type && !$cff_show_video_type && !$cff_show_photos_type && !$cff_show_status_type) $cff_events_only = true;
    /********** LAYOUT **********/
    $cff_includes = $atts[ 'include' ];
    //Look for non-plural version of string in the types string in case user specifies singular in shortcode
    $cff_show_author = false;
    $cff_show_text = false;
    $cff_show_desc = false;
    $cff_show_shared_links = false;
    $cff_show_date = false;
    $cff_show_media = false;
    $cff_show_event_title = false;
    $cff_show_event_details = false;
    $cff_show_meta = false;
    $cff_show_link = false;
    $cff_show_like_box = false;
    if ( stripos($cff_includes, 'author') !== false ) $cff_show_author = true;
    if ( stripos($cff_includes, 'text') !== false ) $cff_show_text = true;
    if ( stripos($cff_includes, 'desc') !== false ) $cff_show_desc = true;
    if ( stripos($cff_includes, 'sharedlink') !== false ) $cff_show_shared_links = true;
    if ( stripos($cff_includes, 'date') !== false ) $cff_show_date = true;
    if ( stripos($cff_includes, 'media') !== false ) $cff_show_media = true;
    if ( stripos($cff_includes, 'eventtitle') !== false ) $cff_show_event_title = true;
    if ( stripos($cff_includes, 'eventdetail') !== false ) $cff_show_event_details = true;
    if ( stripos($cff_includes, 'social') !== false ) $cff_show_meta = true;
    if ( stripos($cff_includes, ',link') !== false ) $cff_show_link = true; //comma used to separate it from 'sharedlinks' - which also contains 'link' string
    if ( stripos($cff_includes, 'like') !== false ) $cff_show_like_box = true;


    //Exclude string
    $cff_excludes = $atts[ 'exclude' ];
    //Look for non-plural version of string in the types string in case user specifies singular in shortcode
    if ( stripos($cff_excludes, 'author') !== false ) $cff_show_author = false;
    if ( stripos($cff_excludes, 'text') !== false ) $cff_show_text = false;
    if ( stripos($cff_excludes, 'desc') !== false ) $cff_show_desc = false;
    if ( stripos($cff_excludes, 'sharedlink') !== false ) $cff_show_shared_links = false;
    if ( stripos($cff_excludes, 'date') !== false ) $cff_show_date = false;
    if ( stripos($cff_excludes, 'media') !== false ) $cff_show_media = false;
    if ( stripos($cff_excludes, 'eventtitle') !== false ) $cff_show_event_title = false;
    if ( stripos($cff_excludes, 'eventdetail') !== false ) $cff_show_event_details = false;
    if ( stripos($cff_excludes, 'social') !== false ) $cff_show_meta = false;
    if ( stripos($cff_excludes, ',link') !== false ) $cff_show_link = false; //comma used to separate it from 'sharedlinks' - which also contains 'link' string
    if ( stripos($cff_excludes, 'like') !== false ) $cff_show_like_box = false;


    //Set free version to thumb layout by default as layout option not available on settings page
    $cff_preset_layout = 'thumb';

    //If the old shortcode option 'showauthor' is being used then apply it
    $cff_show_author_old = $atts[ 'showauthor' ];
    if( $cff_show_author_old == 'false' ) $cff_show_author = false;
    if( $cff_show_author_old == 'true' ) $cff_show_author = true;

    
    /********** META **********/
    $cff_icon_style = $atts[ 'iconstyle' ];
    $cff_meta_text_color = $atts[ 'socialtextcolor' ];
    $cff_meta_bg_color = $atts[ 'socialbgcolor' ];
    $cff_meta_styles = 'style="';
    if ( !empty($cff_meta_text_color) ) $cff_meta_styles .= 'color:#' . str_replace('#', '', $cff_meta_text_color) . ';';
    if ( !empty($cff_meta_bg_color) ) $cff_meta_styles .= 'background-color:#' . str_replace('#', '', $cff_meta_bg_color) . ';';
    $cff_meta_styles .= '"';
    $cff_nocomments_text = isset($options[ 'cff_nocomments_text' ]) ? $options[ 'cff_nocomments_text' ] : '';
    $cff_hide_comments = isset($options[ 'cff_hide_comments' ]) ? $options[ 'cff_hide_comments' ] : '';
    if (!isset($cff_nocomments_text) || empty($cff_nocomments_text)) $cff_hide_comments = true;
    /********** TYPOGRAPHY **********/
    //See More text
    $cff_see_more_text = $atts[ 'seemoretext' ];
    $cff_see_less_text = $atts[ 'seelesstext' ];
    //See Less text
    //Title
    $cff_title_format = $atts[ 'textformat' ];
    if (empty($cff_title_format)) $cff_title_format = 'p';
    $cff_title_size = $atts[ 'textsize' ];
    $cff_title_weight = $atts[ 'textweight' ];
    $cff_title_color = $atts[ 'textcolor' ];
    $cff_title_styles = 'style="';
    if ( !empty($cff_title_size) && $cff_title_size != 'inherit' ) $cff_title_styles .=  'font-size:' . $cff_title_size . 'px; ';
    if ( !empty($cff_title_weight) && $cff_title_weight != 'inherit' ) $cff_title_styles .= 'font-weight:' . $cff_title_weight . '; ';
    if ( !empty($cff_title_color) ) $cff_title_styles .= 'color:#' . str_replace('#', '', $cff_title_color) . ';';
    $cff_title_styles .= '"';
    $cff_title_link = $atts[ 'textlink' ];

    ( $cff_title_link == 'on' || $cff_title_link == 'true' || $cff_title_link == true ) ? $cff_title_link = true : $cff_title_link = false;
    if( $atts[ 'textlink' ] == 'false' ) $cff_title_link = false;

    //Author
    $cff_author_size = $atts[ 'authorsize' ];
    $cff_author_color = $atts[ 'authorcolor' ];
    $cff_author_styles = 'style="';
    if ( !empty($cff_author_size) && $cff_author_size != 'inherit' ) $cff_author_styles .=  'font-size:' . $cff_author_size . 'px; ';
    if ( !empty($cff_author_color) ) $cff_author_styles .= 'color:#' . str_replace('#', '', $cff_author_color) . ';';
    $cff_author_styles .= '"';

    //Description
    $cff_body_size = $atts[ 'descsize' ];
    $cff_body_weight = $atts[ 'descweight' ];
    $cff_body_color = $atts[ 'desccolor' ];
    $cff_body_styles = 'style="';
    if ( !empty($cff_body_size) && $cff_body_size != 'inherit' ) $cff_body_styles .=  'font-size:' . $cff_body_size . 'px; ';
    if ( !empty($cff_body_weight) && $cff_body_weight != 'inherit' ) $cff_body_styles .= 'font-weight:' . $cff_body_weight . '; ';
    if ( !empty($cff_body_color) ) $cff_body_styles .= 'color:#' . str_replace('#', '', $cff_body_color) . ';';
    $cff_body_styles .= '"';

    //Shared link title
    $cff_link_title_format = $atts[ 'linktitleformat' ];
    if (empty($cff_link_title_format)) $cff_link_title_format = 'p';
    $cff_link_title_size = $atts[ 'linktitlesize' ];
    $cff_link_title_color = $atts[ 'linktitlecolor' ];
    $cff_link_url_color = $atts[ 'linkurlcolor' ];

    $cff_link_title_styles = 'style="';
    if ( !empty($cff_link_title_size) && $cff_link_title_size != 'inherit' ) $cff_link_title_styles .=  'font-size:' . $cff_link_title_size . 'px;';
    $cff_link_title_styles .= '"';

    //Shared link box
    $cff_link_bg_color = $atts[ 'linkbgcolor' ];
    $cff_link_border_color = $atts[ 'linkbordercolor' ];
    $cff_disable_link_box = $atts['disablelinkbox'];
    ($cff_disable_link_box == 'true' || $cff_disable_link_box == 'on') ? $cff_disable_link_box = true : $cff_disable_link_box = false;

    $cff_link_box_styles = 'style="';
    if ( !empty($cff_link_border_color) ) $cff_link_box_styles .=  'border: 1px solid #' . str_replace('#', '', $cff_link_border_color) . '; ';
    if ( !empty($cff_link_bg_color) ) $cff_link_box_styles .= 'background-color: #' . str_replace('#', '', $cff_link_bg_color) . ';';
    $cff_link_box_styles .= '"';

    //Event Title
    $cff_event_title_format = $atts[ 'eventtitleformat' ];
    if (empty($cff_event_title_format)) $cff_event_title_format = 'p';
    $cff_event_title_size = $atts[ 'eventtitlesize' ];
    $cff_event_title_weight = $atts[ 'eventtitleweight' ];
    $cff_event_title_color = $atts[ 'eventtitlecolor' ];
    $cff_event_title_styles = 'style="';
    if ( !empty($cff_event_title_size) && $cff_event_title_size != 'inherit' ) $cff_event_title_styles .=  'font-size:' . $cff_event_title_size . 'px; ';
    if ( !empty($cff_event_title_weight) && $cff_event_title_weight != 'inherit' ) $cff_event_title_styles .= 'font-weight:' . $cff_event_title_weight . '; ';
    if ( !empty($cff_event_title_color) ) $cff_event_title_styles .= 'color:#' . str_replace('#', '', $cff_event_title_color) . ';';
    $cff_event_title_styles .= '"';
    $cff_event_title_link = $atts[ 'eventtitlelink' ];
    //Event Date
    $cff_event_date_size = $atts[ 'eventdatesize' ];
    $cff_event_date_weight = $atts[ 'eventdateweight' ];
    $cff_event_date_color = $atts[ 'eventdatecolor' ];
    $cff_event_date_position = $atts[ 'eventdatepos' ];
    $cff_event_date_formatting = $atts[ 'eventdateformat' ];
    $cff_event_date_custom = $atts[ 'eventdatecustom' ];
    $cff_event_date_styles = 'style="';
    if ( !empty($cff_event_date_size) && $cff_event_date_size != 'inherit' ) $cff_event_date_styles .=  'font-size:' . $cff_event_date_size . 'px; ';
    if ( !empty($cff_event_date_weight) && $cff_event_date_weight != 'inherit' ) $cff_event_date_styles .= 'font-weight:' . $cff_event_date_weight . '; ';
    if ( !empty($cff_event_date_color) ) $cff_event_date_styles .= 'color:#' . str_replace('#', '', $cff_event_date_color) . ';';
    $cff_event_date_styles .= '"';
    //Event Details
    $cff_event_details_size = $atts[ 'eventdetailssize' ];
    $cff_event_details_weight = $atts[ 'eventdetailsweight' ];
    $cff_event_details_color = $atts[ 'eventdetailscolor' ];
    $cff_event_link_color = $atts[ 'eventlinkcolor' ];
    $cff_event_details_styles = 'style="';
    if ( !empty($cff_event_details_size) && $cff_event_details_size != 'inherit' ) $cff_event_details_styles .=  'font-size:' . $cff_event_details_size . 'px; ';
    if ( !empty($cff_event_details_weight) && $cff_event_details_weight != 'inherit' ) $cff_event_details_styles .= 'font-weight:' . $cff_event_details_weight . '; ';
    if ( !empty($cff_event_details_color) ) $cff_event_details_styles .= 'color:#' . str_replace('#', '', $cff_event_details_color) . ';';
    $cff_event_details_styles .= '"';
    //Date
    $cff_date_position = $atts[ 'datepos' ];
    if (!isset($cff_date_position)) $cff_date_position = 'below';
    $cff_date_size = $atts[ 'datesize' ];
    $cff_date_weight = $atts[ 'dateweight' ];
    $cff_date_color = $atts[ 'datecolor' ];
    $cff_date_styles = 'style="';
    if ( !empty($cff_date_size) && $cff_date_size != 'inherit' ) $cff_date_styles .=  'font-size:' . $cff_date_size . 'px; ';
    if ( !empty($cff_date_weight) && $cff_date_weight != 'inherit' ) $cff_date_styles .= 'font-weight:' . $cff_date_weight . '; ';
    if ( !empty($cff_date_color) ) $cff_date_styles .= 'color:#' . str_replace('#', '', $cff_date_color) . ';';
    $cff_date_styles .= '"';
    $cff_date_before = isset($options[ 'cff_date_before' ]) ? $options[ 'cff_date_before' ] : '';
    $cff_date_after = isset($options[ 'cff_date_after' ]) ? $options[ 'cff_date_after' ] : '';
    //Set user's timezone based on setting
    $cff_timezone = $atts['timezone'];
    $cff_orig_timezone = date_default_timezone_get();
    date_default_timezone_set($cff_timezone);
    //Link to Facebook
    $cff_link_size = $atts[ 'linksize' ];
    $cff_link_weight = $atts[ 'linkweight' ];
    $cff_link_color = $atts[ 'linkcolor' ];
    $cff_link_styles = 'style="';
    if ( !empty($cff_link_size) && $cff_link_size != 'inherit' ) $cff_link_styles .=  'font-size:' . $cff_link_size . 'px; ';
    if ( !empty($cff_link_weight) && $cff_link_weight != 'inherit' ) $cff_link_styles .= 'font-weight:' . $cff_link_weight . '; ';
    if ( !empty($cff_link_color) ) $cff_link_styles .= 'color:#' . str_replace('#', '', $cff_link_color) . ';';
    $cff_link_styles .= '"';
    $cff_facebook_link_text = $atts[ 'facebooklinktext' ];
    $cff_view_link_text = $atts[ 'viewlinktext' ];
    $cff_link_to_timeline = $atts[ 'linktotimeline' ];
    /********** MISC **********/
    //Like Box styles
    $cff_likebox_bg_color = $atts[ 'likeboxcolor' ];

    $cff_like_box_text_color = $atts[ 'likeboxtextcolor' ];
    $cff_like_box_colorscheme = 'light';
    if ($cff_like_box_text_color == 'white') $cff_like_box_colorscheme = 'dark';

    $cff_likebox_width = $atts[ 'likeboxwidth' ];
    $cff_likebox_height = $atts[ 'likeboxheight' ];
    $cff_likebox_height = preg_replace('/px$/', '', $cff_likebox_height);

    if ( !isset($cff_likebox_width) || empty($cff_likebox_width) || $cff_likebox_width == '' ) $cff_likebox_width = '100%';
    $cff_like_box_faces = $atts[ 'likeboxfaces' ];
    if ( !isset($cff_like_box_faces) || empty($cff_like_box_faces) ) $cff_like_box_faces = 'false';
    $cff_like_box_border = $atts[ 'likeboxborder' ];
    if ($cff_like_box_border) {
        $cff_like_box_border = 'true';
    } else {
        $cff_like_box_border = 'false';
    }

    //Compile Like box styles
    $cff_likebox_styles = 'style="width: ' . $cff_likebox_width . ';';
    if ( !empty($cff_likebox_bg_color) ) $cff_likebox_styles .= ' background-color: #' . str_replace('#', '', $cff_likebox_bg_color) . ';';

    //Set the left margin on the like box based on how it's being displayed
    if ( (!empty($cff_likebox_bg_color) && $cff_likebox_bg_color != '#') || ($cff_like_box_faces == 'true' || $cff_like_box_faces == 'on') ) $cff_likebox_styles .= ' margin-left: 0px;';  

    $cff_likebox_styles .= '"';

    //Get feed header settings
    $cff_header_bg_color = $atts['headerbg'];
    $cff_header_padding = $atts['headerpadding'];
    $cff_header_text_size = $atts['headertextsize'];
    $cff_header_text_weight = $atts['headertextweight'];
    $cff_header_text_color = $atts['headertextcolor'];

    //Compile feed header styles
    $cff_header_styles = 'style="';
    if ( !empty($cff_header_bg_color) ) $cff_header_styles .= 'background-color: #' . str_replace('#', '', $cff_header_bg_color) . ';';
    if ( !empty($cff_header_padding) ) $cff_header_styles .= ' padding: ' . $cff_header_padding . ';';
    if ( !empty($cff_header_text_size) ) $cff_header_styles .= ' font-size: ' . $cff_header_text_size . 'px;';
    if ( !empty($cff_header_text_weight) ) $cff_header_styles .= ' font-weight: ' . $cff_header_text_weight . ';';
    if ( !empty($cff_header_text_color) ) $cff_header_styles .= ' color: #' . str_replace('#', '', $cff_header_text_color) . ';';
    $cff_header_styles .= '"';

    //Video
    //Dimensions
    $cff_video_width = 640;
    $cff_video_height = $atts[ 'videoheight' ];
    
    //Action
    $cff_video_action = $atts[ 'videoaction' ];
    //Separating Line
    $cff_sep_color = $atts[ 'sepcolor' ];
    if (empty($cff_sep_color)) $cff_sep_color = 'ddd';
    $cff_sep_size = $atts[ 'sepsize' ];
    //If empty then set a 0px border
    if ( empty($cff_sep_size) || $cff_sep_size == '' ) {
        $cff_sep_size = 0;
        //Need to set a color otherwise the CSS is invalid
        $cff_sep_color = 'fff';
    }
    //CFF item styles
    $cff_item_styles = 'style="';
    $cff_item_styles .= 'border-bottom: ' . $cff_sep_size . 'px solid #' . str_replace('#', '', $cff_sep_color) . '; ';
    $cff_item_styles .= '"';
   
    //Text limits
    $title_limit = $atts['textlength'];
    if (!isset($title_limit)) $title_limit = 9999;
    $body_limit = $atts['desclength'];
    //Assign the Access Token and Page ID variables
    $access_token = $atts['accesstoken'];
    $page_id = trim( $atts['id'] );

    //If user pastes their full URL into the Page ID field then strip it out
    $cff_facebook_string = 'facebook.com';
    $cff_page_id_url_check = stripos($page_id, $cff_facebook_string);

    if ( $cff_page_id_url_check ) {
        //Remove trailing slash if exists
        $page_id = preg_replace('{/$}', '', $page_id);
        //Get last part of url
        $page_id = substr( $page_id, strrpos( $page_id, '/' )+1 );
    }

    //If the Page ID contains a query string at the end then remove it
    if ( stripos( $page_id, '?') !== false ) $page_id = substr($page_id, 0, strrpos($page_id, '?'));

    //Get show posts attribute. If not set then default to 25
    $show_posts = $atts['num'];
    if (empty($show_posts)) $show_posts = 25;
    if ( $show_posts == 0 || $show_posts == 'undefined' ) $show_posts = 25;
    //If there's no Access Token then use the default
    if ($access_token == '') $access_token = '1436737606570258|MGh1BX4_b_D9HzJtKe702cwMRPI';
    //Check whether a Page ID has been defined
    if ($page_id == '') {
        echo "Please enter the Page ID of the Facebook feed you'd like to display. You can do this in either the Custom Facebook Feed plugin settings or in the shortcode itself. For example, [custom-facebook-feed id=YOUR_PAGE_ID_HERE].<br /><br />";
        return false;
    }


    //Is it SSL?
    $cff_ssl = '';
    if (is_ssl()) $cff_ssl = '&return_ssl_resources=true';

    //Use posts? or feed?
    $old_others_option = get_option('cff_show_others'); //Use this to help depreciate the old option
    $show_others = $atts['others'];
    $show_posts_by = $atts['showpostsby'];
    $graph_query = 'posts';
    $cff_show_only_others = false;

    //If 'others' shortcode option is used then it overrides any other option
    if ($show_others || $old_others_option == 'on') {
        //Show posts by everyone
        if ( $old_others_option == 'on' || $show_others == 'on' || $show_others == 'true' || $show_others == true || $cff_is_group ) $graph_query = 'feed';

        //Only show posts by me
        if ( $show_others == 'false' ) $graph_query = 'posts';

    } else {
    //Else use the settings page option or the 'showpostsby' shortcode option

        //Only show posts by me
        if ( $show_posts_by == 'me' ) $graph_query = 'posts';

        //Show posts by everyone
        if ( $show_posts_by == 'others' || $cff_is_group ) $graph_query = 'feed';

        //Show posts ONLY by others
        if ( $show_posts_by == 'onlyothers' && !$cff_is_group ) {
            $graph_query = 'feed';
            $cff_show_only_others = true;
        }

    }


    //If the limit isn't set then set it to be 5 more than the number of posts defined
    if ( isset($atts['limit']) && $atts['limit'] !== '' ) {
        $cff_post_limit = $atts['limit'];
    } else {
        $cff_post_limit = intval(intval($show_posts) + 7);
    }


    //Calculate the cache time in seconds
    if($cff_cache_time_unit == 'minutes') $cff_cache_time_unit = 60;
    if($cff_cache_time_unit == 'hours') $cff_cache_time_unit = 60*60;
    if($cff_cache_time_unit == 'days') $cff_cache_time_unit = 60*60*24;
    $cache_seconds = $cff_cache_time * $cff_cache_time_unit;

    //Get like box vars
    $cff_likebox_width = $atts[ 'likeboxwidth' ];
    if ( !isset($cff_likebox_width) || empty($cff_likebox_width) || $cff_likebox_width == '' ) $cff_likebox_width = 300;
    $cff_like_box_faces = $atts[ 'likeboxfaces' ];
    if ( !isset($cff_like_box_faces) || empty($cff_like_box_faces) ) $cff_like_box_faces = 'false';

    //Set like box variable
    $like_box = '<div class="cff-likebox';
    if ($cff_like_box_outside) $like_box .= ' cff-outside';
    $like_box .= ($cff_like_box_position == 'top') ? ' top' : ' bottom';
    $like_box .= '" ' . $cff_likebox_styles . '><script src="https://connect.facebook.net/' . $cff_locale . '/all.js#xfbml=1"></script><fb:like-box href="http://www.facebook.com/' . $page_id . '" show_faces="'.$cff_like_box_faces.'" stream="false" header="false" colorscheme="'. $cff_like_box_colorscheme .'" show_border="'. $cff_like_box_border .'" data-height="'.$cff_likebox_height.'"></fb:like-box></div>';
    //Don't show like box if it's a group
    if($cff_is_group) $like_box = '';


    //Feed header
    $cff_show_header = $atts['showheader'];
    ($cff_show_header == 'true' || $cff_show_header == 'on') ? $cff_show_header = true : $cff_show_header = false;

    $cff_header_outside = $atts['headeroutside'];
    ($cff_header_outside == 'true' || $cff_header_outside == 'on') ? $cff_header_outside = true : $cff_header_outside = false;

    $cff_header_text = $atts['headertext'];
    $cff_header_icon = $atts['headericon'];
    $cff_header_icon_color = $atts['headericoncolor'];
    $cff_header_icon_size = $atts['headericonsize'];

    $cff_header = '<h3 class="cff-header';
    if ($cff_header_outside) $cff_header .= ' cff-outside';
    $cff_header .= '"' . $cff_header_styles . '>';
    $cff_header .= '<i class="fa fa-' . $cff_header_icon . '"';
    if(!empty($cff_header_icon_color) || !empty($cff_header_icon_size)) $cff_header .= ' style="';
    if(!empty($cff_header_icon_color)) $cff_header .= 'color: #' . str_replace('#', '', $cff_header_icon_color) . ';';
    if(!empty($cff_header_icon_size)) $cff_header .= ' font-size: ' . $cff_header_icon_size . 'px;';
    if(!empty($cff_header_icon_color) || !empty($cff_header_icon_size))$cff_header .= '"';
    $cff_header .= '></i>';
    $cff_header .= '<span class="header-text" style="height: '.$cff_header_icon_size.'px;">' . $cff_header_text . '</span>';
    $cff_header .= '</h3>';


    //***START FEED***
    $cff_content = '';

    //Add the page header to the outside of the top of feed
    if ($cff_show_header && $cff_header_outside) $cff_content .= $cff_header;

    //Add like box to the outside of the top of feed
    if ($cff_like_box_position == 'top' && $cff_show_like_box && $cff_like_box_outside) $cff_content .= $like_box;

    //Create CFF container HTML
    $cff_content .= '<div class="cff-wrapper">';
    $cff_content .= '<div id="cff" rel="'.$title_limit.'" class="';
    if( !empty($cff_class) ) $cff_content .= $cff_class . ' ';
    if ( !empty($cff_feed_height) ) $cff_content .= 'cff-fixed-height ';
    $cff_content .= '" ' . $cff_feed_styles . '>';

    //Add the page header to the inside of the top of feed
    if ($cff_show_header && !$cff_header_outside) $cff_content .= $cff_header;

    //Add like box to the inside of the top of feed
    if ($cff_like_box_position == 'top' && $cff_show_like_box && !$cff_like_box_outside) $cff_content .= $like_box;
    //Limit var
    $i = 0;

    //Define array for post items
    $cff_posts_array = array();
    
    //ALL POSTS
    if (!$cff_events_only){

        $cff_posts_json_url = 'https://graph.facebook.com/' . $page_id . '/' . $graph_query . '?access_token=' . $access_token . '&limit=' . $cff_post_limit . '&locale=' . $cff_locale . $cff_ssl;

        //Don't use caching if the cache time is set to zero
        if ($cff_cache_time != 0){
            // Get any existing copy of our transient data
            $transient_name = 'cff_'. $graph_query .'_json_' . $page_id;
            if ( false === ( $posts_json = get_transient( $transient_name ) ) || $posts_json === null ) {
                //Get the contents of the Facebook page
                $posts_json = cff_fetchUrl($cff_posts_json_url);
                //Cache the JSON
                set_transient( $transient_name, $posts_json, $cache_seconds );
            } else {
                $posts_json = get_transient( $transient_name );
                //If we can't find the transient then fall back to just getting the json from the api
                if ($posts_json == false) $posts_json = cff_fetchUrl($cff_posts_json_url);
            }
        } else {
            $posts_json = cff_fetchUrl($cff_posts_json_url);
        }


        
        //Interpret data with JSON
        $FBdata = json_decode($posts_json);
        //***STARTS POSTS LOOP***
        foreach ($FBdata->data as $news )
        {
            //Explode News and Page ID's into 2 values
            $PostID = explode("_", $news->id);
            //Check the post type
            $cff_post_type = $news->type;
            if ($cff_post_type == 'link') {
                isset($news->story) ? $story = $news->story : $story = '';
                //Check whether it's an event
                $event_link_check = "facebook.com/events/";
                $event_link_check = stripos($news->link, $event_link_check);
                if ( $event_link_check ) $cff_post_type = 'event';
            }
            //Should we show this post or not?
            $cff_show_post = false;
            switch ($cff_post_type) {
                case 'link':
                    if ( $cff_show_links_type ) $cff_show_post = true;
                    break;
                case 'event':
                    if ( $cff_show_event_type ) $cff_show_post = true;
                    break;
                case 'video':
                     if ( $cff_show_video_type ) $cff_show_post = true;
                    break;
                case 'swf':
                     if ( $cff_show_video_type ) $cff_show_post = true;
                    break;
                case 'photo':
                     if ( $cff_show_photos_type ) $cff_show_post = true;
                    break;
                case 'offer':
                     $cff_show_post = true;
                    break;
                case 'status':
                    //Check whether it's a status (author comment or like)
                    if ( $cff_show_status_type && !empty($news->message) ) $cff_show_post = true;
                    break;
            }


            //ONLY show posts by others
            if ( $cff_show_only_others ) {
                //Get the numeric ID of the page
                $page_object = cff_fetchUrl('https://graph.facebook.com/' . $page_id);
                $page_object = json_decode($page_object);
                $numeric_page_id = $page_object->id;

                //If the post author's ID is the same as the page ID then don't show the post
                if ( $numeric_page_id == $news->from->id ) $cff_show_post = false;
            }


            //Is it a duplicate post?
            if (!isset($prev_post_message)) $prev_post_message = '';
            if (!isset($prev_post_link)) $prev_post_link = '';
            if (!isset($prev_post_description)) $prev_post_description = '';
            isset($news->message) ? $pm = $news->message : $pm = '';
            isset($news->link) ? $pl = $news->link : $pl = '';
            isset($news->description) ? $pd = $news->description : $pd = '';

            if ( ($prev_post_message == $pm) && ($prev_post_link == $pl) && ($prev_post_description == $pd) ) $cff_show_post = false;

            //Check post type and display post if selected
            if ( $cff_show_post ) {
                //If it isn't then create the post
                //Only create posts for the amount of posts specified
                if ( $i == $show_posts ) break;
                $i++;
                //********************************//
                //***COMPILE SECTION VARIABLES***//
                //********************************//
                //Set the post link
                isset($news->link) ? $link = htmlspecialchars($news->link) : $link = '';
                //Is it a shared album?
                $shared_album_string = 'shared an album:';
                isset($news->story) ? $story = $news->story : $story = '';
                $shared_album = stripos($story, $shared_album_string);
                if ( $shared_album ) {
                    $link = str_replace('photo.php?','media/set/?',$link);
                }

                //Is it an album?
                $cff_album = false;
                $album_string = 'relevant_count=';
                $relevant_count = stripos($link, $album_string);

                if ( $relevant_count ) {
                    //If relevant_count is larger than 1 then there are multiple photos
                    $relevant_count = explode('relevant_count=', $link);
                    $num_photos = intval($relevant_count[1]);
                    if ( $num_photos > 1 ) {
                        $cff_album = true;
                    
                        //Link to the album instead of the photo
                        $album_link = str_replace('photo.php?','media/set/?',$link);
                        $link = "https://www.facebook.com/" . $page_id . "/posts/" . $PostID[1];
                    }
                }

                //If there's no link provided then link to either the Facebook page or the individual status
                if (empty($news->link)) {
                    if ($cff_link_to_timeline == true){
                        //Link to page
                        $link = 'http://facebook.com/' . $page_id;
                    } else {
                        //Link to status
                        $link = "https://www.facebook.com/" . $page_id . "/posts/" . $PostID[1];
                    }
                }

                //POST AUTHOR
                $cff_author = '<div class="cff-author"><a href="https://facebook.com/' . $news->from->id . '" '.$target.' title="'.$news->from->name.' on Facebook" '.$cff_author_styles.'>';

                //Set the author image as a variable. If it already exists then don't query the api for it again.
                $cff_author_img_var = '$cff_author_img_' . $news->from->id;
                if ( !isset($$cff_author_img_var) ) $$cff_author_img_var = 'https://graph.facebook.com/' . $news->from->id . '/picture?type=square';
                $cff_author .= '<img src="'.$$cff_author_img_var.'" title="'.$news->from->name.'" alt="'.$news->from->name.'" width=50 height=50>';
                $cff_author .= '<span class="cff-page-name">'.$news->from->name.'</span>';
                $cff_author .= '</a></div>';

                //POST TEXT
                $cff_translate_photos_text = $atts['photostext'];
                if (!isset($cff_translate_photos_text) || empty($cff_translate_photos_text)) $cff_translate_photos_text = 'photos';
                $cff_post_text = '<' . $cff_title_format . ' class="cff-post-text" ' . $cff_title_styles . '>';
                $cff_post_text .= '<span class="cff-text">';
                if ($cff_title_link) $cff_post_text .= '<a class="cff-post-text-link" href="'.$link.'" '.$target.'>';
                //Which content should we use?
                $cff_post_text_type = '';
                //Use the story
                if (!empty($news->story)) {
                    $post_text = htmlspecialchars($news->story);
                    $cff_post_text_type = 'story';
                }
                //Use the message
                if (!empty($news->message)) {
                    $post_text = htmlspecialchars($news->message);
                    $cff_post_text_type = 'message';
                }
                //Use the name
                if (!empty($news->name) && empty($news->story) && empty($news->message)) {
                    $post_text = htmlspecialchars($news->name);
                    $cff_post_text_type = 'name';
                }
                if ($cff_album) {
                    if (!empty($news->name)) {
                        $post_text = htmlspecialchars($news->name);
                        $cff_post_text_type = 'name';
                    }
                    if (!empty($news->message) && empty($news->name)) {
                        $post_text = htmlspecialchars($news->message);
                        $cff_post_text_type = 'message';
                    }
                    $post_text .= ' (' . $num_photos . ' '.$cff_translate_photos_text.')';
                }


                //MESSAGE TAGS
                $cff_post_tags = $atts[ 'posttags' ];
                //If the post tags option doesn't exist yet (ie. on plugin update) then set them as true
                if ( !array_key_exists( 'cff_post_tags', $options ) ) $cff_post_tags = true;
                //Add message and story tags if there are any and the post text is the message or the story
                if( $cff_post_tags && ( isset($news->message_tags) || isset($news->story_tags) ) && ($cff_post_text_type == 'message' || $cff_post_text_type == 'story')  && !$cff_title_link){
                    //Use message_tags or story_tags?
                    ( isset($news->message_tags) )? $text_tags = $news->message_tags : $text_tags = $news->story_tags;

                    //If message tags and message is being used as the post text, or same with story. This stops story tags being used to replace the message inadvertently.
                    if( ( $cff_post_text_type == 'message' && isset($news->message_tags) ) || ( $cff_post_text_type == 'story' && !isset($news->message_tags) ) ) {

                        //Does the Post Text contain any html tags? - the & symbol is the best indicator of this
                        $cff_html_check_array = array('&lt;', '’', '“', '&quot;', '&amp;');

                        //always use the text replace method
                        if( cff_stripos_arr($post_text, $cff_html_check_array) !== false ) {
                            //Loop through the tags
                            foreach($text_tags as $message_tag ) {
                                $tag_name = $message_tag[0]->name;
                                $tag_link = '<a href="http://facebook.com/' . $message_tag[0]->id . '" style="color: #'.str_replace('#', '', $atts['textlinkcolor']).';" target="_blank">' . $message_tag[0]->name . '</a>';

                                $post_text = str_replace($tag_name, $tag_link, $post_text);
                            }

                        } else {
                        //If it doesn't contain HTMl tags then use the offset to replace message tags
                            $message_tags_arr = array();

                            $i = 0;
                            foreach($text_tags as $message_tag ) {
                                $i++;
                                $message_tags_arr = cff_array_push_assoc(
                                    $message_tags_arr,
                                    $i,
                                    array(
                                        'id' => $message_tag[0]->id,
                                        'name' => $message_tag[0]->name,
                                        'type' => $message_tag[0]->type,
                                        'offset' => $message_tag[0]->offset,
                                        'length' => $message_tag[0]->length
                                    )
                                );
                            }

                            for($i = count($message_tags_arr); $i >= 1; $i--) {
                               
                                $b = '<a href="http://facebook.com/' . $message_tags_arr[$i]['id'] . '" style="color: #'.str_replace('#', '', $atts['textlinkcolor']).';" target="_blank">' . $message_tags_arr[$i]['name'] . '</a>';
                                $c = $message_tags_arr[$i]['offset'];
                                $d = $message_tags_arr[$i]['length'];

                                $post_text = cff_mb_substr_replace( $post_text, $b, $c, $d);

                            }   

                        } // end if/else

                    } // end message check

                } //END MESSAGE TAGS

                //Replace line breaks in text (needed for IE8)
                $post_text = preg_replace("/\r\n|\r|\n/",'<br/>', $post_text);

                //If the text is wrapped in a link then don't hyperlink any text within
                if ($cff_title_link) {
                    //Wrap links in a span so we can break the text if it's too long
                    $cff_post_text .= cff_wrap_span( $post_text ) . ' ';
                } else {
                    //Don't use htmlspecialchars for post_text as it's added above so that it doesn't mess up the message_tag offsets
                    $cff_post_text .= cff_autolink( $post_text, $link_color=str_replace('#', '', $atts['textlinkcolor']) ) . ' ';
                }
                
                if ($cff_title_link) $cff_post_text .= '</a>';
                $cff_post_text .= '</span>';
                //'See More' link
                $cff_post_text .= '<span class="cff-expand">... <a href="#" style="color: #'.str_replace('#', '', $atts['textlinkcolor']).'"><span class="cff-more">' . $cff_see_more_text . '</span><span class="cff-less">' . $cff_see_less_text . '</span></a></span>';
                $cff_post_text .= '</' . $cff_title_format . '>';

                //DESCRIPTION
                $cff_description = '';
                //Use the description if it's available and the post type isn't set to offer (offer description isn't useful)
                if ( ( !empty($news->description)  || !empty($news->caption) ) && $cff_post_type != 'offer') {

                    $description_text = '';
                    if ( !empty($news->description) ) {
                        $description_text = $news->description;
                    } else {
                        $description_text = $news->caption;
                    }

                    if (!empty($body_limit)) {
                        if (strlen($description_text) > $body_limit) $description_text = substr($description_text, 0, $body_limit) . '...';
                    }
                    $cff_description .= '<p class="cff-post-desc" '.$cff_body_styles.'><span>' . cff_autolink( htmlspecialchars($description_text) )  . '</span></p>';

                    //If the post text and description/caption are the same then don't show the description
                    if($post_text == $description_text) $cff_description = '';

                }

                //LINK
                $cff_shared_link = '';
                //Display shared link
                if ($cff_post_type == 'link') {
                    $cff_shared_link .= '<div class="cff-shared-link';
                    if($cff_disable_link_box) $cff_shared_link .= ' cff-no-styles"';
                    if(!$cff_disable_link_box) $cff_shared_link .= '" ' . $cff_link_box_styles;
                    $cff_shared_link .= '>';

                    //Display link name and description
                    if (!empty($news->description)) {
                        $cff_shared_link .= '<div class="cff-text-link ';
                        $cff_shared_link .= 'cff-no-image';
                        //The link title:
                        $cff_shared_link .= '"><'.$cff_link_title_format.' class="cff-link-title" '.$cff_link_title_styles.'><a href="'.$link.'" '.$target.' style="color:#' . str_replace('#', '', $cff_link_title_color) . ';">'. $news->name . '</a></'.$cff_link_title_format.'>';
                        //The link source:
                        if(!empty($news->caption)) $cff_shared_link .= '<p class="cff-link-caption" style="color:#' . str_replace('#', '', $cff_link_url_color) . ';">'.$news->caption.'</p>';
                        if ($cff_show_desc) {
                            $cff_shared_link .= $cff_description;
                        }
                        $cff_shared_link .= '</div>';
                    }

                    $cff_shared_link .= '</div>';
                }

                //DATE
                $cff_date_formatting = $atts[ 'dateformat' ];
                $cff_date_custom = $atts[ 'datecustom' ];

                $post_time = $news->created_time;
                $cff_date = '<p class="cff-date" '.$cff_date_styles.'>'. $cff_date_before . ' ' . cff_getdate(strtotime($post_time), $cff_date_formatting, $cff_date_custom) . ' ' . $cff_date_after . '</p>';
                //EVENT
                $cff_event = '';
                if ($cff_show_event_title || $cff_show_event_details) {
                    //Check for media
                    if ($cff_post_type == 'event') {
                        
                        //Get the event id from the event URL. eg: http://www.facebook.com/events/123451234512345/
                        $event_url = parse_url($link);
                        $url_parts = explode('/', $event_url['path']);
                        //Get the id from the parts
                        $eventID = $url_parts[count($url_parts)-2];
                        
                        //Get the contents of the event using the WP HTTP API
                        $event_json_url = 'https://graph.facebook.com/'.$eventID.'?access_token=' . $access_token . $cff_ssl;

                        //Don't use caching if the cache time is set to zero
                        if ($cff_cache_time != 0){
                            // Get any existing copy of our transient data
                            $transient_name = 'cff_timeline_event_json_' . $eventID;
                            if ( false === ( $event_json = get_transient( $transient_name ) ) || $event_json === null ) {
                                //Get the contents of the Facebook page
                                $event_json = cff_fetchUrl($event_json_url);
                                //Cache the JSON
                                set_transient( $transient_name, $event_json, $cache_seconds );
                            } else {
                                $event_json = get_transient( $transient_name );
                                //If we can't find the transient then fall back to just getting the json from the api
                                if ($event_json == false) $event_json = cff_fetchUrl($event_json_url);
                            }
                        } else {
                            $event_json = cff_fetchUrl($event_json_url);
                        }

                        //Interpret data with JSON
                        $event_object = json_decode($event_json);
                        //Event date
                        isset( $event_object->start_time ) ? $event_time = $event_object->start_time : $event_time = '';
                        //If timezone migration is enabled then remove last 5 characters
                        if ( strlen($event_time) == 24 ) $event_time = substr($event_time, 0, -5);
                        $cff_event_date = '';
                        if (!empty($event_time)) $cff_event_date = '<p class="cff-date" '.$cff_event_date_styles.'>' . cff_eventdate(strtotime($event_time), $cff_event_date_formatting, $cff_event_date_custom) . '</p>';
                        //EVENT
                        //Display the event details
                        $cff_event .= '<div class="cff-details">';
                        //show event date above title
                        if ($cff_event_date_position == 'above') $cff_event .= $cff_event_date;
                        //Show event title
                        if ($cff_show_event_title && !empty($event_object->name)) {
                            if ($cff_event_title_link) $cff_event .= '<a href="'.$link.'">';
                            $cff_event .= '<' . $cff_event_title_format . ' ' . $cff_event_title_styles . '>' . $event_object->name . '</' . $cff_event_title_format . '>';
                            if ($cff_event_title_link) $cff_event .= '</a>';
                        }
                        //show event date below title
                        if ($cff_event_date_position !== 'above') $cff_event .= $cff_event_date;
                        //Show event details
                        if ($cff_show_event_details){
                            //Location
                            if (!empty($event_object->location)) $cff_event .= '<p class="cff-where" ' . $cff_event_details_styles . '>' . $event_object->location . '</p>';
                            //Description
                            if (!empty($event_object->description)){
                                $description = $event_object->description;
                                if (!empty($body_limit)) {
                                    if (strlen($description) > $body_limit) $description = substr($description, 0, $body_limit) . '...';
                                }
                                $cff_event .= '<p class="cff-info" ' . $cff_event_details_styles . '>' . cff_autolink($description, $link_color=str_replace('#', '', $cff_event_link_color) ) . '</p>';
                            }
                        }
                        $cff_event .= '</div>';
                        
                    }
                }

                /* VIDEO */

                //Check to see whether it's an embedded video so that we can show the name above the post text if necessary
                $cff_is_video_embed = false;
                if ($news->type == 'video'){
                    $url = $news->source;
                    //Embeddable video strings
                    $youtube = 'youtube';
                    $youtu = 'youtu';
                    $vimeo = 'vimeo';
                    $youtubeembed = 'youtube.com/embed';
                    //Check whether it's a youtube video
                    $youtube = stripos($url, $youtube);
                    $youtu = stripos($url, $youtu);
                    $youtubeembed = stripos($url, $youtubeembed);
                    //Check whether it's a youtube video
                    if($youtube || $youtu || $youtubeembed || (stripos($url, $vimeo) !== false)) {
                        $cff_is_video_embed = true;
                    }
                }


                $cff_media = '';
                if ($news->type == 'video') {
                    //Add the name to the description if it's a video embed
                    if($cff_is_video_embed) {
                        isset($news->name) ? $video_name = $news->name : $video_name = $link;
                        isset($news->description) ? $description_text = $news->description : $description_text = '';
                        //Add the 'cff-shared-link' class so that embedded videos display in a box
                        $cff_description = '<div class="cff-desc-wrap cff-shared-link ';
                        if (empty($picture)) $cff_description .= 'cff-no-image';
                        if($cff_disable_link_box) $cff_description .= ' cff-no-styles"';
                        if(!$cff_disable_link_box) $cff_description .= '" ' . $cff_link_box_styles;
                        $cff_description .= '>';

                        if( isset($news->name) ) $cff_description .= '<'.$cff_link_title_format.' class="cff-link-title" '.$cff_link_title_styles.'><a href="'.$link.'" '.$target.' style="color:#' . str_replace('#', '', $cff_link_title_color) . ';">'. $news->name . '</a></'.$cff_link_title_format.'>';

                        if (!empty($body_limit)) {
                            if (strlen($description_text) > $body_limit) $description_text = substr($description_text, 0, $body_limit) . '...';
                        }

                        $cff_description .= '<p class="cff-post-desc" '.$cff_body_styles.'><span>' . cff_autolink( htmlspecialchars($description_text) ) . '</span></p></div>';
                    } else {
                        isset($news->name) ? $video_name = $news->name : $video_name = $link;
                        if( isset($news->name) ) $cff_description .= '<'.$cff_link_title_format.' class="cff-link-title" '.$cff_link_title_styles.'><a href="'.$link.'" '.$target.' style="color:#' . str_replace('#', '', $cff_link_title_color) . ';">'. $news->name . '</a></'.$cff_link_title_format.'>';
                    }
                }


                //Display the link to the Facebook post or external link
                $cff_link = '';
                //Default link
                $cff_viewpost_class = 'cff-viewpost-facebook';
                if ($cff_facebook_link_text == '') $cff_facebook_link_text = 'View on Facebook';
                $link_text = $cff_facebook_link_text;

                //Link to the Facebook post if it's a link or a video
                if($cff_post_type == 'link' || $cff_post_type == 'video') $link = "https://www.facebook.com/" . $page_id . "/posts/" . $PostID[1];

                if ($cff_post_type == 'offer') $link_text = 'View Offer';
                $cff_link = '<a class="' . $cff_viewpost_class . '" href="' . $link . '" title="' . $link_text . '" ' . $target . ' ' . $cff_link_styles . '>' . $link_text . '</a>';


                //**************************//
                //***CREATE THE POST HTML***//
                //**************************//
                //Start the container
                $cff_post_item = '<div class="cff-item ';
                if ($cff_post_type == 'link') $cff_post_item .= 'cff-link-item';
                if ($cff_post_type == 'event') $cff_post_item .= 'cff-timeline-event';
                if ($cff_post_type == 'photo') $cff_post_item .= 'cff-photo-post';
                if ($cff_post_type == 'video') $cff_post_item .= 'cff-video-post';
                if ($cff_post_type == 'swf') $cff_post_item .= 'cff-swf-post';
                if ($cff_post_type == 'status') $cff_post_item .= 'cff-status-post';
                if ($cff_post_type == 'offer') $cff_post_item .= 'cff-offer-post';
                if ($cff_album) $cff_post_item .= ' cff-album';
                $cff_post_item .=  ' author-'. cff_to_slug($news->from->name) .'" id="'. $news->id .'" ' . $cff_item_styles . '>';
                
                    //POST AUTHOR
                    if($cff_show_author) $cff_post_item .= $cff_author;
                    //DATE ABOVE
                    if ($cff_show_date && $cff_date_position == 'above') $cff_post_item .= $cff_date;
                    //POST TEXT
                    if($cff_show_text) $cff_post_item .= $cff_post_text;
                    //DESCRIPTION
                    if($cff_show_desc && $cff_post_type != 'offer' && $cff_post_type != 'link') $cff_post_item .= $cff_description;
                    //LINK
                    if($cff_show_shared_links) $cff_post_item .= $cff_shared_link;
                    //DATE BELOW
                    if ($cff_show_date && $cff_date_position == 'below') $cff_post_item .= $cff_date;
                    //EVENT
                    if($cff_show_event_title || $cff_show_event_details) $cff_post_item .= $cff_event;
                    //VIEW ON FACEBOOK LINK
                    if($cff_show_link) $cff_post_item .= $cff_link;
                
                //End the post item
                $cff_post_item .= '</div>';

                //PUSH TO ARRAY
                $cff_posts_array = cff_array_push_assoc($cff_posts_array, strtotime($post_time), $cff_post_item);

            } // End post type check

            if (isset($news->message)) $prev_post_message = $news->message;
            if (isset($news->link))  $prev_post_link = $news->link;
            if (isset($news->description))  $prev_post_description = $news->description;

        } // End the loop

        //Sort the array in reverse order (newest first)
        krsort($cff_posts_array);

    } // End ALL POSTS


    //Output the posts array
    $p = 0;
    foreach ($cff_posts_array as $post ) {
        if ( $p == $show_posts ) break;
        $cff_content .= $post;
        $p++;
    }

    //Reset the timezone
    date_default_timezone_set( $cff_orig_timezone );
    //Add the Like Box inside
    if ($cff_like_box_position == 'bottom' && $cff_show_like_box && !$cff_like_box_outside) $cff_content .= $like_box;
    //End the feed
    $cff_content .= '</div><div class="cff-clear"></div>';
    //Add the Like Box outside
    if ($cff_like_box_position == 'bottom' && $cff_show_like_box && $cff_like_box_outside) $cff_content .= $like_box;
    
    //If the feed is loaded via Ajax then put the scripts into the shortcode itself
    $ajax_theme = $atts['ajax'];
    ( $ajax_theme == 'on' || $ajax_theme == 'true' || $ajax_theme == true ) ? $ajax_theme = true : $ajax_theme = false;
    if( $atts[ 'ajax' ] == 'false' ) $ajax_theme = false;
    if ($ajax_theme) {
        $cff_link_hashtags = $atts['linkhashtags'];
        ($cff_link_hashtags == 'true' || $cff_link_hashtags == 'on') ? $cff_link_hashtags = 'true' : $cff_link_hashtags = 'false';
        if ($cff_title_link) $cff_link_hashtags = 'false';
        $cff_content .= '<script type="text/javascript">var cfflinkhashtags = "' . $cff_link_hashtags . '";</script>';
        $cff_content .= '<script type="text/javascript" src="' . plugins_url( '/js/cff-scripts.js?8' , __FILE__ ) . '"></script>';
    }

    $cff_content .= '</div>';

    //Return our feed HTML to display
    return $cff_content;
}

//***FUNCTIONS***

//Get JSON object of feed data
function cff_fetchUrl($url){
    //Can we use cURL?
    if(is_callable('curl_init')){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $feedData = curl_exec($ch);
        curl_close($ch);
    //If not then use file_get_contents
    } elseif ( ini_get('allow_url_fopen') == 1 || ini_get('allow_url_fopen') === TRUE ) {
        $feedData = @file_get_contents($url);
    //Or else use the WP HTTP API
    } else {
        if( !class_exists( 'WP_Http' ) ) include_once( ABSPATH . WPINC. '/class-http.php' );
        $request = new WP_Http;
        $result = $request->request($url);
        $feedData = $result['body'];
    }
    
    return $feedData;
}

//Make links into span instead when the post text is made clickable
function cff_wrap_span($text) {
    $pattern  = '#\b(([\w-]+://?|www[.])[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/)))#';
    return preg_replace_callback($pattern, 'cff_wrap_span_callback', $text);
}
function cff_wrap_span_callback($matches) {
    $max_url_length = 100;
    $max_depth_if_over_length = 2;
    $ellipsis = '&hellip;';
    $target = 'target="_blank"';
    $url_full = $matches[0];
    $url_short = '';
    if (strlen($url_full) > $max_url_length) {
        $parts = parse_url($url_full);
        $url_short = $parts['scheme'] . '://' . preg_replace('/^www\./', '', $parts['host']) . '/';
        $path_components = explode('/', trim($parts['path'], '/'));
        foreach ($path_components as $dir) {
            $url_string_components[] = $dir . '/';
        }
        if (!empty($parts['query'])) {
            $url_string_components[] = '?' . $parts['query'];
        }
        if (!empty($parts['fragment'])) {
            $url_string_components[] = '#' . $parts['fragment'];
        }
        for ($k = 0; $k < count($url_string_components); $k++) {
            $curr_component = $url_string_components[$k];
            if ($k >= $max_depth_if_over_length || strlen($url_short) + strlen($curr_component) > $max_url_length) {
                if ($k == 0 && strlen($url_short) < $max_url_length) {
                    // Always show a portion of first directory
                    $url_short .= substr($curr_component, 0, $max_url_length - strlen($url_short));
                }
                $url_short .= $ellipsis;
                break;
            }
            $url_short .= $curr_component;
        }
    } else {
        $url_short = $url_full;
    }
    return "<span class='cff-break-word'>$url_short</span>";
}

//2013-04-28T21:06:56+0000
//Time stamp function - used for posts
function cff_getdate($original, $date_format, $custom_date) {
    switch ($date_format) {
        
        case '2':
            $print = date_i18n('F jS, g:i a', $original);
            break;
        case '3':
            $print = date_i18n('F jS', $original);
            break;
        case '4':
            $print = date_i18n('D F jS', $original);
            break;
        case '5':
            $print = date_i18n('l F jS', $original);
            break;
        case '6':
            $print = date_i18n('D M jS, Y', $original);
            break;
        case '7':
            $print = date_i18n('l F jS, Y', $original);
            break;
        case '8':
            $print = date_i18n('l F jS, Y - g:i a', $original);
            break;
        case '9':
            $print = date_i18n("l M jS, 'y", $original);
            break;
        case '10':
            $print = date_i18n('m.d.y', $original);
            break;
        case '11':
            $print = date_i18n('m/d/y', $original);
            break;
        case '12':
            $print = date_i18n('d.m.y', $original);
            break;
        case '13':
            $print = date_i18n('d/m/y', $original);
            break;
        default:
            
            $options = get_option('cff_style_settings');

            $cff_second = isset($options['cff_translate_second']) ? $options['cff_translate_second'] : '';
            if ( empty($cff_second) ) $cff_second = 'second';

            $cff_seconds = isset($options['cff_translate_seconds']) ? $options['cff_translate_seconds'] : '';
            if ( empty($cff_seconds) ) $cff_seconds = 'seconds';

            $cff_minute = isset($options['cff_translate_minute']) ? $options['cff_translate_minute'] : '';
            if ( empty($cff_minute) ) $cff_minute = 'minute';

            $cff_minutes = isset($options['cff_translate_minutes']) ? $options['cff_translate_minutes'] : '';
            if ( empty($cff_minutes) ) $cff_minutes = 'minutes';

            $cff_hour = isset($options['cff_translate_hour']) ? $options['cff_translate_hour'] : '';
            if ( empty($cff_hour) ) $cff_hour = 'hour';

            $cff_hours = isset($options['cff_translate_hours']) ? $options['cff_translate_hours'] : '';
            if ( empty($cff_hours) ) $cff_hours = 'hours';

            $cff_day = isset($options['cff_translate_day']) ? $options['cff_translate_day'] : '';
            if ( empty($cff_day) ) $cff_day = 'day';

            $cff_days = isset($options['cff_translate_days']) ? $options['cff_translate_days'] : '';
            if ( empty($cff_days) ) $cff_days = 'days';

            $cff_week = isset($options['cff_translate_week']) ? $options['cff_translate_week'] : '';
            if ( empty($cff_week) ) $cff_week = 'week';

            $cff_weeks = isset($options['cff_translate_weeks']) ? $options['cff_translate_weeks'] : '';
            if ( empty($cff_weeks) ) $cff_weeks = 'weeks';

            $cff_month = isset($options['cff_translate_month']) ? $options['cff_translate_month'] : '';
            if ( empty($cff_month) ) $cff_month = 'month';

            $cff_months = isset($options['cff_translate_months']) ? $options['cff_translate_months'] : '';
            if ( empty($cff_months) ) $cff_months = 'months';

            $cff_year = isset($options['cff_translate_year']) ? $options['cff_translate_year'] : '';
            if ( empty($cff_year) ) $cff_year = 'year';

            $cff_years = isset($options['cff_translate_years']) ? $options['cff_translate_years'] : '';
            if ( empty($cff_years) ) $cff_years = 'years';

            $cff_ago = isset($options['cff_translate_ago']) ? $options['cff_translate_ago'] : '';
            if ( empty($cff_ago) ) $cff_ago = 'ago';

            
            $periods = array($cff_second, $cff_minute, $cff_hour, $cff_day, $cff_week, $cff_month, $cff_year, "decade");
            $periods_plural = array($cff_seconds, $cff_minutes, $cff_hours, $cff_days, $cff_weeks, $cff_months, $cff_years, "decade");

            $lengths = array("60","60","24","7","4.35","12","10");
            $now = time();
            
            // is it future date or past date
            if($now > $original) {    
                $difference = $now - $original;
                $tense = $cff_ago;
            } else {
                $difference = $original - $now;
                $tense = $cff_ago;
            }
            for($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++) {
                $difference /= $lengths[$j];
            }
            
            $difference = round($difference);
            
            if($difference != 1) {
                $periods[$j] = $periods_plural[$j];
            }
            $print = "$difference $periods[$j] {$tense}";

            break;
        
    }
    if ( !empty($custom_date) ){
        $print = date_i18n($custom_date, $original);
    }

    return $print;
}
function cff_eventdate($original, $date_format, $custom_date) {
    switch ($date_format) {
        
        case '2':
            $print = date_i18n('F jS, g:ia', $original);
            break;
        case '3':
            $print = date_i18n('g:ia - F jS', $original);
            break;
        case '4':
            $print = date_i18n('g:ia, F jS', $original);
            break;
        case '5':
            $print = date_i18n('l F jS - g:ia', $original);
            break;
        case '6':
            $print = date_i18n('D M jS, Y, g:iA', $original);
            break;
        case '7':
            $print = date_i18n('l F jS, Y, g:iA', $original);
            break;
        case '8':
            $print = date_i18n('l F jS, Y - g:ia', $original);
            break;
        case '9':
            $print = date_i18n("l M jS, 'y", $original);
            break;
        case '10':
            $print = date_i18n('m.d.y - g:iA', $original);
            break;
        case '11':
            $print = date_i18n('m/d/y, g:ia', $original);
            break;
        case '12':
            $print = date_i18n('d.m.y - g:iA', $original);
            break;
        case '13':
            $print = date_i18n('d/m/y, g:ia', $original);
            break;
        default:
            $print = date_i18n('F j, Y, g:ia', $original);
            break;
    }
    if ( !empty($custom_date) ){
        $print = date_i18n($custom_date, $original);
    }
    return $print;
}
//Time stamp function - used for comments
function cff_timesince($original) {
            
    $options = get_option('cff_style_settings');

    $cff_second = isset($options['cff_translate_second']) ? $options['cff_translate_second'] : '';
    if ( empty($cff_second) ) $cff_second = 'second';

    $cff_seconds = isset($options['cff_translate_seconds']) ? $options['cff_translate_seconds'] : '';
    if ( empty($cff_seconds) ) $cff_seconds = 'seconds';

    $cff_minute = isset($options['cff_translate_minute']) ? $options['cff_translate_minute'] : '';
    if ( empty($cff_minute) ) $cff_minute = 'minute';

    $cff_minutes = isset($options['cff_translate_minutes']) ? $options['cff_translate_minutes'] : '';
    if ( empty($cff_minutes) ) $cff_minutes = 'minutes';

    $cff_hour = isset($options['cff_translate_hour']) ? $options['cff_translate_hour'] : '';
    if ( empty($cff_hour) ) $cff_hour = 'hour';

    $cff_hours = isset($options['cff_translate_hours']) ? $options['cff_translate_hours'] : '';
    if ( empty($cff_hours) ) $cff_hours = 'hours';

    $cff_day = isset($options['cff_translate_day']) ? $options['cff_translate_day'] : '';
    if ( empty($cff_day) ) $cff_day = 'day';

    $cff_days = isset($options['cff_translate_days']) ? $options['cff_translate_days'] : '';
    if ( empty($cff_days) ) $cff_days = 'days';

    $cff_week = isset($options['cff_translate_week']) ? $options['cff_translate_week'] : '';
    if ( empty($cff_week) ) $cff_week = 'week';

    $cff_weeks = isset($options['cff_translate_weeks']) ? $options['cff_translate_weeks'] : '';
    if ( empty($cff_weeks) ) $cff_weeks = 'weeks';

    $cff_month = isset($options['cff_translate_month']) ? $options['cff_translate_month'] : '';
    if ( empty($cff_month) ) $cff_month = 'month';

    $cff_months = isset($options['cff_translate_months']) ? $options['cff_translate_months'] : '';
    if ( empty($cff_months) ) $cff_months = 'months';

    $cff_year = isset($options['cff_translate_year']) ? $options['cff_translate_year'] : '';
    if ( empty($cff_year) ) $cff_year = 'year';

    $cff_years = isset($options['cff_translate_years']) ? $options['cff_translate_years'] : '';
    if ( empty($cff_years) ) $cff_years = 'years';

    $cff_ago = isset($options['cff_translate_ago']) ? $options['cff_translate_ago'] : '';
    if ( empty($cff_ago) ) $cff_ago = 'ago';

    
    $periods = array($cff_second, $cff_minute, $cff_hour, $cff_day, $cff_week, $cff_month, $cff_year, "decade");
    $periods_plural = array($cff_seconds, $cff_minutes, $cff_hours, $cff_days, $cff_weeks, $cff_months, $cff_years, "decade");

    $lengths = array("60","60","24","7","4.35","12","10");
    $now = time();
    
    // is it future date or past date
    if($now > $original) {    
        $difference = $now - $original;
        $tense = $cff_ago;
    } else {
        $difference = $original - $now;
        $tense = $cff_ago;
    }
    for($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++) {
        $difference /= $lengths[$j];
    }
    
    $difference = round($difference);
    
    if($difference != 1) {
        $periods[$j] = $periods_plural[$j];
    }
    return "$difference $periods[$j] {$tense}";
            
}
//Use custom stripos function if it's not available (only available in PHP 5+)
if(!is_callable('stripos')){
    function stripos($haystack, $needle){
        return strpos($haystack, stristr( $haystack, $needle ));
    }
}
function cff_stripos_arr($haystack, $needle) {
    if(!is_array($needle)) $needle = array($needle);
    foreach($needle as $what) {
        if(($pos = stripos($haystack, ltrim($what) ))!==false) return $pos;
    }
    return false;
}
function cff_mb_substr_replace($string, $replacement, $start, $length=NULL) {
    if (is_array($string)) {
        $num = count($string);
        // $replacement
        $replacement = is_array($replacement) ? array_slice($replacement, 0, $num) : array_pad(array($replacement), $num, $replacement);
        // $start
        if (is_array($start)) {
            $start = array_slice($start, 0, $num);
            foreach ($start as $key => $value)
                $start[$key] = is_int($value) ? $value : 0;
        }
        else {
            $start = array_pad(array($start), $num, $start);
        }
        // $length
        if (!isset($length)) {
            $length = array_fill(0, $num, 0);
        }
        elseif (is_array($length)) {
            $length = array_slice($length, 0, $num);
            foreach ($length as $key => $value)
                $length[$key] = isset($value) ? (is_int($value) ? $value : $num) : 0;
        }
        else {
            $length = array_pad(array($length), $num, $length);
        }
        // Recursive call
        return array_map(__FUNCTION__, $string, $replacement, $start, $length);
    }
    preg_match_all('/./us', (string)$string, $smatches);
    preg_match_all('/./us', (string)$replacement, $rmatches);
    if ($length === NULL) $length = mb_strlen($string);
    array_splice($smatches[0], $start, $length, $rmatches[0]);
    return join($smatches[0]);
}

//Push to assoc array
function cff_array_push_assoc($array, $key, $value){
    $array[$key] = $value;
    return $array;
}
//Convert string to slug
function cff_to_slug($string){
    return strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $string)));
}

// remove_filter( 'the_content', 'wpautop' );
// add_filter( 'the_content', 'wpautop', 99 );


//Allows shortcodes in theme
add_filter('widget_text', 'do_shortcode');

//Enqueue stylesheet
add_action( 'wp_enqueue_scripts', 'cff_add_my_stylesheet' );
function cff_add_my_stylesheet() {
    // Respects SSL, Style.css is relative to the current file
    wp_register_style( 'cff', plugins_url('css/cff-style.css?6', __FILE__) );
    wp_enqueue_style( 'cff' );
    wp_enqueue_style( 'cff-font-awesome', '//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.min.css', array(), '4.0.3' );
}
//Enqueue scripts
add_action( 'wp_enqueue_scripts', 'cff_scripts_method' );
function cff_scripts_method() {
    //Register the script to make it available
    wp_register_script( 'cffscripts', plugins_url( '/js/cff-scripts.js?6' , __FILE__ ), array('jquery'), '1.8', true );
    //Enqueue it to load it onto the page
    wp_enqueue_script('cffscripts');
}

function cff_activate() {
    $options = get_option('cff_style_settings');
    $options[ 'cff_show_links_type' ] = true;
    $options[ 'cff_show_event_type' ] = true;
    $options[ 'cff_show_video_type' ] = true;
    $options[ 'cff_show_photos_type' ] = true;
    $options[ 'cff_show_status_type' ] = true;
    $options[ 'cff_show_author' ] = true;
    $options[ 'cff_show_text' ] = true;
    $options[ 'cff_show_desc' ] = true;
    $options[ 'cff_show_shared_links' ] = true;
    $options[ 'cff_show_date' ] = true;
    $options[ 'cff_show_media' ] = true;
    $options[ 'cff_show_event_title' ] = true;
    $options[ 'cff_show_event_details' ] = true;
    $options[ 'cff_show_meta' ] = true;
    $options[ 'cff_show_link' ] = true;
    $options[ 'cff_show_like_box' ] = true;
    update_option( 'cff_style_settings', $options );

    get_option('cff_show_access_token');
    update_option( 'cff_show_access_token', false );
}
register_activation_hook( __FILE__, 'cff_activate' );
//Uninstall
function cff_uninstall()
{
    if ( ! current_user_can( 'activate_plugins' ) )
        return;
    //Settings
    delete_option( 'cff_show_access_token' );
    delete_option( 'cff_access_token' );
    delete_option( 'cff_page_id' );
    delete_option( 'cff_num_show' );
    delete_option( 'cff_post_limit' );
    delete_option( 'cff_show_others' );
    delete_option('cff_cache_time');
    delete_option('cff_cache_time_unit');
    delete_option( 'cff_locale' );
    //Style & Layout
    delete_option( 'cff_title_length' );
    delete_option( 'cff_body_length' );
    delete_option('cff_style_settings');
}
register_uninstall_hook( __FILE__, 'cff_uninstall' );
add_action( 'wp_head', 'cff_custom_css' );
function cff_custom_css() {
    $options = get_option('cff_style_settings');
    isset($options[ 'cff_custom_css' ]) ? $cff_custom_css = $options[ 'cff_custom_css' ] : $cff_custom_css = '';

    if( !empty($cff_custom_css) ) echo "\r\n";
    if( !empty($cff_custom_css) ) echo '<!-- Custom Facebook Feed Custom CSS -->';
    if( !empty($cff_custom_css) ) echo "\r\n";
    if( !empty($cff_custom_css) ) echo '<style type="text/css">';
    if( !empty($cff_custom_css) ) echo "\r\n";
    if( !empty($cff_custom_css) ) echo stripslashes($cff_custom_css);
    if( !empty($cff_custom_css) ) echo "\r\n";
    if( !empty($cff_custom_css) ) echo '</style>';
    if( !empty($cff_custom_css) ) echo "\r\n";
}
add_action( 'wp_footer', 'cff_js' );
function cff_js() {
    $options = get_option('cff_style_settings');
    $cff_custom_js = isset($options[ 'cff_custom_js' ]) ? $options[ 'cff_custom_js' ] : '';

    //Link hashtags?
    isset($options[ 'cff_link_hashtags' ]) ? $cff_link_hashtags = $options[ 'cff_link_hashtags' ] : $cff_link_hashtags = 'true';
    ($cff_link_hashtags == 'true' || $cff_link_hashtags == 'on') ? $cff_link_hashtags = 'true' : $cff_link_hashtags = 'false';

    //If linking the post text then don't link the hashtags
    isset($options[ 'cff_title_link' ]) ? $cff_title_link = $options[ 'cff_title_link' ] : $cff_title_link = false;
    ($cff_title_link == 'true' || $cff_title_link == 'on') ? $cff_title_link = true : $cff_title_link = false;
    if ($cff_title_link) $cff_link_hashtags = 'false';

    
    echo '<!-- Custom Facebook Feed JS -->';
    echo "\r\n";
    echo '<script type="text/javascript">';
    echo "\r\n";
    echo 'var cfflinkhashtags = "' . $cff_link_hashtags . '";';
    echo "\r\n";
    if( !empty($cff_custom_js) ) echo "jQuery( document ).ready(function($) {";
    if( !empty($cff_custom_js) ) echo "\r\n";
    if( !empty($cff_custom_js) ) echo stripslashes($cff_custom_js);
    if( !empty($cff_custom_js) ) echo "\r\n";
    if( !empty($cff_custom_js) ) echo "});";
    if( !empty($cff_custom_js) ) echo "\r\n";
    echo '</script>';
    echo "\r\n";
}



//AUTOLINK
$GLOBALS['autolink_options'] = array(

    # Should http:// be visibly stripped from the front
    # of URLs?
    'strip_protocols' => true,

);

####################################################################

function cff_autolink($text, $link_color='', $span_tag = false, $limit=100, $tagfill='class="cff-break-word"', $auto_title = true){

    $text = cff_autolink_do($text, $link_color, '![a-z][a-z-]+://!i',    $limit, $tagfill, $auto_title, $span_tag);
    $text = cff_autolink_do($text, $link_color, '!(mailto|skype):!i',    $limit, $tagfill, $auto_title, $span_tag);
    $text = cff_autolink_do($text, $link_color, '!www\\.!i',         $limit, $tagfill, $auto_title, 'http://', $span_tag);
    return $text;
}

####################################################################

function cff_autolink_do($text, $link_color, $sub, $limit, $tagfill, $auto_title, $span_tag, $force_prefix=null){

    $text_l = StrToLower($text);
    $cursor = 0;
    $loop = 1;
    $buffer = '';

    while (($cursor < strlen($text)) && $loop){

        $ok = 1;
        $matched = preg_match($sub, $text_l, $m, PREG_OFFSET_CAPTURE, $cursor);

        if (!$matched){

            $loop = 0;
            $ok = 0;

        }else{

            $pos = $m[0][1];
            $sub_len = strlen($m[0][0]);

            $pre_hit = substr($text, $cursor, $pos-$cursor);
            $hit = substr($text, $pos, $sub_len);
            $pre = substr($text, 0, $pos);
            $post = substr($text, $pos + $sub_len);

            $fail_text = $pre_hit.$hit;
            $fail_len = strlen($fail_text);

            #
            # substring found - first check to see if we're inside a link tag already...
            #

            $bits = preg_split("!</a>!i", $pre);
            $last_bit = array_pop($bits);
            if (preg_match("!<a\s!i", $last_bit)){

                #echo "fail 1 at $cursor<br />\n";

                $ok = 0;
                $cursor += $fail_len;
                $buffer .= $fail_text;
            }
        }

        #
        # looks like a nice spot to autolink from - check the pre
        # to see if there was whitespace before this match
        #

        if ($ok){

            if ($pre){
                if (!preg_match('![\s\(\[\{>]$!s', $pre)){

                    #echo "fail 2 at $cursor ($pre)<br />\n";

                    $ok = 0;
                    $cursor += $fail_len;
                    $buffer .= $fail_text;
                }
            }
        }

        #
        # we want to autolink here - find the extent of the url
        #

        if ($ok){
            if (preg_match('/^([a-z0-9\-\.\/\-_%~!?=,:;&+*#@\(\)\$]+)/i', $post, $matches)){

                $url = $hit.$matches[1];

                $cursor += strlen($url) + strlen($pre_hit);
                $buffer .= $pre_hit;

                $url = html_entity_decode($url);


                #
                # remove trailing punctuation from url
                #

                while (preg_match('|[.,!;:?]$|', $url)){
                    $url = substr($url, 0, strlen($url)-1);
                    $cursor--;
                }
                foreach (array('()', '[]', '{}') as $pair){
                    $o = substr($pair, 0, 1);
                    $c = substr($pair, 1, 1);
                    if (preg_match("!^(\\$c|^)[^\\$o]+\\$c$!", $url)){
                        $url = substr($url, 0, strlen($url)-1);
                        $cursor--;
                    }
                }


                #
                # nice-i-fy url here
                #

                $link_url = $url;
                $display_url = $url;

                if ($force_prefix) $link_url = $force_prefix.$link_url;

                if ($GLOBALS['autolink_options']['strip_protocols']){
                    if (preg_match('!^(http|https)://!i', $display_url, $m)){

                        $display_url = substr($display_url, strlen($m[1])+3);
                    }
                }

                $display_url = cff_autolink_label($display_url, $limit);


                #
                # add the url
                #
                
                if ($display_url != $link_url && !preg_match('@title=@msi',$tagfill) && $auto_title) {

                    $display_quoted = preg_quote($display_url, '!');

                    if (!preg_match("!^(http|https)://{$display_quoted}$!i", $link_url)){

                        $tagfill .= ' title="'.$link_url.'"';
                    }
                }

                $link_url_enc = HtmlSpecialChars($link_url);
                $display_url_enc = HtmlSpecialChars($display_url);

                
                if( substr( $link_url_enc, 0, 4 ) !== "http" ) $link_url_enc = 'http://' . $link_url_enc;
                $buffer .= "<a target='_blank' style='color: #".$link_color."' href=\"{$link_url_enc}\"$tagfill>{$display_url_enc}</a>";
                
            
            }else{
                #echo "fail 3 at $cursor<br />\n";

                $ok = 0;
                $cursor += $fail_len;
                $buffer .= $fail_text;
            }
        }

    }

    #
    # add everything from the cursor to the end onto the buffer.
    #

    $buffer .= substr($text, $cursor);

    return $buffer;
}

####################################################################

function cff_autolink_label($text, $limit){

    if (!$limit){ return $text; }

    if (strlen($text) > $limit){
        return substr($text, 0, $limit-3).'...';
    }

    return $text;
}

####################################################################

function cff_autolink_email($text, $tagfill=''){

    $atom = '[^()<>@,;:\\\\".\\[\\]\\x00-\\x20\\x7f]+'; # from RFC822

    #die($atom);

    $text_l = StrToLower($text);
    $cursor = 0;
    $loop = 1;
    $buffer = '';

    while(($cursor < strlen($text)) && $loop){

        #
        # find an '@' symbol
        #

        $ok = 1;
        $pos = strpos($text_l, '@', $cursor);

        if ($pos === false){

            $loop = 0;
            $ok = 0;

        }else{

            $pre = substr($text, $cursor, $pos-$cursor);
            $hit = substr($text, $pos, 1);
            $post = substr($text, $pos + 1);

            $fail_text = $pre.$hit;
            $fail_len = strlen($fail_text);

            #die("$pre::$hit::$post::$fail_text");

            #
            # substring found - first check to see if we're inside a link tag already...
            #

            $bits = preg_split("!</a>!i", $pre);
            $last_bit = array_pop($bits);
            if (preg_match("!<a\s!i", $last_bit)){

                #echo "fail 1 at $cursor<br />\n";

                $ok = 0;
                $cursor += $fail_len;
                $buffer .= $fail_text;
            }
        }

        #
        # check backwards
        #

        if ($ok){
            if (preg_match("!($atom(\.$atom)*)\$!", $pre, $matches)){

                # move matched part of address into $hit

                $len = strlen($matches[1]);
                $plen = strlen($pre);

                $hit = substr($pre, $plen-$len).$hit;
                $pre = substr($pre, 0, $plen-$len);

            }else{

                #echo "fail 2 at $cursor ($pre)<br />\n";

                $ok = 0;
                $cursor += $fail_len;
                $buffer .= $fail_text;
            }
        }

        #
        # check forwards
        #

        if ($ok){
            if (preg_match("!^($atom(\.$atom)*)!", $post, $matches)){

                # move matched part of address into $hit

                $len = strlen($matches[1]);

                $hit .= substr($post, 0, $len);
                $post = substr($post, $len);

            }else{
                #echo "fail 3 at $cursor ($post)<br />\n";

                $ok = 0;
                $cursor += $fail_len;
                $buffer .= $fail_text;
            }
        }

        #
        # commit
        #

        if ($ok) {

            $cursor += strlen($pre) + strlen($hit);
            $buffer .= $pre;
            $buffer .= "<a href=\"mailto:$hit\"$tagfill>$hit</a>";

        }

    }

    #
    # add everything from the cursor to the end onto the buffer.
    #

    $buffer .= substr($text, $cursor);

    return $buffer;
}

####################################################################


//Comment out the line below to view errors
//error_reporting(0);
?>