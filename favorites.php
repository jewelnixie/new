<?php
<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    die(json_encode(["success" => false, "message" => "User not logged in."]));
}

// Read data from JavaScript
$input = json_decode(file_get_contents('php://input'), true);
$destination_id = $input['destination_id'];
$action = $input['action'];
$user_id = $_SESSION['user_id']; // Ensure the user is logged in and has a valid session

if ($action === 'add') {
    // Add to favorites
    $stmt = $conn->prepare("INSERT INTO favorites (user_id, destination_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $user_id, $destination_id);
} else if ($action === 'remove') {
    // Remove from favorites
    $stmt = $conn->prepare("DELETE FROM favorites WHERE user_id = ? AND destination_id = ?");
    $stmt->bind_param("ii", $user_id, $destination_id);
} else {
    echo json_encode(["success" => false, "message" => "Invalid action."]);
    exit;
}

if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "message" => "Failed to update favorite."]);
}

$stmt->close();
$conn->close();
?>
