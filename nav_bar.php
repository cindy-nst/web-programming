<?php
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true && !isset($_SESSION["role"])) {
  header('Location: login.php');
  exit;
} else {
  $name = $_SESSION['name'];
  $role = $_SESSION['role'];
}
?>
<nav class="navbar navbar-default">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="index.php">HarvestHill</a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <li><a href="index.php">Home</a></li>
      </ul>
      <ul class="nav navbar-nav">
        <li><a href="#">Welcome back, <?=htmlspecialchars($name, ENT_QUOTES)?>!</a></li>
      </ul>
      <ul class="nav navbar-nav navbar-right">
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Menu <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="products.php">Products</a></li>
            <?php if ($role === 'ADMIN') {?>
              <li><a href="customers.php">Customers</a></li>
              <li><a href="staffs.php">Staffs</a></li>
            <?php } ?>
            <li><a href="orders.php">Orders</a></li>
            <li><a href="logout.php">Logout</a></li>
          </ul>
        </li>
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>