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
    <style>

    </style>
</head>

<body>
<?php
require_once('panel.php');
?>


            <?php
            require_once('config.php');

            $query = "SELECT students.*, classes.class_name, studentsimages.image, studentsimages.imageType
            FROM students 
            LEFT JOIN classes ON students.class_id = classes.class_id
            LEFT JOIN studentsimages ON students.student_id = studentsimages.student_id";

            $result = $conn->query($query);


            // Affiche la liste des étudiants
            if ($result->num_rows > 0) {
            ?>
                <div class='flex items-start justify-center p-4 mb-32'>
                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg w-11/12">
                        <table class="w-full text-sm text-left rtl:text-right text-blue-100 dark:text-blue-100">
                            <thead class="text-xs text-white uppercase bg-blue-600 dark:text-white">
                                <tr>
                                    <th scope="col" class="px-6 py-3">
                                        Student picture
                                    </th>
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
                                // $getFile = "data:" . $row['imageType'] . ";base64," . base64_encode($row['image']);
                                $getFile = "data:" . $row['imageType'] . ";base64," . base64_encode($row['image']);
                                echo '<tr class="bg-gray-600 border-b border-blue-400">
                                <td class="px-6 py-4">
                                <div class="admin w-16 h-16 overflow-hidden rounded-full cursor-pointer">
                                <img src="' . $getFile . '" alt=" student picture" class="w-full h-full object-cover">
                                </div>
                                </td>
                                <th scope="row" class="px-6 py-4 font-medium text-blue-50 whitespace-nowrap dark:text-blue-100"> ' . $row['student_id'] . '</th>
                                <td class="px-6 py-4">' . $row['full_name'] . '</td>
                                <td class="px-6 py-4">' . $row['class_name'] . '</td>
                                <td class="px-6 py-4">' . $row['Major'] . '</td>
                                <td class="px-6 py-4">' . $row['total_attendance'] . '</td>
                                <td class="px-6 py-4">
                                    <a href="edit_student.php?id=' . $row['student_id'] . '" class="font-medium text-green-500 hover:underline" >Edit</a> | <a href="delete_student.php?id=' . $row['student_id'] . '" class="font-medium text-red-500 hover:underline">Delete</a>
                                </td>
                                </tr>';
                            }

                            echo "</tbody></table>";
                            echo "</div>";
                            echo "</div>";
                        } else {
                            echo "<p>No students found.</p>";
                        }
                            ?>


                            <script>
                                function handleBoxClick(boxType) {
                                    alert("Clicked on " + boxType);

                                }
                            </script>




</body>

</html>