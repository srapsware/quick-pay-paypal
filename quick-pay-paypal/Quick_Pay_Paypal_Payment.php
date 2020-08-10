<?php
/*
Plugin Name: Quick pay paypal - All Currencies
Version: 1.0
Plugin URI: https://www.srapsware.com/product/paypal-quick-pay-all-currencies/
Author: Shiv Singh
Author URI: https://www.shivsingh.net
Description: PayPal quick payment is used for getting quick payment in all currencies by Wordpress plugin. You can use in sidebar, page and post by shortcode.
License: GPL2
*/

//Slug - pqp

if (!defined('ABSPATH')) { //Exit if accessed directly
    exit;
}

define('QPP_PLUGIN_VERSION', '1.0');
define('QPP_PLUGIN_URL', plugins_url('', __FILE__));

include_once('qpp_shortcode_view.php');
include_once('qpp_admin_menu.php');
include_once('qpp_paypal_utility.php');

function qpp_plugin_install()
{
    // Some default options
    add_option('qpp_payment_email', get_bloginfo('admin_email'));
    add_option('paypal_payment_currency', 'USD');
    add_option('qpp_payment_subject', 'Select Your Payment Plan');
    add_option('qpp_payment_item1', 'Basic Service - $10');
    add_option('qpp_payment_value1', '10');
    add_option('qpp_payment_item2', 'Gold Service - $20');
    add_option('qpp_payment_value2', '20');
    add_option('qpp_payment_item3', 'Platinum Service - $30');
    add_option('qpp_payment_value3', '30');
    add_option('wp_paypal_widget_title_name', 'Paypal Payment');
    add_option('select_currency_text', 'Select Currency');
    add_option('enter_amount_text', 'Enter Amount');
    add_option('payment_button_type', 'https://www.paypalobjects.com/webstatic/en_US/i/buttons/checkout-logo-large.png');
    add_option('qpp_show_other_amount', '-1');
    add_option('qpp_show_ref_box', '1');
    add_option('qpp_ref_title', 'Your Email Address');
    add_option('qpp_return_url', home_url());
}

register_activation_hook(__FILE__, 'qpp_plugin_install');

add_shortcode('quick_pay_paypal_box_for_any_amount', 'qpp_buy_now_any_amt_handler');

add_filter('plugin_action_links_'.plugin_basename(__FILE__), 'paypal_quick_pay_add_plugin_page_settings_link');

function paypal_quick_pay_add_plugin_page_settings_link( $links ) {
	$links[] = '<a href="' .
		admin_url( 'options-general.php?page=paypal-quick-pay/qpp_admin_menu.php' ) .
		'">' . __('Settings') . '</a>';
	return $links;
}

function qpp_buy_now_any_amt_handler($args)
{
    $output = wppp_render_paypal_button_with_other_amt($args);
    return $output;
}

add_shortcode('paypal_quick_pay_box', 'qpp_buy_now_button_shortcode');

function qpp_buy_now_button_shortcode($args)
{
    ob_start();
    wppp_render_paypal_button_form($args);
    $output = ob_get_contents();
    ob_end_clean();
    return $output;
}

