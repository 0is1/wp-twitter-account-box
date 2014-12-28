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
    private function process_tweet( $text ){
      // Method to add hyperlink html tags to any urls, twitter ids or hashtags in the tweet
      $text = preg_replace_callback('@(https?|ftp)://(-\.)?([^\s/?\.#-]+\.?)+(/[^\s]*)?$@iS',
       function ($matches) {
             return '<a href="' . $matches[0] . '">' . $matches[0] . '</a>';
         }, $text);

     $text = preg_replace_callback('/(^|[\n ])@([^0-9\s]\w+)/',
       function ($matches) {
             return '<a href="https://www.twitter.com/' . $matches[2] .'">@' . $matches[2] . '</a> ';
         }, $text);

     $text = preg_replace_callback('/#([^0-9]\w+)/',
       function ($matches) {
             return '<a href="https://twitter.com/hashtag/' . $matches[1] . '" >#' . $matches[1] . '</a>';
         }, $text);
    return $text;
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
    if ( ! get_transient('twitteraccountbox_transient') || null === self::$tabGetTwitterData) {
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
    require_once( TAB__PLUGIN_DIR . 'includes/class-tab-get-twitter-data.php' );
    self::$tabGetTwitterData = TabGetTwitterData::get_instance();
  }
  public function createContent(){
    $data = get_transient('twitteraccountbox_transient');
    $twitteraccountbox_data = get_option('twitteraccountbox_options');
    if ( self::$tabGetTwitterData->check_error() ):
      return "<p class='twitteraccountbox-error'>". self::$tabGetTwitterData->get_error_message() . "</p>";

    elseif ( isset( $data['errors'] ) ):
      return "<p class='twitteraccountbox-error'>". $data['errors'][0]['message'] . "</p>";

    elseif ( gettype($data) !== 'NULL' && !self::$tabGetTwitterData->check_error()):

      // Get user Twitter profile image
      isset( $data[0]["user"]["profile_image_url"] ) ? self::$twitter_data['twitter_user_image'] = $data[0]["user"]["profile_image_url"] : self::$twitter_data['twitter_user_image'] = TAB__PLUGIN_URL . 'public/images/empty_image.png';

      isset( $data[0]["user"]["profile_banner_url"] ) ? self::$twitter_data['twitter_profile_banner_url'] = $data[0]["user"]["profile_banner_url"] . '/web' : self::$twitter_data['twitter_profile_banner_url'] = TAB__PLUGIN_URL . 'public/images/empty_header.png';

      isset( $data[0]["user"]["name"] ) ? self::$twitter_data['twitter_user_real_name'] = $data[0]["user"]["name"] : self::$twitter_data['twitter_user_real_name'] = self::$tabGetTwitterData->get_twitter_username();

      isset( $data[0]["user"]["location"] ) ? self::$twitter_data['twitter_user_location'] = $data[0]["user"]["location"] : self::$twitter_data['twitter_user_location'] = "";

      isset( $data[0]["user"]["description"] ) ? self::$twitter_data['twitter_user_description'] = $data[0]["user"]["description"] : self::$twitter_data['twitter_user_description'] = "";

      if( isset( $data[0]["user"]["entities"]["url"]["urls"][0]["expanded_url"] ) ) {
        self::$twitter_data['twitter_user_url'] = $data[0]["user"]["entities"]["url"]["urls"][0]["expanded_url"];
      } else if ( isset( $data[0]["user"]["url"] ) ){
        self::$twitter_data['twitter_user_url'] = $data[0]["user"]["url"];
      } else self::$twitter_data['twitter_user_url'] = "";

      isset( $data[0]["user"]["followers_count"] ) ? self::$twitter_data['twitter_user_followers_count'] = $data[0]["user"]["followers_count"] : self::$twitter_data['twitter_user_followers_count'] = "?";

      isset( $data[0]["user"]["friends_count"] ) ? self::$twitter_data['twitter_user_friends_count'] = $data[0]["user"]["friends_count"] : self::$twitter_data['twitter_user_friends_count'] = "?";

      isset( $data[0]["user"]["statuses_count"] ) ? self::$twitter_data['twitter_user_statuses_count'] = $data[0]["user"]["statuses_count"] : self::$twitter_data['twitter_user_statuses_count'] = "?";

      isset( $data[0]["user"]["screen_name"] ) ? self::$twitter_data['twitter_user_nick'] = $data[0]["user"]["screen_name"] : self::$twitter_data['twitter_user_nick'] = self::$tabGetTwitterData->get_twitter_username();

      self::$twitter_data['twitter_user_profile_link'] = TAB__TWITTER_BASE_URL . self::$twitter_data['twitter_user_nick']; ?>
      <div id="twitteraccountbox">
        <section class="in-twitter header">
          <figure class="twitter-logo">
            <i class="tab-icon-twitter"></i>
          </figure>
          <p><?php echo self::$twitter_data['twitter_user_real_name'];?><?php _e(' – on Twitter', $this->plugin_slug); ?></p>
        </section>
        <section class="twitter-user-details" style="background-image: url(<?php echo self::$twitter_data['twitter_profile_banner_url'];?>);">
          <figure class="twitteraccountbox-img clearfix">
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
        <?php
          // Add tweets if the option is enabled
          if($twitteraccountbox_data['twitter_enable_tweets']):
        ?>
          <section class="twitteraccountbox-tweets">
            <?php
              foreach ($data as $key => $value) { ?>
                <?php
                // Retweet
                if($value['retweeted']): ?>
                  <div class="twitteraccountbox-tweet">
                    <span class="twitteraccountbox-tweet-by retweet">
                      <i class="tab-icon-retweet"></i>
                      <?php _e('Retweeded by', $this->plugin_slug);?>
                      <?php echo self::$twitter_data['twitter_user_real_name'];?>
                    </span>
                      <figure class="twitteraccountbox-img">
                        <img src="<?php echo $value['retweeted_status']['user']['profile_image_url']?>" title="@<?php echo $value['retweeted_status']['user']['screen_name'];?>" alt="@<?php echo $value['retweeted_status']['user']['screen_name'];?>">
                      </figure>
                    <span class="twitteraccountbox-tweet-retweet-by">
                      <a href="<?php echo TAB__TWITTER_BASE_URL . $value['retweeted_status']['user']['screen_name'];?>" title="@<?php echo $value['retweeted_status']['user']['screen_name'];?>">
                        <span class="twitteraccountbox-tweet-username"><?php echo $value['retweeted_status']['user']['name'];?></span>
                        <span>@<?php echo $value['retweeted_status']['user']['screen_name'];?></span>
                      </a>
                      <?php
                      $datetime = new DateTime($value['retweeted_status']['created_at']);
                      $datetime->setTimezone(new DateTimeZone('Europe/Helsinki'));
                      ?>
                      <span><?php echo $datetime->format('d.m.Y');?></span>
                    </span>
                      <p class="twitteraccountbox-tweet-text"><?php echo self::process_tweet($value['retweeted_status']['text']);?></p>
                    <?php
                      if( isset( $value['retweeted_status']['entities']['media'] ) ) :
                      // Add now only first image from media
                     ?>
                      <figure>
                        <a href="<?php echo $value['retweeted_status']['entities']['media'][0]['expanded_url']?>" title="<?php echo $value['retweeted_status']['text'];?>">
                          <img src="<?php echo $value['retweeted_status']['entities']['media'][0]['media_url']?>" alt="<?php _e('Image by ', $this->plugin_slug);?>@<?php echo $value['retweeted_status']['user']['screen_name'];?>">
                        </a>
                      </figure>
                    <?php endif; //if($value['retweeted_status']['entities']['media'] ) ?>
                  </div>
                  <?php
                // Normal tweet
                else :
                ?>
                <div class="twitteraccountbox-tweet">
                  <figure class="twitteraccountbox-img">
                  <img src="<?php echo $value['user']['profile_image_url'];?>" alt="@<?php echo self::$twitter_data['twitter_user_nick'];?>" title="@<?php echo self::$twitter_data['twitter_user_nick'];?>">
                  </figure>
                  <span class="twitteraccountbox-tweet-by">
                    <a href="<?php echo TAB__TWITTER_BASE_URL . self::$twitter_data['twitter_user_nick'];?>" title="@<?php echo self::$twitter_data['twitter_user_nick'];?>">
                      <span class="twitteraccountbox-tweet-username"><?php echo self::$twitter_data['twitter_user_real_name'];?></span>
                      <span>@<?php echo self::$twitter_data['twitter_user_nick'];?></span>
                    </a>
                    <?php
                      $datetime = new DateTime($value['created_at']);
                      $datetime->setTimezone(new DateTimeZone('Europe/Helsinki'));
                     ?>
                    <span><?php echo $datetime->format('d.m.Y');?></span>
                  </span>
                  <p class="twitteraccountbox-tweet-text"><?php echo self::process_tweet($value['text']);?></p>
                  <?php
                    if( isset( $value['entities']['media'] ) ) :
                    // Add now only first image from media
                   ?>
                    <figure>
                      <a href="<?php echo $value['entities']['media'][0]['expanded_url']?>" title="<?php echo $value['text'];?>">
                        <img src="<?php echo $value['entities']['media'][0]['media_url']?>" alt="<?php _e('Image by ', $this->plugin_slug);?>@<?php echo self::$twitter_data['twitter_user_nick'];?>">
                      </a>
                    </figure>
                  <?php endif; //if($value['retweeted_status']['entities']['media'] ) ?>
                </div>
              <?php endif; //if($value['retweeted'] ) ?>
              <?php
              }
              ?>
          </section>
        </div>
        <?php endif; //if($twitteraccountbox_data['twitter_enable_tweets'])?>
    <?php else : // If Twitter data isn't available ?>
      <div class="twitteraccountbox fleft pure-u">
        <section class="twitter-feed-unavailable">
          <figure class="newsfeed-icon twitter-logo">
            <i class="icon-twitter"></i>
          </figure>
          <p><?php _e("There's problems to retrieve data from Twitter at the moment...", $this->plugin_slug); ?></p>
        </section>
      </div>
    <?php endif;
    }
  }
}
?>