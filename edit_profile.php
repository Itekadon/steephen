<?php
session_start();
// include("connection.php");
include("functions.php");

// Ensure the connection is successful
// if (!$conn) {
//     die("Connection failed: " . mysqli_connect_error());
// }

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "digital_dynasty";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
else{
	echo "Connected successfully";
}

// Assuming user is already logged in and user ID is stored in session
if (!isset($_SESSION['user_id'])) {
    die("User not logged in.");
}

$userId = $_SESSION['user_id'];

// Fetch current user data
$sql = "SELECT first_name, last_name, user_name, biography, profile_picture FROM user_profiles WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];
    $userName = $_POST['user_name'];
    $biography = $_POST['biography'];
    $password = $_POST['password'];
    $profilePicture = isset($user['profile_picture']) ? $user['profile_picture'] : null;


    // Handle file upload
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['profile_picture']['tmp_name'];
        $fileName = $_FILES['profile_picture']['name'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

        $allowedfileExtensions = array('jpg', 'jpeg', 'png');
        if (in_array($fileExtension, $allowedfileExtensions)) {
            $uploadFileDir = './uploads/';
            $dest_path = $uploadFileDir . $fileName;

            if (move_uploaded_file($fileTmpPath, $dest_path)) {
                $profilePicture = $fileName;
            }
        }
    }

    // Input validation (simplified)
    if (!empty($firstName) && !empty($lastName) && !empty($userName)) {
        // Update user data
        $sql = "UPDATE user_profiles SET first_name = ?, last_name = ?, user_name = ?, biography = ?, profile_picture = ?";
        $params = [$firstName, $lastName, $userName, $biography, $profilePicture];

        if (!empty($password)) {
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
            $sql .= ", password = ?";
            $params[] = $hashedPassword;
        }

        $sql .= " WHERE id = ?";
        $params[] = $userId;

        $stmt = $conn->prepare($sql);
        $stmt->bind_param(str_repeat("s", count($params) - 1) . "i", ...$params);

        if ($stmt->execute()) {
            echo "Profile updated successfully.";
        } else {
            echo "Error updating profile: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "All fields except password are required.";
    }
}

// Handle profile deletion
if (isset($_POST['delete_profile'])) {
    $sql = "DELETE FROM user_profiles WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);

    if ($stmt->execute()) {
        session_destroy();
        header("Location: goodbye.html"); // Redirect to a goodbye page
        exit();
    } else {
        echo "Error deleting profile: " . $stmt->error;
    }
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link rel="stylesheet" href="login.css">
    <link rel="stylesheet" href="joi_animations.css">
    <link rel="stylesheet" href="./home_animations.css">
    <link rel="icon" type="image/jpeg" href="./logo.jpeg">
</head>
<body class="gradient">
    <header class="container">
        <div class="container">
            <div class="website-name">
                <h1 style="color: rgb(173, 11, 11);font-family: comic sans;">DadsUnityHub</h1>
            </div>
            <div></div>
            <div class="navigation-links">
                <nav>
                    <ul>
                        <li><a href="home.html">Home</a></li>                      
                        <li><a href="profile.php">Profile</a></li>
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
            <h2>Edit Profile</h2>
            <form method="POST" action="edit_profile.php" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="first_name">First Name:</label>
                    <input type="text" id="first_name" name="first_name" value="<?php echo htmlspecialchars($user['first_name']); ?>" required>

                    <label for="last_name">Last Name:</label>
                    <input type="text" id="last_name" name="last_name" value="<?php echo htmlspecialchars($user['last_name']); ?>" required>

                    <label for="user_name">User Name:</label>
                    <input type="text" id="user_name" name="user_name" value="<?php echo htmlspecialchars($user['user_name']); ?>" required>

                   

                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password">

                    <label for="confirm_password">Confirm Password:</label>
                    <input type="password" id="confirm_password" name="confirm_password">

                    <label for="profile_picture">Profile Picture:</label>
                    <input type="file" id="profile_picture" name="profile_picture" accept="image/*"><br>

                
                   <!-- Display current profile picture if available -->
                    <?php if (!empty($user['profile_picture'])): ?>
                        <img src="uploads/<?php echo htmlspecialchars($user['profile_picture']); ?>" alt="Profile Picture" width="100"><br>
                    <?php else: ?>
                        <p>No profile picture uploaded.</p>
                    <?php endif; ?>

                   

                    <label for="biography">Biography:</label>
                    <textarea id="biography" name="biography" rows="4" cols="50"><?php echo htmlspecialchars($user['biography']); ?></textarea><br><br>

                    <button type="submit">Submit</button><br>
                    <button type="submit" name="delete_profile">Delete Profile</button>
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