function Paypal_Quick_Pay_Accept()
{
    $paypal_email = get_option('qpp_payment_email');
    $payment_currency = get_option('paypal_payment_currency');
    $paypal_subject = get_option('qpp_payment_subject');

    $itemName1 = get_option('qpp_payment_item1');
    $value1 = get_option('qpp_payment_value1');
    $itemName2 = get_option('qpp_payment_item2');
    $value2 = get_option('qpp_payment_value2');
    $itemName3 = get_option('qpp_payment_item3');
    $value3 = get_option('qpp_payment_value3');
    $itemName4 = get_option('qpp_payment_item4');
    $value4 = get_option('qpp_payment_value4');
    $itemName5 = get_option('qpp_payment_item5');
    $value5 = get_option('qpp_payment_value5');
    $itemName6 = get_option('qpp_payment_item6');
    $value6 = get_option('qpp_payment_value6');
    $payment_button = get_option('payment_button_type');
    $qpp_show_other_amount = get_option('qpp_show_other_amount');
    $qpp_show_ref_box = get_option('qpp_show_ref_box');
    $qpp_ref_title = get_option('qpp_ref_title');
    $qpp_return_url = get_option('qpp_return_url');
    $select_currency_text = get_option('select_currency_text');
    $enter_amount_text = get_option('enter_amount_text');

    /* === Paypal form === */
    $output = '';
    $output .= '<div id="accept_paypal_payment_form">';
    $output .= '<form action="https://www.paypal.com/cgi-bin/webscr" method="post" class="wp_accept_pp_button_form_classic">';
    $output .= '<input type="hidden" name="cmd" value="_xclick" />';
    $output .= '<input type="hidden" name="business" value="' . esc_attr($paypal_email) . '" />';
    $output .= '<input type="hidden" name="item_name" value="' . esc_attr($paypal_subject) . '" />';

    if ($qpp_show_other_amount != '1') {

        $output .= '<div class="qpp_payment_subject"><span class="payment_subject"><strong>' . esc_attr($paypal_subject) . '</strong></span></div>';
        $output .= '<select id="amount" name="amount" class="">';
        $output .= '<option value="' . esc_attr($value1) . '">' . esc_attr($itemName1) . '</option>';
        if (!empty($value2)) {
            $output .= '<option value="' . esc_attr($value2) . '">' . esc_attr($itemName2) . '</option>';
        }
        if (!empty($value3)) {
            $output .= '<option value="' . esc_attr($value3) . '">' . esc_attr($itemName3) . '</option>';
        }
        if (!empty($value4)) {
            $output .= '<option value="' . esc_attr($value4) . '">' . esc_attr($itemName4) . '</option>';
        }
        if (!empty($value5)) {
            $output .= '<option value="' . esc_attr($value5) . '">' . esc_attr($itemName5) . '</option>';
        }
        if (!empty($value6)) {
            $output .= '<option value="' . esc_attr($value6) . '">' . esc_attr($itemName6) . '</option>';
        }

        $output .= '</select>';
    } else {

        // Show other amount text box

        $output .= '<div class="enter_amount_text_label"><strong>' . esc_attr($enter_amount_text) . '</strong></div>';
        $output .= '<div class="qpp_other_amount_input"><input type="number" min="1" step="any" name="other_amount" title="Other Amount" value="" class="qpp_other_amt_input form-control" placeholder="' . esc_attr($enter_amount_text) . '" /></div>';
    }

    $output .= '<div class="select_currency_text_label"><strong>' . esc_attr($select_currency_text) . '</strong></div>';
    $output .= '<select name="currency_code" class="currency_code form-control">
<option value="AUD">Australian dollar</option>            
<option value="BRL">Brazilian real</option>            
<option value="CAD">Canadian dollar</option>            
<option value="CZK">Czech koruna</option>            
<option value="DKK">Danish krone</option>            
<option value="EUR">Euro</option>            
<option value="HKD">Hong Kong dollar</option>            
<option value="HUF">Hungarian forint</option>            
<option value="INR">Indian rupee</option>            
<option value="ILS">Israeli new shekel</option>            
<option value="JPY">Japanese yen</option>            
<option value="MYR">Malaysian ringgit</option>            
<option value="MXN">Mexican peso</option>            
<option value="TWD">New Taiwan dollar</option>            
<option value="NZD">Norwegian krone</option>            
<option value="PHP">Philippine peso</option>            
<option value="PLN">Polish złoty</option>            
<option value="GBP">Pound sterling</option>            
<option value="RUB">Russian ruble</option>            
<option value="SGD">Singapore dollar</option>            
<option value="SEK">Swedish krona</option>            
<option value="CHF">Swiss franc</option>            
<option value="THB">Thai baht</option>            
<option value="USD" selected="">United States dollar</option>
</select>';

    // Show the reference text box
    if ($qpp_show_ref_box == '1') {
        $output .= '<div class="qpp_ref_title_label"><strong>' . esc_attr($qpp_ref_title) . ':</strong></div>';
        $output .= '<input type="hidden" name="on0" value="' . apply_filters('qpp_button_reference_name', 'Reference') . '" />';
        $output .= '<div class="qpp_ref_value"><input type="text" name="os0" maxlength="60" value="' . apply_filters('qpp_button_reference_value', '') . '" class="qpp_button_reference" /></div>';
    }

    $output .= '<input type="hidden" name="no_shipping" value="0" /><input type="hidden" name="no_note" value="1" /><input type="hidden" name="bn" value="TipsandTricks_SP" />';

    if (!empty($qpp_return_url)) {
        $output .= '<input type="hidden" name="return" value="' . esc_url($qpp_return_url) . '" />';
    } else {
        $output .= '<input type="hidden" name="return" value="' . home_url() . '" />';
    }

    $output .= '<div class="qpp_payment_button">';
    $output .= '<input type="image" src="' . esc_url($payment_button) . '" name="submit" alt="Make payments with payPal - it\'s fast, free and secure!" />';
    $output .= '</div>';

    $output .= '</form>';
    $output .= '</div>';
    $output .= <<<EOT
<script type="text/javascript">
jQuery(document).ready(function($) {
    $('.wp_accept_pp_button_form_classic').submit(function(e){
        var form_obj = $(this);
        var other_amt = form_obj.find('input[name=other_amount]').val();
        if (!isNaN(other_amt) && other_amt.length > 0){
            options_val = other_amt;
            //insert the amount field in the form with the custom amount
            $('<input>').attr({
                type: 'hidden',
                id: 'amount',
                name: 'amount',
                value: options_val
            }).appendTo(form_obj);
        }		
        return;
    });
});
</script>
EOT;
    /* = end of paypal form = */
    return $output;
}

