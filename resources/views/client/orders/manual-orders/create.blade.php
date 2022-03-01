 
@extends('layouts.app')

@section('content')
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
    
    <script>
        
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
                
                //alert(final_images);
                var base_url = '<?php echo e(url('/')); ?>';
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: base_url + '/client/orders/ManualOrders/previouse/order-history',
                    data: {
                        number:  $('#number').val(), 
                    },
                    type: 'POST',
                    dataType: 'json',
                    success: function(e)
                    {
                        $('#previouse_order_detail').html(e.messege),  
                        console.log(e.messege);
                        
                        
                    },
                    error: function(e) {
                        console.log(e.responseText);
                    }
                });
            });
        });
    </script>

    <div class="row mb-3">
        <div class="col-lg-12 margin-tb">
            <div class="text-center">
                <h2>Customer Manual Orders</h2> 
            </div>
        </div>
    </div>
      
    <div class="container"> 
         
        
        <div class="row">
            <div class="col-sm-6">
                <form method="post" action="{{ route('ManualOrders.store') }}" enctype="multipart/form-data" class="dropzone" id="dropzone">
                    @csrf
        
                    <div class="form-group">
                        <div class="file btn btn-lg btn-primary">Upload
                            <input type="file" name="images[]" multiple  required/>
                            
                        </div>
                        @if($errors->get('images'))<small id="images_error" class="form-text text-danger"> {{$errors->first('images')}} </small>@endif
                    </div> 
        
                    <div class="form-group">
                        <label for="Number">Number</label>
                        <input type="number"  class="form-control" onkeydown="limit(this);" onkeyup="limit(this);" pattern="[0-9]{11}" id="number"  name="number" placeholder="number Number" required>
                        @if($errors->get('number')) <small id="number_error" class="form-text text-danger">{{$errors->first('number')}} </small>@endif
                    </div> 
        
                    <div class="form-group">
                        <label for="First Name">Name</label>
                        <input type="text" class="form-control" id="first_name"  name="first_name" placeholder="First Name" required>
                        @if($errors->get('first_name')) <small id="first_name_error" class="form-text text-danger"></small>{{$errors->first('first_name')}} @endif
                    </div> 
        
                    <div class="form-group">
                        <label for="address">Address</label>
                        <textarea class="form-control" id="address"  name="address" placeholder="address" required></textarea>
                        <small id="address_error" class="form-text text-danger">@if($errors->get('address')) {{$errors->first('address')}} @endif</small>
                    </div>   
        
                    <div class="form-group">
                        <label for="address">Description</label>
                        <textarea class="form-control" id="description"  name="description" placeholder="description" required></textarea>
                        <small id="description_error" class="form-text text-danger">@if($errors->get('description')) {{$errors->first('description')}} @endif</small>
                    </div> 
        
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                        
                </form> 
            </div>
            <div class="col-sm-6">
                
            <table class="table" id="previouse_order_detail">
               
            </table>
                
            </div>
        </div>
    </div>
    
     
  @endsection
