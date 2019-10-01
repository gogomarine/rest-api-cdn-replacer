<?php
/**
Plugin Name: rest-api-cdn-replacer
Plugin URI:  https://www.taiduplus.com/
Description:  Modify/Append posts from origin url to CDN url.
Author: Eric Tsao
Version: 1.1
License: GPLv2
*/

register_activation_hook( __FILE__, 'plugin_install');

require_once(dirname(__FILE__).'/plugins/admin-ui.php');

// do_racr_update();

function plugin_install() {
  update_option( 'racr_opt_source_url', untrailingslashit(get_site_url()));
  update_option( 'racr_opt_replace', 'true');
}

function dw_rest_prepare_post( $value, $post, $request ) {
  $DOMAIN_ORIGINAL = get_option('racr_opt_source_url');
  $DOMAIN_CDN = get_option('racr_opt_target_url');

  $data = $value->data;
  
  if (isset($data['content']) && isset($data['content']['rendered'])) {
    $replace = get_option('racr_opt_replace');
    if ($replace == 'true') {
      $data['content']['rendered'] = str_replace($DOMAIN_ORIGINAL.'/wp-content/uploads', $DOMAIN_CDN.'/wp-content/uploads', $data['content']['rendered']);
    } else {
      $data['content']['cdn-rendered'] = str_replace($DOMAIN_ORIGINAL.'/wp-content/uploads', $DOMAIN_CDN.'/wp-content/uploads', $data['content']['rendered']);
    }
  }

  $value->data = $data;

  return $value;
}
add_filter( 'rest_prepare_post', 'dw_rest_prepare_post', 10, 3 );

?>
