<?php require_once('mugshot.php'); ?>
<?php $mugshot = new Mugshot(site_url()); ?>

<div class="lg:grid grid-cols-2">
  <div class="p-8">
    <img class="w-full h-auto" src="<?php echo $mugshot->url(); ?>">
    <h2 class="text-center uppercase">Preview image</h2>
  </div>

  <div class="lg:order-first">
    <h1>Mugshot Bot</h1>

    <form method="post">
      <?php wp_nonce_field('mugshot_bot_settings') ?>

      <table class="form-table">
        <tbody>
          <?php foreach ($this->settings as $index => $setting) : ?>
            <tr>
              <th scope="row">
                <span class="block mb-2"><?php echo $setting['label']; ?></span>

                <?php if (isset($setting['link']) && isset($setting['url'])) : ?>
                  <a href="<?php echo $setting['url']; ?>" class="button button-secondary" target="_blank"><?php echo $setting['link']; ?></a>
                <?php endif; ?>
                </th>
              <td >
                <?php if ($setting['type'] == 'select') : ?>
                  <select name="mugshot_bot_settings[<?php echo $index; ?>]">
                    <?php foreach ($setting['values'] as $v) : ?>
                      <option
                        value="<?php echo $v['value']; ?>"
                        <?php echo($mugshot_bot_settings[$index] == $v['value'] ? ' selected' : ''); ?>
                      >
                        <?php _e($v['label'], $this->plugin_name); ?>
                      </option>
                    <?php endforeach; ?>
                  </select>
                <?php elseif ($setting['type'] == 'checkbox') : ?>
                  <?php $checked = isset($mugshot_bot_settings[$index]); ?>
                  <input type="checkbox" value="1" name="mugshot_bot_settings[<?php echo $index; ?>]" <?php echo $checked ? 'checked' : ''; ?> >
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
  </div>
</div>
