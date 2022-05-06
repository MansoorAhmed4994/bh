
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
        @foreach($slips as $slip)
            <img src="$slip">
        @endforeach
    </body>
</html>
