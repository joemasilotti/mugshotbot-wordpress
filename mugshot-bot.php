<?php
/**
 * Plugin Name: Mugshot Bot
 * Plugin URI: https://mugshotbot.com
 * Description: Automated link previews for WordPress sites.
 * Author: Joe Masilotti
 * Author URI: https://masilotti.com
 * Version: 0.0.5
 * Text Domain: 'mugshot-bot'
 */

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

    $this->settings = $this->buildSettings();
    $this->addActions();
    $this->addFilters();
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

  public function scripts() {
    wp_register_style(
      'mugshot-bot-css',
      plugins_url('mugshot-bot/inc/mugshot-bot.css'),
      null,
      $this->plugin_version
    );
    wp_enqueue_style('mugshot-bot-css');
  }

  public function default_settings() {
    if (! get_option('mugshot_bot_settings')) {
      $mugshot_bot_settings = [
        'theme' => 'default',
        'mode' => 'light',
        'color' => 'red',
        'pattern' => 'none',
      ];

      update_option('mugshot_bot_settings', $mugshot_bot_settings);
    }
  }

  public function settings() {
    if ($_POST) {
      check_admin_referer('mugshot_bot_settings');
      update_option('mugshot_bot_settings', $_POST['mugshot_bot_settings']);
    }

    $mugshot_bot_settings = get_option('mugshot_bot_settings');

    include 'inc/settings.php';
  }

  public function head() {
    global $wp;

    $url = home_url($wp->request);
    $mugshot_bot_settings = get_option('mugshot_bot_settings');

    $mugshot_bot_url = add_query_arg('url', $url, 'http://localhost:3000/m');
    $mugshot_bot_url = add_query_arg('theme', $mugshot_bot_settings['theme'], $mugshot_bot_url);
    $mugshot_bot_url = add_query_arg('image', $mugshot_bot_settings['image'], $mugshot_bot_url);
    $mugshot_bot_url = add_query_arg('mode', $mugshot_bot_settings['mode'], $mugshot_bot_url);
    $mugshot_bot_url = add_query_arg('color', $mugshot_bot_settings['color'], $mugshot_bot_url);
    $mugshot_bot_url = add_query_arg('pattern', $mugshot_bot_settings['pattern'], $mugshot_bot_url);
    $mugshot_bot_url = add_query_arg('hide_watermark', 'true', $mugshot_bot_url); ?>
        <meta property="og:image" content="<?php echo $mugshot_bot_url; ?>">
<?php
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

  private function buildSettings() {
    return [
      'theme' => [
        'label' => 'Theme',
        'description' => 'The overall appearance and layout of your link preview.',
        'values' => [
          [
            'label' => 'Default',
            'value' => 'default',
          ],
          [
            'label' => 'Bold',
            'value' => 'bold',
          ],
          [
            'label' => 'Two Up',
            'value' => 'two_up',
          ],
        ],
      ],
      'image' => [
        'label' => 'Image',
        'description' => 'The generated code is used to embed your uploaded image.',
      ],
      'mode' => [
        'label' => 'Color Scheme',
        'description' => 'Dark mode inverts the colors of light mode.',
        'values' => [
          [
            'label' => 'Light',
            'value' => 'light',
          ],
          [
            'label' => 'Dark',
            'value' => 'dark',
          ],
        ],
      ],
      'color' => [
        'label' => 'Color',
        'description' => 'Accent border, website name, and background pattern tint.',
        'values' => [
          [
            'label' => 'Red',
            'value' => 'red',
          ],
          [
            'label' => 'Orange',
            'value' => 'orange',
          ],
          [
            'label' => 'Yellow',
            'value' => 'yellow',
          ],
          [
            'label' => 'Green',
            'value' => 'green',
          ],
          [
            'label' => 'Teal',
            'value' => 'teal',
          ],
          [
            'label' => 'Blue',
            'value' => 'blue',
          ],
          [
            'label' => 'Indigo',
            'value' => 'indigo',
          ],
          [
            'label' => 'Purple',
            'value' => 'purple',
          ],
          [
            'label' => 'Pink',
            'value' => 'pink',
          ],
        ],
      ],
      'pattern' => [
        'label' => 'Background Pattern',
        'description' => 'Will be tinted with the selected color.',
        'values' => [
          [
            'label' => 'None',
            'value' => 'none',
          ],
          [
            'label' => 'Hideout',
            'value' => 'hideout',
          ],
          [
            'label' => 'Bubbles',
            'value' => 'bubbles',
          ],
          [
            'label' => 'Texture',
            'value' => 'texture',
          ],
          [
            'label' => 'Diagonal Lines',
            'value' => 'diagonal_lines',
          ],
          [
            'label' => 'Charlie Brown',
            'value' => 'charlie_brown',
          ],
          [
            'label' => 'Lines In Motion',
            'value' => 'lines_in_motion',
          ],
          [
            'label' => 'Topography',
            'value' => 'topography',
          ],
          [
            'label' => 'Bank Note',
            'value' => 'bank_note',
          ],
        ],
      ],
    ];
  }

  private function addActions() {
    add_action('admin_menu', [$this, 'menu']);
    add_action('admin_enqueue_scripts', [$this, 'scripts'], 1);

    add_action('init', [$this, 'default_settings'], 1);
    add_action('wp_head', [$this, 'head'], 1);
  }

  private function addFilters() {
    add_filter('wpseo_frontend_presenter_classes', [$this, 'remove_yoast']);
  }
}

$mugshot_bot = Mugshot_Bot_Plugin::get_instance();
