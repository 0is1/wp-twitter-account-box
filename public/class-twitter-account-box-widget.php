<?php

/**
*
* @package        Twitter Account Box
* @author         Janne Saarela <janne@jannejuhani.net>
* @license        GPL-2.0+
* @link           http://tab.jannejuhani.net
* @copyright      2014 Janne Saarela
*/

if ( ! class_exists( 'TwitterAccountBoxWidget' ) ) {
  class TwitterAccountBoxWidget extends WP_Widget {
    function __construct() {
      parent::__construct(
        'twitteraccountbox_widget',
        __( 'Twitter Account Box Widget' , 'twitteraccountbox'),
        array( 'description' => __( 'Display your Twitter Account Box' , 'twitteraccountbox') )
      );

      $plugin = TwitterAccountBox::get_instance();

      $this->plugin_slug = $plugin->get_plugin_slug();

      $createTwitterAccountBox = CreateTwitterAccountBox::get_instance();

      $this->createTwitterAccountBox = $createTwitterAccountBox;

      add_shortcode( 'twitter_account_box', array( $this, 'get_tab_contents' ) );
    }

    /**
     * Outputs the options form on admin
     *
     * @param array $instance The widget options
     */
    public function form( $instance ) {
      if ( $instance ) {
        $title = $instance['title'];
      }
      else {
        $title = __( 'Twitter' , 'twitteraccountbox');
      }
  ?>

      <p>
      <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php esc_html_e( 'Title:' , 'twitteraccountbox'); ?></label>
      <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
      </p>

  <?php
    }
    /**
     * Processing widget options on save
     *
     * @param array $new_instance The new options
     * @param array $old_instance The previous options
     */
    public function update( $new_instance, $old_instance ) {
      $instance['title'] = strip_tags( $new_instance['title'] );
      return $instance;
    }
    /**
     * Outputs the content of the widget
     *
     * @param array $args
     * @param array $instance
     */
    public function widget( $args, $instance ) {
      echo $args['before_widget'];
      if ( ! empty( $instance['title'] ) ) {
        echo $args['before_title'];
        echo esc_html( $instance['title'] );
        echo $args['after_title'];
      }
  ?>
    <?php
      echo $this->createTwitterAccountBox->get_tab_content();
    ?>
  <?php
      echo $args['after_widget'];
    }

  /**
   * Return TAB content for shortcode.
   * @since     0.2.12
   * @return    string    TAB html content.
   */
    function get_tab_contents( ) {
      return $this->createTwitterAccountBox->get_tab_content();

    }

  }

}

function tab_register_widgets() {
  register_widget( 'TwitterAccountBoxWidget' );
}

add_action( 'widgets_init', 'tab_register_widgets' );