<?php
/**
 * CURL.php
 * 
 * Time: 16/6/16 13:50
 * @author leohu <cqleohu@qq.com>
 */

namespace alpha1130\curl;


/**
 * Class CURL
 * @package alpha1130\curl
 *
 * curl request object
 */
class CURL {

    /**
     * make http get request
     *
     * @param string $url url string
     * @param array $params http get params
     * @param array $opts curl options like CURLOPT_XXX
     * @return string http response body string
     * @throws CURLException
     */
    public static function get($url, array $params = array(), array $opts = array()) {

        if($params) {
            $sep = strpos($url, '?') === false ? '?' : '&';
            echo $url = $url . $sep . http_build_query($params);
        }

        $opts[CURLOPT_HTTPGET] = true;

        return self::request(array(CURLOPT_URL => $url) + $opts);

    }

    /**
     * make http post request
     *
     * @param string $url url string
     * @param array $params http post params
     * @param array $opts curl options like CURLOPT_XXX
     * @return string http response body string
     * @throws CURLException
     */
    public static function post($url, $params = array(), array $opts = array()) {

        $opts[CURLOPT_URL] = $url;
        $opts[CURLOPT_POSTFIELDS] = $params;
        $opts[CURLOPT_POST] = true;

        return self::request($opts);

    }

    /**
     * make curl invoke
     *
     * @param array $opts curl options like CURLOPT_XXX
     * @return string response string
     * @throws CURLException
     */
    public static function request(array $opts) {

        $ch = curl_init();
        if($ch === false) {
            throw new CURLException('curl init failed');
        }

        $opts += array(
            CURLOPT_TIMEOUT => 5,
            CURLOPT_RETURNTRANSFER => true,
        );

        if(!curl_setopt_array($ch, $opts)) {
            throw new CURLException('curl setup error');
        }

        $resp = curl_exec($ch);
        $errno = curl_errno($ch);
        $error = curl_error($ch);
        curl_close($ch);

        if($errno !== 0) {
            throw new CURLException('curl exec with error: ' . $error);
        }

        return $resp;

    }

}



