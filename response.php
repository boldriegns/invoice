<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
// show PHP errors
error_reporting(E_ALL);
//Load Composer's autoloader
require ('vendor/autoload.php');
include_once('includes/config.php');

// output any connection error
if ($mysqli->connect_error) {
    die('Error : ('. $mysqli->connect_errno .') '. $mysqli->connect_error);
}

$action = isset($_POST['action']) ? $_POST['action'] : "";

// Set SMTP Configuration
define('SMTP_HOST', 'smtp.gmail.com'); // Specify main and backup SMTP servers
define('SMTP_PORT', 465); // TCP port to connect to
define('SMTP_USERNAME', 'brianriziki2020@gmail.com'); // SMTP username
define('SMTP_PASSWORD', 'jhkh qnhm rkby lpng'); // SMTP password

if ($action == 'email_invoice') {
    $fileId = $_POST['id'];
    $emailId = $_POST['email'];
    $invoice_type = $_POST['invoice_type'];
    $custom_email = $_POST['custom_email'];

    $mail = new PHPMailer(); // defaults to using php "mail()"

    // Set SMTP configuration
  $mail->IsSMTP(); // Use SMTP
  $mail->SMTPDebug = 0;
  $mail->Debugoutput = 'html';
  $mail->Host = 'smtp.gmail.com'; // Note: Enclosed in single quotes
  $mail->Port = 587;
  $mail->SMTPAuth = true;// Enable SMTP authentication
   $mail->Username = 'brianriziki2020@gmail.com'; // Note: Enclosed in single quotes
   $mail->Password = 'jhkh qnhm rkby lpng'; // Note: Enclosed in single quotes
   $mail->SMTPSecure = 'tls'; // or 'tls', depending on your server configuration
   

    $mail->AddReplyTo(EMAIL_FROM, EMAIL_NAME);
    $mail->SetFrom(EMAIL_FROM, EMAIL_NAME);
    $mail->AddAddress($emailId, "");

    $mail->Subject = EMAIL_SUBJECT;
    //$mail->AltBody = EMAIL_BODY; // optional, comment out and test
    if (empty($custom_email)) {
        if ($invoice_type == 'invoice') {
            $mail->MsgHTML(EMAIL_BODY_INVOICE);
        } else if ($invoice_type == 'quote') {
            $mail->MsgHTML(EMAIL_BODY_QUOTE);
        } else if ($invoice_type == 'receipt') {
            $mail->MsgHTML(EMAIL_BODY_RECEIPT);
        }
    } else {
        $mail->MsgHTML($custom_email);
    }

    $mail->AddAttachment("./invoices/" . $fileId . ".pdf"); // attachment

    if (!$mail->Send()) {
        //if unable to create new record
        echo json_encode(array(
            'status' => 'Error',
            //'message'=> 'There has been an error, please try again.'
            'message' => 'There has been an error, please try again.<pre>' . $mail->ErrorInfo . '</pre>'
        ));
    } else {
        echo json_encode(array(
            'status' => 'Success',
            'message' => 'Invoice has been successfully sent to the customer'
        ));
    }
}


if ($action == 'create_customer') {

    // Invoice customer information
    // Billing
    $customer_name = $_POST['customer_name']; // Customer name
    $customer_email = $_POST['customer_email']; // Customer email
    $customer_address_1 = $_POST['customer_address_1']; // Customer address
    $customer_town = $_POST['customer_town']; // Customer town
    $customer_county = $_POST['customer_county']; // Customer county
    $customer_phone = $_POST['customer_phone']; // Customer phone number

    // Shipping
    $customer_name_ship = $_POST['customer_name_ship']; // Customer name (shipping)
    $customer_address_1_ship = $_POST['customer_address_1_ship']; // Customer address (shipping)
    $customer_town_ship = $_POST['customer_town_ship']; // Customer town (shipping)
    $customer_county_ship = $_POST['customer_county_ship']; // Customer county (shipping)

    // Check if customer already exists
    $query_check = "SELECT id FROM store_customers WHERE email = ?";
    $stmt_check = $mysqli->prepare($query_check);
    $stmt_check->bind_param("s", $customer_email);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();
    $stmt_check->close();

    if ($result_check->num_rows > 0) {
        // Customer already exists
        echo json_encode(array(
            'status' => 'Error',
            'message' => 'Customer with this email already exists!'
        ));
    } else {
        // Insert new customer
        $query = "INSERT INTO store_customers (
                    name,
                    email,
                    address_1,
                    town,
                    county,
                    phone,
                    name_ship,
                    address_1_ship,
                    town_ship,
                    county_ship
                ) VALUES (
                    ?,
                    ?,
                    ?,
                    ?,
                    ?,
                    ?,
                    ?,
                    ?,
                    ?,
                    ?
                )";

        /* Prepare statement */
        $stmt = $mysqli->prepare($query);
        if ($stmt === false) {
            trigger_error('Wrong SQL: ' . $query . ' Error: ' . $mysqli->error, E_USER_ERROR);
        }

        /* Bind parameters. Types: s = string, i = integer, d = double,  b = blob */
        $stmt->bind_param(
            'ssssssssss',
            $customer_name, $customer_email, $customer_address_1, $customer_town, $customer_county,
            $customer_phone, $customer_name_ship, $customer_address_1_ship, $customer_town_ship, $customer_county_ship
        );

        if ($stmt->execute()) {
            // If saving is successful
            echo json_encode(array(
                'status' => 'Success',
                'message' => 'Customer has been created successfully!'
            ));
        } else {
            // If unable to create customer
            echo json_encode(array(
                'status' => 'Error',
                'message' => 'There has been an error, please try again.'
                // Debug
                //'message' => 'There has been an error, please try again.<pre>' . $mysqli->error . '</pre><pre>' . $query . '</pre>'
            ));
        }

        // Close database connection
        $mysqli->close();
    }
}

