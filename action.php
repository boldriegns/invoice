<?php
include('header.php');

if(isset($_POST['add_product'])) {
	
    $product_name = $_POST['product_name'];
    $product_desc = $_POST['product_desc'];
    $product_price = $_POST['product_price'];
    $image = $_FILES['image']['name'];
    $image_tmp = $_FILES['image']['tmp_name'];

    $upload_directory = "images/";
    $upload_path = $upload_directory . $image;

    // Move the uploaded file to the images folder
    if (move_uploaded_file($image_tmp, $upload_path)) {
        // File moved successfully, proceed with database operatio
        $photo = $upload_path;

        // Output any connection error
        if ($mysqli->connect_error) {
            error_log('Error : ('. $mysqli->connect_errno .') '. $mysqli->connect_error);
            die('Error : ('. $mysqli->connect_errno .') '. $mysqli->connect_error);
        }

        // Fetch the user's role (assuming it's stored in the session
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
                        product_price,
                        photo
                    )
                    VALUES (
                        ?, 
                        ?,
                        ?,
                        ?,
                        ?
                    )";


            /* Prepare statement */
            $stmt = $mysqli->prepare($query);
            if ($stmt === false) {
                error_log('Wrong SQL: ' . $query . ' Error: ' . $mysqli->error);
                trigger_error('Wrong SQL: ' . $query . ' Error: ' . $mysqli->error, E_USER_ERROR);
            }

            /* Bind parameters. Types: s = string, i = integer, d = double, b = blob */
            $stmt->bind_param('sssss', $product_name, $product_desc, $product_vendor, $product_price, $photo);

            if ($stmt->execute()) {
                $_SESSION['success'] = "<span style='color: green; font-weight: bold; font-size: i8px;'>Your product added successfully!.";
                 // Product already exists
            echo '<script>window.location.href = "product-list.php";</script>';
            exit;
               
            } else {
                // If unable to create new record
                error_log('Error executing SQL statement: ' . $mysqli->error);
                echo json_encode(array(
                    'status' => 'Error',
                    'message' => 'There has been an error, please try again.<pre>' . $mysqli->error . '</pre><pre>' . $query . '</pre>'
                ));
            }
        } else {
            // Product already exists
            $_SESSION['error'] = "<span style='color: red; font-weight: bold;'>product already exists.";
             // Product already exists
             echo '<script>window.location.href = "product-list.php";</script>';
             exit;

        }

     
    } else {
        // File upload failed, return error response
        echo json_encode(array(
            'status' => 'Error',
            'message' => 'Failed to move uploaded file to destination.'
        ));
         // Product already exists
         echo '<script>window.location.href = "product-add.php";</script>';
         exit;
       
    }
       // Close database connection
       $mysqli->close();
}
?>