<?php 
session_start();

	include("connection.php");
	include("functions.php");


    

	if($_SERVER['REQUEST_METHOD'] == "POST")
	{

        
		//something was posted
		$user_name = $_POST['user_name'];
		$password = $_POST['password'];
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $profile_picture = $_FILES['profile_picture']['name'];
        $biography = $_POST['biography'];
        $gender = $_POST['gender'];
        $age = $_POST['age'];
        $location = $_POST['location'];
        $want_nanny = $_POST['want_nanny'];
        $want_partner = $_POST['want_partner'];
        $agree_terms = $_POST['agree_terms'];

        $want_nanny = isset($_POST['nanny']) ? 1 : 0; // Convert checkbox value to boolean
        $want_partner = isset($_POST['partner']) ? 1 : 0; // Convert checkbox value to boolean
        $agree_terms = isset($_POST['agree']) ? 1 : 0; // Convert checkbox value to boolean

        // Handle file upload
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["profile_picture"]["name"]);

        // Check if file has been uploaded
        if(move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file)) {
            // File uploaded successfully
            // Save to database
            // Include this code within the block where you handle form submission
        } else {
            echo "Sorry, there was an error uploading your file:". $_FILES["profile_picture"]["error"];
        }


        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        

		if(!empty($user_name) && !empty($hashed_password) && !is_numeric($user_name))
		{



			//save to database
			$user_id = random_num(10);
			$query = "insert into user_profiles (user_id,first_name,last_name,user_name,password_hash,profile_picture,biography,gender,age,location,want_nanny,want_partner,agree_terms) 
            values ('$user_id','$first_name','$last_name','$user_name','$hashed_password','$profile_picture','$biography','$gender','$age','$location','$want_nanny','$want_partner','$agree_terms')";

			mysqli_query($con, $query);

            // Notify the user
        $_SESSION['registration_success'] = true;

        // Redirect the user to the login page
			header("Location: login2.php");
			die;
		}else
		{
			echo "Please enter some valid information!";
		}
	}
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up Page</title>
    <link rel="stylesheet" href="login.css">
    <link rel="stylesheet" href="joi_animations.css">
    <link rel="stylesheet" href="./home_animations.css">
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

                        <li><a href="login2.php">Login</a></li>
                        <li><a href="#testimonials">Testimonials</a></li>
                        <li><a href="#resources">Resources</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </header>

    <main>
        <section id="login">
            <h2> Member Sign Up </h2>

            <form method="POST" enctype="multipart/form-data">
                <div class="form-group">

                    <label for="first_name">First Name:</label>
                    <input style = "width: 300px;padding: .5rem;margin-bottom: 1rem;border: 1px solid #690e0e;border-radius: 10px; "type="text" id="first_name" name="first_name" placeholder="First Name" required>

                    <label for="last_name">Last Name</label>
                    <input style="width: 300px;padding: .5rem;margin-bottom: 1rem;border: 1px solid #690e0e; border-radius: 10px; " type="text" id= "last_name"  name="last_name" placeholder="Last Name" >

                    <label for="user_name">User Name:</label>
                    <input type="email" id="email" name="user_name" placeholder="Email address"required>


                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>



                     <label for="profilePicture">Profile Picture:</label>
                    <input type="file" id="profile_picture" name="profile_picture" accept="image/*"><br>

                    <label for="biography" placeholder=" About myself">Biography:</label>
                    <textarea id="biography" name="biography" rows="4" cols="50"></textarea><br>

        
    
                    <label for="gender">Gender:</label>
                    <select id="gender" name="gender">
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                    </select><br>
      
                    <label for="age">Age:</label>
                    <input type="number" id="age" name="age"><br>
                
            
                
                    <!-- <label for="birthdate">Birthdate:</label>
                    <input type="date" id="birthdate" name="birthdate"><br> -->
                
                    <label for="location">Location:</label>
                    <select id="location" name="location">
                    <option value="Eastern Cape">Eastern Cape</option>
                    <option value="Free State">Free State</option>
                    <option value="Gauteng">Gauteng</option>
                    <option value="KwaZulu-Natal">KwaZulu-Natal</option>
                    <option value="Limpopo">Limpopo</option>
                    <option value="Mpumalanga">Mpumalanga</option>
                    <option value="North West">North West</option>
                    <option value="Northern Cape">Northern Cape</option>
                    <option value="Western Cape">Western Cape</option>
                    </select><br>
                
                    
                    <label for="nanny">Would you want a nanny?</label>
                    <input type="checkbox" id="nanny" name="nanny"><br>
                    
                
                    <div class="checkbox-container">
                    <label for="partner">Would you want to find a possible partner to date?</label>
                    <input type="checkbox" id="partner" name="partner" onclick="togglePartner()"/>
                    </div>
                    
                    
                    <label for="agree">I agree to the terms and conditions. <a href="#">Terms and Conditions</a></label><br>
                    <input type="checkbox" id="agree" name="agree">
                

                    <button type="submit">Sign Up</button><br>

                    <h2>Already A Member?<li><a href="login2.php">Login</a></li>



                    
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

</html>