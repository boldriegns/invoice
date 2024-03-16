<?php
include('header-login.php');
include('functions.php');
session_start();

if (isset($_SESSION['success'])) {
    echo "<p>" . $_SESSION['success'] . "</p>";
    unset($_SESSION['success']); // Remove the success message after displaying it
} elseif (isset($_SESSION['error'])) {
    echo "<p>" . $_SESSION['error'] . "</p>";
    unset($_SESSION['error']); // Remove the error message after displaying it
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
                <li><a href="regist.php" style="color: purple; font-size: 18px;">Register</a></li>
            </ul>
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>
<div class="background-container">
<div class="row vertical-offset-100">
	<div id="response" class="alert alert-success" style="display:none;">
		<a href="#" class="close" data-dismiss="alert">&times;</a>
		<div class="message"></div>
	</div>

	<div class="col-md-4 col-md-offset-4">
		<div class="panel panel-default register-panel" style="width: 400px; height: 400px;">
		  	<div class="panel-heading panel-register">
		  		<h1 class="text-center">
					<img src="<?php echo COMPANY_LOGO ?>" class="img-responsive">
				</h1>
		    	
		 	</div>
		  	<div class="panel-body">
		    	<form accept-charset="UTF-8" role="form" method="post" id="login_form" >
		    		<input type="hidden" name="action" value="login">
	                <fieldset>
			    	  	<div class="input-group form-group">
			    	  		<div class="input-group-addon"><i class="glyphicon glyphicon-user"></i></div>
			    		    <input class="form-control required" name="username" id="username" type="text" placeholder="Enter Username">
			    		</div><br>
			    		<div class="input-group form-group">
			    		 	<div class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></div>
			    			<input class="form-control required" placeholder="Password" name="password" type="password" placeholder="Enter Password">
			    		</div><br>
			    		<button type="button" id="btn-login" class="btn btn-danger btn-block">Login</button><br>
						<p>
  	         	      Don't have an account? <a href="regist.php">Sign up</a>
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
