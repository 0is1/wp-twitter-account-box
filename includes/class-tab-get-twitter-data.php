<?php
/**
* Get Twitter Data.
*
* @package        Twitter Account Box
* @author         Janne Saarela <janne@jannejuhani.net>
* @license        GPL-2.0+
* @link           http://tab.jannejuhani.net
* @copyright      2014 Janne Saarela
*/

/**
 * Get data from Twitter.
 *
 * @since 0.0.1
 */
if ( ! class_exists( 'TabGetTwitterData' ) ) {
  class TabGetTwitterData {
    /**
     * Instance of this class.
     * @since    0.0.1
     * @var      object
     */
    protected static $instance = null;

    private function __construct() {
      self::create_twitteraccountbox_transient();
    }

    /**
     * Return an instance of this class.
     * @since     0.0.1
     * @return    object    A single instance of this class.
     */
    public static function get_instance() {
      // If the single instance hasn't been set, set it now.
      if ( null == self::$instance ) {
        self::$instance = new self;
      }
      return self::$instance;
    }

    private static function get_twitter_data(){
      // require_once( TAB__PLUGIN_DIR . 'includes/vendor/twitter-api-php/TwitterAPIExchange.php');
      $all_data = get_option('twitteraccountbox_options');
      return $all_data;

      // $settings = array(
      //   'oauth_access_token' => $theme_settings['twitter_oauth_access_token'],
      //   'oauth_access_token_secret' => $theme_settings['twitter_oauth_access_token_secret'],
      //   'consumer_key' => $theme_settings['twitter_consumer_key'],
      //   'consumer_secret' => $theme_settings['twitter_consumer_secret']
      // );
      // $url = 'https://api.twitter.com/1.1/statuses/user_timeline.json';
      // $getfield = '?screen_name='.$theme_settings['twitter_username'].'&count='.$theme_settings['twitter_visible_posts_count'];
      // $requestMethod = 'GET';

      // try {
      //   $twitter = new TwitterAPIExchange($settings);
      //   $twitter_raw_data = $twitter->setGetfield($getfield)
      //              ->buildOauth($url, $requestMethod)
      //              ->performRequest();
      //   return json_decode($twitter_raw_data, true);
      // } catch (Exception $e) {
      //   error_log(date('j.n.Y H:i:s'). " : ", 3, get_stylesheet_directory() .'/logs/twitter-errors.log');
      //   error_log($e.PHP_EOL, 3, get_stylesheet_directory() .'/logs/twitter-errors.log');
      //   error_log("-----".PHP_EOL, 3, get_stylesheet_directory() .'/logs/twitter-errors.log');
      // }
    }

    /**
     * Set Twitter Data Transient
     *
     * @uses set_transient
     */
    private static function create_twitteraccountbox_transient() {
      if(!get_transient('twitteraccountbox_transient')) {
        $twitteraccountbox_transient = self::get_twitter_data();
        // Set 15 min cache
        set_transient('twitteraccountbox_transient', $twitteraccountbox_transient, 900);
      }
    }
  }
}
?>