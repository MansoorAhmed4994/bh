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
        $data['api_key'] = env('LEOPORD_API_KEY');
        $data['api_password'] = env('LEOPORD_API_PASSWORD');
        $apiUrl = "http://new.leopardscod.com/webservice/getAllCities/format/json/";
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
        $url = "http://new.leopardscod.com/webservice/trackBookedPacket/format/json/";
        $headers = ['Authorization:'.env('LEOPORD_API_KEY'), 'Accepts:' . 'application/json',"real:json content"];
        $response = $this->LeopordCurlPostRequest($url,$data);
        return $response = json_decode($response);
    }
    
    
    public function LeopordCreateBooking($data)
    {
        $url = "http://new.leopardscod.com/webservice/bookPacket/format/json/";
        $headers = ['Authorization:'.env('LEOPORD_API_KEY'), 'Accepts:' . 'application/json',"real:json content"];
        $response = $this->LeopordCurlPostRequest($url,json_decode($data));
        return $response = json_decode($response);
    }
    
    
    // public function LeopordGetCnDetails($tracking_number)
    // {
    //     $data = array(
    //     'api_key' => env('LEOPORD_API_KEY'),
    //     'api_password' => env('LEOPORD_API_PASSWORD'),
    //     'track_numbers' => $tracking_number
    //     );
    //     $url = "https://merchantapistaging.leopardscourier.com/api/trackBookedPacket/format/json/";
    //     $headers = ['Authorization:'.env('LEOPORD_API_KEY'), 'Accepts:' . 'application/json',"real:json content"];
    //     $response = $this->LeopordCurlPostRequest($url,$data);
    //     return $response = json_decode($response);
    // }
    
    
    public function LeopordPrintAirWayBill($slip)
    {
        
        return Redirect::to($slip);
        // $apiUrl = 'http://new.leopardscod.com/webservice/getPaymentDetails/format/json/?api_key='.env('LEOPORD_API_KEY').'&api_password='.(env('LEOPORD_API_PASSWORD')).'&cn_numbers='.$tracking_number;
        // // $apiUrl = 'http://new.leopardscod.com/webservice/getTariffDetails/format/json/?api_key='.env('LEOPORD_API_KEY').'&api_password='.(env('LEOPORD_API_PASSWORD')).'&packet_weight='.$weight.'&shipment_type='.$shipment_type.'&origin_city='.(env('LEOPORD_ORIGIN_CITY')).'&destination_city='.$destination_city.'&cod_amount='.$cod;
        // $headers = ['Authorization:'.env('Leopord_API_KEY'), 'Accepts:' . 'application/json',"real:json content"];
        // $response = $this->CurlGetRequest($apiUrl,$headers);
        // $response = json_decode($response);
        // return($response);
    }
    
    
    public function GetTariffDetails($weight,$shipment_type,$origion_city,$destination_city,$cod)
    {
        $apiUrl = 'http://new.leopardscod.com/webservice/getTariffDetails/format/json/?api_key='.env('LEOPORD_API_KEY').'&api_password='.(env('LEOPORD_API_PASSWORD')).'&packet_weight='.$weight.'&shipment_type='.$shipment_type.'&origin_city='.(env('LEOPORD_ORIGIN_CITY')).'&destination_city='.$destination_city.'&cod_amount='.$cod;
        $headers = ['Authorization:'.env('Leopord_API_KEY'), 'Accepts:' . 'application/json',"real:json content"];
        $response = $this->CurlGetRequest($apiUrl,$headers);
        $response = json_decode($response);
        return($response);

    } 
    
    
    public function GetShippingCharges($tracking_number)
    {
        $apiUrl = 'http://new.leopardscod.com/webservice/getShippingCharges/format/json/?api_key='.env('LEOPORD_API_KEY').'&api_password='.(env('LEOPORD_API_PASSWORD')).'&cn_numbers='.$tracking_number;
        // $apiUrl = 'http://new.leopardscod.com/webservice/getTariffDetails/format/json/?api_key='.env('LEOPORD_API_KEY').'&api_password='.(env('LEOPORD_API_PASSWORD')).'&packet_weight='.$weight.'&shipment_type='.$shipment_type.'&origin_city='.(env('LEOPORD_ORIGIN_CITY')).'&destination_city='.$destination_city.'&cod_amount='.$cod;
        $headers = ['Authorization:'.env('Leopord_API_KEY'), 'Accepts:' . 'application/json',"real:json content"];
        $response = $this->CurlGetRequest($apiUrl,$headers);
        $response = json_decode($response);
        return($response);

    } 
    
    
    public function TrackLeopordOrder($tracking_number,$print_type)
    {
        $apiUrl = "https://sonic.pk/api/shipment/track?tracking_number=".$tracking_number.'&type='.$print_type;
        $headers = ['Authorization:'.env('LEOPORD_API_KEY'), 'Accepts:' . 'application/json',"real:json content"];
        $response = $this->CurlGetRequest($apiUrl,$headers);
        return $response = json_decode($response);
    } 
    
    public function GetShipperDetails()
    {
        // return 123;
        $apiUrl = 'http://new.leopardscod.com/webservice/getShipperDetails/format/json/?api_key='.env('LEOPORD_API_KEY').'&api_password='.(env('LEOPORD_API_PASSWORD')).'&request_param=request_param&request_value=request_value';
        $headers = ['Authorization:'.env('LEOPORD_API_KEY'), 'Accepts:' . 'application/json',"real:json content"];
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