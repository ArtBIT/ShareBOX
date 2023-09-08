<?php 
if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/**
 * These are helpers for the google recaptcha v2
 *
 * @author      Djordje Ungar (djordje@ungar.rs)
 * @copyright   Copyright (c) 2014-2016 Djordje Ungar
 * @version     1.0.0
 * @package     ShareBOX
 * @license http://opensource.org/licenses/MIT  MIT License
 */

class ReCaptchaResponse
{
    public $is_valid;
    public $error;
}

/**
 * The reCAPTCHA server URLs
 */
define("RECAPTCHA_API_SERVER", "http://www.google.com/recaptcha/api");
define("RECAPTCHA_API_SECURE_SERVER", "https://www.google.com/recaptcha/api");
define("RECAPTCHA_VERIFY_SERVER", "https://www.google.com");

/**
 * Submits an HTTP POST to a reCAPTCHA server
 * @param string $host
 * @param string $path
 * @param array $data
 * @return array response
 */
function _recaptcha_http_post($host, $path, $data)
{
    $url = "$host/" . ltrim($path, "/");
        // use key 'http' even if you send the request to https://...
        $options = array(
            'http' => array(
                'header'  => implode("\r\n", array(
                    "Content-type: application/x-www-form-urlencoded",
                    "User-Agent: reCAPTCHA/PHP"
                )),
                'method'  => 'POST',

                'content' => http_build_query($data)
            )
        );
    $context  = stream_context_create($options);
    $result = file_get_contents($url, false, $context);
    if ($result === false) {
        die('Could not create request');
    }

    return json_decode($result, true);
}

/**
 * Gets the challenge HTML (javascript and non-javascript version).
 * This is called from the browser, and the resulting reCAPTCHA HTML widget
 * is embedded within the HTML form it was called from.
 * @param string $pubkey A public key for reCAPTCHA
 * @param string $error The error given by reCAPTCHA (optional, default is null)
 * @param boolean $use_ssl Should the request be made over ssl? (optional, default is false)

 * @return string - The HTML to be embedded in the user's form.
 */
function recaptcha_get_html($pubkey, $lang = '', $use_ssl = false)
{
    if ($pubkey == null || $pubkey == '') {
        die("To use reCAPTCHA you must get an API key from <a href='https://www.google.com/recaptcha/admin/create'>https://www.google.com/recaptcha/admin/create</a>");
    }
    
    if ($use_ssl) {
        $server = RECAPTCHA_API_SECURE_SERVER;
    } else {
        $server = RECAPTCHA_API_SERVER;
    }

    return
        "<script src='{$server}.js?hl={$lang}'></script>"
        . "<div class='g-recaptcha' data-sitekey='{$pubkey}'></div>";
}


/**
  * Calls an HTTP POST function to verify if the user's guess was correct
  * @param string $privkey
  * @param string $remoteip
  * @param string $response
  * @param array $extra_params an array of extra variables to post to the server
  * @return ReCaptchaResponse
  */
function recaptcha_check_answer($privkey, $remoteip, $response, $extra_params = array())
{
    if ($privkey == null || $privkey == '') {
        die("To use reCAPTCHA you must get an API key from <a href='https://www.google.com/recaptcha/admin/create'>https://www.google.com/recaptcha/admin/create</a>");
    }

    if ($remoteip == null || $remoteip == '') {
        die("For security reasons, you must pass the remote ip to reCAPTCHA");
    }
    //discard spam submissions
    if ($response == null || strlen($response) == 0) {
        $recaptcha_response = new ReCaptchaResponse();
        $recaptcha_response->is_valid = false;
        $recaptcha_response->error = 'incorrect-captcha-sol';
        return $recaptcha_response;
    }

    $response = _recaptcha_http_post(RECAPTCHA_VERIFY_SERVER, "/recaptcha/api/siteverify",
        array(
            'secret' => $privkey,
            'remoteip' => $remoteip,
            'response' => $response
        ) + $extra_params
    );

    $recaptcha_response = new ReCaptchaResponse();

    if ($response['success']) {
        $recaptcha_response->is_valid = true;
    } else {
        $recaptcha_response->is_valid = false;
        $recaptcha_response->error = $response['error-codes'];
    }
    return $recaptcha_response;
}
