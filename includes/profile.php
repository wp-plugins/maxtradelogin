<?php

/** profile front page */
function mtl_profile_form( $username, $password, $email, $website, $first_name, $last_name, $nickname, $bio ) {
    echo __( 'All fields marked with * are mandatory.', 'mtltextdomain' );
    
    echo '
    <form action="' . $_SERVER['REQUEST_URI'] . '" method="post">
    
    <table cellpadding="0" cellspacing="0" width="100%" border="0">
        <tr>
            <td width="40%" style="padding:10px;text-align:left;">
                <strong>' . __("Username *", "mtltextdomain") . '</strong><br />
                <span style="font-size:90%;font-style:italic;">' . __("Login Username - mandatory", "mtltextdomain") . '</span>
            </td>
            <td width="60%" style="padding:10px;text-align:left;">
                <span style="width:100%;">' . $username . '</span>
            </td>
        </tr>
        <tr>
            <td width="40%" style="padding:10px;text-align:left;">
                <strong>' . __("Password *", "mtltextdomain") . '</strong><br />
                <span style="font-size:90%;font-style:italic;">' . __("User password - mandatory", "mtltextdomain") . '</span>
            </td>
            <td width="60%" style="padding:10px;text-align:left;">
                <input style="width:100%;" type="password" name="password" value="' . $password . '">
            </td>
        </tr>
        <tr>
            <td width="40%" style="padding:10px;text-align:left;">
                <strong>' . __("Email *", "mtltextdomain") . '</strong><br />
                <span style="font-size:90%;font-style:italic;">' . __("User email - mandatory", "mtltextdomain") . '</span>
            </td>
            <td width="60%" style="padding:10px;text-align:left;">
                <input style="width:100%;" type="text" name="email" value="' . $email . '">
            </td>
        </tr>
        <tr>
            <td width="40%" style="padding:10px;text-align:left;">
                ' . __("Website", "mtltextdomain") . '<br />
                <span style="font-size:90%;font-style:italic;">' . __("User website, if exist", "mtltextdomain") . '</span>
            </td>
            <td width="60%" style="padding:10px;text-align:left;">
                <input style="width:100%;" type="text" name="website" value="' . $website . '">
            </td>
        </tr>
        <tr>
            <td width="40%" style="padding:10px;text-align:left;">
                ' . __("First Name", "mtltextdomain") . '<br />
                <span style="font-size:90%;font-style:italic;">' . __("User first name", "mtltextdomain") . '</span>
            </td>
            <td width="60%" style="padding:10px;text-align:left;">
                <input style="width:100%;" type="text" name="fname" value="' . $first_name . '">
            </td>
        </tr>
        <tr>
            <td width="40%" style="padding:10px;text-align:left;">
                ' . __("Last Name", "mtltextdomain") . '<br />
                <span style="font-size:90%;font-style:italic;">' . __("User last name", "mtltextdomain") . '</span>
            </td>
            <td width="60%" style="padding:10px;text-align:left;">
                <input style="width:100%;" type="text" name="lname" value="' . $last_name . '">
            </td>
        </tr>
        <tr>
            <td width="40%" style="padding:10px;text-align:left;">
                ' . __("Nickname", "mtltextdomain") . '<br />
                <span style="font-size:90%;font-style:italic;">' . __("User display name", "mtltextdomain") . '</span>
            </td>
            <td width="60%" style="padding:10px;text-align:left;">
                <input style="width:100%;" type="text" name="nickname" value="' . $nickname . '">
            </td>
        </tr>
        <tr>
            <td width="40%" style="padding:10px;text-align:left;">
                ' . __("About / Bio", "mtltextdomain") . '<br />
                <span style="font-size:90%;font-style:italic;">' . __("User biographical data", "mtltextdomain") . '</span>
            </td>
            <td width="60%" style="padding:10px;text-align:left;">
                <textarea rows="5" style="width:100%;" name="bio">' . $bio . '</textarea>
            </td>
        </tr>
    </table>
    
    <div class="g-recaptcha" data-sitekey="' . get_option("mtb_sitekey") . '"></div>
    
    <span style="font-size:90%;font-style:italic;">' . __("If you have filled in all the data correctly, please:", "mtltextdomain") . '</span><br />
    <input type="submit" name="submit" value="' . __("Change", "mtltextdomain") . '"/>
    </form>
    ';
}

