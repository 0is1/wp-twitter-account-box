<?php
// Exit if accessed directly
if ( !defined('ABSPATH')) exit;

/**
* @file           twitter-account-box.php
* @package        Twitter Account Box
* @author         Janne Saarela
*/

/*
Plugin Name: Twitter Account Box
Plugin URI: http://tab.jannejuhani.net
Description: This plugin adds your Twitter account details box in your Wordpress site.
Version: 0.0.1
Author: Janne Saarela
Author URI: http://www.jannejuhani.net/
License: GPL2
*/

/*  Copyright 2014 Janne Saarela  (email: janne@jannejuhani.net)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program. If not, see <http://www.gnu.org/licenses/>.
*/
?>
<?php
  define( 'TAB_VERSION', '0.0.1' );
  define( 'TAB__MINIMUM_WP_VERSION', '3.0' );
  define( 'TAB__PLUGIN_URL', plugin_dir_url( __FILE__ ) );
  define( 'TAB__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

  register_activation_hook( __FILE__, array( 'TwitterAccountBox', 'plugin_activation' ) );
  register_deactivation_hook( __FILE__, array( 'TwitterAccountBox', 'plugin_deactivation' ) );

  add_action( 'init', array( 'TwitterAccountBox', 'init' ) );
  if ( is_admin() ) {
    require_once( TAB__PLUGIN_DIR . 'twitter-account-box-admin.php' );
    add_action( 'init', array( 'TwitterAccountBoxAdmin', 'init' ) );
  }
?>

<?php
/**
 * Loads the Twitter Account Box plugin.
 *
 * @since 0.0.1
 */
class TwitterAccountBox {

  private static $initiated = false;

  public static function init() {
    if ( ! self::$initiated ) {
      self::init_hooks();
    }
  }
  /**
   * Initializes WordPress hooks
   */
  private static function init_hooks() {
    self::$initiated = true;
  }
  /**
   * Attached to activate_{ plugin_basename( __FILES__ ) } by register_activation_hook()
   * @static
   */
  public static function plugin_activation() {
    if ( version_compare( $GLOBALS['wp_version'], TAB__MINIMUM_WP_VERSION, '<' ) ) {
      var_dump("PLUGIN ACTIVATION FAILED – wp_version <");
    }
  }

  /**
   * Removes all connection options
   * @static
   */
  public static function plugin_deactivation( ) {
    //tidy up
  }
}
?>