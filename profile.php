<?php 
session_start();
include("connection.php");
include("functions.php");

$user_data = check_login($con);

if (!isset($user_data['user_id'])) {
    // Redirect user to login page or display an error message
    header("Location: login.php");
    exit; // Stop further execution
}

// Retrieve user information from the database
$user_id = $user_data['user_id'];
$query = "SELECT * FROM user_profiles WHERE user_id = '$user_id'";
$result = mysqli_query($con, $query);
$user_info = mysqli_fetch_assoc($result);

// Handle file upload
if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_FILES['file'])) {
    if ($_FILES['file']['error'] == 0) {
        $file_name = basename($_FILES['file']['name']);
        $file_type = $_FILES['file']['type'];
        $target_dir = "uploads/";
        $target_file = $target_dir . $file_name;

        // Ensure the uploads directory exists
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0755, true);
        }

        // Move the uploaded file to the target directory
        if (move_uploaded_file($_FILES['file']['tmp_name'], $target_file)) {
            // Save file info to the database
            $query = "INSERT INTO user_files (user_id, file_name, file_type) VALUES (?, ?, ?)";
            if ($stmt = mysqli_prepare($con, $query)) {
                mysqli_stmt_bind_param($stmt, "iss", $id, $file_name, $file_type); 
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
                // Redirect to avoid form resubmission
                header("Location: profile.php");
                exit;
            } else {
                $upload_error = "Database query failed!";
            }
        } else {
            $upload_error = "Failed to upload file!";
        }
    } else {
        $upload_error = "No file uploaded or upload error!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Profile Page</title>
  <link rel="stylesheet" href="login.css">
  <link rel="stylesheet" href="home.css">
  <link rel="stylesheet" href="profile.css">
</head>
<body>

<header class="container"> 
    <div class="website-name">
        <h1>DadsUnityHub</h1>
        <h2>
            <?php echo htmlspecialchars($user_info['first_name']); ?>
            <?php echo htmlspecialchars($user_info['last_name']); ?><br>
            <?php echo htmlspecialchars($user_info['user_name']); ?>
        </h2>
    </div>
    <div class="navigation-links">
        <nav>
            <ul>
                
                <li><a href="members.php">Members</a></li>
                <li><a href="discussion.php">Discussion Forum</a></li>
                <li><a href="payment.html">Subscriptions</a></li>
                <li><a href="help.html">Help</a></li>
                <li><a href="mail.php">Mail</a></li>
                <li><a href="logout.php">Logout</a></li>
                <link rel="icon" type="image/jpeg" href="./logo.jpeg">
            </ul>
        </nav>       
    </div>      
</header>

<div class="member-container">
  <div class="profile-box">
      <h2>Member Profile</h2>
      <p><img class="profile-picture" src="uploads/<?php echo htmlspecialchars($user_info['profile_picture']); ?>" alt="Profile Picture"></p>
      <div class="profile-info">
          <p><?php echo htmlspecialchars($user_info['first_name']); ?> <?php echo htmlspecialchars($user_info['last_name']); ?>: <?php echo htmlspecialchars($user_info['age']); ?></p>
          <p><?php echo htmlspecialchars($user_info['user_name']); ?></p>
      </div>

      <div id="moreContent"> 
          <div class="more_information">
            <p><strong>Gender  : </strong> <?php echo htmlspecialchars($user_info['gender']); ?></p>
            <p><strong> Location: </strong><?php echo htmlspecialchars($user_info['location']); ?></p>
            <p><strong>About me</strong><br><?php echo htmlspecialchars($user_info['biography']); ?></p>
          </div>
      </div>
      
      <button class="show-more-button" onclick="showMore()">Show More</button><br>
      <!-- Add more fields as needed -->

      <a href="logout.php">Logout</a><br>
      <a href="delete_profile.php">Delete Profile</a><br>
      <a href="edit_profile.php">Edit Profile</a>
  </div>

  <!-- File Upload Section -->
  <div class="upload-box">
      <h2>Upload Photos and Videos</h2>
      <?php if (isset($upload_error)): ?>
          <p style="color: red;"><?php echo htmlspecialchars($upload_error); ?></p>
      <?php endif; ?>
      <form action="profile.php" method="post" enctype="multipart/form-data">
          <input type="file" name="file" accept="image/*,video/*" style="font-size:25px;" required><br>
          <button type="submit" style="font-size:25px;">Upload</button>
      </form>
  </div>

  <!-- Display User Uploads -->
  <div class="photo-box">
      <h3>My Photos</h3>
      <?php
      $query = "SELECT * FROM user_files WHERE user_id = ? AND file_type LIKE 'image/%'";
      if ($stmt = mysqli_prepare($con, $query)) {
          mysqli_stmt_bind_param($stmt, "i", $user_id);
          mysqli_stmt_execute($stmt);
          $result = mysqli_stmt_get_result($stmt);

          if ($result && mysqli_num_rows($result) > 0) {
              while ($row = mysqli_fetch_assoc($result)) {
                  $file_path = 'uploads/' . htmlspecialchars($row['file_name']);
                  echo "<div class = 'photo-item'><img src='$file_path' alt='User photo'></div><br>";
              }
          } else {
              echo "<p>No photos yet.</p>";
          }

          mysqli_stmt_close($stmt);
      }
      ?>
  </div>
  <div class="video-box">
      <h3>My Videos</h3>
      <?php
      $query = "SELECT * FROM user_files WHERE user_id = ? AND file_type LIKE 'video/%'";
      if ($stmt = mysqli_prepare($con, $query)) {
          mysqli_stmt_bind_param($stmt, "i", $user_id);
          mysqli_stmt_execute($stmt);
          $result = mysqli_stmt_get_result($stmt);

          if ($result && mysqli_num_rows($result) > 0) {
              while ($row = mysqli_fetch_assoc($result)) {
                  $file_path = 'uploads/' . htmlspecialchars($row['file_name']);
                  echo "<video width='320' height='240' controls>
                          <source src='$file_path' type='" . htmlspecialchars($row['file_type']) . "'>
                        </video><br>";
              }
          } else {
              echo "No videos yet.";
          }

          mysqli_stmt_close($stmt);
      }
      ?>
  </div>
</div>

<script src="join.js"></script>
<script src="home.js"></script>
</body>
<footer>
    <p>
        <nav>
            <ul>
                <li><a href="Blog.html">Our Blog!</a></li>
                <li><a href="FAQs.html">FAQs</a></li>
                <li><a href="#contact">Contact Us</a></li>
                <li><a href="PrivacyPolicy.html">Privacy Policy</a></li><br>
                <li><a href="#sitemap">Site Map</a></li>
                <li><a href="Testimonials.html">Testimonials</a></li>
                <li><a href="books.html">Resources</a></li>
                <li><a href="https://www.dsd.gov.za/index.php">Social Development</a></li>
            </ul>
        </nav>
    </p>
    <p>&copy; 2024 DadsUnityHub.co.za</p>
    <p>All Rights Reserved</p>
</footer>
</html>
