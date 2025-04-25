<?php
    session_start();
        if(!isset($_SESSION["admin"])){
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
    <title>Admin | View Buses</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="./asset/css/view_buses.css"> 
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

    
    <section class="bus-list-section">
        <div class="table-container">
            <h2>Bus List</h2>

          
            <table class="bus-table">
                <thead>
                    <tr>
                        <th>Bus ID</th>
                        <th>Bus Name</th>
                        <th>Source</th>
                        <th>Destination</th>
                        <th>Price per Seat</th>
                        <th>Seats Available</th>
                        <th>Bus Image</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                   
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $id = $row['busId'];
                            $bus_name = $row['bus_name'];
                            $price_per_seat = number_format($row['price_per_seat'], 2);
                            $no_seats = $row['no_seats'];
                            $bus_image = $row['bus_image']; 
                            $source = $row['source']; 
                            $destination = $row['destination']; 

                            echo "<tr>
                                <td>{$id}</td>
                                <td>{$bus_name}</td>
                                <td>{$source}</td>
                                <td>{$destination}</td>
                                <td>{$price_per_seat}</td>
                                <td>{$no_seats}</td>
                                <td><img src='{$bus_image}' alt='Bus Image' class='bus-img'></td>
                                <td>
                                    <a href='edit_bus.php?id={$id}' class='edit-btn'>Edit</a>
                                    <a href='delete_bus.php?id={$id}' class='delete-btn' onclick='return confirm(\"Are you sure you want to delete this bus?\");'>Delete</a>
                                </td>
                            </tr>";
                        }
                    } else {
                       
                        echo "<tr><td colspan='6'>No buses found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </section>

</body>
</html>

<?php
$con->close();
?>
