<?php include_once '../resources/session.php' ?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
	<head>
		<meta charset="utf-8">
		<title>Dashboard</title>
	</head>
	<body>
		<?php if(!isset($_SESSION['username'])): ?>
		<p>You are currently not signed in <a href="../php/login.php">Login</a>. Not yet a member? <a href="../php/signup.php">Sign Up</a></p>
	<?php else: ?>
		<p>You are logged in as <?php if(isset($_SESSION['username'])) echo $_SESSION['username'];?> <a href="../php/logout.php">Log Out</a></p>
	<?php endif ?>
	</body>
</html>
