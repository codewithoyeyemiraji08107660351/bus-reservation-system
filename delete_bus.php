<?php
     session_start();
        if(!isset($_SESSION["admin"])){
            header("location: login.php");
            exit();
}
include 'db_connection.php'; 

if (isset($_GET['id'])) {
    $bus_id = $_GET['id']; 

    $sql = "DELETE FROM buses WHERE busId = ?";
    
    if ($stmt = $con->prepare($sql)) {
 
        $stmt->bind_param("s", $bus_id);

        if ($stmt->execute()) {
            echo "<script>alert('Bus successfully deleted!'); window.location.href='view_buses.php';</script>";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Error preparing the query: " . $con->error;
    }
} else {
    echo "<script>alert('Invalid request!'); window.location.href='view_buses.php';</script>";
}

$con->close();
?>
