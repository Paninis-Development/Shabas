<?php
header('Content-Type: text/plain'); // Ensure plain text response for debugging
include_once('./connection.php');
include_once('./function.php');

if (isset($_GET['barber']) && isset($_GET['date'])) {
    $barber = htmlspecialchars($_GET['barber']);
    $date = htmlspecialchars($_GET['date']);

    echo "Barber: $barber, Date: $date\n"; // Debugging output

    $times = getOpeningClosingTime($date);

    if (!empty($times)) {
        $timeSlots = generateTimeSlotsWithAvailability($times['openTime'], $times['closeTime'], $date, $barber);

        foreach ($timeSlots as $slot) {
            echo $slot;
        }
    } else {
        echo "No opening hours available";
    }
} else {
    echo "Missing parameters!";
}
?>
