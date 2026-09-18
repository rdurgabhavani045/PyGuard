<?php
session_start();
require_once "db.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    if (empty($email) || empty($password)) {
        $error = "Please enter email and password.";
    } else {

        $stmt = $conn->prepare("SELECT id, name, email, password FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows === 1) {

            $user = $result->fetch_assoc();

            if (password_verify($password, $user["password"])) {

                $_SESSION["user_id"] = $user["id"];
                $_SESSION["user_name"] = $user["name"];
                $_SESSION["user_email"] = $user["email"];

                header("Location: analyzer.php");
                exit();

            } else {
                $error = "Invalid email or password.";
            }

        } else {
            $error = "Invalid email or password.";
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login | PyGuard</title>

    <style>

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            min-height: 100vh;
            font-family: Arial, Helvetica, sans-serif;
            background:
                radial-gradient(circle at 20% 20%, rgba(124, 92, 255, 0.18), transparent 30%),
                radial-gradient(circle at 80% 70%, rgba(53, 216, 255, 0.12), transparent 30%),
                #080b16;

            color: #f4f7ff;

            display: flex;
            flex-direction: column;
        }

        /* =========================
           NAVBAR
        ========================= */

        .navbar {
            width: 100%;
            height: 75px;

            padding: 0 6%;

            display: flex;
            align-items: center;
            justify-content: space-between;

            background: rgba(8, 11, 22, 0.85);

            border-bottom: 1px solid rgba(255, 255, 255, 0.08);

            backdrop-filter: blur(15px);
        }

        .nav-logo {
            display: flex;
            align-items: center;
            gap: 12px;

            text-decoration: none;
            color: white;
        }

        .nav-logo-icon {
            width: 42px;
            height: 42px;

            border-radius: 12px;

            display: flex;
            align-items: center;
            justify-content: center;

            font-size: 23px;

            background: linear-gradient(
                135deg,
                #7c5cff,
                #35d8ff
            );

            box-shadow: 0 0 20px rgba(124, 92, 255, 0.35);
        }

        .nav-logo strong {
            font-size: 20px;
            letter-spacing: 0.5px;
        }

        .nav-logo span {
            color: #8f99b3;
            font-size: 12px;
            display: block;
            margin-top: 2px;
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .nav-links a {
            color: #aeb7ce;

            text-decoration: none;

            padding: 10px 17px;

            border-radius: 9px;

            font-size: 14px;

            transition: 0.3s;
        }

        .nav-links a:hover {
            color: white;
            background: rgba(124, 92, 255, 0.12);
        }

        .nav-links a.active {
            color: white;

            background: rgba(124, 92, 255, 0.18);

            border: 1px solid rgba(124, 92, 255, 0.3);
        }

        /* =========================
           LOGIN AREA
        ========================= */

        .login-container {
            flex: 1;

            display: flex;
            align-items: center;
            justify-content: center;

            padding: 50px 20px;
        }

        .login-card {
            width: 100%;
            max-width: 430px;

            padding: 40px;

            background: rgba(18, 23, 40, 0.88);

            border: 1px solid rgba(255, 255, 255, 0.09);

            border-radius: 20px;

            box-shadow:
                0 25px 70px rgba(0, 0, 0, 0.45),
                0 0 40px rgba(124, 92, 255, 0.08);

            backdrop-filter: blur(20px);
        }

        .login-icon {
            width: 70px;
            height: 70px;

            margin: 0 auto 20px;

            display: flex;
            align-items: center;
            justify-content: center;

            border-radius: 18px;

            font-size: 34px;

            background: linear-gradient(
                135deg,
                rgba(124, 92, 255, 0.25),
                rgba(53, 216, 255, 0.18)
            );

            border: 1px solid rgba(124, 92, 255, 0.3);

            box-shadow:
                0 0 30px rgba(124, 92, 255, 0.18);
        }

        .login-card h1 {
            text-align: center;

            font-size: 28px;

            margin-bottom: 8px;
        }

        .login-card .subtitle {
            text-align: center;

            color: #8f99b3;

            font-size: 14px;

            margin-bottom: 30px;
        }

        /* =========================
           ERROR MESSAGE
        ========================= */

        .error-message {
            background: rgba(255, 93, 115, 0.1);

            border: 1px solid rgba(255, 93, 115, 0.3);

            color: #ff7f91;

            padding: 12px 14px;

            border-radius: 9px;

            font-size: 13px;

            margin-bottom: 20px;

            text-align: center;
        }

        /* =========================
           FORM
        ========================= */

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;

            margin-bottom: 8px;

            color: #c7cee0;

            font-size: 13px;

            font-weight: 600;
        }

        .form-group input {
            width: 100%;

            padding: 14px 15px;

            border-radius: 10px;

            border: 1px solid #2b3248;

            outline: none;

            background: #0c101d;

            color: white;

            font-size: 14px;

            transition: 0.3s;
        }

        .form-group input::placeholder {
            color: #555f78;
        }

        .form-group input:focus {
            border-color: #7c5cff;

            box-shadow:
                0 0 0 3px rgba(124, 92, 255, 0.12);
        }

        /* =========================
           LOGIN BUTTON
        ========================= */

        .login-btn {
            width: 100%;

            border: none;

            padding: 14px;

            border-radius: 10px;

            background: linear-gradient(
                135deg,
                #7c5cff,
                #5c8cff
            );

            color: white;

            font-size: 15px;

            font-weight: 700;

            cursor: pointer;

            transition: 0.3s;

            box-shadow:
                0 10px 25px rgba(124, 92, 255, 0.2);
        }

        .login-btn:hover {
            transform: translateY(-2px);

            box-shadow:
                0 14px 30px rgba(124, 92, 255, 0.3);
        }

        /* =========================
           REGISTER
        ========================= */

        .register-text {
            text-align: center;

            margin-top: 25px;

            color: #8f99b3;

            font-size: 14px;
        }

        .register-text a {
            color: #8d78ff;

            text-decoration: none;

            font-weight: 600;
        }

        .register-text a:hover {
            color: #35d8ff;
        }

        /* =========================
           BACK HOME
        ========================= */

        .back-home {
            text-align: center;

            margin-top: 18px;
        }

        .back-home a {
            color: #69738d;

            text-decoration: none;

            font-size: 13px;

            transition: 0.3s;
        }

        .back-home a:hover {
            color: #c8d0e5;
        }

        /* =========================
           FOOTER
        ========================= */

        footer {
            text-align: center;

            padding: 20px;

            color: #59627a;

            font-size: 12px;

            border-top: 1px solid rgba(255, 255, 255, 0.05);
        }

        /* =========================
           RESPONSIVE
        ========================= */

        @media (max-width: 700px) {

            .navbar {
                padding: 0 20px;
            }

            .nav-links a {
                padding: 8px 10px;
                font-size: 12px;
            }

            .nav-logo span {
                display: none;
            }

            .login-card {
                padding: 30px 25px;
            }
        }

        @media (max-width: 480px) {

            .nav-logo strong {
                font-size: 17px;
            }

            .nav-logo-icon {
                width: 36px;
                height: 36px;
                font-size: 19px;
            }

            .nav-links a {
                padding: 7px;
            }

            .login-card h1 {
                font-size: 24px;
            }
        }

    </style>
</head>

<body>

    <!-- NAVBAR -->

    <nav class="navbar">

        <a href="index.php" class="nav-logo">

            <div class="nav-logo-icon">
                🐍
            </div>

            <div>
                <strong>PyGuard</strong>
                <span>Python Code Analyzer</span>
            </div>

        </a>


        <div class="nav-links">

            <a href="index.php">
                Home
            </a>

            <a href="analyzer.php">
                Python Analyzer
            </a>

            <a href="login.php" class="active">
                Login
            </a>

        </div>

    </nav>


    <!-- LOGIN -->

    <main class="login-container">

        <div class="login-card">

            <div class="login-icon">
                🔐
            </div>

            <h1>Welcome Back</h1>

            <p class="subtitle">
                Login to access your PyGuard account
            </p>


            <?php if (!empty($error)): ?>

                <div class="error-message">
                    <?php echo htmlspecialchars($error); ?>
                </div>

            <?php endif; ?>


            <form method="POST" action="login.php">

                <div class="form-group">

                    <label for="email">
                        Email Address
                    </label>

                    <input
                        type="email"
                        id="email"
                        name="email"
                        placeholder="Enter your email"
                        required
                    >

                </div>


                <div class="form-group">

                    <label for="password">
                        Password
                    </label>

                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Enter your password"
                        required
                    >

                </div>


                <button type="submit" class="login-btn">
                    Login to PyGuard
                </button>

            </form>


            <div class="register-text">

                Don't have an account?

                <a href="register.php">
                    Create Account
                </a>

            </div>


            <div class="back-home">

                <a href="index.php">
                    ← Back to Home
                </a>

            </div>

        </div>

    </main>


    <!-- FOOTER -->

    <footer>

        PyGuard • Python Static Code Analyzer
        <br>
        HTML • CSS • JavaScript • PHP • MySQL

    </footer>

</body>

</html>