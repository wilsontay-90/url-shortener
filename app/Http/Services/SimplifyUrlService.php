<?php

namespace App\Http\Services;


class SimplifyUrlService{

    protected $output;
    protected $bitly_oauth_api;
    protected $result;
    protected $url;

    public function __construct()
    {
        $this->bitly_oauth_api = env('BITLY_OAUTH_API');
        $this->result = array();
        $this->output = '';
        $this->url = '';
    }

    /**
     * Format a GET call to the bit.ly API.
     *
     * @param $endpoint
     *   bit.ly API endpoint to call.
     * @param $params
     *   associative array of params related to this call.
     * @param $complex
     *   set to true if params includes associative arrays itself (or using <php5)
     *
     * @return
     *   associative array of bit.ly response
     *
     * @see http://code.google.com/p/bitly-api/wiki/ApiDocumentation#/v3/validate
     */

    public function bitly_get($endpoint , $params, $complex=false ){

        if ($complex) {
            $url_params = "";
            foreach ($params as $key => $val) {
                if (is_array($val)) {
                    // we need to flatten this into one proper command
                    $recs = array();
                    foreach ($val as $rec) {
                        $tmp = explode('/', $rec);
                        $tmp = array_reverse($tmp);
                        array_push($recs, $tmp[0]);
                    }
                    $val = implode('&' . $key . '=', $recs);
                }
                $url_params .= '&' . $key . "=" . $val;
            }
            $url = $this->bitly_oauth_api . $endpoint . "?" . substr($url_params, 1);
        }else{
            $url = $this->bitly_oauth_api . $endpoint . "?" . http_build_query($params);
        }

        $result = json_decode($this->bitly_get_curl($url), true);

        return $result;
    }

    /**
     * Make a GET call to the bit.ly API
      * @param mixed $uri
      * @return array
     * URI to call.
     */
    public function bitly_get_curl($uri){

        try {
            $ch = curl_init($uri);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_TIMEOUT, 4);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

            $this->output = curl_exec($ch);

        } catch (Exception $e) {

            return null;
        }

        return $this->output;
    }



}