// Create invoice
if ($action == 'create_invoice') {

	if (isset($_POST['id'])) {
		$id = $_POST['id'];
		// Proceed with the rest of the code
	} else {
		// Handle the case where id is not set
		echo json_encode(array(
			'status' => 'error',
			'message' => 'ID not found in form data'
		));
		exit;
	}

	session_start();
    $product_vendor = $_SESSION['login_username'];
         // invoice customer information
        $customer_name = $_POST['customer_name']; // customer name
        $customer_email = $_POST['customer_email']; // customer email
        $customer_address_1 = $_POST['customer_address_1']; // customer address
        $customer_town = $_POST['customer_town']; // customer town
        $customer_county = $_POST['customer_county']; // customer county
        $customer_phone = $_POST['customer_phone']; // customer phone number
        
        //shipping
        $customer_name_ship = $_POST['customer_name_ship']; // customer name (shipping)
        $customer_address_1_ship = $_POST['customer_address_1_ship']; // customer address (shipping)
        $customer_town_ship = $_POST['customer_town_ship']; // customer town (shipping)
        $customer_county_ship = $_POST['customer_county_ship']; // customer county (shipping)

        // invoice details
        $invoice_number = $_POST['invoice_id']; // invoice number
        $invoice_date = $_POST['invoice_date']; // invoice date
        $invoice_due_date = $_POST['invoice_due_date']; // invoice due date
        $invoice_subtotal = $_POST['invoice_subtotal']; // invoice sub-total
        $invoice_shipping = $_POST['invoice_shipping']; // invoice shipping amount
        $invoice_vat = $_POST['invoice_vat']; // invoice vat
        $invoice_total = $_POST['invoice_total']; // invoice total
        $invoice_type = $_POST['invoice_type']; // Invoice type
        $invoice_status = $_POST['invoice_status']; // Invoice status


	    // Prepare the first query for invoices insertion
		$query1 = "INSERT INTO invoices (invoice,orders_id,product_vendor, invoice_date, invoice_due_date, subtotal, shipping, vat, total, invoice_type, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
		$stmt1 = $mysqli->prepare($query1);
		$stmt1->bind_param("sssssssssss", $invoice_number,$id, $product_vendor, $invoice_date, $invoice_due_date, $invoice_subtotal, $invoice_shipping, $invoice_vat, $invoice_total, $invoice_type, $invoice_status);
	
		// Execute the first query
		$stmt1->execute();
	
		// Check for errors in the first query
		if ($stmt1->error) {
			echo json_encode(array(
				'status' => 'error',
				'message' => 'Error inserting data: ' . $stmt1->error
			));
			exit; // Stop further execution
		}
	
		// Prepare the second query for customers insertion
		$query2 = "INSERT INTO customers (invoice, name, email, address_1, town, county, phone, name_ship, address_1_ship, town_ship, county_ship) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
		$stmt2 = $mysqli->prepare($query2);
		$stmt2->bind_param("sssssssssss", $invoice_number, $customer_name, $customer_email, $customer_address_1, $customer_town, $customer_county, $customer_phone, $customer_name_ship, $customer_address_1_ship, $customer_town_ship, $customer_county_ship);
	
		// Execute the second query
		$stmt2->execute();
	
		// Check for errors in the second query
		if ($stmt2->error) {
			echo json_encode(array(
				'status' => 'error',
				'message' => 'Error inserting data: ' . $stmt2->error
			));
			exit; // Stop further execution
		}
	
		// Prepare the third query for invoice items insertion
		$invoice_product = $_POST["invoice_product"];
		$invoice_product_qty = $_POST["invoice_product_qty"];
		$invoice_product_price = $_POST["invoice_product_price"];
		$invoice_product_discount = $_POST["invoice_product_discount"];
		$invoice_product_sub = $_POST["invoice_product_sub"];
	
		// Loop through each invoice item
		foreach ($invoice_product as $key => $value) {
			$product = $invoice_product[$key];
			$qty = $invoice_product_qty[$key];
			$price = $invoice_product_price[$key];
			$discount = $invoice_product_discount[$key];
			$sub_total = $invoice_product_sub[$key];
	
			// Insert invoice item data into the database
			$query3 = "INSERT INTO invoice_items (invoice, product, qty, price, discount, subtotal) VALUES (?, ?, ?, ?, ?, ?)";
			$stmt3 = $mysqli->prepare($query3);
			$stmt3->bind_param("ssssss", $invoice_number, $product, $qty, $price, $discount, $sub_total);
			$stmt3->execute();
	
			// Check for errors in the third query
			if ($stmt3->error) {
				echo json_encode(array(
					'status' => 'error',
					'message' => 'Error inserting data: ' . $stmt3->error
				));
				exit; // Stop further execution
			}
			$stmt3->close();
		}
		// If all queries executed successfully, commit the transaction
		$mysqli->commit();
       // Update the status in the orders table
        $updateQuery = "UPDATE orders SET status = 'processed' WHERE id = ?";
        $stmt = $mysqli->prepare($updateQuery);
        $stmt->bind_param("i", $id);
        $stmt->execute();
         // Check for errors
        if ($stmt->error) {
        // Handle error
		echo json_encode(array(
			'status' => 'error',
			'message' => 'Error updating order status'
		));
       } else {
     // Invoice created successfully
	 echo json_encode(array(
        'status' => 'success',
        'message' => 'Invoice created successfully'
    ));
      }
		$stmt1->close();
		$stmt2->close();

		ob_start();
	    // Create new PDF instance
		require('generator.php');
		$pdf = new PDF();
		$pdf->AddPage();
		// Add content
// Label for Invoice Information/Details
$pdf->SetFont('Arial', 'B', 16); // Set font to bold and size 12
$pdf->Cell(0, 10, 'Invoice Information/Details', 0, 1, 'L');
// Invoice details
$pdf->SetFont('Arial', '', 14); // Set font to regular and size 10
$pdf->Cell(0,10,'product description : ' . $product,0,1);
$pdf->Cell(0,10,'product quantity : ' . $qty,0,1);
$pdf->Cell(0,10,'Invoice_date :  ' .  $invoice_date,0,1);
$pdf->Cell(0,10,'Invoice_due_date : ' . $invoice_due_date,0,1);
$pdf->Cell(0,10,'Total Cost : Ksh ' . $invoice_subtotal,0,1);
$pdf->Cell(0,10,'V.A.T : Ksh ' .  $invoice_vat,0,1);
$pdf->Cell(0,10,'Shipping Cost : Ksh ' .  $invoice_shipping,0,1);
$pdf->Cell(0,10,'Discount Allowed : Ksh ' .  $discount,0,1);
$pdf->Cell(0,10,'Total to be Paid : Ksh ' . $invoice_total,0,1);


// Add a line break between sections
$pdf->Ln(10);
// Label for Customer Information/Details
$pdf->SetFont('Arial', 'B', 16); // Set font to bold and size 12
$pdf->Cell(0, 10, 'Customer Information/Details', 0, 1, 'L');

// Customer details
$pdf->SetFont('Arial', '', 14); // Set font to regular and size 10
$pdf->Cell(0,10,'Customer Name : ' . $customer_name,0,1);
$pdf->Cell(0,10,'Customer Email : ' . $customer_email,0,1);
$pdf->Cell(0,10,'Customer Address : ' . $customer_address_1,0,1);
$pdf->Cell(0,10,'Customer Town/Location : ' . $customer_town,0,1);
$pdf->Cell(0,10,'Customer County : ' . $customer_county,0,1);
$pdf->Cell(0,10,'Customer Phone Number : ' . $customer_phone,0,1);
if ($invoice_status === 'paid') {
	$pdfWidth = $pdf->GetPageWidth();
	$pdfHeight = $pdf->GetPageHeight();
	$imageWidth = 100; // Width of the image
	$imageHeight = 50; // Height of the image
	$x = $pdfWidth - $imageWidth - 10;
	$y = $pdfHeight - $imageHeight - 20; // Adjust as needed
	$pdf->Image('images/paid.jpg', $x, $y, $imageWidth, $imageHeight);
}else{
	$pdfWidth = $pdf->GetPageWidth();
	$pdfHeight = $pdf->GetPageHeight();
	$imageWidth = 100; // Width of the image
	$imageHeight = 50; // Height of the image
	$x = $pdfWidth - $imageWidth - 10;
	$y = $pdfHeight - $imageHeight - 20; // Adjust as needed
	$pdf->Image('images/open.jpg', $x, $y, $imageWidth, $imageHeight);
}
	
	    $pdfPath = 'invoices/' . $invoice_number . '.pdf';
		ob_end_flush();
         if ($pdf->Output($pdfPath, 'F')) {
        // Provide download link
        echo 'Invoice generated successfully! <a href="' . $pdfPath . '">Download Invoice</a>';

       }


}

