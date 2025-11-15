<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $__env->yieldContent('title', 'Sitio Campestre'); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --color-primary: #005187;
            --color-secondary: #4d82bc;
            --color-tertiary: #84b6f4;
            --color-light: #c4dafa;
            --color-white: #fcffff;
        }
        body {
            background-color: var(--color-white);
        }
        .navbar {
            background-color: var(--color-primary) !important;
        }
        .btn-primary {
            background-color: var(--color-primary);
            border-color: var(--color-primary);
        }
        .btn-primary:hover {
            background-color: var(--color-secondary);
            border-color: var(--color-secondary);
        }
    </style>
    <?php echo $__env->yieldContent('styles'); ?>
</head>
<body>
    <?php echo $__env->yieldContent('content'); ?>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <?php echo $__env->yieldContent('scripts'); ?>
</body>
</html><?php /**PATH C:\xampp\htdocs\campestre\resources\views/layouts/app.blade.php ENDPATH**/ ?>