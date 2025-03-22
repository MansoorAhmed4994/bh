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
        $apiUrl = "https://merchantapi.leopardscourier.com/api/getAllCities/format/json/";
        $headers = ['Authorization:'.env('Leopord_API_KEY'), 'Accepts:' . 'application/json',"real:json content"];
        $response = $this->CurlGetRequest($apiUrl,$headers);
        return $response = json_decode($response);
    }
    
    public function LeopordGetCities()
    { 
        $data['api_key'] = env('LEOPORD_API_KEY');
        $data['api_password'] = env('LEOPORD_API_PASSWORD');
        $apiUrl = "https://merchantapi.leopardscourier.com/api/getAllCities/format/json/";
        $response = $this->LeopordCurlPostRequest($apiUrl,$data);
        // dd($response);
        return $response = json_decode($response);
    }
    
    public function LeopordTrackBookedPacket($tracking_number)
    { 
        $data = array(
        'api_key' => env('LEOPORD_API_KEY'),
        'api_password' => env('LEOPORD_API_PASSWORD'),
        'track_numbers' => $tracking_number
        );
        $url = "https://merchantapi.leopardscourier.com/api/trackBookedPacket/format/json/";
        $headers = ['Authorization:'.env('LEOPORD_API_KEY'), 'Accepts:' . 'application/json',"real:json content"];
        $response = $this->LeopordCurlPostRequest($url,$data);
        return $response = json_decode($response);
    }
    
    
    public function LeopordCreateBooking($data)
    {
        $url = "https://merchantapi.leopardscourier.com/api/bookPacket/format/json/";
        $headers = ['Authorization:'.env('LEOPORD_API_KEY'), 'Accepts:' . 'application/json',"real:json content"];
        $response = $this->LeopordCurlPostRequest($url,json_decode($data,true));
        return $response = json_decode($response);
    } 
    
  
    public function LeopordPrintAirWayBill($slip)
    {
        
        return Redirect::to($slip); 
    } 
     
    
    public function GetTariffDetails($weight,$shipment_type,$origion_city,$destination_city,$cod)
    {
        // dd(env('LEOPORD_ORIGIN_CITY'));
        // dd('s');
        $apiUrl = 'https://merchantapi.leopardscourier.com/api/getTariffDetails/format/json/?api_key='.env('LEOPORD_API_KEY').'&api_password='.(env('LEOPORD_API_PASSWORD')).'&packet_weight='.$weight.'&shipment_type='.$shipment_type.'&origin_city='.(env('LEOPORD_ORIGIN_CITY')).'&destination_city='.$destination_city.'&cod_amount='.$cod;
        
        
        $headers = ['Authorization:'.env('Leopord_API_KEY'), 'Accepts:' . 'application/json',"real:json content"];
        $response = $this->CurlGetRequest($apiUrl,$headers);
        $response = json_decode($response);
        return($response);

    } 
    
    
    public function GetShippingCharges($tracking_number)
    {
        $apiUrl = 'https://merchantapi.leopardscourier.com/api/getShippingCharges/format/json/?api_key='.env('LEOPORD_API_KEY').'&api_password='.(env('LEOPORD_API_PASSWORD')).'&cn_numbers='.$tracking_number;
        // $apiUrl = 'http://new.leopardscod.com/webservice/getTariffDetails/format/json/?api_key='.env('LEOPORD_API_KEY').'&api_password='.(env('LEOPORD_API_PASSWORD')).'&packet_weight='.$weight.'&shipment_type='.$shipment_type.'&origin_city='.(env('LEOPORD_ORIGIN_CITY')).'&destination_city='.$destination_city.'&cod_amount='.$cod;
        $headers = ['Authorization:'.env('Leopord_API_KEY'), 'Accepts:' . 'application/json',"real:json content"];
        $response = $this->CurlGetRequest($apiUrl,$headers);
        $response = json_decode($response);
        return($response);

    } 
    
    
    public function TrackLeopordOrder($tracking_number)
    {
        $apiUrl = 'https://merchantapi.leopardscourier.com/api/trackBookedPacket/format/json/?api_key='.env('LEOPORD_API_KEY').'&api_password='.(env('LEOPORD_API_PASSWORD')).'&track_numbers='.$tracking_number;
        $headers = ['Authorization:'.env('LEOPORD_API_KEY'), 'Accepts:' . 'application/json',"real:json content"];
        $response = $this->CurlGetRequest($apiUrl,$headers);
        return $response = json_decode($response);
    } 
    
    public function LeopordGetShipperDetails()
    {
        // return 123;
        $apiUrl = 'http://new.leopardscod.com/webservice/getShipperDetails/format/json/?api_key='.env('LEOPORD_API_KEY').'&api_password='.(env('LEOPORD_API_PASSWORD')).'&request_param=shipper_detail&request_value=test';
        $headers = ['Authorization:'.env('LEOPORD_API_KEY'), 'Accepts:' . 'application/json',"real:json content"];
        $response = $this->CurlGetRequest($apiUrl,$headers);
        $response = json_decode($response);
        return($response);
    }
    
    
    
    
    public function LeopordGenerateLoadsheet($ids)
    {
        $data = array(
        'api_key' => '503AFB5BBBD7779D4DA0A3BCC4082076',
        'api_password' => 'MANSOOR1@3',
        'cn_numbers' => array('154147828'),
        'courier_name' => 'Mansoor',
        'courier_code' => '1@3'
        );
        $url = "https://merchantapi.leopardscourier.com/api/generateLoadSheet/format/json/";
        $headers = ['Authorization:'.env('LEOPORD_API_KEY'), 'Accepts:' . 'application/json',"real:json content"];
        $response = $this->LeopordCurlPostRequest($url,$data);
        dd($response);
        return $response = json_decode($response);
        
        
        // $data = json_encode(array(
        //         'api_key' => (env('LEOPORD_API_KEY')),
        //         'api_password' => (env('LEOPORD_API_PASSWORD')),
        //         'cn_numbers' => array('KI796397020'),
        //         'courier_name' => 'Mansoor',
        //         'courier_code' => '1@3'
        //         ));
        // $url = "https://merchantapi.leopardscourier.com/api/generateLoadSheet/format/json/";
        // $headers = ['Authorization:'.env('LEOPORD_API_KEY'), 'Accepts:' . 'application/json',"real:json content"];
        // $response = $this->LeopordCurlPostRequest($url,json_decode($data,true));
        // dd($response);
        // return $response = json_decode($response);
        
        
        
        
        // $curl_handle = curl_init();
        // curl_setopt($curl_handle, CURLOPT_URL,
        // 'https://merchantapi.leopardscourier.com/api/generateLoadSheet/format/json/'); // Write here 
        // curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
        // curl_setopt($curl_handle, CURLOPT_POST, 1);
        // curl_setopt($curl_handle, CURLOPT_HTTPHEADER, ['Authorization:'.env('LEOPORD_API_KEY'), 'Accepts:' . 'application/json',"real:json content"]);
        // curl_setopt($curl_handle, CURLOPT_POSTFIELDS, json_encode(array(
        // 'api_key' => env('LEOPORD_API_KEY'),
        // 'api_password' => env('LEOPORD_API_PASSWORD'),
        // 'cn_numbers' => array('KI796397020'), // E.g. array('XXYYYYYYYY') OR array('XXYYYYYYY1', 
        // 'courier_name' => 'Mansoor',
        // 'courier_code' => '1@3'
        // )));
        // dd(curl_exec($curl_handle));
        // curl_close($curl_handle);
        // return $buffer;
        
 
    }
    
    // public function TestGetPickupAddresses($tracking_number,$print_type)
    // { 
    //     $apiUrl = "https://sonic.pk/api/shipment/track?tracking_number=".$tracking_number.'&type='.$print_type;
    //     $headers = ['Authorization:'.env('TRAX_API_KEY'), 'Accepts:' . 'application/json',"real:json content"];
    //     $response = $this->CurlGetRequest($apiUrl,$headers);
    //     return $response = json_decode($response);
    // }
     
     
    
    
    
}