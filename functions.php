<?php


include_once("includes/config.php");

function getInvoices() {
    // Connect to the database
    $mysqli = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASS, DATABASE_NAME);

    // Output any connection error
    if ($mysqli->connect_error) {
        die('Error : ('.$mysqli->connect_errno .') '. $mysqli->connect_error);
    }

    // Initialize query for retrieving invoices
    $query = "SELECT * 
              FROM invoices i
              JOIN customers c ON c.invoice = i.invoice";

    // Check user role to filter invoices
    if(isset($_SESSION['user_role'])) {
        $user_role = $_SESSION['user_role'];
        if($user_role == 'customer') {
            // If user is a customer, filter invoices to display only their own invoices
            if(isset($_SESSION['login_username'])) {
                $customer_name = $_SESSION['login_username'];
                $query .= " WHERE c.name = '$customer_name'";
            } else {
                // If session username not set, assume 'Guest' and don't show any invoices
                $query .= " WHERE c.name = 'Guest'";
            }
        } elseif ($user_role == 'vendor') {
            // If user is a vendor, filter invoices to display only invoices they created
            if(isset($_SESSION['login_username'])) {
                $vendor_username = $_SESSION['login_username'];
                $query .= " WHERE i.product_vendor = '$vendor_username'";
            } else {
                // Handle scenario when vendor username is not set
                // You may want to handle this according to your application logic
                die("Vendor username not found.");
            }
        }
    }

    // Add ordering by invoice
    $query .= " ORDER BY i.invoice";

    // Execute query
    $results = $mysqli->query($query);

    if($results) {
        // Display table headers
        print '<table class="table table-striped table-hover table-bordered" id="data-table" cellspacing="0"><thead><tr>
                <th>Invoice</th>
                <th>Vendor Name</th>
                <th>Customer</th>
                <th>Issue Date</th>
                <th>Due Date</th>
                <th>Type</th>
                <th>Status</th>
                <th>Actions</th>
              </tr></thead><tbody>';

        while($row = $results->fetch_assoc()) {
            // Display invoice details
            print '<tr>
                <td>'.$row["invoice"].'</td>
                <td>'.$row["product_vendor"].'</td>
                <td>'.$row["name"].'</td>
                <td>'.$row["invoice_date"].'</td>
                <td>'.$row["invoice_due_date"].'</td>
                <td>'.$row["invoice_type"].'</td>';

            if($row['status'] == "open") {
                print '<td><span class="label label-primary">'.$row['status'].'</span></td>';
            } elseif ($row['status'] == "paid") {
                print '<td><span class="label label-success">'.$row['status'].'</span></td>';
            }

            // Check user role for actions
            if(isset($_SESSION['user_role']) && ($_SESSION['user_role'] == 'admin' || $_SESSION['user_role'] == 'vendor')) {
                print '<td><a href="invoice-edit.php?id='.$row["invoice"].'" class="btn btn-primary btn-xs"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></a> <a href="#" data-invoice-id="'.$row['invoice'].'" data-email="'.$row['email'].'" data-invoice-type="'.$row['invoice_type'].'"  class="btn btn-success btn-xs email-invoice"><span class="glyphicon glyphicon-envelope" aria-hidden="true"></span></a> <a href="invoices/'.$row["invoice"].'.pdf" class="btn btn-info btn-xs" target="_blank"><span class="glyphicon glyphicon-download-alt" aria-hidden="true"></span></a> <a data-invoice-id="'.$row['invoice'].'" class="btn btn-danger btn-xs delete-invoice"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a></td>';
            } else {
                print '<td><a href="invoices/'.$row["invoice"].'.pdf" class="btn btn-info btn-xs" target="_blank"><span class="glyphicon glyphicon-download-alt" aria-hidden="true"></span></a></td>';
            }
            print '</tr>';
        }

        print '</tbody></table>';

    } else {
        // If no results, display message
        echo "<p>There are no invoices to display.</p>";
    }

    // Free result memory
    $results->free();

    // Close database connection
    $mysqli->close();
}



