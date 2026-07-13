<?php
$conn = new mysqli('localhost','root','','smart_attendence');
if ($conn->connect_error) {
    die('DBERR '.$conn->connect_error);
}
$res = $conn->query("SELECT id, name, email, role, status, password FROM `user` ORDER BY id");
echo "rows=".$res->num_rows.PHP_EOL;
while ($row = $res->fetch_assoc()) {
    echo $row['id'].'|'.$row['name'].'|'.$row['email'].'|'.$row['role'].'|'.$row['status'].'|'.substr($row['password'],0,80).PHP_EOL;
}
