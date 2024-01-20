<?php
$conn = new mysqli('localhost', 'root', '', 'managementofstudentabsences');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
