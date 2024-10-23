<?php
include_once('./connection.php');
include_once('./function.php');

// Get the available opening days
$allowedDates = getOpeningDays();

// Check if a date was submitted and sanitize the input
$selectedDate = isset($_GET['date']) ? htmlspecialchars($_GET['date']) : '';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="icon" type="image/x-icon" href="assets/images/shababs-logo.ico">
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
        }

        #closing_time {
            background-color: grey;
        }
    </style>
</head>

<header>
    <?php include('./Header.php'); ?>
</header>

<body>
    <div id="admin-div">
        <!-- Form 1: GET Form for selecting date -->
        <form id="dateForm" method="GET">
            <!-- The datepicker input field -->
            <input type="text" id="adminDatepicker" name="date" placeholder="Select a date" class="placeholder" value="<?php echo $selectedDate; ?>" required>
        </form>

        <!-- Appointment Details Table -->
        <table class="table table-dark table-striped" id="appointmentTable">
            <thead>
                <tr>
                    <th>Customer Name</th>
                    <th>Start Time</th>
                    <th>End Time</th>
                </tr>
            </thead>
            <tbody>
                <?php
                try {
                    if ($selectedDate) {
                        // Fetch appointment details for the selected date
                        $appointmentDetails = getAppointmentDetails($selectedDate);

                        if ($appointmentDetails) {
                            $appointments = $appointmentDetails->fetchAll(PDO::FETCH_ASSOC);

                            if (!empty($appointments)) {
                                foreach ($appointments as $appointment) {
                                    // Check if 'appointment_id' exists, and use it for unique row IDs
                                    $appointmentId = isset($appointment['appointment_id']) ? $appointment['appointment_id'] : uniqid();

                                    echo "<tr class='main-row' data-toggle='collapse' data-target='#row{$appointmentId}' aria-expanded='false' aria-controls='row{$appointmentId}'>
                                    <td>{$appointment['customer_name']}</td>
                                    <td>{$appointment['start_time']}</td>
                                    <td>{$appointment['end_time']}</td>
                                </tr>
                                <tr id='row{$appointmentId}' class='collapse'>
                                    <td colspan='3'>
                                        <strong>Email:</strong> {$appointment['customer_email']}<br>
                                        <strong>Phone:</strong> {$appointment['customer_phone']}<br>
                                        <strong>Appointment Date:</strong> {$appointment['appointment_date']}<br><br>
                                        <button class='btn btn-danger' onclick='deleteAppointment({$appointmentId})'>Delete</button>
                                    </td>
                                </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='3'>No appointments found for the selected date.</td></tr>";
                            }
                        } else {
                            echo "<tr><td colspan='3'>No appointments found for the selected date.</td></tr>";
                        }
                    }
                } catch (PDOException $e) {
                    echo "<tr><td colspan='3'>Error fetching appointments: " . $e->getMessage() . "</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <!-- Form 2: POST Form for saving opening hours -->
        <div id="unavailableDates">
            <form id="hoursForm" action="" method="POST">
                <input type="hidden" name="action" value="save_hours">

                <div id="timepickers">
                <input type="time" id="opening_time" name="opening_time" required>
                <label for="opening_time">Opening Time:</label>
                <br><br>

                <input type="time" id="closing_time" name="closing_time" required>
                <label for="closing_time">Closing Time:</label>
                <br><br>
                </div>

                <input id="openUpButton" type="submit" value="Am <?php echo $selectedDate ?> öffnen?">
                <input id="closeButton" type="submit" value="Am <?php echo $selectedDate ?> schließen?">
            </form>
        </div>
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
                minDate: 0,
                onSelect: function(dateText, inst) {
                    // Submit the GET form with selected date via JavaScript
                    $('#dateForm').submit();
                }
            });

            // If a date is already selected, show the appropriate form
            if (selectedDate) {
                if (allowedDates.includes(selectedDate)) {
                    $('#appointmentTable').show(); // Show appointment table
                } else {
                    $('#unavailableDates').show(); // Show POST form to set hours
                }
            }

            // Function to handle row expansion/collapse
            function initializeRowToggle() {
                // Remove existing event handlers to avoid duplicates
                $('.main-row').off('click');

                // Attach click event for each row to toggle the collapse
                $('.main-row').on('click', function() {
                    const target = $(this).data('target');
                    $(target).toggleClass('collapse');
                });
            }

            // Call the function to ensure row toggle works after page load
            initializeRowToggle();
        });



        function deleteAppointment(appointmentId) {
            if (confirm("Are you sure you want to delete this appointment?")) {
                // AJAX call to trigger the delete function
                var xhr = new XMLHttpRequest();
                xhr.open("GET", "deleteAppointment.php?id=" + appointmentId, true); // Adjust the URL and parameters as needed
                xhr.onreadystatechange = function() {
                    if (xhr.readyState == 4 && xhr.status == 200) {
                        alert("Appointment deleted successfully!");
                        // Optionally, you can reload the page or remove the row from the table
                        location.reload(); // Refresh the page to reflect changes
                    }
                };
                xhr.send();
            }
        }
    </script>

</body>

</html>