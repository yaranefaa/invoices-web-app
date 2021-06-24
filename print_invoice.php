<?php
//print_invoice.php
if(isset($_GET["pdf"]) && isset($_GET["id"]))
{
 require_once 'pdf.php';
 include('invoice-db-connection.php');
 $output = '';
 $statement = $connect->prepare("
  SELECT * FROM invoiceinfo 
  WHERE InvoiceNb = :InvoiceNb
  LIMIT 1
 ");
 $statement->execute(
  array(
   ':InvoiceNb'       =>  $_GET["id"]
  )
 );
 $result = $statement->fetchAll();
 foreach($result as $row)
 {
  $output .= '
   <table width="100%" border="1" cellpadding="5" cellspacing="0">
    <tr>
     <td colspan="2" align="center" style="font-size:18px"><b>Invoice</b></td>
    </tr>
    <tr>
     <td colspan="2">
      <table width="100%" cellpadding="5">
       <tr>
        <td width="65%">
         To,<br />
         <b>RECEIVER (BILL TO)</b><br />
         Name : '.$row["InvoiceCustomer"].'<br /> 
         
        </td>
        <td width="35%">
         <br />
         Invoice No. : '.$row["InvoiceNb"].'<br />
         Invoice Date : '.$row["InvoiceDate"].'<br />
        </td>
       </tr>
      </table>
      <br />
      <table width="100%" border="1" cellpadding="5" cellspacing="0">
       <tr>
        <th>Line No.</th>
        <th>Quantity</th>
        <th>Item Desc.</th>
        <th>Unit Price</th>
        <th>Line Total</th>
     
        
       </tr>
       ';
  $statement = $connect->prepare(
   "SELECT * FROM invoiceline
   WHERE InvoiceNb = :InvoiceNb"
  );
  $statement->execute(
   array(
    ':InvoiceNb'       =>  $_GET["id"]
   )
  );
  $item_result = $statement->fetchAll();
  $count = 0;
  foreach($item_result as $sub_row)
  {
   $count++;
   $output .= '
   <tr>
    <td>'.$count.'</td>
    <td>'.$sub_row["Quantity"].'</td>
    <td>'.$sub_row["ItemDesc"].'</td>
    <td>'.$sub_row["UnitPrice"].'</td>
    <td>'.$sub_row["TotalLineAmount"].'</td>
    
   </tr>

   ';
  }
  $output .= '
  <tr>
            <td align="right" colspan="4"><b>Total</b></td>
            <td align="right"><b>'.$row["TotalAmount"].'</b></td>
            
            
    </tr>
  
  
  ';
  $output .= '
      </table>
     </td>
    </tr>
   </table>
   

  ';
 }




 $pdf = new Pdf();
 $file_name = 'Invoice-'.$row["InvoiceNb"].'.pdf';
 $pdf->loadHtml($output);
 $pdf->render();
 $pdf->stream($file_name, array("Attachment" => false));
}
?>