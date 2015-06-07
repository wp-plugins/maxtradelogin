<?php

/**  redirect to home after logout */
function mtl_go_home(){
  wp_redirect( home_url() );
  exit();
}

/**  redirect to home after login */
function mtl_redirect_to_home() {
  global $redirect_to;
  if (!isset($_GET['redirect_to'])) {
    $redirect_to = get_option('siteurl');
  }
}

/** Remove issues with prefetching adding extra views */
remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0); 

/** add custom css files */
function mtl_add_css_files(){
  wp_enqueue_style( 'mtl_styles', MTL_CSS_URI . '/style.css', false, '1.0.0', 'all');
}

/** add custom js files */
function mtl_add_js_files(){
  if (get_option('mtl_captcha_in') == 'on'){
      wp_enqueue_script( 'mtl_recaptcha_js', 'https://www.google.com/recaptcha/api.js');
  }
}

/** load admin menu */
function mtl_admin_options() {
    include('mtl_import_admin.php');
}

/** add nav menu to main site */
function mtl_add_nav_menu_items( $items, $args ) {
    global $current_user;
    get_currentuserinfo();
    if (($current_user->user_login != "") && (get_option('mtl_items_in') == 'on')){
        $items .= '<li id="menu-item-main-site" class="menu-item"><a href="' . get_author_posts_url( $current_user->ID ) . '">' . __("My items", "mtltextdomain") . '</a></li>';
    }
    return $items;
}

/** do output buffer */
function mtl_do_output_buffer() {
    ob_start();
}
 
/** return page url */
if(!function_exists('mtl_curpageurl')){
	function mtl_curpageurl() {
		$pageURL = 'http';
		if ((isset($_SERVER["HTTPS"])) && ($_SERVER["HTTPS"] == "on"))
			$pageURL .= "s";
		$pageURL .= "://";
		if ($_SERVER["SERVER_PORT"] != "80")
			$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
		else
			$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
		return $pageURL;
	}
}

/* function to output password length requirements text */
function mtl_password_length_text(){
    $mtl_minpasswordlength = get_option('mtl_minpasswordlength');
    if( !empty( $mtl_minpasswordlength ) ){
        return sprintf(__('Minimum length of %d characters', 'mtltextdomain'), $mtl_minpasswordlength);
    }
    return '';
}

/**
 * Utility function to check if a gravatar exists for a given email or id
 * @param int|string|object $id_or_email A user ID,  email address, or comment object
 * @return bool if the gravatar exists or not
 */

function validate_gravatar($id_or_email) {
  //id or email code borrowed from wp-includes/pluggable.php
	$email = '';
	if ( is_numeric($id_or_email) ) {
		$id = (int) $id_or_email;
		$user = get_userdata($id);
		if ( $user )
			$email = $user->user_email;
	} elseif ( is_object($id_or_email) ) {
		// No avatar for pingbacks or trackbacks
		$allowed_comment_types = apply_filters( 'get_avatar_comment_types', array( 'comment' ) );
		if ( ! empty( $id_or_email->comment_type ) && ! in_array( $id_or_email->comment_type, (array) $allowed_comment_types ) )
			return false;

		if ( !empty($id_or_email->user_id) ) {
			$id = (int) $id_or_email->user_id;
			$user = get_userdata($id);
			if ( $user)
				$email = $user->user_email;
		} elseif ( !empty($id_or_email->comment_author_email) ) {
			$email = $id_or_email->comment_author_email;
		}
	} else {
		$email = $id_or_email;
	}

	$hashkey = md5(strtolower(trim($email)));
	$uri = 'http://www.gravatar.com/avatar/' . $hashkey . '?d=404';

	$data = wp_cache_get($hashkey);
	if (false === $data) {
		$response = wp_remote_head($uri);
		if( is_wp_error($response) ) {
			$data = 'not200';
		} else {
			$data = $response['response']['code'];
		}
	    wp_cache_set($hashkey, $data, $group = '', $expire = 60*5);

	}		
	if ($data == '200'){
		return true;
	} else {
		return false;
	}
}

?>