<?php
include_once '../resources/session.php';
include_once '../resources/database.php';
include_once '../resources/utilities.php';

if(isset($_POST['loginBtn'])){

	// array to hold errors
	$form_errors = array();

	// validate
	$required_fields = array('username', 'password');

	$form_errors = array_merge($form_errors, check_empty_fields($required_fields));

	if(empty($form_errors)){

		// collect form data
		$user = $_POST['username'];
		$password = $_POST['password'];

		//  check if user exists in the database
		$sqlQuery = "SELECT * FROM users WHERE username = :username";
		$statement = $db->prepare($sqlQuery);
		$statement->execute(array(':username' => $user));

		while($row = $statement->fetch()){
			$id = $row['id'];
			$hashedpassword = $row['password'];
			$username = $row['username'];

			if(password_verify($password, $hashedpassword)){
				$_SESSION['id'] = $id;
				$_SESSION['username'] = $username;
				redirectTo('dashboard');
			}
			else{
				$result = flashMessage("Invalid username or password");
			}
		}

	}
	else{
		if(count($form_errors) == 1){
			$result = flashMessage("There was 1 error in the form");
		}
		else{
			$result = flashMessage("There were " .count($form_errors). " errors in the form");
		}
	}
}

?>


<!DOCTYPE html>
<html lang="en" dir="ltr">
	<head>
		<meta charset="utf-8">
		<title>Login</title>
		<link rel="stylesheet" href="../css/indexstyles.css">
	</head>
	<body>

		<h2>Login Form</h2>
	  <?php if(isset($result)) echo $result; ?>
		<?php if(!empty($form_errors)) echo show_errors($form_errors); ?>
		<form action="" method="post">
			<table>
				<tr>
					<td><input type="text" value="" name="username" placeholder="username"></td>
				</tr>
				<tr>
					<td><input type="password" value="" name="password" placeholder= "password"></td>
				</tr>
				<tr>
					<td></td>
					<td><input style="float: right;" = type="submit" name="loginBtn" value="Login"></td>
				</tr>
			</table>
		</form>
		<a href="dashboard.php">Back</a><br>
		<a href="forgot_password.php">Forgot your Password?</a>
	</body>
</html>
