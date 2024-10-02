<?php
include_once('./connection.php');
include_once('./function.php');

// Get the available opening days
$allowedDates = getOpeningDays();

// Check if a date was submitted and sanitize the input
$selectedDate = isset($_GET['date']) ? htmlspecialchars($_GET['date']) : '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize form inputs
    $date = htmlspecialchars($_POST['date']);
    $slot = htmlspecialchars($_POST['timeSlot']); // Assuming timeSlot is the name of the select field
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

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Date Picker</title>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <style>
        #combobox {
            display: none;
        }

        #nameAndEmail {
            display: none;
        }
    </style>
</head>

<header>
    <?php include('./Header.php'); ?>
</header>

<body>

    <!-- Debugging: Output the value of the selected date -->
    <?php
    if ($selectedDate) {
        echo "<p>Debug: Selected Date = " . $selectedDate . "</p>"; // Debugging output
    }
    ?>

    <form action="" method="GET">
        <!-- The datepicker input field -->
        <input type="text" id="datepicker" name="date" placeholder="Select a date" class="placeholder" value="<?php echo $selectedDate; ?>" required>

        <!-- Time slot combobox -->
        <div id="combobox">
            <select id="options">
                <?php
                if ($selectedDate) {
                    if (strtotime($selectedDate)) {
                        $times = getOpeningClosingTime($selectedDate);
                       
                        if (!empty($times)) {
                            // Generate time slots with availability check
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
                <label for="customerNameForm">Customer Name</label>
                <input type="text" class="form-control" id="customerNameForm" placeholder="Name *" required>
            </div>
            <div class="form-group">
                <label for="customerMailForm">Customer Mail</label>
                <input type="email" class="form-control" id="customerMailForm" placeholder="Email *" required>
            </div>
            <div class="form-group">
                <label for="customerPhoneNrForm">Customer phone number</label>
                <input type="text" class="form-control" id="customerPhoneNrForm" placeholder="Phone Number">
            </div>
        </div>

        <input type="submit" value="Submit Form">
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
                if (allowedDates.includes(formattedDate)) {
                    return [true, "", "Available"];
                }
                return [false, "", "Unavailable"];
            }

            // Initialize the datepicker
            $("#datepicker").datepicker({
                beforeShowDay: enableSpecificDates,
                dateFormat: 'yy-mm-dd',
                onSelect: function(dateText, inst) {
                    // Auto-submit the form when a date is selected
                    $('form').submit();
                }
            });

            // If a date is already selected, show the combobox and name/email fields
            if (selectedDate) {
                $('#combobox').show();
                $('#nameAndEmail').show();
            }
        });
    </script>
</body>

</html>
