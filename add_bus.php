<?php
    session_start();
        if(!isset($_SESSION["admin"])){
            header("location: login.php");
            exit();
}
include 'db_connection.php';


function generateUniqueId() {
    $randomNumber = rand(1000, 9999); 
    return 'bus-' . $randomNumber; 
}


$bus_id = generateUniqueId();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
   
    $id = trim($_POST['bus_id']);
    $bus_name = trim($_POST['bus_name']);
    $price_per_seat = trim($_POST['price_per_seat']);
    $seats_available = trim($_POST['no_seats']);
    $source= trim($_POST['source']);
    $destination = trim($_POST['destination']);

    
    $image_name = $_FILES['bus_image']['name'];
    $image_temp = $_FILES['bus_image']['tmp_name'];
    $upload_dir = 'uploads/';  

    
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    
    $target_file = $upload_dir . basename($image_name);
    if (move_uploaded_file($image_temp, $target_file)) {
       
        $sql = "INSERT INTO buses (busId,bus_name,price_per_seat,no_seats,source,destination,bus_image) VALUES (?, ?, ?, ?, ?,?,?)";

        if ($stmt = $con->prepare($sql)) {
           
            $stmt->bind_param("ssdisss", $bus_id, $bus_name, $price_per_seat, $seats_available, $source, $destination,$target_file);

            
            if ($stmt->execute()) {
                 echo '<script>alert("Bus uploaded successfully!") </script>';
            } else {
                echo "Error: " . $stmt->error;
            }

           
            $stmt->close();
        } else {
            echo "Error preparing the query: " . $con->error;
        }
    } else {
        echo "Error uploading image.";
    }

    $con->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin | Add Bus</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
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

    <section class="add-bus-section">
        <div class="form-container">
            <h2>Add a New Bus</h2>
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="bus_id">Bus ID</label>
                    <input type="text" id="bus_id" name="bus_id" value="<?php echo $bus_id; ?>" readonly>
                </div>

                <div class="form-group">
                    <label for="bus_name">Bus Name</label>
                    <input type="text" id="bus_name" name="bus_name" required>
                </div>

                <div class="form-group">
                    <label for="price_per_seat">Price per Seat</label>
                    <input type="number" step="0.01" id="price_per_seat" name="price_per_seat" required>
                </div>

                 <div class="form-group">
                    <label for="source">Source</label>
                    <input type="text"  id="source" name="source" required>
                </div>

                  <div class="form-group">
                    <label for="destination">Destination</label>
                    <input type="text"  id="destination" name="destination" required>
                </div>

                <div class="form-group">
                    <label for="seats_available">Seats Available</label>
                    <input type="number" id="seats_available" name="no_seats" required>
                </div>

                <div class="form-group">
                    <label for="bus_image">Upload Bus Image</label>
                    <input type="file" id="bus_image" name="bus_image" required>
                </div>

                <button type="submit" class="submit-btn">Add Bus</button>
            </form>
        </div>
    </section>
</body>
</html>
