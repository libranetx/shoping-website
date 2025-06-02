<?php
session_start();

// DB connection
$host = "localhost";
$dbname = "data";
$dbuser = "root";
$dbpass = "";

$conn = new mysqli($host, $dbuser, $dbpass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Sanitize input
function clean_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

// Handle POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = clean_input($_POST["email"]);
    $password = $_POST["password"];

    // REGISTER
    if (isset($_POST["name"])) {
        $name = clean_input($_POST["name"]);

        // Check if email exists
        $check = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $check->bind_param("s", $email);
        $check->execute();
        $check_result = $check->get_result();

        if ($check_result->num_rows > 0) {
            echo "<script>alert('Email already registered. Please log in.'); window.location.href='index.html';</script>";
            exit();
        }

        // Hash and insert user
        $hashed_pass = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $email, $hashed_pass);

        if ($stmt->execute()) {
            $_SESSION["user_id"] = $stmt->insert_id;
            $_SESSION["user_name"] = $name;
            $_SESSION["user_email"] = $email;
            header("Location: product.html");
            exit();
        } else {
            echo "<script>alert('Error creating account.'); window.location.href='index.html';</script>";
        }

    }
    // LOGIN
    else {
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows == 1) {
            $user = $result->fetch_assoc();

            if (password_verify($password, $user["password"])) {
                $_SESSION["user_id"] = $user["id"];
                $_SESSION["user_name"] = $user["name"];
                $_SESSION["user_email"] = $user["email"];
                header("Location: cart-page hidden");
                exit();
            } else {
                echo "<script>alert('Incorrect password.'); window.location.href='index.html';</script>";
                exit();
            }
        } else {
            echo "<script>alert('Email not found. Please register.'); window.location.href='index.html';</script>";
            exit();
        }
    }
}

$conn->close();
?>
