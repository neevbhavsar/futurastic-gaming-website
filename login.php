<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "callisto";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle registration
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['register'])) {
    $name = $_POST['registerName'];
    $email = $_POST['registerEmail'];
    $password = $_POST['registerPassword'];
    $confirmPassword = $_POST['confirmPassword'];

    if ($password !== $confirmPassword) {
        echo "<script>alert('Passwords do not match!');</script>";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $email, $hashed_password);
        
        if ($stmt->execute()) {
            echo "<script>alert('Registration successful! Redirecting to login.'); window.location.href='login.php';</script>";
        } else {
            echo "<script>alert('Registration failed: Email might already be used.');</script>";
        }
        $stmt->close();
    }
}

// Handle login
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    $email = $_POST['loginEmail'];
    $password = $_POST['loginPassword'];

    $stmt = $conn->prepare("SELECT password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($hashed_password);
        $stmt->fetch();
        if (password_verify($password, $hashed_password)) {
            echo "<script>alert('Login successful! Redirecting...'); window.location.href='index.php';</script>";
            exit();
        } else {
            echo "<script>alert('Invalid email or password.');</script>";
        }
    } else {
        echo "<script>alert('No user found with this email.');</script>";
    }
    $stmt->close();
}
?>

<!-- Start Generation Here -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <title>Login and Registration</title>
    <style>
        :root {
            --primary-color: #910303; /* Primary color variable */
            --primary-color2: #0077ff;
            --secondary-color: #e84118; /* Secondary color variable */
            --form-control-bg: rgba(255, 255, 255, 0.2); /* Form control background color variable */
            --form-control-focus-bg: rgba(255, 255, 255, 0.3); /* Form control focus background color variable */
        }

        body {
            display: flex; /* Added display flex */
            justify-content: center; /* Center horizontally */
            align-items: center; /* Center vertically */
            height: 100vh; /* Full viewport height */
            margin: 0; /* Remove default margin */
            background: linear-gradient(to right, rgba(0, 0, 0, 0.414), rgba(0, 0, 0, 0.9)), url('https://i.dell.com/is/image/DellContent/content/dam/ss2/product-images/page/alienware/hero-learn-desktop.jpg?fmt=jpg&wid=2880&hei=1440') no-repeat center center fixed; /* Background image with linear gradient */
            background-size: cover; /* Cover the entire body */
            color: rgb(255, 255, 255);
            font-family: 'Arial', sans-serif;
        }
        .form-container {
            max-width: 500px; /* Increased width */
            padding: 20px;
            border: 2px solid var(--primary-color);
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(255, 255, 255);
            background: rgba(223, 193, 193, 0.121); /* Adjusted background for better contrast */
            backdrop-filter: blur(10px); /* Apply blur effect */
            position: relative;
            overflow: hidden;
            width: 90%; /* Make it responsive */
        }
        .form-container::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            /* background: url('logo.png') no-repeat center center; */
            background-size: cover;
            opacity: 0.1;
            z-index: 0;
        }
        .form {
            display: none;
            position: relative;
            z-index: 1;
            width: 100%; /* Make it responsive */
            height: auto;
            margin: auto;
            transition: transform 0.5s ease; /* Add transition for animation */
        }
        .form.active {
            display: block;
            transform: translateY(0); /* Reset position */
        }
        .form.inactive {
            transform: translateY(-100%); /* Slide up out */
        }
        .form-header {
            text-align: center;
            margin-bottom: 20px;
            width: 100%; /* Full width */
            display: flex;
            flex-direction: row; /* Stack headers on smaller screens */
            padding: 10px 0px;
        }
        .form-header span {
            cursor: pointer;
            margin: 0 15px;
            padding: 5px 10px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
            width: 50%; /* Set width to 100% for responsiveness */
            display: inline-block; /* Make spans inline-block for width to take effect */
        }
        .form-header .active {
            background-color: var(--primary-color); /* Highlight color */
            color: white;
            width: 50%;
        }
        .new-registration, .new-login {
            text-align: center;
            margin: 20px 0px;
        }
        .new-registration a, .new-login a {
            cursor: pointer;
            color: var(--primary-color);
            text-decoration: underline;
        }
        .form-control {
            background-color: var(--form-control-bg);
            border: 1px solid var(--primary-color);
            color: white;
        }
        .form-control:focus {
            background-color: var(--form-control-focus-bg);
            border-color: var(--primary-color);
            color: white;
        }
        .btn-primary, .btn-secondary {
            background-color: var(--primary-color);
            border: none;
        }
        .btn-primary:hover, .btn-secondary:hover {
            background-color: var(--secondary-color);
        }
        .social-login {
            text-align: center;
            margin: 20px 0;
            width: 100%;
        }
        .social-login button {
            margin: 5px;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            color: white;
        }
        .btn-google { background-color: #db4437; }
        .btn-facebook { background-color: #3b5998; }
        .btn-twitter { background-color: #1da1f2; }
        .btn-apple { background-color: #000; }
        .social-icons {
            display: flex;
            justify-content: center;
            margin-top: 10px;
        }
        .social-login i {
            font-size: 18px; /* Set icon size */
            margin: 0 5px; /* Space between icons */
        }
        .social-login {
            text-align: center;
            margin: 20px 0;
        }
        /* Media Queries for Responsiveness */
        @media (max-width: 768px) {
            .form-container {
                padding: 15px;
            }
            .form-header {
                flex-direction: row; /* Stack headers */
                width: 100%;
            }
            .form-header span {
                margin: 10px 0; /* Space between headers */
                width: 50%; /* Full width */
            }
            .form-header .active {
                width: 50%;
            }
            .social-login {
                text-align: center;
                margin: 20px 0;
                width: 100%;
            }
            .btn-google { background-color: #db4437; width: 100%; }
        .btn-facebook { background-color: #3b5998; width: 100%;}
        .btn-apple { background-color: #000; width: 100%;}
        }
    </style>
</head>
<body>

<div class="form-container">
    <div class="form-header">
        <span id="loginHeader" class="active">Login</span>
        <span id="registerHeader">Register</span>
    </div>

    <form id="loginForm" class="form active" method="POST">
        <h2 class="text-center">Login</h2>
        <div class="form-group">
            <label for="loginEmail">Email:</label>
            <input type="email" class="form-control" id="loginEmail" name="loginEmail" required>
        </div>
        <div class="form-group">
            <label for="loginPassword">Password:</label>
            <input type="password" class="form-control" id="loginPassword" name="loginPassword" required>
        </div>
        <button type="submit" name="login" class="btn btn-primary btn-block">Login</button>
        <div class="social-login">
            <button class="btn btn-google">Login with <i class="fab fa-google"></i></button>
            <button class="btn btn-facebook">Login with <i class="fab fa-facebook-f"></i></button>
            <button class="btn btn-apple">Login with <i class="fab fa-apple"></i></button>
        </div>
        <div class="new-registration">
            <span>New here? <a onclick="showRegister()" style="color: var(--primary-color2);text-decoration: underline;">Register now</a></span>
        </div>
    </form>

    <form id="registerForm" class="form inactive" method="POST">
        <h2 class="text-center">Registration</h2>
        <div class="form-group">
            <label for="registerName">Name:</label>
            <input type="text" class="form-control" id="registerName" name="registerName" required>
        </div>
        <div class="form-group">
            <label for="registerEmail">Email:</label>
            <input type="email" class="form-control" id="registerEmail" name="registerEmail" required>
        </div>
        <div class="form-group">
            <label for="registerPassword">Password:</label>
            <input type="password" class="form-control" id="registerPassword" name="registerPassword" required>
        </div>
        <div class="form-group">
            <label for="confirmPassword">Confirm Password:</label>
            <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" required>
        </div>
        <button type="submit" name="register" class="btn btn-secondary btn-block">Register</button>
        <div class="new-login">
            <span>Already a User? <a onclick="showLogin()" style="color: var(--primary-color2);text-decoration: underline;">Login now</a></span>
        </div>
    </form>
</div>

<script>
    function showLogin() {
        document.getElementById('registerForm').classList.add('inactive');
        document.getElementById('loginForm').classList.remove('inactive');
        document.getElementById('loginForm').classList.add('active');
        document.getElementById('registerForm').classList.remove('active');
        document.getElementById('loginHeader').classList.add('active');
        document.getElementById('registerHeader').classList.remove('active');
    }

    function showRegister() {
        document.getElementById('loginForm').classList.add('inactive');
        document.getElementById('registerForm').classList.remove('inactive');
        document.getElementById('registerForm').classList.add('active');
        document.getElementById('loginForm').classList.remove('active');
        document.getElementById('loginHeader').classList.remove('active');
        document.getElementById('registerHeader').classList.add('active');
    }
</script>

</body>
</html>
<!-- End Generation Here -->
