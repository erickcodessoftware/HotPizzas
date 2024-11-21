<?php
include('BE/db.php');

// Verificación de la recepción de branch_id
if (isset($_GET['branch_id']) && !empty($_GET['branch_id'])) {
    $branch_id = $_GET['branch_id'];
} else {
    echo "No se ha especificado una sucursal válida 2.";
    exit;
}

// Consultar las reservas de la sucursal
if (isset($branch_id)) {
    $query = "SELECT * FROM reservations WHERE branch_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $branch_id);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    echo "No se ha especificado una sucursal válida.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservas de la Sucursal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="d-flex justify-content-between mb-3">
            <button onclick="window.location.href='index.php';" class="btn btn-primary">Inicio</button>
            <button onclick="window.location.href='admin.php';" class="btn btn-secondary">Volver</button>
        </div>
        <h1 class="text-center text-success mb-4">Reservas de la Sucursal</h1>
        
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Fecha de Reserva</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['name']; ?></td>
                        <td><?php echo $row['reservation_date']; ?></td>
                        <td>
                            <form method="POST" action="view_reservations.php?branch_id=<?php echo $branch_id; ?>">
                                <input type="hidden" name="reservation_id" value="<?php echo $row['id']; ?>">
                                <input type="hidden" name="branch_id" value="<?php echo $branch_id; ?>">
                                <button type="submit" name="delete_reservation" class="btn btn-danger">Eliminar Reserva</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <?php
if (isset($_POST['delete_reservation'])) {
    if (!empty($_POST['reservation_id']) && !empty($_POST['branch_id'])) {
        $reservation_id = $_POST['reservation_id'];
        $branch_id = $_POST['branch_id'];

        $deleteQuery = "DELETE FROM reservations WHERE id = ? AND branch_id = ?";
        $stmt = $conn->prepare($deleteQuery);
        $stmt->bind_param("ii", $reservation_id, $branch_id);

        if ($stmt->execute()) {
            $updateQuery = "UPDATE branches SET available_tables = available_tables + 1 WHERE id = ?";
            $stmt = $conn->prepare($updateQuery);
            $stmt->bind_param("i", $branch_id);

            if ($stmt->execute()) {
                header("Location: view_reservations.php?branch_id=" . $branch_id);
                exit(); // Asegúrate de que el script se detiene aquí.
            }
        }
    }
    echo "Error al procesar la solicitud.";
}

?>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
