<?php
session_start();


require_once('config.php');

if (isset($_GET['id'])) {
    $studentId = $_GET['id'];

    $query = "SELECT students.*, classes.class_name 
    FROM students 
    LEFT JOIN classes ON students.class_id = classes.class_id";

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
    $newId = $_POST['newId'];
    $newName = $_POST["newName"];
    $newDepartment = $_POST["newDepartment"];
    $newClass = $_POST['newClass'];

    $updateStudentQuery = "UPDATE students SET student_id = $newId, full_name = '$newName', Major = '$newDepartment' WHERE student_id = $studentId";
    $updateClassQuery = "UPDATE classes SET class_name = '$newClass' WHERE class_id = (SELECT class_id FROM students WHERE student_id = $studentId)";

    $conn->query($updateStudentQuery);
    $conn->query($updateClassQuery);

    header("Location: students.php");
    exit();
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


    <div class="flex items-start justify-center p-4">
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg w-3/5">
            <form method="post" action="<?php echo $_SERVER['PHP_SELF'] . "?id=" . $studentId; ?>" class="mt-4 p-5">
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
                    <label for="newClass" class="block text-gray-700 text-sm font-bold mb-2">New Class:</label>
                    <input type="text" name="newClass" value="<?php echo $student['class_name']; ?>" required class="w-full border rounded py-2 px-3 focus:outline-none focus:border-blue-500">
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