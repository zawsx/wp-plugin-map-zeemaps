<?php
/*
Plugin Name: ZeeMaps
Plugin URI: http://www.zeemaps.com/wordpress/
Description: Embed an interactive Google Map in your WordPress blog. Create a map in ZeeMaps (http://www.zeemaps.com), customize it with your own markers, regions, etc. Note the map number from your map's URL, use that to publish the map in your blog.
Author: ZeeMaps
Version: 1.0
Author URI: http://www.zeemaps.com
*/

function zeemaps_addbuttons() {
   // Don't bother doing this stuff if the current user lacks permissions
	if (!current_user_can('edit_posts') && !current_user_can('edit_pages'))
		return;
			
	// Add only in Rich Editor mode
	if (get_user_option('rich_editing') == 'true') {
		add_filter("mce_external_plugins", "add_zeemaps_tinymce_plugin");
		add_filter('mce_buttons_2', 'register_zeemaps_button');
	}
}
								
function register_zeemaps_button($buttons) {
	array_push($buttons, "separator", "zeemaps");
	return $buttons;
}
									   
// Load the TinyMCE plugin : zeemaps.js (wp2.5)
function add_zeemaps_tinymce_plugin($plugin_array) {
	$plugin_array['zeemaps'] = 
		get_bloginfo('wpurl') . '/wp-content/plugins/zeemaps/zeemaps.js'
	;
	return $plugin_array;
}

function zeemaps_put_iframe($matches) {
	$args = '';
	$w = '100%';
	$h = '500';
	$code = " <iframe src='http://www.zeemaps.com/pub?group=$matches[1]";
	if (count($matches) === 3) {
		$options = array("search", "legend", "geosearch", "shuttered", "nopdf", "add",
			"list"
		);
		$args = $matches[2];
		foreach ($options as $opt) {
			if (preg_match("/\b$opt/i", $args)) {
				$code .= "&$opt=1";
			}
		}
		if (preg_match("/\bw=([^[\s\]]]+)[\b\]]/i", $args, $m)) {
			$w = $m[1];
		}
		if (preg_match("/\bh=([^[\s\]]]+)[\b\]]/i", $args, $m)) {
			$h = $m[1];
		}
	}
	$code .= "' width='$w' height='$h' frameborder=0></iframe>";
	return $code;
}

function zeemaps_addiframe($text) {
	return preg_replace_callback("@(?:<p>\s*)?\[zeemaps?\s+(\d+)\s*(.*?)\](?:\s*</p>)?@",
		zeemaps_put_iframe, $text
	);
}
											  
// init process for button control
	add_action('init', 'zeemaps_addbuttons');
	add_filter('the_content', 'zeemaps_addiframe');
?>
