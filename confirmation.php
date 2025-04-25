<?php
session_start();

if (!isset($_SESSION["user"])) {
    header("location: login.php");
    exit();
}

include 'db_connection.php';
$bus_id = isset($_GET['busId']) ? $_GET['busId'] : null;
$booking_id = isset($_GET['booking_id']) ? $_GET['booking_id'] : null;
$amount = isset($_GET['amount']) ? $_GET['amount'] : null;
$status = isset($_GET['status']) ? $_GET['status'] : null;

$username = $_SESSION["user"]; 

if (!$booking_id) {
    echo "Invalid booking.";
    exit();
}

$stmt = $con->prepare("
    SELECT 
        r.booking_id, 
        r.seat_number, 
        bs.bus_name, 
        bs.source, 
        bs.destination, 
        p.payment_status, 
        p.amount, 
        p.payment_date 
    FROM reservation r
    JOIN buses bs ON r.busId = bs.busId
    LEFT JOIN payments p ON r.booking_id = p.booking_id
    WHERE r.booking_id = ? AND r.busId = ? AND r.username = ?
");

$stmt->bind_param('sss', $booking_id, $bus_id, $username); 
$stmt->execute();
$result = $stmt->get_result();
$booking = $result->fetch_assoc();

if (!$booking) {
    echo "No booking found.";
    exit();
}

if ($amount && $status) {
    $booking['amount'] = $amount;
    $booking['payment_status'] = $status;
}

$con->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Confirmation</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="./admin/asset/css/confirmation.css"> 
    
</head>
<body>


    <section class="confirmation-section">
        <h2>Booking Confirmation</h2>

        <p><strong>Booking ID:</strong> <?php echo $booking['booking_id']; ?></p>
        <p><strong>Bus Name:</strong> <?php echo $booking['bus_name']; ?></p>
        <p><strong>Seat Number:</strong> <?php echo $booking['seat_number']; ?></p>
        <p><strong>Source:</strong> <?php echo $booking['source']; ?></p>
        <p><strong>Destination:</strong> <?php echo $booking['destination']; ?></p>
        <p><strong>Payment Status:</strong> <?php echo $booking['payment_status'] ? $booking['payment_status'] : 'Pending'; ?></p>
        <p><strong>Amount Paid:</strong> <?php echo $booking['amount']; ?></p>
        <?php if ($booking['payment_date']) : ?>
            <p><strong>Payment Date:</strong> <?php echo $booking['payment_date']; ?></p>
        <?php endif; ?>

        <a href="view_bookings.php">Back to My Bookings</a>
    </section>

</body>
</html>
