
@extends('layouts.'.Auth::getDefaultDriver())

@section('content')
    <head>
        <script>
            var base_url = '<?php echo e(url('/')); ?>';
            function getorderdetails()
            {
                var orderid = document.getElementById('order_id').value;
                if(orderid == '')
                {
                    alert('please enter order id');
                    return
                }
                window.location = base_url+'/client/orders/ManualOrders/edit/'+orderid;
                
            }
            
            $( document ).ready(function() {
                $("#order_id").keypress(function (event) {
                    if (event.keyCode === 13) {
                        getorderdetails();
                    }
                });
            });
         
        </script>
    </head>
    <nav class="navbar navbar-light bg-light justify-content-center">  
        <div class="form-group">
            
            <div class="input-group">
            
                <input class="input-group-text" type="number" id="order_id" name="order_id" placeholder="Search by Order id #" aria-label="Search">
                <div class="input-group-prepend">
                    <a class="input-group-text btn btn-primary"  onclick="getorderdetails()">Search</a>
                    </a>
                </div>
            </div>
        </div> 
    </nav>
                
                         
                 

    
   
     
  @endsection
