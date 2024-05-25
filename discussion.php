
<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login2.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Discussion Forum</title>
    <link rel="stylesheet" href="styles.css">
    <title>Discussion Forum</title>
  <link rel="stylesheet" href="home.css">
  <link rel="icon" type="image/jpeg" href="./logo.jpeg">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link rel="stylesheet" href="./home_animations.css">
  <link rel="stylesheet" href="discussion.css">

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
                    <li><a href="members.php">Members</a></li>
                    <li><a href="help.html">Help</a></li>
                    <li><a href="mail.php">Mail</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </nav>
        </div>
    </header>

    
    <main>
  <h2>Discussion Forum</h2>
  
        <section id="post-message">
            <form id="messageForm">
                <textarea id="message" name="message" rows="4" placeholder="Type your message..."></textarea>
                <button type="submit">Post Message</button>
            </form>
        </section>
        <section id="messages">
            <h2>Messages</h2>
            <div id="messagesContainer"></div>
        </section>
    </main>
    <script src="discussion.js"></script>
</body>
</html>








  

