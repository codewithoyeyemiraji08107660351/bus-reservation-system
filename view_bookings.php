<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("location: login.php");
    exit();
}

include 'db_connection.php';

$user = $_SESSION['user'];

$stmt = $con->prepare("
    SELECT 
        r.booking_id, 
        r.seat_number, 
        r.book_date, 
        bs.bus_name, 
        bs.source,
        bs.destination, 
        p.payment_status, 
        p.amount
    FROM reservation r
    JOIN buses bs ON r.busId = bs.busId 
    LEFT JOIN payments p ON r.booking_id = p.booking_id
    WHERE r.username = ?
");

if ($stmt === false) {
    die('MySQL prepare error: ' . htmlspecialchars($con->error));
}

$stmt->bind_param("s", $user);
$stmt->execute();
$bookings = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Bookings</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="./admin/asset/css/reserve.css">
    <link rel="stylesheet" href="./admin/asset/css/buses.css" />
    <link rel="stylesheet" href="./admin/asset/css/index.css" />
    <link rel="stylesheet" href="./admin/asset/css/admin_view_bookings.css">
</head>
<body>
    <nav class="navbar">
        <div class="nav-logo">Bus Reservation</div>
        <ul class="nav-links">
            <li><a href="index.php">Home</a></li>
            <li><a href="buses.php">Available Buses</a></li>
            <li><a href="view_bookings.php">My Bookings</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>

    <section class="reservations-section">
        <h2>My Bookings</h2>

        <?php if (empty($bookings)) : ?>
            <p>You have no bookings.</p>
        <?php else : ?>
            <table>
                <thead>
                    <tr>
                        <th>Booking ID</th>
                        <th>Bus Name</th>
                        <th>Seat Number</th>
                        <th>Booking Date</th>
                        <th>Source</th>
                        <th>Destination</th>
                        <th>Payment Status</th>
                        <th>Amount Paid</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($bookings as $booking) : ?>
                        <tr>
                            <td><?php echo $booking['booking_id']; ?></td>
                            <td><?php echo $booking['bus_name']; ?></td>
                            <td><?php echo $booking['seat_number']; ?></td>
                            <td><?php echo $booking['book_date']; ?></td>
                            <td><?php echo $booking['source']; ?></td>
                            <td><?php echo $booking['destination']; ?></td>
                            <td><?php echo $booking['payment_status'] ? $booking['payment_status'] : 'Pending'; ?></td>
                            <td><?php echo $booking['amount']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </section>

    <footer class="footer" id="contact">
        <p>&copy; 2024 BusReserve. All Rights Reserved.</p>
        <div class="social">
            <a href="#">Facebook</a>
            <a href="#">Twitter</a>
        </div>
    </footer>
</body>
</html>
