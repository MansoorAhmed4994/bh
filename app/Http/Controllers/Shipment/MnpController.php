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

class MnpController extends Controller
{
    use ManualOrderTraits;
    use MNPTraits;
    use TraxTraits;
    
    public function MnpCreateBulkBookingByOrderIds(Request $request)
    {  
        
        $order_ids = $request->order_ids;
        //dd($order_ids);
        //$explode_id = explode(',', $order_ids);
        $this->UpdateReferenceNumberByOrderIds($order_ids);
        $ManualOrders = $this->GetOrdersByIds($order_ids);
        $cities = $this->get_mnp_cities();
        
        return view('client.orders.manual-orders.mnp.create')->with(['ManualOrders'=>$ManualOrders, 'cities'=>$cities]);
    }
    
    
    public function mnp_bookings_store(Request $request)
    {
        //$this->get_mnp_cities();
        $mytime = Carbon::now();
        $current_date_time = $mytime->toDateTimeString();
        $id = array();

        for ($x = 0; $x < sizeof($request->reference_number); $x++) 
        {
            
            $receiver_name= $request->receiver_name[$x];
            $receiver_number= $request->receiver_number[$x];
            $city = $request->city[$x];
            $reciever_address= $request->reciever_address[$x];
            $total_pieces= $request->total_pieces[$x];
            $weight= $request->weight[$x];
            $price= $request->price[$x];
            $ManualOrder = ManualOrders::find($request->id[$x]);
            $reference_number= '('.$ManualOrder->id.')('.$current_date_time.')';
            $ManualOrder->receiver_name = $receiver_name;
            $ManualOrder->receiver_number = $receiver_number;
            $ManualOrder->city = $city;
            $ManualOrder->reciever_address = $reciever_address;
            $ManualOrder->total_pieces = $total_pieces;
            $ManualOrder->weight = $weight;
            $ManualOrder->price = $price;
            $ManualOrder->reference_number = $reference_number;
            $ManualOrder->updated_by = Auth::id();
            $status = $ManualOrder->save();
            if($status);
            {
                $data = '{"username": "'.env('MNP_API_USERNAME').'","password": "'.env('MNP_API_PASSWORD').'","consigneeName": "'.$receiver_name.'","consigneeAddress": "'.$reciever_address.'","consigneeMobNo": "'.$receiver_number.'","consigneeEmail": "string","destinationCityName": "'.$city.'","pieces": "'.$total_pieces.'","weight": "'.$weight.'","codAmount": '.$price.',"custRefNo": "'.$reference_number.'","productDetails": "string","fragile": "string","service": "overnight","remarks": "string","insuranceValue": "string","locationID": "string","AccountNo": "string","InsertType": "0"}';
                //dd($status);
                $resp = $this->create_booking($data);
                
                
                //dd($resp); 
                if($resp)
                {
                    // echo '<pre>';
                    // print_r(json_decode($resp)[0]);
                    // echo $price;
                    $ManualOrder = ManualOrders::find($request->id[$x]);
                    //dd($ManualOrder);
                    $ManualOrder->consignment_id = json_decode($resp)[0]->orderReferenceId;
                    $status = $ManualOrder->save();
                    
                    if($status)
                    {   
                        array_push($id, $request->id[$x]);
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
        
        return $this->print_mnp_slips($id);
        //
    }
    
    public function print_mnp_slips($id)
    {
        //dd($id);
        $ManualOrder = Customers::rightJoin('manual_orders', 'manual_orders.customers_id', '=', 'customers.id')->whereIn('manual_orders.id',$id)->get();
        //dd($ManualOrder);
        return view('client.orders.manual-orders.mnp.print_mnp_slip')->with('ManualOrders',$ManualOrder);
        
    }
    //
}
