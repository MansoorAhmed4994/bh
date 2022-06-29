<?php
namespace App\Http\Controllers\Shipment;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Client\ManualOrders;
use Illuminate\Support\Facades\File; 
use Illuminate\Support\Facades\Auth;
use App\Models\Riders;
use App\Models\Client\Customers;
use App\Traits\MNPTraits;
use App\Traits\TraxTraits;
use App\Traits\ManualOrderTraits;
use Carbon\Carbon;
use DB;

class TraxController extends Controller
{
    use ManualOrderTraits;
    use MNPTraits;
    use TraxTraits;
    
    public function CreateBulkBookingByOrderIds(Request $request)
    {  
        
        $order_ids = $request->order_ids;
        //dd($order_ids);
        //$explode_id = explode(',', $order_ids);
        $this->UpdateReferenceNumberByOrderIds($order_ids);
        $ManualOrders = $this->GetOrdersByIds($order_ids);
        $cities = $this->GetCities()->cities;
        
        return view('client.orders.manual-orders.trax.create')->with(['ManualOrders'=>$ManualOrders, 'cities'=>$cities]);
    }
    
    
    public function CreateBulkBookingStore(Request $request)
    {
        
        //dd();
        $pickup_address_id = $this->get_trax_pickup_address();
        
        //dd($request->item_product_type_id);
        $mytime = Carbon::now();
        $current_date_time = $mytime->toDateTimeString();
        $id = array();

        for ($x = 0; $x < sizeof($request->reference_number); $x++) 
        {
            //prepare parameter for create booking
            //dd($request->shipping_mode_id);
            $data = [];
            
            $receiver_name= $request->receiver_name[$x];
            $receiver_number= $request->receiver_number[$x];
            $reciever_address= $request->reciever_address[$x];
            $city = $request->city[$x];
            $total_pieces= $request->total_pieces[$x];
            $weight= $request->weight[$x];
            $price= $request->price[$x];
            $data['order_id'] = $request->id[$x];
            $data['service_type_id'] = 1;
            $data['pickup_address_id'] = $pickup_address_id;
            $data['information_display'] = 0;
            $data['consignee_city_id'] = $city;
            $data['consignee_name'] = trim($receiver_name);
            $data['consignee_address'] = trim($reciever_address);
            $data['consignee_phone_number_1'] = trim($receiver_number);
            $data['consignee_email_address'] = trim('orderstesting@brandhub.com');
            $data['item_product_type_id'] = 1;
            $data['item_description'] = trim($request->item_description[$x]);
            $data['item_quantity'] = (int)trim($total_pieces);
            $data['item_insurance'] = 0;
            $data['item_price'] = trim($request->price[$x]);
            $data['pickup_date'] = $mytime;
            $data['special_instructions'] = trim('Nothing');
            $data['estimated_weight'] = trim($request->weight[$x]);
            $data['shipping_mode_id'] = (int)trim($request->shipping_mode_id[$x]);
            $data['amount'] = (int)trim($request->price[$x]);
            $data['payment_mode_id'] = 1;
            $data['charges_mode_id'] = 4;
            
            //update manualorders
            $ManualOrder = ManualOrders::find($request->id[$x]);
            $reference_number= '('.$ManualOrder->id.')('.$current_date_time.')';
            $data['$shipper_reference_number_1'] = $reference_number;
            $ManualOrder->receiver_name = $receiver_name;
            $ManualOrder->receiver_number = $receiver_number;
            $ManualOrder->city = $city;
            $ManualOrder->reciever_address = $reciever_address;
            $ManualOrder->total_pieces = $total_pieces;
            $ManualOrder->weight = $weight;
            $ManualOrder->price = $price;
            $ManualOrder->description = trim($request->item_description[$x]);
            $ManualOrder->reference_number = $reference_number;
            $ManualOrder->updated_by = Auth::id();
            $status = $ManualOrder->save();
            
            
            
        
        //echo 'working';
            if($status);
            {
                $ApiResponse = $this->CreateBooking($data);
                //dd($ApiResponse);
                
                //DD(env('MNP_API_USERNAME')); 
                if($ApiResponse->status == 0)
                {
                    // echo '<pre>';
                    // print_r(json_decode($resp)[0]);
                    // echo $price;
                    $ManualOrder = ManualOrders::find($request->id[$x]);
                    //dd($ManualOrder);
                    $ManualOrder->consignment_id = $ApiResponse->tracking_number;
                    $ManualOrder->status = 'dispatched';
                    $status = $ManualOrder->save();
                    
                    if($status)
                    {   
                        //echo $ApiResponse->tracking_number;
                        array_push($id, $ApiResponse->tracking_number);
                        //return $this->print_trax_slips($id);
                    }
                    else
                    {
                        //dd($status);
                        //dd($ManualOrder);
                    }
                    //dd(json_decode($resp)[0]->orderReferenceId);
                }
                
            }
            
        }
            
        $slips = $this->print_trax_slips($id);
        return view('client.orders.manual-orders.trax.print_trax_slip')->with('slips',$slips);
        
        
        //dd();
    }
    
    public function get_trax_pickup_address()
    {
        $response = $this->GetPickupAddresses();
        if($response->status == 0)
        {
            return($response->pickup_addresses[0]->id);
        }
        // $baseUrl = "https://sonic.pk/"; 
        // $apiUrl = $baseUrl."api/pickup_addresses";
        // $headers = ['Authorization:'.env('TRAX_API_KEY'), 'Accepts:' . 'application/json',"real:json content"];
        // $ch = curl_init();
        // curl_setopt($ch,CURLOPT_URL, $apiUrl);
        // curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
        // curl_setopt($ch, CURLOPT_HTTPHEADER, $headers); 
        // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
        // $result = curl_exec($ch);
        
        //dd($response); 
    }
    
    public function print_trax_slips($ids)
    {
        $data=array();
        foreach($ids as $id)
        {
            $src = $this->PrintAirWayBill($id,'0');
            array_push($data, $src);
            
        }
        return $data;
    }
    
    //
}
