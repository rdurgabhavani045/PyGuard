<?php

session_start();

require_once "db.php";


if (!isset($_SESSION["user_id"])) {

    header(
        "Location: index.php#login"
    );

    exit;

}


$userId =
    $_SESSION["user_id"];


$stmt = $conn->prepare(
    "SELECT
        filename,
        score,
        errors,
        warnings,
        security_issues,
        style_issues,
        created_at
     FROM analysis_history
     WHERE user_id = ?
     ORDER BY created_at DESC"
);


$stmt->bind_param(
    "i",
    $userId
);

$stmt->execute();

$result =
    $stmt->get_result();

?>
<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Analysis History - PyGuard</title>

    <link
        rel="stylesheet"
        href="style.css"
    >

</head>

<body>


<header class="navbar">

    <div class="nav-container">

        <a
            href="index.php"
            class="logo"
        >

            <span class="logo-icon">
                🐍
            </span>

            <span>
                PyGuard
            </span>

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
                href="history.php"
                class="nav-link active"
            >
                History
            </a>

            <a
                href="logout.php"
                class="nav-link"
            >
                Logout
            </a>

        </nav>

    </div>

</header>


<section class="history-section">

    <div class="section-heading">

        <div class="section-label">
            ANALYSIS HISTORY
        </div>

        <h2>
            Your Previous Analyses
        </h2>

        <p>
            Welcome,
            <?php
            echo htmlspecialchars(
                $_SESSION["user_name"]
            );
            ?>
        </p>

    </div>


    <div class="history-card">

        <?php if ($result->num_rows > 0): ?>

            <div class="table-wrapper">

                <table>

                    <thead>

                        <tr>

                            <th>
                                File
                            </th>

                            <th>
                                Score
                            </th>

                            <th>
                                Errors
                            </th>

                            <th>
                                Warnings
                            </th>

                            <th>
                                Security
                            </th>

                            <th>
                                Style
                            </th>

                            <th>
                                Date
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        <?php while (
                            $row =
                                $result->fetch_assoc()
                        ): ?>

                            <tr>

                                <td>
                                    <?php
                                    echo htmlspecialchars(
                                        $row["filename"]
                                    );
                                    ?>
                                </td>

                                <td>
                                    <strong>
                                        <?php
                                        echo $row["score"];
                                        ?>
                                    </strong>
                                </td>

                                <td>
                                    <?php
                                    echo $row["errors"];
                                    ?>
                                </td>

                                <td>
                                    <?php
                                    echo $row["warnings"];
                                    ?>
                                </td>

                                <td>
                                    <?php
                                    echo $row["security_issues"];
                                    ?>
                                </td>

                                <td>
                                    <?php
                                    echo $row["style_issues"];
                                    ?>
                                </td>

                                <td>
                                    <?php
                                    echo $row["created_at"];
                                    ?>
                                </td>

                            </tr>

                        <?php endwhile; ?>

                    </tbody>

                </table>

            </div>

        <?php else: ?>

            <div class="empty-state">

                <div>
                    📊
                </div>

                <p>
                    You don't have any analysis history yet.
                </p>

                <a
                    href="index.php#analyzer"
                    class="hero-button"
                >
                    Analyze Code
                </a>

            </div>

        <?php endif; ?>

    </div>

</section>


</body>

</html>

<?php

$stmt->close();

$conn->close();

?>