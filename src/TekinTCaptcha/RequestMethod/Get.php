<?php

namespace TekinTCaptcha\RequestMethod;

use TekinTCaptcha\RequestMethod;
use TekinTCaptcha\RequestParameters;

/**
 * Sends POST requests to the TekinTCaptcha service.
 */
class Get implements RequestMethod
{
    /**
     * URL to which requests are POSTed.
     * @const string
     */
    const TCAPTCHA_VERIFY_URL = 'https://ssl.captcha.qq.com/ticket/verify';

    /**
     * Submit the POST request with the specified parameters.
     *
     * @param RequestParameters $params Request parameters
     * @return string Body of the TekinTCaptcha response
     */
    public function submit(RequestParameters $params)
    {
        /**
         * PHP 5.6.0 changed the way you specify the peer name for SSL context options.
         * Using "CN_name" will still work, but it will raise deprecated errors.
         */
        $peer_key = version_compare(PHP_VERSION, '5.6.0', '<') ? 'CN_name' : 'peer_name';
        $options = array(
            'http' => array(
                // 'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                'method' => 'GET',
                'content' => $params->toQueryString(),
                // Force the peer to validate (not needed in 5.6.0+, but still works)
                 // 'verify_peer' => true,
                // Force the peer validation to use www.google.com
                 // $peer_key => 'www.yunnan.ws',
            ),
        );
        
        $context = stream_context_create($options);

        return file_get_contents(self::TCAPTCHA_VERIFY_URL, false, $context);
    }
}
