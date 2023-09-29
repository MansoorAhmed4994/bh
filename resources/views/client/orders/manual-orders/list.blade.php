
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
       white-space: nowrap;
    }
</style>
<script  type="application/javascript">
var base_url = '<?php echo e(url('/')); ?>';
var dispatch_order_id =  '';
var order_status = '';
var order_link='';
var container = "";
      
      
    function demand_view()
    {
        if (document.getElementById('demand_checkbox').checked) 
        {
            
            var tr_data="<div class='col-sm-12 tr-div'>";
            var linkArray = $(".imgaes-demand").map(function() {
                return $(this).attr('src');
            }).get();
    
            for(var i=0; i< linkArray.length;i++)
            { 
                tr_data  += "<img src='"+linkArray[i]+"' style='width:400px; height:600px;margin: 2px; border: 2px solid #777272; border-style: dashed;'>"; 
                // img[i].classList.add("row");
            }
            tr_data += "</div>";
            
            tbody_data = "<div class='col-sm-12 tbody-div '>"+tr_data+"</div>";
            table_data =  "<div class='table-div'>"+tbody_data+"</div>"; 
            document.getElementsByClassName('table-container')[0].innerHTML = table_data;
            // console.log(linkArray[0]);
                
        } 
        else {
            // alert(container);
            document.getElementsByClassName('table-container')[0].innerHTML = container;
        }
        
    }   
    
        
    function mobile_view(mobile_checkbox)
    {
        if (document.getElementById('mobile_checkbox').checked) 
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
        } 
        else {
            // alert(container);
            document.getElementsByClassName('table-container')[0].innerHTML = container;
        }
        
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
    
    function change_price_popup_status()
    {
        let price = $('#price').val();
        let advance_payment = $('#advance_payment').val();
        let cod_amount = $('#cod_amount').val(price-advance_payment);
        
    }
        
    $( document ).ready(function() { 
        // $('.pop').on('click', function() {
        //     // alert($(this).attr('src'));
        //     $('.imagepreview').attr('src', $(this).attr('src'));
        //     $('#imagemodal').modal('show');   
        // });   
        
        $('#order_status_edit_close').on('click',function(){
            $('#order_status_edit').modal('hide');
        });
        
        $('#pos_slip_duplication_modal_close').on('click',function(){
            $('#pos_slip_duplication_modal').modal('hide');
        });
                 
        
        
        $('#save_order_status_and_price').on('click',function(){
            let receiver_name = $('#receiver_name').val();
            let receiver_number = $('#receiver_number').val();
            let reciever_address = $('#reciever_address').val();
            let price = $('#price').val();
            let advance_payment = $('#advance_payment').val();
            let cod_amount = $('#cod_amount').val();
            let status_reason = $('#status_reason').val();
            
            
            if(order_status == 'cancel' || order_status == 'hold' || order_status == 'incomplete')
            {
                if(status_reason == '')
                {
                    
                    alert("please give Reason to "+order_status);
                    // $("body").removeClass("loading");
                    return;
                }
                
            }
            $("body").addClass("loading");
        //console.log(status_reason);
            $.ajax({
              url: base_url + '/client/orders/ManualOrders/dispatch-order-edit/'+dispatch_order_id,
              headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
              type:"POST",
              dataType: 'json',
              data:{
                receiver_name:receiver_name,
                receiver_number:receiver_number,
                reciever_address:reciever_address,
                price:price,
                advance_payment:advance_payment,
                cod_amount:cod_amount,
                status:order_status,
                status_reason:status_reason,
              },
              success:function(response){
                //$('#successMsg').show();
                if(response.messege == true)
                {
                    if(order_status == "cancel")
                    {
                        
                        var get_char_rec_num = receiver_number.substring(0,1);
                        if(get_char_rec_num == 0)
                        {
                            receiver_number = "+92"+receiver_number;
                        }
                        var link = "https://api.whatsapp.com/send?phone="+receiver_number+"&text=Assalamualaikum, "+receiver_name+", I am from Brandhub, i just want to inform you that your parcel has been cancelled due to unavailable of iterm, please contact here for more details 03362240865";
                        $("#dispatch-succes-noti").css("display", "block");
                        window.open(link, '_blank');
                    }
                    else if(order_status == "prepared")
                    { 
                        var get_char_rec_num = receiver_number.substring(0,1);
                        if(get_char_rec_num == 0)
                        {
                            receiver_number = "+92"+receiver_number;
                        }
                        var link = "https://api.whatsapp.com/send?phone="+receiver_number+"&text=Assalamualaikum "+receiver_name+", your order has been Prepared, Our representative will contact you ASAP, Kindly active on your Phone, You can also confirm your order by  clicking on the given link, and press confirmed button "+order_link+" Thank You";
                        $("#dispatch-succes-noti").css("display", "block");
                        window.open(link, '_blank');
                         
                    } 
                    $("#dispatch-succes-noti").css("display", "block");
                    
                }
                $("body").removeClass("loading");
                //console.log(response);
              },
              error: function(response) {
                    alert(response); 
                    $("body").removeClass("loading");
                // $('#nameErrorMsg').text(response.responseJSON.errors.name);
                // $('#emailErrorMsg').text(response.responseJSON.errors.email);
                // $('#mobileErrorMsg').text(response.responseJSON.errors.mobile);
                // $('#messageErrorMsg').text(response.responseJSON.errors.message);
              },
          });
        
    
        }); 
                
    });
    
    
    function print_pos_slip(route,className) 
    {  
        
        const elements = document.getElementsByClassName(className);
            while(elements.length > 0){
                elements[0].parentNode.removeChild(elements[0]);
            }
            window.open(route, '_blank');
    }
    
    function check_pos_slip_duplication(route,id,className)
    {
        // $("body").addClass("loading");
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: base_url + '/client/orders/ManualOrders/chec-pos-slip-duplication/'+id,
            
            type: 'GET',
            dataType: 'json',
            success: function(e)
            {
                if(e.activitylog != null || e.manualorders != null)
                {
                    var data
                    data +='<div class="row col-sm-12">';
                    
                    
                    data += '<div class="col-sm-2">';
                    if(e.activitylog.length > 0)
                    {
                        for(var i=0; i<e.activitylog.length; i++)
                        {
                            data += '<h5>Printed Date: '+e.activitylog['created_at']+'</h5><br>';
                        } 
                    }
                    else
                    {
                        data += '<h5>No Printed Record Found</h5><br>';
                    }
                    data += '</div>';
                    
                    for(var i=0; i<e.manualorders.length; i++)
                    {
                        data += '<div class="col-sm-2"><h4>Order #'+(i+1)+'<hr></h4><h5>order id:'+e.manualorders[0]['id']+'</h5><br>';
                        data += '<h5>Name: '+e.manualorders[0]['receiver_name']+'</h5><br>';
                        data += '<h5>Number: '+e.manualorders[0]['receiver_number']+'</h5><br>';
                        data += '<h5>Address: '+e.manualorders[0]['reciever_address']+'</h5><br>';
                        data += '<h5>Status: '+e.manualorders[0]['status']+'</h5><br></div>';
                    }
                    data +='</div>';
                    $('#PSDM').html(data);
                    $('#PSDM_yes_btn').attr('onclick',"print_pos_slip('"+route+"','"+className+"')");
                    // $("body").removeClass("loading");
                    $('#pos_slip_duplication_modal').modal('show');
                    // console.log(e.activitylog);
                    // print_pos_slip(className);
                } 
                
                
                 
                
                //alert(images);
                
                
            },
            error: function(e) {
                console.log(e.responseText);
            }
        });
        
    }
    
    
    function change_order_status_and_price(val,status) 
    {   
        $("body").addClass("loading");
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
                var order_date =e.messege.created_at;
                var dateParts = order_date.split("-");
                var final_date = new Date(dateParts[0], dateParts[1] - 1, dateParts[2].substr(0,2));
                console.log(final_date); 
                var current_date = new Date();
                
                const diffTime = Math.abs(current_date - final_date);
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));  
                console.log(diffDays + " days");
                
                
              
                if(e.messege.status == 'dispatched')
                {
                    if(diffDays >= 3)
                    {
                        alert('you cant edit this order cause it is already dispatched');
                        $("body").removeClass("loading");
                        return;
                    } 

                }
                
                $('#receiver_name').val(e.messege.receiver_name);
                // $('#imagemodal').val(e.messege.id);
                $('#receiver_number').val(e.messege.receiver_number);
                $('#reciever_address').val(e.messege.reciever_address);
                $('#price').val(e.messege.price);
                $('#cod_amount').val(e.messege.cod_amount);
                $('#advance_payment').val(e.messege.advance_payment);
                
                var images ='';
                var str_array = e.messege.images.split('|'); 
                for(var i = 0; i < str_array.length; i++) 
                {
                     images = images+'<img class="pop rounded" style="margin-right: 5px;" src="{{asset("/")}}'+str_array[i]+'" alt="Card image cap" width="100">';
                }
                
                $('#images_pop').html(images);
                $('#order_status_edit').modal('show');
                $("body").removeClass("loading");
                 
                
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



