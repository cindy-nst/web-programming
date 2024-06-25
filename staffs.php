<?php
include_once 'staffs_crud.php';
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
  <title>Harvest Hill: Staffs</title>
  <!-- Bootstrap -->
  <link href="css/bootstrap.min.css" rel="stylesheet">

  <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
  <?php
  // We need to use sessions, so you should always start sessions using the below code.
  session_start();
  // If the user is not logged in redirect to the login page...
  if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true && !isset($_SESSION["id"])) {
    header('Location: login.php');
    exit;
  }
  ?>
</head>
<body>

 <?php include_once 'nav_bar.php'; ?>

 <div class="container-fluid">
  <div class="row">
    <div class="col-xs-12 col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3">
      <div class="page-header">
        <h2>Create New Staff</h2>
      </div>
      <form action="staffs.php" method="post" class="form-horizontal">
        <div class="form-group">
          <label for="stafftid" class="col-sm-3 control-label">Staff ID</label>
          <div class="col-sm-9">
            <input name="sid" type="text" class="form-control" id="staffid" placeholder="Staff ID" value="<?php if(isset($_GET['edit'])) echo $editrow['fld_staff_id']; ?>" required> 
          </div>
        </div>
        <div class="form-group">
          <label for="staffname" class="col-sm-3 control-label">Staff Name</label>
          <div class="col-sm-9">
            <input name="sname" type="text" class="form-control" id="staffname" placeholder="Staff Name" value="<?php if(isset($_GET['edit'])) echo $editrow['fld_staff_name']; ?>" required>
          </div>
        </div>
        <div class="form-group">
          <label for="staffage" class="col-sm-3 control-label">Age</label>
          <div class="col-sm-9">
            <td><input type="number" name="age" min="0" max="100" step="1" class="form-control id="staffage" placeholder="Staff Age" value="<?php if(isset($_GET['edit'])) echo $editrow['fld_age']; ?>" required></td>
          </div>
        </div>
        <div class="form-group">
          <label for="staffgender" class="col-sm-3 control-label">Gender</label>
          <div class="col-sm-9">
            <div class="radio">
              <label>
                <input name="gender" type="radio" id="staffgender" value="Male" <?php if(isset($_GET['edit'])) if($editrow['fld_gender']=="Male") echo "checked"; ?>required> Male
              </label>
            </div>
            <div class="radio">
              <label>
                <input name="gender" type="radio" id="staffgender" value="Female" <?php if(isset($_GET['edit'])) if($editrow['fld_gender']=="Female") echo "checked"; ?>> Female
              </label>
            </div>
          </div>
        </div>
        <div class="form-group">
          <label for="staffposition" class="col-sm-3 control-label">Position</label>
          <div class="col-sm-9">
            <input name="position" type="text" class="form-control" id="staffposition" placeholder="Staff Position" value="<?php if(isset($_GET['edit'])) echo $editrow['fld_position']; ?>" required> 
          </div>
        </div>
        <div class="form-group">
          <label for="staffpassword" class="col-sm-3 control-label">Password</label>
          <div class="col-sm-9">
            <input name="password" type="password" class="form-control" id="staffpassword" placeholder="Password" required> 
          </div>
        </div>
        <div class="form-group">
          <label for="staffconfirmpassword" class="col-sm-3 control-label">Retype password</label>
          <div class="col-sm-9">
            <input name="confirmpassword" type="password" class="form-control" id="staffconfirmpassword" placeholder="Confirm Password" required> 
            <span id="message"></span>
          </div>
        </div>
        <div class="form-group">
          <label for="staffrole" class="col-sm-3 control-label">Role</label>
          <div class="col-sm-9">
            <select name="role" class="form-control" id="staffposition" required>
              <?php
              $roles = array("ADMIN", "NON-ADMIN");
              foreach($roles as $x){
                $role = $x;
                ?>
                <option value="<?php echo $role; ?>" <?php if(isset($_GET['edit'])) if($editrow['fld_role']=="<?php echo $role; ?>") echo "selected"; ?>><?php echo $role; ?></option>
                <?php
              }
              $conn = null;
              ?>
            </select>
          </div>
        </div>
        <div class="form-group">
          <div class="col-sm-offset-3 col-sm-9">
            <?php if (isset($_GET['edit'])) { ?>
              <input type="hidden" name="oldsid" value="<?php echo $editrow['fld_staff_id']; ?>">
              <button class="btn btn-default" type="submit" name="update"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> Update</button>
            <?php } else { ?>
              <button class="btn btn-default" type="submit" id="createbtn" name="create"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Create</button>
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
        <h2>Staffs List</h2>
      </div>
      <table class="table table-striped table-bordered">
        <tr>
          <td>Staff ID</td>
          <td>Staff Name</td>
          <td>Gender</td>
          <td>Age</td>
          <td>Position</td>
          <td></td>
        </tr>
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
          $stmt = $conn->prepare("select * from tbl_staffs_a192212_pt2 LIMIT $start_from, $per_page");
          $stmt->execute();
          $result = $stmt->fetchAll();
        }
        catch(PDOException $e){
          echo "Error: " . $e->getMessage();
        }
        foreach($result as $readrow) {
          ?>
          <tr>
            <td><?php echo $readrow['fld_staff_id']; ?></td>
            <td><?php echo $readrow['fld_staff_name']; ?></td>
            <td><?php echo $readrow['fld_gender']; ?></td>
            <td><?php echo $readrow['fld_age']; ?></td>
            <td><?php echo $readrow['fld_position']; ?></td>
            <td>
              <a href="staffs.php?pid=<?php echo $readrow['fld_staff_id']; ?>" class="btn btn-warning btn-xs" role="button">Details</a>
              <a href="staffs.php?edit=<?php echo $readrow['fld_staff_id']; ?>" class="btn btn-success btn-xs" role="button"> Edit </a>
              <a href="staffs.php?delete=<?php echo $readrow['fld_staff_id']; ?>" onclick="return confirm('Are you sure to delete?');" class="btn btn-danger btn-xs" role="button">Delete</a>
            </td>
          </tr>
          <?php
        }
        $conn = null;
        ?>
      </table>
    </div>
  </div>

  <div class="row">
    <div class="col-xs-12 col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
      <nav>
        <ul class="pagination">
          <?php
          try {
            $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt = $conn->prepare("SELECT * FROM tbl_staffs_a192212_pt2");
            $stmt->execute();
            $result = $stmt->fetchAll();
            $total_records = count($result);
          }
          catch(PDOException $e){
            echo "Error: " . $e->getMessage();
          }
          $total_pages = ceil($total_records / $per_page);
          ?>
          <?php if ($page==1) { ?>
            <li class="disabled"><span aria-hidden="true">«</span></li>
          <?php } else { ?>
            <li><a href="staffs.php?page=<?php echo $page-1 ?>" aria-label="Previous"><span aria-hidden="true">«</span></a></li>
            <?php
          }
          for ($i=1; $i<=$total_pages; $i++)
            if ($i == $page)
              echo "<li class=\"active\"><a href=\"staffs.php?page=$i\">$i</a></li>";
            else
              echo "<li><a href=\"staffs.php?page=$i\">$i</a></li>";
            ?>
            <?php if ($page==$total_pages) { ?>
              <li class="disabled"><span aria-hidden="true">»</span></li>
            <?php } else { ?>
              <li><a href="staffs.php?page=<?php echo $page+1 ?>" aria-label="Previous"><span aria-hidden="true">»</span></a></li>
            <?php } ?>
          </ul>
        </nav>
      </div>
    </div>
  </div>
  <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
  <!-- Include all compiled plugins (below), or include individual files as needed -->
  <script src="js/bootstrap.min.js"></script>
  <script type="text/javascript">
    $('#staffpassword, #staffconfirmpassword').on('keyup', function () {
      if ($('#staffpassword').val() == $('#staffconfirmpassword').val()) {
        $('#message').html('Match').css('color', 'green');
      } else 
      $('#message').html('Not Match').css('color', 'red');
    });

    $("#createbtn").click(function(event) { 
      if ($('#staffpassword').val() == $('#staffconfirmpassword').val()) {
        $('#message').html('Match').css('color', 'green');
      } else 
      $('#message').html('Not Match').css('color', 'red');
    }); 
  </script>

</body>
</html>