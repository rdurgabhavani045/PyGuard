<?php
session_start();

$isLoggedIn = isset($_SESSION["user_id"]);
$userName = $_SESSION["user_name"] ?? "";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>PyGuard - Python Static Code Analyzer</title>

    <link rel="stylesheet" href="style.css">
</head>

<body>

    <!-- Background decoration -->
    <div class="background-circle circle-one"></div>
    <div class="background-circle circle-two"></div>


    <div class="app-container">

        <!-- ================= NAVIGATION ================= -->

        <nav class="navbar">

            <div class="nav-logo">
                <div class="nav-logo-icon">🐍</div>

                <div>
                    <strong>PyGuard</strong>
                    <span>Code Analyzer</span>
                </div>
            </div>


            <div class="nav-links">

                <a href="index.php" class="active">
                    Home
                </a>

                <a href="analyzer.php">
                    Python Analyzer
                </a>

                <?php if ($isLoggedIn): ?>

                    <a href="history.php">
                        History
                    </a>

                    <a href="logout.php">
                        Logout
                    </a>

                <?php else: ?>

                    <a href="login.php">
                        Login
                    </a>

                <?php endif; ?>

            </div>


            <div class="nav-status">

                <span class="status-dot"></span>

                <?php
                echo $isLoggedIn
                    ? htmlspecialchars($userName)
                    : "System Ready";
                ?>

            </div>

        </nav>


        <!-- ================= HEADER ================= -->

        <header class="header">

            <div class="logo-area">

                <div class="logo">
                    🐍
                </div>

                <div>

                    <h1>Python Static Code Analyzer</h1>

                    <p>
                        Analyze your Python code without executing it
                    </p>

                </div>

            </div>


            <div class="status">

                <span class="status-dot"></span>

                Analyzer Ready

            </div>

        </header>


        <!-- ================= HOME CONTENT ================= -->

        <main>

            <section class="home-hero">

                <span class="badge">
                    PYTHON CODE QUALITY TOOL
                </span>


                <h2>
                    Write Better.
                    <span>Code Smarter.</span>
                </h2>


                <p>
                    PyGuard is a Python static code analysis tool
                    that helps developers detect coding problems,
                    security risks, unused variables and style
                    issues before running their program.
                </p>


                <div class="home-buttons">

                    <a href="analyzer.php"
                       class="primary-home-btn">

                        🔍 Start Analyzing

                    </a>


                    <?php if (!$isLoggedIn): ?>

                        <a href="login.php"
                           class="secondary-home-btn">

                            Login

                        </a>

                    <?php else: ?>

                        <a href="history.php"
                           class="secondary-home-btn">

                            View History

                        </a>

                    <?php endif; ?>

                </div>

            </section>


            <!-- ================= FEATURES ================= -->

            <section class="home-features">

                <div class="feature-card">

                    <div class="feature-icon">
                        ❌
                    </div>

                    <h3>
                        Syntax Errors
                    </h3>

                    <p>
                        Detect basic Python syntax problems
                        before executing your program.
                    </p>

                </div>


                <div class="feature-card">

                    <div class="feature-icon">
                        ⚠️
                    </div>

                    <h3>
                        Code Quality
                    </h3>

                    <p>
                        Find unused variables, imports and
                        potential code quality problems.
                    </p>

                </div>


                <div class="feature-card">

                    <div class="feature-icon">
                        🔐
                    </div>

                    <h3>
                        Security
                    </h3>

                    <p>
                        Identify dangerous functions and
                        common security risks.
                    </p>

                </div>


                <div class="feature-card">

                    <div class="feature-icon">
                        🎨
                    </div>

                    <h3>
                        Code Style
                    </h3>

                    <p>
                        Check formatting and common Python
                        coding style issues.
                    </p>

                </div>

            </section>


            <!-- ================= HOW IT WORKS ================= -->

            <section class="how-section">

                <span class="badge">
                    HOW IT WORKS
                </span>

                <h2>
                    Analyze Your Code in
                    <span>Three Steps</span>
                </h2>


                <div class="steps-grid">

                    <div class="step-card">

                        <div class="step-number">
                            01
                        </div>

                        <h3>
                            Add Python Code
                        </h3>

                        <p>
                            Write your code directly in the
                            PyGuard editor or upload a Python file.
                        </p>

                    </div>


                    <div class="step-card">

                        <div class="step-number">
                            02
                        </div>

                        <h3>
                            Analyze
                        </h3>

                        <p>
                            PyGuard checks your code for syntax,
                            quality, security and style issues.
                        </p>

                    </div>


                    <div class="step-card">

                        <div class="step-number">
                            03
                        </div>

                        <h3>
                            Improve
                        </h3>

                        <p>
                            View the detected issues and use
                            suggestions to improve your Python code.
                        </p>

                    </div>

                </div>

            </section>

        </main>


        <!-- ================= FOOTER ================= -->

        <footer>

            <p>
                PyGuard
                <span>•</span>
                Python Static Code Analyzer
            </p>

            <p>
                HTML • CSS • JavaScript • PHP • MySQL
            </p>

        </footer>

    </div>

</body>

</html>