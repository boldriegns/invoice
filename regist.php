<?php
include('header-login.php');
include('functions.php');
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

if (isset($_SESSION['success'])) {
    echo "<p>" . $_SESSION['success'] . "</p>";
    unset($_SESSION['success']); // Remove the success message after displaying it
} elseif (isset($_SESSION['error'])) {
    echo "<p>" . $_SESSION['error'] . "</p>";
    unset($_SESSION['error']); // Remove the error message after displaying it
}
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['next-btn'])) {
    // Retrieve data from the form
    $username = $_POST['username'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address_1 = $_POST['address_1'];

    // Store data in session variables
    $_SESSION['username'] = $username;
    $_SESSION['email'] = $email;
    $_SESSION['phone'] = $phone;
    $_SESSION['address_1'] = $address_1;

    // Redirect to the second form
    header("Location: registration.php");
    exit();
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
                <li><a href="registration.php" style="color: purple; font-size: 18px;">Next</a></li>
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
                </h1>
            </div>
            <div class="panel-body">
                <form accept-charset="UTF-8" role="form" method="post" >
                    <input type="hidden" name="action" value="register">
                    <fieldset>
                    <div class="input-group form-group">
			    	  		<div class="input-group-addon"><i class="glyphicon glyphicon-user"></i></div>
                              <input class="form-control required" type="text" name="username" id="username" placeholder="username" required>
			    		</div><br>
			    		<div class="input-group form-group">
			    		 	<div class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></div>
			    			 <input class="form-control required" type="email" name="email" id="email"  placeholder="working email" required>
			    		</div><br>
                        <div class="input-group form-group">
			    	  		<div class="input-group-addon"><i class="glyphicon glyphicon-phone"></i></div>
                              <input class="form-control required" type="text" name="phone" id="phone" placeholder="phone number" required>
			    		</div><br>
			    		<div class="input-group form-group">
			    		 	<div class="input-group-addon"><i class="glyphicon glyphicon-home"></i></div>
			    			 <input class="form-control required" type="text" name="address_1" id="address_1" placeholder="address/location" required>
			    		</div><br>
                        <button type="submit" class="btn btn-danger btn-block" name="next-btn">Next</button><br>
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