// Adding new product
if($action == 'delete_invoice') {

	// output any connection error
	if ($mysqli->connect_error) {
	    die('Error : ('. $mysqli->connect_errno .') '. $mysqli->connect_error);
	}

	$id = $_POST["delete"];

	// the query
	$query = "DELETE FROM invoices WHERE invoice = ".$id.";";
	$query .= "DELETE FROM customers WHERE invoice = ".$id.";";
	$query .= "DELETE FROM invoice_items WHERE invoice = ".$id.";";

	$file_path = 'invoices/'.$id.'.pdf';
   if (file_exists($file_path)) {
    unlink($file_path);
   }


	if($mysqli -> multi_query($query)) {
	    //if saving success
		echo json_encode(array(
			'status' => 'Success',
			'message'=> 'The invoice has been deleted successfully!'
		));

	} else {
	    //if unable to create new record
	    echo json_encode(array(
	    	'status' => 'Error',
	    	//'message'=> 'There has been an error, please try again.'
	    	'message' => 'There has been an error, please try again.<pre>'.$mysqli->error.'</pre><pre>'.$query.'</pre>'
	    ));
	}

	// close connection 
	$mysqli->close();

}

// Adding new product
if($action == 'update_customer') {

	// output any connection error
	if ($mysqli->connect_error) {
	    die('Error : ('. $mysqli->connect_errno .') '. $mysqli->connect_error);
	}

	$getID = $_POST['id']; // id

	// invoice customer information
	// billing
	$customer_name = $_POST['customer_name']; // customer name
	$customer_email = $_POST['customer_email']; // customer email
	$customer_address_1 = $_POST['customer_address_1']; // customer address
	$customer_town = $_POST['customer_town']; // customer town
	$customer_county = $_POST['customer_county']; // customer county
	$customer_phone = $_POST['customer_phone']; // customer phone number
	
	//shipping
	$customer_name_ship = $_POST['customer_name_ship']; // customer name (shipping)
	$customer_address_1_ship = $_POST['customer_address_1_ship']; // customer address (shipping)
	$customer_town_ship = $_POST['customer_town_ship']; // customer town (shipping)
	$customer_county_ship = $_POST['customer_county_ship']; // customer county (shipping)

	// the query
	$query = "UPDATE store_customers SET
				name = ?,
				email = ?,
				address_1 = ?,
				town = ?,
				county = ?,
				phone = ?,
				name_ship = ?,
				address_1_ship = ?,
				town_ship = ?,
				county_ship = ?

				WHERE id = ?

			";

	/* Prepare statement */
	$stmt = $mysqli->prepare($query);
	if($stmt === false) {
	  trigger_error('Wrong SQL: ' . $query . ' Error: ' . $mysqli->error, E_USER_ERROR);
	}

	/* Bind parameters. TYpes: s = string, i = integer, d = double,  b = blob */
	$stmt->bind_param(
		'sssssssssss',
		$customer_name,$customer_email,$customer_address_1,$customer_town,$customer_county,
		$customer_phone,$customer_name_ship,$customer_address_1_ship,$customer_town_ship,$customer_county_ship,$getID);

	//execute the query
	if($stmt->execute()){
	    //if saving success
		echo json_encode(array(
			'status' => 'Success',
			'message'=> 'Customer has been updated successfully!'
		));

	} else {
	    //if unable to create new record
	    echo json_encode(array(
	    	'status' => 'Error',
	    	//'message'=> 'There has been an error, please try again.'
	    	'message' => 'There has been an error, please try again.<pre>'.$mysqli->error.'</pre><pre>'.$query.'</pre>'
	    ));
	}

	//close database connection
	$mysqli->close();
	
}

