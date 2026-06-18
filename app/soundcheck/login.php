<?php
session_start();
if (isset($_SESSION['user'])) {
    header('Location: index.php');
    exit();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $creds = file('/var/www/soundcheck/users.db', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($creds as $line) {
        list($user, $pass) = explode(':', $line);
        if ($user === $username && $pass === $password) {
            $_SESSION['user'] = $username;
            header('Location: index.php');
            exit();
        }
    }
    $error = "Invalid credentials";
}
?>
<!DOCTYPE html>
<html lang="en">
<head><meta charset="UTF-8"><title>SoundCheck – Login</title></head>
<body>
<h2>Login to SoundCheck Panel</h2>
<?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>
<form method="POST">
    <label>Username: <input type="text" name="username" required></label><br>
    <label>Password: <input type="password" name="password" required></label><br>
    <button type="submit">Login</button>
</form>
</body>
</html>
