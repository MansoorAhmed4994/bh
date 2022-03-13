@extends('layouts.app')

@section('content') 
<script>
var base_url = '<?php echo e(url('/')); ?>';
var dispatch_order_id =  '';
var order_status = '';
    $( document ).ready(function() { 
                $('.pop').on('click', function() {
                    // alert($(this).attr('src'));
                    $('.imagepreview').attr('src', $(this).attr('src'));
                    $('#imagemodal').modal('show');   
                });   
                
                
            
        $('#save_order_status_and_price').on('click',function(){
        
    
        let name = $('#receiver_name').val();
        let number = $('#receiver_number').val();
        let address = $('#reciever_address').val();
        let price = $('#price').val();
        
        $.ajax({
          url: base_url + '/client/orders/ManualOrders/dispatch-order-edit/'+dispatch_order_id,
          headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
          type:"POST",
          dataType: 'json',
          data:{
            name:name,
            number:number,
            address:address,
            price:price,
            status:order_status,
          },
          success:function(response){
            //$('#successMsg').show();
            if(response.messege == true)
            {
                $("#dispatch-succes-noti").css("display", "block");
                
            }
            console.log(response);
          },
          error: function(response) {
            // $('#nameErrorMsg').text(response.responseJSON.errors.name);
            // $('#emailErrorMsg').text(response.responseJSON.errors.email);
            // $('#mobileErrorMsg').text(response.responseJSON.errors.mobile);
            // $('#messageErrorMsg').text(response.responseJSON.errors.message);
          },
          });
        }); 
                
    });
    
    
    function change_order_status_and_price(val,status) 
    {  
        alert('working');
        dispatch_order_id = val;
        order_status = status;
        //alert('working');
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: base_url + '/client/orders/ManualOrders/dispatch-order-edit/'+val,
            
            type: 'GET',
            dataType: 'json',
            success: function(e)
            {
                console.log(e.messege.id);
                //alert(e.messege.id);
                $('#receiver_name').val(e.messege.receiver_name);
                // $('#imagemodal').val(e.messege.id);
                $('#receiver_number').val(e.messege.receiver_number);
                $('#reciever_address').val(e.messege.reciever_address);
                $('#Price').val(e.messege.Price);
                
                
            },
            error: function(e) {
                console.log(e.responseText);
            }
        });
    }
    
    
    function get_checked_values()
    {
        var array = [];
        var checkboxes = document.querySelectorAll('input[type=checkbox]:checked')
        for (var i = 0; i < checkboxes.length; i++) 
        {
            array.push(checkboxes[i].value)
        }
        document.getElementById('order_ids').value = array;
        //alert(array);
    }
    
    function generateLink(number) 
    {
        //let number = document.form_main.number.value;
        let message = 'as';
        let url = "https://wa.me/";
        let end_url = `${url}${number}?text=${message}`;
        document.getElementById('end_url').innerText = end_url;
    }
    
</script>

<div class="modal fade" id="imagemodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" data-dismiss="modal">
    <div class="modal-content"  >              
      <div class="modal-body">
         <button type="button" class="close" data-dismiss="modal">Close</button> 
        <img src="" class="imagepreview" style="width: 100%;" >
      </div>  


    </div>
  </div>
</div>


<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLongTitle">Modal title</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <div class="modal-body">
                <div class="col-sm-12">
                    
                    <h4>Reciever Detail <hr></h4> 
                        <div class="alert alert-success" id="dispatch-succes-noti" style="display:none" role="alert">successfully dispatch and update</div>
                        <div class="form-group">
                            <label for="receiver_name">Reciever Name</label>
                            <input type="text" class="form-control" value="" id="receiver_name"  name="receiver_name" placeholder="Reciever Name" required>
                            <small id="receiver_name_error" class="form-text text-danger"></small>
                        </div> 
                        
                        <div class="form-group">
                            <label for="receiver_name">Reciever Number</label>
                            <input type="text" class="form-control" value="" id="receiver_number"  name="receiver_number" placeholder="Reciever Number" required>
                            <small id="receiver_name_error" class="form-text text-danger"></small>
                        </div> 
                        
                        <div class="form-group">
                            <label for="receiver_name">Reciever address</label>
                            <textarea class="form-control" id="reciever_address"   name="reciever_address" placeholder="reciever_address" required></textarea>
                            <small id="reciever_address_error" class="form-text text-danger"></small>
                        </div> 
            
                        <div class="form-group">
                            <label for="Number">price</label>
                            <input type="text" class="form-control" value="" id="price"  name="price" placeholder="Price" required>
                            <small id="price_error" class="form-text text-danger"></small>
                        </div>
            
                        <div class="form-group">
                            <label for="Number">Advance Payment</label>
                            <input type="text" class="form-control" value="" id="advance_payment"  name="advance_payment" placeholder="Advance Payment" required>
                            <small id="advance_payment_error" class="form-text text-danger"></small>
                        </div>
            
                        <div class="form-group">
                            <label for="Number">COD Amount</label>
                            <input type="text" class="form-control" value="" id="cod_amount"  name="cod_amount" placeholder="COD" required>
                            <small id="cod_amount_error" class="form-text text-danger"></small>
                        </div>
                    </div>
        
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="save_order_status_and_price">Save changes</button>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Modal title</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        ...
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>

