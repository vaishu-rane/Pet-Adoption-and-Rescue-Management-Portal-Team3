<?php
header("Content-Type: application/json");

// ----------------------------
// HANDLE CAMERA CAPTURE IMAGE
// ----------------------------
if (isset($_POST["captured_image"])) {

    $upload_dir = "uploads/";
    if (!is_dir($upload_dir)) mkdir($upload_dir);

    // Convert base64 → image file
    $data = $_POST["captured_image"];
    $data = str_replace("data:image/png;base64,", "", $data);
    $data = base64_decode($data);

    $file = $upload_dir . "captured_" . time() . ".png";
    file_put_contents($file, $data);

    // Run Python matching
    $cmd = "python model/match_pet.py " . escapeshellarg($file);
    $output = shell_exec($cmd);

    list($name, $breed, $db_image, $score) = explode("\n", trim($output));

    echo json_encode([
        "pet_name" => $name,
        "pet_breed" => $breed,
        "matched_image" => $db_image,
        "accuracy" => (float)$score
    ]);
    exit;
}

// ----------------------------
// HANDLE UPLOADED IMAGE FILE
// ----------------------------
if (!isset($_FILES["pet_image"])) {
    echo json_encode(["error" => "No image uploaded"]);
    exit;
}

$upload_dir = "uploads/";
if (!is_dir($upload_dir)) mkdir($upload_dir);

$img_name = time() . "_" . $_FILES["pet_image"]["name"];
$img_path = $upload_dir . $img_name;

move_uploaded_file($_FILES["pet_image"]["tmp_name"], $img_path);

// Run Python matching
$cmd = "python model/match_pet.py " . escapeshellarg($img_path);
$output = shell_exec($cmd);

list($name, $breed, $db_image, $score) = explode("\n", trim($output));

echo json_encode([
    "pet_name" => $name,
    "pet_breed" => $breed,
    "matched_image" => $db_image,
    "accuracy" => (float)$score
]);

?>
