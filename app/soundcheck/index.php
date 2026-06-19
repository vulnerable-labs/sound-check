<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SoundCheck | Staff Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-color: #0b0f19;
            --card-bg: rgba(20, 25, 40, 0.6);
            --text-primary: #ffffff;
            --accent-1: #8b5cf6;
            --accent-2: #ec4899;
        }
        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-color);
            background-image: radial-gradient(circle at 50% 0%, rgba(139, 92, 246, 0.15), transparent 40%);
            color: var(--text-primary);
            margin: 0;
            padding: 2rem;
            min-height: 100vh;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 3rem;
            max-width: 800px;
            margin-inline: auto;
        }
        .header h1 { margin: 0; font-weight: 800; font-size: 2rem; }
        .highlight {
            background: linear-gradient(135deg, var(--accent-1), var(--accent-2));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .dashboard-box {
            background: var(--card-bg);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            padding: 3rem;
            border-radius: 20px;
            width: 100%;
            max-width: 800px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            margin: auto;
        }
        .btn {
            padding: 0.8rem 1.5rem;
            background: linear-gradient(135deg, var(--accent-1), var(--accent-2));
            color: white;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s;
            text-decoration: none;
        }
        .btn:hover { transform: translateY(-2px); }
        .btn-logout { background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); }
        input[type="text"], input[type="file"] {
            width: 100%;
            padding: 1rem;
            margin-bottom: 1.5rem;
            background: rgba(0,0,0,0.2);
            border: 1px solid rgba(255,255,255,0.1);
            color: white;
            border-radius: 10px;
            font-family: inherit;
            box-sizing: border-box;
        }
        label { display: block; margin-bottom: 0.5rem; color: #94a3b8; font-weight: 600; }
    </style>
</head>
<body>
    <div class="header">
        <h1><span class="highlight">SoundCheck</span> Panel</h1>
        <div>
            <span style="margin-right: 1rem; color: #94a3b8;">Welcome, <strong><?php echo htmlspecialchars($_SESSION['user']); ?></strong></span>
            <a href="logout.php" class="btn btn-logout">Logout</a>
        </div>
    </div>
    
    <div class="dashboard-box">
        <h2 style="margin-top:0; font-weight: 600;">Upload Track to Playlist</h2>
        <form action="upload.php" method="POST" enctype="multipart/form-data">
            <label>Track Title (Metadata)</label>
            <input type="text" name="title" placeholder="E.g. Morning Ambient Mix" required>
            <label>Audio File (MP3)</label>
            <input type="file" name="file" accept=".mp3" required>
            <button type="submit" class="btn" style="width: 100%;">Sync to PA System</button>
        </form>
    </div>
</body>
</html>
