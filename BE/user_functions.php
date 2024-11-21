<?php
include('db.php');

// Obtener las sucursales
$branches = getBranches();

function getBranches() {
    global $conn;
    $sql = "SELECT * FROM branches";
    $result = $conn->query($sql);
    
    // Devolver los resultados como un array
    return $result->fetch_all(MYSQLI_ASSOC);
}

function makeReservation($branch_id, $name) {
    global $conn;

    // Primero, verifica si hay mesas disponibles
    $stmt = $conn->prepare("SELECT available_tables FROM branches WHERE id = ?");
    $stmt->bind_param("i", $branch_id);
    $stmt->execute();
    $stmt->bind_result($available_tables);
    $stmt->fetch();
    $stmt->close();

    // Si hay mesas disponibles, hacer la reserva
    if ($available_tables > 0) {
        // Verificar si ya existe una reserva para este nombre en la misma sucursal
        $stmt = $conn->prepare("SELECT COUNT(*) FROM reservations WHERE branch_id = ? AND name = ?");
        $stmt->bind_param("is", $branch_id, $name);
        $stmt->execute();
        $stmt->bind_result($existing_reservations);
        $stmt->fetch();
        $stmt->close();

        // Si no hay reservas previas para este nombre, proceder con la inserción
        if ($existing_reservations == 0) {
            // Insertar la nueva reserva
            $stmt = $conn->prepare("INSERT INTO reservations (branch_id, name) VALUES (?, ?)");
            $stmt->bind_param("is", $branch_id, $name);
            $stmt->execute();
            $stmt->close();

            // Reducir una mesa disponible
            $stmt = $conn->prepare("UPDATE branches SET available_tables = available_tables - 1 WHERE id = ?");
            $stmt->bind_param("i", $branch_id);
            $stmt->execute();
            $stmt->close();

            return "Reserva realizada con éxito.";
        } else {
            return "Ya existe una reserva a nombre de $name en esta sucursal.";
        }
    } else {
        return "No hay mesas disponibles en esta sucursal.";
    }
}
?>