/** validation profile fields */
function mtl_profile_validation( $password, $email, $email2, $website, $first_name, $last_name, $nickname, $bio, $recaptcha )  {
    /**  Instantiate the WP_Error class and make the instance variable global so it can be access outside the scope of the function */
    global $reg_errors;
    $reg_errors = new WP_Error;
    
    if ( empty( $password ) ){
        $reg_errors->add('field', __( 'Password is missing!', 'mtltextdomain' ) );
    }
    if ( empty( $email ) ){
        $reg_errors->add('field', __( 'Email is missing', 'mtltextdomain' ) );
    }
    if ( 5 > strlen( $password ) ) {
        $reg_errors->add( 'password', __( 'Password length must be greater than 5', 'mtltextdomain' ) );
    }
    if ( !is_email( $email ) ) {
        $reg_errors->add( 'email_invalid', __( 'Email is not valid', 'mtltextdomain' ) );
    }
    if ( email_exists( $email ) ) {
        if ($email2 != $email){
            $reg_errors->add( 'email', __( 'Email Already in use', 'mtltextdomain' ) );
        }
    }
    if ( ! empty( $website ) ) {
        if ( ! filter_var( $website, FILTER_VALIDATE_URL ) ) {
            $reg_errors->add( 'website', __( 'Website is not a valid URL', 'mtltextdomain' ) );
        }
    }
    if ( empty( $recaptcha ) ) {
        $reg_errors->add( 'recaptcha', __( 'Must pass the test for the robot. The last field of your form to fill out.', 'mtltextdomain' ) );
    }
    if ( is_wp_error( $reg_errors ) ) {
        foreach ( $reg_errors->get_error_messages() as $error ) {
            echo '<div>';
            echo '<strong>' . __( "ERROR", "mtltextdomain" ) . '</strong>: ';
            echo '<span class="mtl_error">' . $error . '</span><br/>';
            echo '</div>';
        }
        echo '<br />';
    }    
}

/**  function that handles the user profile */
function mtl_complete_profile() {
    global $reg_errors, $userid, $password, $email, $website, $first_name, $last_name, $nickname, $bio;
    if ( 1 > count( $reg_errors->get_error_messages() ) ) {
        $userdata = array(
        'ID'            =>   $userid,
        'user_email'    =>   $email,
        'user_pass'     =>   $password,
        'user_url'      =>   $website,
        'first_name'    =>   $first_name,
        'last_name'     =>   $last_name,
        'nickname'      =>   $nickname,
        'description'   =>   $bio,
        );
        $user = wp_update_user( $userdata );
        if ( is_wp_error( $user ) ) {
            echo '<span style="font-size:130%;font-weight:bold;color: red;">' . __( "Registration problem. Try later", "mtltextdomain" ) . '</span><br /><br />';   
        } else {
            echo '<span style="font-size:130%;font-weight:bold;color: green;">' . __( "Changing registration data complete successfully", "mtltextdomain" ) . '</span><br /><br />';   
        }
    }
}

/** registration functions */
function mtl_custom_profile_function() {
    global $current_user;
    get_currentuserinfo();
    if ( isset($_POST['submit'] ) ) {
        mtl_profile_validation(
        $_POST['password'],
        $_POST['email'],
        $current_user->user_email,
        $_POST['website'],
        $_POST['fname'],
        $_POST['lname'],
        $_POST['nickname'],
        $_POST['bio'],
        $_POST['g-recaptcha-response']
        );
         
        // sanitize user form input
        global $userid, $password, $email, $website, $first_name, $last_name, $nickname, $bio;
        $userid     =   $current_user->ID ;
        $password   =   esc_attr( $_POST['password'] );
        $email      =   sanitize_email( $_POST['email'] );
        $website    =   esc_url( $_POST['website'] );
        $first_name =   sanitize_text_field( $_POST['fname'] );
        $last_name  =   sanitize_text_field( $_POST['lname'] );
        $nickname   =   sanitize_text_field( $_POST['nickname'] );
        $bio        =   esc_textarea( $_POST['bio'] );
 
        // call @function complete_registration to create the user
        // only when no WP_error is found
        mtl_complete_profile(
        $userid,
        $password,
        $email,
        $website,
        $first_name,
        $last_name,
        $nickname,
        $bio
        );

        mtl_profile_form(
            $current_user->user_login,
            $password,
            $email,
            $website,
            $first_name,
            $last_name,
            $nickname,
            $bio
        ); 
    }else{
        mtl_profile_form(
            $current_user->user_login,
            $current_user->user_pass,
            $current_user->user_email,
            $current_user->user_url,
            $current_user->first_name,
            $current_user->last_name,
            $current_user->nickname,
            $current_user->description
        ); 
    }
}

/** The callback function */
function mtl_custom_profile_shortcode() {
    ob_start();
    global $current_user;
    get_currentuserinfo();
    if ($current_user->user_login != ""){
        mtl_custom_profile_function();
    }else{
        echo '<span style="font-size:110%;font-weight:bold;color: green;">' . __( "Registration need. Goto", "mtltextdomain" ) . ' <a href="' . get_site_url() . '/wp-login.php">' . __( "login page", "mtltextdomain" ) . '</a>.</span><br /><br />';   
    }
    return ob_get_clean();
}

?>