<?php

include_once 'database.php';

$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

try {
  $stmt = $conn->prepare("SELECT DISTINCT fld_category FROM tbl_products_a192212_pt2");
  $stmt->execute();
  $resultcategory = $stmt->fetchAll();
}
catch(PDOException $e){
  echo "Error: " . $e->getMessage();
}

//Create
if (isset($_POST['create'])) {

  try {
    $pid = $_POST['pid'];
    $name = $_POST['name'];
    $price = $_POST['price'];
    $brand =  $_POST['brand'];
    $category = $_POST['category'];
    $quantity = $_POST['quantity'];
    $expirydate = $_POST['expirydate'];
    $temp = explode(".", $_FILES['pimage']['name']);
    $pimage = $pid . '.' . end($temp);

    $stmt = $conn->prepare("INSERT INTO tbl_products_a192212_pt2(fld_product_id,
      fld_product_name, fld_price, fld_brand, fld_category,
      fld_quantity, fld_expiry_date, fld_image) VALUES(:pid, :name, :price, :brand,
      :category, :quantity, :expirydate, :pimage)");

    $stmt->bindParam(':pid', $pid, PDO::PARAM_STR);
    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
    $stmt->bindParam(':price', $price, PDO::PARAM_INT);
    $stmt->bindParam(':brand', $brand, PDO::PARAM_STR);
    $stmt->bindParam(':category', $category, PDO::PARAM_STR);
    $stmt->bindParam(':quantity', $quantity, PDO::PARAM_INT);
    $stmt->bindParam(':expirydate', $expirydate, PDO::PARAM_STR);
    $stmt->bindParam(':pimage', $pimage, PDO::PARAM_STR);

    $stmt->execute();
    
    move_uploaded_file($_FILES['pimage']['tmp_name'], "products/" . $pimage);
  }
  catch(PDOException $e)
  {
    echo "Error: " . $e->getMessage();
  }
}

//Update
if (isset($_POST['update'])) {

  try {
    $pid = $_POST['pid'];
    $name = $_POST['name'];
    $price = $_POST['price'];
    $brand =  $_POST['brand'];
    $category = $_POST['category'];
    $quantity = $_POST['quantity'];
    $expirydate = $_POST['expirydate'];
    $oldpid = $_POST['oldpid'];

    if (!empty($_FILES['pimage']['name'])) { 
      $temp = explode(".", $_FILES['pimage']['name']);
      $pimage = $pid . '.' . end($temp);
      echo $pimage;

      $stmt = $conn->prepare("UPDATE tbl_products_a192212_pt2 SET 
        fld_product_name = :name, fld_price = :price, fld_brand = :brand,
        fld_category = :category, fld_quantity = :quantity, fld_expiry_date = :expirydate, fld_image = :pimage 
        WHERE fld_product_id = :oldpid");

      $stmt->bindParam(':pimage', $pimage, PDO::PARAM_STR);
    }else{
      $stmt = $conn->prepare("UPDATE tbl_products_a192212_pt2 SET 
        fld_product_name = :name, fld_price = :price, fld_brand = :brand,
        fld_category = :category, fld_quantity = :quantity, fld_expiry_date = :expirydate 
        WHERE fld_product_id = :oldpid");
    }
    
    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
    $stmt->bindParam(':price', $price, PDO::PARAM_INT);
    $stmt->bindParam(':brand', $brand, PDO::PARAM_STR);
    $stmt->bindParam(':category', $category, PDO::PARAM_STR);
    $stmt->bindParam(':quantity', $quantity, PDO::PARAM_INT);
    $stmt->bindParam(':expirydate', $expirydate, PDO::PARAM_STR);
    $stmt->bindParam(':oldpid', $oldpid, PDO::PARAM_STR);

    $stmt->execute();
    
    unlink("products/" . $oldpid . "png");
    move_uploaded_file($_FILES['pimage']['tmp_name'], "products/" . $pimage);

    header("Location: products.php");
  }
  catch(PDOException $e)
  {
    echo "Error: " . $e->getMessage();
  }
}

//Delete
if (isset($_GET['delete'])) {

  try {

    $stmt = $conn->prepare("DELETE FROM tbl_products_a192212_pt2 WHERE fld_product_id = :pid");

    $stmt->bindParam(':pid', $pid, PDO::PARAM_STR);

    $pid = $_GET['delete'];

    $stmt->execute();

    header("Location: products.php");
  }
  catch(PDOException $e)
  {
    echo "Error: " . $e->getMessage();
  }
}

//Edit
if (isset($_GET['edit'])) {

  try {

    $stmt = $conn->prepare("SELECT * FROM tbl_products_a192212_pt2 WHERE fld_product_id = :pid");

    $stmt->bindParam(':pid', $pid, PDO::PARAM_STR);

    $pid = $_GET['edit'];

    $stmt->execute();

    $editrow = $stmt->fetch(PDO::FETCH_ASSOC);
  }
  catch(PDOException $e)
  {
    echo "Error: " . $e->getMessage();
  }
}

$conn = null;
?>