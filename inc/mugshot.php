<?php
class Mugshot {
  protected $url;

  public function __construct(string $url) {
    $this->url = $url;
  }

  public function url() {
    $host = wp_get_environment_type() == 'production' ?
      'https://mugshotbot.com' : 'http://localhost:3000';
    $params = $this->params();
    return "$host/m?$params";
  }

  private function params() {
    $settings = get_option('mugshot_bot_settings');

    $options = array(
      'theme' => $settings['theme'],
      'mode' => $settings['mode'],
      'color' => $settings['custom_color'] ?? $settings['color'],
      'pattern' => $settings['pattern'],
    );

    if (isset($settings['image']) && $settings['theme'] == 'two_up') {
      $options['image'] = $settings['image'];
    }

    if (isset($settings['hide_watermark'])) {
      $options['hide_watermark'] = $settings['hide_watermark'];
    }

    $options['url'] = $this->url;

    return http_build_query($options);
  }
}
?>
