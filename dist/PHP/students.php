<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../output.css">
    <link rel="stylesheet" href="../CSS/style.css">
    <title>Students</title>
</head>

<body>
    <div class="main-container flex h-screen">

        <div class="navigation bg-gray-800 text-white w-64 pl-4 pt-4 pb-4 text-xl">
            <ul>
                <li class="mb-2">
                    <a href="#" class="flex space-x-2 mb-8">
                        <span class="icon">
                            <ion-icon name="logo-microsoft"></ion-icon>
                        </span>
                        <span class="title">OS Brand</span>
                    </a>
                </li>


                <li class="mb-2 item">
                    <a href="#" class="">
                        <span class="icon">
                            <ion-icon name="analytics-outline"></ion-icon>
                        </span>
                        <span class="title">Dashboard</span>
                    </a>
                </li>



                <li class="mb-2 item">
                    <a href="students.php" class="">
                        <span class="icon">
                            <ion-icon name="school-outline"></ion-icon>
                        </span>
                        <span class="title">Students</span>
                    </a>
                </li>


                <li class="mb-2 item">
                    <a href="#" class="">
                        <span class="icon">
                            <ion-icon name="alert-outline"></ion-icon>
                        </span>
                        <span class="title">Attendance</span>
                    </a>
                </li>

                <li class="mb-2 item">
                    <a href="#" class="">
                        <span class="icon">
                            <ion-icon name="people-outline"></ion-icon>
                        </span>
                        <span class="title">Admins</span>
                    </a>
                </li>

                <li class="mb-2 item">
                    <a href="#" class="">
                        <span class="icon">
                            <ion-icon name="download-outline"></ion-icon>
                        </span>
                        <span class="title">Export</span>
                    </a>
                </li>

                <li class="mb-2 item">
                    <a href="#" class="">
                        <span class="icon">
                            <ion-icon name="log-out-outline"></ion-icon>
                        </span>
                        <span class="title">Logout</span>
                    </a>
                </li>

            </ul>
        </div>

        <div class="main flex-1 flex flex-col overflow-hidden transition-all duration-500 bg-white">
            <div class="topbar w-full h-16 flex items-center justify-between bg-white p-4">
                <div class="toggle text-gray-800 cursor-pointer">
                    <ion-icon name="menu-outline" class="text-3xl"></ion-icon>
                </div>

                <div class="admin w-14 h-14 overflow-hidden rounded-full cursor-pointer">
                    <img src="../pictures/student5651.jpg" alt="profile picture" class="w-full h-full object-cover">
                </div>
            </div>


            <?php
            require_once('config.php');

            $query = "SELECT students.*, classes.class_name 
            FROM students 
            LEFT JOIN classes ON students.class_id = classes.class_id";

            $result = $conn->query($query);


            // Affiche la liste des étudiants
            if ($result->num_rows > 0) {
            ?>
                <div class='flex items-start justify-center p-4'>
                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg w-4/5">
                        <table class="w-full text-sm text-left rtl:text-right text-blue-100 dark:text-blue-100">
                            <thead class="text-xs text-white uppercase bg-blue-600 dark:text-white">
                                <tr>
                                    <th scope="col" class="px-6 py-3">
                                        Student id
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Student name
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Student class
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Department
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Total attendance
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Action
                                    </th>
                                </tr>
                            </thead>
                            <tbody>

                            <?php

                            while ($row = $result->fetch_assoc()) {
                                echo '<tr class="bg-gray-600 border-b border-blue-400">
                                <th scope="row" class="px-6 py-4 font-medium text-blue-50 whitespace-nowrap dark:text-blue-100"> ' . $row['student_id'] . '</th>
                                <td class="px-6 py-4">' . $row['full_name'] . '</td>
                                <td class="px-6 py-4">' . $row['class_name'] . '</td>
                                <td class="px-6 py-4">' . $row['Major'] . '</td>
                                <td class="px-6 py-4">' . $row['total_attendance'] . '</td>
                                <td class="px-6 py-4">
                                    <a href="edit_student.php?id=' . $row['student_id'] . '" class="font-medium text-white hover:underline" >Edit</a> | <a href="delete_student.php?id=' . $row['student_id'] . '" class="font-medium text-red-500 hover:underline">Delete</a>
                                </td>
                                </tr>';
                            }

                            echo "</tbody></table>";
                            echo "</div>";
                            echo "</div>";
                        } else {
                            echo "<p>No students found.</p>";
                        }
                        $conn->close();
                            ?>
                    </div>
                </div>
                <script>
                    function handleBoxClick(boxType) {
                        alert("Clicked on " + boxType);

                    }
                </script>



                <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
                <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

                <script src="../JS/main.js"></script>

</body>

</html>