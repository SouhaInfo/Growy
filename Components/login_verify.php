<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json');

try {
    require_once 'db_connect.php';

    if ($_SERVER["REQUEST_METHOD"] != "POST") {
        throw new Exception("Invalid request method");
    }

    // Debug: Log received data
    error_log("Received login attempt - Email: " . ($_POST['email'] ?? 'not set'));

    // Validate input
    if (!isset($_POST['email']) || !isset($_POST['password'])) {
        throw new Exception("Email and password are required");
    }

    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $remember = isset($_POST['remember_me']) ? true : false;

    // Debug: Log cleaned input
    error_log("Cleaned input - Email: $email, Password length: " . strlen($password));

    // Additional validation
    if (empty($email) || empty($password)) {
        throw new Exception("Email and password cannot be empty");
    }

    if (strlen($password) < 6) {
        throw new Exception("Password must be at least 6 characters long");
    }

    // Check email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception("Invalid email format");
    }

    // Prepare and execute query
    $query = "SELECT * FROM user WHERE email = ?";
    $stmt = $conn->prepare($query);

    if (!$stmt) {
        throw new Exception("Database error: " . $conn->error);
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // Debug: Log database query result
    error_log("Database query completed - Found rows: " . $result->num_rows);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        // Debug: Log hash comparison
        error_log("Stored hash: " . $user['password']);
        error_log("Attempting password verification...");
        
        $verified = password_verify($password, $user['password']);
        error_log("Password verification result: " . ($verified ? 'true' : 'false'));

        if ($verified) {
            // Login successful
            $_SESSION['userID'] = $user['userID'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['username'] = $user['username'];
              
            if ($remember) {
                $token = bin2hex(random_bytes(32));
                $expire = time() + (30 * 24 * 60 * 60); // 30 days

                $updateQuery = "UPDATE user SET remember_token = ? WHERE userID = ?";
                $updateStmt = $conn->prepare($updateQuery);
                $updateStmt->bind_param("si", $token, $user['userID']);
                $updateStmt->execute();

                setcookie("remember_token", $token, $expire, "/", "", false, true);
            }
            error_log("Login successful for user: " . $user['email']);
            
            echo json_encode([
                'success' => true,
                'message' => 'Login successful',
                'redirect' => '../index.php'
            ]);
        } else {
            // Invalid password
            error_log("Password verification failed for user: " . $user['email']);
            echo json_encode([
                'success' => false,
                'message' => 'Invalid email or password'
            ]);
        }
    } else {
        // User not found
        error_log("No user found with email: $email");
        echo json_encode([
            'success' => false,
            'message' => 'Invalid email or password'
        ]);
    }

    $stmt->close();
    $conn->close();

} catch (Exception $e) {
    error_log("Login error: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>