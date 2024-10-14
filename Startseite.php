<?php
// include_once __DIR__ . '/../config/config.php';
// include('./config/config.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Startseite</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link href="assets/css/Startseite.css" rel="stylesheet">
</head>
<header>
    <?php include('./Header.php'); ?>
</header>

<body>  

    <button onclick="location.href='./Termin.php'" id="termin-buchen-button", class="pulse">VIP Termin buchen</button>
    <!-- <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
        <ol class="carousel-indicators">
            <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
            <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
            <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
        </ol>
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img class="d-block w-100" src="assets/images/exampleFoto1.jpg" alt="First slide">
            </div>
            <div class="carousel-item">
                <img class="d-block w-100" src="assets/images/exampleFoto2.jpg" alt="Second slide">
            </div>
            <div class="carousel-item">
                <img class="d-block w-100" src="assets/images/exampleFoto3.jpg" alt="Third slide">
            </div>
        </div>
        <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>
    </div> -->


    <h2>Normale Öffnungszeiten</h2>

    <table class="table">

        <tr>
            <th>Tag</th>
            <th>Zeit</th>
        </tr>
        <tr>
            <td>Montag</td>
            <td>9:00/10:00 - 19:00/20:00</td>
        </tr>
        <tr>
            <td>Dienstag</td>
            <td>9:00/10:00 - 19:00/20:00</td>
        </tr>
        <tr>
            <td>Mittwoch</td>
            <td>9:00/10:00 - 19:00/20:00</td>
        </tr>
        <tr>
            <td>Donnerstag</td>
            <td>9:00/10:00 - 19:00/20:00</td>
        </tr>
        <tr>
            <td>Freitag</td>
            <td>9:00/10:00 - 19:00/20:00</td>
        </tr>
        <tr>
            <td>Samstag</td>
            <td>9:00/10:00 - 19:00/20:00</td>
        </tr>
        <tr>
            <td>Sonntag</td>
            <td>geschlossen</td>
        </tr>
    </table>
    <details id="Impressum-Section">
        <br>
        <summary><strong>Impressum</strong></summary>
        <p>Name: Ihr Name</p>
        <p>Adresse: Ihre Adresse</p>
        <p>Telefonnummer: Ihre Telefonnummer</p>
        <p>Email: Ihre Email</p>
    </details>

</body>

</html>