
<html xmlns="http://www.w3.org/1999/xhtml"><head>
<script src="jquery.js"></script>
<script src="dist/jquery.inputmask.js"></script>
<style>
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
    </head>
    
    <body>
        
        <div class="main-page">
            <?php $count=1;?>
            
            @foreach($slips as $slip)
            
             @if($count==1)
                <div class="sub-page"> 
            @endif
            
            <img src="{{$slip}}" style="width: 100%;/* float: left; */">
            
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
            
            .sub-page img 
            {
                height: 344px;
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















         
        
    
    </body>
</html>
