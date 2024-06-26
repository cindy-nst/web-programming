<?php
include_once 'database.php';

$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

try {
	$staffid = trim($_POST['staffid']);
	$password = $_POST['password'];

	$stmt = $conn->prepare('SELECT fld_staff_id, fld_staff_name, fld_password, fld_role FROM tbl_staffs_a192212_pt2 where fld_staff_id = :staffid');

	$stmt->bindParam(':staffid', $staffid, PDO::PARAM_STR);

	$stmt->execute();

	if ($stmt->rowCount() == 1) {
		$row = $stmt->fetch(PDO::FETCH_ASSOC);

		$hashed_password = $row['fld_password'];
		if(password_verify($password, $hashed_password)) {
			// We need to use sessions, so you should always start sessions using the below code.
			session_start();

			// Create sessions, so we know the user is logged in, they basically act like cookies but remember the data on the server.
			session_regenerate_id();

			// Store data in session variables
			$_SESSION['loggedin'] = true;
			$_SESSION['id'] = $row['fld_staff_id'];;
			$_SESSION['name'] = $row['fld_staff_name'];;
			$_SESSION['role'] = $row['fld_role'];			

			// Redirect user to home page
			header('Location: index.php');
		} else {
			session_start();
			session_regenerate_id();
			$_SESSION['loggedin'] = false;
			header('Location: login.php');
		}
	} else {
		session_start();
		session_regenerate_id();
		$_SESSION['loggedin'] = false;
		header('Location: login.php');
	}
}
catch(PDOException $e)
{
	echo "Error: " . $e->getMessage();
}

$conn = null;
?>