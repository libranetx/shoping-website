<?php
// Database connection credentials
$host = "localhost";
$dbname = "data";
$username = "root";
$password = "";

try {
    // Connect to the database
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check if form was submitted
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        // Sanitize input
        $name = htmlspecialchars(trim($_POST["name"]));
        $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
        $message = htmlspecialchars(trim($_POST["message"]));

        // Basic validation
        if (!empty($name) && filter_var($email, FILTER_VALIDATE_EMAIL) && !empty($message)) {
            // Insert into database
            $stmt = $pdo->prepare("INSERT INTO contact_messages (name, email, message) VALUES (?, ?, ?)");
            $stmt->execute([$name, $email, $message]);

            echo "<div style='padding: 20px; background: #e0ffe0; color: green; border-radius: 5px; max-width: 500px; margin: 40px auto; text-align:center;'>
                <h2>Thank you, $name!</h2>
                <p>Your message has been successfully submitted.</p>
                <a href='contactus.html' style='display:inline-block; margin-top:10px;'>Back to Contact Page</a>
            </div>";
        } else {
            echo "<div style='padding: 20px; background: #ffe0e0; color: red; border-radius: 5px; max-width: 500px; margin: 40px auto; text-align:center;'>
                <h2>Error</h2>
                <p>Please provide valid input in all fields.</p>
                <a href='contactus.html' style='display:inline-block; margin-top:10px;'>Back to Contact Page</a>
            </div>";
        }
    } else {
        header("Location: contactus.html");
        exit();
    }

} catch (PDOException $e) {
    echo "<div style='padding: 20px; background: #ffe0e0; color: red; border-radius: 5px; max-width: 500px; margin: 40px auto; text-align:center;'>
        <h2>Database Error</h2>
        <p>" . $e->getMessage() . "</p>
    </div>";
}
?>
