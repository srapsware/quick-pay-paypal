<?php

// Displays PayPal Payment Accept Options menu
function paypal_quick_pay_add_option_pages()
{
    if (function_exists('add_options_page')) {
        add_options_page('PayPal Quick Pay', 'PayPal Quick Pay', 'manage_options', __FILE__, 'paypal_quick_pay_options_page');
    }
}
// Insert the paypal_quick_pay_add_option_pages in the 'admin_menu'
add_action('admin_menu', 'paypal_quick_pay_add_option_pages');

function paypal_quick_pay_options_page()
{

    if (!current_user_can('manage_options')) {
        wp_die('You do not have permission to access this settings page.');
    }

    if (isset($_POST['info_update'])) {
        $nonce = $_REQUEST['_wpnonce'];
        if (!wp_verify_nonce($nonce, 'wp_accept_pp_payment_settings_update')) {
            wp_die('Error! Nonce Security Check Failed! Go back to settings menu and save the settings again.');
        }

        $value1 = filter_input(INPUT_POST, 'wp_pp_payment_value1', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $value2 = filter_input(INPUT_POST, 'wp_pp_payment_value2', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $value3 = filter_input(INPUT_POST, 'wp_pp_payment_value3', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $value4 = filter_input(INPUT_POST, 'wp_pp_payment_value4', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $value5 = filter_input(INPUT_POST, 'wp_pp_payment_value5', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $value6 = filter_input(INPUT_POST, 'wp_pp_payment_value6', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

        update_option('wp_paypal_widget_title_name', sanitize_text_field(stripslashes($_POST["wp_paypal_widget_title_name"])));
        update_option('wp_pp_payment_email', sanitize_email($_POST["wp_pp_payment_email"]));
        update_option('paypal_payment_currency', sanitize_text_field($_POST["paypal_payment_currency"]));
        update_option('wp_pp_payment_subject', sanitize_text_field(stripslashes($_POST["wp_pp_payment_subject"])));
        update_option('wp_pp_payment_item1', sanitize_text_field(stripslashes($_POST["wp_pp_payment_item1"])));
        update_option('wp_pp_payment_value1', $value1);
        update_option('wp_pp_payment_item2', sanitize_text_field(stripslashes($_POST["wp_pp_payment_item2"])));
        update_option('wp_pp_payment_value2', $value2);
        update_option('wp_pp_payment_item3', sanitize_text_field(stripslashes($_POST["wp_pp_payment_item3"])));
        update_option('wp_pp_payment_value3', $value3);
        update_option('wp_pp_payment_item4', sanitize_text_field(stripslashes($_POST["wp_pp_payment_item4"])));
        update_option('wp_pp_payment_value4', $value4);
        update_option('wp_pp_payment_item5', sanitize_text_field(stripslashes($_POST["wp_pp_payment_item5"])));
        update_option('wp_pp_payment_value5', $value5);
        update_option('wp_pp_payment_item6', sanitize_text_field(stripslashes($_POST["wp_pp_payment_item6"])));
        update_option('wp_pp_payment_value6', $value6);
        update_option('payment_button_type', sanitize_text_field($_POST["payment_button_type"]));
        update_option('wp_pp_show_other_amount', isset($_POST['wp_pp_show_other_amount']) ? '1' : '-1');
        update_option('wp_pp_show_ref_box', isset($_POST['wp_pp_show_ref_box']) ? '1' : '-1');
        update_option('wp_pp_ref_title', sanitize_text_field(stripslashes($_POST["wp_pp_ref_title"])));
        update_option('wp_pp_return_url', esc_url_raw(sanitize_text_field($_POST["wp_pp_return_url"])));
        update_option('select_currency_text', sanitize_text_field(stripslashes($_POST["select_currency_text"])));
        update_option('enter_amount_text', sanitize_text_field(stripslashes($_POST["enter_amount_text"])));

        echo '<div id="message" class="updated fade"><p><strong>';
        echo 'Options Updated!';
        echo '</strong></p></div>';
    }

    $paypal_payment_currency = stripslashes(get_option('paypal_payment_currency'));
    $payment_button_type = stripslashes(get_option('payment_button_type'));
?>

    <div class=wrap>
        <h2>PayPal Quick Pay - All Currencies v<?php echo QPP_PLUGIN_VERSION; ?></h2>

        <div id="poststuff">
            <div id="post-body">

                <form method="post" action="">
                    <?php wp_nonce_field('wp_accept_pp_payment_settings_update'); ?>

                    <input type="hidden" name="info_update" id="info_update" value="true" />

                    <div class="postbox">
                        <h3 class="hndle"><label for="title">Plugin Usage</label></h3>
                        <div class="inside">
                            <p>There are a few different ways you can use this plugin:</p>
                            <ol>
                                <li>Configure the options below and then add the shortcode <strong>[paypal_quick_pay]</strong> to a post or page (where you want the payment button)</li>
                                <li>Use the shortcode with custom parameter options to add multiple different payment widgets with different configuration.
                                    <a href="https://www.srapsware.com/product/paypal-quick-pay-all-currencies/" target="_blank">View shortcode documentation</a></li>
                                <li>Call the function from a template file: <strong>&lt;?php echo Paypal_Quick_Pay_Accept(); ?&gt;</strong></li>
                                <li>Use the <strong>PayPal Quick Pay</strong> Widget from the Widgets menu</li>
                            </ol>
                        </div>
                    </div>

                    <div class="postbox">
                        <h3 class="hndle"><label for="title">PayPal Quick Pay Plugin Options</label></h3>
                        <div class="inside">

                            <table class="form-table">

                                <tr valign="top">
                                    <td width="25%" align="left">
                                        <strong>PayPal Quick Pay Widget Title:</strong>
                                    </td>
                                    <td align="left">
                                        <input name="wp_paypal_widget_title_name" type="text" size="30" value="<?php echo esc_attr(get_option('wp_paypal_widget_title_name')); ?>" />
                                        <br /><i>This will be the title of the Widget on the Sidebar if you use it.</i><br />
                                    </td>
                                </tr>

                                <tr valign="top">
                                    <td width="25%" align="left">
                                        <strong>Paypal Email address:</strong>
                                    </td>
                                    <td align="left">
                                        <input name="wp_pp_payment_email" type="text" size="35" value="<?php echo esc_attr(get_option('wp_pp_payment_email')); ?>" />
                                        <br /><i>This is the Paypal Email address where the payments will go</i><br />
                                    </td>
                                </tr>

                                <tr valign="top">
                                    <td width="25%" align="left">
                                        <strong>Select Currency Text:</strong>
                                    </td>
                                    <td align="left">
                                    <input name="select_currency_text" type="text" size="35" value="<?php echo esc_attr(get_option('select_currency_text')); ?>" />
                                    <br /><i> It will show before select currency option .</i>
                                    </td>
                                </tr>

                                <tr valign="top">
                                    <td width="25%" align="left">
                                        <strong>Choose Payment Default Currency: </strong>
                                    </td>
                                    <td align="left">
        <select id="paypal_payment_currency" name="paypal_payment_currency">
            <?php _e('<option value="AUD"') ?><?php if ($paypal_payment_currency == "AUD") echo " selected " ?><?php _e('>Australian dollar</option>') ?>
            <?php _e('<option value="BRL"') ?><?php if ($paypal_payment_currency == "BRL") echo " selected " ?><?php _e('>Brazilian real</option>') ?>
            <?php _e('<option value="CAD"') ?><?php if ($paypal_payment_currency == "CAD") echo " selected " ?><?php _e('>Canadian dollar</option>') ?>
            <?php _e('<option value="CZK"') ?><?php if ($paypal_payment_currency == "CZK") echo " selected " ?><?php _e('>Czech koruna</option>') ?>
            <?php _e('<option value="DKK"') ?><?php if ($paypal_payment_currency == "DKK") echo " selected " ?><?php _e('>Danish krone</option>') ?>
            <?php _e('<option value="EUR"') ?><?php if ($paypal_payment_currency == "EUR") echo " selected " ?><?php _e('>Euro</option>') ?>
            <?php _e('<option value="HKD"') ?><?php if ($paypal_payment_currency == "HKD") echo " selected " ?><?php _e('>Hong Kong dollar</option>') ?>
            <?php _e('<option value="HUF"') ?><?php if ($paypal_payment_currency == "HUF") echo " selected " ?><?php _e('>Hungarian forint</option>') ?>
            <?php _e('<option value="INR"') ?><?php if ($paypal_payment_currency == "INR") echo " selected " ?><?php _e('>Indian rupee</option>') ?>
            <?php _e('<option value="ILS"') ?><?php if ($paypal_payment_currency == "ILS") echo " selected " ?><?php _e('>Israeli new shekel</option>') ?>
            <?php _e('<option value="JPY"') ?><?php if ($paypal_payment_currency == "JPY") echo " selected " ?><?php _e('>Japanese yen</option>') ?>
            <?php _e('<option value="MYR"') ?><?php if ($paypal_payment_currency == "MYR") echo " selected " ?><?php _e('>Malaysian ringgit</option>') ?>
            <?php _e('<option value="MXN"') ?><?php if ($paypal_payment_currency == "MXN") echo " selected " ?><?php _e('>Mexican peso</option>') ?>
            <?php _e('<option value="TWD"') ?><?php if ($paypal_payment_currency == "TWD") echo " selected " ?><?php _e('>New Taiwan dollar</option>') ?>
            <?php _e('<option value="NZD"') ?><?php if ($paypal_payment_currency == "NZD") echo " selected " ?><?php _e('>Norwegian krone</option>') ?>
            <?php _e('<option value="PHP"') ?><?php if ($paypal_payment_currency == "PHP") echo " selected " ?><?php _e('>Philippine peso</option>') ?>
            <?php _e('<option value="PLN"') ?><?php if ($paypal_payment_currency == "PLN") echo " selected " ?><?php _e('>Polish złoty</option>') ?>
            <?php _e('<option value="GBP"') ?><?php if ($paypal_payment_currency == "GBP") echo " selected " ?><?php _e('>Pound sterling</option>') ?>
            <?php _e('<option value="RUB"') ?><?php if ($paypal_payment_currency == "RUB") echo " selected " ?><?php _e('>Russian ruble</option>') ?>
            <?php _e('<option value="SGD"') ?><?php if ($paypal_payment_currency == "SGD") echo " selected " ?><?php _e('>Singapore dollar</option>') ?>
            <?php _e('<option value="SEK"') ?><?php if ($paypal_payment_currency == "SEK") echo " selected " ?><?php _e('>Swedish krona</option>') ?>
            <?php _e('<option value="CHF"') ?><?php if ($paypal_payment_currency == "CHF") echo " selected " ?><?php _e('>Swiss franc</option>') ?>
            <?php _e('<option value="THB"') ?><?php if ($paypal_payment_currency == "THB") echo " selected " ?><?php _e('>Thai baht</option>') ?>
            <?php _e('<option value="USD"') ?><?php if ($paypal_payment_currency == "USD") echo " selected " ?><?php _e('>United States dollar</option>') ?>
        </select>
                                        <br /><i>This is the currency for your visitors to make Payments or Donations in.</i><br />
                                    </td>
                                </tr>

                                <tr valign="top">
                                    <td width="25%" align="left">
                                        <strong>Manually Amount Only:</strong>
                                    </td>
                                    <td align="left">
                                        <input name="wp_pp_show_other_amount" type="checkbox" <?php if (get_option('wp_pp_show_other_amount') != '-1') echo ' checked="checked"'; ?> value="1" />
                                        <i> Tick this checkbox if you want to show only manually amount entered textbox.</i>
                                    </td>
                                </tr>

                                <tr valign="top">
                                    <td width="25%" align="left">
                                        <strong>Enter Amount Text:</strong>
                                    </td>
                                    <td align="left">
                                    <input name="enter_amount_text" type="text" size="35" value="<?php echo esc_attr(get_option('enter_amount_text')); ?>" />
                                    <br /><i> It will show before amount input textbox.</i>
                                    </td>
                                </tr>

                                <tr valign="top">
                                    <td width="25%" align="left">
                                        <strong>Payment Subject:</strong>
                                    </td>
                                    <td align="left">
                                        <input name="wp_pp_payment_subject" type="text" size="35" value="<?php echo esc_attr(get_option('wp_pp_payment_subject')); ?>" />
                                        <br /><i>Enter the Product or service name or the reason for the payment here. The visitors will see this text</i><br />
                                    </td>
                                </tr>

                                <tr valign="top">
                                    <td width="25%" align="left">
                                        <strong>Payment Option 1:</strong>
                                    </td>
                                    <td align="left">
                                        <input name="wp_pp_payment_item1" type="text" size="25" value="<?php echo esc_attr(get_option('wp_pp_payment_item1')); ?>" />
                                        <strong>Price :</strong>
                                        <input name="wp_pp_payment_value1" type="text" size="10" value="<?php echo esc_attr(get_option('wp_pp_payment_value1')); ?>" />
                                        <br />
                                    </td>
                                </tr>

                                <tr valign="top">
                                    <td width="25%" align="left">
                                        <strong>Payment Option 2:</strong>
                                    </td>
                                    <td align="left">
                                        <input name="wp_pp_payment_item2" type="text" size="25" value="<?php echo esc_attr(get_option('wp_pp_payment_item2')); ?>" />
                                        <strong>Price :</strong>
                                        <input name="wp_pp_payment_value2" type="text" size="10" value="<?php echo esc_attr(get_option('wp_pp_payment_value2')); ?>" />
                                        <br />
                                    </td>
                                </tr>

                                <tr valign="top">
                                    <td width="25%" align="left">
                                        <strong>Payment Option 3:</strong>
                                    </td>
                                    <td align="left">
                                        <input name="wp_pp_payment_item3" type="text" size="25" value="<?php echo esc_attr(get_option('wp_pp_payment_item3')); ?>" />
                                        <strong>Price :</strong>
                                        <input name="wp_pp_payment_value3" type="text" size="10" value="<?php echo esc_attr(get_option('wp_pp_payment_value3')); ?>" />
                                        <br />
                                    </td>
                                </tr>

                                <tr valign="top">
                                    <td width="25%" align="left">
                                        <strong>Payment Option 4:</strong>
                                    </td>
                                    <td align="left">
                                        <input name="wp_pp_payment_item4" type="text" size="25" value="<?php echo esc_attr(get_option('wp_pp_payment_item4')); ?>" />
                                        <strong>Price :</strong>
                                        <input name="wp_pp_payment_value4" type="text" size="10" value="<?php echo esc_attr(get_option('wp_pp_payment_value4')); ?>" />
                                        <br />
                                    </td>
                                </tr>

                                <tr valign="top">
                                    <td width="25%" align="left">
                                        <strong>Payment Option 5:</strong>
                                    </td>
                                    <td align="left">
                                        <input name="wp_pp_payment_item5" type="text" size="25" value="<?php echo esc_attr(get_option('wp_pp_payment_item5')); ?>" />
                                        <strong>Price :</strong>
                                        <input name="wp_pp_payment_value5" type="text" size="10" value="<?php echo esc_attr(get_option('wp_pp_payment_value5')); ?>" />
                                        <br />
                                    </td>
                                </tr>

                                <tr valign="top">
                                    <td width="25%" align="left">
                                        <strong>Payment Option 6:</strong>
                                    </td>
                                    <td align="left">
                                        <input name="wp_pp_payment_item6" type="text" size="25" value="<?php echo esc_attr(get_option('wp_pp_payment_item6')); ?>" />
                                        <strong>Price :</strong>
                                        <input name="wp_pp_payment_value6" type="text" size="10" value="<?php echo esc_attr(get_option('wp_pp_payment_value6')); ?>" />
                                        <br /><i>Enter the name of the service or product and the price. eg. Enter "Basic service - $10" in the Payment Option text box and "10.00" in the price text box to accept a payment of $10 for "Basic service". Leave the Payment Option and Price fields empty if u don't want to use that option. For example, if you have 3 price options then fill in the top 3 and leave the rest empty.</i>
                                    </td>
                                </tr>

                                <tr valign="top">
                                    <td width="25%" align="left">
                                        <strong>Show Reference Text Box:</strong>
                                    </td>
                                    <td align="left">
                                        <input name="wp_pp_show_ref_box" type="checkbox" <?php if (get_option('wp_pp_show_ref_box') != '-1') echo ' checked="checked"'; ?> value="1" />
                                        <i> Tick this checkbox if you want your visitors to be able to enter a reference text like email or web address.</i>
                                    </td>
                                </tr>

                                <tr valign="top">
                                    <td width="25%" align="left">
                                        <strong>Reference Text Box Title:</strong>
                                    </td>
                                    <td align="left">
                                        <input name="wp_pp_ref_title" type="text" size="35" value="<?php echo esc_attr(get_option('wp_pp_ref_title')); ?>" />
                                        <br /><i>Enter a title for the Reference text box (ie. Your Web Address). The visitors will see this text.</i><br />
                                    </td>
                                </tr>

                                <tr valign="top">
                                    <td width="25%" align="left">
                                        <strong>Return URL from PayPal:</strong>
                                    </td>
                                    <td align="left">
                                        <input name="wp_pp_return_url" type="text" size="60" value="<?php echo esc_url(get_option('wp_pp_return_url')); ?>" />
                                        <br /><i>Enter a return URL (could be a Thank You page). PayPal will redirect visitors to this page after Payment.</i><br />
                                    </td>
                                </tr>

                            </table>

                            <br /><br />
                            <strong>Choose a Submit Button Type :</strong>
                            <br /><i>This is the button the visitors will click on to make Payments or Donations.</i><br />
                            <table style="width:50%; border-spacing:0; padding:0; text-align:center;">
                                <tr>
                                    <td>
                                        <?php _e('<input type="radio" name="payment_button_type" value="https://www.paypalobjects.com/webstatic/en_US/i/buttons/checkout-logo-large.png"') ?>
                                        <?php if ($payment_button_type == "https://www.paypalobjects.com/webstatic/en_US/i/buttons/checkout-logo-large.png") echo " checked " ?>
                                        <?php _e('/>') ?>
                                    </td>
                                    <td>
                                        <?php _e('<input type="radio" name="payment_button_type" value="https://www.paypalobjects.com/webstatic/en_US/i/buttons/cc-badges-ppmcvdam.png"') ?>
                                        <?php if ($payment_button_type == "https://www.paypalobjects.com/webstatic/en_US/i/buttons/cc-badges-ppmcvdam.png") echo " checked " ?>
                                        <?php _e('/>') ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td><img border="0" src="https://www.paypalobjects.com/webstatic/en_US/i/buttons/checkout-logo-large.png" alt="" /></td>
                                    <td><img border="0" src="https://www.paypalobjects.com/webstatic/en_US/i/buttons/cc-badges-ppmcvdam.png" alt="" /></td>
                                </tr>
                            </table>

                        </div>
                    </div><!-- end of postbox -->

                    <div class="submit">
                        <input type="submit" class="button-primary" name="info_update" value="<?php _e('Update options'); ?> &raquo;" />
                    </div>
                </form>

                <div style="background: none repeat scroll 0 0 #FFF6D5;border: 1px solid #D1B655;color: #3F2502;margin: 10px 0;padding: 5px 5px 5px 10px;text-shadow: 1px 1px #FFFFFF;">
                    <p>If you need a custom PayPal payment gateway then please contact us at <a href="https://www.srapsware.com/" target="_blank">Srapsware.com</a>
                    </p>
                </div>

            </div>
        </div> <!-- end of .poststuff and post-body -->
    </div><!-- end of .wrap -->
<?php
                                                                                                                                            }
