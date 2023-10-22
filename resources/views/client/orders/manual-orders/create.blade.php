 
@extends('layouts.'.Auth::getDefaultDriver())

@section('content') 
<script type="application/javascript">
        function fetch_data(id)
        {
            $( document ).ready(function() {
                $('#order_id').val(id); 
                $('#order_addition option[value="addition"]').attr("selected", "selected");
            // alert(name+address);
             });
        }
        
        $( document ).ready(function() {
            $('#order_addition').on('change',function(e)
            {
                if($('#order_addition').val() == 'addition')
                { 
                    $("#order_id_field").css("display", "block"); 
                }
                else
                {
                    $("#order_id_field").css("display", "none"); 
                }
                $('#order_addition option[value="addition"]').attr("selected", "selected");
            // alert(name+address);
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
            
            $('#number').focusout('click',function(e)
            {  
                var number = $('#number').val();
                const firstTwoChars = number.slice(0, 2);
                if(firstTwoChars == '92')
                {
                    alert('please coorect receiver number (for e.g: 03XXXXXXXXX)');
                    return;
                }
                else if(number.length != '11')
                {
                    alert('please coorect receiver number (for e.g: length must be 11 digit)');
                    return;
                    
                }
                // console.log(firstTwoChars);
                var base_url = '<?php echo e(url('/')); ?>';
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: base_url + '/client/orders/ManualOrders/previouse/order-history',
                    data: {
                        number: number,  
                    },
                    type: 'POST',
                    dataType: 'json',
                    success: function(e)
                    {
                        if (typeof e.error !== 'undefined') 
                        {
                            toastr.warning(e.messege, 'No Record found');
                            return;   
                        }
                        // alert(e.city);
                        $('#first_name').val(e.field_values['receiver_name']);
                        $('#address').val(e.address);
                        $('#previouse_order_detail').html(e.messege),  
                        $('#city').val(e.city),   
                        console.log(e.messege);
                        
                        
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
            var fi = document.getElementById('images');
             
            //  alert(fi.files.length);
            if(fi.files.length == 0 )
            {
                $('#images_error').html('Please select files');
                validation_status = false;
            } 
            
            if($('#number').val() == '')
            {
                validation_status = false;
                $('#number_error').html('Please Enter number');
            }
            
            if($('#first_name').val() == '')
            {
                validation_status = false;
                $('#first_name_error').html('Please Enter First Name');
            } 
            
            if($('#address').val() == '')
            {
                validation_status = false;
                $('#address_error').html('Please Enter Address');
            } 
            
            if($('#city').val() == '')
            {
                validation_status = false;
                $('#city_error').html('Please Select City');
            } 
            
            if($('#description').val() == '')
            {
                validation_status = false;
                $('#description_error').html('Please Enter Description');
            } 
            
            if($('#order_addition').val() == '')
            {
                $('#order_addition_error').html('Please Select Order Addition'); 
                validation_status = false;
            }
            
            
            if($('#order_addition').val() == 'addition')
            { 
                if($('#order_id').val() == '')
                {
                    validation_status = false;
                    $('#order_id_error').html('Please Enter Order ID');  
                } 
            }
            
            
            
            return validation_status;
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
        
        .previouse_order_images
        {
            width: 60px;
            margin: 3px;
            border: 9px solid black;
            vertical-align: middle;
            border-style: none;
            border-radius: 17px;
            box-shadow: 1px 1px 0px 0px;
            
        }
        td
        {
            max-width: 225px;
        }
        
        

    </style>
    
    
    
    <div class="row mb-3">
        <div class="col-lg-12 margin-tb">
            <div class="text-center">
                <h2>Customer Manual Orders</h2> 
            </div>
        </div>
    </div>
         
        
    <div class="row col-sm-12">
        <div class="col-sm-3">
            <form method="post" action="{{ route('ManualOrders.store') }}" onsubmit="return validateForm()" enctype="multipart/form-data" class="dropzone" id="dropzone">
                @csrf
                
    
                <div class="form-group">
                    <div class="file btn btn-lg btn-primary">Upload
                        <input type="file" name="images[]" id="images" accept="image/png, image/gif, image/jpeg" multiple  required/>
                        
                    </div>
                    <small id="images_error" class="form-text text-danger">@if($errors->get('images')) {{$errors->first('images')}}@endif </small>
                </div> 
    
                <div class="form-group"> 
                    <label for="Number">Number</label>
                    <input type="tel"  class="form-control"   pattern="0[0-9]{2}(?!1234567)(?!1111111)(?!7654321)[0-9]{8}" id="number"  name="number" placeholder="number Number" required>
                    <small id="number_error" class="form-text text-danger">@if($errors->get('number')) {{$errors->first('number')}} @endif</small>
                </div> 
    
                <div class="form-group">
                    <label for="First Name">Name</label>
                    <input type="text" class="form-control" id="first_name"  name="first_name" placeholder="First Name" required>
                     <small id="first_name_error" class="form-text text-danger">@if($errors->get('first_name')){{$errors->first('first_name')}} @endif</small>
                </div> 
    
                <div class="form-group">
                    <label for="address">Address</label>
                    <textarea class="form-control" id="address"  name="address" placeholder="address" required></textarea>
                    <small id="address_error" class="form-text text-danger">@if($errors->get('address')) {{$errors->first('address')}} @endif</small>
                </div>  
                
                <div class="form-group ">
                        <label for="address">city</label>
                        
                        <select class="form-control " id="city"  name="city" >
                            <option value="">Select City</option>
                            @for($i=0 ; $i < sizeof($cities); $i++)
                             
                                <option value="{{$cities[$i]->id}}">{{$cities[$i]->name}}</option>
                                
                            @endfor
                            
                        </select> 
                        <small id="city_error" class="form-text text-danger">@if($errors->get('city')) {{$errors->first('city')}} @endif</small>
                    </div> 
    
                <div class="form-group">
                    <label for="address">Description</label>
                    <textarea class="form-control" id="description"  name="description" placeholder="description" required></textarea>
                    <small id="description_error" class="form-text text-danger">@if($errors->get('description')) {{$errors->first('description')}} @endif</small>
                </div>  
                
                <div class="form-group ">
                    <label for="address">Order addtion</label>
                    <select class="form-control " id="order_addition"  name="order_addition" required>
                        <option value="">Select Order type</option>
                        <option value="addition">Product Addition</option>
                        <option value="pending">New Order</option>
                    </select> 
                    <small id="order_addition_error" class="form-text text-danger">@if($errors->get('order_addition')) {{$errors->first('order_addition')}} @endif</small>
                </div> 
    
                <div class="form-group" style="display:none;" id="order_id_field">
                    <label for="orderID">Order ID</label>
                    <input type="tel"  class="form-control"  id="order_id"  name="order_id" placeholder="Order ID" >
                    <small id="order_id_error" class="form-text text-danger">@if($errors->get('order_id')) {{$errors->first('order_id')}} @endif</small>
                </div> 
                
    
                <div class="form-group">
                    <button type="submit" onclick="validateForm()" class="btn btn-primary">Save</button>
                </div>
                    
            </form> 
        </div>
        <div class="col-sm-6">
            
        <table class="table" id="previouse_order_detail">
           
        </table>
            
        </div>
    </div>
    
    
    
     
  @endsection
