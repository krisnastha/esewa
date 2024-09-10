<?php
include 'database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Fetch the product details from the database
    $product_id = $_POST['product_id'];
    $sql = "SELECT * FROM foods WHERE id = '$product_id'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $invoice_no = $product_id . time();
        $title = $row['title'];
        $total = $row['price'];
        $created_at = date('Y-m-d H:i:s');
    } else {
        echo "Product not found!";
        exit();
    }

    // Secret Key provided by eSewa
    $secret_key = "8gBm/:&EnhH.1/q";

    // Create a message to be signed in the exact order required by eSewa
    $message = "total_amount={$total},transaction_uuid={$invoice_no},product_code=EPAYTEST";

    // Generate the HMAC signature
    $signature = hash_hmac('sha256', $message, $secret_key, true);
    $signature_base64 = base64_encode($signature);

    // Save the order details in the database
    $sql = "INSERT INTO orders (product_id, invoice_no, total, status, created_at) VALUES ('$product_id', '$invoice_no', '$total', 'pending', '$created_at')";
    if (!$conn->query($sql))
        die("Error: " . $conn->error);
} else {
    echo "Invalid request!";
    exit();
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Page</title>
    <link rel="stylesheet" href="esewa.css">
</head>

<body>
   

    <!-- Main Content -->
    <main class="checkout-container">
        <section class="payment-options">
            <h2>Pay with Esewa</h2>
            <ul>
            
                    <form action="https://rc-epay.esewa.com.np/api/epay/main/v2/form" method="POST">
                        <input type="hidden" id="amount" name="amount" value="<?php echo $total; ?>">
                        <input type="hidden" id="tax_amount" name="tax_amount" value="0">
                        <input type="hidden" id="total_amount" name="total_amount" value="<?php echo $total; ?>">
                        <input type="hidden" id="transaction_uuid" name="transaction_uuid" value="<?php echo $invoice_no; ?>">
                        <input type="hidden" id="product_code" name="product_code" value="EPAYTEST">
                        <input type="hidden" id="product_service_charge" name="product_service_charge" value="0">
                        <input type="hidden" id="product_delivery_charge" name="product_delivery_charge" value="0">
                        <input type="hidden" id="success_url" name="success_url" value="http://http://localhost/esewa/success.php">
                        <input type="hidden" id="failure_url" name="failure_url" value="http://http://localhost/esewa/failure.php">
                        <input type="hidden" id="signed_field_names" name="signed_field_names" value="total_amount,transaction_uuid,product_code">
                        <input type="hidden" id="signature" name="signature" value="<?php echo $signature_base64; ?>">
                        <input class="payment-icon" type="image" src="images/esewa.png">
               
               
            </ul>
        </section>
    </main>

 
</body>

</html>