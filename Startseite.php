<?php
// include_once __DIR__ . '/../config/config.php';
// include('./config/config.php');
?>
<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Startseite</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link href="assets/css/Startseite.css" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="assets/images/shababs-logo.ico">
    <link href="https://fonts.googleapis.com/css2?family=Permanent+Marker&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Rock+Salt&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Henny+Penny&display=swap" rel="stylesheet">


</head>
<header>
    <?php include'./Header.php'; ?>
</header>

<body>

<div id="coverFilter">
    <section class="cover">
    <div class="cover-content">
        <h2 id="graffiti-text">Shababs Barbershop</h2>
      <a href="#pricing" class="cta-btn">Jetzt Termin Buchen</a>
    </div>
  </section>
</div>

<!-- Pricing Plans -->
<section id="pricing" class="pricing">
    <h2>Unsere Preise</h2>
    <div class="pricing-plans">
      <div class="plan">
        <h3>Basic</h3>
        <p>Haarschnitt: 35€</p>
        <p>Bartpflege: 15€</p>
        <button onclick="location.href='./Termin.php'" data-mdb-button-init data-mdb-ripple-init class="btn btn-outline-light btn-lg px-5">Termin buchen</button>
      </div>
      <!-- <div class="plan">
        <h3>Premium</h3>
        <p>Haarschnitt: 30€</p>
        <p>Bartpflege: 15€</p>
        <button onclick="location.href='./Termin.php'" data-mdb-button-init data-mdb-ripple-init class="btn btn-outline-light btn-lg px-5">Termin buchen</button>
      </div> -->
    </div>
</section>

<!-- Gallery -->
<section class="gallery" id="gallery">
    <h2 id="unsere_arbeiten">Unsere Arbeiten</h2>
    <div class="gallery-grid">
      <img src="assets/images/exampleFoto1.jpg" alt="Schnitt 1">
      <img src="assets/images/exampleFoto2.jpg" alt="Schnitt 2">
      <img src="assets/images/exampleFoto3.jpg" alt="Schnitt 3">
    </div>
</section>

<!-- Opening Hours -->
<h2>Öffnungszeiten</h2>
<br>
<table class="table table-light table-striped" id="Öffnungszeiten">
    <tr>
        <th>Tag</th>
        <th>Zeit</th>
    </tr>
    <tr>
        <td>Montag</td>
        <td>9:00 - 19:00</td>
    </tr>
    <tr>
        <td>Dienstag</td>
        <td>9:00 - 19:00</td>
    </tr>
    <tr>
        <td>Mittwoch</td>
        <td>9:00 - 19:00</td>
    </tr>
    <tr>
        <td>Donnerstag</td>
        <td>9:00 - 19:00</td>
    </tr>
    <tr>
        <td>Freitag</td>
        <td>9:00 - 19:00</td>
    </tr>
    <tr>
        <td>Samstag</td>
        <td>9:00 - 19:00</td>
    </tr>
    <tr>
        <td>Sonntag</td>
        <td>geschlossen</td>
    </tr>
</table>

<!-- Imprint -->
<!-- <details id="Impressum-Section">
    <br>
    <summary><strong>Impressum</strong></summary>
    <p>Name: Shababs Barbershop</p>
    <p>Adresse: </p>
    <p>Telefonnummer: </p>
    <p>Email: shababs.barbershop@gmail.com</p>
</details> -->

</body>
<footer id="footer">
    <?php include'./footer.php'; ?>
</footer>
</html>
<script>
  document.addEventListener("DOMContentLoaded", () => {
    const images = document.querySelectorAll(".gallery-grid img");

    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          const imagesInView = Array.from(document.querySelectorAll(".gallery-grid img"));
          imagesInView.forEach((img, index) => {
            setTimeout(() => {
              img.classList.add("show");
            }, index * 400); // 200ms delay between pictures
          });
          observer.disconnect(); // only run once
        }
      });
    }, {
      threshold: 0.1
    });

    images.forEach(img => observer.observe(img));
  });
</script>

