
@extends('layouts.'.Auth::getDefaultDriver())



@section('content') 
<style>
.td-div {
    float: left;
    width: 100%;
    position: relative;
    background: white;
    margin-top: 7px;
    border-radius: 10px;
    text-align: center;
    border-bottom: 1px solid #ccc7c7;
    padding: 5px 0;
}
.tr-div{
    float: left;
    border: 1px solid black;
    border-radius: 15px;
    box-shadow: 1px 1px 10px 1px;
    padding: 13px;
    
} 
.img-scroll-box {
    height: auto;
    float: left;
    overflow: auto;
    white-space: nowrap;
}
.parcel-img {
    border: 8px solid red;
    width: 80%;
}   white-space: nowrap;
}
</style>
<script  type="application/javascript">
var base_url = '<?php echo e(url('/')); ?>';
var dispatch_order_id =  '';
var order_status = '';
var order_link='';
         
    
        
    function mobile_view()
    {
        var table = document.getElementsByTagName('table');
        var td = document.getElementsByTagName('td');
        var thead = document.getElementsByTagName('thead');
        var tbody = document.getElementsByTagName('tbody');
        var tr = document.getElementsByTagName('tr');
        var img = document.getElementsByTagName('img');
        var td_data = "";
        var tr_data = "";
        var table_data = ""; 
        
        for(var i=0; i<img.length;i++)
        {
            img[i].classList.add("parcel-img");
            // img[i].classList.add("row");
        }
        
        for(var i=0; i<td.length;i++)
        {
            if(td[i].querySelector("img") != null)
            {
                td[i].innerHTML = "<div class='img-scroll-box td-div'>"+td[i].innerHTML+"</div>";
            }
            else
            {
                if(td[i].innerHTML == "" || td[i].innerHTML == " ")
                {
                    
                }
                else
                {
                    td[i].innerHTML = "<div class='td-div'>"+td[i].innerHTML+"</div>";
                }
            }
            // td[i].classList.add("td");
        }
        
        for(var i=0; i<thead.length;i++)
        {
            thead[i].remove();
        }
        
        for(var i=0; i<tr.length;i++)
        { 
            tr_data  += "<div class='col-sm-12 tr-div'>"+tr[i].innerHTML+"</div>"; 
        }
        
        tbody_data = "<div class='col-sm-12 tbody-div '>"+tr_data+"</div>"
        table_data =  "<div class='table-div'>"+tbody_data+"</div>"; 
        document.getElementsByClassName('table-container')[0].innerHTML = table_data;
        
        // for(var i=0; i<tr.length;i++)
        // {
        //     tr[i].remove();
        // }
        
        // for(var i=0; i<table.length;i++)
        // {
            
        //     document.getElementsByClassName('table-container')[0].innerHTML =  "<div class='col-sm-12 row table-div'>"+table[i].innerHTML+"</div>";
        //     // table[i].classList.add("col-sm-12");
        //     // table[i].classList.add("row");
        // }
         
        
        // for(var i=0; i<tbody.length;i++)
        // {
            
        //     document.getElementsByClassName('table-div')[0].innerHTML = "<div class='col-sm-12 tbody-div justify-content-around'>"+tbody[i].innerHTML+"</div>";
        //     tbody[i].remove();
        //     // tbody[i].classList.add("col-sm-12");
        //     // tbody[i].classList.add("justify-content-around");
        // }
        
        // f
        
        // 
        
        // for(var i=0; i<td.length;i++)
        // {
        //     if(td[i].querySelector("img") != null)
        //     {
        //         td[i].innerHTML = "<div class='img-scroll-box tr-div'>"+td[i].innerHTML+"</div>";
        //     }
        //     td[i].classList.add("td");
        // }
        //td.classList.add("td");
    }
    
    function checkAll(bx) {
        // alert('work');
        //document.getElementByClassName("order_checkbox_class").checked = true;
        var cbs = document.getElementsByTagName('input');
            for(var i=0; i < cbs.length; i++) {
            if(cbs[i].type == 'checkbox') {
               cbs[i].checked = bx.checked;
            //   cbs[i].checked = true;
            }
            get_checked_values();
        }
    }
        
    $( document ).ready(function() { 
        $('.pop').on('click', function() {
            // alert($(this).attr('src'));
            $('.imagepreview').attr('src', $(this).attr('src'));
            $('#imagemodal').modal('show');   
        });   
                 
                 
        $('#add_new_inventory').on('click',function(){ 
            $('#createinventorymodal').modal('hide');
            var sku = $('#sku').val();
            var units = $('#units').val();
            var unit_type = $('#unit_type').val();
            var sale = $('#sale').val();
            var cost = $('#cost').val(); 
            var name = $('#name').val();
            
            console.log(sku+'\n'+units+'\n'+unit_type+'\n'+sale+'\n'+cost+'\n');
            if(sku !='' && units !='' && unit_type !='' && sale > 0 && cost > 0)
            {
                $.ajax({
                    url: base_url + '/admin/inventory', 
                  headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                  type:"POST",
                  dataType: 'json',
                  data:{
                    sku:sku,
                    units:units,
                    unit_type:unit_type,
                    sale:sale,
                    cost:cost, 
                    name:name, 
                  },
                  success:function(response){
                    //$('#successMsg').show();
                        if(response.messege)
                        {
                           alert(response.messege);
                            
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
            }
            else
            {
                alert('please fill all the fields');
            }
    
        }); 
                
    });
    
    
    
    function change_order_status_and_price(val,status) 
    {   
        //alert('working');
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
                //console.log(e.messege.id);
                //alert(e.messege.id);
                $('#receiver_name').val(e.messege.receiver_name);
                // $('#imagemodal').val(e.messege.id);
                $('#receiver_number').val(e.messege.receiver_number);
                $('#reciever_address').val(e.messege.reciever_address);
                $('#Price').val(e.messege.Price);
                
                var images ='';
                var str_array = e.messege.images.split('|'); 
                for(var i = 0; i < str_array.length; i++) {
                   // Trim the excess whitespace.
                //   str_array[i] = str_array[i].replace(/^\s*/, "").replace(/\s*$/, "");
                     images = images+'<img class="pop rounded" style="margin-right: 5px;" src="{{asset("/")}}'+str_array[i]+'" alt="Card image cap" width="100">';
                   // Add additional code here, such as:
                   //alert(str_array[i]);
                }
                $('#images_pop').html(images);
                
                //alert(images);
                
                
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
         <button type="button" id="closeimagemodal" class="close" data-dismiss="imagemodal">Close</button> 
        <img src="" class="imagepreview" style="width: 100%;" >
      </div>  


    </div>
  </div>
</div>
 


<div class="modal fade" id="createinventorymodal" tabindex="-1" role="dialog"  aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
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
                    
                    <h4>Add New Inventory <hr></h4> 
                        <div class="alert alert-success" id="dispatch-succes-noti" style="display:none" role="alert">successfully dispatch and update</div>
                     
                        
                        <div class="form-group">
                            <label for="SKU">SKU</label>
                            <input type="text" class="form-control" value="" id="sku"  name="sku" placeholder="SKU" required>
                            <small id="sku_error" class="form-text text-danger"></small>
                        </div> 
                     
                        
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" value="" id="name"  name="name" placeholder="Product Name" required>
                            <small id="name_error" class="form-text text-danger"></small>
                        </div>
                        
                        <div class="form-group">
                            <label for="units">Units</label>
                            <input type="text" class="form-control" id="units"  name="units" placeholder="Reciever Number" required>
                            <small id="units_error" class="form-text text-danger"></small>
                        </div> 
                        
                        <div class="form-group">
                            <label for="Type">Type</label>
                            <select class="form-select" aria-label="Default select example" id ="unit_type" name="unit_type">
                              <option selected value ="">Select Type</option> 
                              <option value="ml">ml</option>
                              <option value="mg">mg</option>
                              <option value="pc">pc</option>  
                            </select> 
                            <small id="unit_type_error" class="form-text text-danger"></small>
                        </div> 
            
                        <div class="form-group">
                            <label for="cost">cost</label>
                            <input type="text" class="form-control" value="0" id="cost"  name="cost" placeholder="cost" required>
                            <small id="cost_error" class="form-text text-danger"></small>
                        </div> 
            
                        <div class="form-group">
                            <label for="sale">sale</label>
                            <input type="text" class="form-control" value="0" id="sale"  name="sale" placeholder="sale" required>
                            <small id="sale_error" class="form-text text-danger"></small>
                        </div>   
                    </div>
        
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="add_new_inventory">Save changes</button>
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
  <form class="form-inline" method="post" action="{{ route('ManualOrders.index') }}">
      @csrf
    <div class="form-group">
        <input class="form-control mr-sm-2" type="search" name="search_order_id" placeholder="Search by Order id #" aria-label="Search">
    </div>
    
    <div class="form-group">
        <input class="form-control mr-sm-2" type="search" name="search_text" placeholder="Name OR Number" aria-label="Search">
    </div>
    
    <div class="form-group">
        <select class="form-select" aria-label="Default select example" name="order_status">
          <option selected value ="">Select Order Status</option>
          <option value="all">All</option>
          <option value="pending">Pending</option>
          <option value="duplicate">Dulicate</option>
          <option value="prepared">Prepared</option>
          <option value="confirmed">Confirmed</option>
          <option value="cancel">complete</option> 
          <option value="dispatched">Dispatched</option> 
          <option value="hold">Hold</option>
          <option value="incomplete">incomplete</option> 
          <option value="cancel">cancel</option> 
          <option value="return">return</option> 
          <option value="deleted">delete</option> 
          <option value="not responding"></option>  
        </select> 
    </div>
    
    <div class="form-group">
        <button class="form-control btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
    </div>
    
    <div class="form-group">
        <input onclick="mobile_view()" type="checkbox">Mobile View
    </div>
    
    <div class="form-group">
        <button class="form-control btn btn-outline-success my-2 my-sm-0" data-toggle="modal" data-target="#createinventorymodal" type="button">Add New inventory</button>
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
          <option value="print">Print </option>
          <option value="duplicate_orders">Duplicate Orders</option>
          <option value="print_mnp_slips">Print M&P Slips</option>
          <option value="print_trax_slips">Print Trax Slips</option>
          <option value="print_pos_slips">Print Pos Slips</option>
        </select> 
    </div>
    
    <div class="form-group">
        <button class="form-control btn btn-outline-success my-2 my-sm-0" type="submit">Submit</button>
    </div>
   </form>   
</nav>

<div class="table-container" style="overflow-x:auto;"> 
    <table class="table table-bordered" style="min-height: 500px;">
        <thead>
            <tr> 
                <th scope="col" class="delete_btn_class"><input type="checkbox" onclick="checkAll(this)" ></th>
                <th scope="col">#</th> 
                <th scope="col">Act</th>
                <th scope="col">Img.</th>
                <th scope="col">Consignment.Id</th>
                <th scope="col">Ord.ID</th>
                <th scope="col">F.Name</th> 
                <th scope="col">Rec.Number</th>
                <th scope="col">Number</th> 
                <th scope="col">Description</th>
                <th scope="col">Address</th>
                <th scope="col">Price</th>
                <th scope="col">OD Y/N</th>
                <th scope="col">cr.Date</th>
                <th scope="col">Up.Date</th>
                <th scope="col">Status</th>
                <th scope="col">Status Reason</th>
            </tr>
        </thead>
        <tbody>  
            <?php $count=1;?>
            @foreach($list as $lists)
            
            <tr style="background-color:@if($lists->status == 'deleted')#f99c9c @elseif($lists->status == 'prepaired') #b7b8b9 @elseif($lists->status == 'confirmed') #91c6ff @elseif($lists->status == 'dispatched') #84f39c @elseif($lists->status == 'deleted') #e77272 @else #f9df90 @endif">
                <td ><input type="checkbox" id="order_checkbox" class="order_checkbox_class" name="order_checkbox" onclick="get_checked_values()" value="{{$lists->id}}"></td>
                
                <td scope="row"><?=$count?></td> 
                
                <td>
                    <div class="btn-group" role="group">
                        <button id="btnGroupDrop1" type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                          Actions
                        </button>
                        <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">            
                            <a type="button" target="_blank" href="{{route('ManualOrders.edit',$lists->id)}}" class="dropdown-item">Edit</a>   
                            <button type="button" id="dispatch-btn" onclick="change_order_status_and_price({{$lists->id}},'pending')" class="dropdown-item" data-toggle="modal" data-target="#exampleModalCenter">Quick Edit</button>          
                            <a type="button" target="_blank" href="{{route('ManualOrders.show',$lists->id)}}" class="dropdown-item">view</a>
                            <a type="button" href="{{route('ManualOrders.print.order.slip',$lists->id)}}" class="dropdown-item">Print Slip</a>
                            <!--<a type="button" href="{{route('ManualOrders.order.status',['prepared',$lists->id])}}" class="dropdown-item">prepared</a>   -->
                            <button type="button" id="dispatch-btn" onclick="change_order_status_and_price({{$lists->id}},'prepared');order_link ='{{route('ManualOrders.confirm.order.by.customer.show',$lists->id)}}';" class="dropdown-item" data-toggle="modal" data-target="#exampleModalCenter">Prepared</button>
                            <a type="button" href="{{route('ManualOrders.order.status',['complete',$lists->id])}}" class="dropdown-item">Complete</a> 
                            <button type="button" id="dispatch-btn" onclick="change_order_status_and_price({{$lists->id}},'confirmed')" class="dropdown-item" data-toggle="modal" data-target="#exampleModalCenter">Confirmed</button>
                            <button type="button" id="dispatch-btn" onclick="change_order_status_and_price({{$lists->id}},'dispatched')" class="dropdown-item" data-toggle="modal" data-target="#exampleModalCenter">Dispatch</button>
                            <button type="button" id="dispatch-btn" onclick="change_order_status_and_price({{$lists->id}},'hold')" class="dropdown-item" data-toggle="modal" data-target="#exampleModalCenter">Hold</button>
                            <button type="button" id="dispatch-btn" onclick="change_order_status_and_price({{$lists->id}},'incomplete')" class="dropdown-item" data-toggle="modal" data-target="#exampleModalCenter">Incomplete</button>
                            <button type="button" id="dispatch-btn" onclick="change_order_status_and_price({{$lists->id}},'not responding')" class="dropdown-item" data-toggle="modal" data-target="#exampleModalCenter">not responding</button>
                            <button type="button" id="dispatch-btn" onclick="change_order_status_and_price({{$lists->id}},'cancel')" class="dropdown-item" data-toggle="modal" data-target="#exampleModalCenter">Cancel</button>
                            <button type="button" id="dispatch-btn" onclick="change_order_status_and_price({{$lists->id}},'return')" class="dropdown-item" data-toggle="modal" data-target="#exampleModalCenter">Return</button>
                            <!--<a type="button" href="{{route('ManualOrders.order.status',['hold',$lists->id])}}" class="dropdown-item">Hold</a> -->
                            <!--<a type="button" href="{{route('ManualOrders.order.status',['incomplete',$lists->id])}}" class="dropdown-item">Incomplete</a>-->
                            <!--<a type="button" href="{{route('ManualOrders.order.status',['cancel',$lists->id])}}" class="dropdown-item">Cancel</a>-->
                        </div>
                     </div>
                </td>
                <td >
                    @if(!empty($lists->images))
                        @foreach(explode('|', $lists->images) as $image)   
                        <img class="pop rounded " style="margin-right: 5px;" src="{{asset($image)}}" alt="Card image cap" width="25" >
                        @endforeach
                    @endif
                </td>
                <td>{{$lists->consignment_id}}</td> 
                <td>{{$lists->id}}</td>
                <td>{{$lists->first_name}}</td>   
                <?php 
                $number = $lists->number;
                $reciever_number = $lists->receiver_number;
                
                $get_char_num = substr($lists->number,0,1);
                if($get_char_num == 0)
                {
                    $number = substr($lists->number, 1);
                    $number = '+92'.$number;
                    
                }
                
                $get_char_rec_num = substr($lists->receiver_number,0,1);
                if($get_char_rec_num == 0)
                {
                    $reciever_number = substr($lists->receiver_number, 1);
                    $reciever_number = '+92'.$reciever_number;
                    
                }
                
                
                ?>
                <td><a target="_blank" href="https://api.whatsapp.com/send?phone=<?=$reciever_number?>&text=Assalamualikum {{$lists->first_name}}, I am from Brandhub, Please confirm your order, click on the link to and check your articles and press confirmed button {{route('ManualOrders.confirm.order.by.customer.show',$lists->id)}}"><?=$reciever_number?></a></td> 
                <td><a target="_blank" href="https://api.whatsapp.com/send?phone=<?=$number?>&text=Assalamualaikum, {{$lists->first_name}}, I am from Brandhub, Please confirm your order, click on the link to and check your articles and press confirmed button {{route('ManualOrders.confirm.order.by.customer.show',$lists->id)}}"><?=$number?></a></td> 
                <td>{{$lists->description}}</td> 
                <td>{{$lists->reciever_address}}</td>
                <td>{{$lists->price}}</td>  
                <td><a target="_blank" href="https://api.whatsapp.com/send?phone=<?=$number?>&text=Assalamualaikum, {{$lists->first_name}}, Mam did you recieve your order, please click on link to Track your Order {{route('ManualOrders.confirm.order.by.customer.show',$lists->id)}}">Get Status</a></td> 
                <td>{{date('d-M-y', strtotime($lists->created_at))}} <br> {{date('G:i a', strtotime($lists->created_at))}}</td>
                <td>{{date('d-M-y', strtotime($lists->updated_at))}} <br> {{date('G:i a', strtotime($lists->updated_at))}}</td> 
                <td>{{$lists->status}}</td>
                <td>{{$lists->status_reason}}</td>
            </tr>
            <?php $count++;?>
            @endforeach
        </tbody>
        
    </table>
</div>
{{ $list->links() }}

<script type="application/javascript">

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
        
        $('#closeimagemodal').click(function() {
            $('#imagemodal').modal('hide');
        });
        
    });
         
</script>
@endsection