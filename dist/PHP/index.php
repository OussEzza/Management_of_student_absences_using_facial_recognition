<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../output.css">
    <link rel="stylesheet" href="../CSS/style.css">
    <title>Slide bar</title>

</head>

<body>
    <div class="main-container flex h-screen">
        <div class="navigation bg-gray-800 text-white w-64 pl-4 pt-4 pb-4 text-2xl">
            <ul>
                <li class="mb-2 relative">
                    <a href="#" class="hover:text-gray-300 flex items-center space-x-2">
                        <span class="icon">
                            <ion-icon name="logo-microsoft"></ion-icon>
                        </span>
                        <span class="title">OS Brand</span>
                    </a>

                </li>
                <li class="mb-2 relative">
                    <a href="#" class="">
                        <span class="icon">
                            <ion-icon name="home-outline"></ion-icon>
                        </span>
                        <span class="title">Home</span>
                    </a>
                </li>
                <li class="mb-2 relative">
                    <a href="#" class="">
                        <span class="icon">
                            <ion-icon name="school-outline"></ion-icon>
                        </span>
                        <span class="title">Students</span>
                    </a>
                </li>


                <li class="mb-2 relative">
                    <a href="#" class="">
                        <span class="icon">
                            <ion-icon name="school-outline"></ion-icon>
                        </span>
                        <span class="title">Dashboard</span>
                    </a>
                </li>

                <li class="mb-2 relative">
                    <a href="#" class="">
                        <span class="icon">
                            <ion-icon name="alert-outline"></ion-icon>
                        </span>
                        <span class="title">Attendance</span>
                    </a>
                </li>

                <li class="mb-2 relative">
                    <a href="#" class="">
                        <span class="icon">
                            <ion-icon name="people-outline"></ion-icon>
                        </span>
                        <span class="title">Admins</span>
                    </a>
                </li>

                <li class="mb-2 relative">
                    <a href="#" class="">
                        <span class="icon">
                            <ion-icon name="download-outline"></ion-icon>
                        </span>
                        <span class="title">Export</span>
                    </a>
                </li>

                <li class="mb-2 relative">
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
                    <ion-icon name="menu-outline" class="text-2xl"></ion-icon>
                </div>
                <div class="admin w-10 h-10 overflow-hidden rounded-full cursor-pointer">
                    <img src="../pictures/student5651.jpg" alt="profile picture" class="w-full h-full object-cover">
                </div>
            </div>
        </div>


    </div>




    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

    <script src="../JS/main.js"></script>
</body>

</html>