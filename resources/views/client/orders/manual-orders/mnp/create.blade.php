@extends('layouts.'.Auth::getDefaultDriver())
@section('content')
 <head>
<link  href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.css" rel="stylesheet"/> 
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js" defer></script>


<script type="text/javascript">

$( document ).ready(function() {
    var cities =@json($cities);
    $('.cities_dropdown').select2();
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
        div {
        position: relative;
        overflow: hidden;
        }
        input[type=file] {
        position: absolute;
        font-size: 50px;
        opacity: 0; 
        right: 0;
        top: 0;
        }

    </style>  
</head>
    <div class="row mb-3">
        <div class="col-lg-12 margin-tb">
            <div class="text-center">
                <h2>M&P Create Bookings</h2> 
            </div>
        </div>
    </div>
    
    <div class="container"> 
         
        @if(Session::has('order_placed_message'))
            <div class="alert alert-success" role="alert">
                {{session()->get('order_placed_message')}}
            </div> 
        @endif

        <form action="{{ route('mnp.create.booking') }}"  name="mnp_create_booking" method="post">
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
                        
                        <div class="form-group col-sm">
                            <label for="receiver_name">Reciever Number</label>
                            <input type="number" class="form-control @if($errors->get('receiver_number')) is-invalid @endif" value="{{old('receiver_number')}}@if(isset($ManualOrder)){{trim($ManualOrder->receiver_number)}}@endif" id="receiver_number[]"  name="receiver_number[]" placeholder="Reciever Number" required>
                            @if($errors->get('receiver_number')) <small id="receiver_name_error[]" class="form-text text-danger">{{$errors->first('receiver_name')}} </small>@endif
                        </div> 
                        
                        <div class="form-group col-sm">
                            <label for="receiver_name">Reciever address</label>
                            <textarea class="form-control  @if($errors->get('reciever_address')) is-invalid @endif" id="reciever_address[]"   name="reciever_address[]" placeholder="reciever_address" required>{{old('reciever_address')}}  @if(isset($ManualOrder)) {{trim($ManualOrder->reciever_address)}}  @endif</textarea>
                            <small id="reciever_address_error[]" class="form-text text-danger">@if($errors->get('reciever_address')){{$errors->first('reciever_address')}}@endif</small>
                        </div>    
            
                        <div class="form-group col-sm">
                            <label for="Number">Pieces</label>
                            <input type="text" class="form-control @if($errors->get('total_pieces')) is-invalid @endif" value="{{old('total_pieces')}}@if(isset($ManualOrder)){{trim($ManualOrder->total_pieces)}}@endif" id="total_pieces[]"  name="total_pieces[]" placeholder="Total Pieces" required>
                            @if($errors->get('total_pieces')) <small id="total_pieces_error[]" class="form-text text-danger">{{$errors->first('total_pieces')}} </small>@endif
                        </div>
                    
                    </div>
                    <div class="row">
            
                        <div class="form-group col-sm">
                            <label for="reference_number">Customer refence</label>
                            <input type="text" class="form-control @if($errors->get('reference_number')) is-invalid @endif" value="{{old('reference_number')}}@if(isset($ManualOrder)){{trim($ManualOrder->reference_number)}}@endif" id="reference_number[]"  name="reference_number[]" required>
                            @if($errors->get('reference_number')) <small id="reference_number_error[]" class="form-text text-danger">{{$errors->first('reference_number')}} </small>@endif
                        </div>
            
                        <div class="form-group col-sm">
                            <label for="Number">weight</label>
                            <input type="number" class="form-control @if($errors->get('weight')) is-invalid @endif" value="{{old('weight')}}@if(isset($ManualOrder)){{trim($ManualOrder->weight)}}@endif" id="weight[]"  name="weight[]" placeholder="Weight (in kg)" required>
                            @if($errors->get('weight')) <small id="weight_error[]" class="form-text text-danger">{{$errors->first('weight')}} </small>@endif
                        </div>
            
                        <div class="form-group col-sm">
                            <label for="price">price</label>
                            <input type="number" onkeyup="limit(this);" class="form-control @if($errors->get('price')) is-invalid @endif" value="{{old('price')}}@if(isset($ManualOrder)){{trim($ManualOrder->price)}}@endif" id="price[]"  name="price[]" placeholder="Price" required>
                            @if($errors->get('price')) <small id="price_error[]" class="form-text text-danger price_error[]">{{$errors->first('price')}} </small>@endif
                        </div>
            
                        <div class="form-group col-sm">
                            <label for="address">city</label>
                            <select class="form-control @if($errors->get('city')) is-invalid @endif cities_dropdown" id="city[]"   name="city[]" required>
                                <option value="">Select City</option>
                                @foreach($cities as $city)
                                
                                    
                                    <option value="{{$city}}" {{ ($city == $ManualOrder->city) ? 'selected="selected"' : '' }}>{{$city}}</option>
                                    
                                @endforeach
                                
                            </select> 
                            <small id="city_error" class="form-text text-danger">@if($errors->get('city')) {{$errors->first('city')}} @endif</small>
                        </div>
                    </div>
                    <?php $count++;?>
                @endforeach
                 
            
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </div>
                
        </form> 

    </div>
    
 
    <script type="text/javascript">
    //   $(".cities_dropdown").select2();
    </script>
     
  @endsection
