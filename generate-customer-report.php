<!DOCTYPE html>
<html lang="en">

<head>
  <title></title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="robots" content="noindex, nofollow">
  <script src="fa.js" crossorigin="anonymous"></script>

  <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="bootstrap/js/bootstrap.min.js"></script>
  <script src="dataTables/datatables/js/jquery.dataTables.min.js"></script>
  <script src="dataTables/datatables/js/dataTables.bootstrap.min.js"></script>
  <link rel="stylesheet" href="dataTables/datatables/css/dataTables.bootstrap.min.css">
  <!-- <link rel="stylesheet" href="./bootstrap/css/bootstrap.min.css" > -->
  <!-- jQuery library -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

  <!-- jQuery UI library -->
  <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>


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
  <center>
    <form method="post" id="customer_report_form" action="print-customer-report.php">
      <input type="text" style="width:30%;margin-top:20px" name="customerName" id="customerName" class="form-control input-sm " placeholder="Enter customer Name" required />
      <input type="text" style="width:30%;margin-top:20px" name="fromDate" id="fromDate" class="form-control input-sm" readonly placeholder="Select From Date" />
      <input type="text" style="width:30%;margin-top:20px" name="toDate" id="toDate" class="form-control input-sm" readonly placeholder="Select To Date" />
      <input type="submit" style="margin-top:20px" name="generate_customer_report" id="generate_customer_report" class="btn btn-info" value="Generate Report" />
    </form>
  </center>

  <script>
    $(function() {

      $("#customerName").autocomplete({

        source: 'search-customer.php'
      });


    });
  </script>
  <script>
    //formatting the date in form
    $(document).ready(function() {
      $('#fromDate').datepicker({
        format: "yyyy-mm-dd",
        autoclose: true
      });
      $('#toDate').datepicker({
        format: "yyyy-mm-dd",
        autoclose: true
      });
    });
  </script>

</body>

</html>