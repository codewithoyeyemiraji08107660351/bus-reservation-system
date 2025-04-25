<?php
session_start();

include 'db_connection.php';

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

if (isset($_POST['release_seat'])) {
    $booking_id = $_POST['booking_id'];
    
    $stmt = $con->prepare("UPDATE reservation SET booking_status = 'available' WHERE booking_id = ?");
    $stmt->bind_param("s", $booking_id); 
    
    if ($stmt->execute()) {
        $message = "Seat has been released and is now available.";
    } else {
        $message = "Failed to release the seat.";
    }
}

$stmt = $con->prepare("
    SELECT 
        b.bus_name, 
        b.busId, 
        r.booking_id, 
        r.seat_number, 
        r.full_name,
        r.booking_status 
    FROM buses b
    LEFT JOIN reservation r ON b.busId = r.busId 
    WHERE r.booking_status = 'Booked'
    ORDER BY b.bus_name, r.seat_number
");
$stmt->execute();
$bookedSeats = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - View Booked Seats</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="./asset/css/admin_view_bookings.css">
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

    <section class="bookings-section">
        <h2>Booked Seats</h2>
        <?php if (isset($message)) : ?>
            <p class="success-message"><?php echo $message; ?></p>
        <?php endif; ?>
        
        <table>
            <thead>
                <tr>
                    <th>Bus Name</th>
                    <th>Passenger's Name</th>
                    <th>Seat Number</th>
                    <th>Booking ID</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($bookedSeats)) : ?>
                    <tr>
                        <td colspan="6">All seats are available.</td>
                    </tr>
                <?php else : ?>
                    <?php foreach ($bookedSeats as $seat) : ?>
                        <tr>
                            <td><?php echo $seat['bus_name']; ?></td>
                            <td><?php echo $seat['full_name']; ?></td>
                            <td><?php echo $seat['seat_number']; ?></td>
                            <td><?php echo $seat['booking_id']; ?></td>
                            <td><?php echo $seat['booking_status']; ?></td>
                            <td>
                                <form method="POST" action="">
                                    <input type="hidden" name="booking_id" value="<?php echo $seat['booking_id']; ?>">
                                    <button type="submit" name="release_seat">Release Seat</button>
                                </form>
                            </td>
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
