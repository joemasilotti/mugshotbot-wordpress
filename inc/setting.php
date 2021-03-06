<?php if (!isset($setting['only_theme']) || $setting['only_theme'] == $settings['theme']) : ?>
  <tr>
    <th scope="row">
      <span class="block mb-2"><?php echo $setting['label']; ?></span>

      <?php if (isset($setting['link']) && isset($setting['url'])) : ?>
        <a
          href="<?php echo $setting['url']; ?>"
          class="button button-secondary"
          target="_blank"
        >
          <?php echo $setting['link']; ?>
        </a>
      <?php endif; ?>
      </th>
    <td >
      <?php if ($setting['type'] == 'select') : ?>
        <select name="mugshot_bot_settings[<?php echo $index; ?>]">
          <?php foreach ($setting['values'] as $v) : ?>
            <?php $value = strtolower(str_replace(' ', '_', $v)); ?>
            <option
              value="<?php echo $value ?>"
              <?php echo($settings[$index] == $value ? ' selected' : ''); ?>
            >
              <?php echo($v); ?>
            </option>
          <?php endforeach; ?>
        </select>
      <?php elseif ($setting['type'] == 'checkbox') : ?>
        <?php $checked = isset($settings[$index]); ?>
        <input
          id="mugshot_bot_settings[<?php echo $index; ?>]" <?php echo $checked ? 'checked' : ''; ?>
          name="mugshot_bot_settings[<?php echo $index; ?>]"
          type="checkbox"
          value="1"
        >
        <label for="mugshot_bot_settings[<?php echo $index; ?>]">
          <?php echo $setting['helper']; ?>
        </label>
      <?php else : ?>
        <input
          class="regular-text ltr"
          id="mugshot_bot_settings[<?php echo $index; ?>]"
          name="mugshot_bot_settings[<?php echo $index; ?>]"
          type="text"
          value="<?php echo(isset($settings[$index]) ? $settings[$index] : ''); ?>"
        >
      <?php endif; ?>
      <p class="description"><?php echo $setting['description']; ?>
    </td>
  </tr>
<?php endif; ?>