<div class="modal fade" id="pos_slip_duplication_modal" tabindex="-1" role="dialog" aria-labelledby="Pos Slip Duplication" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
      <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLongTitle">Are you Sure you want to print? (Please check details below)</h5>
            </button>
            </div>
            <div class="modal-body" id="PSDM">
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="pos_slip_duplication_modal_close">Close</button>
                <button type="button" class="btn btn-primary" id="PSDM_yes_btn">Save changes</button>
            </div>
        </div>
    </div>
</div>



<div class="modal fade" id="order_status_edit" tabindex="-1" role="dialog" aria-labelledby="order_status_edit" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
      <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLongTitle">Edit Status</h5> 
            </div>
            <div class="modal-body">
                <div class="row col-sm-12"> 
                
                    <div class="col-sm-4">
                        <h4>Reciever Detail <hr></h4> 
                        <div class="alert alert-success" id="dispatch-succes-noti" style="display:none" role="alert">successfully dispatch and update</div>
                        <div class="form-group col-sm">
                            <div class="card" id="images_pop" style="max-width: 200px;"> 
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-sm-4">
                            
                        <div class="form-group">
                            <label for="receiver_name">Reciever Name</label>
                            <input type="text" class="form-control" value="" id="receiver_name"  name="receiver_name" placeholder="Reciever Name" required>
                            <small id="receiver_name_error" class="form-text text-danger"></small>
                        </div> 
                        
                        <div class="form-group">
                            <label for="receiver_name">Reciever Number</label>
                            <input type="text" class="form-control" id="receiver_number"  name="receiver_number" placeholder="Reciever Number" required>
                            <small id="receiver_name_error" class="form-text text-danger"></small>
                        </div> 
                        
                        <div class="form-group">
                            <label for="receiver_name">Reciever address</label>
                            <textarea class="form-control" id="reciever_address"   name="reciever_address" placeholder="reciever_address" required></textarea>
                            <small id="reciever_address_error" class="form-text text-danger"></small>
                        </div> 
                        
                    </div>
                    
                    <div class="col-sm-4">
            
                        <div class="form-group">
                            <label for="Number">price</label>
                            <input type="text" class="form-control" onkeyup="change_price_popup_status()" value="0" id="price"  name="price" placeholder="Price" required>
                            <small id="price_error" class="form-text text-danger"></small>
                        </div>
            
                        <div class="form-group">
                            <label for="Number">Advance Payment</label>
                            <input type="text" class="form-control" onkeyup="change_price_popup_status()" id="advance_payment"  name="advance_payment" placeholder="Advance Payment" readonly>
                            <small id="advance_payment_error" class="form-text text-danger"></small>
                        </div>
            
                        <div class="form-group">
                            <label for="Number">COD Amount</label>
                            <input type="text" class="form-control" value="" id="cod_amount"  name="cod_amount" placeholder="COD" readonly>
                            <small id="cod_amount_error" class="form-text text-danger"></small>
                        </div>
            
                        <div class="form-group">
                            <label for="Number">Reason</label>
                            <textarea  class="form-control" value="" id="status_reason"  name="status_reason" placeholder="Reason for status" required></textarea>
                            <small id="status_reason_error" class="form-text text-danger"></small>
                        </div>
                    </div>
                        
                    </div>
        
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="order_status_edit_close">Close</button>
                <button type="button" class="btn btn-primary" id="save_order_status_and_price">Save changes</button>
            </div>
        </div>
    </div>
