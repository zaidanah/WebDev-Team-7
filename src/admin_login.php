<?php
session_start();

// Koneksi ke database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "7summits";

$conn = new mysqli($servername, $username, $password, $dbname);

// Periksa koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$error = "";

// Proses form login
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepared statement untuk keamanan
    $stmt = $conn->prepare("SELECT * FROM admin WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    // Jika username dan password benar
    if ($result->num_rows > 0) {
        $_SESSION['username'] = $username;
        header("Location: admin_crud.php");
        exit();
    } else {
        $error = "Username atau password salah";
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>7Summit Admin Menu</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;200;300;400;500;600;700;800;900&family=Roboto&display=swap" rel="stylesheet">
</head>

<link rel="stylesheet" href="../src/css/styles.css" />


<body class="background-admin">
    <div class="flex place-content-center">
        <img class="pl-20 pt-48" src="images/Logo1.png" width="512" height="128">
    </div>
    <div class="font-montserrat text-4xl text-white font-bold flex place-content-center">
        <h1>ADMIN</h1>
    </div>

    <div class="flex justify-center">
        <div class="w-full max-w-xs">
            <form class="px-8 pt-6" action="admin_login.php" method="POST">
                <div class="mb-4">
                    <label class="block text-gray-400 text-base font-bold mb-2" for="username">Username</label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="username" name="username" type="text" placeholder="Username" required>
                </div>
                <div>
                    <label class="block text-gray-400 text-base font-bold mb-2" for="password">Password</label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline" id="password" name="password" type="password" placeholder="Password" required>
                </div>
                <?php if ($error): ?>
                <p class="text-red-500 text-xs italic"><?php echo $error; ?></p>
                <?php endif; ?>
                <div class="flex items-center justify-between">
                    <button class="button-color rounded-full w-full h-12 text-white font-montserrat text-lg font-semibold" type="submit">Log In</button>
                </div>
            </form>
        </div>
    </div>
</body>

</html>
