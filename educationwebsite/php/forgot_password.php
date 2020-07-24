<?php
// add database connection script
include_once '../resources/database.php';
include_once '../resources/utilities.php';

// process the form if the reset password button is clicked
if(isset($_POST['passwordResetBtn'])){
	// initialize an array to store any error message from the form
	$form_errors = array();

	// form validation
	$required_fields = array('email', 'new_password', 'confirm_password');

	// call the function to check empty field and merge the return data into form_error array
	$form_errors = array_merge($form_errors, check_empty_fields($required_fields));

	// fields that requires checking for minimum length
	$fields_to_check_length = array('new_password' => 6, 'confirm_password' => 6);

	// call the function to check minimum required length and merge the return data into form_error array
	$form_errors = array_merge($form_errors, check_min_length($fields_to_check_length));

	// email validation / merge the return data into form error array
	$form_errors = array_merge($form_errors, check_email($_POST));

	// check if error array is empty, if yes process form data and insert record
	if(empty($form_errors)){
		// collect form data and store in variables
		$email = $_POST['email'];
		$password1 = $_POST['new_password'];
		$password2 = $_POST['confirm_password'];

		// check if new password and confirm password are the same
		if($password1 != $password2){
			$result = "<p style = 'padding: 20px; border: 1px solid gray; color: red;'> Passwords do not match </p>";
		}
		else{
			try{
				// create SQL select statement to verify if email address input exists in the database
				$sqlQuery = "SELECT email FROM users Where email =:email";

				// user PDO prepared to sanitize data
				$statement = $db->prepare($sqlQuery);

				// execute the query
				$statement->execute(array(':email' => $email));

				// check if record exists
				if($statement->rowCount() == 1){
					// hash the password
					$hashedpassword = password_hash($password1, PASSWORD_DEFAULT);

					#SQL statement to update password
					$sqlUpdate = "UPDATE users SET password =:password WHERE email=:email";

					// user PDO prepared to sanitize SQL statement
					$statement = $db->prepare($sqlUpdate);

					// execute the statement
					$statement->execute(array(':password' => $hashedpassword, ':email' => $email));

					$result = "<p style='padding: 20px; border: 1px solid gray; color: green;'> Password Reset Succesfully</p>";

				}
				else{
					$result = "<p style='padding: 20px; border: 1px solid gray; color: red;'> The emaiil address provided does not exist in our database, plese try again</p>";
				}
			}
			catch (PDOExeception $ex){
				$result = "<p style='padding: 20px; border: 1px solid gray; color: red;'> An error occured" .$ex->getMessage(). "</p>";
			}
		}
	}
	else{
		if(count($form_errors) == 1){
			$result = "<p style='color: red;'> Therer was 1 error in the form<br>";

		}
		else{
			$result = "<p style='color: red;'> There were " .count($form_errors). " errors in the form <br>";
		}
	}
}

?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
	<head>
		<meta charset="utf-8">
		<title>Password Reset Page</title>
	</head>
	<body>

		<h3>Password Reset Form</h3>

		<?php if(isset($result)) echo $result; ?>
		<?php if(!empty($form_errors)) echo show_errors($form_errors); ?>

		<form action="" method="post">
			<table>
				<tr>
					<!-- email - user input -->
					<td><input type="email" value="" name="email" placeholder="email"></td>
				</tr>
				<tr>
					<!-- password - user input -->
					<td><input type="password" value="" name="new_password" placeholder="password"></td>
				</tr>
				<tr>
					<!-- password - user input -->
					<td><input type="password" value="" name="confirm_password" placeholder="password"></td>
				</tr>
				<tr>
					<td><input style="float: right;" type="submit" name= "passwordResetBtn" value="Reset Password"></td>
				</tr>
			</table>
		</form>
	</body>
</html>
