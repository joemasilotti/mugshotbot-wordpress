<?php
class DefaultSettings {
  public static function build() {
    return [
      'theme' => [
        'description' => 'The overall appearance and layout of your link preview.',
        'label' => 'Theme',
        'pro' => true,
        'type' => 'select',
        'values' => [
          'Default',
          'Bold',
          'Two Up',
        ],
      ],
      'image' => [
        'description' => 'Upload an image and paste in the 8 character code.',
        'label' => 'Image',
        'link' => 'Upload image',
        'only_theme' => 'two_up',
        'pro' => true,
        'type' => 'text',
        'url' => 'https://mugshotbot.com/images',
      ],
      'mode' => [
        'description' => 'Dark mode inverts the colors of light mode.',
        'label' => 'Color Scheme',
        'type' => 'select',
        'values' => [
          'Light',
          'Dark',
        ],
      ],
      'color' => [
        'description' => 'Accent border, website name, and background pattern tint.',
        'label' => 'Color',
        'type' => 'select',
        'values' => [
          'Red',
          'Orange',
          'Yellow',
          'Green',
          'Teal',
          'Blue',
          'Indigo',
          'Purple',
          'Pink',
        ],
      ],
      'pattern' => [
        'description' => 'Will be tinted with the selected color.',
        'label' => 'Background Pattern',
        'type' => 'select',
        'values' => [
          'None',
          'Hideout',
          'Bubbles',
          'Texture',
          'Diagonal Lines',
          'Charlie Brown',
          'Lines In Motion',
          'Topography',
          'Bank Note',
        ],
      ],
      'custom_color' => [
        'description' => 'Use a six digit HEX code, without the leading #.',
        'label' => 'Custom color',
        'pro' => true,
        'type' => 'text',
      ],
      'hide_watermark' => [
        'description' => 'Hides the Mugshot Bot branding in the bottom right.',
        'helper' => 'Hide branding',
        'label' => 'Mugshot Bot branding',
        'pro' => true,
        'type' => 'checkbox',
      ],
    ];
  }
}
?>
