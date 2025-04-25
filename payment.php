<?php
  session_start();
        if(!isset($_SESSION["user"])){
            header("location: login.php");
            exit();
        }
include 'db_connection.php';

$bus_id = isset($_GET['busId']) ? $_GET['busId'] : null;
$booking_id = isset($_GET['booking_id']) ? $_GET['booking_id'] : null;
$amount = isset($_GET['price_per_seat']) ? $_GET['price_per_seat'] : null; 

if (!$booking_id || $amount <= 0) { 
    echo "Invalid booking.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Page</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="./admin/asset/css/payment.css">
    <link rel="stylesheet" href="./admin/asset/css/buses.css">
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

    <section class="payment-section">
        <h2>Payment for Booking ID: <?php echo htmlspecialchars($booking_id); ?></h2>
        <p><strong>Amount to be Paid:</strong> <?php echo $amount; ?></p>

        <form action="process_payment.php" method="POST">
            <input type="hidden" name="booking_id" value="<?php echo htmlspecialchars($booking_id); ?>">
            <input type="hidden" name="amount" value="<?php echo htmlspecialchars($amount); ?>">
            <input type="hidden" name="bus_id" value="<?php echo htmlspecialchars($bus_id); ?>">

            <label for="payment_method">Select Payment Method:</label><br>
            <select name="payment_method" id="payment_method" required>
                <option value="credit_card">Credit Card</option>
                <option value="other">Other</option>
            </select><br>

            <div id="credit_card_info" style="display: none;">
                <label for="card_number">Credit Card Number:</label><br>
                <input type="text" name="card_number" id="card_number"><br>

                <label for="expiry_date">Expiry Date:</label><br>
                <input type="text" name="expiry_date" id="expiry_date"><br>

                <label for="cvv">CVV:</label><br>
                <input type="text" name="cvv" id="cvv"><br>
            </div>

            <button type="submit">Proceed with Payment</button>
        </form>
    </section>


    <script>
        document.getElementById('payment_method').addEventListener('change', function () {
            const creditCardInfo = document.getElementById('credit_card_info');
            if (this.value === 'credit_card') {
                creditCardInfo.style.display = 'block';
            } else {
                creditCardInfo.style.display = 'none';
            }
        });
    </script>
</body>
</html>
