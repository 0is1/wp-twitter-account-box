<?php
/**
* Twitter Account Box.
*
* @package        Twitter Account Box
* @author         Janne Saarela <janne@jannejuhani.net>
* @license        GPL-2.0+
* @link           http://tab.jannejuhani.net
* @copyright      2014 Janne Saarela
*/

/**
 * Loads the Twitter Account Box plugin.
 *
 * @since 0.1.0
 */
if ( ! class_exists( 'TwitterAccountBox' ) ) {
  class TwitterAccountBox {

    /**
     * @since    0.1.0
     * @var      string
     */
    protected $plugin_slug = 'twitteraccountbox';

    /**
     * Instance of this class.
     * @since    0.1.0
     * @var      object
     */
    protected static $instance = null;

    /**
     * Initialize the plugin by setting localization and loading public scripts
     * and styles.
     *
     * @since     0.1.0
     */
    private function __construct() {
      // TODO
      // Load plugin text domain
      add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

      // Load public-facing style sheet and JavaScript.
      add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
      add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ));
      add_action( 'wp_loaded', array( $this, 'enqueue_vendor_scripts' ));
    }

    /**
     * Return the plugin slug.
     * @since    0.1.0
     * @return   Plugin slug variable.
     */
    public function get_plugin_slug() {
      return $this->plugin_slug;
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
     * Attached to activate_{ plugin_basename( __FILES__ ) } by register_activation_hook()
     * @static
     */
    public static function plugin_activation() {
      if ( version_compare( $GLOBALS['wp_version'], TAB__MINIMUM_WP_VERSION, '<' ) ) {
        wp_die( __( 'TwitterAccountBox requires WordPress ', $this->plugin_slug) . TAB__MINIMUM_WP_VERSION . __(' or higher...', $this->plugin_slug));
      }
    }
    /**
     * Removes all connection options
     * @static
     */
    public static function plugin_deactivation( ) {
      //tidy up
    }

    /**
     * Load the plugin text domain for translation.
     *
     * @since    0.1.0
     */
    public function load_plugin_textdomain() {

      $domain = $this->plugin_slug;
      $locale = apply_filters( 'plugin_locale', get_locale(), $domain );

      load_textdomain( $domain, trailingslashit( WP_LANG_DIR ) . $domain . '/' . $domain . '-' . $locale . '.mo' );
      load_plugin_textdomain( $domain, FALSE, basename( plugin_dir_path( dirname( __FILE__ ) ) ) . '/languages/' );
    }
      /**
     * Register and enqueue public-facing style sheet.
     *
     * @since    0.1.0
     */
    public function enqueue_styles() {
      wp_enqueue_style( $this->plugin_slug . '-plugin-styles', plugins_url( 'styles/twitteraccountbox.css', __FILE__ ), array(), TAB__VERSION );
      wp_enqueue_style($this->plugin_slug . '-fontello', plugins_url('vendor/fontello-tab/css/twitter-account-box.css', __FILE__), array(), TAB__VERSION);
    }

    /**
     * Register and enqueues public-facing JavaScript files.
     *
     * @since    0.1.0
     */
    public function enqueue_scripts() {
      // wp_enqueue_script( $this->plugin_slug . '-plugin-script', plugins_url( 'js/twitter.js', __FILE__ ), array(), TAB__VERSION, true );
    }
    /**
     * Register and enqueue public-facing Twitter API JavaScript files.
     *
     * @since    0.1.0
     */
    public function enqueue_vendor_scripts() {
      wp_enqueue_script( $this->plugin_slug . '-plugin-script', plugins_url( 'js/twitter.js', __FILE__ ), array(), TAB__VERSION, true );
    }

  }
}