<nav class="navbar navbar-light bg-light">
  <form class="form-inline" method="post" action="{{ route('ManualOrders.search.order') }}">
      @csrf
    <div class="form-group">
        <input class="form-control mr-sm-2" type="search" name="search_text" placeholder="Search" aria-label="Search">
    </div>
    
    <div class="form-group">
        <select class="form-select" aria-label="Default select example" name="order_status">
          <option selected value ="">Select Order Status</option>
          <option value="">All</option>
          <option value="pending">Pending</option>
          <option value="prepared">Prepared</option>
          <option value="confirmed">Confirmed</option>
          <option value="cancel">complete</option> 
          <option value="dispatched">Dispatched</option> 
          <option value="hold">Hold</option>
          <option value="incomplete">incomplete</option> 
          <option value="cancel">cancel</option> 
          <option value="deleted">delete</option> 
        </select> 
    </div>
    
    <div class="form-group">
        <button class="form-control btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
    </div>
    
    <div class="form-group">
        <button class="form-control btn btn-outline-success my-2 my-sm-0" id="print_mnp_slips" type="button">Print M&P Slips</button>
    </div>
    
    
  </form>
  <!--<button type="button" class="btn btn-primary" data-target="myModalLabel" class="dropdown-item">Dispatched</button> -->
  
  
    <form class="form-inline" method="post" action="{{ route('ManualOrders.order.action') }}">
      @csrf
    <input type="hidden" name="order_ids" id="order_ids">
    <div class="form-group">
        <select class="form-select" aria-label="Default select example" name="order_action" required>
          <option selected >Select Action</option> 
          <option value="pending">Pending</option>
          <option value="prepared">Prepared</option>
          <option value="confirmed">Confirmed</option>
          <option value="cancel">complete</option> 
          <option value="dispatched">Dispatched</option> 
          <option value="hold">Hold</option>
          <option value="incomplete">incomplete</option> 
          <option value="cancel">cancel</option> 
          <option value="print">Print</option>
          <option value="print_mnp_slips">Print M&P Slips</option>
        </select> 
    </div>
    
    <div class="form-group">
        <button class="form-control btn btn-outline-success my-2 my-sm-0" type="submit">Submit</button>
    </div>
   </form>   
</nav>

