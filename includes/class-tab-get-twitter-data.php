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
 * @since 0.1.0
 */
if ( ! class_exists( 'TabGetTwitterData' ) ) {
  class TabGetTwitterData {

    /**
     * Instance of this class.
     * @since    0.1.0
     * @var      object
     */
    protected static $instance = null;

    /**
     * Twitter options for authentication.
     * @since    0.1.0
     * @var      array
     */
    protected static $options;

    /**
     * Error info.
     * @since    0.1.0
     * @var      array
     */
    protected static $error = [];

    private function __construct() {
      self::init();
      self::create_twitteraccountbox_transient();
    }

    /**
     * Return an instance of this class.
     * @since     0.1.0
     * @return    object    A single instance of this class.
     */
    public static function get_instance() {
      // If the single instance hasn't been set, set it now.
      if ( null == self::$instance ) {
        self::$instance = new self;
      }
      return self::$instance;
    }

    /**
     * Return Twitter username.
     * @since     0.1.0
     * @return    string
     */
    public static function get_twitter_username() {
      return self::$options['twitter_username'];
    }
    /**
     * Return error status.
     * @since     0.1.0
     * @return    boolean
     */
    public static function check_error() {
      return self::$error['error'];
    }
    /**
     * Return error message.
     * @since     0.1.0
     * @return    string
     */
    public static function get_error_message() {
      return self::$error['error_message'];
    }
    /**
     * Set Twitter authentication variables
     * @since     0.1.0
     * @var       array
     */
    public static function init(){
      $data = get_option('twitteraccountbox_options');
      self::$options = array(
        'twitter_consumer_key' => $data['consumer_key'],
        'twitter_consumer_secret' => $data['consumer_secret'],
        'twitter_oauth_access_token' => $data['oauth_access_token'],
        'twitter_oauth_token_secret' => $data['oauth_token_secret'],
        'twitter_username' => $data['twitter_username'],
        'twitter_enable_tweets' => $data['twitter_enable_tweets'],
        'twitter_number_of_tweets' => $data['twitter_number_of_tweets']
        );
        self::$error = [
          'error_message' => '',
          'error' => false
          ];
    }

    private static function get_twitter_data(){
      self::init();
      require_once( TAB__PLUGIN_DIR . 'includes/vendor/twitter/TwitterAPIExchange.php');

      $settings = array(
        'oauth_access_token' => self::$options['twitter_oauth_access_token'],
        'oauth_access_token_secret' => self::$options['twitter_oauth_token_secret'],
        'consumer_key' => self::$options['twitter_consumer_key'],
        'consumer_secret' => self::$options['twitter_consumer_secret']
      );
      $url = TAB__TWITTER_API_URL;
      self::$options['twitter_enable_tweets'] ? $getfield = '?screen_name='.self::$options['twitter_username'].'&count='.self::$options['twitter_number_of_tweets'].'' : $getfield = '?screen_name='.self::$options['twitter_username'].'&count=1';
      $requestMethod = 'GET';

      try {
        $twitter = new TwitterAPIExchange($settings);
        $twitter_raw_data = $twitter->setGetfield($getfield)
                   ->buildOauth($url, $requestMethod)
                   ->performRequest();
        return json_decode($twitter_raw_data, true);
      } catch (Exception $e) {
        if ($e->getMessage() === "Make sure you are passing in the correct parameters" ) {

          self::$error = [
            'error_message' => 'Make sure you are passing in the correct parameters in Twitter Account Box settings-page',
            'error' => true
          ];
        } else{
          self::$error = [
            'error_message' => 'Something went wrong, please try again later...',
            'error' => true
          ];
        }
        error_log(date('j.n.Y H:i:s'). " : ", 3, TAB__PLUGIN_DIR .'/logs/twitteraccountbox-errors.log');
        error_log($e.PHP_EOL, 3, TAB__PLUGIN_DIR .'/logs/twitteraccountbox-errors.log');
        error_log("-----".PHP_EOL, 3, TAB__PLUGIN_DIR .'/logs/twitteraccountbox-errors.log');
      }
    }

    /**
     * Set Twitter Data Transient
     *
     * @uses set_transient
     */
    private static function create_twitteraccountbox_transient() {
      if(!get_transient('twitteraccountbox_transient') || get_transient('twitteraccountbox_transient') === "") {
        $twitteraccountbox_transient = self::get_twitter_data();
        if ($twitteraccountbox_transient) {
          // Set 15 min cache
          set_transient('twitteraccountbox_transient', $twitteraccountbox_transient, 900);
        }
      }
    }
  }
}
?>