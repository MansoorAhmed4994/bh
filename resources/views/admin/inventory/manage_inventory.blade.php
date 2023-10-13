
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
        
        $('#createinventorymodalbtn').on('click',function(){
            
            $('#createinventorymodal').modal('show');
        });
         
        
        $('#add_new_inventory_close').on('click',function(){
            
            $('#createinventorymodal').modal('hide');
        });
        
        $('#sku').on('keypress',function(e) {
            if(e.which == 13) {
                add_new_inventory();
            }
        });
        
        $('#cost').on('keypress',function(e) {
            if(e.which == 13) {
                add_new_inventory();
            }
        });
        
        $('#sale').on('keypress',function(e) {
            if(e.which == 13) {
                add_new_inventory();
            }
        });
        
        
                 
         
                
    });
    
    function add_new_inventory()
        {
            $("body").addClass("loading"); 
            
            var sku = $('#sku').val();
            var category_id = $('#category_id').val();
            var name = $('#name').val();
            var sale = $('#sale').val();
            
            var products_id = $('#products_id').val();
            var stock_status = $('#stock_status').val();
            var qty = $('#qty').val(); 
            var cost = $('#cost').val();  
            
            // console.log(sku+'\n'+units+'\n'+unit_type+'\n'+sale+'\n'+cost+'\n');
            if(sku !='' && sale > 0 && cost > 0)
            {
                $.ajax({
                    url: base_url + '/admin/inventory/store', 
                  headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                  type:"POST",
                  dataType: 'json',
                  data:{
                    sku:sku, 
                    name:name,
                    sale:sale,
                    category_id:category_id,
                    
                    product_id:products_id,
                    stock_status:stock_status,
                    qty:qty,
                    cost:cost, 
                  },
                  success:function(response){
                    //$('#successMsg').show();
                        if(response.messege)
                        {
                           alert(response.messege);
                           $('#sku').val('');
                            $('#category_id').val('');
                            $('#name').val('');
                            $('#sale').val('');
                            
                            $('#products_id').val('');
                            $('#stock_status').val('');
                            $('#qty').val(''); 
                            $('#cost').val(''); 
                           
                            $("body").removeClass("loading");
                            
                        }
                    console.log(response);
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
            }
            else
            {
                alert('please fill all the fields');
                $("body").removeClass("loading");
            }
        }
    
    
    
    function change_order_status_and_price(val,status) 
    {   
        $("body").addClass("loading");
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
                
                $("body").removeClass("loading");
                
            },
            error: function(e) {
                alert(e); 
                $("body").removeClass("loading");
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
            </div>
            <div class="modal-body">
                <div class="col-sm-12">
                    
                    <h4>Add New Inventory <hr></h4> 
                        <div class="alert alert-success" id="dispatch-succes-noti" style="display:none" role="alert">successfully dispatch and update</div>
                        
                        <div class="form-group">
                            <label for="SKU">SKU</label>
                            <input type="text" class="form-control" value="" id="sku"  name="sku" placeholder="SKU" >
                            <small id="sku_error" class="form-text text-danger"></small>
                        </div>  
            
                        <div class="form-group">
                            <label for="cost">cost</label>
                            <input type="number" class="form-control" value="0" id="cost"  name="cost" placeholder="cost" >
                            <small id="cost_error" class="form-text text-danger"></small>
                        </div> 
            
                        <div class="form-group">
                            <label for="sale">sale</label>
                            <input type="number" class="form-control" value="0" id="sale"  name="sale" placeholder="sale" >
                            <small id="sale_error" class="form-text text-danger"></small>
                        </div>
                        
                        <div class="form-group">
                            <label for="Type">Category</label>
                            <select class="form-select" aria-label="Default select example" id ="category_id" name="category_id">
                              <option selected value ="">Select Type</option> 
                              @foreach($categories as $category)
                              
                              <option value="1">{{$category->name}}</option>
                              @endforeach 
                            </select> 
                            <small id="category_id_error" class="form-text text-danger"></small>
                        </div>  
                        
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" value="" id="name"  name="name" placeholder="Product Name" >
                            <small id="name_error" class="form-text text-danger"></small>
                        </div>
                        
                        <!--<div class="form-group">-->
                        <!--    <label for="Type">Weight Type</label>-->
                        <!--    <select class="form-select" aria-label="Default select example" id ="unit_type" name="unit_type">-->
                        <!--      <option selected value ="">Select Type</option> -->
                        <!--      <option value="ml">ml</option>-->
                        <!--      <option value="mg">mg</option>   -->
                        <!--    </select> -->
                        <!--    <small id="unit_type_error" class="form-text text-danger"></small>-->
                        <!--</div> -->
                         
                        
                        <div class="form-group">
                            <label for="stock_status">Stock Status</label>
                            <select class="form-select" aria-label="Default select example" id ="stock_status" name="stock_status" >
                                <option selected value ="">Select Type</option> 
                                <option value="in" selected>In</option>
                                <!--<option value="out">Out</option>   -->
                            </select> 
                            <!--<input type="text" class="form-control" id="stock_status"  name="stock_status" placeholder="Reciever Number" required>-->
                            <small id="units_error" class="form-text text-danger"></small>
                        </div> 
                        
                        <div class="form-group">
                            <label for="units">Quantity</label>
                            <input type="number" class="form-control" id="qty" value="0" name="qty" placeholder="qty" >
                            <small id="qty_error" class="form-text text-danger"></small>
                        </div>   
                    </div>
        
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="add_new_inventory_close">Close</button>
                <button type="button" class="btn btn-primary" onclick="add_new_inventory()">Save changes</button>
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
  <form class="form-inline" method="post" action="{{route('inventory.index')}}">
      @csrf
      
      <div class="form-group">
        <div class="input-group">
            <div class="input-group-text"> 
                <input onclick="mobile_view()" type="checkbox">
            </div> 
            <div class="input-group-prepend">
                <span class="input-group-text" id="inputGroup-sizing-sm">Mobile View</span>
            </div>
            
            <input class="input-group-text" type="search" name="search_product" placeholder="Product SKU / Name / Category " aria-label="Search">
            <select class="custom-select" aria-label="Default select example" name="order_status">
                <option selected value ="">Select By</option>
                <option value="all">All</option>
                <option value="stock_status">Stock Status</option>
                <option value="stock_type">Stock Type</option>
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
            
            <input class="input-group-text" type="date" name="date_from" id="date_from">
            <input class="input-group-text" type="date" name="date_to" id="date_to">
            <div class="input-group-append">
                <button class="btn btn-outline-secondary" type="submit">Search</button>
            </div>
            <div class="input-group-append">
            <button class="btn btn-outline-secondary" id="createinventorymodalbtn" type="button">Add New inventory</button>
            </div>
        </div>
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
                <th scope="col">Inv.id</th>
                <th scope="col">Prd.Id</th>
                <th scope="col">SKU</th> 
                <th scope="col">category</th> 
                <th scope="col">Name</th>
                <th scope="col">sale</th> 
                <th scope="col">Weight</th>
                <th scope="col">Refence id</th>
                <th scope="col">Type</th>
                <th scope="col">Status</th>
                <th scope="col">QTY</th>
                <th scope="col">Inv.cost</th>
                <th scope="col">Inv.sale</th>  
            </tr>
        </thead>
        <tbody>  
            <?php $count=1;?>
            @foreach($inventories as $inventory)
            
            <tr>
                <td ><input type="checkbox" id="order_checkbox" class="order_checkbox_class" name="order_checkbox" onclick="get_checked_values()" value="{{$inventory->iid}}"></td>
                
                <td scope="row"><?=$count?></td> 
                
                <td>
                    <div class="btn-group" role="group">
                        <button id="btnGroupDrop1" type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                          Actions
                        </button>
                        <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">            
                            <a type="button" target="_blank" href="{{route('inventory.edit',$inventory->iid)}}" class="dropdown-item">Edit</a>  
                        </div>
                     </div>
                </td>
                <td>{{$inventory->iid}}</td>
                <td>{{$inventory->pid}}</td>
                <td>{{$inventory->sku}}</td>
                <td>{{$inventory->category}}</td>
                <td>{{$inventory->pname}}</td>
                <td>{{$inventory->psale}}</td>
                <td>{{$inventory->weight}}</td>
                <td>{{$inventory->reference_id}}</td>
                <td>{{$inventory->type}}</td>
                <td>{{$inventory->status}}</td>
                <td>{{$inventory->qty}}</td>
                <td>{{$inventory->icost}}</td>
                <td>{{$inventory->isale}}</td> 
                
                
            </tr>
            <?php $count++;?>
            @endforeach
        </tbody>
        
    </table>
</div>
<!--{{ $inventories->links() }}-->
{!! $inventories->appends(Request::all())->links() !!}

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