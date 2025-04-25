<?php
    session_start();
if (!isset($_SESSION["user"])) {
    header("location: login.php");
    exit();
}
include 'db_connection.php';

$bus_id = isset($_GET['busId']) ? $_GET['busId'] : null;
$price_per_seat = isset($_GET['price_per_seat']) ? $_GET['price_per_seat'] : null;

if ($bus_id) {
    $sql = "SELECT * FROM buses WHERE busId = ?";
    if ($stmt = $con->prepare($sql)) {
        $stmt->bind_param("s", $bus_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $bus = $result->fetch_assoc();
        $bus_name = $bus['bus_name'];
        $no_seats = $bus['no_seats'];
        $source = $bus['source'];
        $destination = $bus['destination'];
        $stmt->close();
    }

    $booked_seats = [];
    $sql_booked = "SELECT seat_number FROM reservation WHERE busId = ? AND booking_status = 'Booked'";
    if ($stmt_booked = $con->prepare($sql_booked)) {
        $stmt_booked->bind_param("s", $bus_id);
        $stmt_booked->execute();
        $result_booked = $stmt_booked->get_result();
        while ($row = $result_booked->fetch_assoc()) {
            $booked_seats[] = $row['seat_number'];
        }
        $stmt_booked->close();
    }
} else {
    echo "Bus not found.";
    exit();
}

$con->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reserve Bus</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="./admin/asset/css/reserve.css">
    <link rel="stylesheet" href="./admin/asset/css/buses.css" />
    <link rel="stylesheet" href="./admin/asset/css/index.css" />
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

<section class="bus-info">
    <h2>Reserve a Seat in <?php echo $bus_name; ?></h2>
    <p><strong>Price per Seat:</strong> <?php echo $price_per_seat; ?></p>
    <p><strong>Seats Available:</strong> <?php echo $no_seats; ?></p>
</section>

<section class="seat-container">
    <h3>Select Your Seat</h3>
    <div id="seat-selection"></div>
</section>

<section class="reserve-form">
    <h3>Fill Your Details to Reserve a Seat</h3>
    <form action="submit_reservation.php" method="POST">
        <input type="hidden" name="bus_id" value="<?php echo $bus_id; ?>">
        <input type="hidden" name="seat_number" id="seat_number" value="">
        <input type="hidden" name="price_per_seat" value="<?php echo $price_per_seat; ?>">
        <input type="hidden" name="no_seats" value="<?php echo $no_seats; ?>">

        <label for="full_name">Full Name:</label><br>
        <input type="text" name="full_name" id="full_name" required><br>

        <label for="email">Email:</label><br>
        <input type="email" name="email" id="email" required><br>

        <label for="phone">Phone Number:</label><br>
        <input type="tel" name="phone" id="phone" required><br>

        <label for="date">Date:</label><br>
        <input type="date" name="date" id="date" required><br>

        <label for="source">Source:</label><br>
        <input type="text" name="source" id="source" value="<?php echo $source; ?>" required readonly><br>

        <label for="destination">Destination:</label><br>
        <input type="text" name="destination" id="destination" value="<?php echo $destination; ?>" required readonly><br>

        <label for="seat">Selected Seat:</label><br>
        <input type="number" name="seat" id="seat-selection-info" required readonly><br>

        <button type="submit" id="reserve_btn" disabled>Reserve Seat</button>
    </form>
</section>

<footer class="footer" id="contact">
    <p>&copy; 2024 BusReserve. All Rights Reserved.</p>
    <div class="social">
        <a href="#">Facebook</a>
        <a href="#">Twitter</a>
    </div>
</footer>

    <script>
        var bookedSeats = <?php echo json_encode($booked_seats); ?>;
        var noSeats = <?php echo json_encode($no_seats); ?>;
        var pricePerSeat = <?php echo json_encode($price_per_seat); ?>;
    </script>
    <script src="./admin/asset/js/app.js"></script> 
</body>
</html>