// Update product
if($action == 'update_product') {

	// output any connection error
	if ($mysqli->connect_error) {
	    die('Error : ('. $mysqli->connect_errno .') '. $mysqli->connect_error);
	}

	session_start();
    $product_vendor = $_SESSION['login_username'];

	// invoice product information
	$getID = $_POST['id']; // id
	$product_name = $_POST['product_name']; // product name
	$product_desc = $_POST['product_desc']; // product desc
	$product_price = $_POST['product_price']; // product price

	// the query
	$query = "UPDATE products SET
				product_name = ?,
				product_desc = ?,
				product_vendor = ?,
				product_price = ?
			 WHERE product_id = ?
			";

	/* Prepare statement */
	$stmt = $mysqli->prepare($query);
	if($stmt === false) {
	  trigger_error('Wrong SQL: ' . $query . ' Error: ' . $mysqli->error, E_USER_ERROR);
	}

	/* Bind parameters. TYpes: s = string, i = integer, d = double,  b = blob */
	$stmt->bind_param(
		'sssss',
		$product_name,$product_desc,$product_vendor,$product_price,$getID
	);

	//execute the query
	if($stmt->execute()){
	    //if saving success
		echo json_encode(array(
			'status' => 'Success',
			'message'=> 'Product has been updated successfully!'
		));

	} else {
	    //if unable to create new record
	    echo json_encode(array(
	    	'status' => 'Error',
	    	//'message'=> 'There has been an error, please try again.'
	    	'message' => 'There has been an error, please try again.<pre>'.$mysqli->error.'</pre><pre>'.$query.'</pre>'
	    ));
	}

	//close database connection
	$mysqli->close();
	
}


