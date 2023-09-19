
@extends('layouts.'.Auth::getDefaultDriver())

@section('content')
    <head>
        <script>
            var base_url = '<?php echo e(url('/')); ?>';
            function getorderdetails()
            {
                var orderid = document.getElementById('order_id').value;
                window.location = base_url+'/client/orders/ManualOrders/edit/'+orderid;
                
            }
         
        </script>
    </head>
    <nav class="navbar navbar-light bg-light justify-content-center">  
        <form class="form-inline"> 
            <div class="form-group">
                <div class="input-group">
                    <input class="input-group-text" type="number" id="order_id" name="order_id" placeholder="Search by Order id #" aria-label="Search">
                </div>
                
                <div class="input-group-append">
                    <button class="btn btn-outline-secondary" type="button" onclick="getorderdetails()">Search</button>
                </div>
            </div>
        </form> 
    </nav>
                
                         
                 

    
   
     
  @endsection
