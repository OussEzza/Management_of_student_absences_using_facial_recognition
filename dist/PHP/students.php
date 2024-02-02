<?php
session_start();

if (!isset($_SESSION['email'])) {
    header('location:login.php');
} else {
    $user_id = $_SESSION['id'];
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

        $query = "SELECT * FROM students";

        $result = $conn->query($query);


        // Affiche la liste des étudiants
        if ($result->num_rows > 0) {
        ?>
            <section id="ManageStudent">

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
                                // Fetch student information
                                $id = $row["student_id"];
                                $queryImage = "SELECT * FROM studentsimages WHERE student_id = '$id'";
                                $resultImage = $conn->query($queryImage);
                                if ($resultImage->num_rows > 0) {
                                    $rowImage = $resultImage->fetch_assoc();

                                    // Get image data and create a data URI
                                    $getFile = "data:" . $rowImage['imageType'] . ";base64," . base64_encode($rowImage['image']);

                                    // Display student information in a table row
                                    echo '<tr class="bg-gray-600 border-b border-blue-400">
                                    <td class="px-6 py-4">
                                        <div class="admin w-16 h-16 overflow-hidden rounded-full cursor-pointer">
                                            <img src="' . $getFile . '" alt=" student picture" class="w-full h-full object-cover">
                                        </div>
                                    </td>
                                    <th scope="row" class="px-6 py-4 font-medium text-blue-50 whitespace-nowrap dark:text-blue-100"> ' . $row['student_id'] . '</th>
                                    <td class="px-6 py-4">' . $row['full_name'] . '</td>
                                    <td class="px-6 py-4">' . $row['Major'] . '</td>
                                    <td class="px-6 py-4">' . $row['total_attendance'] . '</td>
                                    <td class="px-6 py-4">
                                        <a href="edit_student.php?id=' . $row['student_id'] . '" class="font-medium text-green-500 hover:underline">Edit</a> | 
                                        <a href="delete_student.php?id=' . $row['student_id'] . '" class="font-medium text-red-500 hover:underline">Delete</a>
                                    </td>
                                    </tr>';
                                }
                            }


                            echo "</tbody></table>";
                            echo "</div>";
                            echo "</div>";
                        } else {
                            echo "<p>No students found.</p>";
                        }
                            ?>
            </section>




            <section id="AddStudent" class="mb-5">

                <!-- Formulaire pour ajouter un étudiant -->
                <h1 class="text-center text-5xl font-bold text-indigo-600 mb-8 mt-8">Add Student</h1>
                <form action="" method="post" enctype="multipart/form-data" class="max-w-md mx-auto bg-white p-6 rounded-md shadow-md">
                    <label for="student_id" class="block text-sm font-medium text-gray-600">ID de l'étudiant :</label>
                    <input type="text" name="student_id" id="student_id" required class="mt-1 p-2 border rounded-md w-full focus:outline-none focus:border-indigo-500">

                    <label for="full_name" class="block mt-4 text-sm font-medium text-gray-600">Nom complet :</label>
                    <input type="text" name="full_name" id="full_name" required class="mt-1 p-2 border rounded-md w-full focus:outline-none focus:border-indigo-500">

                    <label for="major" class="block mt-4 text-sm font-medium text-gray-600">Spécialité :</label>
                    <input type="text" name="major" id="major" required class="mt-1 p-2 border rounded-md w-full focus:outline-none focus:border-indigo-500">

                    <!-- <label for="classe_name" class="block mt-4 text-sm font-medium text-gray-600">Nom de la classe :</label>
                <input type="text" name="classe_name" id="classe_name" required class="mt-1 p-2 border rounded-md w-full focus:outline-none focus:border-indigo-500"> -->

                    <label for="student_photo" class="block mt-4 text-sm font-medium text-gray-600">Choisir une photo :</label>
                    <input type="file" name="student_photo" id="student_photo" required class="mt-1 p-2 border rounded-md w-full focus:outline-none focus:border-indigo-500">

                    <input type="submit" name="add_student" value="Ajouter l'étudiant" class="mt-4 px-4 py-2 bg-indigo-500 text-white rounded-md cursor-pointer hover:bg-indigo-600 focus:outline-none focus:bg-indigo-600">
                </form>



                <?php
                $host = "localhost";
                $dbname = "managementofstudentabsences";
                $user = "root";
                $password = "";

                try {
                    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
                    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                } catch (PDOException $e) {
                    die("Erreur de connexion à la base de données : " . $e->getMessage());
                }

                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    if (isset($_POST["add_student"])) {
                        $student_id = $_POST["student_id"];
                        $full_name = $_POST["full_name"];
                        $major = $_POST["major"];
                        // $class_id = 1;

                        if (isset($_FILES["student_photo"]) && $_FILES["student_photo"]["error"] == 0) {
                            $photo = $_FILES["student_photo"];


                            $allowedTypes = ["image/jpeg", "image/png", "image/gif"];
                            if (in_array($photo["type"], $allowedTypes)) {
                                $imageData = file_get_contents($photo["tmp_name"]);
                                $imageType = $photo["type"];

                                $sqlImage = "INSERT INTO studentsimages (student_id, image, imageType) VALUES (:student_id, :photo_blob, :imageType)";
                                $stmtImage = $pdo->prepare($sqlImage);
                                $stmtImage->bindParam(":student_id", $student_id);
                                $stmtImage->bindParam(":photo_blob", $imageData, PDO::PARAM_LOB);
                                $stmtImage->bindParam(":imageType", $imageType);
                                $stmtImage->execute();

                                $sqlStudent = "INSERT INTO students (student_id, full_name, major) VALUES (:student_id, :full_name, :major)";
                                $stmtStudent = $pdo->prepare($sqlStudent);
                                $stmtStudent->bindParam(":student_id", $student_id);
                                $stmtStudent->bindParam(":full_name", $full_name);
                                $stmtStudent->bindParam(":major", $major);
                                $stmtStudent->execute();

                                // Rename and move the uploaded file
                                $originalFileName = $_FILES["student_photo"]["name"];
                                $extension = pathinfo($originalFileName, PATHINFO_EXTENSION);

                                // Generate the filename using the student ID
                                $newFileName = $student_id . "." . $extension;

                                // Set the destination path for the uploaded file
                                $destinationPath = "../../images/" . $newFileName;

                                move_uploaded_file($_FILES["student_photo"]["tmp_name"], $destinationPath);

                                // $pythonExecutablePath = 'C:\\Path\\To\\Python\\python.exe';
                                $pythonExecutablePath = 'C:\\ProgramData\\anaconda3\\envs\\ManagementOfStudentAbsencesUsingFacialRecognition\\python.exe';
                                $pythonScriptPath = 'C:\\xampp1\\htdocs\\Management_of_student_absences_using_facial_recognition\\dist\\PHP\\test.py';

                                // Use escapeshellarg to escape the script path
                                $escapedScriptPath = escapeshellarg($pythonScriptPath);

                                // Use the full path to the Python interpreter and the escaped script path
                                $command = "{$pythonExecutablePath} {$escapedScriptPath} 2>&1";

                                // Execute the command and capture both output and return code
                                $output = shell_exec($command);
                                $returnCode = 0;  // Default return code is 0 for success

                                // If exec is used, $? will contain the return value of the executed command
                                // Note: This will only work if you use exec, not shell_exec
                                exec($command, $outputArray, $returnCode);

                                // Output the results
                                echo 'output: ' . $output . '<br>';
                                echo 'return code: ' . $returnCode . '<br>';


                                echo "<script>
                                        showMessage('L\'étudiant a été ajouté avec succès.', 'success');
                                        </script>";
                            } else {
                                echo "<script>showMessage('Seules les images au format JPEG, PNG et GIF sont autorisées.', 'error');</script>";
                            }
                        } else {
                            echo "<script>showMessage('Veuillez sélectionner une image à télécharger.', 'error');</script>";
                        }
                    }
                }
                ?>

            </section>


            <script>
                function handleBoxClick(boxType) {
                    alert("Clicked on " + boxType);

                }

                function showMessage(message, type) {
                    var messageDiv = document.getElementById('message');
                    messageDiv.innerHTML = message;
                    messageDiv.style.display = 'block';

                    if (type === 'success') {
                        messageDiv.classList.add('success');
                    } else if (type === 'error') {
                        messageDiv.classList.add('error');
                    }
                }

                function hideMessage() {
                    var messageDiv = document.getElementById('message');
                    messageDiv.style.display = 'none';
                }
            </script>


            <script src="../JS/main.js"></script>

    </body>
    </html>
<?php
}
?>