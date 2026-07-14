<?php
require_once __DIR__ . '/config/db.php';

// Add location column to attendance table if not exists
$check = $conn->query("SHOW COLUMNS FROM attendance LIKE 'location'");
if ($check->num_rows === 0) {
    $conn->query("ALTER TABLE attendance ADD COLUMN location VARCHAR(255) DEFAULT NULL AFTER status");
    echo "Column 'location' added successfully.\n";
} else {
    echo "Column 'location' already exists.\n";
}

$conn->close();
?>
