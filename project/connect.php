<?php
// Configuration
$db_host = 'localhost';
$db_username = 'root';
$db_password = '';
$db_name = 'form';

// Create a connection to the database
$conn = new mysqli($db_host, $db_username, $db_password, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: ". $conn->connect_error);
}

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $date = $_POST['date'];
    $time = $_POST['time'];

    // Validate input data
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email address.";
        exit;
    }

    // Validate phone number
    if (!preg_match('/^[0-9]{10}$/', $phone)) {
        echo "Invalid phone number.";
        exit;
    }

    // Prepare the SQL statement
    $stmt = $conn->prepare("INSERT INTO login (name, email, phone, date, time) VALUES (?,?,?,?,?)");

    // Check if the statement is prepared correctly
    if (!$stmt) {
        error_log('Error preparing statement: '. $conn->error);
        echo "Error preparing statement.";
        exit;
    }

    // Bind parameters
    $stmt->bind_param("sssss", $name, $email, $phone, $date, $time);

    // Execute the query
    if (!$stmt->execute()) {
        error_log('Error: '. $stmt->errno. ' '. $stmt->error);
        echo "Error inserting data into the database.";
    } else {
        echo "RESERVATION SUCCESSFULLY...";
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>