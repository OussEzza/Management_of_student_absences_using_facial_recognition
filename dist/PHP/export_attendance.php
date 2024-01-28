<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../output.css">
    <title>Export Attendance Records</title>
</head>

<body>
    <?php
    require_once('panel.php');
    ?>
    <!-- Section pour l'exportation des relevés de présence -->
    <div class="flex items-start justify-center  p-5">
        <form method="post" action="downoald_export_attendance.php" class="max-w-md mx-auto bg-white p-6 rounded-md shadow-md">
            <h2 class="text-xl font-bold mb-2">Export Attendance Records</h2>
            <!-- Champ de sélection de la date pour l'exportation -->
            <div class="mb-4">
                <label for="exportDate" class="block text-gray-700 text-sm font-bold mb-2">Select Date:</label>
                <input type="date" id="exportDate" name="exportDate" class="border rounded w-full py-2 px-3">
            </div>

            <!-- Bouton de soumission pour l'exportation -->
            <button name="search" type="submit" class="bg-green-500 text-white py-2 px-4 rounded">Export to CSV</button>
        </form>
    </div>

</body>

</html>