<?php

session_start();

header(
    "Content-Type: application/json"
);

require_once "db.php";


if (!isset($_SESSION["user_id"])) {

    echo json_encode([
        "success" => false,
        "message" => "Please login first."
    ]);

    exit;

}


$data =
    json_decode(
        file_get_contents("php://input"),
        true
    );


if (!$data) {

    echo json_encode([
        "success" => false,
        "message" => "Invalid data."
    ]);

    exit;

}


$userId =
    $_SESSION["user_id"];

$filename =
    $data["filename"] ?? "Untitled.py";

$score =
    intval($data["score"] ?? 0);

$errors =
    intval($data["errors"] ?? 0);

$warnings =
    intval($data["warnings"] ?? 0);

$security =
    intval($data["security"] ?? 0);

$style =
    intval($data["style"] ?? 0);


$stmt = $conn->prepare(
    "INSERT INTO analysis_history
    (
        user_id,
        filename,
        score,
        errors,
        warnings,
        security_issues,
        style_issues
    )
    VALUES (?, ?, ?, ?, ?, ?, ?)"
);


$stmt->bind_param(
    "isiiiii",
    $userId,
    $filename,
    $score,
    $errors,
    $warnings,
    $security,
    $style
);


if ($stmt->execute()) {

    echo json_encode([
        "success" => true,
        "message" => "Analysis saved successfully."
    ]);

} else {

    echo json_encode([
        "success" => false,
        "message" => "Unable to save analysis."
    ]);

}

$stmt->close();

$conn->close();

?>