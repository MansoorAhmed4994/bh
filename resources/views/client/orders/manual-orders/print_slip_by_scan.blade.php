 
@extends('layouts.'.Auth::getDefaultDriver())

@section('content')
 
<script type="text/javascript">
        var base_url = '<?php echo e(url('/')); ?>';
        var row_id="1";
        var total_amount=0;
        var total_parcels=0;
        var total_discount=0;
        
       function checkAll(bx) {
          var cbs = document.getElementsByTagName('input');
          for(var i=0; i < cbs.length; i++) {
            if(cbs[i].type == 'checkbox') {
              cbs[i].checked = bx.checked;
            }
          }
        }
        
        function change_amount() {
            products = document.getElementsByName('product_ids[]');
            discounts = document.getElementsByName('discounts[]');
            sale = document.getElementsByName('sale[]');
            qty = document.getElementsByName('qty[]');
            net_amount = document.getElementsByName('net_amount[]');
            var a=0;
            var b=0;
            var c=0;
            var q=0;
            var n=0;
            console.log(sale[0].value);
            for(var x=0;x<products.length;x++){
                a += parseInt(sale[x].value);
                b += parseInt(discounts[x].value); 
                q += parseInt(qty[x].value); 
                net_amount[x].value = (sale[x].value-discounts[x].value)*qty[x].value;
                n += parseInt(net_amount[x].value); 
                c +=q;
            }
             $('#total_amount').val(a);
            $('#total_discount').val(b);
            $('#total_parcels').val(c);
            $('#total_net_amount').val(n);
            
        }
        function change_discount(price,discount)
        { 
             
            net = total_amount-parseInt(price); 
            $('#total_amount').html(total_amount);
            $('#total_parcels').html(total_parcels);
            $('#field_total_amount').val(total_amount);
            $('#field_total_parcels').val(total_parcels);
        }
        
        function change_price(price)
        {
            //total_parcels--;
            total_amount = total_amount-parseInt(price); 
            $('#total_amount').html(total_amount);
            $('#total_parcels').html(total_parcels);
            $('#field_total_amount').val(total_amount);
            $('#field_total_parcels').val(total_parcels);
        }
        
        function removeElementsByClass(className){
            const elements = document.getElementsByClassName(className);
            while(elements.length > 0){
                elements[0].parentNode.removeChild(elements[0]);
            }
        }
        
        function PrintElem()
        {
            removeElementsByClass('delete_btn_class');
            var mywindow = window.open('', 'PRINT', 'height=400,width=600');
        
            mywindow.document.write('<html><head><title>' + document.title  + '</title>');
            mywindow.document.write('<head><style></style><link href="{{ asset("public/css/app.css") }}" rel="stylesheet"><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous"></head><body >');
            mywindow.document.write('<h1>Load Sheet</h1>');
            mywindow.document.write(document.getElementById('print_loadsheet').innerHTML);
            mywindow.document.write('</body></html>');
        
            mywindow.document.close(); // necessary for IE >= 10
            mywindow.focus(); // necessary for IE >= 10*/
        
            mywindow.print();
            //mywindow.close();
        
            return true;
        }

        function delete_row(id,price)
        {
            var row = document.getElementById(id);
            row.parentNode.removeChild(row);
            total_parcels--;
            total_amount = total_amount-parseInt(price); 
            $('#total_amount').val(total_amount);
            $('#total_parcels').val(total_parcels); 
            
        }

        
        $( document ).ready(function() {
            
            $('#generate_slip').on('click',function(e)
            { 
              
                    // $("body").addClass("loading"); 
                    //console.log($('#load_sheet_form').serialize());
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: base_url + '/client/orders/generate/scan/slip',
                    type: 'POST',
                    data: $('#generate_slip_form').serialize(),
                    dataType: 'json',
                    success: function(e)
                    { 
                        if(typeof(e.status) != 'undefined')
                        {
                            if(e.status == '1')
                            {
                                PrintElem();
                            }
                            else
                            {
                                $("body").removeClass("loading");
                                alert(e.messege);
                            }
                        }
                        else
                        {
                             alert('Some thing went wrong');
                        }
                        
                        $("body").removeClass("loading");
                    },
                    error: function(e) {
                        console.log(e.messege);
                    }
                }); 
            });
        });
        



        function order_get_dispatch() 
        { 
            var id = document.getElementById('product_id').value;
            document.getElementById('total_parcels').value = total_parcels;
            document.getElementById('total_amount').value = total_amount;
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: base_url + '/client/orders/get_product_details/'+id,
                type: 'GET',
                dataType: 'json',
                success: function(e)
                {
                    console.log(e.messege.inventory_id);
                    if(e.messege != 'no order found')
                    {
                        var price = 0;
                        if($.isNumeric(parseInt(e.messege.price)))
                        {
                            price = e.messege.sale
                            total_amount += parseInt(e.messege.sale);
                            $('#total_amount').html(total_amount);
                        }
                        total_parcels++;
                        $('#total_parcels').val(total_parcels);
                        var row_data = '<tr id="'+row_id+'"><td class="delete_btn_class"><button type="button" class="btn btn-danger " onclick="delete_row('+row_id+','+price+')">Delete</button></td><td class="delete_btn_class"><input type="checkbox" value="'+e.messege.id+'" name="product_ids[]" checked></td><td class="delete_btn_class"><input type="text" value="'+e.messege.inventory_id+'" name="inventory_ids[]" readonly></td><td class="delete_btn_class"><input type="text" value="'+e.messege.sku+'" name="product_skus[]" readonly></td><td>'+e.messege.name+'</td><td><input tye="text" value="'+e.messege.sale+'" name="sale[]" readonly></td><td><input tye="text" onkeyup="change_amount()" value="0" name="discounts[]"></td><td><input tye="text" onkeyup="change_amount()" value="1" name="qty[]"></td><td><input tye="text" onkeyup="change_amount()" value="'+(e.messege.sale)+'" name="net_amount[]"></td></tr>';
                        $("#row_data").prepend(row_data);
                        row_id++;
                        $("body").removeClass("loading");
                        document.getElementById('product_id').value = '';
                        
                        
                    }
                    else
                    {
                        alert('no record found');
                        $("body").removeClass("loading");
                    }
                    change_amount();
                    //cosole.log(e.messege);
                },
                error: function(e) {
                    console.log(e.messege);
                }
            });
            
        }
        
        function get_order_details() 
        { 
            var id = document.getElementById('order_id').value;
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: base_url + '/client/orders/ManualOrders/get-order-details/'+id,
                type: 'GET',
                dataType: 'json',
                success: function(e)
                {
                    console.log(e);
                    $('#order_name').val(e.messege.receiver_name);
                    $('#order_number').val(e.messege.receiver_number);
                    $('#order_address').val(e.messege.reciever_address); 
                    
                    //cosole.log(e.messege);
                },
                error: function(e) {
                    console.log(e.messege);
                }
            });
            
        }
        
        
        $( document ).ready(function() {
            
            $('#add_dispatch_order_btn').on('click',function(e)
            {  
                    order_get_dispatch();
            });
            
            $('#order_id').on('keypress',function(e)
            {  
                
                if (e.which == 13) {
                    get_order_details();
                }
            });
            
          
            
            $('#product_id').on('keypress',function(e)
            {  
                
                if (e.which == 13) {
                    order_get_dispatch();
                }
            });
        });
        
    </script>
    <style>  
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
                <h2>Dispatch Details</h2> 
            </div>
        </div>
    </div>
    
    <div class="row"> 
         
        @if(Session::has('order_placed_message'))
            <div class="alert alert-success" role="alert">
                {{session()->get('order_placed_message')}}
            </div> 
        @endif 
        
        <form id="generate_slip_form">
            <div class="row col-sm-12">
                <div class="col-sm-3"> 
                    
                    <div class="col-sm-12">        
                        <div class="col-sm-12">
                            <div class="input-group mb-3">  
                                <input type="text" class="form-control " id="order_id" placeholder="Enter Order ID" name="order_id" >
                                <div class="input-group-append">
                                    <button type="button" id="search_order_details" class="btn input-group-text" >Search Order Details</button>
                                </div>
                            </div>
                        </div>        
                        <div class=" col-sm-12">
                            <label>Name</label>
                            <input type="text" class="form-control" id="order_name"  name="order_name" readonly> 
                        </div>        
                        <div class=" col-sm-12">
                            <label>Number</label>
                            <input type="text" class="form-control" id="order_number"  name="order_number" readonly> 
                        </div>        
                        <div class=" col-sm-12">
                            <label>Address</label>
                            <input type="text" class="form-control" id="order_address"  name="order_address" readonly> 
                        </div>     
                        <div class=" col-sm-12">
                            <label>Total Article</label>
                            <input type="text" class="form-control" id="total_parcels"  name="total_parcels" readonly> 
                        </div>    
                        <div class=" col-sm-12">
                            <label>Total price</label>
                            <input type="text" class="form-control" id="total_amount"  name="total_amount" readonly> 
                        </div>    
                        <div class=" col-sm-12">
                            <label>Discount</label>
                            <input type="text" class="form-control" id="total_discount"  name="total_discount" readonly> 
                        </div>    
                        <div class=" col-sm-12">
                            <label>Net Amount</label>
                            <input type="text" class="form-control" id="total_net_amount"  name="total_net_amount" readonly> 
                        </div> 
                           
                        <div class=" col-sm-12"> <br>
                            <input type="button" class="btn btn-primary" id="generate_slip"  name="generate_slip" value="Save"> 
                        </div>
                        
                    </div>
                </div>
                
                <div class="col-sm-9" id="print_loadsheet">
                    
                    <div class="d-flex justify-content-center">
                        <div class="form-group ">
                            <input type="text" class="form-control" id="product_id" placeholder="Enter Id OR scan Barcode" name="product_id" >
                        </div>   
            
                        <div class="form-group">
                            <button type="button" id="add_scan_product" class="btn btn-primary" >Add Order</button>
                        </div>
                        
                    </div>
                    
                    <table class="table table-bordered"> 
                        <thead> 
                            <tr>
                                <th scope="col" class="delete_btn_class">#</th>
                                <th scope="col"  class="delete_btn_class"><input type="checkbox" onclick="checkAll(this)" ></th>
                                <th scope="col">Inventory ID</th>
                                <th scope="col">SKU</th>
                                <th scope="col">Name</th> 
                                <th scope="col">price</th>
                                <th scope="col">Discount</th> 
                                <th scope="col">qty</th>
                                <th scope="col">Net Amount</th>
                            </tr>
                        </thead>
                        <tbody id="row_data">
                              
                        </tbody>
                        
                    </table>
                </div>
            </div>  
        </form>
        
    </div>
    
    
     
  @endsection
