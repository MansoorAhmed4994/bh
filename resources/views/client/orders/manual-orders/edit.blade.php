
@extends('layouts.'.Auth::getDefaultDriver())

@section('content')
 <head>
<script type="text/javascript">

        var row_id="1";
        
       
        
    $( document ).ready(function() {
        
        
        
        
        
        $('#ImageDeleteModalClose').on('click',function(){
            $('#ImageDeleteModal').modal('hide');
        });
        
        var cities =@json($cities);
        $('.cities_dropdown').select2();
        
        $('#add_product_btn').on('click',function(e)
        {  
            // alert('wo');
                get_products_by_sku();
        });
        
      
        
        $('#sku_number').on('keypress',function(e)
        {  
            
            if (e.which == 13) {
                get_products_by_sku();
            }
        });
    });
        
    function delete_row(id,inventory_id)
    {
        $("body").addClass("loading");
        
        
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: base_url + '/admin/inventory/deletcustomerproduct/'+inventory_id,
            type: 'GET', 
            dataType: 'json',
            success: function(e)
            { 
                
                
                
                if (typeof e.success !== 'undefined') {
                    var row = document.getElementById(id);
                    row.parentNode.removeChild(row);
                    $('#product_price').val(e.price);
                    
                    $("#total_amount").html(e.price);
                    toastr["success"](e.messege, 'Customer Product Minise');
                    
                    get_fare_list();
                    onchangeprice();
                } 
                else if(typeof e.error !== 'undefined')
                {
                    toastr(e.messege, 'Error');
                    // alert(e.messege);
                }
                $("body").removeClass("loading");
            },
            error: function(e) {alert(e); 
                $("body").removeClass("loading");
            }
        });
        
    }
    function get_products_by_sku() 
    {
        $("body").addClass("loading"); 
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: base_url + '/admin/inventory/getproduct',
            type: 'post',
            data: $('#update_form').serialize(),
            dataType: 'json',
            success: function(e)
            { 
                if(e.error == 0)
                {
                    var row_data='';
                    // console.log(e.inventory);
                    var total_amount = 0;
                    var total_products = 0;
                    $('#product_price').val(e.price);
                    for(var i=0; i< e.inventory.length; i++)
                    {
                        total_products++;
                        total_amount+=parseInt(e.inventory[i].sale);
                        row_data += '<tr id="'+row_id+'"><td class="delete_btn_class"><button type="button" class="btn btn-danger " onclick="delete_row('+row_id+','+e.inventory[i].id+')">Delete</button></td><td>'+e.inventory[i].name+'</td><td>'+e.inventory[i].sale+'</td></tr>';
    
                    row_id++;
                    }
                    $("#row_data").html(row_data);
                    $("#total_products").html(total_products);
                    $("#total_amount").html(total_amount);
                    $("body").removeClass("loading");
                    get_fare_list();
                    onchangeprice();
                }
                else
                {
                    alert('no product found');
                    $("body").removeClass("loading");
                }
                    // document.getElementById('order_id').value = '';
                
                //cosole.log(e.messege);
            },
            error: function(e) {
                alert(e); 
                $("body").removeClass("loading");
            }
        });
            
        }
    function onchangeprice()
    {
        
        let product_price = parseFloat(document.getElementById("product_price").value);
        let dc = parseFloat(document.getElementById("dc").value);
        let packaging_cost = parseFloat(document.getElementById("packaging_cost").value);
        document.getElementById("price").value = product_price+dc+packaging_cost; 
        
        let advance_payment = parseFloat(document.getElementById("advance_payment").value); 
        let cod_amount = parseFloat((product_price+dc+packaging_cost)-advance_payment).toFixed(2);
        document.getElementById("cod_amount").value = cod_amount;
        // alert(cod_amount);
    }

    $( document ).ready(function() {
        // var Inputmask = require('inputmask');
        var cities =@json($cities);
        $('.cities_dropdown').select2();
        
        var cities =@json($cities);
        $('.information_display_dropdown').select2();
        
        var cities =@json($cities);
        $('.payment_mode_id_dropdown').select2();
            //static mask
    });


        var base_url = '<?php echo e(url('/')); ?>';
        var delete_image_path ="";
        var image_box_id = '2';
        var all_images = '{{$ManualOrder->images}}';
        all_images = all_images.split("|");

         

        function myFunction() {
            var myobj = document.getElementById(image_box_id);
            myobj.remove();
        }
        
        function delete_image(image_path, box_id)
        {
            //alert('text');
            delete_image_path=image_path;
            image_box_id=box_id;  
            document.getElementById("delete_image").disabled = false;
            $('#ImageDeleteModal').modal('show');
            //all_images = all_images.filter(e => e !== delete_image_path); 
            //consle.log(all_images);
        }  

        $( document ).ready(function() {
            
            $('#delete_image').on('click',function(e)
            {  
                $("body").addClass("loading"); 
                 $("#delete_image").prop('disabled', true); 
                 console.log(all_images)
                var final_images = all_images.filter(e => e !== delete_image_path);  
                
                console.log(final_images);
                if(final_images != null)
                {
                    final_images = final_images.join('|');
                    all_images = final_images.split("|");

                }
                else
                {
                    final_images = ''; 
                }
                
                
                //alert(final_images);
                document.getElementById('images_path').value = final_images;
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: base_url + '/client/orders/ManualOrders/delete-image',
                    data: {
                        delete_path: delete_image_path,
                        images: final_images,
                        order_id: '{{$ManualOrder->id}}', 
                    },
                    type: 'POST',
                    dataType: 'json',
                    success: function(e)
                    {
                        console.log(e);
                        
                        myFunction(); 
                        if(typeof e.success !== 'undefined')
                        {
                            $("body").removeClass("loading");
                            toastr(e.messege);
                        } 
                        else if(typeof e.error !== 'undefined')
                        {
                            $("body").removeClass("loading");
                            toastr.error(e.messege,'Error');
                        }
                        else
                        {
                            toastr.warning(e.messege,'Warning');
                        }
                        $('#ImageDeleteModal').modal('hide');
                        $("body").removeClass("loading"); 
                    },
                    error: function(e) {
                        console.log(e.responseText);
                    }
                });
            });
             
            $('#btncustomerpaymentiframe').on('click',function(e)
            { 
                $('#customerpaymentiframe').modal('show');
                $('#customerpaymentiframe').modal('handleUpdate');
                
            });
            
            $('#btnclosecustomerpaymentiframe,#btnclose2customerpaymentiframe').on('click',function(e)
            { 
                // $("body").addClass("loading"); 
                $('#customerpaymentiframe').modal('hide'); 
                var order_id = document.getElementById('order_id').value;
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: base_url + '/client/orders/ManualOrders/GetAdvacePayment',
                    data: { 
                        order_id: order_id, 
                    },
                    type: 'POST',
                    dataType: 'json',
                    success: function(e)
                    {
                        console.log(e);
                        $('#advance_payment').val(e.advance_payment); 
                        // $("body").removeClass("loading"); 
                    },
                    error: function(e) {
                        console.log(e.responseText);
                    }
                });
            });  
            
        });

        function validateForm()
        { 

            var validation_status = true;
             
            //  alert(fi.files.length);
            
            
            if($('#shipment_type').val() == 'trax')
            {
                if($('#shipping_mode_id').val() == '')
                {
                    
                    validation_status = false;
                    toastr.warning('Please Select Shipment Method to auto fil fare', 'Warning'); 
                    toastr.warning('Please Select Shipment Method', 'Warning'); 
                    $('#shipping_mode_id_error').html('Please Select Shipment Method'); 
                    $('#fare_error').html('Please Select Shipment Method to auto fil fare');
                }
                else
                {
                    
                    $('#fare_error').html('');  
                }
                
                if($('#fare').val() == '')
                {
                    
                    validation_status = false;
                    toastr.warning('Please Select Shipment Method to auto fil fare', 'Warning'); 
                    $('#fare_error').html('Please Select Shipment Method to auto fil fare');
                }
                else
                {
                    
                    $('#fare_error').html('');  
                }
                
                if($('#reference_number').val() == '')
                {
                    
                    validation_status = false;
                    toastr.warning('Please Select Shipment Method to auto fil fare', 'Warning'); 
                    $('#reference_number_error').html('Please Select Shipment Method to auto fil fare');
                    
                }
                else
                {
                    
                    $('#reference_number_error').html('');  
                }
            }
            
            if($('#product_price').val() == '' || $('#product_price').val() <= '0')
            {
                
                validation_status = false;
                toastr.warning('Please enter price', 'Warning'); 
                $('#product_price_error').html('Please enter price');  
            }
            else
            {
                
                $('#product_price_error').html('');  
            } 
            
            if($('#cod_amount').val() < 0)
            {
                
                validation_status = false; 
                toastr.warning('You cant select dispatch while only saving record', 'Warning'); 
                $('#cod_amount_error').html('You cant select dispatch while only saving record');
            }
            else
            {
                $('#cod_amount_error').html('');
            }
            
            
             
            
            
            
            return validation_status;
        }
        // var which;
        //     validation_status = false; 
        //     $("#save").click(function () {
        //         which = $(this).attr("id");
        //         console.log('1');
        //         if (which == "save") {
        //             console.log('2');
        //             if($('#order_status').val() == 'dispatched')
        //             {
        //                 console.log('3');
        //                 validation_status = false;  
        //                 toastr.warning('You cant select dispatch while only saving record', 'Warning');
        //                 $('#order_status_error').html('You cant select dispatch while only saving record');
                        
        //             }
        //             return false; // if "button2" submit clicked - prevent submission
        //         }
        //     });
        
        function getorderdetails()
        {
            var orderid = document.getElementById('search_order_id').value;
            if(orderid == '')
            {
                alert('please enter order id');
                return
            }
            window.location = base_url+'/client/orders/ManualOrders/edit/'+orderid;
            
        }
        
        $( document ).ready(function() {
            $("#search_order_id").keypress(function (event) {
                if (event.keyCode === 13) {
                    getorderdetails();
                }
            });
        });

        

    </script>
    <style>  
        /*input[type=file] {*/
        /*position: absolute;*/
        /*font-size: 50px;*/
        /*opacity: 0; */
        /*right: 0;*/
        /*top: 0;*/
        /*}*/

    </style>  
