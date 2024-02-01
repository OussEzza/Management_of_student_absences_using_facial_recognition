<?php
session_start();

if (!isset($_SESSION['email'])) {
    header('location:login.php');
} else {
    $user_id = $_SESSION['id'];

    require_once('config.php');

    if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
        $admin_id = $_GET['id'];

        // Fetch admin details based on $admin_id
        $selectAdminQuery = "SELECT * FROM admins WHERE user_id = '$admin_id'";
        $resultAdmin = mysqli_query($conn, $selectAdminQuery);

        if ($resultAdmin) {
            $adminDetails = mysqli_fetch_assoc($resultAdmin);

            if (!$adminDetails) {
                header('location:admin_not_found.php');
                exit();
            }
        } else {
            echo "Error: " . mysqli_error($conn);
            exit();
        }
    } else {
        header('location:admin_not_found.php');
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Admin</title>
    <!-- Add your stylesheets and other head content here -->
</head>

<body class="bg-gray-100">
    <?php require_once('panel.php'); ?>

    <div class="min-h-screen flex items-center justify-center">
        <div class="bg-white p-8 rounded shadow-md max-w-md w-full">

            <h2 class="text-2xl font-semibold mb-6">Edit Admin</h2>

            <form action="" method="POST" enctype="multipart/form-data">

                <!-- Display existing admin details in form fields -->
                <input type="hidden" name="admin_id" value="<?= $adminDetails['user_id'] ?>">

                <div class="mb-4">
                    <label for="full_name" class="block text-gray-700 text-sm font-medium mb-2">Full Name</label>
                    <input type="text" id="full_name" name="full_name" class="w-full px-4 py-2 border-b rounded-md focus:outline-none focus:border-black" value="<?= $adminDetails['full_name'] ?>" required>
                </div>

                <div class="mb-4">
                    <label for="user_name" class="block text-gray-700 text-sm font-medium mb-2">Username</label>
                    <input type="text" id="user_name" name="user_name" class="w-full px-4 py-2 border-b rounded-md focus:outline-none focus:border-black" value="<?= $adminDetails['user_name'] ?>" required>
                </div>

                <div class="mb-4">
                    <label for="user_email" class="block text-gray-700 text-sm font-medium mb-2">Email</label>
                    <input type="email" id="user_email" name="user_email" class="w-full px-4 py-2 border-b rounded-md focus:outline-none focus:border-black" value="<?= $adminDetails['user_email'] ?>" required>
                </div>

                <!-- Add other fields as needed -->

                <button type="submit" class="w-full bg-gray-700 text-white py-2 px-4 rounded-md hover:bg-gray-900 focus:outline-none focus:shadow-outline-blue">
                    Update Admin
                </button>
            </form>
        </div>
    </div>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $admin_id = $_POST['admin_id'];
        $full_name = $_POST['full_name'];
        $user_name = $_POST['user_name'];
        $user_email = $_POST['user_email'];

        // Update admin details in the database based on $admin_id
        $updateAdminQuery = "UPDATE admins SET full_name='$full_name', user_name='$user_name', user_email='$user_email' WHERE user_id='$admin_id'";
        $resultUpdate = mysqli_query($conn, $updateAdminQuery);

        if ($resultUpdate) {
            echo "<script>
                showMessage('Admin updated successfully!', 'success');
            </script>";
        } else {
            echo "<script>
                showMessage('Error updating admin details.', 'error');
            </script>";
        }
    }
    ?>


</body>

</html>

<?php
}
?>
