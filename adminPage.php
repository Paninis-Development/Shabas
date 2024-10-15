<?php
include_once('./connection.php');
include_once('./function.php');

// Get the available opening days
$allowedDates = getOpeningDays();

// Check if a date was submitted and sanitize the input
$selectedDate = isset($_GET['date']) ? htmlspecialchars($_GET['date']) : '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if this form submission is for saving opening hours
    if (isset($_POST['opening_time']) && isset($_POST['closing_time']) && $selectedDate) {
        // Sanitize form inputs
        $openingTime = htmlspecialchars($_POST['opening_time']);
        $closingTime = htmlspecialchars($_POST['closing_time']);

        // Save the opening hours to the database
        if (saveOpeningHours($selectedDate, $openingTime, $closingTime)) {
            echo "Opening hours saved successfully!";
        } else {
            echo "Error saving opening hours!";
        }
    } else {
        // Sanitize form inputs for the appointment form
        $date = htmlspecialchars($_POST['date']);
        $slot = htmlspecialchars($_POST['timeSlot']);
        $name = htmlspecialchars($_POST['customer_name']);
        $email = htmlspecialchars($_POST['customer_email']);
        $phone = htmlspecialchars($_POST['customer_phone']);

        // Extract start and end times from the selected slot
        list($startTime, $endTime) = explode('-', $slot);

        // Save the appointment
        if (saveAppointment($date, $startTime, $endTime, $name, $email, $phone)) {
            echo "Appointment saved successfully!";
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
    <title>Admin Page</title>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="assets/css/Admin.css" rel="stylesheet">
    <style>
        /* Styling for unavailable dates with red text */
        .unavailable-date a {
            background-color: #f8d7da !important;
            /* Light red background */
            color: #dc3545 !important;
            /* Red text */
        }

        #appointmentTable {
            display: none;
        }

        #unavailableDates {
            display: none;
        }
        #opening_time {
            
            background-color: grey;
        }´
        #closing_time {
            
            background-color: grey;
        }
    </style>
</head>

<header>
    <?php include('./Header.php'); ?>
</header>

<body>

    <form action="" method="GET">
        <!-- The datepicker input field -->
        <input type="text" id="adminDatepicker" name="date" placeholder="Select a date" class="placeholder" value="<?php echo $selectedDate; ?>" required>


        <!-- Appointment Details Table -->
        <table class="table table-dark table-striped" id="appointmentTable">
            <thead>
                <tr>
                    <th>Appointment Date</th>
                    <th>Start Time</th>
                    <th>End Time</th>
                    <th>Customer Email</th>
                    <th>Customer Phone</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($selectedDate) {
                    // Fetch appointment details for the selected date
                    $appointmentDetails = getAppointmentDetails($selectedDate);
                    if ($appointmentDetails) {
                        // Loop through each row of the results and print them in the table
                        foreach ($appointmentDetails->fetchAll(PDO::FETCH_ASSOC) as $appointment) {
                            echo "<tr>
                        <td>{$appointment['appointment_date']}</td>
                        <td>{$appointment['start_time']}</td>
                        <td>{$appointment['end_time']}</td>
                        <td>{$appointment['customer_email']}</td>
                        <td>{$appointment['customer_phone']}</td>

                    </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6'>No appointments found for the selected date.</td></tr>";
                    }
                }
                ?>
            </tbody>

            <div id="unavailableDates">
                <form action="" method="POST">
                    <input type="time" id="opening_time" name="opening_time" required>
                    <label for="opening_time">Opening Time:</label>
                    <br><br>

                    <input type="time" id="closing_time" name="closing_time" required>
                    <label for="closing_time">Closing Time:</label>
                    <br><br>

                    <input id="openUpButton" type="submit" value="Am <?php echo $selectedDate ?> öffnen?">

                </form>
            </div>
    </form>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <script>
        $(document).ready(function() {
            const allowedDates = <?php echo json_encode($allowedDates); ?>;
            const selectedDate = "<?php echo $selectedDate; ?>"; // Get the selected date from PHP

            // Function to enable only specific dates
            function enableSpecificDates(date) {
                const formattedDate = $.datepicker.formatDate('yy-mm-dd', date);

                // If the date is allowed, return normal styling
                if (allowedDates.includes(formattedDate)) {
                    return [true, "available-date", "Available"];
                }
                // If the date is not allowed, still enable it but give it a special class
                return [true, "unavailable-date", "Unavailable"];
            }

            // Initialize the datepicker
            $("#adminDatepicker").datepicker({
                beforeShowDay: enableSpecificDates,
                dateFormat: 'yy-mm-dd',
                onSelect: function(dateText, inst) {
                    // Auto-submit the form when a date is selected
                    $('form').submit();
                }
            });

            // If a date is already selected, show the table
            if (selectedDate) {
                if (allowedDates.includes(selectedDate)) {

                    $('#appointmentTable').show();
                } else {
                    $('#unavailableDates').show();
                }


            }
        });
    </script>

</body>

</html>