<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../output.css">
    <title>Connexion</title>
</head>

<body class="bg-gray-100">

    <?php
    require_once('config.php');


    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['login'])) {

            $user_email = mysqli_real_escape_string($conn, $_POST['user_email']);
            $password = mysqli_real_escape_string($conn, $_POST['password']);

            // Validation des données (ajouter des validations appropriées)
            if (empty($user_email) || empty($password)) {
                echo '<div role="alert">
                <div class="bg-yellow-500 text-white font-bold rounded-t px-4 py-2">
                Attention
                </div>
                <div class="border border-t-0 border-yellow-400 rounded-b bg-yellow-100 px-4 py-3 text-yellow-700">
                <p>Veuillez remplir tous les champs du formulaire.</p>
                </div>
                </div>';
            } else {

                $result = mysqli_query($conn, "SELECT * FROM admins WHERE user_email='$user_email'") or die(mysqli_error($conn));
                
                if ($result->num_rows > 0) {
                    $admin = mysqli_fetch_assoc($result);
                    $passwordAdmin = $admin['password'];
                    if (password_verify($password, $passwordAdmin)) {
                        $_SESSION['id'] = $admin['user_id'];
                        $_SESSION["username"] = $admin['user_name'];
                        $_SESSION["email"] = $admin['user_email'];

                        // echo '<div class="flex items-center bg-blue-500 text-white text-sm font-bold px-4 py-3" role="alert">
                        // <ion-icon name="checkmark-outline" class="text-3xl"></ion-icon>
                        // <p>Connexion réussie !</p>
                        // </div>';
                        header("Location: index.php");
                        exit();
                    } else {
                        // Authentification échouée
                        echo '<div role="alert">
                        <div class="bg-red-500 text-white font-bold rounded-t px-4 py-2">
                            Erreur
                        </div>
                        <div class="border border-t-0 border-red-400 rounded-b bg-red-100 px-4 py-3 text-red-700">
                            <p>Identifiants incorrects. Veuillez réessayer.</p>
                        </div>
                    </div>';
                    }
                } else {
                    // Utilisateur non trouvé
                    echo '<div role="alert">
                    <div class="bg-red-500 text-white font-bold rounded-t px-4 py-2">
                        Erreur
                    </div>
                    <div class="border border-t-0 border-red-400 rounded-b bg-red-100 px-4 py-3 text-red-700">
                        <p>Aucun utilisateur trouvé avec cet e-mail.</p>
                    </div>
                </div>';
                }
            }
        }
    }
    ?>
    <div class="container max-h-screen grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-2 xl:grid-cols-2 gap-6">

        <div class="one-container min-h-screen flex items-center justify-center">
            <div class="bg-white p-8 rounded shadow-md max-w-md w-full">

                <h2 class="text-2xl font-semibold mb-6">Connexion</h2>

                <form action="" method="POST">

                    <div class="mb-4">
                        <label for="user_email" class="block text-gray-700 text-sm font-medium mb-2">Adresse e-mail</label>
                        <input type="email" id="user_email" name="user_email" require class="w-full px-4 py-2 border-b rounded-md focus:outline-none focus:border-black">
                    </div>

                    <label for="password" class="block text-gray-700 text-sm font-medium mb-2">Mot de passe</label>
                    <div class="mb-6 flex items-center">
                        <div class="relative w-full">
                            <input type="password" id="password" name="password" required class="w-full px-4 py-2 border-b rounded-md focus:outline-none focus:border-black">
                            <button type="button" id="togglePassword" class="absolute right-0 top-0 mt-3 mr-4 text-2xl text-gray-600 cursor-pointer">
                                <ion-icon name="eye-off-outline"></ion-icon>
                            </button>
                        </div>
                    </div>


                    <button name="login" type="submit" class="w-full bg-gray-700 text-white py-2 px-4 rounded-md hover:bg-gray-900 focus:outline-none focus:shadow-outline-blue">
                        Se connecter
                    </button>

                </form>
            </div>
        </div>


        <div class="two-container w-full max-h-screen bg-black ">
            <img src="../pictures/20547283_6310507.jpg" alt="" srcset="">
        </div>
    </div>
    <script>
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
    </script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

    <script src="../JS/main.js"></script>
</body>

</html>