
<html xmlns="http://www.w3.org/1999/xhtml"><head><style>
<?php require 'vendor/autoload.php';?>
table, td, th {
    /*border: 1px solid black;*/
	font-family:Calibri;
}
td
{ 
    text-align: center!important;
    border-bottom: 1px solid black
}
td h5
{
     
    text-align: left!important;
    border: none!important;
    font-size: 20px;
}
td h4
{
     
    text-align: right!important;
    border: none!important;
        font-size: 20px;
}
table
{
    width: 89mm;
    border: 1px solid black;
}
    
    body {
  width: 230mm;
  height: 100%;
  margin: 0 auto;
  padding: 0;
  font-size: 12pt;
  background: rgb(204,204,204); 
}
* {
  box-sizing: border-box;
  -moz-box-sizing: border-box;
}
.main-page {
    width: 100mm;
    min-height: 297mm;
    margin: 10mm auto;
    background: white;
    box-shadow:
}
.sub-page {
    padding: 20px;
    float: left;
    height: auto;
    page-break-after: always;
    margin-bottom: 30px;
}

tfoot tr td h5 {
    float: left;
    font-size: 20px;
    margin-left: 5px;
}

td:first-child {
    width: 126px;
}

tfoot tr td input {
    float: left; 
    margin-top: 5px;
}
.label1 th h5, .label1 td h4, .label1 td h5
{
    font-size:20px;
    padding:5px 0;
}
.label1
{
    height: auto!important;
    
}
.label2 th h5, .label2 td h4, .label2 td h5
{
    font-size:25px;
    word-break: break-all;
    padding:0;
}
.label2 
{
    height: auto!important;
}
.barcode-text
{
    font-size:25px;
}

@page {
  size: A4;
  margin: 0;
}
@media print {
  html, body {
    width: 210mm;
    height:297mm ;        
  }
  .main-page {
    margin: 0;
    border: initial;
    border-radius: initial;
    width: initial;
    min-height: initial;
    box-shadow: initial;
    background: initial;
    page-break-after: always;
  }
}

.black_list
{ 
    text-align: center !important;
    width: 100%;
    font-weight: bold;
    font-size: 73px !important;
}

</style>

<script>
    function getdate(id)
    {
        n =  new Date();
        y = n.getFullYear();
        m = n.getMonth() + 1;
        d = n.getDate();
        document.getElementById("date").innerHTML = m + "/" + d + "/" + y;
        
    }
