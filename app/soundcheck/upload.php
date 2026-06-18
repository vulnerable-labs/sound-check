<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    if (!isset($_FILES['file'])) {
        die('No file uploaded');
    }
    $tmpPath = $_FILES['file']['tmp_name'];
    $origName = basename($_FILES['file']['name']);
    $destDir = '/var/www/soundcheck/playlists/';
    $destPath = $destDir . $origName;
    // Move uploaded file
    move_uploaded_file($tmpPath, $destPath);

    // Vulnerable command – title is directly interpolated into shell
    $cmd = "ffmpeg -i " . escapeshellarg($destPath) . " -metadata title='" . $title . "' $destPath";
    // Execute without proper sanitisation – leads to RCE
    exec($cmd, $output, $rc);
    if ($rc === 0) {
        echo "Upload successful!";
    } else {
        echo "Upload failed.";
    }
}
?>