</head>

    <div class="modal fade" id="customerpaymentiframe" tabindex="-1" role="dialog" aria-labelledby="customerpaymentiframe" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-xl" style="height:80%" role="document">
        <div class="modal-content"  style="height:100%">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLongTitle">Customer Payment</h5>
            <button type="button" class="close" data-dismiss="modal" id="btnclose2customerpaymentiframe" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
              <iframe src="{{route('customer.payments.index')}}" style="width:100%;height:100%" title="W3Schools Free Online Web Tutorials"></iframe>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" id="btnclosecustomerpaymentiframe" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>




    <div class="modal fade" id="ImageDeleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Modal title</h5> 
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this image?</p>
            </div>
            <div class="modal-footer"> 
                <button type="button" id="delete_image" class="btn btn-primary" >Yes</button>
                <button type="button" class="btn btn-secondary" id="ImageDeleteModalClose">Close</button>
            </div>
            </div>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-lg-12 margin-tb">
            <div class="text-center">
                <h4>Edit / {{$ManualOrder->id}}</h4> 
            </div>
        </div>
    </div>
    
<div class=""> 
     
    @if(Session::has('order_placed_message'))
        <div class="alert alert-success" role="alert">
            {{session()->get('order_placed_message')}}
        </div> 
    @endif
        <div class="col-sm-12 row"> 
            
            <nav class="navbar navbar-light bg-light justify-content-center">  
                <div class="form-group">
                     
                    <div class="input-group">
                    
                        <input class="input-group-text" type="number" value="@if(isset($ManualOrder->id)){{$ManualOrder->id}}@endif" id="search_order_id" name="search_order_id" placeholder="Search by Order id #" aria-label="Search">
                        <div class="input-group-prepend">
                            <a class="input-group-text btn btn-primary"  onclick="getorderdetails()">Search</a>
                            </a>
                        </div>
                    </div>
                </div> 
            </nav>
        </div> 


        <form action="{{ route('ManualOrders.update',$ManualOrder->id) }}" onsubmit="return validateForm()" id="update_form"  enctype="multipart/form-data" method="post">
            @csrf

            <div class="col-sm-12 row">
                <input type="hidden"  name="order_id" value="@if(isset($ManualOrder->id)){{$ManualOrder->id}}@endif" id="order_id">
                <div class="col-sm-12 row">
                    <div class="form-group">
                        <input type="file" name="images[]" id="images" accept="image/png, image/gif, image/jpeg" multiple/>
                        <!--<div class="file btn btn-lg btn-secondary">Add new-->
                            <input type="hidden" name="images_path" id="images_path" />
                            
                        <!--</div>-->
                        @if($errors->get('images'))<small id="images_error" class="form-text text-danger"> {{$errors->first('images')}} </small>@endif
                    </div>
                    
                    <?php $count=1;?>
                    
                    @if(!empty($ManualOrder->images))
                        @foreach(explode('|', $ManualOrder->images) as $image) 
                        <div class="col-auto">
                            <div class="card " id="imagebox{{$count}}" >
                                <img class="card-img-top edit-img-box" src="{{asset($image)}}" alt="Card image cap" >
                                <div class="card-body">
                                    <!--<p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>-->
                                    <a onclick="delete_image('{{$image}}','imagebox{{$count}}')" class="btn btn-primary" >Delete</a>
                                </div>
                            </div>
                        </div>
                        
                            <?php $count++;?> 
                        @endforeach
                    @endif
                    
                </div>
                    
                <div class="col-sm-12 row"> 
                    
                    
                        
                    <div class="col-sm-2">
                        <h5>Customer Detail <hr></h5>
            
                        <div class="form-group col-auto">
                            <label for="First Name">First Name</label>
                            <input type="text" class="form-control @if($errors->get('first_name')) is-invalid @endif" value="{{old('first_name')}}@if(isset($ManualOrder)){{$ManualOrder->first_name}}@endif" id="first_name"  value="{{old('first_name')}} @if(isset($ManualOrder)) {{$ManualOrder->first_name}}  @endif" name="first_name" placeholder="First Name" required>
                            @if($errors->get('first_name')) <small id="first_name_error" class="form-text text-danger"></small>{{$errors->first('first_name')}} @endif
                        </div> 
            
                        <div class="form-group col-auto">
                            <label for="First Name">last Name</label>
                            <input type="text" class="form-control @if($errors->get('last_name')) is-invalid @endif" value="{{old('last_name')}}@if(isset($ManualOrder)){{$ManualOrder->last_name}}@endif" id="last_name"  value="{{old('last_name')}} @if(isset($ManualOrder)) {{$ManualOrder->last_name}}  @endif" name="last_name" placeholder="Last Name" >
                            @if($errors->get('last_name')) <small id="last_name_error" class="form-text text-danger"></small>{{$errors->first('last_name')}} @endif
                        </div> 
            
                        
            
                        <div class="form-group col-auto">
                            <label for="Number">Number</label>
                            <input type="text" class="form-control @if($errors->get('number')) is-invalid @endif" value="{{old('number')}}@if(isset($ManualOrder)){{$ManualOrder->number}}@endif" id="number"  name="number" placeholder="number Number" required>
                            @if($errors->get('number')) <small id="number_error" class="form-text text-danger">{{$errors->first('number')}} </small>@endif
                        </div>     
            
                        <div class="form-group col-auto">
                            <label for="Number">Whatsapp Number</label>
                            <input type="text" class="form-control @if($errors->get('whatsapp_number')) is-invalid @endif" value="{{old('whatsapp_number')}}@if(isset($ManualOrder)){{$ManualOrder->number}}@endif" id="whatsapp_number"  name="whatsapp_number" placeholder="Whatsapp number" >
                            @if($errors->get('whatsapp_number')) <small id="whatsapp_number_error" class="form-text text-danger">{{$errors->first('whatsapp_number')}} </small>@endif
                        </div> 
            
                        <div class="form-group col-auto">
                            <label for="address">Address</label>
                            <textarea class="form-control" id="address @if($errors->get('address')) is-invalid @endif"   name="address" placeholder="address" required>{{old('address')}}@if(isset($ManualOrder)){{$ManualOrder->address}}@endif</textarea>
                            <small id="address_error" class="form-text text-danger">@if($errors->get('address')) {{$errors->first('address')}} @endif</small>
                        </div>  
                    </div>
                    
                    <div class="col-sm-3">
                
                        <h5>Reciever Detail <hr></h5>
                        
                        <div class="form-group col-auto">
                            <label for="receiver_name">Reciever Name</label>
                            <input type="text" class="form-control @if($errors->get('receiver_name')) is-invalid @endif" value="{{old('receiver_name')}}@if(isset($ManualOrder)){{$ManualOrder->receiver_name}}@endif" id="receiver_name"  name="receiver_name" placeholder="Reciever Name" required>
                            @if($errors->get('receiver_name')) <small id="receiver_name_error" class="form-text text-danger">{{$errors->first('receiver_name')}} </small>@endif
                        </div> 
                         
                        
                        <div class="form-group col-sm">
                            <label for="address">city</label>
                            <select class="form-control @if($errors->get('city')) is-invalid @endif cities_dropdown city" id="city"  onchange="get_fare_list()" name="city" required>
                                <option value="">Select City</option>
                                @for($i=0 ; $i < sizeof($cities); $i++)
                                 
                                    <option value="{{$cities[$i]->id}}" {{ ($cities[$i]->id == $ManualOrder->cities_id) ? 'selected="selected"' : '' }}>{{$cities[$i]->name}}</option>
                                    
                                @endfor
                                
                            </select> 
                            <small id="city_error" class="form-text text-danger">@if($errors->get('city')) {{$errors->first('city')}} @endif</small>
                        </div>
                        
                        <div class="form-group col-auto">
                            <label for="receiver_name">Reciever Number</label>
                            <input type="text" class="form-control @if($errors->get('receiver_number')) is-invalid @endif" value="{{old('receiver_number')}}@if(isset($ManualOrder)){{$ManualOrder->receiver_number}}@endif" id="receiver_number"  name="receiver_number" placeholder="Reciever Number" required>
                            @if($errors->get('receiver_number')) <small id="receiver_name_error" class="form-text text-danger">{{$errors->first('receiver_name')}} </small>@endif
                        </div> 
                        
                        <div class="form-group col-auto">
                            <label for="receiver_name">Reciever address</label>
                            <textarea class="form-control" id="reciever_address @if($errors->get('reciever_address')) is-invalid @endif"   name="reciever_address" placeholder="reciever_address" required>{{old('reciever_address')}}@if(isset($ManualOrder)){{$ManualOrder->reciever_address}}@endif</textarea>
                            <small id="reciever_address_error" class="form-text text-danger">@if($errors->get('reciever_address')) {{$errors->first('reciever_address')}} @endif</small>
                        </div>  
                    </div>
                    
                    <div class="col-sm-2">
                        <h5>Payment Details <hr></h5>
                        <div class="form-group col-auto">
                            <label for="Product Price">Product Price</label>
                            <input type="number" step="0.01" class="form-control @if($errors->get('product_price')) is-invalid @endif" value="{{old('product_price')}}@if($ManualOrder->product_price != ''){{$ManualOrder->product_price}}@else{{0}}@endif" onchange="onchangeprice();get_fare_list();" id="product_price"  name="product_price" required>
                             <small id="product_price_error" class="form-text text-danger">@if($errors->get('product_price')){{$errors->first('product_price')}} @endif</small>
                        </div>
                        <div class="form-group col-auto">
                            <label for="Delivery Charges">Delivery Charges</label>
                            <input type="number" step="0.01" class="form-control @if($errors->get('dc')) is-invalid @endif" value="{{old('dc')}}@if($ManualOrder != ''){{$ManualOrder->dc}}{{0}}@else @endif" onchange="onchangeprice();get_fare_list();" id="dc"  name="dc" placeholder="dc" required>
                            @if($errors->get('dc')) <small id="dc_error" class="form-text text-danger">{{$errors->first('dc')}} </small>@endif
                        </div>
                        <div class="form-group col-auto">
                            <label for="Packaging Cost">Packaging Cost</label>
                            <input type="number" step="0.01" class="form-control @if($errors->get('packaging_cost')) is-invalid @endif" value="{{old('packaging_cost')}}@if($ManualOrder->packaging_cost != ''){{$ManualOrder->packaging_cost}}@else{{0}}@endif" id="packaging_cost" onchange="onchangeprice();get_fare_list();" name="packaging_cost" placeholder="packaging_cost" >
                             <small id="packaging_cost_error" class="form-text text-danger">@if($errors->get('packaging_cost')) {{$errors->first('packaging_cost')}} @endif</small>
                        </div>
                        <div class="form-group col-auto">
                            <label for="Number">price</label>
                            <input type="number" step="0.01" class="form-control @if($errors->get('price')) is-invalid @endif" value="{{old('price')}}@if($ManualOrder->price != ''){{$ManualOrder->price}}@else{{0}}@endif" onchange="onchangeprice();get_fare_list()" id="price"  name="price" placeholder="Price" readonly>
                            <small id="price_error" class="form-text text-danger">@if($errors->get('price')) {{$errors->first('price')}} @endif</small>
                        </div>
                        <div class="form-group col-auto">
                            <label for="Number">Advance Payment</label>
                            
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text" id="btncustomerpaymentiframe">Add</div>
                                </div>
                                <input type="number" step="0.01" class="form-control @if($errors->get('advance_payment')) is-invalid @endif" onchange="onchangeprice()" value="{{old('advance_payment')}}@if($ManualOrder->advance_payment != ''){{$ManualOrder->advance_payment}}@else{{0}}@endif" onchange="onchangeprice();get_fare_list()" id="advance_payment"  name="advance_payment" placeholder="Advance Payment" readonly>
                            </div>
                             
                            <small id="advance_payment_error" class="form-text text-danger">@if($advance_payment_status) {{$advance_payment_status}} @endif</small>
                        </div>
            
                        <div class="form-group col-auto">
                            <label for="Number">COD Amount</label>
                            <input type="number" step="0.01" class="form-control @if($errors->get('cod_amount')) is-invalid @endif" value="{{old('cod_amount')}}@if($ManualOrder->cod_amount != ''){{$ManualOrder->cod_amount}}@else{{0}}@endif" id="cod_amount"  name="cod_amount" placeholder="COD" readonly required>
                             <small id="cod_amount_error" class="form-text text-danger">@if($errors->get('cod_amount')){{$errors->first('cod_amount')}} @endif</small>
                        </div>
                    
                    </div>
                    
                    <div class="col-sm-2">
                        
                        <h5>Product Details <hr></h5>
                        
                        <div class="form-group col-sm">
                            <label for="Number">Pieces</label>
                            <input type="text" class="form-control @if($errors->get('total_pieces')) is-invalid @endif" value="{{old('total_pieces')}}@if(isset($ManualOrder)){{trim($ManualOrder->total_pieces)}}@endif" id="total_pieces"  name="total_pieces" placeholder="Total Pieces" required>
                            @if($errors->get('total_pieces')) <small id="total_pieces_error" class="form-text text-danger">{{$errors->first('total_pieces')}} </small>@endif
                        </div>
            
                        <div class="form-group col-auto">
                            <label for="Number">weight</label>
                            <input type="text" class="form-control @if($errors->get('weight')) is-invalid @endif" value="{{old('weight')}}@if(isset($ManualOrder)){{$ManualOrder->weight}}@endif" id="weight"  name="weight" onchange="get_fare_list()" placeholder="Weight (in kg)" required>
                            @if($errors->get('weight')) <small id="weight_error" class="form-text text-danger">{{$errors->first('weight')}} </small>@endif
                        </div>
                        
                        <div class="form-group col-auto">
                            <label for="Description">Description</label>
                            <textarea class="form-control" id="description @if($errors->get('description')) is-invalid @endif" name="description" placeholder="description" required>{{old('description')}}@if(isset($ManualOrder)){{$ManualOrder->description}}@endif</textarea>
                            <small id="description_error" class="form-text text-danger">@if($errors->get('description')) {{$errors->first('description')}} @endif</small>
                        </div>
                    
                    </div>
                    
                    <div class="col-sm-3">
                        
                        <h5>Shipment Details <hr></h5>
                        
                        <div class="form-group col-sm">
                            <label for="address">Shipment type</label>
                            <select class="form-control" id="shipment_type" onchange="get_fare_list();shipment_type_change();" name="shipment_type" required>
                                <option value="">Select Shipment type</option>
                                <option value="local">Local Rider</option>
                                <option value="trax">Trax</option> 
                            </select> 
                            <small id="shipment_type_error" class="form-text text-danger"></small>
                        </div>
                    
                        <div id="trax_shipment_fields" style="display:none;">
                            
                            <div class="form-group col-sm"> 
                                <label for="Shipment Method">Shipment Method</label>
                                <select class="form-control @if($errors->get('shipping_mode_id')) is-invalid @endif shipping_mode_id_dropdown shipping_mode_id" name="shipping_mode_id" id="shipping_mode_id" onchange="calculate_charges()" >
                                    <option value="" selected="selected">Select Shipment Method</option> 
    
                                </select>
                                <small id="shipping_mode_id_error" class="form-text text-danger shipping_mode_id_error">@if($errors->get('fare')) {{$errors->first('fare')}} @endif</small>
                            </div>
            
                            <div class="form-group col-sm">
                                <label for="fare">Fare</label>
                                <input type="text" onkeyup="limit(this);" class="form-control @if($errors->get('fare')) is-invalid @endif fare" value="{{old('fare')}}@if(isset($ManualOrder)){{trim($ManualOrder->fare)}}@endif" id="fare" autocomplete="off" name="fare" placeholder="fare"  >
                                <small id="fare_error" class="form-text text-danger fare_error">@if($errors->get('fare')) {{$errors->first('fare')}} @endif</small>
                            </div>
            
                            <div class="form-group col-sm">
                                <label for="reference_number">Customer refence</label>
                                <input type="text" class="form-control @if($errors->get('reference_number')) is-invalid @endif" value="{{old('reference_number')}}@if(isset($ManualOrder)){{trim($ManualOrder->reference_number)}}@endif" id="reference_number"  name="reference_number" required>
                                @if($errors->get('reference_number')) <small id="reference_number_error" class="form-text text-danger">{{$errors->first('reference_number')}} </small>@endif
                            </div>
                            
                        </div>
                    
                         
                    <div class="form-group col-auto">   
                    <label for="slect status">Select Order status</label>
                        <select class="form-control custom-select" aria-label="Default select example" name="order_status" id="order_status" required>
                            <option  value ="" selected=selected>Select Order Status</option>
                            @foreach($statuses as $status)
                        
                                <option value="{{$status->name}}">{{$status->name}}</option>                
                                
                            @endforeach 
                        </select>
                        <small id="order_status_error" class="form-text text-danger"></small>
                    </div>
                            
                        <div class="form-group col-auto"> 
                            @if($ManualOrder->status == 'dispatched')
                                @if(Auth::guard('admin')->check())
                                    <button type="submit" class="btn btn-primary" name="submit" id="save" value="save">Save</button>
                                    <button type="submit" class="btn btn-primary" name="submit" id="saveandprint" value="saveandprint">Save & Print Slip</button>
                                    
                                @else
                                
                                    <button type="button"class="btn btn-warning">Parcel is Distached, only admin can retype this parcel</p>
                                @endif
                            @else
                                <button type="submit" class="btn btn-primary" name="submit" id="save" value="save">Save</button>
                                <button type="submit" class="btn btn-primary" name="submit" id="saveandprint" value="saveandprint">Save & Print Slip</button>
                            @endif
                            
                            
                            
                        </div>
                        
                    </div>
                    <div class="col-sm-12 row">
                <div class="col-sm-4">
                    <div class="d-flex justify-content-start">
                        <div class="form-group">
                            <input type="text" class="form-control" id="sku_number" placeholder="Enter SKU" name="sku_number" >
                        </div>   
            
                        <div class="form-group">
                            <button type="button" id="add_product_btn" class="btn btn-primary" >Add Order</button>
                        
                        </div>
                        
                    </div>
                </div>
                
                <div class="col-sm-8">
                    <table class="table table-bordered">
                        
                        <thead>
                            
                            <tr>
                                <th scope="col" class="delete_btn_class">#</th>  
                                <th scope="col">SKU</th>
                                <th scope="col">Product Name</th> 
                                <th scope="col">Price</th>
                            </tr>
                        </thead>
                        <tbody id="row_data">
                            <?php $row_id=1;?>
                            <?php $total_amount=0;?>
                            <?php $total_products=0;?>
                            
                            @foreach($inventories as $inventory)
                                <tr id="<?=$row_id?>">
                                    <td class="delete_btn_class">
                                        <button type="button" class="btn btn-danger " onclick="delete_row('<?=$row_id?>','{{$inventory->id}}')">Delete</button>
                                    </td>
                                    <td>{{$inventory->sku}}</td>
                                    <td>{{$inventory->name}}</td>
                                    <td>{{$inventory->sale}}</td> 
                                    
                                    <?php $total_amount+=$inventory->sale;?>
                                    <?php $total_products++;?>
                                    <?$row_id++?>
                                </tr>
                            @endforeach
                            <?php $total_amount+=0;?>
                            
                        </tbody>
                        <tbody id="row_data">
                            <tr>
                                <th scope="col" colspan="4"><h4><lable>Total Products: <span class="badge badge-secondary" id="total_products">
                            <?=$total_products;?></span></lable></h4></th>
                                <th scope="col" colspan="4"><h4><lable>Total Amount: <span class="badge badge-secondary" id="total_amount">
                            <?=$total_amount;?></span></lable></h4></th>
                            </tr> 
                              
                        </tbody>
                    </table>
                </div>
                
            </div>
                    
                </div>
            </div>
            
            
            
            
        </form>
    </div>
    
