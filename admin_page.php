<?php
session_start(); // Iniciar la sesión

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['username'])) {
    header('Location: login.php'); // Redirigir a la página de inicio de sesión si no está logueado
    exit();
}

$username = $_SESSION['username']; // Obtener el nombre del usuario de la sesión
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página de bienvenida</title>
    <link rel="stylesheet" href="styleAdmin.css">
</head>
<body>
    <div class="container">
        <h1>Bienvenido Admin, <?php echo htmlspecialchars($username); ?>!</h1>
        <div id="clock"></div>
        <button id="toggleTheme">Cambiar modo oscuro/claro</button>
    </div>
    <script src="script.js"></script>
</body>
</html>
