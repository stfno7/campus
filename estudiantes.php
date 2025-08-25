<?php
session_start();
require_once 'config/database.php';
require_once 'components/footer.php';

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$pdo = Database::getInstance()->getConnection();

// Consulta para obtener los estudiantes y sus materias inscritas
$query = "
    SELECT u.id, u.nombre, u.email, u.activo, m.nombre AS materia, i.fecha_inscripcion
    FROM usuarios u
    LEFT JOIN inscripciones i ON u.id = i.usuario_id
    LEFT JOIN materias m ON i.materia_id = m.id
    WHERE u.rol = 'estudiante'
    ORDER BY u.nombre, m.nombre
";
$stmt = $pdo->prepare($query);
$stmt->execute();
$estudiantes = $stmt->fetchAll();
?>

<?php require_once 'components/header.php'; ?>
<?php renderHeader('Lista de Estudiantes'); ?>

<!-- Esqueleto HTML -->
<div class="container mx-auto p-6">
    <h2 class="text-2xl font-bold mb-4"></h2>
    
    <table class="min-w-full bg-white border border-gray-300">
      <thead>
          <tr class="bg-gray-200">
              <th class="py-2 px-4 border">ID</th>
              <th class="py-2 px-4 border">Nombre</th>
              <th class="py-2 px-4 border">Email</th>
              <th class="py-2 px-4 border">Materia Inscrita</th>
              <th class="py-2 px-4 border">Fecha de Inscripción</th>
              <th class="py-2 px-4 border">Estado</th>
              <th class="py-2 px-4 border">Acciones</th>
          </tr>
      </thead>
      <tbody>
          <?php if ($estudiantes): ?>
              <?php foreach ($estudiantes as $est): ?>
                  <tr class="border" data-id="<?php echo $est['id']; ?>">
                      <td class="py-2 px-4 border"><?php echo htmlspecialchars($est['id']); ?></td>
                      <td class="py-2 px-4 border"><?php echo htmlspecialchars($est['nombre']); ?></td>
                      <td class="py-2 px-4 border"><?php echo htmlspecialchars($est['email']); ?></td>
                      <td class="py-2 px-4 border"><?php echo htmlspecialchars($est['materia'] ?? 'No inscrito'); ?></td>
                      <td class="py-2 px-4 border"><?php echo htmlspecialchars($est['fecha_inscripcion'] ?? '-'); ?></td>
                      <td class="py-2 px-4 border estado-text"><?php echo $est['activo'] ? 'Activo' : 'Inactivo'; ?></td>
                      <td class="py-2 px-4 border text-center space-y-1">
                          <a href="crud/usuarios/editar.php?id=<?php echo $est['id']; ?>" class="text-blue-600 hover:underline">Editar</a><br>
                          <a href="crud/materias/asignar.php?id=<?php echo $est['id']; ?>" class="text-green-600 hover:underline">Asignar materia</a><br>
                          <a href="crud/materias/quitar.php?id=<?php echo $est['id']; ?>" class="text-yellow-600 hover:underline">Quitar materia</a><br>
                          <a href="#" 
                          class="toggle-btn text-purple-600 hover:underline" 
                          data-id="<?php echo $est['id']; ?>" 
                          data-estado="<?php echo $est['activo']; ?>">
                          <?php echo $est['activo'] ? 'Deshabilitar' : 'Habilitar'; ?>
                          </a><br>
                          

                          <button 
                              class="delete-btn text-red-600 hover:underline text-sm" 
                              data-id="<?php echo $est['id']; ?>">
                              Eliminar
                          </button>
                      </td>
                  </tr>
              <?php endforeach; ?>
          <?php else: ?>
              <tr>
                  <td colspan="7" class="py-2 px-4 text-center">No hay estudiantes registrados.</td>
              </tr>
          <?php endif; ?>
      </tbody>
    </table>
</div>

<script>
// Habilitar / Deshabilitar
document.querySelectorAll('.toggle-btn').forEach(boton => {
    boton.addEventListener('click', function () {
        const accion = this.textContent.trim();
        const mensaje = accion === 'Deshabilitar'
            ? '¿Estás seguro que querés deshabilitar al estudiante?'
            : '¿Estás seguro que querés habilitar al estudiante?';

        if (!confirm(mensaje)) return;

        const userId = this.dataset.id;
        const estadoActual = parseInt(this.dataset.estado);

        fetch('crud/usuarios/toggle_estado.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `id=${userId}&estado=${estadoActual}`
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                this.dataset.estado = data.nuevo_estado;
                this.textContent = data.nuevo_estado == 1 ? 'Deshabilitar' : 'Habilitar';
                const fila = this.closest('tr');
                fila.querySelector('.estado-text').textContent = data.nuevo_estado == 1 ? 'Activo' : 'Inactivo';
            } else {
                alert('Error al actualizar estado.');
            }
        });
    });
});

// Eliminar usuario
document.querySelectorAll('.delete-btn').forEach(boton => {
    boton.addEventListener('click', function () {
        const userId = this.dataset.id;

        if (confirm('¿Seguro que querés eliminar permanentemente al estudiante?')) {
            fetch('crud/usuarios/eliminar.php?id=' + userId, {
                method: 'GET'
            })
            .then(res => res.text())
            .then(response => {
                alert('Usuario eliminado');
                this.closest('tr').remove();
            });
        }
    });
});
</script>

<?php renderFooter("Dashboard"); ?>