// Initial invoice number
function getInvoiceId() {

	// Connect to the database
	$mysqli = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASS, DATABASE_NAME);

	// output any connection error
	if ($mysqli->connect_error) {
	    die('Error : ('. $mysqli->connect_errno .') '. $mysqli->connect_error);
	}

	$query = "SELECT invoice FROM invoices ORDER BY invoice DESC LIMIT 1";

	if ($result = $mysqli->query($query)) {

		$row_cnt = $result->num_rows;

	    $row = mysqli_fetch_assoc($result);

	    //var_dump($row);

	    if($row_cnt == 0){
			echo INVOICE_INITIAL_VALUE;
		} else {
			echo $row['invoice'] + 1; 
		}

	    // Frees the memory associated with a result
		$result->free();

		// close connection 
		$mysqli->close();
	}
	
}

// populate product dropdown for invoice creation
function popProductsList() {

	// Connect to the database
	$mysqli = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASS, DATABASE_NAME);

	// output any connection error
	if ($mysqli->connect_error) {
	    die('Error : ('. $mysqli->connect_errno .') '. $mysqli->connect_error);
	}

	// the query
	$query = "SELECT * FROM products ORDER BY product_name ASC";

	// mysqli select query
	$results = $mysqli->query($query);

	if($results) {
		echo '<select class="form-control item-select">';
		while($row = $results->fetch_assoc()) {

		    print '<option value="'.$row['product_price'].'">'.$row["product_name"].' - '.$row["product_desc"].'</option>';
		}
		echo '</select>';

	} else {

		echo "<p>There are no products, please add a product.</p>";

	}

	// Frees the memory associated with a result
	$results->free();

	// close connection 
	$mysqli->close();

}

// populate product dropdown for invoice creation
function popUserList() {

	// Connect to the database
	$mysqli = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASS, DATABASE_NAME);

	// output any connection error
	if ($mysqli->connect_error) {
	    die('Error : ('. $mysqli->connect_errno .') '. $mysqli->connect_error);
	}

	// the query
	$query = "SELECT * FROM users ORDER BY username ASC";

	// mysqli select query
	$results = $mysqli->query($query);

	if($results) {

		print '<table class="table table-striped table-hover table-bordered" id="data-table"><thead><tr>

				<th>username</th>
				<th>Email</th>
				<th>Phone</th>
				<th>Action</th>

			  </tr></thead><tbody>';

		while($row = $results->fetch_assoc()) {

		    print '
			    <tr>
					<td>'.$row["username"].'</td>
				    <td>'.$row["email"].'</td>
				    <td>'.$row["phone"].'</td>
					<td><a href="#" class="btn btn-primary btn-xs customer-select" data-customer-name="'.$row['username'].'" data-customer-email="'.$row['email'].'" data-customer-phone="'.$row['phone'].'" data-customer-town="'.$row['town'].'" data-customer-county="'.$row['county'].'" data-customer-address-1="'.$row['address_1'].'" data-customer-name-ship="'.$row['username'].'" data-customer-address-1-ship="'.$row['address_1'].'"  data-customer-town-ship="'.$row['town'].'" data-customer-county-ship="'.$row['county'].'">Select</a></td>
			    </tr>
		    ';
		}

		print '</tr></tbody></table>';

	} else {

		echo "<p>There are no customers to display.</p>";

	}

	// Frees the memory associated with a result
	$results->free();

	// close connection 
	$mysqli->close();

}

