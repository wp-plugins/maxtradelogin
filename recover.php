<?php /** Function that retrieves the unique user key from the database. If we don't have one we generate one and add it to the database */
function mtl_retrieve_activation_key( $requested_user_login ){
	global $wpdb;
	$key = $wpdb->get_var( $wpdb->prepare( "SELECT user_activation_key FROM $wpdb->users WHERE user_login = %s", $requested_user_login ) );
	if ( empty( $key ) ) {
		// Generate something random for a key...
		$key = wp_generate_password( 20, false );
		do_action('wppb_retrieve_password_key', $requested_user_login, $key);
		// Now insert the new md5 key into the db
		$wpdb->update($wpdb->users, array('user_activation_key' => $key), array('user_login' => $requested_user_login));
	}
	return $key;
}

 /** Function that creates a generate new password form */
function mtl_create_recover_password_form( $user, $post_data ){
	?>
	<form enctype="multipart/form-data" method="post" id="mtb-recover-password" class="mtb-user-forms" action="<?php echo add_query_arg( 'finalAction', 'yes', mtl_curpageurl() ); ?>">
		<ul>
	<?php
        if( !empty( $post_data['passw1'] ) )
            $passw_one = $post_data['passw1'];
        else
            $passw_one = '';
        if( !empty( $post_data['passw2'] ) )
            $passw_two = $post_data['passw2'];
        else
            $passw_two = '';
		echo '
			<li>
				<label for="passw1">'. __( 'Password', 'mtltextdomain' ).'</label>
				<input class="password" name="passw1" type="password" id="passw1" value="'. $passw_one .'" autocomplete="off" title="'. mtl_password_length_text() .'"/>
			</li><!-- .passw1 -->
			<input type="hidden" name="userData" value="'.$user->ID.'"/>
			<li>
				<label for="passw2">'. __( 'Repeat Password', 'mtltextdomain' ).'</label>
				<input class="password" name="passw2" type="password" id="passw2" value="'.$passw_two.'" autocomplete="off" />
			</li><!-- .passw2 -->';
?>
		</ul>
		<p class="form-submit">
			<?php $button_name = __('Reset Password', 'mtltextdomain'); ?>
			<input name="recover_password2" type="submit" id="mtb-recover-password-button" class="submit button" value="<?php echo $button_name; ?>" />
			<input name="action2" type="hidden" id="action2" value="recover_password2" />
		</p><!-- .form-submit -->
		<?php wp_nonce_field( 'verify_true_password_recovery2_'.$user->ID, 'password_recovery_nonce_field2' ); ?>
	</form><!-- #recover_password -->
	<?php
}

/** Function that generates the recover password form */
 function mtl_create_generate_password_form( $post_data ){
	?>
	<form enctype="multipart/form-data" method="post" id="mtb-recover-password" class="mtb-user-forms" action="<?php echo add_query_arg( 'submitted', 'yes', mtl_curpageurl() ); ?>">
	<?php
	echo '<p>' . __( 'Please enter your username or email address.', 'mtltextdomain' ) . '<br/>'.__( 'You will receive a link to create a new password via email.', 'mtltextdomain' ).'</p>';
	$username_email = ( isset( $post_data['username_email'] ) ? $post_data['username_email'] : '' );
	echo 
        '<ul>
			<li class="mtb-form-field">
				<label for="username_email">' . __( 'Username or E-mail', 'mtltextdomain' ) . '</label>
				<input class="mtb-text-input" name="username_email" type="text" id="username_email" value="' . trim( $username_email ) . '" />
			</li>
        </ul>';
		?>
	<p class="form-submit">
		<input name="recover_password" type="submit" id="mtb-recover-password-button" class="submit button" value="<?php _e('Get New Password', 'mtltextdomain'); ?>" />
		<input name="action" type="hidden" id="action" value="recover_password" />
	</p>
	<?php wp_nonce_field( 'verify_true_password_recovery', 'password_recovery_nonce_field' ); ?>
	</form>
	<?php
}