// Adding new product
if($action == 'update_invoice') {

	session_start();
    $product_vendor = $_SESSION['login_username'];

    // output any connection error
    if ($mysqli->connect_error) {
        die('Error : ('. $mysqli->connect_errno .') '. $mysqli->connect_error);
    }

   
	$id = $_POST["update_id"]; // Assuming this contains the ID of the invoice being updated

// Retrieve the existing 'orders_id' associated with the invoice being updated
$query_orders_id = "SELECT orders_id FROM invoices WHERE invoice = ?";
$stmt_orders_id = $mysqli->prepare($query_orders_id);
$stmt_orders_id->bind_param("s", $id);
$stmt_orders_id->execute();
$stmt_orders_id->bind_result($orders_id);
$stmt_orders_id->fetch();
$stmt_orders_id->close();

// Check if retrieval was successful
if ($orders_id === null) {
    echo json_encode(array(
        'status' => 'error',
        'message' => 'Failed to retrieve orders_id'
    ));
    exit; // Stop further execution
}

	$mysqli->begin_transaction();

    // Delete existing invoice file
    $pdfPath = 'invoices/' . $id . '.pdf';
    if (file_exists($pdfPath)) {
        unlink($pdfPath);
    }

 // the query to delete existing records
// Prepare the delete query
$query = "DELETE invoices, customers, invoice_items
          FROM invoices
          JOIN customers ON invoices.invoice = customers.invoice
          JOIN invoice_items ON invoices.invoice = invoice_items.invoice
          WHERE invoices.invoice = ?";

   
$stmt = $mysqli->prepare($query);

// Bind the parameter
$stmt->bind_param("s", $id);

    // Execute the delete query
    // Execute the query
$stmt->execute();
     


    // Check if deletion was successful
    if ($stmt->affected_rows > 0) {
        // Insert updated values

             // invoice customer information
			 $customer_name = $_POST['customer_name']; // customer name
			 $customer_email = $_POST['customer_email']; // customer email
			 $customer_address_1 = $_POST['customer_address_1']; // customer address
			 $customer_town = $_POST['customer_town']; // customer town
			 $customer_county = $_POST['customer_county']; // customer county
			 $customer_phone = $_POST['customer_phone']; // customer phone number
			 
			 //shipping
			 $customer_name_ship = $_POST['customer_name_ship']; // customer name (shipping)
			 $customer_address_1_ship = $_POST['customer_address_1_ship']; // customer address (shipping)
			 $customer_town_ship = $_POST['customer_town_ship']; // customer town (shipping)
			 $customer_county_ship = $_POST['customer_county_ship']; // customer county (shipping)
	 
			 // invoice details
			 $invoice_number = $_POST['invoice_id']; // invoice number
			 $invoice_date = $_POST['invoice_date']; // invoice date
			 $invoice_due_date = $_POST['invoice_due_date']; // invoice due date
			 $invoice_subtotal = $_POST['invoice_subtotal']; // invoice sub-total
			 $invoice_shipping = $_POST['invoice_shipping']; // invoice shipping amount
			 $invoice_vat = $_POST['invoice_vat']; // invoice vat
			 $invoice_total = $_POST['invoice_total']; // invoice total
			 $invoice_type = $_POST['invoice_type']; // Invoice type
			 $invoice_status = $_POST['invoice_status']; // Invoice status

        // Prepare the first query for invoices insertion
	
        $query1 = "INSERT INTO invoices (invoice, orders_id, product_vendor, invoice_date, invoice_due_date, subtotal, shipping, 
            vat, total, invoice_type, status)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt1 = $mysqli->prepare($query1);
        $stmt1->bind_param("sssssssssss", $invoice_number, $orders_id, $product_vendor, $invoice_date, $invoice_due_date, 
            $invoice_subtotal, $invoice_shipping, $invoice_vat, $invoice_total, $invoice_type, $invoice_status);

        // Execute the first query
        $stmt1->execute();

        // Prepare the second query for customers insertion
        $query2 = "INSERT INTO customers (invoice, name, email, address_1, town, county, phone,name_ship, address_1_ship, town_ship, county_ship)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt2 = $mysqli->prepare($query2);
        $stmt2->bind_param("sssssssssss", $invoice_number, $customer_name, $customer_email, $customer_address_1, 
           $customer_town, $customer_county, $customer_phone, 
            $customer_name_ship, $customer_address_1_ship, $customer_town_ship, 
            $customer_county_ship);

        // Execute the second query
        $stmt2->execute();

        $invoice_product = $_POST["invoice_product"];
        $invoice_product_qty = $_POST["invoice_product_qty"];
        $invoice_product_price = $_POST["invoice_product_price"];
        $invoice_product_discount = $_POST["invoice_product_discount"];
        $invoice_product_sub = $_POST["invoice_product_sub"];

        // Loop through each invoice item
        foreach ($invoice_product as $key => $value) {
            $product = $invoice_product[$key];
            $qty = $invoice_product_qty[$key];
            $price = $invoice_product_price[$key];
            $discount = $invoice_product_discount[$key];
            $sub_total = $invoice_product_sub[$key];
                    // Insert invoice item data into the database
                    $query3 = "INSERT INTO invoice_items (invoice, product, qty, price, discount, subtotal) VALUES (?, ?, ?, ?, ?, ?)";
                    $stmt3 = $mysqli->prepare($query3);
                    $stmt3->bind_param("ssssss", $invoice_number, $product, $qty, $price, $discount, $sub_total);

                    $stmt3->execute();
                    
                }
                header('Content-Type: application/json');

        // Check if all queries executed successfully
        if ($stmt1->affected_rows > 0 && $stmt2->affected_rows > 0 && $stmt3->affected_rows > 0) {
            // If all queries executed successfully, commit the transaction
            $mysqli->commit();
            echo json_encode(array(
                'status' => 'success',
                'message' => 'Invoice updated successfully'
            ));
            		
		
        } else {
            // If an error occurred, rollback the transaction
            $mysqli->rollback();
            echo json_encode(array(
                'status' => 'error',
                'message' => 'Error inserting data: ' . $mysqli->error
            ));
        }

        // Close the statements
        $stmt1->close();
        $stmt2->close();
        $stmt3->close();

    } 

    // Close the statement for deletion
    $stmt->close();

	ob_start();
	// Create new PDF instance
	require('generator.php');
	$pdf = new PDF();
	$pdf->AddPage();
		// Add content
// Label for Invoice Information/Details
    $pdf->SetFont('Arial', 'B', 16); // Set font to bold and size 12
    $pdf->Cell(0, 10, 'Invoice Information/Details', 0, 1, 'L');
	// Invoice details
    $pdf->SetFont('Arial', '', 14); // Set font to regular and size 10
	$pdf->Cell(0,10,'product description : ' . $product,0,1);
	$pdf->Cell(0,10,'product quantity : ' . $qty,0,1);
	$pdf->Cell(0,10,'Invoice_date :  ' .  $invoice_date,0,1);
	$pdf->Cell(0,10,'Invoice_due_date : ' . $invoice_due_date,0,1);
	$pdf->Cell(0,10,'Total Cost : Ksh ' . $invoice_subtotal,0,1);
	$pdf->Cell(0,10,'Shipping Cost : Ksh ' .  $invoice_shipping,0,1);
	$pdf->Cell(0,10,'Discount Allowed : Ksh ' .  $discount,0,1);
	$pdf->Cell(0,10,'V.A.T : Ksh ' .  $invoice_vat,0,1);
	$pdf->Cell(0,10,'Total to be Paid : Ksh ' . $invoice_total,0,1);
	

	// Add a line break between sections
    $pdf->Ln(10);
	// Label for Customer Information/Details
    $pdf->SetFont('Arial', 'B', 16); // Set font to bold and size 12
    $pdf->Cell(0, 10, 'Customer Information/Details', 0, 1, 'L');

    // Customer details
    $pdf->SetFont('Arial', '', 14); // Set font to regular and size 10
    $pdf->Cell(0,10,'Customer Name : ' . $customer_name,0,1);
	$pdf->Cell(0,10,'Customer Email : ' . $customer_email,0,1);
	$pdf->Cell(0,10,'Customer Address : ' . $customer_address_1,0,1);
	$pdf->Cell(0,10,'Customer Town/Location : ' . $customer_town,0,1);
	$pdf->Cell(0,10,'Customer County : ' . $customer_county,0,1);
	$pdf->Cell(0,10,'Customer Phone Number : ' . $customer_phone,0,1);

	if ($invoice_status === 'paid') {
		$pdfWidth = $pdf->GetPageWidth();
		$pdfHeight = $pdf->GetPageHeight();
		$imageWidth = 100; // Width of the image
		$imageHeight = 50; // Height of the image
		$x = $pdfWidth - $imageWidth - 10;
		$y = $pdfHeight - $imageHeight - 20; // Adjust as needed
		$pdf->Image('images/paid.jpg', $x, $y, $imageWidth, $imageHeight);
	}else{
		$pdfWidth = $pdf->GetPageWidth();
		$pdfHeight = $pdf->GetPageHeight();
		$imageWidth = 100; // Width of the image
		$imageHeight = 50; // Height of the image
		$x = $pdfWidth - $imageWidth - 10;
		$y = $pdfHeight - $imageHeight - 20; // Adjust as needed
		$pdf->Image('images/open.jpg', $x, $y, $imageWidth, $imageHeight);
	}

	$pdfPath = 'invoices/' . $invoice_number . '.pdf';
	ob_end_flush();
	 if ($pdf->Output($pdfPath, 'F')) {
	// Provide download link
	echo 'Invoice generated successfully! <a href="' . $pdfPath . '">Download Invoice</a>';

   }

    //close database connection
    $mysqli->close();
	
}

