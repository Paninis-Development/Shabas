<?php 
include_once __DIR__ . '../config/config.php';

?>
<!doctype html>
<html lang="en" data-bs-theme="auto">
    <head>
    <script src="assets/js/color-modes.js">
    <link rel="icon" href="/mealmaster_web/images/Firmenlogo.png" type="image/x-icon"></link>
    </script>
    </head>
    <body>
        <main class="container">
        <div class="bg-body-tertiary p-5 rounded">
            <?php
            include('./scripts/Startseite.php');

            ?>
        </div>
        </main>
        <script src="assets/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>