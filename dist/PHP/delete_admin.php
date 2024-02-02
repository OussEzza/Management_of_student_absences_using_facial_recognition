<?php
session_start();

if (!isset($_SESSION['email'])) {
    header('location:login.php');
} else {
    $user_id = $_SESSION['id'];

    require_once('config.php');

    if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
        $admin_id = $_GET['id'];

        // Check if the admin exists in the database
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
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Admin</title>
</head>

<body class="bg-gray-100">
    <?php require_once('panel.php'); ?>

    <div class="min-h-screen flex items-center justify-center">
        <div class="bg-white p-8 rounded shadow-md max-w-md w-full">

            <h2 class="text-2xl font-semibold mb-6">Delete Admin</h2>

            <p>Are you sure you want to delete the admin <strong><?= $adminDetails['full_name'] ?></strong>?</p>

            <form action="" method="POST">

                <!-- Hidden input to pass admin ID to the server -->
                <input type="hidden" name="admin_id" value="<?= $admin_id ?>">

                <button name="delete" type="submit" class="w-full bg-red-500 text-white py-2 px-4 rounded-md hover:bg-red-700 focus:outline-none focus:shadow-outline-red">
                    Delete Admin
                </button>
            </form>
        </div>
    </div>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
        $admin_id = $_POST['admin_id'];
        if($admin_id != $user_id){

            // Delete admin from the database based on $admin_id
            $deleteAdminQuery = "DELETE FROM admins WHERE user_id='$admin_id'";
            $resultDelete = mysqli_query($conn, $deleteAdminQuery);
            
            if ($resultDelete) {
                echo "<script>
                showMessage('Admin deleted successfully!', 'success');
                </script>";

                header('location: ManageAdmin.php');
                exit();
            } else {
                echo "<script>
                showMessage('Error deleting admin.', 'error');
                </script>";
            }
        }else{
            echo "<script>
                    showMessage('You not can deleting your account.', 'error');
                </script>";
        }
    }
    ?>


</body>

</html>

<?php
}
?>
