<?php

session_start();

if (!isset($_SESSION['email'])) {
    header('location:login.php');
} else {
    $user_id = $_SESSION['id'];

    require_once('config.php');
    $exportDate = $_POST["exportDate"];

    $query = "SELECT * FROM attendancerecords WHERE date_time LIKE '%$exportDate%'";
    $result = $conn->query($query);

    $csvFile = fopen('attendance_export.csv', 'w');
    fputcsv($csvFile, array('Student ID, Student Name', 'Time Attendance', 'Total Attendance'));

    while ($row = $result->fetch_assoc()) {
        $queryStudents = "SELECT * FROM students WHERE student_id = '" . $row['student_id'] . "'";
        $resultStudents = $conn->query($queryStudents);
        $rowStudents = $resultStudents->fetch_assoc();
        fputcsv($csvFile, array($rowStudents['student_id'], $rowStudents['full_name'], $row['date_time'], $rowStudents['total_attendance']));
    }

    fclose($csvFile);

    header('Content-Type: application/csv');
    header('Content-Disposition: attachment; filename=attendance_export.csv');
    readfile('attendance_export.csv');
    // header("Location: export_attendance.php");
    // exit();
}