<script>
    function calculate_charges()
{  
    $("body").addClass("loading"); 
    let destination_city_id = document.getElementById("city").value;
    let estimated_weight = document.getElementById("weight").value;
    let shipping_mode_id = document.getElementById("shipping_mode_id").value;
    let price = document.getElementById("cod_amount").value;
    let dc = document.getElementById("dc");
    $.ajax({
          url: base_url + '/trax/calculate-charges',
          headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
          type:"POST",
          dataType: 'json',
          data:{
            // $data['service_type_id'] = 1;
            // $data['origin_city_id'] = 202;
            // $data['destination_city_id'] = 172;
            // $data['estimated_weight'] = 0.53;
            // $data['shipping_mode_id'] = 1;
            // $data['amount'] = 300;
            service_type_id:1,
            origin_city_id:202,
            destination_city_id:destination_city_id,
            estimated_weight:estimated_weight,
            shipping_mode_id:shipping_mode_id,
            amount:price,
          },
          success:function(response){
              $("body").removeClass("loading"); 
              //console.log(response.data.status);
            //$('#successMsg').show();
            if(response.data.status == 0)
            {
                let data_fare = response.data.information.charges;
                var tota_fare =data_fare.total_charges+data_fare.gst;
                //console.log(tota_fare);
                // $("#fare[0]").val(tota_fare);
                document.getElementById("fare").value = (tota_fare.toFixed(2));
                dc.value = (tota_fare.toFixed(2));
                onchangeprice();
                
                // $('#exampleModalCenter').modal('hide'); 
                // $('#exampleModalCenter').modal({
                // show: 'false'
                // }); 
                
            }
            else
            { 
                var errors='';
                Object.keys(response.data.errors).forEach(function(key) {
                    const obj = {
                        key: response.data.errors[key]
                    }
                    errors += response.data.errors[key]+'\n'; 
                });
                alert(errors); 
            }
            
            //console.log(response);
            
          },
          error: function(response) {
              alert(response)
              $("body").removeClass("loading"); 
            // $('#nameErrorMsg').text(response.responseJSON.errors.name);
            // $('#emailErrorMsg').text(response.responseJSON.errors.email);
            // $('#mobileErrorMsg').text(response.responseJSON.errors.mobile);
            // $('#messageErrorMsg').text(response.responseJSON.errors.message);
          },
      });
}


