<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Invoice Management System</title>

	<!-- JS -->
	<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
	<script src="js/moment.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="//cdn.datatables.net/1.10.7/js/jquery.dataTables.js"></script>
	<script src="//cdn.datatables.net/plug-ins/1.10.7/integration/bootstrap/3/dataTables.bootstrap.js"></script>
	<script src="js/bootstrap.datetime.js"></script>
	<script src="js/bootstrap.password.js"></script>
	<script src="js/scripts.js"></script>

	<!-- CSS -->
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="css/bootstrap.datetimepicker.css">
	<link rel="stylesheet" href="//cdn.datatables.net/1.10.7/css/jquery.dataTables.css">
	<link rel="stylesheet" href="//cdn.datatables.net/plug-ins/1.10.7/integration/bootstrap/3/dataTables.bootstrap.css">
	<link rel="stylesheet" href="css/styles.css">

	<style>
		@import url(http://fonts.googleapis.com/css?family=Open+Sans:400,700);
		body, h1, h2, h3, h4, h5, h6{
			font-family: 'Open Sans', sans-serif;
		}
		.error {
  width: 100%; 
  margin: 0px auto; 
  padding: 10px; 
  border: 1px solid #a94442; 
  color: #a94442; 
  background: #f2dede; 
  border-radius: 5px; 
  text-align: left;
  font-size: 15px;
  
}
.success {
  color: #3c763d; 
  background: #dff0d8; 
  border: 1px solid #3c763d;
  margin-bottom: 20px;
}
.background-container {
    background-image: url('images/index.jpg');
    background-size: cover;
	padding-top: 0px;
    background-repeat: no-repeat;
    background-position: center; /* Centers the background image within the container */
    width: 100%; /* Ensures the container spans the entire width of the page */
    height: 100vh; /* Sets the height of the container to 100% of the viewport height */
}
.background-registration {
    background-image: url('images/register.jpg');
    background-size: cover;
    background-repeat: no-repeat;
    background-position: center; /* Centers the background image within the container */
    width: 100%; /* Ensures the container spans the entire width of the page */
    height: 100vh; /* Sets the height of the container to 100% of the viewport height */
}
.row.vertical-offset-100-next {
    display: none;
}
.product-info {
    display: flex;
    align-items: center;
}

.product-photo {
    margin-right: 10px; /* Adjust this value to control the space between photo and description */
}

.product-description {
    flex-grow: 1;
    margin-top: 0px;
}


	</style>

</head>

<body>
	<div class="container">