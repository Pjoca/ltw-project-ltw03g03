<?php
declare(strict_types=1);
session_start();

require_once(__DIR__ . '/../database/connection.db.php');

// Set proper content type
header('Content-Type: application/json');

// Verify user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'Not logged in']);
    exit();
}

// Verify POST data
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Method not allowed']);
    exit();
}

// Get JSON input
$input = json_decode(file_get_contents('php://input'), true);
if (json_last_error() !== JSON_ERROR_NONE) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Invalid JSON']);
    exit();
}

// Validate required fields
if (!isset($input['service_id']) || !isset($input['freelancer_id'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Missing required fields']);
    exit();
}

$db = getDatabaseConnection();

try {
    // Verify service exists
    $stmt = $db->prepare('SELECT * FROM Services WHERE id = ?');
    $stmt->execute([$input['service_id']]);
    $service = $stmt->fetch();

    if (!$service) {
        http_response_code(404);
        echo json_encode(['success' => false, 'error' => 'Service not found']);
        exit();
    }

    // Create transaction
    $stmt = $db->prepare('
        INSERT INTO Transactions 
        (client_id, freelancer_id, service_id, status, created_at)
        VALUES (?, ?, ?, "pending", CURRENT_TIMESTAMP)
    ');
    
    $success = $stmt->execute([
        $_SESSION['user_id'],
        $input['freelancer_id'],
        $input['service_id']
    ]);

    if ($success) {
        echo json_encode([
            'success' => true,
            'transaction_id' => $db->lastInsertId()
        ]);
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'error' => 'Database operation failed']);
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Database error: ' . $e->getMessage()]);
}
?>
