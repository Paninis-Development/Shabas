<?php
include_once('./function.php');

$imagePath = '/assets/images/shababs-logo.jpg';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <link href="assets/css/Header.css" rel="stylesheet">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="./index.php">
            <img src="<?php echo $imagePath; ?>" alt="Logo" style="width:100px;height:100px;" />
        </a>

      


        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-item" href="Termin.php">Termin buchen</a>
                </li>
                <li class="nav-item">
                    <a class="nav-item" href="Startseite.php#Öffnungszeiten">Öffnungszeiten</a>
                </li>
                <li class="nav-item">
                    <a class="nav-item" href="Startseite.php#gallery">Gallerie</a>
                </li>
                <li class="nav-item">
                    <a class="nav-item" href="Startseite.php#footer">Hilfe</a>
                </li>
                <li class="nav-item">
                    <a class="nav-item" href="aboutUs.php">Über uns</a>
                </li>
                <li class="nav-item">
                    <a class="nav-item" href="Login.php">Admin Login</a>
                </li>
                <!-- <li class="nav-item">  Was sieht besser aus
                    <a class="nav-link" href="Login.php">Admin Page</a>
                </li> -->
            </ul>
        </div>
    </nav>
</body>

</html>

