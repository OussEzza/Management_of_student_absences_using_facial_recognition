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
        <title>Attendance</title>
    </head>

    <body>
        <?php
        require_once('panel.php');
        ?>
        <section id="AttendanceStatic">
            <?php
            // Récupère le nombre total d'étudiants
            $totalStudentsQuery = "SELECT COUNT(*) as total_students FROM students";
            $totalStudentsResult = $conn->query($totalStudentsQuery);
            $totalStudents = $totalStudentsResult->fetch_assoc()['total_students'];

            // Récupère le nombre total d'étudiants présents pour la date sélectionnée
            // $selectedDate = $_POST["selectedDate"];  // Assure-toi de valider cette date
            $totalPresentQuery = "SELECT COUNT(DISTINCT student_id) as total_present FROM attendancerecords";
            $totalPresentResult = $conn->query($totalPresentQuery);
            $totalPresent = $totalPresentResult->fetch_assoc()['total_present'];

            // Calcule le taux de présence moyen
            $attendanceRate = ($totalPresent / $totalStudents) * 100;

            // Affiche les statistiques
            echo "<p>Total Students: $totalStudents</p>";
            echo "<p>Total Present: $totalPresent</p>";
            echo "<p>Attendance Rate: " . number_format($attendanceRate, 2) . "%</p>";

            // Exemple de code pour afficher un graphique (utilise une bibliothèque comme Chart.js)
            echo "<canvas id='attendanceChart' width='400' height='200'></canvas>";
            ?>

        </section>
        <!-- Section pour le filtrage et la recherche -->
        <div class='flex items-start justify-center p-4 mb-32'>
            <div class="relative overflow-x-auto shadow-md sm:rounded-lg w-11/12 p-5">
                <h2 class="text-xl text-center font-bold mb-2">Filter and Search</h2>
                <form method="post" action="">
                    <!-- Filtres par département, programme, etc. -->
                    <div class="mb-4">
                        <label for="dateFilter" class="block text-gray-700 text-sm font-bold mb-2">Date :</label>
                        <input type="date" id="dateFilter" name="dateFilter" class="border-b-2 border-gray-800 border-solid rounded w-full py-2 px-3 cursor-pointer">

                    </div>

                    <!-- Ajoute d'autres filtres si nécessaire (programme, etc.) -->

                    <!-- Champ de recherche -->
                    <div class="mb-4">
                        <label for="searchInput" class="block text-gray-700 text-sm font-bold mb-2">Search:</label>
                        <input type="text" id="searchInput" name="searchInput" class="border-b-2 border-gray-800 border-solid rounded w-full py-2 px-3 cursor-pointer">
                    </div>

                    <!-- Bouton de soumission pour appliquer les filtres -->
                    <button name="search" type="submit" class="bg-blue-500 text-white py-2 px-4 rounded cursor-pointer">Apply Filters</button>
                </form>

                <!-- Affiche les relevés de présence filtrés -->
                <?php
                require_once('config.php');
                if (isset($_POST['search'])) {
                    // Récupère les valeurs des filtres
                    $dateFilter = isset($_POST['dateFilter']) ? $_POST['dateFilter'] : '';
                    $searchInput = isset($_POST['searchInput']) ? $_POST['searchInput'] : '';

                    $queryStudent = "SELECT student_id FROM students WHERE full_name LIKE '%$searchInput%'";
                    $resultStudent = $conn->query($queryStudent);
                    $StudentId = $resultStudent->fetch_assoc();

                    // Utilise les valeurs des filtres pour récupérer les relevés de présence
                    $query = "SELECT * FROM attendancerecords WHERE date_time LIKe '%$dateFilter%' AND student_id LIKE '%$StudentId%'";
                    $result = $conn->query($query);

                    // Affiche les relevés de présence filtrés
                    if ($result->num_rows > 0) {
                ?>
                        <h2 class='text-xl font-bold mb-2 text-center'>Filtered Attendance Records <?php echo 'for ' . $dateFilter; ?></h2>
                        <table class="w-full text-sm text-left rtl:text-right text-black dark:text-black">
                            <thead class="text-xs text-black uppercase bg-blue-600 dark:text-white">
                                <tr>
                                    <th scope="col" class="px-6 py-3">
                                        Student name
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Department
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Time attendance
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Total attendance
                                    </th>
                                </tr>
                            </thead>
                            <tbody>

                        <?php


                        while ($row = $result->fetch_assoc()) {
                            $Student_Id = $row['student_id'];
                            $queryStudent = "SELECT * FROM students WHERE student_id = '$Student_Id'";
                            $resultStudent = $conn->query($queryStudent);
                            $rowStudent = $resultStudent->fetch_assoc();
                            echo "<tr>";
                            echo "<td class='border px-4 py-2'>" . $rowStudent['full_name'] . "</td>";
                            echo "<td class='border px-4 py-2'>" . $rowStudent['Major'] . "</td>";
                            echo "<td class='border px-4 py-2'>" . $row['date_time'] . "</td>";
                            echo "<td class='border px-4 py-2'>" . $rowStudent['total_attendance'] . "</td>";
                            echo "</tr>";
                        }

                        echo "</tbody></table>";
                    } else {
                        echo "<p class='text-center'>No records found with the specified filters</p>";
                    }
                }

                        ?>



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