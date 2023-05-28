
@extends('layouts.'.Auth::getDefaultDriver())

@section('content')
 <head>
<script type="text/javascript">

        var row_id="1";
        
       
        
    $( document ).ready(function() {
        
        
         
        
        $('#add_product_btn').on('click',function(e)
        {  
            // alert('wo');
                get_products_by_sku();
        });
        
      
        
        $('#sku_number').on('keypress',function(e)
        {  
            
            if (e.which == 13) {
                get_products_by_sku();
            }
        });
        });
        
    function delete_row(id,inventory_id)
    {
        $("body").addClass("loading");
        var row = document.getElementById(id);
        row.parentNode.removeChild(row);
        
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: base_url + '/admin/inventory/deletcustomerproduct/'+inventory_id,
            type: 'GET', 
            dataType: 'json',
            success: function(e)
            { 
                $('#price').val(e.price);
                
                $("#total_amount").html(e.price);
                
                $("body").removeClass("loading");
            },
            error: function(e) {alert(e); 
                $("body").removeClass("loading");
            }
        });
        
    }
    function get_products_by_sku() 
    {
        $("body").addClass("loading"); 
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: base_url + '/admin/inventory/getproduct',
            type: 'post',
            data: $('#update_form').serialize(),
            dataType: 'json',
            success: function(e)
            { 
                if(e.error == 0)
                {
                    var row_data='';
                    //console.log(e.inventory);
                    var total_amount = 0;
                    var total_products = 0;
                    $('#price').val(e.price);
                    for(var i=0; i< e.inventory.length; i++)
                    {
                        total_products++;
                        total_amount+=parseInt(e.inventory[i].sale);
                        row_data += '<tr id="'+row_id+'"><td class="delete_btn_class"><button type="button" class="btn btn-danger " onclick="delete_row('+row_id+','+e.inventory[i].id+')">Delete</button></td><td>'+e.inventory[i].name+'</td><td>'+e.inventory[i].sale+'</td></tr>';
    
                    row_id++;
                    }
                    total_amount += 250;
                    $("#row_data").html(row_data);
                    $("#total_products").html(total_products);
                    $("#total_amount").html(total_amount);
                    $("body").removeClass("loading");
                }
                else
                {
                    alert('no product found');
                    $("body").removeClass("loading");
                }
                    // document.getElementById('order_id').value = '';
                
                //cosole.log(e.messege);
            },
            error: function(e) {
                alert(e); 
                $("body").removeClass("loading");
            }
        });
            
        }
    function onchangeprice()
    {
        
        let price = document.getElementById("price").value; 
        let advance_payment = document.getElementById("advance_payment").value;
        let cod_amount = (price-advance_payment);
        document.getElementById("cod_amount").value = cod_amount;
        // alert(cod_amount);
    } 
    
    var base_url = '<?php echo e(url('/')); ?>'; 
 

    </script>
    <style>  
        /*input[type=file] {*/
        /*position: absolute;*/
        /*font-size: 50px;*/
        /*opacity: 0; */
        /*right: 0;*/
        /*top: 0;*/
        /*}*/

    </style>  
</head>
    
    <div class="container"> 
         
        @if(Session::has('order_placed_message'))
            <div class="alert alert-success" role="alert">
                {{session()->get('order_placed_message')}}
            </div> 
        @endif

        <form action="{{ route('inventory.pos') }}" id="update_form"  enctype="multipart/form-data" method="post">
            @csrf
             
            <div class="container">
                
                <div class="d-flex justify-content-start">
                    <div class="form-group col-sm-3">
                        <input type="text" class="form-control" id="sku_number" placeholder="Enter SKU" name="sku_number" >
                    </div>   
        
                    <div class="form-group">
                        <button type="button" id="add_product_btn" class="btn btn-primary" >Add Order</button>
                    
                    </div>
                </div>
            </div>
            <table class="table table-bordered">
                    
                    <thead>
                        
                        <tr>
                            <th scope="col" class="delete_btn_class">#</th> 
                            <th scope="col">Product ID</th>
                            <th scope="col">Product Name</th> 
                        </tr>
                    </thead>
                    <tbody id="row_data">
                        
                    </tbody>
                    <tbody id="row_data">
                    </tbody>
                </table>
                
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
