<?php
include('header.php');
require_once('includes/config.php');

$start_date = $_POST['start_date'];
$end_date = $_POST['end_date'];
$status = $_POST['status'];

$start_timestamp = date('Y-m-d', strtotime($start_date));
$end_timestamp = date('Y-m-d', strtotime($end_date));

$start = date('jS F,Y', strtotime($start_date));
$end = date('jS F,Y', strtotime($end_date));

$sql = "SELECT invoices.*, orders.*
        FROM invoices
        INNER JOIN orders ON invoices.orders_id = orders.id";

// Check if user role is 'vendor' and adjust the query accordingly
if ($_SESSION['user_role'] == 'vendor') {
    $vendor_name = $_SESSION['login_username']; // Assuming the session variable holds the username of the logged-in user
    if ($status == 'open') {
        $sql .= " WHERE invoices.status = 'open' AND invoices.product_vendor = '$vendor_name'";
    } elseif ($status == 'paid') {
        $sql .= " WHERE invoices.status = 'paid' AND invoices.product_vendor = '$vendor_name'";
    } else {
        $sql .= " WHERE invoices.product_vendor = '$vendor_name'";
    }
} else {
    if ($status == 'open') {
        $sql .= " WHERE invoices.status = 'open' AND invoices.invoice_date BETWEEN '$start_timestamp' AND '$end_timestamp'";
    } elseif ($status == 'paid') {
        $sql .= " WHERE invoices.status = 'paid' AND invoices.invoice_date BETWEEN '$start_timestamp' AND '$end_timestamp'";
    } else {
        $sql .= " WHERE invoices.invoice_date BETWEEN '$start_timestamp' AND '$end_timestamp'";
    }
}

$result = mysqli_query($mysqli, $sql);

if (!$result) {
    echo "Error: " . mysqli_error($mysqli);
} else {
    if (mysqli_num_rows($result) > 0) {
                // Add the image tag for your logo
                echo "<div style='text-align: center;'><img src='images/logo.png' alt='Logo'></div>";
        echo "<h2 style='text-align: center; margin-top: 20px; margin-bottom: 20px;'>Report from $start to $end</h2>";
        echo "<table class='table table-bordered table-striped'>
        <thead>
            <tr>
                <th>Invoice ID</th>
                <th>Product Vendor</th>
                <th>Customer Name</th>
                <th>Product Name</th>
                <th>Invoice Date</th>
                <th>Product Price</th>
                <th>Quantity</th>
                <th>Total Amount</th>
            </tr>
        </thead>
        <tbody>";

        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>
                <td>" . $row['invoice'] . "</td>
                <td>" . $row['product_vendor'] . "</td>
                <td>" . $row['customer_name'] . "</td>
                <td>" . $row['product_name'] . "</td>
                <td>" . $row['invoice_date'] . "</td>
                <td>" . $row['product_price'] . "</td>
                <td>" . $row['quantity'] . "</td>
                <td>" . $row['total'] . "</td>
            </tr>";
        }

        echo "</tbody></table>";

    } else {
        echo "No invoices found for the specified date range.";
    }
}
include('footer.php');
?>
