<?php require_once('mugshot.php'); ?>
<?php $mugshot = new Mugshot(site_url()); ?>

<div class="lg:grid grid-cols-2">
  <div class="p-8">
    <img class="w-full h-auto" src="<?php echo $mugshot->url(); ?>">
    <h2 class="text-center uppercase">Preview image</h2>
    <p class="description text-center">
      The content will automatically be updated to match each blog post.
    </p>
  </div>

  <div class="lg:order-first">
    <h1>Mugshot Bot</h1>

    <p class="description max-w-lg">
      Customize the link preview image for your WordPress site.
    </p>

    <p class="description">
      Need help?
      <a href="mailto:joe@masilotti.com">Send me a message</a>
      and we can figure it out together!
    </p>

    <form method="post" class="mt-2">
      <?php wp_nonce_field('mugshot_bot_settings') ?>

      <?php $pro = array_filter($this->settings, function($s) { return isset($s['pro']); }); ?>
      <?php $free = array_diff_key($this->settings, $pro); ?>

      <table class="form-table">
        <tbody>

          <?php foreach ($free as $index => $setting) : ?>
            <?php include 'setting.php' ?>
          <?php endforeach; ?>
        </table>

        <hr />

        <h2>Pro features</h2>
        <p class="description max-w-lg">
          <b>
            These features only work with a paid subscription to Mugshot Bot.
          </b>
          <a href="https://mugshotbot.com/pricing" target="_blank">Sign up for an account</a>
          then
          <a href="https://mugshotbot.com/customize" target="_blank">customize</a>
          one image for this website to enable Pro features.
        </p>
        <table class="form-table">
          <?php foreach ($pro as $index => $setting) : ?>
            <?php include 'setting.php' ?>
          <?php endforeach; ?>
        </tbody>
      </table>

      <input
        class="button button-primary"
        name="submit"
        type="submit"
        value="<?php esc_attr_e('Save Settings'); ?>"
      />
    </form>
  </div>
</div>
