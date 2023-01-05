
<html xmlns="http://www.w3.org/1999/xhtml"><head><style>
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
    font-size: 2em;
}
td h4
{
     
    text-align: right!important;
    border: none!important;
    font-size: 2em;
}
table
{
    width: 100%;
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
         
            <div class="sub-page">  
        
                <table>
                    <tbody>
                        <tr>
                            <td colspan="2" style="padding: 10px;">
                                <img src="data:image/png;base64,<?php echo DNS1D::getBarcodePNG((string)$ManualOrder->id, 'C39') ?>" alt="barcode" width="200"  />
                    			<span class="notranslate"><h3>{{$ManualOrder->id}}</h3></span></tr>
                            </td>
                        <tr>
                            <td><h5>Name: </h5></td><td><h4>{{$ManualOrder->first_name}}</h4></td> 
                        </tr>
                        <tr>
                            <td><h5>Number: </h5></td><td><h4>{{$ManualOrder->receiver_number}}</h4></td> 
                        </tr>
                        <tr>
                            <td><h5>Address: </h5></td><td><h4>{{$ManualOrder->reciever_address}}</h4></td> 
                        </tr>
                        <tr>
                            <td><h5>price: </h5></td><td><h4>{{$ManualOrder->price}}</h4></td> 
                        </tr>
                    </tbody>
                </table>
             
            
             
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
</html>
