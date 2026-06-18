<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}
$user = htmlspecialchars($_SESSION['user']);
?>
<!DOCTYPE html>
<html lang="en">
<head><meta charset="UTF-8"><title>SoundCheck – Dashboard</title></head>
<body>
<h2>Welcome, <?php echo $user; ?>!</h2>
<p>Use the form below to upload a new playlist (MP3). The "Title" field is vulnerable.</p>
<form action="upload.php" method="POST" enctype="multipart/form-data">
    <label>Title: <input type="text" name="title" required></label><br>
    <label>MP3 File: <input type="file" name="file" accept="audio/mpeg" required></label><br>
    <button type="submit">Upload</button>
</form>
<p><a href="logout.php">Logout</a></p>
</body>
</html>
