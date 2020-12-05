<?php
/**
 * Plugin Name: Mugshot Bot
 * Plugin URI: https://mugshotbot.com
 * Description: Automated link preview images for every page on your site.
 * Author: Joe Masilotti
 * Author URI: https://masilotti.com
 * Version: 0.2
 * Text Domain: 'mugshot-bot'
 * License: GPLv2
 * License URI: https://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

require_once(__DIR__.'/inc/mugshot.php');
require_once(__DIR__.'/inc/default-settings.php');

class Mugshot_Bot_Plugin {
  private static $instance;

  public static function get_instance() {
    if (null === self::$instance) {
      self::$instance = new self();
    }

    return self::$instance;
  }

  private function __construct() {
    $headers = array('Name' => 'Text Domain', 'Version' => 'Version');
    $meta = get_file_data(__FILE__, $headers, 'plugin');
    $this->plugin_name = trim($meta['Name'], "'");
    $this->plugin_version = $meta['Version'];

    $this->settings = DefaultSettings::build();
    $this->add_actions();
    $this->add_filters();
  }

  public function scripts() {
    $version = wp_get_environment_type() == 'production' ?
      $this->plugin_version : time();

    wp_register_style(
      'mugshot-bot-css',
      plugins_url('mugshot-bot/inc/mugshot-bot.css'),
      null,
      $version
    );
    wp_enqueue_style('mugshot-bot-css');
  }

  public function menu() {
    add_submenu_page(
      'options-general.php',
      'Mugshot Bot',
      'Mugshot Bot',
      'manage_options',
      $this->plugin_name,
      [$this, 'settings']
    );
  }

  public function settings() {
    if ($_POST) {
      $settings = $this->clean_post();
      update_option('mugshot_bot_settings', $settings);
    }

    $settings = get_option('mugshot_bot_settings');

    include 'inc/settings.php';
  }

  public function block_editor_assets() {
    wp_enqueue_script('sidebar-js');
  }

  public function init() {
    $this->set_default_settings();
    $this->register_sidebar();
    $this->register_post_meta();
  }

  public function head() {
    $this->add_mugshot_image_tag();
    $this->add_open_graph_image_tag();
  }

  public function remove_yoast($filter) {
    // Hat Tip: https://gist.github.com/amboutwe/811e92b11e5277977047d44ea81ee9d4#file-yoast_seo_opengraph_remove_presenters-php
    if (($key = array_search('Yoast\WP\SEO\Presenters\Twitter\Image_Presenter', $filter)) !== false) {
      unset($filter[$key]);
    }

    if (($key = array_search('Yoast\WP\SEO\Presenters\Open_Graph\Image_Presenter', $filter)) !== false) {
      unset($filter[$key]);
    }

    return $filter;
  }

  private function add_actions() {
    add_action('admin_enqueue_scripts', [$this, 'scripts'], 1);
    add_action('admin_menu', [$this, 'menu']);
    add_action('enqueue_block_editor_assets', [$this, 'block_editor_assets']);
    add_action('init', [$this, 'init'], 1);
    add_action('wp_head', [$this, 'head'], 1);
  }

  private function add_filters() {
    add_filter('wpseo_frontend_presenter_classes', [$this, 'remove_yoast']);
  }

  private function set_default_settings() {
    if (!get_option('mugshot_bot_settings')) {
      $settings = [
        'theme' => 'default',
        'mode' => 'light',
        'color' => 'red',
        'pattern' => 'none',
      ];

      update_option('mugshot_bot_settings', $settings);
    }
  }

  private function register_sidebar() {
    wp_register_script(
      'sidebar-js',
      plugins_url('inc/sidebar.js', __FILE__),
      array(
        'wp-components',
        'wp-data',
        'wp-edit-post',
        'wp-element',
        'wp-plugins',
      )
    );
  }

  private function register_post_meta() {
    register_post_meta('post', 'mugshot_bot_image_url', array(
      'show_in_rest' => true,
      'single' => true,
      'type' => 'string',
    ));
  }

  private function clean_post() {
    $settings = get_option('mugshot_bot_settings');
    $new_settings = $_POST['mugshot_bot_settings'];
    if ($settings['color'] != $new_settings['color']) {
      $new_settings['custom_color'] = null;
    }
    return $new_settings;
  }

  private function add_mugshot_image_tag() {
    $image_url = get_post_meta(get_the_ID(), 'mugshot_bot_image_url', true);
    if (is_single() && !empty($image_url)) {
      echo "<meta property=\"mugshot:image\" content=\"$image_url\">";
    }
  }

  private function add_open_graph_image_tag() {
    global $wp;
    $url = home_url($wp->request);
    $mugshot = new Mugshot($url);
    $mugshot_url = $mugshot->url();

    echo "<meta property=\"og:image\" content=\"$mugshot_url\">";
  }
}

$mugshot_bot = Mugshot_Bot_Plugin::get_instance();
