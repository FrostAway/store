<?php
add_action('admin_menu', 'wc_vnd_settings');
function wc_vnd_settings() {
    add_menu_page('Woocommerce VNĐ Settings', 'Woocommerce VNĐ', 'administrator', 'wc_vn_currency_settings', 'wc_display_vn_currency_settings');
}
function wc_display_vn_currency_settings () {

    $vnd_convert_rate = (get_option('vnd_convert_rate') != '') ? get_option('vnd_convert_rate') : '21083.7';

    $html = '</pre>
<div class="wrap"><form action="options.php" method="post" name="options">
<h2>Woocommerce Vietnam Currency Settings</h2>
' . wp_nonce_field('update-options') . '
<table class="form-table" width="100%" cellpadding="10">
<tbody>
<tr>
<td scope="row" align="left">
 <label>Tỷ giá chuyển đổi</label></br><input type="text" name="vnd_convert_rate" value="' . $vnd_convert_rate . '" /></td>
</tr>
</tbody>
</table>
 <input type="hidden" name="action" value="update" />

 <input type="hidden" name="page_options" value="vnd_convert_rate" />

 <input type="submit" name="Submit" value="Update" /></form></div>
<pre>
';

    echo $html;

}
