
@extends('layouts.'.Auth::getDefaultDriver())
@section('content') 
<script type="application/javascript"> 
        
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
                $("body").addClass("loading"); 
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
                    url: '{{route("support.customer.details")}}',
                    
                    data: {
                        number: number,  
                    },
                    type: 'POST',
                    dataType: 'json',
                    success: function(e)
                    {
                         
                        if (typeof e.error !== 'undefined') 
                        {
                            if(e.error == '1')
                            {  
                                FormFieldRequiredTrue();
                                toastr.warning('please enter details', 'Error'); 
                                 
                            }  
                        }
                        else
                        {
                            if(e.field_values['first_name'] == null || e.field_values['first_name'] == '' || e.field_values['last_name'] == null || e.field_values['last_name'] == '' || e.field_values['address'] == null || e.field_values['address'] == '' || e.field_values['whatsapp_number'] == null || e.field_values['whatsapp_number'] == '' || e.field_values['email'] == null || e.field_values['email'] == '')
                            {
                                FormFieldRequiredTrue();
                                $('#form_field_edit').val('updated');
                                toastr.warning('Please fill all details', 'Error');
                            }
                            
                            $('#customer_id').val(e.field_values['id']);
                            $('#first_name').val(e.field_values['first_name']);
                            $('#last_name').val(e.field_values['last_name']);
                            $('#address').val(e.field_values['address']);
                            $('#email').val(e.field_values['email']);
                            $('#whatsapp_number').val(e.field_values['whatsapp_number']);
                            $("#order_save_btn").show();
                            console.log(e.messege);
                        }
                        
                        // alert(e.city);
                        
                        
                        $("body").removeClass("loading");
                    },
                    error: function(e) {
                        console.log(e.responseText);
                    }
                });
            });
        });
        
            
        function FormFieldRequiredTrue()
        { 
            $('#customer_form_fields').show();
            $('#first_name').show();
            $('#first_name').show();
            $('#address').show();
            $('#email').show();
            $('#last_name').show();
            $('#whatsapp_number').show();
        }
        
            
        function FormFieldRequiredFalse()
        {
            $('#customer_form_fields').hide();
            $('#first_name').hide();
            $('#first_name').hide();
            $('#address').hide();
            $('#email').hide();
            $('#last_name').hide();
            $('#whatsapp_number').hide();
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
        
        form {
            color: black;
        }
        table
        {
            color: black;
            background: white;
        }
        
        

    </style>
    
    
    
    <div class="row mb-3">
        <div class="col-lg-12 margin-tb">
            <div class="text-center">
                <h2>Support Ticket</h2> 
            </div>
        </div>
    </div>
    
    <div class="modal fade" id="create_ticket_form_model" tabindex="-1" role="dialog" data-toggle="modal" aria-labelledby="create_ticket_form_model" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Edit Status</h5> 
                </div>
                
                <div class="modal-body">
                    <form method="post" action="{{ route('support.store') }}" onsubmit="return validateForm()" enctype="multipart/form-data" >
                        @csrf
            
            
                        <div class="form-group">
                            <label class="file btn btn-sm btn-success">Upload File
                                <input type="file" class="form-control" name="images[]" id="images" accept="image/png, image/gif, image/jpeg" multiple  />
                                
                            </label>
                            <small id="images_error" class="form-text text-danger">@if($errors->get('images')) {{$errors->first('images')}}@endif </small>
                        </div> 
                        
                        <div class="row">
                            
                            <div class="col">
                                
                                <div class="form-group"> 
                                    <label for="Number">Number</label>
                                    <input type="tel"  class="form-control"   pattern="0[0-9]{2}(?!1234567)(?!1111111)(?!7654321)[0-9]{8}" id="number"  name="number" placeholder="033X-XXXXXXX" required>
                                    <small id="number_error" class="form-text text-danger">@if($errors->get('number')) {{$errors->first('number')}} @endif</small>
                                </div> 
                                
                            </div>
                            
                            <div class="col">  
                                
                                <div class="form-group"> 
                                    <label for="Whatsapp Number">Whatsapp Number</label>
                                    <input type="tel"  class="form-control"   pattern="0[0-9]{2}(?!1234567)(?!1111111)(?!7654321)[0-9]{8}" id="whatsapp_number"  name="whatsapp_number" placeholder="033X-XXXXXXX" required>
                                    <small id="whatsapp_number_error" class="form-text text-danger">@if($errors->get('whatsapp_number')) {{$errors->first('whatsapp_number')}} @endif</small>
                                </div>
                                
                            </div>
                        </div>
                        
                        <input type="hidden" id="customer_id"  name="customer_id" >
                        <input type="hidden" id="form_field_edit"  name="form_field_edit" >
    
                        <div id="customer_form_fields"> 
                            <div class="row"> 
                                <div class="col"> 
                                    <div class="form-group">
                                        <label for="First Name">First Name</label>
                                        <input type="text" class="form-control" id="first_name"  name="first_name" placeholder="First Name" required>
                                         <small id="first_name_error" class="form-text text-danger">@if($errors->get('first_name')){{$errors->first('first_name')}} @endif</small>
                                    </div> 
                                
                            </div>
                            
                            <div class="col"> 
                
                        
                                    <div class="form-group">
                                        <label for="Last Name">Last Name</label>
                                        <input type="text" class="form-control" id="last_name"  name="last_name" placeholder="Last Name" required>
                                         <small id="last_name_error" class="form-text text-danger">@if($errors->get('last_name')){{$errors->first('last_name')}} @endif</small>
                                    </div>
                                </div>
                            </div>  
                            
                             
                            <div class="row"> 
                                <div class="col"> 
                        
                                    <div class="form-group">
                                        <label for="address">Address</label>
                                        <textarea class="form-control" id="address"  name="address" placeholder="address" required></textarea>
                                        <small id="address_error" class="form-text text-danger">@if($errors->get('address')) {{$errors->first('address')}} @endif</small>
                                    </div>  
                                
                            </div>
                            
                            <div class="col">
                        
                                    <div class="form-group">
                                        <label for="address">email</label>
                                        <input type="email" class="form-control" id="email"  name="email" placeholder="email" required>
                                        <small id="email_error" class="form-text text-danger">@if($errors->get('email')) {{$errors->first('email')}} @endif</small>
                                    </div>
                                </div>
                            </div>
                                
                            
                        </div> 
                        
                        <div class="row"> 
                            <div class="col">  
                    
                                <div class="form-group ">
                                    <label for="address">Select Reason</label>
                                    
                                    <select class="form-control" id="ticket_type"  name="ticket_type" required>
                                        <option value="">Select Reason</option> 
                                        <option value="incomplete_order">Incomplete Order</option> 
                                        <option value="wrong_article">Wron Article</option> 
                                        <option value="damage_order">Damage Order</option> 
                                        <option value="track_order">Track Order</option> 
                                        <option value="delay_in_delivery">Delay in Delivery</option> 
                                        <option value="complain">Complaint</option> 
                                        <option value="feed_back">Feed Back</option>  
                                        <option value="other">Feed Back</option>
                                        
                                    </select> 
                                    <small id="city_error" class="form-text text-danger">@if($errors->get('city')) {{$errors->first('city')}} @endif</small>
                                </div> 
                                
                            </div> 
                            
                            <div class="col"> 
                    
                                <div class="form-group" id="order_id_field">
                                    <label for="orderID">Order ID </label>
                                    <input type="tel"  class="form-control"  id="order_id"  name="order_id" placeholder="Order ID" required>
                                    <small id="order_id_error" class="form-text text-danger">@if($errors->get('order_id')) {{$errors->first('order_id')}} @endif</small>
                                </div> 
                                
                            </div>
                        </div> 
            
                        <div class="form-group">
                            <label for="msg">Messege</label>
                            <textarea class="form-control" id="msg"  name="msg" placeholder="Write your query" required style="height: 150px;"></textarea>
                            <small id="msg_error" class="form-text text-danger">@if($errors->get('msg')) {{$errors->first('msg')}} @endif</small>
                        </div> 
                        
            
                        <div class="form-group" id="order_save_btn">
                            <button type="submit" onclick="validateForm()" class="btn btn-primary">Create</button>
                        </div>
                            
                    </form>
            
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"  id="create_ticket_form_model" class="close" data-dismiss="modal">Close</button>
                    <button button="button" class="btn btn-primary" id="QuickEditOrderUpdate">Save changes</button>
                </div>
                
            </div>
        </div>
    </div>
    
    <div class="col-sm-12">
        <div class="d-flex justify-content-right"> 
                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#create_ticket_form_model">
                      + Create SupportTicket
                    </button>  
        </div>
    <br/>
    
    
    
    
        <div class="table-container" id="table-container-data" style="overflow-x:auto;"> 
        <table class="table table-bordered" style="min-height: 500px;">
            <thead>
                <tr>  
                    <th scope="col">#</th> 
                    <th scope="col">Ticket ID</th>
                    <th scope="col">Status</th>
                    <th scope="col">Send MSG</th> 
                    <th scope="col">Customer ID</th>
                    <th scope="col">Ord.ID</th>
                    <th scope="col">Reson</th>   
                    <th scope="col">MSG</th> 
                    <th scope="col">Images</th> 
                    <th scope="col">cr.Date</th>
                    <th scope="col">Up.Date</th>
                    <th scope="col">Updated by</th>
                    <th scope="col">created by</th>
                </tr>
            </thead>
            <tbody>  
                <?php $count=1;?>
                @foreach($list as $lists)
                
                <tr class="list_<?=$count;?> status-{!! str_replace(' ', '-', $lists->status) !!}"> 
                     
                    <td scope="row"><?=$count?></td>  
                    <td>{{$lists->id}}</td> 
                    <td>{{$lists->status}}</td> 
                    <td>
                        <div class="btn-group" role="group">
                            <button id="btnGroupDrop1" type="button" class="btn btn-secondary dropdown-toggle" style="background:white;border:2px solid #4ac95a;color:black;padding: 0 11px;" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                              <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/5/5e/WhatsApp_icon.png/640px-WhatsApp_icon.png" width=30 style="margin-right: 5px;">Send msg to brandhub
                            </button>
                            <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">            
                                <a target="_blank" class="dropdown-item" href="https://api.whatsapp.com/send?phone=+923362240865&text=Assalamualaikum %0a My name is {{$lists->customers->first_name}} %0aHere is my details: %0a Ticket id: {{$lists->id}} %0a Customer ID: {{$lists->customers->id}} %0aNumber: {{$lists->customers->number}} %0aWhatsapp Number: {{$lists->customers->whatsapp_number}} %0aI am Waiting for your response">Follow up msg</a> 
                            </div>
                        </div>
                    </td> 
                    <td>{{$lists->customers_id}}</td>  
                    <td>{{$lists->order_id}}</td>  
                    <td>{{$lists->ticket_type}}</td>  
                    <td>{{$lists->msg}}</td>   
                    
                    <td >
                        <button class="btn btn-primary" onclick="UniversalImagesBoxes(0,'{{$lists->images}}',{{$lists->id}})">Images</button>
                        <div id="order_images">
                        @if(!empty($lists->images)) 
                         <?php $count_image_index= 0;?>
                            @foreach(explode('|', $lists->images) as $image)   
                            
                            <img class="pop rounded imgaes-demand" style="margin-right: 5px;display:none" src="{{asset($image)}}" onclick="UniversalImagesBoxes(<?=$count_image_index;?>,'{{$lists->images}}',{{$lists->id}})" width="25" />
                            <?php $count_image_index++;?>
                            @endforeach
                        @endif
                        </div>
                    </td>
                      
                    <td style="font-size: 10px;">{{date('d-M-y', strtotime($lists->created_at))}} <br> {{date('G:i a', strtotime($lists->created_at))}}</td>
                    <td style="font-size: 10px;">{{date('d-M-y', strtotime($lists->updated_at))}} <br> {{date('G:i a', strtotime($lists->updated_at))}}</td> 
                    
                    <td>{{$lists->updated_by}}</td>
                    <td>{{$lists->created_by}}</td>
                </tr>
                <?php $count++;?>
                @endforeach
            </tbody>
            
        </table>
    </div>
    {!! $list->appends(Request::all())->links() !!} 
    
    
    </div>
         
        
    <div class="col-sm-12">
        <div class="d-flex justify-content-center">
            <div class="col-sm-3">
                 
            </div> 
            
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