// Adding new product
if($action == 'delete_order') {

	// output any connection error
	if ($mysqli->connect_error) {
	    die('Error : ('. $mysqli->connect_errno .') '. $mysqli->connect_error);
	}

	$id = $_POST["product_id"];


	// the query
	$query = "DELETE FROM orders WHERE product_id = ?";

	/* Prepare statement */
	$stmt = $mysqli->prepare($query);
	if($stmt === false) {
	  trigger_error('Wrong SQL: ' . $query . ' Error: ' . $mysqli->error, E_USER_ERROR);
	}

	/* Bind parameters. TYpes: s = string, i = integer, d = double,  b = blob */
	$stmt->bind_param('s',$id);

	//execute the query
	if($stmt->execute()){
	    //if saving success
		echo json_encode(array(
			'status' => 'Success',
			'message'=> 'order has been deleted successfully!'
		));

	} else {
	    //if unable to create new record
	    echo json_encode(array(
	    	'status' => 'Error',
	    	//'message'=> 'There has been an error, please try again.'
	    	'message' => 'There has been an error, please try again.<pre>'.$mysqli->error.'</pre><pre>'.$query.'</pre>'
	    ));
	}

	// close connection 
	$mysqli->close();

}


// Adding new product
if($action == 'delete_product') {

	// output any connection error
	if ($mysqli->connect_error) {
	    die('Error : ('. $mysqli->connect_errno .') '. $mysqli->connect_error);
	}

	$id = $_POST["delete"];

	// the query
	$query = "DELETE FROM products WHERE product_id = ?";

	/* Prepare statement */
	$stmt = $mysqli->prepare($query);
	if($stmt === false) {
	  trigger_error('Wrong SQL: ' . $query . ' Error: ' . $mysqli->error, E_USER_ERROR);
	}

	/* Bind parameters. TYpes: s = string, i = integer, d = double,  b = blob */
	$stmt->bind_param('s',$id);

	//execute the query
	if($stmt->execute()){
	    //if saving success
		echo json_encode(array(
			'status' => 'Success',
			'message'=> 'Product has been deleted successfully!'
		));

	} else {
	    //if unable to create new record
	    echo json_encode(array(
	    	'status' => 'Error',
	    	//'message'=> 'There has been an error, please try again.'
	    	'message' => 'There has been an error, please try again.<pre>'.$mysqli->error.'</pre><pre>'.$query.'</pre>'
	    ));
	}

	// close connection 
	$mysqli->close();

}
// Login to system
if($action == 'login') {

	// output any connection error
	if ($mysqli->connect_error) {
	    die('Error : ('. $mysqli->connect_errno .') '. $mysqli->connect_error);
	}

	session_start();

    extract($_POST);

    $username = $_POST['username'];
    $pass_encrypt = md5($_POST['password']);

    $query = "SELECT * FROM `users` WHERE username='$username' AND `password` = '$pass_encrypt'";

    $results = mysqli_query($mysqli,$query) or die (mysqli_error());
    $count = mysqli_num_rows($results);

    if($count != 0) { // Check if user exists

        $row = $results->fetch_assoc();
        $user_role = $row['role']; // Fetch user role

		$_SESSION['login_username'] = $row['username'];
        $_SESSION['user_role'] = $user_role; // Store user role in session

		echo json_encode(array(
			'status' => 'Success',
			'message'=> 'Login was a success! Transfering you to the system now, hold tight!',
			'user_role' => $user_role // Send user role in response
		));
    } else {
    	echo json_encode(array(
	    	'status' => 'Error',
	    	'message' => 'Login incorrect, does not exist or simply a problem! Try again!'
	    ));
    }
}

