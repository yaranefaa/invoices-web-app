 <?php include('item-server.php'); ?>
 <?php
  if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $update = true;
    $record = mysqli_query($db, "SELECT itemDesc FROM items WHERE itemId=$id");

    if ($record->num_rows == 1) {
      $n = mysqli_fetch_array($record);

      $itemDesc = $n['itemDesc'];
    }
  } else {
    $itemDesc = null;
  } ?>
 <html>

 <head>
   <title>Items</title>

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
   <form method="post" action="item-server.php">
     <h3>Add, Edit, Delete Items</h3>
     <input type="hidden" name="id" value="<?php echo $id; ?>">
     <div class="input-group">
       <label>Item Description</label>
       <input type="text" name="item_desc" value="<?php echo $itemDesc; ?>">
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

   <?php $results = mysqli_query($db, "SELECT itemId,itemDesc FROM items"); ?>

   <table>
     <thead>
       <tr>
         <th>Item Description</th>
         <th colspan="2">Action</th>
       </tr>
     </thead>
     <tbody>
       <?php while ($row = mysqli_fetch_array($results)) { ?>
         <tr>
           <td style="width: 204px;"><?php echo $row['itemDesc']; ?></td>

           <td style="width: 14px;">
             <a href="item.php?edit=<?php echo $row['itemId']; ?>" class="edit_btn">Edit</a>
           </td>
           <td>
             <a href="item-server.php?del=<?php echo $row['itemId'] . " " . $row['itemDesc']; ?>" class="del_btn">Delete</a>
           </td>
         </tr>
       <?php } ?>
     </tbody>
     <!-- </table>
        <div style="height:500px;overflow:auto;">  
         <table>    
                 -->



   </table>
   <!-- </div> 
           -->



   <script src="./bootstrap/js/bootstrap.min.js"></script>


 </body>

 </html>