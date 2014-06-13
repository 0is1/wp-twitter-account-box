<?php
/**
* Fired when the plugin is uninstalled.
*
* @package        Twitter Account Box
* @author         Janne Saarela <janne@jannejuhani.net>
* @license        GPL-2.0+
* @link           http://tab.jannejuhani.net
* @copyright      2014 Janne Saarela
*/

// If uninstall not called from WordPress, then exit
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
  exit;
}

delete_transient( 'twitteraccountbox_transient' );
delete_option('twitteraccountbox_options');