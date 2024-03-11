<?php
include('header.php');

// Check if user is logged in
if(isset($_SESSION['login_username'])) {
    // User is logged in, set $user_name to the logged-in user's name
    $user_name = $_SESSION['login_username'];
} else {
    // User is not logged in, set $user_name to 'Guest'
    $user_name = 'Guest';
}
?>

<div id="response" class="alert alert-success" style="display:none;">
    <a href="#" class="close" data-dismiss="alert">&times;</a>
    <div class="message"></div>
</div>
<div class="container">
    <h1>Welcome, <?php echo $user_name; ?></h1>
    <!-- Display user's name -->
    <p>Thank you for choosing to make an order. You can proceed to make your order below:</p>

    <div class="row">
        <div class="col-xs-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4>Select Items You Require</h4>
                </div>
                <div class="panel-body form-group form-group-sm">
                    <form method="post" id="make_order" action="process_order.php">
                        <input type="hidden" name="action" value="make_order">
                        <!-- Add a hidden input field to store the customer name -->
                        <input type="hidden" name="customer_name" value="<?php echo $user_name; ?>">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Product ID</th>
                                    <th>Product Name</th>
                                    <th>Product Price</th>
                                    <th>Quantity</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    // connect to the database
                                    $mysqli = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASS, DATABASE_NAME);

                                    // Check connection
                                    if ($mysqli->connect_error) {
                                        die("Connection failed: " . $mysqli->connect_error);
                                    }

                                    // Retrieve the product ID from the URL
                                    $product_id = $_GET['id'];

                                    // Retrieve the selected product from the database
                                    $sql = "SELECT product_id, product_name, product_price FROM products WHERE product_id = $product_id";
                                    $result = $mysqli->query($sql);

                                    if ($result->num_rows > 0) {
                                        // Output data of the selected product
                                        while($row = $result->fetch_assoc()) {
                                            echo "<tr>";
                                            echo "<td>{$row['product_id']}</td>";
                                            echo "<td>{$row['product_name']}</td>";
                                            echo "<td>{$row['product_price']}</td>";
                                            echo "<td><input type='number' name='quantity[{$row['product_id']}]' value='0'></td>";
                                            echo "</tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='4'>Product not found</td></tr>";
                                    }

                                    $mysqli->close();
                                ?>
                            </tbody>
                        </table>
                        <div class="row">
                            <div class="col-xs-12 margin-top btn-group">
                                <input type="submit" id="action_make_order" class="btn btn-success float-right" value="Make Orders" data-loading-text="Adding...">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include('footer.php');
?>
