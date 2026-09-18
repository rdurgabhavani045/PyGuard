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

    <title>PyGuard - Python Analyzer</title>

    <link rel="stylesheet" href="style.css">

</head>

<body>

    <div class="background-circle circle-one"></div>
    <div class="background-circle circle-two"></div>


    <div class="app-container">


        <!-- ================= NAVIGATION ================= -->

        <nav class="navbar">

            <div class="nav-logo">

                <div class="nav-logo-icon">
                    🐍
                </div>

                <div>

                    <strong>PyGuard</strong>

                    <span>
                        Code Analyzer
                    </span>

                </div>

            </div>


            <div class="nav-links">

                <a href="index.php">
                    Home
                </a>

                <a href="analyzer.php"
                   class="active">
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

                    <h1>
                        Python Static Code Analyzer
                    </h1>

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


        <main>


            <!-- ================= HERO ================= -->

            <section class="hero">

                <div class="hero-text">

                    <span class="badge">
                        STATIC ANALYSIS TOOL
                    </span>

                    <h2>
                        Write Better.
                        <span>Code Smarter.</span>
                    </h2>

                    <p>
                        Detect coding problems, security risks,
                        unused variables and style issues before
                        running your Python program.
                    </p>

                </div>

            </section>


            <!-- ================= WORKSPACE ================= -->

            <section class="workspace">


                <!-- CODE EDITOR -->

                <div class="editor-card">

                    <div class="card-header">

                        <div class="card-title">

                            <span class="python-icon">
                                🐍
                            </span>

                            <div>

                                <h3>
                                    Python Code
                                </h3>

                                <span id="fileName">
                                    Untitled.py
                                </span>

                            </div>

                        </div>


                        <div class="editor-actions">

                            <label for="fileInput"
                                   class="upload-btn">

                                📁 Upload

                            </label>


                            <input
                                type="file"
                                id="fileInput"
                                accept=".py"
                                hidden
                            >


                            <button
                                id="clearBtn"
                                class="clear-btn">

                                Clear

                            </button>

                        </div>

                    </div>


                    <!-- CODE INPUT -->

                    <div class="code-wrapper">

                        <div id="lineNumbers"
                             class="line-numbers">

                            1

                        </div>


                        <textarea
                            id="codeInput"
                            spellcheck="false"
                            placeholder="# Write or paste your Python code here...

def calculate_sum(a, b):
    result = a + b
    return result

print(calculate_sum(10, 20))"></textarea>

                    </div>


                    <!-- FOOTER -->

                    <div class="editor-footer">

                        <span id="lineCount">
                            Lines: 1
                        </span>

                        <span id="charCount">
                            Characters: 0
                        </span>

                        <span>
                            Python
                        </span>

                    </div>


                    <button
                        id="analyzeBtn"
    class="analyze-button"
    type="button">

    <span>🔍</span>

    Analyze Python Code
                    </button>

                </div>


                <!-- ================= INFO CARD ================= -->

                <div class="info-card">

                    <h3>
                        What We Check
                    </h3>


                    <div class="check-item">

                        <div class="check-icon error-icon">
                            ❌
                        </div>

                        <div>

                            <strong>
                                Syntax Errors
                            </strong>

                            <p>
                                Basic Python syntax problems
                            </p>

                        </div>

                    </div>


                    <div class="check-item">

                        <div class="check-icon warning-icon">
                            ⚠️
                        </div>

                        <div>

                            <strong>
                                Code Quality
                            </strong>

                            <p>
                                Unused variables and imports
                            </p>

                        </div>

                    </div>


                    <div class="check-item">

                        <div class="check-icon security-icon">
                            🔐
                        </div>

                        <div>

                            <strong>
                                Security
                            </strong>

                            <p>
                                Dangerous functions and patterns
                            </p>

                        </div>

                    </div>


                    <div class="check-item">

                        <div class="check-icon style-icon">
                            🎨
                        </div>

                        <div>

                            <strong>
                                Code Style
                            </strong>

                            <p>
                                Formatting and style problems
                            </p>

                        </div>

                    </div>

                </div>

            </section>


            <!-- ================= RESULTS ================= -->

            <section
                id="resultsSection"
                class="results-section hidden">


                <div class="results-header">

                    <div>

                        <span class="badge">
                            ANALYSIS COMPLETE
                        </span>

                        <h2>
                            Analysis Results
                        </h2>

                        <p id="resultFile">
                            Untitled.py
                        </p>

                    </div>


                    <div class="score-card">

                        <div class="score-circle">

                            <span id="score">
                                100
                            </span>

                        </div>

                        <div>

                            <span>
                                Code Quality
                            </span>

                            <strong id="qualityText">
                                Excellent
                            </strong>

                        </div>

                    </div>

                </div>


                <!-- STAT CARDS -->

                <div class="stats-grid">


                    <div class="stat-card">

                        <div class="stat-icon error-bg">
                            ❌
                        </div>

                        <div>

                            <span>
                                Errors
                            </span>

                            <strong id="errorCount">
                                0
                            </strong>

                        </div>

                    </div>


                    <div class="stat-card">

                        <div class="stat-icon warning-bg">
                            ⚠️
                        </div>

                        <div>

                            <span>
                                Warnings
                            </span>

                            <strong id="warningCount">
                                0
                            </strong>

                        </div>

                    </div>


                    <div class="stat-card">

                        <div class="stat-icon security-bg">
                            🔐
                        </div>

                        <div>

                            <span>
                                Security
                            </span>

                            <strong id="securityCount">
                                0
                            </strong>

                        </div>

                    </div>


                    <div class="stat-card">

                        <div class="stat-icon style-bg">
                            🎨
                        </div>

                        <div>

                            <span>
                                Style
                            </span>

                            <strong id="styleCount">
                                0
                            </strong>

                        </div>

                    </div>

                </div>


                <!-- RESULTS BODY -->

                <div class="results-grid">


                    <div class="issues-card">

                        <div class="section-title">

                            <div>

                                <h3>
                                    Issues Found
                                </h3>

                                <p>
                                    Problems detected in your code
                                </p>

                            </div>


                            <span
                                id="totalIssues"
                                class="issue-count">

                                0 Issues

                            </span>

                        </div>


                        <div
                            id="issuesList"
                            class="issues-list">

                        </div>

                    </div>


                    <div class="suggestions-card">

                        <div class="section-title">

                            <div>

                                <h3>
                                    💡 Suggestions
                                </h3>

                                <p>
                                    Improve your code
                                </p>

                            </div>

                        </div>


                        <div id="suggestionsList">

                            <div class="suggestion">

                                <span>
                                    ✓
                                </span>

                                <p>
                                    Keep your functions small
                                    and readable.
                                </p>

                            </div>


                            <div class="suggestion">

                                <span>
                                    ✓
                                </span>

                                <p>
                                    Use meaningful variable names.
                                </p>

                            </div>


                            <div class="suggestion">

                                <span>
                                    ✓
                                </span>

                                <p>
                                    Avoid unnecessary imports.
                                </p>

                            </div>

                        </div>

                    </div>

                </div>

            </section>

        </main>


        <!-- FOOTER -->

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


    <script src="script.js"></script>

</body>

</html>