function get_fare_list()
{   
    var shipment_type_value = document.getElementById("shipment_type").value;
    if( shipment_type_value == 'local' || shipment_type_value == '')
    {
        return;
    }
    document.getElementById("fare").value = '';
    let destination_city_id = document.getElementById("city").value;
    let estimated_weight = document.getElementById("weight").value;
    let shipping_mode_id = document.getElementById("shipping_mode_id").value;
    let price = document.getElementById("cod_amount").value;
    
    
    let advance_payment = document.getElementById("advance_payment").value;
    
    
    if (destination_city_id == '')
    {
        // alert('city not working');
        return;
        //console.log(destination_city_id+"\n"+estimated_weight+"\n"+shipping_mode_id+"\n"+price)
    }
    else if ( estimated_weight <=0  ||  estimated_weight == "")
    {
        // alert('weight is not working');
        return;
    } 
    else if ( price <0  ||  price == "" || advance_payment == "")
    {
        // alert('cod and advance');
        return;
    }
    $("body").addClass("loading"); 
    $.ajax({
          url: base_url + '/trax/get-fare-list',
          headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
          type:"POST",
          dataType: 'json',
          data:{
            // $data['service_type_id'] = 1;
            // $data['origin_city_id'] = 202;
            // $data['destination_city_id'] = 172;
            // $data['estimated_weight'] = 0.53;
            // $data['shipping_mode_id'] = 1;
            // $data['amount'] = 300;
            service_type_id:1,
            origin_city_id:202,
            destination_city_id:destination_city_id,
            estimated_weight:estimated_weight,
            shipping_mode_id:shipping_mode_id,
            amount:price,
          },
          success:function(response){
               
            if(response.best_fare.length != 0)
            {
                text ='';
                //console.log(response.best_fare);
                (response.best_fare).forEach(element => text+='<option value="'+(element.shipping_mode_id)+'">'+(element.shippment)+' fare:('+(element.fare.toFixed(2))+')</option>' );
                //console.log(text);
                text = '<option>Select Shipment Mode</option>'+text;
                document.getElementById("shipping_mode_id").innerHTML = text;
                
              $("body").removeClass("loading");
                
            }
            else
            { 
                
              $("body").removeClass("loading");
                alert(response.data.message)
            }
            
            //console.log(response); 
          },
          error: function(response) {
              alert(response)
              $("body").removeClass("loading");
              
            // $('#nameErrorMsg').text(response.responseJSON.errors.name);
            // $('#emailErrorMsg').text(response.responseJSON.errors.email);
            // $('#mobileErrorMsg').text(response.responseJSON.errors.mobile);
            // $('#messageErrorMsg').text(response.responseJSON.errors.message);
          },
      });
}

function shipment_type_change()
{
    var shipment_type_value = document.getElementById("shipment_type").value;
    if(shipment_type_value == 'trax')
    {
        document.getElementById("trax_shipment_fields").style.display = "block"; 
    }
    else if(shipment_type_value == 'local' || shipment_type_value == '')
    { 
        document.getElementById("trax_shipment_fields").style.display = "none";
    }
    
}
 
$('#order_status').val('{{$ManualOrder->status}}');
get_fare_list();
onchangeprice();
</script>
     
  @endsection
