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
        <title>Delete student</title>
    </head>

    <body>

        <?php
        require_once('config.php');

        if (isset($_GET['id'])) {
            $required_permission = 2;
            $permission = $_SESSION['permission'];
            if ($permission < $required_permission) {
                echo "<script>
                        showMessage('You don't have the permission for this !', 'error');
                    </script>";
            } else {
                $studentId = $_GET['id'];

                // Fetch student details based on studentId
                $selectStudentQuery = "SELECT * FROM students WHERE student_id = $studentId";
                $result = $conn->query($selectStudentQuery);
                if ($result->num_rows > 0) {
                    $studentData = $result->fetch_assoc();

        ?> <div class="bg-white p-4 mt-4 rounded shadow-md max-w-md mx-auto">
                        <h3 class="text-lg font-semibold">Are you sure you want to delete the following student?</h3>
                        <p class="mb-2">Name: <?php echo $studentData['full_name']; ?></p>
                        <!-- <p class="mb-4">Email: <?php 
                        // echo $studentData['student_email']; ?></p> -->

                        <form action='' method='post'>
                            <input type='hidden' name='student_id' value='<?php echo $studentData['student_id']; ?>'>
                            <button type='submit' name='confirm_delete' class="bg-red-500 text-white px-4 py-2 rounded-md mr-2">Yes, delete</button>
                            <a href='students.php' class="text-gray-700 hover:underline">No, cancel</a>
                        </form>
                    </div>
        
                <?php
                    if (isset($_POST['confirm_delete'])) {
                        $deleteQuery = "DELETE FROM students WHERE student_id = $studentId";
                        if ($conn->query($deleteQuery) === TRUE) {
                            echo "<script>alert('Student deleted successfully!');</script>";
                            header("Location: students.php");
                            exit();
                        } else {
                            echo "<script>alert('Error deleting student: " . $conn->error . "');</script>";
                        }
                    }
                } else {
                    echo "Student not found.";
                }
            }
        } else {
            echo "Invalid request.";
        }
        ?>
    </body>

    </html>

<?php
}
?>