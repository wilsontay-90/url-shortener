<?php

namespace App\Http\Helpers;

class ValidationHelper{

    /**
     * Parse error message from validation
     * @param Exception $e
     * @return string
     */
    public static function parseValidator($validator){

        $errors = $validator->messages()->toArray();

        return self::constructMessage($errors);
    }

    /**
     * Parse validation error message from API
     * @param $e
     * @return string
     */
    public static function parseGuzzleException($e){

        if ($e->hasResponse()) {

            $error_body = $e->getResponse()->getBody()->getContents();
            $match = json_decode($error_body, true);

            if ( isset($match['errors']) ){
                return self::constructMessage($match['errors']);
            }else{

                if ( is_array($match['message']) ){
                    return implode(',', $match['message']);
                }else{
                    return $match['message'];
                }

            }


        }else{
            return $e->getMessage();
        }


    }

    private static function constructMessage($errors){

        $output = "";

        foreach ( $errors as $key => $error ){
            $output .= implode(",", $error);
        }

        return $output;
    }

}