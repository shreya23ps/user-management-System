<?php
include 'db.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: index.php");
    exit;
}

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $email = trim($_POST['email']);

    if (empty($first_name) || empty($last_name) || empty($email)) {
        $errors[] = "All fields are required.";
    } else {
        try {
            $stmt = $pdo->prepare("UPDATE users SET first_name = ?, last_name = ?, email = ? WHERE id = ?");
            $stmt->execute([$first_name, $last_name, $email, $id]);
            header("Location: index.php");
            exit;
        } catch (Exception $e) {
            $errors[] = "Error: " . $e->getMessage();
        }
    }
}

try {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        header("Location: index.php");
        exit;
    }
} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
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
            max-width: 800px;
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

        /* Form Styling */
        form {
            display: flex;
            flex-direction: column;
        }

        label {
            font-size: 1.1rem;
            margin-bottom: 5px;
        }

        input {
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #f4f4f4;
            font-size: 1rem;
        }

        input:focus {
            outline: none;
            border-color: #28a745;
        }

        .btn-edit {
            background-color: #007bff;
            padding: 12px 25px;
            border-radius: 10px;
            text-decoration: none;
            color: white;
            font-weight: bold;
            margin-top: 10px;
            transition: background-color 0.3s ease;
        }

        .btn-edit:hover {
            background-color: #0056b3;
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
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>Edit User</h1>
            <a href="index.php" class="btn btn-back">Back to User List</a>
        </header>

        <?php if (!empty($errors)): ?>
            <div class="error">
                <?php foreach ($errors as $error): ?>
                    <p><?= htmlspecialchars($error) ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form action="edit_user.php?id=<?= $id ?>" method="POST" class="form">
            <label for="first_name">First Name:</label>
            <input type="text" id="first_name" name="first_name" value="<?= htmlspecialchars($user['first_name']) ?>" required>

            <label for="last_name">Last Name:</label>
            <input type="text" id="last_name" name="last_name" value="<?= htmlspecialchars($user['last_name']) ?>" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>

            <button type="submit" class="btn btn-edit">Save Changes</button>
        </form>
    </div>
</body>
</html>
