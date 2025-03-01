<?php

namespace App\Traits; 
use Illuminate\Http\Request;

use Illuminate\Support\Facades\File; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Pagination\LengthAwarePaginator;
use Carbon\Carbon;

trait CurlTraits {
    
    public function CurlGetRequest($url,$headers)
    {
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers); 
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    } 
    
    public function CurlPostRequest($url,$headers,$data)
    {
        
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch,CURLOPT_POST, true);
        curl_setopt($ch,CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $result = curl_exec($ch);
        return $result;
    } 
    
    public function LeopordCurlGetRequest($url,$headers)
    {
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers); 
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    } 
    
    public function LeopordCurlPostRequest($url,$data = []) 
    {  
        $data['api_key'] = env('LEOPORD_API_KEY');
        $data['api_password'] = env('LEOPORD_API_PASSWORD'); 
        // dd($data);  
    {   
        // dd($data);
          
        $curl_handle = curl_init();
        curl_setopt($curl_handle, CURLOPT_URL, $url); // Write here Test or Production Link
        curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl_handle, CURLOPT_POST, 1);
        curl_setopt($curl_handle, CURLOPT_HTTPHEADER, ['Authorization:'.env('LEOPORD_API_KEY'), 'Accepts:' . 'application/json',"real:json content"]);
        curl_setopt($curl_handle, CURLOPT_POSTFIELDS, $data);
        
        $buffer = curl_exec($curl_handle);
        curl_close($curl_handle);
        return $buffer;

        // $curl_handle = curl_init();
        // curl_setopt($curl_handle, CURLOPT_URL, $url); // Write here Test or Production Link
        // curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
        // curl_setopt($curl_handle, CURLOPT_POST, 1);
        // curl_setopt($curl_handle, CURLOPT_POSTFIELDS, json_encode(array(
        //     'api_key' => env('Leopord_API_KEY'),
        //     'api_password' => env('Leopord_API_PASSWORD')
        // )));

        // $result = curl_exec($curl_handle);
        // return $result;
        // curl_close($curl_handle);



        // $ch = curl_init();
        // curl_setopt($ch,CURLOPT_URL, $url);
        // curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
        // curl_setopt($ch,CURLOPT_POST, true);
        // curl_setopt($ch,CURLOPT_POSTFIELDS, json_encode(array(
        //     'api_key' => env('LEOPORD_API_KEY'),
        //     'api_password' => env('LEOPORD_API_PASSWORD')
        // )));
        // curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization:'.env('LEOPORD_API_KEY'), 'Accepts:' . 'application/json',"real:json content"]);
        // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        // $result = curl_exec($ch);
        // return $result;
    } 
    
    
    
}