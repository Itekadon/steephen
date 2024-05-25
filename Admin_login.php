
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin Login</title>
     <link rel="stylesheet" href="home.css">
    <link rel="icon" type="image/jpeg" href="./logo.jpeg">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="./home_animations.css">

</head>
<header class =  " container "> 
    <div class = "ccontainer" >
        <div class = "website-name">
            <h1>DadsUnityHub</h1>
        </div>
        <div class="navigation-links">
            <nav>
                 <ul>
                    <!-- <li><a href="home.html">Home</a></li> -->
                     <!-- <li><a href="http://127.0.0.1:5500/home.html">About Us!</a></li> -->
                    <!-- <li><a href="login.html">Login</a></li> -->
                    <!-- <li><a href="Testimonials.html">Testimonials</a></li>
                    <li><a href="books.html">Resources</a></li> -->
                    <!--
                    <li><a href="login.html" class="button" style="color: white;"> Login </a></li>
                    <li><a href="login.html" class="button" style="color: whitesmoke;">Join</a></li>
                    -->

                    <!--OLD FORM LINKS-->
                    <!-- <li><a href="login2.php" class="button" style="color: whitesmoke;">Login</a></li>
                    <li><a href="signUp.php" class="button" style="color: whitesmoke;">Sign Up</a></li> -->
                </ul>
            </nav>
            
        </div>
      </div>
         
 
</header>
<body>
    <h2>Admin Login</h2>
    <form method="post">
        <label for="username">Username:</label><br>
        <input type="text" id="username" name="username" required><br><br>

        <label for="password">Password:</label><br>
        <input type="password" id="password" name="password" required><br><br>

        <input type="submit" value="Login" name="login">
    </form>
</body>

</html>


<?php

session_start();

// connect to the database
include_once 'db_connection.php';

// Check if the login form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    // Get the username and password from the form
    $username = $_POST['username'];
    $password = $_POST['password'];

    // get password from the saved username
    $sql = "SELECT username, password_hash FROM admin WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        // whenadmin is found verify the password
        $stmt->bind_result($db_username, $db_password_hash);
        $stmt->fetch();
        if (password_verify($password, $db_password_hash)) {
            // if password checks out, redirect to the dashboard or admin page
            $_SESSION['admin_username'] = $username;
            $_SESSION['admin_logged_in'] = true;
            header("Location:dashboard.php");
            exit();
        } else {
            // if password does not match
            $_SESSION['error'] = "Invalid username or password.";
            header("Location: Admin_login.php");
            exit();
        }
    } else {
        // Admin not found
        $_SESSION['error'] = "Invalid username or password.";
        header("Location: Admin_login.php");
        exit();
    }
}
?>


