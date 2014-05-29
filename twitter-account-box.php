<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
  die;
}

/**
* Twitter Account Box.
*
* @package        Twitter Account Box
* @author         Janne Saarela <janne@jannejuhani.net>
* @license        GPL-2.0+
* @link           http://tab.jannejuhani.net
* @copyright      2014 Janne Saarela
*/

/*
Plugin Name:  Twitter Account Box (TAB)
Plugin URI:   http://tab.jannejuhani.net
Description:  This plugin adds your Twitter account details box in your Wordpress site.
Version:      0.0.1
Author:       Janne Saarela
Author URI:   http://www.jannejuhani.net/
License:      GPL2
License URI:  http://www.gnu.org/licenses/gpl-2.0.txt
*/

/*  Copyright 2014 Janne Saarela

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
  define( 'TAB__ADMIN_PANEL_NAME', 'twitter-account-box-admin');

  require_once( TAB__PLUGIN_DIR . 'public/class-twitter-account-box.php' );
  require_once( TAB__PLUGIN_DIR . 'public/class-twitter-account-box-widget.php' );

  register_activation_hook( __FILE__, array( 'TwitterAccountBox', 'plugin_activation' ) );
  register_deactivation_hook( __FILE__, array( 'TwitterAccountBox', 'plugin_deactivation' ) );

  add_action( 'plugins_loaded', array( 'TwitterAccountBox', 'get_instance' ) );

  if ( is_admin() ) {
    require_once( TAB__PLUGIN_DIR . 'admin/class-twitter-account-box-admin.php' );
    add_action( 'plugins_loaded', array( 'TwitterAccountBoxAdmin', 'get_instance' ) );
  }

?>