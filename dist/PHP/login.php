<?php
session_start();
require_once('config.php');

// Vérifie si l'utilisateur est déjà connecté
if (isset($_SESSION["username"])) {
    header("Location: index.php");
    exit();
}

// Vérifie si le formulaire de connexion a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["user_email"]) && isset($_POST["pass"])) {

    // Vérifie les informations d'identification
    $username = $_POST["user_email"];
    $password = $_POST["pass"];

    if (empty($username) || empty($password)) {
        $error = "Username and password are required.";
    } else {
        $query = "SELECT * FROM admins WHERE user_email = '$username'";
        $result = $conn->query($query);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();

            if (!empty($row["password"]) && password_verify($password, $row["password"])) {
                // Authentification réussie
                $_SESSION["username"] = $username;
                header("Location: index.php");
                exit();
            } else {
                $error = "Invalid password";
            }
        } else {
            $error = "User not found";
        }
    }

    echo $error;
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../output.css" rel="stylesheet">
    <link rel="stylesheet" href="../CSS/style.css">
    <title>Login</title>
</head>

<body>
    <div class="flex items-center justify-center h-screen">
        <div class="w-full max-w-xs ">
            <form class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4" action="" method="post">
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="username">
                        Username
                    </label>
                    <input name="user_email" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="username" type="text" placeholder="Username">
                </div>
                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="password">
                        Password
                    </label>
                    <input name="pass" class="shadow appearance-none border w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline" id="password" type="password" placeholder="******************">
                    <!-- <p class="text-red-500 text-xs italic">Please choose a password.</p> -->
                </div>
                <div class="flex items-center justify-between">

                    <button name="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                        Sign In
                    </button>
                    <a class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800" href="#">
                        Forgot Password?
                    </a>
                </div>
            </form>
            <p class="text-center text-gray-500 text-xs">
                &copy;2024 OS EZZAHRI. All rights reserved.
            </p>
        </div>
    </div>


    <?php
    // require_once('config.php');
    // if(isset($_POST['submit'])){
    //     $user_email = $_POST['user_email'];
    //     $pass = $_POST['pass'];
    //     $query = "SELECT * FROM admins WHERE user_email = '$user_email'";
    //     $result = mysqli_query($conn, $query);
    //     if($result){
    //         $row = mysqli_fetch_assoc($result);
    //         if(password_verify($pass, $row['password'])){
    //             echo "Password verified";
    //         }else{
    //             echo "Password not verified";
    //         }
    //     }else{
    //         echo 'no user found !';
    //     }
    // }
    ?>
</body>

</html>