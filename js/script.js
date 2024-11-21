document.addEventListener("DOMContentLoaded", function () {
    // Maneja la navegación entre la vista de Administrador y Usuario
    const adminBtn = document.getElementById("adminBtn");
    const userBtn = document.getElementById("userBtn");

    adminBtn.addEventListener("click", function () {
        window.location.href = "admin.php";
    });

    userBtn.addEventListener("click", function () {
        window.location.href = "user.php";
    });

    // Si estamos en admin.php, habilitar la funcionalidad de agregar, editar y eliminar sucursales
    if (document.querySelector("#adminPanel")) {
        // Mostrar todas las sucursales
        function updateBranchList() {
            const branchList = document.querySelector("#branchList");
            branchList.innerHTML = "Cargando sucursales...";  // Mensaje mientras carga
            fetch('admin.php')  // Realiza una petición para recargar la lista de sucursales
                .then(response => response.text())
                .then(data => {
                    branchList.innerHTML = data;  // Reemplaza la lista actualizada
                })
                .catch(error => {
                    console.error('Error al cargar las sucursales:', error);
                    branchList.innerHTML = "Hubo un error al cargar las sucursales.";
                });
        }

        // Llamar la actualización de la lista de sucursales al cargar la página
        updateBranchList();

        // Agregar una nueva sucursal
        const addBranchForm = document.getElementById("addBranchForm");
        if (addBranchForm) {
            addBranchForm.addEventListener("submit", function (e) {
                e.preventDefault();

                const estado = document.getElementById("estado").value;
                const ciudad = document.getElementById("ciudad").value;
                const ruta = document.getElementById("ruta").value;
                const mesas = document.getElementById("mesas").value;

                if (estado && ciudad && ruta && mesas) {
                    // Enviar los datos para agregar la sucursal
                    fetch('admin.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: `add_branch=1&estado=${estado}&ciudad=${ciudad}&ruta=${ruta}&mesas=${mesas}`
                    })
                    .then(response => response.text())
                    .then(data => {
                        alert("Sucursal agregada correctamente");
                        updateBranchList();  // Actualizar la lista de sucursales
                        addBranchForm.reset();  // Limpiar el formulario
                    })
                    .catch(error => {
                        console.error("Error al agregar la sucursal:", error);
                        alert("Hubo un error al agregar la sucursal.");
                    });
                } else {
                    alert("Por favor, complete todos los campos.");
                }
            });
        }

        // Eliminar una sucursal
        document.querySelectorAll(".deleteBranchBtn").forEach(button => {
            button.addEventListener("click", function () {
                const branchId = this.getAttribute("data-id");
                if (confirm("¿Estás seguro de que deseas eliminar esta sucursal?")) {
                    fetch('admin.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: `delete_branch=1&branch_id=${branchId}`
                    })
                    .then(response => response.text())
                    .then(data => {
                        alert("Sucursal eliminada correctamente");
                        updateBranchList();  // Actualizar la lista de sucursales
                    })
                    .catch(error => {
                        console.error("Error al eliminar la sucursal:", error);
                        alert("Hubo un error al eliminar la sucursal.");
                    });
                }
            });
        });

        // Editar las mesas disponibles de una sucursal
        document.querySelectorAll(".editBranchBtn").forEach(button => {
            button.addEventListener("click", function () {
                const branchId = this.getAttribute("data-id");
                const currentMesas = this.getAttribute("data-mesas");

                const newMesas = prompt("¿Cuántas mesas disponibles quieres asignar?", currentMesas);
                if (newMesas !== null && newMesas !== "") {
                    fetch('admin.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: `edit_tables=1&branch_id=${branchId}&mesas=${newMesas}`
                    })
                    .then(response => response.text())
                    .then(data => {
                        alert("Mesas actualizadas correctamente");
                        updateBranchList();  // Actualizar la lista de sucursales
                    })
                    .catch(error => {
                        console.error("Error al actualizar las mesas:", error);
                        alert("Hubo un error al actualizar las mesas.");
                    });
                }
            });
        });
    }

    // Si estamos en user.php, habilitar la funcionalidad de hacer una reservación
    if (document.querySelector("#userPanel")) {
        const reservaForm = document.getElementById("reservaForm");

        reservaForm.addEventListener("submit", function (e) {
            e.preventDefault();

            const sucursalId = document.getElementById("sucursal").value;
            const nombre = document.getElementById("nombre").value;

            if (sucursalId && nombre) {
                // Enviar los datos para hacer la reserva
                fetch('user.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: `reservar=1&sucursal_id=${sucursalId}&nombre=${nombre}`
                })
                .then(response => response.text())
                .then(data => {
                    alert("Reserva realizada correctamente");
                    reservaForm.reset();  // Limpiar el formulario
                })
                .catch(error => {
                    console.error("Error al hacer la reserva:", error);
                    alert("Hubo un error al hacer la reserva.");
                });
            } else {
                alert("Por favor, complete todos los campos.");
            }
        });
    }

    // script.js - Eliminar reserva
document.querySelectorAll(".deleteReservationBtn").forEach(button => {
    button.addEventListener("click", function () {
        const reservationId = this.getAttribute("data-id");

        if (confirm("¿Estás seguro de que deseas eliminar esta reserva?")) {
            fetch('view_reservations.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `delete_reservation=1&reservation_id=${reservationId}`
            })
            .then(response => response.text())
            .then(data => {
                alert("Reserva eliminada correctamente.");
                location.reload();  // Recargar la página para mostrar los cambios
            })
            .catch(error => {
                console.error("Error al eliminar la reserva:", error);
                alert("Hubo un error al eliminar la reserva.");
            });
        }
    });
});

});
