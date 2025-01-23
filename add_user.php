<?php
include 'db.php';

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    if (empty($first_name) || empty($last_name) || empty($email) || empty($password) || empty($confirm_password)) {
        $errors[] = "All fields are required.";
    } elseif ($password !== $confirm_password) {
        $errors[] = "Passwords do not match.";
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO users (first_name, last_name, email, password) VALUES (?, ?, ?, ?)");
            $stmt->execute([$first_name, $last_name, $email, password_hash($password, PASSWORD_DEFAULT)]);
            header("Location: index.php");
            exit;
        } catch (Exception $e) {
            $errors[] = "Error: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add User</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Global Styles */
        body {
            font-family: 'Arial', sans-serif;
            background: url('bg.jpg') no-repeat center center fixed;
            background-size: cover;
            color: #fff;
            padding: 0;
            margin: 0;
        }

        /* Container Styling */
        .container {
            max-width: 1200px;
            margin: 50px auto;
            background: rgba(0, 0, 0, 0.6); /* Dark overlay for contrast */
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.3);
            animation: fadeIn 1s ease-in-out;
        }

        /* Header Styling */
        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        header h1 {
            font-size: 2.5rem;
            font-weight: bold;
            color: #fff;
        }

        header .btn {
            font-size: 1rem;
            font-weight: bold;
            padding: 12px 25px;
            border-radius: 10px;
            text-decoration: none;
            background-color: #28a745;
            color: white;
            transition: transform 0.2s ease, background-color 0.3s ease;
        }

        .btn:hover {
            background-color: #218838;
            transform: scale(1.05);
        }

        /* Table Styling */
        .user-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
            color: #fff;
        }

        .user-table th, .user-table td {
            padding: 15px;
            text-align: left;
            border: 1px solid #ddd;
        }

        .user-table th {
            background-color: rgba(0, 0, 0, 0.6);
            font-weight: bold;
        }

        .user-table td {
            background-color: rgba(0, 0, 0, 0.4);
        }

        .user-table tr:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }

        .btn-edit, .btn-delete {
            text-decoration: none;
            padding: 8px 15px;
            border-radius: 5px;
            color: white;
            font-size: 0.9rem;
            margin-right: 10px;
        }

        .btn-edit {
            background-color: #007bff;
        }

        .btn-edit:hover {
            background-color: #0056b3;
        }

        .btn-delete {
            background-color: #dc3545;
        }

        .btn-delete:hover {
            background-color: #c82333;
        }

        .no-data {
            text-align: center;
            font-size: 1.2rem;
            color: #ccc;
        }

        /* Error Message Styling */
        .error {
            background-color: #f8d7da;
            color: #721c24;
            padding: 10px 15px;
            border: 1px solid #f5c6cb;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        /* Animations */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Form Styling */
        .form-container {
            display: flex;
            justify-content: space-between;
        }

        .form-left {
            flex: 1;
            text-align: center;
        }

        .user-icon {
            color: #fff;
        }

        .form-right {
            flex: 2;
            padding: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            font-size: 1.1rem;
        }

        .form-group input {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #f4f4f4;
        }

        .form-group input:focus {
            outline: none;
            border-color: #28a745;
        }

        .btn-add {
            background-color: #007bff;
            padding: 15px 25px;
            border-radius: 10px;
            text-decoration: none;
            color: white;
            font-weight: bold;
            display: inline-block;
            margin-top: 20px;
            transition: background-color 0.3s ease;
        }

        .btn-add:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>Add New User</h1>
            <a href="index.php" class="btn btn-back">Back to User List</a>
        </header>

        <?php if (!empty($errors)): ?>
            <div class="error">
                <?php foreach ($errors as $error): ?>
                    <p><?= htmlspecialchars($error) ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <div class="form-container">
            <div class="form-left">
                <i class="fas fa-user fa-5x user-icon"></i>
            </div>

            <div class="form-right">
                <form action="add_user.php" method="POST" class="form">
                    <div class="form-group">
                        <label for="first_name"><i class="fas fa-user"></i> First Name:</label>
                        <input type="text" id="first_name" name="first_name" required>
                    </div>

                    <div class="form-group">
                        <label for="last_name"><i class="fas fa-user"></i> Last Name:</label>
                        <input type="text" id="last_name" name="last_name" required>
                    </div>

                    <div class="form-group">
                        <label for="email"><i class="fas fa-envelope"></i> Email:</label>
                        <input type="email" id="email" name="email" required>
                    </div>

                    <div class="form-group">
                        <label for="password"><i class="fas fa-lock"></i> Password:</label>
                        <input type="password" id="password" name="password" required>
                    </div>

                    <div class="form-group">
                        <label for="confirm_password"><i class="fas fa-lock"></i> Confirm Password:</label>
                        <input type="password" id="confirm_password" name="confirm_password" required>
                    </div>

                    <button type="submit" class="btn btn-add">Add User</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
