<?php
include('header.php');

if(isset($_POST['edit_product'])) {

    if ($mysqli->connect_error) {
        die('Error : ('. $mysqli->connect_errno .') '. $mysqli->connect_error);
    }

    $product_vendor = $_SESSION['login_username'];
    
    // invoice product information
    $getID = $_POST['id']; // id
    $product_name = $_POST['product_name']; // product name
    $product_desc = $_POST['product_desc']; // product desc
    $product_price = $_POST['product_price']; // product price
    
    // Check if image is empty
    if (!empty($_FILES['image']['name'])) {
        $image = $_FILES['image']['name'];
        $image_tmp = $_FILES['image']['tmp_name'];
        $upload_directory = "images/";
        $upload_path = $upload_directory . $image;
    
        // Move the uploaded file to the images folder
        if (move_uploaded_file($image_tmp, $upload_path)) {
            $photo = $upload_path;
        } else {
            // File upload failed, return error response
            echo json_encode(array(
                'status' => 'Error',
                'message' => 'Failed to move uploaded file to destination.'
            ));
            exit;
        }
    } else {
        // If image input is empty, retain the previous photo
        $photo = $_POST['prev_photo'];
    }
    
    // the query
    $query = "UPDATE products SET
                product_name = ?,
                product_desc = ?,
                product_vendor = ?,
                product_price = ?,
                photo = ?
             WHERE product_id = ?";
    
    /* Prepare statement */
    $stmt = $mysqli->prepare($query);
    if($stmt === false) {
        trigger_error('Wrong SQL: ' . $query . ' Error: ' . $mysqli->error, E_USER_ERROR);
    }
    
    /* Bind parameters. Types: s = string, i = integer, d = double, b = blob */
    $stmt->bind_param(
        'ssssss',
        $product_name, $product_desc, $product_vendor, $product_price, $photo, $getID
    );
    
    // Execute the query
    if($stmt->execute()){
        $_SESSION['success'] = "<span style='color: green; font-weight: bold; font-size: i8px;'>Your product updated successfully!.";
                         // Product already exists
              echo '<script>window.location.href = "product-list.php";</script>';
             exit;
                            
    } else {
        // If unable to update record
        $_SESSION['error'] = "<span style='color: red; font-weight: bold;'>Check your updated details and try again!.";
                         // Product already exists
             echo '<script>window.location.href = "product-add.php";</script>';
             exit;
                            
    }
    
    // Close database connection
    $mysqli->close();
}
?>
