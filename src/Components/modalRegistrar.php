<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>
<div class="modal fade" id="modalRegistrar" tabindex="-1" aria-labelledby="modalRegistrarLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content ">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body d-flex justify-content-center modal-fondo">
                <div class="card" style="width: 100%; height: auto;">
                    <div class="card-header">
                        <h5 class="card-title text-center">Registrate</h5>
                    </div>
                    <div class="card-body">
                        <form action="<?php echo BASE_URL; ?>/src/Controllers/registrarUsuario.php" method="post" class="row g-3">
                            <div>
                                <label for="nombre" class="form-label">Nombre</label>
                                <input type="text" class="form-control" id="nombre" name="nombre" required>
                            </div>
                            <div>
                                <label for="apellido" class="form-label">Apellido</label>
                                <input type="text" class="form-control" id="apellido" name="apellido" required>
                            </div>
                            <div>
                                <label for="dni" class="form-label">DNI</label>
                                <input type="text" class="form-control" id="dni" name="dni" required>
                            </div>
                            <div>
                                <label for="telefono" class="form-label">Teléfono</label>
                                <input type="text" class="form-control" id="telefono" name="telefono" required>
                            </div>
                            <div>
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div>
                                <label for="clave" class="form-label">Contraseña</label>
                                <input type="password" class="form-control" id="clave" name="clave" required>
                            </div>
                            <!-- Si tienes roles, puedes agregar un select aquí -->
                            <div class="mb-3">
                                <button type="submit" name="botonRegistrar" class="btn btn-success form-control">Registrarse</button>
                            </div>
                        </form>
                        <?php if (isset($_SESSION['registro_message'])): ?>
                            <div class="alert alert-info alert-dismissible fade show mt-2" role="alert">
                                <strong><?= htmlspecialchars($_SESSION['registro_message'], ENT_QUOTES, 'UTF-8') ?></strong>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                            <?php unset($_SESSION['registro_message']); ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

