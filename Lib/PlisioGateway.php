<?php

namespace Plisio\PlisioGateway\Lib;

class PlisioGateway
{
    const VERSION = '1.0.0';
    const USER_AGENT_ORIGIN = 'Plisio PHP Library';

    public static $user_agent = '';
    public static $curlopt_ssl_verifypeer = false;

    public static function config($authentication)
    {
        if (isset($authentication['user_agent'])) {
            self::$user_agent = $authentication['user_agent'];
        }

        if (isset($authentication['curlopt_ssl_verifypeer'])) {
            self::$curlopt_ssl_verifypeer = $authentication['curlopt_ssl_verifypeer'];
        }
    }

    public static function request($urlPart, $method = 'GET', $params = array(), $authentication = array())
    {
        $user_agent = isset($authentication['user_agent']) ? $authentication['user_agent'] : (isset(self::$user_agent)
            ? self::$user_agent : (self::USER_AGENT_ORIGIN . ' v' . self::VERSION));
        $curlopt_ssl_verifypeer = isset($authentication['curlopt_ssl_verifypeer'])
            ? $authentication['curlopt_ssl_verifypeer'] : self::$curlopt_ssl_verifypeer;

        $url = 'https://plisio.net/api/v1/invoices/new'."?".http_build_query($params);
        $headers = array();
        $curl = curl_init();

        $curl_options = array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $url
        );

        if ($method == 'POST') {
            $headers[] = 'Content-Type: application/x-www-form-urlencoded';
            array_merge($curl_options, array(CURLOPT_POST => 1));
            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($params));
        }


        curl_setopt_array($curl, $curl_options);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_USERAGENT, $user_agent);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, $curlopt_ssl_verifypeer);


        $raw_response = curl_exec($curl);
        $decoded_response = json_decode($raw_response, true);
        $response = $decoded_response ? $decoded_response : $raw_response;
        $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        if ($http_status === 200) {
            return $response;
        } else {
            \Plisio\PlisioGateway\Lib\Exception::throwException($http_status, $response);
        }
    }
}
