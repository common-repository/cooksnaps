<div class="wrap">
  <div id="icon-cooksnaps" class="icon32 icon32-posts-post">
    <img src="<?php echo plugins_url('img/cooksnaps-32.png', dirname(__FILE__)) ?>"/>
  </div>
  <h2><?php _e('Cooksnaps config settings','cooksnaps')?></h2>
  <br/><br/>
  <div class="tool-box">
    <h3 class="title"><?php _e('Cooksnaps widget key','cooksnaps')?></h3>
    <table class="form-table">
      <tbody>
        <tr valign="top">
          <th scope="row" style="font-size:14px;">
            <?php _e('Write here your Cooksnap widget author key','cooksnaps')?>
          </td>
          <td>
            <form method="post" action="admin-post.php">
              <input type="hidden" name="action" value="cksnp_save_key_option" />
              <?php wp_nonce_field( 'cksnps_ky_verify' ); ?>
              <input type="text" name="cooksnaps_key" value="<?php echo get_option(COOKSNAPS_OPTION_KEY) ?>" maxlength="22" size="22">
              <input type="submit" name="save-cooksnaps-key" id="save-cooksnaps-key" class="button button-primary" value="<?php _e('Save changes','cooksnaps')?>"/>
            </form>
          </td>
        </tr>
      </tbody>
    </table>
    <br/><br/>
    <h3 class="title"><?php _e('Cooksnaps widget language','cooksnaps')?></h3>
    <table class="form-table">
      <tbody>
        <tr valign="top">
          <th scope="row" style="font-size:14px;">
            <?php _e('Select the language for Cooksnap widget','cooksnaps')?>
          </td>
          <td>
            <form method="post" action="admin-post.php">
              <input type="hidden" name="action" value="cksnp_save_locale_option" />
              <?php wp_nonce_field( 'cksnps_op_verify' ); ?>
              <select name="cooksnaps_locale">
              <?php
                foreach (unserialize(COOKSNAPS_LANGUAGES) as $key => $value) {
                  echo '<option value="' . $key . '"';
                  if ($key == get_option(COOKSNAPS_OPTION_LOCALE)) echo ' selected>';
                  else echo '>';
                  echo _e($value,'cooksnaps') . '</option>';
                }
              ?>
              </select>
              <input type="submit" name="save-cooksnaps-locale" id="save-cooksnaps-locale" class="button button-primary" value="<?php _e('Save changes','cooksnaps')?>"/>
            </form>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</div>
