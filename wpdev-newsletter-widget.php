<?php
/*
Plugin Name: WPDev Newsletter Widget
Plugin URI: http://wpdevelopers.com/
Description: Displays the WPDev Newsletter Signup Form.
Version: 1.0
Author: Billy Engler
License: GPL2
*/

/**
Plugin Updates
**/
require 'plugin-update-checker/plugin-update-checker.php';
$myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
    'https://github.com/LibertyAllianceGit/wpdev-newsletter-signup',
    __FILE__,
    'wpdev-newsletter-signup'
);

/**
Start Widget Extension
**/
class wpdev_newsletter_widget extends WP_Widget {
  // Create widget
  function wpdev_newsletter_widget() {
    $widget_ops = array('classname' => 'wpdev_newsletter_widget', 'description' => 'Displays WPDev Newsletter Signup Form' );
    $this->WP_Widget('wpdev_newsletter_widget', 'WP Developers Newsletter Widget', $widget_ops);
  }

  // Setup widget options
  function form($instance) {
    $instance = wp_parse_args( (array) $instance, array( 'first' => '', 'second' => '', 'third' => '', 'formid' => '', 'buttontxt' => '', ) );

    $first   = $instance['first'];
    $second  = $instance['second'];
    $third   = $instance['third'];
    $formid  = $instance['formid'];
    $buttontxt  = $instance['buttontxt'];

    // Start widget HTML
?>
    <p><label for="<?php echo $this->get_field_id('first'); ?>">First Header: <input class="widefat" id="<?php echo $this->get_field_id('first'); ?>" name="<?php echo $this->get_field_name('first'); ?>" type="text" value="<?php echo attribute_escape($first); ?>" /></label></p>

    <p><label for="<?php echo $this->get_field_id('second'); ?>">Second Header: <input class="widefat" id="<?php echo $this->get_field_id('second'); ?>" name="<?php echo $this->get_field_name('second'); ?>" type="text" value="<?php echo attribute_escape($second); ?>" /></label></p>

    <p><label for="<?php echo $this->get_field_id('third'); ?>">Paragraph Text: <input class="widefat" id="<?php echo $this->get_field_id('third'); ?>" name="<?php echo $this->get_field_name('third'); ?>" type="text" value="<?php echo attribute_escape($third); ?>" /></label></p>

    <p><label for="<?php echo $this->get_field_id('formid'); ?>">FormID (must have for widget to appear): <input class="widefat" id="<?php echo $this->get_field_id('formid'); ?>" name="<?php echo $this->get_field_name('formid'); ?>" type="text" value="<?php echo attribute_escape($formid); ?>" /></label></p>

    <p><label for="<?php echo $this->get_field_id('buttontxt'); ?>">Button Text: <input class="widefat" id="<?php echo $this->get_field_id('buttontxt'); ?>" name="<?php echo $this->get_field_name('buttontxt'); ?>" type="text" value="<?php echo attribute_escape($buttontxt); ?>" /></label></p>

<?php }

  // Save widget fields
  function update($new_instance, $old_instance) {
    $instance = $old_instance;

    // Retrieve Fields
    $instance['first'] = strip_tags($new_instance['first']);
    $instance['second'] = strip_tags($new_instance['second']);
    $instance['third'] = strip_tags($new_instance['third']);
    $instance['formid'] = strip_tags($new_instance['formid']);
    $instance['buttontxt'] = strip_tags($new_instance['buttontxt']);

    return $instance;
  }

  // Output widget on front end
  function widget($args, $instance) {
    extract($args, EXTR_SKIP);

    echo $before_widget;
    $first   = empty($instance['first']) ? ' ' : apply_filters('widget_title', $instance['first']);
    $second  = $instance['second'];
    $third   = $instance['third'];
    $formid  = $instance['formid'];
    $buttontxt  = $instance['buttontxt'];

    if (!empty($formid))
       echo '
        <div class="wpdev-signupbox">
        	<h2 class="wpdev-email-header-1">'.$first.'</h2>
        	<h2 class="wpdev-email-header-2">'.$second.'</h2>
        	<p class="wpdev-signup-p">'.$third.'</p>
        	<form accept-charset="UTF-8" action="https://if.inboxfirst.com/ga/front/forms/'.$formid.'/subscriptions/" class="simple_form embedded-ga-subscription-form wpdevform" id="new_pending_subscriber" method="post" novalidate="novalidate" style="" _lpchecked="1">
        		<input name="utf8" type="hidden" value="✓">
        		<div class="mc-field-group wpdev-field-group">
        			<input class="required email wpdevemailfield" id="pending_subscriber_email" name="pending_subscriber[email]" size="50" placeholder="Email address..." type="text">
        			<button class="button wpdevbutton" type="submit" id="mc-embedded-subscribe">'.$buttontxt.'</button>
        		</div>
        	</form>
        </div>
       ';

    echo $after_widget;
  }
}
add_action( 'widgets_init', create_function('', 'return register_widget("wpdev_newsletter_widget");') );

/**
Enqueue Scripts
**/
function wpdev_newsletter_scripts() {
    wp_enqueue_style( "wpdev-newsletter-css", plugin_dir_url(__FILE__) . "wpdev-styles.css"));
}
add_action('wp_enqueue_scripts', 'wpdev_newsletter_scripts', 1);
