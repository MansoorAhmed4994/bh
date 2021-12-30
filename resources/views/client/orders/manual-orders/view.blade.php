 
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
                <h2>Customer Manual Orders</h2> 
            </div>
        </div>
    </div>
    
    <div class="container"> 
         
        @if(Session::has('order_placed_message'))
            <div class="alert alert-success" role="alert">
                {{session()->get('order_placed_message')}}
            </div> 
        @endif

        <form id="update_form" name="update_form" enctype="multipart/form-data"method="post">
             
            <div class="form-group">
            <?php $count=1;?>
            
            @if(!empty($ManualOrder->images))
                @foreach(explode('|', $ManualOrder->images) as $image)  
                    <div class="card" style="width: 18rem;" id="imagebox{{$count}}">
                        <img class="card-img-top flot-left" src="{{asset($image)}}" alt="Card image cap">
                    </div>
                    <?php $count++;?> 
                @endforeach
            @endif
                
            </div>
            <br>
            <div class="form-group">
                <div class="file btn btn-lg btn-secondary">Add new
                    <input type="file" name="images[]" id="images" multiple/>
                    <input type="text" name="images_path" id="images_path"/>
                    
                </div>
                @if($errors->get('images'))<small id="images_error" class="form-text text-danger"> {{$errors->first('images')}} </small>@endif
            </div>

            <div class="form-group">
                <label for="First Name">First Name</label>
                <input type="text" class="form-control @if($errors->get('first_name')) is-invalid @endif" value="{{old('first_name')}} @if(isset($ManualOrder)) {{$ManualOrder->first_name}}  @endif" id="first_name"  value="{{old('first_name')}} @if(isset($ManualOrder)) {{$ManualOrder->first_name}}  @endif" name="first_name" placeholder="First Name" required>
                @if($errors->get('first_name')) <small id="first_name_error" class="form-text text-danger"></small>{{$errors->first('first_name')}} @endif
            </div> 

            <div class="form-group">
                <label for="First Name">last Name</label>
                <input type="text" class="form-control @if($errors->get('last_name')) is-invalid @endif" value="{{old('last_name')}} @if(isset($ManualOrder)) {{$ManualOrder->last_name}}  @endif" id="last_name"  value="{{old('last_name')}} @if(isset($ManualOrder)) {{$ManualOrder->last_name}}  @endif" name="last_name" placeholder="First Name" required>
                @if($errors->get('last_name')) <small id="last_name_error" class="form-text text-danger"></small>{{$errors->first('last_name')}} @endif
            </div> 

            

            <div class="form-group">
                <label for="Number">Number</label>
                <input type="text" class="form-control @if($errors->get('number')) is-invalid @endif" value="{{old('number')}} @if(isset($ManualOrder)) {{$ManualOrder->number}}  @endif" id="number"  name="number" placeholder="number Number" required>
                @if($errors->get('number')) <small id="number_error" class="form-text text-danger">{{$errors->first('number')}} </small>@endif
            </div>     

            <div class="form-group">
                <label for="Number">Whatsapp Number</label>
                <input type="text" class="form-control @if($errors->get('whatsapp_number')) is-invalid @endif" value="{{old('whatsapp_number')}} @if(isset($ManualOrder)) {{$ManualOrder->number}}  @endif" id="whatsapp_number"  name="whatsapp_number" placeholder="Whatsapp number" required>
                @if($errors->get('whatsapp_number')) <small id="whatsapp_number_error" class="form-text text-danger">{{$errors->first('whatsapp_number')}} </small>@endif
            </div> 

            <div class="form-group">
                <label for="address">Address</label>
                <textarea class="form-control" id="address @if($errors->get('address')) is-invalid @endif"   name="address" placeholder="address" required>{{old('address')}}  @if(isset($ManualOrder)) {{$ManualOrder->address}}  @endif</textarea>
                <small id="address_error" class="form-text text-danger">@if($errors->get('address')) {{$errors->first('address')}} @endif</small>
            </div>  

            <div class="form-group">
                <label for="address">city</label>
                <textarea class="form-control" id="city @if($errors->get('city')) is-invalid @endif"   name="city" placeholder="City" required>{{old('city')}}  @if(isset($ManualOrder)) {{$ManualOrder->city}}  @endif</textarea>
                <small id="city_error" class="form-text text-danger">@if($errors->get('city')) {{$errors->first('city')}} @endif</small>
            </div> 
            
            <h2>Reciever Detail <hr></h2>
            
            <div class="form-group">
                <label for="receiver_name">Reciever Name</label>
                <input type="text" class="form-control @if($errors->get('receiver_name')) is-invalid @endif" value="{{old('receiver_name')}} @if(isset($ManualOrder)) {{$ManualOrder->receiver_name}}  @endif" id="receiver_name"  name="receiver_name" placeholder="Reciever Name" required>
                @if($errors->get('receiver_name')) <small id="receiver_name_error" class="form-text text-danger">{{$errors->first('receiver_name')}} </small>@endif
            </div> 
            
            <div class="form-group">
                <label for="receiver_name">Reciever Number</label>
                <input type="text" class="form-control @if($errors->get('receiver_number')) is-invalid @endif" value="{{old('receiver_number')}} @if(isset($ManualOrder)) {{$ManualOrder->receiver_number}}  @endif" id="receiver_number"  name="receiver_number" placeholder="Reciever Number" required>
                @if($errors->get('receiver_number')) <small id="receiver_name_error" class="form-text text-danger">{{$errors->first('receiver_name')}} </small>@endif
            </div> 
            
            <div class="form-group">
                <label for="receiver_name">Reciever address</label>
                <textarea class="form-control" id="reciever_address @if($errors->get('reciever_address')) is-invalid @endif"   name="reciever_address" placeholder="reciever_address" required>{{old('reciever_address')}}  @if(isset($ManualOrder)) {{$ManualOrder->reciever_address}}  @endif</textarea>
                <small id="reciever_address_error" class="form-text text-danger">@if($errors->get('reciever_address')) {{$errors->first('reciever_address')}} @endif</small>
            </div>    

            <div class="form-group">
                <label for="Number">Pieces</label>
                <input type="text" class="form-control @if($errors->get('total_pieces')) is-invalid @endif" value="{{old('total_pieces')}} @if(isset($ManualOrder)) {{$ManualOrder->total_pieces}}  @endif" id="total_pieces"  name="total_pieces" placeholder="Total Pieces" required>
                @if($errors->get('total_pieces')) <small id="total_pieces_error" class="form-text text-danger">{{$errors->first('total_pieces')}} </small>@endif
            </div>

            <div class="form-group">
                <label for="Number">weight</label>
                <input type="text" class="form-control @if($errors->get('weight')) is-invalid @endif" value="{{old('weight')}} @if(isset($ManualOrder)) {{$ManualOrder->weight}}  @endif" id="weight"  name="weight" placeholder="Weight (in kg)" required>
                @if($errors->get('weight')) <small id="weight_error" class="form-text text-danger">{{$errors->first('weight')}} </small>@endif
            </div>

            <div class="form-group">
                <label for="Number">price</label>
                <input type="text" class="form-control @if($errors->get('price')) is-invalid @endif" value="{{old('price')}} @if(isset($ManualOrder)) {{$ManualOrder->price}}  @endif" id="price"  name="price" placeholder="Price" required>
                @if($errors->get('price')) <small id="price_error" class="form-text text-danger">{{$errors->first('price')}} </small>@endif
            </div>

            <div class="form-group">
                <label for="Number">Advance Payment</label>
                <input type="text" class="form-control @if($errors->get('advance_payment')) is-invalid @endif" value="{{old('advance_payment')}} @if(isset($ManualOrder)) {{$ManualOrder->advance_payment}}  @endif" id="advance_payment"  name="advance_payment" placeholder="Advance Payment" required>
                @if($errors->get('advance_payment')) <small id="advance_payment_error" class="form-text text-danger">{{$errors->first('advance_payment')}} </small>@endif
            </div>

            <div class="form-group">
                <label for="Number">COD Amount</label>
                <input type="text" class="form-control @if($errors->get('cod_amount')) is-invalid @endif" value="{{old('cod_amount')}} @if(isset($ManualOrder)) {{$ManualOrder->cod_amount}}  @endif" id="cod_amount"  name="cod_amount" placeholder="COD" required>
                @if($errors->get('cod_amount')) <small id="cod_amount_error" class="form-text text-danger">{{$errors->first('cod_amount')}} </small>@endif
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
                
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
