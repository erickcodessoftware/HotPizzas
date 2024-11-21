<?php
$servername = "localhost"; // Cambiar al servidor de InfinityFree
$username = "root";        // Tu nombre de usuario de FTP
$password = "1234";        // Tu contraseña de FTP
$dbname = "hot_pizzas";    // Nombre de la base de datos en InfinityFree

// Conectar a la base de datos en InfinityFree
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar si la conexión tiene éxito
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