// Check if the action is to add a product
if ($_POST['action'] == 'add_product') {
    // Fetch product details from POST data
    $product_name = $_POST['product_name'];
    $product_desc = $_POST['product_desc'];
    $product_price = $_POST['product_price'];
    
    // Fetch the user's role (assuming it's stored in the session)
    session_start();
    $product_vendor = $_SESSION['login_username']; // Adjust this according to how you store user role

// Check if the product already exists
$check_query = "SELECT COUNT(*) AS count FROM products WHERE product_name = ? AND product_vendor = ?";
$check_stmt = $mysqli->prepare($check_query);
$check_stmt->bind_param('ss', $product_name, $product_vendor);
$check_stmt->execute();
$check_result = $check_stmt->get_result();
$row = $check_result->fetch_assoc();
$product_count = $row['count'];


    // If the product doesn't exist, insert it
    if ($product_count == 0) {
        $query = "INSERT INTO products
                (
                    product_name,
                    product_desc,
                    product_vendor,
                    product_price
                )
                VALUES (
                    ?, 
                    ?,
                    ?,
                    ?
                )";

        header('Content-Type: application/json');

        /* Prepare statement */
        $stmt = $mysqli->prepare($query);
        if ($stmt === false) {
            trigger_error('Wrong SQL: ' . $query . ' Error: ' . $mysqli->error, E_USER_ERROR);
        }

        /* Bind parameters. Types: s = string, i = integer, d = double, b = blob */
        $stmt->bind_param('sssd', $product_name, $product_desc, $product_vendor, $product_price);

        if ($stmt->execute()) {
            // If saving success
            echo json_encode(array(
                'status' => 'Success',
                'message' => 'Product has been added successfully!'
            ));
        } else {
            // If unable to create new record
            echo json_encode(array(
                'status' => 'Error',
                'message' => 'There has been an error, please try again.<pre>' . $mysqli->error . '</pre><pre>' . $query . '</pre>'
            ));
        }
    } else {
        // Product already exists
        echo json_encode(array(
            'status' => 'Error',
            'message' => 'Product already exists.'
        ));
    }

    // Close database connection
    $mysqli->close();
}

if ($action == 'make_order') {
    // Connect to the database
    $mysqli = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASS, DATABASE_NAME);

    // Check connection
    if ($mysqli->connect_error) {
        die("Connection failed: " . $mysqli->connect_error);
    }

    // Retrieve customer name from the form submission
    $user_name = $_POST['customer_name'];
	$quantity = $_POST['quantity'];

    // Check if the user is an admin or a customer (You need to implement your own authentication mechanism)
    $is_admin = false; // Example: You need to set this to true if the user is an admin

    // Prepare statement for product retrieval
    $sql_product = "SELECT product_name, product_price FROM products WHERE product_id = ?";
    $stmt_product = $mysqli->prepare($sql_product);
    $stmt_product->bind_param("s", $product_id);

    // Retrieve selected products and quantities
    if (isset($_POST['quantity']) && is_array($_POST['quantity'])) {
        foreach ($_POST['quantity'] as $product_id => $quantity) {
            // Validate quantity (you might want to add more validation)
            $quantity = intval($quantity);
            if ($quantity > 0) {
       // Check if the order already exists for the same customer name, product name, quantity, and status
         $sql_check_order = "SELECT * FROM orders WHERE customer_name = ? AND product_name = ? AND quantity = ? AND status = ?";
         $stmt_check_order = $mysqli->prepare($sql_check_order);
         $stmt_check_order->bind_param("ssis", $user_name, $product_name, $quantity, $status);
         $status = "pending"; // Assuming status is always "pending" for new orders
         $stmt_check_order->execute();
         $result_check_order = $stmt_check_order->get_result();

                if ($result_check_order->num_rows > 0) {
                    // If the order already exists, return an error message
                    echo json_encode(array(
                        'status' => 'Error',
                        'message' => 'Order already exists for the selected customer and quantity'
                    ));
                } else {
                    // Execute product retrieval statement
                    $stmt_product->execute();
                    $result_product = $stmt_product->get_result();

                    if ($result_product->num_rows > 0) {
                        $product = $result_product->fetch_assoc();

                        // Insert into order table
                        $product_name = $product['product_name'];
                        $product_price = $product['product_price'];
                        $sql_insert = "INSERT INTO orders (product_id, customer_name, product_name, product_price, quantity) VALUES (?, ?, ?, ?, ?)";

                        // Prepare statement for insertion
                        $stmt_insert = $mysqli->prepare($sql_insert);
                        $stmt_insert->bind_param("ssssi", $product_id, $user_name, $product_name, $product_price, $quantity);
                        $result_insert = $stmt_insert->execute();

                        if ($result_insert) {
                            // If saving succeeded
                            echo json_encode(array(
                                'status' => 'Success',
                                'message' => 'The order submitted successfully'
                            ));
                        } else {
                            echo json_encode(array(
                                'status' => 'Error',
                                'message' => 'The order submission failed'
                            ));
                        }
                    } else {
                        // If unable to retrieve product details, log the error message
                        echo json_encode(array(
                            'status' => 'Error',
                            'message' => 'Failed to retrieve product details'
                        ));
                    }
                }
            }
        }
    }

    // Close prepared statements
    $stmt_check_order->close();
    $stmt_product->close();

    // Close database connection
    $mysqli->close();
}



if ($action == 'add_user') {

    $user_username = $_POST['username'];
    $user_email = $_POST['email'];
    $user_phone = $_POST['phone'];
	$user_customer_address_1 = $_POST['customer_address_1'];
    $user_customer_county = $_POST['customer_county'];
    $user_customer_town = $_POST['customer_town'];
    $role = $_POST['role'];
    $user_password = $_POST['password'];

    // Check if the user already exists
    $check_query = "SELECT COUNT(*) AS count FROM users WHERE username = ? OR email = ?";
    $check_stmt = $mysqli->prepare($check_query);
    $check_stmt->bind_param('ss', $user_username, $user_email);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();
    $row = $check_result->fetch_assoc();
    $user_count = $row['count'];

    // If the user doesn't exist, insert it
    if ($user_count == 0) {
        //our insert query query
        $query = "INSERT INTO users
                (
                    username,
                    email,
                    phone,
					address_1,
					county,
					town,
                    role,
                    password
                )
                VALUES (
                    ?,
					?,
					?,
					?, 
                    ?,
                    ?,
                    ?,
                    ?
                )";

        header('Content-Type: application/json');

        /* Prepare statement */
        $stmt = $mysqli->prepare($query);
        if ($stmt === false) {
            trigger_error('Wrong SQL: ' . $query . ' Error: ' . $mysqli->error, E_USER_ERROR);
        }

        $user_password = md5($user_password);
        /* Bind parameters. TYpes: s = string, i = integer, d = double,  b = blob */
        $stmt->bind_param('ssssssss', $user_username, $user_email, $user_phone,$user_customer_address_1,$user_customer_county, $user_customer_town, $role, $user_password);

        if ($stmt->execute()) {
            //if saving success
            echo json_encode(array(
                'status' => 'Success',
                'message' => 'User has been added successfully!'
            ));
        } else {
            //if unable to create new record
            echo json_encode(array(
                'status' => 'Error',
                'message' => 'There has been an error, please try again.<pre>' . $mysqli->error . '</pre><pre>' . $query . '</pre>'
            ));
        }
    } else {
        // User already exists
        echo json_encode(array(
            'status' => 'Error',
            'message' => 'User already exists.'
        ));
    }

    //close database connection
    $mysqli->close();
}

