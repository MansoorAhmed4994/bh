 
@extends('frontend.layouts.app')

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
    </script>

    <div class="row mb-3">
        <div class="col-lg-12 margin-tb">
            <div class="text-center">
                <h2>Customer Manual Orders</h2> 
            </div>
        </div>
    </div>
      
    <div class="container"> 
         
        

        <form method="post" action="{{ route('Frontend.ManualOrders.store') }}" enctype="multipart/form-data" class="dropzone" id="dropzone">
            @csrf

            <div class="form-group">
                <div class="file btn btn-lg btn-primary">Add Images
                    <input type="file" name="images[]" multiple  required/>
                    
                </div>
                @if($errors->get('images'))<small id="images_error" class="form-text text-danger"> {{$errors->first('images')}} </small>@endif
            </div>
            
            

            <div class="form-group">
                <label for="Number">Number</label>
                <input type="number"  class="form-control" onkeydown="limit(this);" onkeyup="limit(this);" id="number"  name="number" placeholder="number Number" required>
                @if($errors->get('number')) <small id="number_error" class="form-text text-danger">{{$errors->first('number')}} </small>@endif
            </div> 

            <div class="form-group">
                <label for="address">Description</label>
                <textarea class="form-control" id="description"  name="description" placeholder="description" required></textarea>
                <small id="description_error" class="form-text text-danger">@if($errors->get('description')) {{$errors->first('description')}} @endif</small>
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
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
                
        </form> 
    </div>
    
    <script type="text/javascript">
    var base_url = '<?php echo e(url('/')); ?>'; 
        $(document).ready(function (e) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $('#number').focusout(function(e) {
                var number = $("#number").val();
                e.preventDefault();
                
                $.ajax({
                        type:'GET',
                        url: base_url + '/frontend/client/orders/ManualOrders/'+number,
                        data: '',
                        cache:false,
                        contentType: false,
                        processData: false,
                        success: (data) => {
                            
                            
                            if(data == 'no value')
                            {
                                
                            
                            }else
                            {
                                $("#first_name").val(data.first_name);
                                $("#address").val(data.first_name); 
                                console.log(data.first_name);
                                
                            }
                            //alert('File has been uploaded successfully');
                            
                        },
                        error: function(data){
                        //console.log(data);
                    }
                });
            });
        });
    </script>
    
     
  @endsection
