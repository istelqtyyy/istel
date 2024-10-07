<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $recipient = $_POST['recipient'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];

    // Send email
    if (mail($recipient, $subject, $message)) {
        // Redirect to email.php after successful send
        header("Location: email.php");
        exit();
    } else {
        $error = "Failed to send email.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Form</title>
    <style>
        body {
            background-color: black;
            color: yellow;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }

        form {
            background-color: #333;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(255, 255, 255, 0.2);
            max-width: 400px;
            margin: auto;
        }

        label {
            display: block;
            margin-bottom: 5px;
        }

        input[type="email"],
        input[type="text"],
        textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid yellow;
            border-radius: 4px;
            background-color: #444;
            color: yellow;
        }

        input[type="email"]:focus,
        input[type="text"]:focus,
        textarea:focus {
            border-color: orange;
            outline: none;
        }

        button {
            background-color: yellow;
            color: black;
            border: none;
            padding: 10px 15px;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
        }

        button:hover {
            background-color: orange;
        }

        .error {
            color: red;
            margin-top: 10px;
        }
    </style>
</head>

<body>

    <form method="POST" action="">
        <label for="recipient">Recipient:</label>
        <input type="email" name="recipient" required>

        <label for="subject">Subject:</label>
        <input type="text" name="subject" required>

        <label for="message">Message:</label>
        <textarea name="message" required></textarea>

        <button type="submit" name="send">Send</button>

        <?php if (isset($error)): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
    </form>

</body>

</html>