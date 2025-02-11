<?php
session_start();
include 'include.php';

if (isset($_POST['signUp'])) {
    $firstName = $_POST['fName'];
    $lastName = $_POST['lName'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $password = md5($password);

    $checkEmail = "SELECT * FROM users WHERE email='$email'";
    $result = $conn->query($checkEmail);
    if ($result->num_rows > 0) {
        // ❌ Email already exists: Redirect back with error message
        $_SESSION['error'] = "อีเมลนี้ถูกใช้งานแล้ว";
        header("Location: login.php");
        exit();
    } else {
        $insertQuery = "INSERT INTO users(firstName, lastName, username, email, password)
                       VALUES ('$firstName', '$lastName', '$username', '$email', '$password')";
        if ($conn->query($insertQuery) === TRUE) {
            header("location: login.php");
            exit();
        } else {
            echo "Error:" . $conn->error;
        }
    }
}

if (isset($_POST['signIn'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $password = md5($password);

    $sql = "SELECT * FROM users WHERE email='$email' AND password='$password'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        session_start();
        $row = $result->fetch_assoc();
        $_SESSION['email'] = $row['email'];
        header("Location: main.php");
        exit();
    } else {
        // ❌ Login failed: Redirect back with error message
        $_SESSION['error'] = "อีเมลหรือรหัสผ่านไม่ถูกต้อง";
        header("Location: login.php");
        exit();
    }
}
?>
