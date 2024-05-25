


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="stylesheet" href="login.css">
    <link rel="stylesheet" href="./home_animations.css">
    <link rel="stylesheet" href="joi_animations.css">
    <link rel="icon" type="image/jpeg" href="./logo.jpeg">

</head>

<body class="gradient">
    <header class=" container ">
        <div class="container">
            <div class="website-name">
                <h1 style="color: rgb(173, 11, 11);font-family: comic sans;">DadsUnityHub</h1>
            </div>
            <div></div>
            <div class="navigation-links">

                <nav>
                    <ul>
                        <li><a href="home.html">Home</a></li>

                        <li><a href="signUp.php">Sign Up</a></li>
                        <li><a href="#testimonials">Testimonials</a></li>
                        <li><a href="#resources">Resources</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </header>

    <main>
        <section id="login">
            <h2>Member Login</h2>
            <?php 

session_start();



// Check if registration was successful
if(isset($_SESSION['registration_success']) && $_SESSION['registration_success'] === true) {

    echo "Registration successful! You can now login.";

    
    // Once the message is displayed, remove it from the session
    unset($_SESSION['registration_success']);
}
 

	include("connection.php");
	include("functions.php");


	if($_SERVER['REQUEST_METHOD'] == "POST")
	{
		//something was posted
		$user_name = $_POST['user_name'];
		$password = $_POST['password'];

		if(!empty($user_name) && !empty($password) && !is_numeric($user_name))
		{

			//read from database
			$query = "select * from user_profiles where user_name = '$user_name' limit 1";
			$result = mysqli_query($con, $query);
            

			if($result)
			{
				if($result && mysqli_num_rows($result) > 0)
				{

					$user_data = mysqli_fetch_assoc($result);
					
					if($user_data['password_hash'] === $password)
					{

						$_SESSION['user_id'] = $user_data['user_id'];
						header("Location: profile.php");
						die;
					}
				}
			}
			
			echo "wrong username or password!";
		}else
		{
			echo "wrong username or password!";
		}
	}

?>

            <form method="POST">
                <div class="form-group">
                    <label for="user_name">User Name:</label>
                    <input type="email" id="email" name="user_name" required >


                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>

                    <button type="submit">Login</button><br>
                    


                    <a href="#lost-password">Lost Password</a><br><br>
                    <h2>New Member?<li><a href="signUp.php">Sign Up Now!</a></li>

                    <!-- Login sounds -->
                    <!-- <audio id="successAudio" src="success.mp3"></audio> -->
                   <!-- <audio id="wrongLoginAudio" src="success_sound.mp3.aac"></audio> -->

  

                </div>

            </form>
        </section>
      
    </main>

    <footer>
        <p>
        <nav>
            <ul>
                <li><a href="#blog">Our Blog!</a></li>
                <li><a href="#questions">FAQs</a></li>
                <li><a href="#contact">Contact Us</a></li>
                <li><a href="#privacy">Privacy Policy</a></li>
                <li><a href="#sitemap">Site Map</a></li>
                <li><a href="https://www.dsd.gov.za/index.php "> Social Development</a></li>
            </ul>
        </nav>
        </p>


        <p>&copy; 2024 DadsUnityHub.co.za</p>
        <p>All Rights Reserved</p>
    </footer>
</body>
<script src="login.js"></script>  

</html>