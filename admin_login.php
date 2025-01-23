<?php
session_start();
include 'db.php';

// Automatically insert default admin credentials if not present
try {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM admins WHERE username = :username");
    $stmt->execute([':username' => 'shreya']);
    $adminExists = $stmt->fetchColumn();

    if (!$adminExists) {
        $defaultUsername = 'shreya';
        $defaultPassword = password_hash('1357', PASSWORD_DEFAULT);
        $insertStmt = $pdo->prepare("INSERT INTO admins (username, password) VALUES (:username, :password)");
        $insertStmt->execute([ 
            ':username' => $defaultUsername, 
            ':password' => $defaultPassword
        ]);
        echo "Default admin credentials inserted successfully.<br>";
    }
} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}

// Handle login
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    try {
        $stmt = $pdo->prepare("SELECT * FROM admins WHERE username = :username");
        $stmt->execute([':username' => $username]);
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($admin && password_verify($password, $admin['password'])) {
            // Successful login
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_username'] = $admin['username'];
            header("Location: index.php");
            exit;
        } else {
            $errors[] = "Invalid username or password.";
        }
    } catch (Exception $e) {
        $errors[] = "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <style>
        /* Resetting some defaults */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

/* Body styling with background image */
body {
    font-family: 'Poppins', sans-serif;
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    background: url('bg.jpg') no-repeat center center fixed;
    background-size: cover;
    background-attachment: fixed;
    color: #ffffff;
    overflow: hidden;
}

/* Adding a dark overlay to the background */
.overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5); /* Darken the background */
    z-index: 0;
}

/* Login form container with blur effect */
.login-container {
    position: relative;
    z-index: 1;
    background: rgba(255, 255, 255, 0.2); /* Semi-transparent white */
    backdrop-filter: blur(10px); /* This adds the blur effect */
    padding: 40px;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
    width: 100%;
    max-width: 420px;
    text-align: center;
    border: 1px solid rgba(255, 255, 255, 0.3); /* Light border for the form */
}

/* Heading styling */
h2 {
    font-family: 'Poppins', sans-serif;
    font-size: 2.2rem;
    font-weight: 600;
    color: #fff;
    margin-bottom: 20px;
    text-transform: uppercase;
    letter-spacing: 1.5px;
}

/* Input fields styling */
.form-group {
    margin-bottom: 20px;
}

.form-group label {
    font-size: 1rem;
    font-weight: 500;
    color: #fff;
    display: block;
    margin-bottom: 8px;
}

/* Styled input fields */
.form-group input {
    width: 100%;
    padding: 14px;
    font-size: 1rem;
    font-family: 'Poppins', sans-serif;
    border: 2px solid #fff;
    border-radius: 8px;
    background-color: rgba(255, 255, 255, 0.1);
    color: #fff;
    outline: none;
    transition: border-color 0.3s ease, background-color 0.3s ease;
}

/* Focus effect */
.form-group input:focus {
    border-color: #4CAF50;
    background-color: rgba(255, 255, 255, 0.3);
}

/* Button Styling */
.btn-login {
    width: 100%;
    padding: 14px;
    background-color: #4CAF50;
    color: #fff;
    border: none;
    border-radius: 8px;
    font-size: 1.1rem;
    font-family: 'Poppins', sans-serif;
    cursor: pointer;
    transition: background-color 0.3s ease, transform 0.3s ease;
}

/* Button hover and active states */
.btn-login:hover {
    background-color: #45a049;
    transform: translateY(-2px);
}

.btn-login:active {
    background-color: #399e3c;
    transform: translateY(1px);
}

/* Error message styling */
.error {
    background-color: rgba(255, 99, 71, 0.8);
    color: #fff;
    padding: 15px;
    border-radius: 8px;
    margin-bottom: 20px;
    font-weight: 500;
    text-align: center;
}

/* Responsive design for smaller screens */
@media (max-width: 480px) {
    .login-container {
        width: 85%;
        padding: 30px;
    }

    h2 {
        font-size: 1.8rem;
    }
}

    </style>
</head>
<body>
    <div class="overlay"></div>

    <div class="login-container">
        <h2>Admin Login</h2>

        <?php if (!empty($errors)): ?>
            <div class="error">
                <?php foreach ($errors as $error): ?>
                    <p><?= htmlspecialchars($error) ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form action="admin_login.php" method="POST">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="btn-login">Login</button>
        </form>
    </div>
</body>
</html>
