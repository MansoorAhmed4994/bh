
<html xmlns="http://www.w3.org/1999/xhtml"><head><style>
    table, td, th {
        border: 1px solid black;
		font-family:Calibri;
    }
td{
padding: 0px;
}
    table {
        border-collapse: collapse;
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
  width: 210mm;
  min-height: 297mm;
  margin: 10mm auto;
  background: white;
  box-shadow: 0 0 0.5cm rgba(0,0,0,0.5);
}
.sub-page {
  padding: 1cm;
  height: 1110px;
}
@page {
  size: A4;
  margin: 0;
}
@media print {
  html, body {
    width: 210mm;
    height: 297mm;        
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
        
        @if($count==1)
            <div class="sub-page"> 
        @endif
        
            
            <table border="1" cellpadding="0" cellspacing="0" width="710px" height="290px" style="border: 1px solid black;table-layout: fixed;font-size:10px;">
                <tbody>
                    
                    <tr>
                    	<td width="101px" style="padding:0px;"></td>
                    	<td width="101px" style="padding:0px;"></td>
                    	<td width="32px" style="padding:0px;"></td>
                    	<td width="101px" style="padding:0px;"></td>
                    	<td width="170px" style="padding:0px;"></td>
                    	<td width="202px" style="padding:0px;"></td>
                    </tr>
                    
                	<tr>
                		<td colspan="2" rowspan="2" style="padding:5px; text-align:center;">
                			<img src="https://brandhub.com.pk/wp-content/uploads/2021/09/ic_logo.png" alt="logo" width="125">
                		</td>
                		<td colspan="3" rowspan="2" style="text-align:center;padding:5px;font-size:12px;">
                		    <img src="data:image/png;base64,<?php echo DNS1D::getBarcodePNG((string)$ManualOrder->id, 'C39') ?>" alt="barcode" width="200"  />
                			<span class="notranslate"><p>{{$ManualOrder->id}}</p></span>
                        </td>
                		<td style="text-align:center;" height="10px">COD Amount</td>
                	</tr>
                	
                	<tr>
                		<td style="text-align:center; font-size:20px;font-weight:bold;">
                            <!--{{$ManualOrder->cod}}-->
                        </td>
                	</tr>
                    <tr>
                        <td colspan="2" rowspan="3" style="padding:5px; text-align:center;">
                		 <div class="container mt-4">
                        <div class="mb-3"><img src="data:image/png;base64,<?php echo DNS2D::getBarcodePNG((string)$ManualOrder->id, 'QRCODE') ?>" alt="barcode" width="100"  />
                        </div></div>
                        </td>
                        <td rowspan="2" align="center">
                            <h3 style="writing-mode: vertical-rl;text-align: center;margin: 0;transform: rotate(180deg);">Consignee</h3>
                        </td>
                
                        <td style="font-size:14px; font-weight:bold;padding: 5px;" colspan="2">
                            {{$ManualOrder->first_name}}
                        </td>
                        <td style="font-size:20px; font-weight:bold;text-align:center;;padding: 5px;">
                            {{$ManualOrder->receiver_number}} 
                        </td>
                    </tr>
                    <tr>
                        <td rowspan="2" style="padding-left: 5px;font-weight:bold; padding-right: 5px;height:30px;font-size: 21px;" colspan="3" align="center">
                            {{$ManualOrder->reciever_address}}</td>
                    </tr>
                    <tr>
                        <td rowspan="3" align="center">
                            <h3 style="writing-mode: vertical-rl;text-align: center;margin: 0;transform: rotate(180deg);">Shipper</h3>
                        </td>
                
                        <!--<td colspan="2" style="font-weight:bold;" align="center">-->
                        <!--   BRAND  HUB -->
                        <!--</td>-->
                        <!--<td style="font-weight:bold;" align="center">-->
                        <!--  Brand  Hub -KHI-->
                        <!--</td>-->
                    </tr>
                    
                    <tr>
                	<td colspan="2" style="font-size:16;px;text-align:center; font-weight:bold;">KHI</td>
                		<td>Pickup Address</td>
                        <td colspan="2" align="center" style="height:30px; padding-left: 5px; padding-right: 5px;">
                           Dha Phase 5 touheed commercial area plot 4c Flat 3c 28th Street Karachi.
                        </td>
                    </tr>
                	
                    <tr>
                	<td colspan="2" style="font-size:15px;text-align:center;font-weight:bold;padding-top:0px;padding-bottom:0px;">OVERNIGHT</td>
                        <td>Return Address</td>
                        <td colspan="2" align="center" style="height:30px; padding-left: 5px; padding-right: 5px;">
                            Same as above
                        </td>
                    </tr> 
                    <tr>
                		<td style="font-size:8px;padding-top:0px;padding-bottom:0px;">Return Branch:</td>
                		<td style="font-size:8px;text-align:center;font-weight:bold;padding-top:0px;padding-bottom:0px;">KHI</td>
                        <td colspan="4" rowspan="3"><b>Product Details: </b>branded </td></tr>
                    <tr>
                		<td style="font-size:8px;padding-top:0px;padding-bottom:0px;">Order Ref. No.:</td>
                		<td style="font-size:8px;text-align:center;font-weight:bold;padding-top:0px;padding-bottom:0px;">{{$ManualOrder->reference_number}}</td>
                    </tr>
                	<tr>
                		<td style="font-size:8px;padding-top:0px;padding-bottom:0px;">Pieces:</td>
                		<td style="font-size:8px;text-align:center;padding-top:0px;padding-bottom:0px;">{{$ManualOrder->pieces}}</td>
                    </tr>
                    <tr>
                		<td style="font-size:8px;padding-top:0px;padding-bottom:0px;">Weight:</td>
                		<td style="font-size:8px;text-align:center;padding-top:0px;padding-bottom:0px;">{{$ManualOrder->weight}}</td>
                        <td colspan="4" rowspan="3"><b>Shipper Remarks: </b> </td></tr>
                    <tr>
                		<td style="font-size:8px;padding-top:0px;padding-bottom:0px;">Insurance Value:</td>
                		<td style="font-size:8px;text-align:center;padding-top:0px;padding-bottom:0px;">0</td>
                    </tr>
                	<tr>
                		<td style="font-size:8px;padding-top:0px;padding-bottom:0px;">Printed On</td>
                		<td style="font-size:8px;text-align:center;padding-top:0px;padding-bottom:0px;"><?php echo $current_date?></td>
                    </tr>
                	<tr><td style="font-size:9px; text-align:center;" colspan="6"><span class="notranslate">اگر پیکنگ برقرار نہیں ہے تو پارسل قبول نہ کریں۔ "سی۔او۔ڈی" رقم ادا کرنے سے پہلے پارسل نہیں کھولا جا سکتا۔</span></td></tr>
                
                </tbody>
            </table>
            
            <div style="width: 650px; padding-left: 30px;padding-right: 30px;padding-top:5px;padding-bottom:5px;">
                <hr style="border-top: dashed 1px;">
            </div>
            <?php $count++;?>
            
            
            @if($count==4)
                </div> 
                <?php $count=1;?>
            @endif
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
</html>
