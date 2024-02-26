<?php
session_start();

if (!isset($_SESSION['email'])) {
    header('location:login.php');
} else {
    $user_id = $_SESSION['id'];



    require_once('config.php');

    if (isset($_GET['id'])) {
        $studentId = $_GET['id'];

        $query = "SELECT * 
    FROM students 
    WHERE student_id = $studentId";

        $result = $conn->query($query);


        if ($result->num_rows > 0) {
            $student = $result->fetch_assoc();
        } else {
            echo "Student not found.";
            exit();
        }
    } else {
        echo "Invalid request.";
        exit();
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $required_permission = 2;
        $permission = $_SESSION['permission'];
        if ($permission < $required_permission) {
            echo "<script>
                    showMessage('You don't have the permission for this !', 'error');
                </script>";
        } else {
            $newId = $_POST['newId'];
            $newName = $_POST["newName"];
            $newDepartment = $_POST["newDepartment"];


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

            $photo = $_FILES["picture_profile"];

            $allowedTypes = ["image/jpeg", "image/png", "image/gif"];

            if (in_array($photo["type"], $allowedTypes)) {
                $imageData = file_get_contents($photo["tmp_name"]);
                $imageType = $photo["type"];

                // $updateStudentQuery = "UPDATE students SET student_id = $newId, full_name = '$newName', Major = '$newDepartment' WHERE student_id = $studentId";

                $updateStudentQuery = "UPDATE students SET student_id = :newId, full_name = :newName, Major = :newDepartment WHERE student_id='$studentId'";

                    $stmtImage = $pdo->prepare($updateStudentQuery);
                    $stmtImage->bindParam(":newId", $newId);
                    $stmtImage->bindParam(":newName", $newName);
                    $stmtImage->bindParam(":newDepartment", $newDepartment);
                    $stmtImage->execute();

                $updateStudentImgQuery = "UPDATE studentsimages SET image = :profile_picture, imageType = :TypeImage WHERE student_id='$studentId'";

                    $stmtImage = $pdo->prepare($updateStudentImgQuery);
                    $stmtImage->bindParam(":profile_picture", $imageData, PDO::PARAM_LOB);
                    $stmtImage->bindParam(":TypeImage", $imageType);
                    $stmtImage->execute();

                header("Location: students.php");
                exit();
            }else{
                echo "<script>
                showMessage('Error updating students details.', 'error');
                </script>";
            }
        }
    }
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../output.css">
        <title>Edit student</title>
    </head>

    <body>

        <?php require_once('panel.php'); ?>

        <div class="flex items-start justify-center p-4">
            <div class="relative overflow-x-auto shadow-md sm:rounded-lg w-3/5">
                <form method="post" action="<?php echo $_SERVER['PHP_SELF'] . "?id=" . $studentId; ?>" class="mt-4 p-5" enctype="multipart/form-data">
                    <div class="mb-4">
                        <label for="newId" class="block text-gray-700 text-sm font-bold mb-2">New student id:</label>
                        <input type="text" name="newId" value="<?php echo $student['student_id']; ?>" required class="w-full border rounded py-2 px-3 focus:outline-none focus:border-blue-500">
                    </div>
                    <div class="mb-4">
                        <label for="newName" class="block text-gray-700 text-sm font-bold mb-2">New Name:</label>
                        <input type="text" name="newName" value="<?php echo $student['full_name']; ?>" required class="w-full border rounded py-2 px-3 focus:outline-none focus:border-blue-500">
                    </div>
                    <div class="mb-4">
                        <label for="newDepartment" class="block text-gray-700 text-sm font-bold mb-2">New Department:</label>
                        <input type="text" name="newDepartment" value="<?php echo $student['Major']; ?>" required class="w-full border rounded py-2 px-3 focus:outline-none focus:border-blue-500">
                    </div>

                    <div class="mb-4">
                        <label for="picture_profile" class="block text-gray-700 text-sm font-medium mb-2">Profile Picture</label>
                        <input type="file" id="picture_profile" name="picture_profile" class="w-full px-4 py-2 border-b rounded-md focus:outline-none focus:border-black">
                    </div>

                    <div class="text-center">
                        <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-700 focus:outline-none focus:shadow-outline-blue">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </body>

    </html>

<?php
}
?>