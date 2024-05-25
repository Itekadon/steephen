<?php
session_start();
include("connection.php");
include("functions.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: login2.php");
    exit;
}

$query = "SELECT id, first_name, last_name,age, profile_picture FROM user_profiles";
$result = mysqli_query($con, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Members</title>
    <link rel="stylesheet" href="home.css">
    <link rel="stylesheet" href="members.css">
    <link rel="stylesheet" href="./home_animations.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
     <link rel="stylesheet" href="no_pic.css"> 
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

    <div class="members-container">
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <a href="view_profile.php?id=<?php echo $row['id']; ?>" class="member-container">
                <?php if (!empty($row['profile_picture'])): ?>
                    <img src="uploads/<?php echo htmlspecialchars($row['profile_picture']); ?>" alt="Profile Picture">
                <?php else: ?>
                    <div class="default-profile-picture">
                    <i class="fas fa-user-circle"></i>
                </div>
                <?php endif; ?>
                <div class="member-info">
                    
                    <p class="name"><?php echo htmlspecialchars($row['first_name']); ?>
                     <?php echo htmlspecialchars($row['last_name']); ?></p>
                     <p class="age"><?php echo htmlspecialchars($row['age']); ?></p>
                </div>
            </a>
        <?php endwhile; ?>
    </div>
</body>
</html>