</script>
    </head>
    
    <body>
        
        <div class="main-page">
             
        <?php $count=1;?>
        <?php
            $date = new DateTime();
            $date->setTimezone(new DateTimeZone('UTC'));
            $date->setTimezone(new DateTimeZone('Asia/Karachi'));
            
            $current_date = $date->format('Y-m-d H:i:s');
            //echo $date->format('Y-m-d H:i:s');
            // Will print 2011-02-16 16:24:04
        ?>
         
        
        @foreach($ManualOrders as $ManualOrder) 
            <div class="sub-page">  
        
                <table>
                    <tbody>
                        <tr> 
                            @php
                                $generatorPNG = new Picqer\Barcode\BarcodeGeneratorPNG();
                            @endphp
                                   
                              
                            <td colspan="2" style="padding: 10px;"> 
                                <!--<img src="data:image/png;base64,-->
                                <?php 
                                // echo DNS1D::getBarcodeSVG((string)$ManualOrder->id, 'C39') 
                                
                                ?>
                                <!--alt="barcode" width="200"  />-->
                                <img src="data:image/png;base64,{{ base64_encode($generatorPNG->getBarcode($ManualOrder->id, $generatorPNG::TYPE_CODE_128)) }}" style="width:80%">
                    			<span class="notranslate barcode-text"><h3>{{$ManualOrder->id}}</h3></span></tr>
                            </td> 
                            
                        @if($clc[0]['customer_status'] == 'Black List')
                        
                        <!--<tr class="label2">-->
                            
                        <!--        <td colspan=2 ><h5 class="black_list">{{$clc[0]['customer_status']}}</h5></td>-->
                            
                              
                        <!--</tr>-->
                        
                        <!--<tr class="label2">-->
                        <!--    <td colspan=2 ><h5 >Dispatched: {{$clc[0]['do']}}, Returned:{{$clc[0]['ro']}}</h5></td>-->
                        <!--</tr>-->
                        
                        @endif
                        
                        <tr class="label2">
                            <td><h5>Name: </h5></td>
                            <td><h4>{{$ManualOrder->customers_id}} {{$ManualOrder->receiver_name}}</h4></td> 
                        </tr>
                        <tr class="label2">
                            <td><h5>Number: </h5></td>
                            <td ><h4>{{$ManualOrder->receiver_number}}</h4></td> 
                        </tr>
                        <tr class="label2">
                            <td><h5>Address: </h5></td>
                            <td><h4>{{$ManualOrder->reciever_address}}</h4></td> 
                        </tr>
                        <tr class="label2">
                            <td><h5>City: </h5></td>
                            <td ><h4>@if($ManualOrder->leopord_cities != null) : {{$ManualOrder->leopord_cities->name}} @endif</h4></td> 
                        </tr>
                        <tr class="label1">
                            <td><h5>Pieces: </h5></td>
                            <td ><h4>{{$ManualOrder->total_pieces}}</h4></td> 
                        </tr>
                        <tr class="label1">
                            <td><h5>Weight: </h5></td>
                            <td><h4>{{$ManualOrder->weight}}</h4></td> 
                        </tr>
                        <tr class="label1">
                            <td><h5>Crtd Date: </h5></td>
                            <td ><h4>{{$ManualOrder->created_at}}</h4></td> 
                        </tr>
                        <tr class="label1">
                            <td><h5>Updt Date: </h5></td>
                            <td ><h4>{{$ManualOrder->updated_at}}</h4></td> 
                        </tr>
                        <tr  class="label1">
                            <td><h5>Prnt Date: </h5></td>
                            <?php
                                $date = new DateTime();
                                $date->setTimezone(new DateTimeZone('UTC'));
                                $date->setTimezone(new DateTimeZone('Asia/Karachi'));
                                
                                $current_date = $date->format('Y-m-d H:i:s');
                            ?> 
                            <td><h4>{{$current_date}}</h4></td> 
                        </tr>
                        <tr class="label1">
                            <td><h5>Crtd By: </h5></td>  
                            <td><h4>@if($ManualOrder->users->first_name){{$ManualOrder->users->first_name}}@endif</h4></td> 
                        </tr>
                        <tr class="label1">
                            <td><h5>Updt By: </h5></td>  
                            <td><h4>@if($ManualOrder->UsersUpdatedBy->first_name){{$ManualOrder->UsersUpdatedBy->first_name}}@endif</h4></td> 
                        </tr>
                        <tr class="label1">
                            <td><h5>Assigned To: </h5></td>  
                            <td><h4>@if($ManualOrder->AssignTo->first_name){{$ManualOrder->AssignTo->first_name}}@endif</h4></td> 
                        </tr>
                        <tr class="label1">
                            <td><h5>Printed By: </h5></td>
                            <td ><h4>{{Auth::user()->first_name}}</h4></td> 
                        </tr>
                    </tbody>
                    <tfoot> 
                        <!--<tr>-->
                        <!--    <td style="height:40px"> <input type="checkbox"> <h5>Pending </h5></td>-->
                        <!--    <td> </td> -->
                        <!--</tr>-->
                        <!--<tr>-->
                        <!--    <td style="height:40px"> <input type="checkbox"> <h5>Prepared </h5></td>-->
                        <!--    <td> </td> -->
                        <!--</tr>-->
                        <!--<tr>-->
                        <!--    <td style="height:40px"> <input type="checkbox"> <h5>Incomplete </h5></td>-->
                        <!--    <td> </td> -->
                        <!--</tr>-->
                        <!--<tr>-->
                        <!--    <td style="height:40px"> <input type="checkbox"> <h5>Not.Resp </h5></td>-->
                        <!--    <td> </td> -->
                        <!--</tr>-->
                        <!--<tr>-->
                        <!--    <td style="height:40px"> <input type="checkbox"> <h5>Hold </h5></td>-->
                        <!--    <td> </td> -->
                        <!--</tr>-->
                        <!--<tr>-->
                        <!--    <td style="height:40px"> <input type="checkbox"> <h5>Confirmed </h5></td>-->
                        <!--    <td> </td> -->
                        <!--</tr>  -->
                        <tr >
                            <td colspan="2" style="height:40px;"><h5 style="width:100%;text-align:center!important;">Payment Details </h5></td>
                           
                        </tr> 
                        <tr>
                            <td style="height:70px"> <h5>Product Price </h5></td>
                            <td> </td> 
                        </tr>
                        <tr>
                            <td style="height:70px"> <h5>Delivery Charges </h5></td>
                            <td> </td> 
                        </tr>   
                        <tr>
                            <td style="height:70px"> <h5>Packaging cost </h5></td>
                            <td> </td> 
                        </tr> 
                        <tr>
                            <td style="height:70px"><h5>Advance </h5></td>
                            <td> </td> 
                        </tr>  
                        <tr>
                            <td style="height:70px"> <h5>Amount </h5></td>
                            <td> </td> 
                        </tr> 
                        <tr ><td colspan="2" style="height:300px;text-align: left!important;vertical-align: top;padding: 11px;font-weight: bold;">Note:</td></tr>
                    </tfoot>
                </table>
                <center><h5>Brandhub</h5></center>
             
            
             
            </div>  
        @endforeach
      </div>
    
    
    
    
    
    
    <title>
    
    </title>
        <style type="text/css">
            * {
                padding: 0;
                margin: 0;
            }
    
            td {
                text-align: left;
                padding-left: 10px;
                border-color: Black;
            }
    
            .alignment {
                text-align: left;
            }
        </style>
    
        <style type="text/css" media="print">
            /*@media print {
    
                a[href]:after {
                    content: none !important;
                }
    
                @page {
                    margin-top: 5px;
               margin-bottom: 80px; 
                    /*margin: 10px 37px 10px 37px;
                }
    
                body {
                    padding-top: 10px;
                    padding-bottom: 72px;
                }
            }*/
            @page {
                margin-top: 4mm;
                margin-bottom: 1mm;
            }
    
            body {
                padding-top: 10px;
                padding-bottom: 72px;
            }
        </style>
        
    
    
    
        <!--<form method="post" action="./ViewAddressLabelB_HTML_old.aspx?con=5LlbUnaz9EiyOMF3LKl2zg%3d%3d&amp;htmlprinttype=0" id="form1">-->
        <!--    <div class="aspNetHidden">-->
        <!--        <input type="hidden" name="__VIEWSTATE" id="__VIEWSTATE" value="/wEPDwUIOTEyNDUzNDZkZF/pC7oNRB5Mc3NYDyMRE4UYoTpQPMGAp+Y8Ljcg/NqH">-->
        <!--    </div>-->
    
        <!--    <div class="aspNetHidden"> -->
        <!--    	<input type="hidden" name="__VIEWSTATEGENERATOR" id="__VIEWSTATEGENERATOR" value="90E36213">-->
        <!--    </div>-->
            
        <!--<div>-->
                
        <!--    </div>-->
        <!--</form>-->
    
    
    </body>
    <script>
    window.print();
    </script>
</html>
