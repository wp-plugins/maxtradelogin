<?php

/** MTB Category widget */
class MTL_Login extends WP_Widget {

	/** constructor */
    public function __construct() {
		$widget_ops = array( 'classname' => 'mtl_login', 'description' => __( 'User management sistem widget', 'mtltextdomain' ) );
		parent::__construct('mtl_login', __('MTL Users', 'mtltextdomain'), $widget_ops);
	}

    /** front page function */
	public function widget( $args, $instance ) {

		$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? __( '' ) : $instance['title'], $instance, $this->id_base );

		$show_username = ! empty( $instance['show_username'] ) ? '1' : '0';
		$show_email = ! empty( $instance['show_email'] ) ? '1' : '0';
		$show_name = ! empty( $instance['show_name'] ) ? '1' : '0';
		$show_profile = ! empty( $instance['show_profile'] ) ? '1' : '0';
		$show_items = ! empty( $instance['show_items'] ) ? '1' : '0';
		$show_dashboard = ! empty( $instance['show_dashboard'] ) ? '1' : '0';
		$profile = ! empty( $instance['profile'] ) ? $instance['profile'] : '';
		$register = ! empty( $instance['register'] ) ? $instance['register'] : '';
		$lostpassword = ! empty( $instance['lostpassword'] ) ? $instance['lostpassword'] : '';

		echo $args['before_widget'];

		if ( $title ) {
			echo $args['before_title'] . $title . $args['after_title'];
		}
        
        global $current_user;
        get_currentuserinfo();
        if ($current_user->user_login != ""){
            ?>
            <?php
            if (get_option("mtl_avatar_in") == 'on'){
            ?>
            <table width="100%" cellpadding="0" cellspacing="0" border="0">
              <tr>
                <td width="60px" style="vertical-align: bottom;">
                <?php
                //echo get_avatar( $current_user->user_email, $size = '96', $default = '<path_to_url>' );
                if (validate_gravatar($current_user->user_email)){
                  if ($show_profile){
                    echo '<a href="' . $profile . '" title="' . __( 'Edit my profile', 'mtltextdomain' ) . '">';
                  }
                  echo get_avatar( $current_user->user_email, $size = '60' );
                  if ($show_profile){
                    echo '</a>';
                  }
                }else{
                  if ($show_profile){
                    echo '<a href="' . $profile . '" title="' . __( 'Edit my profile', 'mtltextdomain' ) . '">';
                  }
                ?>
                  <img alt='<?php _e( 'No valid gravater', 'mtltextdomain' ) ?>' src='<?php echo MTL_IMAGES_URI; ?>/avatar-default-icon.png' class='avatar avatar-60' height='60' width='60' />
                <?php
                  if ($show_profile){
                    echo '</a>';
                  }
                }
                ?>
                </td>
                <td style="vertical-align: bottom;padding-left:5px;">
                <?php
                $del = false;
                if ($show_username){
                  echo $current_user->user_login . "<br />";
                  $del = true;
                }
                if ($show_email){
                  echo $current_user->user_email . "<br />";
                  $del = true;
                }
                if ($show_name){
                  echo '<strong>' . $current_user->display_name . "</strong><br />";
                  $del = true;
                }
                ?>
                </td>
              </tr>
            </table>
            <?php
            }else{
                $del = false;
                if ($show_username){
                  echo __( 'Nick name:', 'mtltextdomain' ) . ' ' . $current_user->user_login . "<br />";
                  $del = true;
                }
                if ($show_email){
                  echo __( 'User E-Mail:', 'mtltextdomain' ) . ' ' . $current_user->user_email . "<br />";
                  $del = true;
                }
                if ($show_name){
                  echo '<strong>' . $current_user->display_name . "</strong><br />";
                  $del = true;
                }
            }
            ?>
            <?php
            if ($del){
                echo '<div style="padding-top:10px;"></div>';
            }
            if ($show_profile){
              echo '<a href="' . $profile . '" title="' . __( 'Edit my profile', 'mtltextdomain' ) . '">&raquo;&nbsp;' . __( 'My profile', 'mtltextdomain' ) . '</a>' . "<br />";
            }
            if ($show_items){
              echo '<a href="' . get_author_posts_url( $current_user->ID ) . '" title="' . __( 'Wiew my items', 'mtltextdomain' ) . '">&raquo;&nbsp;' . __( 'My items', 'mtltextdomain' ) . '</a>' . "<br />";
            }
            if ($show_dashboard){
              echo '<a href="' . get_site_url() . '/wp-admin" title="' . __( 'Dashboard', 'mtltextdomain' ) . '">&raquo;&nbsp;' . __( 'Dashboard', 'mtltextdomain' ) . '</a>' . "<br />";
            }
?>
<br />
<?php            
            echo '<a href="' . wp_logout_url() . '" title="' . __( 'Exit', 'mtltextdomain' ) . '">&raquo;&nbsp;' . __( 'Exit', 'mtltextdomain' ) . '</a>';
        }else{
            echo '<a href="' . $register . '" title="' . __( 'New registration', 'mtltextdomain' ) . '">&raquo;&nbsp;' . __( 'New registration', 'mtltextdomain' ) . '</a>' . "<br />";
            echo '<a href="' . $lostpassword . '" title="' . __( 'Lost password', 'mtltextdomain' ) . '">&raquo;&nbsp;' . __( 'Lost password', 'mtltextdomain' ) . '</a>' . "<br />";
            echo '<a href="' . get_site_url() . '/wp-login.php" title="' . __( 'Login', 'mtltextdomain' ) . '">&raquo;&nbsp;' . __( 'Login', 'mtltextdomain' ) . '</a>';
        }
		
