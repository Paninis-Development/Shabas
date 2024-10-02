<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>header</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="assets/css/Header.css" rel="stylesheet">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="./index.php">
            <?php $imagePath = '/assets/images/shababs-logo.jpg'; ?>
            <img src="<?php echo $imagePath; ?>" alt="Logo" style="width:100px;height:100px;" />
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link" href="Startseite.php#Impressum-Section" onclick="openImpressum()">Impressum</a>
                </li>
                <li class="nav-item  mr-auto">
                    <a class="nav-item" href="./Login.php">
                        Admin Login
                    </a>
                </li>
                <li class="nav-item">
                    <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true): ?>
                        <a class="nav-link" href="admin_panel.php">Admin Panel</a>
                    <?php else: ?>
                        <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="<?php $loggedIn ?>">Admin Panel</a>
                    <?php endif; ?>
                </li>
            </ul>
        </div>
    </nav>

    <script>
        function openImpressum() {
            document.getElementById("Impressum-Section").open = true;
        }
    </script>
</body>

</html>