<div style="overflow-x:auto;"> 
    <table class="table table-bordered" style="min-height: 500px;">
        <thead>
            <tr> 
                <th scope="col"></th>
                <th scope="col">#</th>
                <!--<th scope="col">Edit</th>-->
                <!--<th scope="col">view</th>-->
                <!--<th scope="col">Print</th>-->
                <!--<th scope="col">Prepared</th>-->
                <!--<th scope="col">Confirmed</th>-->
                <!--<th scope="col">Dispatched</th>-->
                <!--<th scope="col">deleted</th>-->
                <th scope="col">Act</th>
                <th scope="col">Ord. ID</th>
                <th scope="col">F. Name</th> 
                <th scope="col">Rec. Number</th>
                <th scope="col">Number</th>
                <th scope="col">Description</th>
                <!--<th scope="col">Ord. Location</th>-->
                <th scope="col">Address</th>
                <th scope="col">Price</th>
                <th scope="col">Img.</th>
                <!--<th scope="col">Total Pieces</th>-->
                <!--<th scope="col">Ord.paid Date</th>-->
                <th scope="col">cr. Date</th>
                <th scope="col">Up. Date</th>
                <th scope="col">status</th>
            </tr>
        </thead>
        <tbody>  
            <?php $count=1;?>
            @foreach($list as $lists)
            
            <tr style="background-color:@if($lists->status == 'deleted')#f99c9c @elseif($lists->status == 'prepaired') #b7b8b9 @elseif($lists->status == 'confirmed') #91c6ff @elseif($lists->status == 'dispatched') #84f39c @elseif($lists->status == 'deleted') #e77272 @else #f9df90 @endif">
                <th ><input type="checkbox" id="order_checkbox" class="order_checkbox_class" name="order_checkbox" onclick="get_checked_values()" value="{{$lists->id}}"></th>
                <th scope="row"><?=$count?></th>            
                <!--<th><a type="button" href="{{route('ManualOrders.edit',$lists->id)}}" class="btn btn-warning">Edit</a></th>             -->
                <!--<th><a type="button" href="{{route('ManualOrders.show',$lists->id)}}" class="btn btn-warning">view</a></th> -->
                <!--<th><a type="button" href="{{route('ManualOrders.print.order.slip',$lists->id)}}" class="btn btn-info">Print Slip</a></th>  -->
                <!--<th><a type="button" href="{{route('ManualOrders.order.status',['prepared',$lists->id])}}" class="btn btn-secondary">Prepare</a></th> -->
                <!--<th><a type="button" href="{{route('ManualOrders.order.status',['confirmed',$lists->id])}}" class="btn btn-primary">Confirmed</a></th> -->
                <!--<th><a type="button" href="{{route('ManualOrders.order.status',['dispatched',$lists->id])}}" class="btn btn-success">Dispatch</a></th> -->
                
                <!--<th>-->
                <!--    <div class="btn-group mr-2" role="group" aria-label="First group">            -->
                <!--        <a type="button" href="{{route('ManualOrders.edit',$lists->id)}}" class="btn btn-warning">Edit</a>             -->
                <!--        <a type="button" href="{{route('ManualOrders.show',$lists->id)}}" class="btn btn-dark">view</a>-->
                <!--        <a type="button" href="{{route('ManualOrders.print.order.slip',$lists->id)}}" class="btn btn-info">Print Slip</a>-->
                <!--        <a type="button" href="{{route('ManualOrders.order.status',['prepared',$lists->id])}}" class="btn btn-secondary">Prepare</a> -->
                <!--        <a type="button" href="{{route('ManualOrders.order.status',['confirmed',$lists->id])}}" class="btn btn-primary">Confirmed</a> -->
                <!--        <a type="button" href="{{route('ManualOrders.order.status',['dispatched',$lists->id])}}" class="btn btn-success">Dispatch</a>-->
                <!--    </div>    -->
                <!--</th>-->
                
                <th>
                    <div class="btn-group" role="group">
                        <button id="btnGroupDrop1" type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                          Actions
                        </button>
                        <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">            
                            <a type="button" target="_blank" href="{{route('ManualOrders.edit',$lists->id)}}" class="dropdown-item">Edit</a>             
                            <a type="button" target="_blank" href="{{route('ManualOrders.show',$lists->id)}}" class="dropdown-item">view</a>
                            <a type="button" href="{{route('ManualOrders.print.order.slip',$lists->id)}}" class="dropdown-item">Print Slip</a>
                            <a type="button" href="{{route('ManualOrders.order.status',['prepared',$lists->id])}}" class="dropdown-item">Prepare</a>   
                            <a type="button" href="{{route('ManualOrders.order.status',['complete',$lists->id])}}" class="dropdown-item">Complete</a> 
                            <button type="button" id="dispatch-btn" onclick="change_order_status_and_price({{$lists->id}},'confirmed')" class="dropdown-item" data-toggle="modal" data-target="#exampleModalCenter">Confirmed</button>
                            <button type="button" id="dispatch-btn" onclick="change_order_status_and_price({{$lists->id}},'dispatched')" class="dropdown-item" data-toggle="modal" data-target="#exampleModalCenter">Dispatch</button>
                            <a type="button" href="{{route('ManualOrders.order.status',['hold',$lists->id])}}" class="dropdown-item">Hold</a> 
                            <a type="button" href="{{route('ManualOrders.order.status',['incomplete',$lists->id])}}" class="dropdown-item">Incomplete</a>
                            <a type="button" href="{{route('ManualOrders.order.status',['cancel',$lists->id])}}" class="dropdown-item">Cancel</a>
                        </div>
                     </div>
                </th>
                <!--<th><form method="post" class="delete_form" action="{{route('ManualOrders.destroy',$lists->id)}}">-->
                <!--    @method('DELETE')-->
                <!--    @csrf-->
                <!--    <button type="submit" class="btn btn-danger">Delete</button>-->
                <!--</form></th>-->
                <th>{{$lists->id}}</th>
                <th>{{$lists->first_name}}</th>  
                <!--<th>{{$lists->number}}</th>-->
                <?php 
                $number = substr($lists->number, 1);
                $number = '+92'.$number;
                
                $reciever_number = substr($lists->receiver_number, 1);
                $reciever_number = '+92'.$reciever_number
                ?>
                <th><a target="_blank" href="https://api.whatsapp.com/send?phone=<?=$reciever_number?>&text=Hi {{$lists->first_name}}, I am from Brandhub, i just want you to confirm your order, please click on the link to and check your articles and press confirmed button {{route('ManualOrders.confirm.order.by.customer.show',$lists->id)}}"><?=$reciever_number?></a></th> 
                <th><a target="_blank" href="https://api.whatsapp.com/send?phone=<?=$number?>&text=Hi, {{$lists->first_name}}, I am from Brandhub, i just want you to confirm your order, please click on the link to and check your articles and press confirmed button {{route('ManualOrders.confirm.order.by.customer.show',$lists->id)}}"><?=$number?></a></th> 
                <th>{{$lists->description}}</th>
                <!--<th>{{$lists->order_delivery_location}}</th>-->
                <th>{{$lists->reciever_address}}</th>
                <th>{{$lists->price}}</th>
                <th >
                    @if(!empty($lists->images))
                        @foreach(explode('|', $lists->images) as $image)   
                        <img class="pop rounded " style="margin-right: 5px;" src="{{asset($image)}}" alt="Card image cap" width="25" height="25">
                        @endforeach
                    @endif
                </th>
                <!--<th>{{$lists->total_pieces}}</th>-->
                <!--<th>{{$lists->date_order_paid}}</th>-->
                <th>{{$lists->created_at}}</th>
                <th>{{$lists->updated_at}}</th>
                <th>{{$lists->status}}</th>
            </tr>
            <?php $count++;?>
            @endforeach
        </tbody>
        
    </table>