// populate product dropdown for invoice creation
function popCustomersList() {

	// Connect to the database
	$mysqli = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASS, DATABASE_NAME);

	// output any connection error
	if ($mysqli->connect_error) {
	    die('Error : ('. $mysqli->connect_errno .') '. $mysqli->connect_error);
	}

	// the query
	$query = "SELECT * FROM store_customers ORDER BY name ASC";

	// mysqli select query
	$results = $mysqli->query($query);

	if($results) {

		print '<table class="table table-striped table-hover table-bordered" id="data-table"><thead><tr>

				<th>Name</th>
				<th>Email</th>
				<th>Phone</th>
				<th>Action</th>

			  </tr></thead><tbody>';

		while($row = $results->fetch_assoc()) {

		    print '
			    <tr>
					<td>'.$row["name"].'</td>
				    <td>'.$row["email"].'</td>
				    <td>'.$row["phone"].'</td>
					<td><a href="#" class="btn btn-primary btn-xs customer-select" data-customer-name="'.$row['name'].'" data-customer-email="'.$row['email'].'" data-customer-phone="'.$row['phone'].'" data-customer-address-1="'.$row['address_1'].'" data-customer-town="'.$row['town'].'" data-customer-county="'.$row['county'].'" data-customer-name-ship="'.$row['name_ship'].'" data-customer-address-1-ship="'.$row['address_1_ship'].'"  data-customer-town-ship="'.$row['town_ship'].'" data-customer-county-ship="'.$row['county_ship'].'">Select</a></td>
			    </tr>
		    ';
		}

		print '</tr></tbody></table>';

	} else {

		echo "<p>There are no customers to display.</p>";

	}

	// Frees the memory associated with a result
	$results->free();

	// close connection 
	$mysqli->close();

}
function getOrders() {
    // Connect to the database
    
    $mysqli = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASS, DATABASE_NAME);

    // Output any connection error
    if ($mysqli->connect_error) {
        die('Error : ('. $mysqli->connect_errno .') '. $mysqli->connect_error);
    }

    // Initialize query for retrieving orders from users table
    $query = "SELECT orders.*, users.username, users.email, users.phone, users.address_1, users.county, users.town 
              FROM orders 
              INNER JOIN users ON orders.customer_name = users.username
              INNER JOIN products ON orders.product_name = products.product_name";

    // Check if user is logged in and their role
    if(isset($_SESSION['user_role'])) {
        $user_role = $_SESSION['user_role'];
        // If user is a customer, filter orders to display only their own orders
        if($user_role == 'customer') {
            if(isset($_SESSION['login_username'])) {
                $customer_name = $_SESSION['login_username'];
                $query .= " WHERE customer_name = '$customer_name'";
            } else {
                // If session username not set, assume 'Guest' and don't show any orders
                $query .= " WHERE customer_name = 'Guest'";
            }
        } elseif ($user_role == 'vendor') {
            if(isset($_SESSION['login_username'])) {
                $product_vendor = $_SESSION['login_username'];
                // Join the orders table with the users table to retrieve customer details
                $query = "SELECT orders.*, users.username, users.email, users.phone, users.address_1, users.county, users.town 
                FROM orders 
                INNER JOIN users ON orders.customer_name = users.username
                INNER JOIN products ON orders.product_id = products.product_id
                WHERE products.product_vendor = '$product_vendor'";
            } else {
                // If session username not set for vendor, don't show any orders
                $query .= " WHERE 1=0";
            }
        }
    }

    // Add ordering by customer name
    $query .= " ORDER BY customer_name ASC";

    // Execute query
    $results = $mysqli->query($query);

    if($results) {
        // Display table headers
        print '<table class="table table-striped table-hover table-bordered" id="data-table"><thead><tr>
                <th>Customer Name</th>
                <th>Product Name</th>
                <th>status</th>
                <th>Product Price</th>
                <th>Quantity</th>
                <th>Action</th> <!-- Add a new column for actions -->
              </tr></thead><tbody>';

        while($row = $results->fetch_assoc()) {
                        // Assign retrieved values to variables
                        $customer_name = $row['customer_name'];
                        $customer_address_1 = $row['address_1'];
                        $customer_town = $row['town'];
                        $customer_email = $row['email'];
                        $customer_county = $row['county'];
                        $customer_phone = $row['phone'];
                        $customer_name_ship = $row['username'];
                        $customer_address_1_ship = $row['address_1'];
                        $customer_town_ship = $row['town'];
                        $customer_county_ship = $row['county'];
            // Display order details
            print '<tr>
                <td>'.$row["customer_name"].'</td>
                <td>'.$row["product_name"].'</td>
                <td>'.$row["status"].'</td>
                <td>ksh. '.$row["product_price"].'</td>
                <td>'.$row["quantity"].'</td>';
                
// Check if user is admin or vendor
if (isset($_SESSION['user_role']) && ($_SESSION['user_role'] == 'admin' || $_SESSION['user_role'] == 'vendor')) {
    // Check if the status is "pending"
    if ($row["status"] == "pending") {
        // Display delete button and response button for admin and vendor
        print '<td>
            <a data-product_id="'.$row['product_id'].'" class="btn btn-danger btn-xs delete-order"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span>Delete Order</a>
            <form action="invoice-create.php" method="post" style="display: inline;">
            <input type="hidden" name="id" value="'.$row["id"].'"> <!-- Add hidden input for id -->
            <input type="hidden" name="customer_name" value="'.$row["username"].'">
            <input type="hidden" name="customer_email" value="'.$row["email"].'">
            <input type="hidden" name="customer_phone" value="'.$row["phone"].'">
            <input type="hidden" name="customer_address_1" value="'.$row["address_1"].'">
            <input type="hidden" name="customer_county" value="'.$row["county"].'">
            <input type="hidden" name="customer_town" value="'.$row["town"].'">
            <input type="hidden" name="customer_name_ship" value="'.$row["username"].'">
            <input type="hidden" name="customer_address_1_ship" value="'.$row["address_1"].'">
            <input type="hidden" name="customer_town_ship" value="'.$row["town"].'">
            <input type="hidden" name="customer_county_ship" value="'.$row["county"].'">
            <input type="hidden" name="product_name" value="'.$row["product_name"].'">
            <input type="hidden" name="quantity" value="'.$row["quantity"].'">
            <input type="hidden" name="product_price" value="'.$row["product_price"].'">
            
            <button type="submit" class="btn btn-success btn-xs">
                <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                Create Invoice
            </button>
        </form>
        </td>';
    } else {
        // Display a message indicating that the order cannot be invoiced
        print '<td><a data-product_id="'.$row['product_id'].'" class="btn btn-danger btn-xs delete-order"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span>Delete Order</a></td>';
    }
} else {
    // Otherwise, display delete button only
    print '<td></td>';
}

            print '</tr>';
        }

        print '</tbody></table>';

    } else {
        // If no results, display message
        echo "<p>There are no orders to display.</p>";
    }

    // Free result memory
    $results->free();

    // Close database connection
    $mysqli->close();
}



