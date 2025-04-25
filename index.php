<?php
        session_start();
        if(!isset($_SESSION["admin"])){
            header("location: login.php");
            exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="./asset/css/dashboard.css"  />
    <link rel="stylesheet" href="./asset/css/index.css" />
     <link rel="stylesheet" href="./asset/css/add_bus.css"  />
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

    
    <section class="dashboard-section">
        <div class="dashboard-container">
            <h2>Welcome to the Admin Dashboard</h2>
            <p>Use the links above to manage the bus reservation system.</p>

            <div class="dashboard-actions">
                <a href="add_bus.php" class="dashboard-btn">Add New Bus</a>
                <a href="view_buses.php" class="dashboard-btn">View All Buses</a>
            </div>
        </div>
    </section>
</body>
</html>