</div>
{{ $list->links() }}

<script>

$( document ).ready(function() {
            
            $('#print_mnp_slips').on('click',function(e)
            {  
                var base_url = '<?php echo e(url('/')); ?>';
        
                 var url = "http://mnpcourier.com/mycodapi/api/Booking/InsertBookingData";
        
                var xhr = new XMLHttpRequest();
                xhr.open("POST", url);
                
                xhr.setRequestHeader("Content-Type", "application/json");
                
                xhr.onreadystatechange = function () {
                   if (xhr.readyState === 4) {
                      console.log(xhr.status);
                      console.log(xhr.responseText);
                   }};
                
                var data = '{"username": "mansoor_4b459","password": "Mansoor1@3","consigneeName": "test","consigneeAddress": "test123","consigneeMobNo": "03330139993","consigneeEmail": "string","destinationCityName": "karachi","pieces": "0","weight": "0","codAmount": 0,"custRefNo": "12345689","productDetails": "string","fragile": "string","service": "overnight","remarks": "string","insuranceValue": "string","locationID": "string","AccountNo": "string","InsertType": "0"}';
                
                xhr.send(data);
         
            });
        });
         

        // $( document ).ready(function() {
            
        //     $('#print_mnp_slips').on('click',function(e)
        //     {  
        //         alert('working');
        //         $.ajax({
        //             headers: {
                        
                        
        //                 "Content-Type": "application/json"
        //             },
        //             //url: base_url + '/client/orders/ManualOrders/delete-image',
        //             url: 'http://mnpcourier.com/mycodapi/api/Booking/InsertBookingData',
        //             data: {
        //                     "username": "mansoor_4b459",
        //                     "password": "Mansoor1@3",
        //                     "consigneeName": "test",
        //                     "consigneeAddress": "test123",
        //                     "consigneeMobNo": "03330139993",
        //                     "consigneeEmail": "string",
        //                     "destinationCityName": "karachi",
        //                     "pieces": 0,
        //                     "weight": 0,
        //                     "codAmount": 0,
        //                     "custRefNo": "12345689",
        //                     "productDetails": "string",
        //                     "fragile": "string",
        //                     "service": "overnight",
        //                     "remarks": "string",
        //                     "insuranceValue": "string",
        //                     "locationID": "string",
        //                     "AccountNo": "string",
        //                     "InsertType": 0
        //             },
        //             type: 'POST',
        //             dataType: 'json',
        //             success: function(e)
        //             {
        //                 console.log(e.messege);   
                        
        //             },
        //             error: function(e) {
        //                 console.log(e.responseText);
        //             }
        //         });
        //     });
        // });
</script>
@endsection