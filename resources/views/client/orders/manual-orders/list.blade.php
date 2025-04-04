
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
    .blink {
        text-align: center;
        animation: blinker 1s linear infinite;
        background: white;
        color: red;
        font-weight: bold;
        padding: 1px 5px;
        border-radius: 3px;
    }
    
    @keyframes blinker {
        50% {
            opacity: 0;
        }
    }
    
    td 
    {
        padding-bottom:0!important; 
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
        let price = $('#QuickEdit_price').val();
        let advance_payment = $('#QuickEdit_advance_payment').val();
        let cod_amount = $('#QuickEdit_cod_amount').val(price-advance_payment);
        
    }
    function assign_to(order_id,user_id)
    {
        
        $("body").addClass("loading");
        $.ajax
        ({
            url: base_url + '/client/orders/ManualOrders/assign-to/'+order_id+'/'+user_id,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type:"POST",
            dataType: 'json',
            success:function(response)
            {
                //$('#successMsg').show();
                if(typeof response.success !== 'undefined')
                {
                    flasher.success(response.messege, 'Assigning Order');
                } 
                else 
                {
                    flasher.error(response.messege,'Error');
                }
                    // alert(response.messege);
                 
                $("body").removeClass("loading");
                //console.log(response);
            },
            error: function(response) 
            {
                // alert(response); 
                flasher.error(response);
                $("body").removeClass("loading");
            },
      });
    }
        
        
    $( document ).ready(function() { 
        
        
        $('#order_status_edit_close').on('click',function(){
            $('#order_status_edit').modal('hide');
        });
        
        $('#pos_slip_duplication_modal_close').on('click',function(){
            $('#pos_slip_duplication_modal').modal('hide');
        });
                 
        
        
        $('#QuickEditOrderUpdate').on('click',function(e) {
            // alert('working');
            $("body").addClass("loading"); 
            
            let order_id = $('#QuickEdit_order_id').val(); 
            let order_status = $('#QuickEdit_status').val();
            let status_reason = $('#QuickEdit_status_reason').val();
            
            
            if(order_status == 'cancel' || order_status == 'hold' || order_status == 'incomplete')
            {
                if(status_reason == '')
                {
                    $("body").removeClass("loading"); 
                    flasher.error("please give Reason to "+order_status,'Error'); 
                    return;
                }
                
            }
            
            e.preventDefault();
            let formData = new FormData(document.getElementById("QuickEditOrderForm")); 
            
            
            $.ajax({
                type:'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }, 
                url: base_url + '/client/orders/ManualOrders/QuickEditOrderUpdate/'+order_id,
                data: formData,
                contentType: false,
                processData: false,
                success: (e) => {
                //$('#successMsg').show();
                if(typeof e.success !== 'undefined')
                {
                    $("body").removeClass("loading");
                    flasher.success(e.messege, 'Assigning Order');
                } 
                else 
                {
                    $("body").removeClass("loading");
                    flasher.error(e.messege,'Error');
                }
                if(e.messege == true)
                {
                    $("body").removeClass("loading");
                    if(order_status == "cancel")
                    {
                        
                        var get_char_rec_num = receiver_number.substring(0,1);
                        if(get_char_rec_num == 0)
                        {
                            receiver_number = "+92"+receiver_number;
                        }
                        var link = "https://api.whatsapp.com/send?phone="+receiver_number+"&text=Assalamualaikum, "+receiver_name+", I am from Brandhub, i just want to inform you that your parcel has been cancelled due to unavailable of item, please contact here for more details 03362240865";
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
                
                $('#order_status_edit').modal('hide');
                $("body").removeClass("loading");
                
                //console.log(response);
              },
              error: function(e) {
                    alert(e); 
                    $("body").removeClass("loading"); 
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
    
    
    function QuickEditOrder(order_id) 
    {   
        $("body").addClass("loading");
        
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: base_url + '/client/orders/ManualOrders/QuickEditOrder/'+order_id, 
            
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
                    @if(Auth::guard('admin')->check())
                    
                    @else
                        flasher.error('you cant edit this order cause it is already dispatched', 'Error');
                        $("body").removeClass("loading");
                        return;
                    @endif
                    

                }
                
                $('#QuickEdit_order_id').val(e.messege.id); 
                $('#QuickEdit_receiver_name').val(e.messege.receiver_name); 
                $('#QuickEdit_receiver_number').val(e.messege.receiver_number);
                $('#QuickEdit_reciever_address').val(e.messege.reciever_address);
                $('#QuickEdit_price').val(e.messege.price);
                $('#QuickEdit_cod_amount').val(e.messege.cod_amount);
                $('#QuickEdit_advance_payment').val(e.messege.advance_payment);
                $('#QuickEdit_assign_to').val(e.messege.assign_to);
                $('#QuickEdit_status').val(e.messege.status);
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
    
    
    function ChangeOrderStatus(id) 
    {   
        var status = $('#ChnageOrderStatusId_'+id).val();
        $("body").addClass("loading"); 
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: '{{route("ManualOrders.change.status")}}',
            
            type: 'POST',
            dataType: 'json',
            data: {
                order_id:id,
                status:status
            },
            success: function(e) 
            {
                if(typeof e.success !== 'undefined')
                {
                    flasher.success(e.messege, 'Error');

                }
                else
                {
                    flasher.success(e.messege, 'Success');
                }
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
                <form method="post" name="QuickEditOrderForm" id="QuickEditOrderForm" enctype="multipart/form-data">
                    <div class="row col-sm-12"> 
                        
                        <input type="hidden" name="QuickEdit_order_id" id="QuickEdit_order_id" >
                        <div class="col-sm-3">
                            <div class="alert alert-success" id="dispatch-succes-noti" style="display:none" role="alert">successfully dispatch and update</div>
                            <div class="form-group col-sm">
                                <div class="card" id="images_pop" style="max-width: 200px;"> 
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-sm-3">
                                
                            <div class="form-group">
                                <label for="QuickEdit_receiver_name">Reciever Name</label>
                                <input type="text" class="form-control" value="" id="QuickEdit_receiver_name"  name="QuickEdit_receiver_name" placeholder="Reciever Name" required>
                                <small id="receiver_name_error" class="form-text text-danger"></small>
                            </div> 
                            
                            <div class="form-group">
                                <label for="QuickEdit_receiver_number">Reciever Number</label>
                                <input type="text" class="form-control" id="QuickEdit_receiver_number"  name="QuickEdit_receiver_number" placeholder="Reciever Number" required>
                                <small id="receiver_name_error" class="form-text text-danger"></small>
                            </div> 
                            
                            <div class="form-group">
                                <label for="QuickEdit_reciever_address">Reciever address</label>
                                <textarea class="form-control" id="QuickEdit_reciever_address"   name="QuickEdit_reciever_address" placeholder="reciever_address" required></textarea>
                                <small id="reciever_address_error" class="form-text text-danger"></small>
                            </div> 
                            
                        </div>
                        
                        <div class="col-sm-3">
                
                            <div class="form-group">
                                <label for="QuickEdit_price">price</label>
                                <input type="text" class="form-control" onkeyup="change_price_popup_status()" value="0" id="QuickEdit_price"  name="QuickEdit_price" placeholder="Price" required>
                                <small id="price_error" class="form-text text-danger"></small>
                            </div>
                
                            <div class="form-group">
                                <label for="QuickEdit_advance_payment">Advance Payment</label>
                                <input type="text" class="form-control" onkeyup="change_price_popup_status()" id="QuickEdit_advance_payment"  name="QuickEdit_advance_payment" placeholder="Advance Payment" readonly>
                                <small id="advance_payment_error" class="form-text text-danger"></small>
                            </div>
                
                            <div class="form-group">
                                <label for="QuickEdit_cod_amount">COD Amount</label>
                                <input type="text" class="form-control" value="" id="QuickEdit_cod_amount"  name="QuickEdit_cod_amount" placeholder="COD" readonly>
                                <small id="cod_amount_error" class="form-text text-danger"></small>
                            </div> 
                        </div>
                        
                        <div class="col-sm-3">
                
                            <div class="form-group">
                                <label for="QuickEdit_assign_to">Assign to</label>
                                
                                <select class="form-control" id="QuickEdit_assign_to"  name="QuickEdit_assign_to" required>
                                    <option value="">Select user</option>
                                    @for($i=0 ; $i < sizeof($users); $i++)
                                     
                                        <option value="{{$users[$i]->id}}" >{{$users[$i]->first_name}} {{$users[$i]->last_name}}</option>
                                        
                                    @endfor 
                                    
                                </select>  
                            </div>
                
                            <div class="form-group">
                                <label for="QuickEdit_status">Status</label>
                                <select class="form-control"  name="QuickEdit_status" id="QuickEdit_status">
                                    <option selected >Select Status</option> 
                                    @foreach($statuses as $status) 
                                        <option value="{{$status->name}}">{{$status->name}}</option>
                                    @endforeach
                        
                                </select>  
                            
                            </div>
                
                            <div class="form-group">
                                <label for="QuickEdit_status_reason">Reason</label>
                                <textarea  class="form-control" value="" id="QuickEdit_status_reason"  name="QuickEdit_status_reason" placeholder="Reason for status" required></textarea>
                                <small id="QuickEdit_status_reason_error" class="form-text text-danger"></small>
                            </div>
                            
                        </div>
                        
                    </div>
                </form>
        
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="order_status_edit_close">Close</button>
                <button button="button" class="btn btn-primary" id="QuickEditOrderUpdate">Save changes</button>
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
                    
                    <input class="input-group-text" type="search" name="search_order_id" placeholder="Search by Order ID OR Customer ID" aria-label="Search">
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
                        @foreach($statuses as $status)
                        
                            <option value="{{$status->name}}">{{$status->name}}</option>                
                            
                        @endforeach  
                    </select>
                    <input class="input-group-text" type="date" name="date_from" id="date_from">
                    <select class="custom-select" aria-label="Default select example" name="date_by" id="date_by"> 
                        <option selected value ="">Select Date By</option>
                        <option value="created_at">Created Order</option> 
                        <option value="updated_at">Updated Order</option>  
                    </select>
                    <input class="input-group-text" type="date" name="date_to" id="date_to">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="submit">Search</button>
                    </div>
                </div>
            </div>  
        </form>
    </div>



    <div class="col-sm-12 "> 
        <form class="form-inline float-left" method="post"  target="_blank" action="{{ route('ManualOrders.order.action') }}">
            @csrf
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
                <select class="form-select" aria-label="Default select example" name="order_action" required>
                    <option selected >Select Action</option> 
                    
                    <option value="print_pos_slips">Print Pos Slips</option>
                        @foreach($statuses as $status) 
                            <option value="{{$status->name}}">{{$status->name}}</option>
                        @endforeach
                    <option value="print">Print </option>
                    <option value="duplicate_orders">Duplicate Orders</option> 
                </select> 
                <div class="input-group-append">
                    <button class="btn btn-warning" type="submit">Submit</button> 
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
                <th scope="col" class="delete_btn_class"><input type="checkbox" name="check_all_boxes" onclick="checkAll(this)" ></th> 
                <th scope="col">Action / Assign</th> 
                <th scope="col">Status</th>
                <th scope="col">Status Reason</th> 
                <th scope="col">Shipment</th>
                <th scope="col">Ord.ID</th>
                <th scope="col">Images</th>
                <th scope="col">Customer</th>   
                <th scope="col"> <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/5/5e/WhatsApp_icon.png/640px-WhatsApp_icon.png" width=30 style="margin-right: 5px;"></th> 
                <th scope="col">Desc.</th>
                <th scope="col">City</th>
                <th scope="col">Address</th>
                <th scope="col">Price</th>
                <th scope="col">Adv.Pay</th>
                <th scope="col">COD</th> 
                <th scope="col">created</th>
                <th scope="col">Updated</th> 
            </tr>
        </thead>
        <tbody>  
            <?php $count=1;?>
            @foreach($list as $lists) 
                <tr class="list_<?=$count;?> status-{!! str_replace(' ', '-', $lists->status) !!} ">
                    
                    
                    <!--Check box --> 
                    <td>
                         <input type="checkbox"  class="order_checkbox_class" name="order_checkbox" onclick="get_checked_values()" value="{{$lists->id}}"> <?=$count;?>
                    </td>
                    
                    
                    <!--Assign to-->
                    <td> 
                        <div class="btn-group" role="group">
                            <button id="btnGroupDrop1" type="button" class="btn btn-secondary bi bi-gear" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" @if($lists->status == 'dispatched')disabled="disabled" @endif>  </button>
                            <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">            
                                <a type="button" target="_blank" href="@if($lists->id) {{route('ManualOrders.edit',$lists->id)}} @endif" class="dropdown-item">Edit</a>   
                                <button type="button" id="dispatch-btn" onclick="QuickEditOrder({{$lists->id}})" class="dropdown-item" >Quick Edit</button>          
                                <a type="button" target="_blank" href="@if($lists->id) {{route('ManualOrders.show',$lists->id)}} @endif" class="dropdown-item">view</a>
                                <a type="button" href="@if($lists->id) {{route('ManualOrders.print.order.slip',$lists->id)}} @endif" class="dropdown-item">Print Local Slip</a> 
                                <button type="button" onclick="@if($lists->id) check_pos_slip_duplication('{{route('ManualOrders.print.pos.slip',$lists->id)}}','{{$lists->id}}','list_<?=$count;?>') @endif"class="dropdown-item" >Print Pos Slip</button>
                            </div>
                            
                            @if(in_array('author', $user_roles) || in_array('admin', $user_roles))
                                <div class="btn-group " role="group">
                                    <select class=" form-select form-select-sm @if($errors->get('assign_to')) is-invalid @endif assign_to_dropdown city btn-group" style="width:120px" onchange="assign_to('{{$lists->id}}',this.value)"  name="assign_to" @if($lists->status == 'dispatched')disabled="disabled" @endif>
                                        <option value="">Select Assign To</option> 
                                        @foreach($users as $user) 
                                            <option value="{{$user->id}}" {{ ($user->id == $lists->assign_to) ? 'selected="selected"' : '' }}>{{$user->first_name}} {{$user->last_name}}</option> 
                                        @endforeach 
                                    </select>
                                </div> 
                            @else
                                <div class="input-group-text" id="btnGroupAddon">
                                    @foreach($users as $user)  {{ ($user->id == $lists->assign_to) ? ($user->first_name." ".$user->last_name)  : '' }} @endforeach
                                </div>
                            @endif 
                        </div>  
                    </td>
                    
                    
                    <!-- status-->
                    <td>
                        <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                            <select class="form-select form-select-sm" onchange="ChangeOrderStatus('{{$lists->id}}')" id="ChnageOrderStatusId_{{$lists->id}}" style="width: 153px;" @if(in_array('user', $user_roles)  || in_array('admin', $user_roles)) @else disabled="disabled"@endif>
                                <option selected >Select Status</option> 
                                 @if(in_array('author', $user_roles) || in_array('admin', $user_roles)) 
                                    @foreach($statuses as $status) 
                                         <option value="{{$status->name}}" {{ ($status->name == $lists->status) ? 'selected="selected"' : '' }}>{{$status->name}}</option>
                                     @endforeach  
                                 @else
                                     @if(in_array('user', $user_roles)  || in_array('calling', $user_roles))  
                                         <option selected="">Select Status</option>  
                                        <option value="prepared" {{ ('prepared' == $lists->status) ? 'selected="selected"' : '' }} >prepared</option> 
                                        <option value="addition" {{ ('addition' == $lists->status) ? 'selected="selected"' : '' }} >addition</option>  
                                        <option value="confirmed" {{ ('confirmed' == $lists->status) ? 'selected="selected"' : '' }} >confirmed</option> 
                                        <option value="hold" {{ ('hold' == $lists->status) ? 'selected="selected"' : '' }} >hold</option> 
                                        <option value="not responding" {{ ('not respondin' == $lists->status) ? 'selected="selected"' : '' }} >not responding</option> 
                                        <option value="dc comming" {{ ('dc comming' == $lists->status) ? 'selected="selected"' : '' }}>dc comming</option>   
                                     @endif
                                 @endif
                        
                            </select> 
                        
                            <div class="btn-group" role="group">
                                <button class="bi bi-three-dots btn btn-secondary " type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> </button>
                                
                                <div class="btn-group" role="group">
                                <div class="dropdown-menu">
                                    <table>
                                        <thead>
                                            <tr>
                                                <th>description</th>
                                                <th>Date</th>
                                                <th>User</th>
                                            </tr>
                                        </thead>
                                        
                                        <tbody>
                                            @if($lists->activity_logs)
                                                @foreach($lists->activity_logs as $activity_log)
                                                <tr>
                                                    <td>{{$activity_log->activity_desc}}</td>
                                                    <td>{{$activity_log->created_at}}</td>
                                                    @if(!empty($activity_log->users))
                                                        <td>{{$activity_log->users->first_name}}</td>
                                                    @else
                                                        
                                                        <td>No User Found (User ID: {{$activity_log->created_by}})</td>
                                                        
                                                    @endif
                                                </tr>
                                                @endforeach
                                            @endif
                                        </tbody>
         
                                    </table>
                                </div>
                            </div>
                        </div>
                    </td>
                    
                    
                    <!-- status Reason-->
                    <td>{{$lists->status_reason}}</td>
                    
                    
                    <!-- shipment-->
                    <td>  
                        @if($lists->consignment_id != '' && $lists->consignment_id != '0')
                            <div class="btn-group" role="group"> 
                                <div id="{{$lists->id}}_consignment" style="display:none;">{{$lists->consignment_id}}</div> 
                                <div class="btn-group" role="group">
                                    <button id="btnGroupDrop1" type="button" class="btn btn-secondary bi bi-truck" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> </button>
                                    <div class="dropdown-menu" aria-labelledby="btnGroupDrop1"> 
                                        @if($lists->shipment_company == 'leopord')
                                            <a type="button" target="_blank" href="{{route('leopord.get.shipment.slip',$lists->consignment_id)}}"  class="dropdown-item"><img style="margin:15px; width:60px" src="https://ecom.leopardscourier.com/assets/landing_page/images/c-logo.png">Print Slip</a>
                                        @elseif($lists->shipment_company == 'trax')
                                             <a type="button" target="_blank" href="{{route('trax.get.shipment.slip',$lists->consignment_id)}}" class="dropdown-item"><img style="margin:15px; width:100px" src="https://trax.pk/wp-content/uploads/2021/07/Black-Logo.svg">Print Slip</a>
                                        @endif
                                    </div>
                                </div>
                                <button type="button" class="btn btn-secondary bi bi-copy"  onclick="copy_clipboard_by_id('{{$lists->id}}_consignment')"></button> 
                            </div>
                        @else
                            {{$lists->consignment_id}}
                        @endif 
                    </td>
                    
                    
                    <!-- Order id-->
                    <td>{{$lists->id}}</td>
                    
                    
                    <!-- Images -->
                    <td >
                        <button class="btn btn-primary bi bi-image" onclick="UniversalImagesBoxes(0,'{{$lists->images}}',{{$lists->id}})"></button>
                        <div id="order_images"></div>
                    </td>
                    
                    
                    <!-- Customer Details -->
                    <?php 
                        $do = (int)$lists->dispatched_count;
                        $ro = (int)$lists->return_count;
                        $per=0;
                        if($ro > 0 && $do >0)
                        {
                            $per = ($ro/($do))*100;
                        }
                        else
                        {
                            $per =0;
                        }
                        
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
                    <td style="white-space: nowrap;"> 
                        
                        <span>  
                            <i class="bi bi-copy"  onclick="copy_clipboard_by_id('{{$lists->id}}_customer_data')"></i>
                            <span class="@if($per > 5) blink @endif">{{$lists->customers->first_name}} Code: {{$lists->customers_id}}</span> 
                            <div style="display:none" id="{{$lists->id}}_customer_data">{{$lists->customers->first_name}} Code: {{$lists->customers_id}}</div> 
                            @if($per > 5) 
                                <div class="btn-group " role="group" >
                                    <i class="bi bi-three-dots "  data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> </i> 
                                    <div class="dropdown-menu">
                                        <table>
                                            <thead>
                                                <tr>
                                                    <th>Total Orders</th>
                                                    <th>Dispacth Order</th>
                                                    <th>Return Orders</th>
                                                </tr>
                                            </thead> 
                                            <tbody> 
                                                <tr>
                                                    <td>{{$do }}</td> 
                                                    <td>{{$do }}</td> 
                                                    <td>{{$ro }}</td>  
                                                </tr> 
                                            </tbody> 
                                        </table>
                                    </div> 
                                </div> 
                                
                            @endif
                        </span> <br/>
                        <span> 
                            <i  class="bi bi-copy" onclick="copy_clipboard_by_id('{{$lists->id}}_customer_numbers')"></i> 
                            <span  id="{{$lists->id}}_customer_numbers"><?=$reciever_number?> / <?=$number?></span>
                        </span> 
                    </td> 
                    
                    
                    <!-- Whatsapp msg -->
                    <td>
                        <div class="btn-group" role="group">
                            <button id="btnGroupDrop1" type="button" class="btn btn-secondary dropdown-toggle" style="background:white;border:2px solid #4ac95a;color:black;padding: 0 11px;" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                              <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/5/5e/WhatsApp_icon.png/640px-WhatsApp_icon.png" width=30 style="margin-right: 5px;">
                            </button>
                            <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">            
                                <a target="_blank" class="dropdown-item" href="https://api.whatsapp.com/send?phone=<?=$reciever_number?>&text=Assalamualaikum {{$lists->first_name}} code: {{$lists->customers_id}},%0aI am from Brandhub, check the details and verify.%0aName: {{$lists->first_name}}%0aNumber: {{$lists->receiver_number}}%0aAddress: {{$lists->reciever_address}}%0acity: @if(isset($lists->cities->name)) {{$lists->cities->name}}@else '' @endif %0aCOD: {{$lists->cod_amount}}">Send Confirmation msg</a>
                                
                                <!--send tracking-->
                                <a target="_blank" class="dropdown-item" href="https://api.whatsapp.com/send?phone=<?=$reciever_number?>&text=Assalamualaikum {{$lists->first_name}} code: {{$lists->customers_id}},%0aI am from Brandhub, %0aplease track your order %0aHere is your tracking ID: {{$lists->consignment_id}} %0aHere is your Order ID: {{$lists->id}}  %0a helpline number: @if($lists->shipment_company == 'trax') 021111118729 @else 021111300786 @endif  %0aThank you %0alink: @if($lists->id) {{route('leopord.track.boocked.packet',$lists->consignment_id)}} @endif">Send Tracking 
                                </a> 
                                
                                <a target="_blank" class="dropdown-item" href="@if($lists->id) {{route('leopord.track.boocked.packet',$lists->consignment_id)}} @endif">Track Order</a>
                                
                                <a target="_blank" class="dropdown-item" href="https://api.whatsapp.com/send?phone=<?=$reciever_number?>&text=Assalamualaikum {{$lists->first_name}} code: {{$lists->customers_id}},%0aI am from Brandhub, %0aPlease Follow the Social Platform for latest product upcoming %0aFacebook:https://www.facebook.com/Brandhub000/ %0aInstagram: https://www.instagram.com/brandshub000/ %0aTiktok: https://www.tiktok.com/@brandhub994 %0aYoutube: https://www.youtube.com/@brandhub8324 ">Social Links</a> 
                                <a target="_blank" class="dropdown-item" href="https://api.whatsapp.com/send?phone=<?=$reciever_number?>&text=Assalamualaikum {{$lists->first_name}} code: {{$lists->customers_id}},%0aMain brandhub se mukhatib hoon, %0aham dekh sakte hain ke apne bht time se hamse kuch khareedari nahi ki hai, %0aMubarak hoo, khush khabri hai apke liye apke agle order per apko dene ke liye hamari janib se ak tohfa hai barae meherbani 7 din kay ander apna order place krain or apne tohfe se lutf andooz hoon shukria apka code ye hai: '{{$lists->id}}' isko code ko order place krte wkt agent ko bata dain.%0a %0a %0Or ye social platforms ko like and follow bhi zaroor karain ta ke apko new products ki malomat bawakt milti rhe %0aFacebook: https://www.facebook.com/Brandhub000/ %0aInstagram: https://www.instagram.com/brandshub000/ %0aTiktok: https://www.tiktok.com/@brandhub994 %0aYoutube: https://www.youtube.com/@brandhub8324">Customer Retain</a> 
                            </div>
                        </div>
                    </td> 
                    
                    
                    
                    <!-- copy Description -->
                    <td>
                        <div class="btn-group" role="group" aria-label="Address">
                            <!--<button type="button" class="btn btn-sm btn-primary text-hidden-ellipsis-nowrap" data-bs-toggle="popover" title="Address" id="{{$lists->id}}_address" data-bs-content="{{$lists->reciever_address}}">View More</button>-->
                            <button tabindex="0" class="btn btn-sm btn-primary text-hidden-ellipsis-nowrap" data-bs-content="{{$lists->description}}">{{$lists->description}}</button>
                            <button type="button" class="btn btn-primary"><i class="bi bi-copy " onclick="copy_clipboard_by_id('{{$lists->id}}_description')"></i> </button>
                        </div> 
                    </td>
                    
                    
                    <!-- City -->
                    <td>@if($lists->leopord_cities != null) {{$lists->leopord_cities->name}} @endif</td>
                    
                    
                    <!-- copy Address-->
                    <td>
                        <div class="btn-group" role="group" aria-label="Address">
                            <!--<button type="button" class="btn btn-sm btn-primary text-hidden-ellipsis-nowrap" data-bs-toggle="popover" title="Address" id="{{$lists->id}}_address" data-bs-content="{{$lists->reciever_address}}">View More</button>-->
                            <button tabindex="0" class="btn btn-sm btn-primary text-hidden-ellipsis-nowrap" role="button" data-bs-toggle="popover" data-bs-trigger="focus" id="{{$lists->id}}_address" title="Address" data-bs-content="{{$lists->reciever_address}}">{{$lists->reciever_address}}</button>
                            <button type="button" class="btn btn-primary"><i class="bi bi-copy " onclick="copy_clipboard_by_id('{{$lists->id}}_address')"></i> </button>
                        </div>
                    </td>
                    
                    
                    <!-- Price -->
                    <td>{{$lists->price}}</td>
                    
                    
                    <!-- Advacne payment -->  
                    <td>{{$lists->advance_payment}}</td>
                    
                    
                    <!-- cod --> 
                    <td>{{$lists->cod_amount}}</td>
                    
                    
                    <!-- Created details -->   
                    <td style="white-space: pre;"> @if(!empty($lists->UsersCreatedBy->first_name)) {{$lists->UsersCreatedBy->first_name}} @endif {{date('d-M-y', strtotime($lists->created_at))}} {{date('G:i a', strtotime($lists->created_at))}} ({{$lists->created_by}})</td>
                    
                    
                    <!-- Updated details -->
                    <td style="white-space: pre;">@if(!empty($lists->UsersUpdatedBy->first_name)) {{$lists->UsersUpdatedBy->first_name}} @endif {{date('d-M-y', strtotime($lists->updated_at))}} {{date('G:i a', strtotime($lists->updated_at))}} ({{$lists->updated_by}})</td>
                </tr>
                <?php $count++;?>
            @endforeach
        </tbody>
        
    </table>
</div>
<!--{!! $list->appends(Request::all())->links() !!} -->
{{ $list->links() }}

<script type="application/javascript">

    function copy_clipboard_by_id(id) {
        // Get the text field
        var copyText = document.getElementById(id);
        
        // alert(copyText.getAttribute("data-bs-content"));
        // Select the text field
        //   copyText.select();
        //   copyText.setSelectionRange(0, 99999); // For mobile devices
        
        // Copy the text inside the text field
        navigator.clipboard.writeText(copyText.getAttribute("data-bs-content"));
        
        // Alert the copied text 
    }

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
    
        $('.js-example-basic-multiple').select2();
        
        
        $('.popover-dismiss').popover({
            trigger: 'focus'
        });
        
        
        
    });
     
    var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
    var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl)
    })
 
          
         
</script>
@endsection