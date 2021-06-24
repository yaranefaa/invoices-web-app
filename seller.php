 <?php include('seller-server.php'); ?>
 <?php
  if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $update = true;
    $record = mysqli_query($db, "SELECT SellerId, SellerName, CommissionRate FROM sellers WHERE id=$id");

    if ($record->num_rows == 1) {
      $n = mysqli_fetch_array($record);

      $sellerid = $n['SellerId'];
      $sellername = $n['SellerName'];
      $commissionRate = $n['CommissionRate'];
    }
  } else {
    $sellerid = null;
    $sellername = null;
    $commissionRate = null;
  } ?>
 <html>

 <head>
   <title>Sellers</title>

   <link rel="stylesheet" href="./bootstrap/css/bootstrap.min.css">
   <link rel="stylesheet" type="text/css" href="style.css" />
   <link rel="icon" href="logo-color.png" />
 </head>

 <body>
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
   <form method="post" action="seller-server.php">
     <h3>Add, Edit, Delete Sellers</h3>
     <input type="hidden" name="id" value="<?php echo $id; ?>">
     <div class="input-group">
       <label>Seller Name</label>
       <input type="text" name="seller_name" value="<?php echo $sellername; ?>">
     </div>
     <div class="input-group">
       <label>Seller Id</label>
       <input type="text" name="seller_id" value="<?php echo $sellerid; ?>">
     </div>
     <div class="input-group">
       <label>Commission Rate</label>
       <input type="text" name="commission_rate" value="<?php echo $commissionRate; ?>">
     </div>
     <div class="input-group">
       <?php if ($update == true) : ?>
         <button class="btn" type="submit" name="update" style="background: #556B2F;">Update</button>
       <?php else : ?>
         <button class="btn" type="submit" name="save">Save</button>
       <?php endif ?>
     </div>

   </form>

   <?php if (isset($_SESSION['message'])) : ?>
     <div class="msg">
       <?php
        echo $_SESSION['message'];
        unset($_SESSION['message']);
        ?>
     </div>
   <?php endif ?>

   <?php $results = mysqli_query($db, "SELECT id,sellername, sellerid, commissionrate FROM sellers"); ?>

   <table>
     <thead>
       <tr>
         <th>Seller Name</th>
         <th>Seller Id</th>
         <th>Commission Rate %</th>
         <th colspan="2">Action</th>
       </tr>
     </thead>

     <?php while ($row = mysqli_fetch_array($results)) { ?>
       <tr>
         <td><?php echo $row['sellername']; ?></td>
         <td><?php echo $row['sellerid']; ?></td>
         <td><?php echo $row['commissionrate']; ?></td>
         <td>
           <a href="seller.php?edit=<?php echo $row['id']; ?>" class="edit_btn">Edit</a>
         </td>
         <td>
           <a href="seller-server.php?del=<?php echo $row['id'] . " " . $row['sellername']; ?>" class="del_btn">Delete</a>
         </td>
       </tr>
     <?php } ?>
   </table>

   <!-- <footer class="text-white text-center text-lg-start" style="background-color: #101055;">
      
      <div class="container p-4">
        
        <div class=" text-center col-lg-12 col-md-12 mb-4 mb-md-0">
            <p>
              <img src="logo.png" hieght="80px" width="100px" alt="">
            </p>
          </div>
        
 
      </div>
      

      
       
      <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.2)">
        © 2021 Copyright:
        <span>Yara Nefaa</span>
      </div>
  
    </footer> -->

   <script src="./bootstrap/js/bootstrap.min.js"></script>


 </body>

 </html>