/** The function for the recover password shortcode */
function mtl_front_end_password_recovery(){
    global $mtl_shortcode_on_front;
    $mtl_shortcode_on_front = true;
	$message = $messageNo = $message2 = $messageNo2 = $linkLoginName = $linkKey = '';
	global $wpdb;
	ob_start();
	// If the user entered an email/username, process the request
	if ( 'POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['action'] ) && $_POST['action'] == 'recover_password' && wp_verify_nonce($_POST['password_recovery_nonce_field'],'verify_true_password_recovery') ) {
		$postedData = $_POST['username_email'];	//we get the raw data
		//check to see if it's an e-mail (and if this is valid/present in the database) or is a username
		// if we do not have an email in the posted date we try to get the email for that user
		if( !is_email( $postedData ) ){
			if (username_exists($postedData)){
				$query = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $wpdb->users WHERE user_login= %s", $postedData ) );
				if( !empty( $query[0] ) ){
					$postedData = $query[0]->user_email;
				}
			}
			else{
				$message = __( 'The username entered wasn\'t found in the database!', 'mtltextdomain').'<br/>'.__('Please check that you entered the correct username.', 'mtltextdomain' );
				$messageNo = '4';
			}
		}
		// we should have an email by this point
		if ( is_email( $postedData ) ){
			if ( email_exists( $postedData ) ){
					$message = sprintf( __( 'Check your e-mail for the confirmation link.', 'mtltextdomain'), $postedData );
					$messageNo = '1';
					//verify e-mail validity
					$query = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $wpdb->users WHERE user_email= %s", $postedData ) );
					if( !empty( $query[0] ) ){
						$requestedUserID = $query[0]->ID;
						$requestedUserLogin = $query[0]->user_login;
						$requestedUserEmail = $query[0]->user_email;
                        $display_username_email = $query[0]->user_email;
						//search if there is already an activation key present, if not create one
						$key = mtl_retrieve_activation_key( $requestedUserLogin );
						//send primary email message
						$recoveruserMailMessage1  = sprintf( __('Someone requested that the password be reset for the following account: <b>%1$s</b><br/>If this was a mistake, just ignore this email and nothing will happen.<br/>To reset your password, visit the following link:%2$s', 'mtltextdomain'), $display_username_email, '<a href="'.add_query_arg( array( 'loginName' => $requestedUserLogin, 'key' => $key ), mtl_curpageurl() ).'">'.add_query_arg( array( 'loginName' => $requestedUserLogin, 'key' => $key ), mtl_curpageurl() ).'</a>');
						$recoveruserMailMessageTitle1 = sprintf(__('Password Reset from "%1$s"', 'mtltextdomain'), $blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES));
						//we add this filter to enable html encoding
						add_filter('wp_mail_content_type',create_function('', 'return "text/html"; '));
						//send mail to the user notifying him of the reset request
						if (trim($recoveruserMailMessageTitle1) != ''){
							$sent = wp_mail($requestedUserEmail, $recoveruserMailMessageTitle1, $recoveruserMailMessage1);
							if ($sent === false){
								$message = '<b>'. __( 'ERROR', 'mtltextdomain' ) .': </b>' . sprintf( __( 'There was an error while trying to send the activation link to %1$s!', 'mtltextdomain' ), $postedData );
								$messageNo = '5';
							}
						}
					}
			}elseif ( !email_exists( $postedData ) ){
				$message = __('The email address entered wasn\'t found in the database!', 'mtltextdomain').'<br/>'.__('Please check that you entered the correct email address.', 'mtltextdomain');
				$messageNo = '2';
			}
		}

    }
	// If the user used the correct key-code, update his/her password
	elseif ( 'POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['action2'] ) && $_POST['action2'] == 'recover_password2' && wp_verify_nonce( $_POST['password_recovery_nonce_field2'], 'verify_true_password_recovery2_'.$_POST['userData'] ) ) {
        if( ( $_POST['passw1'] == $_POST['passw2'] ) && ( !empty( $_POST['passw1'] ) && !empty( $_POST['passw2'] ) ) ){
            $mtl_minpasswordlength = get_option('mtl_minpasswordlength');
            if( !empty( $mtl_minpasswordlength ) && is_int( $mtl_minpasswordlength ) ){
                $message2 = '';
                if( mtl_check_password_length( $_POST['passw1'] ) ){
                    $message2 .= '<br/>' . sprintf( __( "The password must have the minimum length of %s characters", "mtltextdomain" ), get_option('mtl_minpasswordlength') ) . '<br/>';
                    $messageNo2 = '2';
                }
            }
            if( $messageNo2 != 2 ){
                $message2 = __( 'Your password has been successfully changed!', 'mtltextdomain' );
                $messageNo2 = '1';
                $userID = $_POST['userData'];
                $new_pass = $_POST['passw1'];
                //update the new password and delete the key
                do_action( 'mtl_password_reset', $userID, $new_pass );
                wp_set_password( $new_pass, $userID );
                $user_info = get_userdata( $userID );
                $display_username_email = $user_info->user_email;
                //send secondary mail to the user containing the username and the new password
                $recoveruserMailMessage2  = sprintf( __( 'You have successfully reset your password to: %1$s', 'mtltextdomain' ), $new_pass );
                $recoveruserMailMessageTitle2 = sprintf( __('Password Successfully Reset for %1$s on "%2$s"', 'mtltextdomain' ), $display_username_email, $blogname = wp_specialchars_decode( get_option('blogname'), ENT_QUOTES ) );
                //we add this filter to enable html encoding
                add_filter( 'wp_mail_content_type',create_function( '', 'return "text/html"; ') );
                //send mail to the user notifying him of the reset request
                if ( trim( $recoveruserMailMessageTitle2 ) != '' )
                    wp_mail( $user_info->user_email, $recoveruserMailMessageTitle2, $recoveruserMailMessage2 );
                //send email to admin
                $recoveradminMailMessage = sprintf( __( '%1$s has requested a password change via the password reset feature.<br/>His/her new password is:%2$s', 'mtltextdomain' ), $display_username_email, $_POST['passw1'] );
                $recoveradminMailMessageTitle = sprintf( __( 'Password Successfully Reset for %1$s on "%2$s"', 'mtltextdomain' ), $display_username_email, $blogname = wp_specialchars_decode( get_option('blogname'), ENT_QUOTES ) );
                //we disable the feature to send the admin a notification mail but can be still used using filters
                $recoveradminMailMessageTitle = '';
                $recoveradminMailMessageTitle = apply_filters( 'mtl_recover_password_message_title_sent_to_admin', $recoveradminMailMessageTitle, $display_username_email );
                //we add this filter to enable html encoding
                add_filter('wp_mail_content_type',create_function('', 'return "text/html"; '));
                //send mail to the admin notifying him of of a user with a password reset request
                if (trim($recoveradminMailMessageTitle) != '')
                    wp_mail(get_option('admin_email'), $recoveradminMailMessageTitle, $recoveradminMailMessage);
            }
		}
        else{
            $message2 = __( 'The entered passwords don\'t match!', 'mtltextdomain' );
            $messageNo2 = '2';
        }
    }
