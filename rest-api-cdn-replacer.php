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

<div class="wrap">
  <h2>rest api cnd replacer</h2>


  <form method="post" action="options.php">
    <?php wp_nonce_field('update-options'); ?>
    
    <table class="form-table">
      <tr valign="top">
        <th scope="row">Source URL</th>
        <td><input type="text" name="racr_opt_source_url" value="<?php echo esc_attr(get_option('racr_opt_source_url')); ?>" /></td>
      </tr>
      <tr valign="top">
        <th scope="row">Target URL</th>
        <td><input type="text" name="racr_opt_target_url" value="<?php echo esc_attr(get_option('racr_opt_target_url')); ?>" /></td>
      </tr>
      <tr valign="top">
        <th scope="row">Replace</th>
        <td><input type="text" name="racr_opt_replace" value="<?php echo esc_attr(get_option('racr_opt_replace')); ?>" /></td>
      </tr>
    </table>
    <input type="hidden" name="action" value="update" />
    <input type="hidden" name="page_options" value="racr_opt_source_url,racr_opt_target_url" />

    <p class="submit">
      <input type="submit" name="Submit" value="<?php _e('Save Changes') ?>" />
    </p>
  </form>

</div>