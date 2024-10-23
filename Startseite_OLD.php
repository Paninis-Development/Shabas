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
    <link rel="icon" type="image/x-icon" href="assets/images/shababs-logo.ico">

</head>
<header>
    <?php include('./Header.php'); ?>
</header>

<body>

    <button onclick="location.href='./Termin.php'" id="termin-buchen-button" , class="pulse">VIP Termin buchen</button>
    <!-- <div id="carouselExampleSlidesOnly" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <video class="img-fluid" autoplay loop muted>
                    <source src="assets/videos/WhatsApp Video 2024-10-11 at 13.47.08.mp4" type="video/mp4" />
                </video>
            </div>
            <div class="carousel-item">
                <video class="img-fluid" autoplay loop muted>
                    <source src="assets/videos/WhatsApp Video 2024-10-11 at 13.47.13.mp4" type="video/mp4" />
                </video>
            </div>
            <div class="carousel-item">
                <video class="img-fluid" autoplay loop muted>
                    <source src="assets/videos/WhatsApp Video 2024-10-11 at 13.47.12.mp4" type="video/mp4" />
                </video>
            </div>
            <div class="carousel-item">
                <video class="img-fluid" autoplay loop muted>
                    <source src="assets/videos/WhatsApp Video 2024-10-11 at 13.47.01 (1).mp4" type="video/mp4" />
                </video>
            </div>
        </div>
    </div> -->



    <details id="Öffnungszeiten-Section">
        <h2>Normale Öffnungszeiten</h2>
        <br>
        <summary><strong>Öffnungszeiten</strong></summary>
        <table class="table table-light table-striped">

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
    </details>
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