// Update product
if($action == 'update_user') {

	// output any connection error
	if ($mysqli->connect_error) {
	    die('Error : ('. $mysqli->connect_errno .') '. $mysqli->connect_error);
	}

	// user information
	$getID = $_POST['id']; // id
	$username = $_POST['username']; // username
	$email = $_POST['email']; // email
	$phone = $_POST['phone']; // phone
	$customer_address_1 = $_POST['customer_address_1'];
    $customer_county = $_POST['customer_county'];
    $customer_town = $_POST['customer_town'];
	$role = $_POST['role'];
	$password = $_POST['password']; // password

	if($password == ''){
		// the query
		$query = "UPDATE users SET
					username = ?,
					email = ?,
					phone = ?,
					address_1 = ?,
					county = ?,
					town = ?,
					role = ?
				 WHERE id = ?
				";
	} else {
		// the query
		$query = "UPDATE users SET
					username = ?,
					email = ?,
					phone = ?,
					address_1 = ?,
					county = ?,
					town = ?,
					role = ?,
					password =?
				 WHERE id = ?
				";
	}

	/* Prepare statement */
	$stmt = $mysqli->prepare($query);
	if($stmt === false) {
	  trigger_error('Wrong SQL: ' . $query . ' Error: ' . $mysqli->error, E_USER_ERROR);
	}

	if($password == ''){
		/* Bind parameters. TYpes: s = string, i = integer, d = double,  b = blob */
		$stmt->bind_param(
			'ssssssss',
			$username,$email,$phone,$customer_address_1,$customer_county, $customer_town,$role,$getID
		);
	} else {
		$password = md5($password);
		/* Bind parameters. TYpes: s = string, i = integer, d = double,  b = blob */
		$stmt->bind_param(
			'sssssssss',
			$username,$email,$phone,$customer_address_1,$customer_county, $customer_town,$role,$password,$getID
		);
	}

	//execute the query
	if($stmt->execute()){
	    //if saving success
		echo json_encode(array(
			'status' => 'Success',
			'message'=> 'User has been updated successfully!'
		));

	} else {
	    //if unable to create new record
	    echo json_encode(array(
	    	'status' => 'Error',
	    	//'message'=> 'There has been an error, please try again.'
	    	'message' => 'There has been an error, please try again.<pre>'.$mysqli->error.'</pre><pre>'.$query.'</pre>'
	    ));
	}

	//close database connection
	$mysqli->close();
	
}

// Delete User
if($action == 'delete_user') {

	// output any connection error
	if ($mysqli->connect_error) {
	    die('Error : ('. $mysqli->connect_errno .') '. $mysqli->connect_error);
	}

	$id = $_POST["delete"];

	// the query
	$query = "DELETE FROM users WHERE id = ?";

	/* Prepare statement */
	$stmt = $mysqli->prepare($query);
	if($stmt === false) {
	  trigger_error('Wrong SQL: ' . $query . ' Error: ' . $mysqli->error, E_USER_ERROR);
	}

	/* Bind parameters. TYpes: s = string, i = integer, d = double,  b = blob */
	$stmt->bind_param('s',$id);

	if($stmt->execute()){
	    //if saving success
		echo json_encode(array(
			'status' => 'Success',
			'message'=> 'User has been deleted successfully!'
		));

	} else {
	    //if unable to create new record
	    echo json_encode(array(
	    	'status' => 'Error',
	    	//'message'=> 'There has been an error, please try again.'
	    	'message' => 'There has been an error, please try again.<pre>'.$mysqli->error.'</pre><pre>'.$query.'</pre>'
	    ));
	}

	// close connection 
	$mysqli->close();

}

// Delete User
if($action == 'delete_customer') {

	// output any connection error
	if ($mysqli->connect_error) {
	    die('Error : ('. $mysqli->connect_errno .') '. $mysqli->connect_error);
	}

	$id = $_POST["delete"];

	// the query
	$query = "DELETE FROM store_customers WHERE id = ?";

	/* Prepare statement */
	$stmt = $mysqli->prepare($query);
	if($stmt === false) {
	  trigger_error('Wrong SQL: ' . $query . ' Error: ' . $mysqli->error, E_USER_ERROR);
	}

	/* Bind parameters. TYpes: s = string, i = integer, d = double,  b = blob */
	$stmt->bind_param('s',$id);

	if($stmt->execute()){
	    //if saving success
		echo json_encode(array(
			'status' => 'Success',
			'message'=> 'Customer has been deleted successfully!'
		));

	} else {
	    //if unable to create new record
	    echo json_encode(array(
	    	'status' => 'Error',
	    	//'message'=> 'There has been an error, please try again.'
	    	'message' => 'There has been an error, please try again.<pre>'.$mysqli->error.'</pre><pre>'.$query.'</pre>'
	    ));
	}

	// close connection 
	$mysqli->close();

}

?>