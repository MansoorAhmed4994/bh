 
@extends('layouts.'.Auth::getDefaultDriver())

@section('content')
 
<script type="text/javascript">
// window.addEventListener( "pageshow", function ( event ) {
//   var historyTraversal = event.persisted || ( typeof window.performance != "undefined" && window.performance.navigation.type === 2 );
//   if ( historyTraversal ) {
//       $("body").addClass("loading");
      
//     // Handle page restore.
//     //alert('refresh');
//     window.location.reload();
//     $("body").addClass("loading");
//     //$("body").removeClass("loading");
//   }
// });


        var base_url = '<?php echo e(url('/')); ?>';
        var row_id="1";  
        var total_parcels=0;
        
       function checkAll(bx) {
          var cbs = document.getElementsByTagName('input');
          for(var i=0; i < cbs.length; i++) {
            if(cbs[i].type == 'checkbox') {
              cbs[i].checked = bx.checked;
            }
          }
        } 
        
        function removeElementsByClass(className){
            const elements = document.getElementsByClassName(className);
            while(elements.length > 0){
                elements[0].parentNode.removeChild(elements[0]);
            }
        } 

        function delete_row(id,price)
        {
             
            
            
            total_parcels--;
            var row = document.getElementById(id);
            row.parentNode.removeChild(row);  
            booking_btn_activate();
        } 
        
        function booking_btn_activate()
        {
            if(total_parcels < 1 )
            {
                $('#create_booking_btn').attr('disabled', true);
            }
            else
            {
                $('#create_booking_btn').removeAttr('disabled');
            }
        }
        
        function order_get_dispatch() {
            
                $("body").addClass("loading"); 
                
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
                            console.log(e.messege);
                            if(e.messege != 'no order found')
                            { 
                                var row_data = '<tr id="'+row_id+'"><td class="delete_btn_class"><button type="button" class="btn btn-danger " onclick="delete_row('+row_id+')">Delete</button></td><td class="delete_btn_class"><input type="checkbox" value="'+e.messege.id+'" name="order_ids[]" checked></td><td>'+e.messege.id+'</td><td>'+e.messege.receiver_name+'</td><td>'+e.messege.receiver_number+'</td><td>'+e.messege.reciever_address+'</td><td><input type="text" onkeyup="change_price(this.value)" value="'+e.messege.price+'" name="reciever_name[]" id="total_amount" readonly></td></td><td>'+e.messege.status+'</td><td style="border: 2px solid black;"></td></tr>';
                                $("#row_data").prepend(row_data);
                                row_id++;
                                $("body").removeClass("loading");
                                document.getElementById('order_id').value = '';
                                total_parcels++;
                                booking_btn_activate();
                            }
                            else
                            {
                                alert('no record found');
                                $("body").removeClass("loading");
                            }
                            
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
                    order_get_dispatch();
                }
            });
        });

        // $( document ).ready(function() {
            
        //     $('#add_new_image').on('change',function(e)
        //     {    
        //         // var formData = new FormData('#');
        //         // var formData = new FormData(document.querySelector('update_form'));
        //         var formData = $('#update_form').serializeArray().reduce(function(obj, item) {
        //             obj[item.name] = item.value;
        //             return obj;
        //         }, {});
        //         $.ajax({
        //             headers: {
        //                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //             },
        //             url: base_url + '/client/orders/ManualOrders/add-image',
        //             data:formData,
        //             type: 'POST',
        //             dataType: 'json',
        //             success: function(e)
        //             {
        //                 alert(e.messege);  
        //                 // $('#'+image_box_id).remove();
        //                 // alert(response);
        //             },
        //             error: function(e) {
        //                 console.log(e.responseText);
        //             }
        //         });
        //     });
        // });

        

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
    
    <div class="container"> 
         
        @if(Session::has('order_placed_message'))
            <div class="alert alert-success" role="alert">
                {{session()->get('order_placed_message')}}
            </div> 
        @endif 
        <div class="d-flex justify-content-center">
            <div class="form-group col-sm-3">
                <input type="text" class="form-control" id="order_id" placeholder="Enter Id OR Scan" name="order_id" >
            </div>   

            <div class="form-group">
                <button type="button" id="add_dispatch_order_btn" class="btn btn-primary" >Add Order</button>
            
            </div>
        </div>
        
        <form method="post" action="{{route('trax.create.bulk.booking')}}">
            @csrf
            <div class="row" id="print_loadsheet">
                
    
                <!--<div class="col-sm-3"><h4><lable>Total Parcels: <span class="badge badge-secondary" id="total_parcels"></span></lable></h4></div>-->
                <!--<div class="col-sm-3"><h4><lable>Total Amount: <span class="badge badge-secondary" id="total_amount"></span></lable></h4></div>-->
                <table class="table table-bordered">
                    
                    <thead>
                        <tr>
                        </tr>
                        <tr>
                            <th scope="col" class="delete_btn_class">#</th>
                            <th scope="col"  class="delete_btn_class"><input type="checkbox" onclick="checkAll(this)" ></th>
                            <th scope="col">id</th>
                            <th scope="col">Name</th>
                            <th scope="col">number</th>
                            <th scope="col">Address</th>
                            <th scope="col">price</th>
                            <th scope="col">status</th>
                            <th scope="col">Cus. Sign</th>
                        </tr>
                    </thead>
                    <tbody id="row_data">
                    </tbody>  
                </table>
                <div class="d-flex justify-content-center">
                    <button type="submit" id="create_booking_btn" class="btn btn-primary col-sm-3" disabled=true>Create</button>
                    
                </div> 
            </div>
        </form>

            
                
        

    </div>
    
    
     
  @endsection
