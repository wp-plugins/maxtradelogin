<?php
/**
 * Plugin Name: MaxtradeLogin
 * Plugin URI: http://software.avalonbg.com/maxtradelogin
 * Description: User management plugin. Login, register, profile and change password.
 * Version: 1.0.1
 * Author: Ilko Ivanov
 * Author URI: http://software.avalonbg.com/ilko
 * Text Domain: mtltextdomain
 * Domain Path: /languages
 * Network: Optional. Whether the plugin can only be activated network wide. Example: true
 * License: MIT
 */
 
 /**
  *  Copyright 2015 Ilko Ivanov (email: ilko.iv at gmail.com)
  *
  *  This program is free software; you can redistribute it and/or modify
  *  it under the terms of the GNU General Public License as published by
  *  the Free Software Foundation; either version 2 of the License, or
  *  (at your option) any later version.
  *
  *  This program is distributed in the hope that it will be useful,
  *  but WITHOUT ANY WARRANTY; without even the implied warranty of
  *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  *  GNU General Public License for more details.
  *
  *  You should have received a copy of the GNU General Public License
  *  along with this program; if not, write to the Free Software
  *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/
 
/** definitions */
define( 'MTL_PLUGIN_DIR', untrailingslashit( dirname( __FILE__ ) ) );
define( 'MTL_INCLUDES_DIR', MTL_PLUGIN_DIR . '/includes' );
define( 'MTL_IMAGES_URI', '/wp-content/plugins/maxtrade-login/images' );
define( 'MTL_CSS_URI', '/wp-content/plugins/maxtrade-login/css' );
define( 'MTL_JS_URI', '/wp-content/plugins/maxtrade-login/js' );

/** includes */
require_once MTL_INCLUDES_DIR . '/functions.php';
require_once MTL_INCLUDES_DIR . '/login_widgets.php';
require_once MTL_INCLUDES_DIR . '/admin.php';
require_once MTL_INCLUDES_DIR . '/registration.php';
require_once MTL_INCLUDES_DIR . '/profile.php';
require_once MTL_INCLUDES_DIR . '/recover.php';

/** load textdomain */
add_action( 'plugins_loaded', 'mtl_load_textdomain' );
function mtl_load_textdomain() {
  load_plugin_textdomain( 'mtltextdomain', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' ); 
}

/** add custom css ###includes/functions.php### */
add_action('wp_enqueue_scripts', "mtl_add_css_files");

/** add custom js ###includes/functions.php### */
add_action('wp_enqueue_scripts', "mtl_add_js_files");

/** redirect after logout and login to home page ###includes/functions.php### */
add_action('wp_logout','mtl_go_home');
add_action('login_form','mtl_redirect_to_home');

/** init widgets ###includes/functions.php### */
add_action( 'widgets_init', 'mtl_register_login_widgets');

/** add admin menu MaxtradeLogin options page ###includes/admin.php### */
add_action('admin_menu', 'mtl_admin_actions');
 
/** add menu item - main site ###includes/functions.php### */
add_filter('wp_nav_menu_items','mtl_add_nav_menu_items', 10, 2);

/** Register a new shortcode: [mtl_registration] [mtl_profile], [mtl_recover_password] ###includes/registration.php includes/profile.php includes/recover.php### */
add_shortcode( 'mtl_registration', 'mtl_custom_registration_shortcode' );
add_shortcode( 'mtl_profile', 'mtl_custom_profile_shortcode' );
add_shortcode( 'mtl_recover_password', 'mtl_front_end_password_recovery' );

/** output buffer */
add_action('init', 'mtl_do_output_buffer');
