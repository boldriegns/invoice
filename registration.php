<?php
include('header-login.php');
include('functions.php');
error_reporting(E_ALL);
ini_set('display_errors', 1);
$errors = array();
session_start();
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['reg_user'])) {
    $errors = array(); // Initialize errors array

    // Retrieve data from the form
    $username = $_SESSION['username'];
    $email = $_SESSION['email'];
    $phone = $_SESSION['phone'];
    $address_1 = $_SESSION['address_1'];
    $town = $_POST['town']; 
    $county = $_POST['county'];
    $password_1 = $_POST['password_1'];
    $password_2 = $_POST['password_2'];

    // Validate password match
    if ($password_1 != $password_2) {
        array_push($errors, "The two passwords do not match");
    } else {
        // Check for existing user
        $user_check_query = "SELECT * FROM users WHERE username=? OR email=? LIMIT 1";
        $stmt = mysqli_prepare($mysqli, $user_check_query);
        mysqli_stmt_bind_param($stmt, "ss", $username, $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $user = mysqli_fetch_assoc($result);
        if ($user) {
                              // Set error message in session
         $_SESSION['error'] = "<span style='color: red; font-weight: bold;'>Registration failed! Username already exists.";
         header('location: regist.php'); // Redirect to login page
          exit();
        } else {
            $password = password_hash($password_1, PASSWORD_DEFAULT); // Hash password
            $query = "INSERT INTO users (username, email, phone, address_1, town, county, password) 
                      VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($mysqli, $query);
            mysqli_stmt_bind_param($stmt, "sssssss", $username, $email, $phone, $address_1, $town, $county, $password);
            if (mysqli_stmt_execute($stmt)) {
    // Set success message in session
    $_SESSION['success'] = "<span style='color: green; font-weight: bold; font-size: i8px;'>Registration successful!  redirected to the login page.";
    header('location: index.php'); // Redirect to login page
    exit();
            } else {
                  // Set error message in session
    $_SESSION['error'] = "<span style='color: red; font-weight: bold;'>Registration failed! Please review your entries and try again.";
    header('location: regist.php'); // Redirect to login page
    exit();
            }
        }
    }
    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
    }
}

?>
<nav class="navbar navbar-default" style="background-color: #c1bf33; color: black;">
    <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="dashboard.php" style="color: black;">Home</a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="navbar-collapse-1">
            <ul class="nav navbar-nav navbar-right">
                <li><a href="index.php" style="color: purple; font-size: 18px;">Login</a></li>
                <li><a href="regist.php" style="color: purple; font-size: 18px;">previous</a></li>
            </ul>
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>
<div class="background-registration">
<div class="row vertical-offset-100">
    <div class="col-md-4 col-md-offset-4">
        <div class="panel panel-default register-panel" style="width: 400px; height: 540px;">
            <div class="panel-heading panel-register">
                <h1 class="text-center">
                    <img src="<?php echo COMPANY_LOGO ?>" class="img-responsive">
                    <?php include('errors.php'); ?>
                </h1>
            </div>
            <div class="panel-body">
                <form accept-charset="UTF-8" role="form" method="post" >
                    <input type="hidden" name="action" value="register">
                    <fieldset>
                    <div class="input-group form-group">
			    	  		<div class="input-group-addon"><i class="glyphicon glyphicon-map-marker"></i></div>
                              <input class="form-control required" type="text" name="town" id="towm" placeholder="town" required>
			    		</div><br>
                    <div class="input-group form-group">
			    		 	<div class="input-group-addon"><i  class="glyphicon glyphicon-map-marker"></i></div>
			    			 <input class="form-control required" type="text" name="county" id="county" placeholder="county" required>
			    		</div><br>
                        <div class="input-group form-group">
			    	  		<div class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></div>
                              <input class="form-control required" placeholder="Password" name="password_1" type="password" placeholder="enter Password" required>
			    		</div><br>
			    		<div class="input-group form-group">
			    		 	<div class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></div>
                             <input class="form-control required" placeholder="Password" name="password_2" type="password" placeholder="confirm Password" required>
			    		</div><br>
                        <button type="submit" class="btn btn-danger btn-block" name="reg_user">Register</button><br>
                        <p>
  	         	      Already have an account? <a href="index.php">Sign in</a>
  	                 </p>
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
</div>
</div>
<?php
include('footer.php');
?>
