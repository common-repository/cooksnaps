<?php

  /*  Copyright 2013 cooksnaps.com  (email : tech@cooksnaps.com)

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License, version 2, as
  published by the Free Software Foundation.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
  */

  /*
  Plugin Name: Cooksnaps
  Plugin URI: http://www.cooksnaps.com
  Description: Cooksnaps is a system to help recipe bloggers to receive photos when other people cook their recipes. Head over to the Cooksnaps admin page to get set up.
  Author: Cooksnaps
  Version: 2.0
  Author URI: http://www.cooksnaps.com
  License: GPLv2 or later
  Text Domain: cooksnaps
  */

  define("COOKSNAPS_META_ID", "cksnp_widg_show");
  define("COOKSNAPS_LANGUAGES", serialize(array("en" => "English", "es" => "Español", "fr" => "Français", "it" => "Italiano", "ja" => "日本語")));
  define("COOKSNAPS_OPTION_LOCALE", "cooksnaps_locale");
  define("COOKSNAPS_OPTION_KEY", "cooksnaps_key");

  function cooksnaps_add($content){
    global $post;
    if(is_single()) {
      if (!is_active_widget(false, false, 'cooksnaps_wpwidget')) {
        $cooksnaps_script = "<a class=\"cooksnaps_widget\" href=\"" . get_permalink($post->ID) . "\"><img src=\"http://cooksnaps.com/images/cooksnaps_mini_button.png\" alt=\"Cooksnaps\" /></a>";
        return $content . $cooksnaps_script;
      }
    }
    return $content;
  }

  function cooksnaps_add_js(){
    if(!is_page()) {
      echo "
            <script data-cfasync=\"false\" type=\"text/javascript\">
              var cs_widget_author_key = '" . get_option(COOKSNAPS_OPTION_KEY) . "';
              var cs_widget_locale = '" . get_option(COOKSNAPS_OPTION_LOCALE) . "';

              (function() {
                var cs = document.createElement('script'); cs.async = true; cs.type = 'text/javascript'; cs.setAttribute('data-cfasync', 'false');
                cs.src = 'http://cooksnaps.com/v1/widget.js?callback=?';
                (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(cs);
              })();
            </script>
      ";
    }
  }

  /* Add admin menu */
  function cooksnaps_custom_menu_page(){
    if (current_user_can('manage_options')) {
      add_menu_page('Cooksnaps options',
                     'Cooksnaps',
                     'manage_options',
                     'cooksnaps_options',
                     'cooksnaps_options_page',
                     plugins_url('img/cooksnaps-16.png', __FILE__), 26.1);
    }
  }

  function cooksnaps_options_page(){
    include('admin/cooksnaps_settings.php');
  }

  function cooksnaps_process_locale_option(){
    if (!current_user_can('manage_options')) {
      wp_die('You are not allowed to be on this page.');
    }

    // Check that nonce field
   check_admin_referer('cksnps_op_verify');

   if (isset($_POST['cooksnaps_locale'])) {
      update_option(COOKSNAPS_OPTION_LOCALE, sanitize_text_field($_POST['cooksnaps_locale']));
   }
   wp_redirect(admin_url('admin.php?page=cooksnaps_options'));
   exit;
  }

  function cooksnaps_process_key_option(){
    if (!current_user_can('manage_options')) {
      wp_die('You are not allowed to be on this page.');
    }

    // Check that nonce field
   check_admin_referer('cksnps_ky_verify');

   if (isset($_POST['cooksnaps_key'])) {
      update_option(COOKSNAPS_OPTION_KEY, sanitize_text_field($_POST['cooksnaps_key']));
   }
   wp_redirect(admin_url('admin.php?page=cooksnaps_options'));
   exit;
  }

  //activation / deactivation
  function cooksnaps_activate() {
    if (get_option(COOKSNAPS_OPTION_LOCALE) == '') {
      add_option(COOKSNAPS_OPTION_LOCALE, 'en', '', 'yes');
    }
    if (get_option(COOKSNAPS_OPTION_KEY) == '') {
      add_option(COOKSNAPS_OPTION_KEY, '', '', 'yes');
    }
  }

  function cooksnaps_deactivate() {
    delete_post_meta_by_key(COOKSNAPS_META_ID);
    delete_option(COOKSNAPS_OPTION_LOCALE);
    delete_option(COOKSNAPS_OPTION_KEY);

    // Clear the permalinks to remove our post type's rules
    flush_rewrite_rules();
  }

  add_action('the_content', 'cooksnaps_add');
  add_action('wp_head', 'cooksnaps_add_js');

  /* Add admin menu */
  add_action('admin_menu', 'cooksnaps_custom_menu_page');
  add_action('admin_post_cksnp_save_locale_option', 'cooksnaps_process_locale_option');
  add_action('admin_post_cksnp_save_key_option', 'cooksnaps_process_key_option');

  /*Internationalization*/
  load_plugin_textdomain('cooksnaps', false, basename(dirname(__FILE__)) . '/locales');

  //activation / deactivation
  register_activation_hook(__FILE__, 'cooksnaps_activate');
  register_deactivation_hook(__FILE__, 'cooksnaps_deactivate');

  //cooksnaps widget
  class Cooksnaps_Widget extends WP_Widget{

    function widget($args,$instance) {
      if (!is_single())
        return;
      global $post;

      echo $before_widget;
      echo $before_title;
?>
      <style>
        .cooksnaps_widget_container {
          text-transform: none;
        }
        .cooksnaps_widget_container ul.cooksnaps_widget_list > li {
          width: 49%;
        }
      </style>
      <a class="cooksnaps_widget" href="<?php echo get_permalink($post->ID) ?>"><img src="http://cooksnaps.com/images/cooksnaps_mini_button.png" alt="Cooksnaps" /></a>
<?php
      echo $after_title;
      echo $after_widget;
    }

    function Cooksnaps_Widget() {
      $widget_options = array(
        'classname'=>'cooksnaps_wpwidget',
        'description'=> __('Shows the cooksnaps widget for the current recipe page in the selected sidebar.')
      );
      $control_options = array(
        'width' =>250
      );
      $this->WP_Widget('cooksnaps_wpwidget','Cooksnaps Widget',$widget_options,$control_options);
    }
  }

  //cooksnaps gallery widget
  class Cooksnaps_Gallery_Widget extends WP_Widget{

    function widget($args,$instance) {
      if (!is_home())
        return;

      echo $before_widget;
      echo $before_title;
?>
      <div class="cooksnaps_gallery_widget"></div>
<?php
      echo $after_title;
      echo $after_widget;
    }

    function Cooksnaps_Gallery_Widget() {
      $widget_options = array(
        'classname'=>'cooksnaps_gallery_wpwidget',
        'description'=> __('Shows the cooksnaps gallery widget in the selected sidebar.')
      );
      $control_options = array(
        'width' =>250
      );
      $this->WP_Widget('cooksnaps_gallery_wpwidget','Cooksnaps Gallery Widget',$widget_options,$control_options);
    }
  }

  // Register and load the cooksnaps widget
  function cooksnaps_load_widget() {
    register_widget( 'Cooksnaps_Widget' );
  }
  add_action( 'widgets_init', 'cooksnaps_load_widget' );

  // Register and load the cooksnaps gallery widget
  function cooksnaps_gallery_load_widget() {
    register_widget( 'Cooksnaps_Gallery_Widget' );
  }
  add_action( 'widgets_init', 'cooksnaps_gallery_load_widget' );

  /*End of File*/
