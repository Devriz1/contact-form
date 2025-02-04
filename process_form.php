<?php
// Database configuration for WAMP on D drive
$host = 'localhost';
$dbname = 'contact_form_db';
$username = 'root';
$password = ''; 

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    // Create database connection
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection Error: " . $e->getMessage());
}

// Form processing
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Modern sanitization methods
    $name = htmlspecialchars($_POST['name'], ENT_QUOTES, 'UTF-8');
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $phone = htmlspecialchars($_POST['phone'], ENT_QUOTES, 'UTF-8');
    $message = htmlspecialchars($_POST['message'], ENT_QUOTES, 'UTF-8');

    if (empty($name) || empty($email) || empty($message)) {
        die("Please fill all required fields.");
    }

    if ($email === false) {
        die("Invalid email address.");
    }

    try {
        $stmt = $pdo->prepare("INSERT INTO contact_messages 
            (name, email, phone, message, submitted_at) 
            VALUES (:name, :email, :phone, :message, NOW())");

        $stmt->execute([
            ':name' => $name,
            ':email' => $email,
            ':phone' => $phone,
            ':message' => $message
        ]);

        echo "Message sent successfully!";
    } catch(PDOException $e) {
        echo "Submission Error: " . $e->getMessage();
    }
}
?>