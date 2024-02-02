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
        <link rel="stylesheet" href="../CSS/style.css">
        <!-- <link rel="stylesheet" href="../CSS/admin.css"> -->
        <title>Dashboard</title>

    </head>

    <body>
        <?php
        require_once('panel.php');
        ?>


        <?php include('config.php'); ?>

        <section class="dashboard">
            <div class="text-center">
                <span class="text-5xl text-gray-800 font-semibold">TABLEAU <span class="text-blue-700">DE BORD</span></span>
            </div>

            <div class="box-container p-5 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-3 xl:grid-cols-3 gap-6 max-w-6xl mx-auto mt-8 ">

                <a href="students.php" class="box border rounded-md p-8 bg-white shadow-md text-center">
                    <?php
                    $select_account = mysqli_query($conn, "SELECT * FROM `students`") or die('query failed');
                    $number_of_students = mysqli_num_rows($select_account);
                    ?>
                    <h3 class="text-5xl font-bold text-blue-700"><?php echo $number_of_students; ?></h3>
                    <p class="mt-6 p-6 bg-gray-100 text-gray-800 text-2xl rounded border">Student totals</p>
                </a>

                <a href="#" class="box border rounded-md p-8 bg-white shadow-md text-center">
                    <?php
                    $select_account = mysqli_query($conn, "SELECT * FROM `admins`") or die('query failed');
                    $number_of_account = mysqli_num_rows($select_account);
                    ?>
                    <h3 class="text-5xl font-bold text-blue-700"><?php echo $number_of_account; ?></h3>
                    <p class="mt-6 p-6 bg-gray-100 text-gray-800 text-2xl rounded border">Total accounts</p>
                </a>

                <a href="Attendance.php" class="box border rounded-md p-8 bg-white shadow-md text-center">
                    <?php

                    $totalPresentQuery = "SELECT SUM(total_attendance) as total_present FROM students";
                    $totalPresentResult = mysqli_query($conn, $totalPresentQuery) or die('Query failed: ' . mysqli_error($conn));
                    $totalPresentData = mysqli_fetch_assoc($totalPresentResult);
                    $totalPresent = $totalPresentData['total_present'];

                    ?>
                    <h3 class="text-5xl font-bold text-blue-700"><?php echo $totalPresent; ?></h3>
                    <p class="mt-6 p-6 bg-gray-100 text-gray-800 text-2xl rounded border">Attendance totals</p>
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

<?php
}
?>