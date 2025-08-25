<?php
// components/footer.php
function renderFooter() {
?>
    <footer class="bg-gray-800 text-white py-6 mt-auto">
        <div class="max-w-7xl mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <img src="img/logo.png" alt="SP Learning Logo" class="h-8 w-auto mb-4">
                    <p class="text-sm text-gray-400">Educación en cualquier lado del mundo</p>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4"></h3>
                    <ul class="space-y-2 text-sm text-gray-400">
                        <li><a href="https://www.google.com/" target="_blank" class="hover:text-white transition duration-300">Sobre Nosotros</a></li>
                        <li><a href="https://www.nosequeestudiar.net/carreras/" target="_blank" class="hover:text-white transition duration-300">Carreras</a></li>
                        <li><a href="/Campus/calendario.php" class="hover:text-white transition duration-300">Calendario Académico</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4">Soporte</h3>
                    <ul class="space-y-2 text-sm text-gray-400">
                        <li><a href="#" class="hover:text-white transition duration-300">Centro de Ayuda</a></li> <!-- Algun link de soporte directo a whatsapp -->
                        <li><a href="mailto:splearning@gmail.com" class="hover:text-white transition duration-300">Contacto</a></li>
                        <li><a href="#" class="hover:text-white transition duration-300">FAQ</a></li> <!-- Preguntas frecuentes -->
                    </ul>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4">Contacto</h3>
                    <ul class="space-y-2 text-sm text-gray-400">
                        <li><i class="fas fa-envelope mr-2"></i>info@splearning.com</li>
                        <li><i class="fas fa-phone mr-2"></i>+54 2262 323031</li>
                        <li><i class="fas fa-map-marker-alt mr-2"></i>Buenos Aires, Argentina</li>
                    </ul>
                </div>
            </div>
            <div class="mt-8 pt-8 border-t border-gray-700 text-center text-sm text-gray-400">
                <p>&copy; <?php echo date('Y'); ?> SP Learning. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>
</body>
</html>
<?php
}
?>