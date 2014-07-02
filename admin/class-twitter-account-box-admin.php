<?php

/**
*
* @package        Twitter Account Box
* @author         Janne Saarela <janne@jannejuhani.net>
* @license        GPL-2.0+
* @link           http://tab.jannejuhani.net
* @copyright      2014 Janne Saarela
*/
if ( ! class_exists( 'TwitterAccountBoxAdmin' ) ) {
  class TwitterAccountBoxAdmin {

    /**
     * Instance of this class.
     * @since    0.1.0
     * @var      object
     */
    protected static $instance = null;
    /**
     * Slug of the plugin screen.
     *
     * @since    1.0.0
     *
     * @var      string
     */
    protected $plugin_screen_hook_suffix = null;

    /**
     * Slug of the plugin screen.
     *
     * @since    1.0.0
     *
     * @var      mixed
     */
    protected static $twitteraccountbox_options = null;


    private function __construct() {

      $plugin = TwitterAccountBox::get_instance();
      $this->plugin_slug = $plugin->get_plugin_slug();

      add_action( 'admin_init', array( $this, 'twitteraccountbox_register_settings'));
      add_action( 'admin_menu', array( $this, 'admin_menu' ) );
      add_action('admin_enqueue_scripts', array( $this, 'add_css' ));
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
     * Register options and validation callbacks
     *
     * @uses register_setting
     */
    public function twitteraccountbox_register_settings() {
      register_setting( 'twitteraccountbox_options', 'twitteraccountbox_options', array( $this,'twitteraccountbox_options_validate'));
    }

    /**
     * Return valitaded input data.
     * @since     0.1.0
     * @return    array
     */
    public function twitteraccountbox_options_validate($options){

      // TODO
      $input_validated = array();

      isset( $options['consumer_key'] ) ? $input_validated['consumer_key'] = trim($options['consumer_key']) : $options['consumer_key'] = "";

      isset( $options['consumer_secret'] ) ? $input_validated['consumer_secret'] = trim($options['consumer_secret']) : $options['consumer_secret'] = "";

      isset( $options['oauth_access_token'] ) ? $input_validated['oauth_access_token'] = trim($options['oauth_access_token']) : $options['oauth_access_token'] = "";

      isset( $options['oauth_token_secret'] ) ? $input_validated['oauth_token_secret'] = trim($options['oauth_token_secret']) : $options['oauth_token_secret'] = "";

      isset( $options['twitter_username'] ) ? $input_validated['twitter_username'] = preg_replace('/\s+/', '', $options['twitter_username']) : $options['twitter_username'] = "";

      self::delete_tab_transients();
      return $input_validated;

    }
    /**
     * Delete Twitter Account Box transients.
     * @since     0.1.0
     */
    private static function delete_tab_transients(){
      delete_transient('twitteraccountbox_transient');
    }

    public function admin_menu() {
     $this->plugin_screen_hook_suffix = add_options_page(
      __( 'Twitter Account Box Options', $this->plugin_slug ),
      __( 'Twitter Account Box', $this->plugin_slug ),
      'manage_options',
      $this->plugin_slug,
      array( $this, 'display_page' )
    );
    }

    public function add_css(){
      wp_enqueue_style( $this->plugin_slug .'-admin-style', TAB__PLUGIN_URL . 'admin/assets/styles/twitteraccountbox.css', array(), TAB__VERSION, false);
      wp_enqueue_style($this->plugin_slug . 'admin-fontello', TAB__PLUGIN_URL . 'admin/vendor/fontello-tab-admin/css/twitter-account-box-admin.css', array(), TAB__VERSION, false);
    }

    private static function init_twitter_data(){
      $createTwitterAccountBox = CreateTwitterAccountBox::get_instance();
      $createTwitterAccountBox->create_tab_content();
    }

    private static function get_input_value($id){
      if(isset(self::$twitteraccountbox_options[$id])) return self::$twitteraccountbox_options[$id];
      else return "";
    }

    public function display_page() {
      if ( !current_user_can( 'manage_options' ) )  {
        wp_die( __( 'You do not have sufficient permissions to access this page.', $this->plugin_slug) );
      }
      if ( isset( $_GET['settings-updated'] ) ) {
        if ('true' === $_GET['settings-updated']){
          self::init_twitter_data();
        }
      }

      ?>
      <div class="twitteraccountbox-wrap">
        <form class="twitteraccountbox-options pure-form pure-form-aligned" method="post" action="options.php">
        <?php
          settings_fields('twitteraccountbox_options');
          do_settings_sections('twitteraccountbox_options');
          self::$twitteraccountbox_options = get_option('twitteraccountbox_options');
        ?>
          <div class="twitter-app-settings">
            <section class="tab-twitter-info">
              <p><a href="<?php echo TAB__TWITTER_DEV_SITE;?>"><?php _e('Get Twitter Developer-account', $this->plugin_slug); ?></a></p>
            </section>
            <h3><?php _e( 'Twitter options', 'twitteraccountbox' );?></h3>
            <div class="wrap pure-control-group">
              <label for="twitteraccountbox_options[consumer_key]"><?php _e( 'Twitter API Key:', $this->plugin_slug );?></label>
              <input type="text" name="twitteraccountbox_options[consumer_key]" value="<?php echo self::get_input_value('consumer_key');?>" required />
            </div>
            <div class="wrap pure-control-group">
              <label for="twitteraccountbox_options[consumer_secret]"><?php _e( 'Twitter API Secret:', $this->plugin_slug );?></label>
              <input type="text" name="twitteraccountbox_options[consumer_secret]" value="<?php echo self::get_input_value('consumer_secret');?>" required />
            </div>
            <div class="wrap pure-control-group">
              <label for="twitteraccountbox_options[oauth_access_token]"><?php _e( 'Access Token:', $this->plugin_slug );?></label>
              <input type="text" name="twitteraccountbox_options[oauth_access_token]" value="<?php echo self::get_input_value('oauth_access_token');?>" required/>
            </div>
            <div class="wrap pure-control-group">
              <label for="twitteraccountbox_options[auth_token_secret]"><?php _e( 'Access Token Secret:', $this->plugin_slug );?></label>
              <input type="text" name="twitteraccountbox_options[oauth_token_secret]" value="<?php echo self::get_input_value('oauth_token_secret');?>" required/>
            </div>
            <div class="wrap pure-control-group">
              <label for="twitteraccountbox_options[twitter_username]"><?php _e( 'Twitter username:', $this->plugin_slug );?></label>
              <input type="text" name="twitteraccountbox_options[twitter_username]" value="<?php echo self::get_input_value('twitter_username');?>" required />
            </div>
          </div>
          <?php submit_button();?>
        </form>
        <section class="developer-details">
          <h3><?php _e('Thanks for using Twitter Account Box -plugin!', $this->plugin_slug); ?></h3>
          <p>
            <?php _e('If you like it, you can also ', $this->plugin_slug); ?>
            <a href="<?php echo TAB__DEVELOPER_TWITTER_PAGE;?>" title="<?php echo TAB__DEVELOPER_TWITTER_NICK;?><?php _e('@Twitter', $this->plugin_slug); ?>">
              <?php _e('follow me on Twitter', $this->plugin_slug); ?>
              <i class="icon-twitter"></i>
            </a>
          </p>
          <p>
            <?php _e('If you have new features on your mind or spotted a bug, please ', $this->plugin_slug); ?>
            <a href="<?php echo TAB__GITHUB_PAGE;?>" title="<?php echo TAB__GITHUB_PAGE;?>">
              <?php _e('tell about it on GitHub', $this->plugin_slug); ?>
            </a>
          </p>
          <h3><?php _e('You can also buy me a beer!', $this->plugin_slug);?><i class="icon-emo-beer"></i></h3>
          <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
            <input type="hidden" name="cmd" value="_s-xclick">
            <input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----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-----END PKCS7-----
            ">
            <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
            <img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
          </form>
        </section>
    </div>
    <?php
    }
  }
}
?>