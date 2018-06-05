<?php

namespace App\Http\Controllers;

use App\Http\Helpers\ValidationHelper;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\SimplifyUrlRequest;


class SimplifyUrlController extends Controller
{

    function __construct()
    {

    }

    /**
     * landing page
     *
     */

    public function index(){
        return view('landing');
    }

    /**
     * handle the post request from the form
     *
     */

    public function doSimplifyUrl(){

        $input = request()->all();

        //Validate the user input url
        $validator = Validator::make($input, [
            'url' => 'required|url',
        ]);

        //response message
        if ($validator->fails()) {
            return response()->json(['status' => '0','message' => 'Unable to create shorten URL']);
        }

        try{
            //generate the url from bitly
            $bitlyURL = app('bitly')->getUrl($input['url']);

            //store on the txt file
            \Storage::disk('local')->append('url.txt', "Original URL:" . $input['url'] . " Bitly URL:" . $bitlyURL);

            //response message
            return response()->json([
                'status' => '1',
                'message' => 'Success',
                'url' => $input['url'],
                'bitly' => $bitlyURL,
            ]);

        }catch(\Exception $e){
            return response()->json(['status' => '0','message' => 'Unable to create shorten URL']);
        }
    }
}