<?php
       session_start();
        if(!isset($_SESSION["user"])){
            header("location: login.php");
            exit();
}

include 'db_connection.php';

$sql = "SELECT * FROM buses";
$result = $con->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available Buses</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="./admin/asset/css/buses.css">
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

    <section class="bus-list-section">
        <h2>Available Buses</h2>

        <div class="bus-list-container">
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $bus_id = $row['busId'];
                    $bus_name = $row['bus_name'];
                    $price_per_seat = number_format($row['price_per_seat'], 2);
                    $no_seats = $row['no_seats'];
                    $bus_image = $row['bus_image'];
                    $source = $row['source'];
                    $destination = $row['destination'];
            ?>
                    <article class="bus-card">
                    <img src="http://localhost/busReservation/admin/<?php echo $bus_image; ?>" alt="Bus Image" class="bus-img">
                        <div class="bus-details">
                            <h3><?php echo $bus_name; ?></h3>
                             <p><strong>Source:</strong> <?php echo $source; ?></p>
                              <p><strong>Destination:</strong> <?php echo $destination; ?></p>
                            <p><strong>Price per Seat:</strong> <?php echo $price_per_seat; ?></p>
                            <p><strong>No. of Seats:</strong> <?php echo $no_seats; ?></p>
                            <a href="reserve_bus.php?busId=<?php echo $bus_id; ?>&price_per_seat=<?php echo $price_per_seat; ?>
                            &source=<?php echo $source; ?>&destination=<?php echo $destination; ?>  " id="btn" class="book-btn">Book Now</a>
                        </div>
                    </article>
            <?php
                } 
            } else {
                echo "<p>No buses available at the moment.</p>";
            }
            ?>
        </div>
    </section>

    <footer class="footer" id="contact">
        <p>&copy; 2024 BusReserve. All Rights Reserved.</p>
        <div class="social">
            <a href="#">Facebook</a>
            <a href="#">Twitter</a>
        </div>
    </footer>

    <script src="./admin/asset/js/app.js"></script>
</body>
</html>

<?php
$con->close();
?>
