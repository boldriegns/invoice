<?php 
include('server.php');
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
<!DOCTYPE html>
<html>
<head>
  <title>Registration form</title>
  <link rel="stylesheet" type="text/css" href="style.css">
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <style>
    body {
      margin: 0;
      padding: 0;
      font-family: Arial, sans-serif;
      background-image: url('images/login.jpg'); /* Replace 'background.jpg' with your image file path */
      background-size: cover;
      background-position: center;
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .logo {
      display: block;
      margin: 0 auto 20px; /* Center the logo */
    }

    h2 {
      text-align: center;
      margin-bottom: 20px;
    }
	.input-group {
    display: flex;
    justify-content: center;
    align-items: center;
}

.input-group .input-wrapper {
    display: flex;
}

.input-group .input-wrapper .input-group {
    flex: 1;
    margin-right: 10px;
}

.input-group .input-wrapper .input-group:last-child {
    margin-right: 0;
}

.btn-block {
    width: 100%;
}


    .input-group-addon {
      width: 20px; /* Set width for the icons */
	  height: 40px;
    }
	.custom-input {
    width: 200px; /* Adjust the width as needed */
	height: 50px;
}

    .input-wrapper {
      display: flex;
      justify-content: space-between;
    }

    .input-wrapper input {
      flex: 1;
      height: 40px; /* Increase height of the input fields */
      padding: 5px 10px;
      font-size: 16px;
      border-radius: 5px;
      border: 1px solid #ccc;
    }

    .btn {
      width: 100%; /* Make the button span the entire width */
      padding: 10px;
      font-size: 15px;
      color: white;
      background-color: #9966cc;
       border-color: #9966cc;
      border-radius: 5px;
      cursor: pointer;
    }

    p {
      text-align: center;
      margin-top: 20px;
    }

    p a {
      color: #9966cc; /* Maroon color */
    }

    /* Adjusted form width */
    .registration-form {
      width: 100%; /* Set width to cover 70% of the screen */
    }
    .error {
  width: 92%; 
  margin: 0px auto; 
  padding: 10px; 
  border: 1px solid #a94442; 
  color: #a94442; 
  background: #f2dede; 
  border-radius: 5px; 
  text-align: left;
}
.success {
  color: #3c763d; 
  background: #dff0d8; 
  border: 1px solid #3c763d;
  margin-bottom: 20px;
}

	
  </style>
</head>
<body>
<div class="panel panel-default login-panel">
<div class="panel-body">
<form method="post" action="register.php">
<img src="images/logo.png" alt="Logo" class="logo">
    <h2>Register with us</h2>
    <?php include('errors.php'); ?>
    <fieldset>
    <div class="input-group">
        <div class="input-wrapper">
            <div class="input-group form-group">
                <div class="input-group-addon"><i class="fas fa-user"></i></div>
                <input class="form-control required" type="text" name="username" value="<?php echo $username; ?>" placeholder="username">
            </div>
            <div class="input-group form-group">
                <div class="input-group-addon"><i class="fas fa-envelope"></i></div>
                <input class="form-control required" type="email" name="email" value="<?php echo $email; ?>" placeholder="working email">
            </div>
        </div>
    </div>
    <div class="input-group">
        <div class="input-wrapper">
            <div class="input-group form-group">
                <div class="input-group-addon"><i class="fas fa-phone"></i></div>
                <input class="form-control required" type="text" name="phone" value="<?php echo $phone; ?>" placeholder="phone number">
            </div>
			<div class="input-group form-group">
                <div class="input-group-addon"><i class="fas fa-map-marker-alt"></i></div>
                <input class="form-control required" type="text" name="address_1" value="<?php echo $address_1; ?>" placeholder="address/location">
            </div>
        </div>
    </div>
    <div class="input-group">
        <div class="input-wrapper">
		<div class="input-group form-group">
                <div class="input-group-addon"><i class="fas fa-map-marker-alt"></i></div>
                <input class="form-control required" type="text" name="town" value="<?php echo $town; ?>" placeholder="town">
            </div>
			<div class="input-group form-group">
                <div class="input-group-addon"><i class="fas fa-map-marker-alt"></i></div>
                <input class="form-control required" type="text" name="county" value="<?php echo $county; ?>" placeholder="county">
            </div>
        </div>
    </div>
	<div class="input-group">
        <div class="input-wrapper">
            <div class="input-group form-group">
                <div class="input-group-addon"><i class="fas fa-lock"></i></div>
				<input class="form-control required" type="password" name="password_1" placeholder="enter Password">
            </div>
            <div class="input-group form-group">
                <div class="input-group-addon"><i class="fas fa-lock"></i></div>
                <input class="form-control required" type="password" name="password_2" placeholder="confirm Password">
            </div>
        </div>
    </div>
    <div class="input-group">
        <button type="submit" class="btn btn-block" name="reg_user">Register</button>
    </div>
    <p>
        Already have an account? <a href="index.php">Sign in</a>
    </p>
</fieldset>
</form>
</div>
</div>
</body>
</html>
