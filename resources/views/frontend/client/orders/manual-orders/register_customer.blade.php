 
@extends('frontend.layouts.app')

@section('content')
    <!--Select2 Drop down--> 
    <script type="application/javascript">
       
        var delete_image_path ="";
        var image_box_id = '';
        var all_images=''; 
        var allimagesboxes = '';
        
        @if(Cookie::get('id') == true)
            all_images = '{{Cookie::get("images")}}';
            all_images = all_images.split("|");
        @endif 
        
        $('#ImageDeleteModalClose').on('click',function(){
            $('#ImageDeleteModal').modal('hide');
        });
        
        function myFunction() {
            var myobj = document.getElementById(image_box_id);
            myobj.remove();
        }
        
        function delete_image(number)
        {
            // alert(image_path);
            var ImagePathID = document.getElementById('img_id_'+number);
            let imageType = ImagePathID.getAttribute("data-image-type"); 
            delete_image_path=imageType;
            image_box_id='imagebox_'+number;  
            document.getElementById("delete_image").disabled = false;
            $('#ImageDeleteModal').modal('show');
            
        } 
        
        $( document ).ready(function() {
        
            $('#delete_image').on('click',function(e)
            {   
                $("body").addClass("loading"); 
                 $("#delete_image").prop('disabled', true); 
                 console.log(all_images)
                var final_images = all_images.filter(e => e !== delete_image_path);  
                console.log('Before Delete'+delete_image_path)
                console.log('after Delete'+final_images);
                if(final_images != null)
                {
                    final_images = final_images.join('|');
                    all_images = final_images.split("|");

                }
                else
                {
                    final_images = ''; 
                }
                
                
                console.log(final_images);
                // document.getElementById('images_path').value = final_images;
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: '{{route("customer.delete.product.screenshot")}}',
                    data: {
                        delete_path: delete_image_path,
                        images: final_images,
                        order_id: $('#order_id').html()
                        // @if(Cookie::get('order_id') == true) 
                        //     order_id: '{{Cookie::get('order_id')}}', 
                        // @endif 
                    },
                    type: 'POST',
                    dataType: 'json',
                    success: function(e)
                    {
                        // console.log(e);
                        
                        myFunction(); 
                        if(typeof e.success !== 'undefined')
                        {
                            $("body").removeClass("loading");
                            toastr["success"](e.messege, 'success'); 
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
                        
                        $('#ImageDeleteModal').modal('hide');
                        $("body").removeClass("loading");
                        console.log(e.responseText);
                    }
                });
            });
            
        });  
        
        function limit(element)
        {
            var max_chars = 11;
        
            if(element.value.length > max_chars) {
                element.value = element.value.substr(0, max_chars);
            }
        }
        
        function login_form_show()
        {
            $('#customer_login_form').show(); 
            $('#customer_details').hide(); 
            $('#customer_registeration_form').hide(); 
            $('#welcome_gif').hide();
        }
        
        function customer_details_show()
        {
            $('#customer_login_form').hide(); 
            $('#customer_details').show(); 
            $('#customer_registeration_form').hide(); 
            $('#welcome_gif').hide();
        }
        
        function register_form_show()
        {
            $('#customer_login_form').hide(); 
            $('#customer_details').hide(); 
            $('#customer_registeration_form').show(); 
            $('#welcome_gif').hide();
        }
         
    
        $( document ).ready(function() {
            $('#customer_login_number').focusout('click',function(e)
            {  
                var number = $('#customer_login_number').val(); 
                CustomerGetRegisterationDetails(number);
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
            });
        });
        
        
        function CustomerDeleteCoockies(number)
        {
            $("body").addClass("loading");
            
            
            var base_url = '<?php echo e(url('/')); ?>'; 
            
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{route("customer.delete.coockies")}}',
                data: {
                    number: number,  
                },
                type: 'POST',
                dataType: 'json',
                success: function(e)
                {
                    console.log('working 1'+e);    
                    if(e.success == 1)
                    {
                        console.log('working 1');  
                        return 'deleted';
                        // console.log('working 4'+e);
                        // console.log('Old Coockies Expired');
                    }
                    else
                    {
                        console.log('working 2');  
                        return 'not deleted';
                        // console.log('working 5'+e);
                        // alert(coockie_delete_status);
                    }
                    
                },
                error: function(e) {
                    console.log(e.responseText);
                    $("body").removeClass("loading");
                }
            });
        } 
        
        function CustomerGetRegisterationDetails(number)
        {
            var coockie_delete_status;
            var coockie_number = "{{Cookie::get('number')}}";
            // console.log();
            // console.log($.parseJSON(getCookies('number')));
            $("body").addClass("loading");
                
            if("{{Cookie::get('number')}}" != '')
            {
                if(number != coockie_number)
                {
                    // alert('working'); 
                    coockie_delete_status = CustomerDeleteCoockies(number);
                    console.log(coockie_delete_status);  
                    // return;
                }
                else
                { 
                    $("body").removeClass("loading");
                    return;
                }
            }
            
            var base_url = '<?php echo e(url('/')); ?>'; 
            
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: base_url + '/customer/login',
                data: {
                    number: number,  
                },
                type: 'POST',
                dataType: 'json',
                success: function(e)
                {
                     
                    if(e.success == 1)
                    {
                        
                        $('#customer_id').html(e.id); 
                        $('#customer_name').html((e.first_name+' '+e.last_name));
                        $('#customer_number').html(e.number);
                        $('#customer_whatsapp_number').html(e.whatsapp_number);
                        // $('#customer_city').html(e.data[a].city);
                        $('#customer_address').html(e.address);
                        $('#receiver_name').html(e.receiver_name);
                        $('#receiver_number').html(e.receiver_number);
                        $('#receiver_address').html(e.receiver_address);
                        $('#order_id').html(e.order_id); 
                       
                        if(e.images != null || typeof e.images !== 'undefined')
                        {
                            // console.log(e.images);
                            all_images = e.images.split("|");
                            // all_images.forEach(RenderImages);
                            // var images_data = RenderImages(e.images);
                            console.log("All Images data: "+all_images);
                            $('#edit_images_box').html(e.rendered_images); 
                            
                        }
 
                        customer_details_show(); 
                        
                         
      
                    }
                    else if(e.success == 0)
                    {
                        register_form_show();
                        $('#number').val(number);
                        $('#whatsapp_number').val(number); 
                        $("body").removeClass("loading");
                    }
                    
                    $("body").removeClass("loading");
                    
                },
                error: function(e) {
                    console.log(e.responseText);
                    $("body").removeClass("loading");
                }
            });
        } 
        
        function RenderImages(item, index) { 
            allimagesboxes += '<div class="card card-box-custom col-sm-6" id="imagebox_'+index+'">';
            allimagesboxes += '<div class="card-body" id="edit_selected_img_card_body"  style="padding: 1rem 1rem 0.5rem 1rem;"> ';
            allimagesboxes += '<img class="card-img-top" src="{{asset('+item+')}}" id="img_id_'+index+'" data-image-type="'+item+'" alt="Card image cap" width="200">';
            allimagesboxes += '<div>';
            allimagesboxes += '<div class="card-footer" style="padding: 0 1.25rem 2em 1.25em;">';
            allimagesboxes += '<a onclick="delete_image('+index+')" class="btn btn-primary">Delete</a>';
            allimagesboxes += '<div></div>'; 
        }
        
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
            
            if($('#customer_login_number').val() == '')
            {
                validation_status = false;
                $('#customer_login_number_error').html('Please Enter number');
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
        
            /* Turn off scrollbar when body element has the loading class */
    body.loading{
        overflow: hidden; 
        
    }
    /* Make spinner image visible when body element has the loading class */
    body.loading .overlay{
        display: block;
        z-index: 11111;
    }
    .welcome_gif
    {
        width:100%;
    }
    #customer_registeration_form .form-group label,  .form-group label {
        color: white;
        font-weight: bold;
        font-size: 2em;
    }
    input[type=file] 
        {
        position: absolute;
        font-size: 50px;
        opacity: 0; 
        right: 0;
        top: 0;
        }
        
    @keyframes highlight {
      from {
        box-shadow: 0;
      }
    
      to { 
        box-shadow: 2px 1px 12px 3px;
      }
    }
    
    /*@keyframes texthighlight {*/
    /*  from {*/
    /*    text-shadow: 0 0 0 white;*/
    /*  }*/
    
    /*  to { */
        
    /*    text-shadow: 1px 1px 2px white;*/
    /*  }*/
    /*}*/
    
    .highlight {
        
        border:1px solid white;
        animation-duration: 0.5s;
        animation-name: highlight;
        animation-iteration-count: infinite;
        animation-direction: alternate;
    }
    
    .non-focus-text
    {
        color: #9d9999;
    }
    
    
    
    </style>

    
    <div class="overlay">
        <div class="text-center" style="margin-top: 25%;">
            <div class="spinner-grow" style="width: 3rem; height: 3rem;" role="status">
              <span class="sr-only">Loading...</span>
            </div>
        </div>
    </div>
    
    
    <div class="row mb-3">
        <div class="col-lg-12 margin-tb">
            
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
        
    <div class="col-sm-12">
        <div class="d-flex justify-content-center">
            <div class="col-sm-3"> 
                @if(Cookie::get('number') == false)
                    <img class="welcome_gif" id="welcome_gif" src="{{ asset('public/videos/customer/welcome.gif') }}" alt="this slowpoke moves"   />
                @endif   

                    <div id="customer_login_form" @if(Cookie::get('number') == true) style="display:none" @endif>
                        <div class="form-group" > 
                            <label for="Customer Number">Mobile Number</label>
                            <input type="tel"  class="form-control" pattern="0[0-9]{2}(?!1234567)(?!1111111)(?!7654321)[0-9]{8}" id="customer_login_number"  name="customer_login_number" placeholder="03xxxxxxxxx" required>
                            <small id="customer_login_number_error" class="form-text text-danger">@if($errors->get('number')) {{$errors->first('number')}} @endif</small>
                        </div>
                        
                        <div class="form-group">  
                            <div class="form-group" > 
                                <button type="button" class="btn btn-primary"  >Get Code</button>
                            </div> 
                        </div>
                    </div>
                    
                    <div id="customer_details" class="customer_details" @if(Cookie::get('number') == false) style="display:none" @endif> 
                        <form method="post"  action="{{ route('customer.upload.product.screenshot') }}"  enctype="multipart/form-data" class="dropzone" id="dropzone"> 
                            @csrf 
                            <div class="row text-center">
                                <!--<h6><span class="non-focus-text" id="customer_name_header">Hello! @if(Cookie::get('first_name') !== false)  {{Cookie::get('first_name')}}  @endif</span></h6>-->
                                <!--<h6><span class="non-focus-text" id="customer_number_header">@if(Cookie::get('number') !== false) {{ Cookie::get('number')}}   @endif</span></h6>-->
                                <h1>
                                    <b style="font-size: 130%;">Code #<span  id="customer_id"   >@if(Cookie::get('id') !== false)  {{Cookie::get('id')}}  @endif </span></b>
                                </h1>
                                  
                                <!--<h6></b><span > please upload your done Article</span></h6>-->
                                <div class="row form-group">
                                    <label for="formFile" class="btn btn-primary highlight" >Select Picture</label>
                                    <input class="form-control" type="file"  name="images[]" id="formFile" accept="image/png, image/gif, image/jpeg" multiple  required/><button type="submit" class="btn btn-success">Upload</button>
                                    <small id="images_error" class="form-text text-danger">@if($errors->get('images')) {{$errors->first('images')}}@endif </small>
                                </div> 
                            </div>  
                            
                            
                            <div class="form-group row" id="edit_images_box">
                                @if(Cookie::get('id') == true)
                                           
                                    @if(Cookie::get('images') !== false) 
                                     
                                        <?php $i=1;?>
                                        @foreach(explode('|',Cookie::get('images')) as $image)
                                            
                                            <div class="card card-box-custom col-sm-6" id="imagebox_{{$i}}">
                                    
                                                <div class="card-body" id="edit_selected_img_card_body"  style="padding: 1rem 1rem 0.5rem 1rem;"> 
                                                        <img class="card-img-top" src="{{ asset($image) }}" data-image-type="{{$image}}" id="img_id_{{$i}}" alt="Card image cap" width="200">
                                                    
                                                </div> 
                                                
                                                <div class="card-footer" style="padding: 0 1.25rem 2em 1.25em;"> 
                                                    <a onclick="delete_image('{{$i}}')" class="btn btn-primary">Delete</a>
                                                </div>
                                            </div>
                                            <?php $i++;?>
                                        @endforeach
                                        
                                     @endif
                                     
                                @endif
                                
                            </div>
                            
                            
                            <br/><br/><br/><br/>
                            
                             
                            <h6><a type="button" onclick="login_form_show()" class="link-warning">Get New Code</a></h6>  
                            <h4><b>Customer Details: </b></h4><hr>
                            <h6><b>Name: </b><span id="customer_name">@if(Cookie::get('first_name') !== false)  {{Cookie::get('first_name')}}  @endif</span></h6>
                            <h6><b>Number: </b><span id="customer_number">@if(Cookie::get('number') !== false) {{ Cookie::get('number')}}   @endif</span></h6>
                            <h6><b>Whatsapp: </b><span id="customer_whatsapp_number">@if(Cookie::get('whatsapp_number') !== false)  {{Cookie::get('whatsapp_number')}}  @endif</span></h6>
                            
                            
                            <h4><b>Last Shipment Details: </b></h4><hr>
                            <h6><b>Order ID: </b><span id="order_id">@if(Cookie::get('order_id') !== false)  {{Cookie::get('order_id')}}  @endif</span></h6>
                            <h6><b>Reciever Name: </b><span id="receiver_name">@if(Cookie::get('receiver_name') !== false)  {{Cookie::get('receiver_name')}}  @endif</span></h6>
                            <h6><b>Reciever Number: </b><span id="receiver_number">@if(Cookie::get('receiver_number') !== false) {{ Cookie::get('receiver_number')}}   @endif</span></h6> 
                            <h6><b>Reciever Address: </b><span id="reciever_address">@if(Cookie::get('reciever_address') !== false)  {{Cookie::get('reciever_address') }}  @endif</span></h6>

                        </form>  
                    </div>
                
    
                <div id="customer_registeration_form" style="display:none;"> 
                
                    <h4 style="color: #aaaeaa;">please register your self thorugh filling the basic details</h4>
                    <form method="post" action="{{ route('customer.store') }}" onsubmit="return validateForm()">
                        @csrf 
                        <div class="form-group" > 
                            <label for="Customer Number">Mobile Number</label>
                            <input type="tel"  class="form-control" pattern="0[0-9]{2}(?!1234567)(?!1111111)(?!7654321)[0-9]{8}" id="number"  name="number" placeholder="03xxxxxxxxx" required>
                            <small id="number_error" class="form-text text-danger">@if($errors->get('number')) {{$errors->first('number')}} @endif</small>
                        </div>
                        <div class="form-group">
                            <label for="First Name">First Name</label>
                            <input type="text" class="form-control" id="first_name"  name="first_name" placeholder="Enter Your First Name" required>
                             <small id="first_name_error" class="form-text text-danger">@if($errors->get('first_name')){{$errors->first('first_name')}} @endif</small>
                        </div> 
                        
                        <div class="form-group">
                            <label for="First Name">Last Name</label>
                            <input type="text" class="form-control" id="laste_name"  name="laste_name" placeholder="Enter Your Last Name" required>
                             <small id="laste_name_error" class="form-text text-danger">@if($errors->get('laste_name')){{$errors->first('laste_name')}} @endif</small>
                        </div> 
                        
                            <div class="form-group " > 
                            <label for="Number"><img src="https://upload.wikimedia.org/wikipedia/commons/thumb/5/5e/WhatsApp_icon.png/640px-WhatsApp_icon.png" width="30" style="margin-right: 5px;">whatsapp Number</label>
                            <input type="tel"  class="form-control" pattern="0[0-9]{2}(?!1234567)(?!1111111)(?!7654321)[0-9]{8}" id="whatsapp_number"  name="whatsapp_number" placeholder="03xxxxxxxxx" required>
                            <small id="number_error" class="form-text text-danger">@if($errors->get('number')) {{$errors->first('number')}} @endif</small>
                        </div>
                        
                        <div class="form-group ">
                            <label for="address">City</label>
                            
                            <select class="form-control js-example-basic-single leopord_city" id="city"  name="city" data-rel="chosen">
                                <option value="">Select City</option>
                                @for($i=0 ; $i < sizeof($cities); $i++)
                                 
                                    <option value="{{$cities[$i]->id}}">{{$cities[$i]->name}}</option>
                                    
                                @endfor
                                
                            </select> 
                            <small id="city_error" class="form-text text-danger">@if($errors->get('city')) {{$errors->first('city')}} @endif</small>
                        </div>  
            
                        <div class="form-group">
                            <label for="address">Address</label>
                            <textarea class="form-control" id="address"  name="address" placeholder="address" required></textarea>
                            <small id="address_error" class="form-text text-danger">@if($errors->get('address')) {{$errors->first('address')}} @endif</small>
                        </div>  
            
                        <!--<div class="form-group">-->
                        <!--    <label for="address">Description</label>-->
                        <!--    <textarea class="form-control" id="description"  name="description" placeholder="description" required></textarea>-->
                        <!--    <small id="description_error" class="form-text text-danger">@if($errors->get('description')) {{$errors->first('description')}} @endif</small>-->
                        <!--</div>  -->
                        
                        <!--<div class="form-group " style="display:none;">-->
                        <!--    <label for="address">Order addtion</label>-->
                        <!--    <select class="form-control " id="order_addition"  name="order_addition" required>-->
                        <!--        <option value="">Select Order type</option>-->
                        <!--        <option value="addition">Product Addition</option>-->
                        <!--        <option value="pending" selected>New Order</option>-->
                        <!--    </select> -->
                        <!--    <small id="order_addition_error" class="form-text text-danger">@if($errors->get('order_addition')) {{$errors->first('order_addition')}} @endif</small>-->
                        <!--</div> -->
            
                        <!--<div class="form-group" style="display:none;" id="order_id_field">-->
                        <!--    <label for="orderID">Order ID</label>-->
                        <!--    <input type="tel"  class="form-control"  id="order_id"  name="order_id" placeholder="Order ID" >-->
                        <!--    <small id="order_id_error" class="form-text text-danger">@if($errors->get('order_id')) {{$errors->first('order_id')}} @endif</small>-->
                        <!--</div> -->
                        
            
                        <!--<div class="form-group" id="order_save_btn">-->
                        <!--    <button type="submit" onclick="validateForm()" class="btn btn-primary">Save</button>-->
                        <!--</div>-->
                        
                        <div class="form-group" id="register">
                            <button type="submit" id="btn_resgiter" class="btn btn-primary">Register</button>
                            <button type="submit" onclick="login_form_show()" class="btn btn-primary">Login</button>
                            
                            
                        </div>
                    </form>    
                </div>
                     
                    
                    
                     
            </div>
        </div>
        
        <div class="col-sm-6">
            
        <table class="table" id="previouse_order_detail">
           
        </table>
            
        </div>
    </div>
    
<script>
    $(document).ready(function() {
    $('.js-example-basic-single').select2({
        width:'100%', 
        templateSelection: function (data, container) {
            $(container).addClass('form-control');
            return data.text;
          }
    });
    
});
</script>
     
  @endsection
