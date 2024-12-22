<?php

namespace App\Traits; 
use Illuminate\Http\Request;
use App\Models\Client\ManualOrders;
use App\Models\Client\Customers;

use Illuminate\Support\Facades\File; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Carbon\Carbon;
 
trait LeopordTraits {
    
    use CurlTraits;
    
    public function LeopordGetPickupAddresses()
    { 
        $apiUrl = "http://new.leopardscod.com/webservice/getAllCitiesTest/format/json/";
        $headers = ['Authorization:'.env('Leopord_API_KEY'), 'Accepts:' . 'application/json',"real:json content"];
        $response = $this->CurlGetRequest($apiUrl,$headers);
        return $response = json_decode($response);
    }
    
    public function LeopordGetCities()
    { 
        $apiUrl = "http://new.leopardscod.com/webservice/getAllCities/format/json/";
        $response = $this->LeopordCurlPostRequest($apiUrl);
        // dd($response);
        return $response = json_decode($response);
    }
    
    
    public function LeopordCreateBooking($data)
    {
        $url = "https://sonic.pk/api/shipment/book";
        $headers = ['Authorization:'.env('Leopord_API_KEY'), 'Accepts:' . 'application/json',"real:json content"];
        $response = $this->CurlPostRequest($url,$headers,$data);
        return $response = json_decode($response);
    }
    
    
    public function LeopordPrintAirWayBill($tracking_number, $print_type)
    {
        //dd(asset('storage/file.txt'));
        $url = "https://sonic.pk/api/shipment/air_waybill?tracking_number=".$tracking_number.'&type='.$print_type;
        $headers = ['Authorization:'.env('Leopord_API_KEY'),'Content-Encoding: none','Content-Type: application/jpeg'];
        $timeout = 30;
        $response = $this->CurlGetRequest($url,$headers);
        $imageData = base64_encode($response);
        $src = 'data:image/png;base64,'.$imageData;
        return $src;
    }
    
    
    public function TrackLeopordOrder($tracking_number,$print_type)
    {
        $apiUrl = "https://sonic.pk/api/shipment/track?tracking_number=".$tracking_number.'&type='.$print_type;
        $headers = ['Authorization:'.env('Leopord_API_KEY'), 'Accepts:' . 'application/json',"real:json content"];
        $response = $this->CurlGetRequest($apiUrl,$headers);
        return $response = json_decode($response);
    } 
    
    
    public function GetTariffDetails($weight,$shipment_type,$origion_city,$destination_city,$cod)
    {
        $apiUrl = 'http://new.leopardscod.com/webservice/getTariffDetails/format/json/?api_key=F360CC23BE3ECC6224374178A9B21BD3&api_password='.(env('LEOPORD_API_PASSWORD')).'&packet_weight='.$weight.'&shipment_type='.$shipment_type.'&origin_city='.$origion_city.'&destination_city='.$destination_city.'&cod_amount='.$cod;
        $headers = ['Authorization:'.env('Leopord_API_KEY'), 'Accepts:' . 'application/json',"real:json content"];
        $response = $this->CurlGetRequest($apiUrl,$headers);
        $response = json_decode($response);
        return($response);

    }
    
    
    public function LeopordGetShipmentPaymentStatus($data)
    {
        $apiUrl = "https://sonic.pk/api/shipment/payments?tracking_number=".$data;
        $headers = ['Authorization:'.env('Leopord_API_KEY'), 'Accepts:' . 'application/json',"real:json content"];
        $response = $this->CurlGetRequest($apiUrl,$headers);
        return $response = json_decode($response);
    }
    // public function TestGetPickupAddresses($tracking_number,$print_type)
    // { 
    //     $apiUrl = "https://sonic.pk/api/shipment/track?tracking_number=".$tracking_number.'&type='.$print_type;
    //     $headers = ['Authorization:'.env('TRAX_API_KEY'), 'Accepts:' . 'application/json',"real:json content"];
    //     $response = $this->CurlGetRequest($apiUrl,$headers);
    //     return $response = json_decode($response);
    // }
     
    
    
    
}