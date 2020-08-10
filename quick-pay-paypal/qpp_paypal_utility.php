<?php

function qpp_validate_paypl_ipn() {

    $qpp_ipn_validated = true;
    
    // Reading POSTed data directly from POST causes serialization issues with array data in the POST.
    // Instead, read raw POST data from the input stream. 
    $raw_post_data = file_get_contents('php://input');
    $raw_post_array = explode('&', $raw_post_data);
    $myPost = array();
    foreach ($raw_post_array as $keyval) {
        $keyval = explode('=', $keyval);
        if (count($keyval) == 2)
            $myPost[$keyval[0]] = urldecode($keyval[1]);
    }
    
    // read the IPN message sent from PayPal and prepend 'cmd=_notify-validate'
    $req = 'cmd=_notify-validate';
    if (function_exists('get_magic_quotes_gpc')) {
        $get_magic_quotes_exists = true;
    }
    foreach ($myPost as $key => $value) {
        if ($get_magic_quotes_exists == true && get_magic_quotes_gpc() == 1) {
            $value = urlencode(stripslashes($value));
        } else {
            $value = urlencode($value);
        }
        $req .= "&$key=$value";
    }


    // Step 2: POST IPN data back to PayPal to validate
    $myPost['method'] = 'POST';
	$myPost['timeout'] = 30;

    $res = wp_remote_post('https://www.paypal.com/cgi-bin/webscr', $myPost);

    // Inspect IPN validation result and act accordingly
    if (strcmp ($res, "VERIFIED") == 0) {
        // The IPN is verified, process it
        $qpp_ipn_validated = true;
    } else if (strcmp ($res, "INVALID") == 0) {
        // IPN invalid, log for manual investigation
        $qpp_ipn_validated = false;
    }


    if (!$qpp_ipn_validated) {
        // IPN validation failed. Email the admin to notify this event.
        $admin_email = get_bloginfo('admin_email');
        $subject = 'IPN validation failed for a payment';
        $body = "This is a notification email from the WP Accept PayPal Payment plugin letting you know that a payment verification failed." .
        "\n\nPlease check your paypal account to make sure you received the correct amount in your account before proceeding" .
        wp_mail($admin_email, $subject, $body);
        exit;
    }
}
