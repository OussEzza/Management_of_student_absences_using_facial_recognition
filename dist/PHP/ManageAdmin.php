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

        <div class=" flex items-center justify-center">
            <div class="bg-white p-8 rounded shadow-md max-w-5xl">

                <h2 class="text-2xl text-center font-semibold mb-6">Liste des administrateurs</h2>

                <div class="overflow-x-auto">
                    <table class="table-auto w-full">
                        <thead>
                            <tr>
                                <th class="px-4 py-2">#</th>
                                <th class="px-4 py-2">Profile picture</th>
                                <th class="px-4 py-2">Full Name</th>
                                <th class="px-4 py-2">Username</th>
                                <th class="px-4 py-2">Email address</th>
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
                                        <a href="edit_admin.php?id=<?= $row['user_id'] ?>" class="hover:underline"><button class="bg-green-500 text-white px-3 py-1 rounded-full">Edit</button></a>
                                        |
                                        <a href="delete_admin.php?id=<?= $row['user_id'] ?>" class="hover:underline"><button class="bg-red-500 text-white px-3 py-1 rounded-full">Delete</button></a>
                                    </td>

                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>



        <div class="min-h-screen flex items-center justify-center">
            <div class="bg-white p-8 rounded shadow-md max-w-md w-full mt-5">

                <h2 class="text-2xl font-semibold mb-6">Add admin</h2>

                <form action="" method="POST" enctype="multipart/form-data">

                    <div class="mb-4">
                        <label for="picture_profile" class="block text-gray-700 text-sm font-medium mb-2">Profile picture</label>
                        <input type="file" id="picture_profile" name="picture_profile" required class="w-full px-4 py-2 border-b rounded-md focus:outline-none focus:border-black">
                    </div>

                    <div class="mb-4">
                        <label for="full_name" class="block text-gray-700 text-sm font-medium mb-2">Full Name :</label>
                        <input type="text" id="full_name" name="full_name" class="w-full px-4 py-2 border-b rounded-md focus:outline-none focus:border-black">
                    </div>

                    <div class="mb-4">
                        <label for="user_name" class="block text-gray-700 text-sm font-medium mb-2">Username</label>
                        <input type="text" id="user_name" name="user_name" class="w-full px-4 py-2 border-b rounded-md focus:outline-none focus:border-black">
                    </div>

                    <div class="mb-4">
                        <label for="user_email" class="block text-gray-700 text-sm font-medium mb-2">Email address</label>
                        <input type="email" id="user_email" name="user_email" class="w-full px-4 py-2 border-b rounded-md focus:outline-none focus:border-black" oninput="validateEmail()" required>
                        <p id="emailValidationError" class="text-red-500 hidden">Invalid email address.</p>
                    </div>

                    <div class="mb-4">
                        <label for="admin_permission" class="block text-gray-700 text-sm font-medium mb-2">Admin permission type</label>
                        <select id="admin_permission" name="admin_permission" class="w-full px-4 py-2 border-b rounded-md focus:outline-none focus:border-black" required>
                            <option value="3">Super Admin</option>
                            <option value="2">Admin</option>
                            <option value="1">Viewer</option>
                        </select>
                    </div>

                    <label for="password" class="block text-gray-700 text-sm font-medium mb-2">Password</label>
                    <div class="mb-6 flex items-center">
                        <div class="relative w-full">
                            <input type="password" id="password" name="password" class="w-full px-4 py-2 border-b rounded-md focus:outline-none focus:border-black" oninput="validatePasswords()" required>
                            <button type="button" id="togglePassword" class="absolute right-0 top-0 mt-3 mr-4 text-2xl text-gray-600 cursor-pointer">
                                <ion-icon name="eye-off-outline"></ion-icon>
                            </button>
                        </div>
                    </div>

                    <label for="confirm_password" class="block text-gray-700 text-sm font-medium mb-2">Confirm password</label>
                    <div class="mb-6 flex items-center">
                        <div class="relative w-full">
                            <input type="password" id="confirm_password" name="confirm_password" class="w-full px-4 py-2 border-b rounded-md focus:outline-none focus:border-black" oninput="validatePasswords()" required>
                            <button type="button" id="togglePasswordConfirm" class="absolute right-0 top-0 mt-3 mr-4 text-2xl text-gray-600 cursor-pointer">
                                <ion-icon name="eye-off-outline"></ion-icon>
                            </button>
                            <p id="passwordMatchError" class="text-red-500 hidden">The two passwords do not match.</p>
                        </div>
                    </div>


                    <button type="submit" class="w-full bg-gray-700 text-white py-2 px-4 rounded-md hover:bg-gray-900 focus:outline-none focus:shadow-outline-blue">
                        Add admin
                    </button>
                </form>
            </div>
        </div>


        <?php

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $full_name = $_POST['full_name'];
            $user_name = $_POST['user_name'];
            $user_email = $_POST['user_email'];
            $admin_permission = $_POST['admin_permission'];
            $password_user = $_POST['password'];
            $confirm_password = $_POST['confirm_password'];
            
            $checkEmailQuery = "SELECT user_id FROM admins WHERE user_email = '$user_email'";
            $resultEmail = mysqli_query($conn, $checkEmailQuery);
            
            if ($resultEmail) {
                $rowCount = mysqli_num_rows($resultEmail);
                $required_permission = 2;
                $permission = $_SESSION['permission'];
                if ($permission < $required_permission) {
                    echo "<script>
                        showMessage('You don't have the permission for this !', 'error');
                    </script>";
                } elseif ($rowCount > 0) {
                    echo "<script>
                showMessage('Cette adresse e-mail est déjà associée à un compte.', 'error');
            </script>";
                } elseif (!filter_var($user_email, FILTER_VALIDATE_EMAIL)) {

                    echo "<script>
                        showMessage('Adresse e-mail invalide.', 'error');
                    </script>";
                } elseif ($password_user !== $confirm_password) {
                    echo "<script>
                        showMessage('Les deux mots de passe ne correspondent pas.', 'error');
                    </script>";
                } else {
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
                                $sql = "INSERT INTO admins (full_name, user_name, user_email, profile_picture, TypeImage, password, permissions) VALUES (:full_name, :user_name, :user_email, :imageData, :imageType, :hashed_password, :admin_permission)";
                                $stmtImage = $pdo->prepare($sql);
                                $stmtImage->bindParam(":full_name", $full_name);
                                $stmtImage->bindParam(":user_name", $user_name);
                                $stmtImage->bindParam(":user_email", $user_email);
                                $stmtImage->bindParam(":imageData", $imageData, PDO::PARAM_LOB);
                                $stmtImage->bindParam(":imageType", $imageType);
                                $stmtImage->bindParam(":hashed_password", $hashed_password);
                                $stmtImage->bindParam(":admin_permission", $admin_permission);
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
            }
        }
        ?>


        <script>
            function validatePasswords() {
                var password = document.getElementById('password').value;
                var confirmPassword = document.getElementById('confirm_password').value;
                var passwordMatchError = document.getElementById('passwordMatchError');

                if (password !== confirmPassword) {
                    passwordMatchError.style.display = 'block';
                } else {
                    passwordMatchError.style.display = 'none';
                }
            }

            function validateEmail() {
                var email = document.getElementById('user_email').value;
                var emailValidationError = document.getElementById('emailValidationError');

                // Simple email validation regex, adjust as needed
                var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

                if (!emailRegex.test(email)) {
                    emailValidationError.style.display = 'block';
                } else {
                    emailValidationError.style.display = 'none';
                }
            }


            // Change le texte du bouton en fonction du type de mot de passe

            document.addEventListener("DOMContentLoaded", function() {
                var togglePassword = document.getElementById("togglePassword");
                var passwordInput = document.getElementById("password");

                togglePassword.addEventListener("click", function() {
                    var type = passwordInput.getAttribute("type") === "password" ? "text" : "password";
                    passwordInput.setAttribute("type", type);

                    // Change le texte du bouton en fonction du type de mot de passe
                    togglePassword.innerHTML = type === "password" ? '<ion-icon name="eye-off-outline"></ion-icon>' : '<ion-icon name="eye-outline"></ion-icon>';
                });
            });

            document.addEventListener("DOMContentLoaded", function() {
                var togglePassword = document.getElementById("togglePasswordConfirm");
                var passwordInput = document.getElementById("confirm_password");

                togglePassword.addEventListener("click", function() {
                    var type = passwordInput.getAttribute("type") === "password" ? "text" : "password";
                    passwordInput.setAttribute("type", type);

                    // Change le texte du bouton en fonction du type de mot de passe
                    togglePassword.innerHTML = type === "password" ? '<ion-icon name="eye-off-outline"></ion-icon>' : '<ion-icon name="eye-outline"></ion-icon>';
                });
            });
        </script>
        <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
        <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

        <script src="../JS/main.js"></script>
    </body>

    </html>
<?php
}
?>