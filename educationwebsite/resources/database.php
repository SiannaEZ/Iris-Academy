<?php

// intialize variables to hold connection parameters
$username = 'root';
$dsn = 'mysql:host=localhost; dbname=register';
$password = '@nn@bethcha$e12';


try{
	// create an instance of the PDO class with the required parameters
	$db = new PDO($dsn, $username, $password);

	// set PDO error mode to exception
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	// display success message
	// echo "Connected to the register database";

}catch (PDOException $ex){

	// display error message
	echo "Connection unsuccesful. ERROR: ".$ex->getMessage();
}
