 
@extends('layouts.'.Auth::getDefaultDriver())

@section('content') 
<script type="application/javascript">
        function fetch_data(name,address)
        {
            $( document ).ready(function() {
                $('#first_name').val(name);
                $('#address').val(address);
            // alert(name+address);
             });
        }
        
        function limit(element)
        {
            var max_chars = 11;
        
            if(element.value.length > max_chars) {
                element.value = element.value.substr(0, max_chars);
            }
        }
        
        $( document ).ready(function() {
            
            $('#order_id').focusout('click',function(e)
            {  
                
                alert('working');
                var base_url = '<?php echo e(url('/')); ?>';
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: base_url + '/client/orders/CustomerPayment/GetCustomerPayments',
                    data: {
                        order_id:  $('#order_id').val(), 
                    },
                    type: 'POST',
                    dataType: 'json',
                    success: function(e)
                    { 
                        $('#previouse_order_detail').html(e.messege); 
                        // alert(e.messege); 
                    },
                    error: function(e) {
                        console.log(e.responseText);
                    }
                });
            });
        });
    </script>
    
    <style> 
        
        input[type=file] 
        {
        position: absolute;
        font-size: 50px;
        opacity: 0; 
        right: 0;
        top: 0;
        }

    </style>
    
    
    
    <div class="row mb-3">
        <div class="col-lg-12 margin-tb">
            <div class="text-center">
                <h2>Customer Manual Orders</h2> 
            </div>
        </div>
    </div>
      
    <div class="container"> 
         
        
        <div class="row">
            <div class="col-sm-4">
                <form method="post" action="{{ route('customer.payments.store') }}" enctype="multipart/form-data" class="dropzone" id="dropzone">
                    @csrf
        
                    <div class="form-group">
                        <div class="file btn btn-lg btn-primary">Upload
                            <input type="file" name="images[]" multiple  required/>
                            
                        </div>
                        <small id="images_error" class="form-text text-danger"></small>
                    </div> 
        
                    <div class="form-group">
                        <label for="orderid">Order ID</label>
                        <input type="number"  class="form-control" id="order_id"  name="order_id" placeholder="order id" required>
                        <small id="order_id_error" class="form-text text-danger"> </small>
                    </div> 
        
                    <div class="form-group">
                        <label for="Number">Transaction Id  OR Reference number</label>
                        <input type="number"  class="form-control" id="transaction_id"  name="transaction_id" placeholder="transaction ID (123XXXX)" required>
                        <small id="transaction_id_error" class="form-text text-danger"> </small>
                    </div>  
        
                    <div class="form-group">
                        <label for="First Name">Sender Name</label>
                        <input type="text" class="form-control" id="sender_name"  name="sender_name" >
                        <small id="datetime_error" class="form-text text-danger"></small>
                    </div> 
        
                    <div class="form-group">
                        <label for="First Name">Date Time</label>
                        <input type="datetime-local" class="form-control" id="datetime"  name="datetime" required>
                        <small id="datetime_error" class="form-text text-danger"></small>
                    </div> 
         
                    
                    <div class="form-group ">
                            <label for="address">Transfer to</label>
                            
                            <select class="form-control " id="transfer_to"  name="transfer_to" >
                                <option value="">Select Transfer Channel</option>  
                                <option value="jazzcash">Jazzcash (03330139993)</option>  
                                <option value="faysalbank">Faysal Bank (0118007000010667)</option> 
                                <option value="hbl">HBL (005047700055903)</option> 
                                <option value="easypaisa">Easypaisa (03362240865)</option> 
                                    
                                
                            </select> 
                            <small id="transfer_to_error" class="form-text text-danger"></small>
                        </div> 
        
                    <div class="form-group">
                        <label for="address">Description</label>
                        <textarea class="form-control" id="description"  name="description" placeholder="description" required></textarea>
                        <small id="description_error" class="form-text text-danger"></small>
                    </div> 
        
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                        
                </form> 
            </div>
            <div class="col-sm-8">
                
            <table class="table" id="previouse_order_detail">
               
            </table>
                
            </div>
        </div>
    </div>
    
     
  @endsection
