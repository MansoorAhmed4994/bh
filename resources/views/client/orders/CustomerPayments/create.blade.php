 
@extends('layouts.'.Auth::getDefaultDriver())

@section('content') 
<script type="application/javascript">
var base_url = '<?php echo e(url('/')); ?>';
        function fetch_data(name,address)
        {
            $( document ).ready(function() {
                $('#first_name').val(name);
                $('#address').val(address); 
             });
             
             
            
              
        }
        
        function delete_image(id)
        {
            
            $('#'+id).show();
            $('#edit_images_box').hide();
        //     const fileListArr = 
        //   fileListArr.splice(index, 1)
        //   console.log(fileListArr)
        }
        
        function edit_readURL(input) { 
            console.log(input.files.length);
            
            var imgages= '';
            $('#edit_selected_img_card_body').html(imgages);
            for(i=0; i<input.files.length; i++)
            {
                if (input.files && input.files[i]) {
                    console.log(input.files[i]);
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        // console.log(reader.readAsDataURL(input.files[i]));
                //   $('#selected_images')
                //     .attr('src', e.target.result)
                //     .width(150)
                //     .height(200);
                    imgages = $('#edit_selected_img_card_body').html()+'<img class="card-img-top col-sm-3" src="'+e.target.result+'" alt="Card image cap" >';
                    $('#edit_selected_img_card_body').html(imgages);
                    
                    };
                    
                }
              
                reader.readAsDataURL(input.files[i]);
            }
            $('#edit_images_box').show();
            
        }
        
        function readURL(input) {
            
            console.log(input.files.length);
            var imgages= '';
            for(i=0; i<input.files.length; i++)
            {
                if (input.files && input.files[i]) {
                    console.log(input.files[i]);
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        // console.log(reader.readAsDataURL(input.files[i]));
                //   $('#selected_images')
                //     .attr('src', e.target.result)
                //     .width(150)
                //     .height(200);
                    imgages = $('#selected_img_card_body').html()+'<img class="card-img-top col-sm-3" src="'+e.target.result+'" alt="Card image cap" >';
                    $('#selected_img_card_body').html(imgages);
                    
                    };
                    
                }
              
                reader.readAsDataURL(input.files[i]);
            }
            
        }

        $( document ).ready(function() {
            
            
            $('#add_payment').on('click',function(e)
            {   
                $('#add_payment_modal').modal('show');
                
            });
            
            $('#add_payment_modal_close').on('click',function(e)
            {  
                 
                $('#add_payment_modal').modal('hide');
                
            });
            
            $('#ConfirmationDeleteCustomerModalClose').on('click',function(e)
            {  
                 
                $('#ConfirmationDeleteCustomerModal').modal('hide');
                
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
            
            $('#payment_image_zoom_close').on('click',function(e)
            {   
                $('#payment_image_zoom').modal('hide');
            });
            
            $('#search_order_id, #search_transaction_id, #search_sender_name, #search_transfer_to, #search_amount, #search_date, #search_payment_status').on('keypress',function(e) {
                if(e.which == 13) {
                    get_payments();
                }
            });
            
            $('#image-upload-btn').on('click',function(e) {
                $("body").addClass("loading"); 
               e.preventDefault();
               let formData = new FormData(document.getElementById("edit_form")); 
      
                $.ajax({
                    type:'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: base_url + '/client/orders/CustomerPayment/update_payment',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: (e) => {
                        
                        if (typeof e.success !== 'undefined') 
                        {
                            // $("body").removeClass("loading");
                            $("body").removeClass("loading");
                            $('#edit_customer_payment').modal('hide');
                            toastr.success(e.messege, 'Payment');
                        }
                        
                        if (typeof e.error !== 'undefined') 
                        {
                            // $("body").removeClass("loading");
                            $("body").removeClass("loading");
                            toastr.success(e.messege, 'Payment');
                        }
                        
                         
                    },
                    error: function(response){
                    //         $('#image-input-error').text(response.responseJSON.message);
                    }
                });
            });
            
        });
        
        function open_image_modal(id)
        {  
            $("#payment_image_src").attr("src",$("#"+id).attr("src"));
            $('#payment_image_zoom').modal('show'); 
            
        }
        
        function get_payments()
        {
            $("body").addClass("loading"); 
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
                    $("body").removeClass("loading"); 
                    $('#previouse_order_detail').html(e.messege);  
                },
                error: function(e) {
                    console.log(e.responseText);
                }
            });
        }
        
        function delete_customer_payment_confirmation(id,action)
        {
            
            $('#ConfirmationDeleteCustomerModal').modal('show');
            
            $('#ConfirmationDeleteCustomerModalYesOnclick').attr('onclick',"actionpaymentapproval("+id+",'delete')");
        }
            
        function actionpaymentapproval(id,action)
        {
            $("body").addClass("loading");
             var url = '';
            if(action == 'delete')
            {
                url = '/client/orders/CustomerPayment/delete/'+id;
                
            }
            if(action == 'delete_confirmation')
            {
                delete_customer_payment_confirmation(id,action);
                $("body").removeClass("loading"); 
                return;
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
            // return;
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: base_url + url,
                data: {
                    payment_id:  id, 
                    action:  action, 
                },
                type: 'POST',
                dataType: 'json',
                success: function(e)
                { 
                    $("body").removeClass("loading");
                    // console.log(e);
                    if(typeof e.success !== 'undefined')
                    {
                        $("body").removeClass("loading");
                        toastr.success(e.messege, 'Payment');
                    } 
                    else 
                    {
                        $("body").removeClass("loading");
                        toastr.error(e.messege,'Error');
                    }
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
                    $('#customer_payment_image').attr('src','{{asset("")}}'+e.data["images"]);
                    // console.log(e.data['images']);
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
    
    
    
    <div class="modal fade" id="ConfirmationDeleteCustomerModal" tabindex="-1" role="dialog" aria-labelledby="ConfirmationDeleteCustomerModal" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirmation</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                
                <div class="modal-body">
                    <p>Are you Sure you want to delete?</p>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="ConfirmationDeleteCustomerModalYesOnclick" onclick="">Yes</button>
                    <button type="button" class="btn btn-secondary" id="ConfirmationDeleteCustomerModalClose">Close</button>
                </div>
                
            </div>
        </div>
    </div>
    
    <div class="modal fade" id="add_payment_modal" tabindex="-1" role="dialog" aria-labelledby="add_payment_modal" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Customer Payment</h5>   
            </div>
            <div class="modal-body">
                
                <form method="post" action="{{ route('customer.payments.store') }}" enctype="multipart/form-data" class="dropzone" id="dropzone">
                    @csrf
         
                            <!--<input type="file" name="images" id="images"   required/>-->
                            
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="inputGroupFileAddon01">Upload</span>
                        </div>
                        
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" onchange="readURL(this)" name="images[]"  id="images" 
                            aria-describedby="inputGroupFileAddon01">
                            <label class="custom-file-label" for="inputGroupFile01">Choose file</label>
                        </div>
                        
                        
                    </div>
                    
                    <div class="form-group">
                        <div class="card card-box-custom" id="imagebox" >
                            
                            <div class="card-body" id="selected_img_card_body">
                                
                                
                                <!--<p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>-->
                                
                            </div> 
                        </div>
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
                <form method="post" name="edit_form" id="edit_form" enctype="multipart/form-data">
                    <input type="hidden" name="edit_customer_payment_id" id="edit_customer_payment_id" >
                    
                    <div class="input-group"  id="edit_image_input_box" style="display:none">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="inputGroupFileAddon01">Upload</span>
                        </div>
                        
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" onchange="edit_readURL(this)" name="edit_images[]" id="edit_images" accept="image/png, image/gif, image/jpeg" multiple>
                            <label class="custom-file-label" for="inputGroupFile01">Choose file</label>
                        </div>
                        
                        
                    </div>
                    
                    <div class="form-group" id="edit_images_box">
                        <div class="card card-box-custom" id="imagebox" >
                            
                            <div class="card-body" id="edit_selected_img_card_body">
                                   <img src="" id="customer_payment_image" width=200>
                                <!--<p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>-->
                                
                            </div> 
                            
                            <div class="card-footer"> 
                                <a onclick="delete_image('edit_image_input_box')" class="btn btn-primary">Delete</a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="edit_orderid">Order ID</label>
                        <input type="number"  class="form-control" id="edit_order_id"  name="edit_order_id" placeholder="order id" required>
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
                        <button  button="button" id="image-upload-btn" class="btn btn-primary">Save</button>
                    </div> 
                </form>
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
