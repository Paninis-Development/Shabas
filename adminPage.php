<?php
include_once('./connection.php');
include_once('./function.php');


// Get the available opening days
$allowedDates = getOpeningDays();
$barberDetails = getBarberDetails();
$barbers = getBarber();

// Check if a date was submitted and sanitize the input
$selectedDate = isset($_GET['date']) ? htmlspecialchars($_GET['date']) : '';
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['opening_time']) && isset($_POST['closing_time'])) {
    $opening_time = htmlspecialchars($_POST['opening_time']);
    $closing_time = htmlspecialchars($_POST['closing_time']);

    saveOpeningHours($selectedDate, $opening_time, $closing_time);
}
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['closeButton'])) {
    $appointmentDetails = getAppointmentDetails($selectedDate);

    foreach ($appointmentDetails as $appointment) {
        $customerEmail = $appointment['customer_email'];
        $customerName = $appointment['customer_name'];
        $startTime = $appointment['start_time'];
        sendDeleteEmail($customerEmail, $customerName, $selectedDate, $startTime);
    }

    closeDay($selectedDate);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_barber'])) {
    $barberId = $_POST['BarberID'];
    deleteBarber($barberId);
    header("Location: " . $_SERVER['PHP_SELF']); // Seite neu laden
    exit();
}

// Neuen Barber hinzufügen
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_barber'])) {
    $name = htmlspecialchars($_POST['barber_name']);
    $email = htmlspecialchars($_POST['barber_email']);
    addBarber($name, $email); // Barber mit Name & Email hinzufügen
    header("Location: " . $_SERVER['PHP_SELF']); // Seite neu laden
    exit();
}

