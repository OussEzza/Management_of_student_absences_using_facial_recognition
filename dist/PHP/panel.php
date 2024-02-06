<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../output.css">
    <link rel="stylesheet" href="../CSS/style.css">
    <title>Panel</title>
</head>

<body>
    <div id="message" class="message">
        <!-- Le message sera affiché ici -->

        <!-- <button onclick="hideMessage()">Delete</button> -->
    </div>
    <div class="main-container flex h-screen">
        <div class="navigation bg-gray-800 text-white w-64 pl-4 pt-4 pb-4 text-xl">
            <ul>
                <li class="mb-2">
                    <a href="index.php" class="">
                        <span class="icon">
                            <ion-icon name="logo-microsoft"></ion-icon>
                        </span>
                        <span class="title">OS Brand</span>
                    </a>
                </li>



                <li class="mb-2 item">
                    <a href="index.php" class="">
                        <span class="icon">
                            <ion-icon name="analytics-outline"></ion-icon>
                        </span>
                        <span class="title">Dashboard</span>
                    </a>
                </li>


                <li class="mb-2 item main-menu relative group">
                    <a href="students.php" class="flex items-center">
                        <span class="icon">
                            <ion-icon name="school-outline"></ion-icon>
                        </span>
                        <span class="title">Students</span>
                    </a>
                </li>



                <li class="mb-2 item">
                    <a href="Attendance.php" class="">
                        <span class="icon">
                            <ion-icon name="alert-outline"></ion-icon>
                        </span>
                        <span class="title">Attendance</span>
                    </a>
                </li>

                <li class="mb-2 item">
                    <a href="ManageAdmin.php" class="">
                        <span class="icon">
                            <ion-icon name="people-outline"></ion-icon>
                        </span>
                        <span class="title">Admins</span>
                    </a>
                </li>

                <li class="mb-2 item">
                    <a href="export_attendance.php" class="">
                        <span class="icon">
                            <ion-icon name="download-outline"></ion-icon>
                        </span>
                        <span class="title">Export</span>
                    </a>
                </li>

                <li class="mb-2 item">
                    <a href="logout.php" class="">
                        <span class="icon">
                            <ion-icon name="log-out-outline"></ion-icon>
                        </span>
                        <span class="title">Logout</span>
                    </a>
                </li>

            </ul>
        </div>

        <div class="main flex-1 flex flex-col overflow-hidden transition-all duration-500 bg-white">
            <div class="topbar w-full h-16 flex items-center justify-between bg-white p-4">
                <div class="toggle text-gray-800 cursor-pointer z-50">
                    <ion-icon name="menu-outline" class="text-3xl"></ion-icon>
                </div>

                <div class="admin w-14 h-14 overflow-hidden rounded-full cursor-pointer">
                    <?php
                    session_start();
                    $user_id = $_SESSION['id'];
                    require_once('config.php');
                    $sql = "SELECT * FROM admins WHERE user_id = '$user_id'";
                    $result = $conn->query($sql);
                    $row = $result->fetch_assoc();
                    ?>
                    <img src="data:<?= $row['TypeImage'] ?>;base64,<?= base64_encode($row['profile_picture']) ?>" alt="Profile Picture" class="w-full h-full object-cover">
                    <!-- <img src="../pictures/student5651.jpg" alt="profile picture" class="w-full h-full object-cover"> -->
                </div>
            </div>


            <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
            <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

            <script src="../JS/main.js"></script>

</body>

</html>