<?php
session_start(); // Start the session

require_once('includes/config.php');

// Check if form data is set
if(isset($_POST['start_date'], $_POST['end_date'], $_POST['status'])) {
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
    if (isset($_SESSION['user_role']) && $_SESSION['user_role'] == 'vendor') {
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
            require('vendor/setasign/fpdf/fpdf.php');
            // Create a new PDF instance
            $pdf = new FPDF();
            $pdf->AddPage();

            // Set font and size
            $pdf->SetFont('Arial', '', 12);

            // Output start and end date
            $pdf->Cell(0, 10, "Report for $start to $end", 0, 1, 'C');

            // Output the table header
            $pdf->Cell(10, 10, 'ID', 1, 0, 'C');
            $pdf->Cell(30, 10, 'Vendor', 1, 0, 'C');
            $pdf->Cell(30, 10, 'Customer', 1, 0, 'C');
            $pdf->Cell(30, 10, 'Product Name', 1, 0, 'C');
            $pdf->Cell(30, 10, 'Invoice Date', 1, 0, 'C');
            $pdf->Cell(20, 10, 'Price', 1, 0, 'C');
            $pdf->Cell(20, 10, 'Quantity', 1, 0, 'C');
            $pdf->Cell(20, 10, 'Amount', 1, 1, 'C'); // Move to the next line after the last column

            // Loop through result set and output data to PDF
            while ($row = mysqli_fetch_assoc($result)) {
                $pdf->Cell(10, 10, $row['invoice'], 1, 0, 'C');
                $pdf->Cell(30, 10, $row['product_vendor'], 1, 0, 'C');
                $pdf->Cell(30, 10, $row['customer_name'], 1, 0, 'C');
                $pdf->Cell(30, 10, $row['product_name'], 1, 0, 'C');
                $pdf->Cell(30, 10, $row['invoice_date'], 1, 0, 'C');
                $pdf->Cell(20, 10, $row['product_price'], 1, 0, 'C');
                $pdf->Cell(20, 10, $row['quantity'], 1, 0, 'C');
                $pdf->Cell(20, 10, $row['total'], 1, 1, 'C'); // Move to the next line after the last column
            }

            // Generate a unique filename for the PDF report
            $filename = 'invoices/report_' . date('YmdHis') . '.pdf';

            // Save the PDF to the specified filename
            $pdf->Output($filename, 'F');

            // Display success message with link to download the PDF
           echo "Report generated successfully! <a href='$filename' target='_blank'>Download PDF</a>";

        } else {
            echo "No invoices found for the specified date range.";
        }
    }
} else {
    echo "Form data is not set.";
}
?>
