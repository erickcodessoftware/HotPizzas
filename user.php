<?php
include('BE/user_functions.php');
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservar - Hot Pizzas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <button onclick="window.location.href='index.php';" class="btn btn-primary mb-3">Inicio</button>
        <h1 class="text-center text-success">Reservaciones</h1>
        
        <!-- Formulario para hacer la reserva -->
        <form method="POST" action="user.php" class="mt-4">
            <div class="mb-3">
                <label for="branch" class="form-label">Selecciona una sucursal:</label>
                <select name="branch" id="branch" class="form-select" required>
                    <?php foreach ($branches as $branch): ?>
                        <option value="<?php echo $branch['id']; ?>"><?php echo $branch['route']; ?>, <?php echo $branch['city']; ?>, <?php echo $branch['state']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="name" class="form-label">Nombre:</label>
                <input type="text" name="name" class="form-control" id="name" required>
            </div>
            <button type="submit" name="reserve_table" class="btn btn-success">Reservar</button>
        </form>

        <?php
        // Verificar si se hizo una reserva
        if (isset($_POST['reserve_table'])) {
            $branch_id = $_POST['branch'];
            $name = $_POST['name'];
            
            // Llamar a la función de reserva y capturar el mensaje
            $message = makeReservation($branch_id, $name);

            // Mostrar el mensaje (si la reserva fue exitosa o hay algún problema)
            echo "<div class='mt-3 alert alert-info'>$message</div>";
        }
        ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
