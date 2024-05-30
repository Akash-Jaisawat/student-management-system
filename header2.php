<?php
session_start();
include('config.php');
if (isset($_SESSION) && !empty($_SESSION['email'])) {
	if (isset($_SESSION['email'])) {
		$email = $_SESSION['email'];
		$sql = "select * from students where email = '$email'";
		$result = mysqli_query($link, $sql);
		if (!$result) {
			echo "Error fetching data: " . mysqli_error($link);
		} else {
			if (mysqli_num_rows($result) > 0) {
				while ($row = mysqli_fetch_assoc($result)) {
					$firstname = $row['firstname'];
					$image = $row['image'];
				}
			} else {
				echo "No result found for .$email";
			}
		}
	}
} else {
	header("Location: login.php");
	exit("please login first");
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
	<title>Student Management System</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="css/font-awesome.min.css">
	<link rel="stylesheet" href="css/style.css">
	<link rel="stylesheet" href="css/component.css">
	<link rel="stylesheet" href="css/bootstrap-datepicker3.css">


</head>

<body>
	<header>
		<div class="container">
			<div class="row">
				<div class="col-lg-4 col-md-4 col-sm-4 col-xs-3">
					<div class="logo">
						<img src="images/logo.png" />
					</div>
				</div>
				<div class="col-lg-8 col-md-8 col-sm-8 col-xs-9">
					<div class="user-account">
						<ul>
							<li>
								<span class="user-welcome">Welcome <?php echo $firstname; ?></span>
								<img class="account-pic" src="images/<?php echo $image ?>" />
							</li>
							<li>
								<a href="logout.php" class="btn btn-green btn-logout"><i class="fa fa-sign-out"></i> Log Out</a>
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</header>