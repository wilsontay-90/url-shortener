<?php

namespace App\Http\Controllers;

use App\Http\Helpers\ValidationHelper;
use Illuminate\Support\Facades\Validator;
use App\Http\Services\SimplifyUrlService;
use App\Http\Requests\SimplifyUrlRequest;


class SimplifyUrlController extends Controller
{
    protected $simplifyUrlService;

    function __construct(SimplifyUrlService $simplifyUrlService)
    {
        $this->simplifyUrlService = $simplifyUrlService;
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
            //configure the parameters
            $params = array();
            $params['access_token'] = env('BITLY_ACCESS_TOKEN');
            $params['longUrl'] = $input['url'];
            $params['domain'] = env('BITLY_DOMAIN');

            //simplify the long url
            //generate the url from bitly
            $results = $this->simplifyUrlService->bitly_get('shorten', $params);

            if(isset($results['data'])){

                //store on the txt file
                \Storage::disk('local')->append('url.txt', "Original URL:" . $input['url'] . " Bitly URL:" . $results['data']['url']);

                //response message
                return response()->json([
                    'status' => '1',
                    'message' => 'Success',
                    'url' => $input['url'],
                    'bitly' => $results['data']['url'],
                ]);
            }else{
                return response()->json(['status' => '0','message' => 'Unable to create shorten URL']);
            }

            //third party to get the
            //$bitlyURL = app('bitly')->getUrl($input['url']);

        }catch(\Exception $e){
            return response()->json(['status' => '0','message' => 'Unable to create shorten URL']);
        }
    }
}