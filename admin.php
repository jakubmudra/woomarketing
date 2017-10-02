<?php
function woomarketing_options_page()
{
?>
  <div>
  <?php screen_icon(); ?>
  <h2>WooMarketing</h2>
  <form method="post" action="options.php">
  <?php settings_fields( 'woomarketing_keys' ); ?>

  <table>
    
  <tr valign="top">
  <th scope="row"><label for="woomarketing_sklik_key">Sklik klíč</label></th>
  <td><input type="text" id="woomarketing_sklik_key" name="woomarketing_sklik_key" value="<?php echo get_option('woomarketing_sklik_key'); ?>" /></td>
  </tr>
   <tr valign="top">
  <th scope="row"><label for="woomarketing_toplist_key">Vaše TopList ID</label></th>
  <td><input type="text" id="woomarketing_toplist_key" name="woomarketing_toplist_key" value="<?php echo get_option('woomarketing_toplist_key'); ?>" /></td>
  </tr>
   <tr valign="top">
  <th scope="row"><label for="woomarketing_analytics_key">Váš Google analytics klíč</label></th>
  <td><input type="text" id="woomarketing_analytics_key" name="woomarketing_analytics_key" value="<?php echo get_option('woomarketing_analytics_key'); ?>" /></td>
  </tr>
   <tr valign="top">
  <th scope="row"><label for="woomarketing_gdr_conversion_key">Váš Google dynamic Remaketing - Conversion id</label></th>
  <td><input type="text" id="woomarketing_gdr_conversion_key" name="woomarketing_gdr_conversion_key" value="<?php echo get_option('woomarketing_gdr_conversion_key'); ?>" /></td>
  </tr>
  </table>
  <?php  submit_button(); ?>
  </form>
  </div>
<?php
}