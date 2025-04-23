<?php
session_start();
include 'database.php';

// if ($_SERVER["REQUEST_METHOD"] == "POST") {
//     $action = $_POST['action'];

//     if ($action == "register") {
//         // Registration logic
//         $email = $_POST['email'];
//         $username = $_POST['username'];
//         $password = $_POST['password'];
//         $confirm_password = $_POST['confirm_password'];

//         if ($password !== $confirm_password) {
//             echo "Passwords do not match!";
//             exit;
//         }

//         // Hash password for security
//         $hashed_password = password_hash($password, PASSWORD_DEFAULT);

//         // Check if username or email already exists
//         $check_user = $conn->prepare("SELECT id FROM user WHERE username = ? OR email = ?");
//         $check_user->bind_param("ss", $username, $email);
//         $check_user->execute();
//         $check_user->store_result();

//         if ($check_user->num_rows > 0) {
//             echo "Username or email already taken!";
//             exit;
//         }

//         // Insert into database
//         $stmt = $conn->prepare("INSERT INTO user (email, username, password) VALUES (?, ?, ?)");
//         $stmt->bind_param("sss", $email, $username, $hashed_password);

//         if ($stmt->execute()) {
//             echo "Registration successful!";
//             header("Location: index.php"); // Redirect to login page
//         } else {
//             echo "Error: " . $stmt->error;
//         }

//         $stmt->close();

//     } elseif ($action == "login") {
//         // Login logic
//         $username = $_POST['username'];
//         $password = $_POST['password'];

//         // Fetch user details
//         $stmt = $conn->prepare("SELECT id, username, password FROM user WHERE username = ?");
//         $stmt->bind_param("s", $username);
//         $stmt->execute();
//         $stmt->store_result();

//         if ($stmt->num_rows > 0) {
//             $stmt->bind_result($id, $username, $hashed_password);
//             $stmt->fetch();

//             // Verify password
//             if (password_verify($password, $hashed_password)) {
//                 $_SESSION['user_id'] = $id;
//                 $_SESSION['username'] = $username;
//                 header("Location: home.php"); // Redirect to dashboard/home
//                 exit();
//             } else {
//                 echo "Invalid credentials!";
//             }
//         } else {
//             echo "No user found!";
//         }

//         $stmt->close();
//     }
// }

// $conn->close();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $username = $_POST["username"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
     // Secure password hashing

    $sql = "INSERT INTO user (email, username, password) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $email, $username,$password);

    if ($stmt->execute()) {
        echo "Account created successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
 ?>
