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
     * @since    0.0.1
     * @var      boolean
     */
    private static $initiated = false;
    /**
     * @since    0.0.1
     * @var      string
     */
    // protected $plugin_slug = 'twitteraccountbox';

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
      // add_action( 'admin_menu', array('TwitterAccountBoxAdmin', 'admin_menu' ));
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
       // register_setting( 'twitteraccountbox_options', 'twitteraccountbox_options', array( $this,'twitteraccountbox_options_validate'));
       register_setting( 'twitteraccountbox_options', 'consumer_key', array( $this,'twitteraccountbox_options_validate'));
       register_setting( 'twitteraccountbox_options', 'consumer_secret', array( $this,'twitteraccountbox_options_validate'));
       register_setting( 'twitteraccountbox_options', 'oauth_access_token', array( $this,'twitteraccountbox_options_validate'));
       register_setting( 'twitteraccountbox_options', 'oauth_token_secret', array( $this,'twitteraccountbox_options_validate'));
       register_setting( 'twitteraccountbox_options', 'enable_twitteraccountbox', array( $this,'twitteraccountbox_options_validate_activation'));
       register_setting( 'twitteraccountbox_options', 'twitter_username', array( $this,'twitteraccountbox_options_validate'));
    }
    function twitteraccountbox_options_validate($options){
      $input_validated = $options;
      return $input_validated;
    }
    function twitteraccountbox_options_validate_activation($options){
      $input_validated = $options;
      isset($options) ? $input_validated = true : $input_validated = false;
      return $input_validated;
    }
    public function admin_menu() {
     // $this->plugin_screen_hook_suffix = add_options_page( __('Twitter Account Box Options', $this->plugin_slug), __('Twitter Account Box', $this->plugin_slug), 'manage_options', basename( __FILE__ ), array( 'TwitterAccountBoxAdmin', 'display_page' ) );

     $this->plugin_screen_hook_suffix = add_options_page(
      __( 'Twitter Account Box Options', $this->plugin_slug ),
      __( 'Twitter Account Box', $this->plugin_slug ),
      'manage_options',
      $this->plugin_slug,
      array( $this, 'display_page' )
    );
    }

    public function add_css(){
      wp_enqueue_style( 'twitteraccountbox_style', TAB__PLUGIN_URL . 'styles/twitteraccountbox.css', array(), TAB_VERSION, false);
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
              <label for="consumer_key"><?php _e( 'Twitter Consumer Key:', $this->plugin_slug );?></label>
              <input type="text" name="consumer_key" value="<?php echo get_option('consumer_key');?>"  />
            </div>
            <div class="wrap pure-control-group">
              <label for="consumer_secret"><?php _e( 'Twitter Consumer Secret:', 'twitteraccountbox' );?></label>
              <input type="text" name="consumer_secret" value="<?php echo get_option('consumer_secret');?>"  />
            </div>
            <div class="wrap pure-control-group">
              <label for="oauth_access_token"><?php _e( 'Twitter OAuth Access Token:', 'twitteraccountbox' );?></label>
              <input type="text" name="oauth_access_token" value="<?php echo get_option('oauth_access_token');?>"/>
            </div>
            <div class="wrap pure-control-group">
              <label for="oauth_token_secret"><?php _e( 'Twitter OAuth Access Token Secret:', 'twitteraccountbox' );?></label>
              <input type="text" name="oauth_token_secret" value="<?php echo get_option('oauth_token_secret');?>"/>
            </div>
            <div class="wrap pure-control-group">
              <label for="enable_twitteraccountbox"><?php _e( 'Aktivoi Twitter Account Box', 'twitteraccountbox' );?></label>
              <input type="checkbox" name="enable_twitteraccountbox" value="<?php echo get_option('enable_twitteraccountbox');?>" <?php if(get_option('enable_twitteraccountbox')) echo "checked=checked";?>  />
            </div>
            <div class="wrap pure-control-group">
              <label for="twitter_username"><?php _e( 'Twitter käyttäjätunnus:', 'twitteraccountbox' );?></label>
              <input type="text" name="twitter_username" value="<?php echo get_option('twitter_username');?>" />
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