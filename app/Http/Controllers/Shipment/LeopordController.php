<?php

namespace App\Http\Controllers\Shipment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Traits\LeopordTraits;

class LeopordController extends Controller
{ 
    use LeopordTraits;
    
    
    
    public function LeopordGetTariffDetails($weight=0,$shipment_type=0,$origion_city=0,$destination_city=0,$cod=0)
    {
        // dd('workin');
        $details = $this->GetTariffDetails($weight,$shipment_type,$origion_city,$destination_city,$cod);
        $final_delivery_charges=0;
        
        if($details->status == 0)
        {
            return response()->json(['success'=>'0','messege' => $details->error]);
            
        }
        else if($details->status == 1)
        {
            $packet_charges_details = $details->packet_charges;
            // dd($packet_charges_details);
            $final_delivery_charges = $packet_charges_details->shipment_charges+$packet_charges_details->cash_handling+$packet_charges_details->insurance_charges+$packet_charges_details->gst_amount+$packet_charges_details->fuel_surcharge_amount;
            // dd($final_delivery_charges);
            return response()->json(['success'=>'1','messege' => 'successfully deleted','final_delivery_charges'=>$final_delivery_charges]);
        }
        else
        {
            return response()->json(['success'=>'0','messege' => 'some thing went wrong'.$details]);
        }
        
        
        
        // $this->GetTariffDetails();
    }
}
