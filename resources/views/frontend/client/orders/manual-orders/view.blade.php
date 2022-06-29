 
@extends('layouts.app')

@section('content')
 
<script type="text/javascript">
        var base_url = '<?php echo e(url('/')); ?>';
        var delete_image_path ="";
        var image_box_id = '2';
        var all_images = '{{$ManualOrder->images}}';
        all_images = all_images.split("|");

         

        function myFunction() {
            var myobj = document.getElementById(image_box_id);
            myobj.remove();
        }
        function delete_image()
        {
            all_images = all_images.filter(e => e !== delete_image_path); 
            
            //alert(all_images);
        }  

        $( document ).ready(function() {
            
            $('#delete_image').on('click',function(e)
            {  
                
                $("#delete_image").prop('disabled', true); 
                var final_images = all_images.filter(e => e !== delete_image_path);  
                if(final_images != null)
                {
                    final_images = final_images.join('|');


                }
                else
                {
                    final_images = '';
                }
                alert(final_images);
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
                        //alert(e.messege);   
                        myFunction(); 
                    },
                    error: function(e) {
                        console.log(e.responseText);
                    }
                });
            });
        });

        // $( document ).ready(function() {
            
        //     $('#add_new_image').on('change',function(e)
        //     {    
        //         // var formData = new FormData('#');
        //         // var formData = new FormData(document.querySelector('update_form'));
        //         var formData = $('#update_form').serializeArray().reduce(function(obj, item) {
        //             obj[item.name] = item.value;
        //             return obj;
        //         }, {});
        //         $.ajax({
        //             headers: {
        //                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //             },
        //             url: base_url + '/client/orders/ManualOrders/add-image',
        //             data:formData,
        //             type: 'POST',
        //             dataType: 'json',
        //             success: function(e)
        //             {
        //                 alert(e.messege);  
        //                 // $('#'+image_box_id).remove();
        //                 // alert(response);
        //             },
        //             error: function(e) {
        //                 console.log(e.responseText);
        //             }
        //         });
        //     });
        // });

        

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

    <div class="row mb-3">
        <div class="col-lg-12 margin-tb">
            <div class="text-center">
                <h2>Customer Manual Orders Details</h2> 
            </div>
        </div>
    </div>
    
    <div class="container"> 
       @isset($success)
            <div class="alert alert-success" role="alert">
                {{$success}}
            </div> 
        @endisset

        <form action="{{route('ManualOrders.confirm.order.by.customer',$ManualOrder->id)}}" method="get">
             @csrf
            <div class="form-group">
                <?php $count=1;?>
                
                @if(!empty($ManualOrder->images))
                    @foreach(explode('|', $ManualOrder->images) as $image)  
                        <div class="card" style="width: 7rem;" id="imagebox{{$count}}">
                            <img class="card-img-top flot-left" src="{{asset($image)}}" width=100 alt="Card image cap">
                        </div>
                        <?php $count++;?> 
                    @endforeach
                @endif
                
            </div>
            <br> 
            <div class="row"> 
                
                <div class="form-group col-sm-3">
                    <label for="receiver_name">Reciever Name</label>
                    <input type="text" class="form-control @if($errors->get('receiver_name')) is-invalid @endif" value="{{old('receiver_name')}} @if(isset($ManualOrder)) {{$ManualOrder->receiver_name}}  @endif" id="receiver_name"  name="receiver_name" placeholder="Reciever Name"  disabled>
                    @if($errors->get('receiver_name')) <small id="receiver_name_error" class="form-text text-danger">{{$errors->first('receiver_name')}} </small>@endif
                </div>  
                
                <div class="form-group col-sm-3">
                    <label for="receiver_name">Reciever Number</label>
                    <input type="text" class="form-control @if($errors->get('receiver_number')) is-invalid @endif" value="{{old('receiver_number')}} @if(isset($ManualOrder)) {{$ManualOrder->receiver_number}}  @endif" id="receiver_number"  name="receiver_number" placeholder="Reciever Number"  disabled>
                    @if($errors->get('receiver_number')) <small id="receiver_name_error" class="form-text text-danger">{{$errors->first('receiver_name')}} </small>@endif
                </div>   
            
                <div class="form-group col-sm-3">
                    <label for="receiver_name">Reciever address</label>
                    <textarea class="form-control" id="reciever_address @if($errors->get('reciever_address')) is-invalid @endif"   name="reciever_address" placeholder="reciever_address"  disabled>{{old('reciever_address')}}  @if(isset($ManualOrder)) {{$ManualOrder->reciever_address}}  @endif</textarea>
                    <small id="reciever_address_error" class="form-text text-danger">@if($errors->get('reciever_address')) {{$errors->first('reciever_address')}} @endif</small>
                </div>  
    
                <div class="form-group col-sm-3">
                    <label for="address">city</label>
                    <input type="text" class="form-control" id="city @if($errors->get('city')) is-invalid @endif"  value="{{old('whatsapp_number')}} @if(isset($ManualOrder)) {{$ManualOrder->city}}  @endif" name="city" placeholder="City"  disabled>{{old('city')}}  @if(isset($ManualOrder)) {{$ManualOrder->city}}  @endif</textarea>
                    <small id="city_error" class="form-text text-danger">@if($errors->get('city')) {{$errors->first('city')}} @endif</small>
                </div> 
            </div>

            <div class="row">
    
                <div class="form-group col-sm-3">
                    <label for="Number">price</label>
                    <input type="text" class="form-control @if($errors->get('price')) is-invalid @endif" value="{{old('price')}} @if(isset($ManualOrder)) {{$ManualOrder->price}}  @endif" id="price"  name="price" placeholder="Price"  disabled>
                    @if($errors->get('price')) <small id="price_error" class="form-text text-danger">{{$errors->first('price')}} </small>@endif
                </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary">Confirmed</button>
            </div>
            </div>
                
        </form> 

        
    </div>
    
    
     
  @endsection
