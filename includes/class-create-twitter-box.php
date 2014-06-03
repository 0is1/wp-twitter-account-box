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
 * @since 0.0.1
 */
if ( ! class_exists( 'CreateTwitterAccountBox' ) ) {
  class CreateTwitterAccountBox {
    /**
     * Instance of this class.
     * @since    0.0.1
     * @var      object
     */
    protected static $instance = null;

    private function __construct() {
      $plugin = TwitterAccountBox::get_instance();
      $this->plugin_slug = $plugin->get_plugin_slug();
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
   /**
   * Return the TAB content
   * @since    0.0.1
   * @return   string
   */
  public function get_tab_content() {
    if (!get_transient('twitteraccountbox_transient')) {
      $this->create_tab_content();
    }
    $d = get_transient('twitteraccountbox_transient');
    $return = '<div class="twitter-page fleft pure-u">
    <section class="in-twitter header">
      <p>'.$d[0]["user"]["name"].'</p>
    </section></div>';
    return $return;
  }
  /**
   * Create the TAB content
   * @since    0.0.1
   * @return   boolean
   */
  public function create_tab_content() {
    require_once( TAB__PLUGIN_DIR . 'includes/class-tab-get-twitter-data.php');
    $tabGetTwitterData = TabGetTwitterData::get_instance();
    $tabGetTwitterData::init();
  }
  private function createContent(){
    // TODO: Clear this mess
    $data = get_transient('twitteraccountbox_transient');
    if (gettype($data) !== 'NULL') :

      // Get user Twitter profile image
      if(isset($data[0]["user"]["profile_image_url"])) $twitter_user_image = $data[0]["user"]["profile_image_url"];
      // Get user Twitter profile banner base url
      if(isset($data[0]["user"]["profile_banner_url"])) $twitter_profile_banner_url = $data[0]["user"]["profile_banner_url"];

      if(isset($data[0]["user"]["name"])) $twitter_user_real_name = $data[0]["user"]["name"];
      if(isset($data[0]["user"]["location"])) $twitter_user_location = $data[0]["user"]["location"];
      if(isset($data[0]["user"]["description"])) $twitter_user_description = $data[0]["user"]["description"];
      if(isset($data[0]["user"]["entities"]["url"]["urls"][0]["expanded_url"])) {
        $twitter_user_url = $data[0]["user"]["entities"]["url"]["urls"][0]["expanded_url"];
      } else if (isset($data[0]["user"]["url"])){
        $twitter_user_url = $data[0]["user"]["url"];
      }
      if(isset($data[0]["user"]["followers_count"])) $twitter_user_followers_count = $data[0]["user"]["followers_count"];
      if(isset($data[0]["user"]["friends_count"])) $twitter_user_friends_count = $data[0]["user"]["friends_count"];
      if(isset($data[0]["user"]["statuses_count"])) $twitter_user_statuses_count = $data[0]["user"]["statuses_count"];
      isset($data[0]["user"]["screen_name"]) ? $twitter_user_nick = $data[0]["user"]["screen_name"] : $twitter_user_nick = $theme_settings['twitter_username'];
      $twitter_user_profile_link = "https://twitter.com/" . $twitter_user_nick; ?>

    <div class="twitter-page fleft pure-u">
      <section class="in-twitter header">
        <figure class="newsfeed-icon twitter-logo">
          <i class="icon-twitter-bird"></i>
        </figure>
        <p><?php echo $twitter_user_real_name;?><?php _e(' – Twitterissä', $this->plugin_slug); ?></p>
      </section>
      <section class="twitter-user-details" style="background-image: url(<?php echo $twitter_profile_banner_url;?>/web);">
        <figure class="newsfeed-icon-img twitter-page-img clearfix">
        <img src="<?php echo $twitter_user_image;?>" alt="<?php echo $twitter_user_real_name;?>" title="<?php echo $twitter_user_real_name;?>">
        </figure>
        <h1><?php echo $twitter_user_real_name; ?></h1>
        <a href="<?php echo $twitter_user_profile_link; ?>" title="<?php echo $twitter_user_nick;?>@Twitter">@<?php echo $twitter_user_nick;?>
        </a>
        <p class="twitter-user-description"><?php echo $twitter_user_description;?></p>
        <p class="twitter-user-location"><?php echo $twitter_user_location;?> – <a href="<?php echo $twitter_user_url;?>" title="<?php echo $twitter_user_url;?>"><?php echo $twitter_user_url; ?></a></p>
      </section>
      <section class="twitter-page-details">
        <ul>
          <li>
            <a href="<?php echo $twitter_user_profile_link; ?>" title="@<?php echo $twitter_user_nick;?> – <?php _e('Twiittiä',$this->plugin_slug);?>">
              <strong><?php echo $twitter_user_statuses_count;?></strong><span><?php _e('Twiittiä', $this->plugin_slug) ?></span>
            </a>
          </li>
          <li>
            <a href="<?php echo $twitter_user_profile_link;?>/following" title="@<?php echo $twitter_user_nick;?> – <?php _e('Seurattua',$this->plugin_slug);?>">
              <strong><?php echo $twitter_user_friends_count;?></strong><span><?php _e('Seurattua', $this->plugin_slug) ?></span>
            </a>
          </li>
          <li>
            <a href="<?php echo $twitter_user_profile_link;?>/followers" title="@<?php echo $twitter_user_nick;?> – <?php _e('Seuraajaa',$this->plugin_slug);?>">
              <strong><?php echo $twitter_user_followers_count;?></strong><span><?php _e('Seuraajaa', $this->plugin_slug) ?></span>
            </a>
          </li>
        </ul>
        <span class="follow-button">
          <a href="<?php echo $twitter_user_profile_link;?>" class="twitter-follow-button" data-show-count="false" data-lang="fi" data-size="large" data-show-screen-name="false">Seuraa @<?php echo $twitter_user_nick;?></a>
        </span>
      </section>
    </div>
    <?php else : // If Twitter data isn't available ?>
      <div class="twitter-page fleft pure-u">
        <section class="twitter-feed-unavailable">
          <figure class="newsfeed-icon twitter-logo">
            <i class="icon-twitter-bird"></i>
          </figure>
          <p><?php _e('Twiittien haussa on tällä hetkellä ongelmia...', $this->plugin_slug); ?></p>
        </section>
      </div>
    <?php endif;
    }
  }
}
?>