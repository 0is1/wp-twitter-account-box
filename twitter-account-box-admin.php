<?php
// Exit if accessed directly
if ( !defined('ABSPATH')) exit;

/**
* @file           twitter-account-box-admin.php
* @package        Twitter Account Box
* @author         Janne Saarela
*/

class TwitterAccountBoxAdmin {

  private static $initiated = false;

  public static function init() {
    if ( ! self::$initiated ) {
      self::init_hooks();
    }
  }
  public static function init_hooks() {
    self::$initiated = true;
    add_action( 'admin_menu', array( 'TwitterAccountBoxAdmin', 'admin_menu' ) );
  }

  public static function admin_menu() {
   add_options_page( __('Twitter Account Box Options','tab'), __('Twitter Account Box','tab'), 'manage_options', 'twitter-account-box-admin', array( 'TwitterAccountBoxAdmin', 'display_page' ) );
  }

  public static function display_page() {
    if ( !current_user_can( 'manage_options' ) )  {
      wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
    }
    echo '<div class="wrap">';
    echo '<p>Hello Wordpress with version: '. $GLOBALS['wp_version'] .'.</p>';
    echo '</div>';
  }
}
?>