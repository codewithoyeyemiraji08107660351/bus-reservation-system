<?php
session_start();

include('db_connection.php');

$name = $email = $phone_no = $password = $address = "";
$email_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone_no = trim($_POST['phone_no']);
    $password = trim($_POST['password']);
    $address = trim($_POST['address']);

    $check_email = "SELECT * FROM admin WHERE email = '$email'";
    $result = mysqli_query($con, $check_email);

    if (mysqli_num_rows($result) > 0) {
        $email_err = "Admin with this email already exists. Please try another one!";

    } else {
        $register_admin = "INSERT INTO admin (name, email, phoneNo, password, address) 
                           VALUES ('$name', '$email', '$phone_no', '$password', '$address')";
        if (mysqli_query($con, $register_admin)) {
            echo "<script>alert('Successfully registered. You may now login!'); window.location.href='login.php';</script>";
            exit();
        } else {
            echo "Error: " . mysqli_error($con);
        }
    }
}

mysqli_close($con);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Signup</title>
     <link rel="stylesheet" href="./asset/css/register.css"  />
</head>
<body>
    <section class="signup-section">
        <div class="form-container">
            <h2>Admin Signup</h2>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" id="name" name="name" required>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>

                <div class="form-group">
                 <label for="phone_no">Phone No.</label>
                <input type="text" id="phone_no" name="phone_no" placeholder="phone number" required>
                </div>

                 <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>

                <div class="form-group">
                    <label for="address">Address</label>
                    <input type="text" id="address" name="address" required>
                </div>

                <button type="submit" class="submit-btn">Sign Up</button>
            </form>
            <p>Already have an account? <a href="login.php">Login</a></p>
        </div>
    </section>
</body>
</html>
