<?php

require_once "db.php";

$message = "";
$messageType = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    if (
        empty($name) ||
        empty($email) ||
        empty($password)
    ) {

        $message = "Please fill all fields.";
        $messageType = "error";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $message = "Please enter a valid email.";
        $messageType = "error";

    } elseif (strlen($password) < 6) {

        $message =
            "Password must contain at least 6 characters.";

        $messageType = "error";

    } else {

        $check = $conn->prepare(
            "SELECT id FROM users WHERE email = ?"
        );

        $check->bind_param(
            "s",
            $email
        );

        $check->execute();

        $result = $check->get_result();

        if ($result->num_rows > 0) {

            $message =
                "An account with this email already exists.";

            $messageType = "error";

        } else {

            $hashedPassword =
                password_hash(
                    $password,
                    PASSWORD_DEFAULT
                );

            $stmt = $conn->prepare(
                "INSERT INTO users
                (name, email, password)
                VALUES (?, ?, ?)"
            );

            $stmt->bind_param(
                "sss",
                $name,
                $email,
                $hashedPassword
            );

            if ($stmt->execute()) {

                header(
                    "Location: index.php?registered=1#login"
                );

                exit;

            } else {

                $message =
                    "Registration failed. Please try again.";

                $messageType = "error";

            }

            $stmt->close();

        }

        $check->close();

    }

}

?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Register - PyGuard</title>

    <link
        rel="stylesheet"
        href="style.css"
    >

</head>

<body>
<style>
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
</style>
    <header class="navbar">

        <div class="nav-container">

            <a href="index.php" class="logo">

                <span class="logo-icon">🐍</span>

                <span>PyGuard</span>

            </a>

            <nav class="nav-menu">

                <a
                    href="index.php#home"
                    class="nav-link"
                >
                    Home
                </a>

                <a
                    href="index.php#analyzer"
                    class="nav-link"
                >
                    Python Analyzer
                </a>

                <a
                    href="index.php#login"
                    class="nav-link"
                >
                    Login
                </a>

            </nav>

        </div>

    </header>


    <section class="auth-section">

        <div class="auth-card">

            <div class="login-icon">
                📝
            </div>

            <h2>
                Create Account
            </h2>

            <p>
                Join PyGuard today
            </p>


            <?php if ($message): ?>

                <div
                    class="auth-message <?php echo $messageType; ?>"
                >

                    <?php echo htmlspecialchars($message); ?>

                </div>

            <?php endif; ?>


            <form
                method="POST"
                action="register.php"
            >

                <div class="input-group">

                    <label>
                        Full Name
                    </label>

                    <input
                        type="text"
                        name="name"
                        placeholder="Enter your name"
                        required
                    >

                </div>


                <div class="input-group">

                    <label>
                        Email
                    </label>

                    <input
                        type="email"
                        name="email"
                        placeholder="Enter your email"
                        required
                    >

                </div>


                <div class="input-group">

                    <label>
                        Password
                    </label>

                    <input
                        type="password"
                        name="password"
                        placeholder="Minimum 6 characters"
                        required
                    >

                </div>


                <button
                    type="submit"
                    class="login-button"
                >
                    Create Account
                </button>

            </form>


            <p class="auth-bottom">

                Already have an account?

                <a href="index.php#login">
                    Login
                </a>

            </p>

        </div>

    </section>

</body>

</html>