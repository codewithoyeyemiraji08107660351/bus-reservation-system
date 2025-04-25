<?php
session_start();

if (!isset($_SESSION["user"])) {
    header("location: login.php");
    exit();
}

include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bus_id = trim($_POST['bus_id']);
    $seat_number = trim($_POST['seat_number']);
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $date = trim($_POST['date']);
    $price_per_seat = trim($_POST['price_per_seat']);
    $source = trim($_POST['source']);
    $destination = trim($_POST['destination']);

    $username = $_SESSION["user"]; 
    $booking_id = 'BOOK-' . strtoupper(uniqid());

    $payment_status = 'Not Paid';
    $booking_status = 'Booked';


    $sql = "INSERT INTO reservation (booking_id, busId, seat_number, full_name, email, phone, price_per_seat, book_date, source, destination, payment_status, booking_status,username) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    if ($stmt = $con->prepare($sql)) {
        $stmt->bind_param("sssssssssssss", $booking_id, $bus_id, $seat_number, $full_name, $email, $phone, $price_per_seat, $date, $source, $destination, $payment_status, $booking_status,$username);

        if ($stmt->execute()) {
            header("Location: payment.php?busId=$bus_id&booking_id=$booking_id&price_per_seat=$price_per_seat");
            exit();
        } else {
            echo "Error: Could not complete booking. Please try again.";
        }

        $stmt->close();
    } else {
        echo "Error preparing query: " . $con->error;
    }
}

$con->close();
?>
