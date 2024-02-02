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

            <div class="box-container p-5 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 lg:grid-cols-4 xl:grid-cols-4 gap-6 max-w-6xl mx-auto mt-8 ">

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

                <?php
                $totalStudentsQuery = "SELECT COUNT(*) as total_students FROM students";
                $totalStudentsResult = $conn->query($totalStudentsQuery);
                $totalStudents = $totalStudentsResult->fetch_assoc()['total_students'];

                $selectedDate = date('Y-m-d');
                $totalPresentQuery = "SELECT COUNT(DISTINCT student_id) as total_present FROM attendancerecords WHERE date_time LIKE '%$selectedDate%'";
                $totalPresentResult = $conn->query($totalPresentQuery);
                $totalPresent = $totalPresentResult->fetch_assoc()['total_present'];

                $attendanceRate = ($totalPresent / $totalStudents) * 100;
                ?>
                <a href="Attendance.php" class="box border rounded-md p-4 bg-white shadow-md text-center block w-64 mx-auto">
                    <div class="p-4 bg-gray-100 text-gray-800 text-lg rounded-t-md border-b">
                        <p class="mb-2">Total Students:
                            <span class="text-xl font-bold text-blue-700">
                                <?php echo $totalStudents; ?>
                            </span>
                        </p>
                        <p class="mb-2">Total Present:
                            <span class="text-xl font-bold text-blue-700">
                                <?php echo $totalPresent; ?>
                            </span>
                        </p>
                        <p class="">Attendance Rate:
                            <span class="text-xl font-bold text-blue-700">
                                <?php echo number_format($attendanceRate, 2); ?>%
                            </span>
                        </p>
                    </div>

                    <div class="rounded-b-lg overflow-hidden mt-4">
                        <canvas id='attendanceChart' width='400' height='200'></canvas>
                    </div>
                </a>




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


        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            // Exemple de code pour initialiser un graphique avec Chart.js
            var ctx = document.getElementById('attendanceChart').getContext('2d');
            var myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Total Students', 'Total Present'],
                    datasets: [{
                        label: 'Attendance Statistics',
                        data: [<?php echo $totalStudents; ?>, <?php echo $totalPresent; ?>],
                        backgroundColor: [
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(255, 99, 132, 0.2)',
                        ],
                        borderColor: [
                            'rgba(75, 192, 192, 1)',
                            'rgba(255, 99, 132, 1)',
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        </script>
    </body>

    </html>

<?php
}
?>