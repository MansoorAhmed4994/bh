<?php 

    use App\Models\ActivityLogs;
    use App\Models\Client\CustomerPayments;
    use App\Models\Client\ManualOrders;
    use App\Models\Statuses;
    use App\Models\User;
    use App\Models\Category;

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
            // dd($status);
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
            // $query = $query->where(function ($query) {
            //     $query->where('manual_orders.status','!=','dispatched')
            //         ->orwhere('manual_orders.status','!=','confirmed') ;
            // });
            // dd($query->get()->count());
            
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
    
    if(!function_exists('get_active_order_status_list'))
    {
        function get_active_order_status_list()
        {
            $user_id = User::find(auth()->user()->id);
            // $user_roles = implode(',',$user_id->roles()->get()->pluck('name')->toArray());
            
            $user_roles = $user_id->roles()->get()->pluck('name')->toArray();
            $query=Statuses::query(); 
            $status = $query->where(['status'=>'active'])->whereIn('permission',$user_roles)->get(); 
            // dd($status);
            // if($query->get()->count() > 0)
            // {  
                // dd($status);
                return $status;
            // } 
            // es
            // {}
        }
    }
    
    if(!function_exists('product_child_categories'))
    {
        function product_child_categories()
        {
            $categories = Category::select('*')->get();
            return $categories;
        }
    }

