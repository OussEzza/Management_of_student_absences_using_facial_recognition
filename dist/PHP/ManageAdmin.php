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
    <title>Add Admin</title>
    <style>
        .profile_pic {
            width: 80px;
        }
    </style>
</head>

<body class="bg-gray-100">

    <?php
    require_once('panel.php');
    require_once('config.php');

    $sql = "SELECT * FROM admins";
    $result = $conn->query($sql);
    ?>

    <div class="min-h-screen flex items-center justify-center">
        <div class="bg-white p-8 rounded shadow-md max-w-4xl">

            <h2 class="text-2xl text-center font-semibold mb-6">Liste des administrateurs</h2>

            <div class="overflow-x-auto">
                <table class="table-auto w-full">
                    <thead>
                        <tr>
                            <th class="px-4 py-2">#</th>
                            <th class="px-4 py-2">Photo de profil</th>
                            <th class="px-4 py-2">Nom complet</th>
                            <th class="px-4 py-2">Nom d'utilisateur</th>
                            <th class="px-4 py-2">Adresse e-mail</th>
                            <th class="px-4 py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()) { ?>
                            <tr>
                                <td class="border px-4 py-2"><?= $row['user_id'] ?></td>
                                <td class="border px-4 py-2">
                                    <img src="data:<?= $row['TypeImage'] ?>;base64,<?= base64_encode($row['profile_picture']) ?>" alt="Profile Picture" class="profile_pic object-cover rounded-full">
                                </td>
                                <td class="border px-4 py-2"><?= $row['full_name'] ?></td>
                                <td class="border px-4 py-2"><?= $row['user_name'] ?></td>
                                <td class="border px-4 py-2"><?= $row['user_email'] ?></td>
                                <td class="border px-4 py-2">
                                    <a href="edit_student.php?id=<?= $row['user_id'] ?>" class="hover:underline"><button class="bg-green-500 text-white px-3 py-1 rounded-full">Edit</button></a>
                                    |
                                    <a href="delete_student.php?id=<?= $row['user_id'] ?>" class="hover:underline"><button class="bg-red-500 text-white px-3 py-1 rounded-full">Delete</button></a>
                                </td>

                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>



    <div class="min-h-screen flex items-center justify-center">
        <div class="bg-white p-8 rounded shadow-md max-w-md w-full">

            <h2 class="text-2xl font-semibold mb-6">Add admin</h2>

            <form action="" method="POST" enctype="multipart/form-data">

                <div class="mb-4">
                    <label for="picture_profile" class="block text-gray-700 text-sm font-medium mb-2">Photo de profil</label>
                    <input type="file" id="picture_profile" name="picture_profile" required class="w-full px-4 py-2 border-b rounded-md focus:outline-none focus:border-black">
                </div>

                <div class="mb-4">
                    <label for="full_name" class="block text-gray-700 text-sm font-medium mb-2">Nom complet</label>
                    <input type="text" id="full_name" name="full_name" class="w-full px-4 py-2 border-b rounded-md focus:outline-none focus:border-black">
                </div>

                <div class="mb-4">
                    <label for="user_name" class="block text-gray-700 text-sm font-medium mb-2">Nom d'utilisateur</label>
                    <input type="text" id="user_name" name="user_name" class="w-full px-4 py-2 border-b rounded-md focus:outline-none focus:border-black">
                </div>

                <div class="mb-4">
                    <label for="user_email" class="block text-gray-700 text-sm font-medium mb-2">Adresse e-mail</label>
                    <input type="email" id="user_email" name="user_email" class="w-full px-4 py-2 border-b rounded-md focus:outline-none focus:border-black">
                </div>

                <div class="mb-6">
                    <label for="password" class="block text-gray-700 text-sm font-medium mb-2">Mot de passe</label>
                    <input type="password" id="password" name="password" class="w-full px-4 py-2 border-b rounded-md focus:outline-none focus:border-black">
                </div>

                <button type="submit" class="w-full bg-gray-700 text-white py-2 px-4 rounded-md hover:bg-gray-900 focus:outline-none focus:shadow-outline-blue">
                    S'inscrire
                </button>
            </form>
        </div>
    </div>


    <?php

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $full_name = $_POST['full_name'];
        $user_name = $_POST['user_name'];
        $user_email = $_POST['user_email'];
        $password_user = $_POST['password'];
        if (isset($_FILES["picture_profile"]) && $_FILES["picture_profile"]["error"] == 0) {
            if (empty($full_name) || empty($user_name) || empty($user_email) || empty($password_user)) {
                echo "<script>
            showMessage('Veuillez remplir tous les champs du formulaire.', 'error');
            </script>";
            } else {
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
                    // echo '<br> <br> <br> '.$password_user.'<br> <br> <br> ';
                    $hashed_password = password_hash($password_user, PASSWORD_DEFAULT);
                    $sql = "INSERT INTO admins (full_name, user_name, user_email, profile_picture, TypeImage, password) VALUES (:full_name, :user_name, :user_email, :imageData, :imageType, :hashed_password)";
                    $stmtImage = $pdo->prepare($sql);
                    $stmtImage->bindParam(":full_name", $full_name);
                    $stmtImage->bindParam(":user_name", $user_name);
                    $stmtImage->bindParam(":user_email", $user_email);
                    $stmtImage->bindParam(":imageData", $imageData, PDO::PARAM_LOB);
                    $stmtImage->bindParam(":imageType", $imageType);
                    $stmtImage->bindParam(":hashed_password", $hashed_password);
                    $stmtImage->execute();


                    echo "<script>
                        showMessage('Inscription réussie!', 'success');
                        </script>";
                } else {
                    echo "<script>showMessage('Seules les images au format JPEG, PNG et GIF sont autorisées.', 'error');</script>";
                }
            }
        } else {
            echo "<script>showMessage('Veuillez sélectionner une image à télécharger.', 'error');</script>";
        }
    }
    ?>


    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

    <script src="../JS/main.js"></script>
</body>

</html>
<?php
}
?>