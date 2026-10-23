<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json');

class RegistrationHandler {
    private $conn;
    private const MIN_PASSWORD_LENGTH = 6;
    private const MIN_USERNAME_LENGTH = 3;
    
    public function __construct($connection) {
        $this->conn = $connection;
    }
    
    private function validateInput($fullName, $username, $email, $password) {
        $errors = [];
        
        // Username validation
        if (empty($username)) {
            $errors[] = "Username is required";
        } elseif (strlen($username) < self::MIN_USERNAME_LENGTH) {
            $errors[] = "Username must be at least " . self::MIN_USERNAME_LENGTH . " characters";
        } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
            $errors[] = "Username can only contain letters, numbers, and underscores";
        }
        
        // Email validation
        if (empty($email)) {
            $errors[] = "Email address is required";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Please enter a valid email address";
        }
        
        // Password validation
        if (empty($password)) {
            $errors[] = "Password is required";
        } elseif (strlen($password) < self::MIN_PASSWORD_LENGTH) {
            $errors[] = "Password must be at least " . self::MIN_PASSWORD_LENGTH . " characters";
        }

        if (empty($fullName)) {
            $errors[] = "Full name is required";
        }
        
        return $errors;
    }
    
    private function isEmailExists($email) {
        $stmt = $this->conn->prepare("SELECT email FROM user WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $exists = $result->num_rows > 0;
        $stmt->close();
        return $exists;
    }
    
    public function register($fullName, $username, $email, $password) {
        try {
            // Input sanitization
            $fullName = trim($fullName);
            $username = trim(strip_tags($username));
            $email = trim(filter_var($email, FILTER_SANITIZE_EMAIL));
            $password = trim($password);
    
            
            // Validate input
            $validationErrors = $this->validateInput($fullName, $username, $email, $password);
            if (!empty($validationErrors)) {
                return [
                    'success' => false,
                    'message' => "Validation failed",
                    'errors' => $validationErrors
                ];
            }
            
            // Check if email exists
            if ($this->isEmailExists($email)) {
                return [
                    'success' => false,
                    'message' => "An account with this email already exists"
                ];
            }
            
            // Hash password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            
            // Insert new user
            $stmt = $this->conn->prepare("INSERT INTO user (fullName, username, email, password) VALUES (?, ?, ?, ?)");
            if (!$stmt) {
                throw new Exception("Database preparation failed");
            }
            
            $stmt->bind_param("ssss", $fullName, $username, $email, $hashedPassword);
            
            if (!$stmt->execute()) {
                throw new Exception("Account creation failed");
            }
            
            $userId = $stmt->insert_id;
            $stmt->close();
            

            // Create cart for the new user
            if (!$this->createCartForUser($userId)) {
                throw new Exception("Failed to create cart for user");
            }
            // Set session
            $_SESSION['userID'] = $userId;
            $_SESSION['email'] = $email;
            $_SESSION['username'] = $username;
            $_SESSION['fullName'] = $fullName;
            
            return [
                'success' => true,
                'message' => "Welcome! Your account has been created successfully",
                'redirect' => '../Components/homeLI.php'
            ];
            
        } catch (Exception $e) {error_log("Registration error: " . $e->getMessage());
            return [
                'success' => false,
                'message' => "An account with this username already exist",
                'debug' => $e->getMessage() // Remove this in production
            ];
        }
    }

    private function createCartForUser($userId) {
        // Prepare the SQL statement with placeholders for the userID, payed, totalSum, and numItems
        $stmt = $this->conn->prepare("INSERT INTO cart (userID, payed, totalSum, numItems) VALUES (?, ?, ?, ?)");
    
        // Bind the parameters. 'i' for integer (userId, payed, totalSum, numItems)
        $payed = 0;  // boolean, but stored as 0 (false)
        $totalSum = 0;  // numeric, initialized to 0
        $numItems = 0;  // number of items in the cart, initialized to 0
    
        // Bind the variables to the prepared statement
        $stmt->bind_param("iiii", $userId, $payed, $totalSum, $numItems);
    
        // Execute the statement and return true if successful, else false
        if ($stmt->execute()) {
            $stmt->close();
            return true;
        } else {
            return false;
        }
    }
}

// Usage
try {
    require_once 'db_connect.php';
    
    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        throw new Exception("Invalid request method");
    }
    
    $handler = new RegistrationHandler($conn);
    $result = $handler->register(
        $_POST['fullName'] ?? '',
        $_POST['username'] ?? '',
        $_POST['email'] ?? '',
        $_POST['password'] ?? ''
    );
    
    echo json_encode($result);
    
} catch (Exception $e) {
    error_log("System error: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => "An unexpected error occurred. Please try again later."
    ]);
}