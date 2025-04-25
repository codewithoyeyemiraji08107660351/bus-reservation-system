<?php
session_start();
if (!isset($_SESSION["admin"])) {
    header("location: login.php");
    exit();
}

include 'db_connection.php';

$query = "SELECT 
            p.payment_id, 
            p.booking_id, 
            p.amount, 
            p.payment_method, 
            p.payment_status, 
            p.payment_date,
            r.busId,
            r.full_name, 
            bs.bus_name
          FROM payments p
          LEFT JOIN reservation r ON p.booking_id = r.booking_id
          LEFT JOIN buses bs ON r.busId = bs.busId
          ORDER BY p.payment_date DESC";


$stmt = $con->prepare($query);
$stmt->execute();

$result = $stmt->get_result();
$payments = $result->fetch_all(MYSQLI_ASSOC); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - View Payments</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="./asset/css/admin_view_payments.css">
    <link rel="stylesheet" href="./asset/css/add_bus.css" />
</head>
<body>
    <nav class="navbar">
        <div class="nav-logo">Admin Panel</div>
        <ul class="nav-links">
            <li><a href="index.php">Dashboard</a></li>
            <li><a href="add_bus.php">Add Bus</a></li>
            <li><a href="reserved.php">All Bookings</a></li>
            <li><a href="view_buses.php">View Buses</a></li>
           <li><a href="admin_view_bookings.php">Release Seat</a></li>
            <li><a href="view_payment.php">Payments</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>

    <section class="payments-section">
        <h2>View All Payments</h2>

        <table>
            <thead>
                <tr>
                    <th>Payment ID</th>
                    <th>Booking ID</th>
                    <th>Bus Name</th>
                    <th>Passenger' Name</th>
                    <th>Amount</th>
                    <th>Payment Method</th>
                    <th>Payment Status</th>
                    <th>Payment Date</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($payments)) : ?>
                    <tr>
                        <td colspan="7">No payments found.</td>
                    </tr>
                <?php else : ?>
                    <?php foreach ($payments as $payment) : ?>
                        <tr>
                            <td><?php echo $payment['payment_id']; ?></td>
                            <td><?php echo $payment['booking_id']; ?></td>
                            <td><?php echo $payment['bus_name']; ?></td>
                            <td><?php echo $payment['full_name']; ?></td>
                            <td><?php echo $payment['amount']; ?></td>
                            <td><?php echo $payment['payment_method']; ?></td>
                            <td><?php echo $payment['payment_status']; ?></td>
                            <td><?php echo $payment['payment_date']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </section>

    <footer class="footer">
        <p>&copy; 2024 BusReservation. All Rights Reserved.</p>
    </footer>
</body>
</html>
