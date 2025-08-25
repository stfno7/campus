<?php
session_start();
require_once 'components/header.php';
require_once 'components/footer.php';
renderHeader("Calendario");
?>
<div class="container mx-auto p-6">
    <h2 class="text-2xl font-bold mb-4"></h2>
    <div class="bg-white p-4 rounded shadow">
        <p></p>
        <iframe src="https://calendar.google.com/calendar/embed?src=es.argentine%23holiday%40group.v.calendar.google.com&ctz=America%2FArgentina_Buenos_Aires" style="border: 0" width="100%" height="600" frameborder="0" scrolling="no"></iframe>
    </div> <!-- Calendario de google con sus estilos, a modo de ejemplo. Para integrar Google Calendar con PHP, se necesita la API de Google Calendar y autenticación OAuth 2.0. -->
    <!-- Ejemplo: https://calendar.google.com/calendar/embed?src=stepaohhh%40gmail.com&ctz=America%2FArgentina%2FBuenos_Aires -->
</div>
<?php // Renderizar el footer
renderFooter("Dashboard");
?>
</body></html>