<?php

  add_action('admin_menu', 'create_admin_page');

  // create admin page
  function create_admin_page() {
      add_options_page('Rest API CDN replacer', 'Rest API CDN', 'manage_options', 'CDN replacer', 'show_replacer_options_page');
  }

  function do_racr_update() {
    if ( isset( $_POST['action'], $_POST['_wpnonce']) ) {
      update_option( 'racr_opt_source_url', untrailingslashit( wp_unslash( $_POST['racr_opt_source_url'] ) ) ); // WPSC: sanitization ok.
      update_option( 'racr_opt_target_url', untrailingslashit( wp_unslash( $_POST['racr_opt_target_url'] ) ) ); // WPSC: sanitization ok.
      update_option( 'racr_opt_replace', sanitize_text_field( wp_unslash( $_POST['racr_opt_replace'] ) ) ); // WPSC: sanitization ok.
    }
  }

  function show_replacer_options_page() {
    ?>

    <div class="wrap">
      <h2>rest api cnd replacer</h2>
      <p>No slash after the url.</p>

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
        <input type="hidden" name="page_options" value="racr_opt_source_url,racr_opt_target_url,racr_opt_replace" />

        <p class="submit">
          <input type="submit" name="Submit" value="<?php _e('Save Changes') ?>" />
        </p>
      </form>

    </div>

    <?php
    } 