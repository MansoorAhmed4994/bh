<?php

namespace App\Traits; 
use Illuminate\Http\Request;
use App\Models\Client\ManualOrders;
use App\Models\Client\Customers;

use Illuminate\Support\Facades\File; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Pagination\LengthAwarePaginator;
use Carbon\Carbon;

trait MNPTraits {
    
    // public function MnpCurlGetRequest($url)
    // {
    //     $ch = curl_init($url);
    //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    //     $response = curl_exec($ch);
    //     curl_close($ch);
    //     return $response;
    // } 
    
    // public function MnpCurlPostRequest($url,$data)
    // {
    //     $curl = curl_init($url);
    //     curl_setopt($curl, CURLOPT_URL, $url);
    //     curl_setopt($curl, CURLOPT_POST, true);
    //     curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    //     $headers = array("Content-Type: application/json");
    //     curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    //     curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    //     curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    //     curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    //     $resp = curl_exec($curl);
    //     curl_close($curl);
    //     return $resp;
    // } 
    
    public function create_booking($data)
    {
        $url = "http://mnpcourier.com/mycodapi/api/Booking/InsertBookingData";
        $headers = array("Content-Type: application/json");
        $resp = $this->CurlPostRequest($url,$headers,$data);
        return $resp;
    }
    
    public function get_mnp_cities()
    {
        $headers = array("Content-Type: application/json");
        $cities = $this->CurlGetRequest('http://mnpcourier.com/mycodapi/api/Branches/Get_Cities?username='.env('MNP_API_USERNAME').'&password='.env('MNP_API_PASSWORD').'&AccountNo=11',$headers);
        //echo '<br>';
        $cities = json_decode($cities)[0]->City;
        return $cities;
        //dd($cities);
    }
    
    public function TrackMnpOrder($id)
    {
        $url = 'http://mnpcourier.com/mycodapi/api/Tracking/Consignment_Tracking_Location?username='.env('MNP_API_USERNAME').'&password='.env('MNP_API_PASSWORD').'&locationID=8103&consignment='.$id;
        // 'http://mnpcourier.com/mycodapi/api/Branches/Get_Cities?username='.env('MNP_API_USERNAME').'&password='.env('MNP_API_PASSWORD').'&AccountNo=11'
        $headers = array("Content-Type: application/json");
        $tracking_details = $this->CurlGetRequest($url,$headers);
        //echo '<br>';
        $tracking_details = json_decode($tracking_details)[0];
        return $tracking_details;
        //dd($cities);
    }
    
    
    
}