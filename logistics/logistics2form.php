<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Database connection
$host = 'localhost';
$dbname = 'user'; // Your database name
$username = 'root';
$password = '';

$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Insert data into the database after form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Fetch form data
    $company_name = $_POST['company_name'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $full_name = $_POST['full_name'] ?? '';
    $gender = $_POST['gender'] ?? '';
    $city = $_POST['city'] ?? '';
    $state = $_POST['state'] ?? '';
    $business_registration = $_POST['business_registration'] ?? '';
    $mayor_permit = $_POST['mayor_permit'] ?? '';
    $tin = $_POST['tin'] ?? '';
    $proof_of_identity = $_POST['proof_of_identity'] ?? '';

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert query
    // Insert query
    $sql = "INSERT INTO vendors (company_name, email, password, full_name, gender, city, state, business_registration, mayor_permit, tin, proof_of_identity) 
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";


    // Prepare and execute statement
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("sssssssssss", $company_name, $email, $hashed_password, $full_name, $gender, $city, $state, $business_registration, $mayor_permit, $tin, $proof_of_identity);


        if ($stmt->execute()) {
            echo "Vendor successfully registered!<br>";
            echo "Redirecting to logistics2.php...";
            // Use this instead of header redirect for debugging
            // header("Location: logistics2.php");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Error preparing the statement: " . $conn->error;
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vendor Registration</title>
    <style>
        /* Your form styling */
    </style>
</head>

<body>
    <div class="form-container">
        <h2>Vendor Registration</h2>
        <form action="logistics2form.php" method="POST">
            <!-- Input fields -->
            <label for="company_name">Company Name:</label>
            <input type="text" name="company_name" id="company_name" required>

            <label for="email">Email:</label>
            <input type="email" name="email" id="email" required>

            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required>

            <label for="full_name">Full Name:</label>
            <input type="text" name="full_name" id="full_name" required>

            <label for="gender">Gender:</label>
            <select name="gender" id="gender" required>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
                <option value="Other">Other</option>
            </select>

            <label for="city">City:</label>
            <input type="text" name="city" id="city" required>

            <label for="state">State:</label>
            <input type="text" name="state" id="state" required>

            <label for="business_registration">Business Registration:</label>
            <input type="text" name="business_registration" id="business_registration" required>

            <label for="mayor_permit">Mayor's Permit:</label>
            <input type="text" name="mayor_permit" id="mayor_permit" required>

            <label for="tin">TIN:</label>
            <input type="text" name="tin" id="tin" required>

            <label for="proof_of_identity">Proof of Identity:</label>
            <input type="text" name="proof_of_identity" id="proof_of_identity" required>

            <button type="submit">Register Vendor</button>
        </form>
    </div>
</body>

</html>