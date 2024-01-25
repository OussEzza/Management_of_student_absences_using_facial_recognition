<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../output.css">
    <title>Add Student</title>
    <style>
        .message {
            text-align: center;
            display: none;
            margin: 0;
            padding: 10px;
            border: 1px solid #ccc;
            background-color: #f2f2f2;
            color: #333;
            position: relative;
        }

        .message button {
            position: absolute;
            top: 5px;
            right: 5px;
            background-color: #ccc;
            border: none;
            color: red;
            cursor: pointer;
        }

        .message.success {
            background-color: #d4edda;
            border-color: #c3e6cb;
            color: #155724;
        }

        .message.error {
            background-color: #f8d7da;
            border-color: #f5c6cb;
            color: #721c24;
        }
    </style>
</head>

<body>
    <div id="message" class="message">
        <!-- Le message sera affiché ici -->
        <button onclick="hideMessage()">X</button>
    </div>
    <!-- Formulaire pour ajouter un étudiant -->
    <form action="" method="post" enctype="multipart/form-data" class="max-w-md mx-auto bg-white p-6 rounded-md shadow-md">
        <label for="student_id" class="block text-sm font-medium text-gray-600">ID de l'étudiant :</label>
        <input type="text" name="student_id" id="student_id" required class="mt-1 p-2 border rounded-md w-full focus:outline-none focus:border-indigo-500">

        <label for="full_name" class="block mt-4 text-sm font-medium text-gray-600">Nom complet :</label>
        <input type="text" name="full_name" id="full_name" required class="mt-1 p-2 border rounded-md w-full focus:outline-none focus:border-indigo-500">

        <label for="major" class="block mt-4 text-sm font-medium text-gray-600">Spécialité :</label>
        <input type="text" name="major" id="major" required class="mt-1 p-2 border rounded-md w-full focus:outline-none focus:border-indigo-500">

        <label for="classe_name" class="block mt-4 text-sm font-medium text-gray-600">Nom de la classe :</label>
        <input type="text" name="classe_name" id="classe_name" required class="mt-1 p-2 border rounded-md w-full focus:outline-none focus:border-indigo-500">

        <label for="student_photo" class="block mt-4 text-sm font-medium text-gray-600">Choisir une photo :</label>
        <input type="file" name="student_photo" id="student_photo" required class="mt-1 p-2 border rounded-md w-full focus:outline-none focus:border-indigo-500">

        <input type="submit" name="add_student" value="Ajouter l'étudiant" class="mt-4 px-4 py-2 bg-indigo-500 text-white rounded-md hover:bg-indigo-600 focus:outline-none focus:bg-indigo-600">
    </form>



    <script>
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
            $class_id = 1;

            if (isset($_FILES["student_photo"]) && $_FILES["student_photo"]["error"] == 0) {
                $photo = $_FILES["student_photo"];

                $allowedTypes = ["image/jpeg", "image/png", "image/gif"];
                if (in_array($photo["type"], $allowedTypes)) {
                    $imageData = file_get_contents($photo["tmp_name"]);

                    $sqlImage = "INSERT INTO studentsimages (student_id, image) VALUES (:student_id, :photo_blob)";
                    $stmtImage = $pdo->prepare($sqlImage);
                    $stmtImage->bindParam(":student_id", $student_id);
                    $stmtImage->bindParam(":photo_blob", $imageData, PDO::PARAM_LOB);
                    $stmtImage->execute();

                    $sqlStudent = "INSERT INTO students (student_id, full_name, major, class_id) VALUES (:student_id, :full_name, :major, :class_id)";
                    $stmtStudent = $pdo->prepare($sqlStudent);
                    $stmtStudent->bindParam(":student_id", $student_id);
                    $stmtStudent->bindParam(":full_name", $full_name);
                    $stmtStudent->bindParam(":major", $major);
                    $stmtStudent->bindParam(":class_id", $class_id);
                    $stmtStudent->execute();

                    echo "<script>showMessage('L\'étudiant a été ajouté avec succès.', 'success');</script>";
                } else {
                    echo "<script>showMessage('Seules les images au format JPEG, PNG et GIF sont autorisées.', 'error');</script>";
                }
            } else {
                echo "<script>showMessage('Veuillez sélectionner une image à télécharger.', 'error');</script>";
            }
        }
    }
    ?>
</body>

</html>