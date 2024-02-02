<?php
session_start();
require '../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset password</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
</head>

<body class="bg-gray-100">
    <?php
    // require_once 'nav.php'; 
    ?>

    <main class="container mx-auto mt-8 max-w-md p-4 bg-white shadow-md text-center">

        <?php if (!isset($_GET['code'])) : ?>
            <form method="POST" class="max-w-md mx-auto mt-8 p-4 bg-white shadow-md text-center border">
                <h1 class="p-3 mb-3 font-bold text-2xl">e-mail</h1>
                <input class="form-input rounded-md bg-gray-100 block w-full border-b-2 border-black h-8" type="email" name="email" required />

                <button type="submit" class="bg-yellow-500 text-white font-bold py-2 px-4 mt-3 w-full rounded-full" name="resetPassword">
                    Send a password reset link to an email
                </button>
            </form>

        <?php elseif (isset($_GET['code']) && isset($_GET['email'])) : ?>
            <form method="POST" class="max-w-md mx-auto mt-8 p-4 bg-white shadow-md text-center">
                <div class="p-3 shadow mb-3">
                    Set a new password
                </div>
                <input class="form-input block w-full" type="password" name="password" required />

                <button type="submit" class="bg-yellow-500 text-white font-bold py-2 px-4 mt-3 w-full rounded-full" name="newPassword">
                    Reset password
                </button>
            </form>

        <?php endif; ?>

        <?php
        // Function to generate a secure random code
        function generateSecurityCode($length = 10)
        {
            return bin2hex(random_bytes($length));
        }
        if (isset($_POST['resetPassword'])) {

            $database = new PDO("mysql:host=localhost; dbname=managementofstudentabsences;", "root", "");

            $securityCode = generateSecurityCode();

            $currentTimestamp = date("Y-m-d H:i:s");

            $insertCode = $database->prepare("UPDATE admins SET security_code = :securityCode, security_code_timestamp = :currentTimestamp WHERE user_email = :email");
            $insertCode->bindParam(":securityCode", $securityCode);
            $insertCode->bindParam(":currentTimestamp", $currentTimestamp);
            $insertCode->bindParam(":email", $_POST['email']);
            $insertCode->execute();

            $checkEmail = $database->prepare("SELECT user_email, security_code, security_code_timestamp FROM admins WHERE user_email = :email");
            $checkEmail->bindParam(":email", $_POST['email']);
            $checkEmail->execute();

            if ($checkEmail->rowCount() > 0) {
                $user = $checkEmail->fetchObject();
                // require_once 'mail.php';
                // $checkEmail = $database->prepare("SELECT user_email, security_code FROM admins WHERE user_email = :email");
                // $checkEmail->bindParam(":email", $_POST['email']);
                // $checkEmail->execute();
                // $user = $checkEmail->fetchObject();

                try {
                    $mail = new PHPMailer(true);

                    // $verificationCode = substr(md5(uniqid(mt_rand(), true)), 0, 8);

                    $email = $_POST['email'];

                    // $query = "INSERT INTO orders(user_id, email, token) VALUES ('$userid', '$email', '$verificationCode')";

                    // $result = mysqli_query($conn, $query);

                    $mail->isSMTP();
                    $mail->Host       = 'smtp.gmail.com';
                    $mail->SMTPAuth   = true;
                    $mail->SMTPOptions = array(
                        'ssl' => array(
                            'verify_peer' => false,
                            'verify_peer_name' => false,
                            'allow_self_signed' => true
                        )
                    );
                    $mail->Username   = 'ezzahriraja@gmail.com';
                    $mail->Password   = 'gqrombpjvedezmqv';
                    $mail->SMTPSecure = 'ssl';
                    $mail->Port       = 465;    // Update port accordingly

                    $mail->setFrom('EZZAHRIos@gmail.com', 'OS Community');
                    $mail->addAddress($email);

                    $mail->Subject = "Reset password";
                    $mail->Body = '
                                Link to reset password
                                <br>
                                ' . '<a href="http://localhost/Management_of_student_absences_using_facial_recognition/dist/PHP/rest_pass.php?email=' . $_POST['email'] .
                        '&code=' . $user->security_code . '">Reset Password</a>';

                    $mail->AltBody = 'Pour voir ce message, veuillez utiliser un client de messagerie compatible HTML.';
                    $mail->isHTML(true);
                    $mail->CharSet = "UTF-8";

                    // $mail->SMTPDebug = 2; // Enable debugging
                    $mail->send();
                    echo '
                                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mt-3" role="alert">
                                        <strong class="font-bold">Success!</strong>
                                        <span class="block sm:inline">A link to reset your password has been sent to your email address.</span>
                                    </div>                 
                                ';
                } catch (Exception $e) {
                    echo "L'envoi de l'e-mail a échoué. Erreur : {$mail->ErrorInfo}";
                }
            } else {
                echo '
                        <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded relative mt-3" role="alert">
                            <strong class="font-bold">Warning!</strong>
                            <span class="block sm:inline">This email is not registered with us.</span>
                        </div>
                            ';
            }
        }



        if (isset($_POST['newPassword'])) {
            $database = new PDO("mysql:host=localhost; dbname=managementofstudentabsences;", "root", "");
            $checkCode = $database->prepare("SELECT user_email, security_code, security_code_timestamp FROM admins WHERE user_email = :email");
            $checkCode->bindParam(":email", $_GET['email']);
            $checkCode->execute();
            $user = $checkCode->fetchObject();
            // Compare security code from URL with the one in the database
            if ($_GET['code'] === $user->security_code) {
                // Check if the link is expired (e.g., set expiration to 1 hour)
                $expirationTime = strtotime($user->security_code_timestamp) + 3600; // 1 hour
                $currentTime = time();

                if ($currentTime <= $expirationTime) {
                    require_once('config.php');
                    $new_password = password_hash($_POST['password'], PASSWORD_DEFAULT);

                    $email = $_GET['email'];
                    $queryUpdatePassword = "UPDATE admins SET password = '$new_password' WHERE user_email = '$email'";
                    $result = mysqli_query($conn, $queryUpdatePassword);
                    if ($result) {
                        echo '<div class="alert alert-success mt-3 bg-green-200 text-green-800 rounded">Password Update by success!</div>';
                    } else {
                        echo '<div class="alert alert-danger mt-3 bg-red-200 text-red-800 rounded">Error!</div>';
                    }
                } else {
                    echo '
                            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mt-3" role="alert">
                                <strong class="font-bold">Error!</strong>
                                <span class="block sm:inline">The password reset link has expired.</span>
                            </div>
                        ';
                }
            } else {
                echo '
                            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mt-3" role="alert">
                                <strong class="font-bold">Error!</strong>
                                <span class="block sm:inline">Invalid security code.</span>
                            </div>
                        ';
            }
        }

        ?>

    </main>
</body>

</html>