</div>




<nav class="navbar navbar-light bg-light"> 
    <div class="col-sm-12">
        <form class="form-inline" method="post" action="{{ route('ManualOrders.index') }}">
        @csrf
     
            <div class="form-group">
                <div class="input-group">
                    <div class="input-group-text"> 
                        <input onclick="mobile_view()" id="mobile_checkbox" type="checkbox">
                    </div> 
                    <div class="input-group-prepend">
                        <span class="input-group-text" >Mobile View</span> 
                    </div>
                    <div class="input-group-text"> 
                        <input onclick="demand_view()" id="demand_checkbox" type="checkbox">
                    </div> 
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="inputGroup-sizing-sm">Demand View</span> 
                    </div>
                    
                    <input class="input-group-text" type="search" name="search_order_id" placeholder="Search by Order id #" aria-label="Search">
                    <input class="input-group-text" type="search" name="search_text" placeholder="Name OR Number" aria-label="Search">
                    <select class="custom-select" aria-label="Default select example" name="order_by">
                        <option selected value ="">Order By</option>
                        <option value="manual_orders.id">Order ID</option>
                        <option value="manual_orders.receiver_name">Reciever Name</option>
                        <option value="manual_orders.receiver_number">Reciever Number</option>
                        <option value="manual_orders.created_at">Created Order</option> 
                        <option value="manual_orders.updated_at">Created Order</option> 
                        <option value="manual_orders.status">Status</option>
                    </select>
                    
                    <select class="custom-select" aria-label="Default select example" name="order_status">
                        <option selected value ="">Order Status</option>
                        <option value="all">All</option>
                        <option value="pending">Pending</option>
                        <option value="duplicate">Dulpicate</option>
                        <option value="prepared">Prepared</option>
                        <option value="confirmed">Confirmed</option>
                        <option value="dispatched">Dispatched</option> 
                        <option value="hold">Hold</option>
                        <option value="not responding">Not Responding</option> 
                        <option value="incomplete">incomplete</option>  
                        <option value="cancel">cancel</option> 
                        <option value="return">return</option> 
                    </select>
                    <input class="input-group-text" type="date" name="date_from" id="date_from">
                    <input class="input-group-text" type="date" name="date_to" id="date_to">
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="submit">Search</button>
                    </div>
                </div>
            </div>  
        </form>
    </div>


    <div class="col-sm-12 "> 
        <form class="form-inline float-right" method="post"  target="_blank" action="{{ route('ManualOrders.order.action') }}">
            @csrf
            <div class="input-group">
                <select class="form-select" aria-label="Default select example" name="order_action" required>
                    <option selected >Select Action</option> 
                    <option value="pending">Pending</option>
                    <option value="prepared">Prepared</option>
                    <option value="confirmed">Confirmed</option>  
                    <option value="dispatched">Dispatched</option> 
                    <option value="hold">Hold</option>
                    <option value="incomplete">incomplete</option> 
                    <option value="print">Print </option>
                    <option value="duplicate_orders">Duplicate Orders</option>
                    <option value="print_mnp_slips">Print M&P Slips</option>
                    <option value="print_trax_slips">Print Trax Slips</option>
                    <option value="print_pos_slips">Print Pos Slips</option>
                </select> 
                <div class="input-group-append">
                    <button class="btn btn-outline-secondary" type="submit">Submit</button> 
                </div>
            </div>
            
            <input type="hidden" name="order_ids" id="order_ids"> 
        </form>  
    </div>
         
