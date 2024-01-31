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

        // Vérifie si un ID est spécifié dans l'URL
        if (isset($_GET['id'])) {
            $studentId = $_GET['id'];

            // Supprime l'étudiant de la base de données
            $deleteQuery = "DELETE FROM students WHERE student_id = $studentId";
            $conn->query($deleteQuery);

            // Redirige vers la liste des étudiants après la suppression
            header("Location: students.php");
            exit();
        } else {
            echo "Invalid request.";
            exit();
        }
        ?>
    </body>

    </html>



<?php
}
?>