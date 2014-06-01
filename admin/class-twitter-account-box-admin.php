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
     * @since    0.0.1
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


    private function __construct() {

      $plugin = TwitterAccountBox::get_instance();
      $this->plugin_slug = $plugin->get_plugin_slug();

      add_action( 'admin_init', array( $this, 'twitteraccountbox_register_settings'));
      add_action( 'admin_menu', array( $this, 'admin_menu' ) );
      add_action('admin_enqueue_scripts', array( $this, 'add_css' ));
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
     * Register options and validation callbacks
     *
     * @uses register_setting
     */
    public function twitteraccountbox_register_settings() {
       register_setting( 'twitteraccountbox_options', 'twitteraccountbox_consumer_key', array( $this,'twitteraccountbox_options_validate'));
       register_setting( 'twitteraccountbox_options', 'twitteraccountbox_consumer_secret', array( $this,'twitteraccountbox_options_validate'));
       register_setting( 'twitteraccountbox_options', 'twitteraccountbox_oauth_access_token', array( $this,'twitteraccountbox_options_validate'));
       register_setting( 'twitteraccountbox_options', 'twitteraccountbox_oauth_token_secret', array( $this,'twitteraccountbox_options_validate'));
       register_setting( 'twitteraccountbox_options', 'twitteraccountbox_twitter_username', array( $this,'twitteraccountbox_options_validate'));
    }
    public function twitteraccountbox_options_validate($options){
      // TODO
      $input_validated = $options;
      return $input_validated;
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
      wp_enqueue_style( 'twitteraccountbox_style', TAB__PLUGIN_URL . 'admin/assets/styles/twitteraccountbox.css', array(), TAB_VERSION, false);
    }

    public function display_page() {
      if ( !current_user_can( 'manage_options' ) )  {
        wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
      }
      ?>
      <div class="twitteraccountbox-wrap">
        <form class="twitteraccountbox-options pure-form pure-form-aligned" method="post" action="options.php">
        <?php
          settings_fields('twitteraccountbox_options');
          do_settings_sections('twitteraccountbox_options');
        ?>
          <div class="twitter-app-settings">
            <h3><?php _e( 'Twitter-sovelluksen asetukset', 'twitteraccountbox' );?></h3>
            <div class="wrap pure-control-group">
              <label for="twitteraccountbox_consumer_key"><?php _e( 'Twitter Consumer Key:', $this->plugin_slug );?></label>
              <input type="text" name="twitteraccountbox_consumer_key" value="<?php echo get_option('twitteraccountbox_consumer_key');?>"  />
            </div>
            <div class="wrap pure-control-group">
              <label for="twitteraccountbox_consumer_secret"><?php _e( 'Twitter Consumer Secret:', $this->plugin_slug );?></label>
              <input type="text" name="twitteraccountbox_consumer_secret" value="<?php echo get_option('twitteraccountbox_consumer_secret');?>"  />
            </div>
            <div class="wrap pure-control-group">
              <label for="twitteraccountbox_oauth_access_token"><?php _e( 'Twitter OAuth Access Token:', $this->plugin_slug );?></label>
              <input type="text" name="twitteraccountbox_oauth_access_token" value="<?php echo get_option('twitteraccountbox_oauth_access_token');?>"/>
            </div>
            <div class="wrap pure-control-group">
              <label for="twitteraccountbox_oauth_token_secret"><?php _e( 'Twitter OAuth Access Token Secret:', $this->plugin_slug );?></label>
              <input type="text" name="twitteraccountbox_oauth_token_secret" value="<?php echo get_option('twitteraccountbox_oauth_token_secret');?>"/>
            </div>
            <div class="wrap pure-control-group">
              <label for="twitteraccountbox_twitter_username"><?php _e( 'Twitter käyttäjätunnus:', $this->plugin_slug );?></label>
              <input type="text" name="twitteraccountbox_twitter_username" value="<?php echo get_option('twitteraccountbox_twitter_username');?>" />
            </div>
          </div>
          <?php submit_button();?>
        </form>
    </div>
    <?php
    }
  }
}
?>