</nav>

<div class="table-container" id="table-container-data" style="overflow-x:auto;"> 
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
                <th scope="col">Rec.Phone</th>
                <th scope="col">Cus.Phone</th>  
                <th scope="col">Send Msg</th> 
                <th scope="col">Description</th>
                <th scope="col">Address</th>
                <th scope="col">Price</th>
                <th scope="col">Adv.Payment</th>
                <th scope="col">COD</th>
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
            
            <tr class="list_<?=$count;?>" style="
            @if($lists->status == 'deleted') background-color:red;color: white;
            @elseif($lists->status == 'pending') background-color:orange ;
            @elseif($lists->status == 'addition') background-color:yellow ;
            @elseif($lists->status == 'prepared') background-color:#90f9ec;
            @elseif($lists->status == 'confirmed') background-color:blue ;color: white;
            @elseif($lists->status == 'dispatched') background-color:green ;color: white;
            @elseif($lists->status == 'deleted') background-color:pink ;
            @elseif($lists->status == 'incomplete') background-color:grey;color: white; 
            @elseif($lists->status == 'incomplete') background-color:lightgrey; 
            @else background-color:white; @endif">
                <td ><input type="checkbox" id="order_checkbox" class="order_checkbox_class" name="order_checkbox" onclick="get_checked_values()" value="{{$lists->id}}"></td>
                
                <td scope="row"><?=$count?></td> 
                
                <td>
                    <div class="btn-group" role="group">
                        <button id="btnGroupDrop1" type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                          Actions
                        </button>
                        <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">            
                            <a type="button" target="_blank" href="{{route('ManualOrders.edit',$lists->id)}}" class="dropdown-item">Edit</a>   
                            <button type="button" id="dispatch-btn" onclick="change_order_status_and_price({{$lists->id}},'pending')" class="dropdown-item" >Quick Edit</button>          
                            <a type="button" target="_blank" href="{{route('ManualOrders.show',$lists->id)}}" class="dropdown-item">view</a>
                            <a type="button" href="{{route('ManualOrders.print.order.slip',$lists->id)}}" class="dropdown-item">Print Slip</a> 
                            <button type="button" id="dispatch-btn" onclick="change_order_status_and_price({{$lists->id}},'prepared');order_link ='{{route('ManualOrders.confirm.order.by.customer.show',$lists->id)}}';" class="dropdown-item">Prepared</button>
                            <button type="button" id="dispatch-btn" onclick="change_order_status_and_price({{$lists->id}},'confirmed')" class="dropdown-item" >Confirmed</button>
                            <button type="button" id="dispatch-btn" onclick="change_order_status_and_price({{$lists->id}},'dispatched')" class="dropdown-item" >Dispatch</button>
                            <button type="button" id="dispatch-btn" onclick="change_order_status_and_price({{$lists->id}},'hold')" class="dropdown-item" >Hold</button>
                            <button type="button" id="dispatch-btn" onclick="change_order_status_and_price({{$lists->id}},'incomplete')" class="dropdown-item" >Incomplete</button>
                            <button type="button" id="dispatch-btn" onclick="change_order_status_and_price({{$lists->id}},'not responding')" class="dropdown-item" >not responding</button>
                            <button type="button" onclick="check_pos_slip_duplication('{{route('ManualOrders.print.pos.slip',$lists->id)}}','{{$lists->id}}','list_<?=$count;?>')"class="dropdown-item" >Print Pos Slip</button>
                            @if(Auth::guard('admin')->check()) 
                            
                            <button type="button" id="dispatch-btn" onclick="change_order_status_and_price({{$lists->id}},'cancel')" class="dropdown-item" >Cancel</button>
                            <button type="button" id="dispatch-btn" onclick="change_order_status_and_price({{$lists->id}},'return')" class="dropdown-item" >Return</button>
                            @endif
                            <!--<a type="button" href="{{route('ManualOrders.order.status',['hold',$lists->id])}}" class="dropdown-item">Hold</a> -->
                            <!--<a type="button" href="{{route('ManualOrders.order.status',['incomplete',$lists->id])}}" class="dropdown-item">Incomplete</a>-->
                            <!--<a type="button" href="{{route('ManualOrders.order.status',['cancel',$lists->id])}}" class="dropdown-item">Cancel</a>-->
                        </div>
                     </div>
                </td>
                <td >
                    @if(!empty($lists->images)) 
                     <?php $count_image_index= 0;?>
                        @foreach(explode('|', $lists->images) as $image)   
                        
                        <img class="pop rounded imgaes-demand" style="margin-right: 5px;" src="{{asset($image)}}" onclick="UniversalImagesSlider(<?=$count_image_index;?>,'{{$lists->images}}')"  data-toggle="modal"  alt="Card image cap" width="25" />
                        <?php $count_image_index++;?>
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
                <td><a target="_blank" href="https://api.whatsapp.com/send?phone=<?=$reciever_number?>&text=Assalamualikum {{$lists->first_name}},%0a I am from Brandhub,%0a Please confirm your order,%0a click on the link to and check your articles and press confirmed button. %0alink: {{route('ManualOrders.confirm.order.by.customer.show',$lists->id)}}"><?=$reciever_number?></a></td> 
                <td><a target="_blank" href="https://api.whatsapp.com/send?phone=<?=$number?>&text=Assalamualaikum, {{$lists->first_name}},%0a I am from Brandhub,%0a Please confirm your order,%0a click on the link to and check your articles and press confirmed button. %0alink: {{route('ManualOrders.confirm.order.by.customer.show',$lists->id)}}"><?=$number?></a></td> 
                <td><a target="_blank" href="https://api.whatsapp.com/send?phone=<?=$reciever_number?>&text=Assalamualaikum {{$lists->first_name}},%0aI am from Brandhub, check the details and verify.%0aName: {{$lists->first_name}}%0aNumber: {{$lists->receiver_number}}%0aAddress: {{$lists->reciever_address}}%0acity: @if(isset($lists->cities->name))?$lists->cities->name@else '' @endif %0aCOD: {{$lists->cod_amount}}">Send Confirmation msg</a></td> 
                <td>{{$lists->description}}</td> 
                <td>{{$lists->reciever_address}}</td>
                <td>{{$lists->price}}</td>  
                <td>{{$lists->advance_payment}}</td> 
                <td>{{$lists->cod_amount}}</td> 
                <td><a target="_blank" href="https://api.whatsapp.com/send?phone=<?=$number?>&text=Assalamualaikum, {{$lists->first_name}}, Mam did you recieve your order, please click on link to Track your Order {{route('ManualOrders.confirm.order.by.customer.show',$lists->id)}}">Get Status</a></td> 
                <td style="font-size: 10px;">{{date('d-M-y', strtotime($lists->created_at))}} <br> {{date('G:i a', strtotime($lists->created_at))}}</td>
                <td style="font-size: 10px;">{{date('d-M-y', strtotime($lists->updated_at))}} <br> {{date('G:i a', strtotime($lists->updated_at))}}</td> 
                <td>{{$lists->status}}</td>
                <td>{{$lists->status_reason}}</td>
            </tr>
            <?php $count++;?>
            @endforeach
        </tbody>
        
    </table>
</div>
{!! $list->appends(Request::all())->links() !!} 


<script type="application/javascript">

container = document.getElementsByClassName('table-container')[0].innerHTML;


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
    $(document).ready(function() {
    $('.js-example-basic-multiple').select2();
});

         
</script>
@endsection