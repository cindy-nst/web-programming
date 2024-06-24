<?php
include_once 'products_crud.php';
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
  <title>Harvest Hill: Products</title>
  <!-- Bootstrap -->
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <link href="css/datatables.min.css" rel="stylesheet">

  <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>
<body>
  <?php include_once 'nav_bar.php'; ?>

  <div class="container-fluid">
    <div class="row">
      <div class="col-xs-12 col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3">
        <div class="page-header">
          <h2>Create New Product</h2>
        </div>

        <form action="products.php" method="post" class="form-horizontal" enctype="multipart/form-data">
          <div class="form-group">
            <label for="productid" class="col-sm-3 control-label">Product ID</label>
            <div class="col-sm-9">
              <input name="pid" type="text" class="form-control" id="productid" placeholder="Product ID" value="<?php if(isset($_GET['edit'])) echo $editrow['fld_product_id']; ?>" required>
            </div>
          </div>
          <div class="form-group">
            <label for="productname" class="col-sm-3 control-label">Name</label>
            <div class="col-sm-9">
              <input name="name" type="text" class="form-control" id="productname"placeholder="Product Name" value="<?php if(isset($_GET['edit'])) echo $editrow['fld_product_name']; ?>" required>
            </div>
          </div>
          <div class="form-group">
            <label for="productprice" class="col-sm-3 control-label">Price</label>
            <div class="col-sm-9">
              <input name="price" type="text" class="form-control" id="productprice" placeholder="Product Price" value="<?php if(isset($_GET['edit'])) echo $editrow['fld_price']; ?>" required>
            </div>
          </div>
          <div class="form-group">
            <label for="productbrand" class="col-sm-3 control-label">Brand</label>
            <div class="col-sm-9">
              <input name="brand" type="text" class="form-control" id="productbrand" placeholder="Product Brand" value="<?php if(isset($_GET['edit'])) echo $editrow['fld_brand']; ?>" required>
            </div>
          </div>
          <div class="form-group">
            <label for="category" class="col-sm-3 control-label">Category</label>
            <div class="col-sm-9">
              <select name="category" class="form-control" id="category" required>
                <?php
                foreach($resultcategory as $readrow){
                  $categoryname= $readrow["fld_category"];
                  ?>
                  <option value="<?php echo $categoryname; ?>" <?php if(isset($_GET['edit'])) if($editrow['fld_category']=="<?php echo $categoryname; ?>") echo "selected"; ?>><?php echo $categoryname; ?></option>
                  <?php
                }
                $conn = null;
                ?>
              </select>
            </div>
          </div>  
          <div class="form-group">
            <label for="expirydate" class="col-sm-3 control-label">Expiry Date</label>
            <div class="col-sm-9">
              <input type="date" id="expirydate" name="expirydate" class="form-control" value="<?php if(isset($_GET['edit'])) echo $editrow['fld_expiry_date']; ?>" required>
            </div>
          </div>  
          <div class="form-group">
            <label for="quantity" class="col-sm-3 control-label">Quantity</label>
            <div class="col-sm-9">
              <input name="quantity" type="number" min=1 class="form-control" id="quantity" placeholder="Product Quantity"  value="<?php if(isset($_GET['edit'])) echo $editrow['fld_quantity']; ?>" required> 
            </div>
          </div>
          <div class="form-group">
            <label for="pimage" class="col-sm-3 control-label">Product Image</label>
            <div class="col-sm-9">
              <input name="pimage" type="file" class="form-control" id="pimage" accept=".png" required>
            </div>
          </div>
          <div class="form-group">
            <div class="col-sm-offset-3 col-sm-9">
              <?php if (isset($_GET['edit'])) { ?>
                <input type="hidden" name="oldpid" value="<?php echo $editrow['fld_product_id']; ?>">
                <button class="btn btn-default" type="submit" name="update"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> Update</button>
              <?php } else { ?>
                <button class="btn btn-default" type="submit" name="create"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Create</button>
              <?php } ?>
              <button class="btn btn-default" type="reset"><span class="glyphicon glyphicon-erase" aria-hidden="true"></span> Clear</button>
            </div>
          </div>
        </form>
      </div>
    </div>

    <div class="row">
      <div class="col-xs-12 col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
        <div class="page-header">
          <h2>Products List</h2>
        </div>
        <table class="table table-striped table-bordered" id="products" style="width:100%">
          <thead>
            <tr>
              <th>Product ID</th>
              <th>Name</th>
              <th>Price</th>
              <th>Brand</th>
              <th>Category</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <?php
            // Read
            $per_page = 5;
            if (isset($_GET["page"]))
              $page = $_GET["page"];
            else
              $page = 1;
            $start_from = ($page-1) * $per_page;
            try {
              $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
              $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
              $stmt = $conn->prepare("select * from tbl_products_a192212_pt2");
              $stmt->execute();
              $result = $stmt->fetchAll();
            }
            catch(PDOException $e){
              echo "Error: " . $e->getMessage();
            }
            foreach($result as $readrow) {
              ?>
              <tr>
                <td><?php echo $readrow['fld_product_id']; ?></td>
                <td><?php echo $readrow['fld_product_name']; ?></td>
                <td><?php echo $readrow['fld_price']; ?></td>
                <td><?php echo $readrow['fld_brand']; ?></td>
                <td><?php echo $readrow['fld_category']; ?></td>
                <td>
                  <a href="javascript:void(0)" data-href="products_details.php?pid=<?php echo $readrow['fld_product_id']; ?>" data-keyboard="true" class="btn btn-warning btn-xs openPopup" role="button">Details</a>
                  <a href="products.php?edit=<?php echo $readrow['fld_product_id']; ?>" class="btn btn-success btn-xs" role="button"> Edit </a>
                  <a href="products.php?delete=<?php echo $readrow['fld_product_id']; ?>" onclick="return confirm('Are you sure to delete?');" class="btn btn-danger btn-xs" role="button">Delete</a>
                </td>
              </tr>
              <?php
            }
            $conn = null;
            ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Modal -->
  <div class="modal fade" id="myModal" role="dialog" tabindex='-1'>
    <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Product Details</h4>
        </div>
        <div class="modal-body">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

  <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
  <!-- Include all compiled plugins (below), or include individual files as needed -->
  <script src="js/bootstrap.min.js"></script>
  <script src="js/datatables.min.js"></script>

  <script type="text/javascript">
    $(document).ready(function() {
      $('.openPopup').on('click',function() {
        var dataURL = $(this).attr('data-href');
        $('.modal-body').load(dataURL,function() {
          $('#myModal').modal({show:true, keyboard:true});
        });
      }); 
    });

    $(document).ready(function() {
      /**
      * 'order' - set column "Name" is sorted ascendingly on load
      * 'columnDefs' - set "Price" column not part of the searching function
      * 'lengthMenu' - set entries per page options
      * 'layout>top2End>buttons' - add export as excel button and exclude last column
      */
      $('#products').DataTable({
        order: [[1, 'asc']],
        columnDefs: [{
          'searchable': false, 
          'targets': [2] 
        }],
        lengthMenu: [5, 10, 20, 30, { label: 'All', value: -1 }],
        layout: {
          top2End: {
            buttons: [{
              extend: 'excelHtml5',
              exportOptions: {
                columns: [0, 1, 2, 3, 4]
              }
            }]
          }
        }
      });
    });
  </script>
</body>
</html>