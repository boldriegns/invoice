<?php
include('header.php');
if (isset($_SESSION['success'])) {
    echo "<p>" . $_SESSION['success'] . "</p>";
    unset($_SESSION['success']); // Remove the success message after displaying it
} elseif (isset($_SESSION['error'])) {
    echo "<p>" . $_SESSION['error'] . "</p>";
    unset($_SESSION['error']); // Remove the error message after displaying it
}
?>
<h2>Add Product</h2>
<hr>
						
<div class="row">
	<div class="col-xs-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h4>Product Information</h4>
			</div>
			<div class="panel-body form-group form-group-sm">
				<form action="action.php" method="post" enctype="multipart/form-data">
					<div class="row">
						<div class="col-xs-4">
							<input type="text" class="form-control required" name="product_name" placeholder="Enter Product Name" required>
						</div>
						<div class="col-xs-4">
						<input type="file" class="form-control required" name="image" placeholder="insert photo" required>
						</div>
						<div class="col-xs-4">
							<input type="text" class="form-control required" name="product_desc" placeholder="Enter Product Description" required>
						</div><br><br>
						<div class="col-xs-4">
							<div class="input-group">
								<span class="input-group-addon"><?php echo CURRENCY ?></span>
								<input type="number" name="product_price" class="form-control required" placeholder="0.00" aria-describedby="sizing-addon1" required>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-12 margin-top btn-group">
							<input type="submit" id="action_add_product" name="add_product" class="btn btn-success float-right" value="Add Product" data-loading-text="Adding...">
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
<div>

<?php
	include('footer.php');
?>