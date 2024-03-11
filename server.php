<?php
session_start();
include_once("includes/config.php");
// initializing variables
$username = "";
$email    = "";
$phone = "";
$address_1 = "";
$county  = "";
$town = "";
$errors = array(); 

// connect to the database
$mysqli = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASS, DATABASE_NAME);

// REGISTER USER
if (isset($_POST['reg_user'])) {
  // receive all input values from the form
  $username = $_POST['username'];
  $email = $_POST['email'];
  $phone = $_POST['phone'];
  $address_1 = $_POST['address_1'];
  $county = $_POST['county'];
  $town = $_POST['town'];
  $password_1 = $_POST['password_1'];
  $password_2 =  $_POST['password_2'];

  // form validation: ensure that the form is correctly filled ...
  // by adding (array_push()) corresponding error unto $errors array
   if (empty($username)) { array_push($errors, "Username is required"); }
  if (empty($email)) { array_push($errors, "Email is required"); }
   if (empty($phone)) { array_push($errors, "phone number is required"); }
   if (empty($address_1)) { array_push($errors, "location is required"); }
   if (empty($county)) { array_push($errors, "county is required"); }
    if (empty($town)) { array_push($errors, "town is required"); }
  if (empty($password_1)) { array_push($errors, "Password is required"); }
  if ($password_1 != $password_2) {
	array_push($errors, "The two passwords do not match");
  }

  // first check the database to make sure 
  // a user does not already exist with the same username and/or email
  $user_check_query = "SELECT * FROM users WHERE username='$username' OR email='$email' LIMIT 1";
  $result = mysqli_query($mysqli, $user_check_query);
  $user = mysqli_fetch_assoc($result);
  
  if ($user) { // if user exists
    if ($user['username'] === $username) {
      array_push($errors, "Username already exists");
    }

    if ($user['email'] === $email) {
      array_push($errors, "email already exists");
    }
  }

  // Finally, register user if there are no errors in the form
  if (count($errors) == 0) {
  	$password = md5($password_1);//encrypt the password before saving in the database

  	$query = "INSERT INTO users (username,email,phone,address_1,county,town,password) 
  			  VALUES('$username', '$email','$phone','$address_1','$county','$town','$password')";
  	mysqli_query($mysqli, $query);
  	$_SESSION['username'] = $username;
  	$_SESSION['success'] = "You are now logged in";
  	header('location: index.php');
  }
}

// LOGIN USER
if (isset($_POST['login_user'])) {
  $username = $_POST['username'];
  $password = $_POST['password'];

  if (empty($username)) {
  	array_push($errors, "Username is required");
  }
  if (empty($password)) {
  	array_push($errors, "Password is required");
  }

  if (count($errors) == 0) {
  	$password = md5($password);
  	$query = "SELECT * FROM users WHERE username='$username' AND password='$password'";
  	$results = mysqli_query($mysqli, $query);
  	if (mysqli_num_rows($results) == 1) {
  	  $_SESSION['success'] = "You are now logged in";
      $_SESSION['login_username'] = $row['username'];
  	  header('location: dashboard.php');
  	}else {
  		array_push($errors, "Wrong username/password combination");
  	}
  }
}

?>