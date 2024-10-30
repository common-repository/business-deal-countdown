<?php

/*
  Plugin Name: Business Deal Countdown
  Plugin URI: http://www.ninjapress.net/business-deal-countdown/
  Description: Create your time offer: let's hurry clients when they are going to buy
  Version: 1.3
  Author: Ninja Press
  Author URI: http://www.ninjapress.net
  License: GPL2
 * 
 */

if (!class_exists('WP_Business_deal_countdown')) {

   class WP_Business_deal_countdown {

      /**
       * Construct the plugin object
       */
      public function __construct() {
         add_action('add_meta_boxes', array($this, 'add_meta_box'));
         add_action('save_post', array($this, 'save_meta_box'));
      }
      
      /**
       * Adds the meta box container.
       */
      public function add_meta_box($post_type) {
         $post_types = array('post', 'page');     //limit meta box to certain post types
         if (in_array($post_type, $post_types)) {
            add_meta_box(
                    'bdc_render_box', 'Business Deal Countdown', array($this, 'render_meta_box_content'), $post_type, 'advanced', 'high'
            );
         }
      }

      /**
       * Save the meta when the post is saved.
       *
       * @param int $post_id The ID of the post being saved.
       */
      public function save_meta_box($post_id) {

         /*
          * We need to verify this came from the our screen and with proper authorization,
          * because save_post can be triggered at other times.
          */

         // Check if our nonce is set.
         if (!isset($_POST['myplugin_inner_custom_box_nonce']))
            return $post_id;

         $nonce = $_POST['myplugin_inner_custom_box_nonce'];

         // Verify that the nonce is valid.
         if (!wp_verify_nonce($nonce, 'myplugin_inner_custom_box'))
            return $post_id;

         // If this is an autosave, our form has not been submitted,
         //     so we don't want to do anything.
         if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
            return $post_id;

         // Check the user's permissions.
         if ('page' == $_POST['post_type']) {

            if (!current_user_can('edit_page', $post_id))
               return $post_id;
         } else {

            if (!current_user_can('edit_post', $post_id))
               return $post_id;
         }

         /* OK, its safe for us to save the data now. */

         // Sanitize the user input.
         $enable = sanitize_text_field($_POST['business_deal_countdown_enable']);
         $date = sanitize_text_field($_POST['business_deal_countdown_date']);
         $time = sanitize_text_field($_POST['business_deal_countdown_time']);
         $redirect = sanitize_text_field($_POST['business_deal_countdown_redirect']);

         // Update the meta field.
         update_post_meta($post_id, 'business_deal_countdown_enable', $enable != '' ? $enable : 'off˙');
         update_post_meta($post_id, 'business_deal_countdown_date', $date);
         update_post_meta($post_id, 'business_deal_countdown_time', $time);
         update_post_meta($post_id, 'business_deal_countdown_redirect', $redirect);
      }

      /**
       * Render Meta Box content.
       *
       * @param WP_Post $post The post object.
       */
      public function render_meta_box_content($post) {

         wp_enqueue_script(
                 '', plugins_url('js/admin.js', __FILE__), array('jquery', 'jquery-ui-core', 'jquery-ui-datepicker'), time(), true
         );

         wp_enqueue_style('jquery-ui', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css');

         //wp_enqueue_style('jquery-ui-datepicker');
         // Add an nonce field so we can check for it later.
         wp_nonce_field('myplugin_inner_custom_box', 'myplugin_inner_custom_box_nonce');

         $enable = get_post_meta($post->ID, 'business_deal_countdown_enable', true);
         $date = get_post_meta($post->ID, 'business_deal_countdown_date', true);
         $time = get_post_meta($post->ID, 'business_deal_countdown_time', true);
         $redirect = get_post_meta($post->ID, 'business_deal_countdown_redirect', true);

         // Render the settings template
         include(sprintf("%s/templates/meta_box.php", dirname(__FILE__)));
      }
   }
}

if (class_exists('WP_Business_deal_countdown')) {

   // instantiate the plugin class
   $wp_business_deal_countdown = new WP_Business_deal_countdown();

   if (isset($wp_business_deal_countdown)) {

      function plugin_redirect() {
         $enable = get_post_meta(get_the_ID(), 'business_deal_countdown_enable');

         if (isset($enable[0]) and $enable[0] == 'on') {
            $date = get_post_meta(get_the_ID(), 'business_deal_countdown_date');
            $time = get_post_meta(get_the_ID(), 'business_deal_countdown_time');
            $redirect = get_post_meta(get_the_ID(), 'business_deal_countdown_redirect');

            if (isset($redirect[0]) and $redirect[0] != '' and strtotime($date[0] . ' ' . $time[0]) < time()) {
               wp_redirect($redirect[0]);
            }
         }
      }

      function view_timer($atts) {
         $enable = get_post_meta(get_the_ID(), 'business_deal_countdown_enable');

         if ($enable[0] == 'on') {
            wp_enqueue_style('myPluginStylesheet');

            $date = get_post_meta(get_the_ID(), 'business_deal_countdown_date');
            $time = get_post_meta(get_the_ID(), 'business_deal_countdown_time');
            $redirect = get_post_meta(get_the_ID(), 'business_deal_countdown_redirect');
            
            ob_start();
            include(sprintf("%s/templates/countdown.php", dirname(__FILE__)));
            $string = ob_get_clean();

            return $string;
         } else {
            return NULL;
         }
      }

      add_filter('wp_head', 'plugin_redirect');
      add_shortcode('cbd_timer', 'view_timer');
   }
}   