        echo $args['after_widget'];
	}

	/** update admin values */
    public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['show_username'] = !empty($new_instance['show_username']) ? 1 : 0;
		$instance['show_email'] = !empty($new_instance['show_email']) ? 1 : 0;
		$instance['show_name'] = !empty($new_instance['show_name']) ? 1 : 0;
		$instance['show_profile'] = !empty($new_instance['show_profile']) ? 1 : 0;
		$instance['show_items'] = !empty($new_instance['show_items']) ? 1 : 0;
		$instance['show_dashboard'] = !empty($new_instance['show_dashboard']) ? 1 : 0;
		$instance['profile'] = !empty($new_instance['profile']) ? $new_instance['profile'] : '';
		$instance['register'] = !empty($new_instance['register']) ? $new_instance['register'] : '';
		$instance['lostpassword'] = !empty($new_instance['lostpassword']) ? $new_instance['lostpassword'] : '';

		return $instance;
	}

	/** admin page function */ 
    public function form( $instance ) {
		//Defaults
		$instance = wp_parse_args( (array) $instance, array( 'title' => '') );
		$title = esc_attr( $instance['title'] );
		$show_username = isset($instance['show_username']) ? (bool) $instance['show_username'] :false;
		$show_email = isset($instance['show_email']) ? (bool) $instance['show_email'] :false;
		$show_name = isset($instance['show_name']) ? (bool) $instance['show_name'] :false;
		$show_profile = isset($instance['show_profile']) ? (bool) $instance['show_profile'] :false;
		$show_items = isset($instance['show_items']) ? (bool) $instance['show_items'] :false;
		$show_dashboard = isset($instance['show_dashboard']) ? (bool) $instance['show_dashboard'] :false;
		$profile = isset($instance['profile']) ? $instance['profile'] : '';
		$register = isset($instance['register']) ? $instance['register'] : '';
		$lostpassword = isset($instance['lostpassword']) ? $instance['lostpassword'] : '';
?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e( 'Title:', 'mtltextdomain' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></p>

		<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('show_username'); ?>" name="<?php echo $this->get_field_name('show_username'); ?>"<?php checked( $show_username ); ?> />
		<label for="<?php echo $this->get_field_id('show_username'); ?>"><?php _e( 'Show username', 'mtltextdomain' ); ?></label><br />

		<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('show_email'); ?>" name="<?php echo $this->get_field_name('show_email'); ?>"<?php checked( $show_email ); ?> />
		<label for="<?php echo $this->get_field_id('show_email'); ?>"><?php _e( 'Show email', 'mtltextdomain' ); ?></label><br />

		<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('show_name'); ?>" name="<?php echo $this->get_field_name('show_name'); ?>"<?php checked( $show_name ); ?> />
		<label for="<?php echo $this->get_field_id('show_name'); ?>"><?php _e( 'Display name ', 'mtltextdomain' ); ?></label><br />

		<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('show_profile'); ?>" name="<?php echo $this->get_field_name('show_profile'); ?>"<?php checked( $show_profile ); ?> />
		<label for="<?php echo $this->get_field_id('show_profile'); ?>"><?php _e( 'Display profile link ', 'mtltextdomain' ); ?></label><br />

		<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('show_items'); ?>" name="<?php echo $this->get_field_name('show_items'); ?>"<?php checked( $show_items ); ?> />
		<label for="<?php echo $this->get_field_id('show_items'); ?>"><?php _e( 'Display items link ', 'mtltextdomain' ); ?></label><br />

		<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('show_dashboard'); ?>" name="<?php echo $this->get_field_name('show_dashboard'); ?>"<?php checked( $show_dashboard ); ?> />
		<label for="<?php echo $this->get_field_id('show_dashboard'); ?>"><?php _e( 'Display dashboard link ', 'mtltextdomain' ); ?></label><br />

		<p><label for="<?php echo $this->get_field_id('profile'); ?>"><?php _e( 'Profile url:', 'mtltextdomain' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('profile'); ?>" name="<?php echo $this->get_field_name('profile'); ?>" type="text" value="<?php echo $profile; ?>" /></p>

		<p><label for="<?php echo $this->get_field_id('register'); ?>"><?php _e( 'New register url:', 'mtltextdomain' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('register'); ?>" name="<?php echo $this->get_field_name('register'); ?>" type="text" value="<?php echo $register; ?>" /></p>

		<p><label for="<?php echo $this->get_field_id('lostpassword'); ?>"><?php _e( 'Lost password url:', 'mtltextdomain' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('lostpassword'); ?>" name="<?php echo $this->get_field_name('lostpassword'); ?>" type="text" value="<?php echo $lostpassword; ?>" /></p>

        <br />
<?php
	}

}

function mtl_register_login_widgets() {
	register_widget( 'MTL_Login' );
}

?>