?>
	<div class="mtl_holder" id="mtb-recover-password">
<?php
			if( isset( $_GET['submitted'] ) && isset( $_GET['loginName'] ) && isset( $_GET['key'] ) ){
				//get the login name and key and verify if they match the ones in the database
				$key = $_GET['key'];
				$login = $_GET['loginName'];
				$user = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $wpdb->users WHERE user_activation_key = %s AND user_login = %s", $key, $login ) );
				if( !empty( $user ) ){
					//check if the "finalAction" variable is not in the address bar, if it is, don't display the form anymore
					if( isset( $_GET['finalAction'] ) && ( $_GET['finalAction'] == 'yes' ) ){
						if( $messageNo2 == '2' ){
							echo '<p class="mtl_error">'.$message2.'</p>', $message2;
							mtl_create_recover_password_form( $user, $_POST );
						}elseif( $messageNo2 == '1' )
							echo '<p class="mtl_success">'.$message2.'</p>', $message2;
					}else{
						mtl_create_recover_password_form( $user, $_POST );
					}
				}else{
					if( $messageNo2 == '1' )
						echo '<p class="mtl_success">'.$message2.'</p>', $message2;
					elseif( $messageNo2 == '2' )
						echo '<p class="mtl_error">'.$message2.'</p>', $message2;
					else
						echo '<p class="mtl_warning"><b>'.__( 'ERROR:', 'mtltextdomain' ).'</b>'.__( 'Invalid key!', 'mtltextdomain' ).'</p>';
				}
			}else{
				//display error message and the form
				if (($messageNo == '') || ($messageNo == '2') || ($messageNo == '4')){
					echo '<p class="mtl_warning">'.$message.'</p>';
					mtl_create_generate_password_form( $_POST );
				}elseif (($messageNo == '5')  || ($messageNo == '6'))
					echo  '<p class="mtl_warning">'.$message.'</p>';
				else
					echo '<p class="mtl_success">'.$message.'</p>'; //display success message

			}
    return ob_get_clean();
}
?>