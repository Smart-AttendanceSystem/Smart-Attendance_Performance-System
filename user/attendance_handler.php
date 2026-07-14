<?php
session_start();
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/company_location.php';

header('Content-Type: application/json');

$userId = (int) ($_SESSION['user_id'] ?? 0);
if ($userId <= 0) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$action = $_POST['action'] ?? '';

// Get user's IP address as location
function getUserLocation() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        return $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        return explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0];
    } else {
        return $_SERVER['REMOTE_ADDR'] ?? 'Unknown';
    }
}
$userLocation = getUserLocation();

if ($action === 'checkin') {
    $existing = $conn->query("SELECT id FROM attendance WHERE user_id = $userId AND date = CURDATE() LIMIT 1");
    if ($existing && $existing->num_rows > 0) {
        echo json_encode(['success' => false, 'message' => 'Already checked in today']);
        exit;
    }
    $stmt = $conn->prepare("INSERT INTO attendance (user_id, date, check_in, check_out, status, location) VALUES (?, CURDATE(), CURTIME(), '00:00:00', IF(CURTIME() > '09:15:00', 'Late', 'Present'), ?)");
    $stmt->bind_param("is", $userId, $userLocation);
    if ($stmt->execute()) {
        $ciRes = $conn->query("SELECT check_in, status FROM attendance WHERE user_id = $userId AND date = CURDATE() LIMIT 1");
        $ciRow = $ciRes->fetch_assoc();
        echo json_encode(['success' => true, 'message' => 'Check-in recorded', 'status' => $ciRow['status'], 'check_in' => $ciRow['check_in']]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Database error']);
    }
    $stmt->close();
} elseif ($action === 'checkout') {
    $existing = $conn->query("SELECT id FROM attendance WHERE user_id = $userId AND date = CURDATE() LIMIT 1");
    if (!$existing || $existing->num_rows === 0) {
        echo json_encode(['success' => false, 'message' => 'No check-in found for today']);
        exit;
    }
    $row = $existing->fetch_assoc();
    $attId = $row['id'];
    $stmt = $conn->prepare("UPDATE attendance SET check_out = CURTIME() WHERE id = ?");
    $stmt->bind_param("i", $attId);
    if ($stmt->execute()) {
        $coRes = $conn->query("SELECT check_out, TIMESTAMPDIFF(MINUTE, check_in, CURTIME()) AS mins_worked FROM attendance WHERE id = $attId");
        $coRow = $coRes->fetch_assoc();
        $checkOutTime = $coRow['check_out'];
        $overtimeMinutes = 0;
        if ($checkOutTime > '17:00:00') {
            $checkInSec = strtotime('17:00:00');
            $checkOutSec = strtotime($checkOutTime);
            $overtimeMinutes = (int)(($checkOutSec - $checkInSec) / 60);
            $overtimeHours = round($overtimeMinutes / 60, 2);
            $otStmt = $conn->prepare("INSERT INTO overtime (employee_id, ot_date, hours) VALUES (?, CURDATE(), ?)");
            $otStmt->bind_param("id", $userId, $overtimeHours);
            $otStmt->execute();
            $otStmt->close();
        }
        echo json_encode([
            'success' => true,
            'message' => 'Check-out recorded',
            'check_out' => $checkOutTime,
            'overtime_minutes' => $overtimeMinutes
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Database error']);
    }
    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid action']);
}
$conn->close();
