<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../output.css">
    <link rel="stylesheet" href="../CSS/style.css">
    <!-- <link rel="stylesheet" href="../CSS/admin.css"> -->
    <title>Slide bar</title>

</head>

<body>
    <div class="main-container flex h-screen">
        <div class="navigation bg-gray-800 text-white w-64 pl-4 pt-4 pb-4 text-xl">
            <ul>
                <li class="mb-2">
                    <a href="#" class="flex space-x-2 mb-8">
                        <span class="icon">
                            <ion-icon name="logo-microsoft"></ion-icon>
                        </span>
                        <span class="title">OS Brand</span>
                    </a>
                </li>


                <li class="mb-2 item">
                    <a href="#" class="">
                        <span class="icon">
                            <ion-icon name="analytics-outline"></ion-icon>
                        </span>
                        <span class="title">Dashboard</span>
                    </a>
                </li>



                <li class="mb-2 item">
                    <a href="students.php" class="">
                        <span class="icon">
                            <ion-icon name="school-outline"></ion-icon>
                        </span>
                        <span class="title">Students</span>
                    </a>
                </li>


                <li class="mb-2 item">
                    <a href="#" class="">
                        <span class="icon">
                            <ion-icon name="alert-outline"></ion-icon>
                        </span>
                        <span class="title">Attendance</span>
                    </a>
                </li>

                <li class="mb-2 item">
                    <a href="#" class="">
                        <span class="icon">
                            <ion-icon name="people-outline"></ion-icon>
                        </span>
                        <span class="title">Admins</span>
                    </a>
                </li>

                <li class="mb-2 item">
                    <a href="#" class="">
                        <span class="icon">
                            <ion-icon name="download-outline"></ion-icon>
                        </span>
                        <span class="title">Export</span>
                    </a>
                </li>

                <li class="mb-2 item">
                    <a href="#" class="">
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
                <div class="toggle text-gray-800 cursor-pointer">
                    <ion-icon name="menu-outline" class="text-3xl"></ion-icon>
                </div>

                <div class="admin w-14 h-14 overflow-hidden rounded-full cursor-pointer">
                    <img src="../pictures/student5651.jpg" alt="profile picture" class="w-full h-full object-cover">
                </div>
            </div>



            <?php include('config.php'); ?>

            <section class="dashboard">
                <div class="text-center">
                    <span class="text-5xl text-gray-800 font-semibold">TABLEAU <span class="text-blue-700">DE BORD</span></span>
                </div>

                <div class="box-container grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-4 gap-6 max-w-6xl mx-auto mt-8 ">

                    <a href="#" class="box border rounded-md p-8 bg-white shadow-md text-center">
                        <?php
                        $select_account = mysqli_query($conn, "SELECT * FROM `students`") or die('query failed');
                        $number_of_students = mysqli_num_rows($select_account);
                        ?>
                        <h3 class="text-5xl font-bold text-blue-700"><?php echo $number_of_students; ?></h3>
                        <p class="mt-6 p-6 bg-gray-100 text-gray-800 text-2xl rounded border">Totals des etudiats</p>
                    </a>

                    <a href="#" class="box border rounded-md p-8 bg-white shadow-md text-center">
                        <?php
                        $select_account = mysqli_query($conn, "SELECT * FROM `users`") or die('query failed');
                        $number_of_account = mysqli_num_rows($select_account);
                        ?>
                        <h3 class="text-5xl font-bold text-blue-700"><?php echo $number_of_account; ?></h3>
                        <p class="mt-6 p-6 bg-gray-100 text-gray-800 text-2xl rounded border">Totals des comptes</p>
                    </a>

                    <a href="#" class="box border rounded-md p-8 bg-white shadow-md text-center">
                        <?php
                        $select_account = mysqli_query($conn, "SELECT * FROM `attendancerecords`") or die('query failed');
                        $number_of_attendances = mysqli_num_rows($select_account);
                        ?>
                        <h3 class="text-5xl font-bold text-blue-700"><?php echo $number_of_attendances; ?></h3>
                        <p class="mt-6 p-6 bg-gray-100 text-gray-800 text-2xl rounded border">Totals des abseneces</p>
                    </a>
                    <a href="#" class="box border rounded-md p-8 bg-white shadow-md text-center">
                        <?php
                        $select_account = mysqli_query($conn, "SELECT * FROM `attendancerecords`") or die('query failed');
                        $number_of_attendances = mysqli_num_rows($select_account);
                        ?>
                        <h3 class="text-5xl font-bold text-blue-700"><?php echo $number_of_attendances; ?></h3>
                        <p class="mt-6 p-6 bg-gray-100 text-gray-800 text-2xl rounded border">Totals des abseneces</p>
                    </a>


                    <!-- Repeat the box structure for additional items -->

                </div>
            </section>

        </div>

    </div>

    <script>
        function handleBoxClick(boxType) {
            alert("Clicked on " + boxType);

        }
    </script>



    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

    <script src="../JS/main.js"></script>
</body>

</html>