@extends('layouts.'.Auth::getDefaultDriver())
@section('content') 

<script type="text/javascript">

    
    
    function get_index(index)
    {
        let elements = document.getElementsByClassName("city");
        // document.getElementById("demo").innerHTML = elements[0].value;
       alert(elements[0].value);
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

    function validateForm() {
        var size = document.getElementsByName("price[]").length;
        //console.log(size);
        for(var i=0; i< size;  i++)
        {
            
            var price = document.getElementsByName("price[]")[i].value;
            //console.log(price);
            if(price <= 0 )
            {
                //console.log(size);
                document.getElementsByClassName("price_error[]")[i].innerHTML = 'error';
            }
        } 
    } 

        var base_url = '<?php echo e(url('/')); ?>';
        function limit(element)
        {
            var max_chars = 5;
        
            if(element.value.length > max_chars) {
                element.value = element.value.substr(0, max_chars);
            }
        }
  
    </script>
    <style>  
        input[type=file] {
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
                <h2>Trax Create Bookings</h2> 
            </div>
        </div>
    </div>
    
    <div class="container"> 
         
        @if(Session::has('order_placed_message'))
            <div class="alert alert-success" role="alert">
                {{session()->get('order_placed_message')}}
            </div> 
        @endif

        <form action="{{ route('trax.create.booking') }}"  name="trax_create_booking" method="post">
            @csrf
            <div class="container"> 
                <?php $count=0;?>
                @foreach($ManualOrders as $ManualOrder)
                    <div class="row">
                        <h4>ID:  {{$ManualOrder->id}}<hr></h4>
            
                        <div class="form-group col-sm">
                            <label for="id">Order ID</label>
                            <input type="text" class="form-control @if($errors->get(' id')) is-invalid @endif" name="id[]" value="{{$ManualOrder->id}}" readonly>
                            @if($errors->get('id')) <small id="id_error[]" class="form-text text-danger">{{$errors->first('id')}} </small>@endif
                        </div>
                        
                        <div class="form-group col-sm">
                            <label for="receiver_name">Reciever Name</label>
                            <input type="text" class="form-control @if($errors->get('receiver_name')) is-invalid @endif" value="{{old('receiver_name')}}@if(isset($ManualOrder)){{trim($ManualOrder->receiver_name)}}@endif" id="receiver_name[]"  name="receiver_name[]" placeholder="Reciever Name" required>
                            @if($errors->get('receiver_name')) <small id="receiver_name_error[]" class="form-text text-danger">{{$errors->first('receiver_name')}} </small>@endif
                        </div> 
                        
                        <?php 
                            
                            $get_char_num = substr($ManualOrder->receiver_number,0,1);
                            if($get_char_num == 0)
                            {
                                $reciever_number = substr($ManualOrder->receiver_number, 1);
                                $reciever_number = '92'.$reciever_number;
                                
                            }
                            elseif($get_char_num == 9)
                            { 
                                $reciever_number = substr($ManualOrder->receiver_number, 2);
                                $reciever_number = '92'.$reciever_number;
                            }
                            
                        ?>
                        <div class="form-group col-sm">
                            <label for="receiver_name">Reciever Number</label>
                            <input type="tel" class="form-control custom-mainforminput" pattern="92[0-9]{2}(?!1234567)(?!1111111)(?!7654321)[0-9]{8}" value="<?=$reciever_number?>" placeholder="(92) xxx xxxxxxx"  id="receiver_number[]"  name="receiver_number[]" data-inputmask="('9'2) 399-9999999" data-mask="" im-insert="true">
                            <!--<input type="number"  data-inputmask="'mask': '99-9999999'"  class="form-control @if($errors->get('receiver_number')) is-invalid @endif receiver_number" value="{{old('receiver_number')}}@if(isset($ManualOrder)){{trim($ManualOrder->receiver_number)}}@endif" id="receiver_number[]"  name="receiver_number[]" placeholder="Reciever Number" required>-->
                            @if($errors->get('receiver_number')) <small id="receiver_name_error[]" class="form-text text-danger">{{$errors->first('receiver_name')}} </small>@endif
                        </div> 
            
                        <div class="form-group col-sm">
                            <label for="Number">Pieces</label>
                            <input type="text" class="form-control @if($errors->get('total_pieces')) is-invalid @endif" value="{{old('total_pieces')}}@if(isset($ManualOrder)){{trim($ManualOrder->total_pieces)}}@endif" id="total_pieces[]"  name="total_pieces[]" placeholder="Total Pieces" required>
                            @if($errors->get('total_pieces')) <small id="total_pieces_error[]" class="form-text text-danger">{{$errors->first('total_pieces')}} </small>@endif
                        </div>
            
                        <div class="form-group col-sm">
                            <label for="Number">weight</label>
                            <input type="number" step='0.01' class="form-control @if($errors->get('weight')) is-invalid @endif weight" onfocusout="get_fare_list(<?=$count?>)" value="{{old('weight')}}@if(isset($ManualOrder)){{trim($ManualOrder->weight)}}@endif" id="weight[]"  name="weight[]" placeholder="Weight (in kg)" required>
                            @if($errors->get('weight')) <small id="weight_error[]" class="form-text text-danger">{{$errors->first('weight')}} </small>@endif
                        </div>
            
                        <div class="form-group col-sm">
                            <label for="address">city</label>
                            
                            <select class="form-control @if($errors->get('city')) is-invalid @endif cities_dropdown city" id="city[]"  onchange="get_fare_list(<?=$count?>)" name="city[]" required>
                                <option value="">Select City</option>
                                @for($i=0 ; $i < sizeof($cities); $i++)
                                 
                                    <option value="{{$cities[$i]->id}}" {{ ($cities[$i]->id == $ManualOrder->cities_id) ? 'selected="selected"' : '' }}>{{$cities[$i]->name}}</option>
                                    
                                @endfor
                                
                            </select> 
                            <small id="city_error" class="form-text text-danger">@if($errors->get('city')) {{$errors->first('city')}} @endif</small>
                        </div> 
                    
                    </div>
                    <div class="row"> 
                        
                        <div class="form-group col-sm">
                            <label for="receiver_name">Reciever address</label>
                            <textarea class="form-control  @if($errors->get('reciever_address')) is-invalid @endif" id="reciever_address[]"   name="reciever_address[]" placeholder="reciever_address" required>{{old('reciever_address')}}@if(isset($ManualOrder)){{trim($ManualOrder->reciever_address)}}@endif</textarea>
                            <small id="reciever_address_error[]" class="form-text text-danger">@if($errors->get('reciever_address')){{$errors->first('reciever_address')}}@endif</small>
                        </div>  
            
                        <div class="form-group col-sm">
                            <label for="reference_number">Customer refence</label>
                            <input type="text" class="form-control @if($errors->get('reference_number')) is-invalid @endif" value="{{old('reference_number')}}@if(isset($ManualOrder)){{trim($ManualOrder->reference_number)}}@endif" id="reference_number[]"  name="reference_number[]" required>
                            @if($errors->get('reference_number')) <small id="reference_number_error[]" class="form-text text-danger">{{$errors->first('reference_number')}} </small>@endif
                        </div>
            
                        <div class="form-group col-sm">
                            <label for="price">price</label>
                            <input type="text"  pattern="[1-9][0-9]{0,4}" class="form-control @if($errors->get('price')) is-invalid @endif price" onchange="onchangePrice(<?=$count?>)" onfocusout="get_fare_list(<?=$count?>);checkprice(<?=$count?>)" value="{{old('price')}}@if(isset($ManualOrder)){{trim($ManualOrder->price)}}@endif" id="price[]"  name="price[]" placeholder="Price" required>
                            @if($errors->get('price')) <small id="price_error[]" class="form-text text-danger price_error[]">{{$errors->first('price')}} </small>@endif
                        </div> 
            
                        <div class="form-group col-sm">
                            <label for="fare">Fare</label>
                            <input type="text" onkeyup="limit(this);" class="form-control @if($errors->get('fare')) is-invalid @endif fare" value="{{old('fare')}}@if(isset($ManualOrder)){{trim($ManualOrder->fare)}}@endif" id="fare[]" autocomplete="off" name="fare[]" placeholder="fare"  required>
                            @if($errors->get('fare')) <small id="fare_error[]" class="form-text text-danger fare_error[]">{{$errors->first('fare')}} </small>@endif
                        </div> 
                        
                        <div class="form-group col-sm">
                            <label for="receiver_name">Item Discription</label>
                            <textarea class="form-control  @if($errors->get('item_description')) is-invalid @endif" id="item_description[]"   name="item_description[]" placeholder="Item description" required>{{old('item_description')}}@if(isset($ManualOrder)){{trim($ManualOrder->description)}}@endif</textarea>
                            <small id="item_description[]" class="form-text text-danger">@if($errors->get('item_description')){{$errors->first('item_description')}}@endif</small>
                        </div> 
                        
                        <div class="form-group col-sm"> 
                            <label for="receiver_name">Shipment Method</label>
                            <select class="form-control @if($errors->get('shipping_mode_id')) is-invalid @endif shipping_mode_id_dropdown shipping_mode_id" name="shipping_mode_id[]" id="shipping_mode_id[]" onchange="calculate_charges(<?=$count?>)" required>
                                <option value="" selected="selected">Select Shipment Method</option> 
                                <option value="1">Rush</option>
                                <option value="2">Saver Plus</option>
                                <option value="3">Swift</option>
                            </select>
                        </div>
                        
                        
                        <div class="row">
                            
                            <div class="form-group col-sm-3">
                                <label for="advance_payment">Advance Payment</label>
                                <input type="number" class="form-control @if($errors->get('advance_payment')) is-invalid @endif advance_payment"  onchange="onchangePrice(<?=$count?>)" value="{{old('advance_payment')}}@if(isset($ManualOrder)){{trim($ManualOrder->advance_payment)}}@endif" id="advance_payment[]"  name="advance_payment[]" placeholder="Price" required>
                                @if($errors->get('advance_payment')) <small id="advance_payment_error[]" class="form-text text-danger advance_payment_error[]">{{$errors->first('advance_payment')}} </small>@endif
                            </div>
                            
                            <div class="form-group col-sm-3">
                                <label for="cod_amount">COD Amount</label>
                                <input type="number" class="form-control @if($errors->get('cod_amount')) is-invalid @endif cod_amount"  value="{{old('cod_amount')}}@if(isset($ManualOrder)){{trim($ManualOrder->cod_amount)}}@endif" id="cod_amount[]"  name="cod_amount[]" placeholder="COD" required >
                                @if($errors->get('cod_amount')) <small id="cod_amount_error[]" class="form-text text-danger cod_amount_error[]">{{$errors->first('cod_amount')}} </small>@endif
                            </div>
                        </div>
                        
                        
                        <!--<div class="form-group">-->
                        <!--    <button type="button" onclick="calculate_charges(<?=$count?>)">Calculate Fare</button>-->
                        <!--</div>-->
                    <?php $count++;?>
                @endforeach
                 
            
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </div>
                
        </form> 

    </div>
    
 <script type="text/javascript">
    
    function onchangePrice(index)
    {
        let price = document.getElementsByClassName("price")[index].value; 
        let advance_payment = document.getElementsByClassName("advance_payment")[index].value;
        let cod_amount = (price-advance_payment);
        document.getElementsByClassName("cod_amount")[index].value = cod_amount;
        
    }
    function checkprice(index)
    {
        let cod_amount = document.getElementsByClassName("cod_amount")[index].value;
        if(cod_amount < 0)
        {
            alert('Cod price is less then 0');
            document.getElementsByClassName("advance_payment")[index].value = "";
        }
    }
    
function calculate_charges(index)
{  
    $("body").addClass("loading"); 
    let destination_city_id = document.getElementsByClassName("city")[index].value;
    let estimated_weight = document.getElementsByClassName("weight")[index].value;
    let shipping_mode_id = document.getElementsByClassName("shipping_mode_id")[index].value;
    let price = document.getElementsByClassName("price")[index].value;
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
                document.getElementsByClassName("fare")[index].value = (tota_fare.toFixed(2));
                
                
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

function get_fare_list(index)
{   
     
    let destination_city_id = document.getElementsByClassName("city")[index].value;
    let estimated_weight = document.getElementsByClassName("weight")[index].value;
    let shipping_mode_id = document.getElementsByClassName("shipping_mode_id")[index].value;
    let price = document.getElementsByClassName("price")[index].value;
    if (destination_city_id == '')
    {
        return;
        //console.log(destination_city_id+"\n"+estimated_weight+"\n"+shipping_mode_id+"\n"+price)
    }
    else if ( estimated_weight <=0  ||  estimated_weight == "")
    {
        return;
    } 
    else if ( price <=0  ||  price == "")
    {
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
              
              //console.log(response);
            //$('#successMsg').show();
            if(response.data.status == 0)
            {
                text ='';
                //console.log(response.best_fare);
                (response.best_fare).forEach(element => text+='<option value="'+(element.shipping_mode_id)+'">'+(element.shippment)+'  fare:('+(element.fare.toFixed(2))+')</option>' );
                //console.log(text);
                text = '<option>Select Shipment Mode</option>'+text;
                document.getElementsByClassName("shipping_mode_id")[index].innerHTML = text;
                
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

    function ShipmentHtml(item, index) 
    {
        // text += "<option>"+index + ": " + item + "<br>"; 
        text += '<option value="'+index+'">'+item+'</option>';
    }

    $( document ).ready(function() { 
        
        $(".fare").on('keydown paste focus mousedown', function(e){
        if(e.keyCode != 9) // ignore tab
            e.preventDefault();
            //alert('dont type, click on Calculate Fare');
    });
      
});
    //   $(".cities_dropdown").select2();
    </script>
    


<!--<script src="{{ asset('public/js/inputmask/jquery.inputmask.js') }}"></script>-->
<!--<script src="{{ asset('public/js/inputmask/bindings/inputmask.binding.js') }}"></script>-->
     
  @endsection
