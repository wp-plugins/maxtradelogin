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

?>