// get products list
function getProducts() {

    // Connect to the database
    $mysqli = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASS, DATABASE_NAME);

    // output any connection error
    if ($mysqli->connect_error) {
        die('Error : ('. $mysqli->connect_errno .') '. $mysqli->connect_error);
    }

    // Initialize the query
    $query = "";

    // Check if user is logged in and their role
    if(isset($_SESSION['user_role'])) {
        $user_role = $_SESSION['user_role'];
        // If user is a vendor, filter products to display only the products they created
        if($user_role == 'vendor') {
            if(isset($_SESSION['login_username'])) {
                $product_vendor = $_SESSION['login_username'];
                // Query to select products created by the vendor
                $query = "SELECT * FROM products WHERE product_vendor = '$product_vendor' ORDER BY product_name ASC";
            } else {
                // If session username not set for vendor, don't show any products
                $query = "SELECT * FROM products WHERE 1=0";
            }
        } else {
            // Query to select all products for admins and other users
            $query = "SELECT * FROM products ORDER BY product_name ASC";
        }
    } else {
        // If user is not logged in, don't show any products
        $query = "SELECT * FROM products WHERE 1=0";
    }

    // mysqli select query
    $results = $mysqli->query($query);

    if($results) {

        print '<table class="table table-striped table-hover table-bordered" id="data-table"><thead><tr>

                <th>Product</th>
                <th>Product vendor</th>
                <th>Description</th>
                <th>Price</th>
                <th>Action</th>

              </tr></thead><tbody>';

        while($row = $results->fetch_assoc()) {

            print '<tr>
                    <td>'.$row["product_name"].'</td>
                    <td>'.$row["product_vendor"].'</td>
                    <td>'.$row["product_desc"].'</td>
                    <td>ksh. '.$row["product_price"].'</td>';

            // Check if user is admin or vendor
            if(isset($_SESSION['user_role']) && ($_SESSION['user_role'] == 'admin' || $_SESSION['user_role'] == 'vendor')) {
                // Display edit and delete buttons
                print '<td><a href="product-edit.php?id='.$row["product_id"].'" class="btn btn-primary btn-xs"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></a> <a data-product-id="'.$row['product_id'].'" class="btn btn-danger btn-xs delete-product"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a></td>';
            } else {
                // Otherwise, display empty cell
                print '<td>
				<a href="orders.php?id='.$row["product_id"].'" class="btn btn-success btn-xs" title="Make Orders"><span class="glyphicon glyphicon-shopping-cart" aria-hidden="true"></span></a>
               </td>';

            }

            print '</tr>';
        }

        print '</tbody></table>';

    } else {

        echo "<p>There are no products to display.</p>";

    }

    // Frees the memory associated with a result
    $results->free();

    // close connection 
    $mysqli->close();
}



