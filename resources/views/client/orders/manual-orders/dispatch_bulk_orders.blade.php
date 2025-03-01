 
@extends('layouts.'.Auth::getDefaultDriver())

@section('content')
 
 
<script type="text/javascript">
        var base_url = '<?php echo e(url('/')); ?>';
        var row_id="1";
        var total_amount=0;
        var total_parcels=0;
        const order_ids = [];
        
       function checkAll(bx) {
          var cbs = document.getElementsByTagName('input');
          for(var i=0; i < cbs.length; i++) {
            if(cbs[i].type == 'checkbox') {
              cbs[i].checked = bx.checked;
            }
          }
        }
        
  
        function change_cod_amount(cod_amount)
        {
            //total_parcels--;
            total_amount = total_amount-parseInt(cod_amount); 
            $('#total_amount').html(total_amount);
            $('#total_parcels').html(total_parcels);
            $('#field_total_amount').val(total_amount);
            $('#field_total_parcels').val(total_parcels);
        }
        
        function removeElementsByClass(className)
        {
            const elements = document.getElementsByClassName(className);
            while(elements.length > 0)
            {
                elements[0].parentNode.removeChild(elements[0]);
            }
        }


        
        function PrintElem()
        {
            removeElementsByClass('delete_btn_class');
            var mywindow = window.open('', 'PRINT', 'height=800,width=1080');
        
            mywindow.document.write('<html>'); 
            mywindow.document.write('<title>' + document.title  + '</title>'); 
            mywindow.document.write('<link href="{{ asset("public/css/app.css") }}" rel="stylesheet"/>'); 
            mywindow.document.write('<body>');
            mywindow.document.write('<h1>Load Sheet</h1>');
            mywindow.document.write(document.getElementById('print_loadsheet').innerHTML);
            mywindow.document.write('</body></html>');
        
            mywindow.document.close(); // necessary for IE >= 10
            mywindow.focus(); // necessary for IE >= 10*/
        
            mywindow.print();
            //mywindow.close();
        
            return true;
        }

        function delete_row(id,cod_amount)
        {
            var row = document.getElementById(id);
            row.parentNode.removeChild(row);
            total_parcels--;
            total_amount = total_amount-parseInt(cod_amount); 
            $('#total_amount').html(total_amount);
            $('#total_parcels').html(total_parcels);
            $('#field_total_amount').val(total_amount);
            $('#field_total_parcels').val(total_parcels);
            
        }

        
        $( document ).ready(function() {
            
            $('#print_loadsheet_btn').on('click',function(e)
            { 
                if($("#riders").val() == "select rider") {
                    alert("Please Select Rider");
                }
                else
                {
                    $("body").addClass("loading"); 
                    //console.log($('#load_sheet_form').serialize());
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: base_url + '/riders/generate-loadsheet',
                        type: 'POST',
                        data: $('#load_sheet_form').serialize(),
                        dataType: 'json',
                        success: function(e)
                        { 
                            if(typeof(e.success) != 'undefined')
                            { 
                                    var today = new Date();
                                    var dd = String(today.getDate()).padStart(2, '0');
                                    var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
                                    var yyyy = today.getFullYear();
                                    
                                    today = mm + '/' + dd + '/' + yyyy; 
                                    document.getElementById("print_date").innerHTML = today;
                                    PrintElem();
                                    $("body").removeClass("loading");
                                
                            }
                            if(typeof(e.error) != 'undefined')
                            
                            {
                                
                                 alert(e.messege);
                                 $("body").removeClass("loading");
                            }
                            
                            $("body").removeClass("loading");
                        },
                        error: function(e) {
                            alert(e.messege);
                            $("body").removeClass("loading");
                        }
                    });
                }   
            });
        });
        



        function order_get_dispatch() {
            
            var id = document.getElementById('order_id').value;
            if(id == '')
            {
                return;
            }
            
            if(jQuery.inArray(id, order_ids) !== -1)
            {
                alert('Already exist');
                document.getElementById('order_id').value = '';
                return
            }
            
                $("body").addClass("loading"); 
                
                    var id = document.getElementById('order_id').value;
                    document.getElementById('total_parcels').value = total_parcels;
                    document.getElementById('total_amount').value = total_amount;
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: base_url + '/client/orders/ManualOrders/get-order-details/'+id,
                        type: 'GET',
                        dataType: 'json',
                        success: function(e)
                        {
                            if (typeof e.error !== 'undefined') 
                            {
                                alert(e.messege);
                                $("body").removeClass("loading");
                                return;
                            } 
                            if(e.messege != 'no order found')
                            {
                                var cod_amount = 0;
                                if($.isNumeric(parseInt(e.messege.cod_amount)))
                                {
                                    cod_amount = e.messege.cod_amount
                                    total_amount += parseInt(e.messege.cod_amount);
                                    $('#total_amount').html(total_amount);
                                }
                                total_parcels++;
                                $('#total_parcels').html(total_parcels);
                                var row_data = '<tr id="'+row_id+'">';
                                row_data += '<td class="delete_btn_class"><button type="button" class="btn btn-danger " onclick="delete_row('+row_id+','+cod_amount+')"><i class="bi bi-trash"></i></button></td>';
                                row_data += '<td class="delete_btn_class"><input type="checkbox" value="'+e.messege.id+'" name="order_ids[]" checked></td>';
                                row_data += '<td>'+e.messege.id+'</td>';
                                row_data += '<td>'+e.messege.receiver_name+'</td>';
                                row_data += '<td>'+e.messege.receiver_number+'</td>';
                                row_data += '<td>'+e.messege.reciever_address+'</td>';
                                row_data += '<td>'+e.messege.product_price+'</td>';
                                row_data += '<td>'+e.messege.advance_payment+'</td>';
                                row_data += '<td>'+e.messege.dc+'</td>';
                                row_data += '<td><input tye="hidden" onkeyup="change_cod_amount(this.value)" value="'+cod_amount+'" name="cod_amount[]" id="total_amount" readonly></td>';
                                row_data += '<td>'+e.messege.status+'</td><td style="border: 2px solid black;"></td>';
                                row_data += '</tr>';
                                
                                $("#row_data").prepend(row_data);
                                row_id++;
                                $("body").removeClass("loading");
                                document.getElementById('order_id').value = '';
                                 
                                order_ids.push(id); 
                                
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
        <div class="d-flex justify-content-start">
            <div class="form-group col-sm-3">
                <input type="text" class="form-control" id="order_id" placeholder="Enter Id OR scan Barcode" name="order_id" >
            </div>   

            <div class="form-group">
                <button type="button" id="add_dispatch_order_btn" class="btn btn-primary" >Add Order</button>
            
            </div>
        </div>
        
        <form id="load_sheet_form">
            
            <input type="hidden" name="total_parcels" id="field_total_parcels">
            <input type="hidden" name="total_amount" id="field_total_amount">
            <div class="d-flex justify-content-end">     
    
                <div class="form-group col-sm-3">
                    <select class="custom-select" id="riders"  name="riders" >
                        <option value="select rider" selected>Select Riders</option>
                        @foreach($riders as $rider)
                        <option value="{{$rider->id}}">{{$rider->id}} {{$rider->first_name}}</option>
                        @endforeach
                    </select>
                </div>   
    
                <div class="form-group">
                
                    <button type="button" id="print_loadsheet_btn"class="btn btn-danger" >Print LoadSheet</button>
                </div>
                
            </div>
            
            
            <div class="row" id="print_loadsheet">
                
    
                <!--<div class="col-sm-3"><h4><lable>Total Parcels: <span class="badge badge-secondary" id="total_parcels"></span></lable></h4></div>-->
                <!--<div class="col-sm-3"><h4><lable>Total Amount: <span class="badge badge-secondary" id="total_amount"></span></lable></h4></div>-->
                <table class="table table-bordered">
                    
                    <thead>
                        <tr>
                            <th scope="col" colspan="3"><h4><lable>Total Parcels: <span class="badge badge-secondary" id="total_parcels"></span></lable></h4></th>
                            <th scope="col" colspan="3"><h4><lable>Total Amount: <span class="badge badge-secondary" id="total_amount"></span></lable></h4></th>
                            <th  scope="col" colspan="2"><h4><lable>Printed on: <span class="badge badge-secondary" id="print_date"></span></lable></h4></th>
                        </tr>
                        <tr>
                            <th scope="col" class="delete_btn_class">#</th>
                            <th scope="col" class="delete_btn_class"><input type="checkbox" onclick="checkAll(this)" ></th>
                            <th scope="col">id</th>
                            <th scope="col">Name</th>
                            <th scope="col">number</th>
                            <th scope="col">Address</th>
                            <th scope="col">Price</th>
                            <th scope="col">Advance</th>
                            <th scope="col">dc</th>
                            <th scope="col">COD</th>
                            <th scope="col">status</th>
                            <th scope="col">Cus. Sign</th>
                        </tr>
                    </thead>
                    <tbody id="row_data">
                          
                    </tbody>
                    <tbody id="row_data">
                        <tr>
                            <td colspan="3" style="height:100px;vertical-align: middle;border:1px solid black">Rider Signature</td>
                            <td colspan="3" style="height:100px;vertical-align: middle;border:1px solid black"></td>
                        </tr>
                        <tr>
                            <td colspan="3" style="height:100px;vertical-align: middle;border:1px solid black">Manager Signature</td>
                            <td colspan="3" style="height:100px;vertical-align: middle;border:1px solid black"></td>
                        </tr>
                          
                    </tbody>
                </table>
            </div>
        </form>

            
                
        

    </div>
    
    
     
  @endsection
