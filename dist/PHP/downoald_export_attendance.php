<?php
session_start();
    require_once('config.php');
        $exportDate = $_POST["exportDate"];

        $query = "SELECT * FROM attendancerecords WHERE date_time LIKE '%$exportDate%'";
        $result = $conn->query($query);

        $csvFile = fopen('attendance_export.csv', 'w');
        fputcsv($csvFile, array('Student Name', 'Time In', 'Time Out'));

        while ($row = $result->fetch_assoc()) {
            fputcsv($csvFile, array($row['student_id'], $row['date_time'], $row['present']));
        }

        fclose($csvFile);

        header('Content-Type: application/csv');
        header('Content-Disposition: attachment; filename=attendance_export.csv');
        readfile('attendance_export.csv');
        // header("Location: export_attendance.php");
        // exit();

    ?>