<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("location: login.php");
    exit();
}

include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $bus_id = isset($_POST['bus_id']) ? $_POST['bus_id'] : null;
    $booking_id = isset($_POST['booking_id']) ? $_POST['booking_id'] : null;
    $amount = isset($_POST['amount']) ? $_POST['amount'] : null;
    $payment_method = isset($_POST['payment_method']) ? $_POST['payment_method'] : null;

    if (!$booking_id || $amount <= 0 || !$payment_method) {
        echo "Invalid payment details.";
        exit();
    }

    if ($payment_method == 'credit_card') {
        // Credit card details validation
        $card_number = isset($_POST['card_number']) ? $_POST['card_number'] : null;
        $expiry_date = isset($_POST['expiry_date']) ? $_POST['expiry_date'] : null;
        $cvv = isset($_POST['cvv']) ? $_POST['cvv'] : null;

        if (!$card_number || !$expiry_date || !$cvv) {
            echo "Credit card details are incomplete.";
            exit();
        }
        $payment_status = "Success";  
    } else {
        $payment_status = "Pending"; 
    }

    $sql = "INSERT INTO payments (booking_id, amount, payment_method, payment_status, payment_date) 
            VALUES (?, ?, ?, ?, NOW())";
    
    if ($stmt = $con->prepare($sql)) {
        
        $stmt->bind_param("ssss", $booking_id, $amount, $payment_method, $payment_status);
        
        if ($stmt->execute()) {
            
            if ($payment_status == "Success") {
                $stmt_update = $con->prepare("UPDATE reservation SET payment_status='Paid' WHERE booking_id=?");
                $stmt_update->bind_param("s", $booking_id);
                $stmt_update->execute();
            }
            
           
            header("Location: confirmation.php?busId=$bus_id&booking_id=$booking_id&amount=$amount&status=$payment_status");
            exit();
        } else {
            echo "Error: Could not complete payment.";
        }
        
        $stmt->close();
    } else {
        echo "Error preparing query: " . $con->error;
    }

    $con->close();
} else {
    echo "Invalid request method.";
}
?>
