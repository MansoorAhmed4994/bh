<?php

namespace App\Http\Controllers\Shipment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Traits\LeopordTraits;

use App\Models\Client\ManualOrders;

class LeopordController extends Controller
{ 
    use LeopordTraits;
    
    public function LeopordCalculateDeliveryCharges(Request $request )
    {
        $weight=$request->estimated_weight;
        $shipment_type= $request->shipping_mode_id;
        $origion_city=env('LEOPORD_ORIGIN_CITY');
        $destination_city= $request->destination_city_id;
        $cod=(Int)$request->amount;
        
        // $best_fare = array();
        // $data['service_type_id'] = 1;
        // $data['origin_city_id'] = 202;
        // $data['destination_city_id'] = $request->destination_city_id;
        // $data['estimated_weight'] = $request->estimated_weight;
        // $data['shipping_mode_id'] = $request->shipping_mode_id;
        // $data['amount'] = (Int)$request->amount;
        
        $weight = (int)(trim($weight)*1000);
        $allfare=[]; 
        $final_delivery_charges=0;
        
        
        // =======================detain
        $details = $this->GetTariffDetails($weight,$shipment_type,$origion_city,$destination_city,$cod);
        if($details->status == 0)
        {
            return response()->json(['success'=>'0','messege' => $details->error]);
            
        }
        else if($details->status == 1)
        {
            $packet_charges_details = $details->packet_charges; 
            $final_delivery_charges = $packet_charges_details->shipment_charges+$packet_charges_details->cash_handling+$packet_charges_details->insurance_charges+$packet_charges_details->gst_amount+$packet_charges_details->fuel_surcharge_amount;

            return response()->json(['data' => $details,'fare'=> $final_delivery_charges]);
        }
        else
        {
            return response()->json(['success'=>'0','messege' => 'some thing went wrong'.$details]);
        } 
        
    }
    
    
    
    public function LeopordGetTariffDetails($weight=0,$origion_city=0,$destination_city=0,$cod=0)
    {
        $weight = (int)(trim($weight)*1000);
        $allfare=[]; 
        $final_delivery_charges=0;
        
        // dd('1');
        // =======================detain
        $shipment_type = 'detain';
        $details = $this->GetTariffDetails($weight,$shipment_type,$origion_city,$destination_city,$cod);
        if($details->status == 0)
        {
            return response()->json(['success'=>'0','messege' => $details->error]);
            
        }
        else if($details->status == 1)
        {
            $packet_charges_details = $details->packet_charges; 
            $final_delivery_charges = $packet_charges_details->shipment_charges+$packet_charges_details->cash_handling+$packet_charges_details->insurance_charges+$packet_charges_details->gst_amount+$packet_charges_details->fuel_surcharge_amount;

            array_push($allfare,array(
                 
                'shippment_value' => $shipment_type,
                'shippment_lable' => 'Detain',
                'fare'=>$final_delivery_charges,
            ));
        }
        else
        {
            return response()->json(['success'=>'0','messege' => 'some thing went wrong'.$details]);
        } 
        
        
        // =======================Over Land
        $shipment_type = 'overland';
        $details = $this->GetTariffDetails($weight,$shipment_type,$origion_city,$destination_city,$cod);
        if($details->status == 0)
        {
            return response()->json(['success'=>'0','messege' => $details->error]);
            
        }
        else if($details->status == 1)
        {
            $packet_charges_details = $details->packet_charges; 
            $final_delivery_charges = $packet_charges_details->shipment_charges+$packet_charges_details->cash_handling+$packet_charges_details->insurance_charges+$packet_charges_details->gst_amount+$packet_charges_details->fuel_surcharge_amount;

            array_push($allfare,array(
                 
                'shippment_value' => $shipment_type,
                'shippment_lable' => 'Over Land',
                'fare'=>$final_delivery_charges,
            ));
        }
        else
        {
            return response()->json(['success'=>'0','messege' => 'some thing went wrong'.$details]);
        } 
        
        
        // =======================Over night
        $shipment_type = 'overnight';
        $details = $this->GetTariffDetails($weight,$shipment_type,$origion_city,$destination_city,$cod);
        if($details->status == 0)
        {
            return response()->json(['success'=>'0','messege' => $details->error]);
            
        }
        else if($details->status == 1)
        {
            $packet_charges_details = $details->packet_charges; 
            $final_delivery_charges = $packet_charges_details->shipment_charges+$packet_charges_details->cash_handling+$packet_charges_details->insurance_charges+$packet_charges_details->gst_amount+$packet_charges_details->fuel_surcharge_amount;

            array_push($allfare,array(
                 
                'shippment_value' => $shipment_type,
                'shippment_lable' => 'Over Night',
                'fare'=>$final_delivery_charges,
            ));
        }
        else
        {
            return response()->json(['success'=>'0','messege' => 'some thing went wrong'.$details]);
        } 
        
        return response()->json(['success'=>'1','messege' => 'successfully Executed','all_shipment_method_fares'=>$allfare,'packet_charges_details'=>$packet_charges_details,'details'=>$details]);

    }
    
    public function LeopordGetShipmentSlip($tracking_number)
    {
        $ManualOrder = ManualOrders::select('shipment_slip')->where('manual_orders.consignment_id',$tracking_number)->first()->shipment_slip;
        // dd($ManualOrder);
        return redirect()->away($ManualOrder);
    }
    
    public function Loadsheet()
    {
        return view('client.orders.manual-orders.leopord.generate_loadsheet');
    }
    
    public function GetCnDetails($cn)
    {
        $response = $this->LeopordTrackBookedPacket($cn);
        // dd($response->packet_list[0]);
        if($response->status == 1)
        {
            return response()->json(['success'=>'1','messege' => 'some thing went wrong','data'=> $response->packet_list[0]]);
        }
        else
        {
            return response()->json(['error'=>'1','messege' => 'some thing went wrong','data'=> $response]);
        }
    }
    
}
