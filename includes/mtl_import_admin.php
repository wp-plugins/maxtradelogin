<?php 
    if($_POST['mtl_hidden'] == 'Y') {
        $mtl_items_in = $_POST['mtl_items_in'];
        update_option('mtl_items_in', $mtl_items_in);
        if ($mtl_items_in == 'on'){
            $mtl_items_in = 1;
        }else{
            $mtl_items_in = 0;
        }
        $mtl_captcha_in = $_POST['mtl_captcha_in'];
        update_option('mtl_captcha_in', $mtl_captcha_in);
        if ($mtl_captcha_in == 'on'){
            $mtl_captcha_in = 1;
        }else{
            $mtl_captcha_in = 0;
        }
        $mtl_sitekey = $_POST['mtl_sitekey'];
        update_option('mtl_sitekey', $mtl_sitekey);
        $mtl_minpasswordlength = $_POST['mtl_minpasswordlength'];
        update_option('mtl_minpasswordlength', $mtl_minpasswordlength);
        $mtl_avatar_in = $_POST['mtl_avatar_in'];
        update_option('mtl_avatar_in', $mtl_avatar_in);
        if ($mtl_avatar_in == 'on'){
            $mtl_avatar_in = 1;
        }else{
            $mtl_avatar_in = 0;
        }
        ?>
        <div class="updated"><p><strong><?php _e('Options saved.', 'mtltextdomain' ); ?></strong></p></div>
        <?php
    } else {
        if (get_option('mtl_items_in') == 'on'){
            $mtl_items_in = 1;
        }else{
            $mtl_items_in = 0;
        }
        if (get_option('mtl_captcha_in') == 'on'){
            $mtl_captcha_in = 1;
        }else{
            $mtl_captcha_in = 0;
        }
        $mtl_sitekey = get_option('mtl_sitekey');
        $mtl_minpasswordlength = get_option('mtl_minpasswordlength');
        if (get_option('mtl_avatar_in') == 'on'){
            $mtl_avatar_in = 1;
        }else{
            $mtl_avatar_in = 0;
        }
    }
?>
<div class="wrap">
    <?php    echo "<h2>" . __( 'MaxtradeLogin options page', 'mtltextdomain' ) . "</h2>"; ?>
     
    <form name="mtl_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
        <input type="hidden" name="mtl_hidden" value="Y">
        <?php    echo "<h4>" . __( 'MaxtradeLogin menu setting', 'mtltextdomain' ) . "</h4>"; ?>
        <table cellspacing="0" cellpadding="0" border="0" width="100%">
          <tr>
            <td width="300px">
              <?php _e( 'Include items in menus', 'mtltextdomain' ); ?>
            </td>
            <td>
		      <input type="checkbox" class="checkbox" id="mtl_items_in" name="mtl_items_in"<?php checked( $mtl_items_in ); ?> />
            </td>
          </tr>
        </table>
        <hr />
        <?php    echo "<h4>" . __( 'MaxtradeLogin reCaptcha setting', 'mtltextdomain' ) . "</h4>"; ?>
        <table cellspacing="0" cellpadding="0" border="0" width="100%">
          <tr>
            <td width="300px">
              <?php _e( 'Include reCaptcha in registration page', 'mtltextdomain' ); ?>
            </td>
            <td>
		      <input type="checkbox" class="checkbox" id="mtl_captcha_in" name="mtl_captcha_in"<?php checked( $mtl_captcha_in ); ?> />
            </td>
          </tr>
          <tr>
            <td width="300px">
              <?php _e( 'Site key', 'mtltextdomain' ); ?>
            </td>
            <td>
              <input type="text" name="mtl_sitekey" value="<?php echo $mtl_sitekey; ?>" size="100">
            </td>
          </tr>
        </table>
        <hr />
        <?php    echo "<h4>" . __( 'MaxtradeLogin user profile setting', 'mtltextdomain' ) . "</h4>"; ?>
        <table cellspacing="0" cellpadding="0" border="0" width="100%">
          <tr>
            <td width="300px">
              <?php _e( 'Minimum password length', 'mtltextdomain' ); ?>
            </td>
            <td>
              <input type="text" name="mtl_minpasswordlength" value="<?php echo $mtl_minpasswordlength; ?>" size="20">
            </td>
          </tr>
        </table>
        <hr />
        <?php    echo "<h4>" . __( 'MaxtradeLogin visual style settings', 'mtltextdomain' ) . "</h4>"; ?>
        <table cellspacing="0" cellpadding="0" border="0" width="100%">
          <tr>
            <td width="300px">
              <?php _e( 'Include avatar in login widget', 'mtltextdomain' ); ?>
            </td>
            <td>
		      <input type="checkbox" class="checkbox" id="mtl_avatar_in" name="mtl_avatar_in"<?php checked( $mtl_avatar_in ); ?> />
            </td>
          </tr>
        </table>
        <hr />
        <p class="submit">
        <input type="submit" name="Submit" value="<?php _e('Update Options', 'mtltextdomain' ) ?>" />
        </p>
    </form>
</div>