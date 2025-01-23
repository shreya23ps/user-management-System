<?php
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit;
}
?>

<?php
include 'db.php';

try {
    $stmt = $pdo->query("SELECT * FROM users"); // Fetch all users
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $users = [];
    echo "Error fetching users: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>

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
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>User Management System</h1>
            <a href="add_user.php" class="btn">+ Add New User</a>
        </header>

        <?php if (isset($error) && !empty($error)): ?>
            <div class="error">
                <p><?= htmlspecialchars($error) ?></p>
            </div>
        <?php endif; ?>

        <table class="user-table">
            <thead>
                <tr>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Email</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($users)): ?>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?= htmlspecialchars($user['first_name']) ?></td>
                            <td><?= htmlspecialchars($user['last_name']) ?></td>
                            <td><?= htmlspecialchars($user['email']) ?></td>
                            <td>
                                <a href="edit_user.php?id=<?= $user['id'] ?>" class="btn-edit">Edit</a>
                                <a href="delete_user.php?id=<?= $user['id'] ?>" class="btn-delete" onclick="return confirm('Are you sure?')">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="no-data">No users found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
