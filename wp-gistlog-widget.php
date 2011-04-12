<?php
/*
Plugin Name: WP-Gistlog-Widget
Plugin URI: 
Description: This plugin allows you to display the gistlog.org histories.
Version: 1.0.0
Author: smeghead
 */

// wpgw_options_page() displays the page content for the Test Options submenu
function wpgw_options_page() {
  // Read in existing option value from database
  $widget_title = get_option('wpgw_widget_title');
  $user_id = get_option('wpgw_user_id' );
  $type = get_option('wpgw_type' );

  // See if the user has posted us some information
  // If they did, this hidden field will be set to 'Y'
  if (isset($_POST['is_submit'])) {
    $widget_title = $_POST['wpgw_widget_title'];
    $user_id = $_POST['wpgw_user_id'];
    $type = $_POST['wpgw_type'];
    update_option('wpgw_widget_title', $widget_title);
    update_option('wpgw_user_id', $user_id);
    update_option('wpgw_type', $type);
  }
?>

  <input type="hidden" name="is_submit" value="true">
  <p><?php _e("WP Gistlog Widget Widget Title", 'mt_trans_domain' ); ?> 
    <input type="text" name="wpgw_widget_title" value="<?php echo $widget_title; ?>" size="40">
  </p>
  <p><?php _e("WP Gistlog User Id:", 'mt_trans_domain' ); ?> 
    <input type="text" name="wpgw_user_id" value="<?php echo $user_id; ?>" size="40">
  </p>
  <p><?php _e("WP Gistlog Widget Category:", 'mt_trans_domain' ); ?> 
    <select name="wpgw_type">
      <option value="recent">recent</options>
    </select>
  </p>
<?php
}

function get_widget_url() {
  $user_id = get_option('wpgw_user_id'); 
  $type = get_option('wpgw_type');
  return "http://www.gistlog.org/{$user_id}/widget.js?type={$type}";
}
function wpgw_show_widget($args) {
  $widget_title = get_option('wpgw_widget_title'); 

  if (empty($widget_title)) {
    $widget_title = 'Gistlog.org';
    update_option('wpgw_widget_title', $widget_title);
  }

  echo $args['before_title'] . $widget_title . $args['after_title'] . $args['before_widget'];

  $widget_url = get_widget_url();
?>
  <script type="text/javascript" src="<?php echo $widget_url ?>"></script>
  <?php
  echo $args['after_widget'];
}

function wpgw_init_widget() {
  register_sidebar_widget('WP Gistlog Widget', 'wpgw_show_widget');
  register_widget_control('WP Gistlog Widget', 'wpgw_options_page', 250, 200 );
}
add_action("plugins_loaded", "wpgw_init_widget");

?>
