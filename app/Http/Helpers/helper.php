<?php 

    use App\Models\ActivityLogs;
    use App\Models\Client\CustomerPayments;
    use App\Models\Client\ManualOrders;

    if(!function_exists('create_activity_log'))
    {
        function create_activity_log($data)
        {
            $status = ActivityLogs::insert($data);
        }
    }
    
    if(!function_exists('check_customer_advance_payment'))
    {
        function check_customer_advance_payment($order_id)
        {
            $status = CustomerPayments::where(['order_id'=>$order_id,'status'=>'approval pending'])->get();
            return $status->count();
        }
    }
    
    if(!function_exists('check_order_status_for_print'))
    {
        function check_order_status_for_print($order_id)
        {
            $query=ManualOrders::query();
            $status = $query->where(['id'=>$order_id]);
            $query = $query->where('manual_orders.status','!=','dispatched')->where('manual_orders.status','!=','confirmed');
            // where(function ($query) {
            //     $query->where('manual_orders.status','!=','dispatched')
            //         ->orwhere('manual_orders.status','!=','confirmed') ;
            // });
            // dd($query->get());
            if($query->get()->count() > 0)
            { 
                $query = $query->first();
                // dd($query->status);
                return ['row_count'=>$query->count(),'status'=>$query->status];
            }
            else
            {
                
                return ['row_count'=>$query->count(),'status'=>''];
            }
        }
    }

