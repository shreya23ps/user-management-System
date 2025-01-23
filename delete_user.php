<?php
include 'db.php';

// Check if 'id' is present in the URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];

    try {
        // Prepare and execute the delete query
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$id]);

        // Redirect after successful deletion
        header('Location: index.php');
        exit;
    } catch (Exception $e) {
        // Handle error (you might want to log it or display a friendly message)
        echo "Error deleting user: " . $e->getMessage();
    }
} else {
    // If no valid id, redirect back or show error
    header('Location: index.php');
    exit;
}
?>
