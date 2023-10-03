 
@extends('layouts.'.Auth::getDefaultDriver())

@section('content') 
<script type="application/javascript">
var base_url = '<?php echo e(url('/')); ?>';
        function fetch_data(name,address)
        {
            $( document ).ready(function() {
                $('#first_name').val(name);
                $('#address').val(address);
            // alert(name+address);
             });
             
             
            
             
        }
        $( document ).ready(function() {
            $('#add_payment').on('click',function(e)
            {  
                 alert('w');
                $('#add_payment_modal').modal('show');
                
            });
            
            $('#add_payment_modal_close').on('click',function(e)
            {  
                 
                $('#add_payment_modal').modal('hide');
                
            });
        });
        
        function limit(element)
        {
            var max_chars = 11;
        
            if(element.value.length > max_chars) {
                element.value = element.value.substr(0, max_chars);
            }
        }
        
        $( document ).ready(function() {
            
            $('#edit_customer_payment_close').on('click',function(e)
            {  
                 
                $('#edit_customer_payment').modal('hide');
                
            });
            
            
            // $('.payment_image_src').on('click',function(e)
            // {   
            //      $(".payment_image_src").attr("src",$('payment_image').attr('src'));
            //     $('#payment_image_zoom').modal('show');
                
            // });
            
            $('#payment_image_zoom_close').on('click',function(e)
            {   
                $('#payment_image_zoom').modal('hide');
            });
            
            
        });
        
        function open_image_modal(id)
        { 
            // alert(id);
            $("#payment_image_src").attr("src",$("#"+id).attr("src"));
            $('#payment_image_zoom').modal('show'); 
            
        }
        
        function get_payments()
        {
            var data = $('#search_customer_form').serialize(); 
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: base_url + '/client/orders/CustomerPayment/GetCustomerPayments',
                data: data,
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
        }
        
            
        function actionpaymentapproval(id,action)
        {
            //  alert(id);
             var ur = '';
            if(action == 'delete')
            {
                url = '/client/orders/CustomerPayment/delete/'+id;
            }
            if(action == 'edit')
            {
                $('#edit_customer_payment').modal('show');
                get_customer_payment(id);
                return;
            }
            else if(action == 'approved')
            {
                url = '/client/orders/CustomerPayment/ChangeStatus/'+id+'/approved';
                
            }
            else if(action == 'approval pending')
            {
                url = '/client/orders/CustomerPayment/ChangeStatus/'+id+'/approval pending';
                
            }
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: base_url + url,
                data: {
                    payment_id:  id, 
                    action:  action, 
                },
                type: 'GET',
                dataType: 'json',
                success: function(e)
                { 
                    if(e.error == 1)
                    {
                        alert(e.messege);
                    }
                    else
                    {
                        alert(e.messege); 
                    }
                    // alert(e.messege); 
                },
                error: function(e) {
                    console.log(e.responseText);
                }
            });
        }
            
        function get_customer_payment(id) 
        {   
            $("body").addClass("loading");  
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: base_url + '/client/orders/CustomerPayment/edit/'+id,
                type: 'GET',
                dataType: 'json',
                success: function(e) 
                {
                    console.log(e.data);
                    console.log(e.data['order_id']);
                    console.log(e.data.transaction_id);
                    
                    
                    $('#edit_order_id').val(e.data['order_id']);
                    $('#edit_transaction_id').val(e.data['transaction_id']);
                    $('#edit_amount').val(e.data['amount']);
                    $('#edit_datetime').val(e.data['datetime']);
                    $('#edit_sender_name').val(e.data['sender_name']);
                    $('#edit_transfer_to').val(e.data['transfer_to']);
                    $('#edit_description').val(e.data['description']); 
                    $('#edit_customer_payment_id').val(e.data['id']); 
                    // var images ='';
                    // var str_array = e.messege.images.split('|'); 
                    // for(var i = 0; i < str_array.length; i++) 
                    // {
                    //      images = images+'<img class="pop rounded" style="margin-right: 5px;" src="{{asset("/")}}'+str_array[i]+'" alt="Card image cap" width="100">';
                    // }
                    
                    // $('#images_pop').html(images);
                    // $('#order_status_edit').modal('show');
                    $("body").removeClass("loading");
                     
                    
                },
                error: function(e) {
                    console.log(e.responseText);
                }
            });
        }
     
    
        function update_customer_payment()
        {
            var edit_customer_payment_id = $('#edit_customer_payment_id').val();
            var edit_order_id = $('#edit_order_id').val();
            var edit_transaction_id = $('#edit_transaction_id').val();
            var edit_amount = $('#edit_amount').val();
            var edit_datetime = $('#edit_datetime').val();
            var edit_sender_name = $('#edit_sender_name').val();
            var edit_transfer_to = $('#edit_transfer_to').val();
            var edit_description = $('#edit_description').val(); 
             
            // if(edit_order_id == '' || edit_transaction_id == '' || edit_amount == '' || edit_amount == '' || edit_datetime == '' || edit_sender_name == '' || edit_transfer_to == '' || edit_description == '')
            // {
            //     aler('please fill all the fields');
            //     return;
            // }
            // return;
                // $("body").addClass("loading"); 
                $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: base_url + '/client/orders/CustomerPayment/update_payment',
                type:"POST",
                dataType: 'json',
                data:{
                    id:edit_customer_payment_id,
                    order_id:edit_order_id,
                    transaction_id:edit_transaction_id,
                    amount:edit_amount,
                    datetime:edit_datetime,
                    sender_name:edit_sender_name,
                    transfer_to:edit_transfer_to,
                    description:edit_description,
                },
                success:function(response)
                { 
                    if (typeof e.success !== 'undefined') 
                    {
                        // $("body").removeClass("loading");
                        alert(e.messege);
                    }
                    
                    if (typeof e.error !== 'undefined') 
                    {
                        // $("body").removeClass("loading");
                        alert(e.messege);
                    }
                    
                    // $("body").removeClass("loading"); 
                },
                error: function(response) {
                    alert(response); 
                    $("body").removeClass("loading"); 
                },
            });
        
        }
         
                
    
    
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
    
    
    <div class="modal fade" id="add_payment_modal" tabindex="-1" role="dialog" aria-labelledby="add_payment_modal" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Customer Payment</h5>   
            </div>
            <div class="modal-body">
                
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
                        <input type="number"  class="form-control" onchange="get_payments()" id="order_id"  name="order_id" placeholder="order id" required>
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
                        <label for="First Name">Amount</label>
                        <input type="number" class="form-control" id="amount"  name="amount" >
                        <small id="amount_error" class="form-text text-danger"></small>
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
            <div class="modal-footer">  
                <button type="button" class="btn btn-secondary" id="add_payment_modal_close" data-dismiss="modal">Close</button>
            </div>
            </div>
        </div>
    </div>
    
    <div class="modal fade" id="edit_customer_payment" tabindex="-1" role="dialog" aria-labelledby="edit_customer_payment" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Customer Payment</h5>   
            </div>
            <div class="modal-body">
                 
                    <input type="hidden" name="edit_customer_payment_id" id="edit_customer_payment_id" >
        
                    <div class="form-group">
                        <label for="edit_orderid">Order ID</label>
                        <input type="number"  class="form-control" onchange="get_payments()" id="edit_order_id"  name="edit_order_id" placeholder="order id" required>
                        <small id="edit_order_id_error" class="form-text text-danger"> </small>
                    </div> 
        
                    <div class="form-group">
                        <label for="edit_Number">Transaction Id  OR Reference number</label>
                        <input type="number"  class="form-control" id="edit_transaction_id"  name="edit_transaction_id" placeholder="transaction ID (123XXXX)" required>
                        <small id="edit_transaction_id_error" class="form-text text-danger"> </small>
                    </div>  
        
                    <div class="form-group">
                        <label for="First Name">Sender Name</label>
                        <input type="text" class="form-control" id="edit_sender_name"  name="edit_sender_name" >
                        <small id="edit_datetime_error" class="form-text text-danger"></small>
                    </div> 
        
                    <div class="form-group">
                        <label for="First Name">Amount</label>
                        <input type="number" class="form-control" id="edit_amount"  name="edit_amount" >
                        <small id="edit_amount_error" class="form-text text-danger"></small>
                    </div> 
        
                    <div class="form-group">
                        <label for="First Name">Date Time</label>
                        <input type="datetime-local" class="form-control" id="edit_datetime"  name="edit_datetime" required>
                        <small id="edit_datetime_error" class="form-text text-danger"></small>
                    </div> 
         
                    
                    <div class="form-group ">
                            <label for="address">Transfer to</label>
                            
                            <select class="form-control " id="edit_transfer_to"  name="edit_transfer_to" >
                                <option value="">Select Transfer Channel</option>  
                                <option value="jazzcash">Jazzcash (03330139993)</option>  
                                <option value="faysalbank">Faysal Bank (0118007000010667)</option> 
                                <option value="hbl">HBL (005047700055903)</option> 
                                <option value="easypaisa">Easypaisa (03362240865)</option> 
                                    
                                
                            </select> 
                            <small id="edit_transfer_to_error" class="form-text text-danger"></small>
                        </div> 
        
                    <div class="form-group">
                        <label for="address">Description</label>
                        <textarea class="form-control" id="edit_description"  name="edit_description" placeholder="description" required></textarea>
                        <small id="edit_description_error" class="form-text text-danger"></small>
                    </div> 
        
                    <div class="form-group">
                        <button type="button"  onclick="update_customer_payment()" class="btn btn-primary">Save</button>
                    </div> 
                  
            </div>
            <div class="modal-footer">  
                <button type="button" class="btn btn-secondary" id="edit_customer_payment_close" data-dismiss="modal">Close</button>
            </div>
            </div>
        </div>
    </div>
    
    <div class="modal fade" id="payment_image_zoom" tabindex="-1" role="dialog" aria-labelledby="payment_image_zoom" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content"> 
            <div class="modal-body">
                
                <img id="payment_image_src" src="">  
            </div>
            <div class="modal-footer">  
                <button type="button" class="btn btn-secondary" id="payment_image_zoom_close" data-dismiss="modal">Close</button>
            </div>
            </div>
        </div>
    </div>
    
    
    <div class="row mb-3">
        <div class="col-lg-12 margin-tb">
            <div class="text-center">
                <h2>Customer Manual Orders</h2> 
            </div>
        </div>
    </div>
      
    <div class="col-sm-12"> 
         
        
        <div class="row"> 
            <div class="col-sm-12">
                
                <form name="search_customer_form" id="search_customer_form" method="post">
                    <div class="form-group">
                        <div class="input-group">
                            <input class="input-group-text" type="search" id="search_order_id" name="search_order_id" placeholder="Order id #" aria-label="Search">
                            <input class="input-group-text" type="search" id="search_transaction_id" name="search_transaction_id" placeholder="transaction id #" aria-label="Search">
                            <input class="input-group-text" type="text" id="search_sender_name" name="search_sender_name" placeholder="sender name #" aria-label="Search">
                            <input class="input-group-text" type="number" id="search_amount" name="search_amount" placeholder="Amount" aria-label="Search">
                            <input class="input-group-text" type="datetime-local" id="search_date" name="search_date" placeholder="date" aria-label="Search"> 
                            <select class="custom-select" aria-label="Default select example" id="search_transfer_to"  name="search_transfer_to" >
                                <option value="">Select Transfer Channel</option>  
                                <option value="jazzcash">Jazzcash (03330139993)</option>  
                                <option value="faysalbank">Faysal Bank (0118007000010667)</option> 
                                <option value="hbl">HBL (005047700055903)</option> 
                                <option value="easypaisa">Easypaisa (03362240865)</option>
                            </select>
                            <select class="custom-select" aria-label="Default select example" id="search_payment_status"  name="search_payment_status" >
                                <option value="">Select Action</option>
                                <option value="delete">Delete</option>
                                <option value="approval pending">Remove Approval</option>
                                <option value="approved">Approved</option><option value="edit">Edit</option> 
                            </select>
                            <div class="input-group-append">
                                <button class="btn btn-primary" onclick="get_payments()" type="button">Search</button>
                            </div>
                            <div class="input-group-append">
                                <button class="btn btn-primary" id="add_payment" type="button">Add</button>
                            </div>
                        </div>
                    </div>
                </form>
                <table class="table" id="previouse_order_detail">
                   
                </table>
                
            </div>
        </div>
    </div>
    
     <script>
         get_payments();
     </script>
  @endsection