if (isset($_POST['delete'])) {
    $appointmentId = $_POST['delete_id'];
    $delete_email = $_POST['delete_email'];
    $customerName = $_POST['customer_name'];
    $startTime = $_POST['start_time'];

    if (deleteAppointment($appointmentId)) {
        logMessage("Termin erfolgreich gelöscht", "INFO");
        sendDeleteEmail($delete_email, $customerName, $selectedDate, $startTime);
    } else {
        logMessage("Fehler beim Löschen des Termins!", "ERROR");
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST["barber_select"]) && !empty($_POST["date_range"])) {
        $barberName = htmlspecialchars($_POST["barber_select"]);
        $barberID = getBarberIdByBarberName($barberName);
        $reason = htmlspecialchars($_POST["reason_select"]);
        // Datum in Start- und Enddatum aufteilen
        $dates = explode(" - ", $_POST["date_range"]);
        if (count($dates) == 2) {
            $startDate = DateTime::createFromFormat("d.m.Y", trim($dates[0]))->format("Y-m-d");
            $endDate = DateTime::createFromFormat("d.m.Y", trim($dates[1]))->format("Y-m-d");

            addHoliday($barberID, $startDate, $endDate, 'vacation');
            logMessage("'$barberName' von '$startDate' bis '$endDate' '$reason' eingetragen");
        } else {
            echo "Ungültiges Datumsformat!";
        }
    }
}



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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-ui-timepicker-addon/1.6.3/jquery-ui-timepicker-addon.min.js"></script> -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-ui-timepicker-addon/1.6.3/jquery-ui-timepicker-addon.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

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
    <div>
        <div id="admin-div">
            <h2>Termine Verwaltung</h2>
            <!-- Form 1: GET Form for selecting date -->
            <form id="dateForm" method="GET">
                <!-- The datepicker input field -->
                <input type="text" id="adminDatepicker" name="date" placeholder="Select a date" class="placeholder" value="<?php echo $selectedDate; ?>" required>
            </form>

            <!-- Appointment Details Table -->
            <table class="table table-dark table-striped" id="appointmentTable">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Start</th>
                        <th>End</th>
                        <th>Barber</th>
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
                                        $appointmentId = isset($appointment['AppointmentID']) ? $appointment['AppointmentID'] : uniqid();

                                        echo "<tr class='main-row' data-toggle='collapse' data-target='#row{$appointmentId}' aria-expanded='false' aria-controls='row{$appointmentId}'>
                                        <td>{$appointment['customer_name']}</td>
                                        <td>{$appointment['start_time']}</td>
                                        <td>{$appointment['end_time']}</td>
                                        <td>{$appointment['barber_name']}</td>
                                        </tr>
                                        <tr id='row{$appointmentId}' class='collapse'>
                                        <td colspan='4'>
                                        <strong>Email:</strong> {$appointment['customer_email']}<br>
                                        <strong>Phone:</strong> {$appointment['customer_phone']}<br>
                                        <strong>Appointment Date:</strong> {$appointment['appointment_date']}<br><br>
            
                                        <form method='post'>
                                        <input type='hidden' name='delete_id' value='{$appointmentId}'>
                                        <input type='hidden' name='delete_email' value='{$appointment['customer_email']}'>
                                        <input type='hidden' name='start_time' value='{$appointment['start_time']}'>
                                        <input type='hidden' name='customer_name' value='{$appointment['customer_name']}'>
                                        <button type='submit' name='delete' class='btn btn-danger'>Delete</button>
                                    </form>
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
                        // echo "<tr><td colspan='3'>Error fetching appointments: " . $e->getMessage() . "</td></tr>";
                        logMessage("Error fetching appointments: " . $e->getMessage() . "", "ERROR");
                    }
                    ?>
                </tbody>
            </table>
            <!-- Im HTML-Formular -->
            <form id="deleteDayForm" action="" method="POST">
                <input id="closeButton" type="submit" name="closeButton" value="Am <?php echo $selectedDate ?> schließen?">
            </form>


            <!-- Form 2: POST Form for saving opening hours -->
            <div id="unavailableDates">
                <form id="hoursForm" action="" method="POST">
                    <input type="hidden" name="action" value="save_hours">

                    <div id="timepickers">
                        <label for="opening_time">Opening Time:</label>
                        <br><br>
                        <input type="time" id="opening_time" placeholder="Bitte Zeit auswählen:" name="opening_time" required>
                        <br><br>
                        <label for="closing_time">Closing Time:</label>
                        <br><br>
                        <input type="time" id="closing_time" placeholder="Bitte Zeit auswählen:" name="closing_time" required>
                        <br><br>
                    </div>


                    <input id="openUpButton" type="submit" value="Am <?php echo $selectedDate ?> öffnen?">

                </form>
            </div>
        </div>
    </div>

    <div class="container" id="barberVerwaltung">
        <!-- Barber hinzufügen -->
        <h2>Barber Verwaltung</h2>
        <button type="button" class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#addBarberModal">
            Barber hinzufügen
        </button>

        <table class="table table-dark table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Email</th>
                    <th>Name</th>
                    <th>Aktionen</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($barberDetails as $barber) : ?>
                    <tr>
                        <td><?php echo htmlspecialchars($barber['BarberID']); ?></td>
                        <td><?php echo htmlspecialchars($barber['barber_mail']); ?></td>
                        <td><?php echo htmlspecialchars($barber['barber_name']); ?></td>
                        <td>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="BarberID" value="<?php echo $barber['BarberID']; ?>">
                                <button type="submit" name="delete_barber" class="btn btn-danger">Löschen</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Modal für das Hinzufügen eines Barbers -->
    <div class="modal fade" id="addBarberModal" tabindex="-1" aria-labelledby="addBarberLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addBarberLabel">Neuen Barber hinzufügen</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST">
                        <div class="mb-3">
                            <label for="barberName" class="form-label">Name</label>
                            <input type="text" class="form-control" id="barberName" name="barber_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="barberEmail" class="form-label">E-Mail</label>
                            <input type="email" class="form-control" id="barberEmail" name="barber_email" required>
                        </div>
                        <button type="submit" name="add_barber" class="btn btn-primary">Speichern</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="container" id="urlaubVerwaltung">
        <h2>Urlaub/Krankenstand Verwaltung</h2>
        <form id="holidayForm" method="POST">
            <label for="barberSelect">Barber auswählen: *</label>
            <select id="barberSelect" name="barber_select" required>
            <option value="" disabled selected>Bitte einen Barber auswählen</option>
                <?php foreach ($barbers as $barber) : ?>
                    <option value="<?php echo htmlspecialchars($barber['barber_name']); ?>">
                        <?php echo htmlspecialchars($barber['barber_name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <label for="reasonSelect">Grund auswählen: *</label>
            <select id="reasonSelect" name="reason_select" required>
                <!-- <option value="">Select a Barber</option> -->
                <option value="sick">Krankenstand</option>
                <option value="vacation">Urlaub</option>
            </select>

            <label>Wähle eine Zeitspanne:</label>
            <div id="datepicker-container">
                <input type="text" id="datepicker" name="date_range" placeholder="Datum wählen..." readonly required>
                <div id="datepicker-popup"></div> <!-- Hier wird der Datepicker eingefügt -->
            </div>
            <button type="button" id="resetDate">Datum zurücksetzen</button>

            <button type="submit">Senden</button>
        </form>
    </div>


   

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>


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

            $(function () {
    let startDate = null;
    let endDate = null;

    $("#datepicker-popup").datepicker({
        dateFormat: "dd.mm.yy",
        numberOfMonths: 1,
        beforeShowDay: function (date) {
            let dateString = $.datepicker.formatDate("dd.mm.yy", date);

            if (startDate && dateString === startDate) {
                return [true, "start", "Startdatum"];
            }
            if (endDate && dateString === endDate) {
                return [true, "end", "Enddatum"];
            }
            if (
                startDate &&
                endDate &&
                date >= $.datepicker.parseDate("dd.mm.yy", startDate) &&
                date <= $.datepicker.parseDate("dd.mm.yy", endDate)
            ) {
                return [true, "range", "Ausgewählter Bereich"];
            }
            return [true, ""];
        },
        onSelect: function (dateText) {
            // Wenn beide gesetzt sind, neu starten
            if (startDate && endDate) {
                startDate = dateText;
                endDate = null;
            } else if (!startDate) {
                startDate = dateText;
            } else {
                let startDateObj = $.datepicker.parseDate("dd.mm.yy", startDate);
                let endDateObj = $.datepicker.parseDate("dd.mm.yy", dateText);

                if (endDateObj < startDateObj) {
                    startDate = dateText;
                    endDate = null;
                } else {
                    endDate = dateText;
                }
            }

            $("#datepicker").val(startDate + (endDate ? " - " + endDate : ""));
            $("#datepicker-popup").datepicker("refresh");
        }
    }).hide();

    $("#datepicker").click(function (event) {
        event.stopPropagation();
        $("#datepicker-popup").toggle();
    });

    $(document).on("mousedown", function (event) {
        if (!$(event.target).closest("#datepicker-container, .ui-datepicker-header").length) {
            $("#datepicker-popup").hide();
        }
    });

    $("#resetDate").click(function () {
        startDate = null;
        endDate = null;
        $("#datepicker").val("");
        $("#datepicker-popup").datepicker("refresh");
    });
});

            // If a date is already selected, show the appropriate form
            if (selectedDate) {
                if (allowedDates.includes(selectedDate)) {
                    $('#appointmentTable').show(); // Show appointment table
                    $('#closeButton').show(); // Show close button

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

        function resetForm() {
            document.getElementById("dateForm").reset();
        }


        // document.getElementById("addRowBtn").addEventListener("click", function() {
        //     let tableBody = document.getElementById("tableBody");

        //     // Neue Zeile erstellen
        //     let newRow = document.createElement("tr");
        //     newRow.innerHTML = `
        //         <td><input type="text" class="form-control" placeholder="Name"></td>
        //         <td><input type="email" class="form-control" placeholder="Email"></td>
        //         <td><button class="btn btn-danger deleteRowBtn">Löschen</button></td>
        //     `;

        //     // Zeile zur Tabelle hinzufügen
        //     tableBody.appendChild(newRow);

        //     // Event Listener für Löschen-Button hinzufügen
        //     newRow.querySelector(".deleteRowBtn").addEventListener("click", function() {
        //         newRow.remove();
        //     });
        // });

        // $(document).ready(function() {
        //     $("#opening_time, #closing_time").timepicker({
        //         timeFormat: 'HH:mm',
        //         interval: 30,
        //         minTime: '06:00',
        //         maxTime: '23:30',
        //         dynamic: false,
        //         dropdown: true,
        //         scrollbar: true
        //     });
        // });

        document.addEventListener("DOMContentLoaded", function() {
            let script = document.createElement("script");
            script.src = "https://cdn.jsdelivr.net/npm/flatpickr";
            script.onload = function() {
                console.log("Flatpickr geladen!");
                flatpickr("#opening_time", {
                    enableTime: true,
                    noCalendar: true,
                    dateFormat: "H:i",
                    time_24hr: true
                });

                flatpickr("#closing_time", {
                    enableTime: true,
                    noCalendar: true,
                    dateFormat: "H:i",
                    time_24hr: true
                });
            };
            document.head.appendChild(script);
            document.getElementById("dateForm").reset();

        });
    </script>

</body>

</html>