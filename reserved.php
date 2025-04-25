<?php

session_start();
if (!isset($_SESSION["admin"])) {
    header("location: login.php");
    exit();
}

include 'db_connection.php';

// Updated query to fetch all from reservation, including related data from payments and buses
$query = "SELECT 
            r.booking_id, 
            r.busId, 
            r.full_name, 
            r.seat_number, 
            r.booking_status, 
            bs.bus_name, 
            p.payment_id, 
            p.amount, 
            p.payment_method, 
            p.payment_status, 
            p.payment_date
          FROM reservation r
          LEFT JOIN payments p ON r.booking_id = p.booking_id
          LEFT JOIN buses bs ON r.busId = bs.busId
          ORDER BY r.booking_id DESC"; 

// Prepare the query and check for errors
if ($stmt = $con->prepare($query)) {
    $stmt->execute();

    $result = $stmt->get_result();
    $reservations = $result->fetch_all(MYSQLI_ASSOC); 
} else {
    // Output the error if prepare fails
    echo "SQL error: " . $con->error; 
    exit(); // Stop the script if there's an error
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - View Reservations</title>
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

    <section class="reservations-section">
        <h2>View All Reservations</h2>

        <table>
            <thead>
                <tr>
                    <th>Booking ID</th>
                    <th>Bus Name</th>
                    <th>Passenger's Name</th>
                    <th>Seat Number</th>
                    <th>Booking Status</th>
                    <th>Payment ID</th>
                    <th>Amount</th>
                    <th>Payment Method</th>
                    <th>Payment Status</th>
                    <th>Payment Date</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($reservations)) : ?>
                    <tr>
                        <td colspan="10">No reservations found.</td>
                    </tr>
                <?php else : ?>
                    <?php foreach ($reservations as $reservation) : ?>
                        <tr>
                            <td><?php echo $reservation['booking_id']; ?></td>
                            <td><?php echo $reservation['bus_name']; ?></td>
                            <td><?php echo $reservation['full_name']; ?></td>
                            <td><?php echo $reservation['seat_number']; ?></td>
                            <td><?php echo $reservation['booking_status']; ?></td>
                            <td><?php echo $reservation['payment_id'] ? $reservation['payment_id'] : 'N/A'; ?></td>
                            <td><?php echo $reservation['amount'] ? $reservation['amount'] : 'N/A'; ?></td>
                            <td><?php echo $reservation['payment_method'] ? $reservation['payment_method'] : 'N/A'; ?></td>
                            <td><?php echo $reservation['payment_status'] ? $reservation['payment_status'] : 'N/A'; ?></td>
                            <td><?php echo $reservation['payment_date'] ? $reservation['payment_date'] : 'N/A'; ?></td>
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
