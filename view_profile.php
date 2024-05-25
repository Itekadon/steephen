<?php
session_start();

// Include database connection and functions
include("connection.php");
include("functions.php");

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login2.php");
    exit;
}

// Get the member ID from the URL
if (isset($_GET['id'])) {
    $member_id = $_GET['id'];

    // Retrieve the member's information from the database
    $query = "SELECT * FROM user_profiles WHERE id = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("i", $member_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $member = $result->fetch_assoc();
} else {
    echo "Member ID not specified.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Member Profile</title>
    <link rel="stylesheet" href="home.css">
    <link rel="stylesheet" href="./home_animations.css">
    <!-- <link rel="stylesheet" href="view_profile.css"> -->
    <link rel="stylesheet" href="no_pic.css"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <header class="container">
        <div class="website-name">
            <h1>DadsUnityHub</h1>
        </div>
        <div class="navigation-links">
            <nav>
                <ul>
                    <li><a href="home.html">Home</a></li>
                    <li><a href="profile.php">Profile</a></li>
                    <li><a href="discussion.php">Discussion Forum</a></li>
                    <li><a href="help.html">Help</a></li>
                    <li><a href="mail.php">Mail</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <div class="profile-container">
        <h2>Member Profile: <?php echo htmlspecialchars($member['user_name']); ?></h2>
        <div class="profile-details">
            <?php if (!empty($member['profile_picture'])): ?>
                <img src="uploads/<?php echo htmlspecialchars($member['profile_picture']); ?>" alt="Profile Picture">
            <?php else: ?>
                <div class="default-profile-picture">
                    <i class="fas fa-user-circle"></i>
                </div>
            <?php endif; ?>
            <p><strong>First Name:</strong> <?php echo htmlspecialchars($member['first_name']); ?></p>
            <p><strong>Last Name:</strong> <?php echo htmlspecialchars($member['last_name']); ?></p>
            <p><strong>User Name:</strong> <?php echo htmlspecialchars($member['user_name']); ?></p>
            <p><strong>Biography:</strong> <?php echo nl2br(htmlspecialchars($member['biography'])); ?></p>
        </div>
    </div>
</body>
</html>