// get user list
function getUsers() {

	// Connect to the database
	$mysqli = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASS, DATABASE_NAME);

	// output any connection error
	if ($mysqli->connect_error) {
	    die('Error : ('. $mysqli->connect_errno .') '. $mysqli->connect_error);
	}

	// the query
	$query = "SELECT * FROM users ORDER BY username ASC";

	// mysqli select query
	$results = $mysqli->query($query);

	if($results) {

		print '<table class="table table-striped table-hover table-bordered" id="data-table"><thead><tr>

				<th>Username</th>
				<th>Email</th>
				<th>Phone</th>
				<th>Action</th>

			  </tr></thead><tbody>';

		while($row = $results->fetch_assoc()) {

		    print '
			    <tr>
					<td>'.$row["username"].'</td>
				    <td>'.$row["email"].'</td>
				    <td>'.$row["phone"].'</td>
				    <td><a href="user-edit.php?id='.$row["id"].'" class="btn btn-primary btn-xs"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></a> <a data-user-id="'.$row['id'].'" class="btn btn-danger btn-xs delete-user"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a></td>
			    </tr>
		    ';
		}

		print '</tr></tbody></table>';

	} else {

		echo "<p>There are no users to display.</p>";

	}

	// Frees the memory associated with a result
	$results->free();

	// close connection 
	$mysqli->close();
}

// get user list
function getCustomers() {

	// Connect to the database
	$mysqli = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASS, DATABASE_NAME);

	// output any connection error
	if ($mysqli->connect_error) {
	    die('Error : ('. $mysqli->connect_errno .') '. $mysqli->connect_error);
	}

	// the query
	$query = "SELECT * FROM store_customers ORDER BY name ASC";

	// mysqli select query
	$results = $mysqli->query($query);

	if($results) {

		print '<table class="table table-striped table-hover table-bordered" id="data-table"><thead><tr>

				<th>Name</th>
				<th>Email</th>
				<th>Phone</th>
				<th>Action</th>

			  </tr></thead><tbody>';

		while($row = $results->fetch_assoc()) {

		    print '
			    <tr>
					<td>'.$row["name"].'</td>
				    <td>'.$row["email"].'</td>
				    <td>'.$row["phone"].'</td>
				    <td><a href="customer-edit.php?id='.$row["id"].'" class="btn btn-primary btn-xs"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></a> <a data-customer-id="'.$row['id'].'" class="btn btn-danger btn-xs delete-customer"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a></td>
			    </tr>
		    ';
		}

		print '</tr></tbody></table>';

	} else {

		echo "<p>There are no customers to display.</p>";

	}

	// Frees the memory associated with a result
	$results->free();

	// close connection 
	$mysqli->close();
}

?>

