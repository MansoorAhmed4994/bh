
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
        background: red;
        color: white;
        width: 100px;
        font-weight: bold;
        padding: 6px;
        border-radius: 5px;
    }
    
    @keyframes blinker {
        50% {
            opacity: 0;
        }
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
                    toastr["success"](response.messege, 'Assigning Order');
                } 
                else 
                {
                    toastr.error(response.messege,'Error');
                }
                    // alert(response.messege);
                 
                $("body").removeClass("loading");
                //console.log(response);
            },
            error: function(response) 
            {
                // alert(response); 
                toastr.error(response);
                $("body").removeClass("loading");
            },
      });
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
                    toastr.error("please give Reason to "+order_status,'Error'); 
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
                    toastr["success"](e.messege, 'Assigning Order');
                } 
                else 
                {
                    $("body").removeClass("loading");
                    toastr.error(e.messege,'Error');
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
                        toastr.error('you cant edit this order cause it is already dispatched', 'Error');
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
                    toastr.success(e.messege, 'Error');

                }
                else
                {
                    toastr.success(e.messege, 'Error');
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
                                <label for="receiver_name">Reciever Name</label>
                                <input type="text" class="form-control" value="" id="QuickEdit_receiver_name"  name="QuickEdit_receiver_name" placeholder="Reciever Name" required>
                                <small id="receiver_name_error" class="form-text text-danger"></small>
                            </div> 
                            
                            <div class="form-group">
                                <label for="receiver_name">Reciever Number</label>
                                <input type="text" class="form-control" id="QuickEdit_receiver_number"  name="QuickEdit_receiver_number" placeholder="Reciever Number" required>
                                <small id="receiver_name_error" class="form-text text-danger"></small>
                            </div> 
                            
                            <div class="form-group">
                                <label for="receiver_name">Reciever address</label>
                                <textarea class="form-control" id="QuickEdit_reciever_address"   name="QuickEdit_reciever_address" placeholder="reciever_address" required></textarea>
                                <small id="reciever_address_error" class="form-text text-danger"></small>
                            </div> 
                            
                        </div>
                        
                        <div class="col-sm-3">
                
                            <div class="form-group">
                                <label for="Number">price</label>
                                <input type="text" class="form-control" onkeyup="change_price_popup_status()" value="0" id="QuickEdit_price"  name="QuickEdit_price" placeholder="Price" required>
                                <small id="price_error" class="form-text text-danger"></small>
                            </div>
                
                            <div class="form-group">
                                <label for="Number">Advance Payment</label>
                                <input type="text" class="form-control" onkeyup="change_price_popup_status()" id="QuickEdit_advance_payment"  name="QuickEdit_advance_payment" placeholder="Advance Payment" readonly>
                                <small id="advance_payment_error" class="form-text text-danger"></small>
                            </div>
                
                            <div class="form-group">
                                <label for="Number">COD Amount</label>
                                <input type="text" class="form-control" value="" id="QuickEdit_cod_amount"  name="QuickEdit_cod_amount" placeholder="COD" readonly>
                                <small id="cod_amount_error" class="form-text text-danger"></small>
                            </div> 
                        </div>
                        
                        <div class="col-sm-3">
                
                            <div class="form-group">
                                <label for="Number">Assign to</label>
                                
                                <select class="form-control" id="QuickEdit_assign_to"  name="QuickEdit_assign_to" required>
                                    <option value="">Select user</option>
                                    @for($i=0 ; $i < sizeof($users); $i++)
                                     
                                        <option value="{{$users[$i]->id}}" >{{$users[$i]->first_name}} {{$users[$i]->last_name}}</option>
                                        
                                    @endfor 
                                    
                                </select>  
                            </div>
                
                            <div class="form-group">
                                <label for="Number">Status</label>
                                <select class="form-control"  name="QuickEdit_status" id="QuickEdit_status">
                                    <option selected >Select Status</option> 
                                    @foreach($statuses as $status) 
                                        <option value="{{$status->name}}">{{$status->name}}</option>
                                    @endforeach
                        
                                </select>  
                            
                            </div>
                
                            <div class="form-group">
                                <label for="Number">Reason</label>
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
                    @foreach($statuses as $status)
                    
                        <option value="{{$status->name}}">{{$status->name}}</option>
                    @endforeach
                    <option value="print">Print </option>
                    <option value="duplicate_orders">Duplicate Orders</option>
                    <option value="print_mnp_slips">Print M&P Slips</option>
                    <option value="print_trax_slips">Print Trax Slips</option>
                    <option value="print_pos_slips">Print Pos Slips</option>
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
                <th scope="col" class="delete_btn_class"><input type="checkbox" onclick="checkAll(this)" ></th>
                <th scope="col">#</th> 
                <th scope="col">Act</th> 
                <th scope="col">Status</th>
                <th scope="col">Status Reason</th>
                <th scope="col">Img.</th>
                <th scope="col">Consignment.Id</th>
                <th scope="col">Ord.ID</th>
                <th scope="col">C.ID</th>
                <th scope="col">F.Name</th> 
                <th scope="col">Rec.Phone</th> 
                <th scope="col"> Whatsapp</th> 
                <th scope="col">Desc.</th>
                <th scope="col">Address</th>
                <th scope="col">Price</th>
                <th scope="col">Adv.Pay</th>
                <th scope="col">COD</th> 
                <th scope="col">cr.Date</th>
                <th scope="col">Up.Date</th>
                <th scope="col">Updated by</th>
                <th scope="col">created by</th>
            </tr>
        </thead>
        <tbody>  
            <?php $count=1;?>
            @foreach($list as $lists)
            
            <tr class="list_<?=$count;?> status-{!! str_replace(' ', '-', $lists->status) !!}
            ">
                <td ><input type="checkbox" id="order_checkbox" class="order_checkbox_class" name="order_checkbox" onclick="get_checked_values()" value="{{$lists->id}}"></td>
                
                <td scope="row"><?=$count?></td> 
                
                <td>
                    <div class="btn-group" role="group">
                        <button id="btnGroupDrop1" type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                          Actions
                        </button>
                        <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">            
                            <a type="button" target="_blank" href="{{route('ManualOrders.edit',$lists->id)}}" class="dropdown-item">Edit</a>   
                            <button type="button" id="dispatch-btn" onclick="QuickEditOrder({{$lists->id}})" class="dropdown-item" >Quick Edit</button>          
                            <a type="button" target="_blank" href="{{route('ManualOrders.show',$lists->id)}}" class="dropdown-item">view</a>
                            <a type="button" href="{{route('ManualOrders.print.order.slip',$lists->id)}}" class="dropdown-item">Print Local Slip</a> 
                            <button type="button" onclick="check_pos_slip_duplication('{{route('ManualOrders.print.pos.slip',$lists->id)}}','{{$lists->id}}','list_<?=$count;?>')"class="dropdown-item" >Print Pos Slip</button>
                        </div>
                    
                        @if(Auth::guard('admin')->check())
                        <div class="btn-group " role="group">
                            <select class=" form-control @if($errors->get('assign_to')) is-invalid @endif assign_to_dropdown city btn-group" style="width:120px" onchange="assign_to('{{$lists->id}}',this.value)" id="assign_to"  name="assign_to">
                                <option value="">Select Assign To</option>
                                
                                @foreach($users as $user)
                                         
                                    <option value="{{$user->id}}" {{ ($user->id == $lists->assign_to) ? 'selected="selected"' : '' }}>{{$user->first_name}} {{$user->last_name}}</option>
                                    
                                @endforeach
                                
                                
                            </select>
                        </div>
                        @else
                        <div class="input-group-text" id="btnGroupAddon">
                        @foreach($users as $user)
                                         
                             {{ ($user->id == $lists->assign_to) ? ($user->first_name." ".$user->last_name)  : '' }}
                                    
                        @endforeach
                        </div>
                        @endif
                    </div>
                    
                </td>
                <td>
                    <select class="form-control" onchange="ChangeOrderStatus('{{$lists->id}}')" id="ChnageOrderStatusId_{{$lists->id}}" style="width:auto;">
                        <option selected >Select Status</option> 
                        @foreach($statuses as $status) 
                            <option value="{{$status->name}}" {{ ($status->name == $lists->status) ? 'selected="selected"' : '' }}>{{$status->name}}</option>
                        @endforeach  
                
                    </select>
                    
                    <div class="btn-group">
                        <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Order History
                        </button>
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
                                </tbody>
 
                            </table>
                        </div>
                    </div>
                    
                </td>
                <td>{{$lists->status_reason}}</td>
                
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
                <td>
                    
                    
                    
                    @if($lists->consignment_id != '' && $lists->consignment_id != '0')
                    <div class="btn-group" role="group">
                        <button id="btnGroupDrop1" type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                          {{$lists->consignment_id}}
                        </button>
                        <div class="dropdown-menu" aria-labelledby="btnGroupDrop1"> 
                                @if($lists->shipment_company == 'leopord')
                                    <a type="button" target="_blank" href="{{route('leopord.get.shipment.slip',$lists->consignment_id)}}"  class="dropdown-item"><img style="margin:15px; width:60px" src="https://ecom.leopardscourier.com/assets/landing_page/images/c-logo.png">Print Slip</a>
                                @elseif($lists->shipment_company == 'trax')
                                     
                                    <a type="button" target="_blank" href="{{route('trax.get.shipment.slip',$lists->consignment_id)}}" class="dropdown-item"><img style="margin:15px; width:100px" src="https://trax.pk/wp-content/uploads/2021/07/Black-Logo.svg">Print Slip</a>
                                @else
                                    
                                @endif
                        </div>
                    
                    </div>
                    @else
                        {{$lists->consignment_id}}
                    @endif
                    
                </td> 
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
                ?>
                <!--{{$per}}-->
                <td>{{$lists->id}}</td>
                <td>{{$lists->customers_id}}</td>
                <td>{{$lists->first_name}}
                    @if($per > 5)
                        <p>TO:{{$do }} DO:{{$ro}} Per:{{$per}}</p>
                        <div class="blink">Black list</div>@endif
                        </td>   
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
                <td>
                    <p><?=$reciever_number?></p>
                    <p><?=$number?></p> 
                </td>  
                
                <td>
                    <div class="btn-group" role="group">
                        <button id="btnGroupDrop1" type="button" class="btn btn-secondary dropdown-toggle" style="background:white;border:2px solid #4ac95a;color:black;padding: 0 11px;" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                          <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/5/5e/WhatsApp_icon.png/640px-WhatsApp_icon.png" width=30 style="margin-right: 5px;">Start msg
                        </button>
                        <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">            
                            <a target="_blank" class="dropdown-item" href="https://api.whatsapp.com/send?phone=<?=$reciever_number?>&text=Assalamualaikum {{$lists->first_name}},%0aI am from Brandhub, check the details and verify.%0aName: {{$lists->first_name}}%0aNumber: {{$lists->receiver_number}}%0aAddress: {{$lists->reciever_address}}%0acity: @if(isset($lists->cities->name)) {{$lists->cities->name}}@else '' @endif %0aCOD: {{$lists->cod_amount}}">Send Confirmation msg</a>
                            
                            <!--send tracking-->
                            <a target="_blank" class="dropdown-item" href="https://api.whatsapp.com/send?phone=<?=$reciever_number?>&text=Assalamualaikum {{$lists->first_name}},%0aI am from Brandhub, %0aplease track your order %0aHere is your tracking ID: {{$lists->consignment_id}} %0aHere is your Order ID: {{$lists->id}}  %0a helpline number: @if($lists->shipment_company == 'trax') 021111118729 @else 021111300786 @endif  %0aThank you %0alink: {{route('leopord.track.boocked.packet',$lists->consignment_id)}}">Send Tracking 
                            </a> 
                            
                            <a target="_blank" class="dropdown-item" href="{{route('leopord.track.boocked.packet',$lists->consignment_id)}}">Track Order</a>
                            
                            <a target="_blank" class="dropdown-item" href="https://api.whatsapp.com/send?phone=<?=$reciever_number?>&text=Assalamualaikum {{$lists->first_name}},%0aI am from Brandhub, %0aPlease Follow the Social Platform for latest product upcoming %0aFacebook:https://www.facebook.com/Brandhub000/ %0aInstagram: https://www.instagram.com/brandshub000/ %0aTiktok: https://www.tiktok.com/@brandhub994 %0aYoutube: https://www.youtube.com/@brandhub8324 ">Social Links</a> 
                            <a target="_blank" class="dropdown-item" href="https://api.whatsapp.com/send?phone=<?=$reciever_number?>&text=Assalamualaikum {{$lists->first_name}},%0aMain brandhub se mukhatib hoon, %0aham dekh sakte hain ke apne bht time se hamse kuch khareedari nahi ki hai, %0aMubarak hoo, khush khabri hai apke liye apke agle order per apko dene ke liye hamari janib se ak tohfa hai barae meherbani 7 din kay ander apna order place krain or apne tohfe se lutf andooz hoon shukria apka code ye hai: '{{$lists->id}}' isko code ko order place krte wkt agent ko bata dain.%0a %0a %0Or ye social platforms ko like and follow bhi zaroor karain ta ke apko new products ki malomat bawakt milti rhe %0aFacebook: https://www.facebook.com/Brandhub000/ %0aInstagram: https://www.instagram.com/brandshub000/ %0aTiktok: https://www.tiktok.com/@brandhub994 %0aYoutube: https://www.youtube.com/@brandhub8324">Customer Retain</a> 
                        </div>
                    </div>
                </td> 
                
                <!--============copy Description-->
                <td style="position:relative">
                    <p class="text-hidden-ellipsis-nowrap" id="{{$lists->id}}_description" >{{$lists->description}}</p>
                    <a class="copy-to-clipboard-btn" onclick="copy_clipboard_by_id('{{$lists->id}}_description')">Copy</a>
                </td> 
                
                <!--============copy Address-->
                <td style="position:relative">
                    <p class="text-hidden-ellipsis-nowrap" id="{{$lists->id}}_address" >{{$lists->reciever_address}}</p>
                    <a class="copy-to-clipboard-btn" onclick="copy_clipboard_by_id('{{$lists->id}}_address')">Copy</a>
                </td>
                
                <td>{{$lists->price}}</td>  
                <td>{{$lists->advance_payment}}</td> 
                <td>{{$lists->cod_amount}}</td>   
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


<script type="application/javascript">

function copy_clipboard_by_id(id) {
  // Get the text field
  var copyText = document.getElementById(id);
    
  // Select the text field
//   copyText.select();
//   copyText.setSelectionRange(0, 99999); // For mobile devices

  // Copy the text inside the text field
  navigator.clipboard.writeText(copyText.innerHTML);
  
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
        
        
    });
    $(document).ready(function() {
    $('.js-example-basic-multiple').select2();
});

         
</script>
@endsection