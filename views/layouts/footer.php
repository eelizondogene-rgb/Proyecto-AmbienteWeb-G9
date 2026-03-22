<?php $baseUrl = '/Proyecto-AmbienteWeb-G9/public/'; ?>
    </main>
    <footer class="footer-custom">
        <div class="container-fluid">
            <p class="mb-0">ExamWeb &copy; 2025 - Sistema de Gestión de Exámenes de Admisión</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo $baseUrl; ?>js/dashboard.js"></script>
    <?php if (isset($additionalJs)): ?>
        <?php foreach ($additionalJs as $js): ?>
            <script src="<?php echo $baseUrl; ?>js/<?php echo $js; ?>.js"></script>
        <?php endforeach; ?>
    <?php endif; ?>
</body>
</html>