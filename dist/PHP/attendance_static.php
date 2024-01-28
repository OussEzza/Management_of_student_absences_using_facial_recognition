<?php
    require_once('config.php');
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
