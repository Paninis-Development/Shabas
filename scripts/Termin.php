<?php
include_once('./function/connection.php');
include_once('./function/function.php');

include_once __DIR__ . '/../config/config.php';
date_default_timezone_set('UTC');
// $date = date('Y/m/d');
//datepicker
// $ben_id = $_SESSION['ben_id'];
if (isset($_POST['date'])) {
    $date = $_POST['date'];
    setcookie("DateCookie", $date, time() + 1000, "/");
} else if (isset($_COOKIE['DateCookie'])) {
    // Retrieve the value of the cookie
    $cookieValue = $_COOKIE['DateCookie'];
    $date = $cookieValue; // Output the value of the cookie
} else {
    $date = date('Y-m-d', strtotime('+1 days'));
}

// $db = new DatabaseConnection();
// $appointment = $db->getAppointment();
// echo $appointment;


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Termin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link href="./assets/css/Termin.css" rel="stylesheet">
</head>
<header>
    <?php include(BASE_DIR . '/scripts/Header.php'); ?>
</header>

<body>
    <form id="dateForm" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <?php
        // Fetch dates from your database
        $dates = getOpeningDays(); // replace this with your function to fetch dates
        ?>

        <script>
            // Pass PHP dates array to JavaScript array
            var enableDays = <?php echo json_encode($dates); ?>;

            function disableDates(date) {
                var formattedDate = jQuery.datepicker.formatDate('yy-mm-dd', date);
                return [enableDays.indexOf(formattedDate) != -1];
            }

            jQuery(function() {
                jQuery("#datePicker").datepicker({
                    beforeShowDay: disableDates,
                    minDate: new Date('<?php echo date('Y-m-d'); ?>'),
                    dateFormat: 'yy-mm-dd'
                });
            });
        </script>
        <input type="date" name="date" id="datePicker" min="<?php echo date('Y-m-d'); ?>" style="border: 1px solid black; padding: 10px; margin: 10px;">

        <!-- <input type="date" name="date" id="datePicker" value="<?php echo $date; ?>" min="<?php echo date('Y-m-d', strtotime('+7 days')); ?>" max="<?php echo date('Y-m-d', strtotime('+3 weeks')); ?>" style=" border: 1px solid black; padding: 10px; margin: 10px;"> -->

        <select name="myComboBox">
            <?php
            $openingTime = getOpeningTime($date);
            $closingTime = getClosingTime($date);

            $openingTime = new DateTime($openingTime);
            $closingTime = new DateTime($closingTime);

            $intervalToSubtract = new DateInterval('PT1H30M');

            $closingTime->sub($intervalToSubtract);

            $interval = new DateInterval('PT1H30M');

            while ($openingTime <= $closingTime) {
                echo '<option value="' . $openingTime->format('H:i') . '">' . $openingTime->format('H:i') . '</option>';
                $openingTime->add($interval);
            }
            ?>


        </select>
        <input type="submit" value="Submit">
    </form>

</body>

</html>