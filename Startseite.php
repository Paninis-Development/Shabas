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
    <?php include'./Header.php'; ?>
</header>

<body>

<div id="coverFilter">
    <section class="cover">
    <div class="cover-content">
      <h2>Freshes Schnitt schneidet freshe Schnitte. Freshe Schnitte schneidet Freshes Schnitt</h2>
      <a href="#pricing" class="cta-btn">Jetzt Termin Buchen</a>
      <!-- <button href="#pricing" class="cta-btn">Jetzt Termin Buchen</button> -->
    </div>
  </section>
  </div>
  <!-- Pricing Plans -->
  <section id="pricing" class="pricing">
    <h2>Unsere Preise</h2>
    <div class="pricing-plans">
      <div class="plan">
        <h3>Basic</h3>
        <p>Haarschnitt: 20€</p>
        <p>Bartpflege: 10€</p>
        <button onclick="location.href='./Termin.php'"  data-mdb-button-init data-mdb-ripple-init class="btn btn-outline-light btn-lg px-5">Termin buchen</button>
      </div>
      <div class="plan">
        <h3>Premium</h3>
        <p>Haarschnitt: 30€</p>
        <p>Bartpflege: 15€</p>
        <button onclick="location.href='./Termin.php'"  data-mdb-button-init data-mdb-ripple-init class="btn btn-outline-light btn-lg px-5" >Termin buchen</button>
      </div>
      <!-- <div class="plan">
        <h3>Luxus</h3>
        <p>Haarschnitt: 40€</p>
        <p>Bartpflege: 20€</p>
        <button onclick="location.href='./Termin.php'" id="termin-buchen-button" , class="pulse">Termin buchen</button>
      </div> -->
    </div>
  </section>

  <!-- Gallery -->
  <section class="gallery">
    <h2>Unsere Arbeiten</h2>
    <div class="gallery-grid">
      <img src="assets/images/exampleFoto1.jpg" alt="Schnitt 1">
      <img src="assets/images/exampleFoto2.jpg" alt="Schnitt 2">
      <img src="assets/images/exampleFoto3.jpg" alt="Schnitt 3">
      
    </div>
  </section>    
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

    <details id="Impressum-Section">
        <br>
        <summary><strong>Impressum</strong></summary>
        <p>Name: Shababs Barbershop</p>
        <p>Adresse: </p>
        <p>Telefonnummer: </p>
        <p>Email: shababs.barbershop@gmail.com</p>
    </details>

</body>
<footer>
    <?php include'./footer.php'; ?>
</footer>
</html>