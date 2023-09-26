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

