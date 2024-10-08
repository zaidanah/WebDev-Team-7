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

$success = $error = "";

// Proses form register admin
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validasi input
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $error = "Semua kolom wajib diisi!";
    } elseif ($password !== $confirm_password) {
        $error = "Password dan konfirmasi password tidak cocok!";
    } else {
        // Cek apakah username sudah ada
        $stmt = $conn->prepare("SELECT * FROM admin WHERE username = ? OR email = ?");
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $error = "Username atau email sudah terdaftar!";
        } else {
            // Tambahkan admin baru
            $stmt = $conn->prepare("INSERT INTO admin (username, password, email, Created_at, Updated_at) VALUES (?, ?, ?, NOW(), NOW())");
            $hashed_password = password_hash($password, PASSWORD_BCRYPT); // Hash password sebelum disimpan
            $stmt->bind_param("sss", $username, $hashed_password, $email);

            if ($stmt->execute()) {
                $success = "Admin berhasil ditambahkan!";
            } else {
                $error = "Gagal menambahkan admin.";
            }
        }

        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Admin - 7Summit</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;200;300;400;500;600;700;800;900&family=Roboto&display=swap" rel="stylesheet">
</head>

<link rel="stylesheet" href="../src/css/styles.css" />

<body class="background-admin">
    <div class="flex place-content-center">
        <img class="pl-20 pt-48" src="../src/images/Logo1.png" width="512" height="128">
    </div>
    <div class="font-montserrat text-4xl text-white font-bold flex place-content-center">
        <h1>REGISTER ADMIN</h1>
    </div>

    <div class="flex justify-center">
        <div class="w-full max-w-xs">
            <form class="px-8 pt-6" action="admin_add.php" method="POST">
                <div class="mb-4">
                    <label class="block text-gray-400 text-base font-bold mb-2" for="username">Username</label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="username" name="username" type="text" placeholder="Username" required>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-400 text-base font-bold mb-2" for="email">Email</label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="email" name="email" type="email" placeholder="Email" required>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-400 text-base font-bold mb-2" for="password">Password</label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="password" name="password" type="password" placeholder="Password" required>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-400 text-base font-bold mb-2" for="confirm_password">Confirm Password</label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="confirm_password" name="confirm_password" type="password" placeholder="Confirm Password" required>
                </div>
                
                <?php if ($error): ?>
                <p class="text-red-500 text-xs italic"><?php echo $error; ?></p>
                <?php endif; ?>
                
                <?php if ($success): ?>
                <p class="text-green-500 text-xs italic"><?php echo $success; ?></p>
                <?php endif; ?>

                <div class="flex items-center justify-between">
                    <button class="button-color rounded-full w-full h-12 text-white font-montserrat text-lg font-semibold" type="submit">Register</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
