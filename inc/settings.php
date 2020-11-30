<div id="mugshot-bot" class="wrap">
  <h2>Mugshot Bot</h2>

  <form method="post">
    <?php wp_nonce_field('mugshot_bot_settings') ?>

    <table class="form-table">
      <tbody>
        <?php foreach ($this->settings as $index => $setting) : ?>
          <tr>
            <th style="width:30%;"><?php echo $setting['label']; ?></th>
            <td style="width:70%;">
              <?php if (isset($setting['values'])) : ?>
                <select name="mugshot_bot_settings[<?php echo $index; ?>]">
                  <?php foreach ($setting['values'] as $v) : ?>
                    <option value="<?php echo $v['value']; ?>"<?php echo($mugshot_bot_settings[$index] == $v['value'] ? ' selected' : ''); ?>><?php _e($v['label'], $this->plugin_name); ?></option>
                  <?php endforeach; ?>
                </select>
              <?php else : ?>
                <input type="text" class="regular-text ltr" id="mugshot_bot_settings[<?php echo $index; ?>]" name="mugshot_bot_settings[<?php echo $index; ?>]" value="<?php echo(isset($mugshot_bot_settings[$index]) ? $mugshot_bot_settings[$index] : ''); ?>">
              <?php endif; ?>
              <p class="description"><?php echo $setting['description']; ?>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

    <input name="submit" class="button button-primary" type="submit" value="<?php esc_attr_e('Save Settings'); ?>" />
  </form>

  <div id="image-preview">
    <img style="width: 100%; height: auto;" src="http://localhost:3000/m?theme=<?php echo $mugshot_bot_settings['theme']; ?>&image=<?php echo $mugshot_bot_settings['image']; ?>&mode=<?php echo $mugshot_bot_settings['mode']; ?>&color=<?php echo $mugshot_bot_settings['color']; ?>&pattern=<?php echo $mugshot_bot_settings['pattern']; ?>&hide_watermark=true&url=<?php echo site_url(); ?>">
  </div>
</div>