function wp_ppp_process($content)
{
    if (strpos($content, "<!-- paypal_quick_pay -->") !== FALSE) {
        $content = preg_replace('/<p>\s*<!--(.*)-->\s*<\/p>/i', "<!--$1-->", $content);
        $content = str_replace('<!-- paypal_quick_pay -->', Paypal_Quick_Pay_Accept(), $content);
    }
    return $content;
}

function show_paypal_quick_pay_widget($args)
{
    extract($args);

    $paypal_quick_pay_widget_title_name_value = get_option('wp_paypal_widget_title_name');
    echo $before_widget;
    echo $before_title . $paypal_quick_pay_widget_title_name_value . $after_title;
    echo Paypal_Quick_Pay_Accept();
    echo $after_widget;
}

function paypal_quick_pay_widget_control()
{
?>
    <p>
        <? _e("Set the Plugin Settings from the Settings menu"); ?>
    </p>
<?php
    }

    function paypal_quick_pay_init()
    {
        wp_register_style('pqp-styles', QPP_PLUGIN_URL . '/qpp-styles.css');
        wp_enqueue_style('pqp-styles');

        //Widget code
        $widget_options = array('classname' => 'widget_paypal_quick_pay', 'description' => __("Display PayPal Quick Pay."));
        wp_register_sidebar_widget('paypal_quick_pay_widgets', __('PayPal Quick Pay'), 'show_paypal_quick_pay_widget', $widget_options);
        wp_register_widget_control('paypal_quick_pay_widgets', __('PayPal Quick Pay'), 'paypal_quick_pay_widget_control');

        //Listen for IPN and validate it
        if (isset($_REQUEST['qpp_paypal_ipn']) && $_REQUEST['qpp_paypal_ipn'] == "process") {
            qpp_validate_paypl_ipn();
            exit;
        }
    }

    function qpp_shortcode_plugin_enqueue_jquery()
    {
        wp_enqueue_script('jquery');
    }

    add_filter('the_content', 'wp_ppp_process');
    add_shortcode('paypal_quick_pay', 'Paypal_Quick_Pay_Accept');
    if (!is_admin()) {
        add_filter('widget_text', 'do_shortcode');
    }

    add_action('init', 'qpp_shortcode_plugin_enqueue_jquery');
    add_action('init', 'paypal_quick_pay_init');
