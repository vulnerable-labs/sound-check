<?php
session_start();
$error = '';
if (isset($_SESSION['user'])) {
    header('Location: index.php');
    exit();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = $_POST['username'] ?? '';
    $pass = $_POST['password'] ?? '';
    
    // Insecure credential check – vulnerable to hardcoded credentials (intended for CTF)
    $db = file_get_contents('/var/www/soundcheck/users.db');
    $lines = explode("\n", $db);
    $valid = false;
    foreach ($lines as $line) {
        $line = trim($line);
        if (empty($line)) continue;
        list($u, $p) = explode(":", $line);
        if ($user === $u && $pass === $p) {
            $valid = true;
            break;
        }
    }
    
    if ($valid) {
        $_SESSION['user'] = $user;
        header('Location: index.php');
        exit();
    } else {
        $error = 'Invalid credentials';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SoundCheck | Staff Login</title>
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
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-box {
            background: var(--card-bg);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            padding: 3rem;
            border-radius: 20px;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            text-align: center;
        }
        h2 { margin-top: 0; margin-bottom: 2rem; font-weight: 800; font-size: 2rem; }
        .highlight {
            background: linear-gradient(135deg, var(--accent-1), var(--accent-2));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        input {
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
        input:focus { outline: none; border-color: var(--accent-1); }
        .btn {
            width: 100%;
            padding: 1rem;
            background: linear-gradient(135deg, var(--accent-1), var(--accent-2));
            color: white;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: transform 0.2s;
        }
        .btn:hover { transform: translateY(-2px); }
        .error { color: #ff4757; margin-bottom: 1rem; font-size: 0.9rem; }
    </style>
</head>
<body>
    <div class="login-box">
        <h2><span class="highlight">Staff</span> Access</h2>
        <?php if($error): ?><div class="error"><?php echo htmlspecialchars($error); ?></div><?php endif; ?>
        <form method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" class="btn">Authenticate</button>
        </form>
    </div>
</body>
</html>
