<?php

//invoice.php  
include('invoice-db-connection.php');

//fetching data from invoiceinfo
$statement = $connect->prepare("
    SELECT InvoiceNb,InvoiceDate,InvoiceCustomer,TotalAmount,PaidAmount FROM invoiceinfo 
    ORDER BY InvoiceNb DESC
  ");

$statement->execute();

$all_result = $statement->fetchAll();

//counting number of rows (total number of invoices)
$total_rows = $statement->rowCount();


//when we click on create invoice this is what happens : we are adding to two tables
if (isset($_POST["create_invoice"])) {



  $statement = $connect->prepare("
      INSERT INTO invoiceinfo
        (InvoiceDate, InvoiceCustomer, TotalAmount, PaidAmount)
        VALUES (:InvoiceDate, :InvoiceCustomer, :TotalAmount, :PaidAmount)
    ");

  $TotalAmount = 0;
  try {
    $statement->execute(
      array(

        ':InvoiceDate'             =>  trim($_POST["InvoiceDate"]),
        ':InvoiceCustomer'          =>  trim($_POST["InvoiceCustomer"]),
        ':PaidAmount'           =>  floatval(trim($_POST["PaidAmount"])),
        ':TotalAmount'       =>  $TotalAmount,

      )
    );
  } catch (PDOException $e) {
    $message = $e->getMessage();
    echo '<p>' . $message . '</p>';
  }




  $statement = $connect->query("SELECT LAST_INSERT_ID()");
  $InvoiceNb = $statement->fetchColumn();


  for ($count = 0; $count < $_POST["total_item"]; $count++) {
    $TotalAmount = $TotalAmount + floatval(trim($_POST["TotalLineAmount"][$count]));


    //if(trim($_POST["ItemDesc"][$count])!=""&&trim($_POST["Quantity"][$count])!=""&&trim($_POST["UnitPrice"][$count])!=""&&trim($_POST["SellerId"][$count])!=""){
    $statement = $connect->prepare("
                    INSERT INTO invoiceline 
                    (ItemDesc, Quantity, UnitPrice, TotalLineAmount, SellerId, InvoiceNb)
                    VALUES ( :ItemDesc, :Quantity, :UnitPrice, :TotalLineAmount, :SellerId, :InvoiceNb)
                  ");

    $statement->execute(
      array(
        ':ItemDesc'              =>  trim($_POST["ItemDesc"][$count]),
        ':Quantity'          =>  trim($_POST["Quantity"][$count]),
        ':UnitPrice'           =>  trim($_POST["UnitPrice"][$count]),
        ':TotalLineAmount'       =>  trim($_POST["TotalLineAmount"][$count]),
        ':SellerId'         =>  trim($_POST["SellerId"][$count]),
        ':InvoiceNb'         =>  $InvoiceNb
      )
    );
    // }
  }



  $statement = $connect->prepare("
        UPDATE invoiceinfo 
        SET TotalAmount = :TotalAmount
        WHERE InvoiceNb = $InvoiceNb
      ");
  $statement->execute(
    array(
      ':TotalAmount'     =>  $TotalAmount
    )
  );
  header("location:invoice.php");
}

//when we click on edit inside the "Edit Invoice"
if (isset($_POST["update_invoice"])) {

  $TotalAmount = 0;
  $InvoiceNb = $_POST["InvoiceNb"];

  $statement = $connect->prepare("
                DELETE FROM invoiceline WHERE InvoiceNb = :InvoiceNb 
            ");
  $statement->execute(
    array(
      ':InvoiceNb'       =>      $InvoiceNb
    )
  );

  for ($count = 0; $count < $_POST["total_item"]; $count++) {
    $TotalAmount = $TotalAmount + floatval(trim($_POST["TotalLineAmount"][$count]));
    if (trim($_POST["ItemDesc"][$count]) != "" && trim($_POST["Quantity"][$count]) != "" && trim($_POST["UnitPrice"][$count]) != "" && trim($_POST["SellerId"][$count]) != "") {
      $statement = $connect->prepare("
          INSERT INTO invoiceline
          (InvoiceNb,SellerId ,ItemDesc, Quantity, UnitPrice, TotalLineAmount) 
          VALUES (:InvoiceNb,:SellerId,:ItemDesc, :Quantity, :UnitPrice, :TotalLineAmount)
        ");
      $statement->execute(
        array(
          ':InvoiceNb' => $InvoiceNb,
          ':ItemDesc'                =>  trim($_POST["ItemDesc"][$count]),
          ':Quantity'          =>  trim($_POST["Quantity"][$count]),
          ':UnitPrice'            =>  trim($_POST["UnitPrice"][$count]),
          ':TotalLineAmount'     =>  trim($_POST["TotalLineAmount"][$count]),
          ':SellerId' => trim($_POST["SellerId"][$count])

        )
      );
    }


    //$result = $statement->fetchAll();
  }


  $statement = $connect->prepare("
        UPDATE invoiceinfo
        SET 
        InvoiceNb= :InvoiceNb,
        InvoiceDate = :InvoiceDate, 
        InvoiceCustomer = :InvoiceCustomer, 
        PaidAmount = :PaidAmount, 
        TotalAmount = :TotalAmount 
        WHERE InvoiceNb = :InvoiceNb 
      ");

  $statement->execute(
    array(

      ':InvoiceDate'             =>  trim($_POST["InvoiceDate"]),
      ':InvoiceCustomer'        =>  trim($_POST["InvoiceCustomer"]),
      ':PaidAmount'     =>  trim($_POST["PaidAmount"]),
      ':TotalAmount'     =>  $TotalAmount,

      ':InvoiceNb'               =>  trim($_POST["InvoiceNb"])
    )
  );


  // $result = $statement->fetchAll();

  header("location:invoice.php");
}

if (isset($_GET["delete"]) && isset($_GET["id"])) {

  $statement = $connect->prepare(
    "DELETE FROM invoiceline WHERE InvoiceNb = :InvoiceNb"
  );
  $statement->execute(
    array(
      ':InvoiceNb'       =>      $_GET["id"]
    )
  );
  $statement = $connect->prepare("DELETE FROM invoiceinfo WHERE InvoiceNb = :InvoiceNb");
  $statement->execute(
    array(
      ':InvoiceNb'       =>      $_GET["id"]
    )
  );

  header("location:invoice.php");
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <title></title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="robots" content="noindex, nofollow">

  <script src="fa.js" crossorigin="anonymous"></script>


  <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">

  <script src="bootstrap/js/bootstrap.min.js"></script>
  <!-- <link rel="stylesheet" href="./bootstrap/css/bootstrap.min.css"> -->
  <!-- jQuery library -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

  <!-- jQuery UI library -->
  <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
  <script src="dataTables/datatables/js/jquery.dataTables.min.js"></script>
  <script src="dataTables/datatables/js/dataTables.bootstrap.min.js"></script>
  <link rel="stylesheet" href="dataTables/datatables/css/dataTables.bootstrap.min.css">

  <!-- <link rel="stylesheet" href="./bootstrap/css/bootstrap.min.css" > -->

  <style>
    /* Remove the navbar's default margin-bottom and rounded borders */
    .navbar {
      margin-bottom: 4px;
      border-radius: 0;
    }

    /* Add a gray background color and some padding to the footer */
    footer {
      background-color: #f2f2f2;
      padding: 25px;
    }

    .carousel-inner img {
      width: 100%;
      /* Set width to 100% */
      margin: auto;
      min-height: 200px;
    }

    .navbar-brand {
      padding: 5px 40px;
    }

    /* Hide the carousel text when the screen is less than 600 pixels wide */
    @media (max-width: 600px) {
      .carousel-caption {
        display: none;
      }
    }
  </style>
  <link rel="icon" href="logo-color.png" />
</head>

<body>
  <style>
    .box {
      width: 100%;
      max-width: 1390px;
      border-radius: 5px;
      border: 1px solid #ccc;
      padding: 15px;
      margin: 0 auto;
      margin-top: 50px;
      box-sizing: border-box;
    }
  </style>
  <link rel="stylesheet" href="datepicker/css/datepicker.css">
  <script src="datepicker/js/bootstrap-datepicker.js"></script>

  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
      <a class="navbar-brand" href="#"><img src="logo.png" height="80px" width="100px" alt=""></a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarText">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="invoice.php">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="seller.php">Sellers</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="customer.php">Customers</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="item.php">Items</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="invoice.php">Invoices</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="generate-seller-report.php">Seller Report</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="generate-customer-report.php">Customer Report</a>
          </li>
        </ul>
        <span class="navbar-text">
          Sakr Flowers
        </span>
      </div>
    </div>
  </nav>

  <script>
    //formatting the date in invoice creation
    $(document).ready(function() {
      $('#InvoiceDate').datepicker({
        format: "yyyy-mm-dd",
        autoclose: true
      });
    });
  </script>
  <div class="container-fluid">
    <?php
    if (isset($_GET["add"])) {
    ?>
      <form method="post" id="invoice_form">
        <div class="table-responsive">
          <table class="table table-bordered">
            <tr>
              <td colspan="2" align="center">
                <h2 style="margin-top:10.5px">Create Invoice</h2>
              </td>
            </tr>
            <tr>
              <td colspan="2">
                <div class="row">
                  <div class="col-md-8">
                    To,<br />
                    <b>CUSTOMER (BILL TO)</b><br />
                    <input type="text" name="InvoiceCustomer" id="InvoiceCustomer" class="form-control input-sm" placeholder="Enter Customer Name" autocomplete="off" required />
                    <div class="list-group" id="show-list-InvoiceCustomer"> </div>
                  </div>
                  <div class="col-md-4">

                    <input type="text" name="InvoiceDate" id="InvoiceDate" class="form-control input-sm" readonly placeholder="Select Invoice Date" />
                    <input type="text" name="PaidAmount" id="PaidAmount" class="form-control input-sm" placeholder="Enter paid amount" />
                  </div>
                </div>
                <br />

                <!-- "add invoice" table header -->
                <table id="invoice-item-table" class="table table-bordered">
                  <tr>
                    <th width="7%">Line No.</th>
                    <th width="15%">Quantity</th>
                    <th width="45%">Item Desc</th>
                    <th width="10%">Unit Price</th>
                    <th width="10%">Seller</th>
                    <th width="10%">Line Total</th>
                    <th width="3%"></th>
                  </tr>

                  <!-- Copy this tr tag a couple of times to add initial rows; edit the ids as well -->
                  <tr>
                    <td><span id="linenb">1</span></td>
                    <td><input type="text" name="Quantity[]" id="Quantity1" data-linenb="1" class="form-control input-sm Quantity" /></td>
                    <td> <input type="text" name="ItemDesc[]" id="ItemDesc1" class="form-control input-sm " autocomplete="off" required />
                      <div class="list-group" id="show-list-ItemDesc1"> </div>
                    </td>
                    <td><input type="text" name="UnitPrice[]" id="UnitPrice1" data-linenb="1" class="form-control input-sm number_only UnitPrice" /></td>
                    <td><input type="text" name="SellerId[]" id="SellerId1" class="form-control input-sm " autocomplete="off" required />
                      <div class="list-group" id="show-list-SellerId1"> </div>
                    </td>
                    <td><input type="text" name="TotalLineAmount[]" id="TotalLineAmount1" data-linenb="1" readonly class="form-control input-sm TotalLineAmount" /></td>
                    <td></td>
                  </tr>

                </table>
                <div align="right">
                  <button type="button" name="add_row" id="add_row" class="btn btn-success btn-xs">+</button>
                </div>
              </td>
            </tr>
            <tr>
              <td align="right"><b>Total</td>
              <td align="right"><b><span id="TotalAmount"></span></b></td>
            </tr>
            <tr>
              <td colspan="2"></td>
            </tr>
            <tr>
              <td colspan="2" align="center">

                <!-- if you want to add rows you need to edit the value in here, for instance if you add initially 10 rows the value becomes 10  -->
                <!-- total_item is the incremental number of items in our invoice -->
                <input type="hidden" name="total_item" id="total_item" value="1" />
                <input type="submit" name="create_invoice" id="create_invoice" class="btn btn-info" value="Create" />
              </td>
            </tr>
          </table>
        </div>
      </form>

      <script>
        //initializing TotalAmount and the count of rows (if we add the initial rows we need to change the count value to the number of initial rows)

        $(document).ready(function() {


          var TotalAmount = $('#TotalAmount').text();
          var count = 1;



          // when we click on the + button this is what happens
          $(document).on('click', '#add_row', function() {

            count++;
            console.log(count);
            $('#total_item').val(count);
            var html_code = '';
            html_code += '<tr id="row_id_' + count + '">';
            html_code += '<td><span id="linenb">' + count + '</span></td>';
            html_code += '<td><input type="text" name="Quantity[]" id="Quantity' + count + '" data-linenb="' + count + '" class="form-control input-sm number_only Quantity" /></td>';
            html_code += '<td><input type="text" name="ItemDesc[]" id="ItemDesc' + count + '" class="form-control input-sm" autocomplete="off" required/><div class="list-group" id="show-list-ItemDesc' + count + '"> </div></td>';
            html_code += '<td><input type="text" name="UnitPrice[]" id="UnitPrice' + count + '" data-linenb="' + count + '" class="form-control input-sm number_only UnitPrice" /></td>';
            html_code += '<td><input type="text" name="SellerId[]" id="SellerId' + count + '" class="form-control input-sm" autocomplete="off" required/><div class="list-group" id="show-list-SellerId' + count + '"> </div></td>';
            html_code += '<td><input type="text" name="TotalLineAmount[]" id="TotalLineAmount' + count + '" data-linenb="' + count + '" class="form-control input-sm TotalLineAmount" readonly /></td>';

            html_code += '<td><button type="button" name="remove_row" id="' + count + '" class="btn btn-danger btn-xs remove_row">X</button></td>';
            html_code += '</tr>';
            $('#invoice-item-table').append(html_code);
            $(function() {

              $("#InvoiceCustomer").autocomplete({
                source: 'search-customer.php'
              });
              $("td input").on("click", function(cell) {
                let strid = this.id + "";
                if (strid.includes("ItemDesc")) {
                  $("#" + this.id).autocomplete({
                    source: 'search-item.php'
                  });
                }
                if (strid.includes("Seller")) {
                  $("#" + this.id).autocomplete({
                    source: 'search-seller.php'
                  });
                }

              })
            });
          });

          // when we click on the - button this is what happens
          $(document).on('click', '.remove_row', function() {
            var row_id = $(this).attr("id");
            var TotalLineAmount = $('#TotalLineAmount' + row_id).val();
            var TotalAmount = $('#TotalAmount').text();
            //removing the line deleted total from the whole total and displaying it
            var result_amount = parseFloat(TotalAmount) - parseFloat(TotalLineAmount);
            $('#TotalAmount').text(result_amount);
            $('#row_id_' + row_id).remove();
            count = count - 1;
            $('#total_item').val(count);
          });

          //this function caculates the total of each invoice
          function cal_final_total(count) {
            var TotalAmount = 0;
            for (j = 1; j <= count; j++) {
              var Quantity = 0;
              var UnitPrice = 0;
              var TotalLineAmount = 0;

              Quantity = $('#Quantity' + j).val();
              if (Quantity > 0) {
                UnitPrice = $('#UnitPrice' + j).val();
                if (UnitPrice > 0) {
                  TotalLineAmount = parseFloat(Quantity) * parseFloat(UnitPrice);
                  $('#TotalLineAmount' + j).val(TotalLineAmount);


                  TotalAmount = parseFloat(TotalAmount) + parseFloat(TotalLineAmount);

                }
              }
            }
            $('#TotalAmount').text(TotalAmount);
          }


          //calls the cal_final_total each time a unit price is inserted; blur means the cursor is no longer there
          $(document).on('blur', '.UnitPrice', function() {
            cal_final_total(count);
          });
          $(document).on('blur', '.Quantity', function() {
            cal_final_total(count);
          });


          //when we click on create this is what happens
          //we can change the conditions if we want to add empty rows; contition where all fields are empty for one line; submit to server
          $('#create_invoice').click(function() {
            let submit = true;
            if ($.trim($('#InvoiceCustomer').val()).length == 0) {
              alert("Please Enter Customer Name");
              return false;
            }

            /* NOT USED 
            if($.trim($('#order_no').val()).length == 0)
             {
               alert("Please Enter Invoice Number");
               return false;
             }*/

            if ($.trim($('#InvoiceDate').val()).length == 0) {
              alert("Please Select Invoice Date");
              return false;
            }

            for (var no = 1; no <= count; no++) {
              if ($.trim($('#ItemDesc' + no).val()).length == 0 && $.trim($('#Quantity' + no).val()).length == 0 && $.trim($('#UnitPrice' + no).val()).length == 0 && $.trim($('#SellerId' + no).val()).length == 0) {
                // alert("empty row- submitting...");
                // $('#invoice_form').submit();
                // return;
              } else {
                if ($.trim($('#ItemDesc' + no).val()).length == 0) {
                  alert("Please Enter Item Description");
                  $('#ItemDesc' + no).focus();
                  return false;
                }

                if ($.trim($('#Quantity' + no).val()).length == 0) {
                  alert("Please Enter Quantity");
                  $('#Quantity' + no).focus();
                  return false;
                }

                if ($.trim($('#UnitPrice' + no).val()).length == 0) {
                  alert("Please Enter Price");
                  $('#UnitPrice' + no).focus();
                  return false;
                }

                if ($.trim($('#SellerId' + no).val()).length == 0) {
                  alert("Please Enter SellerId");
                  $('#SellerId' + no).focus();
                  return false;
                }
              }




            }
            //submitting invoice to server
            $('#invoice_form').submit();

          });
          //modify this to modify default rows number
          // for(let i=0;i<7;i++){
          //   $("#add_row").click();
          // }

        });
      </script>
      <?php
    }
    //this is used to update from clicking on the edit button in our table that shows invoices
    elseif (isset($_GET["update"]) && isset($_GET["id"])) {
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
      foreach ($result as $row) {
      ?>
        <script>
          $(document).ready(function() {
            $('#InvoiceNb').val("<?php echo $row["InvoiceNb"]; ?>");
            $('#InvoiceDate').val("<?php echo $row["InvoiceDate"]; ?>");
            $('#InvoiceCustomer').val("<?php echo $row["InvoiceCustomer"]; ?>");
            $('#PaidAmount').val("<?php echo $row["PaidAmount"]; ?>");
            //used to set the date, it seems like .val does not work properly (when we click anywhere outside the date picker it changes to current date)
            $("#InvoiceDate").datepicker();
            $("#InvoiceDate").datepicker("setDate", new Date("<?php echo $row["InvoiceDate"]; ?>"));

          });
        </script>
        <form method="post" id="invoice_form">

          <div class="table-responsive">
            <table class="table table-bordered">
              <tr>
                <td colspan="2" align="center">
                  <h2 style="margin-top:10.5px">Edit Invoice</h2>
                </td>
              </tr>
              <tr>
                <td colspan="2">
                  <div class="row">
                    <div class="col-md-8">
                      To,<br />
                      <b>RECEIVER (BILL TO)</b><br />
                      <input type="text" name="InvoiceCustomer" id="InvoiceCustomer" class="form-control input-sm" placeholder="Enter Customer Name" autocomplete="off" required />
                      <div class="list-group" id="show-list-InvoiceCustomer"> </div>

                    </div>
                    <div class="col-md-4">
                      <br />
                      <input type="text" name="InvoiceNb" id="InvoiceNb" class="form-control input-sm" placeholder="Invoice No." readonly />
                      <!-- <input type="text" name="InvoiceDate" id="InvoiceDate" class="form-control input-sm"  placeholder="Select Invoice Date" /> -->
                      <input type="text" name="InvoiceDate" id="InvoiceDate" class="form-control input-sm" placeholder="Select Invoice Date" readonly />

                    </div>

                  </div>
                  <br />


                  <!-- "edit invoice" table header -->
                  <table id="invoice-item-table" class="table table-bordered">
                    <tr>
                      <th width="7%">Line No.</th>
                      <th width="15%">Quantity</th>
                      <th width="45%">Item Desc</th>
                      <th width="10%">Unit Price</th>
                      <th width="10%">Seller</th>
                      <th width="13%">Line Total</th>

                    </tr>

                    <?php
                    $statement = $connect->prepare("
                      SELECT * FROM invoiceline
                      WHERE InvoiceNb = :InvoiceNb
                    ");
                    $statement->execute(
                      array(
                        ':InvoiceNb'       =>  $_GET["id"]
                      )
                    );
                    $item_result = $statement->fetchAll();
                    $m = 0;
                    foreach ($item_result as $sub_row) {
                      $m = $m + 1;
                    ?>


                      <tr>
                        <td><span id="linenb"><?php echo $m; ?></span></td>
                        <td><input type="text" name="Quantity[]" id="Quantity<?php echo $m; ?>" data-srno="<?php echo $m; ?>" class="form-control input-sm item_Quantity Quantity" value="<?php echo $sub_row["Quantity"]; ?>" /></td>

                        <!-- added div for autocomplete -->
                        <td> <input type="text" name="ItemDesc[]" id="ItemDesc<?php echo $m; ?>" data-srno="<?php echo $m; ?>"" value=" <?php echo $sub_row["ItemDesc"]; ?>" class="form-control input-sm" autocomplete="off" required />
                          <div class="list-group" id="show-list-ItemDesc<?php echo $m; ?>"> </div>
                        </td>

                        <td><input type="text" name="UnitPrice[]" id="UnitPrice<?php echo $m; ?>" data-srno="<?php echo $m; ?>" class="form-control input-sm number_only order_item_price UnitPrice" value="<?php echo $sub_row["UnitPrice"]; ?>" /></td>
                        <td><input type="text" name="SellerId[]" id="SellerId<?php echo $m; ?>" data-srno="<?php echo $m; ?>" class="form-control input-sm order_item_actual_amount" value="<?php echo $sub_row["SellerId"]; ?>" autocomplete="off" required />
                          <div class="list-group" id="show-list-SellerId<?php echo $m; ?>"> </div>
                        </td>
                        <td><input type="text" name="TotalLineAmount[]" id="TotalLineAmount<?php echo $m; ?>" data-srno="<?php echo $m; ?>" readonly class="form-control input-sm order_item_final_amount" value="<?php echo $sub_row["TotalLineAmount"]; ?>" readonly /></td>
                        <td></td>
                      </tr>




                    <?php
                    }
                    ?>
                  </table>
                  <div align="right">
                    <button type="button" name="add_row" id="add_row" class="btn btn-success btn-xs">+</button>
                  </div>
                </td>

              </tr>
              <tr>
                <td>Paid amount:<input type="text" name="PaidAmount" id="PaidAmount" class="form-control input-sm" width="100%" placeholder="Enter paid amount" /></td>
              </tr>


              <tr>
                <td align="right"><b>Total</td>
                <td align="right"><b><span id="TotalAmount"><?php echo $row["TotalAmount"]; ?></span></b></td>
              </tr>
              <tr>
                <td colspan="2"></td>
              </tr>
              <tr>
                <td colspan="2" align="center">
                  <input type="hidden" name="total_item" id="total_item" value="<?php echo $m; ?>" />
                  <input type="hidden" name="InvoiceNb" id="InvoiceNb" value="<?php echo $row["InvoiceNb"]; ?>" />
                  <input type="submit" name="update_invoice" id="create_invoice" class="btn btn-info" value="Edit" />
                </td>
              </tr>
            </table>
          </div>
        </form>
        <script>
          $(document).ready(function() {

            var TotalAmount = $('#TotalAmount').text();
            var count = "<?php echo $m; ?>";

            // when we click on the + button this is what happens
            $(document).on('click', '#add_row', function() {
              count++;
              $('#total_item').val(count);
              var html_code = '';
              html_code += '<tr id="row_id_' + count + '">';
              html_code += '<td><span id="linenb">' + count + '</span></td>';
              html_code += '<td><input type="text" name="Quantity[]" id="Quantity' + count + '" data-linenb="' + count + '" class="form-control input-sm number_only Quantity" /></td>';
              html_code += '<td><input type="text" name="ItemDesc[]" id="ItemDesc' + count + '" class="form-control input-sm" autocomplete="off" required/><div class="list-group" id="show-list-ItemDesc' + count + '"> </div></td>';
              html_code += '<td><input type="text" name="UnitPrice[]" id="UnitPrice' + count + '" data-linenb="' + count + '" class="form-control input-sm number_only UnitPrice" /></td>';
              html_code += '<td><input type="text" name="SellerId[]" id="SellerId' + count + '" class="form-control input-sm" autocomplete="off" required/><div class="list-group" id="show-list-SellerId' + count + '"> </div></td>';
              html_code += '<td><input type="text" name="TotalLineAmount[]" id="TotalLineAmount' + count + '" data-linenb="' + count + '" class="form-control input-sm TotalLineAmount" readonly /></td>';

              html_code += '<td><button type="button" name="remove_row" id="' + count + '" class="btn btn-danger btn-xs remove_row">X</button></td>';
              html_code += '</tr>';
              $('#invoice-item-table').append(html_code);
            });
            // when we click on the - button this is what happens
            $(document).on('click', '.remove_row', function() {
              var row_id = $(this).attr("id");
              var TotalLineAmount = $('#TotalLineAmount' + row_id).val();
              var TotalAmount = $('#TotalAmount').text();
              //removing the line deleted total from the whole total and displaying it
              var result_amount = parseFloat(TotalAmount) - parseFloat(TotalLineAmount);
              $('#TotalAmount').text(result_amount);
              $('#row_id_' + row_id).remove();
              count = count - 1;
              $('#total_item').val(count);
            });

            //this function caculates the total of each invoice
            function cal_final_total(count) {
              var TotalAmount = 0;
              for (j = 1; j <= count; j++) {
                var Quantity = 0;
                var UnitPrice = 0;
                var TotalLineAmount = 0;

                Quantity = $('#Quantity' + j).val();
                if (Quantity > 0) {
                  UnitPrice = $('#UnitPrice' + j).val();
                  if (UnitPrice > 0) {
                    TotalLineAmount = parseFloat(Quantity) * parseFloat(UnitPrice);
                    $('#TotalLineAmount' + j).val(TotalLineAmount);


                    TotalAmount = parseFloat(TotalAmount) + parseFloat(TotalLineAmount);

                  }
                }
              }
              $('#TotalAmount').text(TotalAmount);
            }

            //calls the cal_final_total each time a unit price is inserted; blur means the cursor is no longer there
            $(document).on('blur', '.UnitPrice', function() {
              cal_final_total(count);
            });
            $(document).on('blur', '.Quantity', function() {
              cal_final_total(count);
            });

            //when we click on create this is what happens
            //we can change the conditions if we want to add empty rows; contition where all fields are empty for one line; submit to server
            $('#create_invoice').click(function() {
              if ($.trim($('#InvoiceCustomer').val()).length == 0) {
                alert("Please Enter Customer Name");
                return false;
              }

              /* NOT USED 
              if($.trim($('#order_no').val()).length == 0)
               {
                 alert("Please Enter Invoice Number");
                 return false;
               }*/

              if ($.trim($('#InvoiceDate').val()).length == 0) {
                alert("Please Select Invoice Date");
                return false;
              }

              for (var no = 1; no <= count; no++) {
                if ($.trim($('#ItemDesc' + no).val()).length == 0 && $.trim($('#Quantity' + no).val()).length == 0 && $.trim($('#UnitPrice' + no).val()).length == 0 && $.trim($('#SellerId' + no).val()).length == 0) {

                } else {
                  if ($.trim($('#ItemDesc' + no).val()).length == 0) {
                    alert("Please Enter Item Description");
                    $('#ItemDesc' + no).focus();
                    return false;
                  }

                  if ($.trim($('#Quantity' + no).val()).length == 0) {
                    alert("Please Enter Quantity");
                    $('#Quantity' + no).focus();
                    return false;
                  }

                  if ($.trim($('#UnitPrice' + no).val()).length == 0) {
                    alert("Please Enter Price");
                    $('#UnitPrice' + no).focus();
                    return false;
                  }

                  if ($.trim($('#SellerId' + no).val()).length == 0) {
                    alert("Please Enter SellerId");
                    $('#SellerId' + no).focus();
                    return false;
                  }

                }
              }
              //submitting invoice to server
              $('#invoice_form').submit();

            });

          });
        </script>
      <?php
      }
    } else {
      ?>
      <h3 align="center">All Invoices</h3>

      <br />
      <div align="center">
        <a href="invoice.php?add=1" class="btn btn-info btn-xs">Create</a>
      </div>
      <br />
      <!-- Showing the invoices in a table -->
      <table id="data-table" class="table table-bordered table-striped">
        <thead>
          <tr>
            <th>Invoice No.</th>
            <th>Invoice Date</th>
            <th>Customer Name</th>
            <th>Invoice Total</th>
            <th>Paid Amount</th>
            <th>PDF</th>
            <th>Edit</th>
            <th>Delete</th>
          </tr>
        </thead>
        <?php
        if ($total_rows > 0) {
          foreach ($all_result as $row) {
            echo '
              <tr>
                <td>' . $row["InvoiceNb"] . '</td>
                <td>' . $row["InvoiceDate"] . '</td>
                <td>' . $row["InvoiceCustomer"] . '</td>
                <td>' . $row["TotalAmount"] . '</td>
                <td>' . $row["PaidAmount"] . '</td>
                <td><a href="print_invoice.php?pdf=1&id=' . $row["InvoiceNb"] . '"><i class="fas fa-file-pdf"></i></a></td>
                <td><a href="invoice.php?update=1&id=' . $row["InvoiceNb"] . '"><i class="fas fa-edit"></i></a></td>
                <td><a href="#" id="' . $row["InvoiceNb"] . '" class="delete"><i class="fas fa-trash-alt"></i></a></td>
                
              </tr>
            ';
          }
        }
        ?>
      </table>
    <?php
    }
    ?>
  </div>
  <br>
  <script>
    $(function() {
      $("#InvoiceCustomer").autocomplete({
        source: 'search-customer.php'
      });
      $("td input").on("click", function(cell) {
        let strid = this.id + "";
        if (strid.includes("ItemDesc")) {
          $("#" + this.id).autocomplete({
            source: 'search-item.php'
          });
        }
        if (strid.includes("Seller")) {
          $("#" + this.id).autocomplete({
            source: 'search-seller.php'
          });
        }

      })
    });
  </script>
</body>


</html>
<script type="text/javascript">
  $(document).ready(function() {
    //this is used to choose which columns can be orderable
    var table = $('#data-table').DataTable({
      "order": [],
      "columnDefs": [{
        "targets": [5, 6, 7],
        "orderable": false,
      }, ],
      "pageLength": 25
    });
    $(document).on('click', '.delete', function() {
      var id = $(this).attr("id");
      if (confirm("Are you sure you want to remove this?")) {
        window.location.href = "invoice.php?delete=1&id=" + id;
      } else {
        return false;
      }
    });
  });
</script>

<script>
  $(document).ready(function() {
    $('.number_only').keypress(function(e) {
      return isNumbers(e, this);
    });

    function isNumbers(evt, element) {
      var charCode = (evt.which) ? evt.which : event.keyCode;
      if (
        (charCode != 46 || $(element).val().indexOf('.') != -1) && // “.” CHECK DOT, AND ONLY ONE.
        (charCode < 48 || charCode > 57))
        return false;
      return true;
    }
  });
</script>