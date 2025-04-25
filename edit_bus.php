<?php
    session_start();
        if(!isset($_SESSION["admin"])){
            header("location: login.php");
            exit();
}
include 'db_connection.php'; 

$edit_id = null;

if (isset($_GET['id'])) {
    $edit_id = $_GET['id'];

    $query = "SELECT * FROM buses WHERE busId = ?";
    if ($stmt = $con->prepare($query)) {
        $stmt->bind_param("i", $edit_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $bus_name = $row['bus_name'];
            $price_per_seat = $row['price_per_seat'];
            $no_seats = $row['no_seats'];
            $current_image = $row['bus_image'];
        }
        $stmt->close();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $bus_name = trim($_POST['bus_name']);
    $price_per_seat = trim($_POST['price_per_seat']);
    $seats_available = trim($_POST['no_seats']);

    
    $image_name = $_FILES['bus_image']['name'];
    $image_temp = $_FILES['bus_image']['tmp_name'];
    $upload_dir = 'uploads/';  

    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    if (!empty($image_name)) {
        $target_file = $upload_dir . basename($image_name);
        if (move_uploaded_file($image_temp, $target_file)) {
            $image_path = $target_file; 
        } else {
            echo "Error uploading image.";
            exit;
        }
    } else {

        $image_path = $current_image;
    }

    $sql = "UPDATE buses SET bus_name = ?, price_per_seat = ?, no_seats = ?, bus_image = ? WHERE busId = ?";
    
    if ($stmt = $con->prepare($sql)) {
        $stmt->bind_param("sssss", $bus_name, $price_per_seat, $seats_available, $image_path, $edit_id);
        
        if ($stmt->execute()) {
           echo '<script>alert("Bus updated successfully!") </script>';
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Error preparing the query: " . $con->error;
    }

    $con->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin | Edit Bus</title>
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
            <h2>Edit Bus</h2>
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>?id=<?php echo $edit_id; ?>" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="bus_id">Bus ID</label>
                    <input type="text" id="bus_id" name="bus_id" value="<?php echo $edit_id; ?>" readonly>
                </div>

                <div class="form-group">
                    <label for="bus_name">Bus Name</label>
                    <input type="text" id="bus_name" name="bus_name" value="<?php echo $bus_name; ?>" required>
                </div>

                <div class="form-group">
                    <label for="price_per_seat">Price per Seat</label>
                    <input type="number" step="0.01" id="price_per_seat" name="price_per_seat" value="<?php echo $price_per_seat; ?>" required>
                </div>

                <div class="form-group">
                    <label for="seats_available">Seats Available</label>
                    <input type="number" id="seats_available" name="no_seats" value="<?php echo $no_seats; ?>" required>
                </div>

                <div class="form-group">
                    <label for="bus_image">Upload New Bus Image</label>
                    <input type="file" id="bus_image" name="bus_image">
                    <p>Current Image: <img src="<?php echo $current_image; ?>" alt="Bus Image" style="width: 100px;"></p>
                </div>

                <button type="submit" class="submit-btn">Update</button>
            </form>
        </div>
    </section>
</body>
</html>
