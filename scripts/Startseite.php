<?php
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

<body>
    <?php include 'scripts/header.php'; ?>

    <button onclick="location.href='scripts/Termin.php'" id="termin-buchen-button">Termin Buchen</button> <!-- Fügen Sie hier den Link zu Ihrer Terminbuchungsseite ein -->

    <h2>Öffnungszeiten</h2>
    <table class="table">

        <tr>
            <th>Tag</th>
            <th>Zeit</th>
        </tr>
        <!-- Fügen Sie hier Ihre Öffnungszeiten ein -->
        <tr>
            <td>Montag</td>
            <td>9:00 - 20:00</td>
        </tr>
        <tr>
            <td>Dienstag</td>
            <td>9:00 - 20:00</td>
        </tr>
        <tr>
            <td>Mittwoch</td>
            <td>9:00 - 20:00</td>
        </tr>
        <tr>
            <td>Donnerstag</td>
            <td>9:00 - 20:00</td>
        </tr>
        <tr>
            <td>Freitag</td>
            <td>9:00 - 20:00</td>
        </tr>
        <tr>
            <td>Samstag</td>
            <td>9:00 - 20:00</td>
        </tr>
        <tr>
            <td>Sonntag</td>
            <td>geschlossen</td>
        </tr>
    </table>

    <h3>Kontakt</h3>
    <p>Name: Ihr Name</p> <!-- Fügen Sie hier Ihren Namen ein -->
    <p>Adresse: Ihre Adresse</p> <!-- Fügen Sie hier Ihre Adresse ein -->
    <p>Telefonnummer: Ihre Telefonnummer</p> <!-- Fügen Sie hier Ihre Telefonnummer ein -->
    <p>Email: Ihre Email</p> <!-- Fügen Sie hier Ihre Email ein -->
</body>

</html>