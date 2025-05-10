<?php
include('db.php');
session_start(); // Start session before any output

// Validate if required fields are set
if (
    !isset($_POST['agency_name'], $_POST['email'], $_POST['contact_number'],
             $_POST['agency_type'], $_POST['address'], $_POST['password'], $_POST['confirm_password'])
) {
    die("Please fill in all required fields.");
}

// Sanitize user input
$agency_name     = trim($_POST['agency_name']);
$email           = trim($_POST['email']);
$contact_number  = trim($_POST['contact_number']);
$agency_type     = trim($_POST['agency_type']);
$address         = trim($_POST['address']);
$password        = $_POST['password'];
$confirm_password= $_POST['confirm_password'];

// Password confirmation check
if ($password !== $confirm_password) {
    die("Passwords do not match.");
}

// Optional: check for valid email format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die("Invalid email format.");
}

// Optional: check if agency already exists
$check_sql = "SELECT id FROM agencies WHERE email = ?";
$check_stmt = $conn->prepare($check_sql);
$check_stmt->bind_param("s", $email);
$check_stmt->execute();
$check_stmt->store_result();

if ($check_stmt->num_rows > 0) {
    die("An agency with this email already exists.");
}
$check_stmt->close();

// Hash the password securely
$password_hash = password_hash($password, PASSWORD_DEFAULT);

// Insert agency
$sql = "INSERT INTO agencies (agency_name, email, contact_number, agency_type, address, password)
        VALUES (?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("SQL error: " . $conn->error);
}

$stmt->bind_param("ssssss", $agency_name, $email, $contact_number, $agency_type, $address, $password_hash);

if ($stmt->execute()) {
    $_SESSION['agency_id'] = $stmt->insert_id;
    header("Location: dashboard.php");
    exit();
} else {
    echo "Error during registration: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
