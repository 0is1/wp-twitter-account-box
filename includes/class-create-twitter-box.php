<?php
/**
* Create Twitter Account Box.
*
* @package        Twitter Account Box
* @author         Janne Saarela <janne@jannejuhani.net>
* @license        GPL-2.0+
* @link           http://tab.jannejuhani.net
* @copyright      2014 Janne Saarela
*/

/**
 * Creates the Twitter Account Box content.
 *
 * @since 0.1.0
 */
if ( ! class_exists( 'CreateTwitterAccountBox' ) ) {
  class CreateTwitterAccountBox {
    /**
     * Instance of this class.
     * @since    0.1.0
     * @var      object
     */
    protected static $instance = null;

    /**
     * Data from Twitter
     * @since    0.1.0
     * @var      array
     */
    protected static $twitter_data;
      /**
     * TabGetTwitterData class instance
     * @since    0.1.0
     * @var      object
     */
    protected static $tabGetTwitterData = null;

    private function __construct() {
      $plugin = TwitterAccountBox::get_instance();
      $this->plugin_slug = $plugin->get_plugin_slug();
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
   * Return the TAB content
   * @since    0.1.0
   * @return   string
   */
  public function get_tab_content() {
    if (!get_transient('twitteraccountbox_transient') || null === self::$tabGetTwitterData) {
      $this->create_tab_content();
    }
    $return = $this->createContent();
    return $return;
  }
  /**
   * Create the TAB content
   * @since    0.1.0
   * @return   boolean
   */
  public function create_tab_content() {
    require_once( TAB__PLUGIN_DIR . 'includes/class-tab-get-twitter-data.php');
    self::$tabGetTwitterData = TabGetTwitterData::get_instance();
  }
  public function createContent(){
    $data = get_transient('twitteraccountbox_transient');
    if (self::$tabGetTwitterData->check_error()):
      return "<p class='twitteraccountbox-error'>". self::$tabGetTwitterData->get_error_message() . "</p>";

    elseif (isset($data['errors'])):
      return "<p class='twitteraccountbox-error'>". $data['errors'][0]['message'] . "</p>";

    elseif (gettype($data) !== 'NULL' && !self::$tabGetTwitterData->check_error()):

      // Get user Twitter profile image
      isset($data[0]["user"]["profile_image_url"]) ? self::$twitter_data['twitter_user_image'] = $data[0]["user"]["profile_image_url"] : self::$twitter_data['twitter_user_image'] = TAB__PLUGIN_URL . 'public/images/empty_image.png';

      isset($data[0]["user"]["profile_banner_url"]) ? self::$twitter_data['twitter_profile_banner_url'] = $data[0]["user"]["profile_banner_url"] . '/web' : self::$twitter_data['twitter_profile_banner_url'] = TAB__PLUGIN_URL . 'public/images/empty_header.png';

      isset($data[0]["user"]["name"]) ? self::$twitter_data['twitter_user_real_name'] = $data[0]["user"]["name"] : self::$twitter_data['twitter_user_real_name'] = self::$tabGetTwitterData->get_twitter_username();

      isset($data[0]["user"]["location"]) ? self::$twitter_data['twitter_user_location'] = $data[0]["user"]["location"] : self::$twitter_data['twitter_user_location'] = "";

      isset($data[0]["user"]["description"]) ? self::$twitter_data['twitter_user_description'] = $data[0]["user"]["description"] : self::$twitter_data['twitter_user_description'] = "";

      if(isset($data[0]["user"]["entities"]["url"]["urls"][0]["expanded_url"])) {
        self::$twitter_data['twitter_user_url'] = $data[0]["user"]["entities"]["url"]["urls"][0]["expanded_url"];
      } else if (isset($data[0]["user"]["url"])){
        self::$twitter_data['twitter_user_url'] = $data[0]["user"]["url"];
      } else self::$twitter_data['twitter_user_url'] = "";

      isset($data[0]["user"]["followers_count"]) ? self::$twitter_data['twitter_user_followers_count'] = $data[0]["user"]["followers_count"] : self::$twitter_data['twitter_user_followers_count'] = "?";

      isset($data[0]["user"]["friends_count"]) ? self::$twitter_data['twitter_user_friends_count'] = $data[0]["user"]["friends_count"] : self::$twitter_data['twitter_user_friends_count'] = "?";

      isset($data[0]["user"]["statuses_count"]) ? self::$twitter_data['twitter_user_statuses_count'] = $data[0]["user"]["statuses_count"] : self::$twitter_data['twitter_user_statuses_count'] = "?";

      isset($data[0]["user"]["screen_name"]) ? self::$twitter_data['twitter_user_nick'] = $data[0]["user"]["screen_name"] : self::$twitter_data['twitter_user_nick'] = self::$tabGetTwitterData->get_twitter_username();

      self::$twitter_data['twitter_user_profile_link'] = TAB__TWITTER_BASE_URL . self::$twitter_data['twitter_user_nick']; ?>
      <section class="in-twitter header">
        <figure class="newsfeed-icon twitter-logo">
          <i class="icon-twitter-bird"></i>
        </figure>
        <p><?php echo self::$twitter_data['twitter_user_real_name'];?><?php _e(' – on Twitter', $this->plugin_slug); ?></p>
      </section>
      <section class="twitter-user-details" style="background-image: url(<?php echo self::$twitter_data['twitter_profile_banner_url'];?>);">
        <figure class="newsfeed-icon-img twitteraccountbox-img clearfix">
        <img src="<?php echo self::$twitter_data['twitter_user_image'];?>" alt="<?php echo self::$twitter_data['twitter_user_real_name'];?>" title="<?php echo self::$twitter_data['twitter_user_real_name'];?>">
        </figure>
        <h1><?php echo self::$twitter_data['twitter_user_real_name']; ?></h1>
        <a href="<?php echo self::$twitter_data['twitter_user_profile_link']; ?>" title="<?php echo self::$twitter_data['twitter_user_nick'];?>@Twitter">@<?php echo self::$twitter_data['twitter_user_nick'];?>
        </a>
        <p class="twitter-user-description"><?php echo self::$twitter_data['twitter_user_description'];?></p>
        <p class="twitter-user-location"><?php echo self::$twitter_data['twitter_user_location'];?> – <a href="<?php echo self::$twitter_data['twitter_user_url'];?>" title="<?php echo self::$twitter_data['twitter_user_url'];?>"><?php echo self::$twitter_data['twitter_user_url']; ?></a></p>
      </section>
      <section class="twitteraccountbox-details">
        <ul>
          <li>
            <a href="<?php echo self::$twitter_data['twitter_user_profile_link']; ?>" title="@<?php echo self::$twitter_data['twitter_user_nick'];?><?php _e('– Tweets',$this->plugin_slug);?>">
              <strong><?php echo self::$twitter_data['twitter_user_statuses_count'];?></strong><span><?php _e('Tweets', $this->plugin_slug); ?></span>
            </a>
          </li>
          <li>
            <a href="<?php echo self::$twitter_data['twitter_user_profile_link'];?>/following" title="@<?php echo self::$twitter_data['twitter_user_nick'];?><?php _e('– Following',$this->plugin_slug);?>">
              <strong><?php echo self::$twitter_data['twitter_user_friends_count'];?></strong><span><?php _e('Following', $this->plugin_slug); ?></span>
            </a>
          </li>
          <li>
            <a href="<?php echo self::$twitter_data['twitter_user_profile_link'];?>/followers" title="@<?php echo self::$twitter_data['twitter_user_nick'];?><?php _e('– Followers',$this->plugin_slug);?>">
              <strong><?php echo self::$twitter_data['twitter_user_followers_count'];?></strong><span><?php _e('Followers', $this->plugin_slug); ?></span>
            </a>
          </li>
        </ul>
        <span class="follow-button">
          <a href="<?php echo self::$twitter_data['twitter_user_profile_link'];?>" class="twitter-follow-button" data-show-count="false" data-lang="<?php echo TAB__SITE_LOCALE;?>" data-size="large" data-show-screen-name="false"><?php _e('Follow @', $this->plugin_slug); ?><?php echo self::$twitter_data['twitter_user_nick'];?></a>
        </span>
      </section>
    <?php else : // If Twitter data isn't available ?>
      <div class="twitteraccountbox fleft pure-u">
        <section class="twitter-feed-unavailable">
          <figure class="newsfeed-icon twitter-logo">
            <i class="icon-twitter-bird"></i>
          </figure>
          <p><?php _e("There's problems to retrieve data from Twitter at the moment...", $this->plugin_slug); ?></p>
        </section>
      </div>
    <?php endif;
    }
  }
}
?>