<?php
include('BE/admin_functions.php');
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrador - Hot Pizzas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <button onclick="window.location.href='index.php';" class="btn btn-primary mb-3">Inicio</button>
        <h1 class="text-center text-success">Gestión de Sucursales</h1>

        <!-- Botón para abrir una nueva sucursal -->
        <button onclick="window.location.href='admin.php?add_branch=true'" class="btn btn-success mb-3">Abrir Nueva Sucursal</button>

        <!-- Mostrar las sucursales existentes -->
        <div class="row">
            <h2 class="col-12 text-center">Sucursales</h2>
            <?php foreach ($branches as $branch): ?>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $branch['route']; ?>, <?php echo $branch['city']; ?>, <?php echo $branch['state']; ?></h5>
                            <p class="card-text">Mesas disponibles: <?php echo $branch['available_tables']; ?></p>
                            <a href="admin.php?delete_branch=<?php echo $branch['id']; ?>" class="btn btn-danger">Eliminar</a>
                            <a href="view_reservations.php?branch_id=<?php echo $branch['id']; ?>" class="btn btn-info">Ver Reservas</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Formulario para agregar una nueva sucursal -->
        <?php if (isset($_GET['add_branch'])): ?>
            <form method="POST" action="admin.php" class="mt-4">
                <h3>Abrir Nueva Sucursal</h3>
                <div class="mb-3">
                    <label for="state" class="form-label">Estado</label>
                    <input type="text" name="state" class="form-control" id="state" required>
                </div>
                <div class="mb-3">
                    <label for="city" class="form-label">Ciudad</label>
                    <input type="text" name="city" class="form-control" id="city" required>
                </div>
                <div class="mb-3">
                    <label for="route" class="form-label">Ruta</label>
                    <input type="text" name="route" class="form-control" id="route" required>
                </div>
                <div class="mb-3">
                    <label for="tables" class="form-label">Número de Mesas</label>
                    <input type="number" name="tables" class="form-control" id="tables" required>
                </div>
                <button type="submit" name="add_branch_submit" class="btn btn-success">Agregar Sucursal</button>
            </form>

            <!-- Formulario para cancelar -->
            <form action="admin.php" method="get" class="mt-3">
                <button type="submit" class="btn btn-secondary">Cancelar</button>
            </form>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
