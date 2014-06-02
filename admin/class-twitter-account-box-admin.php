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
      register_setting( 'twitteraccountbox_options', 'twitteraccountbox_options', array( $this,'twitteraccountbox_options_validate'));
    }

    public function twitteraccountbox_options_validate($options){
      // TODO
      $input_validated = $options;
      delete_transient('twitteraccountbox_transient');
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

    private static function init_twitter_data(){
      $createTwitterAccountBox = CreateTwitterAccountBox::get_instance();
      $createTwitterAccountBox->create_tab_content();
      if(!get_transient('twitteraccountbox_transient')){
        var_dump("NO TRANSIENT");
      } else var_dump(get_transient('twitteraccountbox_transient'));
    }

    public function display_page() {
      if ( !current_user_can( 'manage_options' ) )  {
        wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
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
          $all_data = get_option('twitteraccountbox_options');
        ?>
          <div class="twitter-app-settings">
            <h3><?php _e( 'Twitter-sovelluksen asetukset', 'twitteraccountbox' );?></h3>
            <div class="wrap pure-control-group">
              <label for="twitteraccountbox_options[consumer_key]"><?php _e( 'Twitter Consumer Key:', $this->plugin_slug );?></label>
              <input type="text" name="twitteraccountbox_options[consumer_key]" value="<?php echo $all_data['consumer_key'];?>"  />
            </div>
            <div class="wrap pure-control-group">
              <label for="twitteraccountbox_options[consumer_secret]"><?php _e( 'Twitter Consumer Secret:', $this->plugin_slug );?></label>
              <input type="text" name="twitteraccountbox_options[consumer_secret]" value="<?php echo $all_data['consumer_secret'];?>"  />
            </div>
            <div class="wrap pure-control-group">
              <label for="twitteraccountbox_options[oauth_access_token]"><?php _e( 'Twitter OAuth Access Token:', $this->plugin_slug );?></label>
              <input type="text" name="twitteraccountbox_options[oauth_access_token]" value="<?php echo $all_data['oauth_access_token'];?>"/>
            </div>
            <div class="wrap pure-control-group">
              <label for="twitteraccountbox_options[auth_token_secret]"><?php _e( 'Twitter OAuth Access Token Secret:', $this->plugin_slug );?></label>
              <input type="text" name="twitteraccountbox_options[oauth_token_secret]" value="<?php echo $all_data['oauth_token_secret'];?>"/>
            </div>
            <div class="wrap pure-control-group">
              <label for="twitteraccountbox_options[twitter_username]"><?php _e( 'Twitter käyttäjätunnus:', $this->plugin_slug );?></label>
              <input type="text" name="twitteraccountbox_options[twitter_username]" value="<?php echo $all_data['twitter_username'];?>" />
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