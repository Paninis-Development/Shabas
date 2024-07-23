<?php
include_once __DIR__ . '/../config/config.php';
// $startseitelink = include(BASE_DIR . './scripts/Startseite.php')
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>header</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <link href="assets/css/Header.css" rel="stylesheet">
</head>


<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="../../index.php">
            <?php $imagePath = '/assets/images/shababs-logo.jpg';?>
            <img src="<?php echo $imagePath; ?>" alt="Logo" style="width:100px;height:100px;" />
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <!-- <li class="nav-item active">
                    <a class="nav-link" href='<?php //echo BASE_DIR . './Startseite.php'; 
                                                ?>'>Home <span class="sr-only">(current)</span></a>
                </li> -->
                <li class="nav-item">
                    <a class="nav-link" href="Startseite.php#Impressum-Section" onclick="openImpressum()">Impressum</a>
                    <script>
                        function openImpressum() {
                            document.getElementById("Impressum-Section").open = true;
                        }
                    </script>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Admin
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="Shabas\scripts\Login.php">Admin Login</a>

                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Admin Panel</a>
                </li>
            </ul>
            <!-- <span class="navbar-text">
            <?php //$loginLogoPath = '/assets/images/profile-boy-icon.svg'; ?>
            <img src="<?php //echo $loginLogoPath; ?>" alt="loginLogo" style="width:50px;height:50px;" /> 
            </span> -->
        </div>

    </nav>

</body>

</html>