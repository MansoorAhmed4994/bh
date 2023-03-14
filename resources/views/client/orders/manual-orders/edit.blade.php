
@extends('layouts.'.Auth::getDefaultDriver())

@section('content')
 <head>
<script type="text/javascript">

        var row_id="1";
        
       
        
    $( document ).ready(function() {
        
        
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
        var row = document.getElementById(id);
        row.parentNode.removeChild(row);
        
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: base_url + '/admin/inventory/deletcustomerproduct/'+inventory_id,
            type: 'GET', 
            dataType: 'json',
            success: function(e)
            { 
                $('#price').val(e.price);
                
                $("#total_amount").html(e.price);
                
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
                    $('#price').val(e.price);
                    for(var i=0; i< e.inventory.length; i++)
                    {
                        total_products++;
                        total_amount+=parseInt(e.inventory[i].sale);
                        row_data += '<tr id="'+row_id+'"><td class="delete_btn_class"><button type="button" class="btn btn-danger " onclick="delete_row('+row_id+','+e.inventory[i].id+')">Delete</button></td><td>'+e.inventory[i].name+'</td><td>'+e.inventory[i].sale+'</td></tr>';
    
                    row_id++;
                    }
                    total_amount += 250;
                    $("#row_data").html(row_data);
                    $("#total_products").html(total_products);
                    $("#total_amount").html(total_amount);
                    $("body").removeClass("loading");
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
        
        let price = document.getElementById("price").value; 
        let advance_payment = document.getElementById("advance_payment").value;
        let cod_amount = (price-advance_payment);
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
                    // all_images = final_images;

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
                        $("body").removeClass("loading"); 
                    },
                    error: function(e) {
                        console.log(e.responseText);
                    }
                });
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
    <div class="row mb-3">
        <div class="col-lg-12 margin-tb">
            <div class="text-center">
                <h4>Edit / {{$ManualOrder->id}}</h4> 
            </div>
        </div>
    </div>
    
    <div class="container"> 
         
        @if(Session::has('order_placed_message'))
            <div class="alert alert-success" role="alert">
                {{session()->get('order_placed_message')}}
            </div> 
        @endif

        <form action="{{ route('ManualOrders.update',$ManualOrder->id) }}" id="update_form"  enctype="multipart/form-data" method="post">
            @csrf
            
            <input type="hidden" value="{{$ManualOrder->id}}" name="order_id" id="order_id">
            <div class="container">
                <div class="row"> 
                    <div class="row">
                        
                        <?php $count=1;?>
                        
                        @if(!empty($ManualOrder->images))
                            @foreach(explode('|', $ManualOrder->images) as $image)  
                            <div class="form-group col-sm">
                                <div class="card" id="imagebox{{$count}}" style="max-width: 200px;">
                                    <img class="card-img-top" src="{{asset($image)}}" alt="Card image cap" >
                                    <div class="card-body">
                                        <button type="button"  onclick="delete_image('{{$image}}','imagebox{{$count}}')" class="btn btn-primary"  data-toggle="modal" data-target="#exampleModal">
                                        delete
                                        </button> 
                                    </div>
                                </div>
                            </div>
                                <?php $count++;?> 
                            @endforeach
                        @endif
                            
                        
                        
                    </div>   
                    
                    <div class="form-group">
                        <input type="file" name="images[]" id="images" multiple/>
                        <!--<div class="file btn btn-lg btn-secondary">Add new-->
                            <input type="hidden" name="images_path" id="images_path" />
                            
                        <!--</div>-->
                        @if($errors->get('images'))<small id="images_error" class="form-text text-danger"> {{$errors->first('images')}} </small>@endif
                    </div>
                    
                        
                    <div class="row">
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
                    
                    <div class="row">
                
                        <h5>Reciever Detail <hr></h5>
                        
                        <div class="form-group col-auto">
                            <label for="receiver_name">Reciever Name</label>
                            <input type="text" class="form-control @if($errors->get('receiver_name')) is-invalid @endif" value="{{old('receiver_name')}}@if(isset($ManualOrder)){{$ManualOrder->receiver_name}}@endif" id="receiver_name"  name="receiver_name" placeholder="Reciever Name" required>
                            @if($errors->get('receiver_name')) <small id="receiver_name_error" class="form-text text-danger">{{$errors->first('receiver_name')}} </small>@endif
                        </div> 
            
                        <!--<div class="form-group col-auto">-->
                        <!--    <label for="address">city</label>-->
                        <!--    <input type="text" class="form-control" id="city @if($errors->get('city')) is-invalid @endif"   name="city" placeholder="City" value="{{old('city')}}@if(isset($ManualOrder)){{$ManualOrder->city}}@endif"/>-->
                        <!--    <small id="city_error" class="form-text text-danger">@if($errors->get('city')) {{$errors->first('city')}} @endif</small>-->
                        <!--</div> -->
                         
                        
                        <div class="form-group col-sm">
                            <label for="address">city</label>
                            <select class="form-control @if($errors->get('city')) is-invalid @endif cities_dropdown city" id="city"  onchange="get_fare_list(<?=$count?>)" name="city" required>
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
            
                        <div class="form-group col-auto">
                            <label for="Number">Pieces</label>
                            <input type="text" class="form-control @if($errors->get('total_pieces')) is-invalid @endif" value="{{old('total_pieces')}} @if(isset($ManualOrder)){{$ManualOrder->total_pieces}}@endif" id="total_pieces"  name="total_pieces" placeholder="Total Pieces" required>
                            @if($errors->get('total_pieces')) <small id="total_pieces_error" class="form-text text-danger">{{$errors->first('total_pieces')}} </small>@endif
                        </div>
            
                        <div class="form-group col-auto">
                            <label for="Number">weight</label>
                            <input type="text" class="form-control @if($errors->get('weight')) is-invalid @endif" value="{{old('weight')}}@if(isset($ManualOrder)){{$ManualOrder->weight}}@endif" id="weight"  name="weight" placeholder="Weight (in kg)" >
                            @if($errors->get('weight')) <small id="weight_error" class="form-text text-danger">{{$errors->first('weight')}} </small>@endif
                        </div>
            
                        <div class="form-group col-auto">
                            <label for="Number">price</label>
                            <input type="text" class="form-control @if($errors->get('price')) is-invalid @endif" value="{{old('price')}}@if(isset($ManualOrder)){{$ManualOrder->price}}@endif" onchange="onchangeprice()" id="price"  name="price" placeholder="Price" >
                            @if($errors->get('price')) <small id="price_error" class="form-text text-danger">{{$errors->first('price')}} </small>@endif
                        </div>
            
                        <div class="form-group col-auto">
                            <label for="Number">Advance Payment</label>
                            <input type="text" class="form-control @if($errors->get('advance_payment')) is-invalid @endif" onchange="onchangeprice()" value="{{old('advance_payment')}}@if(isset($ManualOrder)){{$ManualOrder->advance_payment}}@endif" id="advance_payment"  name="advance_payment" placeholder="Advance Payment" >
                            @if($errors->get('advance_payment')) <small id="advance_payment_error" class="form-text text-danger">{{$errors->first('advance_payment')}} </small>@endif
                        </div>
            
                        <div class="form-group col-auto">
                            <label for="Number">COD Amount</label>
                            <input type="text" class="form-control @if($errors->get('cod_amount')) is-invalid @endif" value="{{old('cod_amount')}}@if(isset($ManualOrder)){{$ManualOrder->cod_amount}}@endif" id="cod_amount"  name="cod_amount" placeholder="COD" readonly>
                            @if($errors->get('cod_amount')) <small id="cod_amount_error" class="form-text text-danger">{{$errors->first('cod_amount')}} </small>@endif
                        </div>
                        
                        <div class="form-group col-auto">
                            <label for="Description">Description</label>
                            <textarea class="form-control" id="description @if($errors->get('description')) is-invalid @endif" name="description" placeholder="description" required>{{old('description')}}@if(isset($ManualOrder)){{$ManualOrder->description}}@endif</textarea>
                            <small id="description_error" class="form-text text-danger">@if($errors->get('description')) {{$errors->first('description')}} @endif</small>
                        </div>
            
                        <div class="form-group col-auto">
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-start">
                    <div class="form-group col-sm-3">
                        <input type="text" class="form-control" id="sku_number" placeholder="Enter SKU" name="sku_number" >
                    </div>   
        
                    <div class="form-group">
                        <button type="button" id="add_product_btn" class="btn btn-primary" >Add Order</button>
                    
                    </div>
                    
                </div>
            </div>
            <table class="table table-bordered">
                    
                    <thead>
                        
                        <tr>
                            <th scope="col" class="delete_btn_class">#</th> 
                            <th scope="col">Product ID</th>
                            <th scope="col">Product Name</th> 
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
                                <td>{{$inventory->name}}</td>
                                <td>{{$inventory->sale}}</td> 
                                
                                <?php $total_amount+=$inventory->sale;?>
                                <?php $total_products++;?>
                                <?$row_id++?>
                            </tr>
                        @endforeach
                        <?php $total_amount+=250;?>
                        
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
                
        </form> 

        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this image?</p>
                </div>
                <div class="modal-footer"> 
                    <button type="button" id="delete_image" class="btn btn-primary" >Yes</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
                </div>
            </div>
        </div>
    </div>
    
    
     
  @endsection
