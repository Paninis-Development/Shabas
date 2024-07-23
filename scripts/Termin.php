<?php
include_once('./function/connection.php');
include_once('./function/function.php');

include_once __DIR__ . '/../config/config.php';
date_default_timezone_set('UTC');

if (isset($_POST['date'])) {
    $date = $_POST['date'];
    setcookie("DateCookie", $date, time() + 1000, "/");
} else if (isset($_COOKIE['DateCookie'])) {
    $cookieValue = $_COOKIE['DateCookie'];
    $date = $cookieValue;
} else {
    $date = date('Y-m-d', strtotime('+1 days'));
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Termin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link href="./assets/css/Termin.css" rel="stylesheet">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
</head>
<header>
    <?php include(BASE_DIR . '/scripts/Header.php'); ?>
</header>

<body>
    <form id="dateForm" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <?php
        // Fetch dates from your database
        $dates = getOpeningDays(); // replace this with your function to fetch dates

        // Convert PHP array to JavaScript array
        $datesArray = json_encode($dates);
        ?>

        <script>
            $(function() {
                var openingDates = <?php echo $datesArray; ?>;

                $("#datePicker").datepicker({
                    beforeShowDay: function(date) {
                        var formattedDate = $.datepicker.formatDate('yy-mm-dd', date);
                        return [openingDates.indexOf(formattedDate) != -1];
                    }
                });
            });
        </script>

        <input type="date" name="date" id="datePicker" value="<?php echo $date; ?>" min="<?php echo date('Y-m-d'); ?>" style="border: 1px solid black; padding: 10px; margin: 10px;">

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
    <script>
        document.getElementById('datePicker').addEventListener('change', function() {
            document.getElementById('dateForm').submit();
        });
    </script>
</body>

</htm
