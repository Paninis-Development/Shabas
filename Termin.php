<?php
include_once('./connection.php');
include_once('./function.php');

// Get the available opening days
$allowedDates = getOpeningDays();

// Check if a date was submitted and sanitize the input
$selectedDate = isset($_GET['date']) ? htmlspecialchars($_GET['date']) : '';

// Handle the POST request to save the appointment
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize form inputs
    $date = htmlspecialchars($_POST['date']);
    $slot = htmlspecialchars($_POST['timeSlot']);
    $name = htmlspecialchars($_POST['customer_name']);
    $email = htmlspecialchars($_POST['customer_email']);
    $phone = htmlspecialchars($_POST['customer_phone']);

    // Extract start and end times from the selected slot
    list($startTime, $endTime) = explode('-', $slot);

    if (!empty($date) && !empty($name) && !empty($startTime) && !empty($endTime)) {


        // Save the appointment
        if (saveAppointment($date, $startTime, $endTime, $name, $email, $phone)) {

            if (!empty($email)) {
                // The message
                $message = "Liebe/r $name,\r\n\r\nvielen Dank für Ihre Terminbuchung bei Shababs Barbershop.\r\n\r\nIhr Termin ist am $date um $startTime.\r\n\r\nBitte erscheinen Sie 5-10 Minuten früher, um den Ablauf reibungslos zu gestalten.\r\n\r\nWir freuen uns auf Ihren Besuch!\r\n\r\nHerzliche Grüße,\r\nIhr Shababs-Team";

                // In case any of our lines are larger than 70 characters, we should use wordwrap()
                $message = wordwrap($message, 70, "\r\n");

                // Send
                mail($email, 'My Subject', $message);
            }
        } else {
            echo "Error saving appointment!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Termin</title>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="icon" type="image/x-icon" href="assets/images/shababs-logo.ico">
    <link href="assets/css/Termin.css" rel="stylesheet">
    <style>
        #combobox,
        #nameAndEmail {
            display: none;
        }
    </style>
</head>

<header>
    <?php include('./Header.php'); ?>
</header>

<body>
    <div id="termin-div">
        <!-- Date selection form (GET) -->
        <form action="" method="GET" id="dateForm">
            <!-- The datepicker input field -->
            <input type="text" id="datepicker" name="date" placeholder="Select a date" class="placeholder" value="<?php echo $selectedDate; ?>" required>
            <!-- <input type="submit" value="Check Availability"> -->
        </form>

        <!-- Appointment form (POST) - Only visible when date is selected -->
        <form action="" method="POST" id="appointmentForm">
            <input type="hidden" name="date" value="<?php echo $selectedDate; ?>">

            <!-- Time slot combobox -->
            <div id="combobox">
                <select id="options" name="timeSlot" required>
                    <?php
                    if ($selectedDate) {
                        if (strtotime($selectedDate)) {
                            $times = getOpeningClosingTime($selectedDate);

                            if (!empty($times)) {
                                $timeSlots = generateTimeSlotsWithAvailability($times['openTime'], $times['closeTime'], $selectedDate);
                                foreach ($timeSlots as $slot) {
                                    echo $slot;
                                }
                            } else {
                                echo "<option value='no-time'>No opening hours available</option>";
                            }
                        } else {
                            echo "<option value='invalid-date'>Invalid date format</option>";
                        }
                    } else {
                        echo "<option value=''>Please select a date</option>";
                    }
                    ?>
                </select>
            </div>

            <!-- Name and email fields -->
            <div id="nameAndEmail">
                <div class="form-group">
                    <label for="customerNameForm">Name *</label>
                    <input type="text" class="form-control" id="customerNameForm" name="customer_name" placeholder="Name *" required>
                </div>
                <div class="form-group">
                    <label for="customerMailForm">Email Address</label>
                    <input type="email" class="form-control" id="customerMailForm" name="customer_email" placeholder="Email *" required>
                </div>
                <div class="form-group">
                    <label for="customerPhoneNrForm">Phone Number</label>
                    <input type="text" class="form-control" id="customerPhoneNrForm" name="customer_phone" placeholder="Phone Number">
                </div>
            </div>

            <input type="submit" value="Submit Appointment">
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <script>
        $(document).ready(function() {
            const allowedDates = <?php echo json_encode($allowedDates); ?>;
            const selectedDate = "<?php echo $selectedDate; ?>"; // Get the selected date from PHP

            // Function to enable only specific dates
            function enableSpecificDates(date) {
                const formattedDate = $.datepicker.formatDate('yy-mm-dd', date);
                if (allowedDates.includes(formattedDate)) {
                    return [true, "", "Available"];
                }
                return [false, "", "Unavailable"];
            } 

            // Initialize the datepicker
            $("#datepicker").datepicker({
                beforeShowDay: enableSpecificDates,
                dateFormat: 'yy-mm-dd',
                minDate: 0,
                onSelect: function(dateText, inst) {
                    // Submit the GET form with selected date
                    $('#dateForm').submit();
                }
            });

            // If a date is already selected, show the appointment form
            if (selectedDate) {
                $('#combobox').show();
                $('#nameAndEmail').show();
            }
        });
    </script>
</body>
<footer>
<?php include'./footer.php'; ?>
</footer>

</html>