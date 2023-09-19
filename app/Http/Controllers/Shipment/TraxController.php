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
            // dd($request->id[$x]);
            $data = [];
            
            $receiver_name= $request->receiver_name[$x];
            $receiver_number= $request->receiver_number[$x];
            $reciever_address= $request->reciever_address[$x];
            $city = $request->city[$x];
            $total_pieces= $request->total_pieces[$x];
            $weight= $request->weight[$x];
            $cod_amount = $request->cod_amount[$x];
            $advance_payment = $request->advance_payment[$x]; 
            $price= $request->price[$x];
            $fare = $request->fare[$x];
            
            
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
            $data['item_price'] = trim($price);
            $data['pickup_date'] = $mytime;
            $data['special_instructions'] = trim('Nothing');
            $data['estimated_weight'] = trim($request->weight[$x]);
            $data['shipping_mode_id'] = (int)trim($request->shipping_mode_id[$x]);
            $data['amount'] = (int)trim($cod_amount);
            $data['payment_mode_id'] = 1;
            $data['charges_mode_id'] = 4;
            
            //update manualorders
            $ManualOrder = ManualOrders::find($request->id[$x]);
            $reference_number= '('.$ManualOrder->id.')('.$current_date_time.')';
            $data['$shipper_reference_number_1'] = $reference_number;
            $ManualOrder->receiver_name = $receiver_name;
            $ManualOrder->receiver_number = $receiver_number;
            $ManualOrder->cities_id = $city;
            $ManualOrder->reciever_address = $reciever_address;
            $ManualOrder->total_pieces = $total_pieces;
            $ManualOrder->weight = $weight;
            $ManualOrder->price = $price;
            $ManualOrder->advance_payment = $advance_payment;
            $ManualOrder->cod_amount = $cod_amount;
            
            $ManualOrder->fare = $fare;
            $ManualOrder->description = trim($request->item_description[$x]);
            $ManualOrder->reference_number = $reference_number;
            $ManualOrder->updated_by = Auth::id();
            $status = $ManualOrder->save();
            
            
            
            $error_creating = '';
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
                else
                {
                    $error_creating = 'These shipments not created';
                    
                    //dd($ApiResponse);die;
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
    
    public function calculate_charges(Request $request)
    {
        $best_fare = array();
        $data['service_type_id'] = 1;
        $data['origin_city_id'] = 202;
        $data['destination_city_id'] = $request->destination_city_id;
        $data['estimated_weight'] = $request->estimated_weight;
        $data['shipping_mode_id'] = $request->shipping_mode_id;
        $data['amount'] = $request->amount;
        $calculation =  $this->CalculateDestinationRates($data);
        // dd($data,$calculation);
        return response()->json(['data' => $calculation]);
    }
    
    public function get_fare_list(Request $request)
    {
        $best_fare_result=0;
        $best_fare=[];
        $data['service_type_id'] = 1;
        $data['origin_city_id'] = 202;
        $data['destination_city_id'] = $request->destination_city_id;
        $data['estimated_weight'] = $request->estimated_weight;
        $data['shipping_mode_id'] = 1;
        $data['amount'] = $request->amount;
        $calculation =  $this->CalculateDestinationRates($data);
        
        
        if($calculation->status == 0)
        {
            //dd();
            // dd($best_fare[0]['fare']);
            $charges = $calculation->information->charges;
            $total_charges = $charges->total_charges;
            $gst = $charges->gst;
            //$best_fare[0]['fare']=($total_charges+$gst);
            $best_fare_result=1; 
            array_push($best_fare,array(
                
            'shipping_mode_id' => '1',
            'shippment' => 'Rush',
            'fare'=>$total_charges+$gst,
            ));
            // echo '<pre>';print_r($calculation);
            
        }
            // echo '<pre>';print_r($calculation);
        
        $data['shipping_mode_id'] = 2;
        $calculation =  $this->CalculateDestinationRates($data);
        if($calculation->status == 0)
        {
            
            // dd('checking shipment 2',$calculation);
            $charges = $calculation->information->charges; 
            $total_charges = $charges->total_charges;
            $gst = $charges->gst;
            // $best_fare[1]['fare'] =($total_charges+$gst);
            $best_fare_result=1; 
            array_push($best_fare,array(
                
            'shipping_mode_id' => '2',
            'shippment' => 'Saver Plus',
            'fare'=>$total_charges+$gst,
            ));
            
            // echo '<pre>';print_r($data);
            // echo '<pre>';print_r($calculation);
            // echo '<pre>';print_r($best_fare);
        }
            // echo '<pre>';print_r($calculation);
        
        $data['shipping_mode_id'] = 3;
        $calculation =  $this->CalculateDestinationRates($data);
        if($calculation->status == 0)
        { 
            $charges = $calculation->information->charges;
            $total_charges = $charges->total_charges;
            $gst = $charges->gst;
            // $best_fare[2]['fare'] =($total_charges+$gst);
            $best_fare_result=1;
             
            array_push($best_fare,array(
                
            'shipping_mode_id' => '3',
            'shippment' => 'Swift',
            'fare'=>$total_charges+$gst,
            )); 
        }
            // echo '<pre>';print_r($calculation);
            
            
        if($best_fare_result == 1)
        {
            return response()->json(['data' => $calculation,'best_fare'=>$best_fare]);
        }
        
        // if($calculation->status != 1)
        // {
        //     // dd($calculation);
        //     $charges = $calculation->information->charges;
        //     $total_charges = $charges->total_charges;
        //     $gst = $charges->gst;
        //     $best_fare[] =($total_charges+$gst);
            
        //     $data['shipping_mode_id'] = 3;
        //     $calculation =  $this->CalculateDestinationRates($data);
        //     $charges = $calculation->information->charges;
        //     $total_charges = $charges->total_charges;
        //     $gst = $charges->gst;
        //     $best_fare[] =($total_charges+$gst);
            
        //     $data['shipping_mode_id'] = 2;
        //     $calculation =  $this->CalculateDestinationRates($data);
        //     $charges = $calculation->information->charges;
        //     $total_charges = $charges->total_charges;
        //     $gst = $charges->gst;
        //     $best_fare[] =($total_charges+$gst);
            
        //     $final_fare=[];
        //     sort($best_fare); 
        //     $arrlength = count($best_fare);
        //     for($x = 0; $x < $arrlength; $x++) {
        //       $final_fare[] = $best_fare[$x]; 
        //     }
            
        //     //sort($best_fare);
        //     //dd($best_fare);
        //     $charges->total_charges = $final_fare[0];
        //     return response()->json(['data' => $calculation]);
            
        // }
        return response()->json(['data' => $calculation,'best_fare'=>$best_fare]);
        
    }
     
}
