<?php

include('db.php');
// Obtener todas las sucursales
$branches = getBranches();

// Manejo de la eliminación de sucursales
if (isset($_GET['delete_branch'])) {
    $branch_id = $_GET['delete_branch'];
    if (deleteBranch($branch_id)) {
        echo "<div class='alert alert-success'>Sucursal eliminada correctamente.</div>";
    } else {
        echo "<div class='alert alert-danger'>Error al eliminar la sucursal.</div>";
    }
    header("Location: admin.php");
    exit();
}

// Manejo de la creación de nuevas sucursales
if (isset($_POST['add_branch_submit'])) {
    $state = $_POST['state'];
    $city = $_POST['city'];
    $route = $_POST['route'];
    $tables = $_POST['tables'];
    addBranch($state, $city, $route, $tables);

    // Redirige para evitar el reenvío del formulario
    header("Location: admin.php");
    exit();
}

// admin_functions.php
function getBranches() {
    global $conn;
    $sql = "SELECT * FROM branches"; // Ajusta esto según tu estructura de base de datos
    $result = $conn->query($sql);
    return $result->fetch_all(MYSQLI_ASSOC);
}

function addBranch($state, $city, $route, $tables) {
    global $conn;
    $sql = "INSERT INTO branches (state, city, route, available_tables) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $state, $city, $route, $tables);
    return $stmt->execute();
}

function deleteBranch($branch_id) {
    global $conn;
    // Primero eliminar las reservas asociadas a la sucursal
    $deleteReservationsQuery = "DELETE FROM reservations WHERE branch_id = ?";
    $stmt = $conn->prepare($deleteReservationsQuery);
    $stmt->bind_param("i", $branch_id);
    $stmt->execute();

    // Luego eliminar la sucursal
    $deleteBranchQuery = "DELETE FROM branches WHERE id = ?";
    $stmt = $conn->prepare($deleteBranchQuery);
    $stmt->bind_param("i", $branch_id);
    return $stmt->execute();
}

?>
